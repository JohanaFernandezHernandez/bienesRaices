<?php

namespace Controllers;
use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasController {
    public static function index( Router $router ){

        $propiedades = Propiedad::get(3);
        $inicio = true;

        $router->render('paginas/index',[
            'propiedades'=> $propiedades,
            'inicio' => $inicio

        ]);
        
    }

    public static function nosotros(Router $router){
        $router->render('paginas/nosotros', []);
    }

    public static function propiedades(Router $router){

        $propiedades = Propiedad::all();

        $router->render('paginas/propiedades',[
            'propiedades' => $propiedades

        ]);
    }

    public static function propiedad(Router $router){

        $id = validarORedireccionar('/propiedades');
 
        //Buscar la propiedad por el ID 
        $propiedad = Propiedad::find($id);

        $router->render('paginas/propiedad',[
            'propiedad' => $propiedad
            
        ]);
        
    }

    public static function blog(Router $router){
        $router->render('paginas/blog',[]);
    }

    public static function entradas(Router $router){  
        $router->render('paginas/entradas',[]);
    }

    public static function contacto(Router $router){

        $mensaje = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            $respuestas = $_POST['contacto'];
            
            //Crear una instancia de PHPMailer
            $mail = new PHPMailer();

            //Configurar SMTP utilizado para el envio de EMAIL
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io' ;
            $mail->SMTPAuth = true;
            $mail->Username ='849148c6c745d4';
            $mail->Password = 'e9d7accf207c98';
            $mail->SMTPSecure ='tls';
            $mail->Port = 2525;

            //Configurar el contenido del MAIL
            $mail->setFrom('admin@bienesraices.com');
            $mail->addAddress('admin@bienesraices.com', 'Bienesraices.com');
            $mail->Subject = 'Tienes un nuevo mensaje';

            //Habilitar HTML
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            
            //Definir el contenido
            $contenido = '<html>';
            $contenido .= '<p>Tienes un nuevo mensaje </p>' ;
            $contenido .= '<p>Nombre: ' . $respuestas['nombre']  . ' </p>' ;

            //Enviar de forma condicional el correo o telefono
            if ($respuestas['contacto'] === 'telefono'){
                $contenido .='<p>Eligio ser contactado por Telefono: </p>';
                $contenido .= '<p>Telefono: ' . $respuestas['telefono']  . ' </p>' ;
                $contenido .= '<p>Fecha Contacto ' . $respuestas['fecha']  . ' </p>' ;
                $contenido .= '<p>Hora ' . $respuestas['hora']  . ' </p>' ;

            }else{
                //Eligio Email se añade el campo deEmail
                $contenido .='<p>Eligio ser contactado por Email: </p>';
                $contenido .= '<p>Email: ' . $respuestas['email']  . ' </p>' ;

            }
           
            $contenido .= '<p>Mensaje: ' . $respuestas['mensaje']  . ' </p>' ;
            $contenido .= '<p>Vende o Compra: ' . $respuestas['tipo']  . ' </p>' ;
            $contenido .= '<p>Precio o presupuesto: $' . $respuestas['precio']  . ' </p>' ;
            $contenido .= '<p>Prefiere ser contactado por: ' . $respuestas['contacto']  . ' </p>' ;
            $contenido .= '</html>';

            $mail->Body =$contenido;
            $mail->AltBody = 'Esto es texto alternativo sin HTML';

            //Enviar el email
            if($mail->send()){
                $mensaje = "Mensaje enviado Correctamente";
            }else{
                $mensaje = "El mensaje no pudo ser enviado";
            }

         }


           $router->render('paginas/contacto',[
            'mensaje' => $mensaje

           ]);
    }
}

?>