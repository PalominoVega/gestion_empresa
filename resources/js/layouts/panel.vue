<script>
    import navbar from './capas/navbar.vue'
    import sidebar from './capas/sidebar.vue'
    export default {
        components:{
            'navbar': navbar,
            'sidebar': sidebar,
        },
        mounted() {
            navigator.serviceWorker.register('./sw.js').then((registration) => {
                var messaging;
                messaging=firebase.messaging();
                messaging.useServiceWorker(registration);
                messaging.requestPermission().then(function () {
                    return messaging.getToken();
                }).then(function (token) {
                    if(local.getItem('push')===null){
                        local.setItem('push',token);
                        /**
                         * Registrar
                         */
                        $.ajax({        
                            type : 'POST',
                            url : "https://iid.googleapis.com/iid/v1:batchAdd",
                            headers : {Authorization : 'key=AIzaSyAQLPsHYq2tKv-1T8LDao5epfcCoFEpZnA'},
                            contentType : 'application/json',
                            dataType: 'json',
                            data: JSON.stringify({"to": "/topics/sistema-general-"+1, "registration_tokens": [token]}),
                            success : function(response) {
                                console.log("Push Registrado");
                            }
                        });
                    }
                });
            });
        },
        methods: {
            imagen(img){
                return api_url+'/../img/'+img;
            }
        },
    }
</script>
<template>
    <div id="app">
        <!-- <div class="wrapper ">
            <sidebar></sidebar>
            Main
            <div class="main-panel">
                <div id="close-layer"></div>
                Navbar
                <navbar></navbar>
                <div class="content">
                    <div class="container-fluid">
                        <slot/>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="dashboard">
            <sidebar></sidebar>
            <div class="content">
                <navbar></navbar>
                 <div class="img-panel-superior">
                    <img :src="imagen('lineas-superior.png')" alt="">
                 </div>
                 <slot/>
                 <div class="img-panel-inferior">
                    <img :src="imagen('lineas-inferior.png')" alt="">
                 </div>
            </div>
        </div>
    </div>
</template>