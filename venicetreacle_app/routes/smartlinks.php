<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmartLinkController;

Route::get('/modern-elixir', [SmartLinkController::class, 'modernElixir'])->name('smartlinks.modern-elixir');
Route::get('/bad-aji', [SmartLinkController::class, 'badAji'])->name('smartlinks.bad-aji');
Route::get('/bad-aji-presave', [SmartLinkController::class, 'badAjiPresave'])->name('smartlinks.bad-aji-presave');

Route::get('/callbacks/spotify', [SmartLinkController::class, 'spotifyCallback'])->name('callbacks.spotify');