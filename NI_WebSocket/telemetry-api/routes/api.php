<?php

use App\Http\Controllers\TelemetryController;

Route::get('/telemetry', [TelemetryController::class, 'getTelemetry']);
