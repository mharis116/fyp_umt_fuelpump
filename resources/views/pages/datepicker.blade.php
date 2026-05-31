@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush


@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <div class="card card-default">
        <a data-toggle="collapse" href="#test-block" aria-expanded="true" aria-controls="test-block">
            <div class="card-header"><i data-feather="filter" class="icon-md mr-1"></i>Filter </div>
        </a>
        <div class="card-body" id="test-block">
            <table>
                <tr>
                    <th>Stores</th>
                </tr>
                <div class="row">
                    <tr>
                        <div class="col-md-4">
                            <td>
                                <select name="store" class="js-example-basic-single form-control" id="id_label_single">
                                    <option value="0">Select</option>
                                    @foreach ($data as $d)
                                        <option value="{{$d->shop_id}}">{{$d->shop_name}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>            
                                <!-- Include Required Prerequisites -->
                                       <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
                                       <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
                                       {{-- <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" /> --}}
                                       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                                       <!-- Include Date Range Picker -->
                                       <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
                                       <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
                               <style>
                               .cal{
                                   border-color:transparent;
                                   font-size:14px;
                               }
                               .cal:focus{
                                   border-color:greenyellow;
                               }
                               </style>
                                       <div id="reportrange" class="float-right mb-3 ml-2 form-control item-group" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                           <i class="fa fa-calendar"></i>
                                               <input type="text" name="date" class="cal" value='{{isset($from,$to)? $from != '' ?$from.' to '.$to  : '': ''  }}'  autocomplete="off" id="span"><i class="fa fa-angle-down float-right mt-1"></i>
                                           </div>
                               
                                           <div id="reportrange"></div>
                               {{-- script for date ranger --}}
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
                                                       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                                                       }
                                                   }, cb);
                                                   $('#span').change(function(){
                                                       cb(start, end);
                                                   });
                               
                                           });
                                           </script>
                            </td>
                        </div>
                    </tr>
                </div>
            </table>
        </div>
    </div>
<br>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    {{-- <h6 class="card-title text-center" style="font-size: 24px;">Sale
                        Report</h6> --}}
                    {{-- <p class="card-description">Read the <a
                            href="https://datatables.net/" target="_blank"> Official DataTables Documentation </a>for a full
                        list of instructions and other options.</p> --}}
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Store</th>
                                    <th>Sale Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                    <tr>
                                        <td>{{ $d->shop_name }} </td>
                                        <td>{{ $d->gt }} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        $.fn.select2.defaults.set('amdLanguageBase', 'select2/i18n/');
        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
        $('.js-example-basic-single').select2({
          placeholder: 'Select an option'
        });
    </script>



@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
