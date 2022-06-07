<script setup>
import { useRoute, useRouter } from 'vue-router';
import requests from '../../assets/js/requests';
import DataTable from '../../components/standard/DataTable.vue';

const $router = useRouter();
const $route = useRoute();
const page = $route.query.page|0;

function onEdit(item){
    console.log('Está tentando editar o Projeto',item);
    $router.push({name:'editProject', params:{id:item.id}});
}
</script>
<template>
    <DataTable
        id="dataTable"
        title="Edição de Projeto"
        :request-all="requests.getProjects"
        :fields="{
            projectName:'Nome',
            sector:'Setor',
            process:'Processo-mãe',
            startDate:'Data de Início',
            statusText:'Situação',
        }"
        :exit-route="{name:'changeParameters'}"
        :page="page"
        @onEdit="onEdit"
    ></DataTable>
</template>