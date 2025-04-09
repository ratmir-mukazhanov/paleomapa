<?php
session_start();
$email = '';
if (isset($_COOKIE['remembered_email'])) {
    $email = $_COOKIE['remembered_email'];
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

  <link rel="stylesheet" href="loginStyle.css">
</head>

<?php 
  require_once "../components/header.php";
?>

<body>
  <div class="container d-flex align-items-center justify-content-center" style="margin-top: 20vh;">
    <form action="loginValidation.php" method="POST" id="loginForm" style="width: 30%;">
      <h1 class="h1 mb-3 text-center">Login</h1>

      <div class="form-floating mb-2">
        <input type="email" class="form-control" id="inputEmail" name="inputEmail" placeholder="name@example.com" value="<?php echo htmlspecialchars($email); ?>" required>
        <label for="inputEmail">Email</label>
      </div>

      <div class="form-floating mb-2">
        <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Password" required>
        <label for="inputPassword">Password</label>
      </div>

      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="mostrarPasswordCheckbox">
        <label class="form-check-label" for="mostrarPasswordCheckbox">Mostrar password</label>
      </div>

      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="remember_email" id="remember_email" <?php echo isset($_COOKIE['remembered_email']) ? 'checked' : ''; ?>>
        <label class="form-check-label" for="remember_email">Guardar email</label>
      </div>

      <button id="btnLogin" class="w-100 btn btn-lg btn-primary" type="submit">Login</button>

      <p class="mt-3 text-center"><a id="textVoltar" class="btn btn-link" href="../index.php">Voltar</a></p>

      <?php if (!empty($_SESSION['error_message'])): ?>
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
          <div class="toast align-items-center text-bg-danger border-0 show" role="alert" id="error-message-login">
            <div class="d-flex">
              <div class="toast-body text-center">
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('mostrarPasswordCheckbox').addEventListener('click', () => {
      const input = document.getElementById("inputPassword");
      input.type = input.type === "password" ? "text" : "password";
    });

    document.getElementById('logo').addEventListener('click', () => {
      window.location.href = '../index.php';
    });

    document.getElementById('loginForm').addEventListener('submit', (e) => {
      const email = document.getElementById("inputEmail");
      const password = document.getElementById("inputPassword");

      email.classList.remove("is-invalid");
      password.classList.remove("is-invalid");

      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email.value)) {
        email.classList.add("is-invalid");
        alert("Insira um email válido.");
        e.preventDefault();
      }
    });

    document.addEventListener("DOMContentLoaded", () => {
      const toastEl = document.getElementById('error-message-login');
      if (toastEl) {
        new bootstrap.Toast(toastEl).show();
      }
    });
  </script>
</body>
</html>
