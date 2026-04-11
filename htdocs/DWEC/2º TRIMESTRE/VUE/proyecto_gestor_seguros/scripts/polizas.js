const polizasLogic = {
  data() {
    return {
      polizas: [],
      busquedaPoliza: "",
      modoModalPoliza: "crear",
      clientesDisponibles: [],
      mensajeBorradoPoliza: "",
      polizaABorrar: null,
      formPoliza: {
        id: null,
        cliente_id: null,
        numero_poliza: "",
        fecha: "",
        importe_total: 0,
        estado: "",
        observaciones: "",
        nombre_cliente: "",
      },
      erroresPoliza: {},
    };
  },
  methods: {
    mostrarModalPoliza() {
      const modalElement = document.getElementById("modalPoliza");
      let modal = bootstrap.Modal.getInstance(modalElement);
      if (!modal) modal = new bootstrap.Modal(modalElement);
      modal.show();
    },

    async abrirModalNuevaPoliza() {
      // Limpiar formulario
      this.modoModalPoliza = "crear";
      this.formPoliza = {
        id: null,
        cliente_id: null,
        numero_poliza: "",
        importe_total: "",
        fecha: "",
        estado: "",
        observaciones: "",
      };
      this.erroresPoliza = {};

      try {
        const resp = await fetch("php/listarclientes.php");
        const data = await resp.json();
        if (data.status) {
          this.clientesDisponibles = data.data;
        }
      } catch (e) {
        console.error("Error al obtener clientes disponibles");
      }
      this.mostrarModalPoliza();
    },

    async verClienteDesdePoliza(poliza) {
      try {
        const resp = await fetch(
          `php/listarclientes.php?id=${poliza.cliente_id}`,
        );
        const data = await resp.json();

        if (data.status) {
          if (typeof this.verDetalleCliente === "function") {
            this.verDetalleCliente(data.data);
          } else {
            console.log(
              "El método verDetalleCliente no está accesible desde aquí",
            );
          }
        } else {
          alert("Error: " + data.mensaje);
        }
      } catch (e) {
        console.error("Error al obtener cliente", e);
      }
    },

    prepararEdicionPoliza(poliza) {
      this.modoModalPoliza = "editar";
      this.erroresPoliza = {};
      this.formPoliza = { ...poliza };
      this.mostrarModalPoliza();
    },

    validarFormularioPoliza() {
      this.erroresPoliza = {};
      const f = this.formPoliza;

      if (!f.cliente_id) {
        this.erroresPoliza.cliente_id = "El cliente es obligatorio";
      }

      if (!f.numero_poliza.toString().trim()) {
        this.erroresPoliza.numero_poliza = "El número de póliza es obligatorio";
      }

      if (!f.fecha) {
        this.erroresPoliza.fecha = "La fecha es obligatoria";
      }

      if (f.importe_total <= 0) {
        this.erroresPoliza.importe_total = "El importe debe ser mayor a 0";
      }

      if (!f.estado) {
        this.erroresPoliza.estado = "Debe seleccionar un estado";
      }

      // Devolvemos true si no hay errores
      return Object.keys(this.erroresPoliza).length === 0;
    },

    async guardarPoliza() {
      if (!this.validarFormularioPoliza()) return;

      const url = this.formPoliza.id
        ? "php/editarpoliza.php"
        : "php/insertarpoliza.php";

      try {
        const resp = await fetch(url, {
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
          // Si el PHP detecta que el número de póliza existe, lo ponemos en el objeto errores
          if (data.mensaje.includes("número de póliza")) {
            this.erroresPoliza.numero_poliza = data.mensaje;
          } else {
            alert("Error: " + data.mensaje);
          }
        }
      } catch (e) {
        console.error("Error en la petición", e);
      }
    },

    async prepararBorradoPoliza(poliza) {
      this.polizaABorrar = poliza;
      this.mensajeBorradoPoliza = "Verificando pagos asociados...";

      try {
        const resp = await fetch("php/borrarpoliza.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id: poliza.id, consultar: true }),
        });

        const data = await resp.json();

        if (data.totalPagos > 0) {
          this.mensajeBorradoPoliza = `Esta póliza tiene ${data.totalPagos} pago(s) asociado(s). Si lo eliminas, se borrarán también todos los pagos. Esta acción no se puede deshacer. ¿Deseas continuar?`;
        } else {
          this.mensajeBorradoPoliza = `¿Está seguro de que quieres eliminar esta póliza "${poliza.numero_poliza}"? Esta acción no se puede deshacer.`;
        }

        const modalElement = document.getElementById(
          "modalConfirmarBorradoPoliza",
        );
        let modal = bootstrap.Modal.getInstance(modalElement);
        if (!modal) modal = new bootstrap.Modal(modalElement);
        modal.show();
      } catch (e) {
        console.error("Error al preparar borrado", e);
        this.mensajeBorradoPoliza =
          "Error al cargar información del cliente. No se puede proceder con el borrado.";
      }
    },

    async confirmarBorradoPoliza() {
      if (!this.polizaABorrar) return;

      try {
        const resp = await fetch("php/borrarpoliza.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id: this.polizaABorrar.id }),
        });
        const data = await resp.json();

        bootstrap.Modal.getInstance(
          document.getElementById("modalConfirmarBorradoPoliza"),
        ).hide();

        if (data.status) {
          this.polizaABorrar = null;
          this.cargarPolizas();
          alert(data.mensaje);
        } else {
          alert("Error: " + data.mensaje);
        }
      } catch (e) {
        console.error("Error al borrar poliza", e);
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
