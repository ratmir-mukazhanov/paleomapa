<?php
$config = require '../db/db_config.php';
$dsn = "pgsql:host={$config['host']};dbname={$config['dbname']}";
$pdo = new PDO($dsn, $config['user'], $config['pass']);

// Receber parâmetros da URL
$source = isset($_GET['source']) ? trim($_GET['source']) : '';
$family = isset($_GET['family']) ? trim($_GET['family']) : '';
$order = isset($_GET['order']) ? trim($_GET['order']) : '';
$genus = isset($_GET['genus']) ? trim($_GET['genus']) : '';
$species = isset($_GET['species']) ? trim($_GET['species']) : '';

// Query base
$sql = "
    SELECT
      id,
      title,
      family,
      discovered_by,
      source,
      \"order\",
      genus,
      species,
      date_discovered,
      ST_AsGeoJSON(geom) AS geom_json
    FROM findings
    WHERE geom IS NOT NULL
";

// Preparar parâmetros
$params = [];

// Adicionar condições usando prepared statements para maior segurança
if (!empty($source)) {
    $sql .= " AND LOWER(source) LIKE LOWER(:source)";
    $params[':source'] = "%{$source}%";
}

if (!empty($family)) {
    $sql .= " AND LOWER(family) LIKE LOWER(:family)";
    $params[':family'] = "%{$family}%";
}

if (!empty($order)) {
    $sql .= " AND LOWER(\"order\") LIKE LOWER(:order)";
    $params[':order'] = "%{$order}%";
}

if (!empty($genus)) {
    $sql .= " AND LOWER(genus) LIKE LOWER(:genus)";
    $params[':genus'] = "%{$genus}%";
}

if (!empty($species)) {
    $sql .= " AND LOWER(species) LIKE LOWER(:species)";
    $params[':species'] = "%{$species}%";
}

// Preparar e executar a consulta
$stmt = $pdo->prepare($sql);
$stmt->execute($params);

// Processar resultados
$features = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $geometry = json_decode($row['geom_json'], true);

    $features[] = [
        'type'       => 'Feature',
        'geometry'   => $geometry,
        'properties' => [
            'id'              => $row['id'],
            'title'           => $row['title'],
            'family'          => $row['family'],
            'discovered_by'   => $row['discovered_by'],
            'source'          => $row['source'],
            'order'           => $row['order'],
            'genus'           => $row['genus'],
            'species'         => $row['species'],
            'date_discovered' => $row['date_discovered']
        ]
    ];
}

$geojson = [
    'type'     => 'FeatureCollection',
    'features' => $features
];

// Retornar o JSON
header('Content-Type: application/json');
echo json_encode($geojson, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);