<?php

namespace Model;

class Servicio extends ActiveRecord {
    // BD
    protected static $tabla = 'servicios';
    protected static $columnaDB = ['id', 'nombre', 'precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = []) 
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }
}