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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FriendController;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/users', [UserController::class, 'getUser']);
Route::get('/user/{id}', [UserController::class, 'show']);
Route::middleware('auth:sanctum')->get('/usersOnline', [UserController::class, 'getUserOnline']);

Route::post('/invitations', [FriendController::class, 'sendInvitation'])->middleware('auth:sanctum');
Route::post('/invitations/{invitation}/accept', [FriendController::class, 'acceptInvitation'])->middleware('auth:sanctum');
Route::post('/invitations/{invitation}/reject', [FriendController::class, 'rejectInvitation'])->middleware('auth:sanctum');

Route::get('/friends/status/{selectedUserId}', [FriendController::class, 'checkFriendStatus'])->middleware('auth:sanctum');
