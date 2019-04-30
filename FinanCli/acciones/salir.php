<?php
include ('../utiles/funciones.php');
require("../../parametrosbasedatosfc.php");
$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
mysqli_set_charset($mysqli,"utf8");

if($_GET['result_ok'] == 1)
{ 
	sec_session_restart();
 	$username = $_SESSION['username'];
	
	// Desconfigura todos los valores de sesión.
	$_SESSION = array();
 
 	if(phpversion() < '7.1.0')
	{
		// Obtiene los parámetros de sesión.
		$params = session_get_cookie_params();
	 
		// Borra el cookie actual.
		setcookie(session_name('sec_session_id'),
				'', time() - 42000, 
				$params["path"], 
				$params["domain"], 
				$params["secure"], 
				$params["httponly"]);
	}
 
	// Destruye sesión. 
	session_destroy();

	$date_registro = date("YmdHis");
	$date_registro2 = date("Y-m-d H:i:s");
	if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
	{
		printf("Error: %s\n", $mysqli->error);
	}
	else
	{
		$stmt10->bind_param('ssis', $username, $date_registro, 2, translate('Msg_Log_Out',$GLOBALS['lang']).$date_registro2);
		if(!$stmt10->execute())
		{
			printf("Error: %s\n", $mysqli->error);
		}
	}
	
	header ('Location:../login.php?result_ok=1');
	return;
}

if (verificar_usuario($mysqli))
{
	sec_session_restart();
	$username = $_SESSION['username'];
	
	// Desconfigura todos los valores de sesión.
	$_SESSION = array();
 
  	if(phpversion() < '7.1.0')
	{
		// Obtiene los parámetros de sesión.
		$params = session_get_cookie_params();
	 
		// Borra el cookie actual.
		setcookie(session_name('sec_session_id'),
				'', time() - 42000, 
				$params["path"], 
				$params["domain"], 
				$params["secure"], 
				$params["httponly"]);
	}
 
	// Destruye sesión. 
	session_destroy();
	
	$date_registro = date("YmdHis");
	$date_registro2 = date("Y-m-d H:i:s");
	if(!$mysqli->query("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES ('$username','$date_registro',2,'".translate('Msg_Log_Out',$GLOBALS['lang']).$date_registro2."')"))
	{
		printf("Error: %s\n", $mysqli->error);
	}
	
	header ('Location:../login.php?result_ok=2');
} 
else 
{
	header ('Location:../login.php');
}
?>
