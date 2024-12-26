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
require_once "Backend/dao/salaDAO.php";
require_once "Backend/dao/tipoDAO.php";

$tipoDAO = new TipoDAO();
$tipos = $tipoDAO->getAll();


if (!isset($sala)) {
    $sala = null;
}

$salaDAO = new salaDAO();
$salas = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $sala = $salaDAO->getById($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save'])) {
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $sala = $salaDAO->getById($_POST['id']);


            $sala->setNumero($_POST['numero']);
            $sala->setCapacidade($_POST['capacidade']);
            $sala->setAndar($_POST['andar']);
            $sala->setTipo_id($_POST['tipo_id']);


            $salaDAO->update($sala);
        } else {
            $novaSala = new Sala(null, $_POST['numero'], $_POST['capacidade'], $_POST['andar'], $_POST['tipo_id']);
            $salaDAO->create($novaSala);
        }

        header('Location: sala.php');
        exit;
    }

    if (isset($_POST['delete']) && isset($_POST['id'])) {
        $salaDAO->delete($_POST['id']);
        header('Location: sala.php');
        exit;
    }
}
?>

<?php
require_once "Frontend/template/header.php";
?>

<body>
    <div class="container">
        <h1 class="my-4">Detalhes da Sala</h1>
        <form action="add_sala.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $sala ? $sala->getId() : ''  ?>">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="numero">Número da Sala</label>
                        <input type="text" class="form-control" id="numero" name="numero" value="<?php echo $sala ? $sala->getNumero() : '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="capacidade">Capacidade da Sala</label>
                        <input type="text" class="form-control" id="capacidade" name="capacidade" value="<?php echo $sala ? $sala->getCapacidade() : '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="andar">Andar Sala</label>
                        <input type="text" class="form-control" id="andar" name="andar" value="<?php echo $sala ? $sala->getAndar() : '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="tipo_id" class="form-label">Tipo de lab.</label>
                        <select style="width: 100%; padding: 5px; border-radius: 5px;" class="form-select" id="tipo_id" name="tipo_id" required>
                            <?php foreach ($tipos as $tipo) : ?>
                                <option value="<?php echo $tipo->getId(); ?>"><?php echo $tipo->getTipo(); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <button type="submit" name="save" class="btn btn-success">Salvar</button>



                    <?php if ($sala) : ?>
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
                                                <p>Tem certeza de que deseja a <b><?php echo $sala->getNumero(); ?></b>?</p>
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
                    <a href="sala.php" class="btn btn-secondary">Voltar</a>
                </div>
            </div>



        </form>
    </div>

    <?php
    require_once "Frontend/template/footer.php";
    ?>