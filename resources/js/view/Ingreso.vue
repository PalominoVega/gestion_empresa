<template>   
    <div class="row">
        <div class="modal fade" id="modal-documento" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <iframe :src="url" frameborder="0" width="300" height="500"></iframe>
                </div>
            </div>
        </div>

        <div class="col-12" v-if="pantalla==2">
            <div class="card">
                <div class="card-header card-header-info">
                    SELECCIONAR CAJA A ADMINISTRAR
                </div>
                <div class="card-body">
                    <select v-model="caja_id" class="form-control">
                        <option v-for="caja in cajas" :value="caja.id">{{ caja.nombre }}</option>
                    </select>
                    <button @click="aperturar" class="btn btn-success">APERTURAR</button>
                </div>
            </div>
        </div>
        <div class="col-12" v-if="pantalla==1">
            <div class="card">
                <div class="card-header card-header-info">
                    NUEVA OPERACIÓN
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="">Tipo</label>
                            <select @change="cambioTipo()" v-model="operacion.tipo" name="" id="" class="form-control form-lg">
                                <option value="INGRESO">INGRESO</option>
                                <option value="EGRESO">EGRESO</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="">Modo</label>
                            <select v-model="operacion.modo" name="" id="" class="form-control form-lg">
                                <option value="Efectivo">Efectivo</option>
                                <option value="Tarjeta">Tarjeta</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label v-if="operacion.tipo=='EGRESO'" class="">RUC</label>
                                <label v-else class="">DNI</label>
                                <input v-on:keyup="consulta()" v-model="operacion.doc_identidad" type="text" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="">Nombre</label>
                            <input v-model="operacion.nombre" type="text" class="form-control" :readonly="bloquear">
                        </div>
                        <div v-if="rol!='Administrador'" class="col-md-2">
                            <button @click="cierre()" class="btn btn-danger">Cerrar Caja</button>
                        </div>
                        <!-- <div class="col-md-2">
                            <label for="">Total</label>
                            <input :value="'S/. '+generarTotal" type="text" class="form-control text-right" readonly>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">ITEMS DE CONCEPTOS</h5>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="">Seleccionar CONCEPTOS</label>
                            <v-select v-model="itemMomentaneo.concepto" :options="conceptos" @search:focus="listarConceptos"></v-select>
                        </div>
                        <div class="col-6 col-sm-2 form-group">
                            <label for="">Precio</label>
                            <input v-model="itemMomentaneo.precio" type="number" class="form-control" min="1">
                        </div>
                        <div class="col-6 col-sm-3 form-group">
                            <button @click="agregarItem()" class="btn btn-info">Agregar al Carrito</button>
                        </div>
                    </div>
                    <hr>
                     <table class="table table-striped dg-table-responsive">
                        <thead>
                            <tr class="table-dark">
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Quitar</th>
                                <th>Quitar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item,index) in operacion.items">
                                <td data-label="Nombre">{{ item.nombre}}</td>
                                <td data-label="Precio">S/ {{item.precio}}</td>
                                <td data-label="Quitar">
                                    <button @click="eliminarItem(index)" type="button" class="btn btn-danger btn-link btn-sm">
                                        <i class="material-icons">close</i>
                                    </button>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr v-if="operacion.items.length==0">
                                <td class="text-center" colspan="5">---- Sin Items de operación ----</td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="row">
                        <!-- <div class="text-right col-12">
                            <label for=""><b>SubTotal:</b></label>
                            <input :value="'S/ '+subtotal" type="text" class="resumen-igv" readonly>
                        </div>
                        <div class="text-right col-12">
                            <label for=""><b>IGV 18%:</b></label>
                            <input :value="'S/ '+igv" type="text" class="resumen-igv" readonly>
                        </div> -->
                        <div class="text-right col-12">
                            <label for=""><b>Total:</b></label>
                            <input :value="'S/ '+generarTotal" type="text" class="resumen-igv" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center col-12">
                            <button @click="guardar()" class="btn btn-primary">Guardar</button>
                            <!-- <button @click="confirmar()" class="btn btn-success">Guardar</button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss">
    @media (max-width: 576px){
        .dg-table-responsive thead tr{
            display: none;
        }
        .dg-table-responsive tr, .dg-table-responsive td{
            display: block;
            border: none;
        } 
        .dg-table-responsive.table tbody tr td::before{
            content: attr(data-label);
            width: 100px;
            display: inline-flex;
            font-weight: 800
        }
    }
    table input{
        display: inline !important;
    }
</style>
<script>
import { mapState } from 'vuex'

export default {
    data() {
        return {
            loading:false,
            pantalla: 0,
            /**
             * Bloquear campo de nombre
             */
            bloquear: false,
            /**
             * Variables
             */
            proveedores:[],
            conceptos: [],
            operacion:{
                doc_identidad:null,
                nombre:null,
                tipo: "",
                proveedor_id:null,
                items: [],
                total: 0,
                modo:'Efectivo',
            },
            itemMomentaneo:{
                concepto: null,
                precio: 1,
            },
            cajas: [],
            caja_id: 0,
            igv:0,
            subtotal:0,
            url:'',
            rol:'',
        }
    },
    computed: {
        ...mapState(['cuenta']),
        generarTotal(){
            var total=0;
            this.operacion.items.map((item)=>{
                total=total+parseFloat(item.precio);
            });
            this.operacion.total=total;
            this.igv=(total*0.18) ;
            this.subtotal=(total-this.igv) ;
            return total.toFixed(2) ;
        }
    },
    mounted() {
        axios.get(api_url+'/proveedor?all=true')
        .then(response=>{
            for (let i = 0; i < response.data.length; i++) {
                var proveedor = response.data[i];
                this.proveedores.push({
                    label: proveedor.ruc+" - "+proveedor.nombre,
                    id: proveedor.id
                });
            }
        });
        this.comprobar();
        this.listarCajaLibre();
    },
    methods: {
        comprobar(){
            this.rol=this.cuenta.rol;
            if (this.rol!="Administrador") {
                axios.get(api_url+'/movimientocaja/actual')
                .then(response=>{
                    if (response.data.status==true) {
                        this.pantalla=1;
                    }else{
                        this.pantalla=2;
                    }
                });
            }else{
                this.pantalla=1;
            }
        },
        listarCajaLibre(){
            axios.get(api_url+'/caja/libre')
            .then(response=>{
                this.cajas=response.data;
            });
        },
        aperturar(){
            axios.post(api_url+'/movimientocaja',{caja_id: this.caja_id})
            .then(response=>{
                var respuesta=response.data;
                if(respuesta.status=="OK"){
                    swal({title: "Caja Aperturada",icon: "success",timer: "2000"});
                    this.pantalla=1;
                }
                if (respuesta.status=="ERROR") {
                    swal({title: respuesta.data,icon: "error",timer: "4000"});
                }
            });
        },
        cierre(){
            axios.post(api_url+'/movimientocaja/cierre')
            .then(response=>{
                var respuesta=response.data;
                if(respuesta.status=="OK"){
                    swal({title: "Cierre de Caja",icon: "success",timer: "2000"});
                    this.pantalla=2;
                }
                if (respuesta.status=="ERROR") {
                    swal({title: respuesta.data,icon: "error",timer: "4000"});
                }
            });
        },
        listarConceptos(){
            this.conceptos=[];
            axios.get(api_url+'/listarconcepto?tipo='+this.operacion.tipo)
            .then(response=>{
                for (let i = 0; i < response.data.length; i++) {
                    var concepto = response.data[i];
                    concepto.label=concepto.nombre;
                    this.conceptos.push(concepto);
                }
            });
        },
        cambioTipo(){
            this.operacion.doc_identidad=null;
            this.operacion.nombre=null;
            this.bloquear=false;
            this.operacion.items=[];
        },
        consulta(){
            if (this.operacion.tipo=="EGRESO") {
                if (this.operacion.doc_identidad.length==11) {
                    axios.get(api_url+'/cliente/consulta?ruc='+this.operacion.doc_identidad)
                    .then(response=>{
                        var resultado=response.data;
                        this.operacion.nombre=resultado.razonSocial;
                        this.bloquear=true;                        
                    });
                }else{
                    this.bloquear=false;                        
                    this.operacion.nombre="";
                }
            }
            if (this.operacion.tipo=="INGRESO") {
                if (this.operacion.doc_identidad.length==8) {
                    axios.get(api_url+'/cliente/consulta?dni='+this.operacion.doc_identidad)
                    .then(response=>{
                        var resultado=response.data;
                        this.operacion.nombre=resultado.nombres+' '+resultado.apellidoPaterno+' '+resultado.apellidoMaterno;
                        this.bloquear=true;                        
                    });
                }else{
                    this.bloquear=false;                        
                    this.operacion.nombre="";
                }
            }
        },
        seleccionarProveedor(value){
            this.operacion.proveedor_id=(value==null) ? null : value.id;
        },
        eliminarItem(index){this.operacion.items.splice(index, 1)},
        agregarItem(){

                this.operacion.items.push({
                    producto_id: this.itemMomentaneo.concepto.id,
                    nombre: this.itemMomentaneo.concepto.nombre,
                    precio: parseFloat(this.itemMomentaneo.precio).toFixed(2),
                });
                this.itemMomentaneo={concepto: null,precio: 1};
            
        },
        confirmar(){$('#confirmar').modal()},
        guardar(){
            if(this.loading==false){
                this.loading=true;
                $('#confirmar').modal('hide');
                axios.post(api_url+'/operacion',this.operacion)
                .then(response=>{
                    var respuesta=response.data;
                    if(respuesta.status=="OK"){
                        swal({title: "operacion Registrada",icon: "success",timer: "2000"});
                        this.operacion={
                            doc_identidad:null,
                            nombre:null,
                            tipo: "Boleta",
                            proveedor_id:null,
                            items: [],
                            total: 0,
                            email: ''
                        };
                        $('#modal-documento').modal();
                        this.url="comprobanteoperacion/"+respuesta.data.id;                    
                    }
                    if (respuesta.status=="ERROR") {
                        swal({title: respuesta.data,icon: "error",timer: "4000"});
                    }
                    this.loading=false;
                });
            }
        }
    },
}
</script>
