<script setup>
import { reactive } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import requests from '../../assets/js/requests';
import JobForm from '../../components/frames/JobForm.vue';
import Alert from '../../components/standard/Alert.vue';
import Spinner from '../../components/standard/Spinner.vue';

const data = reactive({
    error: null,
    loaded: false,
    job: {}
});

const $route = useRoute();
const $router = useRouter();
const id = $route.params.id;

async function load(id){
    console.log('Carregando cargo ID', id);
    data.loaded = false;
    try{
        let request = await requests.getJob(id);
        data.job = request.data;
        data.loaded = true;
    }catch(e){
        data.error = e.display ?? console.error(e) ?? `Houve um problema técnico ao carregar a página: \n${e}`;
    }
}

function onSubmitDone(success){
    if (success){
        $router.push({name:'listJob'});
    }
}

load(id);
</script>
<template>
<JobForm
    v-if="data.loaded"
    :job="data.job"
    :exit-route="{name:'changeParameters', params:{submenu:'cargos'}}"
    @onSubmitDone="onSubmitDone"
></JobForm>
<Alert v-else-if="data.error">
    <p>Não foi possível carregar o cargo/função devido a um erro.</p>
    <p><strong>{{ data.error }}</strong></p>
</Alert>
<Spinner v-else></Spinner>
</template>