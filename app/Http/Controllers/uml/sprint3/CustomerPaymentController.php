<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\cust_ledger;
use Illuminate\Support\Facades\Session;
use App\customers;

class CustomerPaymentController extends Controller
{

    public $module_code;
    public $ignored_permission_methods = [];
    public $permission_methods = [];
    public function __construct(){
        $this->inject_permissions();
    }

    public function inject_permissions(){
        $this->module_code = 'customer-payments';
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
        $data = cust_ledger::where('type','payment')->where('cust_ledgers.isdeleted',0)->join('customers','customers.id','cust_ledgers.customer_id')
        ->select('customers.name','customers.phone1','cust_ledgers.desc','cust_ledgers.cr as cash','cust_ledgers.date','cust_ledgers.id as lid')
        ->get();
        return view('ctra.index')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sup = customers::where('isdeleted',0)->get();
        return view('ctra.create')->with('sup',$sup);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        cust_ledger::create(['cr'=>$request->cash,'dr'=>-$request->cash,'type'=>'payment','date'=>date('Y-m-d H:i:s'),'desc'=>$request->desc,'customer_id'=>$request->customer]);
        Session::flash('success','payment Added Successfully !');
        return redirect(route('ctra.index'));
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
        $dat = cust_ledger::where('cust_ledgers.id',$id)->where('type','payment')->join('customers','customers.id','cust_ledgers.customer_id')
        ->select('customers.id as sid','cust_ledgers.cr as cash','cust_ledgers.desc','cust_ledgers.date','cust_ledgers.id as lid')
        ->first();
        $datt = cust_ledger::where('cust_ledgers.customer_id',$dat->sid)->where('id','!=',$id)
        ->selectRaw('sum(dr) as credit,sum(adjustment) as adj')
        ->first();
        $sup = customers::where('isdeleted',0)->get();
        return view('ctra.create')->with('sup',$sup)->with('dat',$dat)->with('credit',$datt);
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
        cust_ledger::where('id',$id)->update(['cr'=>$request->cash,'dr'=>-$request->cash,'type'=>'payment','date'=>date('Y-m-d H:i:s'),'desc'=>$request->desc]);
        Session::flash('success','payment Updated Successfully !');
        return redirect(route('ctra.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        cust_ledger::where('id',$id)->update(['isdeleted'=>1]);
        Session::flash('warning','Payment Deleted Successfully!');
        return redirect(route('ctra.index'));
    }
}
