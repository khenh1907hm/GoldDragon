<?php
header('Content-Type: application/json');

$headers = getallheaders();
echo json_encode([
    'headers' => $headers,
    'x_api_key' => $headers['X-API-Key'] ?? 'not found',
    'content_type' => $headers['Content-Type'] ?? 'not found'
], JSON_PRETTY_PRINT); 