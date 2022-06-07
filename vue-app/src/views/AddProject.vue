<script setup>
import { RouterLink, useRouter } from 'vue-router';
import FormInput from '../components/standard/FormInput.vue';
import FormRequest from '../components/standard/FormRequest.vue';
import FormRadioGroup from '../components/standard/FormRadioGroup.vue';
import consts from '../assets/js/consts';

const props = defineProps({
  id: { default: 'new' },
  editing: { default: false },
  exitRoute: { default: {name: 'index'} },
  projectName: { default: '' },
  sector: { default: '' },
  process: { default: '' },
  projectManager: { default: '' },
  startDate: { default: '' },
  endDate: { default: '' },
  status: { default: 1 },
});

const $router = useRouter();
const fields = JSON.parse(JSON.stringify(props));
const items = {
  status: new Map(consts.Status),
}

function onSubmitDone(success){
  if (success){
      $router.push(props.exitRoute);
  }
}
</script>
<template>
  <FormRequest id="pageForm" :action="'/api/project/'+id" kind="O projeto" :fields="fields" title-field="projectName" @onSubmitDone="onSubmitDone">
    <template v-slot:fields="values">
      <div class="row gx-5">
        <div class="col-5">
          <div class="row">
            <FormInput id="projectName" v-model="values.fields.projectName"
              :invalid-feedback="values.validation.projectName" label="Nome do Projeto"></FormInput>
          </div>
          <div class="row">
            <div class="col-12">
              <FormInput id="sector" v-model="values.fields.sector" :invalid-feedback="values.validation.sector"
                label="Setor"></FormInput>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <FormInput id="process" v-model="values.fields.process" :invalid-feedback="values.validation.process"
                label="Processo-mãe"></FormInput>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <FormInput id="projectManager" v-model="values.fields.projectManager"
                :invalid-feedback="values.validation.projectManager" label="Gerente do Projeto"></FormInput>
            </div>
          </div>
        </div>
        <div class="col-3">
          <div class="row">
            <div class="col-12">
              <FormInput id="startDate" v-model="values.fields.startDate"
                :invalid-feedback="values.validation.startDate" label="Data de Início" type="date"></FormInput>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <FormInput id="endDate" v-model="values.fields.endDate" :invalid-feedback="values.validation.endDate"
                label="Data de Término" type="date"></FormInput>
            </div>
          </div>
          <div class="row" v-if="editing">
            <FormRadioGroup label="Situação" id="status" v-model="values.fields.status" :invalid-feedback="values.validation.status" :items="items.status"></FormRadioGroup>
          </div>
        </div>
      </div>
      <hr class="my-4 bg-light" />
      <div class="row">
        <div class="d-flex justify-content-center">
          <button type="submit" class="wide-button btn btn-success mx-1">Gravar</button>
          <RouterLink class="wide-button btn btn-danger mx-1" :to="{ name: 'index' }">Sair</RouterLink>
        </div>
      </div>
    </template>
  </FormRequest>
</template>
<style scope>
.wide-button {
  min-width: 200px;
}

.row {
  margin-top: 0.5rem;
}
</style>