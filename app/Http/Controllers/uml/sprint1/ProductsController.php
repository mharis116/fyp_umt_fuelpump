<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\products;
use App\sales_items;
use Illuminate\Support\Facades\Session;
use App\purchaseItem;
use App\stock;
use App\dip;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{

    public $module_code;
    public $ignored_permission_methods = [];
    public $permission_methods = [];
    public function __construct(){
        $this->inject_permissions();
    }

    public function inject_permissions(){
        $this->module_code = 'products';
        $this->ignored_permission_methods = [
        ];
        $this->permission_methods = [
            'index' => [
                'module_permission_type_code' => 'read',
            ],
            'show' => [
                'module_permission_type_code' => 'read',
            ],
            'edit' => [
                'module_permission_type_code' => 'read',
            ],
            'update' => [
                'module_permission_type_code' => 'update',
            ],
            'create' => [
                'module_permission_type_code' => 'create',
            ],
            'store' => [
                'module_permission_type_code' => 'create',
            ],
            'destroy' => [
                'module_permission_type_code' => 'delete',
            ],
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $p = db::table('products')
        ->join('stocks','stocks.pro_id','products.id')
        ->where('products.isdeleted',0)
        ->get();
        return view('products.index')
        ->with('data' , $p);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|unique:products',
            'sku' => 'required|unique:products',
        ]);

        $products = new products;
        $products->name =  strtolower($request->name);
        $products->alert_qty = $request->alert_qty;
        $products->sku =  strtolower($request->sku);
        $products->cost_Price = $request->cost_price;
        $products->retail_price = $request->retail_price;
        $products->save();
        $stock = new stock;
        $stock->pro_id = $products->id;
        $stock->sku =  $request->sku;
        $stock->desc =  $request->desc;
        $stock->stock_capacity =  $request->cap;
        $qty = $request->qty;
        if(!$request->qty){
            $qty = 0;
        }
        $stock->qty = $qty;
        $stock->save();
        $request->session()->flash('success', 'Fuel Added Successfuly!');
        return redirect(route('products.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = db::table('products')
        ->join('stocks','stocks.pro_id','products.id')
        ->where('products.isdeleted',0)
        ->where('products.id',$id)
        ->first();
        return view('products.create')->with('data',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'name' => 'required|unique:products,name,'.$id.',id',
            'sku' => 'required|unique:products,sku,'.$id.',id',
        ]);
        $products = products::find($id);
        $products->name =  strtolower($request->name);
        $products->alert_qty = $request->alert_qty;
        $products->sku =  strtolower($request->sku);
        $products->cost_Price = $request->cost_price;
        $products->retail_price = $request->retail_price;
        $products->save();
        $request->session()->flash('success', 'Fuel updated successfuly!');
        return redirect(route('products.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $products = products::find($id);
        $sale = sales_items::where('pro_id',$id)->exists();
        $purchase = purchaseItem::where('pro_id',$id)->exists();
        $dip = dip::where('pro_id',$id)->exists();
        if(!$sale and !$purchase and !$dip){
            products::where('id',$id)->update(['isdeleted'=>1]);
            Session::flash('success','Product Deleted Successfully!');
            return redirect()->back();
        }else{
            Session::flash('error','Product is Associated with sales , Purchases or dip Can not Delete!');
            return redirect()->back();
        }
    }
}
