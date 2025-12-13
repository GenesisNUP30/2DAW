function mostrarSeleccion() {
            // Obteniendo la referencia al elemento select
            const selectElement = document.getElementById('frutas');
            
            // Array para almacenar las opciones seleccionadas
            let seleccionadas = [];

            // Iterando sobre las opciones del select
            for (let option of selectElement.options) {
                // Si la opción está seleccionada, se agrega al array
                if (option.selected) {
                    seleccionadas.push(option.value);
                }
            }

            // Mostrando la selección en el párrafo con id="resultado"
            document.getElementById('resultado').textContent = 
                seleccionadas.length > 0 ? `Has seleccionado: ${seleccionadas.join(', ')}` : "No has seleccionado nada";
        }
