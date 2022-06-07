<template>
    <header>
        <div class="d-flex justify-content-between">
            <img src="@/assets/logo.png" class="my-2" height="60" />
            <slot name="login"></slot>
        </div>
        <div class="pt-3">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-start">
                    <ul class="nav col-12 justify-content-center text-small">
                        <li class="d-flex mx-1 mb-1">
                            <RouterLink to="/cadastrar-projeto" active-class="btn-primary text-white" class="btn btn-outline-primary d-flex flex-column justify-content-center px-4">
                                <div>Cadastro</div>
                                <small class="small">Projeto</small>
                            </RouterLink>
                        </li>
                        <li class="d-flex mx-1 mb-1">
                            <RouterLink to="/cadastrar-pessoa-fisica" active-class="btn-primary text-white" class="btn btn-outline-primary d-flex flex-column justify-content-center px-4">
                                <div>Cadastro</div>
                                <small class="small">Pessoa Física</small>
                            </RouterLink>
                        </li>
                        <li class="d-flex mx-1 mb-1">
                            <RouterLink to="/relatorios" active-class="btn-primary text-white" class="btn btn-outline-primary d-flex flex-column justify-content-center px-4">
                                <div>Relatórios</div>
                            </RouterLink>
                        </li>
                        <li class="d-flex mx-1 mb-1">
                            <RouterLink to="/gerar-folha" active-class="btn-success text-white" class="btn btn-outline-success d-flex flex-column justify-content-center px-4">
                                <div>Gerar Folha</div>
                            </RouterLink>
                        </li>
                        <li class="d-flex mx-1 mb-1">
                            <RouterLink to="/alterar-parametros" active-class="btn-danger text-white" class="btn btn-outline-danger d-flex flex-column justify-content-center px-4">
                                <div>Alterar parâmetros</div>
                            </RouterLink>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <hr>
    </header>
</template>