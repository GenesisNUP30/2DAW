new Vue ({
    el: '#aplicacion',
    data: {
        articulos: [],
        codigo: '',
        descripcion: '',
        cantidad: 0,
        precio: 0,
        totalPrecio: 0
    },
    methods: {
        anade: function (event) {
            this.articulos.push({
                codigo: this.codigo,
                descripcion: this.descripcion,
                cantidad: this.cantidad,
                precio: this.precio
            })
            this.codigo = ''
            this.descripcion = ''
            this.cantidad = 0
            this.precio = 0
            console.log(this.articulos)

            total = 0;
            for (let i = 0; i < this.articulos.length; i++) {
                total += Number(this.articulos[i].cantidad) * Number(this.articulos[i].precio);
            }
            this.totalPrecio = total;
        }
    }
})

