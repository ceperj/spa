<script setup>
  import { RouterLink } from 'vue-router';
  import { onBeforeRouteUpdate, useRoute } from 'vue-router';
  
  const $route = useRoute();

  function getRouteParam(){
    let param = $route.params.submenu;
    return param instanceof Array ? param[0] : param;
  }

  function isRoute(compared){
    let param = getRouteParam();
    if (! param) return !compared;
    return param === compared;
  }

  const SubMenus = {
    Main: '',
    User: 'usuarios',
    Job: 'cargos',
    Project: 'projetos',
  }

  onBeforeRouteUpdate(() => {
    console.log('Before Route Update', arguments);
  });
</script>

<template>
  <section class="container" v-if="isRoute(SubMenus.Main)">
    <p class="fw-bold pt-4">Alteração de Parâmetros</p>
    <RouterLink :to="{name:'editBattery'}" class="btn btn-lg mx-1 btn-success">Bateria de Pagamento</RouterLink>
    <RouterLink :to="{name:'listIrpf'}" class="btn btn-lg mx-1 btn-success">Tabela IRPF</RouterLink>
    <RouterLink :to="{name:'changeInss'}" class="btn btn-lg mx-1 btn-success">Tabela INSS</RouterLink>

    <p class="fw-bold pt-4">Realizar backup do banco de dados</p>
    <button class="btn btn-lg mx-1 btn-danger">Backup</button>

    <div class="d-flex flex-wrap gap-5 gap-x-5">
      <div>
        <p class="fw-bold pt-4">Usuários</p>
        <RouterLink class="btn btn-lg mx-1 btn-primary" :to="{name:'changeParameters', params:{submenu:'usuarios'}}">Cadastrar</RouterLink>
      </div>
      <div>
        <p class="fw-bold pt-4">Cargos e funções</p>
        <RouterLink class="btn btn-lg mx-1 btn-primary" :to="{name:'changeParameters', params:{submenu:'cargos'}}">Cadastrar</RouterLink>
      </div>
      <div>
        <p class="fw-bold pt-4">Projetos</p>
        <RouterLink class="btn btn-lg mx-1 btn-info" :to="{name:'listProject'}">Editar</RouterLink>
      </div>
      <div>
        <p class="fw-bold pt-4">Pessoa Física</p>
        <RouterLink class="btn btn-lg mx-1 btn-info" :to="{name:'listPerson'}">Editar</RouterLink>
      </div>
    </div>
  </section>
  <section class="container" v-else-if="isRoute(SubMenus.User)">
    <p class="fw-bold pt-4">Usuários</p>
    <div class="d-flex flex-column">
      <div class="pt-2 d-flex"><RouterLink :to="{name: 'addUser'}" class="btn mx-1 btn-primary d-flex flex-column align-items-stretch justify-content-center"><div>Cadastrar</div><small>Usuário</small></RouterLink></div>
      <div class="pt-2 d-flex"><RouterLink :to="{name: 'listUser'}" class="btn mx-1 btn-danger d-flex flex-column align-items-stretch justify-content-center"><div>Editar</div><small>Usuário</small></RouterLink></div>
      <div class="pt-2 d-flex"><RouterLink :to="{name:'changeParameters', params:{submenu:''}}" class="btn mx-1 d-flex flex-column align-items-stretch justify-content-center"><div>Voltar</div></RouterLink></div>
    </div>
  </section>
  <section class="container" v-else-if="isRoute(SubMenus.Job)">
    <p class="fw-bold pt-4">Cargos</p>
    <div class="d-flex flex-column">
      <div class="pt-2 d-flex"><RouterLink :to="{name: 'addJob'}" class="btn mx-1 btn-primary d-flex flex-column align-items-stretch justify-content-center"><div>Cadastrar</div><small>Cargo</small></RouterLink></div>
      <div class="pt-2 d-flex"><RouterLink :to="{name: 'listJob'}" class="btn mx-1 btn-danger d-flex flex-column align-items-stretch justify-content-center"><div>Editar</div><small>Cargo</small></RouterLink></div>
      <div class="pt-2 d-flex"><RouterLink :to="{name:'changeParameters', params:{submenu:''}}" class="btn mx-1 d-flex flex-column align-items-stretch justify-content-center"><div>Voltar</div></RouterLink></div>
    </div>
  </section>
  <p v-else>O menu selecionado (<span>{{ getRouteParam() }}</span>) não é válido. Tente reabrir a página, ou contate o suporte.</p>
</template>
<style scoped>
.btn{
  min-width: 120px;
}
.gap-x-5{
  row-gap: 0 !important;
}
</style>
