<?php
session_start();

// Verificar se o utilizador está autenticado e é um admin
if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: ../login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar Dados - Paleomapa</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cores.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .dashboard-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-back-dashboard {
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

        .btn-back-dashboard:hover {
            background-color: var(--primary-color);
            color: var(--text-white);
        }

        .import-section {
            margin: 20px 0;
        }

        .import-card {
            background-color: var(--text-white);
            box-shadow: var(--shadow);
            border-radius: var(--border-radius);
            padding: 25px;
            margin-bottom: 20px;
        }

        .import-card h2 {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--primary-color);
            font-size: 1.4rem;
            margin-top: 0;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--highlight);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: var(--text-black);
        }

        .form-group input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--highlight);
            border-radius: var(--border-radius);
            background-color: #f9f9f9;
            transition: all 0.3s ease;
        }

        .form-group input[type="file"]:hover {
            border-color: var(--primary-light);
        }

        .form-info {
            background-color: #f8f9fa;
            border-left: 4px solid var(--primary-light);
            border-radius: var(--border-radius);
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .form-info h3 {
            margin-top: 0;
            font-size: 1.1rem;
            color: var(--primary-color);
            margin-bottom: 12px;
        }

        .form-info ul {
            padding-left: 20px;
            margin-bottom: 0;
        }

        .form-info li {
            margin-bottom: 8px;
            color: #555;
        }

        .form-info li:last-child {
            margin-bottom: 0;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .upload-btn {
            background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
            color: var(--text-white);
            border: none;
            padding: 12px 25px;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .upload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0,0,0,0.15);
        }

        .upload-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .upload-status {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 20px;
            padding: 15px;
            border-radius: var(--border-radius);
            background-color: #f8f9fa;
            border-left: 4px solid #6c757d;
        }

        .format-requirements {
            background-color: rgba(231, 240, 253, 0.8);
            border-left: 4px solid var(--accent-color);
            padding: 15px;
            margin-top: 25px;
            border-radius: var(--border-radius);
        }

        .format-requirements h4 {
            margin-top: 0;
            color: var(--accent-color);
            font-size: 1rem;
        }

        .format-note {
            margin-top: 15px;
            color: #555;
            font-style: italic;
        }

        .step-container {
            margin: 30px 0;
        }

        .step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: var(--border-radius);
            border-left: 3px solid var(--primary-light);
            transition: transform 0.3s ease;
        }

        .step:hover {
            transform: translateX(5px);
        }

        .step-number {
            background-color: var(--primary-color);
            color: white;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .step-content {
            flex-grow: 1;
        }

        .step-title {
            font-weight: 500;
            margin-bottom: 5px;
            color: var(--primary-color);
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
        Importar Dados
        <a href="dashboard.php" class="btn-back-dashboard">
            <i class="fas fa-arrow-left"></i> Voltar à Dashboard
        </a>
    </h1>

    <div class="import-section">
        <div class="import-card">
            <h2><i class="fas fa-file-excel"></i> Importar Fósseis do Excel</h2>

            <p>Carregue facilmente múltiplos registos de fósseis através de um ficheiro Excel. O sistema processará automaticamente os dados e adicionará à base de dados do Paleomapa.</p>

            <div class="step-container">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <div class="step-title">Prepare o seu ficheiro Excel</div>
                        <p>Certifique-se que o seu ficheiro está no formato .xlsx ou .xls e contém os campos necessários na primeira linha.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <div class="step-title">Selecione o ficheiro</div>
                        <p>Clique no botão abaixo para selecionar o seu ficheiro Excel com os dados dos fósseis.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <div class="step-title">Importe os dados</div>
                        <p>Clique em "Importar Dados" e aguarde enquanto o sistema processa o seu ficheiro.</p>
                    </div>
                </div>
            </div>

            <div id="response-message" class="alert" style="display: none;"></div>

            <form id="upload-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="excel_file"><i class="fas fa-file-upload"></i> Selecione o ficheiro Excel:</label>
                    <input type="file" id="excel_file" name="excel_file" accept=".xlsx, .xls" required>
                </div>

                <div class="form-info">
                    <h3><i class="fas fa-info-circle"></i> Requisitos do ficheiro</h3>
                    <ul>
                        <li><strong>Formato:</strong> .xlsx ou .xls</li>
                        <li><strong>Primeira linha:</strong> Deve conter os nomes dos campos</li>
                        <li><strong>Campos obrigatórios:</strong> gbifID (ID do fóssil) e scientificName (título)</li>
                        <li><strong>Latitude/Longitude:</strong> decimalLatitude e decimalLongitude (serão convertidos para formato geométrico)</li>
                        <li><strong>Campos taxonómicos:</strong> kingdom, phylum, class, order, family, genus, species</li>
                        <li><strong>Outros campos:</strong> identifiedBy (descobridor), eventDate (data de descoberta), source (fonte), created_at (data de criação)</li>
                    </ul>

                    <div class="format-note">
                        <strong>Nota:</strong> Se não fornecer os campos "created_at" ou "source", o sistema usará a data atual e "Paleomapa" como valores predefinidos.
                    </div>
                </div>

                <button type="submit" class="upload-btn">
                    <i class="fas fa-upload"></i> Importar Dados
                </button>
            </form>

            <div class="format-requirements">
                <h4><i class="fas fa-map-marker-alt"></i> Dados Geográficos</h4>
                <p>O sistema converte automaticamente as coordenadas (latitude e longitude) para o formato geométrico (geom) usado no mapa interativo.</p>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.getElementById('upload-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const responseMessage = document.getElementById('response-message');
        const submitButton = this.querySelector('button[type="submit"]');

        // Alterar o texto do botão e desabilitá-lo
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> A importar...';
        submitButton.disabled = true;

        // Limpar mensagem anterior
        responseMessage.style.display = 'none';
        responseMessage.textContent = '';
        responseMessage.className = 'alert';

        fetch('../scripts/upload.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                // Exibir a resposta
                if (data.includes('sucesso')) {
                    responseMessage.innerHTML = '<i class="fas fa-check-circle fa-lg"></i> ' + data;
                    responseMessage.classList.add('alert-success');
                } else {
                    responseMessage.innerHTML = '<i class="fas fa-exclamation-circle fa-lg"></i> ' + data;
                    responseMessage.classList.add('alert-error');
                }
                responseMessage.style.display = 'block';

                // Resetar o botão
                submitButton.innerHTML = '<i class="fas fa-upload"></i> Importar Dados';
                submitButton.disabled = false;

                // Scroll para a mensagem
                responseMessage.scrollIntoView({behavior: 'smooth'});
            })
            .catch(error => {
                responseMessage.innerHTML = '<i class="fas fa-exclamation-triangle fa-lg"></i> Erro ao enviar o ficheiro: ' + error.message;
                responseMessage.style.display = 'block';
                responseMessage.classList.add('alert-error');

                // Resetar o botão
                submitButton.innerHTML = '<i class="fas fa-upload"></i> Importar Dados';
                submitButton.disabled = false;
            });
    });

    // Mostrar o nome do arquivo selecionado
    document.getElementById('excel_file').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'Nenhum ficheiro selecionado';
        const label = this.previousElementSibling;
        label.innerHTML = `<i class="fas fa-file-excel"></i> Ficheiro selecionado: <strong>${fileName}</strong>`;
    });
</script>
</body>
</html>