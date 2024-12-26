<?php
session_start(); // Inicia uma sessão na página

require_once "Backend/dao/UsuarioDAO.php";

// Verifica o nível de acesso do usuário
$usuarioDAO = new UsuarioDAO();
$is_admin = isset($_SESSION['token']) ? $usuarioDAO->isAdmin($_SESSION['token']) : false;
$is_didatico = isset($_SESSION['token']) ? $usuarioDAO->isDidatico($_SESSION['token']) : false;
$is_gestor = isset($_SESSION['token']) ? $usuarioDAO->isGestor($_SESSION['token']) : false;

require_once "Frontend/template/header.php";
?>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <?php if (!isset($_SESSION['token']) || $is_didatico) : ?>
                <div class="card col-md-6 card-body" style="margin-top: 40px;">
                <br><br>
                    <h2>Login</h2>
                    
                    <form action="authService.php" method="post">
                        <input type="hidden" name="type" value="login">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha:</label>
                            <input type="password" class="form-control" name="password" id="password" required>
                        </div>
                        <br>
                        <button type="submit" style="width: 100%;" class="btn btn-primary">Entrar</button>
                    </form>
                </div>
            <?php endif; ?>

            <!-- antes do if // para comentar e cadastrar primeiro usuario ADM da aplicação -->
            <?php if ($is_admin || $is_gestor) : ?>
              
        
                <h2 style="margin-top: 40px;">Cadastro</h2>

                <div class="card col-md-6 card-body" style="margin-top: 40px;">
                    
                    <form action="authService.php" method="post">
                        <input type="hidden" name="type" value="register">
                        <div class="mb-3">
                            <label for="new_nome" class="form-label">Nome:</label>
                            <input type="text" class="form-control" id="new_nome" name="new_nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="new_email" name="new_email" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Senha:</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmar Senha:</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>

                        <div class="form-check form-check-inline">
                            <label class="form-check-label" for="didatico">Didático</label>
                            <input type="radio" class="form-check-input" id="didatico" name="opcao" value="Didático">
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label" for="adimin">Administrador</label>
                            <input type="radio" class="form-check-input" id="adimin" name="opcao" value="Admin">
                        </div>
                        <?php if($is_gestor) : ?>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label" for="gestor">Gestor</label>
                            <input type="radio" class="form-check-input" id="gestor" name="opcao" value="Gestor">
                        </div>
                        <?php endif; ?>

                        <br><br>
                        <button type="submit" style="width: 100%" class="btn btn-primary">Cadastrar</button>
                    </form>
                </div>
                <!-- antes do endif // para comentar e cadastrar primeiro usuario ADM da aplicação -->
            <?php endif; ?>
      
        </div>
    </div>
</body>

</html>


<?php
require_once "Frontend/template/footer.php";
?>