<script setup>
import consts from '../../assets/js/consts';
import FormRequest from '../standard/FormRequest.vue';
import FormInput from '../standard/FormInput.vue';
import FormRadioGroup from '../standard/FormRadioGroup.vue';
import { unref } from 'vue';
import utils from '../../assets/js/utils';

const minimumNumberOfBatteries = 4;

const props = defineProps({
  'batteries': {
    type: Array,
    default: [],
  },
  'route': {
    type: String,
    default: '/api/batteries'
  },
  'exitRoute': Object,
});

const emit = defineEmits(['onSubmitDone']);

const fields = JSON.parse(JSON.stringify(unref(props.batteries)));
for (let i = fields.length; i < minimumNumberOfBatteries; i++)
{
  let title = utils.ordinalToStr(fields.length, 'f');
  fields.push({ title: title + ' Bateria', date: '', status: 1});
}

const items = {
  status: new Map(consts.Status),
}

function onSubmitDone(success) {
  emit('onSubmitDone', success);
}
</script>
<template>
  <FormRequest id="pageForm" :action="route" kind="O conjunto" :fields="fields" title-field="title"
    @on-submit-done="onSubmitDone">
    <template v-slot:fields="values">
      <h3>Bateria de Pagamento</h3>
      <div class="row gx-5">
        <div class="col-4">
          <div class="row gx-5" v-for="(battery, index) of values.fields">
            <div><strong>{{ battery.title }}</strong></div>
            <FormInput :id="'battery['+index+'][date]'" col="6" type="number" min="1" max="31" v-model="battery.date" :invalid-feedback="values.validation['battery.'+index+'.date']" label="Dia do Mês"></FormInput>
            <FormRadioGroup :id="'battery['+index+'][status]'" col="6" v-model="battery.status" :items="items.status" :invalid-feedback="values.validation['battery.'+index+'.status']" label="Situação"></FormRadioGroup>
            <hr class="mt-3">
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

strong{
  margin-bottom: 0.5em;
}

.row {
  margin-top: 0.5rem;
  margin-bottom: 1rem;
}
</style>