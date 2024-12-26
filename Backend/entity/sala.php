<?php
    class Sala {
        private $id;
        private $numero;
        private $capacidade;
        private $andar;
        private $tipo_id;


        public function __construct($id, $numero, $capacidade, $andar, $tipo_id) {
            $this->id = $id;
            $this->numero = $numero;
            $this->capacidade = $capacidade;
            $this->andar = $andar;
            $this->tipo_id = $tipo_id;


        }

        // GETTERS

        public function getId() {
            return $this->id;
        }

        public function getNumero() {
            return $this->numero;
        }

        public function getCapacidade() {
            return $this->capacidade;
        }

        public function getAndar() {
            return $this->andar;
        }

        public function getTipo_id() {
            return $this->tipo_id;
        }



        // SETTERS

        
        public function setNumero($numero) {
            $this->numero = $numero;
        }

        public function setCapacidade($capacidade) {
            $this->capacidade = $capacidade;
        }

        public function setAndar($andar) {
            $this->andar = $andar;
        }

        public function setTipo_id($tipo_id) {
            $this->tipo_id = $tipo_id;
        }
    }
?>