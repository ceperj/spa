<script setup>
import { reactive } from "vue";
import { useRouter } from "vue-router";
import requests from "../../assets/js/requests";
import BatteryForm from "../../components/frames/BatteryForm.vue";
import Alert from "../../components/standard/Alert.vue";
import Spinner from "../../components/standard/Spinner.vue";

const $router = useRouter();

const data = reactive({
    loaded: false,
    error: null,
    batteries: [],
});

async function load(){
    data.loaded = false;
    try{
        let request = await requests.getBatteries();
        data.batteries = request.data;
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
    <BatteryForm
        v-if="data.loaded"
        :batteries="data.batteries"
        :exit-route="{name:'changeParameters', params:{submenu:''}}"
        @onSubmitDone="onSubmitDone"
    ></BatteryForm>
    <Alert v-else-if="data.error">
        <p>Não foi possível carregar os dados das Baterias de Pagamento devido a um erro.</p>
        <p><strong>{{ data.error }}</strong></p>
    </Alert>
    <Spinner v-else></Spinner>
</template>