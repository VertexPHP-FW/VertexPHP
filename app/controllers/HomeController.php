<?php

    namespace controllers;
    use classes\Views as View;
    use controllers\auth\SessionController as SC;

    class HomeController extends Controller  {
        public function __construct()
        {
              parent::__construct();
        }

        public function index($params = null)
        {            
            
            $response =[
                'ua' => SC::sessionValidate() ?? [ 'sv' => 0 ],
                'title' => APP_NAME,
                'code' => 200,
            ];
            View::render('home',$response);

        }
    }
