
function anade() {
    let codigo = document.querySelector("#codigo").value;
    let descripcion = document.querySelector("#descripcion").value;
    let cantidad = parseInt(document.querySelector("#cantidad").value);
    let precio = document.querySelector("#precio").value;
    let subtotal = cantidad * precio;
    let fila = document.createElement('tr');
    document.getElementById('table').appendChild(fila);


    let celda = document.createElement('td');
    celda.textContent = codigo;
    fila.appendChild(celda);

    let celda2= document.createElement('td');
    celda2.textContent = descripcion;
    fila.appendChild(celda2);

    let celda3 = document.createElement('td');
    celda3.textContent = cantidad;
    fila.appendChild(celda3);

    let celda4 = document.createElement('td');
    celda4.textContent = precio;
    fila.appendChild(celda4);

    let celda5 = document.createElement('td');
    celda5.setAttribute('class', 'subtotal');
    celda5.textContent = subtotal;
    fila.appendChild(celda5);

    let celda6 = document.createElement('td');
    celda6.innerHTML = '<button onclick="borrar(this);">Borrar</button>';
    fila.appendChild(celda6);
    
    celda6.innerHTML += '<button onclick="modificar(this);">Modificar</button>';
    fila.appendChild(celda6);
    calculatotal();
}

function borrar(celda) {
    let fila = celda.parentNode.parentNode.rowIndex;
    document.getElementById('table').deleteRow(fila);
    calculatotal();   
}

function calculatotal() {
    let total = 0;
    let subtotales = document.getElementsByClassName('subtotal');
    for (i = 0; i < subtotales.length; i++) {
        total += parseFloat(subtotales[i].textContent);
    }
    document.getElementById("total").textContent = total.toFixed(2);
}

function modificar(celda) {
    let valores = celda.parentNode.parentNode.children;
    let fila = celda.parentNode.parentNode;
    document.getElementById('codigo2').value = valores[0].innerHTML;
    document.getElementById('descripcion2').value = valores[1].innerHTML;
    document.getElementById('cantidad2').value = valores[2].innerHTML;
    document.getElementById('precio2').value = valores[3].innerHTML;

    document.getElementById('botonmodifica').onclick = function() {
        valores[0].innerHTML = document.getElementById('codigo2').value;
        valores[1].innerHTML = document.getElementById('descripcion2').value;
        valores[2].innerHTML = document.getElementById('cantidad2').value;
        valores[3].innerHTML = document.getElementById('precio2').value;

        let cantidad = parseInt(document.getElementById('cantidad2').value);
        let precio = parseFloat(document.getElementById('precio2').value);
        valores[4].innerHTML = cantidad * precio;
        calculatotal();
    }

}

function rellenarBusqueda() {
    let codigo = document.querySelector("#codigo").value;
    let tabla2 = document.getElementById('busquedaCodigos');
    let filas = tabla2.getElementsByTagName('tr');

    for (let i = 1; i < filas.length; i++) {
        let codigoBuscado = filas[i].getElementsByTagName('td')[0].textContent;
        if (codigoBuscado === codigo) {
            let descripcion = filas[i].getElementsByTagName('td')[1].textContent;
            let precio = filas[i].getElementsByTagName('td')[2].textContent;
            document.getElementById('descripcion').value = descripcion;
            document.getElementById('precio').value = precio;
            break;
        }
        else {
            document.getElementById('descripcion').value = 'Articulo no encontrado';
            document.getElementById('precio').value = '0';
        }
    }
}

// function buscararticulo() {
//     let codigo = document.getElementById('codigo').value;
//     console.log(codigo);
//     let codigos = document.getElementsByClassName('codigo');
//     console.log(codigos);
//     for (let i = 0; i < codigos.length; i++) {
//         if (codigos[i].textContent === codigo) {
//             let fila = codigos[i].parentNode;
//             document.getElementById('descripcion').value = fila.getElementsByTagName('td')[1].textContent;
//             document.getElementById('cantidad').value = fila.getElementsByTagName('td')[2].textContent;
//         }
//     }
// if (!encontrado) {
//     document.getElementById('descripcion').value = 'Articulo no encontrado';
//     document.getElementById('cantidad').value = '0';
// }
// }






  


