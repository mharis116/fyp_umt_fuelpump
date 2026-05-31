<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\sup_ledger;
use Illuminate\Support\Facades\Session;
use App\suppliers;

class SupplierPaymentController extends Controller
{

    public $module_code;
    public $ignored_permission_methods = [];
    public $permission_methods = [];
    public function __construct(){
        $this->inject_permissions();
    }

    public function inject_permissions(){
        $this->module_code = 'supplier-payments';
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
        $data = sup_ledger::where('type','payment')->where('sup_ledgers.isdeleted',0)->join('suppliers','suppliers.id','sup_ledgers.sup_id')
        ->select('suppliers.name','suppliers.phone1','sup_ledgers.desc','sup_ledgers.cr as cash','sup_ledgers.date','sup_ledgers.id as lid')
        ->get();
        return view('tra.index')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sup = Suppliers::where('isdeleted',0)->get();
        return view('tra.create')->with('sup',$sup);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        sup_ledger::create(['cr'=>$request->cash,'dr'=>-$request->cash,'type'=>'payment','date'=>date('Y-m-d H:i:s'),'desc'=>$request->desc,'sup_id'=>$request->supplier]);
        Session::flash('success','payment Added Successfully !');
        return redirect(route('tra.index'));
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
        $dat = sup_ledger::where('sup_ledgers.id',$id)->where('type','payment')->join('suppliers','suppliers.id','sup_ledgers.sup_id')
        ->select('suppliers.id as sid','sup_ledgers.cr as cash','sup_ledgers.desc','sup_ledgers.date','sup_ledgers.id as lid')
        ->first();
        $datt = sup_ledger::where('sup_ledgers.sup_id',$dat->sid)->where('id','!=',$id)
        ->selectRaw('sum(dr) as credit,sum(adjustment) as adj')
        ->first();
        $sup = Suppliers::where('isdeleted',0)->get();
        return view('tra.create')->with('sup',$sup)->with('dat',$dat)->with('credit',$datt);
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
        sup_ledger::where('id',$id)->update(['cr'=>$request->cash,'dr'=>-$request->cash,'type'=>'payment','date'=>date('Y-m-d H:i:s'),'desc'=>$request->desc]);
        Session::flash('success','payment Updated Successfully !');
        return redirect(route('tra.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        sup_ledger::where('id',$id)->update(['isdeleted'=>1]);
        Session::flash('warning','Payment Deleted Successfully!');
        return redirect(route('tra.index'));
    }
}
