function mostrarErrores(form, error){
    var errores=error;
    $('.has-error strong').remove();
    var arrKeys=Object.keys(errores);
    for (let index = 0; index < arrKeys.length; index++) {
        var indexName=arrKeys[index];
        console.log(indexName);
        if(index==0){
            $('#'+form+' [name='+indexName+']').focus().parents('span.bmd-form-group').addClass('has-error')
                .append("<div class=text-center><strong>"+errores[indexName]+"</strong></div>");
            }else{
                $('#'+form+' [name='+indexName+']').parents('span.bmd-form-group').addClass('has-error')
                .append("<div class=text-center><strong>"+errores[indexName]+"</strong></div>");
        }
    }
}
function limpiarErrores() {
    $('.has-error strong').remove();
}