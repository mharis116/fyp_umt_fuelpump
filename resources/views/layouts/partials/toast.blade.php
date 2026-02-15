@php
    $error = session('error')??null;
    $success = session('success')??null;
    $warning = session('warning')??null;
    $info = session('info')??null;
@endphp
<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "timeOut": "10000",
    };

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif

    @if ($success)
        @if(gettype($success) == 'array')
            @foreach($success as $suc)
                toastr.success("{{ $suc }}");
            @endforeach
        @else
            toastr.success("{{ $success }}");
        @endif
    @endif

    @if ($error && !$errors->any())
        @if(gettype($error) == 'array')
            @foreach($error as $err)
                toastr.error("{{ $err }}");
            @endforeach
        @else
            toastr.error("{{ $error }}");
        @endif
    @endif

    @if ($warning)
        @if(gettype($warning) == 'array')
            @foreach($warning as $war)
                toastr.warning("{{ $war }}");
            @endforeach
        @else
            toastr.warning("{{ $warning }}");
        @endif
    @endif

    @if ($info)
        @if(gettype($info) == 'array')
            @foreach($info as $inf)
                toastr.info("{{ $inf }}");
            @endforeach
        @else
            toastr.info("{{ $info }}");
        @endif
    @endif
</script>
