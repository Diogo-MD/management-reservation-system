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
include_once "Backend/dao/EventoDAO.php";
include_once "Backend/entity/Evento.php";



$eventoDAO = new EventoDAO();
$evento = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['evento_id'])) {
    $evento  = $eventoDAO->getById($_GET['evento_id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save'])) {
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $evento  = $eventoDAO->getById($_POST['id']);
            // $dias_semanaStr = implode(", ", $_POST['dias']);

            $evento->setTitulo($_POST['titulo']);
            $evento->setSigla($_POST['sigla']);
            $evento->setOferta($_POST['oferta']);


            $eventoDAO->update($evento);
        } else {
            $novoEvento = new Evento(null, $_POST['titulo'],$_POST['sigla'], $_POST['oferta']);
            $eventoDAO->create($novoEvento);
        }

        $evento = $eventoDAO->getByOferta($_POST['oferta']);
        if($evento) {
            header("Location: add_reserva.php?evento_id=" . $evento->getId() . "&reserva_id=" . $_POST['reserva_id'] );
             exit();
        }
        
    }


    if (isset($_POST['delete']) && $evento->getId()) {
        $eventoDAO->delete($evento->getId());
        header('Location: index.php');
        exit;
    }
}
?>

<?php
require_once "Frontend/template/header.php";
?>


<body>
    <div class="container">
        <h3 class="my-4">Detalhes do Evento</h3>
        <form action="eventos_add.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $evento ? $evento->getId() : ''  ?>">
            <div class="card">
                <div class="card-body">

                    <div class="form-group">
                        <label for="titulo">Titulo do Evento:</label>
                        <!-- <label for="status_sala">status:</label> -->
                        <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $evento ? $evento->getTitulo() : ''  ?>" required>
                    </div>

                    <div class="form-group" style="display: none;">
                        <label for="reserva_id">Titulo do Evento:</label>
                        <!-- <label for="status_sala">status:</label> -->
                        <input type="text" class="form-control" id="reserva_id" name="reserva_id" value="<?php echo $_GET['reserva_id'] ? $_GET['reserva_id'] : ''  ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="sigla">Sigla do Evento:</label>
                        <!-- <label for="status_sala">status:</label> -->
                        <input type="text" class="form-control" id="sigla" name="sigla" value="<?php echo $evento ? $evento->getSigla() : ''  ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="oferta">Oferta:</label>
                        <!-- <label for="status_sala">status:</label> -->
                        <input type="text" class="form-control" id="oferta" name="oferta" value="<?php echo $evento ? $evento->getOferta() : ''  ?>" required>
                    </div>

                    <br>

                    <button type="submit" name="save" class="btn btn-success">Salvar</button>
                    <a href="eventos.php" class="btn btn-secondary">Voltar</a>
                </div>
            </div>
        </form>

    </div>
</body>

<?php
require_once "Frontend/template/footer.php";
?>


</html>