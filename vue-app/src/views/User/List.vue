<script setup>
import { useRouter } from 'vue-router';
import requests from '../../assets/js/requests';
import DataTable from '../../components/standard/DataTable.vue';

const $router = useRouter();

function onEditUser(user){
    console.log('Está tentando editar o usuário',user);
    $router.push({name:'editUser', params:{id:user.id}});
}
</script>
<template>
    <DataTable
        id="dataTable"
        title="Edição de Usuários"
        :request-all="requests.getUsers"
        :fields="{username:'Usuário', name:'Nome Completo', email:'E-mail', roleText:'Perfil', statusText:'Situação'}"
        :exit-route="{name:'changeParameters', params:{submenu:'usuarios'}}"
        @onEdit="onEditUser"
    ></DataTable>
</template>