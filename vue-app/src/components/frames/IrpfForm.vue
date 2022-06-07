<script setup>
import { reactive, unref } from 'vue';
import FormRequest from '../../components/standard/FormRequest.vue';
import FormInput from '../standard/FormInput.vue';
import CurrencyCentsInput from '../standard/CurrencyCentsInput.vue';
import PercentageInput from '../standard/PercentageInput.vue';

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

function transformBeforeSend(formData) {
  let iterableForm = [...formData];
  for (let [field, value] of iterableForm) {
    if (!isIntegerField(field)) continue;

    let number = Number(value.replace(',', '.'));

    if (Number.isNaN(number)) throw new Error('O campo ' + field + ' não estava pronto para envio, há um erro no valor: ' + value);

    formData.set(field, ((number * 100 + 0.0001) | 0) + '');
  }
}

function isIntegerField(field) {
  return field.endsWith('[min_cents]')
    || field.endsWith('[max_cents]')
    || field.endsWith('[aliquot]');
}
</script>
<template>
  <FormRequest id="pageForm" :action="route" :fields="data.fields"
    kind="O registro" @on-submit-done="onSubmitDone"
    :form-transformation="transformBeforeSend">
    <template v-slot:fields="values">
      <table class=table>
        <thead>
          <th style="width:200px">Limite Mínimo (R$)</th>
          <th style="width:200px">Limite Máximo (R$)</th>
          <th style="width:200px">Alíquota</th>
          <th>&nbsp;</th>
        </thead>
        <tbody>
          <tr v-for="(item, index) in values.fields">
            <td>
              <CurrencyCentsInput :id="'irpf[' + index + '][min_cents]'"
                v-model="item.min_cents"
                :invalid-feedback="values.validation['irpf.' + index + '.min_cents']"
                :classes="{ 'input-group-sm': true }"></CurrencyCentsInput>
            </td>
            <td>
              <CurrencyCentsInput :id="'irpf[' + index + '][max_cents]'"
                v-model="item.max_cents"
                :invalid-feedback="values.validation['irpf.' + index + '.max_cents']"
                :classes="{ 'input-group-sm': true }"></CurrencyCentsInput>
            </td>
            <td>
              <PercentageInput :id="'irpf[' + index + '][aliquot]'"
                v-model="item.aliquot"
                :invalid-feedback="values.validation['irpf.' + index + '.aliquot']"
                :classes="{ 'input-group-sm': true }"></PercentageInput>
            </td>
            <td>
              <button type="button" class="btn btn-sm btn-link"
                @click="removeItem(index)">Excluir esta linha</button>
            </td>
          </tr>
          <tr>
            <td colspan="4 gx-3">
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