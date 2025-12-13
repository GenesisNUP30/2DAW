var titulo;
function mostrartitulo() {
    titulo = document.getElementById('titulo');
    //alert(titulo.textContent); 
    titulo.innerHTML = "Hola a todos, como estais";
}

function contador() {
    cuenta = document.getElementById('cuenta');
    cuenta.innerHTML = parseInt(cuenta.innerHTML) + 1;

}

function parrafos() {
    parrafos = document.getElementsByTagName('p');
    parrafo = prompt('Parrafo a cambiar');
    parrafos[parrafo].innerHTML = 'PARRAFO CAMBIADO';

}

function sumaCeldas() {
    let suma = 0;
    celdas = document.getElementsByTagName('td');
    for (let i = 0; i < celdas.length; i++) {
        suma = suma + parseInt(celdas[i].innerHTML);
    }
    document.getElementById('suma').innerHTML = 'SUMA: ' + suma;

    suma = 0;
    celdas = document.getElementsByClassName('derecha');
    for (let i = 0; i < celdas.length; i++) {
        suma = suma + parseInt(celdas[i].innerHTML);
    }
    document.getElementById('suma').innerHTML = document.getElementById('suma').innerHTML + '<br>SUMA DERECHA: ' + suma;
}

function aumenta() {
    imagen = document.querySelector('#imagen');1
    //imagen.setAttribute('width',600);
    imagen.width = imagen.width + 10;
}

function cambiaEstilo() {
        miCaja = document.querySelector("#miCaja");
        miCaja.style.backgroundColor = "blue";
        miCaja.style.width = "200px";
}


