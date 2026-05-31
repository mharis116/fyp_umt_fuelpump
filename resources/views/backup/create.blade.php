@extends('layout.master')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('backup.index')}}">Backup</a></li>
        <li class="breadcrumb-item" aria-current="page">
           Transfer
        </li>
    </ol>
</nav>
<br>
@if(auth()->user()->account_type == 'admin' or auth()->user()->account_type == 'manager') 
    <div class="card">
        <div class="card-header">
            Transfer Backup
        </div>
        <div class="card-body">
            <form action="{{route('backup.update',$dat->fbid)}}" method="post">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Product Name:</label>
                            <input type="text" name="name" id="name" value='{{$dat->name}}' readonly class="form-control input">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Sku:</label>
                            <input type="text" name="sku" id="sku" value='{{$dat->sku}}' readonly class="form-control input">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Truck Tank Capacity:</label>
                            <input type="text" name="stc" id="qty" value='{{$dat->stock_capacity}} ltrs' readonly class="form-control input">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Stock Tank Capacity:</label>
                            <input type="text" name="ttc" id="qty" value='{{$sto->stock_capacity}} ltrs' readonly class="form-control input">
                        </div>
                    </div>
                 </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Availabel Fuel in Backup Truck Tank:</label>
                            <input type="text" name="ttc" id="abtc" value='{{$dat->qty}} ltrs' readonly class="form-control input">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Empty space in Stock Tank:</label>
                            <input type="text" name="ava" id="ava"  value='{{$sto->stock_capacity - $sto->qty}}' readonly class="form-control input">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Transfer:</label>
                            <input type="number" name="transfer" max="{{$dat->qty}}" placeholder="00 ltrs" id="transfer" required autofocus class="form-control input">
                        </div>
                    </div>
                </div>
                @php
                    $data=[
                        'button' => 'Transfer',
                        'id' => 'Transfer',
                        'color'=>'primary',
                        'float' => 'right text-light',
                        'type' => 'info',
                        'desc' => 'Do you realy want to add or Update Suplier 1'
                    ];
                @endphp
                @include('partials.popup',$data) 
            </form>
        </div>
    </div>
@endif

@endsection