class Persona {
    constructor(nombre, edad, genero) {
        this.nombre = nombre;
        this.edad = edad;
        this.genero = genero;
    }

    presentarse() {
        return `Hola, me llamo ${this.nombre}, tengo ${this.edad} años y soy ${this.genero}.`;
    }
}
let persona1 = new Persona("Juan", 25, "hombre");
let persona2 = new Persona("María", 30, "mujer");
let persona3 = new Persona("Alex", 20, "hombre");

console.log(persona1.presentarse());
console.log(persona2.presentarse());
console.log(persona3.presentarse());


