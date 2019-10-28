<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-info">
                    CUADRES DE CAJA
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>FECHA APERTURA</th>
                                    <th>FECHA CIERRE</th>
                                    <th>CAJA</th>
                                    <th>USUARIO</th>
                                    <th>MONTO RECAUDADO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in movimientos">
                                    <td>{{fecha(item.created_at)}}</td>
                                    <td>{{fecha(item.updated_at)}}</td>
                                    <td>{{ (item.caja==null)? ' ' : item.caja.nombre}}</td>
                                    <td>{{item.user.nombre +' '+item.user.apellido}}</td>
                                    <td class="text-right">S/ {{item.total.toFixed(2)}}</td>
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
            movimientos:[]
        }
    },
    mounted() {
        axios.get(api_url+'/movimientocaja')
        .then(response=>{
            this.movimientos=response.data;
        });
    },
    methods: {
        fecha(date){
            console.log(date);
            moment.locale('es-ES');
            fecha_convertida = moment(date).format('llll');
            
            
            return moment(date,"YYYY-MM-DD HH:mm:ss").format('hh:mm a DD/MM/YYYY');
        }
    },
}
</script>