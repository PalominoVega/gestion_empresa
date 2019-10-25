<template>
    <div>
        <component :is="layout">
            <router-view></router-view>
        </component>
    </div>	
</template>
<script>
var default_layouts="panel";
export default {
    computed:{
        layout(){
            return (this.$route.meta.layout || default_layouts);
        }
    }
}
</script>
