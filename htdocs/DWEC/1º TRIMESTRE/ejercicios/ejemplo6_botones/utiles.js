function realizarOperaciones(numeros, operacion) {
    let resultado = 0;

    switch (operacion) {
        case "suma":
            resultado = 0;
            for (let i = 0; i < numeros.length; i++) {
                resultado += numeros[i];
            }
            break;
        case "resta":
            resultado = numeros[0];
            for (let i = 1; i < numeros.length; i++) {
                resultado -= numeros[i];
            }
            break;
        case "multiplicacion":
            resultado = 1;
            for (let i = 0; i < numeros.length; i++) {
                resultado *= numeros[i];
            }
            break;
        case "division":
            resultado = numeros[0];
            for (let i = 1; i < numeros.length; i++) {
                resultado /= numeros[i];
            }
            break;

        default:
            console.log("Error en la operacion");
            return null;
    }
    alert("El resultado es: " + resultado);
    return resultado;
}

function esPalindromo() {
    let texto = prompt("Escribe el texto que quieres comprobar: ");
    let palindromo = true;
    let textoReversido = texto.split("").reverse().join("");
    if (texto == textoReversido) {
         palindromo = true;
         alert("El texto " + texto + " es palindromo: " + palindromo); 
    } else {
         palindromo = false;
         alert("El texto " + texto + " no es palindromo: " + palindromo);
    }
    
    return palindromo;

}

let array = ["rojo","azul","verde","amarillo"];

function añadirElemento() {
let nuevoValor = prompt("Escribe el nuevo elemento que quieres añadir al array: ");
array.push(nuevoValor);
alert("El array modificado es: " + array);
}

function eliminarElemento() {
array.pop();
alert("El array modificado es: " + array);
}

function filtrarYTransformar() {
    let array = [1,2,3,4,5,6,7,8,9,10];
    let arrayModificado = [];

    for (let i = 0; i < array.length; i++) {
        if (array[i] % 2 == 0) {
             arrayModificado.push(array[i] * 2);
        }
    }
    alert("El array filtrado y transformado es: " + arrayModificado);
}

function interseccionArray() {
    let array1 = [3, 7, 9, 10, 2, 5, 14, 35, 26];
    let array2 = [4, 8, 10, 32, 7, 2, 9, 56, 76];
    let arrayInterseccion = [];

    for (let i = 0; i < array1.length; i++) {
         if (array2.includes(array1[i]) && !arrayInterseccion.includes(array1[i])) {
            arrayInterseccion.push(array1[i]);
        }
    }
    alert("La intersección entre los dos arrays es: " + arrayInterseccion);
}

function eliminarDuplicados() {
    let array = [10, 24, 11, 5, 9, 2, 9, 3, 8, 1, 5, 10, 7];
    let arrayModificado = [];

    for (let i = 0; i < array.length; i++) {
        if (!arrayModificado.includes(array[i])) {
            arrayModificado.push(array[i]);
        }
    }
    alert ("El array con los elementos únicos es: " + arrayModificado);
}




