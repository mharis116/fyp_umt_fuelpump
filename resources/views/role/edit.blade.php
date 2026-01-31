@extends('layout.master')


@section('content')
    {{-- @if(isset($breadcrumbs))
        @include('layouts.partials.breadcrumb',compact('breadcrumbs'))
    @endif --}}
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">{{ __('Update') }} Role</h6>

            <form method="POST" action="{{ route('roles.update', $role->id) }}"  role="form" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                @csrf

                @include('role.form')

            </form>
        </div>
    </div>
@endsection
