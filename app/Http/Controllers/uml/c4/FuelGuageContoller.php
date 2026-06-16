<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FuelSensorReading;
use App\Models\Stock;

use App\Repositories\FuelSensorRepository;

class FuelGuageContoller extends Controller
{
    protected $fuelSensorRepository;

    public $module_code;
    public $ignored_permission_methods = [];
    public $permission_methods = [];


    public function __construct(FuelSensorRepository $fuelSensorRepository)
    {
        $this->inject_permissions();
        $this->fuelSensorRepository = $fuelSensorRepository;
    }

    public function inject_permissions(){
        $this->module_code = 'fuel-dips';
        $this->ignored_permission_methods = [
            'index',
            'last_reading'
        ];
        $this->permission_methods = [
            // 'index' => [
            //     'module_permission_type_code' => 'read',
            // ],
        ];
    }
    public function index()
    {
        $filters = [];

        $filters['start_date'] = $_GET['date'] ?? date('Y-m-d');
        $filters['end_date'] = $_GET['date'] ?? date('Y-m-d');

        // dd($filters);

        $fuelReadings = $this->fuelSensorRepository->find($filters)->get()->groupBy('fuel_sensor_id');
        // dd($fuelReadings);
        return view('fuel_guage.index', compact('fuelReadings'));
    }

    public function last_reading($product_id){
        $filters = [];

        $stock = Stock::where('pro_id', $product_id)->firstOrfail();
        $fuelReadings = $this->fuelSensorRepository->get_last_reading_by_stock($stock?->id);

        return response()->json(['data'=>$fuelReadings], 200);
    }
}
