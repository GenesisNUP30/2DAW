// Cuando la página carga
document.addEventListener("DOMContentLoaded", async () => {
    const tabla_empleados = document.getElementById("tabla-empleados");
    const tbody_empleados = tabla_empleados.querySelector("tbody");
    const cargando_empleados = document.getElementById("cargando-empleados");

    

    try {
        // Petición al PHP que devuelve JSON
        const respuesta = await fetch("php/listarempleados.php");
        const datos = await respuesta.json();
        
        if (datos.length > 0) {
            console.log(datos);
            datos.forEach(empleado => {
                const fila = document.createElement("tr");
                fila.innerHTML = `
                    <td>${empleado.id}</td>
                    <td>${empleado.nombre}</td>
                    <td>${empleado.salario}</td>
                    <td>${empleado.fecha_ingreso}</td>
                    <td>${empleado.id_departamento}</td>
                    `;
                tbody_empleados.appendChild(fila);
            });
            tabla_empleados.style.display = "table";
            cargando_empleados.style.display = "none";
        } else {
            tabla_empleados.style.display = "none";
            cargando_empleados.style.display = "block";
            cargando_empleados.textContent = "No hay empleados para este alumno.";
        }
    }
    catch (error) {
        cargando.textContent = "Error al cargar los datos.";
        console.error(error);
    }
})
