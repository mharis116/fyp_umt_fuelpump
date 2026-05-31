@extends('layout.master')
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush
@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item" aria-current="page">Fuel Backup /</li>
    </ol>
</nav>
<br>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Fuel Backup Management</h6>
                <div class="table-responsive">
                <table id="dataTableExample" class="table text-center">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Invoice No</th>
                        <th>Buying Quantity</th>
                        <th>Remaining Quantity</th>
                        <th>Stock Capacity</th>
                        @if(auth()->user()->account_type == 'admin' or auth()->user()->account_type == 'manager') 
                            <th>Function</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $p)
                            <tr>
                                <td>{{$p->created_at}}</td>
                                <td>{{$p->name}}</td>
                                <td>{{$p->sku}}</td>
                                <td>{{$p->inv_no}}</td>
                                <td>{{$p->fqty}} ltrs</td>
                                <td>{{$p->qty}} ltrs</td>
                                <td>{{$p->stock_capacity}} ltrs</td>
                        
                                @if(auth()->user()->account_type == 'admin' or auth()->user()->account_type == 'manager') 
                                    @if($p->qty != 0)
                                        <td>
                                            <a href="{{route('backup.edit',$p->fbid)}}">
                                                <button class="btn btn-primary float-right">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                                    Transfer​​
                                                </button>
                                            </a>
                                        </td>
                                        @else
                                        <td>
                                            <a href="#">
                                                <button class="btn btn-success float-right">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                                    Transferd
                                                </button>
                                            </a>
                                        </td>
                                    @endif
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection