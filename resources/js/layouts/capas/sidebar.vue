<template>
    <!-- <div class="sidebar" data-color="azure" data-background-color="black" data-image="https://demos.creative-tim.com/material-dashboard/assets/img/sidebar-4.jpg">
        <div class="sidebar-background" style="background-image: url(https://demos.creative-tim.com/material-dashboard/assets/img/sidebar-4.jpg) "></div>
        <div class="logo">
            <router-link to="/" class="simple-text logo-normal">
                Sistema
            </router-link>
        </div>
        <div class="profile">
            <ul class="nav">
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ cuenta.nombre }}
                        <i class="material-icons">person</i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                    
                        <a class="dropdown-item close-nav" @click="salir()">Log out</a>
                        <router-link tag="li" class="nav-item"  to="/contraseña_cambiar">
                            <a class="dropdown-item close-nav">Cambiar contraseña</a>
                        </router-link>
                    </div>
                </li>
                <router-link tag="li" class="nav-item"  to="/notificaciones">
                    <a class="nav-link close-nav">
                        <i class="material-icons">notifications</i>
                        NOTIFICACIONES
                        <span class="notification">5</span>
                    </a>
                </router-link>
            </ul>
        </div>
        <div class="sidebar-wrapper">
            <ul class="nav">
                <router-link tag="li" class="nav-item"  to="/home" exact>
                    <a class="nav-link close-nav">
                        <i class="material-icons">dashboard</i>   
                        <p>Panel</p>
                    </a>
                </router-link>
                <router-link tag="li" class="nav-item"  to="/compra">
                    <a class="nav-link close-nav">
                        <i class="material-icons">library_books</i>
                        <p>Compra</p>
                    </a>
                </router-link>
                <router-link tag="li" class="nav-item"  to="/venta">
                    <a class="nav-link close-nav">
                        <i class="material-icons">library_books</i>
                        <p>Venta</p>
                    </a>
                </router-link>
                <router-link tag="li" class="nav-item"  to="/concepto">
                    <a class="nav-link close-nav">
                        <i class="material-icons">library_books</i>
                        <p>Concepto</p>
                    </a>
                </router-link>
                <router-link tag="li" class="nav-item"  to="/producto">
                    <a class="nav-link close-nav">
                        <i class="material-icons">
                            work_outline
                        </i>
                        <p>Producto</p>
                    </a>
                </router-link>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#componentInformes" aria-expanded="false">
                        <i class="material-icons">content_paste</i>                                
                        <p> Informes
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="componentInformes">
                        <ul class="nav">
                            <router-link tag="li" class="nav-item"  to="/lista-venta">
                                <a class="nav-link close-nav">
                                    <p>lista de Ventas</p>
                                </a>
                            </router-link>
                            <router-link tag="li" class="nav-item"  to="/lista-compra">
                                <a class="nav-link close-nav">
                                    <p>lista de compras</p>
                                </a>
                            </router-link>
                           
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#componentInventario" aria-expanded="false">
                        <i class="material-icons">content_paste</i>                                
                        <p> Inventario
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="componentInventario">
                        <ul class="nav">
                            <router-link tag="li" class="nav-item"  to="/stock">
                                <a class="nav-link close-nav">
                                    <p>Stock</p>
                                </a>
                            </router-link>
                            <router-link tag="li" class="nav-item"  to="/lote">
                                <a class="nav-link close-nav">
                                    <p>Lote</p>
                                </a>
                            </router-link>
                            <router-link tag="li" class="nav-item"  to="/kardex">
                                <a class="nav-link close-nav">
                                    <p>Kardex</p>
                                </a>
                            </router-link>
                            <router-link tag="li" class="nav-item"  to="/reorden">
                                <a class="nav-link close-nav">
                                    <p>Bajo Stock</p>
                                </a>
                            </router-link>
                            <router-link tag="li" class="nav-item"  to="/salidas">
                                <a class="nav-link close-nav">
                                    <p>Rotacion de Productos</p>
                                </a>
                            </router-link>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#componentContactos" aria-expanded="false">
                        <i class="material-icons">person</i>
                        <p> Contactos
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="componentContactos">
                        <ul class="nav">
                            <router-link tag="li" class="nav-item"  to="/usuario">
                                <a class="nav-link close-nav">
                                    <p>Usuarios</p>
                                </a>
                            </router-link>
                            <router-link tag="li" class="nav-item"  to="/proveedor">
                                <a class="nav-link close-nav">
                                    <p>Proveedores</p>
                                </a>
                            </router-link>
                            
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div> -->
    <div>
        <div class="sidebar" :class="{'open':(abrir)}">
            <div class="sidebar-header">
                <a class="sidebar-close" @click="close()">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </a>
                <h3 class="text-center my-3">SISTEMAS</h3>
            </div>
            <div class="sidebar-body">
                <ul>
                    <!-- <li class="active"> -->
                        <router-link tag="li" to="/home" exact>
                            <!-- <a class="nav-link close-nav"> -->
                        <a href=""><i class="material-icons">dashboard</i>Panel</a>
                        </router-link>
                    <!-- </li>  -->
                    <router-link tag="li" to="/compra">
                        <a href=""><i class="material-icons">library_books</i></i>Compra</a>
                    </router-link>
                    
                    <router-link tag="li"  to="/venta">
                        <a><i class="material-icons">shopping_cart</i> Venta</a>
                    </router-link>
                    <router-link tag="li"   to="/concepto">
                        <a><i class="material-icons">library_books</i>Concepto</a>
                    </router-link>
                    <router-link tag="li"   to="/producto">
                        <a ><i class="material-icons">work_outline</i>Producto</a>
                    </router-link>
                    <!-- <li>
                        <a href=""><i class="fa fa-calendar-o" aria-hidden="true"></i>Agenda</a>
                    </li>  -->
                    <li >
                        <a  data-toggle="collapse" href="#componentInformes" aria-expanded="false">
                            <i class="material-icons">content_paste</i> Informes <i class="material-icons icon-rigth">keyboard_arrow_down</i>                             
                        </a>
                        <div class="collapse" id="componentInformes">
                            <ul >
                                <router-link tag="li"   to="/lista-venta">
                                    <a>lista de Ventas</a>
                                </router-link>
                                <router-link tag="li"   to="/lista-compra">
                                    <a>lista de compras</a>
                                </router-link>
                            </ul>
                        </div>
                    </li>
                    <li >
                        <a  data-toggle="collapse" href="#componentInventario" aria-expanded="false">
                            <i class="material-icons">content_paste</i>  Inventario <i class="material-icons icon-rigth">keyboard_arrow_down</i></a>
                        <div class="collapse" id="componentInventario">
                            <ul >
                                <router-link tag="li"   to="/stock">
                                    <a>Stock </a>
                                </router-link>
                                <router-link tag="li"   to="/lote">
                                    <a>Lote </a>
                                </router-link>
                                <router-link tag="li"   to="/kardex">
                                    <a>Kardex </a>
                                </router-link>
                                <router-link tag="li"   to="/reorden">
                                    <a>Bajo Stock </a>
                                </router-link>
                                <router-link tag="li"   to="/salidas">
                                    <a>Rotacion de Productos </a>
                                </router-link>
                            </ul>
                        </div>
                    </li>
                    <li >
                        <a  data-toggle="collapse" href="#componentContactos" aria-expanded="false">
                            <i class="material-icons">person</i>Contactos <i class="material-icons icon-rigth">keyboard_arrow_down</i></a>
                        <div class="collapse" id="componentContactos">
                            <ul >
                                <router-link tag="li"   to="/usuario">
                                    <a >Usuarios</a>
                                </router-link>
                                <router-link tag="li"   to="/proveedor">
                                    <a >Proveedores</a>
                                </router-link>
                                
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="show-sidebar" :class="{'open':(abrir)}" @click="close()">
        </div>
    </div>
</template>
<script>
import { mapState } from 'vuex'

export default {
    data() {
        return {
            abrir: false,
        }
    },
    mounted() {
        // NUEVO DISEÑO
        var style=document.createElement("style");
        style.setAttribute("id",'dis-responsive');
        document.body.appendChild(style);
    },

    computed: {
        ...mapState(['cuenta']),
    },
    methods: {
        salir(){
            this.$store.dispatch("logout").then(() => {
                this.$router.push("login");
            });
        },
        close(){
    		this.abrir=false;
    	},
    },
}
</script>