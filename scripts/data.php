<?php
$config = require '../db/db_config.php';
$dsn = "pgsql:host={$config['host']};dbname={$config['dbname']}";
$pdo = new PDO($dsn, $config['user'], $config['pass']);

// Consulta somente os campos necessários e a geometria em formato GeoJSON
$sql = "
    SELECT
      id,
      title,
      family,
      discovered_by,
      ST_AsGeoJSON(geom) AS geom_json
    FROM findings
    WHERE geom IS NOT NULL
";

$stmt = $pdo->query($sql);

$features = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    // Decodifica a geometria GeoJSON para transformar em array
    $geometry = json_decode($row['geom_json'], true);

    $features[] = [
        'type'       => 'Feature',
        'geometry'   => $geometry,
        'properties' => [
            'id'            => $row['id'],
            'title'         => $row['title'],
            'family'        => $row['family'],
            'discovered_by' => $row['discovered_by']
        ]
    ];
}

$geojson = [
    'type'     => 'FeatureCollection',
    'features' => $features
];

// Retorna o JSON
header('Content-Type: application/json');
echo json_encode($geojson, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
