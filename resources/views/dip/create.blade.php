@extends('layout.master')


@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item" ><a href="{{route('dip.index')}}">Dips</a></li>
        <li class="breadcrumb-item" aria-current="page">{{isset($data)? 'Update' : 'Create'}} Dip</li>
    </ol>
</nav>
<br>
<div class="card">
    <div class="card-header">
        {{isset($data)? 'Update' : 'Create'}} Dip
    </div>
    @php
        if(auth()->user()->account_type == 'admin'){
            $up = route('dip.update',isset($data)?$data->id:0);
        }else{
            $up = route('eup');
        }
    @endphp
    <div class="card-body">
        <form action="{{isset($data)?$up:route('dip.store')}}" method="post">
            @csrf
            @if(isset($data))
                @method('Put')
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Products:</label>
                        @if(isset($data->pro_id))
                            <input type="hidden" name="product" id="name" value="{{$data->pro_id}}" class="form-control inputa" placeholder="Description">
                        @endif
                        <select name="product" id="product" onchange="handle_product_dropdown()" class="form-control inputa" {{isset($data->pro_id)? 'disabled' : 'required'}}>
                            <option value="">--select--</option>
                            @foreach ($exp as $e)
                                <option value="{{$e->id}}"{{isset($data->pro_id)?$data->pro_id == $e->id ? 'selected':null:null}} >{{$e->name.' - '.$e->sku}}</option>
                            @endforeach    
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Quantity in liters:</label>
                        <input type="text" name="qty" id="qty" required value="{{isset($data->qty)?$data->qty:old('qty')}}" class="form-control inputa" placeholder="00">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Description:</label>
                        <input type="text" name="desc" id="name" value="{{isset($data->desc)?$data->desc:old('desc')}}" class="form-control inputa" placeholder="Description">
                    </div>
                </div>
            </div>
            @php
            $data=[
                    'button' => isset($data)? 'Update' : 'Create',
                    'id' => 'expt',
                    'color'=>isset($data)? 'info' : 'success',
                    'float' => 'right text-light',
                    'type' => 'info',
                    'desc' => 'Do you realy want to add or Update Dip!'
                ];
            @endphp
            @include('partials.popup',$data) 
        </form>
    </div>
</div>
<script>
    function handle_product_dropdown(){
        let product_id = $('#product option:selected').val();
        // for tank later add drop down for stock cascade

        if(!product_id){
            return;
        }

        $.get(`/fuel-guage/${product_id}/last_sensor_reading`).then(function(response){
            console.log(response?.data?.quantity_in_ltrs);
            
            if(response?.data?.quantity_in_ltrs != null && response?.data?.quantity_in_ltrs != '' && response?.data?.quantity_in_ltrs != undefined){
                $('#qty').val(response?.data?.quantity_in_ltrs??'');
                $('#qty').attr('readonly', true);
            }else{
                $('#qty').val('');
                $('#qty').removeAttr('readonly');
            }
            
        });

        // console.log(product);
        
    }
</script>
@endsection


{{-- 




SELECT
    pro_id,

    /* Time behavior */
    IFNULL(
        TIMESTAMPDIFF(
            MINUTE,
            LAG(date) OVER (PARTITION BY pro_id ORDER BY date),
            date
        ) / 60.0,
        0
    ) AS elapsed_hours,

    /* Tank state */
    qty AS current_dip_qty,
    last_dip_qty,
    change_in_qty,

    /* Environment */
    temperature,
    humidity,

    /* Business activity in this dip window */
    total_sales_qty,
    total_sales_count,
    total_purchase_qty,
    total_purchase_count,

    /* Core anomaly signal */
    variance,

    /* Training label */
    true_label

FROM dips
WHERE isdeleted = 0
ORDER BY pro_id, date;


===========



SELECT
    pro_id,

    /* ================= TIME FEATURE ================= */
    IFNULL(
        TIMESTAMPDIFF(
            MINUTE,
            LAG(date) OVER (PARTITION BY pro_id ORDER BY date),
            date
        ) / 60.0,
        0
    ) AS elapsed_hours,

    /* ================= SENSOR STATE ================= */
    qty AS current_dip_qty,
    last_dip_qty,
    change_in_qty,

    /* ================= ENVIRONMENT ================= */
    temperature,
    humidity,

    /* ================= BUSINESS ACTIVITY ================= */
    total_sales_qty,
    total_sales_count,
    total_purchase_qty,
    total_purchase_count,

    /* ================= CORE FEATURES ================= */
    variance,
    abs_variance,

    /* ================= DERIVED FEATURES ================= */

    /* 1. NORMALIZED VARIANCE SPEED */
    (variance / NULLIF(
        TIMESTAMPDIFF(
            MINUTE,
            LAG(date) OVER (PARTITION BY pro_id ORDER BY date),
            date
        ) / 60.0,
        1
    )) AS variance_rate,

    /* 2. DIRECTION (LOSS / GAIN) */
    SIGN(variance) AS direction,

    /* 3. NET BUSINESS FLOW */
    (total_purchase_qty - total_sales_qty) AS expected_change,

    /* 4. ACTIVITY INTENSITY */
    (total_sales_qty + total_purchase_qty) AS activity_intensity,

    /* 5. TIME GAP ANOMALY FLAG */
    CASE
        WHEN TIMESTAMPDIFF(
            HOUR,
            LAG(date) OVER (PARTITION BY pro_id ORDER BY date),
            date
        ) > 48 THEN 1
        ELSE 0
    END AS long_gap_flag,

    /* 6. SENSOR JUMP FLAG */
    CASE
        WHEN ABS(qty - LAG(qty) OVER (PARTITION BY pro_id ORDER BY date)) > 5 THEN 1
        ELSE 0
    END AS sensor_jump_flag,

    /* ================= LABEL ================= */
    true_label

FROM dips
WHERE isdeleted = 0
ORDER BY pro_id, date;
--}}