var votos = [0, 0, 0];
var totalvotos = 0;

function sumar() {
    // document.querySelector('#formulario').preventDefault();
    if (document.querySelector('#numero1').value === '' || document.querySelector('#numero2').value === '') {
        alert('Error. Debe introducir los numeros');
    }
    let num1 = parseInt(document.querySelector('#numero1').value);
    let num2 = parseInt(document.querySelector('#numero2').value);
    let suma = num1 + num2;
    document.querySelector('#resultado').innerHTML = 'SUMA: ' + suma;
}

function vota() {
    let opciones = document.getElementsByName('encuesta');
    for (let i = 0; i < opciones.length; i++) {
        if (opciones[i].checked) {
            votos[i]++;
            totalvotos++;
        }
        //alert(opciones[i].value);             
        let porcentajeSi = (votos[0] / totalvotos) * 100;
        let porcentajeNo = (votos[1] / totalvotos) * 100;
        let porcentajeNsNc = (votos[2] / totalvotos) * 100;

        document.getElementById('votosSi').innerHTML = " " + votos[0] + ' / ' + porcentajeSi.toFixed(2) + '%';
        document.getElementById('votosNo').innerHTML = " " + votos[1] + ' / ' + porcentajeNo.toFixed(2) + '%';
        document.getElementById('votosNsNc').innerHTML = " " + votos[2] + ' / ' + porcentajeNsNc.toFixed(2) + '%';

        document.getElementById('verde').width = 5 * porcentajeSi;
        document.getElementById('verde').height = 5;
        document.getElementById('rojo').width = 5 * porcentajeNo;
        document.getElementById('rojo').height = 5;
        document.getElementById('azul').width = 5 * porcentajeNsNc;
        document.getElementById('azul').height = 5;
    }
}

function anade() {
    //document.getElementById('pais').option.push('Hola');
    let paises = document.getElementById('pais');
    const nuevaOpcion = new Option(document.getElementById('texto').value, "nw");
    paises.options.add(nuevaOpcion);
}



