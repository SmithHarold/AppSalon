<?php

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController {
    public static function index(Router $router) {

        session_start();

        isAdmin(); // comprueba si el usuario es admin
        
        $fecha = $_GET['fecha'] ?? date('Y-m-d'); // si no hay ninguna fecha seleccionada muestra la fecha del servidor(hoy)
        $fechas = explode('-', $fecha); //explode separa una cadena de string
        if(!checkdate($fechas[1], $fechas[2], $fechas[0])) { // valida que los dias sean validos es decir un mes como maximo 31 dias asi sucesivamente
            header('Location: /404'); // si no es valido la fecha dirije a error 404
        };

        
        // consulta la BD
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasservicios ";
        $consulta .= " ON citasservicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasservicios.servicioId ";
        $consulta .= " WHERE fecha =  '$fecha' ";

        $citas = AdminCita::SQL($consulta);

        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha
        ]);
    }
}

