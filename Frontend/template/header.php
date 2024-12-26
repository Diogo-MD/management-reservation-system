<?php
// Verifica se a sessão já foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Inicia uma sessão na página
}

require_once "Backend/dao/UsuarioDAO.php";

// Verifica o nível de acesso do usuário
$usuarioDAO = new UsuarioDAO();
$is_admin = isset($_SESSION['token']) ? $usuarioDAO->isAdmin($_SESSION['token']) : false;
$is_didatico = isset($_SESSION['token']) ? $usuarioDAO->isDidatico($_SESSION['token']) : false;
$is_gestor = isset($_SESSION['token']) ? $usuarioDAO->isGestor($_SESSION['token']) : false;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema Reserva de Salas</title>
  <!-- Inclua o CSS do MDBootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/mdb.min.css">
  <!-- Inclua seus estilos personalizados -->
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap');

    body {
      min-height: 100vh;
      margin-bottom: 200px;
      font-family: 'Poppins', sans-serif;
      background-color: aliceblue;
    }

    :root {
      font-size: 90%;
    }

    nav {
      min-height: 70px;
      box-shadow: 0px 2px 5px 2px rgba(0, 0, 0, .2);
      background-color: #fff;
    }

    .form-select {
      width: 100%;
    }

    #btnLogout {
      background-color: #fff;
      border: none;
      font-size: 1.2rem;
      margin-top: 7px;
    }

    .card {
      margin-bottom: 1rem;
      background-color: #fff;
    }

    .navbar-nav a {
      width: 100%;
      color: black;
      font-size: 1.2rem;
      transition: .3s ease;
    }

    .navbar-nav a:hover {
      color: #6F6F6F;
    }

    .fa-bars {
      margin-top: 5px;
    }

    footer {
      backdrop-filter: blur(5px);
      background-color: rgba(255, 255, 255, 0.479);
      color: #333;
      padding: 10px;
      text-align: center;
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
    }
  </style>
</head>

<body>
  <header>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary">
      <!-- Container wrapper -->
      <div class="container">
        <!-- Toggle button -->
        <button data-mdb-collapse-init class="navbar-toggler" type="button" data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fas fa-bars"></i>
        </button>

        <!-- Collapsible wrapper -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Navbar brand -->

          <!-- Left links -->
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="mapao.php">Mapa</a>
            </li>

            <?php if ($is_admin || $is_gestor) : ?>
              <li class="nav-item">
                <a class="nav-link" href="eventos.php">Eventos</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="index.php">Consultar</a>
              </li>

              <li class="nav-item dropdown">
                <a data-mdb-dropdown-init class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" aria-expanded="false">
                  <i class="fa-solid fa-building"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                  <li>
                    <a class="dropdown-item" href="sala.php">Salas</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="tipo.php">Labs</a>
                  </li>
                </ul>
              </li>

              <li class="nav-item dropdown">
                <a data-mdb-dropdown-init class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" aria-expanded="false">
                  <i class="fa-solid fa-user"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                      <?php if ($is_admin) : ?>
                        <li>
                          <a class="dropdown-item" href="login.php">Adicionar</a>
                        </li>
                      <?php endif; ?>
                      <?php if ($is_gestor) : ?>
                        <li>
                          <a class="dropdown-item" href="gestaoUsuarios.php">Gestão de Usuários</a>
                        </li>
                      <?php endif; ?>
                  <li>
                    
                      <form action="authService.php" method="post" style="display: inline;">
                        <input type="hidden" name="type" value="logout">
                        <button class="dropdown-item" id="btnLogout" type="submit">Logout</button>
                      </form>
          
                  </li>
                </ul>
              </li>

            <?php else : ?>
              <?php if($is_didatico) : ?>
                <form action="authService.php" method="post" style="display: inline;">
                  <input type="hidden" name="type" value="logout">
                  <button class="dropdown-item" id="btnLogout" type="submit">Logout</button>
                </form>
              <?php endif?>
              <?php if (!isset($_SESSION['token']) || !$is_didatico) : ?>
              <a class="nav-link" href="login.php"><b>Login</b></a>
              <?php endif; ?>
            <?php endif; ?>
          </ul>
        </div>
        <!-- Collapsible wrapper -->

        <!-- Right elements -->
        <div class="d-flex align-items-center">
          <a class="navbar-brand" href="#">
            <img src="assets/logo-senac.webp" alt="Logo Senac" width="90" height="40">
          </a>
        </div>
        <!-- Right elements -->
      </div>
    </nav>
  </header>

  <main>
    <!-- Conteúdo da página -->
  </main>

  <!-- Inclua os scripts JavaScript do MDBootstrap no fim do body -->
  <script type="text/javascript" src="assets/js/mdb.min.js"></script>
</body>

</html>
