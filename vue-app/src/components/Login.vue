<script setup>
/*
 |--------------------------------------------------------------|
 | Login
 |--------------------------------------------------------------|
 |
 | Este componente possui diversas responsabilidades. Optou-se
 | por elevar um pouco sua complexidade, abstraindo muito do
 | processo de autenticação do usuário e controle.
 |
 | Como responsabilidade, este componente:
 |  - Exibe o nome do usuário autenticado;
 |  - Exibe um menu para o usuário desautenticar-se;
 |  - Faz uma requisição ao servidor verificando se há um usuário
 |    autenticado, e que usuário é esse;
 |  - Redireciona para a tela do login, caso nenhum usuário esteja
 |    autenticado.
 */
import {ref, reactive} from 'vue';
import api from '../assets/js/api';

const emitUser = defineEmits(['user-loaded']);
const props = defineProps(['user']);
const data = reactive({
    user: ref(props.user),
});

(async function(){
    try{
        data.user = await api.getUser();
    } catch(e){
        console.error(e);
        console.log('Usuário não autenticado, ou houve um erro ao conectar na API. Retornando para a página de login.');
        window.location.href = "/login";
    }
    emitUser('user-loaded', data.user);
})();
</script>
<template>
    <div class="dropdown d-flex">
        <button class="btn d-flex flex-column justify-content-center px-4" id="loginButton" data-bs-toggle="dropdown">
            <div>Bem-vindo,</div>
            <div class="fw-bold">{{ data.user ? data.user.name : 'visitante' }}</div>
        </button>
        <ul class="dropdown-menu" aria-labelledby="loginButton">
            <li>
                <form method="post" action="/logout">
                    <button class="dropdown-item btn-link" type="submit">Sair</button>
                </form>
            </li>
        </ul>
    </div>
</template>
<style>
</style>