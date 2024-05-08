<?php

use App\Http\Controllers\Auth\VerifyLogin;
use App\Http\Controllers\Workspace\CreateWorkspace;
use App\Http\Controllers\Workspace\getDetailWorkspace;
use App\Http\Controllers\Workspace\getListWorkspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/verify-login', VerifyLogin::class);

Route::post('/workspace', CreateWorkspace::class)->middleware('auth');
Route::get('/workspace', getListWorkspace::class)->middleware('auth');
Route::get('/workspace/{workspace_id}', getDetailWorkspace::class)->middleware('auth');
