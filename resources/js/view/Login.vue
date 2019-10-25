<template>
    <div class="wrapper wrapper-full-page">
        <div class="page-header login-page header-filter" filter-color="black" style="background-image: url('img/cover.jpeg'); background-size: cover; background-position: top center;">
      <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
      <div class="container">
        <div class="row">
          <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
            
              <div class="card card-login">
                <div class="card-header card-header-info text-center">
                  <h4 class="card-title">Login</h4>
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
                <form id="form-login" v-on:submit.prevent="ingresar">
                    <div class="card-body ">
                        <!-- <p class="card-description text-center">Or Be Classical</p> -->
                        <span class="bmd-form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                    <i class="material-icons">email</i>
                                    </span>
                                </div>
                                <input name="email" v-model="validacion.email" type="email" class="form-control" placeholder="Email...">
                            </div>
                        </span>
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
                        <div class="text-center">
                            <button style="font-size: 16px" type="submit" href="#pablo" class="btn btn-info btn-link">Continuar</button>
                            <router-link to="/passwordReset" style="font-size: 10px" class="btn btn-default btn-link btn-sm">
                                Olvide mi contraseña
                            </router-link>
                        </div>
                    </div>
                </form>
                <!-- <div class="card"> -->
                <!-- </div> -->
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
                email: null,
                password: null     
            }
        }
    },
    methods: {
        ingresar(){
            this.$store.dispatch("login",this.validacion).then(response => {
                console.log(response.status);
                
                if(response.status=="OK"){
                    console.log("hola");
                    this.$router.push("home");
                }
            });
        }
    },
}
</script>