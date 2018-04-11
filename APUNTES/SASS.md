# CURSO DE SASS
- Existen dos formas de trabajar con sass, que conllevan a 2 tipos de archivos: SASS Y SCSS
- Para poder compilar una archivo SASS o SCSS usamos una gema de Ruby (hay que tener instalado ruby)
```
gem sass [name_file].sass:[compiled_file_name].css
gem sass [name_file].scss:[compiled_file_name].css
```
- Para compilar, pero seguir compilando automáticamente cuando se modifique el archivo sass
```
gem sass --watch [name_file].scss:[compiled_file_name].css
```
- Por ahora nos centraremos en trabajar con archivos con extension SASS.

******
###Variables
>$[nombre_variable] : [valor_css]
    
###Funciones propias de SASS
- **darken([variable,porcentaje_opacidad)**
>darken($colorPrincipal,30%)

###Mixins (funciones)
>=[name_mixin] (var_1, var_2, ... var_n)
> >[propiedad1] : var_1
> >[propiedad2] : var_2
> >...
> >[propiedadn] : var_n

Ejemplo:
```
=botones($padding, $radius, $bg, $color)
    padding: $padding
    border-radius: $radius
    -webkit-webkit-border-raius: $radius
    -moz-webkit-border-radius
    background: $bg
    color: $color    
```
LLamamos a la funcion:
```
.myBtn
    +botones(1rem, 10p, #666fff, blue)
```


