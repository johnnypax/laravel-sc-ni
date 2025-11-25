<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TelemetryController extends Controller
{
    public function getTelemetry()
    {
        // Simulazione dati
        $temperature = round(mt_rand(180, 320) / 10, 1); // 18.0 - 32.0 °C
        $humidity = round(mt_rand(400, 800) / 10, 1);   // 40.0 - 80.0 %

        return response()->json([
            'timestamp' => now()->toDateTimeString(),
            'temperature' => $temperature,
            'humidity' => $humidity,
        ]);
    }
}
