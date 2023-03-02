<?php
//Definir variable que recibirá el contenido de la tabla
$contenidoTabla = $mensajes = null;

//Recuperamos el numero de registros a mostrar
$filas_a_mostrar = filter_input(INPUT_POST, 'numpacientes', FILTER_VALIDATE_INT) ?? 5;

//Recuperamos el numero de paginas
$pagina = filter_input(INPUT_GET, 'pagina') ?? 1;

//Definimos el registro que tenemos que seleccionar
$fila_desde = ($pagina - 1) * $filas_a_mostrar;

//Recuperamos el valor que nos llega del control de búsqueda
$buscar_apellido = filter_input(INPUT_POST, 'buscaapellido') ?? null;


//Definimos el registro que tenemos que seleccionar
$fila_desde = ($pagina - 1) * $filas_a_mostrar;

try {
    //Confeccionamos la sentencia SELECT para consultar todos los pacientes en la base de datos
    $sql = "SELECT * FROM paciente WHERE apellidos LIKE '%$buscar_apellido%' ORDER By nombre,apellidos LIMIT " . $fila_desde . ", " . $filas_a_mostrar;

    $primeraConsulta = mysqli_query($conexionHospital, $sql);

    //extraer el número de filas
    if ($primeraConsulta->num_rows == 0) {
        $mensajes = "No hay ningun paciente registrado.";
    } else {

        //Segundo acceso a la base de datos
        $sql = "SELECT COUNT(*) AS numregistros FROM paciente WHERE apellidos LIKE '%$buscar_apellido%'";

        //Guardamos la consulta de la base de datos como objeto
        $cantidad = mysqli_query($conexionHospital, $sql);

        //Realizamos la conversion de un objeto a un Array
        $cantidadTotal = mysqli_fetch_all($cantidad, MYSQLI_ASSOC);

        //Pasamos la cantidad total para una variable
        $numregistros = $cantidadTotal[0]['numregistros'];

        //Calculamos el numero de paginas a mostrar
        $paginas = ceil($numregistros / $filas_a_mostrar);

        //Utilizamos una funcion para pasar la consulta a un array y así extraer los datos de cada persona
        $pacientes = mysqli_fetch_all($primeraConsulta, MYSQLI_ASSOC);

        //Para cada paciente, iremos imprimir los valores y añadirlos en la tabla. Utilizamos un array tipo clave/valor
        foreach ($pacientes as $clave => $valor) {

            //Incluiremos cada botón de detalle dentro de un formulario con un input tipo hidden y los atributos name
            $fila = "<form method='post' action='?mantenimiento'>" .
                "<tr><td>" . $valor['nif'] . "</td><td>" . $valor['nombre'] . "</td><td>" . $valor['apellidos'] . "</td><td><button name='mantenimiento'>Detalle paciente</button>" .
                "<input type='hidden' name='idpaciente' value=" . $valor['idpaciente'] . "> </td></tr></form>";
            $contenidoTabla = $contenidoTabla . $fila;
        }

        $enlaces = '';
        //con un bucle for, construimos tantos botones de enlace como número de páginas hemos obtenido del cálculo anterior
        for ($p = 1; $p <= $paginas; $p++) {
            if ($p == $pagina) {
                $enlaces .= "<a href='?consulta&pagina=$p'><input type='button' value='$p'></a>";
            } else {
                $enlaces .= "<a href='?consulta&pagina=$p'><input type='button' value='$p'></a>";
            }
        }
        ;

        //Cerramos conexion con base de datos
        $conexionHospital->close();
    }


} catch (Exception $e) {
    $errores = $e->getMessage();
}