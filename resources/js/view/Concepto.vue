<template>
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header card-header-info">
                    Nuevo Concepto
                </div>
                <div class="card-body">
                    <form v-on:submit.prevent="guardar()" class="row">
                        <div class="col-12 form-group">
                            <label for="">Nombre de Concepto</label>
                            <input v-model="concepto.nombre" type="text" class="form-control">
                        </div>
                        <div class="col-12 form-group">
                            <label for="">Nombre de Concepto</label>
                            <select v-model="concepto.tipo" class="form-control">
                                <option value="Ingreso">Ingreso</option>
                                <option value="Egreso">Egreso</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </form>        
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header card-header-info">
                    Lista de Concepto
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre de Concepto</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(concepto,index) in conceptos">
                                <td>{{ index+1 }}</td>
                                <td>{{ concepto.nombre }}</td>
                                <td>{{ concepto.tipo }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            concepto:{
                nombre:null,
                tipo: null
            },
            conceptos: []
        }
    },
    mounted() {
        this.listar();  
    },
    methods: {
        listar(){
            axios.get(api_url+'/concepto').then(response=>{
                this.conceptos=response.data;
            });
        },
        guardar(){
            axios.post(api_url+'/concepto',this.concepto).then(response=>{
                if (response.data.status="OK") {
                    this.listar();
                    this.concepto={
                        nombre:null,
                        tipo: null
                    };
                    swal({title: "Concepto Registrado",icon: "success",timer: "2000"});
                }
            });
        },
    },
}
</script>