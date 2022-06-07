<script setup="props">
  import { computed, reactive } from 'vue';

  const minimumOptionsSize = 10;

  let props = defineProps({
    'id' : String,
    'options' : Map,
    'name' : String,
    'modelValue' : Object,
    'invalidFeedback': {default: null},
    'search' : {
      type: Boolean,
      default: true,
    },
  });

  defineEmits(['update:modelValue']);

  const data = reactive({
    searchText: ''
  });

  const nameOrId = computed(() => ! props.name ? props.id : props.name);

  function filterMap(map, predicate){
    let exploded = [...map];
    let filtered = exploded.filter(predicate);
    return new Map(filtered);
  }

  const optionFilter = item => (''+item[1]).toLowerCase().includes(data.searchText);
  
  const filteredOptions = computed(() =>
    ! props.search || ! data.searchText || (props.options.size < minimumOptionsSize)
      ? props.options
      : filterMap(props.options, optionFilter));
</script>
<template>
  <div class="dropdown">
    <input type="hidden" :id="id" :name="nameOrId" :value="modelValue">
    <button
      type="button"
      class="form-select text-start"
      data-bs-toggle="dropdown"
      aria-expanded="false"
      :id="id+'-dropdown'"
    >
      <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ options.get(modelValue) ?? "&nbsp;" }}</div>
    </button>
    <ul class="dropdown-menu shadow p-1 overflow-auto" style="max-height: 200px;" :aria-labelledby="id+'-dropdown'">
      <li v-if="search && options.size >= minimumOptionsSize">
        <div class="mb-2 p-2">
          <input type="search" class="form-control" placeholder="Busque um item&hellip;" v-model="data.searchText">
        </div>
      </li>
      <li v-for="[key, value] in filteredOptions"><a class="dropdown-item" href="#" @click.prevent="$emit('update:modelValue', key)">{{ value }}</a></li>
      <li v-if="!options.size"><p class="m-4 text-muted">Nenhum item foi registrado para esta caixa de seleção.</p></li>
      <li v-if="options.size && ! filteredOptions.size"><p class="m-2 text-muted">Nenhum resultado para a busca.</p></li>
    </ul>
    <label v-if="invalidFeedback" class="small text-danger">{{ invalidFeedback }}</label>
  </div>
</template>