<?php

session_start(); // Inicia uma sessão na página

require_once 'Backend/dao/UsuarioDAO.php';

// Verifica o nível de acesso do usuário
$usuarioDAO = new UsuarioDAO();
$is_didatico = isset($_SESSION['token']) ? $usuarioDAO->isDidatico($_SESSION['token']) : false;



if (!isset($_SESSION['token']) || $is_didatico) {
    header("Location: mapao.php");
    exit();
}


include_once "Backend/config/Database.php";
include_once "Backend/dao/UsuarioDAO.php";
include_once "Backend/entity/Usuario.php";



$usuarioDAO = new UsuarioDAO();
$usuario = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['usuario_id'])) {
    $usuario  = $usuarioDAO->getById($_GET['usuario_id']);
}

?>

<?php
require_once "Frontend/template/header.php";
?>


<body>
    <div class="container">
        <h3 class="my-4">Editar Usuário</h3>
        <form action="authService.php" method="POST">
            <input type="hidden" name="type" value="update">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group" style="display: none;">
                            <input type="number" class="form-control" id="id" name="id" value="<?php echo $usuario->getId(); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="new_nome">Nome:</label>
                            <!-- <label for="status_sala">status:</label> -->
                            <input type="text" class="form-control" id="new_nome" name="new_nome" value="<?php echo $usuario->getNome() ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="new_email">Email:</label>
                            <!-- <label for="status_sala">status:</label> -->
                            <input type="text" class="form-control" id="new_email" name="new_email" value="<?php echo $usuario->getEmail() ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="new_password">Senha:</label>
                            <!-- <label for="status_sala">status:</label> -->
                            <input type="password" class="form-control" id="new_password" name="new_password" value="" required>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirme a Senha:</label>
                            <!-- <label for="status_sala">status:</label> -->
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="" required>
                        </div><br>

                        <div class="form-check form-check-inline">
                            <label class="form-check-label" for="didatico">Didático</label>
                            <input type="radio" class="form-check-input" id="didatico" name="opcao" value="Didático">
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label" for="adimin">Administrador</label>
                            <input type="radio" class="form-check-input" id="adimin" name="opcao" value="Admin">
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label" for="gestor">Gestor</label>
                            <input type="radio" class="form-check-input" id="gestor" name="opcao" value="Gestor">
                        </div>

                        <br><br>

                        <button type="submit" class="btn btn-success"><i class="fa-solid fa-pen-to-square"></i></button>
                        <a href="authService.php?usuario_mail=<?php echo $usuario->getEmail(); ?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                        <a href="gestaoUsuarios.php" class="btn btn-secondary"><i class="fa-solid fa-backward"></i></a>
                    </div>
                </div>
        </form>
    </div>
</body>

<?php
require_once "Frontend/template/footer.php";
?>


</html>

