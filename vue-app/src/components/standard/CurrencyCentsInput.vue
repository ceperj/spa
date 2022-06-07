<script setup>
    import consts from '../../assets/js/consts';
    import FormFormattedInput from './FormFormattedInput.vue';

    defineProps({
        'id': String,
        'label': String,
        'col': { default: '12' },
        'classes': { type: Object, default: {} },
        'invalidFeedback': { default: null },
        'modelValue': {},
    });
    const emit = defineEmits(['update:modelValue']);    
    const imprecision = 0.00001;

    function emitValue(value){
        emit('update:modelValue', value);
    }

    function readyToEditable(modelValue){
        return (modelValue / 100)
            .toFixed(2)
            .replace('.', consts.intl.decimalSeparator);
    }

    function editableToReady(value){
        const num = Number((''+value).replace(',', '.'));
        const ready = Number(num) * 100;
        return Number.isNaN(ready) ? ready : (ready  + imprecision | 0);
    }

    function isValid(modelValue){
        return ! Number.isNaN(+modelValue);
    }

    function readyToDisplay(modelValue){
        return (modelValue / 100).toLocaleString(consts.lang, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
            useGrouping: false,
        });
    }
</script>
<template>
<FormFormattedInput
    :id="id"
    :label="label"
    :col="col"
    :classes="classes"
    :invalid-feedback="invalidFeedback"
    :model-value="modelValue"
    @update:model-value="emitValue"
    
    error-message="O valor monetário não é válido. Não inclua letras ou separadores de milhares (.)."
    :is-valid="isValid"
    :ready-to-editable="readyToEditable"
    :ready-to-display="readyToDisplay"
    :editable-to-ready="editableToReady">
    <template v-slot:before>
        <span class="input-group-text">R$</span>
    </template>
</FormFormattedInput>
</template>