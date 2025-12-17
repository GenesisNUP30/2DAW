id_modelo_global = null;
id_marca_global = null;

nombre_marca_global = null;
nombre_modelo_global = null;

async function cargarMarcas() {
    try {
        const res = await fetch("php/listarmarcas.php");
        const marcas = await res.json();
        console.log(marcas);

        const select = document.getElementById("marca");
        const divIcono = document.getElementById("icono-marca");

        marcas.forEach(marca => {
            const option = document.createElement("option");
            option.value = marca.id;
            option.textContent = marca.nombre;

            nombre_marca_global = marca.nombre;
            

            select.appendChild(option);
            console.log("MARCA: " + marca.id);
            id_marca_global = marca.id;

            let icono = "";
            switch(marca.nombre) {
                case "Toyota":
                    icono = "img/Toyota.png";
                    break;
                case "Ford":
                    icono = "img/Ford.png";
                    break;
                case "Renault":
                    icono = "img/Renault.png";
                    break;
                case "Audi":
                    icono = "img/Audi.png";
                    break;
                case "Mercedes":
                    icono = "img/Mercedes.png";
                    break;
                case "Peugeot":
                    icono = "img/Peugeot.png";
                    break;
                case "BMW":
                    icono = "img/BMW.png";
                    break;
                case "Volkswagen":
                    icono = "img/Volkswagen.png";
                    break;
                case "Seat":
                    icono = "img/Seat.png";
                    break;
            }

            divIcono.innerHTML = `<img src="${icono}" alt="Icono de la marca" width="50" height="50">`;

        });
    } catch (error) {
        console.error("Error al cargar marcas:", error);
    }
}

function cargarModelos() {
    const id_marca = document.getElementById("marca").value;
    const selectModelo = document.getElementById("modelo");
    selectModelo.innerHTML = '<option value="">Cargando...</option>';

    const url = `php/listarmodelos.php?id_marca=${id_marca}`;
    fetch(url)
        .then(res => res.json())
        .then(modelos => {
            selectModelo.innerHTML = '<option value="">Seleccione un modelo</option>';
            modelos.forEach(modelo => {
                const option = document.createElement("option");
                option.value = modelo.id;
                option.textContent = modelo.nombre;

                nombre_modelo_global = modelo.nombre;
                

                selectModelo.appendChild(option);

                id_modelo_global = modelo.id;

                const divNumeroModelos = document.getElementById("numero-modelos");
                divNumeroModelos.innerHTML = `<p>Hay ${modelo.cantidad} modelos para esta marca.</p>`;

            });

        })
        .catch(error => console.error(error));
}

document.addEventListener("DOMContentLoaded", () => {
    cargarMarcas();
});

function obtenerprecio(id_modelo) {
    console.log(id_marca_global);
    console.log(id_modelo_global);
    const divprecio = document.getElementById("precio-modelo");
    const precioinput = document.getElementById("precio");

    const url = `php/obtenerprecio.php?id_modelo=${id_modelo}`;
    console.log(url);
    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);
            if (data[0].precio === null) {
                divprecio.style.display = "block";
                precioinput.value = "";
            } 
        })
        .catch(error => console.error(error));
}

function actualizarprecio() {
    const id_modelo = document.getElementById("modelo").value;
    const precio = document.getElementById("precio").value;
    const url = `php/actualizarprecio.php?id_modelo=${id_modelo}&precio=${precio}`;
    console.log(url);
    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);
            if (data.error) {
                alert(data.error);
            } else {
                alert(data.mensaje);
            }
        })
        .catch(error => console.error(error));
}


const buscador = document.getElementById("busca-modelo");

buscador.addEventListener("input", function () {
    const texto = buscador.value;
    const tabla = document.getElementById("tabla-modelos");
    const tbody = tabla.querySelector("tbody");
    const url = `php/buscar_modelos.php?texto=${texto}`;

    console.log(texto);

    fetch(url)
        .then(res => res.json())
        .then(data => {
            tbody.innerHTML = "";
            data.forEach(modelo => {
                const fila = document.createElement("tr");
                fila.innerHTML = `
                <td>${modelo.id}</td>
                <td>${modelo.nombre}</td>
                `;
                tbody.appendChild(fila);

                fila.onclick = function () {
                    console.log("Haz seleccionado el modelo");
                    document.getElementById("modelo").value = modelo.id;
                    const informacion = document.getElementById("marca-busqueda");
                    const url = `php/obtenermarca_modelo.php?id_modelo=${modelo.id}`;

                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            console.log(data);
                            informacion.innerHTML = "";
                            if (data.length > 0) {
                                data.forEach(marca => {
                                    informacion.innerHTML += `
                                        <p>
                                            La marca de este modelo es <strong>${marca.nombre}</strong>
                                        </p>
                                    `;
                                });
                            } else {
                                informacion.innerHTML = `<p>No hay marcas para este modelo.</p>`;
                            }
                        });

                }
            });
        })
        .catch(error => console.error(error));
});