<template>
    <div>
        <div class="card">
            <div class="card-header card-header-info">
                Lista de Ventas
            </div> 
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form v-on:submit.prevent="listar" class="row">
                            <div class="col-sm-3">
                                <input type="text" v-model="busqueda.nombre_dni" class="form-control" placeholder="Escribe Nombre o Documento de identidad"> 
                            </div>
                            <div class="col-sm-1">
                                <button type="submit" class="btn btn-primary">
                                    <i class="material-icons">search</i>
                                </button>
                            </div>
                        </form>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>FECHA</th>
                                    <th>COMPROBANTE</th>
                                    <th>DOC. CLIENTE</th>
                                    <th>CLIENTE</th>
                                    <th>USUARIO</th>
                                    <th>TOTAL</th>
                                    <th>Facturacion</th>
                                    <th>ANULAR</th>
                                    <th>ANULAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in ventas">
                                    <td>{{ item.created_at }}</td>
                                    <td>{{ item.serie +'-'+ item.correlativo}}</td>
                                    <td>{{ (item.nombre==null)? "000000000" : item.documento }}</td>
                                    <td>{{ (item.nombre==null)? "Cliente Anonimo" : item.nombre }}</td>
                                    <td>{{ (item.user==null)? "" : item.user.nombre }}</td>
                                    <td>S/ {{ item.total.toFixed(2) }}</td>
                                    <td>
                                        <button v-if="item.hash!=null" @click="facturacion(item.id)" class="btn btn-primary">
                                            Detalles
                                        </button>
                                        <p v-else>--</p>
                                    </td>
                                    <td>
                                        <button v-if="item.estado==0" @click="anular(item.id)" type="button" class="btn btn-danger btn-link btn-sm">
                                            <i class="material-icons">clear</i>
                                        </button>
                                        <p v-else>
                                            Anulado
                                        </p>
                                    </td>
                                    <td>
                                        <button v-if="item.estado==1 && item.hash!=null" @click="consulta_anular(item.id)" type="button" class="btn btn-danger btn-link btn-sm">
                                            <i class="material-icons">clear</i>
                                        </button>
                                        <p v-else>
                                            --
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-rigth">
                            <ul class="pagination">
                                <li class="page-item" :class="(n==activo)?'active':''" v-for="n in paginas">
                                    <a @click="page(n)" class="page-link">{{n}}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="facturacion" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        Detalles de Facturacion
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body text-center" v-if="data_facturacion!=null">
                        <h4>{{ data_facturacion.sunat_description }}</h4>
                        <a v-if="data_facturacion.enlace_del_cdr!=''" :href="data_facturacion.enlace_del_cdr" class="btn btn-primary" target="_blank">Enlace CDR</a>
                        <a v-if="data_facturacion.enlace_del_pdf!=''" :href="data_facturacion.enlace_del_pdf" class="btn btn-primary" target="_blank">Enlace PDF</a>
                        <a v-if="data_facturacion.enlace_del_xml!=''" :href="data_facturacion.enlace_del_xml" class="btn btn-primary" target="_blank">Enlace XML</a>
                    </div>
                </div>
            </div>
        </div>
        <div id="consulta" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        Detalles de Anulación
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body text-center" v-if="data_consulta!=null">
                        <h4>{{ data_consulta.sunat_description }}</h4>
                        <a v-if="data_consulta.enlace_del_cdr!=''" :href="data_consulta.enlace_del_cdr" class="btn btn-primary" target="_blank">Enlace CDR</a>
                        <a v-if="data_consulta.enlace_del_xml!=''" :href="data_consulta.enlace_del_xml" class="btn btn-primary" target="_blank">Enlace XML</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
// import dragonTable from "../components/dragon-table.vue";
export default {
    // components:{
    //     dragonTable
    // },
    data() {
        return {
            ventas: [],
            paginas: 0,
            activo: 1,
            busqueda:{
                nombre_dni:'',
            },
            data_facturacion: null,
            data_consulta: null
        }
    },
    mounted() {
        this.listar();
    },
    methods: {
        hola(){
            alert('hola');
        },
        listar(){
            axios.get(api_url+'/venta?page='+this.activo,{
                params: this.busqueda
            })
            .then(response=>{
                this.ventas=response.data.data;
                this.paginas=response.data.last_page;
                this.activo=response.data.current_page;
            });
        },
        facturacion(id){
            this.data_facturacion=null;
            axios.get(api_url+'/venta/'+id+'/facturacion').then(response=>{
                this.data_facturacion=response.data; 
            });
            $('#facturacion').modal();
        },
        page(n){
            this.activo=n;
            this.listar();
        },
        anular(id){
            var t=this;
            swal({
                title: "Eliminar Venta",
                text: "Desea eliminar esta venta",
                icon: "warning",
                buttons: true,
                // dangerMode: true,
            })
            .then((result) => {
                if (result) {
                    axios.post(api_url+'/venta/'+id+'/anular')
                    .then(response=>{
                        if (response.data.status=="OK") {
                            t.listar();
                            swal({title: "Venta Anulada",icon: "success",timer: "2000"});
                        }else{
                            swal({title: response.data.data, icon: "error",timer: "2000"});
                        }
                    });
                }
            });
        },
        consulta_anular(id){
            this.data_consulta=null;
            axios.get(api_url+'/venta/'+id+'/consulta').then(response=>{
                this.data_consulta=response.data; 
            });
            $('#consulta').modal();
        },
    },
}
</script>