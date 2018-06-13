#MÓDULOS
Son archivos que contienen metodos y son intercambiables (exportables e importables) en nuestro proyecto

###Definición
- Para ES6 creamos el modulo de la siguiente manera:
 - file: module.js
        export default function(nombre){
        	alert(`Hola ${nombre}`)
        }
 - file: main.js
		import cualquier_nombre from './module.js'
        cualquier_nombre('Abel Obando')
		      
- Para es5 en node, sería de la siguiente manera:
 - file: module.js
		module.export = function(nombre){
        	alert(`Hola ${nombre}`)
        }
 - file: main.js
 		var cualquier_nombre = require('./module.js')
        cualquier_nombre('Abel Obando')
- Si no exportamos con la palabra "defaul" debemos ponerle nombre a la funcion; y al importar, para saber que metodo utilizar, debemos usar DESTRUCTORING para llamar al metodo:
 - file: module.js
        export function saludar(nombre){
        	alert(`Hola ${nombre}`)
        }
 - file: main.js
		import {saludar} from './module.js'
        saludar('Abel Obando')
- si tuvieramos más de un método en un módulo, ya sean exportados por defoult o con nombre, los trabajaremos de la siguiente manera:
 - file: module.js
        export function saludar(nombre){
        	alert(`Hola ${nombre})
        }
        export function despedirse(nombre){
        	alert(`Adios ${nombre}`)
        }
        export default function(nombre){
        	console.error(nombre)
        }
 - file: main.js
		import cualquier_nombre, {saludar,despedirse} from './module.js'
        saludar('Abel Obando')
        despedirse('Abel Obando')
        cualquier_nombre('Abel Obando')
- Si queremos exportar un objeto que contenga estos metodos, deberíamos instanciar con nombre a estos metodos y agregar su definicion en el objeto a exportar:
 - file: module.js
        function saludar(nombre){
        	alert(`Hola ${nombre})
        }
        function despedirse(nombre){
        	alert(`Adios ${nombre}`)
        }
        function elError(nombre){
        	console.error(nombre)
        }
        export default {
        	saludar: saludar,
            despedirse: despedirse,
            elError: elError
        }
 - file: main.js
		import funciones from './module.js'
		funciones.saludar('Abel Obando')   
        funciones.despedirse('Abel Obando') 
        funciones.elError('Abel Obando') 
- Sería correcto definir el objeto e instanciar/declarar los metodos dentro de ellas:
 - file: module.js
		export default {
        	function saludar(nombre){ alert(`Hola ${nombre}) }
        	function despedirse(nombre){ alert(`Adios ${nombre}`) }
        	function elError(nombre){ console.error(nombre) }
        }
