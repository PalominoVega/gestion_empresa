<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-tabs card-header-info">
                    <h4 class="card-title">Stock al Día</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-sm-4 col-6">
                            <input class="form-control" type="date" v-model="fecha_consulta">
                        </div>
                        <div class="col-sm-3 col-6">
                            <button @click="consultar()" class="btn btn-default">
                                <i class="material-icons">
                                refresh
                                </i>
                            </button>
                        </div>
                        <div class="col-sm-6 text-right">
                            <button @click="excel()" class="btn btn-success">
                                <i class="fa fa-file-excel-o"></i>
                            </button>
                        </div>
                    </div>
                    <table id="tabla" class="table">
                        <thead class="text-info">
                            <tr hidden>
                                <th colspan="5">
                                    Stock al dia {{ fecha_consulta }}
                                </th>
                            </tr>
                            <tr>
                                <th>COD</th>
                                <th>ITEM</th>
                                <th>STOCK</th>
                                <th>P. Promedio.</th>
                                <th>P. Venta</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="stock in stocks">
                                <td>{{ stock.codigo }}</td>
                                <td>{{ stock.nombre }}</td>
                                <td>{{ (stock!=null) ? stock.stock : 0 }}</td>
                                <td>S/. {{ (stock!=null) ? stock.precio_promedio.toFixed(2) : 0 }}</td>
                                <td>S/. {{ (stock!=null) ? stock.precio.toFixed(2) : 0 }}</td>
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
            fecha_consulta: moment().format('YYYY-MM-DD'),
            stocks: []
        }
    },
    mounted() {
        this.consultar();
    },
    methods: {
        consultar(){
            axios.get(api_url+'/stock?dia='+this.fecha_consulta)
            .then(response=>{
                this.stocks=response.data;
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
            table2excel.export($('#tabla'),'Lista de reportes ');
        },
    },
}
</script>