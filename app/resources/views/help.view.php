<?php include_once LAYOUTS . 'main_head.php'; setHeader($d); ?>
<h1 class="mb-3">Ayuda del framework</h1>
<p class="lead">La aplicación usa una estructura ligera organizada por responsabilidades.</p>
<div class="table-responsive"><table class="table table-striped align-middle">
    <thead><tr><th>Ruta</th><th>Responsabilidad</th></tr></thead>
    <tbody>
        <tr><td><code>app/app.php</code></td><td>Inicializa configuración, funciones, clases, sesión, CSRF y rutas.</td></tr>
        <tr><td><code>app/classes/</code></td><td>Servicios del núcleo: router, vistas, base de datos, sesión y utilidad CSRF.</td></tr>
        <tr><td><code>app/controllers/</code></td><td>Controladores que atienden las rutas declaradas.</td></tr>
        <tr><td><code>app/routes/web.php</code></td><td>Rutas HTTP, acciones y nombres usados para construir URLs.</td></tr>
        <tr><td><code>app/models/</code></td><td>Modelos y acceso a datos mediante MySQL.</td></tr>
        <tr><td><code>app/resources/views/</code></td><td>Plantillas HTML renderizadas por los controladores.</td></tr>
        <tr><td><code>app/resources/layouts/</code></td><td>Diseño compartido de encabezado y pie de página.</td></tr>
        <tr><td><code>app/public/</code></td><td>Entrada HTTP y archivos públicos: CSS, JavaScript e imágenes públicas.</td></tr>
        <tr><td><code>database/</code></td><td>Esquema inicial genérico de usuarios para autenticación.</td></tr>
    </tbody>
</table></div>
<h2 class="h4 mt-4">Declarar rutas en <code>web.php</code></h2>
<p>El recorrido de una petición tiene tres pasos: <code>app.php</code> carga <code>web.php</code>; <code>Router</code> compara el método HTTP y la URL con las rutas; y, al encontrar una coincidencia, llama al método indicado del controlador.</p>
<p>En <code>app/routes/web.php</code>, cada declaración conecta un método HTTP, una URL y una acción de controlador. Importa las clases que usarás y declara cada ruta con <code>get()</code> o <code>post()</code>:</p>
<pre class="bg-light border rounded p-3"><code>use controllers\HelpController;
use controllers\ContactController;

$router-&gt;get('/ayuda', [HelpController::class, 'index'])
       -&gt;name('help');

$router-&gt;post('/contacto', [ContactController::class, 'send'])
       -&gt;name('contact.submit');</code></pre>
<p>La acción se escribe como <code>[NombreDelControlador::class, 'metodoPublico']</code>. Por ejemplo, <code>get()</code> registra una ruta GET y <code>post()</code> registra una ruta POST. El método del controlador atiende la petición. Cada ruta puede tener un nombre para generar enlaces; el router rechaza rutas duplicadas con el mismo método y URL.</p>
<h3 class="h5 mt-3">Parámetros en la URL</h3>
<p>Un segmento entre llaves define un parámetro. El router lo entrega al controlador en un arreglo asociativo:</p>
<pre class="bg-light border rounded p-3"><code>use controllers\UserController;

$router-&gt;get('/usuarios/{id}', [UserController::class, 'show'])
       -&gt;name('user.show');

// En UserController::show($params):
$id = $params['id'];</code></pre>
<h3 class="h5 mt-3">Usar rutas nombradas</h3>
<p>En las vistas PHP, usa <code>route()</code> para generar URLs sin repetir la ruta escrita:</p>
<pre class="bg-light border rounded p-3"><code>&lt;a href="&lt;?= route('help') ?&gt;"&gt;Ayuda&lt;/a&gt;
&lt;a href="&lt;?= route('user.show', ['id' =&gt; 5]) ?&gt;"&gt;Ver usuario&lt;/a&gt;</code></pre>
<p>Los parámetros que coinciden con segmentos como <code>{id}</code> se incorporan a la ruta y se codifican para URL. Los parámetros adicionales se agregan como query string.</p>
<p>Para usar una URL desde JavaScript, agrega su nombre y helper a la lista <code>APP_ROUTES</code> de <code>app/resources/layouts/main_foot.php</code>. El objeto estará disponible como <code>app.routes</code>:</p>
<pre class="bg-light border rounded p-3"><code>// En main_foot.php:
'help' =&gt; route('help'),

// En JavaScript:
fetch(app.routes.help);</code></pre>
<p>Si una URL existe pero no admite el método solicitado, el router responde con <code>405 Method Not Allowed</code>. Si no hay una ruta coincidente, responde con <code>404 Not Found</code>.</p>
<h2 class="h4 mt-4">Agregar una pantalla</h2>
<ol><li>Crea un controlador en <code>app/controllers/</code> que extienda <code>Controller</code>.</li><li>Crea su plantilla en <code>app/resources/views/</code> y usa <code>View::render()</code>.</li><li>Declara la ruta correspondiente en <code>app/routes/web.php</code> y asígnale un nombre.</li><li>Agrega CSS o JavaScript en <code>app/public/assets/</code>.</li></ol>
<h2 class="h4 mt-4">Agregar bibliotecas JavaScript</h2>
<p>Las bibliotecas JavaScript se administran con npm y Vite. Necesitas Node.js <code>22.12</code> o posterior. Ejecuta los comandos desde la carpeta principal del proyecto.</p>
<p>Instala solamente el paquete que vayas a utilizar. Por ejemplo, para agregar Axios:</p>
<pre class="bg-light border rounded p-3"><code>npm install axios</code></pre>
<p>Después, impórtalo en <code>app/resources/js/main.js</code> y úsalo en el código de la aplicación:</p>
<pre class="bg-light border rounded p-3"><code>import axios from 'axios';

const response = await axios.get('/api/ejemplo');</code></pre>
<p>Vite incluye en los archivos públicos las dependencias importadas. Genera la compilación con:</p>
<pre class="bg-light border rounded p-3"><code>npm run build</code></pre>
<p>PHP sirve el JavaScript compilado desde <code>app/public/assets/build/</code>. Para restaurar las dependencias del proyecto en otra computadora, ejecuta <code>npm ci</code> y después <code>npm run build</code>. Para quitar una biblioteca que ya no se necesita, usa <code>npm uninstall nombre-paquete</code> y vuelve a compilar.</p>
<p>Vue 3 ya está instalado. En una vista puedes añadir <code>&lt;div data-vue-app&gt;&lt;/div&gt;</code> para montar el componente inicial. También puedes pasarle propiedades JSON mediante el atributo <code>data-props</code>:</p>
<pre class="bg-light border rounded p-3"><code>&lt;div data-vue-app data-props='{"message":"Hola desde esta vista"}'&gt;&lt;/div&gt;</code></pre>
<h2 class="h4 mt-4">Base de datos</h2>
<p>La clase <code>DB</code> abre la conexión MySQL. Los modelos escriben sus consultas con sentencias preparadas: el signo <code>?</code> representa un valor que se envía por separado, para que los datos del usuario no se interpreten como parte del SQL. Evita concatenar datos recibidos en una consulta.</p>
<p>Los modelos pueden reutilizar estos métodos heredados de <code>DB</code>: <code>fetchOne()</code> obtiene una fila, <code>fetchAll()</code> obtiene una lista, <code>insert()</code> ejecuta una inserción y devuelve el identificador generado, y <code>execute()</code> sirve para modificaciones como <code>UPDATE</code> o <code>DELETE</code>.</p>
<pre class="bg-light border rounded p-3"><code>$usuario = $this-&gt;fetchOne(
    'SELECT id, email FROM usuario WHERE email = ?',
    [$email]
);</code></pre>
<p>Configura <code>DB_HOST</code>, <code>DB_PORT</code>, <code>DB_NAME</code>, <code>DB_USER</code> y <code>DB_PASS</code> mediante variables de entorno. No hay una contraseña predeterminada para la aplicación. El esquema inicial se encuentra en <code>database/schema.sql</code>; Docker lo aplica al crear el volumen de base de datos por primera vez.</p>
<p>Copia <code>.env.example</code> a <code>.env</code>, ajusta las contraseñas y arranca los servicios con <code>docker compose up --build</code>. La aplicación queda disponible en el puerto <code>8001</code>; phpMyAdmin puede iniciarse con el perfil <code>tools</code>.</p>
<h2 class="h4 mt-4">Autenticación</h2>
<p>El framework incluye inicio de sesión, registro y cambio de contraseña. El registro crea cuentas activas y guarda contraseñas con el algoritmo de hash de PHP.</p>
<?php include_once LAYOUTS . 'main_foot.php'; setFooter($d); closeFooter(); ?>
