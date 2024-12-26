<?php

session_start(); // Inicia uma sessão na página

require_once "Backend/dao/UsuarioDAO.php";

// Verifica o nível de acesso do usuário
$usuarioDAO = new UsuarioDAO();
$is_didatico = isset($_SESSION['token']) ? $usuarioDAO->isDidatico($_SESSION['token']) : false;



if (!isset($_SESSION['token']) || $is_didatico) {
    header("Location: mapao.php");
    exit();
}
require_once "Backend/config/Database.php";
require_once "Backend/dao/tipoDAO.php";


if (!isset($tipo)) {
    $tipo = null;
}

$tipoDAO = new TipoDAO();
$tipos = null;


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $tipo = $tipoDAO->getById($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save'])) {
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $tipo = $tipoDAO->getById($_POST['id']);

            $tipo->setTipo_sala($_POST['tipo_sala']);


            $tipoDAO->update($tipo);
        } else {
            $novoTipo = new Tipo(null, $_POST['tipo_sala']);
            $tipoDAO->create($novoTipo);
        }

        header('Location: tipo.php');
        exit;
    }

    if (isset($_POST['delete']) && isset($_POST['id'])) {
        $tipoDAO->delete($_POST['id']);
        header('Location: tipo.php');
        exit;
    }
}
?>


<?php
require_once "Frontend/template/header.php";
?>

<body>
    <div class="container">
        <h1 class="my-4">Detalhes do Tipo de Sala</h1>
        <form action="add_tipo.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $tipo ? $tipo->getId() : ''  ?>">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="tipo_sala">Tipo de Sala</label>
                        <input type="text" class="form-control" id="tipo_sala" name="tipo_sala" value="<?php echo $tipo ? $tipo->getTipo() : ''  ?>" required>
                    </div>

                    <br>

                    <button type="submit" name="save" class="btn btn-success">Salvar</button>
                    <?php if ($tipo) : ?>


                        <button type="button" class="btn btn-danger" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#myModal">Excluir</button>
                        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                        <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                        <div class="card">
                                            <div class="card-body">
                                                <div class="modal-header">
                                                    <h2 class="modal-title" id="exampleModalLabel">Confirmar Exclusão</h2>
                                                </div>
                                            </div>
                                            <div class="modal-body text-center">
                                                <p>Tem certeza de que deseja excluir o tipo de sala <b><?php echo $tipo->getTipo(); ?></b>?</p>
                                                <p>Esta ação não pode ser desfeita.</p>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Cancelar</button>
                                                <button type="submit" name="delete" class="btn btn-danger">Excluir</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>








                            </div>

                        </div>
                    <?php endif ?>
                    <a href="tipo.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
    
    <?php
    require_once "Frontend/template/footer.php";
    ?>