<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\suppliers;
use Illuminate\Support\Facades\Session;
use App\sup_ledger;

class suplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Suppliers::where('isdeleted',0)->get();
        return view('supplier.index')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('supplier.create');
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
            'name' => 'required|unique:suppliers',
            'address' => 'required',
            'phone1' => 'required|min:11|unique:suppliers',
            'phone2' => 'nullable|min:11|unique:suppliers',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:150',
            'email' => 'required|unique:suppliers',
        ]);
        $s = Suppliers::create([
            'name' =>  strtolower($request->name),
            'company' =>  strtolower($request->company),
            'email' => $request->email,
            'phone1' => $request->phone1,
            'phone2' => $request->phone2,
            'city' => $request->city,
            'address' => $request->address,
            'opening_bal' => $request->opening_bal,
            'date' => date('Y-m-d H:i:s')
        ]);
        if($request->opening_bal != null){
            $sp =sup_ledger::create(['dr'=>$request->opening_bal,'type'=>'opbl','date'=>date('Y-m-d H:i:s'),'desc'=>'opening balance','sup_id'=>$s->id]);
            Suppliers::where('id',$s->id)->update(['op_bal_id' => $sp->id]);
        }
        $request->session()->flash('success', 'Supplier Added Successfuly!');
        return redirect()->back();
        // 'op_bal_id' => $request->opening_bal,

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
        $data = Suppliers::find($id);
        return view('supplier.create')
        ->with('data',$data);
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
            'name' => 'required|unique:suppliers,name,'.$id,
            'address' => 'required',
            'phone1' => 'required|min:11|unique:suppliers,phone1,'.$id,
            'phone2' => 'nullable|min:11|unique:suppliers,phone2,'.$id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:150',
            'email' => 'required|unique:suppliers,email,'.$id,
        ]);
        $s = Suppliers::where('id',$id)->first();
        Suppliers::where('id',$id)->update([
            'name' =>  strtolower($request->name),
            'company' =>  strtolower($request->company),
            'email' => $request->email,
            'phone1' => $request->phone1,
            'phone2' => $request->phone2,
            'city' => $request->city,
            'address' => $request->address,
            'opening_bal' => $request->opening_bal
        ]);
        if($s->opening_bal != $request->opening_bal){
            $sp =sup_ledger::where('id',$s->op_bal_id)->update(['dr'=>$request->opening_bal,'date'=>date('Y-m-d H:i:s'),'desc'=>'opening balance']);
        }
        $request->session()->flash('success', 'Supplier Updated Successfuly!');
        return redirect(route('supplier.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $custl = sup_ledger::where('sup_id',$id)->where('type','!=','opbl')->exists();
        // dd($custl);
        if(!$custl){
            suppliers::where('id',$id)->update(['isdeleted'=>1]);
            sup_ledger::where('sup_id',$id)->where('type','opbl')->update(['isdeleted'=>1]);
            Session::flash('success','Supplier Deleted Successfully !');
            return redirect()->back();
        }
        else
        {
            Session::flash('error','This Supplier has ledger it can not be deleted !');
            return redirect()->back();
        }
    }
}
