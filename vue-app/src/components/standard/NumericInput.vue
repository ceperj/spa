<script setup>
import { reactive, unref } from 'vue';
import consts from '../../assets/js/consts';
import FormattedInput from './FormattedInput.vue';

const props = defineProps({
    'prefix': {default:false},
    'sufix': {default:false},
    'decimals': {type: Number, required: true},
    'stripTrailingZeros': {type: Boolean, default: false},
    'displayedAs': {type:Function, default:null},

    // inherit from FormattedInput (FormInput)
    'id': String,
    'label': String,
    'col': {default:'12'},
    'invalidFeedback': { default: null },
    'modelValue': {},
    'classes': { default: {} },
});
const decimals = unref(props.decimals);
const emit = defineEmits(['update:modelValue']);
const pattern = /^(\d+)(?:[.,](\d*))?$/;
const data = reactive({
    errorMessage: 'Valor inválido.'
});

/**
 * Transforma o valor da variável em um valor amigável para a edição do campo.
 */
function readyToEditable(value){
    const str = ''+value;

    if (! str.match(/^\d+$/))
        return value;
    
    // Ex.: 1 será 0,01, 10 será 0,10, e 0 será 0,00
    if (str.length <= decimals)
    {
        const onlyPart = str.padStart(decimals, '0');
        updateModelValue('0'+onlyPart);
        return '0' + consts.intl.decimalSeparator + onlyPart;
    }
    
    let integerPart = str.substr(0, str.length - decimals);
    let decimalPart = str.substr(-decimals);
    
    if (props.stripTrailingZeros)
        decimalPart = decimalPart.replace(/0+$/, '');

    if (decimalPart)
        decimalPart = consts.intl.decimalSeparator + decimalPart;

    return integerPart + decimalPart;
}

/**
 * Transforma o valor digitado pelo usuário em um valor amigável para
 * armazenamento na variável do v-model.
 */
function editableToReady(value){
    const match = (''+value).trim().match(pattern);
    if (! match)
    {
        data.errorMessage = getErrorForValue(value);
        return value;
    }

    const integerPart = match[1].replace(/^0+/, '') || '0';
    const decimalsPart = (match[2] || '').substr(0, decimals).padEnd(decimals, '0');
    return integerPart + decimalsPart;
}

/**
 * Transforma o valor da variável v-model, se válido de acordo com `isValid`, em
 * um valor amigável para a exibição na tela fora da edição. Pode diferir do
 * valor armazenado e do valor na edição.
 */
function readyToDisplay(value){
    const text = readyToEditable(value);
    if (props.displayedAs)
        return props.displayedAs(text, value);
    return text;
}

/**
 * Valida se o valor armazenado (seja dado pelo servidor ou pela última edição
 * do usuário) está em um formato válido.
 */
function isValid(readyValue){
    const str = (''+readyValue).trim();
    return str.match(/^[\d]+$/);
}

/**
 * Retorna um texto informativo de acordo com o tipo de erro que o valor contém.
 */
function getErrorForValue(invalidValue)
{
    const str = ''+invalidValue;
    if (! str)
        return null;

    if (str.match(/[-+]/))
        return 'Informe somente o número, sem sinal (+ e -).';
    
    if (str.match(/[.,]\d+[.,]/))
        return 'Não utilize separador de milhares (.), e inclua apenas um separador decimal (,).';
    
    return 'Informe somente números e um separador decimal, mas não utilize separador de milhar, sinal ou outro caractere.';
}

function updateModelValue(readyValue, ...args){
    emit('update:modelValue', readyValue, ...args);
}
</script>
<template>
<input type="hidden" :value="modelValue" :name="id">
<FormattedInput
    name=""
    class="input-group"
    :classes="classes"
    :id="id"
    :label="label"
    :is-valid="isValid"
    :col="col"
    :ready-to-editable="readyToEditable"
    :editable-to-ready="editableToReady"
    :ready-to-display="readyToDisplay"
    :error-message="data.errorMessage"
    :invalid-feedback="invalidFeedback"
    :model-value="modelValue"
    @update:model-value="updateModelValue"
>
    <template v-slot:before>
        <div class="input-group-text" v-if="prefix !== false">{{ prefix }}</div>
    </template>
    <template v-slot:after>
        <div class="input-group-text" v-if="sufix !== false">{{ sufix }}</div>
    </template>
</FormattedInput>
</template>