const { createApp } = Vue;

createApp({
  // Mezclamos las lógicas de los otros archivos
  mixins: [authLogic, clientesLogic, polizasLogic],
  data() {
    return {
      vistaActual: "clientes", // Puede ser 'clientes' o 'polizas'
    };
  },
  mounted() {
    console.log("App montada")
    // Al arrancar, si hay sesión, cargamos ambos
    if (this.logueado) {
      this.cargarClientes();
      this.cargarPolizas();
    }
  },
}).mount("#app");
