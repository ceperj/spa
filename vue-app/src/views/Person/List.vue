<script setup>
import { useRoute, useRouter } from 'vue-router';
import requests from '../../assets/js/requests';
import DataTable from '../../components/standard/DataTable.vue';

const $router = useRouter();
const $route = useRoute();
const page = $route.query.page|0;

function onEdit(item){
    console.log('Está tentando editar a Pessoa Física',item);
    $router.push({name:'editPerson', params:{id:item.id}});
}
</script>
<template>
    <DataTable
        id="dataTable"
        title="Edição de Pessoa Física"
        :request-all="requests.getPersons"
        :fields="{
            cpf:'CPF',
            name:'Nome',
            projectName:'Projeto',
            statusText:'Situação'
        }"
        :exit-route="{name:'changeParameters'}"
        :page="page"
        @onEdit="onEdit"
    ></DataTable>
</template>