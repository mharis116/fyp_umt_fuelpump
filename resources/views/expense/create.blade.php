@extends('layout.master')


@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item" ><a href="{{route('exp.index')}}">Expenses</a></li>
        <li class="breadcrumb-item" aria-current="page">{{isset($data)? 'Update' : 'Create'}} Expenses</li>
    </ol>
</nav>
<br>
<div class="card">
    <div class="card-header">
        {{isset($data)? 'Update' : 'Create'}} Expenses
    </div>
    @php
        if(auth()->user()->account_type == 'admin'){
            $up = route('exp.update',isset($data)?$data->id:0);
        }else{
            $up = route('eup');
        }
    @endphp
    <div class="card-body">
        <form action="{{isset($data)?$up:route('exp.store')}}" method="post">
            @csrf
            @if(isset($data))
                @method('Put')
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Amount:</label>
                        <input type="text" name="amount" required id="name" value="{{isset($data->amount)?$data->amount:old('amount')}}" class="form-control inputa" placeholder="RS.00">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Type:</label>
                        <select name="type" id="type" class="form-control inputa" required>
                            <option value="">--select--</option>
                            @foreach ($exp as $e)
                                <option value="{{$e->id}}"{{isset($data->exp_type_id)?$data->exp_type_id == $e->id ? 'selected':null:null}} >{{$e->name.' - '.$e->type}}</option>
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
                    'desc' => 'Do you realy want to add or Update Expense !'
                ];
            @endphp
            @include('partials.popup',$data) 
        </form>
    </div>
</div>

@endsection