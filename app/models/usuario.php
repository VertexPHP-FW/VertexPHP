<?php
namespace models;

use mysqli_sql_exception;

class usuario extends Model {
    public function findByEmail($email) {
        return $this->fetchOne(
            'SELECT id, nombre, email, passwd, rol, activo FROM usuario WHERE email = ? LIMIT 1',
            [$email]
        );
    }

    public function register($name, $email, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $role = 'user';
        $active = 1;
        try {
            $id = $this->insert(
                'INSERT INTO usuario (nombre, email, passwd, rol, activo) VALUES (?, ?, ?, ?, ?)',
                [$name, $email, $hash, $role, $active]
            );

            return json_encode(['success' => true, 'id' => $id]);
        } catch (mysqli_sql_exception $exception) {
            return json_encode(['success' => false, 'error' => 'No fue posible crear la cuenta. Verifica los datos e inténtalo de nuevo.']);
        }
    }

    public function updatePassword($id, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $this->execute(
                'UPDATE usuario SET passwd = ? WHERE id = ?',
                [$hash, $id]
            );

            return json_encode(['success' => true]);
        } catch (mysqli_sql_exception $exception) {
            return json_encode(['success' => false, 'error' => 'No fue posible actualizar la contraseña.']);
        }
    }
}
