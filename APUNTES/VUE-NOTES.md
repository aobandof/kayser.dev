#VUE NOTES

- ####Como se invocan las variables o funciones dentro de etiquetas o componentes
 - variable dentro de directiva
```
	<h2 v-if="show" > 
```
 - funcion dentro de evento (aca es opcional declarar los parametros, se pueden detallar en la definicion del metodo)
 ```
	<button @click="showFullName">
```
 - variable dentro de propiedad, pasada a componente (notose que se antepone ":" al nombre de la propiedad para que el valor de esta sea una variable y no texto)
 ```
	<emit-event :text_button="text_btn" />
```
 - variable dentro de propiedad key para identificar cada nodo iterativo (tambien usa ":" para indicar que el valor de la variable es otra variable))
 ```
	<li :key="index" />
```