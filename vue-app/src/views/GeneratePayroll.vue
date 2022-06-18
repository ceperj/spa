<script setup>
</script>
<template>
  <section class="container">
    <p class="fw-bold pt-4 text-start">Gerar Folha</p>
    <div class="d-flex flex-column">
      <div class="pt-2 d-flex"><button :to="{name: 'generateCheckFile'}" class="btn mx-1 btn-primary d-flex flex-column align-items-stretch justify-content-center">Gerar arquivo de conferência (.csv)</button></div>
      <div class="pt-2 d-flex"><button :to="{name: 'generateDatabaseFile'}" class="btn mx-1 btn-danger d-flex flex-column align-items-stretch justify-content-center">Gerar arquivo de banco</button></div>
      <div class="pt-2 d-flex"><RouterLink :to="{name:'index'}" class="btn mx-1 d-flex flex-column align-items-stretch justify-content-center">Sair</RouterLink></div>
    </div>
  </section>
</template>
<style scoped>
  .btn{
    min-width: 270px;
  }
</style>