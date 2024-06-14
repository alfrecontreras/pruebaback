<?php

class Modelo
{
    public $id;
    public $imagen;
    public $titulo;
    public $descripcion;
    public $activo;

    function __construct()
    {
        $this->id = 0;
        $this->imagen="";
        $this->titulo = 'Sin titulo';
        $this->descripcion="Descripcion X";
        $this->activo = false;
    }
}
