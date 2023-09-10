<?php 

namespace Controllers;

use Model\Dia;
use Model\Hora;
use MVC\Router;
use Model\Evento;
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
        $horas = Hora::all('ASC');

        $evento = new Evento;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $evento->sincronizar($_POST);
            
            $alertas = $evento->validar();

            // debug($evento);

            if(empty($alertas)){

                // $resultado = $evento->guardar();   
                // if($resultado){
                //     header('Location: /admin/eventos/');
                // }
            }
        }
        
        $router->render('admin/eventos/crear', [
            'titulo' => 'Registrar Evento',
            'alertas' => $alertas,
            'categorias' => $categorias,
            'dias' => $dias,
            'horas' => $horas,
            'evento' => $evento
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