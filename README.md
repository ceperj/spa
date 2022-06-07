<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

# Sistema de Pagamentos Avulsos

## Instalação

Para instalar a aplicação, há alguns passos que devem ser seguidos. Siga a ordem dos passos, haverá orientações adicionais em cada um e notificaremos de qualquer risco que possa haver.

### Requerimentos

Para a instalação do Laravel, além dos requerimentos padrões do framework, também são necessárias as ferramentas [Composer](https://getcomposer.org/) e [NPM](https://www.npmjs.com/). Garanta que estejam atualizadas antes de prosseguir.

### Passo 1. Instalação dos Arquivos

Para baixar os arquivos do Laravel e todas as suas dependências, entre no diretório da aplicação Laravel e execute os seguintes comandos:

    composer install
    npm install

Com o Laravel instalado, coloque a aplicação em modo de manutenção:

    php artisan down

### Passo 2. Configurações iniciais

Para adicionar as configurações iniciais iremos copiar um arquivo `.env` de exemplo e, em seguida, gerar uma chave aleatória para o campo APP_KEY:

    cp .env.example .env
    php artisan key:generate

O comando `key:generate` pode pedir confirmação se o Laravel detectar que está em ambiente de produção. Confirme e gere a chave. Você pode verificar sua geração no arquivo `.env`, campo `APP_KEY`.

A partir deste momento, algumas configurações adicionais são necessárias. Abra o arquivo `.env` dentro da raiz do projeto Laravel e configure os seguintes campos:

    APP_URL=http://...
    ...
    SESSION_DOMAIN=...
    SANCTUM_STATEFUL_DOMAINS=http://...
    ...
    DB_CONNECTION=mysql
    DB_HOST=...
    DB_PORT=...
    DB_DATABASE=...
    DB_USERNAME=...
    DB_PASSWORD=...
    ...
    CACHE_DRIVER=file
    FILESYSTEM_DISK=local
    ...
    SESSION_DRIVER=file
    SESSION_LIFETIME=360

Estes não são todos os campos do arquivo, mas são os utilizados. Outras configurações, como Redis e SMTP são possíveis, mas desnecessárias, pois a aplicação não utilizará tais serviços.

### Passo 3. Geração do Banco de Dados

Neste passo, a conexão com o Banco de Dados é requerida. As configurações acima devem ser suficientes para também configurar tal conexão, mas quaisquer problemas de conectividade devem ser tratados.

/!\\ Este passo oferece algum risco, pois criará tabelas no Banco de Dados, alterando sua estrutura. É recomendando, neste ponto, que o Banco de Dados esteja vazio ou que o comando seja executado em um ambiente de testes para depois ser transportado ao banco. Alguns arquivos do `artisan` podem, inclusive, destruir o banco de dados, mas por hora utilizaremos apenas um comando não-destrutivo que deve somente acrescentar.

Para criar as tabelas no banco, execute:

    php artisan migrate

Se o comando for bem-sucedido, o banco de dados estará pronto para o próximo passo. Caso contrário, verifique a conectividade e configurações da aplicação no arquivo `.env`.

### Passo 4. Criação de um usuário administrador

Para criar o primeiro usuário administrador do sistema, utilize o comando:

    php artisan createSuperUser

Siga as instruções e preencha os dados corretamente. Após o término do comando, um novo usuário será inserido na tabela `users`.

Se for necessário cancelar o comando em qualquer momento antes de finalizar, utilize a combinação `Ctrl+C` para forçar sua parada. O usuário é criado apenas ao final do formulário.

### Passso 5. Publicar aplicação front-end

Até este ponto, já temos uma aplicação Laravel back-end funcional (em modo de manutenção), inclusive a tela de login, mas ainda não temos a interface do sistema para acessar.

Entre na pasta do Vue, com `cd vue-app` e execute os seguintes comandos:

    npm install
    npm run buildClear

Este comando é uma modificação de `npm run build` e irá remover qualquer arquivo previamente gerado (somente do Vue) e gerar novamente toda a aplicação.

### Instalação Concluída

Agora que você já instalou e configurou o Laravel, criou o banco de dados e um usuário, e publicou a aplicação front-end, tire a aplicação do modo de manutenção:

    php artisan up

Se tudo deu certo, você já pode acessar e utilizar a aplicação. Comece fazendo *login* com o super-usuário e cadastrando os demais utilizadores.

---

## Laravel

*(Trecho retirado do README original)*

Laravel is a web application framework with expressive, elegant syntax.

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

More information at https://laravel.com/.

---