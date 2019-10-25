<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-tabs card-header-info">
                    <h4 class="card-title">Lotes Actuales</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button @click="excel()" class="btn btn-success">
                                <i class="fa fa-file-excel-o"></i>
                            </button>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>COD</th>
                                <th>ITEM</th>
                                <th>Stock</th>
                                <th>PC</th>
                                <th>FV</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="lote in lotes">
                                <td>{{ lote.codigo }}</td>
                                <td>{{ lote.nombre }}</td>
                                <td>{{ lote.cantidad - lote.consumo }}</td>
                                <td>S/. {{lote.precio}}</td>
                                <td>{{(lote.fecha_vencimiento==null)? '-': lote.fecha_vencimiento}}</td>
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
            axios.get(api_url+'/lote')
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
            table2excel.export($('#tabla'),'Reporte de Lotes');
        },
    },
}
</script>