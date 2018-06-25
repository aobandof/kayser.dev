##Definicion de Clase en ES5
		function Persona (nom, ape, anio) {
        	this nombre = nom
        	this apellido = ape
            this nacimiento = anio
            this nombreCompleto = function(){
            	return this.nombre + ' ' + this.apellido
            }
        }
        var abel = new Persona('Abel','Obando',1982)
- Si queremos añadir una propiedad a la clase:
		abel.nacionalidad = 'peruana'
- Si queremos agregar un metodo a la clase:
		abel.nacimiento = function(){
        	return "nacimiento en el año: " + this.anio
        }
- Añadir una propiedad al prototipo de la clase (añadiendo directamente a la definicion del prototipo de la clase): 
		Persona.prototype.muerte = 2080
- Añadir un metodo al prototipo de la clase:
		Persona.prototype.defuncion = function(){
        	return "Defunción en el año " + this.muerte
        } 
####** Cuando se definen prototipos como métodos, cuando se instancien objetos y se llamen a esos metodos, independiente del objeto, el metodo siempre ocupará el mismo espacio en memoria, permitiendo asi optimizar recursos 
- Por ejemplo, si tenemos 2 objetos: abel y joan:
		var abel = new Persona('Abel','Obando',1982)
    	var joan = new Persona('Joan','Monterrey',1993)
- y realizamos las siguientes comparaciones de funciones (ojo de funciones, y no de resultado de funciones)
		console.log(abel.nacimiento === joan.nacimiento) 
        //return false, ya que los metodos se instancias en distintos espacios
        console.log(abel.muerte === joan.muerte )
        //return true, ya que el metodo se instancia una vez para cualquier objeto
    