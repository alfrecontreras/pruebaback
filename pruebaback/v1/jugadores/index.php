<?php
include_once '../version1.php';

//valores parametros del carrusel
$existeId = false;
$valorId = 0;
$existeAccion = false;
$valorAccion = "";

if (count($_parametros) > 0) {
    foreach ($_parametros as $p) {
        if (strpos($p, 'id') !== false) {
            $existeId = true;
            $valorId = explode('=', $p)[1];
        }
        if (strpos($p, 'accion') !== false) {
            $existeAccion = true;
            $valorAccion = explode('=', $p)[1];
        }
    }
}
if ($_version == 'v1') {
    if ($_mantenedor == 'jugadores') {
        switch ($_metodo) {
            case 'GET':
                if ($_header == $_token_get) {
                    include_once './controller.php';
                    include_once './model.php';
                    include_once '../conexion.php';
                    if ($existeId == true) {
                        //http_response_code(200);
                        //echo json_encode(['data' => 'un objeto de id (' . $valorId . ') buscado']);
                    } else {
                        //all
                        $control = new Controlador();
                        $lista = $control->getAll();
                        http_response_code(200);
                        echo json_encode(['data' => $lista]);
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(['Error' => 'Sin autorización']);
                }
                break;
            case 'POST':
                if ($_header == $_token_post) {
                    include_once './controller.php';
                    include_once './model.php';
                    include_once '../conexion.php';
                    $body = json_decode(file_get_contents("php://input", true));
                    $control = new Controlador();
                    $nuevo = new Modelo();
                    if(!isset($body->nombre)||!isset($body->apellido)||!isset($body->posicionId)||!isset($body->profesion)){
                        http_response_code(400);
                        echo json_encode(['Error' => 'Faltan datos en el body']);
                        break;
                    }
                    if($body->nombre===""||$body->apellido===""||$body->posicionId===""||$body->profesion===""){
                        http_response_code(400);
                        echo json_encode(['Error' => 'Faltan datos en el body']);
                        break;
                    }
                    $nuevo->personal->profesion=$body->profesion;
                    $nuevo->activo = true;
                    $lista=$control->getPosicionId($body->posicionId);
                    if($lista==null){
                        http_response_code(400);
                        echo json_encode(['Error' => 'Posición ID inválido']);
                        break;
                    }
                    $nuevo = $control->postNew($nuevo,$body->nombre,$body->apellido,$body->posicionId);
                    if ($nuevo != null) {
                        http_response_code(201);
                        echo json_encode(['data' => $nuevo]);
                    } else {
                        http_response_code(409);
                        echo json_encode(['data' => false]);
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(['Error' => 'Sin autorización']);
                }
                break;
            case 'PUT':
                if ($_header == $_token_put) {
                    include_once './controller.php';
                    include_once './model.php';
                    include_once '../conexion.php';
                    $control = new Controlador();
                    $body = json_decode(file_get_contents("php://input", true));
                    $nombre=null;
                    $apellido=null;
                    $profesion=null;
                    $posicion_id=null;
                    if(!isset($body->jugador)){
                        http_response_code(400);
                        echo json_encode(['Error' => 'Falta ingresar datos de jugador']);
                        break;
                    }
                    if(!isset($body->jugador->id)){
                        http_response_code(400);
                        echo json_encode(['Error' => 'Falta ingresar datos de jugador']);
                        break;
                    }

                    if(!isset($body->jugador->nombre) && !isset($body->jugador->apellido) &&!isset($body->jugador->profesion)&&!isset($body->jugador->posicion_id)){
                        http_response_code(400);
                        echo json_encode(['Error' => 'Falta ingresar datos de jugador']);
                        break;
                    }

                    if(isset($body->jugador->nombre)){
                        $nombre=$body->jugador->nombre;
                    }
                    if(isset($body->jugador->apellido)){
                        $apellido=$body->jugador->apellido;
                    }
                    if(isset($body->jugador->profesion)){
                        $profesion=$body->jugador->profesion;
                    }
                    if(isset($body->jugador->posicion_id)){
                        $posicion_id=$body->jugador->posicion_id;
                        $lista=$control->getPosicionId($body->jugador->posicion_id);
                        if($lista==null){
                            http_response_code(400);
                            echo json_encode(['Error' => 'Posicion Id inválido']);
                            break;
                        }
                    }
                    $respuesta=$control->updateRegistroById($body->jugador->id,$nombre,$apellido,$profesion,$posicion_id);
                    http_response_code(200);
                    echo json_encode(['data' => $respuesta]);
                } else {
                    http_response_code(401);
                    echo json_encode(['Error' => 'Sin autorización']);
                }
                break;
            case 'PATCH':
                if ($_header == $_token_patch) {
                    $body = json_decode(file_get_contents("php://input", true));
                    // echo $valorAccion . ']';
                    if ($existeAccion && $existeId) {
                        include_once './controller.php';
                        include_once './model.php';
                        include_once '../conexion.php';
                        $control = new Controlador();
                        if ($valorAccion == 'encender') {
                            http_response_code(200);
                            $resultado = $control->patchEncenderApagar($valorId, 'true');
                            echo json_encode(['data' => $resultado]);
                        } else if ($valorAccion == 'apagar') {
                            http_response_code(200);
                            $resultado = $control->patchEncenderApagar($valorId, 'false');
                            echo json_encode(['data' => $resultado]);
                        } else {
                            echo json_encode(['Error' => 'No implementado']);
                        }
                    } else {
                        http_response_code(400);
                        echo json_encode(['Error' => 'incompleto']);
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(['Error' => 'Sin autorización']);
                }
                break;
            case 'DELETE':
                if ($_header == $_token_delete) {
                    include_once './controller.php';
                    include_once './model.php';
                    include_once '../conexion.php';
                    $control = new Controlador();
                    if ($existeId == true) {
                        $respuesta = $control->deleteById($valorId);
                        if ($respuesta) {
                            http_response_code(200);
                        } else {
                            http_response_code(404);
                        }
                        echo json_encode(['data' => $respuesta]);
                    } else {
                        //no se puede eliminar
                        http_response_code(404);
                        echo json_encode(['data' => false]);
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(['Error' => 'Sin autorización']);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(['Error' => 'No implementado']);
                break;
        }
    } else {
        http_response_code(405);
        echo json_encode(['mensaje' => 'No implementado']);
    }
} else {
    http_response_code(405);
    echo json_encode(['mensaje' => 'No implementado']);
}
