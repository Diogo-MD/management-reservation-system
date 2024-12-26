<?php

    class AcessoSalas {

        private $id;
        private $checado;
        private $id_reserva;
        private $data_check;

        public function __construct($id, $checado, $id_reserva, $data_check) 
        {
            $this->id = $id;
            $this->checado = $checado;
            $this->id_reserva = $id_reserva;
            $this->data_check = $data_check;
        }


        public function getId() {
            return $this->id;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function getData_check() {
            return $this->data_check;
        }

        public function setData_check($data) {
            $this->data_check = $data;
        }

        public function getChecado() {
            return $this->checado;
        }

        public function setChecado($check) {
            $this->checado = $check;
        }

        public function getId_reserva() {
            return $this->id_reserva;
        }

        public function setId_reserva($id_reserva) {
            $this->id_reserva = $id_reserva;
        }
    }

?>