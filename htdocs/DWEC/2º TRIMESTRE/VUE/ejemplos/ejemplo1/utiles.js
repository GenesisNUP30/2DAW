var app = new Vue({
    el: '#app',
    data: {
        mensaje: 'Hola amiguitos',
        muestra: true,
        frutas: [
            {
                nombre: 'manzana',
                cantidad: '10'
            },
            {
                nombre: 'pera',
                cantidad: '5'
            },
            {
                nombre: 'fresa',
                cantidad: '2'
            }
        ],
        nuevaFruta: '',
        nuevoKilo: 0,
        totalKilos: 0,
        fotos: [
            'images/fresa.jpg',
            'images/manzana.jpg',
            'images/pera.jpg',
            'images/kiwi.jpg'
        ],
        foto:''
    },
    mounted() {
        this.calcularTotal();
    },
    methods: {
        cambia() {
            this.muestra = !this.muestra;
        },
        calcularTotal() {
            total = 0;
            for (let i = 0; i < this.frutas.length; i++) {
                total += Number(this.frutas[i].cantidad);
            }
            this.totalKilos = total;
        },
        anadeFruta() {
            this.frutas.push({ nombre: this.nuevaFruta, cantidad: this.nuevoKilo });
            this.nuevaFruta = '';
            this.nuevoKilo = 0;
            this.calcularTotal();
        },
        borraFruta(indice) {
            console.log(indice);
            this.frutas.splice(indice, 1);
            this.calcularTotal();
        },
        cambiaFoto() {
            let indice = Math.floor(Math.random() * 4);
            this.foto = this.fotos[indice];
        }

    }
})
