<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchTransferController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeliveryHubController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\KdsController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\QrOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaaSController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\WaiterController;
use Illuminate\Support\Facades\Route;

// Authentication & SaaS Registration
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/auth/quick-switch', [AuthController::class, 'quickSwitch'])->name('auth.quickSwitch');

// SaaS Multi-Tenant Platform & Onboarding
Route::prefix('saas')->name('saas.')->group(function () {
    // Public Onboarding & Plans
    Route::get('/register', [SaaSController::class, 'registerForm'])->name('register');
    Route::post('/register', [SaaSController::class, 'register'])->name('register.submit');
    Route::get('/plans', [SaaSController::class, 'plans'])->name('plans');

    // SuperAdmin Protected Command Center
    Route::middleware(['auth', 'superadmin'])->group(function () {
        Route::get('/dashboard', [SaaSController::class, 'dashboard'])->name('dashboard');
        Route::post('/tenant/{tenant}', [SaaSController::class, 'updateTenant'])->name('tenant.update');
        Route::delete('/tenant/{tenant}', [SaaSController::class, 'deleteTenant'])->name('tenant.delete');
        Route::post('/impersonate/{user}', [SaaSController::class, 'impersonate'])->name('impersonate');
    });
});

// Leave Impersonation (Return to SuperAdmin)
Route::get('/leave-impersonation', [SaaSController::class, 'leaveImpersonation'])->name('impersonate.leave');

// Redirect root to POS
Route::get('/', function () {
    return redirect()->route('pos.index');
});

// 1. POS Billing Terminal
Route::prefix('pos')->name('pos.')->group(function () {
    Route::get('/', [PosController::class, 'index'])->name('index');
    Route::get('/catalog', [PosController::class, 'getCatalog'])->name('catalog');
    Route::get('/tables-live', [PosController::class, 'getLiveTables'])->name('tablesLive');
    Route::post('/order', [PosController::class, 'storeOrder'])->name('order.store');
    Route::post('/order/{order}/settle', [PosController::class, 'settlePayment'])->name('order.settle');
    Route::post('/shift/open', [PosController::class, 'openShift'])->name('shift.open');
    Route::post('/shift/{shift}/close', [PosController::class, 'closeShift'])->name('shift.close');
    Route::get('/order/{order}/mushak', [PosController::class, 'getMushakInvoice'])->name('order.mushak');
});

// 2. Menu, Categories, Items, Variants & Modifiers
Route::prefix('menu')->name('menu.')->group(function () {
    Route::get('/', [MenuController::class, 'index'])->name('index');
    Route::post('/category', [MenuController::class, 'storeCategory'])->name('category.store');
    Route::delete('/category/{category}', [MenuController::class, 'deleteCategory'])->name('category.delete');
    Route::post('/item', [MenuController::class, 'storeItem'])->name('item.store');
    Route::delete('/item/{item}', [MenuController::class, 'deleteItem'])->name('item.delete');
    Route::post('/modifier', [MenuController::class, 'storeModifier'])->name('modifier.store');
    Route::delete('/modifier/{modifier}', [MenuController::class, 'deleteModifier'])->name('modifier.delete');
});

// 3. Tables & Floor Layout Management + QR Cards
Route::prefix('tables')->name('tables.')->group(function () {
    Route::get('/', [TableController::class, 'index'])->name('index');
    Route::get('/live-status', [TableController::class, 'getLiveStatus'])->name('liveStatus');
    Route::post('/', [TableController::class, 'storeTable'])->name('store');
    Route::post('/{table}/status', [TableController::class, 'updateStatus'])->name('status');
    Route::delete('/{table}', [TableController::class, 'deleteTable'])->name('delete');
    Route::get('/qr-cards', [QrOrderController::class, 'printTableCards'])->name('qrCards');
});

// 4. Public QR Code Table Self-Ordering
Route::prefix('order/table')->name('qr.')->group(function () {
    Route::get('/{token}', [QrOrderController::class, 'showGuestMenu'])->name('menu');
    Route::post('/{token}/place', [QrOrderController::class, 'placeGuestOrder'])->name('place');
});

// 5. Customer CRM & Loyalty Points
Route::prefix('customers')->name('customers.')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('index');
    Route::get('/search', [CustomerController::class, 'search'])->name('search');
    Route::post('/', [CustomerController::class, 'store'])->name('store');
    Route::post('/{customer}/points', [CustomerController::class, 'adjustPoints'])->name('points');
    Route::post('/sms', [CustomerController::class, 'sendSms'])->name('sms');
    Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
});

// 6. Waiter / Captain Handheld Terminal
Route::prefix('waiter')->name('waiter.')->group(function () {
    Route::get('/', [WaiterController::class, 'index'])->name('index');
    Route::post('/transfer', [WaiterController::class, 'transferTable'])->name('transfer');
});

// 7. Daily Expenses & Profit/Loss (P&L)
Route::prefix('expenses')->name('expenses.')->group(function () {
    Route::get('/', [ExpenseController::class, 'index'])->name('index');
    Route::post('/', [ExpenseController::class, 'store'])->name('store');
    Route::get('/staff-ledger/{user}', [ExpenseController::class, 'staffLedger'])->name('staffLedger');
    Route::post('/category', [ExpenseController::class, 'storeCategory'])->name('category.store');
    Route::delete('/{expense}', [ExpenseController::class, 'destroy'])->name('destroy');
});

// 8. Multi-Branch Inter-Stock Transfers
Route::prefix('transfers')->name('transfers.')->group(function () {
    Route::get('/', [BranchTransferController::class, 'index'])->name('index');
    Route::post('/', [BranchTransferController::class, 'store'])->name('store');
    Route::post('/{transfer}/receive', [BranchTransferController::class, 'markReceived'])->name('receive');
});

// 9. Online Delivery Hub (Foodpanda & Pathao)
Route::prefix('delivery-orders')->name('delivery.')->group(function () {
    Route::get('/', [DeliveryHubController::class, 'index'])->name('index');
    Route::post('/{order}/status', [DeliveryHubController::class, 'updateDeliveryStatus'])->name('status');
});

// 10. Kitchen Display System (KDS)
Route::prefix('kds')->name('kds.')->group(function () {
    Route::get('/', [KdsController::class, 'index'])->name('index');
    Route::get('/tickets', [KdsController::class, 'getActiveTickets'])->name('tickets');
    Route::post('/item/{item}/status', [KdsController::class, 'updateItemStatus'])->name('item.status');
    Route::post('/order/{order}/bump', [KdsController::class, 'bumpOrder'])->name('order.bump');
});

// 11. Inventory & Recipe BOM
Route::prefix('inventory')->name('inventory.')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('index');
    Route::post('/ingredient', [InventoryController::class, 'storeIngredient'])->name('ingredient.store');
    Route::delete('/ingredient/{ingredient}', [InventoryController::class, 'deleteIngredient'])->name('ingredient.delete');
    Route::post('/purchase', [InventoryController::class, 'addPurchase'])->name('purchase.store');
    Route::post('/recipe', [InventoryController::class, 'saveRecipe'])->name('recipe.save');
    Route::post('/wastage', [InventoryController::class, 'recordWastage'])->name('wastage.store');
});

// 12. Reports & NBR Mushak 6.3 Register
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');
    Route::get('/shifts', [ReportController::class, 'shiftReport'])->name('shifts');
    Route::get('/mushak-6-3', [ReportController::class, 'mushakRegister'])->name('mushak');
    Route::get('/ai-insight', [ReportController::class, 'getAiInsight'])->name('ai.insight');
});

// 13. System, Branch & Staff Settings
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingController::class, 'index'])->name('index');
    Route::post('/branch', [SettingController::class, 'updateBranch'])->name('branch.update');
    Route::post('/user', [SettingController::class, 'storeUser'])->name('user.store');
    Route::delete('/user/{user}', [SettingController::class, 'deleteUser'])->name('user.delete');
});

// 14. AI Voice & Natural Language Order Parser
Route::post('/ai/parse-voice', [AiController::class, 'parseVoiceOrder'])->name('ai.parseVoice');
