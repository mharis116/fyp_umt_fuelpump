@extends('layout.master')
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush
@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
@section('content')
@if(auth()->user()->account_type == 'admin') 
    <a href="{{route('user.create')}}">
        <div class="btn-group float-right " role="group" aria-label="Basic example">
            <button type="button" class="btn btn-primary p-0 px-2 text-light">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
            </button>
            <button type="button" class="btn btn-primary px-2 text-light">Add</button>
        </div>
    </a>
@endif
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item" aria-current="page">Users /</li>
    </ol>
</nav>
          
<br>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Fuels</h6>
                <div class="table-responsive">
                    <table id="dataTableExample" class="table text-center">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Account Type</th>
                                <th>Created At</th>
                                <th>Status</th>
                                @if(auth()->user()->account_type == 'admin') 
                                    <th>Function</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $p)
                                <tr>
                                    <td>
                                        <div class="text-center">
                                            <img src="{{asset('storage/prof'.$p->logo)}}" alt=""  style="width: 50px;height:50px;border-radius:50%;">
                                        </div>    
                                    </td>
                                    <td>{{$p->name}}</td>
                                    <td>{{$p->email}}</td>
                                    <td>{{$p->contact}}</td>
                                    <td>{{$p->account_type}}</td>
                                    <td>{{$p->created_at}}</td>
                                    <td>
                                        @if ($p->isactive == 1)

                                            <div class="btn btn-success">Active</div>

                                        @elseif($p->isactive == 0)
                                            
                                            <div class="btn btn-danger">Not Active</div>

                                        @endif
                                    </td>
                            
                                    @if(auth()->user()->account_type == 'admin') 
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-primary text-light float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="{{route('user.edit',$p->id)}}">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                    <span class="ml-2"> Edit</span> </a>
                                                <div class="dropdown-item pointer"  onclick="$('#frm{{$p->id}}').submit()">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                    <span class="ml-2">Delete</span>
                                                </div>
                                                <form action="{{route('user.destroy',$p->id)}}" id="frm{{$p->id}}" method="post">
                                                @csrf
                                                @method('Delete')
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                </div>
            </div>
        </div>
    </div>
 
    
</div>

@endsection