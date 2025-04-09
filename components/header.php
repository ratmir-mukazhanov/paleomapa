<?php
session_start();
?>

<link rel="stylesheet" href="../css/header.css">
<link rel="stylesheet" href="../css/cores.css">

<ul class="headerUL">
    <li id="headerLogo" class="headerList">
        <img src="../img/logoFossils.png" alt="Paleomapa Logo" class="header-logo">
        <span id="headerTitlePage">Paleomapa</span>
    </li>

    <li id="loginHeader" class="headerList">
        <?php if (!empty($_SESSION['authenticated']) && $_SESSION['authenticated'] === true): ?>
            <a href="../login/logout.php" class="login-button">
                <span>Logout</span>
                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                    <path d="M480-120v-80h280v-560H480v-80h280q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H480Zm-80-160-55-58 102-102H120v-80h327L345-622l55-58 200 200-200 200Z"/>
                </svg>
            </a>
        <?php else: ?>
            <a href="../login/login.php" class="login-button">
                <span>Iniciar Sessão</span>
                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                    <path d="M480-120v-80h280v-560H480v-80h280q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H480Zm-80-160-55-58 102-102H120v-80h327L345-622l55-58 200 200-200 200Z"/>
                </svg>
            </a>
        <?php endif; ?>
    </li>
</ul>
