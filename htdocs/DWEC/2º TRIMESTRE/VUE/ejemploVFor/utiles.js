new Vue ({
    el: '#aplicacion',
    data: {
        items:["Naruto", "Dragon ball", "Pokemon"],
        salida: ""
    },
    methods: {
        mostrarValor:function(event){
            if(event){
                this.salida=event.target.innerText;
            }
        }
    }
})