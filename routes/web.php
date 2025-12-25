<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShoppingCartController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    Route::resource('products', ProductController::class);

    Route::prefix('shopping-cart')->name('shopping-cart.')->controller(ShoppingCartController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('add/{product}', 'add')->name('add');
        Route::delete('remove/{shoppingCartProduct}', 'remove')->name('remove');
        Route::patch('update/{shoppingCartProduct}', 'update')->name('update');
    });

    Route::prefix('checkout')->name('checkout.')->controller(CheckoutController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('process', 'process')->name('process');
    });
});
