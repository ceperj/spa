<script setup>
import { useRouter } from 'vue-router';
import requests from '../../assets/js/requests';
import DataTable from '../../components/standard/DataTable.vue';

const $router = useRouter();

function onEdit(item){
    console.log('Está tentando editar o usuário',item);
    $router.push({name:'editJob', params:{id:item.id}});
}
</script>
<template>
    <DataTable
        id="dataTable"
        title="Edição de Cargos"
        :request-all="requests.getJobs"
        :fields="{name:'Cargo', statusText:'Situação'}"
        :exit-route="{name:'changeParameters', params:{submenu:'cargos'}}"
        @onEdit="onEdit"
    ></DataTable>
</template>