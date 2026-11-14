<?php

namespace controllers;

use controllers\auth\SessionController;

class Controller {
    public function __construct() {
    }

    /**
     * Devuelve los datos de la sesión si hay un usuario autenticado.
     * Los controladores hijos pueden usarlo sin acceder directamente a $_SESSION.
     */
    protected function authenticatedUser(): ?array {
        return SessionController::sessionValidate();
    }

    public function method(): string {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }
}
