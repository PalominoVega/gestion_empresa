<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-info">
                    LISTA DE PROVEEDORES
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <button @click="abrir()" class="btn btn-danger">
                                Agregar Proveedor
                            </button>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>RUC</th>
                                        <th>NOMBRE DE PROVEEDOR</th>
                                        <th>NUMERO</th>
                                        <th>E-MAIL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in proveedores">
                                        <td>{{item.ruc}}</td>
                                        <td>{{item.nombre}}</td>
                                        <td>{{item.numero}}</td>
                                        <td>{{item.email}}</td>
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
                        <h5 class="modal-title">Nuevo Proveedor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">RUC:</label>
                            <input v-model="proveedor.ruc" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Nombre de Proveedor:</label>
                            <input v-model="proveedor.nombre" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Número:</label>
                            <input v-model="proveedor.numero" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">E-Mail:</label>
                            <input v-model="proveedor.email" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="guardar()">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
            proveedor: {
                ruc: "",
                nombre: "",
                numero: "",
                email: "",
            },
            proveedores:[]
        }
    },
    mounted() {
        axios.get(api_url+'/proveedor')
        .then(response=>{
            this.proveedores=response.data;
        });
    },
    methods: {
        abrir(){
            $('#modal-nuevo').modal();
        },
        guardar(){
            axios.post(api_url+'/proveedor?data=true',this.proveedor)
            .then(response=>{
                var respuesta=response.data;
                this.proveedores.push(respuesta.data);
                this.proveedor={
                    ruc: "",
                    nombre: "",
                    numero: "",
                    email: "",
                };
                swal({
                    title: "Proveedor Registrado",
                    icon: "success",
                    timer: "2000"
                });
            });
        }

    },
}
</script>