<template>
    <div class="card">
        <div class="card-header card-header-info">
            Lista de notificaciones
        </div> 
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>HORA</th>
                                <th>DESCRIPCIÓN DE LA NOTIFICACION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in notificaciones">
                                <td>{{item.created_at}}</td>
                                <td>
                                    <router-link v-if="item.tipo=='Stock'" to="reorden" class="text-info">
                                        {{item.descripcion}}
                                    </router-link>
                                    <router-link v-else-if="item.tipo=='Vencimiento'" to="lote" class="text-info">
                                        {{item.descripcion}}
                                    </router-link>
                                </td>
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
            notificaciones: []
        }
    },
    mounted() {
        axios.get(api_url+'/notificacion')
        .then(response=>{
            this.notificaciones=response.data;
        });
    },
}
</script>