<script setup>
import FormRequest from '../standard/FormRequest.vue';
import FormInput from '../standard/FormInput.vue';
import { unref } from 'vue';

const props = defineProps({
  'inss': {
    type: Object,
    default: {},
  },
  'route': {
    type: String,
    default: '/api/inss/unique'
  },
  'exitRoute': Object,
});

const emit = defineEmits(['onSubmitDone']);
const fields = JSON.parse(JSON.stringify(unref(props.inss)));

function onSubmitDone(success) {
  emit('onSubmitDone', success);
}
</script>
<template>
  <FormRequest id="pageForm" :action="route" kind="O valor" :fields="fields"
    title-field="aliquot" @on-submit-done="onSubmitDone">
    <template v-slot:fields="values">
      <h3>Alterar alíquota de INSS</h3>
      <div class="row gx-5">
        <div class="col-4">
          <div class="row gx-5">
            <FormInput id="aliquot" col="12" class="input-group" type="number"
              v-model="values.fields.inss"
              :invalid-feedback="values.validation.inss" label="Alíquota">
              <template v-slot:after>
                <span class="input-group-text">%</span>
              </template>
            </FormInput>
          </div>
        </div>
      </div>
      <hr class="my-4 bg-light" />
      <div class="row">
        <div class="d-flex justify-content-center">
          <button type="submit"
            class="wide-button btn btn-success mx-1">Gravar</button>
          <RouterLink class="wide-button btn btn-danger mx-1" :to="exitRoute">
            Sair</RouterLink>
        </div>
      </div>
    </template>
  </FormRequest>
</template>
<style scoped>
.wide-button {
  min-width: 200px;
}

strong {
  margin-bottom: 0.5em;
}

.row {
  margin-top: 0.5rem;
  margin-bottom: 1rem;
}
</style>