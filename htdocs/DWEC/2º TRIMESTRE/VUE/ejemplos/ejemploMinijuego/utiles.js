new Vue({
    el: '#aplicacion',
    data: {
        jugador1: 0,
        jugador2: 0,
        ganador: "--",
        contadorjugador1: 0,
        contadorjugador2: 0,
        contadorempate: 0,
        oportunidades:0
    },
    methods: {
        reiniciar: function(event) {
            this.oportunidades = 0;
        },

        jugar: function (event) {
            this.jugador1 = Math.floor(Math.random() * 10);
            this.jugador2 = Math.floor(Math.random() * 10);

            var mensaje = "¡Hay un empate!";
            this.contadorempate++;

            if ((this.jugador1 > this.jugador2)) {
                mensaje = "¡Gana el jugador 1!";
                this.contadorjugador1++;
            } else if (this.jugador1 != this.jugador2) {
                mensaje = "¡Gana el jugador 2!";
                this.contadorjugador2++;
            }
            this.oportunidades++;
            this.ganador = mensaje;
        }
    }
})