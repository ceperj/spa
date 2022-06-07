<script setup>
import { computed, reactive, ref, watch } from 'vue';
import Pagination from './Pagination.vue';

const props = defineProps({
    id: String,
    title: String,
    requestAll: Function,
    exitRoute: Object,
    fields: Array,
    page: Number,
    presentation: Object,
    removeControls: Boolean,
});

const data = reactive({
    count: 0,
    items: [],
    meta: {},
    loadingTable: true,
    error: null,
    currentChecked: null,
});

const page = ref(props.page | 0);

const headers = computed(() => Object.keys(props.fields));
const itemsLength = computed(() => data.items.length);
watch(page, () => loadTableItems(page));

const emit = defineEmits(['onEdit']);

async function loadTableItems(page){
    data.loadingTable = true;

    try{
        const response = await props.requestAll(page.value);
        data.items = response.data;
        data.meta = response.meta;
        data.loadingTable = false;
    }catch(e){
        data.error = e.display;
    }
}

function onClickEdit(){
    const all = [...document.querySelectorAll('input[type="radio"]')];
    const checked = all.find(i => i.checked);
    if (! checked)
        return console.error('Botão radio não selecionado.');
    const id = checked.value;
    const item = data.items.find(i => i.id === id);
    emit('onEdit', item);
}

loadTableItems(page);
</script>
<template>
    <div class="container">
        <h3 class="my-4">{{ title }}</h3>
        <table class="table">
            <thead>
                <tr>
                    <th v-if="! removeControls" scope="col">&nbsp;</th>
                    <th v-for="header in headers" scope="col">{{ fields[header] }}</th>
                </tr>
            </thead>
            <tbody v-if="!data.loadingTable">
                <tr v-for="item in data.items" :key="item.id" :class="{'table-secondary':(data.currentChecked==item.id)}">
                    <td v-if="! removeControls">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" :name="id+'Radios'" :value="item.id" v-model="data.currentChecked">
                        </div>
                    </td>
                    <td v-for="col in headers">{{ presentation && presentation[col] ? presentation[col](item[col], col) : item[col] }}</td>
                </tr>
                <tr v-if="!itemsLength">
                    <td :colspan="headers.length + 1">
                        Parece que não há registros nesta tabela.
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td :colspan="headers.length + 1">
                        <div v-if="!data.error" class="text-center p-3">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading&hellip;</span>
                            </div>
                        </div>
                        <div v-else class="text-center">
                            <p>Houve um erro ao carregar os itens da tabela. <a @click.prevent="loadTableItems()" class="text-link" href="">Tentar novamente</a></p>
                            <p>{{ data.error }}</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <Pagination v-if="data.meta" :links="data.meta.links" v-model:page="page"></Pagination>
        <div class="row" v-if="! removeControls">
            <div class="d-flex justify-content-center col-12">
                <button type="submit" class="wide-button btn btn-success mx-1" @click="onClickEdit">Editar</button>
                <RouterLink class="wide-button btn btn-danger mx-1" :to="exitRoute">Sair</RouterLink>
            </div>
        </div>
    </div>
</template>