<?php
declare(strict_types=1);
require_once dirname(__DIR__, 3) . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['ok' => false, 'error' => 'Método não permitido.'], 405);
require_csrf();
$data = request_json();
$email = strtolower(request_string($data, 'email', 190));
$password = $data['password'] ?? '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !is_string($password) || $password === '') {
    json_response(['ok' => false, 'error' => 'Credenciais inválidas.'], 422);
}

$stmt = database_connection()->prepare('SELECT id, password_hash, status FROM users WHERE email = :email LIMIT 1');
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();
if (!$user || $user['status'] !== 'active' || !password_verify($password, $user['password_hash'])) {
    usleep(250000);
    json_response(['ok' => false, 'error' => 'E-mail ou palavra-passe incorretos.'], 401);
}
if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
    $rehash = database_connection()->prepare('UPDATE users SET password_hash = :hash WHERE id = :id');
    $rehash->execute(['hash' => password_hash($password, PASSWORD_DEFAULT), 'id' => $user['id']]);
}
session_regenerate_id(true);
$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
database_connection()->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id')->execute(['id' => $user['id']]);
json_response(['ok' => true, 'user' => current_user(), 'csrf_token' => csrf_token()]);
