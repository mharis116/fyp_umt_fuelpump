@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
@endpush
@push('plugin-styles')
  {{-- <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" /> --}}
@endpush 

@section('content')
<script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/moment.min.js')}}"></script>
<link rel="stylesheet" href="{{asset("css/font-awesome.min.css")}}">
<link rel="stylesheet" href="{{asset("css/daterangepicker.css")}}">

    {{-- 
        "id" => 1
        "percentage_full" => 23.33
        "distance_from_fuel_level" => 16.64
        "quantity_in_ltrs" => 1.17
        "created_at" => "2026-05-14 01:18:23"
        "updated_at" => "2026-05-14 01:18:23"
        "fuel_sensor_id" => 1
    --}}

    <div class="row">
        <div class="col-md-6">
            <div class="">
                
                <input class="form-control" type="date" id="start_date" name="start_date" value="{{$_GET['date']??date('Y-m-d')}}">
                <script>
                    $(document).ready(function() {
                        $('#start_date').change(function() {
                            let url = new URL(window.location.href);
                            url.searchParams.set('date', $('#start_date').val());
                            window.location.href = url.toString();
                        });
                    });
                </script>

                {{-- @include("layout.partials.daterange")
                <script>
                    $(document).ready(function() {
                        $('#reportrange #span').change(function() {
                            // var val = $('#span').val();
                            // if (val.length >= 24) {
                            //     $('#form').submit();
                            //     $('.loaded .loader-wrapper').css('visibility', 'inherit')
                            //     $('.loaded .loader-wrapper').css('opacity', '0.7')
                            // }

                            let url = new URL(window.location.href);
                            url.searchParams.set('date_range', $('#span').val());
                            window.location.href = url.toString();
                        });
                    });
                </script> --}}


            </div>
        </div>
    </div>

    <br>
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Fuel Guage</h6>

            <br>
            {{-- Fuel level changes all fuel sensor groupby Apex Chart in jQuery--}}
            <div id="fuelChart"></div>
            <script>
                var options = {
                    chart: {
                        type: 'area',
                        height: 350
                    },
                    series: [
                        @foreach($fuelReadings as $sensorId => $readings)
                            {
                                name: 'Sensor {{ $sensorId }}',
                                data: [
                                    @foreach($readings as $reading)
                                        {  
                                            x: "{{ $reading->created_at }}", // timestamp for x-axis
                                            y: {{ round($reading->quantity_in_ltrs, 2) }}, // liters shown on line
                                            percent: {{ round($reading->percentage_full, 2) }} // custom field
                                        },
                                    @endforeach
                                ]
                            },
                        @endforeach
                    ],
                    xaxis: {
                        type: 'datetime',
                        labels: {
                            datetimeUTC: false,
                            tickAmount: 'dataPoints',
                            format: 'hh:mm TT', // Example: 18 May 2024
                            datetimeFormatter: {
                                year: 'yyyy',
                                month: 'MMM \'yy',
                                day: 'dd MMM',
                                hour: 'hh:mm TT'
                            }
                        }
                    },
                    tooltip: {
                        // format:"hh:mm TT",
                        // x: { format: 'hh:mm TT' },
                        custom: function({series, seriesIndex, dataPointIndex, w}) {

                            let point = w.config.series[seriesIndex].data[dataPointIndex];


                            let date = new Date(point.x);
                            let formattedTime = date.toLocaleString('en-US', {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: true
                            });

                            return `
                                <div style="padding:8px;">
                                    <b>${formattedTime}</b><br>
                                    Liters: ${point.y} Ltrs<br>
                                    Fullness: ${point.percent}%
                                </div>
                            `;
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Percentage Full (%)'
                        }
                    },
                    title: {
                        text: 'Fuel Level Changes Over Time',
                        align: 'center'
                    }
                };

                $(document).ready(function() {
                    // Initialize the chart
                    var chart = new ApexCharts(document.querySelector("#fuelChart"), options);
                    chart.render();
                });
            </script>

        </div>
        <!-- Your fuel guage content here -->
    </div>
@endsection


@push('plugin-scripts')
    {{-- <script src="{{ asset('assets/plugins/chartjs/Chart.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/plugins/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery.flot/jquery.flot.resize.js') }}"></script> --}}
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/plugins/progressbar-js/progressbar.min.js') }}"></script> --}}
@endpush

@push('custom-scripts')
    {{-- <script src="{{ asset('assets/js/dashboard.js') }}"></script> --}}
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
@endpush


{{-- 


--}}