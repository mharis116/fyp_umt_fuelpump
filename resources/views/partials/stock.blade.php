<style>
    .alt{
        position: fixed;
        top:50;
        right:10px;
        width:550px;
        z-index: 100;
    }
</style>
@php
    use App\stock;
    
@endphp
@if ($message = Session::get('success'))
    <div class="alert alert-fill-success alert-dismissible fade show alt" role="alert">
        <strong>{{ $message }}</strong>
        <button type="button" class="close text-light" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif