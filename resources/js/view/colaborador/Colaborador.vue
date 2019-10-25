<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-info">LISTA DE COLABORADORES</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <router-link class="btn btn-info" to="/colaborador/nuevo">
                                Agregar Colaborador
                            </router-link>
                            <!-- <button @click="abrir()" class="btn btn-info">
                            </button> -->
                            <button @click="abrirModalPuesto()" class="btn btn-info" >
                                Lista de Puestos
                            </button>
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="card card-profile">
                                <div class="card-header">
                                    asfssaf
                                </div>
                                <div class="card-avatar">
                                    <a href="#pablo">
                                        <img class="img" src="https://demos.creative-tim.com/material-dashboard/assets/img/faces/marc.jpg">
                                    </a>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-category text-gray">CEO / Co-Founder</h6>
                                    <h4 class="card-title">Alec Thompson</h4>
                                    <p class="card-description">
                                        Don't be scared of the truth because we need to restart the human foundation in truth And I love you like Kanye loves Kanye I love Rick Owens’ bed design but the back is...
                                    </p>
                                    <a href="#pablo" class="btn btn-primary btn-round">Follow</a>
                         
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="modal-puesto" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Lista de Puestos</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form v-on:submit.prevent="guardarPuesto()" class="row">
                                <div class="col-7">
                                    <input v-model="puesto.nombre" type="text" class="form-control" placeholder="Nombre de Puesto ..">
                                </div>
                                <div class="col-5">
                                    <button class="btn btn-primary" type="submit">Guardar</button>
                                </div>
                            </form>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><b>Nombre de Puesto</b></th>
                                        <th><b>Opciones</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(puesto,index) in puestos">
                                        <td v-if="puesto_index_select!=index">{{puesto.nombre}}</td>
                                        <td v-else><input type="text" v-model="puesto.nombre" class="form-control"></td>
                                        <td>
                                            <a v-if="puesto_index_select!=index" @click="seleccionarPuesto(index)" class="btn btn-link btn-success btn-sm">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a v-else @click="actualizarPuesto()" class="btn btn-link btn-primary btn-sm">
                                                <i class="material-icons">save</i>
                                            </a>
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
                            <h5 class="modal-title">Nuevo Colaborador</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form v-on:submit.prevent="guardarColaborador()">
                                <div class="form-group bmd-form-group">
                                    <label class="bmd-label-floating">DNI</label>
                                    <input v-model="colaborador.dni" type="text" class="form-control">
                                </div>
                                <!-- <div class="form-group">
                                    <label for="">DNI:</label>
                                    <input v-model="colaborador.dni" type="text" class="form-control">
                                </div> -->
                                <div class="form-group">
                                    <label for="">Nombre:</label>
                                    <input v-model="colaborador.nombre" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Apellido:</label>
                                    <input v-model="colaborador.apellido" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Celular:</label>
                                    <input v-model="colaborador.celular" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Salario:</label>
                                    <input v-model="colaborador.salario" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Día de Pago:</label>
                                    <input v-model="colaborador.dia_pago" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Puesto:</label>
                                    <select v-model="colaborador.puesto_id" type="text" class="form-control">
                                        <option v-for="puesto in puestos" :value="puesto.id">{{ puesto.nombre }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-info" type="submit">Guardar</button>
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
export default {
    data() {
        return {
            /**
             * Puestos
             */
            puestos: [],
            puesto:{
                nombre: ''
            },
            puesto_index_select: -1,
            /**
             * Colaborador
             */
            colaborador: {
                dni: "",
                nombre: "",
                apellido: "",
                celular: "",
                salario: "",
                dia_pago: "",
                puesto_id: "",
            },
            colaboradores:[],
        }
    },
    mounted() {
        this.listarColaboradores();
        this.listarPuestos();
    },
    methods: {
        /**
         * Puesto
         */
        abrirModalPuesto(){
            $('#modal-puesto').modal();
        },
        listarPuestos(){
            axios.get(api_url+'/puesto')
            .then(response=>{
                this.puestos=response.data;
            });
        },
        guardarPuesto(){
            axios.post(api_url+'/puesto',this.puesto)
            .then(response=>{
                var respuesta=response.data;
                this.puestos.push(respuesta.data);
                this.puesto={nombre:''};
                swal({
                    title: "Puesto Registrado",
                    icon: "success",
                    timer: "2000"
                });
            });
        },
        actualizarPuesto(){
            var puesto=this.puestos[this.puesto_index_select];
            axios.post(api_url+'/puesto/'+puesto.id+'?_method=PUT',puesto)
            .then(response=>{
                var respuesta=response.data;
                if (respuesta.status=="OK") {
                    this.puesto_index_select=-1;
                    swal({title: "Puesto Actualizado",icon: "success",timer: "2000"});
                }else{
                    swal({title: respuesta.data, icon: "error",timer: "3000"});
                }
            });
        },
        seleccionarPuesto(index){
            this.puesto_index_select=index;
        },
        /**
         * Colaborador
         */
        listarColaboradores(){
            axios.get(api_url+'/colaborador')
            .then(response=>{
                this.colaboradores=response.data;
            });
        },
        abrir(){
            $('#modal-nuevo').modal();
        },
        
        guardarColaborador(){
            axios.post(api_url+'/colaborador',this.colaborador)
            .then(response=>{
                var respuesta=response.data;
                this.colaboradores.push(respuesta.data);
                this.colaborador={
                    dni: "",
                    nombre: "",
                    apellido: "",
                    celular: "",
                    salario: "",
                    dia_pago: "",
                    puesto_id: "",
                };
                this.listarColaboradores();
                swal({
                    title: "Colaborador Registrado",
                    icon: "success",
                    timer: "2000"
                });
            });
        },
        

    },
}
</script>