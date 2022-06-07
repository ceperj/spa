<script setup>
import { reactive, ref } from 'vue';

let props = defineProps(['id', 'kind']);
let emit = defineEmits(['onClose']);

const data = reactive({
  title: '',
  success: true,
  status: '',
  statusText: '',
});

function showOnSuccess(title) {
  data.title = title;
  data.success = true;
  data.status = '';
  const element = document.getElementById(props.id);
  const modal = new bootstrap.Modal(element);
  modal.show();
}

function showOnError(title, errorInfo) {
  data.title = title;
  data.success = false;
  data.status = errorInfo.status;
  data.statusText = errorInfo.statusText;
  const element = document.getElementById(props.id);
  const modal = new bootstrap.Modal(element);
  modal.show();
}

function onClose(){
  emit('onClose');
}

defineExpose({
  showOnSuccess: ref(showOnSuccess),
  showOnError: ref(showOnError),
});
</script>
<template>
  <div class="modal fade" tabindex="-1" :id="id" data-bs-backdrop="static" v-on="{'hidden.bs.modal':onClose}">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 v-if="data.success" class="modal-title">Registro Gravado</h5>
          <h5 v-else class="modal-title">Erro na Gravação</h5>
        </div>
        <div class="modal-body">
          <p v-if="data.success">{{ kind }} <strong>{{ data.title }}</strong> foi gravado com sucesso.</p>
          <div v-else>
            <p>{{ kind }} <strong>{{ data.title }}</strong> não foi gravado devido a um erro (<code>HTTP {{data.status}} {{data.statusText}}</code>).</p>
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          <slot v-if="data.success" name="successButton">
            <button type="button" class="btn btn-success" data-bs-dismiss="modal">Fechar</button>
          </slot>
          <slot v-else name="errorButton">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
          </slot>
        </div>
      </div>
    </div>
  </div>
</template>