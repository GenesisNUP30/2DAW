function anade() {
    let palabra = document.getElementById('select1');
    const nuevaOpcion = new Option(document.getElementById('texto').value, "nw");
    palabra.options.add(nuevaOpcion);
}

function anade2() {
    let select1 = document.getElementById('select1');
    let select2 = document.getElementById('select2');

    let opcion = select1.options[select1.selectedIndex];
    if (!opcion) return;

    if (!select2.querySelector('option[value="${opcion.value}"]')) {
        select2.add(new Option(opcion.text, opcion.value));
    }
}

const listasubcategorias = [
    ["Ordenadores", "Smartphones", "Accesorios"],
    ["Lámparas", "Toallas", "Compresas"],
    ["Camisas", "Pantalones", "Zapatos"]
]

function cargarSubcategorias() {
    let categoria = document.getElementById('categoria').value;
    console.log(categoria);
    let subcategorias = listasubcategorias[categoria];
    console.log(subcategorias);
    var selectsubcategorias = document.getElementById('subcategoria');
    for (let i = 0; i < subcategorias.length; i++) {
        // console.log(subcategorias[i]);
        
        let nuevo = document.createElement('option');
        nuevo.text = subcategorias[i];
        nuevo.value = i;
        selectsubcategorias.appendChild(nuevo);
        // let nuevo = new Option(subcategorias[i]);
        
        //selectsubcategorias.options.add(new Option(subcategorias[i], i));
    }
}