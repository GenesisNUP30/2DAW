new Vue ({
    el: '#aplicacion',
    data:{
        valorA: 0,
        valorB: 0,
    }, 
    methods: {
        suma:function(){
            return "A+B es: " + (this.valorA + this.valorB);
        },
        resta:function(){
            return "A-B es: " + (this.valorA - this.valorB);
        }

    }
})