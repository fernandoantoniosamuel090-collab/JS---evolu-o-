<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_name('js_evolucao_session');
    session_set_cookie_params([
        'lifetime' => 0, 'path' => '/', 'domain' => '',
        'secure' => $secure, 'httponly' => true, 'samesite' => 'Lax',
    ]);
    session_start();
}

function json_response(array $payload, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function request_json(): array
{
    $raw = file_get_contents('php://input');
    if ($raw === false || $raw === '') return [];
    $data = json_decode($raw, true);
    if (!is_array($data)) json_response(['ok' => false, 'error' => 'JSON inválido.'], 400);
    return $data;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}

function require_csrf(): void
{
    $sent = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    $saved = $_SESSION['csrf_token'] ?? '';
    if (!is_string($sent) || !is_string($saved) || $saved === '' || !hash_equals($saved, $sent)) {
        json_response(['ok' => false, 'error' => 'Pedido de segurança inválido. Atualize a página.'], 419);
    }
}

function current_user(): ?array
{
    if (empty($_SESSION['user_id'])) return null;
    $stmt = database_connection()->prepare(
        'SELECT u.id, u.email, u.full_name, u.status, GROUP_CONCAT(r.code ORDER BY r.code) AS roles
         FROM users u LEFT JOIN user_roles ur ON ur.user_id = u.id
         LEFT JOIN roles r ON r.id = ur.role_id
         WHERE u.id = :id AND u.status = "active" GROUP BY u.id LIMIT 1'
    );
    $stmt->execute(['id' => (int) $_SESSION['user_id']]);
    $user = $stmt->fetch();
    if (!$user) return null;
    $user['roles'] = $user['roles'] ? explode(',', $user['roles']) : [];
    return $user;
}

function require_user(): array
{
    $user = current_user();
    if (!$user) json_response(['ok' => false, 'error' => 'Autenticação necessária.'], 401);
    return $user;
}

function require_role(array $allowed): array
{
    $user = require_user();
    if (!array_intersect($allowed, $user['roles'])) {
        json_response(['ok' => false, 'error' => 'Não tem permissão para esta operação.'], 403);
    }
    return $user;
}

function request_string(array $data, string $key, int $max): string
{
    $value = $data[$key] ?? '';
    if (!is_string($value)) return '';
    return trim(mb_substr($value, 0, $max));
}
