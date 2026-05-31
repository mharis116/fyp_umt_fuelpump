@extends('layout.master')

@section('content')
<script src="{{asset('js/jquery.print.js')}}"></script>

<button  data-toggle='tooltip' data-placement='top' title='Press Alt + P'  class="btn btn-outline-primary no-print float-right print-link"><i data-feather="printer" class="mr-2 icon-md"></i>Print</button>

<nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Report</a></li>
      <li class="breadcrumb-item active" aria-current="page">Credit</li>
    </ol>
  </nav>
  <script>
    jQuery(function($) { 'use strict';
      $('.print-link').on('click', function() {
        $.print(".print");
      });
    });
   
    $(document).ready(function(){
        var cust  = {{$cl->credit + $cl->adj}};
        var sup  = {{$sl->credit + $sl->adj}};
        if($('#chartjsBar').length) 
        {
            new Chart($("#chartjsBar"), {
            type: 'doughnut',
            data: {
                labels: [ "Customer", "Supplier"],
                datasets: [
                {
                    label: "Credit Report",
                    backgroundColor: ["#46c35f","#5e50f9"],
                    data: [cust,sup]
                }
                ]
            },
            options: {
                legend: { display: true },
            }
            });
        }
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

<div class="row ">
    <div class="col-md-7 ">
        <div class="card p-4 print">
            <h6 class="card-title">Credit Report</h6>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                Trader
                            </th>
                            <th>
                                Payment
                            </th>
                            <th>
                                Amount
                            </th>
                            <th class="no-print">
                                Color
                            </th>
                            <th class="no-print">
                                Function
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                Customer
                            </td>
                            @php
                                $cc  = $cl->credit + $cl->adj;
                            @endphp
                            <td>
                                {{$cc > 0 ?'Reciveable':'Payable'}}
                            </td>
                            <td>
                                Rs.{{$cc}}
                            </td>
                            <td class="no-print">
                                <div style="width:100%;height:10px;; background-color:#46c35f;"></div>
                            </td>
                            <td class="no-print">
                                <a href="{{route('custledger.index',['test=1','total='.$cc])}}">
                                    <div class="btn btn-primary float-right">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </div>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Supplier
                            </td>
                            @php
                                $sc = $sl->credit + $sl->adj;
                                $test  = 1;
                            @endphp
                            <td>
                                {{$sc > 0 ?'Payable':'Reciveable'}}
                            </td>
                            <td>
                                Rs.{{$sc}} 
                            </td>
                            <td class="no-print">
                                <div style="width:100%;height:10px; background-color:#5e50f9;"></div>
                            </td>
                            <td class="no-print">
                                <a href="{{route('supledger.index',['test=1','total='.$sc])}}">
                                    <div class="btn btn-primary float-right">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </div>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br>
            </div>
        </div>
    </div>
    <div class="col-md-5 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <h6 class="card-title">Bar chart</h6>
              <canvas id="chartjsBar"></canvas>
            </div>
        </div>
    </div>
</div>


@include("ai.report_agent.widget", ['report_identifier'=>'credit'])

@endsection
@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/chartjs/Chart.min.js') }}"></script>
@endpush