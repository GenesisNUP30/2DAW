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
    }
})

