<?php 

namespace Controllers;

use Model\Evento;
use MVC\Router;
use Model\Usuario;
use Model\Registro;

class DashboardController {
    public static function index(Router $router){

        // Obtener los ultimos registros
        $registros = Registro::get(5);

        foreach($registros as $key => $registro){
            $object = $registro->getStdClass();

            $object->usuario = Usuario::find($registro->usuario_id);

            $registros[$key] = $object;
        }

        // Calcular los ingresos
        $virtuales = Registro::total('paquete_id', 2);
        $presenciales = Registro::total('paquete_id', 1);

        $ingresos = ($virtuales * 46.41) + ($presenciales * 189.54);

        // Obtener eventos con más y ménos lugares disponibles
        $menos_disponibles = Evento::ordenarLimite('disponibles', 'ASC', 5);
        $mas_disponibles = Evento::ordenarLimite('disponibles', 'DESC', 5);

        $router->render('admin/dashboard/index', [
            'titulo' => 'Panel de Administración',
            'registros' => $registros,
            'ingresos' => $ingresos,
            'menos_disponibles' => $menos_disponibles,
            'mas_disponibles' => $mas_disponibles
        ]);
    }
}