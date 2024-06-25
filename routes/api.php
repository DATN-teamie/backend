<?php

use App\Http\Controllers\Auth\ResendEmailVerify;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\VerifyLogin;
use App\Http\Controllers\Board\AssignBoardRole;
use App\Http\Controllers\Board\CreateBoard;
use App\Http\Controllers\Board\CreateBoardRole;
use App\Http\Controllers\Board\DeleteBoard;
use App\Http\Controllers\Board\DeleteBoardRole;
use App\Http\Controllers\Board\DeleteUserInBoard;
use App\Http\Controllers\Board\GetDetailBoard;
use App\Http\Controllers\Board\GetDetailRoleBoard;
use App\Http\Controllers\Board\GetListBoard;
use App\Http\Controllers\Board\GetListBoardRole;
use App\Http\Controllers\Board\GetUsersInBoard;
use App\Http\Controllers\Board\GetUsersNotInBoard;
use App\Http\Controllers\Board\InviteUsersToBoard;
use App\Http\Controllers\Board\LeaveBoard;
use App\Http\Controllers\Board\UpdateBoard;
use App\Http\Controllers\Board\UpdateBoardRole;
use App\Http\Controllers\Container\CreateContainer;
use App\Http\Controllers\Container\DeleteContainer;
use App\Http\Controllers\Container\GetListContainer;
use App\Http\Controllers\Container\UpdateContainerTitle;
use App\Http\Controllers\Container\UpdatePositionContainer;
use App\Http\Controllers\Item\AddChecklistItem;
use App\Http\Controllers\Item\AddUsersToItem;
use App\Http\Controllers\Item\CreateItem;
use App\Http\Controllers\Item\DeleteAttachment;
use App\Http\Controllers\Item\DeleteChecklist;
use App\Http\Controllers\Item\DeleteItem;
use App\Http\Controllers\Item\DeleteItemMember;
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
use App\Http\Controllers\User\GetUserById;
use App\Http\Controllers\User\ResetPassword;
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
use App\Http\Controllers\Workspace\LeaveWorkspace;
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

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class);
Route::post('/resend-email-verify', ResendEmailVerify::class)->middleware(
    'throttle:6,1'
);

Route::middleware(['auth', 'auth.session', 'verified'])->group(function () {
    Route::get('/user', GetUser::class);
    Route::get('/user/{user_id}', GetUserById::class);
    Route::put('/user', UpdateUser::class);
    Route::post('/reset-password', ResetPassword::class);

    Route::post('/workspaces', CreateWorkspace::class);
    Route::get('/workspaces', GetListWorkspace::class);
    Route::get('/workspaces/{workspace_id}', GetDetailWorkspace::class);
    Route::delete('/workspaces/{workspace_id}', DeleteWorkspace::class);
    Route::get('/workspaces/{workspace_id}/leave', LeaveWorkspace::class);
    Route::get(
        '/workspaces/{workspace_id}/users-not-in',
        GetUsersNotInWorkspace::class
    );
    Route::get('/workspaces/{workspace_id}/users', GetUsersInWorkspace::class);
    Route::delete(
        '/workspaces/{workspace_id}/users/{user_id}',
        DeleteUserInWsp::class
    );
    Route::post(
        '/workspaces/{workspace_id}/invite',
        InviteUsersToWorkspace::class
    );
    Route::put('/workspaces/{workspace_id}', UpdateWorkspace::class);
    Route::post('/workspaces/{workspace_id}/roles', CreateWspRole::class);
    Route::get('/workspaces/{workspace_id}/roles', GetListWspRole::class);
    Route::get(
        '/workspaces/{workspace_id}/roles/{role_wsp_id}',
        GetDetailRoleWsp::class
    );
    Route::post(
        '/workspaces/{workspace_id}/roles/{role_wsp_id}',
        UpdateWspRole::class
    );
    Route::delete(
        '/workspaces/{workspace_id}/roles/{role_wsp_id}',
        DeleteWspRole::class
    );
    Route::post(
        '/workspaces/{workspace_id}/roles-assign',
        AssignWspRole::class
    );

    Route::post('/boards', CreateBoard::class);
    Route::get('/boards', GetListBoard::class);
    Route::get('/boards/{board_id}', GetDetailBoard::class);
    Route::delete('/boards/{board_id}', DeleteBoard::class);
    Route::get('/boards/{board_id}/leave', LeaveBoard::class);
    Route::get('/boards/{board_id}/users', GetUsersInBoard::class);
    Route::get('/boards/{board_id}/users-not-in', GetUsersNotInBoard::class);
    Route::post('/boards/{board_id}/invite', InviteUsersToBoard::class);
    Route::delete(
        '/boards/{board_id}/users/{user_id}',
        DeleteUserInBoard::class
    );
    Route::put('/boards/{board_id}', UpdateBoard::class);
    Route::get('/boards/{board_id}/roles', GetListBoardRole::class);
    Route::post('/boards/{board_id}/roles', CreateBoardRole::class);
    Route::get(
        '/boards/{board_id}/roles/{role_board_id}',
        GetDetailRoleBoard::class
    );
    Route::delete(
        '/boards/{board_id}/roles/{role_board_id}',
        DeleteBoardRole::class
    );
    Route::post(
        '/boards/{board_id}/roles/{role_board_id}',
        UpdateBoardRole::class
    );
    Route::post('/boards/{board_id}/roles-assign', AssignBoardRole::class);

    Route::post('/containers', CreateContainer::class);
    Route::get('/containers', GetListContainer::class);
    Route::post('/containers/{container_id}', UpdateContainerTitle::class);
    Route::delete('/containers/{container_id}', DeleteContainer::class);
    Route::put('/containers-position', UpdatePositionContainer::class);

    Route::post('/items', CreateItem::class);
    Route::get('/items/{item_id}', GetDetailItem::class);
    Route::delete('/items/{item_id}', DeleteItem::class);
    Route::put('/items-position', UpdatePositionItem::class);
    Route::put('/items/{item_id}/overview', UpdateItemOverview::class);
    Route::get('/items/{item_id}/users', GetUsersInItem::class);
    Route::get('/items/{item_id}/users-not-in', GetUsersNotInItem::class);

    Route::post('/items/{item_id}/add-member', AddUsersToItem::class);
    Route::post('/items/{item_id}/attachments', UpdateItemAttachments::class);
    Route::get('/items/{item_id}/attachments', GetListItemAttachments::class);
    Route::post('/items/{item_id}/checklist-items', AddChecklistItem::class);
    Route::get('/items/{item_id}/checklist-items', GetChecklistItem::class);
    Route::post(
        '/items/{item_id}/checklist-items/{checklist_item_id}',
        UpdateChecklistItem::class
    );

    Route::delete(
        '/items/{item_id}/user-in-item/{user_id}',
        DeleteItemMember::class
    );
    Route::delete('/item-attachments/{attachment_id}', DeleteAttachment::class);
    Route::delete('/checklist-items/{checklist_id}', DeleteChecklist::class);
});
