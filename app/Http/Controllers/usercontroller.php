<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class usercontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::where('isdeleted',0)->where('account_type','!=','admin')->get();
        return view('user.index')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create');
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
            'name' => 'required',
            'contact' => 'required|unique:users',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:150',
            'email' => 'required|unique:users',
        ]);

        $password = $request->password;
        $conpass = $request->cpassword;
        if($conpass == $password){
            
            $user = User::create([
                'name' =>  strtolower($request->name),
                'email' => $request->email,
                'contact' => $request->contact,
                'password' => Hash::make($request->password),
                'account_type' => $request->acc_type,
                'isactive' => 1,
            ]);
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $format = $request->logo->extension();
                $file = $request->file('logo')->storeAs('prof/user',$user->id.'.'.$format);
                $path = '/user/'.$user->id.'.'.$format;
            }else{
                $path = '/place/1.png';
            }
            User::where('id',$user->id)->update(['logo'=> $path]);
            Session::flash('success','User Created Successfully!');
            return redirect(route('user.index'));
        }else{
            Session::flash('error','Password Not Match');
            return redirect()->back();
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
        $data = User::where('id',$id)->first();
        return view('user.create')->with('dat',$data);
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
            'name' => 'required',
            'contact' => 'required|unique:users,contact,'.$id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:150',
            'email' => 'required|unique:users,email,'.$id,
        ]);

        $password = isset($request->password)?$request->password:1;
        $conpass = isset($request->cpassword)?$request->cpassword:1;
        if($conpass == $password){
            
            $user = User::find($id);
            $user->name =  strtolower($request->name);
            $user->email = $request->email;
            $user->isactive = $request->status;
            $user->contact = $request->contact;
            if(isset($request->password)){
                $user->password = Hash::make($request->password);
            }
            $user->account_type = $request->acc_type;
            $user->save();
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $format = $request->logo->extension();
                $file = $request->file('logo')->storeAs('prof/user',$user->id.'.'.$format);
                $path = '/user/'.$user->id.'.'.$format;
                User::where('id',$user->id)->update(['logo'=> $path]);
            }
            Session::flash('success','User Updated Successfully!');
            return redirect(route('user.index'));
        }else{
            Session::flash('error','Password Not Match');
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
        User::where('id',$id)->update(['isdeleted'=> 1,'isactive'=>0]);
        Session::flash('warning','User Deleted Successfully!');
        return redirect()->back();
    }
}
