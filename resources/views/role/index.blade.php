@extends('layout.master')



@section('content')
    {{-- @if(isset($breadcrumbs))
        @include('layouts.partials.breadcrumb',compact('breadcrumbs'))
    @endif --}}
    <div class="card">
        <div class="card-body">
            <div class="float-right">
                <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                    {{ __('Create New') }}
                </a>
            </div>
            <h6 class="card-title">
                {{ __('Roles') }}
            </h6>
            <br>
            <br>

            <div class="table-responsive">
                <table class="table table-hover" id='role'>
                    <thead class="thead">
                        <tr>
                            <th>No</th>

                            <th >Name</th>
                            <th >Landing Relative Url</th>
                            {{-- <th >Description</th> --}}

                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ ++$i }}</td>


                                <td >{{ $role->name }}</td>
                                <td >{{ $role->landing_relative_url }}</td>
                                {{-- <td >{{ $role->description }}</td> --}}


                                <td style="min-width:180px;">
                                    {{-- <a class="btn btn-outline-primary float-end  mx-1" href="{{ route('roles.edit',$role->id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a> --}}
                                    {{-- <a class="btn btn-outline-success float-end  mx-1" href="{{ route('roles.show',$role->id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </a> --}}
                                    {{-- <form action="{{ route('roles.destroy', $role->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        @php
                                            $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>';
                                            $model=[
                                                'notify_btn' => $icon,
                                                'function' => "Delete",
                                                'body' => 'Please Confirm do you realy want to Delete '.$role->id.' ?',
                                                'btn-color' => 'danger',
                                                'float' => "end  mx-1",
                                                'id' => "del-$role->id"
                                            ];
                                        @endphp
                                        @include('partials.modal', ['data'=>$model])
                                    </form> --}}


                                            <div class="dropdown">
                                                <button class="btn btn-primary text-light float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="{{ route('roles.edit',$role->id) }}">
                                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                        <span class="ml-2"> Edit</span>
                                                    </a>

                                                    <span  id="sub" class="dropdown-item cursor-pointer" onclick="if(confirm('Are You Sure')  ){event.preventDefault(); document.getElementById('del{{$role->id}}').submit();}">
                                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                        <span class="ml-2"> Delete</span>
                                                    </span>
                                                    <form  action="{{ route('roles.destroy', $role->id) }}" method="POST" id='del{{$role->id}}'>
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <br>
            {!! $roles->withQueryString()->links() !!}
        </div>
    </div>
@endsection

{{--
@push('script')
    @include('partials.perPage')
    <script>
        $(document).ready(function(){
            prepare_datatable('#role', [0, 'asc']);
        })
    </script>
@endpush --}}

