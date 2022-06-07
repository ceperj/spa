<script setup>
/*
 |--------------------------------------------------------------|
 | FormFormattedInput
 |--------------------------------------------------------------| 
 |
 | Este componente foi feito para servir de base aos componentes
 | CurrencyCentsInput e PercentageInput, que formatam os valores
 | utilizando uma máscara, e transformam na hora de editar e
 | ao concluir a edição.
 |
 | Há um exemplo de funcionamento na tabela de IRPF.
 |
 */
    import { computed, reactive, unref } from 'vue';
    import FormInput from './FormInput.vue';

    const props = defineProps({
        'id': String,
        'label': String,
        'type': { default:'text' },
        'col': { default: '12' },
        'modelValue': {},
        'invalidFeedback': { default: null }, // Server validation
        'classes': {type: Object, default: {}},
        'errorMessage': {type:String, default:'O valor não é válido.'}, // Client validation
        'isValid': {type: Function, default: ()=>true}, // Client validation
        'readyToEditable': {type:Function, default:v=>v},
        'editableToReady': {type:Function, default:v=>v},
        'readyToDisplay': {type:Function, default:v=>v},
    });
    const isValid = unref(props.isValid);
    const readyToEditable = unref(props.readyToEditable);
    const editableToReady = unref(props.editableToReady);
    const readyToDisplay = unref(props.readyToDisplay);
    const emit = defineEmits(['update:modelValue']);    
    const data = reactive({
        editing: false,
        error: null,
        input: unref(props.modelValue),
    });

    const displayModelValue = computed(() => {
        if (data.editing)
            return data.input;

        const ready = props.modelValue;
        return isValid(ready) ? readyToDisplay(ready) : ready;
    });

    function onFocus(){
        let modelValue = unref(props.modelValue);
        data.input = isValid(modelValue)
            ? readyToEditable(modelValue)
            : data.input;
        data.editing = true;
    }

    function onBlur(){
        data.editing = false;
    }

    function updateModelValue(value){
        if (! data.editing)
            return;
        const ready = editableToReady(value);
        data.input = value;
        data.error = isValid(ready) ? null : props.errorMessage;
        emit('update:modelValue', isValid(ready) ? ready : value);
    }
</script>
<template>
<FormInput
    :type="type"
    :class="{'input-group':true, ...classes}"
    :id="id"
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