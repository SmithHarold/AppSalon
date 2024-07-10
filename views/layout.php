<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Salón</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="/build/css/app.css">
    <link rel="icon" type="image/png" href="/build/img/icon.png">
</head>
<body>
    <div class="contenedor-app">
        <div class="imagen">
            
        </div>
            
        <div class="app">
            <?php echo $contenido; ?>
        </div>
    </div>

    <?php
        echo $script ?? ''; //si no hay variables de srcript imprime vacio
    ?>

            
    <!-- <link rel="stylesheet" href="build/js/app.js"> -->
  
    <footer class="footer">
        <?php
            $mesIngles = date('F');
            $mesEspañol = traducirMes($mesIngles);
            $anio = date('Y');
        ?>
        <p class="copyright">Developed by: Smith Harold Corales. Todos los derechos reservados <?php echo $mesEspañol . ' del ' . $anio; ?> &copy;</p>
    </footer>
</body>

</html>