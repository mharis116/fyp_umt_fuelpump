@extends('layout.master')


@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item" ><a href="{{route('exptype.index')}}">Expense Types</a></li>
        <li class="breadcrumb-item" aria-current="page">{{isset($data)? 'Update' : 'Create'}} Type</li>
    </ol>
</nav>
<br>
<div class="card">
    <div class="card-header">
        {{isset($data)? 'Update' : 'Create'}} Expense Type
    </div>
    @php
        if(auth()->user()->account_type == 'admin'){
            $up = route('exptype.update',isset($data)?$data->id:0);
        }else{
            $up = route('eup');
        }
    @endphp
    <div class="card-body">
        <form action="{{isset($data)?$up:route('exptype.store')}}" method="post">
            @csrf
            @if(isset($data))
                @method('Put')
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name" required value="{{isset($data->name)?$data->name:old('name')}}" class="form-control inputa" placeholder="Type Name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Type:</label>
                        <select name="type" id="type" class="form-control inputa" required>
                            <option value="">--select--</option>
                            <option value="Usual" {{isset($data->type)?$data->type == 'Usual'?'selected':null:null}}>Usual</option>
                            <option value="Daily" {{isset($data->type)?$data->type == 'Daily'?'selected':null:null}}>Daily</option>
                            <option value="Weakly" {{isset($data->type)?$data->type == 'Weakly'?'selected':null:null}}>Weakly</option>
                            <option value="Monthly" {{isset($data->type)?$data->type == 'Monthly'?'selected':null:null}}>Monthly</option>
                            <option value="Yearly" {{isset($data->type)?$data->type == 'Yearly'?'selected':null:null}}>Yearly</option>
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
                    'desc' => 'Do you realy want to add or Update Expense Type !'
                ];
            @endphp
            @include('partials.popup',$data) 
        </form>
    </div>
</div>

@endsection