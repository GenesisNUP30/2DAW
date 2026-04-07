const clientesLogic = {
    data() {
        return {
            busqueda: '',
            clientes: []
        }
    },
    methods: {
        async cargarClientes() {
            try {
                const resp = await fetch('php/listarclientes.php');
                const data = await resp.json();
                if (data.status) {
                    this.clientes = data.data;
                }
            } catch (e) {
                console.error("Error al obtener clientes");
            }
        }
    },
    computed: {
        clientesFiltrados() {
            if (!this.busqueda) return this.clientes;
            const b = this.busqueda.toLowerCase();
            return this.clientes.filter(c => 
                c.nombre.toLowerCase().includes(b) || 
                (c.apellidos && c.apellidos.toLowerCase().includes(b)) ||
                (c.dni && c.dni.toLowerCase().includes(b))
            );
        }
    }
};