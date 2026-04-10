const polizasLogic = {
  data() {
    return {
      polizas: [],
      busquedaPoliza: "",
      formPoliza: {
        id: null,
        numero_poliza: "",
        fecha: "",
        importe_total: 0,
        estado: "Pendiente",
        observaciones: "",
      },
    };
  },
  methods: {
    async cargarPolizas() {
      try {
        const resp = await fetch("php/listarpolizas.php");
        const data = await resp.json();
        if (data.status) {
          this.polizas = data.data;
        }
      } catch (e) {
        console.error("Error al obtener pólizas");
      }
    },

    mostrarModal() {
      const modalElement = document.getElementById("modalEditarPoliza");
      let modal = bootstrap.Modal.getInstance(modalElement);
      if (!modal) modal = new bootstrap.Modal(modalElement);
      modal.show();
    },

    prepararEdicionPoliza(poliza) {
      this.formPoliza = { ...poliza };
      this.mostrarModal();
    },

    async guardarEdicionPoliza() {
      try {
        const resp = await fetch("php/editarpoliza.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(this.formPoliza),
        });
        const data = await resp.json();

        if (data.status) {
          // Cerrar el modal
          bootstrap.Modal.getInstance(
            document.getElementById("modalEditarPoliza"),
          ).hide();
          // Recargar la lista de pólizas para ver el nuevo
          this.cargarPolizas();
          // Mostrar un mensaje de éxito
          alert(data.mensaje);
        } else {
          alert("Error: " + data.mensaje);
        }
      } catch (e) {
        console.error("Error en la petición", e);
      }
    },

    // Función para poner color al estado
    claseEstado(estado) {
      if (!estado) return "bg-secondary";
      switch (estado.toLowerCase()) {
        case "cobrada":
          return "bg-success"; //Verde
        case "a cuenta":
          return "bg-info text-dark"; // Azul claro
        case "liquidada":
          return "bg-primary"; // Azul oscuro
        case "anulada":
          return "bg-danger"; //Rojo
        case "pre-anulada":
          return "bg-warning text-dark"; // Amarillo
        default:
          return "bg-secondary"; // Gris
      }
    },
  },
  computed: {
    polizasFiltradas() {
      if (!this.busquedaPoliza) return this.polizas;
      const b = this.busquedaPoliza.toLowerCase();
      return this.polizas.filter(
        (p) =>
          p.numero_poliza.toLowerCase().includes(b) ||
          p.nombre_cliente.toLowerCase().includes(b),
      );
    },
  },
};
