<?php
    namespace controllers;
    use classes\Views as View;
    use controllers\auth\SessionController as SC;

    class ErrorController{
        public function __construct(){      

        }

        public function error100(){
            http_response_code(404);
            $response = [
                'ua' => SC::sessionValidate() ?? [ 'sv' => 0 ],
                'title' => "Error: Acción no permitida",
                'code' => 100
            ];

            View::render('error',$response);
        }
                    

        public function error404(){
            http_response_code(404);
            $response = [
                'ua' => SC::sessionValidate() ?? [ 'sv' => 0 ],
                'title' => "Error: no encontrado",
                'code' => 404
            ];

            View::render('error',$response);
        }

        public function error405(){
            http_response_code(405);
            $response = [
                'ua' => SC::sessionValidate() ?? [ 'sv' => 0 ],
                'title' => "Error: método no permitido",
                'code' => 405
            ];

            View::render('error',$response);
        }

        public function error500(){
            $response = [
                'ua' => SC::sessionValidate() ?? [ 'sv' => 0 ],
                'title' => "Error: interno del servidor",
                'code' => 500
            ];

            View::render('error',$response);
        }
    }
