@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
@endpush 

@section('content')
<script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/moment.min.js')}}"></script>
<link rel="stylesheet" href="{{asset("css/font-awesome.min.css")}}">
<link rel="stylesheet" href="{{asset("css/daterangepicker.css")}}">


<nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Report</a></li>
      <li class="breadcrumb-item active" aria-current="page">Prices</li>
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
@include("ai.report_agent.widget", ['report_identifier'=>$report_identifier, 'filters'=>$filters??''])
<div class="row">
    <div class="col-md-12">
        <form id="form" action="{{route('report.pricefilter')}}" method="post">
            @csrf
            <div class="card p-4">
                <h6 class="card-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#727cf5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                </h6>
                <div class="row">
                    <div class="col-md-4">
                        <script type="text/javascript">
                            $(function() {
                                var start = moment().subtract( 'days');
                                var end = moment();
                                function cb(start, end) {
                                    $('#reportrange #span').val(start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                                }
                                $('#reportrange').daterangepicker({
                                    startDate: start,
                                    endDate: end,
                                    ranges: {
                                        'Today': [moment(), moment()],
                                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                                        'This Year': [moment().subtract(0, 'year').startOf('year'), moment().subtract(0, 'year').endOf('year')],
                                        'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                                    }
                                }, cb);
                                $('#span').click(function(){
                                    $('#span').val(null);
                                });
                                $('.ranges ul li').click(function(){
                                    if($(this).attr('data-range-key') == 'Custom Range'){

                                    }else{
                                        $('#span').focus();
                                    }
                                });
                                $('.applyBtn').click(function(){
                                    $('#span').focus();
                                });
                                $('#span').focusout(function(){
                                    var val = $('#span').val();
                                    if (val.length >= 24){
                                        $('#form').submit();
                                        $('.loaded .loader-wrapper').css('visibility','inherit')
                                        $('.loaded .loader-wrapper').css('opacity','0.7')
                                    }
                                });
                                $('.clear').click(function(){
                                    var val = $('#span').val();
                                    if (val.length >= 24){
                                        $('#reportrange').append('<input type="hidden" name="clear" value="clear">');
                                        $('#form').submit();
                                        $('.loaded .loader-wrapper').css('visibility','inherit')
                                        $('.loaded .loader-wrapper').css('opacity','0.7')
                                    }
                                    $('#span').val(null);
                                    $('#span').focus();
                                });
                            });
                        </script>
                        <div id="reportrange" class="item-group" style="display:flex;">
                            <input type="text" placeholder='YYYY-MM-DD to YYYY-MM-DD' name="date" style="width:100%;" class="cal form-control" value='{{isset($from,$to)? $from != '' ?$from.' to '.$to  : '': ''  }}'  autocomplete="off" id="span">
                            <div  class="btn btn-primary clear">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </form>
    </div>
</div>
<br>
<div class="row print">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <button  data-toggle='tooltip' data-placement='top' title='Press Alt + P'  class="btn btn-outline-primary float-right print-link no-print"><i data-feather="printer" class="mr-2 icon-md"></i>Print</button>
        <h6 class="card-title">Fuel Price Report</h6>
        <br>
        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Cost Price</th>
                    <th>Retail Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($price as $s)
                    <tr>
                        <td>{{$s->date}}</td>
                        <td> {{$s->name}} </td>
                        <td> Rs.{{$s->cost_price}} </td>
                        <td> Rs.{{$s->retail_price}} </td>
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
@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
@endpush

@push('custom-scripts')
  {{-- <script src="{{ asset('assets/js/apexcharts.js') }}"></script> --}}
@endpush