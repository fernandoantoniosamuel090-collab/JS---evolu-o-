<?php
declare(strict_types=1);
require_once dirname(__DIR__, 3) . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['ok' => false, 'error' => 'Método não permitido.'], 405);
require_csrf();
$data = request_json();
$name = request_string($data, 'full_name', 160);
$email = strtolower(request_string($data, 'email', 190));
$password = $data['password'] ?? '';

if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !is_string($password) || strlen($password) < 12) {
    json_response(['ok' => false, 'error' => 'Informe nome, e-mail válido e palavra-passe com pelo menos 12 caracteres.'], 422);
}
if (strlen($password) > 200) json_response(['ok' => false, 'error' => 'Palavra-passe demasiado longa.'], 422);

$pdo = database_connection();
try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare('INSERT INTO users (email, password_hash, full_name, status) VALUES (:email, :hash, :name, "active")');
    $stmt->execute(['email' => $email, 'hash' => password_hash($password, PASSWORD_DEFAULT), 'name' => $name]);
    $userId = (int) $pdo->lastInsertId();
    $role = $pdo->prepare('SELECT id FROM roles WHERE code = "customer" LIMIT 1');
    $role->execute();
    $roleId = $role->fetchColumn();
    if (!$roleId) throw new RuntimeException('Role customer não configurada.');
    $assign = $pdo->prepare('INSERT INTO user_roles (user_id, role_id) VALUES (:user, :role)');
    $assign->execute(['user' => $userId, 'role' => $roleId]);
    $pdo->commit();
    json_response(['ok' => true, 'message' => 'Conta criada. Já pode iniciar sessão.'], 201);
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    if ($e->getCode() === '23000') json_response(['ok' => false, 'error' => 'Este e-mail já está registado.'], 409);
    error_log('Registration failed: ' . $e->getMessage());
    json_response(['ok' => false, 'error' => 'Não foi possível criar a conta.'], 500);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log('Registration failed: ' . $e->getMessage());
    json_response(['ok' => false, 'error' => 'Não foi possível criar a conta.'], 500);
}
