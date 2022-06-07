<script setup>
// Modo de usar:
//
//      v-model:page="page"
//             or
//      :page="page" @navigate="link => onNavigate(link)"
//

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