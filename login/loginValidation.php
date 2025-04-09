<?php
session_start();
require_once '../db/db_config.php';
require_once '../db/db_connect.php';

if (!isset($_POST['inputEmail'], $_POST['inputPassword'])) {
    $_SESSION['error_message'] = "Campos obrigatórios em falta.";
    header('Location: login.php');
    exit;
}

$email = trim($_POST['inputEmail']);
$pass = $_POST['inputPassword'];

$connection = connect_db();

try {
    $query = "SELECT * FROM admins WHERE email = $1 LIMIT 1";
    $result = pg_query_params($connection, $query, [$email]);

    if ($result && pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);

        if (isset($_POST['remember_email'])) {
            setcookie('remembered_email', $email, time() + (86400), "/");
        } else {
            setcookie('remembered_email', '', time() - 3600, "/");
        }

        if ($pass === $row['password']) {
            $_SESSION["authenticated"] = true;
            $_SESSION["name"] = $row['nome'];
            $_SESSION["id_admin"] = $row['id_admin'];

            header('Location: ' . ($_SESSION['last_page'] ?? '../index.php'));
            exit;
        } else {
            $_SESSION['error_message'] = "Email ou password incorretos.";
        }
    } else {
        $_SESSION['error_message'] = "Email não encontrado.";
    }

    header('Location: login.php');
    exit;

} catch (Exception $e) {
    $_SESSION['error_message'] = "Erro ao autenticar: " . $e->getMessage();
    header('Location: login.php');
    exit;
}
