<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\products;
use App\suppliers;
use App\purchases;
use Illuminate\Support\Facades\Session;
use App\stock;
use App\FuelBackup;
use App\purchaseItem;
use App\sup_ledger;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{

    public $module_code;
    public $ignored_permission_methods = [];
    public $permission_methods = [];
    public function __construct(){
        $this->inject_permissions();
    }

    public function inject_permissions(){
        $this->module_code = 'purchases';
        $this->ignored_permission_methods = [
            "dt",
            "ledger"
        ];
        $this->permission_methods = [
            'index' => [
                'module_permission_type_code' => 'create',
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
                'module_permission_type_code' => 'read',
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
        $data  = Products::where('isdeleted',0)->get();
        $cust  = suppliers::where('isdeleted',0)->get();
        $stock = db::table('products')
        ->join('stocks','stocks.pro_id','products.id')
        ->select('products.name as name','stocks.*')
        ->where('products.isdeleted',0)
        ->get();

        $purchases = db::table('purchases')
        ->join('suppliers','suppliers.id','purchases.sup_id')
        ->join('sup_ledgers','sup_ledgers.pur_id','purchases.id')
        ->select('suppliers.name','suppliers.company','purchases.*','sup_ledgers.cr','sup_ledgers.dr')
        ->where('purchases.isdeleted',0)
        ->where('suppliers.isdeleted',0)
        ->orderBy('id', 'desc')->take(7)->get();

        $inv = Purchases::max('inv_no');
        $inv += 1 ;
        return view('purchase.index')
        ->with('inv',$inv)
        ->with('sell',$purchases)
        ->with('stock',$stock)
        ->with('cust',$cust)
        ->with('dat',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $purchases = db::table('purchases')
        ->join('suppliers','suppliers.id','purchases.sup_id')
        ->join('sup_ledgers','sup_ledgers.pur_id','purchases.id')
        ->select('suppliers.name','purchases.id as pid','purchases.*','sup_ledgers.cr','sup_ledgers.dr')
        ->where('purchases.isdeleted',0)
        // ->where('purchases.isdeleted',0)
        ->orderBy('id', 'desc')->get();
        return view('purchase.view')
        ->with('sell',$purchases);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $x = 1;
        $names = [];
        $ids = [];
        $qtys = [];
        $skus = [];
        $cps = [];
        $rps = [];
        $qtys = [];
        $bfcs = [];
        $sub_totals = [];
        $hierarchy_id = $request->hierarchy_id;

        while(True){
            $n = 'name'.$x;
            $ni = 'nameid'.$x;
            $q = 'qty'.$x;
            $s = 'sub_total'.$x;
            $sk = 'sku'.$x;
            $c = 'cost_price'.$x;
            $r = 'retail_price'.$x;
            $b = 'bfc'.$x;
            $name = $request->$n;
            $id = $request->$ni;
            $qty = $request->$q;
            $sub_total = $request->$s;
            $sku = $request->$sk;
            $cp = $request->$c;
            $rp = $request->$r;
            $bfc = $request->$b;
            if($name and $qty and $sub_total and $id and $cp and $rp and $sku ){
                array_push($names,$name);
                array_push($qtys,$qty);
                array_push($sub_totals,$sub_total);
                array_push($skus,$sku);
                array_push($ids,$id);
                array_push($cps,$cp);
                array_push($rps,$rp);
                array_push($bfcs,$bfc);
            }else{
                if($x == $request->hidden){
                    break;
                }
                $x += 1;
                continue;
            }
            if($x == $request->hidden){
                break;
            }
             $x += 1;
        }
        $gt = $request->gt;
        // $cost_price = $request->cp;
        $cash = $request->cash;
        $adjust = $request->adjust;
        $credit = $request->cth;
        $items = $request->items;
        $sup = $request->supplier;
        $inv = '1';
        $mid = purchases::max('id');
        if($mid){
           $lsa = purchases::where('id',$mid)->first();
           $linv = $lsa->inv_no;
           $inv = $linv + 1;
        }
        $purchases = new purchases;
        $purchases->date = date('Y-m-d H:i:s');
        $purchases->inv_no = $inv;
        $purchases->hierarchy_id = $hierarchy_id;
        $purchases->sup_bill_no = $request->bill_no;
        $cost_amount = array();
        $retail_amount = array();
        foreach ($cps as $key=>$value) {
            $cost_amount[] = $value * $qtys[$key];
        }
        foreach ($rps as $key=>$value) {
            $retail_amount[] = $value * $qtys[$key];
        }
        $purchases->cost_amount = array_sum($cost_amount);
        $purchases->retail_amount =  array_sum($retail_amount);
        $purchases->desc =  $request->desc;
        if($request->storage == 's'){
            $purchases->pur_type =  'stock';
        }elseif($request->storage == 'b'){
            $purchases->pur_type =  'backup';
        }
        $purchases->total_qty =  array_sum($qtys);
        $purchases->adjustment = $adjust;
        $purchases->sup_id = $sup;
        $purchases->save();
        $ii = 0;
        $i = 0;
        $e = [];
        $a = [];
        $backup = [];
        while(True){
            if($request->storage == 's'){
                array_push($backup,1);
                if(null === stock::where('pro_id',$ids[$ii])->first()){
                    array_push($e,0);
                }else{
                    array_push($e,1);
                }
                $stoc = stock::where('pro_id',$ids[$ii])->first();
                $qq = $stoc->qty;
                if($stoc->qty == 0 ){
                    $qq = 0;
                }

                if($stoc->stock_capacity >= $qtys[$ii] + $qq){
                    array_push($a,1);
                }else{
                    array_push($a,0);
                }


            }elseif($request->storage == 'b'){
                array_push($a,1);
                array_push($e,1);
                if($bfcs[$ii] >= $qtys[$ii]){
                    array_push($backup,1);
                }else{
                    array_push($backup,0);
                }
            }
            if($ii == count($ids) - 1){
                break;
            }
            $ii += 1;
        }
        if(array_product($e) == 1 and array_product($a) == 1 and array_product($backup) == 1){

            while(True){
                    $purchases_items = new purchaseItem;
                    $purchases_items->date = date('Y-m-d H:i:s');
                    $purchases_items->pur_id =  $purchases->id;
                    $purchases_items->pro_id =  $ids[$i];
                    $purchases_items->sku =  $skus[$i];
                    $purchases_items->qty =  $qtys[$i];
                    $purchases_items->cost_price =  $cps[$i];
                    $purchases_items->sub_total =  $sub_totals[$i];
                    $purchases_items->retail_price =  $rps[$i];
                    $purchases_items->save();
                    // -----------------------adding from stock -------------------------------
                    if($request->storage == 's'){
                        $stoc = stock::where('pro_id',$ids[$i])->first();
                        $sto = stock::where('pro_id',$ids[$i])->update([
                            'qty' => $stoc->qty + $qtys[$i]
                        ]);
                    }elseif($request->storage == 'b'){
                        $prod = products::where('id',$ids[$i])->first();
                        $sto = FuelBackup::create([
                            'qty' => $qtys[$i],
                            'fqty' => $qtys[$i],
                            'pro_id' => $ids[$i],
                            'pur_id' => $purchases->id,
                            'sku' => $prod->sku,
                            'stock_capacity' =>$bfcs[$i],
                        ]);
                    }
                if($i == count($ids)-1){
                    break;
                }
                $i += 1;
            }
            $cust_ledger = new sup_ledger;
            $cust_ledger->pur_id =  $purchases->id;
            $cust_ledger->date = date('Y-m-d H:i:s');
            $cust_ledger->sup_id = $sup;
            $cust_ledger->cr = $cash;
            $cust_ledger->dr = array_sum($cost_amount) - $cash;
            $cust_ledger->desc = $request->desc;
            $cust_ledger->adjustment = $adjust;
            $cust_ledger->save();
            $request->session()->flash('success', 'Entry Saved!');
            return redirect()->back();
        }elseif(array_product($e) == 0){
            purchases::where('id',$purchases->id)->delete();
                $request->session()->flash('error', 'Please Check Your Fuel Stock, Entry not saved some fuel stock not exist !');
                return redirect()->back();
        }elseif(array_product($a) == 0){
            purchases::where('id',$purchases->id)->delete();
                $request->session()->flash('error', 'Please Check Your Fuel Stock, Entry not saved fuel capacity exceed!');
                return redirect()->back();
        }elseif(array_product($backup) == 0){
            purchases::where('id',$purchases->id)->delete();
                $request->session()->flash('error', 'Fuel Backup tank Capacity cannot be smaller then fuel Quantity !');
                return redirect()->back();
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
        $purchase = purchases::where('purchases.id',$id)
        ->join('suppliers','suppliers.id','purchases.sup_id')
        ->select('purchases.id as pid','purchases.date as pdate','purchases.*','suppliers.id as sid','suppliers.*')
        ->first();
        $led = sup_ledger::groupBy('sup_ledgers.sup_id')->groupBy('suppliers.name')->groupBy('suppliers.opening_bal')->groupBy('suppliers.id')
        ->join('suppliers','suppliers.id','sup_ledgers.sup_id')
        ->where('sup_ledgers.isdeleted',0)
        ->selectRaw('sum(dr) as credit,sum(cr) as cash,sum(sup_ledgers.adjustment) as adj, sup_ledgers.sup_id,suppliers.name as cust_name,suppliers.opening_bal as opbl')
        ->where('suppliers.id',$purchase->sid)
        ->first();
        $sl = sup_ledger::where('pur_id',$purchase->pid)->first();
        $pi = purchaseItem::where('pur_id',$purchase->pid)->join('products','products.id','purchase_items.pro_id')->get();
        // dd($sl);
        return view('purchase.invoice')->with('pur',$purchase)->with('sl',$sl)->with('led',$led)->with('pii',$pi);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sale = purchases::where('id',$id)->first();
        $sale_item = purchaseitem::where('pur_id',$id)->get();
        $vali = [];
        $qt = [];
        if($sale->pur_type == 'stock'){
            foreach($sale_item as $si){
                $sid = purchaseitem::where('id',$si->id)->first();
                $pro = stock::where('pro_id',$sid->pro_id)->first();
                if($pro->qty > $sid->qty){
                    array_push($vali,1);
                    array_push($qt,1);
                }else{
                    array_push($vali,0);
                    array_push($qt,1);
                }
            }
        }elseif($sale->pur_type == 'backup'){
            array_push($vali,1);
            foreach($sale_item as $si){
                $sid = purchaseitem::where('id',$si->id)->first();
                $back = FuelBackup::where('pur_id',$sid->pur_id)->where('pro_id',$sid->pro_id)->first();
                if($back->qty == $sid->qty){
                    array_push($qt,1);
                }elseif($back->qty != $sid->qty ){
                    array_push($qt,0);

                }
            }
        }
        if(array_product($qt) == 1){
            if(array_product($vali) == 1)
            {

                $sale = purchases::where('id',$id)->first();
                purchaseitem::where('pur_id',$id)->update(['isdeleted' => '1']);
                $customer = sup_ledger::where('sup_id',$sale->sup_id)->where('pur_id',$id)->first();
                $sale->isdeleted = 1;
                $customer->isdeleted = 1;
                $sale->save();
                $customer->save();
                if($sale->pur_type == 'stock'){
                    foreach($sale_item as $si){
                        $sid = purchaseitem::where('id',$si->id)->first();
                        $pro = stock::where('pro_id',$sid->pro_id)->first();
                        $v = $pro->qty - $sid->qty;
                        $pro->qty = $v;
                        $pro->save();

                    }
                }elseif($sale->pur_type == 'backup'){
                    foreach($sale_item as $si){
                        $sid = purchaseitem::where('id',$si->id)->first();
                        $back = FuelBackup::where('pur_id',$sid->pur_id)->where('pro_id',$sid->pro_id)->update(['isdeleted'=>1]);
                    }
                }
                session::flash('warning','Purchase deleted Successfully!');
                return redirect()->back();
            }else{
                session::flash('error','This purchase can not delete Stock Error!');
                return redirect()->back();
            }
        }else{
            Session::flash('error','This purchase cannot delete becouse the backup tank is transferd !');
            return redirect()->back();
        }
    }

    //region helpers
    public function dt(){
        $data  = Products::where('id',$id)->where('isdeleted',0)->first();
        return response()->json($data, 200);
    }

    public function ledger($id){
        $data = sup_ledger::groupBy('sup_ledgers.sup_id')->groupBy('suppliers.opening_bal')->groupBy('suppliers.name')
        ->groupBy('suppliers.name')
        ->join('suppliers','suppliers.id','sup_ledgers.sup_id')
        // ->join('purchases','purchases.id','sup_ledgers.pur_id')
        ->where('sup_ledgers.isdeleted',0)
        ->selectRaw('sum(dr) as credit,sum(cr) as cash,sum(sup_ledgers.adjustment) as adj, sup_ledgers.sup_id,suppliers.name as cust_name,suppliers.opening_bal as opbl')
        ->where('suppliers.id',$id)
        ->first();
        return response()->json($data, 200);
    }
}
