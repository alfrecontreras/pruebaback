<?php

class Modelo
{
    public $id;
    public $nombre;
    public $activo;

    function __construct()
    {
        $this->id = 0;
        $this->nombre = 'Sin Nombre';
        $this->activo = false;
    }
}
