<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

Route::post('/reset', [AccountController::class, 'reset'])->name('account.reset');
