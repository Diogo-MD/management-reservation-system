<?php
    class Reserva {
        private $id;
        private $docente;
        private $data_inicio;
        private $data_fim; 
        private $horario_inicio; 
        private $horario_fim; 
        private $dias_semana; 
        private $evento_id;   
        private $sala_id;
        
        public function __construct($id, $docente, $data_inicio, $data_fim, $horario_inicio, $horario_fim, $dias_semana, $evento_id, $sala_id)
        {
            $this->id = $id;
            $this->docente = $docente;
            $this->data_inicio = $data_inicio;
            $this->data_fim = $data_fim;
            $this->horario_inicio = $horario_inicio;
            $this->horario_fim = $horario_fim;
            $this->dias_semana = $dias_semana;
            $this->evento_id = $evento_id;
            $this->sala_id = $sala_id;
        }

        // GETTERS

        public function getId() {
            return $this->id;
        }

        public function getDocente() {
            return $this->docente;
        }

        public function getData_inicio() {
            return $this->data_inicio;
        }

        public function getData_fim() {
            return $this->data_fim;
        }

        public function getHorario_inicio() {
            return $this->horario_inicio;
        }

        public function getHoraio_fim() {
            return $this->horario_fim;
        }

        public function getDias_semana() {
            return $this->dias_semana;
        }

        public function getEvento_id() {
            return $this->evento_id;
        }

        public function getSala_id() {
            return $this->sala_id;
        }


        // SETTERS

        public function setDocente($docente) {
            $this->docente = $docente;
        }

        public function setData_inicio($data_inicio) {
            $this->data_inicio = $data_inicio;
        }

        public function setData_fim($data_fim) {
            $this->data_fim = $data_fim;
        }

        public function setHorario_inicio($horario_inicio) {
            $this->horario_inicio = $horario_inicio;
        }

        public function setHoraio_fim($horario_fim) {
            $this->horario_fim = $horario_fim;
        }

        public function setDias_semana($dias_semana) {
            $this->dias_semana = $dias_semana;
        }

        public function setEvento_id($evento_id) {
            $this->evento_id = $evento_id;
        }

        public function setSala_id($sala_id) {
            $this->sala_id = $sala_id;
        }
    }
?>