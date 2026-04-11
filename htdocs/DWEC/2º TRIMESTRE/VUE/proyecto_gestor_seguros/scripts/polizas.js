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
      errores: {},
    };
  },
  methods: {
    validarFormulario() {
      this.errores = {};
      const f = this.formPoliza;

      if (!f.numero_poliza.toString().trim()) {
        this.errores.numero_poliza = "El número de póliza es obligatorio";
      }

      if (!f.fecha) {
        this.errores.fecha = "La fecha es obligatoria";
      }

      if (f.importe_total <= 0) {
        this.errores.importe_total = "El importe debe ser mayor a 0";
      }

      if (!f.estado) {
        this.errores.estado = "Debe seleccionar un estado";
      }

      if (!f.observaciones || !f.observaciones.toString().trim()) {
        this.errores.observaciones = "Las observaciones son obligatorias";
      }

      // Devolvemos true si no hay errores
      return Object.keys(this.errores).length === 0;
    },

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
      if (!this.validarFormulario()) return;

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
          // Si el PHP detecta que el número de póliza existe, lo ponemos en el objeto errores
          if (data.mensaje.includes("número de póliza")) {
            this.errores.numero_poliza = data.mensaje;
          } else {
            alert("Error: " + data.mensaje);
          }
        }
      } catch (e) {
        console.error("Error en la petición", e);
      }
    },

    async verClienteDesdePoliza(poliza) {
      try {
        const resp = await fetch(`php/obtenercliente.php?=id=${poliza.cliente_id}`);
        const data = await resp.json();
        if (data.status) {
          if (typeof this.verDetalleCliente === "function") {
            this.verDetalleCliente(data.data);
          } else {
            console.log("El método verDetalleCliente no está accesible desde aquí");
          }
        } else {
          alert("Error: " + data.mensaje);
        }
      } catch (e) {
        console.error("Error al obtener cliente", e);
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
