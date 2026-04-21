<?php

use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\MetadataController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProductController;
use App\Models\Banner;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        $admin = Auth::guard('admin')->user();
        $stats = [
            'agents' => User::query()->where('role', 'agent')->count(),
            'products' => Product::query()->where('type', 'product')->count(),
            'services' => Product::query()->where('type', 'service')->count(),
            'banners' => Banner::query()->count(),
        ];

        return view('admin.dashboard', compact('admin', 'stats'));
    })->name('dashboard');

    Route::resource('agents', AgentController::class)->except('show')->parameters([
        'agents' => 'agent',
    ]);

    Route::resource('products', ProductController::class)->except('show');

    Route::get('banners', [BannerController::class, 'index'])->name('banners.index');
    Route::post('banners', [BannerController::class, 'store'])->name('banners.store');
    Route::delete('banners/{banner}', [BannerController::class, 'destroy'])->name('banners.destroy');

    Route::resource('metadata', MetadataController::class)->except('show');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('settings/password', [ProfileController::class, 'editPassword'])->name('settings.password.edit');
    Route::put('settings/password', [ProfileController::class, 'updatePassword'])->name('settings.password.update');

    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
