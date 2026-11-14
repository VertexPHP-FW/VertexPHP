<?php
function setHeader($args, ...$styles) {
    $ua = as_object($args->ua ?? ['sv' => 0]);
    $title = htmlspecialchars($args->title ?? APP_NAME, ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= CSS ?>bootstrap.min.css">
    <link rel="stylesheet" href="<?= CSS ?>app.css">
    <?php foreach ($styles as $style): ?><link rel="stylesheet" href="<?= CSS . htmlspecialchars($style, ENT_QUOTES, 'UTF-8') ?>"><?php endforeach; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= route('home') ?>"><?= htmlspecialchars(APP_NAME, ENT_QUOTES, 'UTF-8') ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Abrir navegación"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto"><li class="nav-item"><a class="nav-link" href="<?= route('home') ?>">Inicio</a></li><li class="nav-item"><a class="nav-link" href="<?= route('help') ?>">Ayuda</a></li></ul>
            <ul class="navbar-nav">
                <?php if (!empty($ua->sv)): ?>
                    <li class="nav-item"><span class="navbar-text me-3"><?= htmlspecialchars($ua->nombre ?? '', ENT_QUOTES, 'UTF-8') ?></span></li>
                    <li class="nav-item"><a class="nav-link" href="<?= route('logout') ?>">Cerrar sesión</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= route('login') ?>">Iniciar sesión</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= route('register') ?>">Crear cuenta</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="container py-4">
<?php }
