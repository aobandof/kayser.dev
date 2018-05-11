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

- ####Directivas
 - **v-html**
 - **v-text**
 - **v-if**
 - **v-else**
 - **v-for**
 - **v-model** : Permite relacionar reactivamente una variable a un control html, de tal forma que cuando este cambie, la variable también cambiará.
 - **v-on** : Permite asignar un evento a un metodo, desde Vue 2, esta directiva se abrevia y solo usamos "@"
```
	<button v-on:click="my_method">Enviar</button>
    <button @click="my_method">Enviar</button>
```
 - **v-bind** : Permite vincular un atributo html, o propiedad de componente, a una variable declarada en nuestro modelo. Desde Vue 2 podemos abreviarla solo con ":"
```
	<button v-bind:disabled="my_variable">Enviar</button>
    <button :disabled="my_variable">Enviar</button>
```

- ####props
	Son variables que son pasadas al componente desde el exterior y son declaradas cuando se llama al componente, asignandole un valor directo o una variable (esto último con v-bind).

- ####$emit