<?php

namespace Model;


class Admin extends ActiveRecord {
    //Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnaDB = ['id', 'email', 'password'];

    public $id;
    public $email;
    public $password;

    public function __construct($arg =[])
    {
       $this->id = $arg['id'] ?? null;
       $this->email = $arg['email'] ?? ''; 
       $this->password = $arg['password'] ?? '';  
    }

    public function validar(){
        if(!$this->email){
            self::$errores[] = 'El email es obligatorio';
        }
        if(!$this->password){
            self::$errores[] = 'El password es obligatorio';
        }

        return self::$errores;
    }

    public function existeUsuario(){

       // Revisar si un usuario existe o no
        $query = "SELECT * FROM " . self::$tabla . " WHERE email= '" . $this->email . "' LIMIT 1";
        
        $resultado = self::$db->query($query);

        if(!$resultado->num_rows){
            self::$errores[] = 'El usuario no existe';
            return;
        }
        return $resultado;
    }

    public function comprobarPasword($resultado){
        $usuario = $resultado->fetch_object();

        $autenticado = password_verify($this->password, $usuario->password);

        if(!$autenticado){
            self::$errores[] = 'El password es incorrecto';
        }
        return $autenticado;

    }

    public function autenticar(){
        session_start();

        //llenar el arreglo de session
        $_SESSION['usuario'] = $this->email;
        $_SESSION['login'] = true;

        header('Location: /admin');
    }

}


?>