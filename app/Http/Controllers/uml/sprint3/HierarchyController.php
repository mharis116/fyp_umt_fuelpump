<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\HierarchyImport;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Carbon\Carbon;
use App\Models\Hierarchy;

use App\Repositories\HierarchyRepository;
use App\Repositories\AssetRepository;

class HierarchyController extends Controller
{

    public $module_code;
    public $ignored_permission_methods = [];
    public $permission_methods = [];

    public function __construct(private HierarchyRepository $hierarchyRepository)
    {
        $this->inject_permissions();
    }

    public function inject_permissions()
    {
        $this->module_code = 'hierarchy-management';
        $this->ignored_permission_methods = [
            // 'import_hierarchy_view',
            // 'create_node_location',
            // 'import_hierarchy',
            'get_tree_data',
            'get_hierarchy_level_locations',
            'get_hierarchy_location_assets'
        ];
        $this->permission_methods = [
            'import_hierarchy' => [
                'module_permission_type_code' => 'upload',
            ],
            'import_hierarchy_view' => [
                'module_permission_type_code' => 'read',
            ],
            'create_node_location' => [
                'module_permission_type_code' => 'update',
            ],
            // 'destroy' => [
            //     'module_permission_type_code' => 'delete',
            // ],
        ];
    }

    public function import_hierarchy_view()
    {
        $hierarchy = Hierarchy::first();
        return view('hierarchy.index', compact('hierarchy'));
    }

    public function import_hierarchy(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
        $file = $request->file('file');

        // dd([
        //     'exists' => $file->isValid(),
        //     'realPath' => $file->getRealPath(),
        //     'pathname' => $file->getPathname(),
        //     'readable' => file_exists($file->getPathname())
        // ]);

        // dd($request->file('file'));
        try {
            Excel::import(new HierarchyImport, $file);
            return back()->with('success', 'Hierarchy imported successfully!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }



    public function get_tree_data()
    {
        $tree = $this->hierarchyRepository->get_full_hierarchy_tree();
        return response()->json($tree);
    }

    public function create_node_location(Request $request)
    {
        $payload = $request->only(['hierarchy_level_id', 'location_id', 'parent_id', 'name', 'code', 'address', 'id', 'type']);

        try {
            $this->hierarchyRepository->validateSingleNode($payload);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => collect($e->errors())->flatten()->all()], 400);
        }

        // dd($payload);
        if($payload['type'] == 'edit'){
            $node = $this->hierarchyRepository->update_hierarchy_node($payload);
        }else{
            $node = $this->hierarchyRepository->create_hierarchy_node($payload);
        }


        return response()->json(['success' => true, 'new_node_id' => $node->id], 200);
    }


    public function get_hierarchy_level_locations($hierarchy_level_id){
        $hierarchies = $this->hierarchyRepository->get(filters:[
            'hierarchy_level_id' => $hierarchy_level_id
        ])->with([
            'location',
        ])->get();

        return response()->json([
            'message' => 'success',
            'data' => $hierarchies,
        ], 200);
    }

    //region end loc asset
    public function get_hierarchy_location_assets($hierarchy_id){
        $end_level_hierarchies = $this->hierarchyRepository->getEndLevelHierarchies($hierarchy_id);
        $assets = app(AssetRepository::class)->find(['hierarchy_ids'=>$end_level_hierarchies])->get();
        return response()->json(['data' => $assets, 'end_level_hierarchies'=>$end_level_hierarchies], 200);
    }
}
