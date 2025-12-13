function cargarCategorias() {
    fetch('https://fakestoreapi.com/products/categories')
        .then((response) => response.json())
        .then(datos => {
            const categorias = datos;
            console.log(categorias);
            const select = document.getElementById('categoria');

            const opcionDefecto = document.createElement('option');
            opcionDefecto.value = '';
            opcionDefecto.textContent = 'Todas las categorias';
            select.appendChild(opcionDefecto);

            categorias.forEach(categoria => {
                const nuevaCategoria = document.createElement('option');
                nuevaCategoria.value = categoria;
                nuevaCategoria.textContent = categoria;
                select.appendChild(nuevaCategoria);
            });
            cargarProductos();
        })
        .catch(error => console.log('Error al cargar las categorias', error));
}

function cargarProductos() {
    let categoria = document.getElementById('categoria').value;
    let orden = document.getElementById('ordenar').value;

    console.log(categoria);
    fetch('https://fakestoreapi.com/products')

        .then((response) => response.json())
        .then(datos => {
            const productos = datos;

            const cardOriginal = document.getElementById('cards-productos');
            const contenedor = cardOriginal.parentElement;


            contenedor.querySelectorAll('.card').forEach(card => {
                if (card !== cardOriginal) card.remove();
            });

            cardOriginal.style.display = 'none';

            productos.forEach(producto => {
                if (categoria === '' || producto.category === categoria) {
                    const card = cardOriginal.cloneNode(true);

                    card.style.display = 'inline-block';
                    card.style.margin = '10px';

                    const imagen = card.querySelector('#card-imagen');
                    const titulo = card.querySelector('.card-titulo');
                    const precio = card.querySelector('.card-precio');
                    imagen.src = producto.image;
                    titulo.innerHTML = producto.title;
                    precio.innerHTML = producto.price;
                    contenedor.appendChild(card);
                }
            });
        })
        .catch(error => console.log('Error al cargar los productos', error));
}

function mostrarProductos(productos) {
    const cardOriginal = document.getElementById('cards-productos');
    const contenedor = cardOriginal.parentElement;

    contenedor.querySelectorAll('.card').forEach(card => {
        if (card !== cardOriginal) card.remove();
    });

    cardOriginal.style.display = 'none';

    productos.forEach(producto => {
        const card = cardOriginal.cloneNode(true);
        card.style.display = 'inline-block';
        card.style.margin = '10px';

        const imagen = card.querySelector('#card-imagen');
        const titulo = card.querySelector('.card-titulo');
        const precio = card.querySelector('.card-precio');

        imagen.src = producto.image;
        imagen.alt = producto.title;
        titulo.textContent = producto.title;
        precio.textContent = `$${producto.price}`;

        contenedor.appendChild(card);
    });
}







