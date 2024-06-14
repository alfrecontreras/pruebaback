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
    if ($_mantenedor == 'sobrenosotros') {
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
                    if(!isset($body->logo_color)||!isset($body->descripcion)){
                        http_response_code(400);
                        echo json_encode(['Error' => 'Faltan datos en el body']);
                        break;
                    }
                    if($body->logo_color===""||$body->descripcion===""){
                        http_response_code(400);
                        echo json_encode(['Error' => 'Faltan datos en el body']);
                        break;
                    }
                    $nuevo->logo_color=$body->logo_color;
                    $nuevo->descripcion=$body->descripcion;
                    $nuevo->activo = true;
                    $nuevo = $control->postNew($nuevo);
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
                    $logo_color=null;
                    $descripcion=null;
                    if(!isset($body->sobrenosotros)){
                        http_response_code(400);
                        echo json_encode(['Error' => 'Falta ingresar datos de sobre nosotros']);
                        break;
                    }
                    if(!isset($body->sobrenosotros->id)){
                        http_response_code(400);
                        echo json_encode(['Error' => 'Falta ingresar datos de sobre nosotros']);
                        break;
                    }
                    if(!isset($body->sobrenosotros->logo_color) && !isset($body->sobrenosotros->descripcion)){
                        http_response_code(400);
                        echo json_encode(['Error' => 'Falta ingresar datos de sobre nosotros']);
                        break;
                    }

                    if(isset($body->sobrenosotros->logo_color)){
                        $logo_color=$body->sobrenosotros->logo_color;
                    }
                    if(isset($body->sobrenosotros->descripcion)){
                        $descripcion=$body->sobrenosotros->descripcion;
                    }
                    $respuesta=$control->updateRegistroById($body->sobrenosotros->id,$logo_color,$descripcion);
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
