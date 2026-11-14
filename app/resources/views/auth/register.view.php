<?php

    include_once LAYOUTS . 'main_head.php';

    setHeader($d);
?>
<div class="container">
    <div class="card mt-5 w-50 mx-auto">
        <div class="card-body">
            <h5 class="card-title">Registrarse</h5>
            <hr>            
            <form action="" id="register-form">
                <div class="form-group input-group mb-3">
                    <label class="input-group-text" for="nombre"><i class="bi bi-person-vcard"></i></label>
                    <input type="text" 
                            id="nombre"
                            class="form-control"
                            name="nombre"
                            placeholder="Nombre completo"
                            required>   
                </div>
                <div class="form-group input-group mb-3">
                    <label class="input-group-text" for="email"><i class="bi bi-envelope-at"></i></label>
                    <input type="email" 
                            id="email"
                            class="form-control"
                            name="email"
                            placeholder="Correo electrónico"
                            required>   
                </div>
                <div class="form-group input-group mb-3">
                    <label class="input-group-text" for="passwd"><i class="bi bi-lock-fill"></i></label>
                    <input type="password" 
                            class="form-control" 
                            id="passwd"
                            name="passwd"
                            placeholder="Contraseña"
                            required>
                </div>
                <div class="form-group input-group mb-3">
                    <label class="input-group-text" for="passwd2"><i class="bi bi-lock-fill"></i></label>
                    <input type="password" 
                            class="form-control" 
                            id="passwd2"
                            
                            placeholder="Confirmar contraseña"
                            required>
                </div>
                <div class="d-grid gap-2 my-2">
                    <small class="form-text text-danger d-none" id="error">
                        No se pudo realizar el registro, por favor inténtelo más tarde.
                    </small>
                    <hr>
                    <button class="btn btn-primary mt-3" type="submit">
                        Continuar <i class="bi bi-chevron-right"></i>
                    </button>
                    <a class="btn btn-link float-end" href="<?= route('login') ?>">Ya tengo una cuenta</a>
                </div>
            </form>
        </div>
    </div>
</div>


<?php
    include_once LAYOUTS . 'main_foot.php';
    setFooter($d);
?>    
<?php

    closeFooter();
