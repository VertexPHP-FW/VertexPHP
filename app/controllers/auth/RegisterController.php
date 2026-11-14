<?php
namespace controllers\auth;

use controllers\Controller;
use models\usuario;
use classes\Views as View;
use controllers\auth\SessionController as SC;

class RegisterController extends Controller {
    public function index() {
        View::render('auth/register', ['ua' => SC::sessionValidate() ?? ['sv' => 0], 'title' => 'Crear cuenta', 'code' => 200]);
    }

    public function register() {
        $data = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW) ?: [];
        $name = trim($data['nombre'] ?? '');
        $email = filter_var(trim($data['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $password = $data['passwd'] ?? '';
        if (!$name || !$email || strlen($password) < 8) {
            echo json_encode(['success' => false, 'error' => 'Ingresa un nombre, correo válido y contraseña de al menos 8 caracteres.']);
            return;
        }
        echo (new usuario())->register($name, $email, $password);
    }
}
