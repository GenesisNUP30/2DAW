const { createApp } = Vue;

// Validador externo (igual que antes)
const validator = {
  validarIdentificacion(identidad) {
    const value = identidad.toUpperCase().trim();
    const letras = "TRWAGMYFPDXBNJZSQVHLCKE";

    if (!/^[A-Z0-9]{9}$/.test(value)) return "Formato inválido (9 caracteres)";

    // NIF (DNI)
    if (/^[0-9]{8}[A-Z]$/.test(value)) {
      const numero = value.substring(0, 8);
      return letras[numero % 23] === value.substring(8)
        ? null
        : "La letra del DNI no es correcta";
    }

    // NIE
    if (/^[XYZ][0-9]{7}[A-Z]$/.test(value)) {
      let prefijo = value[0]
        .replace("X", "0")
        .replace("Y", "1")
        .replace("Z", "2");
      const numero = prefijo + value.substring(1, 8);
      return letras[numero % 23] === value.substring(8)
        ? null
        : "La letra del NIE no es correcta";
    }

    // CIF
    if (/^[ABCDEFGHJKLMNPQRSUVW][0-9]{7}[A-Z0-9]$/.test(value)) {
      let sumaPar = 0,
        sumaImpar = 0;

      // Posiciones pares: 2, 4, 6
      for (let i = 2; i <= 6; i += 2) sumaPar += parseInt(value[i], 10);

      // Posiciones impares: 1, 3, 5, 7
      for (let i = 1; i <= 7; i += 2) {
        let doble = parseInt(value[i], 10) * 2;
        sumaImpar += doble > 9 ? doble - 9 : doble;
      }
      const sumaTotal = sumaPar + sumaImpar;
      const control = (10 - (sumaTotal % 10)) % 10;
      const letrasControl = "JABCDEFGHI";
      const controlEsperado = value[8];

      let esValido = false;

      // Si el último carácter es una letra
      if (/[A-Z]/.test(controlEsperado)) {
        esValido = controlEsperado === letrasControl[control];
      } else {
        // Si el último carácter es un número
        esValido = parseInt(controlEsperado, 10) === control;
      }

      return esValido ? null : "El dígito de control del CIF no es correcto";
    }
    return "Identificación no válida";
  },
  validarCPProvincia(cp, provinciaId) {
    // Extraemos los dos primeros dígitos del CP introducido
    const primerosDosCP = cp.substring(0, 2);

    // Convertimos el ID de la provincia a string y le ponemos un 0 delante si es menor de 10
    // Ejemplo: ID 1 -> "01", ID 28 -> "28"
    const idEsperado = provinciaId.toString().padStart(2, "0");

    // 3. Comparamos
    return primerosDosCP === idEsperado;
  },
  validarEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email) ? null : "Email no válido";
  },
  validarTelefono(tel) {
    return /^[0-9]{9}$/.test(tel) ? null : "El teléfono debe tener 9 dígitos";
  },
};

createApp({
  data() {
    return {
      // --- AUTH ---
      logueado: false,
      usuarioActivo: null,
      errorLogin: "",
      formLogin: { usuario: "", password: "" },

      // --- NAVEGACIÓN ---
      vistaActual: "clientes",
      busqueda: "",
      busquedaPoliza: "",
      provincias: [],
      municipios: [],

      // --- CLIENTES ---
      clientes: [],
      modoModal: "crear",
      mensajeBorrado: "",
      clienteABorrar: null,
      clienteSeleccionadoId: null,
      polizasCliente: [],
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
      errores: {},

      // --- PÓLIZAS ---
      polizas: [],
      modoModalPoliza: "crear",
      clientesDisponibles: [],
      mensajeBorradoPoliza: "",
      polizaABorrar: null,
      formPoliza: {
        id: null,
        cliente_id: "",
        numero_poliza: "",
        fecha: "",
        importe_total: 0,
        estado: "",
        observaciones: "",
        nombre_cliente: "",
      },
      erroresPoliza: {},

      // --- PAGOS ---
      polizaSeleccionada: null,
      pagos: [],
      totalPagadoPoliza: 0,
      pagoABorrar: null,
      mensajeBorradoPago: "",
      formPago: {
        poliza_id: null,
        fecha: new Date().toISOString().split("T")[0],
        importe: 0,
      },
      erroresPago: {},

      // --- LISTADO DE CLIENTES Y POLIZAS ---
      vistaListados: {
        codInicio: "",
        codFin: "",
        fechaInicio: "",
        fechaFin: "",
        estadoFiltro: "Todas", // "Todas", "cobrada", "anulada", etc.
      },
      resultadosListados: [],
    };
  },

  mounted() {
    console.log("Vue ha arrancado correctamente");
    this.verificarSesion();
  },

  methods: {
    // --- AUTH ---
    async hacerLogin() {
      this.errorLogin = "";
      try {
        const resp = await fetch("php/login.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(this.formLogin),
        });
        const data = await resp.json();
        if (data.status) {
          this.logueado = true;
          this.usuarioActivo = data.usuario;
          localStorage.setItem("usuario", JSON.stringify(data.usuario));
          this.inicializarApp();
        } else {
          this.errorLogin = data.mensaje;
        }
      } catch (e) {
        this.errorLogin = "Error de conexión";
      }
    },
    verificarSesion() {
      const sesionGuardada = localStorage.getItem("usuario");
      if (sesionGuardada) {
        this.usuarioActivo = JSON.parse(sesionGuardada);
        this.logueado = true;
        this.inicializarApp();
      }
    },
    salir() {
      this.logueado = false;
      this.usuarioActivo = null;
      localStorage.removeItem("usuario");
    },
    inicializarApp() {
      this.cargarClientes();
      this.cargarPolizas();
      this.cargarProvincias();
    },

    // --- CLIENTES ---
    cambiarTipoCliente() {
      // Limpiamos todos los errores al cambiar de Particular a Empresa o viceversa
      this.errores = {};
    },

    abrirModalNuevo() {
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
      this.mostrarModal("modalCliente");
    },

    async cargarClientes() {
      try {
        const resp = await fetch("php/listarclientes.php");
        const data = await resp.json();
        if (data.status) this.clientes = data.data;
      } catch (e) {
        console.error("Error al obtener clientes", e);
      }
    },
    async cargarProvincias() {
      try {
        const resp = await fetch("php/extraerprovincias.php");
        this.provincias = await resp.json();
      } catch (e) {
        console.error("Error provincias", e);
      }
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

    async togglePolizas(cliente) {
      if (this.clienteSeleccionadoId === cliente.id) {
        this.clienteSeleccionadoId = null;
        return;
      }
      this.clienteSeleccionadoId = cliente.id;
      try {
        const resp = await fetch(
          `php/listarpolizas.php?cliente_id=${cliente.id}`,
        );
        const data = await resp.json();

        if (data.status) {
          this.polizasCliente = data.data;
        } else {
          this.polizasCliente = [];
        }
      } catch (e) {
        console.error("Error al obtener pólizas del cliente", e);
      }
    },

    validarFormulario() {
      this.errores = {};
      const f = this.formCliente;
      // Nombre
      if (!f.nombre.trim()) this.errores.nombre = "El nombre es obligatorio";
      // Apellidos (solo particulares)
      if (f.tipo_cliente === "Particular" && !f.apellidos.trim())
        this.errores.apellidos = "Los apellidos son obligatorios";

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

      if (!f.cp.trim()) {
        this.errores.cp = "El código postal es obligatorio";
      } else if (!/^[0-9]{5}$/.test(f.cp)) {
        this.errores.cp = "Debe tener 5 dígitos";
      } else if (
        f.provincia_id &&
        !validator.validarCPProvincia(f.cp, f.provincia_id)
      ) {
        this.errores.cp = "El código postal no corresponde a la provincia";
      }

      if (!f.provincia_id) {
        this.errores.provincia_id = "La provincia es obligatoria";
      } else if (!f.municipio_id) {
        // Solo entra aquí si provincia_id SÍ tiene valor
        this.errores.municipio_id = "La localidad es obligatoria";
      }
      if (!f.direccion.trim())
        this.errores.direccion = "La dirección es obligatoria";

      return Object.keys(this.errores).length === 0;
    },

    async guardarCliente() {
      if (!this.validarFormulario()) return;

      // Aquí llamarías a tu validador
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
          this.cerrarModal("modalCliente");
          this.cargarClientes();
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
        console.error("Error al guardar el cliente", e);
      }
    },

    async prepararBorrado(cliente) {
      this.clienteABorrar = cliente;
      this.mensajeBorrado = "Cargando información...";
      this.mostrarModal("modalConfirmarBorrado");
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
          this.mensajeBorrado = `Este cliente tiene ${data.totalPolizas} póliza(s) y ${data.totalPagos} pago(s). Si lo eliminas, se borrará todo. ¿Deseas continuar?`;
        } else {
          this.mensajeBorrado = `¿Seguro que quieres eliminar a "${nombre}"?`;
        }
      } catch (e) {
        this.mensajeBorrado = "Error al verificar datos del cliente.";
      }
    },

    async confirmarBorrado() {
      try {
        const resp = await fetch("php/borrarcliente.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id: this.clienteABorrar.id }),
        });
        const data = await resp.json();
        this.cerrarModal("modalConfirmarBorrado");
        if (data.status) this.cargarClientes();
        alert(data.mensaje);
      } catch (e) {
        console.error("Error borrado", e);
      }
    },

    async verDetalleCliente(cliente) {
      this.modoModal = "ver";
      await this.cargarDatosCliente(cliente);
      this.mostrarModal("modalCliente");
    },

    async prepararEdicion(cliente) {
      this.modoModal = "editar";
      await this.cargarDatosCliente(cliente);
      this.mostrarModal("modalCliente");
    },
    // Método auxiliar compartido por verDetalleCliente y prepararEdicion
    async cargarDatosCliente(cliente) {
      this.errores = {};
      if (cliente.provincia_id) {
        const resp = await fetch(
          `php/extraermunicipios.php?provincia_id=${cliente.provincia_id}`,
        );
        this.municipios = await resp.json();
      }
      this.formCliente = { ...cliente };
    },

    // --- GESTIÓN DE PÓLIZAS ---
    async cargarPolizas() {
      try {
        const resp = await fetch("php/listarpolizas.php");
        const data = await resp.json();
        if (data.status) this.polizas = data.data;
      } catch (e) {
        console.error("Error pólizas", e);
      }
    },

    validarFormularioPoliza() {
      this.erroresPoliza = {};
      const f = this.formPoliza;
      if (!f.cliente_id)
        this.erroresPoliza.cliente_id = "El cliente es obligatorio";
      if (!f.numero_poliza || String(f.numero_poliza).trim() === "")
        this.erroresPoliza.numero_poliza = "El número es obligatorio";
      if (!f.fecha) this.erroresPoliza.fecha = "La fecha es obligatoria";
      if (parseFloat(f.importe_total) <= 0)
        this.erroresPoliza.importe_total = "El importe debe ser mayor a 0";
      if (!f.estado) this.erroresPoliza.estado = "El estado es obligatorio";
      return Object.keys(this.erroresPoliza).length === 0;
    },

    async guardarPoliza() {
      if (!this.validarFormularioPoliza()) return;

      const url = this.formPoliza.id
        ? "php/editarpoliza.php"
        : "php/insertarpoliza.php";
      try {
        console.log("Enviando datos:", this.formPoliza);

        const resp = await fetch(url, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(this.formPoliza),
        });
        const data = await resp.json();
        console.log("Respuesta backend:", data);

        if (data.status) {
          this.cerrarModal("modalPoliza");
          this.cargarPolizas();
          // 2. Si estamos en la pestaña Clientes y hay uno expandido, refrescar sus pólizas
          if (this.clienteSeleccionadoId) {
            // Buscamos el objeto cliente completo para pasárselo a togglePolizas
            const clienteActivo = this.clientes.find(
              (c) => c.id === this.clienteSeleccionadoId,
            );
            if (clienteActivo) {
              // Llamamos dos veces a toggle para cerrar y reabrir (limpiar estado)
              // o mejor, crea un método "refrescarPolizasCliente()"
              this.clienteSeleccionadoId = null;
              this.togglePolizas(clienteActivo);
            }
          }
          alert(data.mensaje);
        } else {
          alert(data.mensaje);
        }
      } catch (e) {
        console.error("Error al guardar póliza", e);
      }
    },

    nuevaPolizaParaCliente(cliente) {
      this.modoModalPoliza = "crear";
      this.erroresPoliza = {};

      this.clientesDisponibles = this.clientes;
      this.formPoliza = {
        id: null,
        cliente_id: cliente.id,
        nombre_cliente: `${cliente.nombre} ${cliente.apellidos || ""}`,
        numero_poliza: "",
        importe_total: 0,
        fecha: "",
        estado: "",
        observaciones: "",
      };
      this.mostrarModal("modalPoliza");
    },

    async abrirModalNuevaPoliza() {
      this.modoModalPoliza = "crear";
      this.erroresPoliza = {};
      this.formPoliza = {
        id: null,
        cliente_id: "",
        numero_poliza: "",
        importe_total: 0,
        fecha: "",
        estado: "",
        observaciones: "",
      };
      const resp = await fetch("php/listarclientes.php");
      const data = await resp.json();
      if (data.status) this.clientesDisponibles = data.data;
      this.mostrarModal("modalPoliza");
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

    async prepararEdicionPoliza(poliza) {
      this.modoModalPoliza = "editar";
      this.erroresPoliza = {};

      // Cargamos los clientes para que el select tenga opciones
      try {
        const resp = await fetch("php/listarclientes.php");
        const data = await resp.json();
        if (data.status) this.clientesDisponibles = data.data;
      } catch (e) {
        console.error("Error al cargar clientes para edición", e);
      }

      this.formPoliza = { ...poliza };
      this.mostrarModal("modalPoliza");
    },

    async prepararBorradoPoliza(poliza) {
      this.polizaABorrar = poliza;
      this.mensajeBorradoPoliza = "Verificando pagos asociados...";
      this.mostrarModal("modalConfirmarBorradoPoliza");
      try {
        const resp = await fetch("php/borrarpoliza.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id: poliza.id, consultar: true }),
        });
        const data = await resp.json();
        if (data.totalPagos > 0) {
          this.mensajeBorradoPoliza = `Esta póliza tiene ${data.totalPagos} pago(s). Si la borras, se perderán. ¿Continuar?`;
        } else {
          this.mensajeBorradoPoliza = `¿Eliminar póliza "${poliza.numero_poliza}"?`;
        }
      } catch (e) {
        this.mensajeBorradoPoliza = "Error al verificar pagos.";
      }
    },

    async confirmarBorradoPoliza() {
      try {
        const resp = await fetch("php/borrarpoliza.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id: this.polizaABorrar.id }),
        });
        const data = await resp.json();
        this.cerrarModal("modalConfirmarBorradoPoliza");
        if (data.status) {
          this.cargarPolizas();
          if (this.clienteSeleccionadoId) {
            const cli = this.clientes.find(
              (c) => c.id === this.clienteSeleccionadoId,
            );
            this.togglePolizas(cli);
          }
        }
        alert(data.mensaje);
      } catch (e) {
        console.error("Error borrado poliza", e);
      }
    },

    // --- MÉTODOS DE PAGOS ---
    async abrirModalPagos(poliza) {
      this.polizaSeleccionada = poliza;
      this.formPago.poliza_id = poliza.id;
      this.formPago.importe = 0;
      this.formPago.fecha = new Date().toISOString().split("T")[0];

      await this.cargarPagos(poliza.id); // Este es el método que llama a listarpagos.php
      this.mostrarModal("modalPagos");
    },

    async cargarPagos(poliza_id) {
      try {
        const resp = await fetch(`php/listarpagos.php?poliza_id=${poliza_id}`);
        const data = await resp.json();
        if (data.status) {
          this.pagos = data.data;
          // Calculamos el total sumando los importes
          this.totalPagadoPoliza = this.pagos.reduce(
            (acc, p) => acc + parseFloat(p.importe),
            0,
          );
        }
      } catch (e) {
        console.error("Error al cargar pagos", e);
      }
    },

    validarPago() {
      this.erroresPago = {};
      const f = this.formPago;
      const restante =
        parseFloat(this.polizaSeleccionada.importe_total) -
        this.totalPagadoPoliza;

      if (!f.fecha) {
        this.erroresPago.fecha = "La fecha es obligatoria";
      }

      if (!f.importe || f.importe <= 0) {
        this.erroresPago.importe = "El importe debe ser mayor a 0";
      } else if (f.importe > restante + 0.01) {
        // Añadimos aquí la validación del importe máximo para que salga en el campo
        this.erroresPago.importe = `Máximo permitido: ${restante.toFixed(2)}€`;
      }

      // Devolvemos true si no hay errores
      return Object.keys(this.erroresPago).length === 0;
    },

    async guardarPago() {
      if (!this.validarPago()) return;

      try {
        const resp = await fetch("php/insertarpago.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(this.formPago),
        });
        const data = await resp.json();

        if (data.status) {
          // Limpiamos errores y formulario tras éxito
          this.erroresPago = {};
          this.formPago.importe = 0;

          alert(data.mensaje);
          this.cargarPagos(this.polizaSeleccionada.id); // Recargar lista y totales
          this.cargarPolizas(); // Recargar la lista principal por si cambió el estado
        } else {
          alert(data.mensaje);
        }
      } catch (e) {
        console.error("Error al guardar pago", e);
      }
    },

    async prepararBorradoPago(pago) {
      this.pagoABorrar = pago;
      this.mensajeBorradoPago = "Verificando datos del pago...";
      this.mostrarModal("modalConfirmarBorradoPago");
      this.mensajeBorradoPago = `¿Eliminar pago de ${pago.importe}€ realizado el ${this.formatearFecha(pago.fecha)}?`;
    },

    async confirmarBorradoPago() {
      if (!this.pagoABorrar) return;

      try {
        const resp = await fetch("php/borrarpago.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id: this.pagoABorrar.id }),
        });
        const data = await resp.json();

        // Cerrar el modal
        this.cerrarModal("modalConfirmarBorradoPago");
        if (data.status) {
          // Recargar los pagos y los totales de la póliza
          await this.cargarPagos(this.polizaSeleccionada.id);
          // Recargar la lista principal por si cambió el estado
          this.cargarPolizas();
        } else {
          alert(data.mensaje);
        }
      } catch (e) {
        console.error("Error borrado", e);
      } finally {
        this.pagoABorrar = null;
      }
    },

    // --- GENERAR LISTADO ---
    validarRangoId() {
      if (
        this.vistaListados.codFin &&
        parseInt(this.vistaListados.codFin) <
          parseInt(this.vistaListados.codInicio)
      ) {
        this.vistaListados.codFin = "";
      }
    },

    async generarListado() {
      try {
        const resp = await fetch("php/generarlistado.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(this.vistaListados),
        });
        const data = await resp.json();
        if (data.status) {
          this.resultadosListados = data.data;
        }
      } catch (e) {
        console.error("Error al generar el listado", e);
      }
    },

    // --- UTILIDADES ---
    mostrarModal(id) {
      const el = document.getElementById(id);
      if (el)
        (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).show();
    },
    cerrarModal(id) {
      const el = document.getElementById(id);
      if (el) {
        const m = bootstrap.Modal.getInstance(el);
        if (m) m.hide();
      }
    },
    colorFilaEstado(estado) {
      const colores = {
        cobrada: "table-success",
        "a cuenta": "table-info",
        liquidada: "table-primary",
        anulada: "table-danger",
        "pre-anulada": "table-warning",
      };
      return colores[estado] || "";
    },
    claseEstado(estado) {
      if (!estado) return "bg-secondary";
      const clases = {
        cobrada: "bg-success",
        "a cuenta": "bg-info text-dark",
        liquidada: "bg-primary",
        anulada: "bg-danger",
        "pre-anulada": "bg-warning text-dark",
      };
      return clases[estado.toLowerCase()] || "bg-secondary";
    },

    formatearFecha(fecha) {
      if (!fecha) return "";
      // Separamos el año, mes y día (asumiendo que viene YYYY-MM-DD o YYYY-MM-DD HH:mm:ss)
      const partes = fecha.split(" ")[0].split("-");
      if (partes.length !== 3) return fecha; // Por si acaso el formato es raro

      const [anio, mes, dia] = partes;
      return `${dia}-${mes}-${anio}`;
    },
  },
  computed: {
    clientesFiltrados() {
      const b = this.busqueda.toLowerCase();
      return this.clientes.filter(
        (c) =>
          (c.nombre && c.nombre.toLowerCase().includes(b)) ||
          (c.dni && c.dni.toLowerCase().includes(b)),
      );
    },
    polizasFiltradas() {
      const b = this.busquedaPoliza.toLowerCase();
      return this.polizas.filter(
        (p) =>
          (p.numero_poliza && p.numero_poliza.toLowerCase().includes(b)) ||
          (p.nombre_cliente && p.nombre_cliente.toLowerCase().includes(b)),
      );
    },
    clientesFinFiltrados() {
      if (!this.vistaListados.codInicio) {
        return this.clientes;
      }
      return this.clientes.filter(
        (c) => parseInt(c.id) >= parseInt(this.vistaListados.codInicio),
      );
    },
  },
}).mount("#app");
