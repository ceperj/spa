<script setup>
import consts from '../../assets/js/consts';
import FormRequest from '../standard/FormRequest.vue';
import FormInput from '../standard/FormInput.vue';
import FormRadioGroup from '../standard/FormRadioGroup.vue';
import { unref } from 'vue';
import { useRouter } from 'vue-router';

const props = defineProps({
  'job': {
    type: Object,
    default: { id: 'new', name: '', status: 1 }
  },
  'route': {
    type: String,
    default: '/api/job/{id}'
  },
  'exitRoute': Object,
});

let emit = defineEmits(['onSubmitDone']);

const fields = JSON.parse(JSON.stringify(unref(props.job)));
const items = {
  status: new Map(consts.Status),
}

function onSubmitDone(success) {
  emit('onSubmitDone', success);
}
</script>
<template>
  <FormRequest id="pageForm" :action="route.replace('{id}', job.id)" kind="O cargo" :fields="fields" title-field="name"
    @on-submit-done="onSubmitDone">
    <template v-slot:fields="values">
      <div class="row gx-5">
        <div class="col-4">
          <div class="row">
            <FormInput id="name" v-model="values.fields.name" :invalid-feedback="values.validation.name" label="Nome do Cargo"></FormInput>
          </div>
        </div>
        <div class="col-4">
          <div class="row">
            <FormRadioGroup label="Situação" id="status" v-model="values.fields.status" :invalid-feedback="values.validation.status" :items="items.status"></FormRadioGroup>
          </div>
        </div>
      </div>
      <hr class="my-4 bg-light" />
      <div class="row">
          <div class="d-flex justify-content-center">
          <button type="submit" class="wide-button btn btn-success mx-1">Gravar</button>
          <RouterLink class="wide-button btn btn-danger mx-1" :to="exitRoute">Sair</RouterLink>
          </div>
      </div>
    </template>
  </FormRequest>
</template>
<style scoped>
.wide-button {
  min-width: 200px;
}

.row {
  margin-top: 0.5rem;
}
</style>