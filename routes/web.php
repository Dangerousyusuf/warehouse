<?php
use App\Http\Controllers\StockController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\TransferItemController;
use App\Http\Controllers\TransferListController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FactorySettingsController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\VariationController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\StatisticsController;

Auth::routes();



Route::group(['middleware' => 'auth'], function () {

    Route::get('/', function () {
        return view('index');
    })->name('index');

    Route::get('warehouse-list', [WarehouseController::class, 'index'])->name('warehouse.warehouse_list');
    Route::get('warehouse-add', function () {
        return view('warehouse.warehouse_add');
    })->name('warehouse.warehouse_add');
    Route::post('warehouse-add', [WarehouseController::class, 'store'])->name('warehouse.warehouse_store');
    Route::get('warehouse-edit/{id}', [WarehouseController::class, 'edit'])->name('warehouse.warehouse_edit');
    Route::put('warehouse-edit/{id}', [WarehouseController::class, 'update'])->name('warehouse.update');
    Route::delete('warehouse-delete/{id}', [WarehouseController::class, 'destroy'])->name('warehouse.destroy');

    Route::post('warehouse/{id}/add-products', [WarehouseController::class, 'addProducts'])->name('warehouse.addProducts');


    Route::delete('/shelf/{id}', [WarehouseController::class, 'deleteShelf'])->name('shelf.delete');



    Route::get('variation-add', function () {
        return view('variation.variation_add');
    })->name('variation.variation_add');



    Route::get('variations', [VariationController::class, 'index'])->name('variations.index');
    Route::get('variations/create', [VariationController::class, 'create'])->name('variations.create');
    Route::post('variations', [VariationController::class, 'store'])->name('variations.store');
    Route::get('variation-edit/{id}/', [VariationController::class, 'edit'])->name('variations.edit');
    Route::put('variations/{id}', [VariationController::class, 'update'])->name('variations.update');
    Route::delete('variation/{id}', [VariationController::class, 'destroy'])->name('variation.destroy');
    Route::delete('variation/{id}/product', [VariationController::class, 'destroyProductVariantion'])->name('variation.productDestroy');

    /*Route::get('category-add', function () {
        return view('category.category_add');
    })->name('category.category_add');

    Route::get('category-edit/{id}', function ($id) {
        return view('category.category_edit', compact('id'));
    })->name('category.category_edit');

    Route::get('category-list', function () {
        return view('category.category_list');
    })->name('category.category_list');*/

    Route::get('category-list', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('category-add', function () {
        return view('category.category_add');
    })->name('categories.add');
    Route::post('category-add', [CategoryController::class, 'store'])->name('categories.store');

    // Kategori düzenleme rotaları
    Route::get('category-edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('category-edit/{id}', [CategoryController::class, 'update'])->name('categories.update');

    // Kategori silme işlemi için DELETE rotası
    Route::delete('category-delete/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');


    Route::post('user_add', [UserController::class, 'store'])->name('users.store');
    Route::get('user-edit/{id}', [UserController::class, 'edit'])->name('user-edit'); // Kullanıcıyı düzenleme formunu göster
    Route::put('user-edit/{id}', [UserController::class, 'update'])->name('user-update'); // Kullanıcı güncelleme işlemi
    /*Route::get('product-list', function () {
        return view('product.product_list');
    })->name('product.product_list');

    Route::get('product-add', function () {
        return view('product.product_add');
    })->name('product.product_add');

    Route::get('product-edit/{id}/', function ($id) {
        return view('product.product_edit', compact('id'));
    })->name('product.product_edit');*/

    // Ürün listesi, ekleme, düzenleme ve silme işlemleri
    Route::get('product-list', [ProductController::class, 'index'])->name('product.product_list');
    Route::get('product-add', [ProductController::class, 'create'])->name('product.product_add');
    Route::get('/products/filter', [ProductController::class, 'filterByCategory'])->name('products.filter');

    Route::post('product-add', [ProductController::class, 'store'])->name('products.store');

    Route::get('product-edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('product-edit/{id}', [ProductController::class, 'update'])->name('products.update');

    Route::delete('product-delete/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/products/category/{category}', [ProductController::class, 'filterByCategory'])->name('products.filterByCategory');

    Route::get('/products/filter', [ProductController::class, 'filter'])->name('products.filter');

    Route::get('stock-list', [StockController::class, 'index'])->name('stock.stock_list');

    Route::get('/stock/filter', [StockController::class, 'filter'])->name('stock.filter');

    //Route::delete('/stock/{id}', [StockController::class, 'destroy'])->name('stock.destroy');

    Route::get('/stock/shelf/{id}', [StockController::class, 'showByShelf'])->name('stock.showByShelf');

    Route::delete('/stock', [StockController::class, 'destroy'])->name('stock.destroy');

// routes/web.php
// routes/web.php
Route::post('/warehouse/save-selection', [WarehouseController::class, 'saveSelection'])->name('warehouse.saveSelection');
    /*Route::get('stock-list', function () {
        return view('stock.stock_list');
    })->name('stock.stock_list');*/

    Route::get('stock-movements', [StockMovementController::class, 'index'])->name('stock.stock_movements');
    Route::get('stock-movements/{id}', [StockMovementController::class, 'show'])->name('stock.stock_movement');

    


    Route::get('transfer-list', [TransferListController::class, 'index'])->name('transfer.transfer_list');

    Route::delete('transfer-destroy', [TransferListController::class, 'destroy'])->name('transfer.destroy');

    Route::post('transfer-store', [TransferItemController::class, 'store'])->name('transfer.store');

    Route::post('transfer-add', [TransferListController::class, 'add'])->name('transfer.add');
    Route::post('transfer-remove', [TransferListController::class, 'remove'])->name('transfer.remove');
    

    Route::get('transfer-incoming', [TransferItemController::class, 'incoming'])->name('transfer.transfer_incoming');
    Route::post('/transfer/inc',  [TransferItemController::class, 'transferSave'])->name('transfer.storeIncoming');
    Route::post('/transfer/bulk-inc', [TransferItemController::class, 'bulkStoreIncoming'])->name('transfer.bulkStoreIncoming');
    Route::post('/transfer/cancel', [TransferItemController::class, 'transferCancel'])->name('transfer.transferCancel');


    Route::get('transfer-outgoing', function () {
        return view('transfer.transfer_outgoing');
    })->name('transfer.transfer_outgoing');


    Route::prefix('transfers')->group(function () {
        Route::get('/{id}/edit', [TransferItemController::class, 'edit'])->name('transfer.edit');
        Route::get('/incoming', [TransferItemController::class, 'incoming'])->name('transfer.incoming');
        Route::get('/outgoing', [TransferItemController::class, 'outgoing'])->name('transfer.outgoing');
        Route::put('/{id}', [TransferItemController::class, 'update'])->name('transfer.update');
        //Route::delete('/{id}', [TransferItemController::class, 'destroy'])->name('transfer.destroy');
        Route::patch('/{id}/approve', [TransferController::class, 'approve'])->name('transfers.approve');
        // web.php
        Route::post('/add', [TransferListController::class, 'add'])->name('transfer.add');


    });





    Route::get('settings', function () {
        return view('settings.settings');
    })->name('settings.settings');

    Route::post('update-stock', [ProductController::class, 'updateStock'])->name('update.stock');


    Route::get('/user/warehouses', [WarehouseController::class, 'getUserWarehouses'])->name('user.warehouses');
    Route::get('factory-settings', [FactorySettingsController::class, 'index'])->name('factory.settings');
    Route::post('factory-settings/update', [FactorySettingsController::class, 'update'])->name('factory.settings.update'); // Değişiklik: Güncelleme rotası eklendi
    Route::post('/factory/settings/update/stock', [FactorySettingsController::class, 'updateStock'])->name('factory.settings.update.stock');

    Route::get('role-edit/{id}', function ($id) {
        return view('role.role_edit', compact('id'));
    })->name('role.role_edit');//->middleware('checkRole:Müdür,Yönetici');

    Route::get('user-list', [UserController::class, 'index'])->name('users.user_list');
    Route::get('user-add', [UserController::class, 'create'])->name('users.user_add');

    Route::get('roles', [RoleController::class, 'index'])->name('roles.index'); // Rol listesi
    Route::get('roles/create', function () {
        return view('role.role_create'); // Rol ekleme formu
    })->name('roles.create');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store'); // Rol ekleme
    Route::get('roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit'); // Rol düzenleme formu
    Route::put('roles/{id}', [RoleController::class, 'update'])->name('roles.update'); // Rol güncelleme
    Route::delete('roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy'); // Rol silme
});
Route::post('user_add', [UserController::class, 'store'])->name('users.store');

Route::delete('user-delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');

Route::get('/warehouse/select/{id}', [WarehouseController::class, 'selectWarehouse'])->name('warehouse.select');



Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');

//Route::post('password/email', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('password/reset', [LoginController::class, 'showResetForm'])->name('reset');

Route::post('/api/auth/forgot-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'sendResetLinkEmail']);
//Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('login.reset_password');

//Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('login.reset');



Route::post('/save-theme', [ThemeController::class, 'saveTheme'])->name('save.theme');





Route::get('/activity-list', [ActivityLogController::class, 'index'])->name('activity.activity_list');

Route::get('/statistics', [StatisticsController::class, 'index']);

















