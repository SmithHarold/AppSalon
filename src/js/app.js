let paso = 1; //muestra el primer paso por defecto

const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function() { //cuando todo el DOM este cargado ejecutar la sgte funcion 
    iniciarApp();
    
});

function iniciarApp() {
    mostrarSeccion();//muestra u oculta las secciones
    tabs(); // Cambia la sección cuando se presionan los tabs
    botonesPaginador(); // Agrega o quita los botones del paginador

    paginaSiguiente();
    paginaAnterior();

    consultarAPI(); // Consulta la API en el backend de PHP

    idCliente();

    nombreCliente(); // Añade el nombre del cliente al objetode cita

    seleccionarFecha(); // Añade la fecha de la cita en el objeto
    seleccionarHora(); // Añade la hora de la cita en el objeto

    mostrarResumen(); // MUestra el resumen de la cita
}

function mostrarSeccion() {

    // console.log('MOstrndo seccion');

    //ocultar la sección que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    // seccionAnterior.classList.remove('mostrar');
    if (seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');
    }
    
    // seleccionar la sección con el paso
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar'); //agrega la clase mostrar


    // Elimina la clase actual o anterior
    const tabAnterior = document.querySelector('.actual');
    if (tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

    // Resalta el tab actual (La seccion)
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');    

}

function tabs() {
    const botones = document.querySelectorAll('.tabs button');

    botones.forEach( boton => {
        boton.addEventListener('click', function(e) {
            paso = parseInt(e.target.dataset.paso); //identifica donde se dio click
            
            mostrarSeccion();
            botonesPaginador();

            
        });
    })
}

function botonesPaginador() {

    // // Elimina la clase actual o anterior
    // const botonAnterior = document.querySelector('.ocultar');
    // if (botonAnterior) {
    //     botonAnterior.classList.remove('ocultar');
    // }

    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    if(paso === 1) {
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
        
    } else if(paso === 3) {
        
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');

        mostrarResumen();
    } else {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }

    mostrarSeccion();
}

function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function() {

        if(paso <= pasoInicial) return;
        paso--;
        botonesPaginador();

    });
}

function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function() {

        if(paso >= pasoFinal) return;
        paso++;
        botonesPaginador();

    });
}

async function consultarAPI() {
    try {
        // const url = '${location.origin}/api/servicios';1 ocion
        const url = '/api/servicios'; // 2 opcion
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);
    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    servicios.forEach(servicio => {
        const {id, nombre, precio} = servicio

        // cuando se crea html por js es recomendable ponerlo en mayusculas
        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$ ${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function() {
            seleccionarServicio(servicio); //callback
        };

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        // inyectamos el codigo generado en HTML
        document.querySelector('#servicios').appendChild(servicioDiv);

    });
}

function seleccionarServicio(servicio) {

    const {id} = servicio;

    const { servicios } = cita;

    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`); // Identifica a lo que se esta dando click

    // Comprobar si un servicio ya fue agregado
    if(servicios.some(agregado => agregado.id === id)) { // some itera sobre el arreglo servicios y retorna true o false
        //agregado.id es lo que esta en memoria servicio.id es lo que se selecciona en la interfaz de usuario

        // Eliminarlo //filter permite sacar cierta inf en base a cierta condicion
        cita. servicios = servicios.filter(agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');
    } else {

        // Agregarlo
        cita.servicios = [...servicios, servicio]; // Toma una copia de lo que hay en el arreglo

        divServicio.classList.add('seleccionado');
    }  

}

function idCliente() {
    cita.id = document.querySelector('#id').value;
    
}

function nombreCliente() {
    cita.nombre = document.querySelector('#nombre').value;
    
}

function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e) {

        const dia = new Date(e.target.value).getUTCDay(); // getUTCDate retorna los dias como números enteros

        // Validar los dias laborables
        // includes permite comprobar si un valor existe
        if( [6, 0].includes(dia)) {  // Se cerea un arreglo de 6 sabado y 0 domingo
            e.target.value = ''; // No permite que la fecha se seleccione en el input

            mostrarAlerta('Lo sentimos, Sábados y Domingos no atendemos', 'error', '.formulario');

        } else {
            mostrarAlerta('La fecha se selecciono con éxito', 'exito', '.formulario');
            cita.fecha = e.target.value;
        }

        // console.log(dia);


        // cita.fecha = inputFecha.value;
    });
}

function seleccionarHora() {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function(e) {
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0]; // Split separa strings
        
        if(hora < 10 || hora > 18) {

            e.target.value = '';
            mostrarAlerta('La hora no es valida', 'error', '.formulario')
        } else {
            cita.hora = e.target.value;
        }
    })
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {

    //evita que se genere mas de una alerta al mismo tiempo
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia) {
        alertaPrevia.remove();
    };

    //crear la alerta
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const referencia = document.querySelector(elemento); //selecciona el formulario
    referencia.appendChild(alerta); // Agregamos la alerta al formulario

    if(desaparece) {
        setTimeout(() => {
        alerta.remove(); // Elimina la alerta
        }, 2000); // Despues de 3 segundos se ejecutara esta funcion
    }

    

}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    // LImpiar contenido de resumen
    while(resumen.firstChild) { 
        resumen.removeChild(resumen.firstChild);
    }


    if(Object.values(cita).includes('') || cita.servicios.length === 0 ) { // includes (si hay un campo con un valor vacio) lenht verifica si uhn objeto esta fvacio
        mostrarAlerta('Faltan datos de servicios, fecha u hora', 'error', '.contenido-resumen', false)
   
        return;
    }

    // Formatear el div de resumen
const {nombre, fecha, hora, servicios} = cita;


// Heading para servicios en Resumen
const headingServicios = document.createElement('H3');
headingServicios.textContent = 'Resumen de servicios';

resumen.appendChild(headingServicios);

// Iterando en los servicios
servicios.forEach(servicio => {
    const {id, precio, nombre} = servicio;
    const contenedorServicio =document.createElement('DIV');
    contenedorServicio.classList.add('contenedor-servicio');

    const textoServicio = document.createElement('P');
    textoServicio.textContent = nombre;

    const precioServicio = document.createElement('P');
    precioServicio.innerHTML = `<span>Precio:</span> ${precio}`;

    contenedorServicio.appendChild(textoServicio);
    contenedorServicio.appendChild(precioServicio);

    resumen.appendChild(contenedorServicio);
})

// Heding para cita en resumen
const headingCita = document.createElement('H3');
headingCita.textContent = 'Resumen de Cita';

resumen.appendChild(headingCita);

const nombreCliente = document.createElement('P');
nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

// Formatear la fecha en español
const fechaObj = new Date(fecha);
const mes = fechaObj.getMonth();
const dia = fechaObj.getDate() + 2; // En js hay un desfase de 1 cuando se usa date aqui usamos dos veces date por eso mas 2
const year = fechaObj.getFullYear();

const fechaUTC = new Date(Date.UTC(year, mes, dia));

const opciones = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'}
const fechaFormateada = fechaUTC.toLocaleDateString('es-PE', opciones); // toLocaleDateString regresa una fecha formateada en un determinado idioma

const fechaCita = document.createElement('P');
fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

const horaCita = document.createElement('P');
horaCita.innerHTML = `<span>Hora:</span> ${hora}`;

// Boton para crear una cita
const botonReservar = document.createElement('BUTTON')
botonReservar.classList.add('boton');
botonReservar.textContent = 'Reservar cita';
botonReservar.onclick = reservarCita;

// Se manda a llamar para que aparezca en pantalla ( lo que se genera con js botnones parrafos)
resumen.appendChild(nombreCliente);
resumen.appendChild(fechaCita);
resumen.appendChild(horaCita);
resumen.appendChild(botonReservar);

}

async function reservarCita() {

    const { nombre, fecha, hora, servicios, id } = cita;

    const idServicios = servicios.map( servicio => servicio.id); // map almacena los que coinciden
    // console.log(idServicios);
    // return;

    const datos = new FormData();
    datos.append('fecha', fecha); // lado derecho es la variable que se declaro al principio
    datos.append('hora', hora);
    datos.append('usuarioId', id); //lado izquierdo es el objeto que se va acceder
    datos.append('servicios', idServicios);

    try {
         // Petición hacia la API
        const url = '/api/citas'

        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });

        const resultado = await respuesta.json();

        console.log(resultado.resultado);

        if(resultado.resultado) {
            Swal.fire({
                icon: "success",
                title: "Cita Creada",
                text: "Tú cita fué creada correctamente!",
                button: 'OK'
            }).then( () => {
                setTimeout(() => {
                    window.location.reload(); // reload() recarga la pagina despues que la alerta se muestra con exito
                }, 2000);
            });
        }

    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Hubo un error al crear la cita!",
            button: 'OK'
          });

    }

   

    // console.log(resultado);

    // console.log([...datos]);

}