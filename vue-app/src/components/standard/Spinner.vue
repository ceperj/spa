<script setup>
defineProps({
  padding: {default:true},
  centralize: {default:true},
  classes: {default:""}
});
</script>
<template>
<div :class="{'text-center':centralize, 'p-3':padding}">
  <div class="spinner-border" :class="classes" role="status">
    <span class="visually-hidden">Carregando&hellip;</span>
  </div>
</div>
</template>