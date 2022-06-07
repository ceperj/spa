<script setup>
    import { unref } from 'vue';
    import consts from '../../assets/js/consts';
    import FormFormattedInput from './FormFormattedInput.vue';

    const props = defineProps({
        'id': String,
        'label': String,
        'col': { default: '12' },
        'classes': { type: Object, default: {} },
        'invalidFeedback': { default: null },
        'modelValue': {},
        'decimals' : { type:Number, default: 2 }
    });
    const emit = defineEmits(['update:modelValue']);    
    const decimalsCount = unref(props.decimals) |0;
    const factor = Math.pow(10, decimalsCount);
    const imprecision = 0.00001;

    function emitValue(value){
        emit('update:modelValue', value);
    }

    function readyToEditable(modelValue){
        return (modelValue / factor).toString();
    }

    function editableToReady(value){
        const num = Number((''+value).replace(',', '.').replace('%', '').trim());
        const ready = Number(num) * factor;
        return Number.isNaN(ready) ? ready : (ready + imprecision | 0);
    }

    function isValid(modelValue){
        return ! Number.isNaN(+modelValue);
    }

    function readyToDisplay(modelValue){
        return (modelValue / factor).toLocaleString(consts.lang, {
            minimumFractionDigits: 0,
            maximumFractionDigits: decimalsCount,
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
    
    error-message="A porcentagem (0 a 100) não é válida. Não inclua separador de milhares ou texto."
    :is-valid="isValid"
    :ready-to-editable="readyToEditable"
    :ready-to-display="readyToDisplay"
    :editable-to-ready="editableToReady">
    <template v-slot:after>
        <span class="input-group-text">%</span>
    </template>
</FormFormattedInput>
</template>