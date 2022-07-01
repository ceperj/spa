<script setup>
/*
 |--------------------------------------------------------------|
 | FormattedInput
 |--------------------------------------------------------------|
 |
 | Este componente foi feito para servir de base aos componentes
 | CurrencyCentsInput e PercentageInput, que formatam os valores
 | utilizando uma máscara, e transformam na hora de editar e
 | ao concluir a edição.
 |
 | Há um exemplo de funcionamento na tabela de IRPF.
 |
 | Este componente introduz algumas palavras-chave. Entendê-las
 | ajudará a compreender o código dos componentes que o usam:
 |
 | display ..... É o valor exibido na tela para o usuário quando
 |               não há edição em andamento. Pode diferir do
 |               valor gravado na variável do v-model. Somente
 |               valores válidos são transformados para exibir
 |               na tela, valores inválidos são exibidos como são.
 |
 | editable .... Valor que aparece quando o usuário está editando
 |               o campo. Pode diferir de "display" e "ready".
 | 
 | ready ....... Valor gravado na variável v-model, atualizado
 |               antes da edição (com o valor emitido pelo
 |               servidor), ou após a última edição ter sido
 |               finalizada.
 |
 | valid ....... Aplicado sobre "ready" para garantir que o valor
 |               gravado na variável atenda ao formato esperado.
 |
 */
    import { computed, reactive } from 'vue';
    import utils from '../../assets/js/utils';
    import FormInput from './FormInput.vue';

    const props = defineProps({
        'errorMessage': {type:String, default:'O valor não é válido.'}, // Client validation
        'isValid': {type: Function, default: ()=>true}, // Client validation
        'readyToEditable': {type:Function, default:v=>v},
        'editableToReady': {type:Function, default:v=>v},
        'readyToDisplay': {type:Function, default:v=>v},
        
        // inherit from FormInput
        'id': String,
        'name': {default:null},
        'label': String,
        'type': { default:'text' },
        'col': { default: '12' },
        'classes': {type: Object, default: {}},
        'invalidFeedback': { default: null }, // Server validation
        'modelValue': {},
    });
    const isValid = props.isValid;
    const readyToEditable = props.readyToEditable;
    const editableToReady = props.editableToReady;
    const readyToDisplay = props.readyToDisplay;
    const emit = defineEmits(['update:modelValue']);    
    const data = reactive({
        editing: false,
        error: null,
        input: props.modelValue,
    });

    const displayModelValue = computed(() => {
        if (data.editing)
            return data.input;

        const ready = props.modelValue;
        return isValid(ready) ? readyToDisplay(ready) : ready;
    });

    function onFocus(){
        let modelValue = props.modelValue;
        data.input = isValid(modelValue)
            ? readyToEditable(modelValue)
            : data.input;
        data.editing = true;
    }

    function onBlur(){
        data.editing = false;
    }

    async function updateModelValue(value){
        if (! data.editing)
            return;
        const ready = editableToReady(value);
        data.input = value;
        emit('update:modelValue', ready);
        await utils.sleep(0);
        data.error = isValid(ready) ? null : props.errorMessage;
    }
</script>
<template>
<FormInput
    :type="type"
    :class="{'input-group':true, ...classes}"
    :id="id"
    :name="name"
    :col="col"
    :label="label"
    :input-classes="{ 'text-end': true }"
    :invalid-feedback="invalidFeedback ?? data.error"
    :model-value="displayModelValue"
    @update:model-value="updateModelValue"
    @focus="onFocus"
    @blur="onBlur"
>
    <template v-slot:before><slot name="before"></slot></template>
    <template v-slot:after><slot name="after"></slot></template>
</FormInput>
</template>