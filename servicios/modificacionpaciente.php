<?php

//Si el botón de modificacion ha sido pulsado
if (isset($_POST['modificacion'])) {

    try {
        //Recuperar los datos del usuario
        $idpaciente = addslashes($_POST['idpaciente']);
        $nif = addslashes($_POST['nif']);
        $nombre = addslashes($_POST['nombre']);
        $apellidos = addslashes($_POST['apellidos']);
        $fechaingreso = addslashes($_POST['fechaingreso']);
        $fechaalta = addslashes($_POST['fechaalta']);

        //validamos que se encuentre informado, sea numérico y tenga un valor mayor que cero
        if (isset($idpaciente) && is_numeric($idpaciente) && $idpaciente > 0) {

            //Validamos los datos con una función
            $validacion = validacionDatos($nif, $nombre, $apellidos, $fechaingreso, $mensajes);

            //Si es un String, pasamos para el mensajes 
            if (is_string($validacion)) {
                $mensajes = $validacion;
            }

            if ($validacion === TRUE) {
                //Confeccionamos la sentencia UPDATE para actualizar los datos del paciente en la base de datos
                $sql = "UPDATE paciente SET nif='$nif', nombre='$nombre', apellidos='$apellidos', fechaingreso='$fechaingreso', 
                fechaalta='$fechaalta' WHERE idpaciente='$idpaciente'";
                
                $objetoDatos = mysqli_query($conexionHospital, $sql);

                if ($objetoDatos === TRUE) {
                    if ($conexionHospital->affected_rows == 0) {
                        $mensajes = "No hay datos alterados.";
                    } else {
                        $mensajes = "¡Modificacion Efectuada!";
                    }
                }
            }

            //Cerramos la conexion con la base de datos
            $conexionHospital->close();
        }

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