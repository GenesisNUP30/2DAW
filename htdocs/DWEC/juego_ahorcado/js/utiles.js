// Cuando la página carga
function iniciarSesion() {
    const mensaje = document.getElementById("mensaje");

    const usuario = document.getElementById("usuario").value;
    const password = document.getElementById("password").value;

    if (!usuario || !password) {
        mensaje.textContent = "Por favor, completa todos los campos.";
        return;
    }

    const url = `php/validar_login.php?usuario=${encodeURIComponent(usuario)}&password=${encodeURIComponent(password)}`;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                if (data.rol === "admin") {
                    window.location.href = "php/administracion.php";
                } else {
                    window.location.href = "php/juego.php";
                }
            } else {
                mensaje.textContent = data.message;
            }
        })

}