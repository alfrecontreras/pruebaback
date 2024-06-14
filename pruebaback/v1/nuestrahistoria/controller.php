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
        $sql = "SELECT id, texto,activo FROM nuestra_historia;";
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
        $sql = "SELECT id FROM nuestra_historia ORDER BY id DESC LIMIT 1;";
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
        $sql = "INSERT INTO nuestra_historia (id, texto,imagen, activo) VALUES ($id,'$_new->texto','$_new->imagen',$_new->activo)";
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
        $sql = "UPDATE nuestra_historia SET activo = $_action WHERE id = $_id";
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

    public function updateNameById($_parameter, $_id)
    {
        $con = new Conexion();
        // Obtener los valores del objeto $_new
        $sql = "UPDATE mantenedor SET nombre = '$_parameter' WHERE id = $_id";
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

    public function updateHistoriaById($_id,$imagen,$texto)
    {
        $con = new Conexion();
        $cantidadSet=0;
        $setFinal="";
        if(isset($imagen)){
            $setFinal.=" imagen='$imagen'";
            $cantidadSet++;
        }
        if(isset($texto)){
            if($cantidadSet==1){
                $setFinal.=",texto='$texto'";
            }else{
                $setFinal.=" texto='$texto'";
            }
            $cantidadSet++;
        }
        
        $sql = "UPDATE nuestra_historia SET $setFinal WHERE id = $_id";
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
        $sql = "DELETE FROM nuestra_historia WHERE id = $_id";

        try {
            // Verificar si el registro existe antes de intentar eliminarlo
            $existsSql = "SELECT COUNT(*) as count FROM nuestra_historia WHERE id = $_id";
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
