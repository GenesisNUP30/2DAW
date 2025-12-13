
function cargarArticulos() {
    fetch('https://jsonplaceholder.typicode.com/posts')
        .then((response) => response.json())
        .then(data => {
            const articulos = data.slice(0, 5);
            const tbody = document.querySelector('#tabla-articulos tbody');

            articulos.forEach(articulo => {
                const fila = document.createElement('tr');
                fila.innerHTML = `
                <td>${articulo.id}</td>
                <td>${articulo.title}</td>
                <td>${articulo.body}</td>
                `;
                tbody.appendChild(fila);
            });

        })
        .catch(error => console.log('Error al cargar los articulos', error));
}

function muestra() {
    id = document.getElementById('text');
    fetch(`https://jsonplaceholder.typicode.com/posts/${id.value}`)
        .then((response) => response.json())
        .then(data => {
            const articulo = data;
            const tbody = document.querySelector('#tabla-articulos tbody');
            const fila = document.createElement('tr');
            fila.innerHTML = `
                <td>${articulo.id}</td>
                <td>${articulo.title}</td>
                <td>${articulo.body}</td>
                `;
            tbody.appendChild(fila);
            // tbody.innerHTML = `
            // <tr>
            //     <td>${articulo.id}</td>
            //     <td>${articulo.title}</td>
            //     <td>${articulo.body}</td>
            // </tr>
            // `;
        })
        .catch(error => console.log('Error al cargar el articulo', error));
}

function cargaAutores() {
    const select = document.getElementById('autor');
    fetch('https://jsonplaceholder.typicode.com/users')
        .then((response) => response.json())
        .then(datos => {
            const autores = datos;
            autores.forEach(autor => {
                nuevoAutor = new Option(autor.name, autor.id);
                // const nuevoAutor = document.createElement('option');
                // nuevoAutor.value = autor.id;
                // nuevoAutor.innerHTML = autor.name;
                select.appendChild(nuevoAutor);
            });
        })
        .catch(error => console.log('Error al cargar los usuarios', error));

}

function seleccionaAutor() {
    let id = document.querySelector('#autor').value;
    // alert(id);
    fetch('https://jsonplaceholder.typicode.com/posts')
        .then((response) => response.json())
        .then(datos => {
            const articulos = datos;
            const tbody = document.querySelector('#tabla-articulos2 tbody');
            tbody.innerHTML = '';
            articulos.forEach(articulo => {
                if (articulo.userId == id) {
                    const fila = document.createElement('tr');
                    fila.innerHTML = `
                <td>${articulo.id}</td>
                <td>${articulo.userId}</td>
                <td>${articulo.title}</td>
                `;
                    tbody.appendChild(fila);
                }
            });

        })
        .catch(error => console.log('Error al cargar los articulos', error));
}


