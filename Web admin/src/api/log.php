<?php
/**
 * API logimise funktsioon
 * Kirjutab JSON logikirje faili /var/log/apache2/api.log
 *
 * @param string $level   Logi tase: INFO, WARN, ERROR
 * @param string $message Sõnum, mis kirjeldab sündmust
 */
function log_api_event(string $level, string $message): void
{
    $log = [
        'timestamp' => date('c'),
        'komponent' => 'api',
        'tase'      => $level,
        'sõnum'     => $message,
    ];

    $line = json_encode($log, JSON_UNESCAPED_UNICODE) . PHP_EOL;

    file_put_contents('/var/log/apache2/api.log', $line, FILE_APPEND | LOCK_EX);
}
