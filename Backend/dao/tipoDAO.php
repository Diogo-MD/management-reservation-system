<?php
    require_once "Backend/config/Database.php";
    require_once "BaseDAO.php";
    require_once "Backend/entity/tipo.php";

    class TipoDAO implements BaseDAO {
        private $db;

        public function __construct() {
            $this->db = Database::getInstance();
        }

        public function getById($id) {
            try {
                $sql = "SELECT * FROM tipo WHERE Id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                $tipo = $stmt->fetch(PDO::FETCH_ASSOC);

                return $tipo ?
                    new Tipo($tipo['id'],
                              $tipo['tipo_sala'])
                    : null;

            } catch (PDOException $e) {
                return false;
            }
        }

        public function getAll() {
            try {
                $sql = "SELECT * FROM tipo";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();

                $tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return array_map(function ($tipo) {
                    return new Tipo($tipo['id'],
                                    $tipo['tipo_sala']);
                }, $tipos);
            } catch (PDOException $e) {
                return false;
            }
        }

        public function create($tipo) {
            try {
                $sql = "INSERT INTO tipo (id, tipo_sala) VALUES
                (null, :tipo)";

                $stmt = $this->db->prepare($sql);

                // Bind parameters by reference
                $tipo = $tipo->getTipo();
                

                $stmt->bindParam(':tipo', $tipo);

                $stmt->execute();

                return true;
            } catch (PDOException $e) {
                return false;
            }
        }

        public function update($tipo) {
            try{
                $existingTipo = $this->getById($tipo->getId());
                    if(!$existingTipo) {
                        return false; // Retorna falso se o usuário não existir
                    }
                    
                    $sql = "UPDATE tipo SET tipo_sala = :tipo_sala WHERE id = :id";
                    
    
                    $stmt = $this->db->prepare($sql);
                    // Bind parameters by reference
                    $id = $tipo->getId();
                    $tipo = $tipo->getTipo();
    
                    $stmt->bindParam(':id', $id);
                    $stmt->bindParam(':tipo_sala', $tipo);
    
                    $stmt->execute();
    
                    return true;
                } catch (PDOException $e) {
                    return false;
                }
        }

        public function delete($id) {
            try {
                $sql = "DELETE FROM tipo WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
           
            } catch (PDOException $e) {
                return false;
            }
        }

    }