<?php 

namespace Controllers;

use Model\Dia;
use Model\Hora;
use MVC\Router;
use Model\Evento;
use Model\Ponente;
use Model\Categoria;
use Classes\Paginacion;

class EventosController {
    public static function index(Router $router){
        only_admin();

        // Paginación
        $pagina_actual = $_GET['page'];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

        if(!$pagina_actual || $pagina_actual < 1){
            header('Location: /admin/eventos?page=1');
        }

        $por_pagina = 10;
        $total = Evento::total();
        $paginacion = new Paginacion($pagina_actual, $por_pagina, $total);

        if($paginacion->total_paginas() < $pagina_actual){
            header('Location: /admin/eventos?page=1');
        }

        $eventos = Evento::paginar($por_pagina, $paginacion->offset());

        foreach($eventos as $key => $evento){
            $object = $evento->getStdClass(); // Obtiene el objeto estándar con las propiedades del evento

            // Crea nuevas propiedades que asignamos al objeto recuperado
            $object->categoria = Categoria::find($evento->categoria_id); 
            $object->dia = Dia::find($evento->dia_id); 
            $object->hora = Hora::find($evento->hora_id); 
            $object->ponente = Ponente::find($evento->ponente_id); 

            // Por último, añade el nuevo objeto creado al array $eventos, sustituyendo el antiguo objeto de la clase Evento por este objeto estándar
            $eventos[$key] = $object;
        }

        $router->render('admin/eventos/index', [
            'titulo' => 'Conferencias y Workshops',
            'eventos' => $eventos,
            'paginacion' => $paginacion->paginacion()
        ]);
    }

    public static function crear(Router $router){
        only_admin();
        
        $alertas = [];

        $categorias = Categoria::all('ASC');
        $dias = Dia::all('ASC');
        $horas = Hora::all('ASC');

        $evento = new Evento;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            only_admin();

            $evento->sincronizar($_POST);
            
            $alertas = $evento->validar();

            if(empty($alertas)){
                $resultado = $evento->guardar();   
                if($resultado){
                    header('Location: /admin/eventos');
                }
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
        only_admin();

        $alertas = [];

        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if(!$id){
            header('Location: /admin/eventos');
        }

        $categorias = Categoria::all('ASC');
        $dias = Dia::all('ASC');
        $horas = Hora::all('ASC');

        $evento = Evento::find($id);

        if(!$evento){
            header('Location: /admin/eventos');
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            only_admin();

            $evento->sincronizar($_POST);
            
            $alertas = $evento->validar();

            if(empty($alertas)){
                $resultado = $evento->guardar();   
                if($resultado){
                    header('Location: /admin/eventos');
                }
            }
        }
        
        $router->render('admin/eventos/editar', [
            'titulo' => 'Editar Evento',
            'alertas' => $alertas,
            'categorias' => $categorias,
            'dias' => $dias,
            'horas' => $horas,
            'evento' => $evento
        ]);
    }

    public static function eliminar(){
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            only_admin();
            $id = $_POST['id'];
            $evento = Evento::find($id);
            if(!isset($evento)){
                header('Location: /admin/eventos');
            }
            $resultado = $evento->eliminar();
            if($resultado){
                header('Location: /admin/eventos');
            }
        }

    }
}