# VertexPHP

VertexPHP es una base ligera para crear aplicaciones web con PHP, MySQL, Vue y Vite. Esta guía explica cómo instalar sus dependencias, poner al día el framework y comenzar una aplicación.

## Requisitos

- PHP 8.2 o posterior
- Composer 2
- Node.js 22.12 o posterior y npm
- Docker con Docker Compose (opcional, para iniciar la aplicación y MySQL en contenedores)

## Instalación

Crea un proyecto nuevo con Composer desde Packagist. Reemplaza `nombre-del-proyecto` por el nombre de la carpeta que quieres crear:

```bash
composer create-project vertexphp-fw/vertexphp nombre-del-proyecto
cd nombre-del-proyecto
```

Si no indicas una versión, Composer instala la versión estable más reciente publicada del framework. `create-project` también instala las dependencias PHP definidas por el paquete. Para solicitar una versión concreta, agrega el número de versión al comando, por ejemplo `composer create-project vertexphp-fw/vertexphp nombre-del-proyecto 1.2.3`.

Instala las dependencias JavaScript y compila los recursos iniciales:

```bash
npm install
npm run build
```

`npm install` instala las dependencias definidas en `package.json` y actualiza `package-lock.json` si hace falta. Para restaurar las dependencias en otra computadora usando las versiones fijadas en `package-lock.json`, ejecuta `npm ci` y luego `npm run build`.

## Configuración y primer inicio

Prepara la configuración local:

```bash
cp .env.example .env
```

Edita `.env` y cambia `DB_PASS` y `MYSQL_ROOT_PASSWORD` por contraseñas propias. La aplicación no incluye una contraseña predeterminada. Con Docker Compose, inicia la aplicación y la base de datos:

```bash
docker compose up --build
```

Abre [http://localhost:8001](http://localhost:8001). phpMyAdmin está disponible en [http://localhost:8080](http://localhost:8080). El esquema inicial de usuarios en `database/schema.sql` se importa cuando MySQL crea su volumen por primera vez.

Si no usas Docker, configura un servidor PHP con los módulos requeridos por el proyecto y una base MySQL. Define las variables `APP_NAME`, `APP_ENV`, `BASE_URL`, `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` y `DB_PASS` en el entorno del servidor, importa `database/schema.sql` y configura el directorio público para servir `app/public/`. Para compilar los recursos después de modificarlos, ejecuta `npm run build` desde la raíz.

## Actualizar el framework

Para crear un proyecto con una versión más reciente del framework, ejecuta de nuevo `composer create-project` con un nombre de carpeta distinto. Si necesitas actualizar un proyecto que ya existe, revisa las instrucciones de actualización publicadas para la versión destino antes de reemplazar sus archivos: conserva tus cambios y datos propios, y aplica los cambios de configuración o base de datos que correspondan. Después sincroniza las dependencias JavaScript y recompila los recursos:

```bash
npm ci
npm run build
```

## Empezar a crear la aplicación

La estructura principal está organizada así:

| Ruta | Responsabilidad |
| --- | --- |
| `app/app.php` | Inicializa configuración, funciones, clases, sesión, CSRF y rutas. |
| `app/classes/` | Servicios del núcleo: router, vistas, base de datos, sesión y CSRF. |
| `app/controllers/` | Controladores que atienden las rutas. |
| `app/routes/web.php` | Rutas HTTP, acciones y nombres usados para generar URLs. |
| `app/models/` | Modelos y acceso a datos MySQL. |
| `app/resources/views/` | Plantillas HTML renderizadas por los controladores. |
| `app/resources/layouts/` | Encabezado y pie de página compartidos. |
| `app/resources/js/` | Código JavaScript y puntos de entrada para Vite y Vue. |
| `app/public/` | Entrada HTTP y archivos públicos, incluidos recursos compilados. |
| `database/` | Esquema inicial de la base de datos. |

Para agregar una pantalla:

1. Crea un controlador en `app/controllers/` que extienda `Controller`.
2. Crea su plantilla en `app/resources/views/` y renderízala con `View::render()`.
3. Declara la ruta en `app/routes/web.php` y asígnale un nombre.
4. Agrega CSS, JavaScript o imágenes públicas bajo `app/public/`.

Las rutas relacionan un método HTTP y una URL con una acción de controlador. Además de `get()` y `post()`, el router permite `put()` para actualizar recursos y `delete()` para eliminarlos:

```php
use controllers\UserController;

$router->put('/usuarios/{id}', [UserController::class, 'update'])
       ->name('user.update');

$router->delete('/usuarios/{id}', [UserController::class, 'delete'])
       ->name('user.delete');
```

Los formularios HTML solo admiten GET y POST. Para enviar PUT o DELETE desde un formulario, usa POST con un campo oculto `_method`; el router acepta `PUT` y `DELETE` como valores. Desde JavaScript puedes enviar los métodos HTTP directamente, por ejemplo con Axios:

```html
<form method="post" action="<?= route('user.update', ['id' => $id]) ?>">
    <input type="hidden" name="_method" value="PUT">
    <!-- Incluye aquí el token CSRF según la configuración de la aplicación. -->
    <input type="text" name="nombre" value="<?= htmlspecialchars($nombre) ?>">
    <button type="submit">Guardar</button>
</form>
```

Para usar Axios, instálalo con `npm install axios`. Agrega una URL base a la lista `APP_ROUTES` en `app/resources/layouts/main_foot.php` (por ejemplo, `'users' => rtrim(BASE_URL, '/') . '/usuarios'`) y úsala para construir la URL del recurso:

```js
import axios from 'axios';

const userUrl = `${app.routes.users}/${encodeURIComponent(userId)}`;

// Envía los campos del recurso en el cuerpo JSON de PUT.
await axios.put(userUrl, { nombre: 'Ada Lovelace' });

// Envía DELETE al mismo recurso.
await axios.delete(userUrl);
```

En una acción PUT que recibe JSON, decodifica el cuerpo con `json_decode(file_get_contents('php://input'), true)`. Las acciones que modifican datos también deben validar el token CSRF de la aplicación.

En una vista, genera enlaces a rutas GET con el nombre de la ruta:

```php
<a href="<?= route('help') ?>">Ayuda</a>
```

Los segmentos entre llaves, como `/usuarios/{id}`, se entregan al controlador en un arreglo asociativo. Por ejemplo, la ruta `/usuarios/{id}` permite leer `$params['id']` en la acción.

## JavaScript, Vue y Vite

Vue 3 ya está incluido. Para agregar una biblioteca, instálala con npm, impórtala desde `app/resources/js/main.js` y vuelve a compilar. Por ejemplo:

```bash
npm install axios
```

```js
import axios from 'axios';

const response = await axios.get('/api/ejemplo');
```

Para montar el componente Vue inicial desde una vista, usa el atributo `data-vue-app`; puedes enviarle propiedades JSON con `data-props`:

```html
<div data-vue-app data-props='{"message":"Hola desde esta vista"}'></div>
```

Al terminar los cambios de JavaScript, compila los recursos:

```bash
npm run build
```

Los archivos generados quedan en `app/public/assets/build/`. Para trabajar con Vite en modo desarrollo, ejecuta `npm run dev` desde la raíz del repositorio.

## Base de datos y seguridad

La clase `DB` ofrece `fetchOne()`, `fetchAll()`, `insert()` y `execute()` para consultas y modificaciones. Usa sentencias preparadas para los valores recibidos; evita concatenar datos de usuario dentro del SQL. Configura las credenciales mediante variables de entorno y no guardes contraseñas reales en el repositorio.

Estos métodos son `protected`: úsalos desde un modelo que extienda `Model`. Por ejemplo, puedes agregar métodos como estos a `app/models/usuario.php`:

```php
<?php
namespace models;

class usuario extends Model {
    // Consulta una fila. Devuelve un arreglo asociativo o null si no existe.
    public function findById(int $id): ?array {
        return $this->fetchOne(
            'SELECT id, nombre, email FROM usuario WHERE id = ?',
            [$id]
        );
    }

    // Consulta varias filas. Devuelve un arreglo, vacío si no hay coincidencias.
    public function all(): array {
        return $this->fetchAll(
            'SELECT id, nombre, email FROM usuario ORDER BY id'
        );
    }

    // Inserta una fila y devuelve el ID autogenerado.
    public function create(string $nombre, string $email, string $passwordHash): int {
        return $this->insert(
            'INSERT INTO usuario (nombre, email, passwd, rol, activo) VALUES (?, ?, ?, ?, ?)',
            [$nombre, $email, $passwordHash, 'user', 1]
        );
    }

    // UPDATE y DELETE se ejecutan con execute(); no devuelve filas afectadas.
    public function rename(int $id, string $nombre): void {
        $this->execute(
            'UPDATE usuario SET nombre = ? WHERE id = ?',
            [$nombre, $id]
        );
    }

    public function delete(int $id): void {
        $this->execute(
            'DELETE FROM usuario WHERE id = ?',
            [$id]
        );
    }
}
```

Cada `?` corresponde al valor en la misma posición del arreglo de parámetros. Pasa los valores por separado como en los ejemplos; no insertes datos recibidos en la cadena SQL. Para contraseñas, guarda siempre el resultado de `password_hash()` y nunca la contraseña original.

El framework incluye inicio de sesión, registro y cambio de contraseña. El registro crea cuentas activas y almacena contraseñas con el algoritmo de hash de PHP.
