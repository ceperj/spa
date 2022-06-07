import axios from 'axios';
import netutils from './netutils';
import utils from './utils';

export default {
    async getUser(){
        /* DEV */ if (import.meta.env.DEV) return {"id":1,"name":"Autenticado","email":"autenticado@usuario.local.test","role":2};
        
        let value = await this.getJSON('/api/whoami');
        return await value.data || value;
    },
    
    async getJSON(url){
        /* DEV */ if (import.meta.env.DEV) return {data:Array(19).fill(0).map((v,k)=>[k+1,'Item '+(k+1)])};

        return netutils.prepareGet(async () => {
            try{
                let response = await axios.get(url);
                return response.data;
            }catch(e){
                return this.asDisplayableError(e);
            }
        });
    },

    async sendForm(method, url, formSelector, formTransformation){
        await netutils.requestCsrf();

        const form = document.querySelector(formSelector);
        if ((form instanceof HTMLFormElement) === false)
            return console.error("O objeto informado não era um Form") || null;

        let formData = new FormData(form);
        this.removeEmptyFields(formData);
        if (formTransformation)
            formData = formTransformation(formData) || formData;
        const body = new URLSearchParams(formData);

        try{
            const response = await netutils.request(method, url, body);
            return await utils.readJsonOrText(response);
        }catch(response){
            console.error('Erro na requisição', response);
            
            if (response instanceof TypeError)
                response = { status: 0, statusText: 'Network Error', isCsrfError: false, data: null };

            throw {
                isValidationError: response.status === 422,
                isCsrfError: response.status === 419,
                status: response.status,
                statusText: response.statusText,
                data: await utils.readJsonOrText(response),
            };
        }
    },

    async postForm(url, formSelector, formTransformation){
        return this.sendForm('POST', url, formSelector, formTransformation);
    },

    asDisplayableError(error){
        error.display = this.getDisplayableError(error.response?.status);
        return error;
    },

    getDisplayableError(status){
        switch(status){
            case 400: return 'A requisição enviada estava mal-formada. Isto pode acontecer devido a um erro do código. Tente novamente, se o problema persistir, contate o suporte.';
            case 401: return 'O seu login expirou enquanto tentava acessar. Atualize a página e tente novamente.';
            case 403: return 'Você não tem permissão para editar os dados deste usuário. Se a edição é necessária, contate o suporte.';
            case 404: return 'O usuário não existe na base de dados.';
            case 419: return 'Alguns valores da requisição expiraram. Atualize a página para tentar novamente.';
            case 422: return 'Alguns dos campos gravados estavam com valores incorretos. Revise os valores e tente novamente.';
            
            case 502: case 503: case 504:
            case 500: return 'Há algum erro no servidor. Tente novamente mais tarde e, caso o problema persista, contate o suporte.';

            case null: case 0:
            case undefined: return 'Há algum erro na conexão. Tente atualizar a página e verifique a conexão com o sistema.';

            default: {
                if (status)
                    return 'Ocorreu um erro na requisição (HTTP '+status+').';
                else
                    return 'Ocorreu algum problema de conexão, mas não temos maiores informações.';
            }
        }
    },

    removeEmptyFields(formData){
        let removables = [];
        
        for(let [key, value] of formData){
            if (value === '')
                removables.push(key);
        }

        for(let key of removables){
            formData.delete(key);
        }
    },
}