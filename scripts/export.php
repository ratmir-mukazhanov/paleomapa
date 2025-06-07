<?php
session_start();

// Verificar se o utilizador está autenticado e é um admin
if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: ../login/login.php");
    exit();
}

// Verificar se o método é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../admin/export.php");
    exit();
}

require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

// Obter parâmetros de exportação
$exportFormat = $_POST['export_format'] ?? '';
$fields = $_POST['fields'] ?? [];

if (empty($exportFormat) || empty($fields)) {
    die("Formato de exportação ou campos não especificados.");
}

// Filtrar e garantir que os campos são válidos
$allowedFields = [
    'id', 'title', 'kingdom', 'phylum', 'class', 'order', 'family',
    'genus', 'species', 'latitude', 'longitude', 'discovered_by',
    'date_discovered', 'created_at', 'source'
];
$fields = array_intersect($fields, $allowedFields);

if (empty($fields)) {
    die("Nenhum campo válido selecionado.");
}

// Conectar à base de dados
$config = require '../db/db_config.php';
$dsn = "pgsql:host={$config['host']};dbname={$config['dbname']}";

try {
    $pdo = new PDO($dsn, $config['user'], $config['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Construir consulta SQL
    $columnsList = implode(', ', array_map(function($field) {
        return $field === 'order' ? '"order"' : $field;
    }, $fields));

    $sql = "SELECT $columnsList FROM findings ORDER BY id";
    $stmt = $pdo->query($sql);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($data)) {
        die("Nenhum dado encontrado para exportação.");
    }

    // Exportar com base no formato
    switch ($exportFormat) {
        case 'excel':
            exportToExcel($data, $fields);
            break;
        case 'csv':
            exportToCSV($data, $fields);
            break;
        case 'sql':
            exportToSQL($data, $fields);
            break;
        default:
            die("Formato de exportação não suportado.");
    }

} catch (PDOException $e) {
    die("Erro na conexão com a base de dados: " . $e->getMessage());
} catch (Exception $e) {
    die("Erro durante a exportação: " . $e->getMessage());
}

// Função para exportar para Excel
function exportToExcel($data, $fields) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Adicionar cabeçalhos
    foreach ($fields as $index => $field) {
        $columnLetter = Coordinate::stringFromColumnIndex($index + 1);
        $sheet->setCellValue($columnLetter . '1', ucfirst($field));
    }

    // Adicionar dados
    $row = 2;
    foreach ($data as $record) {
        $col = 1;
        foreach ($fields as $field) {
            $columnLetter = Coordinate::stringFromColumnIndex($col);
            $value = $record[$field] ?? '';

            // Formatação de datas
            if (($field === 'date_discovered' || $field === 'created_at') && !empty($value)) {
                $date = new DateTime($value);
                $value = $date->format('d/m/Y');
            }

            $sheet->setCellValue($columnLetter . $row, $value);
            $col++;
        }
        $row++;
    }

    // Configurar largura automática das colunas
    foreach (range('A', $sheet->getHighestColumn()) as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Configurar estilo dos cabeçalhos
    $highestColumn = $sheet->getHighestColumn();
    $headerStyle = [
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF'],
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => '2C3E50'],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
    ];

    $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray($headerStyle);

    // Definir nome do arquivo
    $filename = 'paleomapa_fosseis_' . date('Y-m-d_H-i-s') . '.xlsx';

    // Configurar cabeçalhos para download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

// Função para exportar para CSV
function exportToCSV($data, $fields) {
    // Definir nome do arquivo
    $filename = 'paleomapa_fosseis_' . date('Y-m-d_H-i-s') . '.csv';

    // Configurar cabeçalhos para download
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment;filename="' . $filename . '"');

    // Criar arquivo CSV
    $output = fopen('php://output', 'w');

    // Adicionar BOM para UTF-8
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    // Adicionar cabeçalhos
    $headers = array_map('ucfirst', $fields);
    fputcsv($output, $headers);

    // Adicionar dados
    foreach ($data as $record) {
        $row = [];
        foreach ($fields as $field) {
            $value = $record[$field] ?? '';

            // Formatação de datas
            if (($field === 'date_discovered' || $field === 'created_at') && !empty($value)) {
                $date = new DateTime($value);
                $value = $date->format('d/m/Y');
            }

            $row[] = $value;
        }
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}

// Função para exportar para SQL
function exportToSQL($data, $fields) {
    // Definir nome do arquivo
    $filename = 'paleomapa_fosseis_' . date('Y-m-d_H-i-s') . '.sql';

    // Configurar cabeçalhos para download
    header('Content-Type: application/sql');
    header('Content-Disposition: attachment;filename="' . $filename . '"');

    // Cabeçalho do arquivo SQL
    $output = "-- Paleomapa - Exportação de Fósseis\n";
    $output .= "-- Data: " . date('Y-m-d H:i:s') . "\n\n";
    $output .= "-- Inserir os dados na tabela 'findings'\n\n";

    // Converter os campos para uso em SQL (tratar 'order' especialmente)
    $sqlFields = array_map(function($field) {
        return $field === 'order' ? '"order"' : $field;
    }, $fields);

    // Gerar instruções SQL INSERT
    foreach ($data as $record) {
        $values = [];
        foreach ($fields as $field) {
            $value = $record[$field] ?? null;
            if ($value === null) {
                $values[] = 'NULL';
            } else {
                // Escapar strings
                if (is_numeric($value)) {
                    $values[] = $value;
                } else {
                    // Escapar aspas simples
                    $value = str_replace("'", "''", $value);
                    $values[] = "'" . $value . "'";
                }
            }
        }

        $output .= "INSERT INTO findings (" . implode(', ', $sqlFields) . ") VALUES (" . implode(', ', $values) . ");\n";
    }

    echo $output;
    exit;
}