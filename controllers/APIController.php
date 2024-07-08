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

    public static function eliminar() {
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id']; // Obtiene el id de la cita seleccionada

            $cita = Cita::find($id); // Busca el id en la BD

            $cita->eliminar(); // Elimina la cita con el id enviado

            header('Location:' .$_SERVER['HTTP_REFERER']); // Redirecciona a la pagina donde estaba anteriormente el usuario con HTTP_REFERER

            debuguear($id);
        }
    }

    
}