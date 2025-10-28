<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\SuburbController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TwilioWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\PdfReportController;
use App\Http\Controllers\StateController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//   return $request->user();
// });
Route::get('/report/pdf', [PdfReportController::class, 'generate']);
Route::post('/report/generate-pdf', [PdfReportController::class, 'generatePdfFromData']);


Route::get('/suburbs-data', [SuburbController::class, 'index']);
Route::get('/suburbs-state', [StateController::class, 'apiIndex']);


// Report
Route::post('/reports', [UserController::class, 'store']);
Route::post('/verify-otp', [UserController::class, 'verifyOtp']);
Route::post('/resend-otp', [UserController::class, 'resendOtp']);
Route::post('/login', [UserController::class, 'login']);

// Twilio SMS status callback (use a publicly reachable URL in production)
Route::post('/twilio/status-callback', [TwilioWebhookController::class, 'statusCallback']);



// Google api
Route::get('/proxy-suburbs', function () {
  $response = Http::get('https://storage.googleapis.com/suburbtrends-map-dev/api/suburbs');
  return response($response->body(), $response->status())
    ->header('Content-Type', $response->header('Content-Type'));
});


Route::get('/proxy-suburb/{id}', function ($id) {
  $response = Http::get("https://storage.googleapis.com/suburbtrends-map-dev/api/suburb/{$id}");
  return response($response->body(), $response->status())
    ->header('Content-Type', $response->header('Content-Type'));
});

Route::get('/proxy-infrastructure', function () {
  $response = Http::get("https://storage.googleapis.com/suburbtrends-map-dev/api/infrastructure");
  return response($response->body(), $response->status())
    ->header('Content-Type', $response->header('Content-Type'));
});
