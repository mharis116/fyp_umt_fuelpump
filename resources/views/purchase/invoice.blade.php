@extends('layout.master')

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('purchase.create')}}">Purchases</a></li>
    <li class="breadcrumb-item active" aria-current="page">Invoice</li>
  </ol>
</nav>
<script src="{{asset('js/jquery.print.js')}}"></script>
<script type='text/javascript'>
  jQuery(function($) { 'use strict';
    $(".card-body").find('.print-link').on('click', function() {
      $.print(".card-body");
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
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="container-fluid d-flex justify-content-between">
          <div class="col-lg-3 pl-0">
            <a href="#" class="noble-ui-logo d-block mt-3">{{config('app.name','laravel')}}<span> Petroleum</span></a>                 
            <h5 class="mt-5 mb-2 text-muted">Invoice to :</h5>
            <p>{{$pur->name}},<br> {{$pur->email}},<br> {{$pur->city}}.</p>
            {{-- <br>
            <h5 class="text-muted mb-1">Supplier Credit :</h5>
            <h4 class="font-weight-normal"> Rs</h4> --}}
          </div>
          <div class="col-lg-3 pr-0">
            <h4 class="font-weight-medium text-uppercase text-right mt-4 mb-2">Invoice</h4>
            <h6 class="text-right mb-5 pb-4"># INV-{{$pur->inv_no}}</h6>
            
            <p class="text-right mb-1" style='font-size:25px;'>Balance Due</p>
            <h4 class="text-right font-weight-normal">Rs.{{$led->credit + $led->adj}} </h4>
            <h6 class="mb-0 mt-3 text-right font-weight-normal mb-2"><span class="text-muted">Invoice Date :</span> {{$pur->pdate}}</h6>
            <h6 class="mb-0 mt-3 text-right font-weight-normal mb-2"><span class="text-muted">Date :</span> {{date('Y-m-d H:i:s')}}</h6>
          </div>
        </div>
        <div class="container-fluid mt-5 d-flex justify-content-center w-100">
          <div class="table-responsive w-100">
              <table class="table table-bordered">
                <thead>
                  <tr>
                      <th>#</th>
                      <th>Product</th>
                      <th class="text-right">Quantity</th>
                      <th class="text-right">Unit cost</th>
                      <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                  @php
                    $i = 1;
                  @endphp
                  @foreach ($pii as $pi)
                     <tr class="text-right">
                      <td class="text-left">{{$i}}</td>
                      <td class="text-left">{{$pi->name}}</td>
                      <td>{{$pi->qty}} ltrs</td>
                      <td>{{$pi->cost_price}} Rs</td>
                      <td>{{$pi->sub_total}} Rs</td>
                    </tr>
                    @php
                      $i+=1;
                    @endphp
                  @endforeach
                 
                  
                </tbody>
              </table>
            </div>
        </div>
        <div class="container-fluid mt-5 w-100">
          <div class="row">
            <div class="col-md-6 ml-auto">
                <div class="table-responsive">
                  <table class="table">
                      <tbody>
                        <tr>
                          <td>Sub Total</td>
                          <td class="text-right">{{$pur->cost_amount}} Rs</td>
                        </tr>
                       
                        <tr>
                          <td>Adjustment</td>
                          <td class="text-right">{{!$sl->adjustment?'00':$sl->adjustment}} Rs</td>
                        </tr>
                        <tr class="bg-light">
                          <td class="text-bold-800">Grand Total</td>
                          <td class="text-bold-800 text-right">{{$pur->cost_amount + $pur->adjustment}} Rs</td>
                        </tr>
                         <tr>
                          <td>Cash</td>
                          <td class="text-right">{{!$sl->cr?'00':$sl->cr}} Rs</td>
                        </tr>
                        <tr>
                          <td>Credit</td>
                          @php
                            $tc = $sl->dr + $sl->adjustment;
                          @endphp
                          <td class="text-right">{{$tc? $tc > 0? $tc:'00':'00'}} Rs</td>
                        </tr>
                      </tbody>
                  </table>
                </div>
            </div>
          </div>
        </div>
        <div class="container-fluid w-100">
          <button data-toggle='tooltip' data-placement='top' title='Press Alt + P' class="btn btn-outline-primary float-right print-link no-print mt-4"><i data-feather="printer" class="mr-2 icon-md"></i>Print</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection