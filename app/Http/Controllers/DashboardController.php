<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\sales;
use App\Expense;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public $module_code;
    public $ignored_permission_methods = [];
    public $permission_methods = [];
    public function __construct(){
        $this->inject_permissions();
    }

    public function inject_permissions(){
        $this->module_code = 'main-dashboard';
        $this->ignored_permission_methods = [
            'error',
            'losted'
        ];
        $this->permission_methods = [
            'index' => [
                'module_permission_type_code' => 'read',
            ]
        ];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $stock = DB::table('products')
        ->join('stocks','stocks.pro_id','products.id')
        ->select('products.name as name','stocks.*')
        ->where('products.isdeleted',0)
        ->get();
        $sale = Sales::where('isdeleted',0)->groupBy('date')
        ->selectRaw('sum(total_qty) as qty ,sum(retail_amount) as rm,sum(adjustment) as adj,date')
        ->get();

        $exp = Expense::where('isdeleted',0)->selectRaw('date_format(date,"%m-%Y") as date,sum(amount) as exp')->groupBy(DB::Raw('date_format(date,"%m-%Y")'))->get();
        $salemy = Sales::where('sales.isdeleted',0)
        ->groupBy(DB::Raw('date_format(sales.date,"%m-%Y")'))
        ->selectRaw('sum(sales.retail_amount) as rtm,sum(sales.cost_amount) as ctm,sum(sales.adjustment) as adj,sum(sales.retail_amount + sales.adjustment - sales.cost_amount) as gp,date_format(sales.date,"%m-%Y") as date')
        ->get();
        return view('dashboard')
        ->with('exp',$exp)
        ->with('salemy',$salemy)
        ->with('sale',$sale)
        ->with('stock',$stock);
    }

    public function error(){
        Session::flash('error', 'You are not Eligible !');
        return redirect(route('dashboard.main'));
    }

    public function losted(){
        return view("losted.index");
    }
}
