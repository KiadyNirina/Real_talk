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

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\ChatController;

use App\Http\Controllers\Invitations\AcceptInvitationController;
use App\Http\Controllers\Invitations\CheckAllUsersFriendStatusController;
use App\Http\Controllers\Invitations\CheckFriendStatusController;
use App\Http\Controllers\Invitations\RejectInvitationController;
use App\Http\Controllers\Invitations\SendInvitationController;
use App\Http\Controllers\Invitations\CancelInvitationController;

use App\Http\Controllers\Friends\GetFriendsController;
use App\Http\Controllers\Friends\GetUserFriendOnlineController;
use App\Http\Controllers\Friends\RemoveFriendController;

/* Authentication */
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');



Route::middleware('auth:sanctum')->group(function () {
    // All users
    Route::get('/users', [UserController::class, 'getUser']);
    
    // Selected user
    Route::get('/user/{id}', [UserController::class, 'show']);
    
    // Update user
    Route::put('/user/update', [UserController::class, 'updateUser']);
});

/* Send friend request */
Route::post('/invitations', SendInvitationController::class)->middleware('auth:sanctum');
/* Accept friend request */
Route::post('/invitations/{invitation}/accept', AcceptInvitationController::class)->middleware('auth:sanctum');
/* Reject friend request */
Route::post('/invitations/{invitation}/reject', RejectInvitationController::class)->middleware('auth:sanctum');
// Cancel friend request
Route::post('/invitations/{invitationId}/cancel', CancelInvitationController::class)->middleware('auth:sanctum');
/* Show friend status of user selected */
Route::get('/friends/status/{selectedUserId}', CheckFriendStatusController::class)->middleware('auth:sanctum');
/* Show friend status of all users */
Route::get('/friends/status', CheckAllUsersFriendStatusController::class)->middleware('auth:sanctum');
/* Friends of user */
Route::get('/friends', GetFriendsController::class)->middleware('auth:sanctum');
/* All user's friends online */
Route::get('/usersOnline', GetUserFriendOnlineController::class)->middleware('auth:sanctum');
/* Remove friend */
Route::delete('/friend/remove/{friendId}', RemoveFriendController::class)->middleware('auth:sanctum');

Route::post('/send-message', [ChatController::class, 'sendMessage'])->middleware('auth:sanctum');
Route::get('/messages/{userId}', [ChatController::class, 'getMessage'])->middleware('auth:sanctum');
