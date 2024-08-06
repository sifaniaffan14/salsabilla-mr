<?php

use App\Http\Controllers\AboutUsSettingController;
use App\Http\Controllers\FooterContentController;
use App\Http\Controllers\JumbotronSettingController;
use App\Http\Controllers\ProductController;
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

Route::apiResource('/products', ProductController::class);
Route::apiResource('/footer-contents', FooterContentController::class);
Route::apiResource('/jumbotron', JumbotronSettingController::class);
Route::apiResource('/about-us', AboutUsSettingController::class);
Route::apiResource('/social-media', SocialMediaController::class);
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
