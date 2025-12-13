function incrementar(celda) {
    let valor = parseInt(celda.textContent);
    valor++;
    celda.textContent = valor;
    if (valor >= 10) {
        celda.style.backgroundColor = 'yellow';
    }

}

// const celdas = document.querySelectorAll('td');
// celdas.forEach(celda => {
//     let valor = parseInt(this.textContent);
//     valor++;
//     this.textContent = valor;
//     if (valor >= 10) {
//         this.style.backgroundColor = 'yellow';
//     }
// });
