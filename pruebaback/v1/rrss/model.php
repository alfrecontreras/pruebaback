<?php

class Modelo
{
    public $id;
    public $nombre;
    public $icono;
    public $valor;
    public $activo;

    function __construct()
    {
        $this->id = 0;
        $this->nombre = "";
        $this->icono="";
        $this->valor="";
        $this->activo = false;
    }
}
