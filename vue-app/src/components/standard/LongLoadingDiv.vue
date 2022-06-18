<script setup>
    import DelayedDiv from './DelayedDiv.vue';

    function reloadPage(){
        window.location.reload();
    }
</script>
<template>
    <DelayedDiv>
        <p>Isso está demorando mais tempo do que o normal.</p>
        <p><a href="#" @click.prevent="reloadPage()">Recarregar a página?</a></p>
    </DelayedDiv>
</template>