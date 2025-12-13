
let recetas = [];

function cargarRecetas() {
    fetch("https://dummyjson.com/recipes")
        .then(response => response.json())
        .then(datos => {
            recetas = datos.recipes;
            console.log(recetas);
            mostrarRecetas(recetas);
            llenarFiltros();
            buscar();
        })
        .catch(error => console.log("Error al cargar las recetas", error));
}

function mostrarRecetas(listas) {
    const contenedor = document.getElementById("contenedor-recetas");
    contenedor.innerHTML = "";

    if (listas.length === 0) {
        const p = document.createElement("p");
        p.textContent = "No hay recetas que coincidan con los filtros seleccionados";
        contenedor.appendChild(p);
        return;
    }

    listas.forEach(receta => {
        const tarjeta = document.createElement("div");
        tarjeta.className = "col-md-4";
        tarjeta.style.width = "19rem";

        const imagen = document.createElement("img");
        imagen.className = "card-img-top";
        imagen.src = receta.image;
        tarjeta.appendChild(imagen);

        const cuerpo = document.createElement("div");
        cuerpo.className = "card-body";
        tarjeta.appendChild(cuerpo);

        const nombre = document.createElement("h5");
        nombre.className = "card-title";
        nombre.textContent = receta.name;
        cuerpo.appendChild(nombre);

        const valoracion = document.createElement("p");
        valoracion.className = "card-text";
        valoracion.textContent = "Valoracion: " + receta.rating;
        cuerpo.appendChild(valoracion);

        const verReceta = document.createElement("button");
        verReceta.className = "btn btn-primary btn-ver";
        verReceta.textContent = "Ver receta";

        verReceta.setAttribute("data-bs-toggle", "modal");
        verReceta.setAttribute("data-bs-target", "#modalReceta");

        verReceta.onclick = () => verMas(receta);

        cuerpo.appendChild(verReceta);

        contenedor.appendChild(tarjeta);

    });
}


function llenarFiltros() {
    const tipoComida = document.getElementById("tipo-comida");
    tipoComida.innerHTML = '<option value="">Selecciona un tipo de comida</option>';

    let tipos = [];
    recetas.forEach(receta => {
        receta.mealType.forEach(tipo => {
            if (!tipos.includes(tipo)) {
                tipos.push(tipo);
            }
        });
    });

    tipos.forEach(tipo => {
        const opcion = document.createElement("option");
        opcion.value = tipo;
        opcion.textContent = tipo;
        tipoComida.appendChild(opcion);
    });

    const niveles = document.getElementById("dificultad");
    niveles.innerHTML = '<option value="">Selecciona una dificultad</option>';

    let dificultades = [];
    recetas.forEach(receta => {
        if (!dificultades.includes(receta.difficulty)) {
            dificultades.push(receta.difficulty);
        }
    });

    dificultades.forEach(dificultad => {
        const opcion = document.createElement("option");
        opcion.value = dificultad;
        opcion.textContent = dificultad;
        niveles.appendChild(opcion);
    });

    const cocina = document.getElementById("cocina");
    cocina.innerHTML = '<option value="">Selecciona una cocina</option>';

    let estilos = [];
    recetas.forEach(receta => {
        if (!estilos.includes(receta.cuisine)) {
            estilos.push(receta.cuisine);
        }
    });

    estilos.forEach(estilo => {
        const opcion = document.createElement("option");
        opcion.value = estilo;
        opcion.textContent = estilo;
        cocina.appendChild(opcion);
    });

    const tiempoPreparacion = document.getElementById("tiempo-preparacion");
    let rangosPosibles = [
        "Menos de 15 minutos",
        "15 a 30 minutos",
        "30 a 60 minutos",
        "60 minutos o más"
    ];

    let rangos = [];

    recetas.forEach(receta => {
        const tiempo = receta.prepTimeMinutes + receta.cookTimeMinutes;

        let rango = "";
        if (tiempo < 15) {
            rango = "Menos de 15 minutos";
        } else if (tiempo < 30) {
            rango = "15 a 30 minutos";
        } else if (tiempo < 60) {
            rango = "30 a 60 minutos";
        } else {
            rango = "60 minutos o más";
        }

        if (!rangos.includes(rango)) {
            rangos.push(rango);
        }
    });

    rangosPosibles.forEach(rango => {
        if (rangos.includes(rango)) {
            const opcion = document.createElement("option");
            opcion.value = rango;
            opcion.textContent = rango;
            tiempoPreparacion.appendChild(opcion);
        }
    });
}

function aplicarFiltros() {
    const tipoComida = document.getElementById("tipo-comida").value;
    const dificultad = document.getElementById("dificultad").value;
    const cocina = document.getElementById("cocina").value;
    const tiempoSeleccionado = document.getElementById("tiempo-preparacion").value;

    let recetasFiltradas = [];

    recetas.forEach(receta => {
        const tiempoTotal = receta.prepTimeMinutes + receta.cookTimeMinutes;
        let coincide = true;
        if (tipoComida !== "" && !receta.mealType.includes(tipoComida)) {
            coincide = false;
        }

        if (dificultad !== "" && receta.difficulty !== dificultad) {
            coincide = false;
        }

        if (cocina !== "" && receta.cuisine !== cocina) {
            coincide = false;
        }

        if (tiempoSeleccionado !== "") {
            if (tiempoSeleccionado === "Menos de 15 minutos" && tiempoTotal >= 15) {
                coincide = false;
            } else if (tiempoSeleccionado === "15 a 30 minutos" && (tiempoTotal <= 15 || tiempoTotal >= 30)) {
                coincide = false;
            } else if (tiempoSeleccionado === "30 a 60 minutos" && (tiempoTotal <= 30 || tiempoTotal >= 60)) {
                coincide = false;
            } else if (tiempoSeleccionado === "60 minutos o más" && tiempoTotal <= 60) {
                coincide = false;
            }
        }

        if (coincide) {
            recetasFiltradas.push(receta);
        }
    });

    mostrarRecetas(recetasFiltradas);
}

function buscar() {
    const palabra = document.getElementById("buscar-recetas").value;

    let recetasFiltradas = [];
    recetas.forEach(receta => {
        let encontrado = false;

        // Buscar en el nombre
        if (receta.name.toLowerCase().includes(palabra.toLowerCase())) {
            encontrado = true;
        }
        // Buscar en los ingredientes
        receta.ingredients.forEach(ingrediente => {
            if (ingrediente.toLowerCase().includes(palabra.toLowerCase())) {
                encontrado = true;
            }
        });

        if (encontrado) {
            recetasFiltradas.push(receta);
        }
    });
    mostrarRecetas(recetasFiltradas);

}

function verMas(receta) {
    const cuerpoModal = document.getElementById("modal-body-receta");
    cuerpoModal.innerHTML = '';

    const tituloIngredientes = document.createElement("h5");
    tituloIngredientes.textContent = "Ingredientes:";
    cuerpoModal.appendChild(tituloIngredientes);

    const ingredientes = document.createElement("ul");
    receta.ingredients.forEach(ingrediente => {
        const li = document.createElement("li");
        li.textContent = ingrediente;
        ingredientes.appendChild(li);
    });
    cuerpoModal.appendChild(ingredientes);

    const tituloInstrucciones = document.createElement("h5");
    tituloInstrucciones.textContent = "Instrucciones:";
    cuerpoModal.appendChild(tituloInstrucciones);

    const instrucciones = document.createElement("ul");
    receta.instructions.forEach(instruccion => {
        const li = document.createElement("li");
        li.textContent = instruccion;
        instrucciones.appendChild(li);
    });
    cuerpoModal.appendChild(instrucciones);

    const calorias = document.createElement("h5");
    calorias.textContent = "Calorías: " + receta.caloriesPerServing;
    cuerpoModal.appendChild(calorias);

    const tiempoPrep = document.createElement("h5");
    tiempoPrep.textContent = "Tiempo de preparación: " + receta.prepTimeMinutes + " min";
    cuerpoModal.appendChild(tiempoPrep);

    const tiempoCoccion = document.createElement("h5");
    tiempoCoccion.textContent = "Tiempo de cocción: " + receta.cookTimeMinutes + " min"
    cuerpoModal.appendChild(tiempoCoccion);


}
