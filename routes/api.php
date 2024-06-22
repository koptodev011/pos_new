<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;
use App\Http\Controllers\API\AppAuthController;
use App\Http\Controllers\API\FavoriteController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\OrderHistory;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\Api\LoyaltyController;

Route::post('/login', [Api\AuthController::class, 'login']);
Route::post('/forgot-password', [Api\AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [Api\AuthController::class, 'resetPassword']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile', [Api\AuthController::class, 'profile']);
    Route::post('/logout', [Api\AuthController::class, 'logout']);
    Route::post('/change-password', [Api\AuthController::class, 'changePassword']);

    Route::middleware(['role:Super Admin'])->group(function () {
        Route::get('/users',[Api\UserController::class,'index']);
        Route::post('/users',[Api\UserController::class,'save']);
        Route::get('/tenants',[Api\TenantController::class,'index']);
        Route::post('/tenants',[Api\TenantController::class,'save']);
        Route::get('/roles',[Api\UserController::class,'roles']);
        Route::post('/add_role',[Api\UserController::class,'add_role']);

    });
    Route::middleware(['role:Owner|Manager'])->group(function () {
        Route::get('/floor-tables', [Api\FloorTableController::class, 'index']);
        Route::post('/floor-tables', [Api\FloorTableController::class, 'save']);
        Route::get('/menus', [Api\MenuController::class, 'index']);

        // Cart
        Route::prefix('/cart')->group(function () {
            Route::post('/adjust', [Api\CartController::class, 'adjust']);
            Route::post('/floor-table', [Api\CartController::class, 'setFloorTable']);
            Route::post('/summary', [Api\CartController::class, 'summary']);
        });


        // Order
        Route::prefix('/orders')->group(function () {
            Route::post('/place', [Api\OrderController::class, 'place']);
            Route::get('/pending', [Api\OrderController::class, 'progressList']);
            Route::post('/{order}/payment', [Api\OrderController::class, 'addPayment']);
            Route::post('/{order}/cancel', [Api\OrderController::class, 'cancel']);
        });



    });

});

//Login APIS
Route::post('/register',[Api\AppAuthController::class,'register']);
Route::post('/login',[Api\AppAuthController::class,'login']);
Route::post('/sendOtp',[Api\AppAuthController::class,'sendOtp']);//Sending Otp to the mail
Route::post('/verifyOtp',[Api\AppAuthController::class,'veryfyOtp']);
Route::post('/resetPassword',[Api\AppAuthController::class,'resetPassword']);

Route::middleware('auth:sanctum')->prefix('/orders')->group(function () {
    Route::post('/changePassword',[Api\AppAuthController::class,'changePassword']);
    Route::post('/logOut',[Api\AppAuthController::class,'logout']);
});

//Home page APIS
Route::get('/homePage',[Api\MenuController::class,'getMenuData']);
Route::get('/categories',[Api\MenuController::class,'getCategoriesData']);
Route::get('/mean',[Api\MenuController::class,'index1']);



//This is used to fetch all data from faorites
Route::middleware('auth:sanctum')->prefix('customer')->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/addfavorite/{menuID}', [FavoriteController::class, 'addFavorite']);
});


//Profile Apis
Route::middleware('auth:sanctum')->prefix('customer')->group(function () {
    Route::get('/profileData', [ProfileController::class, 'ProfileData']);
    Route::post('/update', [ProfileController::class, 'update']); 
    // Route::get('/orderHistory',[OrderHistory::class,'orderHistory']);
    Route::post('/send-ebill', [OrderHistory::class,'sendEBill']);
    Route::post('/download-bill', [OrderHistory::class,'download']);
});
Route::post('/AddToCart',[CartController::class,'AddToCart']);

//Cart Section Routes
Route::middleware('auth:sanctum')->prefix('customer')->group(function () {
    Route::post('/AddToCart',[CartController::class,'AddToCart']);
    Route::post('/AllOrderDetails',[Api\CartController::class,'AllOrderDetails']);//Fetching All cart items
    Route::post('/placeOrder',[Api\OrderController::class,'placeOrder']);
});


Route::middleware('auth:sanctum')->prefix('customer')->group(function () {
    Route::post('/OrderList',[Api\OrderController::class,'OrderList']);
    Route::post('/PayNow',[Api\OrderController::class,'PayNow']);
    Route::post('/order-summary',[Api\PaymentController::class,'render']);
   
});


Route::middleware('auth:sanctum')->prefix('/orders')->group(function () {
    // Route::get('/{order}/show', [PaymentController::class, 'show']);
    // Route::get('/{order}/waiterTip', [PaymentController::class, 'waiterTip']);
    // Route::get('/{order}/applycoupon', [PaymentController::class, 'applyCoupon']);
    Route::get('/OrderHistory',[Api\PaymentController::class,'OrderHistory']);
    Route::post('/applycoupon', [PaymentController::class, 'applyCoupon']);
    Route::get('/{order}/customTip', [PaymentController::class, 'CustomTip']);
    // Route::get('/{order}/render', [PaymentController::class, 'render']);
    Route::get('/singlePaymentDetails', [Api\PaymentController::class, 'paymentDetails']);
    Route::get('/spiltPaymentDetails', [Api\PaymentController::class, 'paymentDetails123']);
    // Route::get('/payment',[Api\PaymentController::class,'payment']);
    
});

Route::post('/waiterTip', [PaymentController::class, 'waiterTip']);
Route::get('/show', [Api\OrderController::class, 'show']);



//LoyaltyPoints Apis



Route::get('/LoyaltyPointsDetails', [Api\LoyaltyController::class, 'loyaltyPointsDetails']);



Route::middleware('auth:sanctum')->prefix('/orders')->group(function () {
    Route::post('/getLoyalty', [Api\LoyaltyController::class, 'getLoyalty']); 
    Route::post('/applyLoyaltyPoints', [Api\LoyaltyController::class, 'applyLoyaltyPoints']); 

});













