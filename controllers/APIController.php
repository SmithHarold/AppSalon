<?php 

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController {
    public static function index() {
        $servicios = Servicio::all();
      
        echo json_encode($servicios); //para generar un JSON con los servicios
        // debuguear($servicios);
    }

    public static function guardar() {
        // Almacena la cita y devuelve el ID
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        $id = $resultado['id'];

        // Almacena la cita y el servicio con el ID de la cita

        $idServicios = explode(",", $_POST['servicios']);// Explode pone un separador por comas del arreglo postservicios

        foreach($idServicios as $idServicio) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }



        echo json_encode(['resultado' => $resultado]);
    }

    
}