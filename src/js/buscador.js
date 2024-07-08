document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});

function iniciarApp() {
    buscarPorFecha();
}

function buscarPorFecha() {
    const fechaInput = document.querySelector('#fecha');
    fechaInput.addEventListener('input', function(e) { // para leer el evento se pasa el parametro e
        const fechaSeleccionada = e.target.value; // Lee el contenido seleccionado

        window.location = `?fecha=${fechaSeleccionada}`; // template string (query string) redirecciona a esa fecha seleccionada 
    });
}