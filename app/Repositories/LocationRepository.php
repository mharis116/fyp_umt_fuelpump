<?php

namespace App\Repositories;

use App\Models\HierarchyLevel;
use App\Models\Hierarchy;
use App\Models\Location;
use App\Repositories\HierarchyRepository;

class LocationRepository
{
    public function getEndLocations()
    {
        $levelName = 'City';

        // 1️⃣ Get the hierarchy_level id for the given name (case-insensitive)
        $cityLevel = HierarchyLevel::whereRaw('LOWER(name) = ?', [strtolower($levelName)])->first();

        if (!$cityLevel) {
            return collect();
        }

        // 2️⃣ Get all end (leaf) hierarchies
        $leafHierarchies = Hierarchy::doesntHave('children') // leaf nodes
            ->with('location', 'parent.location') // eager load
            ->get();

        // 3️⃣ Filter by parent whose level is 'City'
        $result = $leafHierarchies->filter(function ($hierarchy) use ($cityLevel) {
            $parent = $hierarchy->parent;
            while ($parent) {
                if ($parent->hierarchy_level_id == $cityLevel->id) {
                    // attach City info dynamically
                    $hierarchy->city_parent_id = $parent->id;
                    $hierarchy->city_parent_name = $parent->location->name ?? null;
                    return true;
                }
                $parent = $parent->parent;
            }
            return false;
        });


        return $result->values(); // reset keys
    }
    public function getUserEndLocations($userId)
    {
   
        // 1️⃣ User ki assigned hierarchies
        $assignedHierarchies = \App\Models\AssignHierarchyToUser::where('user_id', $userId)
            ->pluck('hierarchy_id');

        if ($assignedHierarchies->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => "You don't have any location access",
                'data' => [],
            ], 403);
        }

        // 3️⃣ End-level hierarchies retrieve karna
        $hierarchyRepo = new HierarchyRepository();
        $endHierarchyIds = collect();

        foreach ($assignedHierarchies as $hierarchyId) {
            $endHierarchyIds = $endHierarchyIds->merge(
                $hierarchyRepo->getEndLevelHierarchies($hierarchyId)
            );
        }


        // 4️⃣ Now fetch service history with allowed hierarchy IDs
        $branches = $endHierarchyIds->toArray();
        $locations = Hierarchy::with('location')
            ->whereIn('id', $branches)
            ->get()
            ->map(function ($hierarchy) {
                return [
                    'hierarchy_id' => $hierarchy->id,
                    'location_name' => $hierarchy->location->name ?? null,
                ];
        });

        return $locations;
    }
}
