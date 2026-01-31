@extends('layout.master')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('customer.index')}}">Customers</a></li>
        <li class="breadcrumb-item" aria-current="page">
           {{isset($data)?'Update':'Add'}}
        </li>
    </ol>
</nav>
<br>
<div class="card">
    <div class="card-header">
        {{isset($data)?'Update':'Add'}} Customer
    </div>
    <div class="card-body">
        @php
            if(auth()->user()->account_type == 'admin'){
                $up = route('customer.update',isset($data->id)?$data->id:0);
            }else{
                $up = route('eup');
            }
            @endphp
        <form action="{{isset($data)?$up:route('customer.store')}}" method="post">
            @csrf
            @if(isset($data))
                @method('PUT')
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Customer Name:</label>
                        <input type="text" placeholder="Name..." value="{{isset($data->name )?$data->name : old('name')}}" class="form-control" name="name" id="name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" placeholder="abc@gmail.com" value="{{isset($data->email)? $data->email : old('email')}}" class="form-control" name="email" id="email" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone1">Contact:</label>
                        <input type="text" class="form-control" value="{{isset($data->phone1)? $data->phone1: old('phone1')}}" placeholder="03001234567" name="phone1" id="phone1" maxlength="11" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone2">Alternate Contact:</label>
                        <input type="text" class="form-control " value="{{isset($data->phone2)? $data->phone2: old('phone2')}}" placeholder="03007654321" name="phone2" id="phone2" maxlength="11" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="city">City:</label>
                        <input type="text" class="form-control" value="{{isset($data->city)? $data->city : old('city') }}" placeholder="City..." name="city" id="city" maxlength="11" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" class="form-control" value="{{isset($data->address)? $data->address : old('address') }}" placeholder="Address..." name="address" id="address" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="o_b">Opening Balance:</label>
                        <input type="number" class="form-control" value="{{isset($data->opening_bal)? $data->opening_bal : old('opening_bal')}}" placeholder="Rs.00" name="opening_bal" id="o_b" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="credit_limit">Credit Limit:</label>
                        <input type="number" class="form-control" value="{{isset($data->credit_limit)? $data->credit_limit : old('credit_limit')}}" placeholder="Rs.00" name="credit_limit" id="credit_limit">
                    </div>
                </div>
            </div>
            @php
                $data=[
                    'button' => isset($data)? 'Update' : 'Create',
                    'id' => 'sup-up',
                    'color'=>isset($data)? 'info' : 'success',
                    'float' => 'right text-light',
                    'type' => 'info',
                    'desc' => 'Do you realy want to add or Update Suplier !'
                ];
            @endphp
            @include('partials.popup',$data) 
        </form>
    </div>
</div>


@endsection