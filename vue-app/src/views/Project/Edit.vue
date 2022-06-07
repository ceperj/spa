<script setup>
import { reactive } from 'vue';
import AddProject from '../AddProject.vue';
import Alert from '../../components/standard/Alert.vue';
import Spinner from '../../components/standard/Spinner.vue';
import { useRoute } from 'vue-router';
import requests from '../../assets/js/requests';

const data = reactive({
    loaded: false,
    error: null,
    item: {},
});

const $route = useRoute();
const id = $route.params.id;
const exitRoute = {name: 'changeParameters'}

async function load(){
    console.log('Carregando projeto ID', id);
    data.loaded = false;
    try{
        let request = await requests.getProject(id);
        data.item = request.data;
        data.item.editing = true;
        data.item.id = id;
        data.item.exitRoute = exitRoute;
        data.loaded = true;
    }catch(e){
        data.error = e.display ?? console.error(e) ?? `Houve um problema técnico ao carregar a página: \n${e}`;
    }
}

load();
</script>
<template>
<AddProject v-if="data.loaded"
    v-bind="data.item"></AddProject>
<Alert v-else-if="data.error">
    <p>Não foi possível carregar o projeto devido a um erro.</p>
    <p><strong>{{ data.error }}</strong></p>
</Alert>
<Spinner v-else></Spinner>
</template>