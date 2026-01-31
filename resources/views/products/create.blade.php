@extends('layout.master')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('products.index')}}">Fuels</a></li>
      <li class="breadcrumb-item" aria-current="page"><a href="#">{{isset($data)? 'Edit':' Add Fuel'}}</a></li>
    </ol>
  </nav>
  <br>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{isset($data)? 'Edit':' Add Fuel'}}</div>
                <div class="card-body">
                    
                        @php
                        if(auth()->user()->account_type == 'admin'){
                            $up = route('products.update',isset($data)?$data->pro_id:0);
                        }else{
                            $up = route('eup');
                        }
                        @endphp
                    <form action="{{isset($data)?$up:route('products.store')}}" method="post">
                        @csrf
                        @if(isset($data))
                            @method('PUT')
                        @endif
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Fuel Name:</label>
                                    <input type="text" value="{{isset($data->name)? $data->name : null}}" placeholder="Fuel Type Name" name="name" id='name' required class="form-control input">
                                </div>
                            </div>
                            <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="aq">Alert Quantity in liters:</label>
                                        <input type="number" value="{{isset($data->alert_qty)? $data->alert_qty : null}}" name="alert_qty" placeholder="00 ltrs" id='aq' required class="form-control input">
                                    </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sku">SKU:</label>
                                    <input type="text" name="sku" value="{{isset($data->sku)? $data->sku : null}}" placeholder=" P-0000" id='sku' required class="form-control input">
                                </div>
                        </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cp">Cost Price/ltr:</label>
                                    <input type="number"  value="{{isset($data->cost_Price)? $data->cost_Price : null}}" placeholder="Rs.00" name="cost_price"  step="0.01" id='cp' required class="form-control input">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rp">Retail Price/ltr:</label>
                                    <input type="number" value="{{isset($data->retail_price)? $data->retail_price : null}}" name="retail_price" placeholder="Rs.00"  step="0.01" id='rp' required  class="form-control input">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="qty">Availabel Quantity in ltrs:</label>
                                    <input type="number"  value="{{isset($data->qty)? $data->qty : null}}" {{isset($data->qty)? 'readonly' : null}}  placeholder="Available quantity in ltrs (Optional)" name="qty" id='qty' class="form-control input">
                                </div>
                            </div> --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="cap">Stock Capacity in ltrs:</label>
                                    <input type="number" value="{{isset($data->stock_capacity)? $data->stock_capacity : null}}"  name="cap" placeholder="Max Stock Capacity in ltrs" id='cap' required  class="form-control input">
                                </div>
                            </div>
                        </div>
                            @php
                                $data=[
                                    'button' => isset($data)? 'Update' : 'Create',
                                    'id' => 'sub-up',
                                    'color'=>isset($data)? 'info' : 'success',
                                    'float' => 'right text-light',
                                    'type' => 'info',
                                    'desc' => 'Do you realy want to add or Update Fuel !'
                                ];
                            @endphp
                            @include('partials.popup',$data)                        
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection