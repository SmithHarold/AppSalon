<div class="barra">
    <p>Bienvenido: <?php echo $nombre ?? ''; ?> </p>

    <a class="boton" href="/logout">Cerrar Sesión</a> 
</div>

<h1 class="nombre-pagina">Panel de Administración</h1>

<?php if(isset($_SESSION['admin'])) { ?>
 <div class="barra-servicios">
    <a href="/admin" class="boton">Ver Citas</a>
    <a href="/servicios" class="boton">Ver Servicios</a>
    <a href="/servicios/crear" class="boton">Nuevo Servicio</a>
 </div>
    
<?php } ?>

