<?php
    session_start();
    require_once 'Backend/dao/UsuarioDAO.php';

    // Verifica o nível de acesso do usuário
    $usuarioDAO = new UsuarioDAO();
    $is_didatico = isset($_SESSION['token']) ? $usuarioDAO->isDidatico($_SESSION['token']) : false;
    
    
    
    if (!isset($_SESSION['token']) || $is_didatico) {
        header("Location: mapao.php");
        exit();
    }
    
    

require_once "Backend/config/Database.php";
require_once "Backend/dao/salaDAO.php";

$salaDAO = new salaDAO();
$salas = $salaDAO->getAll();
?>

<?php
    require_once "Frontend/template/header.php";
?>
    <div class="container">
        <h1 class="my-4">Lista de Salas</h1>
        <a href="add_sala.php" class="btn btn-primary mb-4">Adicionar Sala</a>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($salas as $sala) : ?>

                <?php
                    require_once "Backend/dao/tipoDAO.php";
                    $tipoDAO = new TipoDAO();
                    $tipo_sala = $tipoDAO->getById($sala->getTipo_id());
                ?>

                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">Numero de Sala: <?php echo htmlspecialchars($sala->getNumero(), ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="card-text">Capacidade <?php echo htmlspecialchars($sala->getCapacidade(), ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="card-text">Andar <?php echo htmlspecialchars($sala->getAndar(), ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="card-text"><?php echo htmlspecialchars($tipo_sala->getTipo(), ENT_QUOTES, 'UTF-8'); ?></p>
                            <a href="add_sala.php?id=<?php echo $sala->getId(); ?>" class="btn btn-primary">Editar <i class="fa-solid fa-pen"></i></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>    
        </div>
    </div>
<?php
    require_once "Frontend/template/footer.php";
?>
</html>