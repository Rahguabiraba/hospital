<?php
//Si el botón de modificacion ha sido pulsado
if (isset($_POST['baja'])) {

    try {
        //Recuperar los datos del usuario
        $idpaciente = addslashes($_POST['idpaciente']);

        //validamos que se encuentre informado, sea numérico y tenga un valor mayor que cero

        if (isset($idpaciente) && is_numeric($idpaciente) && $idpaciente >=0) {
            
            //Confeccionamos la sentencia DELETE para borrar el paciente de la base de datos
            $sql = "DELETE FROM paciente WHERE idpaciente='$idpaciente'";

            $objetoDatos = mysqli_query($conexionHospital, $sql);

            if ($objetoDatos === TRUE) {
                if ($conexionHospital->affected_rows == 0) {
                    $mensajes = "El paciente no existe.";
                }
                else {
                    //Eliminamos el paciente de la variable sessión
                    unset($_SESSION['paciente']);
                    $mensajes = "¡Baja Efectuada!";
                }

                //Cerramos la conexion con la base de datos
                $conexionHospital->close();
            }
        }

    } catch (Exception $e) {
        $errores = $e->getMessage();
    }

}