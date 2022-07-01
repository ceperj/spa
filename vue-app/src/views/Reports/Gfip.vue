<script setup>
import Spinner from '../../components/standard/Spinner.vue';
import LongLoadingDiv from '../../components/standard/LongLoadingDiv.vue';
import ProgressBar from '../../components/standard/ProgressBar.vue';
import { onMounted, onUnmounted, reactive, ref, toRaw } from 'vue';
import ModalVue from '../../components/standard/Modal.vue';
import requests from '../../assets/js/requests';
import utils from '../../assets/js/utils';
import { computed } from '@vue/reactivity';
import date from '../../assets/js/date';

const data = reactive({
  loaded: false,
  state: 'NOTSTARTED',
  success: false,
  message: '',
  current: 0,
  total: 0,
  progress: null,
  startedAt: '',
  startedAtRead: '',
  updatedAt: '',
  updatedAtRead: '',
  completedAt: '',
  completedAtRead: '',
  expiresAt: false,
  running: false,
});
const emit = defineEmits(['updateLoader']);
const modalOverride = ref(null);
const error = computed(() => data.success ? null : data.message);

let refreshTimeout;

async function refresh(){
  try{
    const response = await requests.getGfipInfo();
    data.loaded = true;
    data.state = response.state;
    data.success = response.success;
    data.message = response.message;
    data.startedAt = date.brazilianize(response.startedAt);
    data.startedAtRead = response.startedAtRead;
    data.completedAt = date.brazilianize(response.completedAt);
    data.completedAtRead = response.completedAtRead;
    data.updatedAt = date.brazilianize(response.updatedAt);
    data.updatedAtRead = response.updatedAtRead;
    data.expiresAt = response.expiresAt;
    data.current = response.current;
    data.total = response.total;
    data.progress = response.progress;
    data.running = ['RUNNING', 'QUEUED', 'STARTED'].indexOf(data.state) >= 0;
  } catch (e){
    data.loaded = false;
    data.success = false;
    data.message = e.display;
  }

  clearTimeout(refreshTimeout);
  if (data.running)
    refreshTimeout = setTimeout(refresh, 1000);
}

async function generate(){
  if (! confirm('Deseja iniciar a geração?'))
    return;

  return generateConfirm();
}

async function generateConfirm(){
  emit('updateLoader', true);
  await requests.generateGfip();

  while(! data.running)
  {
    await utils.sleep(1000);
    await refresh();
  }

  emit('updateLoader', false);
}

function generateAgain(){
  modalOverride.value.show();
}

async function generateAgainConfirm(ev){
  if (ev.cancel) return;
  return generateConfirm();
}

onMounted(() => refresh());
onUnmounted(() => clearTimeout(refreshTimeout));
</script>
<template>
  <section class="container">
    <p class="fw-bold pt-4 text-start">Gerar arquivo GFIP</p>

    <!-- Erro ao fazer o carregamento inicial -->
    <div v-if="!data.loaded && error">
      <div class="alert alert-danger">
        <p>Não foi possível carregar os dados sobre geração de arquivo GFIP devido a um erro na conexão ou servidor:</p>
        <p><strong>{{error}}</strong></p>
      </div>
    </div>

    <!-- Tela de carregamento inicial -->
    <div v-else-if="!data.loaded" class="d-flex flex-column">
      <div class="d-flex">
        <Spinner :centralize="false" :padding="false"
          :classes="{ 'spinner-border-sm': true }" class="me-2"></Spinner>
        <p>Estamos verificando se já existe uma geração em andamento. Por favor, aguarde!</p>
      </div>
      <LongLoadingDiv></LongLoadingDiv>
    </div>
 
    <!-- Tela de conclusão: sucesso -->
    <div v-else-if="(data.state === 'COMPLETED') && data.success" class="d-inline-block">
      <span>A geração foi concluída com sucesso!</span>
      <div class="ms-2 my-3">
        <div>Iniciada: <strong>em {{ data.startedAt }}.</strong></div>
        <div>Concluída: <strong>em {{ data.completedAt }}.</strong></div>
        <div>Expira: <strong>{{ data.expiresAt }}.</strong></div>
      </div>
      <ProgressBar animated="" striped="" class="my-3" labeled="true" progress="100"></ProgressBar>
      <div class="pt-2 d-flex">
        <a href="/api/gfip/download" download class="btn btn-wide mx-1 btn-success d-flex flex-column align-items-stretch justify-content-center">
          Baixar arquivo
        </a>
      </div>
      <div class="pt-2 d-flex">
        <button @click="generateAgain" class="btn btn-wide mx-1 btn-danger d-flex flex-column align-items-stretch justify-content-center">
          Gerar novamente
        </button>
      </div>
      <div class="pt-2 d-flex">
        <RouterLink :to="{ name: 'reports' }" class="btn btn-light btn-wide mx-1 d-flex flex-column align-items-stretch justify-content-center">
          Voltar mais tarde
        </RouterLink>
      </div>
    </div>

    <!-- Tela de progresso -->
    <div v-else-if="data.running" class="d-inline-block">
      <span>A geração está em andamento.</span>
      <div class="ms-2 my-3">
        <div>Iniciada: <strong>em {{ data.startedAt }}.</strong></div>
        <div>Atualização mais recente: <strong>{{data.updatedAtRead}}, em {{ data.updatedAt }}.</strong></div>
      </div>
      <ProgressBar v-if="data.progress" animated="true" striped="true" class="my-3" labeled="true" :progress="data.progress|0"></ProgressBar>
      <ProgressBar v-else animated="true" striped="true" class="my-3" :labeled="false" background="secondary" progress="100"></ProgressBar>
      <div class="d-flex">
        <div class="ms-1 px-2 d-flex">
          <RouterLink :to="{ name: 'reports' }" class="btn btn-light btn-wide btn-secondary mx-1 d-flex flex-column align-items-stretch justify-content-center">
            Voltar mais tarde
          </RouterLink>
        </div>
      </div>
    </div>

    <!-- Tela inicial + Tela de erro -->
    <div v-else class="d-flex flex-column">
      <div v-if="data.state === 'COMPLETED' && ! data.success" class="alert alert-danger">
        <p>A última tentativa de geração, iniciada em {{data.startedAt}} e atualizada em {{data.updatedAt}}, foi mal-sucedida:</p>
        <p><strong>Erro: {{error || 'não há informações adicionais sobre o erro.'}}</strong></p>
      </div>
      <span v-else>No momento, nenhum arquivo está sendo gerado.</span>
      <div class="pt-2 d-flex">
        <button @click="generate" class="btn btn-wide mx-1 btn-primary d-flex flex-column align-items-stretch justify-content-center">
          Iniciar Geração
        </button>
      </div>
      <div class="pt-2 d-flex">
        <RouterLink :to="{ name: 'reports' }" class="btn btn-light btn-wide mx-1 d-flex flex-column align-items-stretch justify-content-center">
          Sair
        </RouterLink>
      </div>
    </div>
  </section>
  <ModalVue ref="modalOverride"
    @modal-result="generateAgainConfirm"
    title="Gerar Novamente"
    :buttons="[{text: 'Confirmar Geração', theme: 'danger'}]"
    cancel="Voltar">
    <p>Deseja realmente gerar novamente o arquivo GFIP?</p>
    <p>O arquivo atual será <strong class="text-danger">apagado</strong>, e você precisará aguardar a conclusão da nova geração para baixar o novo arquivo.</p>
  </ModalVue>
</template>
<style scoped>
.btn-wide {
  min-width: 270px;
}
</style>