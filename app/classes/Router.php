<?php
namespace classes;

use controllers\ErrorController;
use InvalidArgumentException;
use RuntimeException;

/**
 * Guarda las rutas de la aplicación y decide qué controlador debe responder.
 *
 * El recorrido básico es:
 * 1. web.php registra las rutas.
 * 2. dispatch() compara la petición actual con esas rutas.
 * 3. Si encuentra una coincidencia, ejecuta la acción del controlador.
 */
class Router {
    /** @var RouteDefinition[] */
    private array $routes = [];

    /** El router activo permite que route() encuentre los nombres registrados. */
    private static ?self $current = null;

    public function __construct() {
        self::$current = $this;
    }

    /** Registra una ruta para una petición GET. */
    public function get(string $path, array $action): RouteDefinition {
        return $this->add('GET', $path, $action);
    }

    /** Registra una ruta para una petición POST. */
    public function post(string $path, array $action): RouteDefinition {
        return $this->add('POST', $path, $action);
    }

    /**
     * Guarda una ruta. La acción tiene la forma [Controlador::class, 'metodo'].
     */
    private function add(string $method, string $path, array $action): RouteDefinition {
        if (count($action) !== 2) {
            throw new InvalidArgumentException('La acción debe indicar un controlador y un método.');
        }

        if (!is_string($action[0]) || !is_string($action[1])) {
            throw new InvalidArgumentException('El controlador y el método deben escribirse como texto.');
        }

        $path = $this->normalizePath($path);

        foreach ($this->routes as $route) {
            if ($route->method === $method && $route->path === $path) {
                throw new RuntimeException("La ruta {$method} {$path} ya está registrada.");
            }
        }

        $route = new RouteDefinition($method, $path, $action);
        $this->routes[] = $route;

        return $route;
    }

    /** Carga el archivo que contiene las declaraciones de rutas. */
    public function load(string $routesFile): void {
        if (!is_file($routesFile)) {
            throw new RuntimeException("No se encontró el archivo de rutas: {$routesFile}");
        }

        // La variable $router queda disponible dentro de web.php.
        $router = $this;
        require $routesFile;

        $this->checkRouteNames();
    }

    /** Comprueba que los nombres existan una sola vez. */
    private function checkRouteNames(): void {
        $names = [];

        foreach ($this->routes as $route) {
            if ($route->name === null) {
                continue;
            }

            if (in_array($route->name, $names, true)) {
                throw new RuntimeException("El nombre de ruta {$route->name} está duplicado.");
            }

            $names[] = $route->name;
        }
    }

    /** Busca y ejecuta la ruta que corresponde a la petición actual. */
    public function dispatch(): void {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $requestPath = $this->getRequestPath();
        $pathMatched = false;
        $allowedMethods = [];

        foreach ($this->routes as $route) {
            // Primero comparamos la URL. Después comprobamos el método HTTP.
            $parameters = $this->match($route->path, $requestPath);
            if ($parameters === null) {
                continue;
            }

            $pathMatched = true;
            $allowedMethods[] = $route->method;

            if ($route->method !== $method) {
                continue;
            }

            $this->runAction($route, $parameters);
            return;
        }

        if ($pathMatched) {
            header('Allow: ' . implode(', ', array_unique($allowedMethods)));
            (new ErrorController())->error405();
            return;
        }

        (new ErrorController())->error404();
    }

    /** Obtiene la parte de la URL que se compara con las rutas. */
    private function getRequestPath(): string {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $basePath = parse_url(BASE_URL, PHP_URL_PATH) ?: '';

        // Cuando la aplicación vive en una subcarpeta, quitamos esa carpeta.
        if ($basePath !== '' && strpos($path, rtrim($basePath, '/') . '/') === 0) {
            $path = substr($path, strlen(rtrim($basePath, '/')));
        } elseif ($basePath !== '' && rtrim($path, '/') === rtrim($basePath, '/')) {
            $path = '/';
        }

        return $this->normalizePath($path);
    }

    /** Ejecuta el controlador y el método guardados en la ruta. */
    private function runAction(RouteDefinition $route, array $parameters): void {
        list($controllerClass, $method) = $route->action;

        if (!class_exists($controllerClass)) {
            (new ErrorController())->error404();
            return;
        }

        $controller = new $controllerClass();
        if (!is_callable([$controller, $method])) {
            (new ErrorController())->error404();
            return;
        }

        if (count($parameters) > 0) {
            $controller->$method($parameters);
        } else {
            $controller->$method();
        }
    }

    /** Construye una URL usando el nombre de una ruta. */
    public static function url(string $name, array $parameters = []): string {
        if (self::$current === null) {
            throw new RuntimeException('El router aún no ha sido inicializado.');
        }

        foreach (self::$current->routes as $route) {
            if ($route->name !== $name) {
                continue;
            }

            $path = $route->path;
            $usedParameters = [];
            $segments = explode('/', trim($path, '/'));

            // Reemplazamos segmentos como {id} con el valor recibido.
            foreach ($segments as $index => $segment) {
                if (preg_match('/^\{([A-Za-z_][A-Za-z0-9_]*)\}$/', $segment, $matches)) {
                    $parameterName = $matches[1];
                    if (!array_key_exists($parameterName, $parameters)) {
                        throw new InvalidArgumentException("Falta el parámetro {$parameterName} para la ruta {$name}.");
                    }

                    $segments[$index] = rawurlencode((string) $parameters[$parameterName]);
                    $usedParameters[] = $parameterName;
                }
            }

            $path = $path === '/' ? '/' : '/' . implode('/', $segments);
            $queryParameters = array_diff_key($parameters, array_flip($usedParameters));

            if (count($queryParameters) > 0) {
                $path .= '?' . http_build_query($queryParameters);
            }

            return rtrim(BASE_URL, '/') . ($path === '' ? '/' : $path);
        }

        throw new InvalidArgumentException("No existe una ruta llamada {$name}.");
    }

    /** Compara una ruta con la URL solicitada y devuelve sus parámetros. */
    private function match(string $routePath, string $requestPath): ?array {
        $routeSegments = $routePath === '/' ? [] : explode('/', trim($routePath, '/'));
        $requestSegments = $requestPath === '/' ? [] : explode('/', trim($requestPath, '/'));

        if (count($routeSegments) !== count($requestSegments)) {
            return null;
        }

        $parameters = [];
        foreach ($routeSegments as $index => $segment) {
            if (preg_match('/^\{([A-Za-z_][A-Za-z0-9_]*)\}$/', $segment, $matches)) {
                $parameters[$matches[1]] = rawurldecode($requestSegments[$index]);
            } elseif ($segment !== rawurldecode($requestSegments[$index])) {
                return null;
            }
        }

        return $parameters;
    }

    /** Agrega una barra inicial y quita barras finales para comparar rutas. */
    private function normalizePath(string $path): string {
        $path = '/' . trim($path, '/');
        return $path === '/' ? '/' : rtrim($path, '/');
    }
}
