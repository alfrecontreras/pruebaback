<?php

class Momento {
    public $fecha;
    public $horario;

    function __construct() {
        $this->fecha = '';
        $this->horario = '';
    }
}

class Direccion {
    public $calle;
    public $comuna;

    function __construct() {
        $this->calle = '';
        $this->comuna = '';
    }
}

class Lugar {
    public $id;
    public $nombre;
    public $direccion;

    function __construct() {
        $this->id = 0;
        $this->nombre = '';
        $this->direccion = new Direccion();
    }
}

class Modelo {
    public $id;
    public $momento;
    public $lugar;
    public $activo;

    function __construct() {
        $this->id = 0;
        $this->momento = new Momento();
        $this->lugar = new Lugar();
        $this->activo = false;
    }

    public function fromArray($data) {
        $this->id = $data['id'];
        $this->momento->fecha = $data['fecha'];
        $this->momento->horario = $data['hora'];
        $this->lugar->id = $data['entrenamiento_lugar_id'];
        $this->lugar->nombre = $data['lugar_nombre'];
        $this->lugar->direccion->calle = $data['direccion'];
        $this->lugar->direccion->comuna = $data['comuna'];
        $this->activo = $data['activo'];
    }
}