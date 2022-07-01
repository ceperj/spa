<script setup>
import { useRouter } from 'vue-router';
import consts from '../../assets/js/consts';
import requests from '../../assets/js/requests';
import DataTable from '../../components/standard/DataTable.vue';

const $router = useRouter();

const presentation = {
    min_cents(value) { return ! value ? '' : (value / 100).toLocaleString(consts.lang, {style:'currency', currency:consts.intl.currency}); },
    max_cents(value) { return ! value ? '' : (value / 100).toLocaleString(consts.lang, {style:'currency', currency:consts.intl.currency}); },
    aliquot(value) { return (value / 10000).toLocaleString(consts.lang, {style:'percent', maximumFractionDigits:2}); },
}

function onClickEdit(){
    $router.push({name:'editIrpf'});
}
</script>
<template>
<DataTable
    id="dataTable"
    title="Edição de IRPF"
    :request-all="requests.getIrpf"
    :fields="{min_cents:'Limite mínimo (R$)', max_cents:'Limite máximo (R$)', aliquot:'Alíquota (%)'}"
    :presentation="presentation"
    :remove-controls="true"
></DataTable>
<div class="row">
    <div class="d-flex justify-content-center col-12">
        <button type="submit" class="wide-button btn btn-success mx-1" @click="onClickEdit">Editar Tabela</button>
        <RouterLink class="wide-button btn btn-danger mx-1" :to="{name:'changeParameters'}">Sair</RouterLink>
    </div>
</div>
</template>