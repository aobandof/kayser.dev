// **** FUNCIION GENERAL ****
//FUNCION QUE OBTIENE TODOS LOS HERMANOS, pasandode los siguientes parametros:
//  nodo actual: El nodo capturado, de quien hay que encontrar sus hijos
//  alcance:     Hasta que padres buscamos,
//  selector: es para limitar la busqueda, seleccionando solo las que los selectores indiquen
//  si alcance=0, buscaremos hasta los hermanos, si alcance=1 buscaremos hermanos y primos hermanos, alcance=2 buscaremos, hermanos, primos hermanos y primos lejanos; y asi sucesivamentesfunction getAllNodesEqualType(nodo, alcance, selector) {
function getAllNodesEqualType(nodo, alcance, selector) {
    let cousinsList = [];
    if (!selector || selector == '') {
        selector = nodo.tagName;
    }
    if (alcance == 0)
        cousins = nodo.parentNode.querySelectorAll(selector);
    else if (alcance == 1)
        cousins = nodo.parentNode.parentNode.querySelectorAll(selector);
    else if (alcance == 2)
        cousins = nodo.parentNode.parentNode.parentNode.querySelectorAll(selector);
    else
        cousins = nodo.parentNode.parentNode.parentNode.parentNode.querySelectorAll(selector);
    cousins.forEach(function (cous) {
        if (cous !== nodo)
            cousinsList.push(cous)
    })
    // console.log(cousinsList);
    return cousinsList;
}

function getSiblings(node,children) {
    siblingList = children;
    index = siblingList.indexOf(node);
    if (index != -1) {
        siblingList.splice(index, 1);
    }
    return siblingList;
}