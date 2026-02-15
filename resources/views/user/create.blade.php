@extends('layout.master')


@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item" ><a href="{{route('user.index')}}">Users</a></li>
        <li class="breadcrumb-item" aria-current="page">{{isset($dat)? 'Update' : 'Create'}} User</li>
    </ol>
</nav>
<br>
@if(auth()->user()->account_type == 'admin')
    <div class="row">
        <div class="col-md-{{isset($dat->logo)? '8':'12'}}">
            <div class="card">
                <div class="card-header">
                    {{isset($dat)? 'Update' : 'Create'}} User
                </div>
                @php
                    if(auth()->user()->account_type == 'admin'){
                        $up = route('user.update',isset($dat)?$dat->id:0);
                    }else{
                        $up = route('eup');
                    }
                @endphp
                <div class="card-body">
                    <form action="{{isset($dat)?$up:route('user.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @if(isset($dat))
                            @method('Put')
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" name="name" id="name"  value="{{isset($dat->name)?$dat->name:old('name')}}" class="form-control inputa" placeholder="Name" required autofocus>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="acc_type">Account Type:</label>
                                    <select name="acc_type" id="acc_type" class="form-control inputa" required autofocus>
                                        <option value="">--select--</option>
                                        <option value="manager" {{isset($dat->account_type)?$dat->account_type == 'manager' ? 'selected':null:null}}>Manager</option>
                                        <option value="staff" {{isset($dat->account_type)?$dat->account_type == 'staff' ? 'selected':null:null}}>Staff</option>
                                        <option value="customer" {{isset($dat->account_type)?$dat->account_type == 'customer' ? 'selected':null:null}}>Customer</option>
                                        <option value="supplier" {{isset($dat->account_type)?$dat->account_type == 'supplier' ? 'selected':null:null}}>Supplier</option>
                                    </select>
                                </div>
                            </div> --}}


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role_ids" class="form-label text-danger">{{ __('Roles') }}</label>
                                    <select name="role_ids[]" class="form-control @error('role_ids') is-invalid @enderror"  id="role_ids">
                                        <option value="" >-- Select --</option>
                                        @foreach(App\Role::dropdown()??[] as $role)
                                            <option value="{{$role->id}}" {{isset($dat)?$dat?->roles->where('id', $role->id)->first()? 'selected':'':''}}>{{$role->name}}</option>
                                        @endforeach
                                        {{-- <option value="inactive" {{ old('status', $user?->status) == 'inactive'?'selected':'' }}>In-Active</option> --}}
                                    </select>
                                    {!! $errors->first('role_ids', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" name="email" id="email" value="{{isset($dat->email)?$dat->email:old('email')}}" class="form-control inputa" placeholder="abc@gmail.com" required autofocus>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact">Contact:</label>
                                    <input type="text" name="contact" id="contact" maxlength="11" value="{{isset($dat->contact)?$dat->contact:old('contact')}}" class="form-control inputa" placeholder="03000000000" required autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-{{isset($dat)? '6':'12'}}">
                                <div class="form-group">
                                    <label for="logo">Profile Pic:</label>
                                    <input type="file" name="logo" id="logo" value="{{old('logo')}}" class="form-control inputa" accept=".jpg,.png,.jpeg" autofocus>
                                </div>
                            </div>
                            @if(isset($dat))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status:</label>
                                        <select name="status" class="form-control inputa" id="status" autofocus>
                                            <option value="1" {{$dat->isactive == 1 ? 'selected':null}}>Activated</option>
                                            <option value="0"  {{$dat->isactive == 0 ? 'selected':null}}>De-Activated</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="row">

                            <div class="col-md-12">
                                <hr>
                                <label for="">Hierarchy Details</label>
                                <hr>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hierarchy_level_id" class="form-label">{{ __('Hierarchy Level') }}</label>
                                    <input type="hidden" name="is_hierarchy_end_level" id="is_hierarchy_end_level" value="0">
                                    <select name="hierarchy_level_id" onchange="load_hierarchy_locations()" class="form-control @error('hierarchy_level_id') is-invalid @enderror"  id="hierarchy_level_id">
                                        <option value="" >-- Select --</option>
                                        @php
                                            $last_level_id = null;
                                        @endphp
                                        @foreach(App\Models\HierarchyLevel::dropdown()??[] as $hierarchy_level)
                                            <option value="{{$hierarchy_level->id}}" {{$dat->hierarchy_level_id == $hierarchy_level->id? 'selected':''}}>{{$hierarchy_level->name}}</option>
                                            @php
                                                $last_level_id = $hierarchy_level->id;
                                            @endphp
                                        @endforeach
                                    </select>
                                    {!! $errors->first('hierarchy_level_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hierarchy_id" class="form-label">{{ __('Hierarchy Location') }}</label>
                                    <select name="hierarchy_ids[]" multiple class="form-control select2 @error('hierarchy_id') is-invalid @enderror"  id="hierarchy_id">
                                        <option value="" >-- Select Hierarchy Level First --</option>
                                    </select>
                                    {!! $errors->first('hierarchy_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <hr>
                                <label for="">Credential Details</label>
                                <hr>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="password" name="password" id="password" class="form-control inputa" minlength="8" placeholder="*********" {{isset($dat->password)? null : 'required autofocus'}} >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cpassword">Confirm Password:</label>
                                    <input type="password" name="cpassword" id="cpassword" class="form-control inputa" minlength="8" placeholder="*********"  {{isset($dat->password)? null : 'required autofocus'}}>
                                </div>
                            </div>
                        </div>

                        @php
                        $data=[
                                'button' => isset($dat)? 'Update' : 'Create',
                                'id' => 'expt',
                                'color'=>isset($dat)? 'info' : 'success',
                                'float' => 'right text-light',
                                'type' => 'info',
                                'desc' => 'Do you realy want to add or Update User!'
                            ];
                        @endphp
                        @include('partials.popup',$data)
                    </form>
                </div>
            </div>
        </div>
        @if(isset($dat->logo))
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Profile Pic</h6>
                        <div class="text-center">
                            <img src="{{asset('storage/prof'.$dat->logo)}}" alt=""  style="width: 150px;height:150px;border-radius:50%;">
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endif

@endsection

@push('scripts')
    <script>

        let user_hierarchy_ids = {!! json_encode($dat->hierarchies->pluck('id')) !!};
        let last_level_id = {{ $last_level_id }};
        function load_hierarchy_locations(){
            let hierarchy_level_id = $('#hierarchy_level_id option:selected').val();
            let hierarchy_id_select_elem = $('#hierarchy_id');

            if(last_level_id == hierarchy_level_id){
                $('#is_hierarchy_end_level').val(1);
            }else{
                $('#is_hierarchy_end_level').val(0);
            }

            $.get(`/hierarchy/level/${hierarchy_level_id}/locations`).then(function(response){
                hierarchy_id_select_elem.empty();
                hierarchy_id_select_elem.append(`<option value="">-- Select --</option>`);
                response.data?.forEach(function(hierarchy){
                    let selected = user_hierarchy_ids.includes(hierarchy.id) ? 'selected' : '';
                    hierarchy_id_select_elem.append(`<option value="${hierarchy.id}" ${selected}>${hierarchy?.location?.name}</option>`);
                });

            })
            .fail(function(xhr){
                xhr.responseJSON.errors.forEach(function(error) {
                    toastr.error(error);
                });
            })
        }

        $(document).ready(function(){
            load_hierarchy_locations();
            $('.select2').select2();
        });
    </script>
@endpush
