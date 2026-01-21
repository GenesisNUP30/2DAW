new Vue ({
    el: '#aplicacion',
    data: {
        palabras: [],
        nuevaPalabra: '',
        palabraSeleccionada: ''
    },
    methods: {
        anadePalabra: function () {
            this.palabras.push(this.nuevaPalabra)
            this.nuevaPalabra = ''
            console.log(this.nuevaPalabra)
        }
    }
})