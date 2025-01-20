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

use App\Http\Controllers\Invitations\AcceptInvitationController;
use App\Http\Controllers\Invitations\CheckAllUsersFriendStatusController;
use App\Http\Controllers\Invitations\CheckFriendStatusController;
use App\Http\Controllers\Invitations\RejectInvitationController;
use App\Http\Controllers\Invitations\SendInvitationController;

/* Authentication */
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

/* All users */
Route::get('/users', [UserController::class, 'getUser'])->middleware('auth:sanctum');

/* Selected user */
Route::get('/user/{id}', [UserController::class, 'show']);

/* All users online */
Route::get('/usersOnline', [FriendController::class, 'getUserFriendOnline'])->middleware('auth:sanctum');

/* Send friend request */
Route::post('/invitations', SendInvitationController::class)->middleware('auth:sanctum');

/* Accept friend request */
Route::post('/invitations/{invitation}/accept', AcceptInvitationController::class)->middleware('auth:sanctum');

/* Reject friend request */
Route::post('/invitations/{invitation}/reject', RejectInvitationController::class)->middleware('auth:sanctum');

/* Show friend status of user selected */
Route::get('/friends/status/{selectedUserId}', CheckFriendStatusController::class)->middleware('auth:sanctum');

/* Show friend status of all users */
Route::get('/friends/status', CheckAllUsersFriendStatusController::class)->middleware('auth:sanctum');

/* Friends of user connected */
Route::get('/friends', [FriendController::class, 'getFriends'])->middleware('auth:sanctum');
Route::delete('/friend/remove/{friendId}', [FriendController::class, 'removeFriend'])->middleware('auth:sanctum');


Route::post('/messages', [FriendController::class, 'sendMessage'])->middleware('auth:sanctum');
Route::get('/getMessage/{selectedUserId}', [FriendController::class, 'getMessage'])->middleware('auth:sanctum');

Route::put('/updateUser', [UserController::class, 'updateUser'])->middleware('auth:sanctum');