<?php
session_start();

require_once "Backend/dao/UsuarioDAO.php";

// Verifica o nível de acesso do usuário
$usuarioDAO = new UsuarioDAO();
$is_didatico = isset($_SESSION['token']) ? $usuarioDAO->isDidatico($_SESSION['token']) : false;

require_once "Backend/config/Database.php";
require_once "Backend/dao/ReservaDAO.php";
require_once "Backend/dao/tipoDAO.php";
require_once "Backend/dao/EventoDAO.php";
require_once "Backend/dao/salaDAO.php";
require_once "Backend/dao/Acesso_salasDAO.php";
require_once "Backend/entity/acesso_salas.php";

$acessoDAO = new AcessoSalasDAO();
$acessos = $acessoDAO->getAll();

date_default_timezone_set('America/Sao_Paulo');

// Função para obter o número do dia da semana
function obterNumeroDiaSemana($data) {
    $diaSemana = date('l', strtotime($data));
    $dia_semana_numero = 0;

    switch ($diaSemana) {
        case "Monday": $dia_semana_numero = 1; break;
        case "Tuesday": $dia_semana_numero = 2; break;
        case "Wednesday": $dia_semana_numero = 3; break;
        case "Thursday": $dia_semana_numero = 4; break;
        case "Friday": $dia_semana_numero = 5; break;
        case "Saturday": $dia_semana_numero = 6; break;
        case "Sunday": $dia_semana_numero = 7; break;
    }

    return $dia_semana_numero;
}

$mapaoDAO = new ReservaDAO();
$dia_atual = date("Y-m-d");
$hora_ini = date("H:i:s");
$hora_atual = date("H:i:s", strtotime('+1 hour'));
$dia = obterNumeroDiaSemana($dia_atual);

// Carregar salas para a data atual por padrão
$mapao = $mapaoDAO->listarSalas($dia_atual, $hora_atual, $hora_atual, $dia);

// Se o formulário de filtro foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save'])) {
        $data_filtro = $_POST['data'];
        $dia_semana_filtro = obterNumeroDiaSemana($data_filtro);
        $horario_inicio = $_POST['horario_inicio'];
        $horario_fim = $_POST['horario_fim'];

        // Listar salas com base na data e horários fornecidos no filtro
        $mapao = $mapaoDAO->listarSalas($data_filtro, $horario_inicio, $horario_fim, $dia_semana_filtro);
    }
}

// Função para lidar com os checkboxes via AJAX
if (isset($_POST['checked']) && isset($_POST['id_reserva']) && isset($_POST['data_filtro'])) {
    $checked = filter_var($_POST['checked'], FILTER_VALIDATE_BOOLEAN);
    $id_reserva = $_POST['id_reserva'];
    $data_filtro = $_POST['data_filtro'];

    // Verifica se já existe um registro para a reserva e a data usada
    $acesso_existente = $acessoDAO->getByReservaAndDate($id_reserva, $data_filtro);

    if ($acesso_existente) {
        // Atualiza o registro existente
        $acesso_existente->setChecado($checked);
        $acessoDAO->update($acesso_existente);
    } else {
        // Cria um novo registro se não existir
        $novo_acesso = new AcessoSalas(null, $checked, $id_reserva, $data_filtro);
        $acessoDAO->create($novo_acesso);
    }
}
?>

<!-- Frontend: Formulário de Filtro e Tabela -->
<?php require_once "Frontend/template/header.php"; ?>

<style>
    .form-check-input[type=checkbox] {
      width: 20px;
      height: 20px;
      border-radius: 100%;
      display: flex;
      justify-content: center;
    }
    .form-check-input[type=checkbox]:checked:focus {
    background-color: #4EBB1F;
    border: none;
}


    .form-check-input[type=checkbox]:checked {
      background-image: none;
      background-color: #4EBB1F;
      border: none;
    }
</style>

<body>
    <div class="container">
        <!-- Botão para abrir o modal de filtro -->
        <button style="margin-top: 2rem; margin-bottom: 2rem;" type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#myModal">
            Filtrar
        </button>

        <!-- Modal de filtro -->
        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Filtrar Salas</h1>
                        <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="mapao.php" method="post">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="data">Data: </label>
                                        <input type="date" class="form-control" name="data" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="horario_inicio">Horário Início: </label>
                                        <input type="time" class="form-control" name="horario_inicio" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="horario_fim">Horário Fim: </label>
                                        <input type="time" class="form-control" name="horario_fim" required>
                                    </div>

                                    <button type="submit" name="save" class="btn btn-success">Filtrar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <h1>Mapa de Salas</h1>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Sala</th>
                        <th scope="col">Título</th>
                        <th scope="col">Início</th>
                        <th scope="col">Fim</th>
                        <th scope="col">Docente</th>
                        <?php if ($is_didatico): ?>
                            <th scope="col">Check</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mapao as $mapa): ?>
                        <tr>
                            <th scope="row"><?php echo $mapa['numero'] ?></th>
                            <td><?php echo $mapa['titulo'] ?></td>
                            <td><?php echo $mapa['horario_inicio'] ?></td>
                            <td><?php echo $mapa['horario_fim'] ?></td>
                            <td><?php echo $mapa['docente'] ?></td>
                            <?php if ($is_didatico): ?>
                                <td>
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        onchange="atualizarCheckbox(this.checked, <?php echo $mapa['id']; ?>, '<?php echo $_POST['data'] ?? date('Y-m-d'); ?>')"
                                        <?php
                                        foreach ($acessos as $acesso) {
                                            if ($acesso->getId_reserva() == $mapa['id'] && $acesso->getData_check() == ($_POST['data'] ?? date('Y-m-d')) && $acesso->getChecado()) {
                                                echo "checked";
                                            }
                                        }
                                        ?>
                                        aria-label="..." />
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Função para atualizar o checkbox via AJAX
        function atualizarCheckbox(checked, id_reserva, data_filtro) {
            let formData = new FormData();
            formData.append('checked', checked);
            formData.append('id_reserva', id_reserva);
            formData.append('data_filtro', data_filtro);

            fetch('mapao.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log('Atualização bem-sucedida:');
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        }
    </script>

<?php require_once "Frontend/template/footer.php"; ?>





