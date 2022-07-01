export default new class {

    constructor() {
        this.monthNames = new Map([
            [1, 'Janeiro (mês 1)'],
            [2, 'Fevereiro (mês 2)'],
            [3, 'Março (mês 3)'],
            [4, 'Abril (mês 4)'],
            [5, 'Maio (mês 5)'],
            [6, 'Junho (mês 6)'],
            [7, 'Julho (mês 7)'],
            [8, 'Agosto (mês 8)'],
            [9, 'Setembro (mês 9)'],
            [10, 'Outubro (mês 10)'],
            [11, 'Novembro (mês 11)'],
            [12, 'Dezembro (mês 12)']
        ]);
    }

    /**
     * Retorna um objeto Date para a data e hora atuais.
     * @return {Date}
     */
    now() {
        return new Date();
    }

    /**
     * Retorna o mês atual (1 a 12).
     * @return {Number}
     */
    currentMonth() {
        return this.now().getMonth() + 1;
    }

    /**
     * Retorna o ano atual.
     * @return {Number}
     */
    currentYear() {
        return this.now().getFullYear();
    }

    /**
     * Converte um objeto de data para um texto no formato ISO-8601.
     * 
     * @param {Date?} date A data para converter. Valor padrão: hoje.
     * @returns Uma data no formato 'yyyy-mm-dd'.
     */
    asISO8601(date) {
        const length = 'yyyy-mm-dd'.length;
        const value = date || this.now();
        return value.toISOString().substr(0, length);
    }

    /**
     * Torna uma data no formato PHP 'Y-m-d H:i:s', retornada propositalmente
     * assim para padronização do sistema, em algo mais visualmente familiar
     * para exibir na tela, no formato brasileiro de data.
     * 
     * @param {string} YmdHis A data no formato 'Y-m-d H:i:s', onde 's' é opcional.
     * @return O texto com a data mais legível. Caso não esteja no formato esperado,
     *         o próprio parâmetro YmdHis é retornado.
     */
    brazilianize(YmdHis) {
        const match = ('' + YmdHis).trim().match(/^(\d+)-(\d+)-(\d+)\s*(\d+):(\d+)(?::(\d+))$/);
        if (!match) return YmdHis;
        const Y = match[1] | 0;
        const m = match[2] | 0;
        const d = match[3] | 0;
        const H = match[4] | 0;
        const i = match[5] | 0;
        const pad = (s, l) => ('' + s).padStart(l, '0');
        return `${pad(d, 2)}/${pad(m, 2)}/${pad(Y, 4)} às ${pad(H, 2)}:${pad(i, 2)}h`;
    }

};