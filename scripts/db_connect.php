<?php
function connect_db() {
    $host = "gis4cloud.com";
    $dbname = "grupo2_ptas2025";
    $user = "grupo2_ptas2025";
    $password = "aguedacity";

    $connection = pg_connect("host=$host dbname=$dbname user=$user password=$password");

    if (!$connection) {
        throw new Exception("Erro na conexão à base de dados.");
    }

    return $connection;
}
?>
