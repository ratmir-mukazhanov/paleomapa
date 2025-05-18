<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$config = require '../db/db_config.php';
$dsn = "pgsql:host={$config['host']};dbname={$config['dbname']}";
$pdo = new PDO($dsn, $config['user'], $config['pass']);

try {
    if (isset($_FILES['excel_file']['tmp_name'])) {
        $spreadsheet = IOFactory::load($_FILES['excel_file']['tmp_name']);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        if (count($rows) < 2) {
            throw new Exception("O ficheiro não contém dados suficientes.");
        }

        $headers = array_map('trim', $rows[0]);
        $dataAtual = date('Y-m-d');

        for ($i = 1; $i < count($rows); $i++) {
            $assocRow = array_combine($headers, $rows[$i]);

            if ($assocRow === false) {
                continue;
            }

            $id = isset($assocRow['gbifID']) && is_numeric($assocRow['gbifID']) ? (int)$assocRow['gbifID'] : null;
            $title = $assocRow['scientificName'] ?? null;

            $kingdom = $assocRow['kingdom'] ?? null;
            $phylum = $assocRow['phylum'] ?? null;
            $class = $assocRow['class'] ?? null;
            $order = $assocRow['order'] ?? null;
            $family = $assocRow['family'] ?? null;
            $genus = $assocRow['genus'] ?? null;
            $species = $assocRow['species'] ?? null;

            $discovered_by = $assocRow['identifiedBy'] ?? null;

            $latitude = isset($assocRow['decimalLatitude']) && is_numeric($assocRow['decimalLatitude']) ? floatval($assocRow['decimalLatitude']) : null;
            $longitude = isset($assocRow['decimalLongitude']) && is_numeric($assocRow['decimalLongitude']) ? floatval($assocRow['decimalLongitude']) : null;

            // Criando valor para geom a partir de latitude e longitude
            $geom = null;
            if ($latitude !== null && $longitude !== null) {
                // Usando o formato WKT (Well-Known Text) para criar a geometria
                $geom = "POINT($longitude $latitude)";
            }

            // Processamento da data de descoberta
            $date_discovered = null;
            if (!empty($assocRow['eventDate'])) {
                $rawDate = trim($assocRow['eventDate']);
                $formats = ['Y-m-d', 'd/m/Y', 'Y-m-d\TH:i:s', 'm/d/Y'];
                foreach ($formats as $format) {
                    $dt = DateTime::createFromFormat($format, $rawDate);
                    if ($dt !== false) {
                        $date_discovered = $dt->format('Y-m-d');
                        break;
                    }
                }
                if ($date_discovered === null) {
                    $timestamp = strtotime($rawDate);
                    if ($timestamp) {
                        $date_discovered = date('Y-m-d', $timestamp);
                    }
                }
            }

            // Processar o campo created_at se existir no Excel
            $created_at = $dataAtual;
            if (!empty($assocRow['created_at'])) {
                $rawCreatedAt = trim($assocRow['created_at']);
                $formats = ['Y-m-d', 'd/m/Y', 'Y-m-d\TH:i:s', 'm/d/Y'];
                foreach ($formats as $format) {
                    $dt = DateTime::createFromFormat($format, $rawCreatedAt);
                    if ($dt !== false) {
                        $created_at = $dt->format('Y-m-d');
                        break;
                    }
                }
                if ($created_at === $dataAtual) {
                    $timestamp = strtotime($rawCreatedAt);
                    if ($timestamp) {
                        $created_at = date('Y-m-d', $timestamp);
                    }
                }
            }

            $source = $assocRow['source'] ?? 'Paleomapa';

            if ($id !== null) {
                // Modificando a query para incluir o campo geom usando ST_GeomFromText
                $stmt = $pdo->prepare("
                    INSERT INTO findings (
                        id, title, latitude, longitude, discovered_by, date_discovered,
                        kingdom, phylum, class, \"order\", family, genus, species,
                        created_at, source, geom
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
                      ST_SetSRID(ST_GeomFromText(?), 4326))
                    ON CONFLICT (id) DO UPDATE SET
                        title = EXCLUDED.title,
                        latitude = EXCLUDED.latitude,
                        longitude = EXCLUDED.longitude,
                        discovered_by = EXCLUDED.discovered_by,
                        date_discovered = EXCLUDED.date_discovered,
                        kingdom = EXCLUDED.kingdom,
                        phylum = EXCLUDED.phylum,
                        class = EXCLUDED.class,
                        \"order\" = EXCLUDED.\"order\",
                        family = EXCLUDED.family,
                        genus = EXCLUDED.genus,
                        species = EXCLUDED.species,
                        source = EXCLUDED.source,
                        geom = EXCLUDED.geom
                ");
                $stmt->execute([
                    $id, $title, $latitude, $longitude, $discovered_by, $date_discovered,
                    $kingdom, $phylum, $class, $order, $family, $genus, $species,
                    $created_at, $source, $geom
                ]);
            }
        }

        echo "Importação concluída com sucesso.";
    } else {
        echo "Nenhum ficheiro foi enviado.";
    }

} catch (PDOException $e) {
    echo "Erro na base de dados: " . $e->getMessage();
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>