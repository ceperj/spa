export default {
    /**
     * Retorna um objeto cujos nomes de campos são dados pelo array de string,
     * e valores dos campos são `defaultValue`.
     * 
     * Por exemplo, o array `['a', 'b', 'c']` e defaultValue `50` retornará um
     * objeto `{a:50, b:50, c:50}`.
     * 
     * @param {Array} array      Os nomes dos campos.
     * @param {Any} defaultValue O valor padrão de cada campo criado.
     * @returns Object           O objeto criado.
     * 
     * @see https://stackoverflow.com/a/54789452/2084091
     */
    arrayAsObjectKeys(array, defaultValue){
        return array.reduce((result, entry) => {
            result[entry] = defaultValue;
            return result;
        }, {});
    },

    /**
     * Preenche todos os campos de um objeto com o valor padrão.
     * 
     * @param {Object} obj Objeto cujos campos devem ser ajustados.
     * @param {Any} defaultValue O valor para colocar em cada campo.
     */
    fillObject(obj, defaultValue){
        for (let field of Object.keys(obj)){
            obj[field] = defaultValue;
        }
    },

    /**
     * Lê uma resposta da Fetch API na forma de JSON, e se falhar, retorna o
     * texto como string.
     * 
     * @param {Response} response   O objeto de resposta da Fetch API.
     * @returns O valor JSON convertido, ou uma string com todo o texto.
     */
    async readJsonOrText(response){
        try{
            return await response.clone().json();
        }
        catch(e){
            return await response.text();
        }
    },

    _ordinals: [
        'Primeir@', 'Segund@', 'Terceir@', 'Quart@', 'Quint@',
        'Sext@', 'Sétim@', 'Oitav@', 'Non@', 'Décim@'],
    
    /**
     * Converte um número para uma string ordinal ("primeiro", "segundo",
     * "terceiro"), respeitando o artigo.
     * 
     * @param {Number} number Um número inteiro a partir de 1 que se tornará o ordinal.
     * @param {String} gender O artigo onde 'F' ou 'f' é feminino, e outros valores (ex.: 'm') é masculino.
     * @returns Um ordinal por extenso para valores de 1 a 10, e abreviado (ex.: 20º) para valores fora deste limite.
     */
    ordinalToStr(number, gender){
        let ao = gender.toLowerCase() === 'f' ? 'a' : 'o';
        let supAo = gender.toLowerCase() === 'f' ? 'ª' : 'º';
        return (this._ordinals[number] ?? number+'º')
            .replace('@', ao)
            .replace('º', supAo);
    },

    /**
     * Retorna uma `Promise` para um tempo de espera em funções async.
     */
    sleep(milliseconds)
    {
        return new Promise(resolve => setTimeout(resolve, milliseconds));
    },
}