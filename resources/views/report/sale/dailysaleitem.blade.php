@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
@endpush 

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Report</a></li>
      <li class="breadcrumb-item active" aria-current="page"><a href="#">Sales</a></li>
      <li class="breadcrumb-item active" aria-current="page"><a href="{{route('report.sale.dailysale')}}">Daily Sales</a></li>
      <li class="breadcrumb-item active" aria-current="page">Daily Sale Items</li>
    </ol>
  </nav>
<script src="{{asset('js/jquery.print.js')}}"></script>
<script>
    jQuery(function($) { 'use strict';
      $('.print-link').on('click', function() {
        $.print(".print");
      });
    });
   
    $(document).ready(function(){
      function alt(event) {
          if (event.altKey) {
              return true;
          } else {
              return false;
          }
      }
      $(document).keydown(function(e){
          if(e.keyCode  == 80 && alt(event) == true){
              $('.print-link').trigger('click');
          }else{
              // 
          }
      });
    });
</script>
  <br>

@php
    $url = strval(Request::url());
    $e = explode("/",$url);
    $dat = end($e);
@endphp

<div class="row print">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <button  data-toggle='tooltip' data-placement='top' title='Press Alt + P'  class="btn btn-outline-primary float-right print-link no-print"><i data-feather="printer" class="mr-2 icon-md"></i>Print</button>
          <h6 class="card-title">Daily Sales Report </h6>
          <h6 class="text-center">{{$dat}} </h6>
          <br>
          <div class="table-responsive">
            <table id="dataTableExample" class="table">
              <thead>
                <tr>
                    <th>Invoice</th>
                    {{-- <th>Date</th> --}}
                    <th>Customer</th>
                    <th>Sale Amount</th>
                    <th>Adjustment</th>
                    <th>Total Sale Amount</th>
                    <th>Quantity</th>
                    <th class="no-print">Action</th>
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
                  @foreach ($sale as $s)
                      <tr class="tr pointer" id="{{$s->name}}">
                            <td>{{$s->invoice_no}}</td>
                            {{-- <td>{{$s->date}}</td> --}}
                            <td>{{$s->name}}</td>
                            <td> Rs.{{$s->rm}} </td>
                            <td> Rs.{{$s->adj?$s->adj:0}} </td>
                            <td> Rs.{{$s->rm + $s->adj}} </td>
                            <td> {{$s->qty}} ltrs </td>
                            <td>
                                <a href="{{route('sale.show',[$s->id,'test=1'])}}">
                                    <div class="btn btn-primary float-right no-print">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentcolor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </div>
                                </a>
                            </td>
                      </tr>
                  @endforeach
              
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>



@endsection
@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush