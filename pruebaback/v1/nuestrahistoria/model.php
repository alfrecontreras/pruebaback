<?php

class Modelo
{
    public $id;
    public $texto;
    public $activo;

    function __construct()
    {
        $this->id = 0;
        $this->texto = 'Sin titulo';
        $this->activo = false;
    }
}
