<?php
namespace controllers\auth;

use controllers\Controller;
use models\usuario;
use classes\Views as View;
use classes\Redirect;

class SessionController extends Controller {
    public function index() { 
        Redirect::to(route('login')); 
    }

    public function iniSession($params = null) {
        View::render('auth/inisession', ['ua' => self::sessionValidate() ?? ['sv' => 0], 'title' => 'Iniciar sesión', 'code' => 200]);
    }

    public function changePassword($params = null) {
        View::render('auth/changepasswd', ['ua' => self::sessionValidate() ?? ['sv' => 0], 'title' => 'Cambiar contraseña', 'code' => 200]);
    }

    public function checkPassword() {
        $data = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW) ?: [];
        $password = $data['password'] ?? '';
        $user = $_SESSION['user'] ?? null;
        echo json_encode(['r' => $user && password_verify($password, $user['passwd'])]);
    }

    public function userAuth() {
        $data = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW) ?: [];
        $user = (new usuario())->findByEmail(trim($data['email'] ?? ''));
        if ($user && (int)$user['activo'] === 1 && password_verify($data['passwd'] ?? '', $user['passwd'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = $user;
            $_SESSION['sv'] = true;
            echo json_encode(['r' => true]);
            return;
        }
        self::sessionDestroy();
        echo json_encode(['r' => false]);
    }

    public static function sessionValidate() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $user = $_SESSION['user'] ?? null;
        if (!empty($_SESSION['sv']) && $user) {
            session_write_close();
            return ['sv' => true, 'id' => $user['id'], 'nombre' => $user['nombre'], 'email' => $user['email'], 'rol' => $user['rol']];
        }
        session_write_close();
        return null;
    }

    public static function getUserId() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $id = $_SESSION['user']['id'] ?? null;
        session_write_close();
        return $id;
    }

    public function updatePassword($params = null) {
        $data = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW) ?: [];
        $id = self::getUserId();
        if (!$id || empty($data['newpasswd']) || strlen($data['newpasswd']) < 8) {
            echo json_encode(['success' => false, 'error' => 'La contraseña debe tener al menos 8 caracteres.']);
            return;
        }
        echo (new usuario())->updatePassword($id, $data['newpasswd']);
    }

    public static function sessionDestroy() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $_SESSION = [];
        session_destroy();
    }

    public function logout() {
        self::sessionDestroy();
        Redirect::to(route('home'));
    }
}
