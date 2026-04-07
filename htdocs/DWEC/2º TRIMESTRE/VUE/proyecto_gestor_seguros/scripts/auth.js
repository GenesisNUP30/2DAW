const authLogic = {
    data() {
        return {
            logueado: false,
            usuarioActivo: null,
            errorLogin: '',
            formLogin: { usuario: '', password: '' }
        }
    },
    mounted() {
        this.verificarSesion();
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
                    
                    // Guardamos en localStorage 
                    localStorage.setItem('usuario', JSON.stringify(data.usuario));

                    if (this.cargarClientes) this.cargarClientes(); 
                    if (this.cargarPolizas) this.cargarPolizas();
                } else {
                    this.errorLogin = data.mensaje;
                }
            } catch (e) {
                this.errorLogin = "Error de conexión";
            }
        },
        verificarSesion() {
            // Intentamos recuperar los datos guardados
            const sesionGuardada = localStorage.getItem('usuario');
            if (sesionGuardada) {
                this.usuarioActivo = JSON.parse(sesionGuardada);
                this.logueado = true;
                // Si ya estamos logueados, cargamos los clientes y pólizas
                if (this.cargarClientes) this.cargarClientes();
                if (this.cargarPolizas) this.cargarPolizas();
            }
        },
        salir() {
            this.logueado = false;
            this.usuarioActivo = null;
            this.formLogin = { usuario: '', password: '' };
            
            // --- LIMPIEZA ---
            localStorage.removeItem('usuario');
        }
    }
};