<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-info" style="margin-right: auto;">
                    LISTA DE USUARIOS
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <button @click="abrir()" class="btn btn-danger">
                                Agregar Usuario
                            </button>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>DNI</th>
                                        <th>NOMBRE DE USUARIO</th>
                                        <th>NUMERO</th>
                                        <th>E-MAIL</th>
                                        <th>ESTADO</th>
                                        <th>ROL</th>
                                        <th>EDITAR</th>
                                        <th>ESTADO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in usuarios">
                                        <td>{{item.dni}}</td>
                                        <td>{{item.nombre}} {{item.apellido}}</td>
                                        <td>{{item.numero}}</td>
                                        <td>{{item.email}}</td>
                                        <td  v-if="item.estado=='0'">Activo</td>
                                        <td  v-if="item.estado=='1'">Inactivo</td>
                                        <td>{{item.rol}}</td>
                                        <td>
                                            <button @click="abrirEditar(item.id)" type="button" class="btn btn-warning btn-link btn-sm">
                                                <i class="material-icons">edit</i>
                                            </button>
                                        </td>
                                        <td>
                                            <button v-if="item.estado=='0'" @click="cambiarEstado(item.id)" type="button" class="btn btn-success btn-link btn-sm">
                                                <i class="material-icons">
                                                    radio_button_checked
                                                </i>
                                            </button>
                                            <button v-if="item.estado=='1'" @click="cambiarEstado(item.id)" type="button" class="btn btn-default btn-link btn-sm">
                                                <i class="material-icons">
                                                    radio_button_unchecked
                                                </i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="modal-nuevo" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Nuevo Usuario</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="form-nuevo" v-on:submit.prevent="guardar">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="">DNI:</label>
                                    <input name="dni" v-on:keyup="consulta()" v-model="usuario.dni" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Nombre de usuario:</label>
                                    <input name="nombre" v-model="usuario.nombre" type="text" class="form-control" :readonly="bloquear">
                                </div>
                                <div class="form-group">
                                    <label for="">Apellidos de usuario:</label>
                                    <input name="apellido" v-model="usuario.apellido" type="text" class="form-control" :readonly="bloquear">
                                </div>
                                <div class="form-group">
                                    <label for="">Número:</label>
                                    <input name="numero" v-model="usuario.numero" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">E-Mail:</label>
                                    <input name="email" v-model="usuario.email" type="email" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Rol:</label>
                                    <select name="rol" v-model="usuario.rol" id="" class="form-control">
                                        <option value="Administrador" selected>Administrador</option>
                                        <option value="Operador">Operador</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Contraseña:</label>
                                    <input name="password" v-model="usuario.password" type="password" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" @click="guardar()" :disabled="btn_bloquear">Guardar</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="modal-editar" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Usuario</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="form-editar" v-on:submit.prevent="update">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="">DNI:</label>
                                    <input name="dni" v-model="usuario_edit.dni" type="text" class="form-control" :readonly=true>
                                </div>
                                <div class="form-group">
                                    <label for="">Nombre de usuario:</label>
                                    <input name="nombre" v-model="usuario_edit.nombre" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Apellidos de usuario:</label>
                                    <input name="apellido" v-model="usuario_edit.apellido" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Número:</label>
                                    <input name="numero" v-model="usuario_edit.numero" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">E-Mail:</label>
                                    <input name="email" v-model="usuario_edit.email" type="email" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Rol:</label>
                                    <select name="rol" v-model="usuario_edit.rol"  class="form-control">
                                        <option value="Administrador" :selected="usuario_edit.rol=='Administrador'" >Administrador</option>
                                        <option value="Operador" :selected="usuario_edit.rol=='Operador'" >Operador</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" @click="update()" :disabled="btn_bloquear">Guardar</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            /**
             * Bloquear campo de nombre y apellidos
             */
            bloquear: true,
            /**
             * bloquear el btn guardar
             */
            btn_bloquear:false,
            // estado_cliente:"",

            usuario: {
                dni:"",
                nombre:"",
                apellido:"",
                numero:"",
                email:"",
                rol:"",
                password:"",
            },
            usuario_edit: {
                dni:"",
                nombre:"",
                apellido:"",
                numero:"",
                email:"",
                rol:"",
            },
            usuarios:[]
        }
    },
    mounted() {
        /* axios.get(api_url+'/usuario?api_token='+cuenta.token+'&all=true')
        .then(response=>{
            this.usuarios=response.data;
        }); */
        this.listar();
    },
    methods: {
        listar(){
            axios.get(api_url+'/usuario?all=true')
            .then(response=>{
                this.usuarios=response.data;
            });
        },
        abrir(){
            $('#modal-nuevo').modal();
        },
        consulta(){
            if (this.usuario.dni.length==8) {
                    axios.get(api_url+'/usuario/consulta?dni='+this.usuario.dni)
                    .then(response=>{
                        var resultado=response.data;
                        if(resultado.length!=undefined){
                            this.bloquear=false;                        
                            this.usuario.nombre="";
                            this.usuario.apellido="";
                        }else{
                            this.usuario.nombre=resultado.nombres;
                            this.usuario.apellido=resultado.apellidoPaterno+' '+resultado.apellidoMaterno;
                            this.bloquear=true;                        
                        }
                    });
            }else{
                // this.bloquear=false;                        
                this.usuario.nombre="";
                this.usuario.apellido="";
            }
        },
        guardar(){
            this.btn_bloquear=true,
            axios.post(api_url+'/usuario?data=true',this.usuario)
            .then(response=>{
                var respuesta=response.data;
                if(respuesta.status=='OK'){
                    this.limpiarErrores();
                    this.usuarios.push(respuesta.data);
                    this.usuario={
                        dni:"",
                        nombre:"",
                        apellido:"",
                        numero:"",
                        email:"",
                        rol:"",
                        password:"",
                    };
                    swal({
                        title: "Usuario Registrado",
                        icon: "success",
                        timer: "2000"
                    });
                }
                if(respuesta.status=='VALIDATION'){
                    this.mostrarErrores('form-nuevo', respuesta.data);
                }
                if(respuesta.status=='DANGER'){
                    swal({title: respuesta.data,icon: "error",timer: "4000"});
                }
                this.btn_bloquear=false;
            });
        },
        abrirEditar(id){
            axios.get(api_url+'/usuario/'+id)
            .then(response=>{
                this.usuario_edit=response.data;
            });
            $('#modal-editar').modal();
        },
        update(){
            this.btn_bloquear=false;

            axios.post(api_url+'/usuario/'+this.usuario_edit.id+'?_method=PUT',this.usuario_edit)
            .then(response=>{
                this.error_editar=null;
                var respuesta=response.data;
                if (respuesta.status=="OK") {
                    this.limpiarErrores();
                    this.listar();
                    $('#modal-editar').modal('hide');
                    swal({title: "Usuario Actualizado",icon: "success",timer: "2000"});
                }
                if (respuesta.status=="VALIDATION") {
                    this.mostrarErrores('form-editar', respuesta.data)
                }
                if(respuesta.status=='DANGER'){
                    swal({title: respuesta.data,icon: "error",timer: "4000"});
                }
                this.btn_bloquear=false;
            });
        },
        cambiarEstado(id){
            axios.post(api_url+'/usuario/estado/'+id+'?_method=PUT')
            .then(response=>{
                
                var respuesta=response.data;
                if (respuesta.status=="OK") {
                    this.limpiarErrores();
                    this.listar();

                    swal({title: respuesta.data,icon: "success",timer: "2000"});
                }
                if(respuesta.status=='DANGER'){
                    swal({title: respuesta.data,icon: "error",timer: "4000"});
                }
            });
        },
        mostrarErrores(form, error){
            var errores=error;
            $('.has-error span').remove();
            var arrKeys=Object.keys(errores);
            for (let index = 0; index < arrKeys.length; index++) {
                var indexName=arrKeys[index];
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