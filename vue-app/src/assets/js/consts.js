const decimalSeparator = (1.1).toLocaleString().substring(1,2);

export default {
    get Status() { return [[1, 'Ativo'], [0, 'Inativo']] },
    get Roles() { return [[1, 'Usuário'], [2, 'Administrador']] },
    get lang() { return 'pt-br'; },
    get intl() {return{
        decimalSeparator,
        currency: 'BRL',
    }},
}