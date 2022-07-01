<script setup>
import { reactive, unref } from 'vue';
import FormRequest from '../../components/standard/FormRequest.vue';
import FormInput from '../standard/FormInput.vue';
import CurrencyCentsInput from '../standard/CurrencyCentsInput.vue';
import PercentageInput from '../standard/PercentageInput.vue';
import NumericInput from '../standard/NumericInput.vue';

const emit = defineEmits(['onSubmitDone']);
const props = defineProps(['rows', 'route', 'exitRoute']);
const fields = JSON.parse(JSON.stringify(unref(props.rows)));

const data = reactive({
  loaded: false,
  error: null,
  fields: fields,
});

function onSubmitDone(success) {
  emit('onSubmitDone', success);
}

function addItem() {
  data.fields.push({ min_cents: 0, max_cents: 0, aliquot: 0 });
}

function removeItem(index) {
  data.fields.splice(index, 1);
}

function getArrayId(index, field){
  return 'irpf[' + index + '][' + field + ']';
}

function getValidationId(index, field){
  return 'irpf.' + index + '.' + field;
}

function currency(text){
  return 'R$ ' + text;
}

function percentage(text){
  return text + ' %';
}
</script>
<template>
  <FormRequest id="pageForm" :action="route" :fields="data.fields"
    kind="O registro" @on-submit-done="onSubmitDone">
    <template v-slot:fields="values">
      <table class="table">
        <thead>
          <th style="width:200px">Limite Mínimo (R$)</th>
          <th style="width:200px">Limite Máximo (R$)</th>
          <th style="width:200px">Alíquota</th>
          <th>&nbsp;</th>
        </thead>
        <tbody>
          <tr v-for="(item, index) in values.fields">
            <td>
              <NumericInput
                :id="getArrayId(index, 'min_cents')" :displayed-as="currency"
                v-model="item.min_cents" :decimals="2"
                :invalid-feedback="values.validation[getValidationId(index, 'min_cents')]"
                :classes="{ 'input-group-sm': true }"></NumericInput>
            </td>
            <td>
              <NumericInput  v-if="index < values.fields.length - 1"
                :id="getArrayId(index, 'max_cents')" :displayed-as="currency"
                v-model="item.max_cents" :decimals="2"
                :invalid-feedback="values.validation[getValidationId(index, 'max_cents')]"
                :classes="{ 'input-group-sm': true }"></NumericInput>
              <div v-else class="input-group input-group-sm">
                <input class="form-control text-end" value="" disabled readonly>
                <input type="hidden" value="0" :name="getArrayId(index, 'max_cents')">
              </div>
            </td>
            <td>
              <NumericInput
                :id="getArrayId(index, 'aliquot')" :displayed-as="percentage"
                :invalid-feedback="values.validation[getValidationId(index, 'aliquot')]"
                v-model="item.aliquot" :strip-trailing-zeros="true" :decimals="2"
                :classes="{ 'input-group-sm': true }"></NumericInput>
            </td>
            <td>
              <button type="button" class="btn btn-sm btn-link"
                @click="removeItem(index)">Excluir esta linha</button>
            </td>
          </tr>
          <tr>
            <td colspan="4" class="gx-3">
              <button type="button" class="d-block btn btn-link"
                :class="{ 'is-invalid': values.validation.irpf }"
                @click="addItem">Adicionar nova linha</button>
              <label v-if="values.validation.irpf" class="invalid-feedback">Não
                é possível apagar a tabela de IRPF, adicione pelo menos uma
                linha.</label>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="d-flex gx-1">
        <button type="submit" class="btn btn-success wide-button">Salvar
          alterações</button>
        <RouterLink class="btn btn-danger wide-button"
          :to="{ name: 'listIrpf' }">
          Sair</RouterLink>
      </div>
    </template>
  </FormRequest>
</template>
<style scoped>
.gx-1 {
  column-gap: 0.25rem;
}

.wide-button {
  min-width: 200px;
}

.row {
  margin-top: 0.5rem;
}
</style>