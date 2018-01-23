use kayser_articulos;

select * from lista;


describe articulo;


select prenda_name, prenda_code, talla_familia from articulo where talla_familia='';

select lista_id, codigo, prenda_name, prenda_code, talla_familia, existencia from articulo where talla_familia='';


select lista_id, codigo, prenda_name, prenda_code, talla_familia, existencia from articulo where existencia='sap';


update articulo set prenda_code='25', talla_familia='T05' WHERE existencia='sap' and talla_familia='' and prenda_name='SOSTEN'
update articulo set prenda_code='09', talla_familia='T05' WHERE existencia='sap' and talla_familia='' and prenda_name='CALZON'


SELECT S.codigo as cod_sku, S.barcode, A.caracteristica_name, A.itemname, A.dpto_code, A.prenda_name, A.codigo as cod_articulo, S.color_name, S.talla_name, A.talla_familia, A.prenda_code, S.talla_orden, A.marca_name, A.tprenda_name, A.material_name, A.grupouso_name, A.subdpto_name, A.composicion_name, A.categoria_code, S.copa, A.presentacion_name, A.tcatalogo_name, S.fcopa FROM sku as S INNER JOIN articulo as A ON S.articulo_codigo=A.codigo WHERE A.lista_id=19 ORDER BY S.barcode ASC