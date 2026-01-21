var aplicacion = new Vue({
    el: '#aplicacion',
    data: {
        noticias: []
    },
    mounted: function () {
        fetch('https://jsonplaceholder.typicode.com/posts')
            .then(function (response) {
                return response.json()
            })
            .then (function (data) {
                aplicacion.noticias = data
            })
            .catch(function (error) {
                console.error('Error al cargar las noticias:', error)
            })
    }
})