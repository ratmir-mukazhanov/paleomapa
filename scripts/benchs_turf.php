<?php
header('Content-Type: application/json');
require_once '../db/db_connect.php';

try {
    $db_connection = connect_db();

    $query = "
        SELECT jsonb_build_object(
            'type',       'FeatureCollection',
            'features',   jsonb_agg(feature)
        )
        FROM (
            SELECT jsonb_build_object(
                'type',       'Feature',
                'id',         osm_id,
                'geometry',   ST_AsGeoJSON(ST_Transform(way, 4326))::jsonb,
                'properties', to_jsonb(c) - 'geom'
            ) AS feature
            FROM (SELECT * FROM benchs) c
        ) features;
    ";

    $result = pg_query($db_connection, $query);

    if (!$result) {
        throw new Exception('Erro na execução da query: ' . pg_last_error($db_connection));
    }

    $geojson = pg_fetch_assoc($result)["jsonb_build_object"];
    echo $geojson;

    pg_close($db_connection);

} catch (Exception $e) {
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
?>
