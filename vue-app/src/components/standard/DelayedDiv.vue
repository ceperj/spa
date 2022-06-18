<script setup>
import { onMounted, onUnmounted, ref, unref } from 'vue';

const props = defineProps({
    delay: {type:Number, default: 5000}
});

let delay = unref(props.delay);
let timer = 0;
const triggered = ref(false);

onMounted(() => {
    timer = setTimeout(() => triggered.value = true, delay);
});
onUnmounted(() => {
    clearTimeout(timer);
})
</script>
<template>
<div v-if="triggered">
    <slot></slot>
</div>
</template>