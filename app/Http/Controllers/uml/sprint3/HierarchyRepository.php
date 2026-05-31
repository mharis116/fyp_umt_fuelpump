<?php

namespace App\Repositories;

use App\Models\Hierarchy;
use App\Models\HierarchyLevel;
use App\Models\Location;
use App\Models\User;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class HierarchyRepository
{
    public Hierarchy $hierarchy;

    public function __construct(){
        $this->hierarchy = new Hierarchy;
    }

    public function get($filters = []){
        $hierarchy = $this->hierarchy;

        if(isset($filters['hierarchy_level_id'])){
            $hierarchy = $hierarchy->where('hierarchy_level_id', $filters['hierarchy_level_id']);
        }

        return $hierarchy;
    }

    public function assignHierarchyTopToAdmin(){
        $user = app(UserRepository::class)->getAdminUser();
        $hierarchyLevel = HierarchyLevel::orderBy('level')->first();
        $hierarchy_ids = Hierarchy::where('hierarchy_level_id', $hierarchyLevel->id)->select(['id'])->get()->pluck('id')->toArray();
        $user->hierarchies()->sync($hierarchy_ids);
    }

    public function createDynamicHierarchyTree(array $levels, ?string $code = null, ?string $address = null)
    {
        if (empty($levels)) {
            return null;
        }

        DB::beginTransaction();

        try {
            $parentId = null;
            $levelNumber = 1;

            foreach ($levels as $headerName => $levelValue) {

                // Create or find hierarchy level based on header name
                $hierarchyLevel = HierarchyLevel::firstOrCreate(
                    ['level' => $levelNumber],
                    ['name' => ucfirst($headerName)]
                );

                $locationData = ['code' => '', 'address'=>''];
                if(count($levels) == $levelNumber){
                    $locationData = ['code' => $code, 'address' => $address];
                }

                // Create or find the location for this level
                // $location = Location::firstOrCreate(
                //     ['name' => $levelValue],
                //     $locationData
                // );
                $location = $this->create_location($levelValue, $locationData);

                // Link hierarchy node
                $hierarchy = Hierarchy::firstOrCreate([
                    'location_id' => $location->id,
                    'hierarchy_level_id' => $hierarchyLevel->id,
                    'parent_id' => $parentId,
                ]);

                $parentId = $hierarchy->id;
                $levelNumber++;
            }

            $this->assignHierarchyTopToAdmin();

            DB::commit();

            return Hierarchy::find($parentId);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function create_hierarchy_node($payload): Hierarchy
    {
        return DB::transaction(function () use($payload) {
            $location = $this->create_location($payload['name'], ['code'=>$payload['code']??'', 'address'=>$payload['address']??'']);
            $hierarchy = Hierarchy::firstOrCreate([
                'location_id' => $location->id,
                'hierarchy_level_id' => $payload['hierarchy_level_id'],
                'parent_id' => $payload['parent_id']??null,
            ]);

            return $hierarchy;
        });
    }



    public function update_hierarchy_node($payload): Hierarchy
    {
        return DB::transaction(function () use($payload) {
            // $location = $this->create_location($payload['name'], ['code'=>$payload['code']??'', 'address'=>$payload['address']??'']);
            $hierarchy = Hierarchy::where('id', $payload['id'])->first();
            // $hierarchy = Hierarchy::where('id', $payload['id'])->update([
            //     // 'location_id' => $location->id,
            //     'hierarchy_level_id' => $payload['hierarchy_level_id'],
            //     'parent_id' => $payload['parent_id'],
            // ]);
            $hierarchy->location()->update([
                'name' => $payload['name'],
                'code' => $payload['code'],
                'address' => $payload['address'],
            ]);

            return $hierarchy;
        });
    }

    public function create_location($location_name, $location_data): Location
    {
        $location = Location::firstOrCreate(
            ['name' => $location_name],
            $location_data
        );

        return $location;
    }


    public function get_full_hierarchy_tree()
    {
        $roots = Hierarchy::with(['location', 'children.location'])
        ->whereNull('parent_id')
        ->get();

        return $this->build_tree($roots);
    }

    public function get_last_levels(){
        return HierarchyLevel::orderByDesc('level')->limit(2)->get();
    }

    /**
     * Recursive tree builder for jsTree JSON format
     */
    protected function build_tree($nodes)
    {
        $tree = [];
        $last_levels = $this->get_last_levels();

        foreach ($nodes as $node) {
            $locationName = $node->location->name ?? 'Unnamed';
            $children = $node->children ? $this->build_tree($node->children) : [];

            $tree[] = [
                'id' => $node->id,
                'text' => $locationName,
                'children' => $children,
                'data' => [
                    'hierarchy_id' => $node->id,
                    'location_id' => $node->location->id,
                    'hierarchy_level_id' => $node->hierarchy_level_id,
                    // 'hierarchy_next_level_id' => $node->children[0]?->hierarchy_level_id??null,
                    'hierarchy_next_level_id' => $node->hierarchyLevel->next()?->id,
                    // 'hierarchy_second_next_level_id' => $node->children[0]?->children[0]?->hierarchy_level_id??null,
                    'hierarchy_parent_id' => $node->parent_id,
                    'last_levels' =>$last_levels,

                    'name' => $node->location->name ?? '',
                    'code' => $node->location->code ?? '',
                    'address' => $node->location->address ?? '',
                    'level' => optional($node->hierarchyLevel)->name,
                ],
            ];
        }

        return $tree;
    }

    public function validateSingleNode(array $payload)
    {
        // 1️⃣ Basic validation
        $validator = Validator::make($payload, [
            'hierarchy_level_id' => ['required', 'integer', Rule::exists('hierarchy_levels', 'id')],
            'parent_id' => ['nullable', 'integer', Rule::exists('hierarchies', 'id')],
            'id' => ['nullable', Rule::exists('hierarchies', 'id')],
            'location_id' => ['nullable', Rule::exists('locations', 'id')],
            'type' => ['required', Rule::in('child', 'brother', 'edit')],
            'name' => ['required', 'string',Rule::unique('locations')->when($payload['type'] === 'edit', function ($rule) use ($payload) {
                $rule->ignore($payload['location_id'], 'id');
            })],
            'code' => ['nullable', 'string', Rule::unique('locations')->when($payload['type'] === 'edit', function ($rule) use ($payload) {
                $rule->ignore($payload['location_id'], 'id');
            })],
            'address' => ['nullable', Rule::unique('locations')->when($payload['type'] === 'edit', function ($rule) use ($payload) {
                $rule->ignore($payload['location_id'], 'id');
            })],
        ]);

        if ($validator->fails()) {
            throw \Illuminate\Validation\ValidationException::withMessages($validator->errors()->all());
        }

        // dd($payload);

        $levelId = $payload['hierarchy_level_id'];
        $parentId = $payload['parent_id'] ?? null;
        $name = $payload['name'];
        $code = $payload['code'] ?? null;
        $address = $payload['address'] ?? null;

        // 2️⃣ Parent-child name uniqueness
        // if ($parentId) {
        //     $parentHierarchy = \App\Models\Hierarchy::with('location')->find($parentId);
        //     if ($parentHierarchy && $parentHierarchy->location->name === $name) {
        //         throw \Illuminate\Validation\ValidationException::withMessages([
        //             'name' => "Node name '{$name}' cannot be the same as its parent."
        //         ]);
        //     }
        // }

        // 2️⃣.1 🔁 Prevent parent from taking the same name as any of its children
        // if (isset($payload['id'])) {
        //     $hasChildWithSameName = \App\Models\Hierarchy::where('parent_id', $payload['id'])
        //         ->whereHas('location', function ($q) use ($name) {
        //             $q->where('name', $name);
        //         })
        //         ->exists();

        //     if ($hasChildWithSameName) {
        //         throw \Illuminate\Validation\ValidationException::withMessages([
        //             'name' => "This parent already has a child named '{$name}'."
        //         ]);
        //     }
        // }

        // 3️⃣ Name uniqueness under same parent and level
        // if (\App\Models\Hierarchy::where('hierarchy_level_id', $levelId)
        //     ->where('parent_id', $parentId)
        //     ->whereHas('location', function ($q) use ($name) {
        //         $q->where('name', $name);
        //     })
        //     ->when($payload['type'] == 'edit', function($q)use($payload){
        //         return $q->where('id', '!=', $payload['id']);
        //     })
        //     ->exists()) {
        //     throw \Illuminate\Validation\ValidationException::withMessages([
        //         'name' => "Name '{$name}' already exists under this parent at this level."
        //     ]);
        // }

        // 4️⃣ Code uniqueness under same parent and level
        // if ($code && \App\Models\Hierarchy::where('hierarchy_level_id', $levelId)
        //     // ->where('parent_id', $parentId)
        //     ->whereHas('location', function ($q) use ($code) {
        //         $q->where('code', $code);
        //     })
        //     ->when($payload['type'] == 'edit', function($q)use($payload){
        //         return $q->where('id', '!=', $payload['id']);
        //     })
        //     ->exists()) {
        //     throw \Illuminate\Validation\ValidationException::withMessages([
        //         'code' => "Code '{$code}' already exists under this parent at this level."
        //     ]);
        // }

        // 5️⃣ Address uniqueness under same parent and level
        // if ($address && \App\Models\Hierarchy::where('hierarchy_level_id', $levelId)
        //     ->where('parent_id', $parentId)
        //     ->whereHas('location', function ($q) use ($address) {
        //         $q->where('address', $address);
        //     })
        //     ->when($payload['type'] == 'edit', function($q)use($payload){
        //         return $q->where('id', '!=', $payload['id']);
        //     })
        //     ->exists()) {
        //     throw \Illuminate\Validation\ValidationException::withMessages([
        //         'address' => "Address '{$address}' already exists under this parent at this level."
        //     ]);
        // }

        return true; // passed all validations
    }
    //aiza

    private function getEndLevelChildrenRecursive($parentId, $endLevel)
    {
        $children = Hierarchy::where('parent_id', $parentId)->get();
        $endNodes = [];

        foreach ($children as $child) {
            if ($child->hierarchy_level_id == $endLevel) {
                $endNodes[] = $child->id;
            } else {
                $endNodes = array_merge(
                    $endNodes,
                    $this->getEndLevelChildrenRecursive($child->id, $endLevel)
                );
            }
        }

        return $endNodes;
    }


    public function getEndLevelHierarchies(int $hierarchyId)
    {
        $endLevel = HierarchyLevel::orderByDesc('level')->first()->id;
        $current = Hierarchy::find($hierarchyId);
        if (!$current) return [];
        if ($current->hierarchy_level_id == $endLevel) {
            return [$hierarchyId];
        }

        return $this->getEndLevelChildrenRecursive($hierarchyId, $endLevel);
    }

    public function getEndLevelHierarchiesFromArray(array $hierarchyIds)
    {
        $hierarchies = [];
        foreach($hierarchyIds as $hierarchyId){
            $hierarchies = array_merge($this->getEndLevelHierarchies($hierarchyId), $hierarchies);
        }

        return array_unique($hierarchies);
    }

    public function getEndLevelHierarchiesData(array $hierarchyIds)
    {
        $hierarchies = Hierarchy::whereIn('id', $this->getEndLevelHierarchiesFromArray($hierarchyIds))->with(['location'])->get();

        return $hierarchies;
    }

    public function dropdown(array $hierarchyIds){
        return $this->getEndLevelHierarchiesData($hierarchyIds);
    }

}
