@extends('layout.master')


@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item" ><a href="{{route('dip.index')}}">Dips</a></li>
        <li class="breadcrumb-item" aria-current="page">{{isset($data)? 'Update' : 'Create'}} Dip</li>
    </ol>
</nav>
<br>
<div class="card">
    <div class="card-header">
        {{isset($data)? 'Update' : 'Create'}} Dip
    </div>
    @php
        if(auth()->user()->account_type == 'admin'){
            $up = route('dip.update',isset($data)?$data->id:0);
        }else{
            $up = route('eup');
        }
    @endphp
    <div class="card-body">
        <form action="{{isset($data)?$up:route('dip.store')}}" method="post">
            @csrf
            @if(isset($data))
                @method('Put')
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Quantity in liters:</label>
                        <input type="text" name="qty" id="name" required value="{{isset($data->qty)?$data->qty:old('qty')}}" class="form-control inputa" placeholder="00">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Products:</label>
                        <select name="product" id="type" class="form-control inputa" {{isset($data->pro_id)? 'disabled' : 'required'}}>
                            <option value="">--select--</option>
                            @foreach ($exp as $e)
                                <option value="{{$e->id}}"{{isset($data->pro_id)?$data->pro_id == $e->id ? 'selected':null:null}} >{{$e->name.' - '.$e->sku}}</option>
                            @endforeach    
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Description:</label>
                        <input type="text" name="desc" id="name" value="{{isset($data->desc)?$data->desc:old('desc')}}" class="form-control inputa" placeholder="Description">
                    </div>
                </div>
            </div>
            @php
            $data=[
                    'button' => isset($data)? 'Update' : 'Create',
                    'id' => 'expt',
                    'color'=>isset($data)? 'info' : 'success',
                    'float' => 'right text-light',
                    'type' => 'info',
                    'desc' => 'Do you realy want to add or Update Dip!'
                ];
            @endphp
            @include('partials.popup',$data) 
        </form>
    </div>
</div>

@endsection