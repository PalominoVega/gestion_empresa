<template>
    <div class="card">
        <div class="card-header card-header-info">
            Lista de Compra
        </div> 
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>FECHA</th>
                                <th>DOCUMENTO</th>
                                <th>PROVEEDOR</th>
                                <th>TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in compras">
                                <td>{{ item.created_at }}</td>
                                <td>{{ item.documento }}</td>
                                <td>{{ item.proveedor.nombre }}</td>
                                <td>S/ {{ item.total.toFixed(2) }}</td>
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
            compras:[]
        }
    },
    mounted() {
        axios.get(api_url+'/compra')
        .then(response=>{
            this.compras=response.data;
        });
    },
}
</script>