<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\ShelfController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\WarehouseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\StockCategoryController; // Kontrolcü sınıfını ekleyin
use App\Http\Controllers\Api\ProductFeatureController;
Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLinkEmail']);

    Route::put('/settings', [UserController::class, 'updatePassword']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::patch('/user-update/{id}', [UserController::class, 'update'])->name('user-update');
    Route::delete('/user-delete/{id}', [UserController::class, 'delete'])->name('user-delete');
});

Route::group(['middleware' => TokenVerificationMiddleware::class], function () {

    Route::group(['prefix' => 'auth'], function () {
        // Auth
        Route::post('/logout', [UserController::class, 'userLogout']);
    });

    Route::group(['prefix' => 'user'], function () {
        // User
    });


});

Route::middleware('api')->group(function () {

    Route::group(['prefix' => 'warehouse', 'as' => 'warehouse.'], function () {
        Route::post('/', [WarehouseController::class, 'store']);
        Route::patch('/{id}', [WarehouseController::class, 'update']);
        Route::delete('/{id}', [WarehouseController::class, 'destroy']);
        Route::get('/', [WarehouseController::class, 'index']);
        Route::get('/{id}', [WarehouseController::class, 'show']);
        Route::get('/{warehouse}/shelves', [WarehouseController::class, 'getWarhouseShelves']);
    });

    Route::group(['prefix' => 'role', 'as' => 'role.'], function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::patch('/{id}', [RoleController::class, 'edit']);
        Route::delete('/{id}', [RoleController::class, 'destroy']);
    });

    Route::group(['prefix' => 'shelves', 'as' => 'shelves.'], function () {

        Route::get('/getByWarehouseId/{warehouseId}', [ShelfController::class, 'getByWarehouseId']);


    });


    Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('/category', [CategoryController::class, 'index']);
        Route::patch('/category-update/{id}', [CategoryController::class, 'update'])->name('category-update');

    });

    Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
        Route::post('/', [ProductController::class, 'store'])->name('product-add');
        Route::get('/product', [ProductController::class, 'index']);
        Route::post('/{id}', [ProductController::class, 'update']); // BU DÜZENLENECEK
        Route::post('/{id}/stock-add', [ProductController::class, 'addStock']);
    });

    Route::group(['prefix' => 'stock', 'as' => 'stock.'], function () {
        Route::post('/', [ProductController::class, 'store']);
    });

    Route::get('/product-feature', [ProductFeatureController::class, 'index']); // Tek ürün özelliğini getir
    Route::put('/product-feature', [ProductFeatureController::class, 'update']); // Ürün özelliğini güncelle
    Route::post('/product-feature', [ProductFeatureController::class, 'store']); // Yeni ürün özelliği ekle

    Route::get('/stock-categories', [StockCategoryController::class, 'index']); // Stok düşme kategorilerini getir
    Route::put('/stock-categories', [StockCategoryController::class, 'update']); // Stok düşme kategorilerini güncelle
    Route::put('/stock-limit-warning', [StockCategoryController::class, 'updateStockLimitWarning']);
});

Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']);

