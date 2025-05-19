<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\Crons\CronJobsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/v1')->group(function () {
    Route::post('/prepare-today-task', [CronJobsController::class, 'sendTodaysTasks']);
    Route::post('/check-hourly-task', [CronJobsController::class, 'scheduleHourly']);

    // Send Champ
    Route::post('/sendchamp-webhook', [WebhookController::class, 'sendChamp']);
    Route::post('/test-sms', [WebhookController::class, 'testSMS']);
    Route::post('/test-whatsapp', [WebhookController::class, 'testWhatsApp']);
    Route::post('/test-ebulksms', [WebhookController::class, 'sendEbulkSMS']);
    Route::post('/test-ebulksms-whatsapp', [WebhookController::class, 'sendWhatsAppEbulkSMS']);
});
