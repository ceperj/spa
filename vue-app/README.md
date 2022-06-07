# SPA Vue App

Esta parte do repositório é referente à aplicação front-end (exceto a página de
*login* que é feita na forma de *view* do Laravel).

## Instalação

Para instalar a aplicação pela primeira vez, baixando suas dependências, execute:

    npm install

Para gerar seus arquivos dentro do diretório `../public/app` sempre que a aplicação for modificada, execute o comando:

    npm run buildClear

Este comando é uma modificação de `npm run build` que irá remover os arquivos atuais e construir novos, garantindo que não sobre "lixo" de gerações anteriores.

<br>

---

---

**O documento a seguir é a versão do README original, criado ao instalar uma aplicação Vue 3.**

---

## Recommended IDE Setup

[VSCode](https://code.visualstudio.com/) + [Volar](https://marketplace.visualstudio.com/items?itemName=johnsoncodehk.volar) (and disable Vetur) + [TypeScript Vue Plugin (Volar)](https://marketplace.visualstudio.com/items?itemName=johnsoncodehk.vscode-typescript-vue-plugin).

## Customize configuration

See [Vite Configuration Reference](https://vitejs.dev/config/).

## Project Setup

```sh
npm install
```

### Compile and Hot-Reload for Development

```sh
npm run dev
```

### Compile and Minify for Production

```sh
npm run build
```

### Lint with [ESLint](https://eslint.org/)

```sh
npm run lint
```
