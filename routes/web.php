<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Menu;
use App\Livewire\CategoryMenus;
use App\Livewire\EditProfile;
use App\Livewire\Customer\LoyaltyPoints;
use App\Livewire\Notification;
use App\Livewire\Customer\OrderHistory;
use App\Livewire\Orders;
use App\Livewire\OrdersPaymentSuccess;
use App\Livewire\Payment;
use App\Livewire\Profile;
use App\Livewire\ProfilePage;
use App\Livewire\Customer;

use App\Livewire\Kitchen\KitchenOrders;

use App\Livewire\Customer\Success;
use App\Livewire\Customer\TableReservation;
use App\Livewire\Host\ServingOrders;

Route::get('lang/{lang}', function ($locale) {
    session()->put("locale", $locale);
    return redirect()->back();
})->name("locale");


Route::get('orders', Orders::class) ->name('orders');
Route::get('payment', Payment::class) ->name('payment');
Route::get('success', Success::class) ->name('success');
Route::get('cancel', Payment::class) ->name('cancel');
Route::get('profilepage', ProfilePage::class) ->name('profilepage');
Route::get('orderspaymentsuccess', OrdersPaymentSuccess::class) ->name('orderspaymentsuccess');
Route::get('profile', Profile::class) ->name('profile');

Route::get('notification', Notification::class) ->name('notification');
Route::get('editprofile', EditProfile::class) ->name('editprofile');

Route::prefix('/customers/orders')->name('customers.orders.')->group(function () {
    Route::get('/tables/{floorTable}', Customer\Home::class)
    ->name('tables')
    ->middleware('signed');
    Route::get('/home', Customer\Home::class)
    ->name('home');
    
    Route::get('/view', Orders::class)->name('orders');
    Route::get('/cart', Customer\Cart::class) ->name('cart');
    Route::get('/favorites', Customer\Favorite::class) ->name('favorites');
    Route::get('/search', Customer\SearchMenu::class) ->name('search');
    Route::get('/login', Customer\Login::class) ->name('login');
    Route::get('/signup', Customer\SignUp::class) ->name('signup');
    Route::get('/orderhistory', Customer\OrderHistory::class) ->name('orderhistory');
    Route::get('/tablereservation', Customer\TableReservation::class) ->name('tablereservation');
    Route::get('/loyaltypoints', Customer\LoyaltyPoints::class) ->name('loyaltypoints');
});

Route::prefix('/kitchen/orders')->name('kitchen.orders.')->group(function () {
    Route::get('/show', KitchenOrders::class) ->name('kitchenorders');

});

Route::prefix('/host/orders')->name('host.orders.')->group(function () {
    Route::get('/show', ServingOrders::class) ->name('servingorders');
    Route::get('/', ServingOrders::class) ->name('servingorders');

});

Route::get('/recommended-data', 'RecommendedDataController@index');