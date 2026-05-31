<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\dip;
use Illuminate\Support\Facades\Session;
use App\products;
use Illuminate\Support\Facades\DB;
use App\stock;

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
        $stock = db::table('products')
        ->join('stocks','stocks.pro_id','products.id')
        ->select('products.name as name','stocks.*')
        ->where('products.isdeleted',0)
        ->get();
        $data = dip::join('products','products.id','pro_id')
        ->where('products.isdeleted',0)
        ->where('dips.isdeleted',0)
        // ->join('stocks','stocks.pro_id','products.id')
        ->select('products.*','dips.id as dip_id','dips.desc as ddesc','dips.*')->get();
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
                $dip = dip::create([
                    'pro_id' => $request->product,
                    'qty' => $qty,
                    'change_in_qty' => $t,
                    'sighn' => $s,
                    'desc' => $request->desc,
                    'date' => date('Y-m-d H:i:s')
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
                    'qty' => $qty,
                    'change_in_qty' => $t,
                    'desc' => $request->desc,
                    'sighn' => $s,
                    'date' => date('Y-m-d H:i:s')
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
                'qty' => $qty,
                'change_in_qty' => $t,
                'desc' => $request->desc,
                'sighn' => $s,
                'date' => date('Y-m-d H:i:s')
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
