function cargarProvincias() {
    fetch('https://raw.githubusercontent.com/IagoLast/pselect/master/data/provincias.json')
        .then((response) => response.json())
        .then(datos => {
            const provincias = datos;
            seleccionar = document.getElementById('provincia');
            provincias.forEach(provincia => {
                nuevaProvincia = new Option(provincia.nm, provincia.id);
                seleccionar.appendChild(nuevaProvincia);
            });
        })
        .catch(error => console.error('Error al cargar las provincias:', error));
}


function buscarPoblaciones() {
    
}