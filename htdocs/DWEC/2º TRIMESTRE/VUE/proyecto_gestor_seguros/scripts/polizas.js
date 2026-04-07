const polizasLogic = {
    data() {
        return {
            polizas: [],
            busquedaPoliza: ''
        }
    },
    methods: {
        async cargarPolizas() {
            try {
                const resp = await fetch('php/listarpolizas.php');
                const data = await resp.json();
                if (data.status) {
                    this.polizas = data.data;
                }
            } catch (e) {
                console.error("Error al obtener pólizas");
            }
        },
        // Función para poner color al estado
        claseEstado(estado) {
            switch (estado.toLowerCase()) {
                case 'activa': return 'bg-success';
                case 'pendiente': return 'bg-warning text-dark';
                case 'cancelada': return 'bg-danger';
                default: return 'bg-secondary';
            }
        }
    },
    computed: {
        polizasFiltradas() {
            if (!this.busquedaPoliza) return this.polizas;
            const b = this.busquedaPoliza.toLowerCase();
            return this.polizas.filter(p => 
                p.numero_poliza.toLowerCase().includes(b) || 
                p.observaciones.toLowerCase().includes(b)
            );
        }
    }
};