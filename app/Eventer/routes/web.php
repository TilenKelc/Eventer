<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\RentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TestController;

Auth::routes();
//Auth::routes(['verify' => true]);

/*
Route::middleware(['auth', 'agent'])->group(function(){

});
*/

Route::middleware(['auth', 'admin'/*, 'verified'*/])->group(function(){
    // Agents
    Route::get('/agent/add', [AgentController::class, 'addNewAgent']);
    Route::post('/agent/save', [AgentController::class, 'saveNewAgent']);
    Route::get('/agent/edit/{id}', [AgentController::class, 'editAgent']);
    Route::post('/agent/save/{id}', [AgentController::class, 'saveUpdatedAgent']);
    Route::post('/agent/delete/{id}', [AgentController::class, 'delete']);

    Route::get('/agent/index', [AgentController::class, 'index']);
    Route::get('/agent/all', [AgentController::class, 'getAgents']);

    // Company
    Route::get('/company/edit', [CompanyController::class, 'show']);
    Route::post('/company/save', [CompanyController::class, 'saveUpdatedData']);
});

Route::middleware(['auth', 'staff'/*, 'verified'*/])->group(function(){
    // Admin product panel
    Route::get('/product/admin/index', [ProductController::class, 'index']);
    Route::get('/product/all', [ProductController::class, 'getProducts']);

    // Agent
    Route::get('/rent/show/{status}', [AgentController::class, 'showRentals']);
    Route::get('/rent/get/{status}', [AgentController::class, 'getRentals']);


    Route::get('/rent/remove/{id}', [RentController::class, 'getRemoveRentalView']);
    Route::post('/rent/remove/{id}', [RentController::class, 'postRemoveRental']);

    // Rent
    /*
    Route::get('/rent/add', [RentController::class, 'addNewRent']);
    Route::post('/rent/save', [RentController::class, 'saveNewRent']);
    */

    // Category
    Route::get('/category/add', [CategoryController::class, 'addNewCategory'])->name('category/add');
    Route::get('/category/edit/{id}', [CategoryController::class, 'editCategory']);
    Route::post('/category/save', [CategoryController::class, 'saveNewCategory']);
    Route::post('/category/save/{id}', [CategoryController::class, 'saveUpdatedCategory']);

    Route::get('/category/admin/index', [CategoryController::class, 'index']);
    Route::get('/category/all', [CategoryController::class, 'getCategories']);
    Route::post('/category/delete/{id}', [CategoryController::class, 'delete']);

    // Products
    Route::get('/product/add', [ProductController::class, 'addNewProduct'])->name('product/add');
    Route::get('/product/edit/{id}', [ProductController::class, 'editProduct']);
    Route::post('/product/save', [ProductController::class, 'saveNewProduct']);
    Route::post('/product/save/{id}', [ProductController::class, 'saveUpdatedProduct']);
    Route::post('/product/fetch', [ProductController::class, 'fetch'])->name('product.fetch');

    Route::get('/product/delete/{id}', [ProductController::class, 'delete']);

    // User
    Route::get('/user/show/all', [UserController::class, 'showAll']);
    Route::get('/user/get/all', [UserController::class, 'getAllUsers']);
});

Route::middleware(['auth'/*,'verified'*/])->group(function(){
    // Cart
    Route::get('/cart', [RentController::class, 'showCart']);

    // Payment
    Route::get('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment/{id}', [PaymentController::class, 'showPayment']);
    Route::get('/payment_redirect', [PaymentController::class, 'redirect']);

    Route::get('/payment/refund/{id}', [PaymentController::class, 'refundPayment'])->name('payment.refund');
    // dodatna routa za refund renta pred izbrisom
    Route::get('/payment/refund/remove/{id}', [PaymentController::class, 'refundRemovePayment']);

    //Route::get('/test', [PaymentController::class, 'test']);

    // fetch customers
    //Route::post('/autocomplete/fetch', [RentController::class, 'fetch'])->name('autocomplete.fetch');

    // Rent
    Route::get('/myrents', [RentController::class, 'showUserRents']);
    Route::get('/rent/edit/{id}', [RentController::class, 'editRent']);
    Route::post('/rent/save/{id}', [RentController::class, 'saveUpdatedRent']);

    // Remove product from cart
    Route::get('/removefromcart/{id}', [RentController::class, 'removeFromCart']);


    // Reservation
    Route::get('/reservation/show/{id}', [ReservationController::class, 'show']);
    Route::get('/reservation/full', [ReservationController::class, 'getFullDates'])->name('reservation.full');

    Route::post('/reservation/add', [ReservationController::class, 'addNewReservation']);

    Route::get('/reservation/add/{id}', [ReservationController::class, 'addProductToReservation']);
    // Route::get('/reservation/delete', [ReservationController::class, 'delete']);

    // User edit
    Route::get('/user/edit/{id}', [UserController::class, 'showEdit']);
    Route::post('/user/save/{id}', [UserController::class, 'saveUpdatedUser']);
    //Route::get('/user/get_rent', [UserController::class, 'getUserRentals']);
    Route::get('/user/get_rent/{id}', [UserController::class, 'getUserRentals']);

});

Route::get('/', 'HomeController@index');
Route::get('/home-bikes', 'HomeController@index');

// Category
Route::get('/category/index', [CategoryController::class, 'showCategories']);
Route::get('/category/{id}', [CategoryController::class, 'showCategoryProducts']);

// All available products by selected date
Route::get('/products/bydate', [ProductController::class, 'showAllProductsByDate']);

// Product
Route::get('/product/{id}', [ProductController::class, 'showProduct']);

// Search
Route::post('/reservation/set', [ReservationController::class, 'setReservationSession']);

// Reset dates
Route::get('/reservation/delete', [ReservationController::class, 'delete']);

// Common
Route::get("/pravila-in-pogoji-poslovanja", function(){
   return View::make("common.pravilapogoji");
});

Route::get("/cenik", function(){
   return View::make("common.cenik");
});



// oddamo danes
// oddamo v 7 dneh
// vračilo danes



// jr eloquent test
// Route::get('/category/withdates/{id}', [TestController::class, 'showCategoryProductsWithDates']);
// Route::get('/kategorija/zdatumi/{id}', [TestController::class, 'showCategoryProductsWithDates']);
