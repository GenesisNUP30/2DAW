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
    async cargarProvincias() {
      const resp = await fetch("php/extraer_provincias.php");
      this.provincias = await resp.json();
    },
    async cargarMunicipios() {
      this.municipios = [];
      this.formCliente.municipio_id = "";
      if (!this.formCliente.provincia_id) return;

      const resp = await fetch(
        `php/extraer_municipios.php?provincia_id=${this.formCliente.provincia_id}`,
      );
      this.municipios = await resp.json();
    },
    validarFormulario() {
      this.errores = {};

      if (!this.formCliente.nombre)
        this.errores.nombre = "El nombre es obligatorio";
      if (
        this.formCliente.tipo_cliente === "Particular" &&
        !this.formCliente.apellidos
      ) {
        this.errores.apellidos = "Los apellidos son obligatorios";
      }

      const errDni = validator.validarIdentificacion(this.formCliente.dni);
      if (errDni) this.errores.dni = errDni;

      const errEmail = validator.validarEmail(this.formCliente.email);
      if (errEmail) this.errores.email = errEmail;

      const errTel = validator.validarTelefono(this.formCliente.telefono);
      if (errTel) this.errores.telefono = errTel;

      if (!this.formCliente.provincia_id)
        this.errores.provincia_id = "Selecciona una provincia";
      if (!this.formCliente.municipio_id)
        this.errores.municipio_id = "Selecciona un municipio";

      return Object.keys(this.errores).length === 0;
    },

    async guardarCliente() {
      if (!this.validarFormulario()) return;

      // Lógica de fetch para guardar en la BD...
      console.log("Enviando datos...", this.formCliente);
    },
    abrirModalNuevo() {
      // Limpiar formulario
      this.formCliente = {
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
      this.cargarProvincias();
      const modal = new bootstrap.Modal(
        document.getElementById("modalCliente"),
      );
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
