<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{

    public $module_code;
    public $ignored_permission_methods = [];
    public $permission_methods = [];
    public function __construct(){
        $this->inject_permissions();
    }

    public function inject_permissions(){
        $this->module_code = 'profile';
        $this->ignored_permission_methods = [
            "index",
            "update"
        ];
        $this->permission_methods = [
            // 'index' => [
            //     'module_permission_type_code' => 'read',
            // ],
            // 'show' => [
            //     'module_permission_type_code' => 'read',
            // ],
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
        $user = Auth::User();
        return view('profile.index')->with('user',$user);
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
        $user = User::where('id',$id)->first();
         $validated = $request->validate([
            'name' => 'required',
            'contact' => 'required|unique:users,contact,'.$id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:150',
            'email' => 'required|unique:users,email,'.$id,
        ]);
           if(Hash::check($request->opassword, $user->password)){
               $p = 1;
           }else{
               $p = 0;
           }
        $password = isset($request->password)?$request->password:1;
        $conpass = isset($request->cpassword)?$request->cpassword:1;
        if($conpass == $password){

            $user = User::find($id);
            $user->name =  strtolower($request->name);
            $user->email = $request->email;
            $user->contact = $request->contact;
            if(isset($request->password) and $p == 1){
                $user->password = Hash::make($request->password);
            }
            $user->save();
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $format = $request->logo->extension();
                $file = $request->file('logo')->storeAs('prof/user',$user->id.'.'.$format);
                $path = '/user/'.$user->id.'.'.$format;
                User::where('id',$user->id)->update(['logo'=> $path]);
            }
            if($request->password and $request->cpassword and $request->password == $request->cpassword and $p == 0){
                session::flash('error','Old Password is Incorect !');
            }
            Session::flash('success','User Profile Updated Successfully!');
            return redirect(route('profile.index'));
        }else{

            Session::flash('error','Password not Match !');
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
