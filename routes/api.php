<?php

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\AuthController;
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


Route::post('/register', [AuthController::class, 'register'] );
Route::post('/login', [AuthController::class, 'login'] );
Route::middleware('auth:sanctum')->get('/getUserTasks/{user_id}', [TaskController::class, 'getUserTasks'] );
Route::middleware('auth:sanctum')->post('/create', [TaskController::class, 'create'] );
Route::middleware('auth:sanctum')->put('/update/{id}', [TaskController::class, 'update'] );
Route::middleware('auth:sanctum')->delete('/delete/{id}', [TaskController::class, 'destroy'] );

// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('todos', TaskController::class);
// });

