<?php

class Personal {
    public $nombre;
    public $profesion;

    function __construct() {
        $this->nombre = '';
        $this->profesion = '';
    }
}

class Posicion {
    public $abreviado;
    public $nombre;

    function __construct() {
        $this->abreviado = '';
        $this->nombre = '';
    }
}

class RedesSociales {
    public $rrss_nombre;
    public $rrss_icono;
    public $rrss_valor;

    function __construct() {
        $this->rrss_nombre = null;
        $this->rrss_icono = null;
        $this->rrss_valor = null;
    }
}

class Modelo {
    public $id;
    public $personal;
    public $posicion;
    public $redes_sociales;
    public $activo;

    function __construct() {
        $this->id = 0;
        $this->personal = new Personal();
        $this->posicion = new Posicion();
        $this->redes_sociales = new RedesSociales();
        $this->activo = false;
    }

    public function fromArray($data) {
        $this->id = $data['id'];
        $this->personal->nombre = $data['nombre'] . ' ' . $data['apellido'];
        $this->personal->profesion = $data['profesion'];
        $this->posicion->abreviado = $data['posicion_abreviado'];
        $this->posicion->nombre = $data['posicion_nombre'];
        if ($data['rrss_nombre'] || $data['rrss_icono'] || $data['rrss_valor']) {
            $this->redes_sociales->rrss_nombre = $data['rrss_nombre'];
            $this->redes_sociales->rrss_icono = $data['rrss_icono'];
            $this->redes_sociales->rrss_valor = $data['rrss_valor'];
        } else {
            $this->redes_sociales = null;
        }
        $this->activo = $data['activo'];
    }
}