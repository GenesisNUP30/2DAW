async function listarmarcas() {
    try {
        const res = await fetch("php/listarmarcas.php");
        const marcas = await res.json();
        console.log(marcas);

        const marcaselect = document.getElementById("marca");

        marcaselect.innerHTML = '<option value="">Selecciona una marca</option>';

        marcas.forEach(marca => {
            const option = document.createElement("option");
            option.value = marca.id;
            option.textContent = marca.nombre;

            marcaselect.appendChild(option);
        })
    }
    catch (error) {
        console.error("Error al cargar marcas:", error);
    }
}

document.addEventListener("DOMContentLoaded", () => {
    listarmarcas();
});

// document.getElementById("marca").addEventListener("change", () => {
//     listarmodelos();
// });

function listarmodelos() {
    const id_marca = document.getElementById("marca").value;
    const modeloselect = document.getElementById("modelo");

    modeloselect.innerHTML = '<option value="">Selecciona un modelo</option>';

    const url = `php/listarmodelos.php?id_marca=${id_marca}`;
    fetch(url)
        .then(res => res.json())
        .then(modelos => {
            console.log(modelos);
            modelos.forEach(modelo => {
                const option = document.createElement("option");
                option.value = modelo.id;
                option.textContent = modelo.nombre;

                modeloselect.appendChild(option);
            })
        })

    contarmodelos();
}

function contarmodelos() {
    const id_marca = document.getElementById("marca").value;
    const div = document.getElementById("mumero-modelos");

    const url = `php/contarmodelos.php?id_marca=${id_marca}`;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);
            div.innerHTML = `<p>Hay ${data[0].total} modelos para esta marca.</p>`;
        })
}

function mostrarlogo() {
    const img = document.getElementById("icono-marca");
    const id_marca = document.getElementById("marca").value;
    console.log(id_marca);
    let logo = "";
    switch (id_marca) {
        case "1":
            logo = "img/Toyota.png";
            break;
        case "2":
            logo = "img/Ford.png";
            break;
        case "3":
            logo = "img/Volkswagen.png";
            break;
        case "4":
            logo = "img/Renault.png";
            break;
        case "5":
            logo = "img/Seat.png";
            break;
        case "6":
            logo = "img/BMW.png";
            break;
        case "7":
            logo = "img/Audi.png";
            break;
        case "8":
            logo = "img/Mercedes.png";
            break;
        case "9":
            logo = "img/Peugeot.png";
            break;
        case "10":
            logo = "img/Peugeot.png";
            break;
    }
    img.src = logo;
}

function resetearExtras() {
    document.querySelectorAll('input[name="color"]').forEach(radio => {
        radio.checked = radio.value === "0";
    });

    document.querySelectorAll('input[name="puertas"]').forEach(radio => {
        radio.checked = radio.value === "0";
    });

    document.getElementById("aire-acondicionado").checked = false;
    document.getElementById("llantas").checked = false;

    document.getElementById("precio-final").innerHTML = "";
}


function obtenerprecio() {
    resetearExtras();
    const id_modelo = document.getElementById("modelo").value;
    const div = document.getElementById("precio-modelo");
    const divextras = document.getElementById("extras");
    console.log(id_modelo);

    const url = `php/obtenerprecio.php?id_modelo=${id_modelo}`;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);
            if (data[0].precio === null) {
                divextras.style.display = "none";
                div.innerHTML =
                    `Introduce el precio: <input type="number" id="precio">
                <button onclick="actualizarprecio();">Actualizar precio</button>`;
            }
            else {
                div.innerHTML = `El precio del modelo es de <strong>${data[0].precio}</strong>`;
                divextras.style.display = "block";
                const precio_base = document.getElementById("precio-base");
                precio_base.innerHTML = data[0].precio;
            }
        })
}

function actualizarprecio() {
    const id_modelo = document.getElementById("modelo").value;
    const precio = document.getElementById("precio").value;

    const url = `php/actualizarprecio.php?id_modelo=${id_modelo}&precio=${precio}`;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);
            alert(data.message);
            if (data.status === "error") {
                alert(data.message);
            }
        })
}

function calcularpreciofinal() {
    const precio_base = document.getElementById("precio-base").innerHTML;
    console.log(precio_base);
    let preciofinal = parseInt(precio_base);
    
    const color = document.querySelector('input[name="color"]:checked');
    if (color) {
        preciofinal += parseInt(color.value);
    }

    const puertas = document.querySelector('input[name="puertas"]:checked');
    if (puertas) {
        preciofinal += parseInt(puertas.value);
    }

    if (document.getElementById("aire-acondicionado").checked) {
        preciofinal += parseInt(document.getElementById("aire-acondicionado").value);
    }

    if (document.getElementById("llantas").checked) {
        preciofinal += parseInt(document.getElementById("llantas").value);
    }
    
    console.log(preciofinal);
    document.getElementById("precio-final").innerHTML = 
    `${preciofinal} €`;

    const divcompra = document.getElementById("compra");
    divcompra.innerHTML = `<button onclick="comprarcoche();">Comprar coche</button>`;
}

function comprarcoche() {
    const marcaselect = document.getElementById("marca");
    const marcaid = marcaselect.value;
    const marcanombre = marcaselect.options[marcaselect.selectedIndex].text;
    const modeloselect = document.getElementById("modelo");
    const modeloid = modeloselect.value;
    const modelonombre = modeloselect.options[modeloselect.selectedIndex].text;
    const precio = document.getElementById("precio-final").innerHTML;

    console.log(marcaid, marcanombre, modeloid, modelonombre, precio);

    const url = `php/comprarcoche.php?marcanombre=${marcanombre}&modelonombre=${modelonombre}&precio=${precio}`;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);
            if (data.status === "success") {
                console.log(data.message);
                const tablacompras = document.getElementById("tabla-compras");
                const tbody = tablacompras.querySelector("tbody");
                const fila = document.createElement("tr");
                fila.innerHTML = `
                    <td>${marcanombre}</td>
                    <td>${modelonombre}</td>
                    <td>${precio}</td>
                `;
                tbody.appendChild(fila);
                tablacompras.style.display = "table";
            }
            else {
                alert(data.message);
            }
        })

}

const modelobusqueda = document.getElementById("buscador-modelo");

modelobusqueda.addEventListener("input", function () {
    const texto = modelobusqueda.value;
    const div = document.getElementById("resultados-busqueda");

    if (texto === "") {
        div.innerHTML = "";
        return;
    }

    const url = `php/buscarmodelo.php?texto=${texto}`;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);

            if (data.status === "error") {
                div.innerHTML = data.mensaje;
                return;
            }

            div.innerHTML = "";
            data.forEach(modelo => {
                let span = document.createElement("span");
                span.classList.add("modelo");
                span.innerHTML = `${modelo.modelo} <br>`;
                div.appendChild(span);

                span.onclick = () => {
                    const marcaDiv = document.getElementById("marca-busqueda");
                    marcaDiv.innerHTML = `<p>Marca: ${modelo.marca}</p>`;
                }
            });
        })
        .catch(error => console.error(error));
});