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
  },
}).mount("#app");
