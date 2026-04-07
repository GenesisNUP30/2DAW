const { createApp } = Vue;

createApp({
    // Mezclamos las lógicas de los otros archivos
    mixins: [authLogic, clientesLogic],
    
    mounted() {
        console.log("Aplicación de Seguros lista");
    }
}).mount('#app');