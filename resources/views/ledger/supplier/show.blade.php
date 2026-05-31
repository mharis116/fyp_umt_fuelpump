@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')
<script src="{{asset('js/jquery.print.js')}}"></script>
<script>
    jQuery(function($) { 'use strict';
        $('.print-link').on('click', function() {
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
            }
        });
        $('.tr').on('dblclick',function(){
            $('input[type=search]').val($(this).attr('id'));
            $('input[type=search]').keyup();
            $('input[type=search]').focus();
            $('input[type=search]').select();
        });
    });
</script>

<button data-toggle='tooltip' data-placement='top' title='Press Alt + P' class="btn btn-outline-primary float-right print-link "><i data-feather="printer" class="mr-2 icon-md"></i>Print</button>

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('supledger.index')}}">Ledgers</a></li>
    <li class="breadcrumb-item"><a href="{{route('supledger.index')}}">Suppliers Ledgers</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$cust->name}}</li>
  </ol>
</nav>
<br>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">{{$cust->name}}</h6>
        <div class="table-responsive">
            <table id="dataTableExample" class="table table-hover">
            <thead>
                <tr>
                <th>Date</th>
                <th>invoice_no</th>
                <th>Total</th>
                <th>Adjustment</th>
                <th>Subtotal</th>
                <th>Cash</th>
                <th>Credit</th>
                <th>Type</th>
                <th>Desc</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchase as $s)
                    <tr class="tr pointer" id="{{$s->type}}">
                        <td>
                            {{$s->date}}
                        </td>
                        <td>{{$s->inv_no}}</td>
                        <td>Rs.{{$s->cost_amount}}</td>
                        <td>{{$s->adjustment?$s->adjustment:0}} Rs</td>
                        <td>Rs.{{$s->cost_amount + $s->adjustment}}</td>
                        <td>Rs.{{$s->cr?$s->cr:0}}</td>
                        @php
                            $dr = $s->dr + $s->adjustment;
                        @endphp
                        <td>Rs.{{$dr?$dr:0}}</td>
                        <td>{{$s->type}}</td>
                        <td>{{$s->desc}}</td>
                        <td>
                            <a href="{{route('purchase.show',$s->id)}}">
                                <div class="btn btn-primary">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </div>
                            </a>
                        </td>
                    </tr>
                @endforeach
                @foreach ($other as $o)
                    @php
                        $dr = $o->dr?$o->dr:0;
                        $cr = $o->cr != null?$o->cr:0;
                        $t = $cr + $dr;
                    @endphp
                    <tr  class="tr pointer" id="{{$o->type}}">
                        <td>
                            {{$o->date}}
                        </td>
                        <td>-</td>
                        <td>Rs.{{$t}}</td>
                        <td>0 Rs</td>
                        <td>Rs.{{$t}}</td>
                        <td>Rs.{{$o->cr?$o->cr:0}}</td>
                        <td>Rs.{{$o->dr?$o->dr > 0?$o->dr:0:0}}</td>
                        <td>{{$o->type}}</td>
                        <td>{{$o->desc}}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
            </table>
            <br>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <td>Total</td>
                        <td>Adjustment</td>
                        <td>Subtotal</td>
                        <td>Cash</td>
                        <td>Credit</td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $t = $total->cash + $total->credit;
                        $st = $total->cash + $total->credit + $total->adj ;
                        $drr = $total->credit + $total->adj ;
                    @endphp
                    <tr>
                        <th>Rs.{{$t?$t:0}}</th>
                        <th>Rs.{{$total->adj?$total->adj:0}}</th>
                        <th>Rs.{{$st?$st:0}}</th>
                        <th>Rs.{{$total->cash?$total->cash:0}}</th>
                        <th>Rs.{{$drr?$drr:0}}</th>
                    </tr>
                </tbody>
            </table>
            <br>
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