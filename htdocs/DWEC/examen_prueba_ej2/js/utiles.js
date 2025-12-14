function cargarArticulos() {
    console.log('Cargando artículos');
    const div = document.getElementById("capa-articulos");
    const tabla = document.getElementById("tabla-articulos");
    const tbody = tabla.querySelector("tbody");
    tbody.innerHTML = '';

    const url = `php/listar_articulos.php`;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            tbody.innerHTML = "";
            console.log(data);

            if (data.length > 0) {
                data.forEach(articulo => {
                    div.style.display = "block";

                    const fila = document.createElement("tr");

                    fila.innerHTML = `
                    <td>${articulo.codigo}</td>
                    <td>${articulo.descripcion}</td>
                    <td>${articulo.cantidad}</td>
                    <td>${articulo.precio}</td>
                    <td>${subtotal.innerHTML = parseFloat(articulo.precio) * parseInt(articulo.cantidad)}</td>
                    `;

                    fila.onclick = function () {
                        console.log("Haz seleccionado el artículo");
                    }


                    tbody.appendChild(fila);
                });
            } else {
                alert("No hay artículos para mostrar.");
            }
        })
        .catch(error => console.error(error));
}



function rellenarBusqueda() {
    const codigo = document.getElementById("codigo").value;
    const descripcion = document.getElementById("descripcion");
    const precio = document.getElementById("precio");
    const subtotal = document.getElementById("subtotal");

    const url = `php/obtener_articulo.php?codigo=${codigo}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);

            if (data.length > 0) {
                data.forEach(articulo => {
                    descripcion.value = articulo.descripcion;
                    precio.value = articulo.precio;
                    cantidad.value = 1;
                    subtotal.innerHTML = parseFloat(articulo.precio) * parseInt(cantidad.value);
                    console.log(subtotal.innerHTML);
                });
            } else {
                descripcion.value = "Artículo no encontrado";
            }
        });
}

function insertar() {
    const codigo = document.getElementById("codigo").value;
    const descripcion = document.getElementById("descripcion").value;
    const cantidad = document.getElementById("cantidad").value;
    const precio = document.getElementById("precio").value;
    const subtotal = document.getElementById("subtotal").innerHTML;

    const informacion = document.getElementById("informacion");
    const tabla = document.getElementById("tabla");
    const tbody = tabla.querySelector("tbody");

    const url = `php/insertar.php?codigo=${codigo}&descripcion=${encodeURIComponent(descripcion)}&cantidad=${cantidad}&precio=${precio}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                informacion.style.display = "block";
                tabla.style.display = "table";

                const fila = document.createElement("tr");
                fila.innerHTML = `
                    <td>${data.data.codigo}</td>
                    <td>${data.data.descripcion}</td>
                    <td>${data.data.cantidad}</td>
                    <td>${data.data.precio}</td>
                    <td class="subtotal">${subtotal.innerHTML = parseFloat(precio) * parseInt(cantidad)}</td>
                `;
                tbody.appendChild(fila);

            } else {
                alert("Error al insertar el artículo: " + data.mensaje);
            }
        })
}



function calcularBase() {
    let base = 0;
    let subtotales = document.getElementsByClassName('subtotal');
    for (i = 0; i < subtotales.length; i++) {
        base += parseFloat(subtotales[i].textContent);
    }
    document.getElementById("base").textContent = base.toFixed(2);
}

function calcularTotal() {
    let total = parseFloat(document.getElementById("base").textContent);
    let iva = total * 0.21;
    document.getElementById("total").textContent = total + iva;
}

function cargarArticulo() {
    alert('Cargando artículos');

}