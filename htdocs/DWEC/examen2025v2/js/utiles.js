async function cargarMarcas() {
    try {
        const res = await fetch("php/listarmarcas.php");
        const marcas = await res.json();
        console.log(marcas);

        const select = document.getElementById("marca");
        marcas.forEach(marca => {
            const option = document.createElement("option");
            option.value = marca.id;
            option.textContent = marca.nombre;
            select.appendChild(option);
        });
    } catch (error) {
        console.error("Error al cargar marcas:", error);
    }
}

async function cargarModelos(marca, selectModeloId) {
    const selectModelo = document.getElementById(selectModeloId);
    selectModelo.innerHTML = '<option value="">Cargando...</option>';

    if (!marca) {
        selectModelo.innerHTML = '<option value="">Seleccione una marca primero</option>';
        return;
    }

    try {
        const res = await fetch(`php/listarmodelos.php?marca=${encodeURIComponent(marca)}`);
        const modelos = await res.json();

        selectModelo.innerHTML = '<option value="">Seleccione un modelo</option>';
        modelos.forEach(modelo => {
            const option = document.createElement("option");
            option.value = modelo.id;
            option.textContent = modelo.nombre;
            selectModelo.appendChild(option);
        });
    } catch (error) {
        console.error("Error al cargar modelos:", error);
        selectModelo.innerHTML = '<option value="">Error al cargar</option>';
    }
}

document.addEventListener("DOMContentLoaded", () => {
    cargarMarcas();
    
    document.getElementById("marca").addEventListener("change", function () {
        cargarModelos(this.value, "modelo");
    });
});

function insertarCompra(marca, modelo, precio) {
    const url = `php/insertarcompra.php?marca=${encodeURIComponent(marca)}&modelo=${encodeURIComponent(modelo)}&precio=${encodeURIComponent(precio)}`;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);
            if (data.status === "error") {
                alert(data.mensaje);
                return;
            }
            alert(data.mensaje);
            cargarMarcas();
        })
        .catch(error => console.error(error));
}