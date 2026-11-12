<?php
declare(strict_types=1);
require_once dirname(__DIR__, 3) . '/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['ok' => false, 'error' => 'Método não permitido.'], 405);
require_csrf();
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();
json_response(['ok' => true, 'message' => 'Sessão terminada.']);
