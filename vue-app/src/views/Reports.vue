<script setup>
</script>
<template>
  <section class="container">
    <p class="fw-bold pt-4 text-start">Relatórios</p>
    <div class="d-flex flex-column">
      <div class="pt-2 d-flex"><RouterLink :to="{name: 'generateGfip'}" class="btn mx-1 btn-info d-flex flex-column align-items-stretch justify-content-center">Gerar arquivo GFIP</RouterLink></div>
      <div class="pt-2 d-flex"><RouterLink :to="{name:'index'}" class="btn mx-1 d-flex flex-column align-items-stretch justify-content-center">Sair</RouterLink></div>
    </div>
  </section>
</template>
<style scoped>
  .btn{
    min-width: 270px;
  }
</style>
