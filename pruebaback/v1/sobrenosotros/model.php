<?php

class Modelo
{
    public $id;
    public $logo_color;
    public $descripcion;
    public $activo;

    function __construct()
    {
        $this->id = 0;
        $this->logo_color = "";
        $this->descripcion="";
        $this->activo = false;
    }
}
