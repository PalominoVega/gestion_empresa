<template>
    <div class="wrapper wrapper-full-page">
        <div class="page-header login-page header-filter" filter-color="black" style="background-image: url('/img/cover.jpeg'); background-size: cover; background-position: top center;">
      <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
      <div class="container">
        <div class="row">
          <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
            
              <div class="card card-login">
                <div class="card-header card-header-info text-center">
                  <h4 class="card-title">Nueva contraseña</h4>
                  <div class="social-line">
                    <a href="#" class="btn btn-just-icon btn-link btn-white">
                      <i class="fa fa-facebook-square"></i>
                    </a>
                    <a href="#" class="btn btn-just-icon btn-link btn-white">
                      <i class="fa fa-twitter"></i>
                    </a>
                    <a href="#" class="btn btn-just-icon btn-link btn-white">
                      <i class="fa fa-google-plus"></i>
                    </a>
                  </div>
                </div>
                <form id="form-login" v-on:submit.prevent="newpassword">
                    <div class="card-body ">
                        <!-- <p class="card-description text-center">Or Be Classical</p> -->
                        <span class="bmd-form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                    <i class="material-icons">lock_outline</i>
                                    </span>
                                </div>
                                <input name="password" v-model="validacion.password" type="password" class="form-control" placeholder="Password...">
                            </div>
                        </span>
                        <span class="bmd-form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                    <i class="material-icons">lock_outline</i>
                                    </span>
                                </div>
                                <input name="repassword" v-model="validacion.repassword" type="password" class="form-control" placeholder="Password...">
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
                password: null,
                repassword: null,
            }
        }
    },
    methods: {
        newpassword(){
            axios.post(api_url+'/newPassword/'+this.$route.params.token+'?_method=PUT',this.validacion)
            .then(response=>{
                limpiarErrores();
                var respuesta=response.data;
                if(respuesta.status=="OK"){
                    cuenta=respuesta.data;
                    local.setItem('cuenta',JSON.stringify(respuesta.data));
                    this.$router.push({path: "/home"} );
                    $.notify({
                        message: 'Bienvenido a Gestión de Empresa' 
                    },{
                        placement: {
                            from: "botoon",
                            align: "right"
                        },
                        timer: 500,
                        type: 'info'
                    });
                }
                if (respuesta.status=="VALIDATION") {
                    mostrarErrores('form-login',respuesta.data)
                }
                if (respuesta.status=="WARNING") {
                    swal({
                        title: respuesta.data,
                        icon: "warning",
                        timer: "4000"
                    });
                }
            });
        }
    },
}
</script>