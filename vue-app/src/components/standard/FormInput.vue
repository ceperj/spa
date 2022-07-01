<script setup>
defineProps({
  'id': String,
  'label': String,
  'type': {type: String, default: 'text'},
  'col': {default: '12'},
  'modelValue': {default: ''},
  'invalidFeedback': {default: null},
  'step': String,
  'min': String,
  'max': String,
  'inputClasses': {type: Object, default: {}},
  'name': {default: null},
});
defineEmits(['update:modelValue', 'focus', 'blur']);
</script>
<template>
  <div :class="'col-' + col">
    <slot name="label">
      <label :for="id" class="d-block w-100">{{ label }}</label>
    </slot>
    <slot name="before"></slot>
    <slot name="input">
      <input
        @input="ev => $emit('update:modelValue', ev.target.value)"
        @focus="(...args) => $emit('focus', ...args)"
        @blur="(...args) => $emit('blur', ...args)"
        :type="type"
        :value="modelValue"
        :id="id"
        :class="{'form-control':true, 'is-invalid': invalidFeedback, ...inputClasses}"
        :name="name ?? id"
        :step="step"
        :min="min"
        :max="max">
    </slot>
    <slot name="after"></slot>
    <label v-if="invalidFeedback" class="invalid-feedback" :for="id">{{ invalidFeedback }}</label>
  </div>
</template>