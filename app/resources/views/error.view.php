<?php
    
    include_once LAYOUTS . 'main_head.php';
    setHeader($d);

?>

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="mt-5"><?=$d->code?></h1>
                <?php if($d->code == 404): ?>
                <h2>Página No Encontrada</h2>
                <p>Lo sentimos, la página que buscas no existe o ha sido movida.</p>
                <a href="<?= route('home') ?>" class="btn btn-primary">Volver al Inicio</a>
                <?php endif; ?>
                <?php if($d->code == 100): ?>
                <h2>Acción No Permitida</h2>
                <p>Lo sentimos, no tienes permiso para realizar esta acción.</p>
                <a href="<?= route('home') ?>" class="btn btn-primary">Volver al Inicio</a>
                <?php endif; ?>
                <?php if($d->code == 405): ?>
                <h2>Método no permitido</h2>
                <p>La ruta existe, pero no acepta este método HTTP.</p>
                <a href="<?= route('home') ?>" class="btn btn-primary">Volver al Inicio</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php
    
    include_once LAYOUTS . 'main_foot.php';

    setFooter($d);
    closeFooter();

