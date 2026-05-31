{{-- <style>
    .alt{
        position: fixed;top:50;right:10px;width:550px;z-index: 100;
    }
</style> --}}
@php
    $i=70;
@endphp
@if ($message = Session::get('success'))
    <div class="alert alert-fill-success alert-dismissible   " style="position: fixed;top:{{$i}}px;right:10px;width:550px;z-index: 100;" role="alert">
        <strong>{{ $message }}</strong>
        <button type="button" class="close text-light" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @php
        $i+=60;
    @endphp
@endif


@if ($message = Session::get('error'))
    <div class="alert alert-fill-danger alert-dismissible   " style="position:fixed;top:{{$i}}px;right:10px;width:550px;z-index:100;"  role="alert">
        <strong>{{ $message }}</strong>
        <button type="button" class="close text-light" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @php
        $i+=60;
    @endphp
@endif


@if ($message = Session::get('warning'))
    <div class="alert alert-fill-warning alert-dismissible fade show alt" style="position: fixed;top:{{$i}}px;right:10px;width:550px;z-index: 100;"  role="alert">
        <strong>{{ $message }}</strong>
        <button type="button" class="close text-light" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @php
        $i+=60;
    @endphp
@endif


@if ($message = Session::get('info'))
    <div class="alert alert-fill-info alert-dismissible fade show alt" style="position: fixed;top:{{$i}}px;right:10px;width:550px;z-index: 100;"  role="alert">
        <strong>{{ $message }}</strong>
        <button type="button" class="close text-light" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if ($errors->any())
    @php
        $v = 70;
    @endphp
   
    @foreach ($errors->all() as $error)
        <div class="alert alert-fill-danger alert-dismissible "  style="position: fixed;top:{{$v}}px;right:10px;width:550px;z-index: 100;"
         role="alert">
            <strong>{{ $error }}</strong>
            <button type="button" class="close text-light" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @php
            $v+=70;
        @endphp
    @endforeach
@endif