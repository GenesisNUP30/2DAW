function creaOpcion() {
    let numero = document.querySelectorAll('li').length;
    console.log(numero);
    let nuevo = document.createElement('li');
    nuevo.innerHTML = 'Opcion ' + parseInt(numero + 1) + '<button onclick="borrar('+numero+');">Borrar</button>';
    console.log(numero);
    let padre = document.querySelector('#opciones');
    padre.appendChild(nuevo);
}

function borraUltimaOpcion() {
    let padre = document.querySelector('#opciones');
    let ultima = padre.lastElementChild;
    padre.removeChild(ultima);
}

function borrar(fila) {
    let opcion = document.querySelectorAll('li')[fila];
    opcion.remove();
    //alert(fila);
}

function crearCeldasTabla() {
    let celda = document.createElement('td');
    celda.innerHTML = 'Celda ' + parseInt(document.querySelectorAll('td').length + 1);
    let padre = document.querySelector('table');
    padre.appendChild(celda);
}


function crearTabla () {
    let filas = prompt('Ingresa el numero de filas');
    let columnas = prompt ('Ingresa el numero de columnas');
    let tabla = document.createElement('table');
    tabla.border = '1';
    let contador = 0;
    for (let i = 0; i < filas; i++) {
        let fila =  document.createElement('tr');
        for (let j = 0; j < columnas; j++) {
            let celdas = document.createElement("td");
            celdas.innerHTML = 'Celda ' + contador;
            contador++;
            fila.appendChild(celdas);
        }
        tabla.appendChild(fila);
    }

    document.body.appendChild(tabla);

}
 