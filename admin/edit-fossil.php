<?php
session_start();

// Verificar se o utilizador está autenticado e é um admin
if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: ../login/login.php");
    exit();
}

require_once "../services/dashboard_service.php";
$dashboardService = new DashboardService();

$errorMsg = "";
$successMsg = "";
$fossilData = [];

// Verificar se um ID foi fornecido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: fossils-management.php");
    exit();
}

$fossilId = (int)$_GET['id'];

// Buscar dados do fóssil
$fossilData = $dashboardService->getFossilById($fossilId);

// Se o fóssil não existir, redirecionar
if (empty($fossilData)) {
    header("Location: fossils-management.php?error=fossil_not_found");
    exit();
}

// Processar o formulário quando enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar todos os campos do formulário
    $title = trim($_POST['title'] ?? '');
    $discoveredBy = trim($_POST['discovered_by'] ?? '');
    $dateDiscovered = trim($_POST['date_discovered'] ?? '');
    $kingdom = trim($_POST['kingdom'] ?? '');
    $phylum = trim($_POST['phylum'] ?? '');
    $class = trim($_POST['class'] ?? '');
    $order = trim($_POST['order'] ?? '');
    $family = trim($_POST['family'] ?? '');
    $genus = trim($_POST['genus'] ?? '');
    $species = trim($_POST['species'] ?? '');
    $latitude = trim($_POST['latitude'] ?? '');
    $longitude = trim($_POST['longitude'] ?? '');
    $source = trim($_POST['source'] ?? '');

    // Validação básica
    if (empty($title)) {
        $errorMsg = "Por favor, forneça um título para o fóssil.";
    } elseif (!is_numeric($latitude) || !is_numeric($longitude)) {
        $errorMsg = "Latitude e longitude devem ser valores numéricos válidos.";
    } else {
        // Atualizar o fóssil no banco de dados com o campo source
        $result = $dashboardService->updateFossil($fossilId, $title, $discoveredBy, $dateDiscovered,
            $kingdom, $phylum, $class, $order,
            $family, $genus, $species,
            $latitude, $longitude, $source);

        if ($result) {
            $successMsg = "Fóssil atualizado com sucesso.";
            // Recarregar os dados após a atualização
            $fossilData = $dashboardService->getFossilById($fossilId);

            // Redirecionar para a lista de fósseis após atualização bem-sucedida
            header("Location: fossils-management.php?success=fossil_updated");
            exit();
        } else {
            $errorMsg = "Erro ao atualizar o fóssil. Por favor, tente novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Fóssil - Paleomapa</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cores.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .form-container {
            background-color: var(--text-white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: var(--shadow);
        }

        .dashboard-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-back-fossils {
            background-color: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            padding: 6px 12px;
            border-radius: var(--border-radius);
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: var(--transition);
        }

        .btn-back-fossils:hover {
            background-color: var(--primary-color);
            color: var(--text-white);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-black);
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--highlight);
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .btn-submit {
            background-color: var(--success-color);
            color: var(--text-white);
            border: none;
            padding: 12px 20px;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-submit:hover {
            background-color: #218838;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: var(--border-radius);
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: var(--border-radius);
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-cancel {
            background-color: #6c757d;
            color: var(--text-white);
            border: none;
            padding: 12px 20px;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-cancel:hover {
            background-color: #5a6268;
        }

        .coordinates-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: var(--border-radius);
            border: 1px solid var(--highlight);
            margin-bottom: 20px;
        }

        .coordinates-section h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: var(--primary-color);
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
<?php
require_once "../components/header.php";
require_once "../components/sidebar.php";
?>
<div class="dashboard-container">
    <h1 class="dashboard-title">
        Editar Fóssil
        <a href="fossils-management.php" class="btn-back-fossils">
            <i class="fas fa-arrow-left"></i> Voltar à Lista
        </a>
    </h1>

    <div class="admin-sections">
        <div class="section">
            <div class="form-container">
                <?php if (!empty($errorMsg)): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $errorMsg; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($successMsg)): ?>
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i> <?php echo $successMsg; ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="edit-fossil.php?id=<?php echo $fossilId; ?>">
                    <div class="form-group">
                        <label for="title">Título do Fóssil*</label>
                        <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($fossilData['title'] ?? ''); ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="discovered_by">Descoberto por</label>
                            <input type="text" id="discovered_by" name="discovered_by" class="form-control" value="<?php echo htmlspecialchars($fossilData['discovered_by'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="date_discovered">Data da Descoberta</label>
                            <input type="date" id="date_discovered" name="date_discovered" class="form-control" value="<?php echo $fossilData['date_discovered'] ?? ''; ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="kingdom">Reino</label>
                            <input type="text" id="kingdom" name="kingdom" class="form-control" value="<?php echo htmlspecialchars($fossilData['kingdom'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="phylum">Filo</label>
                            <input type="text" id="phylum" name="phylum" class="form-control" value="<?php echo htmlspecialchars($fossilData['phylum'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="class">Classe</label>
                            <input type="text" id="class" name="class" class="form-control" value="<?php echo htmlspecialchars($fossilData['class'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="order">Ordem</label>
                            <input type="text" id="order" name="order" class="form-control" value="<?php echo htmlspecialchars($fossilData['order'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="family">Família</label>
                            <input type="text" id="family" name="family" class="form-control" value="<?php echo htmlspecialchars($fossilData['family'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="genus">Género</label>
                            <input type="text" id="genus" name="genus" class="form-control" value="<?php echo htmlspecialchars($fossilData['genus'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="species">Espécie</label>
                        <input type="text" id="species" name="species" class="form-control" value="<?php echo htmlspecialchars($fossilData['species'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="source">Fonte</label>
                        <select id="source" name="source" class="form-control">
                            <option value="">Selecione uma fonte</option>
                            <option value="Paleomapa" <?php echo (isset($fossilData['source']) && $fossilData['source'] == 'Paleomapa') ? 'selected' : ''; ?>>Paleomapa</option>
                            <option value="UA" <?php echo (isset($fossilData['source']) && $fossilData['source'] == 'UA') ? 'selected' : ''; ?>>UA</option>
                        </select>
                    </div>

                    <div class="coordinates-section">
                        <h3>Localização do Fóssil</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="latitude">Latitude*</label>
                                <input type="text" id="latitude" name="latitude" class="form-control" value="<?php echo htmlspecialchars($fossilData['latitude'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="longitude">Longitude*</label>
                                <input type="text" id="longitude" name="longitude" class="form-control" value="<?php echo htmlspecialchars($fossilData['longitude'] ?? ''); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="fossils-management.php" class="btn-cancel">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Guardar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>