
<?php 
    include_once __DIR__ . '/../templates/barra.php';
?>

<h3>Buscar Citas</h2>
<div class="busqueda">
    <form action="" class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha"name="fecha" value="<?php echo $fecha; ?>">
        </div>
    </form>
</div>

<?php
    if(count($citas) === 0) { // Si las citas es = 0
        echo "<h3>No hay citas en esta fecha</h3>";
    }
?>

<div id="citas-admin">
   <ul class="citas">
        <?php
            $idCita = 0;
            foreach($citas as $key => $cita) {
                // debuguear($key);
                if($idCita !== $cita->id) {
                    $total = 0;

                
        ?>

        <li>
            <p>ID: <span><?php echo $cita->id; ?></span></p>
            <p>Hora: <span><?php echo $cita->hora; ?></span></p>
            <p>Ciente: <span><?php echo $cita->cliente; ?></span></p>
            <p>Email: <span><?php echo $cita->email; ?></span></p>
            <p>Telefono: <span><?php echo $cita->telefono; ?></span></p>

            <h3>Servicios</h3>
             <?php
            $idCita = $cita->id;
             }
                $total += $cita->precio; 
             ?> <!-- fin del if -->
             <p class="servicio"><?php echo $cita->servicio . " " . $cita->precio;?></p>

            <?php
                $actual = $cita->id; // Retorna el id donde nos encontramos
                $proximo = $citas[$key + 1]->id ?? 0; // Indice en la BD

                // echo "<hr>"; 
                // echo $actual;
                // echo "<hr>";
                // echo $proximo;
                if(esUltimo($actual, $proximo)) { ?>
                    <p class="total">Total: <span>$ <?php echo $total; ?> </span></p>

                    <form action="/api/eliminar" method="POST">
                        <input type="hidden" name="id" value="<?php echo $cita->id; ?>">

                        <input type="submit" class="boton-eliminar" value="Eliminar">
                    </form>
            <?php } 
            }
            ?>  <!-- fin del foreach -->
   </ul>
</div>

<?php
    $script = "<script src='build/js/buscador.js'></script>"
?>