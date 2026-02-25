@extends('layout.master3')
@section('content')
<div class="row">
    <div class="col-md-8">
        <style>
            .hvr:focus{
                background-color: yellowgreen;
                border-color: yellowgreen;
            }
        </style>
        <script>
            $(document).ready(function(){
                $('#submit').attr('data-toggle','tooltip');
                $('#submit').attr('data-placement','bottom');
                $('#submit').attr('title','Press Alt + S');

                function alt(event) {
                    if (event.altKey) {
                        return true;
                    } else {
                        return false;
                    }
                }
                $(document).keydown(function(e){
                    if(e.keyCode  == 83 && alt(event) == true){
                        $('#submit').trigger('click');

                    }else if(e.keyCode  == 78 && alt(event) == true){
                        $('#id_label_single').focus();
                    }else if(e.keyCode  == 67 && alt(event) == true){
                        $('#id_label_single2').focus();
                    }else if(e.keyCode  == 72 && alt(event) == true){
                        $('#cash').focus();
                    }else if(e.keyCode  == 65 && alt(event) == true){
                        $('#adjust').focus();
                    }else if(e.keyCode  == 66 && alt(event) == true){
                        $('#bill_no').focus();
                    }else if(e.keyCode  == 84 && alt(event) == true){
                        $('#storage').focus();
                    }else if(e.keyCode  == 82 && alt(event) == true){
                        $('#desc').focus();
                    }else{
                        //
                    }
                });
                function trash(idd){
                   idd = idd.match(/[\d]+|\d+/g);
                   $('#'+idd).trigger('click');
                }


                var i = 0;
                var li = [];
                var name = [];
                function add(i,n,ni,s,r,c){
                    var x = '<td><button id='+i+' value="0" onclick=rem('+i+') class="btn cl hvr btn-danger"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button></td>';
                    var name = '<td><input type="text" tabIndex="-1" readonly value="'+n+'" name="name'+i+'" placeholder="product" id="name'+i+'" class="form-control input input"><input type="hidden" value="'+ni+'" name="nameid'+i+'" id="nameid'+i+'"></td>';
                    var sku = '<td><input type="text" tabIndex="-1" readonly value="'+s+'" name="sku'+i+'" placeholder="D-0000" id="sku'+i+'" class="form-control input input"><input type="hidden"  value="'+r+'" name="retail_price'+i+'" placeholder="Rs.00" id="rp'+i+'"></td>';
                    var cp = '<td><input type="number" value="'+c+'" step="0.01" onkeypress="return event.keyCode!=13"  onchange=change('+i+') name="cost_price'+i+'" id="cp'+i+'" placeholder="Rs.00"  class="form-control input" readonly tabindex="-1"></td>';
                    var qty = '<td> <input type="number" name="qty'+i+'" min="0.1" step="0.01" onkeypress="return event.keyCode!=13"  onchange=change('+i+') placeholder="00" required autofocus id="qty'+i+'" class="form-control quat inputa"></td>';
                    var bfc = '<td class="bfcc" style="display:none;"><input  name="bfc'+i+'"  type="number" title="Required field" onkeypress="return event.keyCode!=13"  placeholder="00 ltrs"  autofocus id="bfc'+i+'" class="form-control bfc inputa"></td>';
                    var s_t = '<td><input tabIndex="-1" readonly type="number" name="sub_total'+i+'" placeholder="00" id="sub_total'+i+'" class="form-control qty input"></td>';
                    $('#r').append('<tr id="row'+i+'">'+name+sku+cp+qty+bfc+s_t+x+'</tr>');
                    if($('#items').val() != 0){
                        $('#no').remove();
                    }
                    $('#qty'+i+'').focus();
                    backup();
                    $('#qty'+i+'').keydown(function(e){
                        if(e.keyCode  == 68 && alt(event) == true){
                            var idd = $(this).attr('id');
                            trash(idd);
                        }
                    });
                    $('#cp'+i+'').keydown(function(e){
                        if(e.keyCode  == 68 && alt(event) == true){
                            var idd = $(this).attr('id');
                            trash(idd);
                        }
                    });
                }
                $('#id_label_single').on('change',function(){
                    i += 1;
                    $('#id_label_single > option:eq(0)').removeAttr('selected', true);
                    var id = $('#id_label_single').val();
                        $.ajax({
                            type: 'GET',
                            url: 'data/'+id,
                            dataType: 'json',
                            success: function (data) {
                                add(i,data.name,data.id,data.sku,data.retail_price,data.cost_Price);
                            },error:function(){
                                console.log(data);
                            }
                        });
                    $('#id_label_single > option:eq(0)').attr('selected', true);
                    li.push(i);
                    $('#items').val($("#r").children().length + 1);
                    cash();

                });
                $('#id_label_single2').on('change',function(){
                    var id = $('#id_label_single2').val();
                        $.ajax({
                            type: 'GET',
                            url: 'purdata/ledger/'+id,
                            dataType: 'json',
                            success: function (data) {
                                var out = data.credit+data.adj;
                                $('#bal').val('Rs. '+ out);
                            },error:function(){
                                console.log(data);
                            }
                        });

                });
                if($('#items').val() == 0){
                    $('#no').append('No Products Selected Yet');
                }
                function validate(){
                    var stor = $('#storage').val();
                    var ch = $('#cash').val();
                    var gd = parseFloat($('#gt').val());
                    var id = $('#id_label_single2 option:selected').text();
                    var qty = $('.qty').val();
                    var bfc = $('.bfc').val();
                    var cl = $('.cl').val();
                    var bn = $('.bill').val();
                    if(ch > gd){
                        alert('Cash amount is Greater then Grand Total');
                            $("#submit").removeAttr("data-toggle");
                            $('#id_label_single').focus();
                    }else
                    if(!cl){
                            alert('Please Insert Product');
                            $("#submit").removeAttr("data-toggle");
                            $('#id_label_single').focus();
                    }else if(!qty){
                            alert('Please Insert Fuel Qunatity');
                            $("#submit").removeAttr("data-toggle");
                            // $('.quat').focus();
                    }else if(id == '--Select--'){
                            alert('Please Select Supplier !');
                            $("#submit").removeAttr("data-toggle");
                            $('#id_label_single2').focus();
                    }else if(!bn){
                            alert('Please Insert Supplier Bill Number!');
                            $("#submit").removeAttr("data-toggle");
                            $('.bill').focus();
                    }else{
                            $("#submit").attr("data-toggle","modal");
                    }
                }
                $('#submit').click(function(){
                    cash();
                    $('#hidden').val(i);
                    validate();
                });

                function cash(){
                    var grand = $('#gt').val();
                    if(grand != 0){
                        var cash = $('#cash').val();
                        var toc = grand - cash;
                        $('#ct').val(toc.toFixed(2));
                        $('#cth').val(toc.toFixed(2));
                    }
                }

                $('#cash').on('change',function(){
                    cash();
                });

                function summ(){
                    var sum = 0;
                    $(".qty").each(function(){
                        sum += +$(this).val();
                    });
                    $("#gt").val(sum.toFixed(2));
                    cash();
                }
                function adj(){
                    var grandi = parseFloat($('#gt').val());
                    if(grandi != 0){
                        var adjust = parseFloat($('#adjust').val());
                        if(isNaN(adjust)){
                            adjust = 0;
                            summ();
                        }else{
                            var sum = 0;
                            $(".qty").each(function(){
                                sum += +$(this).val();
                            });
                            var to = sum.toFixed(2) + adjust.toFixed(2);
                            $('#gt').val(to.toFixed(2));
                            $('#ct').val(to.toFixed(2));
                            cash();
                        }
                    }
                }
                $('#adjust').on('change',function(){
                    adj();
                });
               function backup(){
                   var stor = $('#storage').val();
                    if(stor == 'b'){
                        $(".bfcc").each(function(){
                            $(this).css('display','block');
                        });
                        $(".bfc").each(function(){
                            $(this).attr('required',true);
                        });
                    }else{
                        $(".bfcc").each(function(){
                            $(this).css('display','none');
                        });
                        $(".bfc").each(function(){
                            $(this).removeAttr('required');
                        });
                    }
               }
                $('#storage').on('change',function(){
                    backup();
                });
            });
        </script>
        <div class="card  bgc top ">
            <div class="card-header">
                Purchase Invoice
                <span class="float-right text-danger">
                    Invoice #: {{$inv}}
                </span>
            </div>
            <div class="card-body">
                <form action="{{route('purchase.store')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="" class="mt-1 mr-2">Location: </label>
                                <select name="hierarchy_id" id="hierarchy_id" required class="form-control">
                                    {{-- <option value="">-- Select --</option> --}}
                                    @foreach (app(\App\Repositories\HierarchyRepository::class)->dropdown(auth()->user()->hierarchies()->get()->pluck('id')->toArray()) as $hierarchy)
                                        <option value="{{$hierarchy->id}}">{{$hierarchy->location->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Products:</label>
                                <select class="form-control drop" name='product' data-toggle="tooltip" data-placement="bottom" title="Products" id="id_label_single" autofocus>
                                    <option value="" >--Select--</option>
                                    @foreach($dat as $d)
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Suppliers:</label>
                                <select class="form-control drop" name='supplier' data-toggle="tooltip" data-placement="bottom" title="Supplier" id="id_label_single2" required autofocus>
                                    <option value="" >--Select--</option>
                                    @foreach($cust as $c)
                                        <option value="{{$c->id}}">{{$c->name}} - {{$c->company}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Our Credit:</label>
                                <input type="text" data-toggle="tooltip" data-placement="bottom" title="Payable Amount"  name="bal" id="bal" placeholder="Payable" tabIndex="-1" readonly class="form-control input">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table text-center" style="min-width: 720px;">
                                <thead>
                                    <tr>
                                        <th class="">Product</th>
                                        <th class="">SKU</th>
                                        <th class="">Cost Price</th>
                                        <th class="">Quantity</th>
                                        <th class="bfcc" style="display:none;">Tank Capacity</th>
                                        <th class="">sub_total</th>
                                        <th class="">Trash</th>

                                    </tr>
                                </thead>
                                <script>

                                    function cash(){
                                        var grand = $('#gt').val();
                                        if(grand != 0){
                                            var cash = $('#cash').val();
                                            var toc = grand - cash;
                                            $('#cth').val(toc);
                                            $('#ct').val(toc);
                                        }
                                    }
                                    function adj(){
                                        var grandi = parseFloat($('#gt').val());
                                        if(grandi != 0){
                                            var adjust = parseFloat($('#adjust').val());
                                            if(isNaN(adjust)){
                                                adjust = 0;
                                                summ();
                                            }else{
                                                var sum = 0;
                                                $(".qty").each(function(){
                                                    sum += +$(this).val();
                                                });
                                                var to = parseFloat(sum) + parseFloat(adjust);
                                                $('#gt').val(to.toFixed(2));
                                                $('#ct').val(to.toFixed(2));
                                                cash();
                                            }
                                        }
                                    }
                                    function summ(){
                                        var sum = 0;
                                        $(".qty").each(function(){
                                            sum += +$(this).val();
                                        });
                                        $("#gt").val(sum.toFixed(2));
                                        cash();
                                        adj();
                                    }
                                    function rem(i,li){
                                        var gtm = $('#gt').val();
                                        var st = $('#sub_total'+i+'').val();
                                        var sbt = gtm - st;
                                        $('#gt').val(sbt.toFixed(2));
                                        $('#row'+i+'').remove();
                                        var dt = $('#items').val();
                                        var itm = $('#items').val(dt - 1);
                                        cash();
                                        adj();
                                    }
                                    function change(i){
                                        var rp = $('#cp'+i+'').val();
                                        var qty = $('#qty'+i+'').val();
                                        t = rp * qty;
                                        $('#sub_total'+i+'').val(t.toFixed(2));
                                        summ();
                                        cash();
                                        adj();
                                    }

                                </script>
                                <tbody id='r'></tbody>
                            </table>
                            <div id='no' class="text-center mt-5"></div>
                            <br>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" >
                                <table class="table" style="min-width: 720px;">
                                    <tr class="">
                                        <th>Fuel Items</th>
                                        {{-- <th>Adjustment</th> --}}
                                        <th>Cash</th>
                                        <th>Credit</th>
                                        <th>Grand Total</th>

                                    </tr>
                                    <tr class="">
                                        <td><input type="number" value="0"  class='input form-control' name="items" tabIndex="-1" readonly id="items" placeholder="00"></td>
                                        {{-- <td><input type="number" onkeypress="return event.keyCode!=13"  class='inputa form-control' name="adjust" id="adjust" data-toggle="tooltip" data-placement="bottom" title="Press Alt + A"  step="0.01" placeholder="Rs.00"></td> --}}
                                        <td><input type="number" onkeypress="return event.keyCode!=13"  class='inputa form-control' name="cash"  data-toggle="tooltip" data-placement="bottom" title="Press Alt + H"  step="0.01" id="cash" placeholder="Rs.00"></td>
                                        <td><input type="text" value="0" class='input form-control' name="ct" tabIndex="-1" readonly id="ct" placeholder="Rs.00"><input type="hidden" name="cth" id="cth" ></td>
                                        <td><input type="text" value="0" class='input form-control text-danger'  style="font-size: 30px;" name="gt" tabIndex="-1" readonly id="gt" placeholder="Rs.00"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col-md-3">
                            <textarea name="desc" id="desc"  data-toggle="tooltip" data-placement="bottom" title="Press Alt + R" placeholder="Description" cols="30" rows="5" class="form-control inputa"></textarea>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="bill_no"  data-toggle="tooltip" data-placement="bottom" title="Press Alt + B"  onkeypress="return event.keyCode!=13"  id="bill_no" placeholder="Supplier bill no " class="bill form-control inputa">
                        </div>
                        <div class="col-md-3">
                            <select name="storage"  data-toggle="tooltip" data-placement="bottom" title="Press Alt + T" class="form-control" id="storage" required autofocus>
                                <option value="">--select--</option>
                                <option value="s">Stock</option>
                                <option value="b">Backup</option>
                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @php
                            $data=[
                                'button' => 'Save',
                                'id' => 'sub-up',
                                'color'=>'success',
                                'float' => 'right btn-lg',
                                'type' => 'info',
                                'desc' => 'Do you realy want to Save !'
                            ];
                            @endphp
                            @include('partials.popup',$data)
                        </div>
                    </div>
                    <input type="hidden" value="" name="hidden" id='hidden'>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card top">
            <div class="card-body">
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
                @endforeach
            </div>
            {{-- insert recent tranasactions --}}
        </div>
        <br>
        <div class="card">
            <div class="card-header">
                Recent Purchases
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>Supplier</th>
                            <th>Invoice No</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Adjust</th>
                            <th>Subtotal</th>
                            <th>Cash</th>
                            <th>Credit</th>
                            @if(auth()->user()->account_type == 'admin')
                                <th>Action</th>
                            @endif
                        </tr>
                        @foreach($sell as $se)
                        <tr>
                            <td>
                               {{$se->name}}
                            </td>
                            <td>{{$se->inv_no}}</td>
                            <td>{{$se->total_qty}} ltrs</td>
                            <td>Rs.{{$se->cr + $se->dr}}</td>
                            <td>Rs.{{$se->adjustment?$se->adjustment:'00'}}</td>
                            <td>Rs.{{$se->cr + $se->dr + $se->adjustment}}</td>
                            <td>Rs.{{$se->cr?$se->cr:'00'}}</td>
                            @php
                                $ct = $se->dr + $se->adjustment;
                            @endphp
                            <td>Rs.{{$ct?$ct > 0 ?$ct:'00':'00'}}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary text-light float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{route('purchase.show',$se->id)}}">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text link-icon"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                            <span class="ml-2"> Invoice </span>
                                        </a>
                                        @if(auth()->user()->account_type == 'admin')
                                            <span onclick="$('#delete{{$se->id}}').submit()" class="dropdown-item pointer">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                <span class="ml-2"> Delete</span>
                                            </span>
                                            <form action="{{route('purchase.destroy',$se->id)}}" id="delete{{$se->id}}" method="post">
                                            @csrf
                                            @method('Delete')
                                            </form>
                                        @endif
                                    </div>
                                </div></td>
                        </tr>

                        @endforeach
                    </table>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
