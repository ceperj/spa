<script setup>
/*
 |--------------------------------------------------------------|
 | NotFound
 |--------------------------------------------------------------|
 |
 | Esta página é exibida quando uma rota inválida foi solicitada
 | ao Vue Router.
 |
*/
import { useRoute } from 'vue-router';

const $route = useRoute();
</script>
<template>
    <div class="container">
        <h1 class="py-3">Página não encontrada!</h1>
        <p>Talvez um botão ou link tenha redirecionado você para esta página por
            engano, pois ela não existe. Se você digitou o endereço manualmente,
            verifique a ortografia.</p>
        <p>Endereço: <code>{{ $route.path }}</code></p>
        <hr>
    </div>
</template>