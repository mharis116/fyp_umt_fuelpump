<?php

namespace App\Http\Controllers;

use App\Repositories\LocationRepository;

class LocationController extends Controller
{
    protected $locationRepository;

  // 🧩 Permission module setup
    public $module_code;
    public $ignored_permission_methods = [];
    public $permission_methods = [];

    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;

        // Module code for permission check
        $this->module_code = 'hierarchy-management';

        // Methods to ignore from permission check
        $this->ignored_permission_methods = [];

        // Map methods to permission types
        $this->permission_methods = [
            'getEndLocations' => ['module_permission_type_code' => 'read'],
            'getUserEndLocations' => ['module_permission_type_code' => 'read']
        ];
    }

    /**
     * API endpoint to get all end-level locations.
     */
    public function getEndLocations()
    {
        $locations = $this->locationRepository->getEndLocations();

        return response()->json([
            'status' => true,
            'message' => 'End level locations fetched successfully',
            'data' => $locations
        ]);
    }
    public function getUserEndLocations()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $locations = $this->locationRepository->getUserEndLocations($user->id);

        if ($locations->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => "You don't have any location access",
                'data' => [],
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'User end-level locations fetched successfully',
            'data' => $locations
        ]);
    }

}
