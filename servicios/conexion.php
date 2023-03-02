<?php

$servidor = "localhost";
$usuario = "root";
$contrasenya = "";
$baseDatos = "hospital";

// Creacion de conexion
$conexionHospital = new mysqli($servidor, $usuario, $contrasenya, $baseDatos);

// Verificar conexion
if ($conexionHospital->connect_error) {
    $mensajes = "Conexion ha fallado: " . $conexionHospital->connect_error;
    exit();
}
