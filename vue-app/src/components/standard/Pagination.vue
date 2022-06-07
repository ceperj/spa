<script setup>
/*
|--------------------------------------------------------------|
| Pagination
|--------------------------------------------------------------|
|
| Componente que exibe uma linha de números de páginas para a
| paginação de tabelas que contém muitos itens. Pode ser
| utilizado em conjunto com `DataTable` ou isoladamente, embora
| force a utilização do estilo de retorno da paginação Laravel.
|
| Há dois modos de usar, alterando diretamente uma variável que
| pode ser assistida (`watch`):
|
|   v-model:page="page"
|
| Ou interagindo diretamente com eventos que fornecem informação
| extra:
| 
|   :page="page" @navigate="link => onNavigate(link)"
|
*/

defineProps(['page', 'links']);
const emit = defineEmits(['navigate', 'update:page']);

function clickPage(link){
    if(! link.url) return;
    const page = getPageFromUrl(link.url);
    emit('update:page', page);
    emit('navigate', {...link, page });
}

function getPageFromUrl(url){
    const result = url.match(/[?&]page=(\d+)/);
    return result[1]|0;
}
</script>
<template>
    <nav>
       <ul class="pagination justify-content-center" v-if="links && links.length > 1">
            <li
                v-for="link in links"
                class="page-item"
                :class="{disabled:link.active || ! link.url}">
                <a class="page-link" :href="link.url" @click.prevent="clickPage(link)">{{ link.label }}</a>
            </li>
        </ul>
        <ul class="pagination justify-content-center" v-else>
            <li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>
            <li class="page-item"><a class="page-link" href="" @click.prevent="">1</a></li>
            <li class="page-item disabled"><a class="page-link" href="#">&raquo;</a></li>
        </ul>
    </nav>
</template>