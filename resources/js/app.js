/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.moment = require('moment');
import swal from 'sweetalert';
import vSelect from 'vue-select';
require('bootstrap4-notify');
import 'table2excel';
window.Table2Excel;
window.QRCode = require('qrcode');

Vue.component('v-select', vSelect)
import money from 'v-money'
Vue.use(money, {
    decimal: '.',
    thousands: ',',
    prefix: 'S/ ',
    precision: 2,
    masked: false
})

/**
 * INICIALIZAR FIREBASE
 */
var config = {
    apiKey: "AIzaSyA0K9tir8Bo0XrjgpM3VWx9uniIdxwGi1U",
    authDomain: "vespro-technology.firebaseapp.com",
    databaseURL: "https://vespro-technology.firebaseio.com",
    projectId: "vespro-technology",
    storageBucket: "vespro-technology.appspot.com",
    messagingSenderId: "105524483936"
};
firebase.initializeApp(config);
/**
 * Inicializar Vue y Router Vue
 */
var router = require('./router.js').default;
import App from './App.vue';
Vue.component('empty',require("./layouts/empty.vue").default);
Vue.component('panel',require("./layouts/panel.vue").default);

window.local=window.localStorage;
window.cuenta=(local.getItem("cuenta")===null) ? null: JSON.parse(local.getItem("cuenta"));

window.comun=new Vue();

/**
 * Vuex
 */
import Vuex from 'vuex'
Vue.use(Vuex)


window.store = new Vuex.Store({
  state: {
    cuenta: JSON.parse(localStorage.getItem('cuenta'))||null
  },
  mutations: {
    auth_success(state,cuenta){
      state.cuenta=cuenta;
      local.setItem('cuenta',JSON.stringify(state.cuenta));
      axios.defaults.headers.common['Authorization'] = state.cuenta.token;
    }
  },
  actions: {
    login({commit}, validacion){
      return new Promise((resolve, reject) => {
        axios.post(api_url+'/login',validacion)
        .then(response=>{
            limpiarErrores();
            var respuesta=response.data;
            if (respuesta.status=="VALIDATION") {
              mostrarErrores('form-login',respuesta.data)
            }
            if(respuesta.status=="OK"){
              console.log('hola2');
              commit('auth_success', respuesta.data);
              $.notify({ message: 'Bienvenido a Gestión de Empresa' },{placement: {from: "botoon",align: "right"},timer: 500000,type: 'info'});
            }
            if (respuesta.status=="WARNING") {
              swal({title: respuesta.data,icon: "warning",timer: "4000"});
             }
            resolve(response.data);
        }).catch(err => {
          console.log(err);
          reject(err)
        });
      })
    },
    logout({commit,state}){
      return new Promise((resolve,reject)=>{
        localStorage.removeItem('cuenta');
        delete axios.defaults.headers.common['Authorization']
        if(localStorage.getItem('push')!==null){
          var token=localStorage.getItem('push');
          localStorage.removeItem('push');
          $.ajax({        
            type : 'POST',
            url : "https://iid.googleapis.com/iid/v1:batchRemove",
            headers : {Authorization : 'key=AIzaSyAQLPsHYq2tKv-1T8LDao5epfcCoFEpZnA'},
            contentType : 'application/json',
            dataType: 'json',
            data: JSON.stringify({"to": "/topics/sistema-general-"+state.cuenta.id_empresa, "registration_tokens": [token]}),
            success : function(response) {
            }
          });
           
          navigator.serviceWorker.getRegistrations().then(function(registrations) {
          for(let registration of registrations) {
            registration.unregister()
          } })
          Notification.permissions = 'default';
          resolve();          
        }else{
          resolve();
        }
      })
    }
  }
});
if (store.state.cuenta!=null) {
  axios.defaults.headers.common['Authorization'] = store.state.cuenta.token;
}

import { mapMutations } from 'vuex'
const app = new Vue({
    el: '#app',
    store,
    router,
    mounted() {
      // this.obtenerToken();
      const messaging=firebase.messaging();    
      messaging.onMessage(function(payload) {
        comun.$emit('Notificacion', "nuevo");
        console.log("Mensaje Recibido");
      });
    },
    methods: {
      ...mapMutations(['obtenerToken'])
    },
    render: h => h(App)
});
