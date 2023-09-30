<?php 

namespace Controllers;

use MVC\Router;
use Model\Paquete;
use Model\Usuario;
use Model\Registro;
use Classes\Paginacion;

class RegistradosController {
    public static function index(Router $router){
        only_admin();

        // Paginación
        $pagina_actual = $_GET['page'];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

        if(!$pagina_actual || $pagina_actual < 1){
            header('Location: /admin/registrados?page=1');
        }

        $registros_por_pagina = 10;
        $total = Registro::total();
        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total);

        if($paginacion->total_paginas() < $pagina_actual){
            header('Location: /admin/registrados?page=1');
        }

        $registros = Registro::paginar($registros_por_pagina, $paginacion->offset());
        foreach($registros as $key => $registro){
            $object = $registro->getStdClass(); // Obtiene el objeto estándar con las propiedades del registro

            // Crea nuevas propiedades que asignamos al objeto recuperado
            $object->usuario = Usuario::find($registro->usuario_id); 
            $object->paquete = Paquete::find($registro->paquete_id); 
             

            // Por último, añade el nuevo objeto creado al array $registros, sustituyendo el antiguo objeto de la clase Evento por este objeto estándar
            $registros[$key] = $object;
        }

        $router->render('admin/registrados/index', [
            'titulo' => 'Usuarios Registrados',
            'registros' => $registros,
            'paginacion' => $paginacion->paginacion()
        ]);
    }
}