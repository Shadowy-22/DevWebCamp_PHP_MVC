<?php 

namespace Controllers;

use Model\Dia;
use MVC\Router;
use Model\Categoria;

class EventosController {
    public static function index(Router $router){

        $router->render('admin/eventos/index', [
            'titulo' => 'Conferencias y Workshops'
        ]);
    }

    public static function crear(Router $router){
        $alertas = [];

        $categorias = Categoria::all('ASC');
        $dias = Dia::all('ASC');
        
        $router->render('admin/eventos/crear', [
            'titulo' => 'Registrar Evento',
            'alertas' => $alertas,
            'categorias' => $categorias,
            'dias' => $dias
        ]);
    }

    public static function editar(Router $router){
        $alertas = [];

        $router->render('admin/eventos/editar', [
            'titulo' => 'Editar Evento'
        ]);
    }

    public static function eliminar(){


    }
}