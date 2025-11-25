<?php
/**
 * Server WebSocket PHP che riceve dati da API Laravel
 * e li trasmette ai client in tempo reale
 */

$host = '127.0.0.1';
$port = 8080;
$apiUrl = 'http://127.0.0.1:8000/api/telemetry';

$server = stream_socket_server("tcp://$host:$port", $errno, $errstr);
if (!$server) die("Errore: $errstr ($errno)\n");

echo "WebSocket Server attivo su ws://$host:$port\n";
echo "Lettura dati da $apiUrl ogni 2 secondi\n";

$clients = [];

// Funzioni utility
function ws_unmask(string $payload): string {
    if (strlen($payload) < 6) return '';
    $len = ord($payload[1]) & 127;
    if ($len === 126) { $mask = substr($payload, 4, 4); $data = substr($payload, 8); }
    elseif ($len === 127) { $mask = substr($payload, 10, 4); $data = substr($payload, 14); }
    else { $mask = substr($payload, 2, 4); $data = substr($payload, 6); }
    $txt = '';
    for ($i = 0; $i < strlen($data); $i++) $txt .= $data[$i] ^ $mask[$i % 4];
    return $txt;
}

function ws_mask(string $text): string {
    $b1 = 0x81; $len = strlen($text);
    if ($len <= 125)   return pack('CC', $b1, $len) . $text;
    if ($len <= 65535) return pack('CCn', $b1, 126, $len) . $text;
    return pack('CCNN', $b1, 127, 0, $len) . $text;
}

function getTelemetryData(string $apiUrl): ?array {
    $ch = curl_init($apiUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 3
    ]);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status !== 200 || !$response) return null;
    return json_decode($response, true);
}

// Ciclo principale
$lastUpdate = 0;

while (true) {
    $read = [$server];
    foreach ($clients as $c) $read[] = $c;
    $write = null; $except = null;

    if (stream_select($read, $write, $except, 1) > 0) {
        // Nuova connessione
        if (in_array($server, $read)) {
            $conn = stream_socket_accept($server);
            if ($conn) {
                stream_set_blocking($conn, false);
                $clients[] = $conn;
                echo "Nuovo client connesso (" . count($clients) . " totali)\n";
            }
            unset($read[array_search($server, $read)]);
        }

        // Gestione handshake
        foreach ($read as $client) {
            $data = @fread($client, 1024);
            if (!$data) continue;

            if (preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $data, $match)) {
                $key = trim($match[1]);
                $accept = base64_encode(sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true));
                $headers =
                    "HTTP/1.1 101 Switching Protocols\r\n" .
                    "Upgrade: websocket\r\n" .
                    "Connection: Upgrade\r\n" .
                    "Sec-WebSocket-Accept: $accept\r\n\r\n";
                fwrite($client, $headers);
                echo "Handshake completato\n";
            }
        }
    }

    // Ogni 2 secondi interroga Laravel e invia i dati
    $now = time();
    if ($now - $lastUpdate >= 2 && count($clients) > 0) {
        $telemetry = getTelemetryData($apiUrl);
        if ($telemetry) {
            $json = json_encode($telemetry);
            $frame = ws_mask($json);

            foreach ($clients as $c) {
                @fwrite($c, $frame);
            }

            echo "Telemetria: {$telemetry['temperature']} °C, {$telemetry['humidity']} %\n";
        } else {
            echo "Nessun dato ricevuto dall’API\n";
        }

        $lastUpdate = $now;
    }
}
