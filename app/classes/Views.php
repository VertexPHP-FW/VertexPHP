<?php

    namespace classes;
    class Views{
        public function __construct(){      
                    
        }

        /**
         * La función "renderizar" en PHP se utiliza para representar una vista con datos opcionales pasados.
         * 
         * @param view El parámetro `` en la función `render` es una cadena que representa el nombre del
         * archivo de vista que se va a representar. Este archivo de vista suele ser un archivo PHP que
         * contiene el formato HTML que se mostrará al usuario.
         * @param data El parámetro `` en la función `render` es una matriz que contiene los datos que se
         * pasarán a la vista para su renderización. Es opcional y tiene un valor predeterminado de una matriz
         * vacía `[]`. Estos datos pueden incluir variables, matrices u objetos a los que se debe acceder
         * dentro de la vista.
         */
        public static function render($view, $data = []){
            $d = as_object($data);
            require_once VIEWS . $view . '.view.php';
            exit();
        }
    }
