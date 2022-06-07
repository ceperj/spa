export default {
    /**
     * Returns an object whose keys match the array entries.
     * 
     * @param {Array} array 
     * @param {Any} defaultValue 
     * @returns Object
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
     * Fill all the fields of the object with default value.
     * 
     * @param {Object} obj Object whose fields will be filled.
     * @param {Any} defaultValue The value to put into object fields.
     */
    fillObject(obj, defaultValue){
        for (let field of Object.keys(obj)){
            obj[field] = defaultValue;
        }
    },

    /**
     * Read a Fetch API response as JSON, with a fallback to text.
     * 
     * @param {Response} response Fetch API response.
     * @returns JSON data or a plain text.
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
    
    ordinalToStr(number, gender){
        let ao = gender.toLowerCase() === 'f' ? 'a' : 'o';
        let supAo = gender.toLowerCase() === 'f' ? 'ª' : 'º';
        return (this._ordinals[number] ?? number+'º')
            .replace('@', ao)
            .replace('º', supAo);
    },
}