@include('role.partials.cdn')
<div class="row g-3">

    <div class="col-md-6">
        <div class="form-group">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" required autofocus name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $role?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="code" class="form-label">{{ __('Code') }}</label>
            <input type="text" readonly required autofocus name="code" class="form-control" value="{{ old('code', $role?->code) }}" id="code" placeholder="Code">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="landing_relative_url" class="form-label">{{ __('Landing Relative Url') }}</label>
            <select name="landing_relative_url" class="form-control @error('landing_relative_url') is-invalid @enderror" id="landing_relative_url" >
                <option value="/dashboard"  {{ old("landing_relative_url", $role?->landing_relative_url) == "/dashboard"?'selected':'' }} >Main Dashboard (/dashboard)</option>
                <option value="/losted" {{ old("landing_relative_url", $role?->landing_relative_url) == "/losted"?'selected':'' }}>Blank Page (/losted)</option>
            </select>
            {!! $errors->first('landing_relative_url', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $role?->description) }}" id="description" placeholder="Description">
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    <div class="col-md-12">
        @php
            $idx = 0;
        @endphp
        @foreach ($modules as $module)
            {{-- {{$module->name}} --}}
            <div class="accordion" id="moduleAccordian{{$module->code}}">
                <div class="accordion-item ">
                    <div class="w-100 p-2 d-flex bg-primary">

                        <input type="checkbox" name="" class="cursor-pointer" onclick="toggleModuleCheckboxes({{$module->id}})" id="all_checkbox_module_{{$module->id}}">
                        <div class="h5 ms-2"  style="margin:5px;">
                            {{$module->name}}
                            {{-- (code:{{$module->code}}) --}}
                        </div>


                        <button class="ms-auto btn btn-primary  text-light rounded" type="button" data-bs-toggle="collapse" data-bs-target="#moduleAccordianPermissions{{$module->code}}" aria-expanded="true" aria-controls="collapseOne">
                            <i class="bi bi-chevron-down arrow-icon"></i>
                        </button>



                    </div>
                    <div id="moduleAccordianPermissions{{$module->code}}" class="accordion-collapse collapse show" data-bs-parent="#moduleAccordian{{$module->code}}">
                        <div class="accordion-body">
                            {{-- Permissions --}}

                            @foreach ($module['permission_types']??[] as $module_permission_type)
                                <label class="d-inline-block border rounded-pill px-2 m-1 border-info bg-info bg-opacity-10 text-nowrap cursor-pointer">
                                    <input type="hidden" name="permissions[{{$idx}}][module_id]" value="{{$module->id}}" id="">
                                    <input type="hidden" name="permissions[{{$idx}}][module_permission_type_id]" value="{{$module_permission_type->id}}" id="">
                                    <input type="hidden" name="permissions[{{$idx}}][is_permitted]"  value="0" id="">
                                    <input type="checkbox" name="permissions[{{$idx}}][is_permitted]" {{$role->role_has_permissions?->where('module_id', $module->id)->where('module_permission_type_id', $module_permission_type->id)->first()?->is_permitted == 1? 'checked':''}}  value="1" class="permission-checkbox checkbox_module_{{$module->id}}" id="" data-module-id="{{ $module->id }}" data-code="{{ $module_permission_type->code }}">
                                    {{$module_permission_type->name}}
                                </label>
                                @php
                                    $idx++;
                                @endphp
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>


    <div class="col-md-12 mt-2">
        {{-- @php
        $model=[
            'notify_btn' => __('Submit'),
            'function' => __('Submit'),
            'body' => 'Please Confirm do you realy want to Save?',
            'btn-color' => 'primary',
            'float' => "end",
            'id' => "save"
            ];
        @endphp
        @include('partials.modal', ['data'=>$model]) --}}


        @php
            $data=[
                    'button' => "Save",
                    'id' => 'expt',
                    'color'=>isset($data)? 'info' : 'success',
                    'float' => 'right text-light',
                    'type' => 'info',
                    'desc' => 'Do you realy want to add or Update Role !'
                ];
        @endphp
        @include('partials.popup',$data)
    </div>
</div>


