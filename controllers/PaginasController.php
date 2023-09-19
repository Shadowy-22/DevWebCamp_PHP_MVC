<?php

namespace Controllers;

use Model\Dia;
use Model\Hora;
use MVC\Router;
use Model\Evento;
use Model\Ponente;
use Model\Categoria;

class PaginasController {
    public static function index(Router $router) {

        $eventos = Evento::ordenar('hora_id', 'ASC');

        $eventos_formateados = [];
        foreach ($eventos as $evento) {
            $dia_nombre = Dia::find($evento->dia_id)->nombre;
            $categoria_nombre = Categoria::find($evento->categoria_id)->nombre;

            // Agrega las propiedades de categoría, día, hora y ponente al evento
            $evento->categoria = Categoria::find($evento->categoria_id);
            $evento->dia = Dia::find($evento->dia_id);
            $evento->hora = Hora::find($evento->hora_id);
            $evento->ponente = Ponente::find($evento->ponente_id);
            
            // Agrega el evento al array correspondiente en $eventos_formateados
            if (!isset($eventos_formateados[$categoria_nombre])) {
                $eventos_formateados[$categoria_nombre] = [];
            }
            
            if (!isset($eventos_formateados[$categoria_nombre][$dia_nombre])) {
                $eventos_formateados[$categoria_nombre][$dia_nombre] = [];
            }
            
            $eventos_formateados[$categoria_nombre][$dia_nombre][] = $evento;
        }

        // Obtener el total de cada bloque
        $ponentes_total = Ponente::total();
        $conferencias_total = Evento::total('categoria_id', 1);
        $workshops_total = Evento::total('categoria_id', 2);

        // Obtener todos los ponentes
        $ponentes = Ponente::all();

        $router->render('paginas/index', [
            'titulo' => 'Inicio',
            'eventos' => $eventos_formateados,
            'ponentes_total' => $ponentes_total,
            'conferencias_total' => $conferencias_total,
            'workshops_total' => $workshops_total,
            'ponentes' => $ponentes
        ]);
    }

    public static function evento(Router $router) {


        $router->render('paginas/devwebcamp', [
            'titulo' => 'Sobre DevWebCamp'
        ]);
    }

    public static function paquetes(Router $router) {


        $router->render('paginas/paquetes', [
            'titulo' => 'Paquetes DevWebCamp'
        ]);
    }

    public static function conferencias(Router $router) {
        
        $eventos = Evento::ordenar('hora_id', 'ASC');

        $eventos_formateados = [];
        foreach ($eventos as $evento) {
            $dia_nombre = Dia::find($evento->dia_id)->nombre;
            $categoria_nombre = Categoria::find($evento->categoria_id)->nombre;

            // Agrega las propiedades de categoría, día, hora y ponente al evento
            $evento->categoria = Categoria::find($evento->categoria_id);
            $evento->dia = Dia::find($evento->dia_id);
            $evento->hora = Hora::find($evento->hora_id);
            $evento->ponente = Ponente::find($evento->ponente_id);
            
            // Agrega el evento al array correspondiente en $eventos_formateados
            if (!isset($eventos_formateados[$categoria_nombre])) {
                $eventos_formateados[$categoria_nombre] = [];
            }
            
            if (!isset($eventos_formateados[$categoria_nombre][$dia_nombre])) {
                $eventos_formateados[$categoria_nombre][$dia_nombre] = [];
            }
            
            $eventos_formateados[$categoria_nombre][$dia_nombre][] = $evento;
        }

        $router->render('paginas/conferencias', [
            'titulo' => 'Conferencias & Workshops',
            'eventos' => $eventos_formateados
        ]);
    }
    
    public static function error(Router $router) {
        

        $router->render('paginas/error', [
            'titulo' => 'Página no encontrada'
        ]);
    }
}