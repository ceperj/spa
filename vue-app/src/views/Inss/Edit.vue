<script setup>
import { reactive } from "vue";
import { useRouter } from "vue-router";
import requests from "../../assets/js/requests";
import InssForm from "../../components/frames/InssForm.vue";
import Alert from "../../components/standard/Alert.vue";
import Spinner from "../../components/standard/Spinner.vue";

const $router = useRouter();

const data = reactive({
    loaded: false,
    error: null,
    inss: [],
});

async function load(){
    data.loaded = false;
    try{
        let request = await requests.getInssUniqueAliquot();
        data.inss = request.data;
        data.loaded = true;
    }catch(e){
        data.error = e.display ?? console.error(e) ?? `Houve um problema técnico ao carregar a página: \n${e}`;
    }
}

function onSubmitDone(success){
    if (success){
        $router.push({name:'changeParameters', params:{submenu:''}});
    }
}

load();
</script>
<template>
    <InssForm
        v-if="data.loaded"
        :inss="data.inss"
        :exit-route="{name:'changeParameters', params:{submenu:''}}"
        @onSubmitDone="onSubmitDone"
    ></InssForm>
    <Alert v-else-if="data.error">
        <p>Não foi possível carregar os valores da alíquota de INSS devido a um erro.</p>
        <p><strong>{{ data.error }}</strong></p>
    </Alert>
    <Spinner v-else></Spinner>
</template>