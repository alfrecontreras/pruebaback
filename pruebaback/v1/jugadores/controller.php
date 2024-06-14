<?php

class Controlador
{

    private $lista;

    public function __construct()
    {
        $this->lista = [];
    }

    public function getAll()
    {
        $con = new Conexion();
        $sql = "SELECT j.id, j.nombre, j.apellido, j.profesion, 
                       jp.abreviado as posicion_abreviado, jp.nombre as posicion_nombre,
                       rs.valor as rrss_valor, rs.activo as rrss_activo,
                       red.nombre as rrss_nombre, red.icono as rrss_icono,
                       j.activo 
                FROM jugador j
                JOIN jugador_posicion jp ON j.posicion_id = jp.id
                LEFT JOIN jugador_rrss rs ON j.id = rs.jugador_id AND rs.activo = 1
                LEFT JOIN red_social red ON rs.red_social_id = red.id AND red.activo = 1 ORDER BY j.id ASC";
        $rs = mysqli_query($con->getConnection(), $sql);
        if ($rs) {
            while ($tupla = mysqli_fetch_assoc($rs)) {
                $tupla['activo'] = $tupla['activo'] == 1 ? true : false;
                $modelo=new Modelo();
                $modelo->fromArray($tupla);
                array_push($this->lista, $modelo);
            }
            mysqli_free_result($rs);
        }
        $con->closeConnection();
        return $this->lista;
    }

    public function getPosicionId($id){
        $con = new Conexion();
        $sql = "SELECT id FROM jugador_posicion WHERE id=$id AND activo=1";
        $rs = mysqli_query($con->getConnection(), $sql);
        if ($rs) {
            while ($tupla = mysqli_fetch_assoc($rs)) {
                array_push($this->lista, $tupla);
            }
            mysqli_free_result($rs);
        }
        $con->closeConnection();
        return $this->lista;
    }

    public function getUltimoId(){
        $valor=null;
        $con = new Conexion();
        $sql = "SELECT id FROM jugador ORDER BY id DESC LIMIT 1;";
        $rs = mysqli_query($con->getConnection(), $sql);
        if ($rs) {
            while ($tupla = mysqli_fetch_assoc($rs)) {
                $valor=$tupla;
            }
            mysqli_free_result($rs);
        }
        $con->closeConnection();
        return $valor;
    }

    public function postNew($_new,$nombre,$apellido,$posicionId)
    {
        $con = new Conexion();
        $obtenerId = $this->getUltimoId();
        var_dump($obtenerId);
        $id=$obtenerId["id"]+1;
        // Obtener los valores del objeto $_new
        $personal=$_new->personal;
        $sql = "INSERT INTO jugador (id, nombre,apellido,profesion,posicion_id,activo) VALUES ($id,'$nombre','$apellido','$personal->profesion',$posicionId,$_new->activo)";
        // echo $sql;
        $rs = [];
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = [];
        }
        // Cerrar la conexión
        $con->closeConnection();
        // Verificar si la preparación de la consulta fue exitosa
        // var_dump($rs);
        if ($rs) {
            return true;
        } else {
            // echo "Error en la preparación de la consulta: " . mysqli_error($con->getConnection());
            return null;
        }
    }

    public function patchEncenderApagar($_id, $_action)
    {
        $con = new Conexion();
        // Obtener los valores del objeto $_new
        $sql = "UPDATE jugador SET activo = $_action WHERE id = $_id";
        // echo $sql;
        $rs = [];
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = [];
        }
        // Cerrar la conexión
        $con->closeConnection();
        // Verificar si la preparación de la consulta fue exitosa
        // var_dump($rs);
        if ($rs) {
            return true;
        } else {
            // echo "Error en la preparación de la consulta: " . mysqli_error($con->getConnection());
            return null;
        }
    }

    public function updateRegistroById($_id,$nombre,$apellido,$profesion,$posicionId)
    {
        $con = new Conexion();
        $cantidadSet=0;
        $setFinal="";
        if(isset($nombre)){
            $setFinal.=" nombre='$nombre'";
            $cantidadSet++;
        }
        if(isset($apellido)){
            if($cantidadSet==1){
                $setFinal.=",apellido='$apellido'";
            }else{
                $setFinal.=" apellido='$apellido'";
            }
            $cantidadSet++;
        }
        if(isset($profesion)){
            if($cantidadSet>=1){
                $setFinal.=",profesion='$profesion'";
            }else{
                $setFinal.=" profesion='$profesion'";
            }
            $cantidadSet++;
        }

        if(isset($posicionId)){
            if($cantidadSet>=1){
                $setFinal.=",posicion_id=$posicionId";
            }else{
                $setFinal.=" posicion_id=$posicionId";
            }
            $cantidadSet++;
        }
        
        $sql = "UPDATE jugador SET $setFinal WHERE id = $_id";
        // echo $sql;
        $rs = [];
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = [];
        }
        // Cerrar la conexión
        $con->closeConnection();
        // Verificar si la preparación de la consulta fue exitosa
        // var_dump($rs);
        if ($rs) {
            return true;
        } else {
            // echo "Error en la preparación de la consulta: " . mysqli_error($con->getConnection());
            return null;
        }
    }

    public function deleteById($_id)
    {
        $con = new Conexion();
        $sql = "DELETE FROM jugador WHERE id = $_id";

        try {
            // Verificar si el registro existe antes de intentar eliminarlo
            $existsSql = "SELECT COUNT(*) as count FROM jugador WHERE id = $_id";
            $result = mysqli_query($con->getConnection(), $existsSql);
            $row = mysqli_fetch_assoc($result);
            $exists = $row['count'] > 0;

            // Si el registro no existe, retornar falso
            if (!$exists) {
                $rs = false;
            } else {
                // Ejecutar la consulta DELETE si el registro existe
                $deleteResult = mysqli_query($con->getConnection(), $sql);
                // Verificar si se eliminó correctamente
                $rs = $deleteResult !== false;
            }
        } catch (\Throwable $th) {
            // Manejar cualquier excepción que ocurra durante la ejecución de la consulta
            error_log($th->getMessage()); // Registrar el mensaje de error en el registro de errores
            $rs = false; // Establecer $rs como false en caso de error
        }

        // Cerrar la conexión
        $con->closeConnection();

        // Devolver true si se eliminó correctamente, de lo contrario, false
        return $rs;
    }
}
