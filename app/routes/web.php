<?php

use controllers\HomeController;
use controllers\HelpController;
use controllers\auth\RegisterController;
use controllers\auth\SessionController;

// Cada declaración conecta método HTTP + URL + acción del controlador.
// El nombre permite generar enlaces con route('nombre') desde las vistas.
$router->get('/', [HomeController::class, 'index'])->name('home');
$router->get('/ayuda', [HelpController::class, 'index'])->name('help');

// Las páginas se consultan con GET y los formularios envían datos con POST.
$router->get('/sesion', [SessionController::class, 'index'])->name('session.index');
$router->get('/iniciar-sesion', [SessionController::class, 'iniSession'])->name('login');
$router->post('/iniciar-sesion', [SessionController::class, 'userAuth'])->name('login.submit');
$router->get('/cerrar-sesion', [SessionController::class, 'logout'])->name('logout');
$router->get('/cambiar-contrasena', [SessionController::class, 'changePassword'])->name('password.change');
$router->post('/cambiar-contrasena/verificar', [SessionController::class, 'checkPassword'])->name('password.check');
$router->post('/cambiar-contrasena', [SessionController::class, 'updatePassword'])->name('password.update');

$router->get('/registro', [RegisterController::class, 'index'])->name('register');
$router->post('/registro', [RegisterController::class, 'register'])->name('register.submit');
