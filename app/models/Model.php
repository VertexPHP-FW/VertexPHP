<?php
namespace models;

use classes\DB;

/** Base de los modelos: prepara una conexión reutilizable. */
class Model extends DB {
    public function __construct() {
        parent::__construct();
        $this->connect();
    }
}
