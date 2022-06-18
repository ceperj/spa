<script setup>
defineProps({
    progress: Number,
    labeled: {default:false},
    striped: {default:false},
    animated: {default:false},
    background: {default:''}, // '' or primary, 'success', 'danger'...
});
</script>
<template>
<div class="progress">
    <div class="progress-bar"
        :class="{
            'progress-bar-striped':striped,
            'progress-bar-animated': animated,
            ['bg-' + background]: !! background
        }"
        role="progressbar" :aria-valuenow="progress" aria-valuemin="0"
        aria-valuemax="100" :style="{width: (progress+'%')}">
        <span v-if="labeled"><slot>{{ progress }}%</slot></span>
    </div>
</div>
</template>