<?php

use Illuminate\Http\Request;

use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Header
// Accept: application/json
// Content-type: application/json

Route::middleware(['auth:api'])->group(function(){
    // needs auth token
    // user info
    Route::get('/user', [ApiController::class, 'getUserInfoApi']);

    //'name' => ['required', 'string', 'max:255'],
    //'surname' => ['required', 'string', 'max:255'],
    //'phone_number' => ['required', 'string', 'min:8'],
    //'street' => ['required', 'string'],
    //'city' => ['required', 'string'],
    //'postal_code' => ['required', 'string', 'regex:/([1-9][0-9]{3})/'],
    Route::post('/user/save', [ApiController::class, 'saveUserInfoApi']);

    Route::get('/user/rentals', [ApiController::class, 'getUserRentals']);

    // id - rent id
    Route::get('/user/rent/{id}', [ApiController::class, 'getRentInfo']);

    // id - product id
    Route::get('/rent/full/{id}', [ApiController::class, 'getFullDates']);

    // id - product id, date (Y-m-d), time_from (exp: 19:00), time_to, card_num (xxxx-xxxx-xxxx-xxxx), valid (MM/YY), ccv (xxx) 
    Route::post('/rent/pay', [ApiController::class, 'payRent']);
});

// body (email, password)
Route::post('/login', [ApiController::class, 'loginApi']);

//body (name, surname, email, password, phone_number, street,
// city, postal_code)
Route::post('/register', [ApiController::class, 'registerApi']);

// all categories
Route::get('/categories', [ApiController::class, 'getCategoriesApi']);

// all products for specific category
Route::get('/category/products/{id}', [ApiController::class, 'getCategoryProductsApi']);

