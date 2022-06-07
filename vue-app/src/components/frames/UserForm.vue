<script setup>
import FormRequest from '../standard/FormRequest.vue';
import FormInput from '../standard/FormInput.vue';
import FormDropdown from '../standard/FormDropdown.vue';
import { unref } from 'vue';
import { useRoute } from 'vue-router';
import consts from '../../assets/js/consts';
import FormRadioGroup from '../standard/FormRadioGroup.vue';

const props = defineProps({
    'user': {
        type: Object,
        default: { id: 'new', role: 1, status: 1 }
    },
    'route': {
        type: String,
        default: '/api/user/{id}'
    },
    'exitRoute': Object,
});

const fields = JSON.parse(JSON.stringify(unref(props.user)));
fields.password = fields.password || '';
fields.confirm = fields.confirm || '';

const $router = useRoute();

const dropdown = {
    types: new Map(consts.Roles),
    status: new Map(consts.Status),
};

function onSubmitDone(success) {
    if (success) {
        $router.push({ name: 'listUser' });
    }
}
</script>
<template>
    <FormRequest id="pageForm" :action="route.replace('{id}', user.id)"
        kind="O usuário" :fields="fields" title-field="username"
        @on-submit-done="onSubmitDone">
        <template v-slot:fields="values">
            <div class="row gx-5">
                <div class="col-4">
                    <div class="row">
                        <FormInput id="username"
                            v-model="values.fields.username"
                            :invalid-feedback="values.validation.username"
                            label="Usuário"></FormInput>
                    </div>
                    <div class="row">
                        <FormInput type="password" id="password"
                            v-model="values.fields.password"
                            :invalid-feedback="values.validation.password"
                            label="Senha"></FormInput>
                    </div>
                    <div class="row">
                        <FormInput type="password" id="password_confirmation"
                            v-model="values.fields.confirm"
                            :invalid-feedback="values.validation.confirm"
                            label="Repetir Senha"></FormInput>
                    </div>
                    <div class="row">
                        <FormInput id="email" v-model="values.fields.email"
                            :invalid-feedback="values.validation.email"
                            label="E-mail"></FormInput>
                    </div>
                    <div class="row">
                        <FormInput id="name" v-model="values.fields.name"
                            :invalid-feedback="values.validation.name"
                            label="Nome Completo"></FormInput>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label>Perfil</label>
                            <FormDropdown id="role" v-model="values.fields.role"
                                :invalid-feedback="values.validation.role"
                                :options="dropdown.types"></FormDropdown>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="row">
                        <FormRadioGroup id="status"
                            v-model="values.fields.status"
                            :invalid-feedback="values.validation.status"
                            :items="dropdown.status"
                            label="Situação">
                        </FormRadioGroup>
                    </div>
                </div>
            </div>
            <hr class="my-4 bg-light" />
            <div class="row">
                <div class="d-flex justify-content-center">
                    <button type="submit"
                        class="wide-button btn btn-success mx-1">Gravar</button>
                    <RouterLink class="wide-button btn btn-danger mx-1"
                        :to="exitRoute">Sair</RouterLink>
                </div>
            </div>
        </template>
    </FormRequest>
</template>
<style scoped>
.wide-button {
    min-width: 200px;
}

.row {
    margin-top: 0.5rem;
}
</style>