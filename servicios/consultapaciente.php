<?php

//Iniciar varibla de session
session_start();

//Definir variables
$idpaciente = $mensajes = $nif = $nombre = $apellidos = $fechaingreso = $fechaalta = null;

//Utilizamos un operador de fusión de php que nos permite recuperar el id por el botón detalle o por la session
$idpaciente = filter_input(INPUT_POST, 'idpaciente', FILTER_VALIDATE_INT) ?? $_SESSION['paciente'] ?? null;

if (!empty($idpaciente)) {
    //Confeccionamos la sentencia SELECT para consulta un paciente en la base de datos
    $sql = "SELECT * FROM paciente WHERE idpaciente='$idpaciente'";
    $objetoDatos = mysqli_query($conexionHospital, $sql);

    $paciente = mysqli_fetch_assoc($objetoDatos);

    if ($objetoDatos->num_rows == 0) {
        $mensajes = "Paciente no existe.";
    } else {
        //Guardamos el id del paciente en la variable de session
        $_SESSION['paciente'] = $idpaciente;

        //Iremos imprimir los datos de cada persona utilizando array tipo clave/valor
        $nif = $paciente['nif'];
        $nombre = $paciente['nombre'];
        $apellidos = $paciente['apellidos'];
        $fechaingreso = $paciente['fechaingreso'];
        $fechaalta = $paciente['fechaalta'];
    }
}
else {
    $mensajes = "Todavía ningun paciente consultado.";
}

if (isset($_POST['mantenimiento'])) {

    try {
        //Recuperamos el id del paciente
        $idpaciente = addslashes($_POST['idpaciente']);

        //validamos que se encuentre informado, sea numérico y tenga un valor mayor que cero
        if (isset($idpaciente) && is_numeric($idpaciente) && $idpaciente > 0) {

            //Confeccionamos la sentencia SELECT para consulta un paciente en la base de datos
            $sql = "SELECT * FROM paciente WHERE idpaciente='$idpaciente'";

            //La consulta nos devuelve un objeto
            $objetoDatos = mysqli_query($conexionHospital, $sql);

            //Creamos un array a partir de este objeto
            $paciente = mysqli_fetch_assoc($objetoDatos);

            if ($objetoDatos->num_rows == 0) {
                $mensajes = "Paciente no existe.";
            } else {
                //Guardamos el id del paciente en la variable de session
                $_SESSION['paciente'] = $idpaciente;

                //Iremos imprimir los datos de cada persona utilizando array tipo clave/valor
                $nif = $paciente['nif'];
                $nombre = $paciente['nombre'];
                $apellidos = $paciente['apellidos'];
                $fechaingreso = $paciente['fechaingreso'];
                $fechaalta = $paciente['fechaalta'];
            }

        } else {
            $mensajes = "No hay paciente seleccionado";
        }

    } catch (Exception $e) {
        $errores = $e->getMessage();
    }
}