<?php

use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\CallController;
use App\Http\Controllers\Api\MessageController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('calls/start', function () {

    $repository = app(\App\Repositories\VoiceRepository::class);

    $response = $repository->voiceCall('+5581997380232');
    
    return response($response, 200, ['Content-type' => 'application/xml']);
});

// Call's entry endpoint
Route::get('calls/welcome', [CallController::class, 'welcome']);

// Call's main menu flow
Route::post('calls/flow', [CallController::class, 'menu']);

// Call's to an agent by code
Route::post('agents/call', [AgentController::class, 'find']);

// Sends a message
Route::post('messages/send', [MessageController::class, 'send']);

// Ends the call
Route::get('calls/goodbye', [CallController::class, 'hangup']);