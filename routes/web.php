<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('/pay')->group(function () {
        Route::get('{order}', [OrderController::class, 'create'])
            ->whereNumber('order')->name('pay');

        Route::get('/result', [OrderController::class, 'store']);

        Route::get('/receipt/{transaction}', [OrderController::class, 'show'])->name('receipt');
        Route::get('/transactions', [OrderController::class, 'index'])->name('transactions');
    });
});

require __DIR__ . '/auth.php';
