<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\dip;
use Illuminate\Support\Facades\Session;
use App\products;
use App\sales;
use App\purchases;
use App\sales_items;
use App\purchaseItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\stock;
use App\Services\WeatherService;
use App\Services\AnomalyDetectionService;

class FuelDipController extends Controller
{

    public $module_code;
    public $ignored_permission_methods = [];
    public $permission_methods = [];
    public function __construct(){
        $this->inject_permissions();
    }

    public function inject_permissions(){
        $this->module_code = 'fuel-dips';
        $this->ignored_permission_methods = [
        ];
        $this->permission_methods = [
            'index' => [
                'module_permission_type_code' => 'read',
            ],
            'show' => [
                'module_permission_type_code' => 'read',
            ],
            'edit' => [
                'module_permission_type_code' => 'read',
            ],
            'update' => [
                'module_permission_type_code' => 'update',
            ],
            'create' => [
                'module_permission_type_code' => 'create',
            ],
            'store' => [
                'module_permission_type_code' => 'create',
            ],
            'destroy' => [
                'module_permission_type_code' => 'delete',
            ],
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $stock = DB::table('products')
        ->join('stocks','stocks.pro_id','products.id')
        ->select('products.name as name','stocks.*')
        ->where('products.isdeleted',0)
        ->get();

        $data = dip::join('products','products.id','pro_id')
        ->where('products.isdeleted',0)
        ->where('dips.isdeleted',0)
        // ->join('stocks','stocks.pro_id','products.id')
        ->select('products.*','dips.id as dip_id','dips.desc as ddesc','dips.*')
        ->get();
        
        // dd($data);
        return view('dip.index')->with('data',$data)->with('stock',$stock);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        // $response = WeatherService::getCurrentWeather(31.5204, 74.3587);
        // dd($response);
        $exp  = Products::where('isdeleted',0)->get();
        return view('dip.create')->with('exp',$exp);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $weather = [];
        try{
            $weather = WeatherService::getCurrentWeather(31.5204, 74.3587);
        }catch (\Exception $e){
            // Session::flash('error', 'Failed to fetch weather data: ' . $e->getMessage());
            // return redirect()->back();
        }

        $last_dip = dip::where('pro_id', $request->product)->where('isdeleted', 0)->orderByDesc('id')->first();

        $last_dip_qty = $last_dip ? $last_dip->qty : 0;
        $current_dip_qty = $request->qty;
        // $dip_change = $current_dip_qty - $last_dip_qty;
        // $hours_since_last_dip = $last_dip ? now()->diffInHours($last_dip->date) : null;
        $hours_since_last_dip = $last_dip ? Carbon::parse($last_dip->date)->diffInHours(now()) : 0;
        // $net_expected = $last_dip ? ($last_dip->qty + $last_dip->total_purchase_qty - $last_dip->total_sales_qty) : null;
        // $variance = $current_dip_qty - $net_expected;


        $sale = sales_items::where('pro_id', $request->product)
        ->whereHas('sales', function($q) use ($last_dip) {
            $q->whereBetween('created_at', [$last_dip->date, now()]);
        })
        ->selectRaw('SUM(qty) as total_qty, COUNT(*) as total_count')
        ->first();

        $purchase = purchaseItem::where('pro_id', $request->product)
        ->whereHas('purchase', function($q) use ($last_dip) {
            $q->whereBetween('created_at', [$last_dip->date, now()]);
        })
        ->selectRaw('SUM(qty) as total_qty, COUNT(*) as total_count')
        ->first();


        $total_purchase_qty = $purchase->total_qty ?? 0;
        $total_sales_qty = $sale->total_qty ?? 0;
        
        $net_expected = $last_dip_qty + $total_purchase_qty - $total_sales_qty;
        $variance = $current_dip_qty - $net_expected;
        $abs_variance = abs($variance);
        
        $qty = $request->qty;

        

        // $true_label = $variance > 0 ? 'Over' : ($variance < 0 ? 'Under' : 'Equal');
        // $predicted_label = $abs_variance > 10 ? ($variance > 0 ? 'Over' : 'Under') : 'Equal';
        // $true_label = $variance > 0 ? 'Over' : ($variance < 0 ? 'Under' : 'Equal');
        // $predicted_label = $abs_variance > 10 ? ($variance > 0 ? 'Over' : 'Under') : 'Equal';

        $payload = [
            'temperature' => $weather['temperature'] ?? null,
            'humidity' => $weather['humidity'] ?? null,
            'total_sales_qty' => $total_sales_qty ?? 0,
            'total_sales_count' => $sale->total_count ?? 0,
            'total_purchase_qty' => $total_purchase_qty ?? 0,
            'total_purchase_count' => $purchase->total_count ?? 0,
            'last_dip_qty' => $last_dip_qty ?? 0,
            'variance' => $variance ?? 0,
            'abs_variance' => $abs_variance ?? 0,
            'qty' => $qty,
            // 'true_label' => $true_label ?? null,
            // 'predicted_label' => $predicted_label ?? null,
        ];


        // dd($payload);

        $stock = Stock::where('pro_id',$request->product)->first();
        $q = $stock->qty;

        $anomaly_detection_payload = [
            "elapsed_hours"=>$hours_since_last_dip,
            ...$payload
        ];

        $anomaly_response = app(AnomalyDetectionService::class)->predictAnomaly($anomaly_detection_payload);
        // variance is inverted makesure nextime train corret variance sign

        // dd($anomaly_response['predicted_label'], $anomaly_response['confidence'], $anomaly_detection_payload);
        
        $payload['predicted_label'] = $anomaly_response['predicted_label'];
        $payload['confidence'] = $anomaly_response['confidence'];

        if($q > $qty){
            $t = $q - $qty;
            $st = $q - $t;
            $s = '-';
            if($st > $stock->stock_capacity){
                Session::flash('error' ,'Entering Wrong Dip Quantity !');
                return redirect()->back();
            }else{

                $dip = dip::create([
                    'pro_id' => $request->product,
                    // 'qty' => $qty,
                    'change_in_qty' => $t,
                    'sighn' => $s,
                    'desc' => $request->desc,
                    'date' => date('Y-m-d H:i:s'),
                    ...$payload
                ]);
                stock::where('pro_id' , $request->product)->update(['qty'=> $st,'dip_id'=>$dip->id]);
                $request->session()->flash('success','Dip added Succussfully!');
                return redirect(route('dip.index'));
            }
            // con ---- sighn
        }elseif($qty > $q){
            $t = $qty - $q;
            $st = $q + $t;
            $s = '+';

            if($st > $stock->stock_capacity){
                Session::flash('error' ,'Entering Wrong Dip Quantity !');
                return redirect()->back();
            }else{
                $dip = dip::create([
                    'pro_id' => $request->product,
                    // 'qty' => $qty,
                    'change_in_qty' => $t,
                    'desc' => $request->desc,
                    'sighn' => $s,
                    'date' => date('Y-m-d H:i:s'),
                    ...$payload
                ]);
                stock::where('pro_id' , $request->product)->update(['qty'=> $st,'dip_id'=>$dip->id]);
                $request->session()->flash('success','Dip added Succussfully!');
                return redirect(route('dip.index'));
            }
            // con +++++ sighn
        }elseif($q == $qty){
            $t = 0;
            $s = 'Equal';
            $dip = dip::create([
                'pro_id' => $request->product,
                // 'qty' => $qty,
                'change_in_qty' => $t,
                'desc' => $request->desc,
                'sighn' => $s,
                'date' => date('Y-m-d H:i:s'),
                ...$payload
            ]);
            stock::where('pro_id' , $request->product)->update(['dip_id'=>$dip->id]);
            $request->session()->flash('success','Dip added Succussfully!');
            return redirect(route('dip.index'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dip = dip::where('id',$id)->first();
        $stock = stock::where('dip_id',$dip->id)->first();
        $dip2 = dip::where('pro_id',$dip->pro_id)->where('isdeleted',0)->max('id');

        if($id == $dip2){
            $exp  = Products::where('isdeleted',0)->get();
            $data = dip::where('id',$id)->first();
            return view('dip.create')
            ->with('exp',$exp)
            ->with('data',$data);
        }else{
            Session::flash('error','Old Dips Cannot Edit !');
            return redirect()->back();
        };
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $stock = Stock::where('pro_id',$request->product)->first();
        $q = $stock->qty;
        $qty = $request->qty;
        if($q > $qty){
            $t = $q - $qty;
            $st = $q - $t;
            $s = '-';

            if($st > $stock->stock_capacity){
                Session::flash('error' ,'Entering Wrong Dip Quantity !');
                return redirect()->back();
            }else{
                $dip = dip::where('id',$id)->update([
                    'pro_id' => $request->product,
                    'qty' => $qty,
                    'change_in_qty' => $t,
                    'sighn' => $s,
                    'desc' => $request->desc,
                ]);
                stock::where('pro_id' , $request->product)->update(['qty'=> $st,'dip_id'=>$id]);
                $request->session()->flash('success','Dip added Succussfully!');
                return redirect(route('dip.index'));
            }
            // con ---- sighn
        }elseif($qty > $q){
            $t = $qty - $q;
            $st = $q + $t;
            $s = '+';

            if($st > $stock->stock_capacity){
                Session::flash('error' ,'Entering Wrong Dip Quantity !');
                return redirect()->back();
            }else{
                $dip = dip::where('id',$id)->update([
                    'pro_id' => $request->product,
                    'qty' => $qty,
                    'change_in_qty' => $t,
                    'desc' => $request->desc,
                    'sighn' => $s,
                ]);
                stock::where('pro_id' , $request->product)->update(['qty'=> $st,'dip_id'=>$id]);
                $request->session()->flash('success','Dip added Succussfully!');
                return redirect(route('dip.index'));
            }
            // con +++++ sighn
        }elseif($q == $qty){
            $t = 0;
            $s = 'Equal';
            $dip = dip::where('id',$id)->update([
                'pro_id' => $request->product,
                'qty' => $qty,
                'change_in_qty' => $t,
                'desc' => $request->desc,
                'sighn' => $s,
            ]);
            stock::where('pro_id' , $request->product)->update(['dip_id'=>$id]);
            $request->session()->flash('success','Dip added Succussfully!');
            return redirect(route('dip.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dip = dip::where('id',$id)->first();
        $stock = stock::where('dip_id',$dip->id)->first();
        $dip2 = dip::where('pro_id',$dip->pro_id)->where('isdeleted',0)->max('id');
        $secondlast = dip::where('pro_id',$dip->pro_id)
        ->orderByDesc('id')->where('isdeleted',0)
        ->skip(1)->take(1)
        ->select('id')->first();
        if(!$secondlast){
            $sl = null;
        }else{
            $sl = $secondlast->id;
        }

        if($id == $dip2){
            if($dip->sighn == '+'){
                $ciq = $dip->change_in_qty;
                $stockqty = $stock->qty;
                if($stockqty >=  $ciq){
                    $re = $stockqty - $ciq;
                    dip::where('id',$id)->update(['isdeleted' => 1]);
                    stock::where('pro_id',$stock->pro_id)->update(['dip_id'=>$sl,'qty'=>$re]);
                }else{
                    Session::flash('error','Please Add new dip this dip cannot delete !');
                    return redirect()->back();
                }
            }elseif($dip->sighn == '-'){
                $ciq = $dip->change_in_qty;
                $stockqty = $stock->qty;
                $re = $stockqty + $ciq;
                dip::where('id',$id)->update(['isdeleted' => 1]);
                stock::where('pro_id',$stock->pro_id)->update(['dip_id'=>$sl,'qty'=>$re]);
            }elseif($dip->sighn == 'Equal'){
                dip::where('id',$id)->update(['isdeleted' => 1]);
                stock::where('pro_id',$stock->pro_id)->update(['dip_id'=>$sl]);
                return redirect()->back();
            }

        }
        else{
            Session::flash('error','Old Dips Cannot Delete !');
            return redirect()->back();
        }
            Session::flash('warning','Dip Deleted Successfuly !');
            return redirect()->back();

        // dd($dip->sighn);
    }
}