<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'GET') json_response(['ok' => false, 'error' => 'Método não permitido.'], 405);
$user = current_user();
json_response(['ok' => true, 'authenticated' => (bool) $user, 'user' => $user, 'csrf_token' => csrf_token()]);
