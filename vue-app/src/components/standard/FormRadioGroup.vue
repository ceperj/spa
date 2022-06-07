<script setup>
defineProps({
  id: String,
  label: String,
  items: Array,
  col: { default: '12' },
  invalidFeedback: { type: Boolean, default: false },
  modelValue: { default: undefined },
});
defineEmits(['update:modelValue']);
</script>
<template>
  <div :class="'col-' + col">
    <label class="d-block">{{ label }}</label>
    <div class="form-check mt-1" v-for="[value, label] of items">
      <input
        type="radio"
        :class="{ 'form-check-input': true, 'is-invalid': invalidFeedback }"
        :name="id"
        :id="id + value"
        :value="value" :checked="value === modelValue"
        @change="ev => (ev.target.checked && $emit('update:modelValue', value))">
      <label class="form-check-label" :for="id + value">{{ label }}</label>
    </div>
  </div>
</template>