<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Auth::routes();

Route::get('losted', [DashboardController::class, 'losted'])->name('losted');

Route::middleware([
    "auth",
    'check_role_permission'
])->group(function(){
    Route::get('/',[DashboardController::class, 'index'])->name('dashboard.main');
    Route::put('eup',[DashboardController::class, 'error'])->name('eup');
    // Route::middleware(['admin'])->group(function(){
    //     Route::middleware(['other'])->group(function(){
            Route::resource('products', ProductsController::class);
            Route::resource('supplier', SuplierController::class);
            Route::resource('customer', CustomerController::class);
            Route::resource('sale', SalesController::class);
            Route::resource('purchase', PurchaseController::class);
            Route::resource('custledger', CustomerLedgerController::class);
            Route::resource('supledger', SupplierLedgerController::class);
            Route::resource('exptype', ExpenseTypeController::class);
            Route::resource('exp', ExpenseController::class);
            Route::resource('dip', FuelDipController::class);
            Route::resource('stock', StockController::class);
            Route::resource('user', UserController::class);
            Route::resource('tra', SupplierPaymentController::class);
            Route::resource('ctra', CustomerPaymentController::class);
            Route::resource('roles', RoleController::class);
            Route::resource('backup', FuelBackupController::class);
    //     });
    // });
     Route::prefix('hierarchy')->controller(HierarchyController::class)->name('hierarchy.')->group(function () {
        Route::get('/import', 'import_hierarchy_view')->name('index');
        Route::post('/import', 'import_hierarchy')->name('import');
        Route::get('/data', 'get_tree_data')->name('tree');
        Route::get('/level/{level_id}/locations', 'get_hierarchy_level_locations')->name('level.locations');
        Route::get('/location/{hierarchy_id}/assets', 'get_hierarchy_location_assets')->name('level.location.assets');
        Route::post('/node/location/create', 'create_node_location')->name('create.node.location');
    });

    Route::controller(ReportController::class)->group(function(){
        Route::get('report/credit/{return_type?}','credit')->name('report.credit');

        Route::get('report/sale/dailysale','dailysale')->name('report.sale.dailysale');
        Route::get('report/sale/dailysaleitem/{date}','dailysaleitem')->name('report.sale.dailysaleitem');

        Route::post('report/sale/dailysale','dailysalefilter')->name('report.sale.dailysalefilter');
        
        Route::post('report/sale/profitfilter/{return_type?}','profitfilter')->name('report.sale.profitfilter');
        Route::get('report/sale/profit/{return_type?}','profit')->name('report.sale.profit');
        Route::get('report/expense/{return_type?}','expense')->name('report.expense');
        Route::get('report/expense/item/{date}','expenseitem')->name('report.expense.item');
        Route::post('report/expensefilter/{return_type?}','expensefilter')->name('report.expensefilter');
        Route::get('report/prices/{return_type?}','price')->name('report.price');
        Route::post('report/prices/filter/{return_type?}','pricefilter')->name('report.pricefilter');
        Route::post('report/ai/ask', 'ai_ask')->name("ai.ask");
    });
    Route::controller(FuelGuageContoller::class)->group(function(){
        Route::get('report/fuel-guage', 'index')->name('fuel.guage');
        Route::get('fuel-guage/{product_id}/last_sensor_reading', 'last_reading')->name('fuel.guage.last_reading');
    });


    //  Route::middleware(['other'])->group(function(){
        Route::get('data/{data}',[SalesController::class, 'dt']);
        Route::get('/data/ledger/{data}',[SalesController::class, 'ledger']);
        Route::get('purdata/ledger/{data}',[PurchaseController::class, 'ledger'])->name('slp');
        Route::get('purdata/{data}',[PurchaseController::class, 'dt']);
        Route::resource('profile', ProfileController::class);
    //  });


});


// 404 for undefined routes
Route::any('/{page?}',function(){
    return view('pages.error.404');
})->where('page','.*');



