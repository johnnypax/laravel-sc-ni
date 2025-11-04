<?php

namespace App\Logging;

use Monolog\Formatter\JsonFormatter;

class CustomizeJsonFormatter{

    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $formatter = new JsonFormatter(JsonFormatter::BATCH_MODE_JSON, true);
            if (method_exists($formatter, 'includeStacktraces')) {
                $formatter->includeStacktraces(true);
            }
            $handler->setFormatter($formatter);
        }
    }

}
