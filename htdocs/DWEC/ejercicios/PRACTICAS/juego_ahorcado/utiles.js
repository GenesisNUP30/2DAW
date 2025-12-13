// window.onload 

const usuarios = [
    { nombre: "Juan", contraseña: "1234" },
    { nombre: "Luis", contraseña: "4567" },
    { nombre: "Maria", contraseña: "8910" },
]

const diccionario = {
    
}

function iniciarSesion() {
    const nombre = document.getElementById("nombre").value;
    const contraseña = document.getElementById("contraseña").value;

    for (let i = 0; i < usuarios.length; i++) {
        if (usuarios[i].nombre === nombre) {
            if (usuarios[i].contraseña !== contraseña) {
                alert("Contraseña incorrecta");
            } else {
                alert("Bienvenido " + nombre);
            }
            return;
        }
    }
    alert("Usuario no encontrado");
}

