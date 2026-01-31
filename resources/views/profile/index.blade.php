@extends('layout.master')


@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item" ><a href="#">Profile</a></li>
        <li class="breadcrumb-item" aria-current="page">Update User Profile</li>
    </ol>
</nav>
<br>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Update User Profile
                </div>
                <div class="card-body">
                    <form action="{{route('profile.update',$user->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('Put')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" name="name" id="name"  value="{{ucfirst($user->name)}}" class="form-control inputa" placeholder="Name" required autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" name="email" id="email" value="{{$user->email}}" class="form-control inputa" placeholder="abc@gmail.com" required autofocus>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact">Contact:</label>
                                    <input type="text" name="contact" id="contact" maxlength="11" value="{{$user->contact}}" class="form-control inputa" placeholder="03000000000" required autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-{{isset($dat)? '6':'12'}}">
                                <div class="form-group">
                                    <label for="logo">Profile Pic:</label>
                                    <input type="file" name="logo" id="logo" value="{{old('logo')}}" class="form-control inputa" accept=".jpg,.png,.jpeg" autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="opassword">Old Password:</label>
                                    <input type="password" name="opassword" id="opassword" class="form-control inputa" minlength="8" placeholder="*********"  >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password">New Password:</label>
                                    <input type="password" name="password" id="password" class="form-control inputa" minlength="8" placeholder="*********"  >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cpassword">Confirm Password:</label>
                                    <input type="password" name="cpassword" id="cpassword" class="form-control inputa" minlength="8" placeholder="*********"  >
                                </div>
                            </div>
                        </div>
                        @php
                        $data=[
                                'button' => 'Update',
                                'id' => 'exptu',
                                'color'=>'success',
                                'float' => 'right text-light',
                                'type' => 'info',
                                'desc' => 'Do you realy want to  Update Your Profile !'
                            ];
                        @endphp
                        @include('partials.popup',$data) 
                    </form>
                </div>
            </div>
        </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Profile Pic</h6>
                        <div class="text-center">
                            <img src="{{asset('storage/prof'.$user->logo)}}" alt=""  style="width: 150px;height:150px;border-radius:50%;">
                        </div>
                        <br>
                    </div>
                </div>
            </div>
    </div>
@endsection