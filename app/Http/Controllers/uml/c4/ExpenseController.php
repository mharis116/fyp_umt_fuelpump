<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\exp_type;
use App\Expense;

class ExpenseController extends Controller
{

    public $module_code;
    public $ignored_permission_methods = [];
    public $permission_methods = [];
    public function __construct(){
        $this->inject_permissions();
    }

    public function inject_permissions(){
        $this->module_code = 'expenses';
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
        $data = DB :: table('expenses')
        ->join('exp_types','exp_types.id','expenses.exp_type_id')
        ->where('expenses.isdeleted',0)
        ->select('expenses.id as expid','expenses.desc as expdesc','exp_types.desc as exptdesc','expenses.*','exp_types.*')
        ->get();
        // dd($data);
        return view('expense.index')
        ->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = exp_type::where('isdeleted',0)->get();
        return view('expense.create')->with('exp' , $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Expense::create([
           'amount' => $request->amount ,
           'exp_type_id'=>$request->type,
           'desc'=>$request->desc,
           'date' => date('Y-m-d H:i:s')
        ]);
        Session::flash('success','Expense Added Successfully !');
        return redirect(route('exp.create'));
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
        $exp = exp_type::where('isdeleted',0)->get();
        $data = Expense::where('isdeleted',0)->where('id',$id)->first();
        return view('expense.create')
        ->with('data' , $data)
        ->with('exp' , $exp);
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
        Expense::where('id',$id)->update([
            'amount' => $request->amount ,
            'exp_type_id'=>$request->type,
            'desc'=>$request->desc,
            'date' => date('Y-m-d H:i:s')
         ]);
         Session::flash('success','Expense Updated Successfully !');
         return redirect(route('exp.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Expense::where('id',$id)->update([
            'isdeleted' => 1
         ]);
         Session::flash('warning','Expense Deleted Successfully !');
         return redirect(route('exp.index'));
    }
}
