<script setup>
import { reactive } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import requests from "../../assets/js/requests";
import UserForm from "../../components/frames/UserForm.vue";
import Spinner from "../../components/standard/Spinner.vue";
import Alert from "../../components/standard/Alert.vue";

const data = reactive({
    error: null,
    loaded: false,
    user: {}
});

const $route = useRoute();
const $router = useRouter();
const id = $route.params.id;

async function loadUser(id){
    console.log('Carregando ID', id);
    data.loaded = false;
    data.error = null;
    try{
        let request = await requests.getUser(id);
        data.user = request.data;
        data.loaded = true;
    }catch(e){
        data.error = e.display;
    }
}

function onSubmitDone(success){
    if (success){
        $router.push({name:'listUser'});
    }
}

loadUser(id);
</script>
<template>
    <UserForm
        v-if="data.loaded"
        :user="data.user"
        :exit-route="{name:'changeParameters', params:{submenu:'usuarios'}}"
        @onSubmitDone="onSubmitDone"
    ></UserForm>
    <Alert v-else-if="data.error">
        <p>Não foi possível carregar o usuário devido a um erro.</p>
        <p><strong>{{ data.error }}</strong></p>
    </Alert>
    <Spinner v-else></Spinner>
</template>