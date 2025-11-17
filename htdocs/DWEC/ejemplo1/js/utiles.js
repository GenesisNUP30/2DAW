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

function cargalista(){
    fetch("php/listaralumnosfacil.php")
    .then(response => response.text()) // Convierte la respuesta a texto
    .then(data => {
        document.getElementById('lista-alumnos').innerHTML = data;
  })
  .catch(error => console.error('Error:', error));
}

function altaalumno() {
    //alert("Hola");
    nombre = document.getElementById("nombre").value;
    apellidos = document.getElementById("apellidos").value;
    nota = document.getElementById("nota").value;

    fetch("php/altaalumno.php?nombre=" + nombre + "&apellidos=" + apellidos + "&nota=" + nota)
        .then(response => response.json()) // Convierte la respuesta a JSON
        .then(data => {
            if (data.status === "ok") {
                alert(data.mensaje);
            } else {
                alert(data.mensaje);
            }
        })
        .catch(error => console.error('Error:', error));
}