var aplicacion = new Vue ({
    el: '#aplicacion',
    data: {
        productos: []
    },
    mounted: function () {
        fetch('https://fakestoreapi.com/products')
            .then(function (response) {
                return response.json()
            })
            .then(function (data) {
                aplicacion.productos = data
            })
            .catch(function (error) {
                console.error('Error al cargar los productos:', error)
            })
    },
    methods: {
        // borrarProducto: function (index) {
        //     this.productos.splice(index, 1)
        // }
        borrarProducto: function (index) {
            fetch('https://fakestoreapi.com/products', {
                method: 'DELETE',
                
            })
        }
    }
})

