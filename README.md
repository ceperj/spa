<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

# Sistema de Pagamentos Avulsos

## Instalação

Para instalar a aplicação, há alguns passos que devem ser seguidos. Siga a ordem dos passos, haverá orientações adicionais em cada um e notificaremos de qualquer risco que possa haver.

### Requerimentos

Para a instalação do Laravel, além dos requerimentos padrões do framework, também são necessárias as ferramentas [Composer](https://getcomposer.org/) e [NPM](https://www.npmjs.com/). Garanta que estejam atualizadas antes de prosseguir.

## Máquina Redhat 8

### Instalação PHP 8.0 e driver Mysql
    dnf update
    dnf module install php:8.0
    dnf install php-mysqlnd


Caso seja necessário PHP a partir de **8.1**, necessário instalar repositório REMI (pule os passos anteriores)

    dnf -y install https://rpms.remirepo.net/enterprise/remi-release-8.rpm
    dnf -y install dnf-utils
    dnf --enablerepo=remi module install php:8.2
    dnf install php82-php-mysqlnd
    ln -s /usr/bin/php82 /usr/bin/php

### Instalação Composer
Composer pode ser instalado localmente, ou universal na máquina, que será preferível.

    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
    mv composer.phar /usr/local/bin/composer

### Passos finais

Instalação de NPM, HTTPD (Apache), Git e Zip

    dnf install npm git httpd unzip

Atualizar versão de NodeJS (por padrão vem a 10 que é incompatível)

    dnf module switch-to nodejs:16

Clone o repositório e insira as credenciais necessárias

    git clone https://github.com/ceperj/spa/

Configure o httpd para o diretório spa/public, permitindo roteamento dos .htaccess (`AllowOverride All`).

Caso haja problemas de permissão(403), mesmo com diretório sob usuário apache, verificar permissões SELINUX (/etc/selinux/config), configurar `SELINUX=permissive` e reiniciar servidor. Também verificar firewall (`systemctl disable firewalld`)

### Instalação dos arquivos do Laravel

Para baixar os arquivos do Laravel e todas as suas dependências, entre no diretório da aplicação Laravel e execute os seguintes comandos:

    composer install
    npm install

Com o Laravel instalado, coloque a aplicação em modo de manutenção:

    php artisan down

### Configurações iniciais do Laravel

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
    QUEUE_CONNECTION=database
    SESSION_DRIVER=file
    SESSION_LIFETIME=360

Estes não são todos os campos do arquivo, mas são os utilizados. Outras configurações, como Redis e SMTP são possíveis, mas desnecessárias, pois a aplicação não utilizará tais serviços.

### Geração do Banco de Dados

Neste passo, a conexão com o Banco de Dados é requerida. As configurações acima devem ser suficientes para também configurar tal conexão, mas quaisquer problemas de conectividade devem ser tratados.

/!\\ Este passo oferece algum risco, pois criará tabelas no Banco de Dados, alterando sua estrutura. É recomendando, neste ponto, que o Banco de Dados esteja vazio ou que o comando seja executado em um ambiente de testes para depois ser transportado ao banco. Alguns arquivos do `artisan` podem, inclusive, destruir o banco de dados, mas por hora utilizaremos apenas um comando não-destrutivo que deve somente acrescentar.

Para criar as tabelas no banco, execute:

    php artisan migrate

Se o comando for bem-sucedido, o banco de dados estará pronto para o próximo passo. Caso contrário, verifique a conectividade e configurações da aplicação no arquivo `.env`.

### Criação de um usuário administrador

Para criar o primeiro usuário administrador do sistema, utilize o comando:

    php artisan createSuperUser

Siga as instruções e preencha os dados corretamente. Após o término do comando, um novo usuário será inserido na tabela `users`.

Se for necessário cancelar o comando em qualquer momento antes de finalizar, utilize a combinação `Ctrl+C` para forçar sua parada. O usuário é criado apenas ao final do formulário.

### Publicar aplicação front-end

Até este ponto, já temos uma aplicação Laravel back-end funcional (em modo de manutenção), inclusive a tela de login, mas ainda não temos a interface do sistema para acessar.

Entre na pasta do Vue, com `cd vue-app` e execute os seguintes comandos:

    npm install
    npm run buildClear

Este comando é uma modificação de `npm run build` e irá remover qualquer arquivo previamente gerado (somente do Vue) e gerar novamente toda a aplicação.

### Instalação concluída

Agora que você já instalou e configurou o Laravel, criou o banco de dados e um usuário, e publicou a aplicação front-end, tire a aplicação do modo de manutenção:

    php artisan up

Se tudo deu certo, você já pode acessar e utilizar a aplicação. Comece fazendo *login* com o super-usuário e cadastrando os demais utilizadores.

---

## Atualização

Quando o código-fonte é alterado, o PHP estará sempre pronto para servir o novo
código. Outras partes do sistema, porém, podem requer passos adicionais para
atualização.

Por isso, antes de atualizar recomenda-se colocar o sistema em modo de manutenção:

    php artisan down

Mudanças no arquivo `.env` devem ser aplicadas manualmente. As demais mudanças
podem ser enviadas para o servidor através do Git.

Após atualizar o código no servidor, os seguintes passos devem ser executados
a partir da pasta do Laravel:

    php artisan migrate
    cd vue-app
    npm run buildClear
    cd ..

A execução das _migrations_ irá atualizar o banco de dados.
**É recomendado que exista um backup do banco de dados antes de executar este passo.**
_Migrations_ irão, normalmente, criar novas tabelas ou adicionar campos às tabelas.
Contudo, como a alteração ocorre na estrutura do banco de dados, é recomendado estar
preparado para imprevistos. Além disso, quaisquer exceções ao executar uma _migration_
devem ser tratados antes de uma nova tentativa de executá-las.

Como o Laravel mantém uma tabela de registro, _migrations_ já aplicadas não serão
executadas novamente. Apenas migrations ainda não aplicadas serão executadas.

Já a construção do front-end através do comando `buildClear` é similar ao passo
da instalação, onde os arquivos gerados previamente em `./public/app` serão
excluídos e novos arquivos serão gerados. Esta exclusão é desejável, pois todos
os arquivos de tal pasta foram gerados automaticamente, e sua exclusão evita que
reste "lixo" ao gerar novos _hashes_ para os arquivos.

Para finalizar, estando na pasta do Laravel, reativa-se a aplicação:

    php artisan up

## Laravel

*(Trecho retirado do README original)*

Laravel is a web application framework with expressive, elegant syntax.

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

More information at https://laravel.com/.

---