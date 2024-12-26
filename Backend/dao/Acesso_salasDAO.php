<?php

require_once "Backend/config/Database.php";
require_once "Backend/entity/acesso_salas.php";
require_once "Backend/dao/BaseDAO.php";

class AcessoSalasDAO implements BaseDAO
{

    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }


    public function getById($id)
    {
        try {
            $sql = "SELECT * FROM acesso_salas WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $acesso = $stmt->fetch(PDO::FETCH_ASSOC);

            return $acesso ?
                new AcessoSalas(
                    $acesso['id'],
                    $acesso['checado'],
                    $acesso['id_reserva'],
                    $acesso['data_check']
                )
                : null;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAll()
    {
        try {
            $sql = "SELECT * FROM acesso_salas";
            $stmt = $this->db->query($sql);

            $acessos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map(function ($acesso) {
                return new AcessoSalas(
                    $acesso["id"],
                    $acesso['checado'],
                    $acesso['id_reserva'],
                    $acesso['data_check']
                );
            }, $acessos);
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function getByReservaAndDate($id_reserva, $data_check)
{
    try {
        $sql = "SELECT * FROM acesso_salas WHERE id_reserva = :id_reserva AND data_check = :data_check";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_reserva', $id_reserva);
        $stmt->bindParam(':data_check', $data_check);
        $stmt->execute();

        $acesso = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($acesso) {
            return new AcessoSalas(
                $acesso["id"],
                $acesso['checado'],
                $acesso['id_reserva'],
                $acesso['data_check']
            );
        }

        return null;
    } catch (\Throwable $th) {
        return false;
    }
}


    public function create($acesso)
    {
        try {
            $sql = "INSERT INTO acesso_salas (id, data_check, checado, id_reserva) VALUES (:id, :data_check, :checado, :id_reserva)";

            $id = $acesso->getId();
            $id_reseva = $acesso->getId_reserva();
            $checado = $acesso->getChecado();
            $data_check = $acesso->getData_check();

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':data_check', $data_check, PDO::PARAM_STR);
            $stmt->bindParam(':checado', $checado, PDO::PARAM_BOOL);
            $stmt->bindParam(':id_reserva', $id_reseva, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function update($acesso)
    {
        try {
            $existingAcesso = $this->getById($acesso->getId());
            if (!$existingAcesso) {
                return false; // Retorna falso se o usuário não existir
            }

            $id = $acesso->getId();
            $checado = $acesso->getChecado();
            $id_reserva = $acesso->getId_reserva();
            $data_check = $acesso->getData_check();

            $sql = "UPDATE acesso_salas SET data_check = :data_check, checado = :checado, id_reserva = :id_reserva WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':data_check', $data_check, PDO::PARAM_STR);
            $stmt->bindParam(':checado', $checado, PDO::PARAM_BOOL);
            $stmt->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);

            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e;
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $sql = "DELETE FROM acesso_salas WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
