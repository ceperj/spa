<script setup>
import { reactive } from "vue";
import { RouterView } from "vue-router";
import ApplicationHeader from "./components/ApplicationHeader.vue";
import MainLoader from "./components/MainLoader.vue";
import Login from "./components/Login.vue";
import router from "./router";

/**
 * Ativa a tela de carregamento.
 */
var data = reactive({
  mainLoaderActive: true,
  pageLoader: false
});

function beforeEach(){ data.pageLoader = true; }
function afterEach(){ data.pageLoader = false; }
router.beforeEach(beforeEach);
router.afterEach(afterEach);

/**
 * Conecta o usuário autenticado.
 */
var user = {};

function setLoadedUser(loadedUser)
{
  if (! loadedUser) return;
  data.mainLoaderActive = false;
  user = loadedUser;
}

function receiveLoaderEvent(active){
  data.pageLoader = active;
}
</script>
<template>
  <main>
    <ApplicationHeader>
      <template v-slot:login>
        <Login @user-loaded="setLoadedUser"></Login>
      </template>
    </ApplicationHeader>
    <div v-show="!data.pageLoader">
    <RouterView v-slot="{ Component }" @updateLoader="receiveLoaderEvent">
      <Component :is="Component" :key="$route.fullPath"></Component>
    </RouterView>
    </div>
    <div v-if="data.pageLoader">
      <div class="p-3 bg-white d-flex flex-column text-center justify-content-center align-items-center">
        <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
        <span class="visually-hidden">Carregando&hellip;</span>
        </div>
        <h3 class="fw-light mt-3">Aguarde&hellip;</h3>
        </div>
    </div>
    <footer class="pt-3">
    </footer>
    <MainLoader :loading="data.mainLoaderActive"></MainLoader>
  </main>
</template>
