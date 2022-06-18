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
     * Torna uma data no formato PHP 'Y-m-d H:i:s', retornada propositalmente
     * assim para padronização do sistema, em algo mais visualmente familiar
     * para exibir na tela.
     * 
     * @param {string} YmdHis A data no formato 'Y-m-d H:i:s', onde 's' é opcional.
     * @return O texto com a data mais legível. Caso não esteja no formato esperado,
     *         o próprio parâmetro YmdHis é retornado.
     */
    brazilianizeDateYmdHis(YmdHis)
    {
        const match = (''+YmdHis).trim().match(/^(\d+)-(\d+)-(\d+)\s*(\d+):(\d+)(?::(\d+))$/);
        if (! match) return YmdHis;
        const Y = match[1]|0;
        const m = match[2]|0;
        const d = match[3]|0;
        const H = match[4]|0;
        const i = match[5]|0;
        const pad = (s,l) => (''+s).padStart(l, '0');
        return `${pad(d,2)}/${pad(m,2)}/${pad(Y,4)} às ${pad(H,2)}:${pad(i,2)}h`;
    },

    /**
     * Returns a Promise for an async sleep time.
     */
    sleep(milliseconds)
    {
        return new Promise(resolve => setTimeout(resolve, milliseconds));
    },
}