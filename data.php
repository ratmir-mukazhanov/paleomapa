<?php
$config = require 'db_config.php';
$dsn = "pgsql:host={$config['host']};dbname={$config['dbname']}";
$pdo = new PDO($dsn, $config['user'], $config['pass']);

$stmt = $pdo->query("SELECT id, title, family, date_discovered, latitude, longitude FROM findings WHERE latitude IS NOT NULL AND longitude IS NOT NULL");

$features = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $features[] = [
        'type' => 'Feature',
        'geometry' => [
            'type' => 'Point',
            'coordinates' => [(float)$row['longitude'], (float)$row['latitude']]
        ],
        'properties' => [
        'id' => $row['id'],
        'title' => $row['title'],
        'family' => $row['family'],
        'date_discovered' => $row['date_discovered']
        ]
    ];
}

echo json_encode([
    'type' => 'FeatureCollection',
    'features' => $features
]);
