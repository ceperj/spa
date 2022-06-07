<script setup>
import { useRouter } from "vue-router";
import UserForm from "../../components/frames/UserForm.vue";

const $router = useRouter();

function onSubmitDone(success){
    if (success){
        $router.push({name:'changeParameters', params:{submenu:'usuarios'}});
    }
}
</script>
<template>
    <UserForm
        :exit-route="{name:'changeParameters', params:{submenu:'usuarios'}}"
        @onSubmitDone="onSubmitDone"
    ></UserForm>
</template>