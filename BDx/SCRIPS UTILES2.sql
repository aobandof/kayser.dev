use kayser_articulos;

SELECT S.codigo as cod_sku, S.barcode, A.caracteristica_name, A.itemname, A.dpto_code, A.prenda_name, A.codigo as cod_articulo,
S.color_name, S.talla_name, A.talla_familia, A.prenda_code, S.talla_orden, A.marca_name, A.tprenda_name, A.material_name, A.grupouso_name,
A.subdpto_name, A.composicion_name, A.categoria_code, S.copa, A.presentacion_name, A.tcatalogo_name, S.fcopa FROM sku as S
INNER JOIN articulo as A ON S.articulo_codigo=A.codigo
WHERE A.lista_id=1
ORDER BY S.barcode ASC;

SELECT S.codigo as cod_sku, A.codigo as cod_articulo FROM sku as S INNER JOIN articulo as A WHERE  A.lista_id=1;

SELECT S.codigo as cod_sku, S.barcode, A.caracteristica_name, A.itemname, A.dpto_code, A.prenda_name, A.codigo as cod_articulo, 
S.color_name, S.talla_name, A.talla_familia, A.prenda_code, S.talla_orden, A.marca_name, A.tprenda_name, A.material_name, A.grupouso_name, 
A.subdpto_name, A.composicion_name, A.categoria_code, S.copa, A.presentacion_name, A.tcatalogo_name, S.fcopa FROM sku as S 
INNER JOIN articulo as A ON S.articulo_codigo=A.codigo WHERE A.lista_id=1 ORDER BY S.barcode ASC
select * from color;


select * from lista;
select * from articulo; 

describe articulo;
describe sku;