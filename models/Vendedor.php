<?php

namespace Model;

class Vendedor extends ActiveRecord {
   
    protected static $tabla = 'vendedores';
    protected static $columnasDB = ['id','nombre', 'apellido', 'telefonol'];
    public $id;
    public $nombre;
    public $apellido;
    public $telefonol;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->telefonol = $args['telefonol'] ?? '';
       
    }

    public function validar(){
        if (!$this->nombre) {
            self::$errores [] = "El Nombre es Obligatorio";
          }
          if (!$this->apellido) {
            self::$errores [] = "El Apellido es Obligatorio";
          }
          if (!$this->telefonol) {
            self::$errores [] = "El Telefono es Obligatorio";
          }

        if(!preg_match('/[0-9]{10}/', $this->telefonol)){
            self::$errores [] = "Formato No Valido";

        }

          return self::$errores;

    }
  
}

?>