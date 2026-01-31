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
            Route::resource('exptype', ExpenseTypeController::class);
            Route::resource('exp', ExpenseController::class);
            Route::resource('dip', FuelDipController::class);
            Route::resource('stock', StockController::class);
            Route::resource('user', UserController::class);
            Route::resource('roles', RoleController::class);
            Route::resource('backup', FuelBackupController::class);
    //     });

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
    return View::make('pages.error.404');
})->where('page','.*');



