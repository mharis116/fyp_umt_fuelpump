<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\exp_type;
use App\expenses;

class exptypecontroller extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = exp_type::where('isdeleted',0)->get();
        return view('expense.type.index')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('expense.type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:exp_types',
        ]);
        exp_type::create(['name'=>strtolower($request->name),'type'=>$request->type,'desc'=>$request->desc]);
        $request->session()->flash('success', 'Expense Type Added Successfuly!');
        return redirect()->back();
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
        $data = exp_type::where('id',$id)->first();
        return view('expense.type.create')->with('data',$data);
        
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
        $validated = $request->validate([
            'name' => 'required|unique:exp_types,name,'.$id.',id',
        ]);
        exp_type::where('id',$id)->update(['name'=>strtolower($request->name),'type'=>$request->type,'desc'=>$request->desc]);
        $request->session()->flash('success', 'Expense Type Updated Successfuly!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expt = exp_type::where('id',$id)->first();
        $exp = expenses::where('exp_type_id',$id)->exists();
        if(!$exp){
            exp_type::where('id',$id)->update(['isdeleted'=>1]);
            Session::flash('success','Deleted Successfully !');
            return redirect()->back();
        }else{
            Session::flash('error','Cannot Delete, There is an association with Expenses !');
            return redirect()->back();
        }
    }
}
