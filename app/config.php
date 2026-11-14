<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', __DIR__ . DS);

define('APP_NAME', getenv('APP_NAME') ?: 'Framework Base');
define('APP_ENV', getenv('APP_ENV') ?: 'production');
define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_USER', getenv('DB_USER') ?: 'framework');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'framework');
define('DB_PORT', (int)(getenv('DB_PORT') ?: 3306));

define('CLASSES'     , ROOT .       'classes' . DS);
define('CONTROLLERS' , ROOT .       'controllers' . DS);
define('CLASSES_PATH', CLASSES);
define('RESOURCES'   , ROOT .       'resources' . DS);
define('ASSETS'      , DS .         'assets' . DS);
define('CSS'         , ASSETS .     'css' . DS);
define('JS'          , ASSETS .     'js' . DS);
define('LAYOUTS'     , RESOURCES .  'layouts' . DS);
define('VIEWS'       , RESOURCES .  'views' . DS);
define('FUNCTIONS'   , RESOURCES .  'functions' . DS);
define('ROUTES'      , ROOT .       'routes' . DS);
define('BASE_URL', getenv('BASE_URL') ?: '');




 
