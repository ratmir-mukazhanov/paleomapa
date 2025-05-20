<?php

$config = require '../db/db_config.php';
$dsn = "pgsql:host={$config['host']};dbname={$config['dbname']}";
$pdo = new PDO($dsn, $config['user'], $config['pass']);

$sql = 'SELECT DISTINCT "source" FROM findings';

$sql-> $pdo->query($sql);

$result = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($result);