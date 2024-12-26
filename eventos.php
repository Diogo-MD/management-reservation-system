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


include_once "Backend/config/Database.php";
include_once "Backend/dao/EventoDAO.php";
include_once "Backend/entity/Evento.php";

$eventoDAO = new EventoDAO();
$eventos = $eventoDAO->getAll();

?>

<?php
require_once "Frontend/template/header.php";
?>

<div class="container">

    <h1 class="my-4">Lista de Eventos</h1>

    <div class="d-flex justify-content-between">
        <a href="eventos_add.php" class="btn btn-primary">Adicionar Evento</a>
        <form class="d-flex" role="search" onsubmit="return false;"> <!-- Impede o comportamento de submissão -->
            <input id="searchInput" class="form-control me-2" type="search" placeholder="Pesquisar" aria-label="Search">
        </form>
    </div>

    <br>

    <div id="eventosContainer" class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($eventos as $evento) : ?>
            <div class="col evento-item">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Título: <?php echo $evento->getTitulo(); ?></h5>
                        <p class="card-title">Sigla: <?php echo $evento->getSigla(); ?></p>
                        <p class="card-text">Oferta: <?php echo $evento->getOferta(); ?></p>
                        <a href="eventos_reservas.php?evento_id=<?php echo $evento->getId(); ?>" class="btn btn-primary">Editar <i class="fa-solid fa-pen"></i></a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>


</div>

<script>
    let search = document.getElementById("")
</script>

<?php
require_once "Frontend/template/footer.php";
?>

<script>
    // Selecionar o campo de pesquisa e o container dos eventos
    const searchInput = document.getElementById("searchInput");
    const eventosContainer = document.getElementById("eventosContainer");
    
    // Adicionar o evento de escuta de input
    searchInput.addEventListener("input", function () {
        const searchTerm = searchInput.value.toLowerCase(); // O texto inserido em minúsculas
        
        // Selecionar todos os itens de evento
        const eventoItems = eventosContainer.getElementsByClassName("evento-item");

        // Loop para cada evento e verificar se deve ser exibido ou não
        Array.from(eventoItems).forEach(function (eventoItem) {
            const titulo = eventoItem.querySelector(".card-title").textContent.toLowerCase();
            const sigla = eventoItem.querySelector(".card-title + p").textContent.toLowerCase(); // Sigla está no segundo p
            
            // Verificar se o título ou a sigla contém o termo de pesquisa
            if (titulo.includes(searchTerm) || sigla.includes(searchTerm)) {
                eventoItem.style.display = "block"; // Exibe o evento
            } else {
                eventoItem.style.display = "none"; // Esconde o evento
            }
        });
    });
</script>
</html>