<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-info">
                  <h4 class="card-title">Nuevo Colaborador</h4>
                  <p class="card-category">Complete perfil de Colaborador</p>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>Email</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Nombre</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Apellido</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Dirección</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Distrito</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Provincia</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Departamento</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Celular</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Teléfono</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Profesión</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Fecha de Nacimiento</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>otros Estudios</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Nacionalidad</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Puesto</label>
                                <select v-model="colaborador.puesto_id" type="text" class="form-control">
                                    <option v-for="puesto in puestos" :value="puesto.id">{{ puesto.nombre }}</option>
                                </select>
                            </div>
                        </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            colaborador:{
                dni: '',
                nombre: '',
                apellido: '',
                email: '',
                direccion:'',
                telefono:'',
                telefono2:'',
                profesion:'',

            },
            puestos:[]
        }
    },
    mounted() {
        axios.get(api_url+'/puesto')
        .then(response=>{
            this.puestos=response.data;
        });
    },
}
$('body').on('focus','.bmd-form-group .form-control',function (e) {
    $(this).parent('.bmd-form-group').addClass('is-focused');
});
$('body').on('focusout','.bmd-form-group .form-control',function (e) {
    $(this).parent('.bmd-form-group').removeClass('is-focused');
    // $(this).val();
    if ($(this).val()!="") {
        $(this).parent('.bmd-form-group').addClass('is-filled');
    }else{
        $(this).parent('.bmd-form-group').removeClass('is-filled');
    }
});
</script>