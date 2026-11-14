<?php

namespace app;

use classes\Autoloader;
use classes\Csrf;
use classes\Router;
use controllers\auth\SessionController as Session;
use ErrorException;
use RuntimeException;
use Throwable;

// APP_ENV puede no estar disponible en config.php si ese archivo falla al cargar.
$isDevelopment = getenv('APP_ENV') === 'development';
error_reporting(E_ALL);
ini_set('display_errors', $isDevelopment ? '1' : '0');
ini_set('log_errors', '1');

set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
    // Respeta errores silenciados con @ y la configuración actual de error_reporting.
    if (!(error_reporting() & $severity)) {
        return false;
    }

    throw new ErrorException($message, 0, $severity, $file, $line);
});

set_exception_handler(static function (Throwable $exception) use ($isDevelopment): void {
    error_log(sprintf(
        "%s: %s in %s:%d\nStack trace:\n%s",
        get_class($exception),
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        $exception->getTraceAsString()
    ));

    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/plain; charset=UTF-8');
    }

    if ($isDevelopment) {
        echo sprintf(
            "Error: %s\nArchivo: %s:%d\n",
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );
        return;
    }

    echo 'Ocurrió un error interno. Inténtalo de nuevo más tarde.';
});

class App
{
    public function __construct()
    {
        $this->init();
    }

    private function init(): void
    {
        $this->initConfig();
        $this->loadFunctions();
        $this->initAutoloader();

        // Primero valida el estado de autenticación; después asegura el token CSRF.
        $this->initSession();
        $this->initCsrf();

        $this->initRouter();
    }

    private function initConfig(): void
    {
        $configFile = __DIR__ . '/config.php';
        if (!is_file($configFile)) {
            throw new RuntimeException('No se encontró el archivo de configuración.');
        }

        require_once $configFile;
    }

    private function loadFunctions(): void
    {
        $functionsFile = FUNCTIONS . 'main_functions.php';
        if (!is_file($functionsFile)) {
            throw new RuntimeException('No se encontró el archivo de funciones.');
        }

        require_once $functionsFile;
    }

    private function initAutoloader(): void
    {
        $autoloaderFile = CLASSES . 'Autoloader.php';
        if (!is_file($autoloaderFile)) {
            throw new RuntimeException('No se encontró la clase Autoloader.');
        }

        require_once $autoloaderFile;
        Autoloader::register();
    }

    private function initCsrf(): void
    {
        new Csrf();
    }

    private function initSession(): void
    {
        Session::sessionValidate();
    }

    private function initRouter(): void
    {
        $router = new Router();
        $router->load(ROUTES . 'web.php');
        $router->dispatch();
    }

    public static function run(): void
    {
        new self();
    }
}
