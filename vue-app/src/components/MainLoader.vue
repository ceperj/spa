<script setup>
defineProps(['loading']);
</script>
<template>
    <section v-if="loading" style="position:fixed; top:0; bottom:0; left:0; right:0; background-color: rgba(0,0,0,.3);">
        <div style="margin: 0vh 0vw; width:100vw; height: 100vh;" class="bg-white d-flex flex-column text-center justify-content-center align-items-center">
            <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
            <h2 class="fw-light mt-3">Aguarde enquanto preparamos tudo...</h2>
        </div>
    </section>
</template>