function colorCeldas() {
    let celdas = document.querySelectorAll('#tabla-fotografias td');
    console.log(celdas);
    celdas.forEach(celda => {
        if (celda.textContent === '') {
            celda.style.backgroundColor = '#797979ff';
        }
    });

    let celdasValoracion = document.querySelectorAll('.valoracion');
    celdasValoracion.forEach(celda => {
        let valor = parseInt(celda.textContent);
        if (!isNaN(valor) && valor <= 5) {
            celda.style.backgroundColor = 'red';
        }
    });

    let filas = document.querySelectorAll('#tabla-fotografias tr');
    filas.forEach(fila => {
        fila.addEventListener('mouseover', () => {
            fila.style.backgroundColor = '#cce7ff';
        })
        fila.addEventListener('mouseout', () => {
            fila.style.backgroundColor = '';
        });
    });
}

function mostrarImagen(ruta) {
    const vista = document.getElementById('vista');
    vista.innerHTML = `<img src="${ruta}" alt="Imagen" style="width: 30%; height: 30%;">`;
}

function ocultarImagen() {
    const vista = document.getElementById('vista');
    vista.innerHTML = '';
}



function capturarValor(celda) {
    valorSeleccionado = celda.textContent;
    console.log(valorSeleccionado);
}


function aplicarValor(celda) {
    if (valorSeleccionado !== null) {
        celda.textContent = valorSeleccionado;
        // Actualizar color según nuevo valor
        const valor = parseInt(valorSeleccionado);
        if (valor <= 5) {
            celda.style.backgroundColor = 'red';
        } else {
            celda.style.backgroundColor = '';
        }
        colorCeldas();
    }
}

function rellenarBusqueda() {
    let codigo = document.getElementById('codigo').value;
    let tabla2 = document.getElementById('tabla-articulos');
    let filas = tabla2.getElementsByTagName('tr');

    for (let i = 1; i < filas.length; i++) {
        let codigoBusqueda = filas[i].getElementsByTagName('td')[0].textContent;
        if (codigoBusqueda === codigo) {
            let descripcion = filas[i].getElementsByTagName('td')[1].textContent;
            let precio = filas[i].getElementsByTagName('td')[2].textContent;
            document.getElementById('descripcion').value = descripcion;
            document.getElementById('precio').value = precio;
            document.getElementById('cantidad').value = 1;
            calcularSubtotal();

            break;
        } else {
            document.getElementById('descripcion').value = 'Artículo no encontrado';
            document.getElementById('precio').value = '0';
        }
    }
}

function calcularSubtotal() {
    const cant = parseFloat(document.getElementById('cantidad').value);
    const prec = parseFloat(document.getElementById('precio').value);
    document.getElementById('subtotal').value = (cant * prec).toFixed(2);
}

function anade() {
    let codigo = document.querySelector("#codigo").value;
    let descripcion = document.querySelector("#descripcion").value;
    let cantidad = parseInt(document.querySelector("#cantidad").value);
    let precio = parseInt(document.querySelector("#precio").value);
    const subtotal = precio * cantidad;

    let fila = document.createElement('tr');
    document.getElementById('table').appendChild(fila);

    let celda = document.createElement('td');
    celda.textContent = codigo;
    fila.appendChild(celda);

    let celda2 = document.createElement('td');
    celda2.textContent = descripcion;
    fila.appendChild(celda2);

    let celda3 = document.createElement('td');
    celda3.textContent = cantidad;
    fila.appendChild(celda3);

    let celda4 = document.createElement('td');
    celda4.textContent = precio;
    fila.appendChild(celda4);

    let celda5 = document.createElement('td');
    celda5.textContent = subtotal;
    fila.appendChild(celda5);
    baseIva();


}

function baseIva() {
    let precioTotal = parseFloat(document.getElementById('subtotal').value);
    document.getElementById('base').value = precioTotal.toFixed(2);

    let total = parseFloat(document.getElementById('total').value);
    const iva = total * 0.21;
    document.getElementById('total').value = (total + iva).toFixed(2);
}










