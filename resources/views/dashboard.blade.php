@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <div class="input-group date datepicker dashboard-date mr-2 mb-2 mb-md-0 d-md-none d-xl-flex" id="dashboardDate">
      <span class="input-group-addon bg-transparent"><i data-feather="calendar" class=" text-primary"></i></span>
      <input type="text" class="form-control">
    </div>
    {{-- <button type="button" class="btn btn-outline-info btn-icon-text mr-2 d-none d-md-block">
      <i class="btn-icon-prepend" data-feather="download"></i>
      Import
    </button>
    <button type="button" class="btn btn-outline-primary btn-icon-text mr-2 mb-2 mb-md-0">
      <i class="btn-icon-prepend" data-feather="printer"></i>
      Print
    </button>
    <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
      <i class="btn-icon-prepend" data-feather="download-cloud"></i>
      Download Report
    </button> --}}
  </div>
</div>

{{-- <div class="row">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="row flex-grow">
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">New Customers</h6>
              <div class="dropdown mb-2">
                <button class="btn p-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="printer" class="icon-sm mr-2"></i> <span class="">Print</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h3 class="mb-2">3,897</h3>
                <div class="d-flex align-items-baseline">
                  <p class="text-success">
                    <span>+3.3%</span>
                    <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                  </p>
                </div>
              </div>
              <div class="col-6 col-md-12 col-xl-7">
                <div id="apexChart1" class="mt-md-3 mt-xl-0"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">New Orders</h6>
              <div class="dropdown mb-2">
                <button class="btn p-0" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="printer" class="icon-sm mr-2"></i> <span class="">Print</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h3 class="mb-2">39,500,084</h3>
                <div class="d-flex align-items-baseline">
                  <p class="text-danger">
                    <span>-2.8%</span>
                    <i data-feather="arrow-down" class="icon-sm mb-1"></i>
                  </p>
                </div>
              </div>
              <div class="col-6 col-md-12 col-xl-7">
                <div id="apexChart2" class="mt-md-3 mt-xl-0"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">Growth</h6>
              <div class="dropdown mb-2">
                <button class="btn p-0" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="printer" class="icon-sm mr-2"></i> <span class="">Print</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h3 class="mb-2">89.87%</h3>
                <div class="d-flex align-items-baseline">
                  <p class="text-success">
                    <span>+2.8%</span>
                    <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                  </p>
                </div>
              </div>
              <div class="col-6 col-md-12 col-xl-7">
                <div id="apexChart3" class="mt-md-3 mt-xl-0"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> --}}

<div class="row">
  <script>
    var percent = [];
    var fuel = [];
    @foreach($stock as $stt)
    @php
        $qtt = $stt->qty;
        $capp = $stt->stock_capacity;
        $pertt = $qtt/$capp;
    @endphp

      var per = {{round($pertt*100,2)}};
      percent.push(per);
      fuel.push('{{$stt->name}}');
    @endforeach
    $(function() {
      'use strict';
      var optionsr = {
        chart: {
          height: 300,
          type: "radialBar",
          parentHeightOffset: 0
        },
        colors: ["#f77eb9", "#7ee5e5","#4d8af0","#fbbc06","#f77eb9", "#7ee5e5","#4d8af0","#fbbc06"],
        grid: {
          borderColor: "rgba(77, 138, 240, .1)",
          padding: {
            top: 5
          }
        },
        plotOptions: {
          radialBar: {
            dataLabels: {
              total: {
                show: true,
                label: 'TOTAL'
              }
            }
          }
        },
        series: percent,
        labels: fuel
      };
      var chartr = new ApexCharts(document.querySelector("#apexRadialBarr"), optionsr);

      chartr.render();

      var chartAreaBounds = chart.w.globals.dom.baseEl.querySelector('.apexcharts-inner').getBoundingClientRect();
    });
  </script>
  <div class="col-md-6 stretch-card">
    <div class="row flex-grow">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">Fuel Guage</h6>
            </div>
            <br>
            @foreach($stock as $st)
            @php
                $qt = $st->qty;
                $cap = $st->stock_capacity;
                $pert = $qt/$cap;
                $per = round($pert*100,2);
                if($per <= 50 and $per > 40){
                    $color = 'info';
                    }elseif($per <= 40 and $per > 15){
                    $color = 'warning';
                    }elseif($per <= 15){
                    $color = 'danger';
                    }else{
                    $color = 'success';
                    }
            @endphp
            <div class="form-group my-2">
                <label for="">{{$st->name}}:</label>
                <div class="progress">
                    <div class="progress-bar-striped progress-bar-animated bg-{{$color}} text-center text-light" role="progressbar" style="width:{{$per}}%;padding-top:10px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-placement="top" title="{{$per}}%"><span style='position:absolute;'>{{$per}}%</span></div>
                </div>
                <label for="" >{{$qt}} ltrs</label>
                <label for="" class="float-right">{{$cap}} ltrs</label>
            </div>
            <br>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6 stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Fuel Guage</h6>
        <div id="apexRadialBarr"></div>
      </div>
    </div>
  </div>
</div>
<br>
<div class="row">
  <script>
    var dp = [];
    var qp = [];
    var rp = [];
    @foreach ($sale as $sr)
        dp.push('{{date_format(date_create($sr->date),"Y-m-d")}}');
        qp.push({{$sr->qty}});
        rp.push({{$sr->rm + $sr->adj}});
    @endforeach
    $(function() {
        'use strict';
        // Apex Line chart start
        var optiona = {
            chart: {
            type: "area",
            height: 300,
            parentHeightOffset: 0,

            },
            colors: ["#727cf5"],
            stroke: {
            curve: "smooth",
            width: 3
            },
            dataLabels: {
            enabled: false
            },
            series: [{
            name: 'Sale Quantity',
            data: qp
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
                type: "category",
                categories: dp
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                    return value + " ltrs";
                    }
                },
            },
            grid: {
            borderColor: "rgba(77, 138, 240, .1)"
            },
            legend: {
            position: 'top',
            horizontalAlign: 'left'
            }
        };
        var chart = new ApexCharts(document.querySelector("#apexAreat"), optiona);
        chart.render();

        var optionz = {
            chart: {
            type: "area",
            height: 300,
            parentHeightOffset: 0,

            },
            colors: ["#727cf5"],
            stroke: {
            curve: "smooth",
            width: 3
            },
            dataLabels: {
            enabled: false
            },
            series: [{
            name: 'Sale Cash',
            data: rp
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
                type: "category",
                categories: dp
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                    return value + " Rs";
                    }
                },
            },
            grid: {
            borderColor: "rgba(77, 138, 240, .1)"
            },
            legend: {
            position: 'top',
            horizontalAlign: 'left'
            }
        };
        var chartrm = new ApexCharts(document.querySelector("#apexArearm"), optionz);
        chartrm.render();
    });
</script>
  <div class="col-xl-6 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h6 class="card-title">Daily Sale Cash</h6>
          <div id="apexArearm"></div>
        </div>
      </div>
  </div>
  <div class="col-xl-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Daily Fuel Sale</h6>
        <div id="apexAreat"></div>
      </div>
    </div>
  </div>
</div>
<br>
<div class="row">
  <script>
      var d = [];
      var r = [];
      @foreach ($exp as $sr)
          d.push('{{$sr->date}}');
          r.push({{$sr->exp}});
      @endforeach
      $(function() {
          'use strict';
          // Apex Line chart start
          var optione = {
              chart: {
              type: "area",
              height: 300,
              parentHeightOffset: 0,

              },
              colors: ["#727cf5"],
              stroke: {
              curve: "smooth",
              width: 3
              },
              dataLabels: {
              enabled: false
              },
              series: [{
              name: 'Amount',
              data: r
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
                  type: "category",
                  categories: d
              },
              yaxis: {
                  labels: {
                      formatter: function (value) {
                      return value + " Rs";
                      }
                  },
              },
              grid: {
              borderColor: "rgba(77, 138, 240, .1)"
              },
              legend: {
              position: 'top',
              horizontalAlign: 'left'
              }
          };
          var charte = new ApexCharts(document.querySelector("#apexAreaexp"), optione);
          charte.render();
      });
  </script>
  <div class="col-xl-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Monthly Expense Report</h6>
        <div id="apexAreaexp"></div>
      </div>
    </div>
  </div>
</div>
<br>
<div class="row">
    <script>
        $(function() {
        'use strict';
            var g = [];
            // var n = [];
            var date = [];
            @foreach ($salemy as $sale)
            @php
                $rtm = $sale->rtm + $sale->adj;
                $p = $rtm - $sale->ctm;
            @endphp
                var gp  = '{{$p}}';
                g.push(gp);
                date.push('{{$sale->date}}');
            @endforeach
            var options = {
                chart: {
                type: "area",
                height: 300,
                parentHeightOffset: 0
                },
                colors: ["#727cf5"],
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

            var chart = new ApexCharts(document.querySelector("#apexArea1"), options);

            chart.render();
     });
    </script>
    <div class="col-xl-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Date Wise Groce Profit & Loss Report</h6>
                <div id="apexArea1"></div>
            </div>
        </div>
    </div>
</div>
{{-- <div class="row">
  <div class="col-12 col-xl-12 grid-margin stretch-card">
    <div class="card overflow-hidden">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-baseline mb-4 mb-md-3">
          <h6 class="card-title mb-0">Revenue</h6>
          <div class="dropdown">
            <button class="btn p-0" type="button" id="dropdownMenuButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="printer" class="icon-sm mr-2"></i> <span class="">Print</span></a>
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
            </div>
          </div>
        </div>
        <div class="row align-items-start mb-2">
          <div class="col-md-7">
            <p class="text-muted tx-13 mb-3 mb-md-0">Revenue is the income that a business has from its normal business activities, usually from the sale of goods and services to customers.</p>
          </div>
          <div class="col-md-5 d-flex justify-content-md-end">
            <div class="btn-group mb-3 mb-md-0" role="group" aria-label="Basic example">
              <button type="button" class="btn btn-outline-primary">Today</button>
              <button type="button" class="btn btn-outline-primary d-none d-md-block">Week</button>
              <button type="button" class="btn btn-primary">Month</button>
              <button type="button" class="btn btn-outline-primary">Year</button>
            </div>
          </div>
        </div>
        <div class="flot-wrapper">
          <div id="flotChart1" class="flot-chart"></div>
        </div>
      </div>
    </div>
  </div>
</div> <!-- row --> --}}

{{-- <div class="row">
  <div class="col-lg-7 col-xl-8 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-baseline mb-2">
          <h6 class="card-title mb-0">Monthly sales</h6>
          <div class="dropdown mb-2">
            <button class="btn p-0" type="button" id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton4">
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="printer" class="icon-sm mr-2"></i> <span class="">Print</span></a>
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
            </div>
          </div>
        </div>
        <p class="text-muted mb-4">Sales are activities related to selling or the number of goods or services sold in a given time period.</p>
        <div class="monthly-sales-chart-wrapper">
          <canvas id="monthly-sales-chart"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-5 col-xl-4 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-baseline mb-2">
          <h6 class="card-title mb-0">Cloud storage</h6>
          <div class="dropdown mb-2">
            <button class="btn p-0" type="button" id="dropdownMenuButton5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton5">
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="printer" class="icon-sm mr-2"></i> <span class="">Print</span></a>
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
            </div>
          </div>
        </div>
        <div id="progressbar1" class="mx-auto"></div>
        <div class="row mt-4 mb-3">
          <div class="col-6 d-flex justify-content-end">
            <div>
              <label class="d-flex align-items-center justify-content-end tx-10 text-uppercase font-weight-medium">Total storage <span class="p-1 ml-1 rounded-circle bg-primary-muted"></span></label>
              <h5 class="font-weight-bold mb-0 text-right">8TB</h5>
            </div>
          </div>
          <div class="col-6">
            <div>
              <label class="d-flex align-items-center tx-10 text-uppercase font-weight-medium"><span class="p-1 mr-1 rounded-circle bg-primary"></span> Used storage</label>
              <h5 class="font-weight-bold mb-0">6TB</h5>
            </div>
          </div>
        </div>
        <button class="btn btn-primary btn-block">Upgrade storage</button>
      </div>
    </div>
  </div>
</div> <!-- row --> --}}

{{-- <div class="row">
  <div class="col-lg-5 col-xl-4 grid-margin grid-margin-xl-0 stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-baseline mb-2">
          <h6 class="card-title mb-0">Inbox</h6>
          <div class="dropdown mb-2">
            <button class="btn p-0" type="button" id="dropdownMenuButton6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton6">
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="printer" class="icon-sm mr-2"></i> <span class="">Print</span></a>
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
            </div>
          </div>
        </div>
        <div class="d-flex flex-column">
          <a href="#" class="d-flex align-items-center border-bottom pb-3">
            <div class="mr-3">
              <img src="{{ url('https://via.placeholder.com/35x35') }}" class="rounded-circle wd-35" alt="user">
            </div>
            <div class="w-100">
              <div class="d-flex justify-content-between">
                <h6 class="text-body mb-2">Leonardo Payne</h6>
                <p class="text-muted tx-12">12.30 PM</p>
              </div>
              <p class="text-muted tx-13">Hey! there I'm available...</p>
            </div>
          </a>
          <a href="#" class="d-flex align-items-center border-bottom py-3">
            <div class="mr-3">
              <img src="{{ url('https://via.placeholder.com/35x35') }}" class="rounded-circle wd-35" alt="user">
            </div>
            <div class="w-100">
              <div class="d-flex justify-content-between">
                <h6 class="text-body mb-2">Carl Henson</h6>
                <p class="text-muted tx-12">02.14 AM</p>
              </div>
              <p class="text-muted tx-13">I've finished it! See you so..</p>
            </div>
          </a>
          <a href="#" class="d-flex align-items-center border-bottom py-3">
            <div class="mr-3">
              <img src="{{ url('https://via.placeholder.com/35x35') }}" class="rounded-circle wd-35" alt="user">
            </div>
            <div class="w-100">
              <div class="d-flex justify-content-between">
                <h6 class="text-body mb-2">Jensen Combs</h6>
                <p class="text-muted tx-12">08.22 PM</p>
              </div>
              <p class="text-muted tx-13">This template is awesome!</p>
            </div>
          </a>
          <a href="#" class="d-flex align-items-center border-bottom py-3">
            <div class="mr-3">
              <img src="{{ url('https://via.placeholder.com/35x35') }}" class="rounded-circle wd-35" alt="user">
            </div>
            <div class="w-100">
              <div class="d-flex justify-content-between">
                <h6 class="text-body mb-2">Amiah Burton</h6>
                <p class="text-muted tx-12">05.49 AM</p>
              </div>
              <p class="text-muted tx-13">Nice to meet you</p>
            </div>
          </a>
          <a href="#" class="d-flex align-items-center border-bottom py-3">
            <div class="mr-3">
              <img src="{{ url('https://via.placeholder.com/35x35') }}" class="rounded-circle wd-35" alt="user">
            </div>
            <div class="w-100">
              <div class="d-flex justify-content-between">
                <h6 class="text-body mb-2">Yaretzi Mayo</h6>
                <p class="text-muted tx-12">01.19 AM</p>
              </div>
              <p class="text-muted tx-13">Hey! there I'm available...</p>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-7 col-xl-8 stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-baseline mb-2">
          <h6 class="card-title mb-0">Projects</h6>
          <div class="dropdown mb-2">
            <button class="btn p-0" type="button" id="dropdownMenuButton7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton7">
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="printer" class="icon-sm mr-2"></i> <span class="">Print</span></a>
              <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th class="pt-0">#</th>
                <th class="pt-0">Project Name</th>
                <th class="pt-0">Start Date</th>
                <th class="pt-0">Due Date</th>
                <th class="pt-0">Status</th>
                <th class="pt-0">Assign</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>NobleUI jQuery</td>
                <td>01/01/2021</td>
                <td>26/04/2021</td>
                <td><span class="badge badge-danger">Released</span></td>
                <td>Leonardo Payne</td>
              </tr>
              <tr>
                <td>2</td>
                <td>NobleUI Angular</td>
                <td>01/01/2021</td>
                <td>26/04/2021</td>
                <td><span class="badge badge-success">Review</span></td>
                <td>Carl Henson</td>
              </tr>
              <tr>
                <td>3</td>
                <td>NobleUI ReactJs</td>
                <td>01/05/2021</td>
                <td>10/09/2021</td>
                <td><span class="badge badge-info-muted">Pending</span></td>
                <td>Jensen Combs</td>
              </tr>
              <tr>
                <td>4</td>
                <td>NobleUI VueJs</td>
                <td>01/01/2021</td>
                <td>31/11/2021</td>
                <td><span class="badge badge-warning">Work in Progress</span>
                </td>
                <td>Amiah Burton</td>
              </tr>
              <tr>
                <td>5</td>
                <td>NobleUI Laravel</td>
                <td>01/01/2021</td>
                <td>31/12/2021</td>
                <td><span class="badge badge-danger-muted text-white">Coming soon</span></td>
                <td>Yaretzi Mayo</td>
              </tr>
              <tr>
                <td>6</td>
                <td>NobleUI NodeJs</td>
                <td>01/01/2021</td>
                <td>31/12/2021</td>
                <td><span class="badge badge-primary">Coming soon</span></td>
                <td>Carl Henson</td>
              </tr>
              <tr>
                <td class="border-bottom">3</td>
                <td class="border-bottom">NobleUI EmberJs</td>
                <td class="border-bottom">01/05/2021</td>
                <td class="border-bottom">10/11/2021</td>
                <td class="border-bottom"><span class="badge badge-info-muted">Pending</span></td>
                <td class="border-bottom">Jensen Combs</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div> <!-- row --> --}}
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/chartjs/Chart.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/jquery.flot/jquery.flot.js') }}"></script>
  <script src="{{ asset('assets/plugins/jquery.flot/jquery.flot.resize.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/progressbar-js/progressbar.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/dashboard.js') }}"></script>
  <script src="{{ asset('assets/js/datepicker.js') }}"></script>
@endpush
