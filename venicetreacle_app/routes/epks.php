<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EpkController;

Route::get('/bad-aji-epk', [EpkController::class, 'badAji'])->name('epks.bad-aji-epk');
