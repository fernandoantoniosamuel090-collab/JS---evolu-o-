<?php
declare(strict_types=1);
require_once dirname(__DIR__, 3) . '/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'GET') json_response(['ok' => false, 'error' => 'Método não permitido.'], 405);
require_role(['super_admin', 'admin']);

$pdo = database_connection();
$counts = [];
foreach ([
    'users' => 'SELECT COUNT(*) FROM users WHERE status = "active"',
    'products' => 'SELECT COUNT(*) FROM products WHERE status = "active"',
    'orders' => 'SELECT COUNT(*) FROM orders',
    'pending_orders' => 'SELECT COUNT(*) FROM orders WHERE status = "pending"',
] as $key => $sql) {
    $counts[$key] = (int) $pdo->query($sql)->fetchColumn();
}
json_response(['ok' => true, 'metrics' => $counts, 'generated_at' => gmdate(DATE_ATOM)]);
