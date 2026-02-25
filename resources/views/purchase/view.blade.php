@extends('layout.master')
@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush
@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item" aria-current="page">Purchases /</li>
    </ol>
  </nav>
  
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h6 class="card-title">Purchases Management</h6>
          <div class="table-responsive">
            <table id="dataTableExample" class="table">
              <thead>
                <tr>
                  <th>Invoice No</th>
                  <th>Supplier</th>
                  <th>Date</th>
                  <th>Fuel Quantity</th>
                {{-- <th>SubTotal</th>
                <th>Total</th>
                <th>Cash</th>
                <th>Credit</th>
                <th>Adjustment</th> --}}
                  <th>Purchase Type</th>
                  <th>Action</th>
                </tr>
              </thead>
              <script>
                $(document).ready(function(){
                  $('.tr').on('dblclick',function(){
                      $('input[type=search]').val($(this).attr('id'));
                      $('input[type=search]').keyup();
                      $('input[type=search]').focus();
                      $('input[type=search]').select();
                  });
                });
            </script>
              <tbody>
                @php
                  $i=1;
                @endphp
                @foreach($sell as $se)
                    <tr class="tr pointer" id="{{$se->name}}"> 
                      <td>{{$se->inv_no}}</td>
                        <td>
                            {{$se->name}}
                        </td>
                        <td>{{$se->date}}</td>
                        <td><span data-toggle="tooltip" data-placement="bottom" id='qty{{$i}}' onmouseover="toWords({{$se->total_qty}},'qty{{$i}}')" >{{$se->total_qty}} ltrs</span></td>
                        {{-- <td><span data-toggle="tooltip" data-placement="bottom" id='st{{$i}}' onmouseover="toWords({{$se->cost_amount}},'st{{$i}}')" >{{$se->cost_amount}} Rs</span></td>
                        <td><span data-toggle="tooltip" data-placement="bottom" id='st{{$i}}' onmouseover="toWords({{$se->cost_amount + $se->adjustment}},'st{{$i}}')" >{{$se->cost_amount + $se->adjustment}} Rs</span></td>
                        <td><span data-toggle="tooltip" data-placement="bottom" id='cr{{$i}}' onmouseover="toWords({{$se->cr}},'cr{{$i}}')" >{{!$se->cr?'00':$se->cr}} Rs</span></td>
                        <td><span data-toggle="tooltip" data-placement="bottom" id='dr{{$i}}' onmouseover="toWords({{$se->dr > 0 ? $se->dr + $se->adjustment : '00'}},'dr{{$i}}')" >{{$se->dr?$se->dr > 0 ? $se->dr + $se->adjustment : '00':'00'}} Rs</span></td>
                        <td>{{!$se->adjustment?'00':$se->adjustment}} Rs</td> --}}
                        <td>{{$se->pur_type}}</td>
                        
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary text-light float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{route('purchase.show',$se->pid)}}">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text link-icon"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                            <span class="ml-2"> Invoice </span> 
                                          </a>
                                          @if(auth()->user()->account_type == 'admin') 
                                              <span onclick="$('#delete{{$se->pid}}').submit()" class="dropdown-item pointer">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                <span class="ml-2"> Delete</span> 
                                            </span>
                                            <form action="{{route('purchase.destroy',$se->pid)}}" id="delete{{$se->pid}}" method="post">
                                            @csrf
                                            @method('Delete')
                                            </form>
                                          @endif
                                    </div>
                                </div>
                            </td>
                        
                    </tr>
                    @php
                      $i+=1;
                    @endphp
                @endforeach
 
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection