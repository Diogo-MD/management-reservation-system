<?php
session_start();

require_once "Backend/dao/UsuarioDAO.php";

// Verifica o nível de acesso do usuário
$usuarioDAO = new UsuarioDAO();
$is_admin = isset($_SESSION['token']) ? $usuarioDAO->isAdmin($_SESSION['token']) : false;
$is_didatico = isset($_SESSION['token']) ? $usuarioDAO->isDidatico($_SESSION['token']) : false;
$is_gestor = isset($_SESSION['token']) ? $usuarioDAO->isGestor($_SESSION['token']) : false;


if (!isset($_SESSION['token']) || $is_didatico) {
    header("Location: mapao.php");
    exit();
}

require_once "Backend/config/Database.php";
require_once "Backend/dao/ReservaDAO.php";
require_once "Backend/dao/salaDAO.php";


$reservaDAO = new ReservaDAO();
$reservas = $reservaDAO->listarSalasPorPeriodo(date("y-m-d"), date("y-m-d"), "06:00:00", "22:00:00", "1, 2, 3, 4, 5, 6");


$dia_semana_numero = 0;

function obterNumeroDiaSemana($data)
{
    // Obter o nome completo do dia da semana a partir da data
    $diaSemana = date('l', strtotime($data)); // 'l' retorna o nome completo do dia da semana

    // Inicializar a variável que armazenará o número do dia da semana
    $dia_semana_numero = 0;

    // Usar switch para mapear o nome do dia da semana para seu número correspondente
    switch ($diaSemana) {
        case "Monday":
            $dia_semana_numero = 1;
            break;
        case "Tuesday":
            $dia_semana_numero = 2;
            break;
        case "Wednesday":
            $dia_semana_numero = 3;
            break;
        case "Thursday":
            $dia_semana_numero = 4;
            break;
        case "Friday":
            $dia_semana_numero = 5;
            break;
        case "Saturday":
            $dia_semana_numero = 6;
            break;
        case "Sunday":
            $dia_semana_numero = 7;
            break;
    }

    return $dia_semana_numero;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save']) && isset($_POST['dias'])) {
        $data_inicio = $_POST['data_inicio'];
        $data_fim = $_POST['data_fim'];
        $horario_inicio = $_POST['horario_inicio'];
        $horario_fim = $_POST['horario_fim'];
        $dias_semana = implode(", ", $_POST['dias']);

        $reservas = $reservaDAO->listarSalasPorPeriodo($data_inicio, $data_fim, $horario_inicio, $horario_fim, $dias_semana);
    } else {
        echo "<h1>Não Existem salas reservadas neste periodo.</h1>";
    }
}


?>

<?php
require_once "Frontend/template/header.php";
?>
<br>



<div class="container">


    <div class="d-flex justify-content-between">
        <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#myModal">
            Filtrar
        </button>
        <form class="d-flex" role="search" onsubmit="return false;"> <!-- Impede o comportamento de submissão -->
            <input id="searchInput" class="form-control me-2" type="search" placeholder="Pesquisar" aria-label="Search">
        </form>
    </div>


    <br>

    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Filtro de salas Já reservadas</h1>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="index.php" method="post">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="data_inicio">Data Inicio: </label>
                                    <input type="date" class="form-control" name="data_inicio" require>
                                </div>
                                <div class="form-group">
                                    <label for="data_fim">Data Fim: </label>
                                    <input type="date" class="form-control" name="data_fim" require>
                                </div>

                                <div class="form-group">
                                    <label for="horario_inicio">Horario Inicio: </label>
                                    <input type="time" class="form-control" name="horario_inicio" require>
                                </div>
                                <div class="form-group">
                                    <label for="horario_fim">Horario Fim: </label>
                                    <input type="time" class="form-control" name="horario_fim" require>
                                </div>
                                
                                <h5>Dias da semana:</h5>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="seg" name="dias[]" value="1" />
                                        <label class="form-check-label" for="seg">Seg</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label" for="ter">Ter</label>
                                        <input type="checkbox" class="form-check-input" id="ter" name="dias[]" value="2">
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label" for="qua">Qua</label>
                                        <input type="checkbox" class="form-check-input" id="qua" name="dias[]" value="3">
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label" for="qui">Qui</label>
                                        <input type="checkbox" class="form-check-input" id="qui" name="dias[]" value="4">
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label" for="sex">Sex</label>
                                        <input type="checkbox" class="form-check-input" id="sex" name="dias[]" value="5">
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label" for="sab">Sab</label>
                                        <input type="checkbox" class="form-check-input" id="sab" name="dias[]" value="6">
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label" for="dom">Dom</label>
                                        <input type="checkbox" class="form-check-input" id="dom" name="dias[]" value="7">
                                    </div>
                                </div>

                            </div>
                            <br>
                            <button type="submit" name="save" class="btn btn-success">Filtar</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5" id="exampleModalLabel">Consultar Salas Disponiveis</h3>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                </div>

            </div>

        </div>
    </div>


    <div class="table-responsive">
        <table style="background-color: #fff;" class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Numero</th>
                    <th scope="col">Titulo</th>
                    <th scope="col">Data Inicio</th>
                    <th scope="col">Data fim</th>
                    <th scope="col">Horario Inicio</th>
                    <th scope="col">Horario fim</th>
                    <th scope="col">Docente</th>
                    <th scope="col">Dias semana</th>
                </tr>
            </thead>
            <tbody id="items">
                <?php foreach ($reservas as $reserva) : ?>
                    <tr class="item-table">
                        <td class="table-number"><?php echo $reserva['numero']; ?></td>
                        <td class="table-title"><?php echo $reserva['titulo']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($reserva['data_inicio'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($reserva['data_fim'])); ?></td>
                        <td><?php echo $reserva['horario_inicio']; ?></td>
                        <td><?php echo $reserva['horario_fim']; ?></td>
                        <td><?php echo $reserva['docente']; ?></td>
                        <?php
                        $dias = explode(", ", $reserva['dias_semana']);
                        $string_dias = array();

                        foreach ($dias as $dia) {
                            switch ($dia) {
                                case 1:
                                    array_push($string_dias, "seg");
                                    break;

                                case 2:
                                    array_push($string_dias, "ter");
                                    break;

                                case 3:
                                    array_push($string_dias, "qua");
                                    break;
                                case 4:
                                    array_push($string_dias, "qui");
                                    break;
                                case 5:
                                    array_push($string_dias, "sex");
                                    break;
                                case 6:
                                    array_push($string_dias, "sab");
                                    break;
                                case 7:
                                    array_push($string_dias, "dom");
                                    break;

                                default:
                                    # code...
                                    break;
                            }
                        }

                        $teste = implode(', ', $string_dias);

                        ?>
                        <td><?php echo $teste; ?></td>
                    <?php endforeach; ?>
                    </tr>
            </tbody>
        </table>
    </div>
</div>

</div>

<script>
    // Selecionar o campo de pesquisa e o container dos eventos
    const searchInput = document.getElementById("searchInput");
    const eventosContainer = document.getElementById("items");

    // Adicionar o evento de escuta de input
    searchInput.addEventListener("input", function() {
        const searchTerm = searchInput.value.toLowerCase().trim(); // O texto inserido em minúsculas e sem espaços

        // Selecionar todos os itens (linhas) da tabela
        const eventoItems = eventosContainer.getElementsByClassName("item-table");

        // Loop para cada linha da tabela e verificar se deve ser exibida ou não
        Array.from(eventoItems).forEach(function(eventoItem) {
            // Selecionar todas as células (td) dentro da linha
            const cells = eventoItem.getElementsByTagName("td");

            // Variável para controlar se a linha deve ser exibida
            let showRow = false;

            // Verificar cada célula da linha
            Array.from(cells).forEach(function(cell) {
                if (cell.textContent.toLowerCase().includes(searchTerm)) {
                    showRow = true; // Se algum valor corresponder ao termo de pesquisa, exibe a linha
                }
            });

            // Exibir ou ocultar a linha da tabela com base no resultado
            eventoItem.style.display = showRow ? "table-row" : "none";
        });
    });
</script>



<?php
require_once "Frontend/template/footer.php";
?>

</html>