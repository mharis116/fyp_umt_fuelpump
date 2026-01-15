<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\customers;
use App\cust_ledger;
use Illuminate\Support\Facades\Session;

class customerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Customers::where('isdeleted',0)->get();

        return view('customer.index')->with('data',$data);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer.create');
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
            'name' => 'required|unique:customers',
            'address' => 'required',
            'phone1' => 'required|min:11|unique:customers',
            'phone2' => 'nullable|min:11|unique:customers',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:150',
            'email' => 'required|unique:customers',
        ]);
        $s =customers::create([
            'name' =>  strtolower($request->name),
            'email' => $request->email,
            'phone1' => $request->phone1,
            'phone2' => $request->phone2,
            'city' => $request->city,
            'address' => $request->address,
            'opening_bal' => $request->opening_bal,
            'credit_limit' => $request->credit_limit,
            'date' => date('Y-m-d H:i:s')
        ]);
        if($request->opening_bal != null){
            $sp =cust_ledger::create(['dr'=>$request->opening_bal,'type'=>'opbl','date'=>date('Y-m-d H:i:s'),'desc'=>'opening balance','customer_id'=>$s->id]);
            Customers::where('id',$s->id)->update(['op_bal_id' => $sp->id]);
        }
        $request->session()->flash('success', 'Customer Added Successfuly!');
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
        $data = customers::find($id);
        return view('customer.create')
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
            'name' => 'required|unique:customers,name,'.$id,
            'address' => 'required',
            'phone1' => 'required|min:11|unique:customers,phone1,'.$id,
            'phone2' => 'nullable|min:11|unique:customers,phone2,'.$id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:150',
            'email' => 'required|unique:customers,email,'.$id,
        ]);
        $s = customers::where('id',$id)->first();
        customers::where('id',$id)->update([
            'name' =>  strtolower($request->name),
            'email' => $request->email,
            'phone1' => $request->phone1,
            'phone2' => $request->phone2,
            'city' => $request->city,
            'address' => $request->address,
            'opening_bal' => $request->opening_bal,
            'credit_limit' => $request->credit_limit
        ]);
        if($s->opening_bal != $request->opening_bal){
            $sp =cust_ledger::where('id',$s->op_bal_id)->update(['dr'=>$request->opening_bal,'date'=>date('Y-m-d H:i:s'),'desc'=>'opening balance']);
        }
        $request->session()->flash('success', 'Customer Updated Successfuly!');
        return  redirect(route('customer.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $custl = cust_ledger::where('customer_id',$id)->where('type','!=','opbl')->exists();
        // dd($custl);
        if(!$custl){
            $cust = customers::where('id',$id)->first();
            if($cust->name == 'Walk In Customer'){
                Session::flash('error','Walk In Customer Can not Delete !');
                return redirect()->back();
            }else{
                customers::where('id',$id)->update(['isdeleted'=>1]);
                cust_ledger::where('customer_id',$id)->where('type','opbl')->update(['isdeleted'=>1]);
                Session::flash('success','Customer Deleted Successfully !');
                return redirect()->back();
            }
            
        }
        else
        {
            Session::flash('error','This customer has ledger it can not be deleted !');
            return redirect()->back();
        }
    }
}
