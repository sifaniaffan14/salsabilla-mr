<?php

use App\Http\Controllers\AboutUsSettingController;
use App\Http\Controllers\FooterContentController;
use App\Http\Controllers\JumbotronSettingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\SocialMediaController;
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

Route::apiResource('/products', ProductController::class)->except('update');
Route::post('/products/{productId}', [ProductController::class, "update"]);
Route::apiResource('/footer-contents', FooterContentController::class);
Route::apiResource('/jumbotron', JumbotronSettingController::class)->except('update');
Route::post('/jumbotron/{jumbotronId}', [JumbotronSettingController::class, "update"]);
Route::apiResource('/about-us', AboutUsSettingController::class);
Route::apiResource('/social-media', SocialMediaController::class);
Route::apiResource('/product-details', ProductDetailController::class);
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
