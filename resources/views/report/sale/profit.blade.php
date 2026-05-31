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
      <li class="breadcrumb-item active" aria-current="page"><a href="#">Sales</a></li>
      <li class="breadcrumb-item active" aria-current="page">Daily Sales</li>
    </ol>
  </nav>
<script src="{{asset('js/jquery.print.js')}}"></script>
<script>
    jQuery(function($) { 'use strict';
      $('.print-link').on('click', function() {
        $.print(".print");
      });
    });
    jQuery(function($) { 'use strict';
      $('.print-link2').on('click', function() {
        $.print(".print2");
      });
    });
</script>
<br>
@include("ai.report_agent.widget", ['report_identifier'=>$report_identifier, 'filters'=>$filters??''])

<div class="row print">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <button  data-toggle='tooltip' data-placement='top' title='Press Alt + P'  class="btn btn-outline-primary float-right print-link no-print"><i data-feather="printer" class="mr-2 icon-md"></i>Print</button>
        <h6 class="card-title">Whole Business Profit & Loss Report</h6>
        <br>
        <div class="table-responsive">
          <table class="table table-hover">
            <tr>
                <th> Invest Amount </th>
                <td> Rs.{{$ctm}} </td>
            </tr>
            <tr>
                <th>Reatial Amount</th>
                <td> Rs.{{$rtm}} </td>
            </tr>
            <tr>
                <th>Expenses</th>
                <td> Rs.{{$exp}} </td></tr>
            <tr>
                <th> Groce Profit</th>
                <td> Rs.{{$gp < 0?0:$gp}} <span class="float-right"> {{$gp < 0?'0.00':$gpp = round($rtm <= 0?0:($gp/$rtm)*100,2)}} %</span> </td>
            </tr>
            <tr>
                <th> Groce Loss </th>
                <td> Rs.{{$gp < 0?$gp*-1:0}}  <span class="float-right"> {{$gp > 0?'0.00':$glp = round($rtm <= 0?0:($gp*-1/$rtm)*100,2)}} %</span></td>
            </tr>
            <tr>
                <th> Net Profit </th>
                <td> Rs.{{$np<0?0:$np}} <span class="float-right"> {{$np < 0?'0.00':$npp = round($rtm <= 0?0:($np/$rtm*100),2)}} %</span></td>
            </tr>
            <tr>
                <th> Net Loss </th>
                <td> Rs.{{$np<0?$np*-1:0}}  <span class="float-right"> {{$np > 0?'0.00':$nlp = round($rtm <= 0?0:($np*-1/$rtm*100),2)}} %</span></td>
            </tr>

          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="card p-4">
            <h6 class="card-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#727cf5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
            </h6>
            <form id="form" action="{{route('report.sale.profitfilter')}}" method="post">
            @csrf
                <div class="row">
                    <div class="col-md-4 no-print">
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
            </form>
            {{-- <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="" class="table">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Invest Amount</th>
                                <th>Retail Amount</th>
                                <th>Expenses</th>
                                <th>Groce Profit</th>
                                <th>Groce Loss</th>
                                <th>Net Profit</th>
                                <th>Net Loss</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Tiger Nixon</td>
                                <td>System Architect</td>
                                <td>Edinburgh</td>
                                <td>61</td>
                                <td>2011/04/25</td>
                                <td>$320,800</td>
                                <td>2011/04/25</td>
                                <td>$320,800</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
<br>
<div class="row">
    <script>
        $(function() {
        'use strict';
            var g = [];
            var date = [];
            @foreach ($salemy as $sale)
            @php
                $rtm = $sale->rtm + $sale->adj;
                $p = $rtm - $sale->ctm;
            @endphp
                var gp  = '{{$p}}';
                // var np  = '{{$p < 0?$p*-1:0}}';
                g.push(gp);
                // n.push(np);
                date.push('{{$sale->date}}');
            @endforeach
            var options = {
                chart: {
                type: "area",
                height: 300,
                parentHeightOffset: 0
                },
                colors: ["#727cf5",'blue'],
                stroke: {
                curve: "smooth",
                width: 3
                },
                dataLabels: {
                enabled: false
                },
                series: [{
                    name: 'Groce Profit',
                    data: g
                }],
                markers: {
                size: 0,
                strokeColor: "#fff",
                strokeWidth: 3,
                strokeOpacity: 1,
                fillOpacity: 1,
                hover: {
                    size: 6
                }
                },
                xaxis: {
                    type: 'category',
                    categories:date
                },
                yaxis: {
                    labels: {
                        formatter: function (value) {
                        return value + " Rs";
                        }
                    }
                },
                grid: {
                borderColor: "rgba(77, 138, 240, .1)"
                },
                tooltip: {
                x: {
                    format: "dd MMM yyyy"
                },
                },
                legend: {
                position: 'top',
                horizontalAlign: 'left'
                }
            };

            var chart = new ApexCharts(document.querySelector("#apexArea"), options);

            chart.render();
            // ---------------------------------

           
     });
    </script>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Date Wise Groce Profit & Loss Report</h6>
                <div id="apexArea"></div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="row print2">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <button  data-toggle='tooltip' data-placement='top' title='Press Alt + P'  class="btn btn-outline-primary float-right print-link2 no-print"><i data-feather="printer" class="mr-2 icon-md"></i>Print</button>
                <h6 class="card-title">Date Wise Groce Profit & Net Profit</h6>
                <br>
              <div class="table-responsive">
                <table id="dataTableExample" class="table">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Invest Amount</th>
                      <th>Retail Amount</th>
                      <th>Adjustment</th>
                      <th>Groce Profit</th>
                      <th>Groce Profit %</th>
                    </tr>
                  </thead>
                  <tbody>
                        @foreach ($salemy as $st)
                            @php
                                $gp = (($st->rtm+($st->adj?$st->adj:0))-$st->ctm)??0
                            @endphp
                            <tr>
                                <td>{{$st->date}}</td>
                                <td>Rs.{{$st->ctm}}</td>
                                <td>Rs.{{$st->rtm}}</td>
                                <td>Rs.{{$st->adj?$st->adj:0}}</td>
                                <td>Rs.{{$gp}}</td>
                                <td>{{$st->rtm>0?round((($gp)/$st->rtm) *100,2):0}} %</td>
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