<?php

use App\Http\Controllers\Auth\VerifyLogin;
use App\Http\Controllers\Board\CreateBoard;
use App\Http\Controllers\Board\DeleteBoard;
use App\Http\Controllers\Board\GetDetailBoard;
use App\Http\Controllers\Board\GetListBoard;
use App\Http\Controllers\Board\GetUsersInBoard;
use App\Http\Controllers\Board\GetUsersNotInBoard;
use App\Http\Controllers\Board\InviteUsersToBoard;
use App\Http\Controllers\Board\UpdateBoard;
use App\Http\Controllers\Container\CreateContainer;
use App\Http\Controllers\Container\DeleteContainer;
use App\Http\Controllers\Container\GetListContainer;
use App\Http\Controllers\Container\UpdateContainerTitle;
use App\Http\Controllers\Container\UpdatePositionContainer;
use App\Http\Controllers\Item\AddChecklistItem;
use App\Http\Controllers\Item\AddUsersToItem;
use App\Http\Controllers\Item\CreateItem;
use App\Http\Controllers\Item\DeleteItem;
use App\Http\Controllers\Item\GetChecklistItem;
use App\Http\Controllers\Item\GetDetailItem;
use App\Http\Controllers\Item\GetListItemAttachments;
use App\Http\Controllers\Item\GetUsersInItem;
use App\Http\Controllers\Item\GetUsersNotInItem;
use App\Http\Controllers\Item\UpdateChecklistItem;
use App\Http\Controllers\Item\UpdateItemAttachments;
use App\Http\Controllers\Item\UpdateItemOverview;
use App\Http\Controllers\Item\UpdatePositionItem;
use App\Http\Controllers\User\GetUser;
use App\Http\Controllers\User\UpdateUser;
use App\Http\Controllers\Workspace\AssignWspRole;
use App\Http\Controllers\Workspace\CreateWorkspace;
use App\Http\Controllers\Workspace\CreateWspRole;
use App\Http\Controllers\Workspace\DeleteUserInWsp;
use App\Http\Controllers\Workspace\DeleteWorkspace;
use App\Http\Controllers\Workspace\DeleteWspRole;
use App\Http\Controllers\Workspace\GetDetailRoleWsp;
use App\Http\Controllers\Workspace\GetDetailWorkspace;
use App\Http\Controllers\Workspace\GetListWorkspace;
use App\Http\Controllers\Workspace\GetListWspRole;
use App\Http\Controllers\Workspace\GetUsersInWorkspace;
use App\Http\Controllers\Workspace\GetUsersNotInWorkspace;
use App\Http\Controllers\Workspace\InviteUsersToWorkspace;
use App\Http\Controllers\Workspace\UpdateWorkspace;
use App\Http\Controllers\Workspace\UpdateWspRole;
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
Route::get('/workspaces/{workspace_id}', GetDetailWorkspace::class)->middleware(
    'auth'
);
Route::delete('/workspaces/{workspace_id}', DeleteWorkspace::class)->middleware(
    'auth'
);
Route::get(
    '/workspaces/{workspace_id}/users-not-in',
    GetUsersNotInWorkspace::class
)->middleware('auth');
Route::get(
    '/workspaces/{workspace_id}/users',
    GetUsersInWorkspace::class
)->middleware('auth');
Route::delete(
    '/workspaces/{workspace_id}/users/{user_id}',
    DeleteUserInWsp::class
)->middleware('auth');
Route::post(
    '/workspaces/{workspace_id}/invite',
    InviteUsersToWorkspace::class
)->middleware('auth');
Route::put('/workspaces/{workspace_id}', UpdateWorkspace::class)->middleware(
    'auth'
);
Route::post(
    '/workspaces/{workspace_id}/roles',
    CreateWspRole::class
)->middleware('auth');
Route::get(
    '/workspaces/{workspace_id}/roles',
    GetListWspRole::class
)->middleware('auth');
Route::get(
    '/workspaces/{workspace_id}/roles/{role_wsp_id}',
    GetDetailRoleWsp::class
)->middleware('auth');
Route::post(
    '/workspaces/{workspace_id}/roles/{role_wsp_id}',
    UpdateWspRole::class
)->middleware('auth');
Route::delete(
    '/workspaces/{workspace_id}/roles/{role_wsp_id}',
    DeleteWspRole::class
)->middleware('auth');
Route::post(
    '/workspaces/{workspace_id}/roles-assign',
    AssignWspRole::class
)->middleware('auth');

Route::post('/boards', CreateBoard::class)->middleware('auth');
Route::get('/boards', GetListBoard::class)->middleware('auth');
Route::get('/boards/{board_id}', GetDetailBoard::class)->middleware('auth');
Route::delete('/boards/{board_id}', DeleteBoard::class)->middleware('auth');
Route::get('/boards/{board_id}/users', GetUsersInBoard::class)->middleware(
    'auth'
);
Route::get(
    '/boards/{board_id}/users-not-in',
    GetUsersNotInBoard::class
)->middleware('auth');
Route::post('/boards/{board_id}/invite', InviteUsersToBoard::class)->middleware(
    'auth'
);
Route::put('/boards/{board_id}', UpdateBoard::class)->middleware('auth');

Route::post('/containers', CreateContainer::class)->middleware('auth');
Route::get('/containers', GetListContainer::class)->middleware('auth');
Route::post(
    '/containers/{container_id}',
    UpdateContainerTitle::class
)->middleware('auth');
Route::delete('/containers/{container_id}', DeleteContainer::class)->middleware(
    'auth'
);
Route::put('/containers-position', UpdatePositionContainer::class)->middleware(
    'auth'
);

Route::post('/items', CreateItem::class)->middleware('auth');
Route::get('/items/{item_id}', GetDetailItem::class)->middleware('auth');
Route::delete('/items/{item_id}', DeleteItem::class)->middleware('auth');
Route::put('/items-position', UpdatePositionItem::class)->middleware('auth');
Route::put('/items/{item_id}/overview', UpdateItemOverview::class)->middleware(
    'auth'
);
Route::get('/items/{item_id}/users', GetUsersInItem::class)->middleware('auth');
Route::get(
    '/items/{item_id}/users-not-in',
    GetUsersNotInItem::class
)->middleware('auth');

Route::post('/items/{item_id}/add-member', AddUsersToItem::class)->middleware(
    'auth'
);
Route::post(
    '/items/{item_id}/attachments',
    UpdateItemAttachments::class
)->middleware('auth');
Route::get(
    '/items/{item_id}/attachments',
    GetListItemAttachments::class
)->middleware('auth');
Route::post(
    '/items/{item_id}/checklist-items',
    AddChecklistItem::class
)->middleware('auth');
Route::get(
    '/items/{item_id}/checklist-items',
    GetChecklistItem::class
)->middleware('auth');
Route::post(
    '/items/{item_id}/checklist-items/{checklist_item_id}',
    UpdateChecklistItem::class
)->middleware('auth');
