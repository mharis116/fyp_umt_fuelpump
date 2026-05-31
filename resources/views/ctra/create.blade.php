@extends('layout.master')


@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item" ><a href="{{route('ctra.index')}}">Customer Payments</a></li>
        <li class="breadcrumb-item" aria-current="page">{{isset($dat)? 'Update' : 'Add'}} Payment</li>
    </ol>
</nav>
<br>
<script>
    $(document).ready(function(){
        $('#customer').on('change',function(){
            var id = $('#customer').val();
            $.ajax({
                type: 'GET',
                url: '/data/ledger/'+id,
                dataType: 'json',
                success: function (data) {
                    var out = data.credit + data.adj;
                    $('#credit').val('Rs. '+ out);
                    // $('#cash').attr('max', out);
                },error:function(){ 
                    console.log(data);
                }
            });
        });
    });
</script>
<div class="row">
    <div class="col-md-{{isset($dat->logo)? '8':'12'}}">
        <div class="card">
            <div class="card-header">
                {{isset($dat)? 'Update' : 'Add'}} Payment
            </div>
            @php
                if(auth()->user()->account_type == 'admin'){
                    $up = route('ctra.update',isset($dat)?$dat->lid:0);
                }else{
                    $up = route('eup');
                }
            @endphp
            <div class="card-body">
                <form action="{{isset($dat)?$up:route('ctra.store')}}" method="post">
                    @csrf
                    @if(isset($dat))
                        @method('Put')
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">customer:</label>
                                <select name="customer" id="customer" class="form-control inputa" required autofocus {{isset($dat)?'disabled':null}}>
                                    <option value="">--Select--</option>
                                    @foreach ($sup as $s)
                                    @if ($s->name != 'Walk In Customer')
                                        <option value="{{$s->id}}" {{isset($dat)?$dat->sid == $s->id?'selected':null:null}}>{{$s->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="credit">Credit:</label>
                                <input type="text" name="credit" id="credit"  value="{{isset($credit->credit)? null :old('credit')}}" class="form-control input" placeholder="Rs.00" readonly tabindex="-1" autofocus>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cash">Cash:</label>
                                {{--  max='{{isset($dat)? $credit->credit + $credit->adj : null}}'    --}}
                                <input type="number" name="cash" id="cash"  value="{{isset($dat->cash)?$dat->cash:old('cash')}}" class="form-control inputa" placeholder="Rs.00" required autofocus>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="desc">Description:</label>
                                <input type="text" name="desc" id="desc"  value="{{isset($dat->desc)?$dat->desc:old('desc')}}" class="form-control inputa" placeholder="DESC"  autofocus>
                            </div>
                        </div>
                    </div>
                  
                    @php
                    $data=[
                            'button' => isset($dat)? 'Update' : 'Add',
                            'id' => 'etrat',
                            'color'=>isset($dat)? 'info' : 'success',
                            'float' => 'right text-light',
                            'type' => 'info',
                            'desc' => 'Do you realy want to add or Update Payment!'
                        ];
                    @endphp
                    @include('partials.popup',$data) 
                </form>
            </div>
        </div>
    </div>

</div>
@endsection