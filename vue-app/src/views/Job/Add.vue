<script setup>
import { useRouter } from 'vue-router';
import JobForm from '../../components/frames/JobForm.vue';

const $router = useRouter();

function onSubmitDone(success){
    if (success){
        $router.push({name:'changeParameters', params:{submenu:'cargos'}});
    }
}
</script>
<template>
<JobForm
    :exit-route="{name:'changeParameters', params:{submenu:'cargos'}}"
    @onSubmitDone="onSubmitDone"
></JobForm>
</template>