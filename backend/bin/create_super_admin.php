<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit("Not found\n");
}
require_once dirname(__DIR__) . '/config/database.php';

$email = strtolower(trim((string) getenv('ADMIN_EMAIL')));
$name = trim((string) getenv('ADMIN_NAME'));
$password = (string) getenv('ADMIN_PASSWORD');

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $name === '' || strlen($password) < 16) {
    fwrite(STDERR, "Defina ADMIN_EMAIL, ADMIN_NAME e ADMIN_PASSWORD (mínimo 16 caracteres) no terminal.\n");
    exit(1);
}

$pdo = database_connection();
$exists = (int) $pdo->query("SELECT COUNT(*) FROM user_roles ur JOIN roles r ON r.id=ur.role_id WHERE r.code='super_admin'")->fetchColumn();
if ($exists > 0) {
    fwrite(STDERR, "Já existe um superadministrador. Operação cancelada.\n");
    exit(1);
}

$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare('INSERT INTO users (email,password_hash,full_name,status) VALUES (:email,:hash,:name,"active")');
    $stmt->execute(['email'=>$email,'hash'=>password_hash($password,PASSWORD_DEFAULT),'name'=>$name]);
    $userId = (int) $pdo->lastInsertId();
    $roleId = $pdo->query("SELECT id FROM roles WHERE code='super_admin'")->fetchColumn();
    if (!$roleId) throw new RuntimeException('Papel super_admin não existe. Importe o schema SQL.');
    $assign = $pdo->prepare('INSERT INTO user_roles (user_id,role_id) VALUES (:user,:role)');
    $assign->execute(['user'=>$userId,'role'=>$roleId]);
    $pdo->commit();
    fwrite(STDOUT, "Superadministrador criado. Remova as variáveis de ambiente do terminal.\n");
} catch (Throwable $error) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    fwrite(STDERR, "Falha ao criar administrador: " . $error->getMessage() . "\n");
    exit(1);
}
