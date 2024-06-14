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
        $sql = "SELECT na.id, na.fecha, na.hora, el.id as entrenamiento_lugar_id, el.nombre as lugar_nombre, el.direccion, el.comuna, na.activo 
                FROM entrenamientos_proximos na 
                JOIN entrenamiento_lugar el ON na.entrenamiento_lugar_id = el.id;";
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

    public function getEntrenamientoLugar($id){
        $con = new Conexion();
        $sql = "SELECT id FROM entrenamiento_lugar WHERE id=$id";
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
        $sql = "SELECT entrenamientos_proximos.id FROM entrenamientos_proximos ORDER BY entrenamientos_proximos.id DESC LIMIT 1;";
        $rs = mysqli_query($con->getConnection(), $sql);
        if ($rs) {
            while ($tupla = mysqli_fetch_assoc($rs)) {
                $valor=$tupla;
                //var_dump($tupla);
            }
            mysqli_free_result($rs);
        }
        $con->closeConnection();
        return $valor;
    }

    public function postNew($_new)
    {
        $con = new Conexion();
        $obtenerId = $this->getUltimoId();
        $id=$obtenerId['id']+1;
        // Obtener los valores del objeto $_new
        $momento=$_new->momento;
        $lugar=$_new->lugar;
        $sql = "INSERT INTO entrenamientos_proximos (id, fecha,hora,entrenamiento_lugar_id, activo) VALUES ($id,'$momento->fecha','$momento->horario',$lugar->id,$_new->activo)";
        // echo $sql;
        $rs = [];
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            //var_dump($th);
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
        $sql = "UPDATE entrenamientos_proximos SET activo = $_action WHERE id = $_id";
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

    public function updateRegistroById($_id,$fecha,$hora,$lugarid)
    {
        $con = new Conexion();
        $cantidadSet=0;
        $setFinal="";
        if(isset($fecha)){
            $setFinal.=" fecha='$fecha'";
            $cantidadSet++;
        }
        if(isset($hora)){
            if($cantidadSet==1){
                $setFinal.=",hora='$hora'";
            }else{
                $setFinal.=" hora='$hora'";
            }
            $cantidadSet++;
        }
        if(isset($lugarid)){
            if($cantidadSet>=1){
                $setFinal.=",entrenamiento_lugar_id=$lugarid";
            }else{
                $setFinal.=" entrenamiento_lugar_id=$lugarid";
            }
            $cantidadSet++;
        }
        
        $sql = "UPDATE entrenamientos_proximos SET $setFinal WHERE id = $_id";
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
        $sql = "DELETE FROM entrenamientos_proximos WHERE id = $_id";

        try {
            // Verificar si el registro existe antes de intentar eliminarlo
            $existsSql = "SELECT COUNT(*) as count FROM entrenamientos_proximos WHERE id = $_id";
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
