@extends('layouts.app')

@section('template_title')
    {{ $role->name ?? __('Show') . " " . __('Role') }}
@endsection

@section('content')
    @if(isset($breadcrumbs))
        @include('layouts.partials.breadcrumb',compact('breadcrumbs'))
    @endif
    <div class="card">
        <div class="card-body">
            <div class="float-end">
                <a class="btn btn-primary btn-sm" href="{{ route('client.roles.index') }}"> {{ __('Back') }}</a>
            </div>
            <h6 class="card-title">{{ __('Show') }} Role</h6>
            <br>
            <br>

            
                                <div class="form-group mb-2 mb20">
                                    <strong>Name:</strong>
                                    {{ $role->name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Landing Relative Url:</strong>
                                    {{ $role->landing_relative_url }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Description:</strong>
                                    {{ $role->description }}
                                </div>

        </div>
    </div>
@endsection
