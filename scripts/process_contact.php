<?php
session_start();
require_once '../db/db_connect.php';

// Verificar se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar e sanitizar os dados do formulário
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    // Variáveis para mensagens de erro/sucesso
    $error = false;
    $errorMessage = "";
    
    // Validar os campos obrigatórios
    if (empty($name)) {
        $error = true;
        $errorMessage .= "O nome é obrigatório.<br>";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $errorMessage .= "E-mail inválido ou não fornecido.<br>";
    }
    
    if (empty($message)) {
        $error = true;
        $errorMessage .= "A mensagem é obrigatória.<br>";
    }
    
    // Se não houver erros de validação, inserir no banco de dados
    if (!$error) {
        try {
            // Conectar ao banco de dados
            $conn = connect_db();
            
            // Preparar a query SQL para inserir na tabela contact_us
            $query = "INSERT INTO contact_us (name, email, subject, message) 
                     VALUES ($1, $2, $3, $4)";
                     
            // Executar a query com os parâmetros
            $result = pg_query_params(
                $conn, 
                $query, 
                array($name, $email, $subject, $message)
            );
            
            // Verificar se a inserção foi bem-sucedida
            if ($result) {
                // Armazenar mensagem de sucesso na sessão
                $_SESSION['contact_msg'] = "Mensagem enviada com sucesso! Entraremos em contato em breve.";
                $_SESSION['contact_status'] = "success";
            } else {
                // Armazenar mensagem de erro na sessão
                $_SESSION['contact_msg'] = "Erro ao enviar a mensagem: " . pg_last_error($conn);
                $_SESSION['contact_status'] = "error";
            }
            
            // Fechar a conexão com o banco de dados
            pg_close($conn);
            
        } catch (Exception $e) {
            // Log do erro e exibir mensagem amigável
            error_log("Erro ao inserir contato: " . $e->getMessage());
            $_SESSION['contact_msg'] = "Ocorreu um erro ao processar sua solicitação. Por favor, tente novamente mais tarde.";
            $_SESSION['contact_status'] = "error";
        }
    } else {
        // Se houver erros de validação, armazenar na sessão
        $_SESSION['contact_msg'] = $errorMessage;
        $_SESSION['contact_status'] = "error";
    }
    
    // Redirecionar de volta para a página de contato
    header("Location: ../pages/main.php#contacts");
    exit();
} else {
    // Se alguém tentar acessar este arquivo diretamente, redirecionar para a página principal
    header("Location: ../pages/main.php");
    exit();
}
?>