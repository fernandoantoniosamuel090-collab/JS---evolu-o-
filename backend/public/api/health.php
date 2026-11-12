<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

http_response_code(200);
echo json_encode([
    'ok' => true,
    'service' => 'js-evolucao-api',
    'status' => 'running',
], JSON_UNESCAPED_SLASHES);