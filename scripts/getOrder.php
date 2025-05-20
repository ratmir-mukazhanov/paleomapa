<?php

$config = require '../db/db_config.php';
$dsn = "pgsql:host={$config['host']};dbname={$config['dbname']}";

try {
    
    $pdo = new PDO($dsn, $config['user'], $config['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT DISTINCT "order" FROM findings';
    $stmt = $pdo->query($sql);

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result);

} catch (PDOException $e) {

    echo json_encode(['error' => $e->getMessage()]);
}