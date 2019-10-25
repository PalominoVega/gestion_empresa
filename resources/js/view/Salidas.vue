<template>
    <div class="card">
        <div class="card-header card-header-tabs card-header-info">
            <h4 class="card-title">Salida de productos</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>ENE</th>
                            <th>FEB</th>
                            <th>MAR</th>
                            <th>ABR</th>
                            <th>MAY</th>
                            <th>JUN</th>
                            <th>JUL</th>
                            <th>AGO</th>
                            <th>SEP</th>
                            <th>OCT</th>
                            <th>NOV</th>
                            <th>DIC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="producto in productos">
                            <td>{{ producto.nombre }}</td>
                            <td v-for="salida in producto.salidas">{{ salida.cantidad }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            productos:[]
        }
    },
    mounted() {
        axios.get(api_url+'/salidas?all=true')
        .then(response=>{
            this.productos=response.data;
        });
    },
}
</script>
