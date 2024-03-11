<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the 'api' middleware group. Enjoy building your API!
|
*/

// Authentication routes
Route::prefix('auth')->group(function () {
    //Admin authentication routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/changePassword', [AuthController::class, 'changePassword']);
        Route::post('/changeUserData', [AuthController::class, 'changeUserData']);
        Route::get('/currentUser', [AuthController::class, 'get']);
    });

    //Client authentication routes
    Route::post('/client/register', [ClientController::class, 'register']);
    Route::post('/client/login', [ClientController::class, 'login']);
    Route::middleware(['auth:sanctum', 'abilities:client'])->group(function () {
        Route::post('/client/logout', [ClientController::class, 'logout']);
    });
});

//Admin Routes  
Route::middleware(['auth:sanctum'])->group(function () {

    //Dashboard Routes
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'getStats']);
    });

    //Clients Routes
    Route::prefix('clients')->group(function () {
        Route::get(
            '/all',
            [ClientController::class, 'index']
        );
        Route::get('/get/{id}', [ClientController::class, 'get']);
        Route::patch('/changeStatus/{id}', [ClientController::class, 'changeStatus']);
        Route::delete('/{id}', [ClientController::class, 'destroy']);
    });

    //Categories Routes
    Route::prefix('categories')->group(function () {
        // Route::get('/all', [CategoryController::class, 'all']);
        Route::get('/get/{id}', [CategoryController::class, 'get']);
        Route::get('/search', [CategoryController::class, 'search']);
        Route::post('/add', [CategoryController::class, 'add']);
        Route::post('/update/{id}', [CategoryController::class, 'update']);
        Route::patch('/changeStatus/{id}', [CategoryController::class, 'changeStatus']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    //Products Routes 
    Route::prefix('products')->group(function () {
        Route::get('/all', [ProductController::class, 'all']);
        Route::get('/images/{id}', [ProductController::class, 'getImages']);
        Route::post('/add', [ProductController::class, 'add']);
        Route::get('/category/{id}', [ProductController::class, 'getByCategory']);
        Route::post('/update/{id}', [ProductController::class, 'update']);
        Route::patch('/changeStatus/{id}', [ProductController::class, 'changeStatus']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });

    //Orders Routes
    Route::prefix('orders')->group(function () {
        Route::get('/all', [OrderController::class, 'all']);
        Route::get('/get/{id}', [OrderController::class, 'get']);
        Route::get('/client/get/{id}', [
            OrderController::class,
            'getClientOrders'
        ]);
        Route::patch('/pend/{id}', [OrderController::class, 'pend']);
        Route::patch('/fulfill/{id}', [OrderController::class, 'fulfill']);
        Route::patch('/cancel/{id}', [OrderController::class, 'cancel']);
        Route::delete('/{id}', [OrderController::class, 'destroy']);
    });
});

Route::get('/categories/all', [CategoryController::class, 'all']);
Route::post('/orders/add', [OrderController::class, 'add']);
Route::get('/get/{id}', [ProductController::class, 'get']);
