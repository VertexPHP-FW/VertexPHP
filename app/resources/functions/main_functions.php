<?php

function as_object($array){

    return json_decode(json_encode($array));

}

function route(string $name, array $parameters = []): string {
    return \classes\Router::url($name, $parameters);
}

function vite_asset(string $entry): string {
    static $manifest = null;

    if ($manifest === null) {
        $manifestPath = ROOT . 'public/assets/build/manifest.json';
        if (!is_file($manifestPath)) {
            throw new \RuntimeException('No se encontró el manifiesto de Vite. Ejecuta npm run build.');
        }

        $manifest = json_decode((string) file_get_contents($manifestPath), true, 512, JSON_THROW_ON_ERROR);
    }

    if (!isset($manifest[$entry]['file'])) {
        throw new \RuntimeException("La entrada {$entry} no está en el manifiesto de Vite.");
    }

    return '/assets/build/' . $manifest[$entry]['file'];
}

