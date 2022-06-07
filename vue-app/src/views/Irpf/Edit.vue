<script setup>
import { reactive } from 'vue';
import requests from '../../assets/js/requests';
import IrpfForm from '../../components/frames/IrpfForm.vue';
import Alert from '../../components/standard/Alert.vue';
import Spinner from '../../components/standard/Spinner.vue';

const data = reactive({
    loaded: false,
    error: null,
    rows: [],
});

function onSubmitDone(success){
  console.log('onSubmitDone',success);
}

async function load(){
  try{
    const response = await requests.getIrpf();
    data.rows = response.data;
    data.error = null;
    data.loaded = true;
  }catch(e){
    data.loaded = false;
    data.error = e.message;
    data.error = e.display;
  }
}

load();
</script>
<template>
  <IrpfForm
    v-if="data.loaded"
    :rows="data.rows"
    :exit-route="{name:'listIrpf'}"
    route="/api/irpf"
    @on-submit-done="onSubmitDone"
  ></IrpfForm>
  <Alert v-else-if="data.error">
    <p>Não foi possível carregar os registros de IRPF devido a um erro.</p>
    <p><strong>{{ data.error }}</strong></p>
  </Alert>
  <Spinner v-else></Spinner>
</template>