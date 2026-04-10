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
      const modalElement = document.getElementById("modalCliente");
      let modal = bootstrap.Modal.getInstance(modalElement);
      if (!modal) modal = new bootstrap.Modal(modalElement);
      modal.show();
    },

    prepararEdicion(poliza) {
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
            document.getElementById("modalPoliza"),
          ).hide();
          // Recargar la lista de pólizas para ver el nuevo
          this.cargarPolizas();
          // Mostrar un mensaje de éxito
          alert(data.mensaje);
        } else {
          // Si el DNI está duplicado, por ejemplo, lo mostramos como error del campo DNI
          if (data.mensaje.includes("DNI")) {
            this.errores.dni = data.mensaje;
          } else {
            alert("Error: " + data.mensaje);
          }
        }
      } catch (e) {
        console.error("Error en la petición", e);
      }
    },

    // Función para poner color al estado
    claseEstado(estado) {
      switch (estado.toLowerCase()) {
        case "activa":
          return "bg-success";
        case "pendiente":
          return "bg-warning text-dark";
        case "cancelada":
          return "bg-danger";
        default:
          return "bg-secondary";
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
          p.observaciones.toLowerCase().includes(b),
      );
    },
  },
};
