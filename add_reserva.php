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

require_once 'Backend/dao/ReservaDAO.php';
require_once 'Backend/entity/Reserva.php';
require_once "Backend/dao/EventoDAO.php";

require_once "Backend/dao/salaDAO.php";

$salasDAO = new SalaDAO();
$salas = $salasDAO->getAll();

$reservaDAO = new ReservaDAO();
$reserva = null;


$eventoDAO = new EventoDAO();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['reserva_id'])) {
    $reserva  = $reservaDAO->getById($_GET['reserva_id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save']) && isset($_POST['dias'])) {

        $dias_semanaStr = implode(", ", $_POST['dias']);

        $data_inicio = $_POST['data_inicio'];
        $data_fim = $_POST['data_fim'];
        $horario_inicio = $_POST['horario_inicio'];
        $horario_fim = $_POST['horario_fim'];
        $sala_id = $_POST['sala_id'];

        $_isConflict = $reservaDAO->isConflict($data_inicio, $data_fim, $horario_inicio, $horario_fim, $sala_id, $dias_semanaStr);

        function validationConflictUpdate($_isConflict, $id_reserva, $id_sala, $id_evento){
            if($_isConflict['reserva_id'] == $id_reserva && $_isConflict['sala_ID'] == $id_sala && $_isConflict['evento_ID'] == $id_evento){
                return true;
            }

            return false;

        }

        if ($_isConflict || validationConflictUpdate($_isConflict, isset($_GET['reserva_id']), $sala_id, $_POST['evento_id'])) {

            $conflitos = $reservaDAO->isConflict($data_inicio, $data_fim, $horario_inicio, $horario_fim, $sala_id, $dias_semanaStr);

            echo "<div class='alert alert-danger' role='alert'>Já existe uma reserva para este horário e sala.</div>";

            foreach ($conflitos as $conflito) {
                $evento = $eventoDAO->getById($conflito['evento_ID']);
                echo "<div class='alert alert-danger' role='alert'>
                                                                    Data do Conflito: " . $conflito['data_inicio'] .
                    ",<br> Horário: " . $conflito['horario_inicio'] . " até " . $conflito['horario_fim'] .
                    ",<br> Dia inicial: " . $conflito['data_inicio'] .
                    ",<br> Data final: " . $conflito['data_fim'] .
                    ",<br>Evento: " . $conflito['evento_ID'] .
                    ",<br>Número da Sala: " . $conflito['sala_ID'] . "</div>";
            }
        } else {
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $reserva  = $reservaDAO->getById($_POST['id']);
                $dias_semanaStr = implode(", ", $_POST['dias']);

                $reserva->setDocente($_POST['docente']);
                $reserva->setData_inicio($_POST['data_inicio']);
                $reserva->setData_fim($_POST['data_fim']);
                $reserva->setHorario_inicio($_POST['horario_inicio']);
                $reserva->setHoraio_fim($_POST['horario_fim']);
                $reserva->setDias_semana($dias_semanaStr);
                $reserva->setEvento_id($_POST['evento_id']);
                $reserva->setSala_id($_POST['sala_id']);

                $reservaDAO->update($reserva);
            } else {
                if (isset($_POST['dias'])) {
                    $dias_semanaStr = implode(", ", $_POST['dias']);
                } else {
                    echo "<p>Insira os dias da semana</p>";
                    return;
                }

                $novaReserva = new Reserva(null, $_POST['docente'], $_POST['data_inicio'], $_POST['data_fim'], $_POST['horario_inicio'], $_POST['horario_fim'], $dias_semanaStr, $_POST['evento_id'], $_POST['sala_id']);
                $reservaDAO->create($novaReserva);
            }

            header('Location: eventos.php');
            exit;
        }
    } else {
        echo "<div class='alert alert-danger' role='alert'><h5>Insira dias da semana</h5></div>";
    }

    if (isset($_POST['delete']) && isset($_POST['id'])) {
        $reservaDAO->delete($_POST['id']);
        header('Location: eventos.php');
        exit;
    }
}
?>

<?php
require_once "Frontend/template/header.php";
?>
<br>
<div class="container">

    <h3>Detalhes da Reserva</h3>
    <br>
    <form action="add_reserva.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $reserva ? $reserva->getId() : ''  ?>">
        <div class="card">
            <div class="card-body">
                <div class="form-group" style="display: none;">
                    <label for="evento_id">Evento id:</label>
                    <input type="number" class="form-control" id="evento_id" name="evento_id" value="<?php echo $_GET['evento_id'] ? $_GET['evento_id'] : ''  ?>" required>
                </div>
                <div class="form-group">
                    <label for="docente">Docente:</label>
                    <input type="text" class="form-control" id="docente" name="docente" value="<?php echo $reserva ? $reserva->getDocente() : ''  ?>" required>
                </div>
                <div class="form-group">
                    <label for="data_inicio">Data Inicio:</label>
                    <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="<?php echo $reserva ? $reserva->getData_inicio() : ''  ?>">
                </div>
                <div class="form-group">
                    <label for="data_fim">Data Fim:</label>
                    <input type="date" class="form-control" id="data_fim" name="data_fim" value="<?php echo $reserva ? $reserva->getData_fim() : ''  ?>" required>
                </div>
                <div class="form-group">
                    <label for="horario_inicio">horario Inicio:</label>
                    <input type="time" class="form-control" id="horario_inicio" name="horario_inicio" value="<?php echo $reserva ? $reserva->getHorario_inicio() : ''  ?>" required>
                </div>
                <div class="form-group">
                    <label for="horario_fim">Horario Fim:</label>
                    <input type="time" class="form-control" id="horario_fim" name="horario_fim" value="<?php echo $reserva ? $reserva->getHoraio_fim() : ''  ?>" required>
                </div>
                <div class="form-group">
                    <h5>Dias Da Semana:</h5>
                    <?php
                    //$dias_semana = explode(", ", $reserva->getDias_semana());
                    ?>
                    <div class="d-flex" style="justify-content: center;">
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
                <div class="mb-3">
                    <label for="sala_id" class="form-label">Salas: </label>
                    <select style="width: 100%; padding: 5px; border-radius: 5px;" id="sala_id" name="sala_id" required>
                        <?php foreach ($salas as $sala) : ?>
                            <option value="<?php echo $sala->getId(); ?>"><?php echo $sala->getNumero(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <script>
    let data_fim = document.getElementById("data_fim");
    let data_inicio = document.getElementById("data_inicio");

    let horario_inicio = document.getElementById("horario_inicio");
    let horario_fim = document.getElementById("horario_fim");

    // Executa ao perder o foco (blur)
    data_fim.addEventListener("blur", function() {
        if (data_fim.value < data_inicio.value) {
            window.alert("A data final não pode ser antes da data inicial!");
            data_fim.value = '';
        }
    });

    // Também executa ao perder o foco (blur) em vez de input
    horario_fim.addEventListener("blur", function() {
        if (horario_fim.value < horario_inicio.value) {
            window.alert("O horário final não pode ser antes do horário de início");
            horario_fim.value = "";
        }
    });
</script>

                <button type="submit" name="save" class="btn btn-success">Salvar</button>
                <?php if ($reserva) : ?>
                    <button type="button" class="btn btn-danger" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#myModal">Excluir</button>
                <?php endif ?>
                <a href="eventos.php" class="btn btn-secondary">Voltar</a>

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
                                        <div class="modal-body text-center">
                                            <p>Tem certeza de que deseja excluir a reserva de <b><?php echo $reserva->getDocente(); ?></b> para a sala <b><?php echo $sala->getNumero(); ?></b>?</p>
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

                </div>
            </div>
    </form>
</div>


<script>
    let dias_semana = [<?= $reserva->getDias_semana() ?>];
    dias_semana.forEach(dia_semana => {
        if (dia_semana == 1) {
            document.getElementById("seg").setAttribute('checked', true)
        }

        if (dia_semana == 2) {
            document.getElementById("ter").setAttribute('checked', true)
        }

        if (dia_semana == 3) {
            document.getElementById("qua").setAttribute('checked', true)
        }

        if (dia_semana == 4) {
            document.getElementById("qui").setAttribute('checked', true)
        }

        if (dia_semana == 5) {
            document.getElementById("sex").setAttribute('checked', true)
        }

        if (dia_semana == 6) {
            document.getElementById("sab").setAttribute('checked', true)
        }

        if (dia_semana == 7) {
            document.getElementById("dom").setAttribute('checked', true)
        }
    })
</script>

<script>

document.getElementById("data_inicio").addEventListener("change", checkAvailableRooms);
document.getElementById("data_fim").addEventListener("change", checkAvailableRooms);
document.getElementById("horario_inicio").addEventListener("change", checkAvailableRooms);
document.getElementById("horario_fim").addEventListener("change", checkAvailableRooms);

const diasCheckboxes = document.querySelectorAll('input[name="dias[]"]');
diasCheckboxes.forEach(checkbox => {
    checkbox.addEventListener("change", checkAvailableRooms);
});

    function checkAvailableRooms() {
    // Obtenção dos valores dos campos do formulário
    const dataInicio = document.getElementById("data_inicio").value;
    const dataFim = document.getElementById("data_fim").value;
    const horarioInicio = document.getElementById("horario_inicio").value;
    const horarioFim = document.getElementById("horario_fim").value;
    const diasSemana = [];
    const diasCheckbox = document.querySelectorAll('input[name="dias[]"]:checked');

    diasCheckbox.forEach(checkbox => {
        diasSemana.push(checkbox.value);
    });

    // Se os campos não estiverem preenchidos, não faz a verificação
    if (!dataInicio || !dataFim || !horarioInicio || !horarioFim || diasSemana.length === 0) {
        return;
    }

    // Aqui vai a requisição para o servidor para verificar as salas disponíveis
    // O ideal seria você chamar uma API ou fazer uma requisição AJAX para verificar os conflitos no servidor.
    // Suponhamos que você tenha a função PHP isConflict no lado do servidor e que você use AJAX para chamar essa função.

    // Exemplo de chamada AJAX usando fetch
    fetch('verificar_salas_disponiveis.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            data_inicio: dataInicio,
            data_fim: dataFim,
            horario_inicio: horarioInicio,
            horario_fim: horarioFim,
            dias_semana: diasSemana
        })
    })
    .then(response => response.json())
    .then(data => {
        // Atualize o campo de seleção das salas com as salas disponíveis
        const salasDisponiveis = data.salasDisponiveis;

        // Limpar as opções existentes
        const salaSelect = document.getElementById("sala_id");
        salaSelect.innerHTML = '';

        // Adicionar novas opções (salas disponíveis)
        salasDisponiveis.forEach(sala => {
            const option = document.createElement('option');
            option.value = sala.id;
            option.textContent = sala.numero;
            salaSelect.appendChild(option);
        });

    })
    .catch(error => {
        console.error('Erro ao verificar salas disponíveis:', error);
    });
}

</script>

<?php
require_once "Frontend/template/footer.php";
?>