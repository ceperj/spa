export default {

    async request(method, url, body){
        let headers = {
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": this.csrfToken,
        };

        let response = await fetch(url, {
            method,
            headers,
            body,
            credentials: "same-origin"
        });

        if (response.status >= 400)
        {
            console.error(`Requisição ${method} para ${url} retornou HTTP ${response.status}`);
            throw response;
        }
        
        return response;
    },

    async requestCsrf() {
        /* DEV */if (import.meta.env.DEV) return true;

        try {
            let token = await this.request('GET', '/api/csrf', undefined);
            this.csrfToken = await token.text();
            return true;
        } catch (e) {
            console.error('Não foi possível carregar o cookie CSRF para realizar ações com a API (HTTP '+e.status+').');
            console.error(e);
            return false;
        }
    },

    async prepareGet(action) {
        await this.requestCsrf();
        if (action)
            return await action();
    },

}