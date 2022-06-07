const http = require('http');
const protocol = 'http';
const host = 'localhost';
const port = 8080;
const origin = `${protocol}://${host}:${port}`;
const vitePort = 3000;
const viteOrigin = `${protocol}://${host}:${vitePort}`;

function vite(uri) { return viteOrigin + uri };

function execute(){
    const requestListener = function (req, res) {
        if (req.url == "/")
        {
            res.writeHead(307,{Location: vite('/app/')});
            res.end();
            return;
        }

        if (req.url.startsWith('/app/'))
        {
            redirectToVite(req, res);
            return;
        }

        if (req.url === '/sanctum/csrf-cookie')
        {
            res.writeHead(204);
            res.end();
            return;
        }

        if (req.url === '/api/whoami'){
            res.writeHead(200);
            res.end('{"id":1, "name": "Eliakim Zacarias", "email": "eliakim.zacarias@gmail.com"}');
            return;
        }

        res.writeHead(200);
        res.end(`Request to ${req.url} was succesful, but it is not proxied nor have special meaning.`);
    };

    const server = http.createServer(requestListener);
    server.listen(port);
}

function redirectToVite(req, res){
    const where = {
        port: vitePort,
        path: req.url,
        headers: req.headers,
        method: req.method
    };
    viteRequest = http.request(where, function(viteResponse){
        res.writeHead(viteResponse.statusCode, viteResponse.headers);
        viteResponse.pipe(res);
    });

    viteRequest.on('error', (e) => {
        console.error('proxied request failed: '+e.message);
        res.writeHead(500);
        res.end('Proxied Request Failed to respond: '+e.message);
    })

    req.pipe(viteRequest);
}

export default {
    execute,
}