const clientesLogic = {
  data() {
    return {
      clientes: [],
      busqueda: "",
      provincias: [],
      municipios: [],
      modoModal: "crear",
      mensajeError: "",
      mensajeBorrado: "",
      clienteABorrar: null,
      // Objeto para el formulario de creación/edición/ver detalle de cliente
      formCliente: {
        id: null,
        tipo_cliente: "Particular",
        nombre: "",
        apellidos: "",
        dni: "",
        email: "",
        telefono: "",
        cp: "",
        provincia_id: "",
        municipio_id: "",
        direccion: "",
      },
      errores: {}, // Aquí guardaremos los mensajes de error
    };
  },
  methods: {
    cambiarTipoCliente() {
      this.errores = {}; // Limpiamos todos los errores al cambiar de Particular a Empresa o viceversa
    },

    async cargarProvincias() {
      const resp = await fetch("php/extraerprovincias.php");
      this.provincias = await resp.json();
    },

    async cargarMunicipios() {
      this.municipios = [];
      this.formCliente.municipio_id = "";
      if (!this.formCliente.provincia_id) return;

      const resp = await fetch(
        `php/extraermunicipios.php?provincia_id=${this.formCliente.provincia_id}`,
      );
      this.municipios = await resp.json();
    },

    // Método auxiliar compartido por verDetalleCliente y prepararEdicion
    async cargarDatosCliente(cliente) {
      this.errores = {};
      if (this.provincias.length === 0) await this.cargarProvincias();
      if (cliente.provincia_id) {
        const resp = await fetch(
          `php/extraermunicipios.php?provincia_id=${cliente.provincia_id}`,
        );
        this.municipios = await resp.json();
      } else {
        this.municipios = [];
      }
      this.formCliente = { ...cliente };
    },

    mostrarModal() {
      const modalElement = document.getElementById("modalCliente");
      let modal = bootstrap.Modal.getInstance(modalElement);
      if (!modal) modal = new bootstrap.Modal(modalElement);
      modal.show();
    },

    abrirModalNuevo() {
      // Limpiar formulario
      this.modoModal = "crear";
      this.formCliente = {
        id: null,
        tipo_cliente: "Particular",
        nombre: "",
        apellidos: "",
        dni: "",
        email: "",
        telefono: "",
        cp: "",
        provincia_id: "",
        municipio_id: "",
        direccion: "",
      };
      this.errores = {};
      this.municipios = [];
      this.cargarProvincias();
      this.mostrarModal();
    },

    async verDetalleCliente(cliente) {
      this.modoModal = "ver";
      await this.cargarDatosCliente(cliente);
      this.mostrarModal();
    },

    async prepararEdicion(cliente) {
      this.modoModal = "editar";
      await this.cargarDatosCliente(cliente);
      this.mostrarModal();
    },

    validarFormulario() {
      this.errores = {};
      const f = this.formCliente;

      // Nombre
      if (!f.nombre.trim()) this.errores.nombre = "El nombre es obligatorio";

      // Apellidos (solo particulares)
      if (f.tipo_cliente === "Particular") {
        if (!f.apellidos.trim())
          this.errores.apellidos = "Los apellidos son obligatorios";
      }

      // Identificación
      if (!f.dni.trim()) {
        this.errores.dni = "La identificación es obligatoria";
      } else {
        const err = validator.validarIdentificacion(f.dni);
        if (err) this.errores.dni = err;
      }

      // Email
      if (!f.email.trim()) {
        this.errores.email = "El email es obligatorio";
      } else {
        const err = validator.validarEmail(f.email);
        if (err) this.errores.email = err;
      }

      // Teléfono
      if (!f.telefono.trim()) {
        this.errores.telefono = "El teléfono es obligatorio";
      } else {
        const err = validator.validarTelefono(f.telefono);
        if (err) this.errores.telefono = err;
      }

      // Validación de Código Postal
      if (!f.cp.trim()) {
        this.errores.cp = "El código postal es obligatorio";
      } else if (!/^[0-9]{5}$/.test(f.cp)) {
        this.errores.cp = "El código postal debe tener 5 dígitos";
      } else if (f.provincia_id) {
        // Validamos coherencia con la provincia elegida
        if (!validator.validarCPProvincia(f.cp, f.provincia_id)) {
          this.errores.cp = "El CP no corresponde a la provincia seleccionada";
        }
      }

      // Provincia
      if (!f.provincia_id)
        this.errores.provincia_id = "La provincia es obligatoria";
      // Localidad
      if (!f.municipio_id)
        this.errores.municipio_id = "La localidad es obligatoria";
      // Dirección
      if (!f.direccion.trim())
        this.errores.direccion = "La dirección es obligatoria";

      return Object.keys(this.errores).length === 0;
    },

    async guardarCliente() {
      if (!this.validarFormulario()) return;

      const url = this.formCliente.id
        ? "php/editarcliente.php"
        : "php/insertarcliente.php";

      try {
        const resp = await fetch(url, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(this.formCliente),
        });
        const data = await resp.json();

        if (data.status) {
          // Cerrar el modal
          bootstrap.Modal.getInstance(
            document.getElementById("modalCliente"),
          ).hide();
          // Recargar la lista de clientes para ver el nuevo
          this.cargarClientes();
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
    async prepararBorrado(cliente) {
      this.clienteABorrar = cliente;
      this.mensajeBorrado = "Cargando información...";

      try {
        const resp = await fetch("php/borrarcliente.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id: cliente.id, consultar: true }),
        });
        const data = await resp.json();

        const nombre =
          cliente.nombre + (cliente.apellidos ? " " + cliente.apellidos : "");

        if (data.totalPolizas > 0 || data.totalPagos > 0) {
          this.mensajeBorrado = `Este cliente tiene ${data.totalPolizas} póliza(s) y ${data.totalPagos} pago(s) asociado(s). Si lo eliminas, se borrarán también todas sus pólizas y pagos. Esta acción no se puede deshacer. ¿Deseas continuar?`;
        } else {
          this.mensajeBorrado = `¿Está seguro de que quieres eliminar a "${nombre}"? Esta acción no se puede deshacer.`;
        }

        const modalElement = document.getElementById("modalConfirmarBorrado");
        let modal = bootstrap.Modal.getInstance(modalElement);
        if (!modal) modal = new bootstrap.Modal(modalElement);
        modal.show();
      } catch (e) {
        console.error("Error al preparar borrado", e);
        this.mensajeBorrado =
          "Error al cargar información del cliente. No se puede proceder con el borrado.";
      }
    },

    async confirmarBorrado() {
      if (!this.clienteABorrar) return;

      try {
        const resp = await fetch("php/borrarcliente.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id: this.clienteABorrar.id }),
        });
        const data = await resp.json();

        bootstrap.Modal.getInstance(
          document.getElementById("modalConfirmarBorrado"),
        ).hide();

        if (data.status) {
          this.clienteABorrar = null;
          this.cargarClientes();
          alert(data.mensaje);
        } else {
          alert("Error: " + data.mensaje);
        }
      } catch (e) {
        console.error("Error al borrar cliente", e);
      }
    },

    async cargarClientes() {
      try {
        const resp = await fetch("php/listarclientes.php");
        const data = await resp.json();
        if (data.status) this.clientes = data.data;
      } catch (e) {
        console.error("Error al obtener clientes");
      }
    },
  },
  computed: {
    clientesFiltrados() {
      if (!this.busqueda) return this.clientes;
      const b = this.busqueda.toLowerCase();
      return this.clientes.filter(
        (c) =>
          c.nombre.toLowerCase().includes(b) ||
          (c.apellidos && c.apellidos.toLowerCase().includes(b)) ||
          (c.dni && c.dni.toLowerCase().includes(b)),
      );
    },
  },
};
