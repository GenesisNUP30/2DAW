const clientesLogic = {
  data() {
    return {
      clientes: [],
      busqueda: "",
      provincias: [],
      municipios: [],
      // Objeto para el formulario
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
      if (!this.formCliente.cp.trim()) {
        this.errores.cp = "El código postal es obligatorio";
      } else if (!/^[0-9]{5}$/.test(this.formCliente.cp)) {
        this.errores.cp = "El código postal debe tener 5 dígitos";
      } else if (this.formCliente.provincia_id) {
        // Validamos coherencia con la provincia elegida
        if (
          !validator.validarCPProvincia(
            this.formCliente.cp,
            this.formCliente.provincia_id,
          )
        ) {
          this.errores.cp = "El CP no corresponde a la provincia seleccionada";
        }
      }

      // Provincia
      if (!f.provincia_id) {
        this.errores.provincia_id = "La provincia es obligatoria";
      }

      // Localidad
      if (!f.municipio_id) {
        this.errores.municipio_id = "La localidad es obligatoria";
      }

      // Dirección
      if (!f.direccion.trim()) {
        this.errores.direccion = "La dirección es obligatoria";
      }
      return Object.keys(this.errores).length === 0;
    },

    async guardarCliente() {
      if (!this.validarFormulario()) return;

      try {
        const resp = await fetch("php/insertarcliente.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(this.formCliente),
        });
        const data = await resp.json();

        if (data.status) {
          // Cerrar el modal
          const modalElement = document.getElementById("modalCliente");
          const modal = bootstrap.Modal.getInstance(modalElement);
          modal.hide();

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
    abrirModalNuevo() {
      // Limpiar formulario
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
      const modalElement = document.getElementById("modalCliente");
      let modal = bootstrap.Modal.getInstance(modalElement);
      if (!modal) {
        modal = new bootstrap.Modal(modalElement);
      }
      modal.show();
    },
    async cargarClientes() {
      try {
        const resp = await fetch("php/listarclientes.php");
        const data = await resp.json();
        if (data.status) {
          this.clientes = data.data;
        }
      } catch (e) {
        console.error("Error al obtener clientes");
      }
    },

    async verDetalleCliente(cliente) {
      this.errores = {};

      // Cargar provincias si no existen
      if (this.provincias.length === 0) await this.cargarProvincias();

      // Cargar municipios de la provincia del cliente específico
      // Usamos await para que la lista de municipios se llene ANTES de asignar el municipio_id
      if (cliente.provincia_id) {
        const resp = await fetch(
          `php/extraermunicipios.php?provincia_id=${cliente.provincia_id}`,
        );
        this.municipios = await resp.json();
      } else {
        this.municipios = [];
      }

      // Clonar el objeto para no editar la fila de la tabla directamente
      this.formCliente = { ...cliente };

      // Abrir el modal
      const modalElement = document.getElementById("modalCliente");
      let modal = bootstrap.Modal.getInstance(modalElement);
      if (!modal) modal = new bootstrap.Modal(modalElement);
      modal.show();
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
