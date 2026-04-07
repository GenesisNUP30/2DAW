const validator = {
    validarIdentificacion(identidad) {
        const value = identidad.toUpperCase().trim();
        const letras = 'TRWAGMYFPDXBNJZSQVHLCKE';

        if (!/^[A-Z0-9]{9}$/.test(value)) {
            return "Formato inválido (deben ser 9 caracteres)";
        }

        // NIF (DNI)
        if (/^[0-9]{8}[A-Z]$/.test(value)) {
            const numero = value.substring(0, 8);
            const letra = value.substring(8);
            return letras[numero % 23] === letra ? null : "La letra del DNI no es correcta";
        }

        // NIE
        if (/^[XYZ][0-9]{7}[A-Z]$/.test(value)) {
            let prefijo = value[0].replace('X', '0').replace('Y', '1').replace('Z', '2');
            const numero = prefijo + value.substring(1, 8);
            const letra = value.substring(8);
            return letras[numero % 23] === letra ? null : "La letra del NIE no es correcta";
        }

        // CIF
        if (/^[ABCDEFGHJNPQRSUVW][0-9]{7}[A-Z0-9]$/.test(value)) {
            // Validación simplificada de CIF para el ejemplo
            return null; 
        }

        return "El número de identificación no es válido";
    },

    validarEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email) ? null : "Email no válido";
    },

    validarTelefono(tel) {
        return /^[0-9]{9}$/.test(tel) ? null : "El teléfono debe tener 9 dígitos";
    }
};