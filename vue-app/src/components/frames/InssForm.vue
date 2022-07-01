<script setup>
import FormRequest from '../standard/FormRequest.vue';
import NumericInput from '../standard/NumericInput.vue';

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
const fields = JSON.parse(JSON.stringify(props.inss));

function onSubmitDone(success) {
  emit('onSubmitDone', success);
}
</script>
<template>
  <FormRequest id="pageForm" :action="route" kind="O valor" :fields="fields"
    title-field="inss" @on-submit-done="onSubmitDone">
    <template v-slot:fields="values">
      <h3>Edição de INSS</h3>
      <div class="row gx-5">
        <div class="col-4">
          <NumericInput id="aliquot" label="Alíquota do INSS" sufix="%"
            decimals="2" v-model="values.fields.aliquot"
            :strip-trailing-zeros="true"
            :invalid-feedback="values.validation.aliquot"></NumericInput>
        </div>
        <div class="col-4">
          <NumericInput id="ceil" label="Teto do INSS" prefix="R$" decimals="2"
            v-model="values.fields.ceil" :strip-trailing-zeros="false"
            :invalid-feedback="values.validation.ceil"></NumericInput>
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