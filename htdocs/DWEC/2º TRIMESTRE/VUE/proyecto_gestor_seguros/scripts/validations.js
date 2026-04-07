const validator = {
  validarIdentificacion(identidad) {
    const value = identidad.toUpperCase().trim();
    const letras = "TRWAGMYFPDXBNJZSQVHLCKE";

    if (!/^[A-Z0-9]{9}$/.test(value)) {
      return "Formato inválido (deben ser 9 caracteres)";
    }

    // NIF (DNI)
    if (/^[0-9]{8}[A-Z]$/.test(value)) {
      const numero = value.substring(0, 8);
      const letra = value.substring(8);
      return letras[numero % 23] === letra
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
      const letra = value.substring(8);
      return letras[numero % 23] === letra
        ? null
        : "La letra del NIE no es correcta";
    }

    // CIF
    if (/^[ABCDEFGHJNPQRSUVW][0-9]{7}[A-Z0-9]$/.test(value)) {
      // Validación simplificada de CIF para el ejemplo
      let sumaPar = 0;
      let sumaImpar = 0;

      for (let i = 1; i <= 6; i += 2) {
        sumaPar += parseInt(value[i], 10);
      }

      for (let i = 0; i <= 6; i += 2) {
        let doble = parseInt(value[i], 10) * 2;
        sumaImpar += doble > 9 ? doble - 9 : doble;
      }

      const sumaTotal = sumaPar + sumaImpar;
      const control = (10 - (sumaTotal % 10)) % 10;
      const controlEsperado = value[8];

      let esValido = false;
      if (/[A-Z]/.test(controlEsperado)) {
        esValido = controlEsperado === String.fromCharCode(64 + control);
      } else {
        esValido = parseInt(controlEsperado, 10) === control;
      }

      return esValido ? null : "El dígito de control del CIF no es correcto";
    }

    return "El número de identificación no es válido";
  },

  validarEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email) ? null : "El formato del email no es válido";
  },

  validarTelefono(tel) {
    return /^[0-9]{9}$/.test(tel) ? null : "El teléfono debe tener 9 dígitos";
  },
};
