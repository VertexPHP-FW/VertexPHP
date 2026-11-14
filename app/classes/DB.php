<?php
namespace classes;

use mysqli;
use mysqli_sql_exception;
use mysqli_stmt;
use RuntimeException;

/**
 * Crea y conserva la conexión MySQL de una instancia del modelo.
 * Las consultas se escriben en los modelos con sentencias preparadas.
 */
class DB {
    private string $host;
    private string $name;
    private string $user;
    private string $passwd;
    private int $port;

    protected ?mysqli $connection = null;

    public function __construct(
        string $host   = DB_HOST,
        string $name   = DB_NAME,
        string $user   = DB_USER,
        string $passwd = DB_PASS,
        int    $port   = DB_PORT
    ) {
        $this->host   = $host;
        $this->name   = $name;
        $this->user   = $user;
        $this->passwd = $passwd;
        $this->port   = $port;
    }

    /** Abre la conexión una sola vez y la devuelve para ejecutar consultas. */
    public function connect(): mysqli {
        if ($this->connection !== null) {
            return $this->connection;
        }

        if ($this->passwd === '') {
            throw new RuntimeException('Falta configurar la contraseña de la base de datos.');
        }

        // Hacemos que los errores de MySQL se puedan manejar como excepciones.
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $this->connection = new mysqli(
                $this->host,
                $this->user,
                $this->passwd,
                $this->name,
                $this->port
            );
            $this->connection->set_charset('utf8mb4');
        } catch (mysqli_sql_exception $exception) {
            // El detalle técnico queda disponible como excepción anterior para el registro interno.
            throw new RuntimeException('No fue posible conectar con la base de datos.', 0, $exception);
        }

        return $this->connection;
    }

    /** Devuelve una fila o null cuando no hay coincidencias. */
    protected function fetchOne(string $sql, array $parameters = []): ?array {
        $statement = $this->prepareAndExecute($sql, $parameters);
        $row = $statement->get_result()->fetch_assoc();
        $statement->close();

        return $row ?: null;
    }

    /** Devuelve todas las filas que coinciden con la consulta. */
    protected function fetchAll(string $sql, array $parameters = []): array {
        $statement = $this->prepareAndExecute($sql, $parameters);
        $rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        return $rows;
    }

    /** Ejecuta una inserción y devuelve el identificador generado. */
    protected function insert(string $sql, array $parameters = []): int {
        $statement = $this->prepareAndExecute($sql, $parameters);
        $id = $statement->insert_id;
        $statement->close();

        return $id;
    }

    /** Ejecuta una modificación, como UPDATE o DELETE. */
    protected function execute(string $sql, array $parameters = []): void {
        $statement = $this->prepareAndExecute($sql, $parameters);
        $statement->close();
    }

    /** Prepara la consulta y envía los valores por separado del SQL. */
    private function prepareAndExecute(string $sql, array $parameters = []): mysqli_stmt {
        $statement = $this->connect()->prepare($sql);

        try {
            $statement->execute($parameters);
        } catch (mysqli_sql_exception $exception) {
            $statement->close();
            throw $exception;
        }

        return $statement;
    }
}
