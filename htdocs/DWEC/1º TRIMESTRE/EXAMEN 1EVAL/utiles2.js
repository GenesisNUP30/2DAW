function mostrarImagen() {
    fetch('https://dog.ceo/api/breeds/image/random')
        .then((response) => response.json())
        .then(datos => {
            let objetoImagen = {
                message: datos.message,
                status: datos.status
            }
            console.log(objetoImagen);



            const contenedor = document.createElement('div');
            const imagen = document.createElement('img');

            contenedor.appendChild(imagen);
            document.body.appendChild(contenedor);
            contenedor.setAttribute('id', 'imagen');

            imagen.setAttribute('class', 'imagen');
            imagen.setAttribute('src', objetoImagen.message);

            imagen.style.display = 'block';
            imagen.style.margin = 'auto';
            imagen.style.width = '50%';
            imagen.style.height = '50%';

            

        })
        .catch(error => console.log('Error al cargar la imagen', error));
}

function verHistorial() {
    let imagen = document.getElementsByClassName('imagen');
    console.log(imagen);
    let historial = [];
    for (let i = 0; i < imagen.length; i++) {
        console.log(imagen[i]);
        historial.push(imagen[i].src);
        console.log(historial);

        let card = document.createElement('div');
        card.setAttribute('class', 'card');
        card.innerHTML = '<img src="' + imagen[i].src + '" alt="imagen">';
        card.style.margin = '10px';
        card.style.width = '30%';
        card.style.height = '30%';
        document.body.appendChild(card);
    }

}
