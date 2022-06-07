<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrar - {{ config('app.name') }}</title>
    <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="icon" href="/favicon.png" />
    
    {{-- Style with sspecific overrides for login template --}}
    <style>
        body {
            padding-bottom: 40px;
            background-color: white;
        }

        main {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
        }

        main input[type="text"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        main input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>
<body>
    <header>
        <div class="d-flex justify-content-between">
            <img src="logo.png" class="my-2" height="60" />
            <slot name="login"></slot>
        </div>
        <div class="pt-3">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-start">
                </div>
            </div>
        </div>
        <hr>
    </header>
    <main class="text-center">
        <form class="w-100 m-0 p-0" method="post" action="{{ (route('loginPost')) }}">
            @csrf
            <h1 class="h3 mb-3 fw-normal">Entrar</h1>
            
            @if($errors->has("invalidCredentials"))
            <p class="text-danger small">E-mail e/ou senha incorretos.</p>
            @endif
            
            <div class="form-floating">
                <input type="text" class="form-control" id="username" name="username" placeholder="nome@exemplo.com">
                <label for="username">Usuário</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" placeholder="Senha">
                <label for="password">Senha</label>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">Entrar</button>
        </form>
    </main>
</body>
</html>