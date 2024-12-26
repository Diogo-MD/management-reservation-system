<?php
    require_once "Backend/config/Database.php";
    require_once "BaseDAO.php";
    require_once "Backend/entity/sala.php";

    class SalaDAO implements BaseDAO {
        private $db;

        public function __construct() {
            $this->db = Database::getInstance();
        }

        public function getById($id) {
            try {
                $sql = "SELECT * FROM sala WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                $sala = $stmt->fetch(PDO::FETCH_ASSOC);

                return $sala ?
                    new Sala($sala['id'],
                            $sala['numero'],
                            $sala['capacidade'],
                            $sala['andar'],
                            $sala['tipo_ID'])
                    : null;

            } catch (PDOException $e) {
                return false;
            }
        }

        public function getAll() {
            try {
                $sql = "SELECT * FROM sala";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();

                $salas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return array_map(function ($sala) {
                    return new Sala($sala['id'],
                                    $sala['numero'],
                                    $sala['capacidade'],
                                    $sala['andar'],
                                    $sala['tipo_ID']);
                }, $salas);
            } catch (PDOException $e) {
                return false;
            }
        }

        public function create($sala) {
            try {
                $sql = "INSERT INTO sala (numero, capacidade, andar, tipo_ID) VALUES
                (:numero, :capacidade, :andar, :tipo_id)";

                $stmt = $this->db->prepare($sql);

                // Bind parameters by reference
                $numero = $sala->getNumero();
                $capacidade = $sala->getCapacidade();
                $andar = $sala->getAndar();
                $tipo_id = $sala->getTipo_id();
                
                $stmt->bindParam(':numero', $numero);
                $stmt->bindParam(':capacidade', $capacidade);
                $stmt->bindParam(':andar', $andar);
                $stmt->bindParam(':tipo_id', $tipo_id);
                

                $stmt->execute();

                return true;
            } catch (PDOException $e) {
                return false;
            }
        }

        public function update($sala) {
            try{
                $existingSala = $this->getById($sala->getId());
                    if(!$existingSala) {
                        return false; // Retorna falso se o usuário não existir
                    }
                    
                    $sql = "UPDATE sala SET numero = :numero, capacidade = :capacidade, andar = :andar, tipo_ID = :tipo_id WHERE id = :id";
                    
    
                    $stmt = $this->db->prepare($sql);
                    // Bind parameters by reference
                    $id = $sala->getId();
                    $numero = $sala->getNumero();
                    $capacidade = $sala->getCapacidade();
                    $andar = $sala->getAndar();
                    $tipo_id = $sala->getTipo_id();
    
                    $stmt->bindParam(':id', $id);
                    $stmt->bindParam(':numero', $numero);
                    $stmt->bindParam(':capacidade', $capacidade);
                    $stmt->bindParam(':andar', $andar);
                    $stmt->bindParam(':tipo_id', $tipo_id);
                    
    
                    $stmt->execute();
    
                    return true;
                } catch (PDOException $e) {
                    return false;
                }
        }

        public function delete($id) {
            try {
                $sql = "DELETE FROM sala WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                
                return true;
            } catch (PDOException $e) {
                return false;
            }
        }
    }
?>