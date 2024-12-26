<?php
class Evento {
    private $id;
    private $titulo;
    private $sigla;
    private $oferta;

    public function __construct($id, $titulo, $sigla, $oferta) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->sigla = $sigla;
        $this->oferta = $oferta;
    }

    // GETTERS
    public function getId() {
        return $this->id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getSigla() {
        return $this->sigla;
    }


    public function getOferta() {
        return $this->oferta;
    }

    // SETTERS
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setSigla($sigla) {
        $this->sigla = $sigla;
    }


    public function setOferta($oferta) {
        $this->oferta = $oferta;
    }
}
?>
