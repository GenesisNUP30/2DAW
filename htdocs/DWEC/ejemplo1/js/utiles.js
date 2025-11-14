function obtenerAlumnos() {
    fetch("php/listaralumnos.php")
        .then(response => response.json())
        .then(data => {
            alumnos = data;

            const tabla = document.getElementById("tabla-alumnos");
            const tbody = tabla.querySelector("tbody");
            const cargando = document.getElementById("cargando");

            if (alumnos.length > 0) {
                alumnos.forEach(alumno => {
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
        });
}

function cargalista() {
    fetch("php/listaralumnosfacil.php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("lista-alumnos").innerHTML = data;
        });
}

