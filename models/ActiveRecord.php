<?php
namespace Model;

class ActiveRecord{
    //Base de datos
    protected static $db;
    protected static $columnasDB =[];
    protected static $tabla = '';

    //Errores
    protected static $errores = [];

    //Definir la coneccion a la BD
        public static function setDB($database){
        self::$db = $database;
        }


    public function guardar() {
      if(!is_null($this->id)){
        //actualizar
        $this->actualizar();
      }else{
        //creando un nuevo registro
        $this->crear();
      }

    }


    public function crear(){

        //Sanitizar entrada de datos
    $atributos = $this->sanitizarAtributos();
 
    //Insertar en la basede datos
    $query = "INSERT INTO " . static::$tabla  . " (";
    $query .=join(', ', array_keys($atributos));
    $query .= " ) VALUES ('"; 
    $query .= join("', '", array_values($atributos));
    $query .= "')";

    $resultado = self::$db->query($query);

     //Mensaje de exito y error
     if ($resultado) {
      //redireccionar al usuario.
      header('location:/admin?resultado=1');
    }
    }

    public function actualizar(){
              //Sanitizar entrada de datos
    $atributos = $this->sanitizarAtributos();
    $valores = [];
    
    foreach($atributos as $key => $value){
      $valores[]= "{$key} ='{$value}'";

    }

    $query = " UPDATE " . static::$tabla .  " SET ";
    $query .= join(', ', $valores ) ;
    $query .=" WHERE id= '" . self::$db->escape_string($this->id) . "' " ;
    $query .=" LIMIT 1 ";

    $resultado = self::$db->query($query);

    if ($resultado){
      //redireccionar al usuario.
      header('location: /admin?resultado=2');
    }

    return $resultado;
    
    }

    //Eliminar un registro
    public function eliminar(){
    $query = "DELETE FROM " . static::$tabla . " WHERE id  = " . self::$db->escape_string($this->id). " LIMIT 1";
    $resultado = self::$db->query($query);


    if ($resultado) {
      $this->borrarImagen();
      header('location:/admin?resultado=3');
    }
  }



   public function atributos(){
    $atributos = [];
    foreach(static::$columnasDB as $columna){
        if($columna === 'id') continue;
        $atributos[$columna] = $this->$columna;
    }
    return $atributos;
   }

   public function sanitizarAtributos(){
     
    $atributos = $this->atributos();
    $sanitizado =[];

    foreach ($atributos as $key => $value){
        $sanitizado[$key] = self::$db->escape_string($value);
    }

    return $sanitizado;
   }

   //Subida de archivos
   public function setImagen($imagen){

    //Elimina la imagen previa en actualizar
    if(!is_null($this->id)){
      $this->borrarImagen();
    }
    //Asignar al atributo de imagen el nombre de la imagen
    if ($imagen){
        $this->imagen = $imagen;
    }
   }

   //Eliminar Archivo
   public function borrarImagen(){
    //Validar si existe el archivo
    $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
    if($existeArchivo) {
      unlink(CARPETA_IMAGENES . $this->imagen);
    }
   
   }

   //validacion
   public static function getErrores(){
    return static::$errores;
   }

   public function validar(){
    static::$errores =[];
    return static::$errores;
   }

   //Lista todas los registros
   public static function all() {
    $query = "SELECT * FROM " . static::$tabla;

    $resultado = self::consultarSQL($query);

    return $resultado;
   }

   //Obtiene determinado Numero de registro 
   public static function get($cantidad) {
    $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;

    $resultado = self::consultarSQL($query);

    return $resultado;
   }

   //Busca un registro por su ID 
   public static function find($id){
    $query = "SELECT * FROM " . static::$tabla . " WHERE id = ${id}";
    $resultado = self::consultarSQL($query);

    return array_shift($resultado);

   }
   public static function consultarSQL($query){
    //cONSULTAR LA BASE DE DATOS
    $resultado = self::$db->query($query);

    //ITERAR LOS RESULTADOS
    $array = [];
    while($registro = $resultado->fetch_assoc()){
        $array[] = static::crearObjeto($registro);
    }

    //LIBERAR LA MEMORIA
    $resultado->free();

    //RETORNAR LOS RESULTADOS
    return $array;
   }

   protected static function crearObjeto($registro){
    $objeto = new static;

    foreach ($registro as $Key => $value){
        if (property_exists($objeto, $Key)){
            $objeto->$Key = $value;

        }
    }
    return $objeto;
   }

   //sincroniza el objeto en memoria conlos cambios realizados por el usuario
   public function sincronizar( $args = [] ){
    foreach($args as $key => $value){
      if (property_exists($this, $key) && !is_null($value)){
        $this->$key = $value;
      }
    }

   }

}


?>