<?php

    include_once LAYOUTS . 'main_head.php';

    setHeader($d);
?>
<div class="container">
    <div class="card mt-5 w-50 mx-auto">
        <div class="card-body">
            <h5 class="card-title">Inicio de sesión</h5>
            <hr>            
            <form action="" id="login-form">
                <div class="form-group input-group">
                    <label class="input-group-text" for="email"><i class="bi bi-person-fill"></i></label>
                    <input type="email" 
                            id="email"
                            class="form-control"
                            name="email"
                            placeholder="Correo electrónico"
                            required>   
                </div>
                <div class="form-group input-group mt-3">
                    <label class="input-group-text" for="passwd"><i class="bi bi-lock-fill"></i></label>
                    <input type="password" 
                            class="form-control" 
                            id="passwd"
                            name="passwd"
                            placeholder="Contraseña"
                            required>
                </div>
                <div class="d-grid gap-2 my-2">
                    <small class="form-text text-danger d-none" id="error">
                        Sus datos de inicio de sesión son incorrecctos
                    </small>
                    <hr>
                    <button class="btn btn-primary mt-3" type="submit">
                        Iniciar sesión <i class="bi bi-box-arrow-in-right"></i>
                    </button>
                    <a href="<?= route('register') ?>" class="btn btn-link float-end">Registrarse</a>
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
    
