<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\BlacklistController;
use App\Http\Controllers\UserLogController;


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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('users')->middleware(['auth', 'verified', 'checkRole:Administrator'])->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/export', [UserController::class, 'export'])->name('users.export');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/{user}/invite', [UserController::class, 'invite'])->name('users.invite');
});

Route::prefix('emails')->middleware(['auth', 'verified', 'checkRole:Administrator'])->group(function () {
    Route::get('/', [EmailController::class, 'index'])->name('emails.index');
    Route::get('/export', [EmailController::class, 'export'])->name('emails.export');
    Route::get('/{id}', [EmailController::class, 'show'])->name('emails.show');
});

Route::prefix('blacklist')->middleware(['auth', 'verified', 'checkRole:Administrator'])->group(function () {
    Route::get('/', [BlacklistController::class, 'index'])->name('blacklist.index');
    Route::get('/export', [BlacklistController::class, 'export'])->name('blacklist.export');
});

Route::prefix('user-logs')->middleware(['auth', 'verified', 'checkRole:Administrator'])->group(function () {
    Route::get('/', [UserLogController::class, 'index'])->name('user-logs.index');
    Route::get('/export', [UserLogController::class, 'export'])->name('user-logs.export');
});

Route::prefix('callback-logs')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [\App\Http\Controllers\CallbackLogController::class, 'index'])->name('callback-logs.index');
    Route::get('/export', [\App\Http\Controllers\CallbackLogController::class, 'export'])->name('callback-logs.export');
});

Route::prefix('presaves')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [\App\Http\Controllers\PresaveController::class, 'index'])->name('presaves.index');
    Route::get('/export', [\App\Http\Controllers\PresaveController::class, 'export'])->name('presaves.export');
});

Route::prefix('page-visits')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [\App\Http\Controllers\PageVisitController::class, 'index'])->name('page-visits.index');
    Route::get('/export', [\App\Http\Controllers\PageVisitController::class, 'export'])->name('page-visits.export');
});


require __DIR__.'/auth.php';
