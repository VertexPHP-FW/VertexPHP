<?php

    include_once LAYOUTS . 'main_head.php';

    setHeader($d);
?>
<div class="container">
    <div class="card mt-5 w-50 mx-auto">
        <div class="card-body">
            <h5 class="card-title">Cambiar contraseña <i class="bi bi-hdd-stack ms-2"></i></h5>
            <hr>            
            <form action="" id="change-passwd-form">
                <input type="password" style="display:none;" name="password">
                <div class="form-group input-group mb-3">
                    <label class="input-group-text" for="oldpasswd"><i class="bi bi-lock-fill"></i></label>
                    <input type="password" 
                            class="form-control" 
                            id="oldpasswd"
                            name="oldpasswd"                            
                            placeholder="Contraseña actual"
                            required>
                    <button class="btn btn-light border toggle-passwd" type="button" data-ownpasselem="oldpasswd"><i class="bi bi-eye"></i></button>
                </div>
                <div class="form-group input-group mb-3">
                    <label class="input-group-text" for="passwd"><i class="bi bi-lock-fill"></i></label>
                    <input type="password" 
                            class="form-control" 
                            id="newpasswd"
                            name="newpasswd"
                            placeholder="Nueva contraseña"
                            required>
                    <button class="btn btn-light border toggle-passwd" type="button" data-ownpasselem="newpasswd"><i class="bi bi-eye"></i></button>
                </div>
                <div class="form-group input-group mb-3">
                    <label class="input-group-text" for="passwd2"><i class="bi bi-lock-fill"></i></label>
                    <input type="password" 
                            class="form-control" 
                            id="passwd2"                            
                            placeholder="Confirmar nueva contraseña"
                            required>
                </div>
                <div class="d-grid gap-2 my-2">
                    <small class="form-text text-danger d-none" id="error">
                        No se pudo realizar el cambio de contraseña, por favor inténtelo más tarde.
                    </small>
                    <hr>
                    <button class="btn btn-primary mt-1" type="submit">
                        Continuar <i class="bi bi-chevron-right"></i>
                    </button>                    
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
