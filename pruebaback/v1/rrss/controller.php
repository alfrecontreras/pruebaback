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
        $sql = "SELECT id, nombre,icono,valor,activo FROM redes_sociales;";
        $rs = mysqli_query($con->getConnection(), $sql);
        if ($rs) {
            while ($tupla = mysqli_fetch_assoc($rs)) {
                $tupla['activo'] = $tupla['activo'] == 1 ? true : false;
                array_push($this->lista, $tupla);
            }
            mysqli_free_result($rs);
        }
        $con->closeConnection();
        return $this->lista;
    }

    public function getUltimoId(){
        $con = new Conexion();
        $sql = "SELECT id FROM redes_sociales ORDER BY id DESC LIMIT 1;";
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

    public function postNew($_new)
    {
        $con = new Conexion();
        $obtenerId = $this->getUltimoId();
        $id=$obtenerId[0]["id"]+1;
        // Obtener los valores del objeto $_new
        $sql = "INSERT INTO redes_sociales (id, nombre,icono,valor, activo) VALUES ($id,'$_new->nombre','$_new->icono','$_new->valor',$_new->activo)";
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
        $sql = "UPDATE redes_sociales SET activo = $_action WHERE id = $_id";
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

    public function updateRegistroById($_id,$nombre,$icono,$valor)
    {
        $con = new Conexion();
        $cantidadSet=0;
        $setFinal="";
        if(isset($nombre)){
            $setFinal.=" nombre='$nombre'";
            $cantidadSet++;
        }
        if(isset($icono)){
            if($cantidadSet==1){
                $setFinal.=",icono='$icono'";
            }else{
                $setFinal.=" icono='$icono'";
            }
            $cantidadSet++;
        }
        if(isset($valor)){
            if($cantidadSet>=1){
                $setFinal.=",valor='$valor'";
            }else{
                $setFinal.=" valor='$valor'";
            }
            $cantidadSet++;
        }
        
        $sql = "UPDATE redes_sociales SET $setFinal WHERE id = $_id";
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
        $sql = "DELETE FROM redes_sociales WHERE id = $_id";

        try {
            // Verificar si el registro existe antes de intentar eliminarlo
            $existsSql = "SELECT COUNT(*) as count FROM redes_sociales WHERE id = $_id";
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
