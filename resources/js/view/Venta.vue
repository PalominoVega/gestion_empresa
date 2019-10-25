<template>   
    <div class="row">
        <div id="confirmar" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        Confirmar Venta
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Email del cliente (opcional)" v-model="venta.email">
                        </div>
                        <div class="text-center">
                            <button @click="guardar()" class="btn btn-primary">Confirmar Registro</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                    NUEVA VENTA
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="">Tipo</label>
                            <select @change="cambioTipo()" v-model="venta.tipo" name="" id="" class="form-control form-lg">
                                <option value="Boleta">Boleta</option>
                                <option value="Factura">Factura</option>
                                <option value="Ticket">Ticket</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label v-if="venta.tipo=='Factura'" class="">RUC</label>
                                <label v-else class="">DNI</label>
                                <input v-on:keyup="consulta()" v-model="venta.doc_identidad" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="">Nombre</label>
                            <input v-model="venta.nombre" type="text" class="form-control" :readonly="bloquear">
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
                    <h5 class="card-title">ITEMS DE VENTA</h5>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="">Seleccionar Producto</label>
                            <v-select v-model="itemMomentaneo.producto" :options="productos" @search:focus="listarProductos"></v-select>
                        </div>
                        <div class="col-6 col-sm-2 form-group">
                            <label for="">Cantidad</label>
                            <input v-model="itemMomentaneo.cantidad" type="number" class="form-control" min="1">
                        </div>
                        <div class="col-6 col-sm-3 form-group">
                            <button @click="agregarItem()" class="btn btn-info">Agregar al Carrito</button>
                        </div>
                    </div>
                    <hr>
                     <table class="table table-striped dg-table-responsive">
                        <thead>
                            <tr class="table-dark">
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Quitar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item,index) in venta.items">
                                <td data-label="Codigo">{{ item.codigo}}</td>
                                <td data-label="Nombre">{{ item.nombre}}</td>
                                <td data-label="Cantidad">
                                    <input type="number" v-model="item.cantidad" class="form-control text-center cantidad"></td>
                                <td data-label="Precio">S/ {{ item.precio.toFixed(2)}}</td>
                                <td data-label="Quitar">
                                    <button @click="eliminarItem(index)" type="button" class="btn btn-danger btn-link btn-sm">
                                        <i class="material-icons">close</i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr v-if="venta.items.length==0">
                                <td class="text-center" colspan="5">---- Sin Items de venta ----</td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="row">
                        <div class="text-right col-12">
                            <label for=""><b>SubTotal:</b></label>
                            <input :value="'S/ '+subtotal" type="text" class="resumen-igv" readonly>
                        </div>
                        <div class="text-right col-12">
                            <label for=""><b>IGV 18%:</b></label>
                            <input :value="'S/ '+igv" type="text" class="resumen-igv" readonly>
                        </div>
                        <div class="text-right col-12">
                            <label for=""><b>Total:</b></label>
                            <input :value="'S/ '+generarTotal" type="text" class="resumen-igv" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center col-12">
                            <button @click="confirmar()" class="btn btn-success">Guardar</button>
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
            productos: [],
            venta:{
                doc_identidad:null,
                nombre:null,
                tipo: "Boleta",
                proveedor_id:null,
                documento: null,
                items: [],
                total: 0,
                email: '',
            },
            itemMomentaneo:{
                producto: null,
                cantidad: 1,
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
            this.venta.items.map((item)=>{
                total=total+(item.cantidad*item.precio);
            });
            this.venta.total=total;
            this.igv=(total*0.18).toFixed(2);
            this.subtotal=(total-this.igv).toFixed(2);
            return total.toFixed(2);
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
        listarProductos(){
            this.productos=[];
            axios.get(api_url+'/stock?all=true&')
            .then(response=>{
                for (let i = 0; i < response.data.length; i++) {
                    var producto = response.data[i];
                    producto.label=producto.codigo+" - "+producto.nombre+" - "+producto.stock +" UNI - "+"S/."+producto.precio;
                    this.productos.push(producto);
                }
            });
        },
        cambioTipo(){
            this.venta.doc_identidad=null;
            this.venta.nombre=null;
            this.bloquear=false;
        },
        consulta(){
            if (this.venta.tipo=="Factura") {
                if (this.venta.doc_identidad.length==11) {
                    axios.get(api_url+'/cliente/consulta?ruc='+this.venta.doc_identidad)
                    .then(response=>{
                        var resultado=response.data;
                        this.venta.nombre=resultado.razonSocial;
                        this.bloquear=true;                        
                    });
                }else{
                    this.bloquear=false;                        
                    this.venta.nombre="";
                }
            }else{
                if (this.venta.doc_identidad.length==8) {
                    axios.get(api_url+'/cliente/consulta?dni='+this.venta.doc_identidad)
                    .then(response=>{
                        var resultado=response.data;
                        this.venta.nombre=resultado.nombres+' '+resultado.apellidoPaterno+' '+resultado.apellidoMaterno;
                        this.bloquear=true;                        
                    });
                }else{
                    this.bloquear=false;                        
                    this.venta.nombre="";
                }
            }
        },
        seleccionarProveedor(value){
            this.venta.proveedor_id=(value==null) ? null : value.id;
        },
        eliminarItem(index){this.venta.items.splice(index, 1)},
        agregarItem(){
            if (this.itemMomentaneo.cantidad<=this.itemMomentaneo.producto.stock) {
                this.venta.items.push({
                    producto_id: this.itemMomentaneo.producto.id,
                    codigo: this.itemMomentaneo.producto.codigo,
                    nombre: this.itemMomentaneo.producto.nombre,
                    precio: this.itemMomentaneo.producto.precio,
                    cantidad: this.itemMomentaneo.cantidad,
                });
                this.itemMomentaneo={producto: null,cantidad: 1,precio: "0.00"};
            }else{
                swal({title: "Stock insuficiente",icon: "info",timer: "2000"});
            }
        },
        confirmar(){$('#confirmar').modal()},
        guardar(){
            if(this.loading==false){
                this.loading=true;
                $('#confirmar').modal('hide');
                axios.post(api_url+'/venta',this.venta)
                .then(response=>{
                    var respuesta=response.data;
                    if(respuesta.status=="OK"){
                        swal({title: "venta Registrada",icon: "success",timer: "2000"});
                        this.venta={
                            doc_identidad:null,
                            nombre:null,
                            tipo: "Boleta",
                            proveedor_id:null,
                            documento: null,
                            items: [],
                            total: 0,
                            email: ''
                        };
                        $('#modal-documento').modal();
                        this.url="comprobanteventa/"+respuesta.data.id;                    
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
