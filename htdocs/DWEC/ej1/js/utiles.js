var codigo_global = null;

// Cuando la página carga
document.addEventListener("DOMContentLoaded", async () => {
    const tabla = document.getElementById("tabla-alumnos");
    const tbody = tabla.querySelector("tbody");
    const cargando = document.getElementById("cargando");

    try {
        // Petición al PHP que devuelve JSON
        const respuesta = await fetch("php/listaralumnos.php");
        const datos = await respuesta.json();

        // Si hay alumnos
        if (datos.length > 0) {
            datos.forEach(alumno => {
                const fila = document.createElement("tr");
                fila.innerHTML = `
                    <td>${alumno.codigo}</td>
                    <td>${alumno.nombre}</td>
                    <td>${alumno.apellidos}</td>
                    <td>${alumno.nota}</td>
                    <td>
                    <button onclick=eliminaalumno(${alumno.codigo});>Eliminar</button>
                    <button onclick=modificaalumno(${alumno.codigo});>Modificar</button>
                    <button onclick=vernotas(${alumno.codigo});>Ver notas</button>
                    </td>
                `;
                tbody.appendChild(fila);
            });
            cargando.style.display = "none";
            tabla.style.display = "table";
        } else {
            cargando.textContent = "No hay registros de alumnos.";
        }

    } catch (error) {
        cargando.textContent = "Error al cargar los datos.";
        console.error(error);
    }
});

function cargarlista() {
    fetch("php/listaralumnosfacil.php")
        .then(respuesta => respuesta.text()) //Convierte la respuesta a texto
        .then(data => {
            document.getElementById("lista-alumnos").innerHTML = data;
        })
        .catch(error => console.error("Error", error));
}

function altaalumno() {
    const nombre = document.getElementById("nombre").value;
    const apellidos = document.getElementById("apellidos").value;
    const nota = document.getElementById("nota").value;

    console.log(nombre, apellidos, nota);
    const url = `php/altaalumno.php?nombre=${encodeURIComponent(nombre)}&apellidos=${encodeURIComponent(apellidos)}&nota=${encodeURIComponent(nota)}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);
            alert(data.message);
            // const tabla = document.getElementById("tabla-alumnos");
            // const tbody = tabla.querySelector("tbody");

            // const fila = document.createElement("tr");
            // fila.innerHTML = `
            // <td>${data.data.codigo}</td>
            // <td>${data.data.nombre}</td>
            // <td>${data.data.apellidos}</td>
            // <td>${data.data.nota}</td>
            // <td><button onclick=eliminaalumno(${data.data.codigo});>Eliminar</button></td>
            // <td><button onclick=modificaalumno(${data.data.codigo});>Modificar</button></td>
            // `;
            // tbody.appendChild(fila);
        })
        .catch(error => console.error(error));
}

function eliminaalumno(codigo) {
    const url = `php/borraalumno.php?id=${codigo}`;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);
            alert(data.message);

            // const tabla = document.getElementById("tabla-alumnos");
            // const tbody = tabla.querySelector("tbody");

            // console.log(tbody.children[codigo]);
            // tbody.removeChild(tbody.children[codigo]);
        })
        .catch(error => console.error(error));
}

function modificaalumno(codigo) {
    const url = `php/modificaalumno.php?codigo=${codigo}`;
    codigo_global = codigo;
    
    fetch(url)
        .then(res => res.json())
        .then(data => {
            document.querySelector("input#nombre").value = data[0].nombre;
            document.querySelector("input#apellidos").value = data[0].apellidos;
            document.querySelector("input#nota").value = data[0].nota;

            console.log(data);
        })
        .catch(error => console.error(error));
}



function modificaalumno2() {
    alert('Voy a modificar');
    const nombre = document.getElementById("nombre").value;
    const apellidos = document.getElementById("apellidos").value;
    const nota = document.getElementById("nota").value;

    const url = `php/modificaalumno2.php?codigo=${codigo_global}&nombre=${encodeURIComponent(nombre)}&apellidos=${encodeURIComponent(apellidos)}&nota=${encodeURIComponent(nota)}`;
    
    fetch(url)
    .then(res => res.json())
    .then(data => {
        console.log(data);
        alert(data.message);
        cargarlista();

    })
    .catch(error => console.error(error));
}

function vernotas(codigo) {
    alert(codigo);
    const url = `php/vernotas.php?codigo=${codigo}`;
    fetch(url)
    .then(res => res.json())
    .then(data => {
        console.log(data);
        const div = document.getElementById("notas");
        const ul = document.createElement("ul");

        data.forEach(nota => {
            const li = document.createElement("li");
            li.innerHTML = `
                <p>${nota.asignatura}</p>
                <p>${nota.nota}</p>
            `;
            ul.appendChild(li);
        });
        div.appendChild(ul);
    })
    .catch(error => {
    });
}