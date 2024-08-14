<?php

use App\Http\Controllers\AboutUsSettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfigUserController;
use App\Http\Controllers\FooterContentController;
use App\Http\Controllers\JumbotronSettingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Middleware\EnsureTokenIsValid;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::get('/update-token', [AuthController::class, 'generateToken']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{productId}', [ProductController::class, 'show']);

Route::get('/footer-contents', [FooterContentController::class, 'index']);
Route::get('/footer-contents/{footerContentId}', [FooterContentController::class, 'show']);

Route::get('/jumbotron', [JumbotronSettingController::class, 'index']);
Route::get('/jumbotron/{jumbotronId}', [JumbotronSettingController::class, 'show']);

Route::get('/about-us', [AboutUsSettingController::class, 'index']);
Route::get('/about-us/{aboutUsId}', [AboutUsSettingController::class, 'show']);

Route::get('/social-media', [SocialMediaController::class, 'index']);
Route::get('/social-media/{socialMediaId}', [SocialMediaController::class, 'show']);

Route::get('/product-details', [ProductDetailController::class, 'index']);
Route::get('/product-details/{productDetailId}', [ProductDetailController::class, 'show']);

Route::middleware([EnsureTokenIsValid::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::apiResource('/products', ProductController::class)->except('update','index', 'show');
    Route::post('/products/{productId}', [ProductController::class, "update"]);
    Route::apiResource('/footer-contents', FooterContentController::class)->except('update','index', 'show');
    Route::apiResource('/jumbotron', JumbotronSettingController::class)->except('update','index', 'show');
    Route::post('/jumbotron/{jumbotronId}', [JumbotronSettingController::class, "update"]);
    Route::apiResource('/about-us', AboutUsSettingController::class)->except('update','index', 'show');
    Route::post('/about-us/{aboutUsId}', [AboutUsSettingController::class, "update"]);
    Route::apiResource('/social-media', SocialMediaController::class)->except('index', 'show');
    Route::apiResource('/product-details', ProductDetailController::class);
    Route::apiResource('/users', ConfigUserController::class)->except('update');
    Route::post('/users/{userId}', [ConfigUserController::class, "update"]);
    // Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    //     return $request->user();
    // });
});
