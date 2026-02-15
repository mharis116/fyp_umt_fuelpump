@extends('layout.master')


@section('content')

@if(!$hierarchy)
    <div class="card">
        <div class="card-body">
            <button type="button" class="btn btn-outline-info btn-sm float-right" data-toggle="modal" data-target="#importHelpModal">
                <i class="bi bi-info-circle mr-1"></i> Import Help
            </button>
            <h3 class="card-title">🧭 Hierarchy Upload</h3>
            <form action="{{ route('hierarchy.import') }}" method="post" enctype="multipart/form-data">
                @csrf()
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="file" class="form-label">Import Hierarchy Sheet</label>
                            <input type="file" name="file" accept=".xlx,.xlsx" id="file" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mt-2">
                        {{-- @php
                            $model=[
                                'notify_btn' => __('Upload'),
                                'function' => __('Upload'),
                                'body' => 'Please Confirm do you realy want to Upload?',
                                'btn-color' => 'primary',
                                'float' => "end",
                                'id' => "Upload"
                            ];
                        @endphp
                        @include('partials.popup', ['data'=>$model]) --}}


                        @php
                        $data=[
                                'button' =>  __('Upload'),
                                'id' => 'expt',
                                'color'=> 'primary',
                                'float' => 'end text-light',
                                'type' => 'info',
                                'desc' => 'Do you realy want to Upload Hierarchy!'
                            ];
                        @endphp
                        @include('partials.popup',$data)
                    </div>
                </div>

            </form>
        </div>
    </div>
    <!-- Import Help Modal -->
    <div class="modal fade" id="importHelpModal" tabindex="-1" aria-labelledby="importHelpLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="importHelpLabel">
                        <i class="bi bi-file-earmark-excel mr-2 text-success"></i>Excel Import Rules
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="upload-rules p-3 rounded">
                        <ul style="line-height:1.6;padding-left:1.1rem;">
                            <li><a href="{{asset("assets/samples/Hierarchy_Import_Template.xlsx")}}">Download Sample Template</a></li>
                            <li><strong>File format:</strong> .xlsx, .xls or .csv only.</li>

                            <li><strong>Header rows:</strong>
                            <ul>
                                {{-- <li>Row 1: <em>level numbers</em> (can be 1,2,3...). This row is ignored by the importer.</li> --}}
                                <li>Row 2: <em>column names</em> — e.g. <code>Country, Zone, Region, Territory, Area, Branch, Code, Address</code>.</li>
                                <li>Data begins at row 3.</li>
                            </ul>
                            </li>

                            <li><strong>Dynamic levels:</strong> Any number of hierarchy columns (Country → ... → Branch) is supported.
                            <br><small style="opacity:.85">The importer treats all columns before the <code>Code</code> column as hierarchy levels.</small>
                            </li>

                            <li><strong>Trimmed headers & values:</strong> Leading/trailing spaces in headers and cells are ignored. Use clean header names (no extra whitespace).</li>


                        </ul>

                        <div class="">
                            <table class="table table-bordered table-striped  align-middle mt-3">
                                <thead class="table-secondary ">
                                    <tr>
                                    <th style="width:40px;">#</th>
                                    <th>Validation Type</th>
                                    <th>Scope</th>
                                    <th>Allowed</th>
                                    <th>Not Allowed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><strong>Unique Code</strong></td>
                                        <td>Global</td>
                                        <td>Each code must be unique</td>
                                        <td>Duplicate codes</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td><strong>Unique Address</strong></td>
                                        <td>Global</td>
                                        <td>Each address must be unique</td>
                                        <td>Duplicate addresses</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td><strong>Branch Name</strong></td>
                                        <td>Branch</td>
                                        <td>Each branch name must be unique</td>
                                        <td>Duplicate branch name</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td><strong>Cross-Level Name Reuse</strong></td>
                                        <td>Per row</td>
                                        <td>Unique names across hierarchy columns</td>
                                        <td>Same name appearing in multiple columns of same row</td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td><strong>Level Value Tracking</strong></td>
                                        <td>Global (info)</td>
                                        <td>—</td>
                                        <td>—</td>
                                    </tr>
                                </tbody>
                                </table>
                        </div>

                        <div style="margin-top:.6rem;font-size:.92rem;">
                            <strong>Quick Example Row:</strong>
                            <table class="table table-sm table-bordered mt-2" >
                                <thead >
                                <tr>
                                    {{-- <th>#</th> --}}
                                    <th>Country</th>
                                    <th>Zone</th>
                                    <th>Region</th>
                                    <th>Territory</th>
                                    <th>Area</th>
                                    <th>Branch</th>
                                    <th>Code</th>
                                    <th>Address</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    {{-- <td>1</td> --}}
                                    <td>Pakistan</td>
                                    <td>North</td>
                                    <td>Punjab</td>
                                    <td>Lahore</td>
                                    <td>Gulberg</td>
                                    <td>Gulberg Branch Mall 1</td>
                                    <td>1001</td>
                                    <td>"12-G Gulberg III, Lahore"</td>
                                </tr>
                                </tbody>
                            </table>
                            </div>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="bi bi-x-circle mr-1"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

@else
    <div class="card">
        <div class="card-body">
            {{-- <div class="mb-2"> --}}
                <button type="button" class="btn btn-sm btn-outline-primary float-right" id="expand-all">
                    <i class="bi bi-arrows-expand"></i> Expand All
                </button>
                <button type="button" class="btn btn-sm btn-outline-info mr-2 float-right" id="collapse-all">
                    <i class="bi bi-arrows-collapse"></i> Collapse All
                </button>
            {{-- </div> --}}
            <h4 class="">🧭 Organization Hierarchy</h4>
            <br>
            <div id="tree-wrapper" class="tree-wrapper">
                <div id="hierarchy-tree"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addNodeModal" tabindex="-1" aria-labelledby="addNodeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content" >
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="addNodeModalLabel">
                        <i class="bi bi-plus-circle mr-2 text-primary"></i>Add / Edit Child Node
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route("hierarchy.create.node.location") }}" id="node_form">
                        @csrf
                        <input type="hidden" name="hierarchy_level_id" id="node_hierarchy_level_id" value="">
                        <input type="hidden" name="parent_id" id="node_parent_id" value="">
                        <input type="hidden" name="id" id="node_id" value="">
                        <input type="hidden" name="location_id" id="node_location_id" value="">
                        <input type="hidden" name="type" id="node_type" value="">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name"  id="node_name" placeholder="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating" id="node_code_wrapper">
                                    <label for="code" class="form-label">Code</label>
                                    <input type="text" name="code" id="node_code" placeholder="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating" id="node_address_wrapper">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" name="address" id="node_address" placeholder="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="mt-5">
                            <button type="submit" class="btn btn-primary float-right">
                                <i class="bi bi-check-circle mr-1"></i> Submit
                            </button>

                            <button type="button" class="btn btn-secondary mr-2 float-right" data-dismiss="modal">
                                <i class="bi bi-x-circle mr-1"></i> Close
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endif






@push('scripts')
    @include("hierarchy.partials.cdn")
@endpush

@endsection
