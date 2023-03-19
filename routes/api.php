<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//Buyeres
Route::resource('buyers','Buyer\BuyerController')->except(['create','edit']);

//Categories
Route::resource('categories','Category\CategoryController')->except(['create','edit']);

//Products
Route::resource('products','Product\ProductController')->only(['index','show']);

//Transactions
Route::resource('transactions','Transaction\TransactionController')->only(['index','show']);

//Sellers
Route::resource('sellers','Seller\SellerController')->only(['index','show']);

//User
Route::resource('user','User\UserController')->except(['create','edit']);