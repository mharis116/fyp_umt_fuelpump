<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\sales;
use App\sale_items;
use App\customers;
use App\cust_ledger;
use Illuminate\Support\Facades\DB;


class CustomerLedgerController extends Controller
{

    public $module_code;
    public $ignored_permission_methods = [];
    public $permission_methods = [];
    public function __construct(){
        $this->inject_permissions();
    }

    public function inject_permissions(){
        $this->module_code = 'customer-ledgers';
        $this->ignored_permission_methods = [
        ];
        $this->permission_methods = [
            'index' => [
                'module_permission_type_code' => 'read',
            ],
            'show' => [
                'module_permission_type_code' => 'read',
            ],
            // 'edit' => [
            //     'module_permission_type_code' => 'read',
            // ],
            // 'update' => [
            //     'module_permission_type_code' => 'update',
            // ],
            // 'create' => [
            //     'module_permission_type_code' => 'create',
            // ],
            // 'store' => [
            //     'module_permission_type_code' => 'create',
            // ],
            // 'destroy' => [
            //     'module_permission_type_code' => 'delete',
            // ],
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = cust_ledger::groupBy('cust_ledgers.customer_id')->groupBy('customers.name')
        ->groupBy('customers.credit_limit')->groupBy('customers.id')
        ->join('customers','customers.id','cust_ledgers.customer_id')
        // ->join('sales','sales.id','cust_ledgers.sale_id')
        ->where('cust_ledgers.isdeleted',0)
        ->selectRaw('sum(dr) as credit,sum(cr) as cash,sum(cust_ledgers.adjustment) as adj, cust_ledgers.customer_id,customers.name as cust_name,customers.credit_limit,customers.id')
        ->get();
        // dd($data);
        return view('ledger.customer.index')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cust  = customers::where('id',$id)->first();
        $total = cust_ledger::groupBy('customer_id')
        ->where('customer_id',$id)
        ->where('isdeleted',0)
        ->selectRaw('sum(dr) as credit,sum(cr) as cash,sum(cust_ledgers.adjustment) as adj, cust_ledgers.customer_id')
        ->first();
        $sale = cust_ledger::
        where('cust_ledgers.customer_id',$id)
        ->join('sales','sales.id','cust_ledgers.sale_id')
        ->where('cust_ledgers.isdeleted',0)
        ->where('sales.isdeleted',0)
        ->get();
        $payment = cust_ledger::
        where('customer_id',$id)
        ->where('sale_id',null)
        ->where('type','!=','sale')
        ->where('isdeleted',0)
        ->get();
        return view('ledger.customer.show')->with('total',$total)->with('sale',$sale)->with('other',$payment)->with('cust',$cust);
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
        //
    }
}
