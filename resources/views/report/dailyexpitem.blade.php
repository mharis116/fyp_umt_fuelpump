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
            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('report.expense') }}">Monthly Expense</a></li>
            <li class="breadcrumb-item active" aria-current="page">Monthly Expense Items</li>
        </ol>
    </nav>
    <script src="{{ asset('js/jquery.print.js') }}"></script>
    <script>
        jQuery(function($) {
            'use strict';
            $('.print-link').on('click', function() {
                $.print(".print");
            });
        });

        $(document).ready(function() {
            function alt(event) {
                if (event.altKey) {
                    return true;
                } else {
                    return false;
                }
            }
            $(document).keydown(function(e) {
                if (e.keyCode == 80 && alt(event) == true) {
                    $('.print-link').trigger('click');
                } else {
                    // 
                }
            });
        });
    </script>
    <br>

    @php
        $url = strval(Request::url());
        $e = explode('/', $url);
        $dat = end($e);
    @endphp

    <div class="row print">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-outline-primary float-right print-link no-print" data-toggle='tooltip' data-placement='top' title='Press Alt + P'><i class="mr-2 icon-md" data-feather="printer"></i>Print</button>
                    <h6 class="card-title">Monthly Expense Report</h6>
                    <h6 class="text-center">{{ $dat }}</h6>
                    <br>
                    <div class="table-responsive">
                        <table class="table" id="dataTableExample">
                            <thead>
                                <tr>
                                    <th>Expense</th>
                                    <th>Type</th>
                                    <th>Expense Amount</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <script>
                                $(document).ready(function() {
                                    function sumv() {
                                        var i = 0;
                                        $('.span').each(function() {
                                            i += parseInt($(this).html());
                                        });
                                        $('#dt').html(i);
                                    }
                                    sumv();
                                    $('.tr').on('click', function() {
                                        sumv();
                                    });
                                    $(document).keydown(function() {
                                        sumv();
                                    });
                                    $(document).mouseover(function() {
                                        sumv();
                                    });
                                    $('.tr').on('dblclick', function() {
                                        $('input[type=search]').val($(this).attr('id'));
                                        $('input[type=search]').keyup();
                                        $('input[type=search]').focus();
                                        $('input[type=search]').select();
                                        sumv();
                                    });
                                });
                            </script>
                            <tbody>
                                @foreach ($exp as $s)
                                    <tr class="tr pointer" id="{{ $s->type }}">
                                        <td>{{ $s->name }} </td>
                                        <td>{{ $s->type }} </td>
                                        <td>Rs. <span class="span">{{ $s->amount }} </span></td>
                                        <td> {{ $s->desc }} </td>
                                    </tr>
                                @endforeach

                            </tbody>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>
                                    Total Expense
                                </th>
                                <th>
                                    Rs.<span id='dt'></span>
                                </th>
                            </tr>
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
