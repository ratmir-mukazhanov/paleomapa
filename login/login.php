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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../css/cores.css">
  <link rel="stylesheet" href="loginStyle.css">
</head>
<?php
  require_once "../components/header.php";
?>
<body>
  <!-- Elementos decorativos de fundo -->
  <div class="bg-decoration bg-decoration-1"></div>
  <div class="bg-decoration bg-decoration-2"></div>
  
  <div class="container d-flex align-items-center justify-content-center login-container">
    <div class="login-wrapper">
      <form action="loginValidation.php" method="POST" id="loginForm">
        <div class="form-header">
          <h1>Bem-vindo</h1>
          <p class="subtitle">Entre com suas credenciais</p>
        </div>
        
        <div class="form-group">
          <label for="inputEmail" class="form-label">Email</label>
          <div class="input-wrapper">
            <span class="input-icon"><i class="fas fa-envelope"></i></span>
            <input type="email" class="form-control" id="inputEmail" name="inputEmail" 
                   placeholder="Endereço de Email" value="<?php echo htmlspecialchars($email); ?>" required>
          </div>
        </div>
        
        <div class="form-group">
          <label for="inputPassword" class="form-label">Password</label>
          <div class="input-wrapper">
            <span class="input-icon"><i class="fas fa-lock"></i></span>
            <input type="password" class="form-control" id="inputPassword" name="inputPassword" 
                   placeholder="Sua Password" required>
            <button type="button" class="password-toggle" id="passwordToggle">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>

        <button id="btnLogin" class="w-100 btn btn-primary" type="submit">
          <span>Entrar</span>
          <i class="fas fa-arrow-right"></i>
        </button>
        
        <div class="form-footer">
          <a id="textVoltar" href="../index.php">
            <i class="fas fa-chevron-left"></i> Voltar à página inicial
          </a>
        </div>
      </form>
    </div>
  </div>
  
  <?php if (!empty($_SESSION['error_message'])): ?>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
      <div class="toast align-items-center text-bg-danger border-0 show" role="alert" id="error-message-login">
        <div class="d-flex">
          <div class="toast-body">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>
  <?php endif; ?>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Toggle password visibility
    document.getElementById('passwordToggle').addEventListener('click', () => {
      const input = document.getElementById("inputPassword");
      const icon = document.querySelector('#passwordToggle i');
      
      if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
    
    // Form validation
    document.getElementById('loginForm').addEventListener('submit', (e) => {
      const email = document.getElementById("inputEmail");
      const password = document.getElementById("inputPassword");
      email.classList.remove("is-invalid");
      password.classList.remove("is-invalid");
      
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email.value)) {
        email.classList.add("is-invalid");
        showErrorToast("Por favor, insira um email válido.");
        e.preventDefault();
      }
    });
    
    // Show error toast
    function showErrorToast(message) {
      const toastContainer = document.createElement('div');
      toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
      
      const toastElement = `
        <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
          <div class="d-flex">
            <div class="toast-body">
              <i class="fas fa-exclamation-circle me-2"></i> ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      `;
      
      toastContainer.innerHTML = toastElement;
      document.body.appendChild(toastContainer);
      
      const toast = new bootstrap.Toast(toastContainer.querySelector('.toast'));
      toast.show();
      
      setTimeout(() => {
        toastContainer.remove();
      }, 5000);
    }
    
    // Initialize toast if exists
    document.addEventListener("DOMContentLoaded", () => {
      const toastEl = document.getElementById('error-message-login');
      if (toastEl) {
        const toast = new bootstrap.Toast(toastEl, {
          autohide: true,
          delay: 5000
        });
        toast.show();
      }
    });
  </script>
</body>
</html>