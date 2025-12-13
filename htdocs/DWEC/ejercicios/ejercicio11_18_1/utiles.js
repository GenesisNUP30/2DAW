document.getElementById('validarRegistro').addEventListener('submit', function (event) {
    let errores = [];
    const nombre = document.getElementById('nombre').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const password2 = document.getElementById('password2').value;

    if (nombre === '') {
        errores.push('El nombre es obligatorio.');
    }
    if (email === '') {
        errores.push('El email es obligatorio.');
    }

    const emailcorrecto = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    if (!emailcorrecto.test(email)) {
        errores.push('El email no es correcto.');
    }

    if (password === '') {
        errores.push('La contraseña es obligatoria.');
    }
    if (password2 === '') {
        errores.push('La confirmación de contraseña es obligatoria.');
    }


    if (password !== password2) {
        errores.push('Las contraseñas no coinciden.');
    }

    if (errores.length > 0) {
        event.preventDefault();
        document.getElementById('errores').innerHTML = errores.join('<br>');
    }
});