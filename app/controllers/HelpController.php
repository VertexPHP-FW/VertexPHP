<?php
namespace controllers;

use classes\Views as View;
use controllers\auth\SessionController as SC;

class HelpController extends Controller {
    public function index($params = null) {
        View::render('help', [
            'ua' => SC::sessionValidate() ?? ['sv' => 0],
            'title' => 'Ayuda | ' . APP_NAME,
            'code' => 200,
        ]);
    }
}
