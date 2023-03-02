<?php

//inicializar variables
$mensajes = $nif = $nombre = $apellidos = $fechaingreso = null;

//Si el botón de alta ha sido pulsado
if (isset($_POST['alta'])) {

    try {
        //Recuperar los datos del usuario
        $nif = addslashes($_POST['nif']);
        $nombre = addslashes($_POST['nombre']);
        $apellidos = addslashes($_POST['apellidos']);
        $fechaingreso = addslashes($_POST['fechaingreso']);

        //Validamos los datos con una función
        $validacion = validacionDatos($nif, $nombre, $apellidos, $fechaingreso, $mensajes);

        //Si es un String, pasamos para el mensajes 
        if (is_string($validacion)) {
            $mensajes = $validacion;
        }

        if ($validacion === TRUE) {
            //Confeccionamos la sentencia INSERT para la inclusión del paciente en la base de datos
            $sql = "INSERT INTO paciente (nif, nombre, apellidos, fechaingreso)
                        VALUES('$nif' , '$nombre', '$apellidos', '$fechaingreso')";

            //Utilizamos el condicional para validar si el NIF ya existe en la base de datos
            if (!mysqli_query($conexionHospital, $sql)) {
                if ($conexionHospital -> errno == 1062) {
                    $mensajes = "El paciente ya existe en la base de datos";
                }
                //texto del error, código de error
                throw new Exception($conexionHospital -> error, $conexionHospital -> errno);
            } else {
                $mensajes = "¡Alta Efectuada!";
            }
        }

        //Cerramos la conexion con la base de datos
        $conexionHospital->close();

    } catch (Exception $e) {
        $errores = $e->getMessage();
    }

}

function validacionDatos($ni, $no, $ap, $fe, $er)
{
    //Validamos cada uno de los datos. Verificamos si está vacio. 
    if (empty($ni)) {
        $er = "NIF obligatorio<br>";
        return $er;
    } elseif (empty($no)) {
        $er = "Nombre obligatorio<br>";
        return $er;
    } elseif (empty($ap)) {
        $er = "Apellidos obligatorio<br>";
        return $er;
    } elseif (empty($fe)) {
        $er = "Fecha de Ingreso obligatorio<br>";
        return $er;
    } elseif (!empty($er)) {
        throw new Exception($er);
    } else {
        return true;
    }
}

?>