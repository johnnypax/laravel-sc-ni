<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class LogController extends Controller{

    public function generate(Request $request)
    {
        $ctx = [
            'route' => '/api/logs',
            'ip' => $request->ip(),
            'user_id' => optional($request->user())->id,
            'ua' => $request->userAgent(),
        ];

        Log::channel('daily_info')->info('Operazione riuscita', $ctx);
        Log::channel('daily_warnings')->warning('Tentativo di accesso non autorizzato', $ctx);
        Log::channel('daily_errors')->error('Errore applicativo simulato', $ctx);
        Log::info('Log default stack (single) con contesto', $ctx);
        Log::channel('daily_json')->info('Evento JSON', $ctx);

        return response()->json(['status' => 'Logs generati', 'context' => $ctx]);
    }

    public function simulateException()
    {
        try {
            throw new \RuntimeException('Eccezione simulata per test logging');
        } catch (Throwable $e) {
            Log::channel('daily_errors')->error($e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            Log::channel('daily_json')->error('Exception catturata', [
                'exception' => (string)$e,
            ]);

            return response()->json(['status' => 'Exception loggata']);
        }
    }

}
