<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ItemController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [ItemController::class, 'index']);
Route::get('/items', [ItemController::class, 'fetchItems'])->name('items.fetch');
Route::post('/items', [ItemController::class, 'store'])->name('items.store');
Route::patch('/items/{id}/status', [ItemController::class, 'updateStatus'])->name('items.updateStatus');
Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
Route::get('/items/selected', [ItemController::class, 'fetchSelectedItems'])->name('items.selected');
Route::patch('/items/{id}', [ItemController::class, 'update'])->name('items.update');
