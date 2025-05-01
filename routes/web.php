<?php
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

// Home atau dashboard (opsional)
Route::get('/', [UserController::class, 'main'])->name('index');

// ========== CATEGORY ==========
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/json', [CategoryController::class, 'json'])->name('categories.json');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
Route::post('/categories/restore/{id}', [CategoryController::class, 'restore'])->name('categories.restore');
Route::delete('/categories/force-delete/{id}', [CategoryController::class, 'forceDelete'])->name('categories.forceDelete');

// ========== LOCATION ==========
Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
Route::get('/locations/json', [LocationController::class, 'json'])->name('locations.json');
Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
Route::put('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');
Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');
Route::post('/locations/restore/{id}', [LocationController::class, 'restore'])->name('locations.restore');
Route::delete('/locations/force-delete/{id}', [LocationController::class, 'forceDelete'])->name('locations.forceDelete');

// ========== ITEM ==========
Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/json', [ItemController::class, 'json'])->name('items.json');
Route::post('/items', [ItemController::class, 'store'])->name('items.store');
Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
Route::post('/items/restore/{id}', [ItemController::class, 'restore'])->name('items.restore');
Route::delete('/items/force-delete/{id}', [ItemController::class, 'forceDelete'])->name('items.forceDelete');
