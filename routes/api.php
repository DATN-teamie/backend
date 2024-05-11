<?php

use App\Http\Controllers\Auth\VerifyLogin;
use App\Http\Controllers\Board\CreateBoard;
use App\Http\Controllers\Board\GetListBoard;
use App\Http\Controllers\User\GetUser;
use App\Http\Controllers\User\UpdateUser;
use App\Http\Controllers\Workspace\CreateWorkspace;
use App\Http\Controllers\Workspace\GetDetailWorkspace;
use App\Http\Controllers\Workspace\GetListWorkspace;
use App\Http\Controllers\Workspace\UpdateWorkspace;
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

Route::get('/user', GetUser::class)->middleware('auth');
Route::put('/user', UpdateUser::class)->middleware('auth');

Route::post('/workspaces', CreateWorkspace::class)->middleware('auth');
Route::get('/workspaces', GetListWorkspace::class)->middleware('auth');
Route::get('/workspaces/{workspace_id}', GetDetailWorkspace::class)->middleware('auth');
Route::put('/workspaces/{workspace_id}', UpdateWorkspace::class)->middleware('auth');


Route::post('/boards', CreateBoard::class)->middleware('auth');
Route::get('/boards', GetListBoard::class)->middleware('auth');
