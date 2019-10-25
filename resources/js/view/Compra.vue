<template>
    <div class="row">
        <div class="col-12">
            <div class="row justify-content-center ">
                <div class="col-sm-5">
                    <div class="img">
                        <img :src="imagen('compras.png')" alt="" class="img-fluid">
                    </div>
                    <h4>Nueva Compra</h4>
                </div>
                <div class="col-sm-5">
                    <div class="card">
                        <div class="card-header card-header-info">
                            NUEVA COMPRA
                        </div>
                        <div class="card-body">
                            <form id="" class="row">
                                <div class="col-md-3 form-group">
                                    <label for="">Documento</label>
                                    <input v-model="compra.documento" type="text" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group bmd-form-group">
                                        <label class="">Proveedor</label>
                                        <v-select :options="proveedores" @input="seleccionarProveedor"></v-select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Total</label>
                                    <money :value="generarTotal" class="form-control text-right" readonly></money>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">ITEMS DE COMPRA</h5>
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label for="">Seleccionar Producto</label>
                            <v-select v-model="itemMomentaneo.producto" :options="productos"></v-select>
                        </div>
                        <div class="col-md-3 col-lg-2 form-group">
                            <label for="">Cantidad</label>
                            <input v-model="itemMomentaneo.cantidad" type="number" class="form-control" min="1">
                        </div>
                        <div class="col-md-3 col-lg-2 form-group">
                            <label for="">Precio</label>
                            <money v-model="itemMomentaneo.precio" class="form-control"></money>
                        </div>
                        <div class="col-md-3 col-lg-2 form-group text-center">
                            <button @click="agregarItem()" class="btn btn-info">Agregar Item</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>F.V.</th>
                                    <th>Código</th>
                                    <th>Quitar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item,index) in compra.items">
                                    <td>{{ item.codigo}}</td>
                                    <td>{{ item.nombre}}</td>
                                    <td>
                                        <input type="number" class="form-control" v-model="item.cantidad">                                    
                                    </td>
                                    <td>
                                        <money v-model="item.precio" class="form-control precio"></money>
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" v-model="item.fecha_vencimiento">
                                    </td>
                                    <td>
                                        <input class="form-control" v-model="item.codigo_lote">
                                    </td>
                                    <td>
                                        <button @click="eliminarItem(index)" type="button" class="btn btn-danger btn-link btn-sm">
                                            <i class="material-icons">close</i>
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="compra.items.length==0">
                                    <td class="text-center" colspan="6">Sin Items de compra</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="text-center col-12">
                            <button @click="guardar()" class="btn btn-success">Guardar</button>
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
                proveedores:[],
                productos: [],
                compra:{
                    proveedor_id:null,
                    documento: null,
                    total: 0.00,
                    items: [],
                },
                itemMomentaneo:{
                    producto: null,
                    cantidad: 1,
                }
            }
        },
        computed: {
            generarTotal(){
                var total=0;
                for (let i = 0; i < this.compra.items.length; i++) {
                    const item = this.compra.items[i];
                    total=total+(item.cantidad*item.precio);
                }
                this.compra.total=total;
                return total;
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
            axios.get(api_url+'/producto?all=true')
            .then(response=>{
                for (let i = 0; i < response.data.length; i++) {
                    var producto = response.data[i];
                    this.productos.push({
                        label: producto.codigo+" - "+producto.nombre,
                        codigo: producto.codigo,
                        nombre: producto.nombre,
                        id: producto.id
                    });
                }
            });
        },
        methods: {
            seleccionarProveedor(value){
                if(value==null){
                    this.compra.proveedor_id=null;
                }else{
                    this.compra.proveedor_id=value.id;
                }
            },
            eliminarItem(index){
                this.compra.items.splice(index, 1);
            },
            agregarItem(){
                this.compra.items.push({
                    producto_id: this.itemMomentaneo.producto.id,
                    codigo: this.itemMomentaneo.producto.codigo,
                    nombre: this.itemMomentaneo.producto.nombre,
                    precio: this.itemMomentaneo.precio,
                    cantidad: this.itemMomentaneo.cantidad,
                    fecha_vencimiento: null,
                    codigo_lote: null
                });
                this.$nextTick(function () {
                    $('.precio').number(true,2,'.','');
                });
                this.itemMomentaneo={
                    producto: null,
                    cantidad: 1,
                    precio: "0.00"
                };
            },
            guardar(){
                axios.post(api_url+'/compra',this.compra)
                .then(response=>{
                    var respuesta=response.data;
                    if(respuesta.status=="OK"){
                        swal({
                            title: "Compra Registrada",
                            icon: "success",
                            timer: "2000"
                        });
                        this.compra={
                            proveedor_id:null,
                            documento: null,
                            total: 0,
                            items: [],
                        };
                    }
                    if (respuesta.status=="ERROR") {
                        swal({
                            title: respuesta.data,
                            icon: "error",
                            timer: "4000"
                        });
                    }
                });
            },
            imagen(img){
                return api_url+'/../img/'+img;
            }
        },
    }
</script>
