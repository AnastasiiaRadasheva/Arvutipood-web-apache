<?php
require_once __DIR__ . '/log.php';

log_api_event('INFO', 'Kasutaja küsis toodete nimekirja.');

header('Content-Type: application/json; charset=utf-8');

try {
    // Näidisandmed – siia saab hiljem päris andmebaasi ühendada
    $products = [
        ['id' => 1, 'nimi' => 'Sülearvuti', 'hind' => 999.99],
        ['id' => 2, 'nimi' => 'Hiir', 'hind' => 19.99],
        ['id' => 3, 'nimi' => 'Klaviatuur', 'hind' => 49.90],
    ];

    echo json_encode([
        'status' => 'ok',
        'data'   => $products,
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    log_api_event('ERROR', 'Toodete päring ebaõnnestus: ' . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Sisemine serveri viga',
    ], JSON_UNESCAPED_UNICODE);
}
