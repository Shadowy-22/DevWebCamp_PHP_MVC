<?php 

namespace Controllers;

use Model\Dia;
use Model\Hora;
use MVC\Router;
use Model\Evento;
use Model\Regalo;
use Model\Paquete;
use Model\Ponente;
use Model\Usuario;
use Model\Registro;
use Model\Categoria;
use Model\EventosRegistros;

class RegistroController {
    public static function crear(Router $router){
        if(!is_auth()) {
            header('Location: /');
            return;
        }

        // Verificar si el usuario ya está registrado
        $registro = Registro::where('usuario_id', $_SESSION['id']);
        if(isset($registro) && ($registro->paquete_id === '3' || $registro->paquete_id === '2')) {
            header('Location: /boleto?id=' . urlencode($registro->token));
            return;
        };

        if(isset($registro) && $registro->paquete_id === "1"){
            header('Location: /finalizar-registro/conferencias');
            return;
        }

        $router->render('registro/crear',[
            'titulo' => 'Finalizar Registro'
        ]);
    }

    public static function gratis(){
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            if(!is_auth()) {
                header('Location: /login');
                return;
            }

            // Verificar si el usuario ya está registrado
            $existeRegistro = Registro::where('usuario_id', $_SESSION['id']);
            if(isset($existeRegistro) && $existeRegistro->paquete_id === '3') {
                header('Location: /boleto?id=' . urlencode($existeRegistro->token));
                return;
            };

            $token = substr( md5(uniqid( rand(), true)), 0, 8);
            
            // Crear Registro
            $datos = [
                'paquete_id' => 3,
                'pago_id' => '',
                'token' => $token,
                'usuario_id' => $_SESSION['id']
            ];

            $registro = new Registro($datos);
            $resultado = $registro->guardar();
            
            if($resultado) {
                header('Location: /boleto?id=' . urlencode($registro->token));
                return;
            }
        }
    }

    public static function pagar(){
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            if(!is_auth()) {
                header('Location: /login');
                return;
            }

            // Validar que POST No venga vacio
            if(empty($_POST)){
                echo json_encode([]);
                return;
            }

            // Crear el Registro
            $datos = $_POST;
            $datos['token'] = substr( md5(uniqid( rand(), true)), 0, 8);
            $datos['usuario_id'] = $_SESSION['id'];         
            
            try {
                $registro = new Registro($datos);
                $resultado = $registro->guardar();
                $resultado['host'] = $_ENV['HOST']; 
                echo json_encode($resultado);
            } catch (\Throwable $th) {
                echo json_encode([
                    'resultado' => 'error'
                ]);
            }
        }
    }

    public static function boleto(Router $router){
        // Validar la URL
        $id = $_GET['id'];
        if(!$id || !strlen($id) === 8){
            header('Location: /');
            return;
        }

        // Buscarlo en la BD
        $registro = Registro::where('token', $id);
        if(!$registro){
            header('Location: /');
            return;
        }

        // Llenar las tablas de referencia
        $object = $registro->getStdClass(); // Crea el objeto modelo
        $object->usuario = Usuario::find($registro->usuario_id);
        $object->paquete = Paquete::find($registro->paquete_id);
        
        $router->render('registro/boleto',[
            'titulo' => 'Asistencia a DevWebCamp',
            'registro' => $object
        ]);
    }

    public static function conferencias(Router $router){
        
        if(!is_auth()) {
            header('Location: /login');
            return;
        }
        
        // Validar que el usuario tenga el plan presencial
        $usuario_id =  $_SESSION['id'];
        $registro = Registro::where('usuario_id', $usuario_id);

        // Si obtuvo el Pase Virtual
        if(isset($registro) && $registro->paquete_id === "2"){
            header('Location: /boleto?id=' . urlencode($registro->token));
            return;
        }

        // Si obtuvo el pase gratis
        if($registro->paquete_id !== "1"){
            header('Location: /');
            return;
        }

        $registroCompletado = EventosRegistros::where('registro_id', $registro->id);

        // Redireccionar a boleto virtual en caso de haber finalizado su registro
        if($registroCompletado){
            header('Location: /boleto?id=' . urlencode($registro->token));
            return;
        }
        

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
        
        $regalos = Regalo::all('ASC');

        // Manejando el registro mediante $_POST
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_auth()) {
                header('Location: /login');
                return;
            }

            // Verificar que el usuario no haya enviado el evento vacio
            $eventos = explode(',', $_POST['eventos_id']);
            if(empty($eventos)) {
                echo json_encode(['resultado' => false]);
                return;
            }

            // Obtener el registro de usuario
            $registro = Registro::where('usuario_id', $_SESSION['id']);

            // Verificar que el registro correspondiente al usuario exista y tenga el Paquete Presencial
            if(!isset($registro) || $registro->paquete_id !== "1") {
                echo json_encode(['resultado' => false]);
                return;
            }

            $eventos_array = [];
            // Validar la disponibilidad de los eventos seleccionados
            foreach($eventos as $evento_id){
                $evento = Evento::find($evento_id);
                
                // Comprobar que el evento exista
                if(!isset($evento) || $evento->disponibles === "0"){
                    echo json_encode(['resultado' => false]);
                    return;
                }

                $eventos_array[] = $evento;
            }

            foreach($eventos_array as $evento){
                $evento->disponibles -= 1;
                $evento->guardar();

                // Almacenar el registro
                $datos = [
                    'evento_id' => (int) $evento->id,
                    'registro_id' => (int) $registro->id
                ];

                $registro_usuario = new EventosRegistros($datos);
                $registro_usuario->guardar();
            }

            // Almacenar el regalo
            $registro->sincronizar(['regalo_id' => $_POST['regalo_id']]);
            $resultado = $registro->guardar();

            if($resultado){
                echo json_encode([
                    'resultado' => $resultado,
                    'token' => $registro->token
                ]);
            } else {
                echo json_encode(['resultado' => false]);
            }

            return;
        }

        $router->render('registro/conferencias',[
            'titulo' => 'Elige Workshops y Conferencias',
            'eventos' => $eventos_formateados,
            'regalos' => $regalos
        ]);
    }
}