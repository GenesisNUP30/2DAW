
let tareas = [];

function anadir() {
    let nombreTarea = document.getElementById("nombreTarea").value;
    let prioridad = document.getElementById("prioridad").value;
    console.log(nombreTarea, prioridad);
    let existe = false;

    for (let i = 0; i < tareas.length; i++) {
        if (tareas[i].nombre === nombreTarea) {
            existe = true;
            if (tareas[i].prioridad !== prioridad) {
                tareas[i].prioridad = prioridad;
            } else {
                alert("La tarea ya existe con la misma prioridad");
            }
        } 
    }

    if (!existe) {
        let nuevaTarea = {
            nombre: nombreTarea,
            prioridad: prioridad
        }
        tareas.push(nuevaTarea);
        console.log(tareas);
    }
    mostrarTarea();

}

function mostrarTarea() {
    let tabla = document.getElementById('tablaTareas');
    let lista = document.getElementById('lista-tareas');
    let prioridadSeleccionada = document.getElementById("prioridadTarea").value;

        tabla.innerHTML = `
        <tr>
            <td>Nombre de la tarea</td>
            <td>Prioridad</td>
            <td>Operación</td>
        </tr>
    `;

    lista.innerHTML = "";

    for (let i = 0; i < tareas.length; i++) {
        if (prioridadSeleccionada === "todas" || tareas[i].prioridad === prioridadSeleccionada) {
            let fila = document.createElement("tr");
            tabla.appendChild(fila);

            let celda1 = document.createElement("td");
            celda1.textContent = tareas[i].nombre;
            fila.appendChild(celda1);

            let celda2 = document.createElement("td");
            celda2.textContent = tareas[i].prioridad;
            fila.appendChild(celda2);

            let celda3 = document.createElement("td");
            celda3.innerHTML = '<button onclick="borrar('+ i +');">Borrar</button>';
            fila.appendChild(celda3);

            let li = document.createElement("li");
            li.innerHTML = 'Nombre: ' + tareas[i].nombre + '<br>Prioridad: ' + tareas[i].prioridad + '<br><button onclick="borrar('+ i +');">Borrar</button>';
            lista.appendChild(li);
        }
    }

}

function borrar(posicion) {
    let respuesta = prompt("¿Estás seguro de que quieres borrar la tarea?");
    if (respuesta === "si") {
        tareas.splice(posicion, 1);
        mostrarTarea();
        console.log('Tarea borrada');
    } else {
        console.log('Tarea no borrada');
    }
    
}


function seleccionPrioridad() {
    mostrarTarea();
}
