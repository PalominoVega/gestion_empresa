<template>
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
        
            <div class="card card-login">
            <div class="card-header card-header-info text-center">
                <h4 class="card-title">Cambiar contraseña</h4>
            </div>
            <form id="form-password-update" v-on:submit.prevent="cambiarpassword">
                <div class="card-body ">
                    <!-- <p class="card-description text-center">Or Be Classical</p> -->
                    <span class="bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="material-icons">lock_outline</i>
                                </span>
                            </div>
                            <input name="passwordactual" v-model="validacion.passwordactual" type="password" class="form-control" placeholder="Contraseña Actual...">
                        </div>
                    </span>
                    <span class="bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="material-icons">lock_outline</i>
                                </span>
                            </div>
                            <input name="passwordnuevo" v-model="validacion.passwordnuevo" type="password" class="form-control" placeholder="Contraseña Nuevo...">
                        </div>
                    </span>
                    <span class="bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="material-icons">lock_outline</i>
                                </span>
                            </div>
                            <input name="repasswordnuevo" v-model="validacion.repasswordnuevo" type="password" class="form-control" placeholder="Repetir Contraseña Nuevo...">
                        </div>
                    </span>
                </div>
                <div class="card-footer justify-content-center">
                        <button type="submit" href="#pablo" class="btn btn-info btn-link btn-lg">Continuar</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</template>
<script>
import { stringify } from 'querystring';
export default {
    data() {
        return {
            validacion:{
                passwordactual: null,
                passwordnuevo: null,
                repasswordnuevo: null,
            }
        }
    },
    methods: {
        cambiarpassword(){
            
            axios.post(api_url+'/contraseña?_method=PUT',this.validacion)
            .then(response=>{
                var respuesta=response.data;
                if(respuesta.status=="OK"){
                    limpiarErrores();
                    this.validacion={
                        passwordactual: '',
                        passwordnuevo: '',
                        repasswordnuevo: '',
                    }
                    swal({title: respuesta.data,icon: "success",timer: "2000"});
                }
                if (respuesta.status=="VALIDATION") {
                    mostrarErrores('form-password-update',respuesta.data)
                }
                if (respuesta.status=="WARNING") {
                    swal({
                        title: respuesta.data,
                        icon: "warning",
                        timer: "4000"
                    });
                }
                if (respuesta.status=="DANGER") {
                    swal({
                        title: respuesta.data,
                        icon: "warning",
                        timer: "4000"
                    });
                }
                if (respuesta.status=="ERROR") {
                    swal({
                        title: respuesta.data,
                        icon: "warning",
                        timer: "4000"
                    });
                }
            });
        },
        mostrarErrores(form, error){
            var errores=error;
            $('.has-error span').remove();
            var arrKeys=Object.keys(errores);
            for (let index = 0; index < arrKeys.length; index++) {
                var indexName=arrKeys[index];
                console.log(indexName);
                
                if(index==0){
                    $('#'+form+' [name='+indexName+']').focus().parents('div.form-group').addClass('has-error')
                        .append("<div class=text-center><span>"+errores[indexName]+"</span></div>");
                    }else{
                        $('#'+form+' [name='+indexName+']').parents('div.form-group').addClass('has-error')
                        .append("<div class=text-center><span>"+errores[indexName]+"</span></div>");
                }
            }
        },
        limpiarErrores() {
            $('.has-error span').remove();
        }
    },
}
</script>