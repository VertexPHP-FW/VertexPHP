<?php
namespace classes;

/**
 * Representa una ruta registrada por Router.
 * Guarda el método HTTP, la URL, la acción y, opcionalmente, un nombre.
 */
class RouteDefinition {
    public string $method;
    public string $path;
    public array $action;
    public ?string $name = null;

    public function __construct(string $method, string $path, array $action) {
        $this->method = $method;
        $this->path = $path;
        $this->action = $action;
    }

    /** Asigna un nombre y devuelve esta ruta para permitir encadenar ->name(). */
    public function name(string $name): self {
        $this->name = $name;
        return $this;
    }
}
