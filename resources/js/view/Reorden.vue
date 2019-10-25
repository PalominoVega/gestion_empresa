<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-tabs card-header-info">
                    <h4 class="card-title">Productos por Agotarse</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button @click="excel()" class="btn btn-success">
                                <i class="fa fa-file-excel-o"></i>
                            </button>
                        </div>
                    </div>
                    <table id="tb-reorden" class="table">
                        <thead>
                            <tr>
                                <th>COD</th>
                                <th>ITEM</th>
                                <th>Stock Minimo</th>
                                <th>Stock Actual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="lote in lotes">
                                <td>{{ lote.codigo }}</td>
                                <td>{{ lote.nombre }}</td>
                                <td>{{ lote.punto_reorden}}</td>
                                <td>{{ lote.stock }}</td>
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
            lotes: []
        }
    },
    mounted() {
        this.consultar();
    },
    methods: {
        consultar(){
            axios.get(api_url+'/reorden')
            .then(response=>{
                this.lotes=response.data;
            });
        },
        excel(){
            var table2excel=new Table2Excel();
            Table2Excel.extend(function(cell, cellText) {
                if(cellText) return {
                    t: 'text',
                    v: cellText,
                };
                return null;
            });
            table2excel.export($('#tb-reorden'),'Reporte de Bajos Stock');
        },
    },
}
</script>