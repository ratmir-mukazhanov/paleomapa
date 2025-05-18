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
    <title>Exportar Dados - Paleomapa</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cores.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/export.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<?php
require_once "../components/header.php";
require_once "../components/sidebar.php";
?>
<div class="dashboard-container">
    <h1 class="dashboard-title">
        Exportar Dados
        <a href="dashboard.php" class="btn-back-dashboard">
            <i class="fas fa-arrow-left"></i> Voltar à Dashboard
        </a>
    </h1>

    <div class="export-section">
        <div class="export-card">
            <h2><i class="fas fa-file-export"></i> Exportar Dados dos Fósseis</h2>

            <p>Selecione os campos que deseja exportar e depois escolha o formato de exportação desejado.</p>

            <form id="exportForm" action="../scripts/export.php" method="post">
                <input type="hidden" name="export_format" id="export_format" value="">

                <div class="form-section">
                    <h3><i class="fas fa-filter"></i> Selecionar Campos para Exportação</h3>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            Selecione os campos que deseja incluir no arquivo exportado. Para melhor visualização, recomendamos selecionar apenas os campos relevantes.
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="select-all-container">
                            <div class="form-check custom-checkbox">
                                <input type="checkbox" id="select_all" checked>
                                <label for="select_all"><strong>Selecionar Todos os Campos</strong></label>
                            </div>
                        </div>

                        <div class="field-selection-container">
                            <div class="field-category">
                                <h4>Informações Básicas</h4>
                                <div class="field-options">
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" name="fields[]" id="field_id" value="id" checked>
                                        <label for="field_id">ID</label>
                                    </div>
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" name="fields[]" id="field_title" value="title" checked>
                                        <label for="field_title">Título</label>
                                    </div>
                                </div>
                            </div>

                            <div class="field-category">
                                <h4>Taxonomia</h4>
                                <div class="field-options">
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" name="fields[]" id="field_kingdom" value="kingdom" checked>
                                        <label for="field_kingdom">Reino</label>
                                    </div>
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" name="fields[]" id="field_phylum" value="phylum" checked>
                                        <label for="field_phylum">Filo</label>
                                    </div>
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" name="fields[]" id="field_class" value="class" checked>
                                        <label for="field_class">Classe</label>
                                    </div>
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" name="fields[]" id="field_order" value="order" checked>
                                        <label for="field_order">Ordem</label>
                                    </div>
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" name="fields[]" id="field_family" value="family" checked>
                                        <label for="field_family">Família</label>
                                    </div>
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" name="fields[]" id="field_genus" value="genus" checked>
                                        <label for="field_genus">Género</label>
                                    </div>
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" name="fields[]" id="field_species" value="species" checked>
                                        <label for="field_species">Espécie</label>
                                    </div>
                                </div>
                            </div>

                            <div class="field-category">
                                <h4>Localização</h4>
                                <div class="field-options">
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" name="fields[]" id="field_latitude" value="latitude" checked>
                                        <label for="field_latitude">Latitude</label>
                                    </div>
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" name="fields[]" id="field_longitude" value="longitude" checked>
                                        <label for="field_longitude">Longitude</label>
                                    </div>
                                </div>
                            </div>

                            <div class="field-category">
                                <h4>Descoberta e Origem</h4>
                                <div class="field-options">
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" name="fields[]" id="field_discovered_by" value="discovered_by" checked>
                                        <label for="field_discovered_by">Descoberto por</label>
                                    </div>
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" name="fields[]" id="field_date_discovered" value="date_discovered" checked>
                                        <label for="field_date_discovered">Data de descoberta</label>
                                    </div>
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" name="fields[]" id="field_created_at" value="created_at" checked>
                                        <label for="field_created_at">Data de criação</label>
                                    </div>
                                    <div class="form-check custom-checkbox">
                                        <input type="checkbox" name="fields[]" id="field_source" value="source" checked>
                                        <label for="field_source">Fonte</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h3><i class="fas fa-download"></i> Escolha o Formato de Exportação</h3>
                <div class="export-options">
                    <div class="export-option" data-format="excel">
                        <i class="fas fa-file-excel export-icon"></i>
                        <h3 class="export-title">Excel (.xlsx)</h3>
                        <p class="export-desc">Formato compatível com Microsoft Excel, LibreOffice Calc e outros editores de planilhas.</p>
                        <button class="export-btn" data-format="excel">
                            <i class="fas fa-download"></i> Exportar Excel
                        </button>
                    </div>

                    <div class="export-option" data-format="csv">
                        <i class="fas fa-file-csv export-icon"></i>
                        <h3 class="export-title">CSV (.csv)</h3>
                        <p class="export-desc">Formato simples de texto separado por vírgulas, compatível com muitos sistemas.</p>
                        <button class="export-btn" data-format="csv">
                            <i class="fas fa-download"></i> Exportar CSV
                        </button>
                    </div>

                    <div class="export-option" data-format="sql">
                        <i class="fas fa-database export-icon"></i>
                        <h3 class="export-title">SQL (.sql)</h3>
                        <p class="export-desc">Script SQL com comandos INSERT para importar em qualquer banco de dados.</p>
                        <button class="export-btn" data-format="sql">
                            <i class="fas fa-download"></i> Exportar SQL
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Loading Spinner -->
<div id="loadingSpinner" style="display: none;">
    <div class="spinner-content">
        <i class="fas fa-spinner"></i>
        <div class="spinner-text">A gerar ficheiro de exportação...</div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Manipulação do select all checkbox
        document.getElementById('select_all').addEventListener('change', function() {
            const isChecked = this.checked;
            const checkboxes = document.querySelectorAll('input[name="fields[]"]');

            checkboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
        });

        // Atualizar o "select all" se algum checkbox for desmarcado
        const checkboxes = document.querySelectorAll('input[name="fields[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                document.getElementById('select_all').checked = allChecked;
            });
        });

        // Configurar o formato ao clicar nos cards
        document.querySelectorAll('.export-option').forEach(option => {
            option.addEventListener('click', function(e) {
                if (!e.target.closest('.export-btn')) {
                    const format = this.getAttribute('data-format');
                    document.getElementById('export_format').value = format;

                    document.querySelectorAll('.export-option').forEach(opt => {
                        opt.classList.remove('active');
                    });

                    this.classList.add('active');
                }
            });
        });

        // Configurar cliques nos botões
        document.querySelectorAll('.export-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const format = this.getAttribute('data-format');
                if (format) {
                    document.getElementById('export_format').value = format;

                    // Verificar se um formato foi selecionado
                    if (!format) {
                        alert('Por favor, selecione um formato de exportação.');
                        return;
                    }

                    // Verificar se pelo menos um campo está selecionado
                    const selectedFields = document.querySelectorAll('input[name="fields[]"]:checked');
                    if (selectedFields.length === 0) {
                        alert('Por favor, selecione pelo menos um campo para exportar.');
                        return;
                    }

                    // Mostrar loading spinner
                    document.getElementById('loadingSpinner').style.display = 'flex';

                    // Iniciar o download usando um iframe oculto para evitar redirecionamento
                    const formData = new FormData(document.getElementById('exportForm'));

                    // Criar um iframe oculto para lidar com o download
                    const iframe = document.createElement('iframe');
                    iframe.style.display = 'none';
                    document.body.appendChild(iframe);

                    // Criar um formulário dentro do iframe
                    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                    const exportForm = iframeDoc.createElement('form');
                    exportForm.method = 'POST';
                    exportForm.action = '../scripts/export.php';

                    // Adicionar os campos do formulário
                    formData.forEach((value, key) => {
                        const input = iframeDoc.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = value;
                        exportForm.appendChild(input);
                    });

                    iframeDoc.body.appendChild(exportForm);
                    exportForm.submit();

                    // Esconder o spinner após um tempo (para dar tempo ao download iniciar)
                    setTimeout(function() {
                        document.getElementById('loadingSpinner').style.display = 'none';
                    }, 2000);
                }
            });
        });

        // Manipular o envio do formulário
        document.getElementById('exportForm').addEventListener('submit', function(e) {
            // Verificar se um formato foi selecionado
            const formatSelect = document.getElementById('export_format');
            if (!formatSelect.value) {
                e.preventDefault();
                alert('Por favor, selecione um formato de exportação.');
                return;
            }

            // Verificar se pelo menos um campo está selecionado
            const selectedFields = document.querySelectorAll('input[name="fields[]"]:checked');
            if (selectedFields.length === 0) {
                e.preventDefault();
                alert('Por favor, selecione pelo menos um campo para exportar.');
                return;
            }

            // Mostrar loading spinner
            document.getElementById('loadingSpinner').style.display = 'flex';
        });
    });
</script>
</body>
</html>