<?php
//Declaramos variables
$seccion = 'index';

//inicializar variables
$mensajes = $nif = $password = $menu = null;

//Definicion de constante SECCIONES
define("SECCIONES", [
	"consulta",
	"alta",
	"mantenimiento"
]);

//Definicion de array 
$arrayClaves = array();

//Validamos si existe algun parametro con el metodo GET
if (sizeof($_GET) > 0) {
	//Recuperamos el nombre del parámetro y pasamos a un array asociativo
	$arrayClaves = array_keys($_GET);
	if (in_array($arrayClaves[0], SECCIONES)) {
		//S la clave se encuentra dentro de las secciones válidas la asignaremos a la variable
		$seccion = $arrayClaves[0];
	}
}

//Conexion con la base de datos
include("servicios/conexion.php");

//Comprobamos si el cookie existe.
if (isset($_COOKIE['usuario'])) {
	//Recuperamos el NIF del usuario
	$nif = $_COOKIE['usuario'];
	//Confeccionamos la sentencia para validar si el NIF existe en la base de datos
	$sql = "SELECT * FROM usuarios WHERE nif='$nif'";
	//Realizamos la consulta en la base de datos y pasamos los datos a la variable
	$consulta = mysqli_query($conexionHospital, $sql);
	//Validamos si el NIF no ha sido modificado
	if ($consulta->num_rows > 0) {
		//Si hay el nif existe, cargamos el menú de opciones
		$menu = "secciones/menu.html";
	} else {
		//Caso el nif no exista en la base de datos, mostramos otra vez el login 
		$menu = "secciones/login.html";
		//Borramos la cookie de la base de datos
		setcookie('usuario', '', time() - 1, '/');
	}
} else {
	$menu = "secciones/login.html";
}

//comprobar si se ha pulsado el botón de login
if (isset($_POST['login'])) {
	try {
		//Recuperar los datos del usuario
		$nif = addslashes($_POST['nif']);
		$password = addslashes($_POST['password']);

		//Validamos si los han sido rellenados correctamente
		if (empty($nif) || empty($password)) {
			$mensajes = "Introduzca usuario y contraseña";
		} else {
			//Confeccionamos la sentencia para validar si el NIF existe en la base de datos
			$sql = "SELECT * FROM usuarios WHERE nif='$nif'";
			//Realizamos la consulta en la base de datos y pasamos los datos a la variable
			$consulta = mysqli_query($conexionHospital, $sql);
			//Validamos si el NIF no ha sido modificado
			if ($consulta->num_rows > 0) {
				//Funcion nos permite transformar un objeto en un array asociativo
				$user = mysqli_fetch_assoc($consulta);
				//Validamos que la contraseña informada y la que esta en la base de datos sea iguales
				if ($user['password'] == $password) {
					//Si la contraseña esta correcta, la guardamos dentro de un cookie
					setcookie('usuario', $nif, time() + (3600 * 24), '/');
					$menu = "secciones/menu.html";
				} else {
					//Caso la contraseña no exista en la base de datos..
					$mensajes = "Nombre de usuario o contraseña incorrectos";
				}
			} else {
				//Caso el nif no exista en la base de datos..
				$mensajes = "Nombre de usuario o contraseña incorrectos";
			}
		}
	} catch (Exception $e) {
		$mensajes = $e->getMessage();
	}
}

//comprobar si se ha pulsado el botón de logoff
if (isset($_POST['logoff'])) {
	//Borramos la cookie con la información del usuario conectado
	setcookie('usuario', '', time() - 1, '/');
	//Volvemos a cargar la plataforma desde inicio
	header("Location:hospital.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Hospital</title>
	<link rel="stylesheet" type="text/css" href="css/hospital.css">
</head>

<body>
	<div class="container">
		<header>
			<h1 id="title">HOSPITAL</h1>
		</header>
		<nav>
			<?php include($menu); ?>
		</nav>
		<section id='contenido'>
			<div>
				<?php include("secciones/$seccion.html"); ?>
			</div>
		</section>
	</div>
</body>

</html>