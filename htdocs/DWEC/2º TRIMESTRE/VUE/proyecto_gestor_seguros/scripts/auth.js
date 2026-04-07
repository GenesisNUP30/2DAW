const authLogic = {
    data() {
        return {
            logueado: false,
            usuarioActivo: null,
            errorLogin: '',
            formLogin: { usuario: '', password: '' }
        }
    },
    methods: {
        async hacerLogin() {
            this.errorLogin = '';
            try {
                const resp = await fetch('php/login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(this.formLogin)
                });
                const data = await resp.json();

                if (data.status) {
                    this.logueado = true;
                    this.usuarioActivo = data.usuario;
                    // Llamamos a cargar clientes desde el componente principal
                    if (this.cargarClientes) this.cargarClientes(); 
                } else {
                    this.errorLogin = data.mensaje;
                }
            } catch (e) {
                this.errorLogin = "Error de conexión";
            }
        },
        salir() {
            this.logueado = false;
            this.usuarioActivo = null;
            this.formLogin = { usuario: '', password: '' };
        }
    }
};