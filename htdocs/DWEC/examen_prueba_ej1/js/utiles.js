const cp = document.getElementById("codigopostal");
const poblacion = document.getElementById("poblacion");
const numerohabitantes = document.getElementById("numerohabitantes");
const sugerencias = document.getElementById("sugerencias");

cp.addEventListener("input", function () {
    const texto = cp.value;
    if (texto.length === 0) {
        sugerencias.style.display = "none";
        return;
    }

    fetch("php/buscar_cp.php?codigopostal=" + texto)
        .then(res => res.json())
        .then(data => {
            sugerencias.innerHTML = "";
            console.log(data);

            if (data.length === 0) {
                sugerencias.style.display = "none";
                return;
            }

            sugerencias.style.display = "block";

            data.forEach(poblacion => {
                let span = document.createElement("span");
                span.classList.add("poblacion");
                span.innerHTML = `${poblacion.codigo_postal} - ${poblacion.nombre} <br>`;
                span.onclick = () => seleccionar(poblacion.codigo_postal);
                sugerencias.appendChild(span);
            });

        })
        .catch(error => console.error(error));
});

function seleccionar(codigopostal) {
    const textohabitantes = document.getElementById("habitantesrellenar");
    const url = `php/obtener_poblacion.php?cp=${codigopostal}`;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);
            cp.value = data[0].codigo_postal;
            poblacion.value = data[0].nombre;
            sugerencias.style.display = "none";

            if (data[0].habitantes === null) {
                textohabitantes.classList.remove("oculto");
                numerohabitantes.value = "";
            } else {
                textohabitantes.classList.add("oculto");
            }
        })

        .catch(error => console.error(error));
}

function actualizarHabitantes() {
    alert("Actualizando habitantes...");
    const habitantes = numerohabitantes.value;
    const codigo_postal = cp.value;
    const url = `php/actualizar_habitantes.php?cp=${codigo_postal}&habitantes=${habitantes}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            console.log(data);
            if (data.error) {
                alert(data.error);
            } else {
                alert("Datos actualizados correctamente");
            }
        })
        .catch(error => console.error(error));
}