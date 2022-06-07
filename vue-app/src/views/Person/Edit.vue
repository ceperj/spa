<script setup>
import { reactive } from 'vue';
import AddPerson from '../AddPerson.vue';
import Alert from '../../components/standard/Alert.vue';
import Spinner from '../../components/standard/Spinner.vue';
import { useRoute } from 'vue-router';
import requests from '../../assets/js/requests';

const data = reactive({
    loaded: false,
    error: null,
    item: {},
});

let emit = defineEmits(['updateLoader']);

const $route = useRoute();
const id = $route.params.id;
const exitRoute = {name: 'changeParameters'}

async function load(){
    console.log('Carregando pessoa física ID', id);
    data.loaded = false;
    try{
        let request = await requests.getPerson(id);
        data.item = request.data;
        data.item.editing = true;
        data.item.id = id;
        data.item.exitRoute = exitRoute;
        data.loaded = true;
    }catch(e){
        data.error = e.display ?? console.error(e) ?? `Houve um problema técnico ao carregar a página: \n${e}`;
    }
}

function updateLoader(...anything){
    emit('updateLoader', ...anything);
}

load();
</script>
<template>
<AddPerson v-if="data.loaded"
    v-bind="data.item"
    @update-loader="updateLoader"></AddPerson>
<Alert v-else-if="data.error">
    <p>Não foi possível carregar o registro de Pessoa Física devido a um erro.</p>
    <p><strong>{{ data.error }}</strong></p>
</Alert>
<Spinner v-else></Spinner>
</template>