<template>
    <div class="row">
        
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-tabs card-header-info">
                    <h4 class="card-title">Kardex Valorizado</h4>
                </div>
                <div class="card-body">
                    <div class="kardex" v-for="kardex in kardexs">
                        <h4>COD: {{ kardex.codigo }} - {{ kardex.nombre }}</h4>
                        <table class="table table-striped">
                            <thead class="table-bordered">
                                <tr>
                                    <th rowspan="2">Fecha</th>
                                    <th colspan="3" class="text-center">ENTRADAS</th>
                                    <th colspan="3" class="text-center">SALIDAS</th>
                                    <th colspan="3" class="text-center">SALDO FINAL</th>
                                </tr>
                                <tr>
                                    <th>Cantidad</th>
                                    <th>Costo Unitario</th>
                                    <th>Costo Total</th>
                                    <th>Cantidad</th>
                                    <th>Costo Unitario</th>
                                    <th>Costo Total</th>
                                    <th>Cantidad</th>
                                    <th>Costo Unitario</th>
                                    <th>Costo Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="kardex.anterior!=null" >
                                    <td>Saldo Anterior</td>
                                    <td>{{ kardex.anterior.stock }}</td>
                                    <td>{{ kardex.anterior.precio_promedio }}</td>
                                    <td>{{ kardex.anterior.precio_promedio*kardex.anterior.stock }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ kardex.anterior.stock }}</td>
                                    <td>{{ kardex.anterior.precio_promedio }}</td>
                                    <td>{{ kardex.anterior.precio_promedio*kardex.anterior.stock }}</td>
                                </tr>
                                <tr v-else>
                                    <td>Saldo Anterior</td>
                                    <td>0</td>
                                    <td>0.000</td>
                                    <td>0.000</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>0</td>
                                    <td>0.000</td>
                                    <td>0.000</td>
                                </tr>
                                <tr v-for="item in kardex.kardex" :class="item.concepto=='Venta Anulada' ? 'table-danger':''">
                                    <td>{{ item.fecha}}</td>
                                    <td v-if="item.tipo=='Entrada'">{{ item.cantidad}}</td><td v-else></td>
                                    <td v-if="item.tipo=='Entrada'">{{ item.precio_promedio}}</td><td v-else></td>
                                    <td v-if="item.tipo=='Entrada'">{{ item.cantidad*item.precio_promedio}}</td><td v-else></td>
                                    <td v-if="item.tipo=='Salida'">{{ item.cantidad}}</td><td v-else></td>
                                    <td v-if="item.tipo=='Salida'">{{ item.precio_promedio}}</td><td v-else></td>
                                    <td v-if="item.tipo=='Salida'">{{ item.cantidad*item.precio_promedio}}</td><td v-else></td>
                                    <td>{{ item.stock }}</td>
                                    <td>{{ item.precio_promedio.toFixed(3) }}</td>
                                    <td class="text-right">{{ (item.precio_promedio * item.stock).toFixed(3) }}</td>
                                </tr>
                            </tbody>
                        </table>
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
            fecha_consulta: moment().format('YYYY-MM'),
            kardexs:[]
        }
    },
    mounted() {        
        this.consultar();
    },
    methods: {
        consultar(){
            axios.get(api_url+'/kardex?fecha='+this.fecha_consulta)
            // axios.get(api_url+'/kardex?api_token=qwerty&fecha=2019-09')
            .then(response=>{
                this.kardexs=response.data;
            });
        }
    },
}
</script>