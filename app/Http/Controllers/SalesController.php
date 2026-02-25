<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\products;
use Illuminate\Support\Facades\Session;
use App\customers;
use App\sales;
use App\stock;
use App\sales_items;
use App\cust_ledger;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public $module_code;
    public $ignored_permission_methods = [];
    public $permission_methods = [];
    public function __construct(){
        $this->inject_permissions();
    }

    public function inject_permissions(){
        $this->module_code = 'sales';
        $this->ignored_permission_methods = [
            "ledger", "dt"
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
        $data  = products::where('isdeleted',0)->get();
        $cust  = customers::where('isdeleted',0)->get();
        $stock = db::table('products')
        ->join('stocks','stocks.pro_id','products.id')
        ->select('products.name as name','stocks.*')
        ->where('products.isdeleted',0)
        ->get();

        $sales = db::table('sales')
        ->join('customers','customers.id','sales.customer_id')
        ->join('cust_ledgers','cust_ledgers.sale_id','sales.id')
        ->select('customers.name','sales.*','cust_ledgers.cr','cust_ledgers.dr')
        ->where('sales.isdeleted',0)
        ->where('customers.isdeleted',0)
        ->orderBy('id', 'desc')->take(7)->get();

        $inv = sales::max('invoice_no');
        $inv += 1;

        return view('sales.index')
        ->with('invo',$inv)
        ->with('sell',$sales)
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
        $sales = db::table('sales')
        ->join('customers','customers.id','sales.customer_id')
        ->join('cust_ledgers','cust_ledgers.sale_id','sales.id')
        ->select('customers.name','sales.id as sid','sales.*','cust_ledgers.cr','cust_ledgers.dr')
        ->where('sales.isdeleted',0)
        ->where('customers.isdeleted',0)
        ->orderBy('id', 'desc')->get();
        return view('sales.view')
        ->with('sell',$sales);
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
        $sub_totals = [];
        while(True){
            $n = 'name'.$x;
            $ni = 'nameid'.$x;
            $q = 'qty'.$x;
            $s = 'sub_total'.$x;
            $sk = 'sku'.$x;
            $c = 'cost_price'.$x;
            $r = 'retail_price'.$x;
            $name = $request->$n;
            $id = $request->$ni;
            $qty = $request->$q;
            $sub_total = $request->$s;
            $sku = $request->$sk;
            $cp = $request->$c;
            $rp = $request->$r;
            if($name and $qty and $sub_total and $id and $cp and $rp and $sku){
                array_push($names,$name);
                array_push($qtys,$qty);
                array_push($sub_totals,$sub_total);
                array_push($skus,$sku);
                array_push($ids,$id);
                array_push($cps,$cp);
                array_push($rps,$rp);
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
        $cust = $request->customer;
        $inv = '1';
        $mid = sales::max('id');

        //

        $ledger = cust_ledger::groupBy('cust_ledgers.customer_id')->groupBy('customers.name')
        ->groupBy('customers.credit_limit')->groupBy('customers.id')
        ->join('customers','customers.id','cust_ledgers.customer_id')
        ->join('sales','sales.id','cust_ledgers.sale_id')
        ->where('customers.id',$cust)
        ->selectRaw('sum(dr) as credit,sum(cr) as cash,sum(cust_ledgers.adjustment) as adj,sum(sales.retail_amount) as rtm, cust_ledgers.customer_id,customers.name as cust_name,customers.credit_limit')
        ->first();

        $limitc = customers::where('id',$cust)->first();
        $limit = $limitc->credit_limit;
        if(isset($ledger->credit)){
            $limit = $limit - $ledger->credit;
        }
        if($limitc->credit_limit == null or ($limitc->credit_limit != null)){ //$limitc->credit_limit != null  & $limit >= $credit need to add with
            if($mid){
                $lsa = sales::where('id',$mid)->first();
                $linv = $lsa->invoice_no;
                $inv = $linv + 1;
            }
            $sales = new sales;
            $sales->date = date('Y-m-d H:i:s');
            $sales->invoice_no = $inv;
            $sales->hierarchy_id = $request->hierarchy_id;
            $cost_amount = array();
            $retail_amount = array();
            foreach ($cps as $key=>$value) {
                $cost_amount[] = $value * $qtys[$key];
            }
            foreach ($rps as $key=>$value) {
                $retail_amount[] = $value * $qtys[$key];
            }
            $sales->cost_amount = array_sum($cost_amount);
            $sales->retail_amount =  array_sum($retail_amount);
            $sales->desc =  $request->desc;
            $sales->total_qty =  array_sum($qtys);
            $sales->adjustment = $adjust;
            $sales->customer_id = $cust;
            $sales->save();
            $ii = 0;
            $i = 0;
            $a = [];
            $e = [];
            while(True){
                if(null === stock::where('pro_id',$ids[$ii])->first()){
                    array_push($e,0);
                }else{
                    array_push($e,1);
                    $st = stock::where('pro_id',$ids[$ii])->first();
                    $q = $st->qty;
                    if($q >= $qtys[$ii]){
                        $ab = 1;
                    }else{
                        $ab = 0;
                    }
                    array_push($a,$ab);
                }
                if($ii == count($ids)-1){
                    break;
                }
                $ii += 1;
            }
            if(array_product($e) == 1){
                if(array_product($a) == 1){
                    while(True){
                        $sales_items = new sales_items;
                        $sales_items->date = date('Y-m-d H:i:s');
                        $sales_items->sale_id =  $sales->id;
                        $sales_items->pro_id =  $ids[$i];
                        $sales_items->sku =  $skus[$i];
                        $sales_items->qty =  $qtys[$i];
                        $sales_items->cost_price =  $cps[$i];
                        $sales_items->subtotal =  $sub_totals[$i];
                        $sales_items->retail_price =  $rps[$i];
                        $sales_items->save();
                        // -----------------------subtracting from stock -------------------------------
                        $stoc = stock::where('pro_id',$ids[$i])->first();
                        $sto = stock::where('pro_id',$ids[$i])->update([
                            'qty' => $stoc->qty - $qtys[$i]
                        ]);
                        if($i == count($ids)-1){
                            break;
                        }
                        $i += 1;
                    }
                    $cust_ledger = new cust_ledger;
                    $cust_ledger->sale_id =  $sales->id;
                    $cust_ledger->date = date('Y-m-d H:i:s');
                    $cust_ledger->customer_id = $cust;
                    $cust_ledger->cr = $cash;
                    $cust_ledger->dr = array_sum($retail_amount)-$cash;
                    $cust_ledger->desc = $request->desc;
                    $cust_ledger->adjustment = $adjust;
                    $cust_ledger->save();
                    $request->session()->flash('success', 'Entry Saved!');
                    if($limit < $request->ct and $limitc->credit_limit != null){
                        Session::flash('warning','Customer Credit Limit Exceed Entry Saved !');
                    }
                    return redirect()->back();
                }elseif(array_product($a) == 0){
                    sales::where('id',$sales->id)->delete();
                    $request->session()->flash('error', 'Please Check Your Fuel Stock, Entry not saved by fuel exist!');
                    return redirect()->back();
                }
            }elseif(array_product($e) == 0){
                sales::where('id',$sales->id)->delete();
                $request->session()->flash('error', 'Please Check Your Fuel Stock, Entry not saved by fuel check!');
                return redirect()->back();
            }
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
        $sale = sales::where('sales.id',$id)
        ->join('customers','customers.id','sales.customer_id')
        ->select('sales.id as sid','sales.date as sdate','sales.*','customers.id as cid','customers.*')
        ->first();
        $led = cust_ledger::groupBy('cust_ledgers.customer_id')->groupBy('customers.name')
        ->groupBy('customers.credit_limit')->groupBy('customers.id')->groupBy('customers.opening_bal')
        ->join('customers','customers.id','cust_ledgers.customer_id')
        // ->join('sales','sales.id','cust_ledgers.sale_id')
        ->where('cust_ledgers.isdeleted',0)
        ->selectRaw('sum(dr) as credit,sum(cr) as cash,sum(cust_ledgers.adjustment) as adj, cust_ledgers.customer_id,customers.name as cust_name,customers.opening_bal as opbl')
        ->where('customers.id',$sale->cid)
        ->first();
        $sl = cust_ledger::where('sale_id',$sale->sid)->first();
        $pi = sales_items::where('sale_id',$sale->sid)->join('products','products.id','sales_items.pro_id')->get();
        // dd($sl);
        return view('sales.invoice')->with('pur',$sale)->with('sl',$sl)->with('led',$led)->with('pii',$pi);
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
        $sale_item = Sales_items::where('sale_id',$id)->get();
        $li = [];
        foreach($sale_item as $si){
            $sid = sales_items::where('id',$si->id)->first();
            $pro = stock::where('pro_id',$sid->pro_id)->first();
            if($pro->qty + $sid->qty > $pro->stock_capacity){
                array_push($li,0);
            }else{
                array_push($li,1);
            }

        }
        if(array_product($li) == 1){
            foreach($sale_item as $si){
                $sid = sales_items::where('id',$si->id)->first();
                $pro = stock::where('pro_id',$sid->pro_id)->first();
                $pro->qty = $pro->qty + $sid->qty;
                $pro->save();
            }
            $sale = Sales::where('id',$id)->first();
            Sales_items::where('sale_id',$id)->update(['isdeleted' => '1']);
            $customer = cust_ledger::where('customer_id',$sale->customer_id)->where('sale_id',$id)->first();
            $sale->isdeleted = 1;
            $customer->isdeleted = 1;
            $sale->save();
            $customer->save();
            session::flash('warning','Sale deleted Successfully!');
            return redirect()->back();
        }else{
            session::flash('error','This Sale cannot delete stock error!');
            return redirect()->back();
        }
    }


    //region helpers
    public function dt($id){
        $data  = products::where('id',$id)->where('isdeleted',0)->first();
        return response()->json($data, 200);
    }

    public function ledger($id){
        $data = cust_ledger::groupBy('cust_ledgers.customer_id')->groupBy('customers.name')
        ->groupBy('customers.credit_limit')->groupBy('customers.id')
        ->join('customers','customers.id','cust_ledgers.customer_id')
        ->where('customers.id',$id)
        ->where('cust_ledgers.isdeleted',0)
        ->selectRaw('sum(dr) as credit,sum(cr) as cash,sum(cust_ledgers.adjustment) as adj, cust_ledgers.customer_id,customers.name as cust_name,customers.credit_limit as climit,customers.id as cuid')
        ->first();
        return response()->json($data, 200);
    }
}
