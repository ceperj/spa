<script setup>
import { reactive } from "vue";
import { RouterLink, useRouter } from 'vue-router';
import requests from "../assets/js/requests";
import FormDropdown from "../components/standard/FormDropdown.vue";
import FormInput from "../components/standard/FormInput.vue";
import FormRequest from "../components/standard/FormRequest.vue";
import FormRadioGroup from "../components/standard/FormRadioGroup.vue";
import consts from "../assets/js/consts";
import NumericInput from "../components/standard/NumericInput.vue";
import date from "../assets/js/date";

const props = defineProps({
  /**
   * Valores padrões são referentes a uma nova pessoa-física sendo cadastrada.
   */
  id: { default: 'new' },
  exitRoute: { default: {name: 'index'} },
  editing: { default: false },
  name: { default: '' },
  cpf: { default: '' },
  rg: { default: '' },
  rgexp: { default: '' },
  pis: { default: '' },
  phone1: { default: '' },
  phone2: { default: '' },
  project_id: { default: 0 },
  bank_id: { default: 0 },
  bank_agency: { default: '' },
  bank_account: { default: '' },
  battery_id: { default: 0 },
  salary: { default: 0 },
  admission_date: { default: date.asISO8601() },
  registration_number: { default: '' },
  email: { default: '' },
  job_id: { default: 0 },
  status: { default: 1 },
});

const $router = useRouter();
const fields = JSON.parse(JSON.stringify(props));
const emit = defineEmits(['updateLoader']);

const dropdown = reactive({
  projects: new Map(),
  banks: new Map(),
  batteries: new Map(),
  jobs: new Map(),
});

(async function () {
  emit('updateLoader', true);
  let projects = requests.getProjectsDropdown();
  let banks = requests.getBanksDropdown();
  let batteries = requests.getBatteriesDropdown();
  let jobs = requests.getJobsDropdown();
  dropdown.projects = new Map((await projects).data);
  dropdown.banks = new Map((await banks).data);
  dropdown.batteries = new Map((await batteries).data);
  dropdown.jobs = new Map((await jobs).data);
  dropdown.status = new Map(consts.Status);
  emit('updateLoader', false);
})();

function onSubmitDone(success){
  if (success){
      $router.push(props.exitRoute);
  }
}
</script>

<template>
  <FormRequest
    id="pageForm"
    :action="'/api/person/'+id"
    kind="O cadastro"
    :fields="fields"
    title-field="name"
    @valuesReady="onValuesReady"
    @onSubmitDone="onSubmitDone">
    <template v-slot:fields="values">
      <div class="row gx-5">
        <div class="col-5">
          <div class="row">
            <FormInput id="name" v-model="values.fields.name" :invalid-feedback="values.validation.name"
              label="Nome Completo"></FormInput>
          </div>
          <div class="row">
            <FormInput id="cpf" v-model="values.fields.cpf" :invalid-feedback="values.validation.cpf" label="CPF">
            </FormInput>
          </div>
          <div class="row">
            <FormInput col="6" id="rg" v-model="values.fields.rg" :invalid-feedback="values.validation.rg" label="RG">
            </FormInput>
            <FormInput col="6" id="rgexp" v-model="values.fields.rgexp" :invalid-feedback="values.validation.rgexp"
              label="Órgão Expedidor"></FormInput>
          </div>
          <div class="row">
            <FormInput id="pis" v-model="values.fields.pis" col="6"
              :invalid-feedback="values.validation.pis" label="PIS"></FormInput>
            <FormInput id="registration_number" v-model="values.fields.registration_number" col="6"
              :invalid-feedback="values.validation.registration_number" label="Matrícula do Empregado">
            </FormInput>
          </div>
          <div class="row">
            <FormInput col="6" id="phone1" v-model="values.fields.phone1" :invalid-feedback="values.validation.phone1"
              label="Telefone"></FormInput>
            <FormInput col="6" id="phone2" v-model="values.fields.phone2" :invalid-feedback="values.validation.phone2"
              label="Celular"></FormInput>
          </div>
          <div class="row">
            <div class="col-12">
              <label>Projeto</label>
              <FormDropdown id="project_id" v-model="values.fields.project_id" :invalid-feedback="values.validation.project_id" :options="dropdown.projects"></FormDropdown>
            </div>
          </div>
        </div>
        <div class="col-3">
          <div class="row">
            <div class="col-12">
              <label>Banco</label>
              <FormDropdown id="bank_id" v-model="values.fields.bank_id" :invalid-feedback="values.validation.bank_id" :options="dropdown.banks"></FormDropdown>
            </div>
          </div>
          <div class="row">
            <FormInput id="bank_agency" v-model="values.fields.bank_agency" :invalid-feedback="values.validation.bank_agency"
              label="Agência"></FormInput>
          </div>
          <div class="row">
            <FormInput id="bank_account" v-model="values.fields.bank_account" :invalid-feedback="values.validation.bank_account"
              label="Conta com Dígito"></FormInput>
          </div>
          <div class="row">
            <div class="col-12">
              <label>Bateria de Pagamento</label>
              <FormDropdown id="battery_id" v-model="values.fields.battery_id" :invalid-feedback="values.validation.battery_id" :options="dropdown.batteries"></FormDropdown>
            </div>
          </div>
        </div>
        <div class="col-4">
          <div class="row">
            <FormInput id="email" type="email" v-model="values.fields.email" :invalid-feedback="values.validation.email"
              label="E-mail"></FormInput>
          </div>
          <div class="row">
            <div class="col-12">
              <label>Cargo ou Função</label>
              <FormDropdown id="job_id" v-model="values.fields.job_id" :invalid-feedback="values.validation.job_id" :options="dropdown.jobs"></FormDropdown>
            </div>
          </div>
          <div class="row">
            <FormInput id="admission_date" v-model="values.fields.admission_date" col="12"
              :invalid-feedback="values.validation.admission_date" label="Data de Admissão"
              type="date"></FormInput>
          </div>
          <div class="row">
            <NumericInput id="salary" decimals="2" col="12" v-model="values.fields.salary"
            :invalid-feedback="values.validation.salary" label="Salário Bruto (R$)"
            :strip-trailing-zeros="false"></NumericInput>
          </div>
          <div class="row" v-if="editing">
            <FormRadioGroup label="Situação" id="status" v-model="values.fields.status" :invalid-feedback="values.validation.status" :items="dropdown.status"></FormRadioGroup>
          </div>
        </div>
      </div>
      <hr class="my-4 bg-light" />
      <div class="row">
        <div class="d-flex justify-content-center">
          <button type="submit" class="wide-button btn btn-success mx-1">Gravar</button>
          <RouterLink class="wide-button btn btn-danger mx-1" to="/">Sair</RouterLink>
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