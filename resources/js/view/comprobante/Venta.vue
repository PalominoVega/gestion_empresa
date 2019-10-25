<template>
    <div class="baucher2 ticket-55">
      <div class="text-center">
        <button class="oculto-impresion btn-success btn btn-sm" @click="imprimir()">Imprimir</button>
      </div>
      <table>
        <tbody>
            <tr>
                <td class="centrado">
                    <img class="centrado" :src="imagen(empresa.logo)" onerror="this.style.display='none'" alt="Logotipo">
                    <br>
                    <b>{{empresa.nombre}}</b>
                    <br>{{empresa.ruc}}
                    <br>{{empresa.direccion}}
                    <br>{{ fecha(venta.updated_at) }}
                    <br>{{ venta.serie+"-"+venta.correlativo }}
                </td>
            </tr>
            <tr>
                <td v-if="venta.cliente.tipo=='Persona'">
                  <br>DNI: {{ venta.cliente.documento }}
                  <br>Cliente: {{ venta.cliente.nombre}}
                </td>        
                <td v-else>
                  <br>RUC: {{ venta.cliente.documento }}
                  <br>Cliente: {{ venta.cliente.nombre}}
                </td>        
            </tr>
        </tbody>
      </table>
      <div class="separador"></div>
      <table>
        <thead>
          <tr>
            <th class="cantidad">
              <b>CANT.</b>
            </th>
            <th><b>DESCRIPCIÓN</b></th>
            <th class="precio"><b>TOTAL</b></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="detalle in venta.detalles">
            <td class="cantidad">{{detalle.cantidad}}</td>
            <td>{{detalle.producto.nombre}}</td>
            <td class="precio text-right">{{(detalle.cantidad*detalle.precio).toFixed(2)}}</td>
          </tr>
        </tbody>
      </table>
      <div class="separador"></div>
    <table>
      <tbody>
        <tr>
          <td>OP. GRAVADA</td>
          <td class="precio text-right">{{(venta.total/1.18).toFixed(2)}}</td>
        </tr>
        <tr>
          <td class="">IGV</td>
          <td class="text-right">{{venta.igv.toFixed(2)}}</td>
        </tr>
        <tr>
          <td class="">TOTAL</td>
          <td class="text-right">{{venta.total.toFixed(2)}}</td>
        </tr>
      </tbody>
    </table>
    <div class="text-center">
      <canvas id="canvas"></canvas>
    </div>
    <table>
      <tr>
        <td class="centrado">
          ¡GRACIAS POR SU COMPRA!
        </td>
      </tr>
    </table>
  </div>
</template>
<script>

export default {
    data() {
        return {
            empresa: null,
            // id: this.$route.params.id,
            venta:null,
        }
    },
    mounted() {
        
        axios.get(api_url+'/venta/'+this.$route.params.id)
        .then(response=>{
            this.venta=response.data;
            this.empresa=this.venta.empresa;
            
            var comprobante=((this.venta.num_doc==null)?'':this.venta.num_doc).split('-');
            // var comprobante=this.venta.num_doc;
            // console.log(this.vent);
            
            var qr=[
              this.empresa.ruc,
              ((this.venta.tipo_documento=='2'))? '03': '01',
              this.venta.serie,
              zfill(this.venta.correlativo,6),
              this.venta.igv.toFixed(2),
              this.venta.total.toFixed(2),
              moment(this.venta.updated_at).format('DD/MM/YYYY'),
              (this.venta.tipo_documento=='2') ? 1 : 6,
              this.venta.cliente.documento,
              this.venta.hash
            ];
            var stringQr="";
            for (let i = 0; i < qr.length; i++) {
              const element = qr[i];
              stringQr=stringQr+((element==null)?'':element)+'|';
            }

            this.$nextTick(function () {
              var canvas = document.getElementById('canvas');
              QRCode.toCanvas(canvas, 
                /**
                 * QR DE SUNAT
                 */ 
                stringQr,
              function (error) {
                if (error) console.error(error)
                console.log('success!');
              });
            });
        });
    },
    methods: {
        imprimir() {
            window.print();
        },
        fecha(date){
          return moment(date).format('DD/MM/YYYY hh:mm A');
        },
        imagen(img){
          return api_url+'/../storage/logo/'+img;
        }
    },
}
function zfill(number, width) {
    var numberOutput = Math.abs(number); /* Valor absoluto del número */
    var length = number.toString().length; /* Largo del número */ 
    var zero = "0"; /* String de cero */  
    
    if (width <= length) {
        if (number < 0) {
             return ("-" + numberOutput.toString()); 
        } else {
             return numberOutput.toString(); 
        }
    } else {
        if (number < 0) {
            return ("-" + (zero.repeat(width - length)) + numberOutput.toString()); 
        } else {
            return ((zero.repeat(width - length)) + numberOutput.toString()); 
        }
    }
}

</script>