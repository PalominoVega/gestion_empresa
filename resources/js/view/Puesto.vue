<template>
    <div>
        
    </div>
</template>
<script>
export default {
    data() {
        return {
            puestos:[]
        }
    },
    mounted() {
        this.listar();
    },
    methods: {
        listar(){
            axios.get(api_url+'/puesto')
            .then(response=>{
                this.puestos=response.data;
            });
        },
    }
}
</script>