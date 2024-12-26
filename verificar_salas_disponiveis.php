<?php
require_once "Backend/dao/salaDAO.php";
require_once 'Backend/dao/ReservaDAO.php';
$reservaDAO = new ReservaDAO();
$salasDAO = new SalaDAO();

// Recebe os dados via POST
$data = json_decode(file_get_contents('php://input'), true);

$data_inicio = $data['data_inicio'];
$data_fim = $data['data_fim'];
$horario_inicio = $data['horario_inicio'];
$horario_fim = $data['horario_fim'];
$dias_semana = implode(", ", $data['dias_semana']);


// Recupera as salas e verifica os conflitos
$salas = $salasDAO->getAll();
$salasDisponiveis = [];

foreach ($salas as $sala) {
    $sala_id = $sala->getId();
    $conflitos = $reservaDAO->isConflict($data_inicio, $data_fim, $horario_inicio, $horario_fim, $sala_id, $dias_semana);

    // Se não houver conflitos, a sala é adicionada à lista de disponíveis
    if (empty($conflitos)) {
        $salasDisponiveis[] = ['id' => $sala->getId(), 'numero' => $sala->getNumero()];
    }
}

// Retorna as salas disponíveis em formato JSON
echo json_encode(['salasDisponiveis' => $salasDisponiveis]);
?>
