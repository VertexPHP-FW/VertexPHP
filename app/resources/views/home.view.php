<?php
include_once LAYOUTS . 'main_head.php';
setHeader($d);
?>
<section class="home-hero p-4 p-md-5 mb-4 rounded-3 text-center">
    <div class="container-fluid py-2 d-flex flex-column align-items-center">
        <img class="home-logo img-fluid mb-3" src="<?= ASSETS ?>images/vertexphp.webp" alt="Logotipo de VertexPHP">
        <h1 class="display-5 fw-bold">Bienvenido a VertexPHP</h1>
        <p class="home-tagline fs-4 fw-semibold">Donde todo se conecta</p>
        <p class="col-md-9 fs-5">Una base para crear aplicaciones web con PHP, vistas HTML, autenticación, MySQL y recursos frontend.</p>
        <a class="btn btn-primary btn-lg" href="<?= route('help') ?>">Conocer la estructura</a>
    </div>
</section>
<div class="row g-4">
    <div class="col-md-6"><div class="card h-100"><div class="card-body"><h2 class="h5">Componentes incluidos</h2><p class="mb-0">Enrutamiento, controladores, modelos, vistas, sesión, protección CSRF, conexión MySQL y recursos estáticos.</p></div></div></div>
    <div class="col-md-6"><div class="card h-100"><div class="card-body"><h2 class="h5">Punto de partida</h2><p class="mb-0">Consulta la ayuda para conocer las carpetas principales y los pasos para agregar una nueva funcionalidad.</p></div></div></div>
</div>
<?php include_once LAYOUTS . 'main_foot.php'; setFooter($d); closeFooter(); ?>
