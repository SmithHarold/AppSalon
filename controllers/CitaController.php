<?php

namespace Controllers;

use MVC\Router;

class CitaController {
    public static function index(Router $router) {


        session_start(); //inicia la sesion del usuario y protege la ruta

        isAuth(); // verifica si se ha iniciado sesión o no.
        
    
        $router->render('cita/index', [
            'nombre' => $_SESSION['nombre'],
            'id' => $_SESSION['id']

        ]);
    }
}