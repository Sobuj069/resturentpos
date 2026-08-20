<?php

use App\Http\Controllers\DeliveryWebhookController;
use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Route;

// Delivery Aggregators API Webhooks
Route::prefix('delivery')->group(function () {
    Route::post('/foodpanda/webhook', [DeliveryWebhookController::class, 'handleFoodpanda']);
    Route::post('/pathao/webhook', [DeliveryWebhookController::class, 'handlePathao']);
});

// Offline-First IndexedDB Batch Sync Endpoint
Route::post('/offline/batch-sync', [DeliveryWebhookController::class, 'batchSyncOfflineOrders']);

// POS API for Waiter Apps & Mobile Terminals
Route::get('/pos/catalog', [PosController::class, 'getCatalog']);
Route::post('/pos/order', [PosController::class, 'storeOrder']);
