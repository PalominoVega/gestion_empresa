import Vue from 'vue';
import VueRouter from 'vue-router'
import multiguard from 'vue-router-multiguard'
// import { Store } from 'vuex';

Vue.use(VueRouter)

var title=(to, from,next)=>{
    document.title=to.meta.title;
    next();
}
var auth=(to, from,next)=>{
    // thistore.cuenta;
    // console.log(store.state.cuenta);
    if(store.state.cuenta===null){
        next('/login');
    }else{
        var cuenta=JSON.parse(local.getItem("cuenta"));
        next(); 
    }
}
var landing_auth=(to, from,next)=>{
    if(local.getItem("cuenta")!==null){
        var cuenta=JSON.parse(local.getItem("cuenta"));
        next('/home');
    }else{
        next();
    }
}

var routes =[
    { 
        path: '/', 
        component: require('./view/Landing.vue').default,
        beforeEnter: landing_auth,
        meta:{
            layout: "empty",
        },
    },
    { 
        path: '/home', 
        component: require('./view/Home.vue').default,
        beforeEnter: auth
    },
    { 
        path: '/stock', 
        component: require('./view/Stock.vue').default,
        beforeEnter: auth
    },
    { 
        path: '/lote', 
        component: require('./view/Lote.vue').default,
        beforeEnter: auth        
    },
    { 
        path: '/kardex', 
        component: require('./view/Kardex.vue').default,
        beforeEnter: auth
    },
    { 
        path: '/reorden', 
        component: require('./view/Reorden.vue').default,
        beforeEnter: auth
    },
    { 
        path: '/salidas', 
        component: require('./view/Salidas.vue').default,
        beforeEnter: auth
    },
    { 
        path: '/compra', 
        component: require('./view/Compra.vue').default,
        beforeEnter: auth
    },
    { 
        path: '/lista-compra', 
        component: require('./view/ListaCompra.vue').default,
        beforeEnter: auth
    },
    { 
        path: '/venta', 
        component: require('./view/Venta.vue').default,
        beforeEnter: auth
    },
    { 
        path: '/lista-venta', 
        component: require('./view/ListaVenta.vue').default,
        beforeEnter: auth
    },
    { 
        path: '/cuadres-caja', 
        component: require('./view/CuadresCaja.vue').default,
        beforeEnter: auth
    },
    { 
        path: '/proveedor', 
        component: require('./view/Proveedor.vue').default,
        beforeEnter: auth
    },
    { 
        path: '/concepto', 
        component: require('./view/Concepto.vue').default,
        beforeEnter: auth
    },
    { 
        path: '/colaborador', 
        component: require('./view/colaborador/Colaborador.vue').default,
        beforeEnter: auth
    },
    { 
        path: '/colaborador/nuevo', 
        component: require('./view/colaborador/Nuevo.vue').default,
        beforeEnter: auth
    },
    { 
        path: '/producto', 
        component: require('./view/Producto.vue').default,
        beforeEnter: auth
    },
    { 
        path: '/notificacion', 
        component: require('./view/Notificacion.vue').default,
        beforeEnter: auth
    },
    { 
        path: '/usuario', 
        component: require('./view/Usuario.vue').default,
        beforeEnter: auth
    },
    { 
        path: '/puesto', 
        component: require('./view/Puesto.vue').default,
        beforeEnter: auth
    },
    { 
        path: '/contraseña_cambiar', 
        component: require('./view/CambiarPassword.vue').default,
        beforeEnter: auth
    },
    { 
        path: '/login', 
        component: require('./view/Login.vue').default,
        beforeEnter: landing_auth,
        meta:{
            layout: "empty",
        },
    },
    { 
        path: '/passwordReset', 
        component: require('./view/ResetPassword.vue').default,
        meta:{
            layout: "empty",
        },
    },
    { 
        path: '/passwordNew/:token', 
        component: require('./view/NewPassword.vue').default,
        meta:{
            layout: "empty",
        },
    },
    { 
        path: '/comprobanteventa/:id', 
        component: require('./view/comprobante/Venta.vue').default,
        meta:{
            layout: "empty",
        },
    },
    {
        path: '*',
        component: require('./view/404.vue').default,
        meta:{
            layout: "empty"
        }
    },
    { 
        path: '/ingreso', 
        component: require('./view/Ingreso.vue').default,
        beforeEnter: auth
    },
];
var router=new VueRouter({
    mode: 'history',
    routes,
    linkActiveClass: 'active',
    exact: true
});
export default router;