<template>
    <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
            <div class="navbar-wrapper">
                <a class="navbar-brand"></a>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                <span class="sr-only">Toggle navigation</span>
                <span class="navbar-toggler-icon icon-bar"></span>
                <span class="navbar-toggler-icon icon-bar"></span>
                <span class="navbar-toggler-icon icon-bar"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end">
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a @click="actualizarNotificaciones()" class="nav-link" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">notifications</i>
                            <span class="notification" v-if="conteoSinLeer!=0">{{ conteoSinLeer }}</span>
                            <p class="d-lg-none d-md-block">
                                Some Actions
                            </p>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                            <div v-for="notificacion in notificaciones">
                                <router-link v-if="notificacion.tipo=='Stock'"  to="reorden"  class="dropdown-item">
                                    {{ notificacion.descripcion }}
                                </router-link>
                                <router-link v-else-if="notificacion.tipo=='Vencimiento'"  to="lote"  class="dropdown-item">
                                    {{ notificacion.descripcion }}
                                </router-link>
                            </div>
                            <div class="dropdown-divider"></div>
                            <router-link class="dropdown-item text-center" to="notificacion">
                                Ver más
                            </router-link>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ cuenta.nombre }}
                            <i class="material-icons">person</i>
                            <p class="d-lg-none d-md-block">
                                Account
                            </p>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                       
                            <a class="dropdown-item" @click="salir()">Log out</a>
                            <router-link tag="li" class="nav-item"  to="/contraseña_cambiar">
                                <a class="dropdown-item" >Cambiar contraseña</a>
                            </router-link>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    

</template>
<script>
import { mapState } from 'vuex'
import { mapActions } from 'vuex'

export default {
    data() {
        return {
            // cuenta: cuenta,
            notificaciones: [], 
            abrir: false,
        }
    },
    computed: {
        ...mapState(['cuenta']),
        conteoSinLeer(){            
            var conteo=0
            for (let i = 0; i < this.notificaciones.length; i++) {
                const notificacion = this.notificaciones[i];
                if (notificacion.estado==0) {
                    conteo++;
                }
            }
            return conteo;
        }
    },
    // computed: ,
    mounted() {
        this.listarNotificaciones();
        var t=this;
        comun.$on('Notificacion', function (value) {
            t.listarNotificaciones();
        })
    },
    methods: {
        ...mapActions(['logout']),
        actualizarNotificaciones(){
            axios.post(api_url+'/notificacion')
            .then(response=>{
                this.listarNotificaciones();
            });
        },
        listarNotificaciones(){
            axios.get(api_url+'/notificacion')
            .then(response=>{
                this.notificaciones=response.data;
            });
        },
        salir(){
            this.$store.dispatch("logout").then(() => {
                this.$router.push("login");
            });
        },
        open(){
    		this.abrir=true;
    	},
    },
}
</script>

