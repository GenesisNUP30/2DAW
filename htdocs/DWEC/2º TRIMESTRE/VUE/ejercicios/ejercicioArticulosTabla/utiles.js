var aplicacion = new Vue({
  el: "#aplicacion",
  data: {
    articulos: [],
    codigo: "",
    descripcion: "",
    cantidad: 0,
    precio: 0,
    totalPrecio: 0,
    botoneditar: false,
  },
  mounted: function () {
    this.cargarArticulos();
  },
  methods: {
    cargarArticulos() {
      fetch("php/listararticulos.php")
        .then(res => res.json())
        .then(data => {
          this.articulos = data;
          this.calculaTotal();
        });
    },
    calculaTotal: function () {
      let total = 0;
      for (let i = 0; i < this.articulos.length; i++) {
        total +=
          Number(this.articulos[i].cantidad) * Number(this.articulos[i].precio);
      }
      this.totalPrecio = total;
    },
    anade: function (event) {
      let articulo = {
        codigo: this.codigo,
        descripcion: this.descripcion,
        cantidad: this.cantidad,
        precio: this.precio,
      };

      fetch("php/insertararticulo.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(articulo),
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.status === "ok") {
            this.cargarArticulos();
            this.codigo = "";
            this.descripcion = "";
            this.cantidad = 0;
            this.precio = 0;
            console.log(this.articulos);
          }
        });
    },
    eliminaArticulo: function (articulo) {
      fetch("php/eliminararticulo.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ codigo: articulo.codigo }),
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === "ok") {
          this.cargarArticulos();
        }
      });
      
    },

    modificar: function (articulo) {
      console.log(articulo);
      this.codigo = articulo.codigo;
      this.descripcion = articulo.descripcion;
      this.cantidad = articulo.cantidad;
      this.precio = articulo.precio;
      this.botoneditar = true;
    },
    editar: function () {
      this.articulos.splice(
        this.articulos.findIndex((a) => a.codigo === this.codigo),
        1,
        {
          codigo: this.codigo,
          descripcion: this.descripcion,
          cantidad: this.cantidad,
          precio: this.precio,
        },
      );
      this.codigo = "";
      this.descripcion = "";
      this.cantidad = 0;
      this.precio = 0;
      console.log(this.articulos);

      this.calculaTotal();
      this.botoneditar = false;
    },
  },
});
