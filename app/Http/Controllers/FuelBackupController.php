<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\FuelBackup;
use App\stock;

class FuelBackupController extends Controller
{


    public $module_code;
    public $ignored_permission_methods = [];
    public $permission_methods = [];
    public function __construct(){
        $this->inject_permissions();
    }

    public function inject_permissions(){
        $this->module_code = 'fuel-backups';
        $this->ignored_permission_methods = [
        ];
        $this->permission_methods = [
            'index' => [
                'module_permission_type_code' => 'read',
            ],
            // 'show' => [
            //     'module_permission_type_code' => 'read',
            // ],
            'edit' => [
                'module_permission_type_code' => 'transfer',
            ],
            'update' => [
                'module_permission_type_code' => 'transfer',
            ],
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
        $data = FuelBackup::join('products','products.id','fuel_backups.pro_id')
        ->join('purchases','purchases.id','fuel_backups.pur_id')
        ->where('fuel_backups.isdeleted',0)
        ->select('fuel_backups.*','fuel_backups.id as fbid','products.*','purchases.inv_no')
        // ->where('qty','!=',0)
        ->get();
        return view('backup.index')->with('data',$data);
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
        $dat = FuelBackup::where('fuel_backups.id',$id)->join('products','products.id','fuel_backups.pro_id')
        ->where('fuel_backups.isdeleted',0)->select('fuel_backups.*','fuel_backups.id as fbid','products.id as pid','products.*')->where('qty','!=',0)->first();
        $stock = stock::where('pro_id',$dat->pid)->first();
        return view('backup.create')->with('dat',$dat)->with('sto',$stock);
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
        $backup = FuelBackup::where('id',$id)->first();
        $stock = stock::where('pro_id',$backup->pro_id)->first();
        if($request->ava >= $request->transfer){
            stock::where('id',$stock->id)->update(['qty'=>$stock->qty + $request->transfer]);
            FuelBackup::where('id',$backup->id)->update(['qty'=>$backup->qty - $request->transfer]);
            session::flash('success',$request->transfer.' ltrs '.$request->name.' transferd to stock Tank successfully !');
            return redirect(route('backup.index'));
        }else{
            session::flash('error',$request->name.' can not transfer to stock tank more then '.$request->ava.'ltrs !');
            return redirect()->back();
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
        //
    }
}
