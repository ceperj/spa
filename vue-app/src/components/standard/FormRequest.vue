<script setup>
/*
 |--------------------------------------------------------------|
 | FormRequest
 |--------------------------------------------------------------| 
 |
 | Componente de formulário, cujo POST conduz a uma requisição
 | HTTP. Possui diversas configurações:
 |
 | id - atribute "id" do formulário HTML.
 | action - URL onde a requisição será feita.
 | kind - O tipo de item tratado precedido pelo artigo (ex.: O registro), para visualização
 | fields - Um objeto JSON com os campos que serão tratados.
 | titleField - O campo principal de "fields" que será usado para identificar um item.
 | formTransformation - Um objeto de transformação de valor. Para cada campo que
 |    tiver um método com o mesmo nome, o método será chamado após submeter e
 |    antes de enviar ao servidor, para transformação do valor enviado.
 | Slot "fields" - Este slot deve conter os campos HTML do formulário. Ele
 |    receberá uma variável "values", o qual é um objeto com as propriedades
 |    "fields" e "validation", onde "fields" é o valor do campo a editar, e
 |    "validation" é um conjunto de campos que falharam na validação do servidor.
 */

import { reactive, ref, unref, watch } from 'vue';
import api from '../../assets/js/api';
import utils from '../../assets/js/utils';
import FormRequestCompletionDialogs from './FormRequestCompletionDialogs.vue';
import Spinner from './Spinner.vue';

const props = defineProps([
    'id',
    'action',
    'kind',
    'fields',
    'titleField',
    'formTransformation',
    'submitTransformation',
]);

const values = reactive({
  fields: props.fields,
  validation: utils.arrayAsObjectKeys(Object.keys(props.fields), null),
  sending: false,
  success: undefined,
});

let emit = defineEmits(['valuesReady', 'onSubmitDone']);
emit('valuesReady', values);

watch(values.fields, () => {clearValidationErrors()});

const completionDialog = ref(null);

function submitForm() {
  if (values.sending)
    return;

  if (confirm('Você confirma a gravação do cadastro?')) {
    values.sending = true;
    api.postForm(props.action, '#' + props.id, props.formTransformation)
      .then(onSubmitSuccess)
      .catch(onSubmitFailure);
  }
}

function onSubmitSuccess(){
  values.sending = false;
  values.success = true;
  values.validation = {};
  completionDialog.value.showOnSuccess(values.fields[props.titleField]);
}

function onSubmitFailure(response){
  values.sending = false;
  values.success = false;
  console.error('Submit failed either for code, network, or HTTP response', response);
  clearValidationErrors();

  if (response.isValidationError)
    copyValidationErrors(values.validation, response);
  else
    completionDialog.value.showOnError(values.fields[props.titleField], response);
}

function copyValidationErrors(dest, response){
  for (let field in response.data.errors)
      dest[field] = response.data.errors[field][0];
}

function clearValidationErrors(){
  utils.fillObject(values.validation, null)
}

function onCloseCompletionDialog(){
  emit('onSubmitDone', unref(values.success));
}
</script>
<template>
<form class="container" :id="id" @submit.prevent="submitForm">
    <slot name="fields" :fields="values.fields" :validation="values.validation"></slot>
</form>
<FormRequestCompletionDialogs
  ref="completionDialog"
  :id="id+'CompletionDialog'"
  :kind="kind"
  @onClose="onCloseCompletionDialog"
></FormRequestCompletionDialogs>
<div v-if="values.sending" class="backdrop-overlay bg-light bg-opacity-75 d-flex flex-column justify-content-center align-items-center">
  <Spinner></Spinner>
  <span>Enviando&hellip;</span>
</div>
</template>
<style>
.backdrop-overlay{
  position: fixed;
  top: 0;
  bottom: 0;
  right: 0;
  left: 0;
}
</style>