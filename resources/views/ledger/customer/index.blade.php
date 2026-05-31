@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')
@if (Request::get('test') != 1)
  <a href="{{route('ctra.create')}}">
    <div class="btn-group float-right " role="group" aria-label="Basic example">
        <button type="button" class="btn btn-primary p-0 px-2 text-light">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
        </button>
        <button type="button" class="btn btn-primary px-2 text-light">Add Payment</button>
      </div>
  </a>
@endif
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">{{Request::get('test') == 1 ? 'Reports':'Ledgers'}}</li>
    <li class="breadcrumb-item active" aria-current="page"><a href="{{Request::get('test') == 1 ? route('report.credit'):route('custledger.index')}}">{{Request::get('test') == 1 ?'Credit Report':'Customers'}}</a></li>
    @if (Request::get('test') == 1)
    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('custledger.index','test=1')}}">Customers Credit Info</a></li>
    @endif
  </ol>
</nav>
<br>
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
<div class="row print">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <button  data-toggle='tooltip' data-placement='top' title='Press Alt + P'  class="btn btn-outline-primary float-right print-link no-print"><i data-feather="printer" class="mr-2 icon-md"></i>Print</button>
        <h6 class="card-title">{{Request::get('test') != 1?'Customers Ledgers':'Customer Credit info'}}</h6>
        <br>
        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
              <tr>
                <th>Name</th>
                @if (Request::get('test') != 1)
                  <th>Amount</th>
                  <th>Adjustment</th>
                  <th>Total Amount</th>
                  <th>Cash</th>
                @endif
                <th>Credit</th>
                @if (Request::get('test') != 1)
                  <th class="no-print">Function</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @php
                $i = 1;
              @endphp
                @foreach($data as $d)
                  @php
                  $t = $d->cash+$d->credit;                  
                  $st = $t + $d->adj;                  
                  $dr = $d->credit + $d->adj;                  
                  @endphp
                  @if ($d->cust_name != 'Walk In Customer')
                    <tr class="tr pointer" id="{{$d->cust_name}}">
                      <td>{{$d->cust_name}}</td>
                      @if (Request::get('test') != 1)
                        <td><span data-toggle="tooltip" data-placement="bottom" id='rtm{{$i}}' onmouseover="toWords({{$t}},'rtm{{$i}}')" >Rs.{{$t?$t:0}}</span></td>
                        <td>{{$d->ad?$d->adj:0 }} Rs</td>
                        <td><span data-toggle="tooltip" data-placement="bottom" id='rtm{{$i}}' onmouseover="toWords({{$st}},'rtm{{$i}}')" >Rs.{{$st?$st:0}}</span></td>
                        <td><span data-toggle="tooltip" data-placement="bottom" id='cr{{$i}}' onmouseover="toWords({{$d->cash}},'cr{{$i}}')" >Rs.{{$d->cash?$d->cash:0}}</span></td>
                      @endif
                      <td><span data-toggle="tooltip" data-placement="bottom" id='dr{{$i}}' onmouseover="toWords({{$dr}},'dr{{$i}}')" >Rs.  <span class="span">{{$dr?$dr:0}}</span> </span></td>
                      @if (Request::get('test') != 1)
                      <td class="no-print">
                        <a href="{{route('custledger.show',$d->id)}}">
                          <div class="btn btn-primary float-right">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                          </div>
                        </a>
                      </td>
                      @endif
                    </tr>
                  @endif
                  @php
                    $i+=1;
                  @endphp
                @endforeach
            </tbody>
            @if (Request::get('test') == 1)
              <tr>
                <th></th>
                <th></th>
              </tr>
              <tr>
                <th style="font-size: 20px;">
                  Total
                </th>
                <script>
                  $(document).ready(function(){
                    function sumv(){
                        var i = 0;
                        $('.span').each(function(){
                          i+= parseInt($(this).html());
                        });
                        $('#dt').html(i);
                      }
                      sumv();
                    $('.tr').on('click',function(){
                      sumv();
                    });
                    $(document).mouseover(function(){
                      sumv();
                    });
                    $(document).keydown(function(){
                      sumv();
                    });
                    $('.tr').on('dblclick',function(){
                        $('input[type=search]').val($(this).attr('id'));
                        $('input[type=search]').keyup();
                        $('input[type=search]').focus();
                        $('input[type=search]').select();
                        sumv();
                    });
                  });
                </script>
                <th class="text-danger" style="font-size: 20px;">
                  Rs. <span id="dt"></span>
                </th>
              </tr>
              <tr>
                <th></th>
                <th></th>
              </tr>
            @endif
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