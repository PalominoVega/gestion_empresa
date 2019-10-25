<template>
    <div class="row row-home justify-content-center">
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-home">
          <div class="card-header">
            <div class="card-img">
              <img :src="imagen('estadistica-black.png')" alt="">
            </div>
            <p class="card-category">Ventas</p>
            <h4 >S/ {{ resumen_almacen.ventas.toFixed(2) }}</h4>
          </div>
          <div class="card-footer">
            Día Actual
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-home">
          <div class="card-header">
            <div class="card-img">
              <img :src="imagen('compras.png')" alt="">
            </div>
            <p class="card-category">Ventas</p>
            <h4 >S/ {{ resumen_almacen.ventas.toFixed(2) }}</h4>
          </div>
          <div class="card-footer">
            Día Actual
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-home">
          <div class="card-header">
             <div class="card-img">
              <img :src="imagen('compras.png')" alt="">
            </div>
            <p class="card-category">Ventas</p>
            <h4 >S/ {{ resumen_almacen.ventas.toFixed(2) }}</h4>
          </div>
          <div class="card-footer">
            Día Actual
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-home">
          <div class="card-header">
             <div class="card-img">
              <img :src="imagen('compras.png')" alt="">
            </div>
            <p class="card-category">Ventas</p>
            <h4 >S/ {{ resumen_almacen.ventas.toFixed(2) }}</h4>
          </div>
          <div class="card-footer">
            Día Actual
          </div>
        </div>
      </div>
      
      <!-- {{ cuenta }} -->
    </div>
</template>
<script>
export default {
    data() {
        return {
          resumen_almacen: {
            ventas: 0,
            compras: 0
          },
          cuenta: this.$store.state.token
        }
    },
    mounted() {
        axios.get(api_url+'/resumen/mes')
        .then(response=>{
            this.resumen_almacen=response.data;
        });
    },
    methods: {
      prueba(){
        store.commit('increment')
      },
      imagen(img){
        return api_url+'/../img/'+img;
      }
    },
}
</script>