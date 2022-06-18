<script setup>
import { reactive, ref, toRaw } from 'vue';

const props = defineProps({
    title: String,
    id: { default: '' },
    cancel: { default: 'Cancelar' },
    buttons: { type: Array, default: [] }
});
const emit = defineEmits(['modalResult']);
defineExpose({ show });
const data = reactive({ result: null })

const modal = ref(null);
let bs = null;

/**
 * Acessível ao programador através do "ref".
 */
function show(){
    data.result = {cancel:true};
    bs = new bootstrap.Modal(modal.value, {
        keyboard: !! props.cancel,
        backdrop: (props.cancel ? true : 'static'),
    });
    bs.show();
}

/**
 * Chamado pelo usuário ao clicar em um botão do modal, exceto "Cancelar".
 */
function onClickButton(index){
    data.result = { button: index, cancel:false };
    bs.hide();
}

/**
 * Chamado pelo usuário ao fechar o modal clicando em um botão ou através dos
 * métodos da tecla ESC ou backdrop não-estático.
 */
function onModalHide(){
    emit('modalResult', toRaw(data.result));
}
</script>
<template>
    <div ref="modal" :id="id" class="modal" tabindex="-1" v-on="{'hide.bs.modal': onModalHide}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ title }}</h5>
                    <button v-if="!!cancel" type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <slot></slot>
                </div>
                <div class="modal-footer">
                    <button v-for="(button, index) in buttons"
                        type="button"
                        class="btn"
                        @click="onClickButton(index)"
                        :class="{['btn-'+button.theme]:(!!button.theme)}"
                    >{{ button.text ?? button }}</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ cancel }}</button>
                </div>
            </div>
        </div>
    </div>
</template>