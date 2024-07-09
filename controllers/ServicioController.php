<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController {
    public static function index(Router $router) {

        session_start();
        isAdmin(); // Proteje la ruta

        $servicios = Servicio::all(); // Trae todos los registros de servicio

        $router->render('servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);
    }

    public static function crear(Router $router) {

        session_start();
        isAdmin(); // Proteje la ruta

        $servicio = new Servicio; // Crear instancia para poder pasarla a la vista

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if(empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios');
            }

        }


        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar(Router $router) {

        session_start();
        isAdmin(); // Proteje la ruta
        // $id = is_numeric($_GET['id']);
        // if(!$id) return;

        $id = $_GET['id'];
        if(!is_numeric($id)) return;

        $servicio = Servicio::find($_GET['id']); // busca en la BD el servicio por su id
        // debuguear($servicio);
        $alertas = [];  
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if(empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios');
            }
        }


        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar() { // No requiere Router $router
        session_start();
        isAdmin(); // Proteje la ruta
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $servicio = Servicio::find($id);
            $servicio->eliminar();
            header('Location: /servicios');
        }
    }
}
