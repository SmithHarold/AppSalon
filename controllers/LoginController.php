<?php 

namespace Controllers;

use Clases\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router) {
        $alertas = [];

        $auth = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if(empty($alertas)) { //en caso de que las alertas esten vacias
                //comprobar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);

                if($usuario) {
                    //verificar el password
                   if($usuario->comprobarPasswordAndVerificado($auth->password)) {
                    //autenticar el usuario
                    session_start();

                    $_SESSION['id'] = $usuario->id;
                    $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                    $_SESSION['email'] = $usuario->email;
                    $_SESSION['login'] = true;

                    //redireccionanmiento

                    if($usuario->admin === "1") {
                        $_SESSION['admin'] = $usuario->admin ?? null;
                        header('Location: /admin');
                    } else {
                        header('Location: /cita');
                    }
                   }
                } else {
                    Usuario::setAlerta('error', 'Este Usuario no existe');
                }
            }
           
        }

        $alertas =Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }

    public static function logout() {
        echo "Desde logout";
    }

    public static function olvide(Router $router) {

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();
            
            if(empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);

                if($usuario && $usuario->confirmado === "1") {

                    //generar un token
                    $usuario->crearToken();
                    $usuario->guardar();
                    
                    // Enviar  el email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarInstrucciones();

                    // Alerta de Exito
                    Usuario::setAlerta('exito', 'Revisa tu email');
                    

                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-cuenta', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router) {
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);

        //buscar usuario por su token
        $usuario = Usuario::where('token', $token);

        //mensaje de error 
        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token no Válido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // leer el nuevo password y guardarlo
            $password = new Usuario($_POST);
            $password->validarPassword();

            if(empty($alertas)) {
                $usuario->password = null; //elimina el password anterior
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();
                if($resultado) {
                    header('Location: /');
                }
                debuguear($usuario);
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    
    }

    public static function crear(Router $router) {
        
        $usuario = new Usuario;
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //sincronizar valores
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            //Revisar que alerta este vacio

            if(empty($alertas)) {
                //verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();
                

                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear password
                    $usuario->hashPassword();

                    // Generar un Token único
                    $usuario->crearToken();

                    // Enviar el email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                   
                    $email->enviarConfirmacion();

                    //crear el usuario
                    $resultado = $usuario->guardar();
                    if($resultado) {
                        header('Location: /mensaje');
                    }
                    // debuguear($usuario);
                    //si no esta registrado
                    
                    
                }
            }


        }
         $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
         ]);
    }

    public static function mensaje(Router $router) {
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router) {
        $alertas = [];

        //sanitizar las entradas
        $token = s($_GET['token']);

        $usuario = Usuario::where('token', $token);

        //validar por el token
        if(empty($usuario)) {
            //mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no válido');
        } else {
            //modificar a usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta comprobada con éxito');
        }

        
        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }


}

