<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\purchases;
use App\purchaseItem;
use App\suppliers;
use App\sup_ledger;
use Illuminate\Support\Facades\DB;

class SupplierLedgerController extends Controller
{

    public $module_code;
    public $ignored_permission_methods = [];
    public $permission_methods = [];
    public function __construct(){
        $this->inject_permissions();
    }

    public function inject_permissions(){
        $this->module_code = 'supplier-ledgers';
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
        $data = sup_ledger::groupBy('sup_ledgers.sup_id')->groupBy('suppliers.name')->groupBy('suppliers.id')->groupBy('suppliers.opening_bal')
        ->join('suppliers','suppliers.id','sup_ledgers.sup_id')
        // ->join('purchases','purchases.id','sup_ledgers.pur_id')
        ->where('sup_ledgers.isdeleted',0)
        ->selectRaw('sum(dr) as credit,sum(cr) as cash,sum(sup_ledgers.adjustment) as adj, sup_ledgers.sup_id,suppliers.name as cust_name,suppliers.opening_bal,suppliers.id')
        ->get();
        // dd($data);
        return view('ledger.supplier.index')->with('data',$data);
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
        $cust  = suppliers::where('id',$id)->first();
        $total = sup_ledger::groupBy('sup_id')
        ->where('sup_id',$id)
        ->where('isdeleted',0)
        ->selectRaw('sum(dr) as credit,sum(cr) as cash,sum(sup_ledgers.adjustment) as adj, sup_ledgers.sup_id')
        ->first();
        $purchase = sup_ledger::
        where('sup_ledgers.sup_id',$id)
        ->join('purchases','purchases.id','sup_ledgers.pur_id')
        ->where('sup_ledgers.isdeleted',0)
        ->where('purchases.isdeleted',0)
        ->get();
        $payment = sup_ledger::
        where('sup_id',$id)
        ->where('pur_id',null)
        ->where('type','!=','purchase')
        ->where('isdeleted',0)
        ->get();
        return view('ledger.supplier.show')->with('total',$total)->with('purchase',$purchase)->with('other',$payment)->with('cust',$cust);
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
