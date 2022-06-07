<?php

use App\Constants;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('createSuperUser', function(){
    $username = $this->ask('Usuário para login');
    $name = $this->ask('Nome de exibição do usuário');
    $email = $this->ask('E-mail do usuário');
    $password = $this->secret('Senha do usuário');
    $user = new User();
    $user->name = $name;
    $user->username = $username;
    $user->email = $email;
    $user->password = Hash::make($password);
    $user->role = Constants::USER_ROLE_ADMIN;
    $user->status = Constants::STATUS_ACTIVE;
    $user->save();
    $this->info('Usuário criado!');
    $this->info('Agora você pode entrar no sistema Web e selecionar a opção para gerenciar usuários.');
})->purpose('Create a super user to manage users');