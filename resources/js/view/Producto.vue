<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-info">
                    LISTA DE PRODUCTOS
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <button @click="abrir()" class="btn btn-warning">
                                Agregar producto
                            </button>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>CODIGO</th>
                                        <th>NOMBRE DE PRODUCTO</th>
                                        <th>PRECIO VENTA</th>
                                        <th>EDITAR</th>
                                        <th>ESTADO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td v-if="productos.length==0" colspan="5" class="text-center">
                                            No se encontraron productos
                                        </td>
                                    </tr>
                                    <tr v-for="item in productos">
                                        <td>{{item.codigo}}</td>
                                        <td>{{item.nombre}}</td>
                                        <td>{{item.precio}}</td>
                                        <td>
                                            <button @click="abrirEditar(item.id)" type="button" class="btn text-warning btn-link btn-sm">
                                                <i class="material-icons">edit</i>
                                            </button>
                                        </td>
                                        <td>
                                            <button v-if="item.estado=='0'" @click="cambiarEstado(item.id)" type="button" class="btn text-success btn-link btn-sm">
                                                <i class="material-icons">
                                                    radio_button_checked
                                                </i>
                                            </button>
                                            <button v-if="item.estado=='1'" @click="cambiarEstado(item.id)" type="button" class="btn text-default btn-link btn-sm">
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
                            <h5 class="modal-title">Nuevo producto</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="form-nuevo" v-on:submit.prevent="guardar">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="">Nombre de producto:</label>
                                    <input name="nombre" v-model="producto.nombre" type="text" class="form-control">
                                    <!-- <span class="error">{{ error.nombre }}</span> -->
                                </div>
                                <div class="form-group">
                                    <label for="">Precio:</label>
                                    <money name="precio" v-model="producto.precio" class="form-control"></money>
                                </div>
                                <div class="form-group">
                                    <label for="">Punto de Reorden:</label>
                                    <input name="punto_reorden" v-model="producto.punto_reorden" type="number" class="form-control">
                                    <!-- <span class="error">{{ error.nombre }}</span> -->
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Guardar</button>
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
                            <h5 class="modal-title">Editar producto</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="form-editar" v-on:submit.prevent="update">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="">Nombre de producto:</label>
                                    <input v-model="producto_editar.nombre" type="text" class="form-control">
                                    <!-- <span class="error">{{ error_editar.nombre }}</span> -->
                                </div>
                                <div class="form-group">
                                    <label for="">Precio:</label>
                                    <money v-model="producto_editar.precio" class="form-control"></money>
                                </div>
                                <div class="form-group">
                                    <label for="">Punto de Reorden:</label>
                                    <input name="punto_reorden" v-model="producto_editar.punto_reorden" type="number" class="form-control">
                                    <!-- <span class="error">{{ error.nombre }}</span> -->
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Guardar</button>
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
            producto: {nombre: "",precio: "",punto_reorden: ""},
            producto_editar: {nombre: "",precio: "",punto_reorden: ""},
            productos:[],
            //Nuevo producto
            error:{

            },
            error_editar:{

            }
        }
    },
    mounted() {
        this.listar();
    },
    methods: {
        listar(){
            axios.get(api_url+'/producto?all=true')
            .then(response=>{
                this.productos=response.data;
            });
        },
        abrir(){
            $('#modal-nuevo').modal();
        },
        guardar(){
            axios.post(api_url+'/producto?data=true',this.producto)
            .then(response=>{
                this.limpiarErrores();
                var respuesta=response.data;
                if (respuesta.status=="OK") {
                    this.limpiarErrores();
                    this.productos.push(respuesta.data);
                    this.producto={nombre: "",precio: "",punto_reorden: ""};
                    swal({title: "Producto Registrado",icon: "success",timer: "2000"});
                }
                if (respuesta.status=="VALIDATION") {
                    this.mostrarErrores('form-nuevo', respuesta.data)
                    // this.error=respuesta.data;
                }
            });
        },
        abrirEditar(id){
            axios.get(api_url+'/producto/'+id)
            .then(response=>{
                this.producto_editar=response.data;
            });
            $('#modal-editar').modal();
        },
        update(){
            axios.post(api_url+'/producto/'+this.producto_editar.id+'?_method=PUT',this.producto_editar)
            .then(response=>{
                this.limpiarErrores();

                this.error_editar=null;
                var respuesta=response.data;
                if (respuesta.status=="OK") {
                    this.limpiarErrores();
                    this.listar();
                    swal({title: "Producto Actualizado",icon: "success",timer: "2000"});
                }
                if (respuesta.status=="VALIDATION") {
                    this.mostrarErrores('form-editar', respuesta.data)
                 
                }
            });
        },
        cambiarEstado(id){
            axios.post(api_url+'/producto/'+id+'?_method=PUT',{
                estado: true    
            })
            .then(response=>{
                var respuesta=response.data;
                if (respuesta.status=="OK") {
                    this.listar();
                    swal({title: "Estado Modificado",icon: "success",timer: "2000"});
                }
            });
        },
        mostrarErrores(form, error){
            var errores=error;
            $('.has-error span').remove();
            var arrKeys=Object.keys(errores);
            for (let index = 0; index < arrKeys.length; index++) {
                var indexName=arrKeys[index];
                console.log(indexName);
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