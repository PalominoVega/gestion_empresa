<template>
    <div class="wrapper wrapper-full-page">
    <div class="page-header register-page header-filter" filter-color="black" style="background-image: url('img/cover.jpeg'); background-size: cover; background-position: top center;">
      <div class="container">
        <div class="row">
          <div class="col-md-10 ml-auto mr-auto">
            <div class="card card-signup">
              <h2 class="card-title text-center">Registrar Empresa</h2>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-5 ml-auto">
                    <div class="info info-horizontal">
                      <div class="icon icon-rose">
                        <i class="material-icons">timeline</i>
                      </div>
                      <div class="description">
                        <h4 class="info-title">Estadisticas</h4>
                        <p class="description">
                            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Incidunt rerum laudantium, quam, molestias totam nulla qui assumenda animi eum tenetur suscipit, expedita adipisci iure inventore. Eveniet architecto molestiae aspernatur corporis.
                        </p>
                      </div>
                    </div>
                    <div class="info info-horizontal">
                      <div class="icon icon-primary">
                        <i class="material-icons">code</i>
                      </div>
                      <div class="description">
                        <h4 class="info-title">Almacen</h4>
                        <p class="description">
                          Lorem ipsum dolor sit, amet consectetur adipisicing elit. Illo fugit nemo quisquam dignissimos vel ab doloremque corporis nostrum molestias modi ex quidem architecto ratione expedita, veritatis eveniet laborum qui impedit.
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-5 mr-auto">
                    <div class="social text-center">
                      <!-- <button class="btn btn-just-icon btn-round btn-twitter">
                        <i class="fa fa-twitter"></i>
                      </button>
                      <button class="btn btn-just-icon btn-round btn-dribbble">
                        <i class="fa fa-dribbble"></i>
                      </button>
                      <button class="btn btn-just-icon btn-round btn-facebook">
                        <i class="fa fa-facebook"> </i>
                      </button> -->
                      <h4 class="mt-3"> Ingresar Datos para continuar </h4>
                    </div>
                    <form class="form" v-on:submit.prevent="registrar()" id="form-nuevo">
                        <div class="form-group has-default bmd-form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="material-icons">face</i>
                                    </span>
                                </div>
                                <input name="nombre" v-model="cuenta.nombre" type="text" class="form-control" placeholder="Nombre ...">
                            </div>
                        </div>
                        <div class="form-group has-default bmd-form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="material-icons">more</i>
                                    </span>
                                </div>
                                <input name="ruc" v-model="cuenta.ruc" type="text" class="form-control" placeholder="RUC ...">
                            </div>
                        </div>
                        <div class="form-group has-default bmd-form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="material-icons">work</i>
                                    </span>
                                </div>
                                <input name="nombre_empresa" v-model="cuenta.nombre_empresa" type="text" class="form-control" placeholder="Empresa ...">
                            </div>
                        </div>
                        <div class="form-group has-default bmd-form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="material-icons">phone_android</i>
                                    </span>
                                </div>
                                <input name="telefono" v-model="cuenta.telefono" type="text" class="form-control" placeholder="Teléfono ...">
                            </div>
                        </div>
                        <div class="form-group has-default bmd-form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="material-icons">mail</i>
                                    </span>
                                </div>
                                <input name="email" v-model="cuenta.email" type="text" class="form-control" placeholder="Email...">
                            </div>
                        </div>
                        <!-- <div class="form-group has-default bmd-form-group">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" value="" checked="">
                                    <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                                    I agree to the
                                    <a href="#something">terms and conditions</a>.
                                </label>
                            </div>
                        </div> -->
                        <div class="text-center">
                            <button  class="btn btn-info btn-round mt-4">INICIAR</button>
                        </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer">
        <div class="container">
          <nav class="float-left">
            <ul>
              <li>
                <a href="https://corporacionvespro.com">
                  Corporacion Vespro
                </a>
              </li>
            </ul>
          </nav>
          <div class="copyright float-right">
            ©
            2019, Gestión de Empresa.
          </div>
        </div>
      </footer>
    </div>
    
  </div>
</template>
<script>
export default {
    data() {
        return {
            cuenta:{
                nombre: null,
                nombre_empresa: null,
                ruc: null,
                email: null,
                telefono: null,
                tipo: 'demo' 
            }
        }
    },
    methods: {
        registrar(){
            axios.post(api_url+'/empresa',this.cuenta)
            .then(response=>{
                var respuesta=response.data;
                if (respuesta.status=="OK") {
                  swal({title: "Empresa Registrada",icon: "success",timer: "2000"});
                  this.$router.push({path: "/login"} );
                }
                if(respuesta.status=='VALIDATION'){
                    this.mostrarErrores('form-nuevo', respuesta.data);
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