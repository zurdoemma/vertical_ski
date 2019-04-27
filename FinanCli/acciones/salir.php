<?php
include ('../utiles/funciones.php');
require("../../parametrosbasedatosfc.php");
$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);

if($_GET['result_ok'] == 1)
{ 
	sec_session_start($_SESSION['username']);
 	$username = $_SESSION['username'];
	
	// Desconfigura todos los valores de sesi�n.
	$_SESSION = array();
 
	// Obtiene los par�metros de sesi�n.
	$params = session_get_cookie_params();
 
	// Borra el cookie actual.
	setcookie(session_name('sec_session_id_'.$_SESSION['username']),
        	'', time() - 42000, 
        	$params["path"], 
        	$params["domain"], 
        	$params["secure"], 
        	$params["httponly"]);
 
	// Destruye sesi�n. 
	session_destroy();

	$date_registro = date("YmdHis");
	$date_registro2 = date("Y-m-d H:i:s");
	if(!$mysqli->query("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES ('$username','$date_registro',2,'".translate('Msg_Log_Out',$GLOBALS['lang']).$date_registro2."')"))
	{
		printf("Error: %s\n", $mysqli->error);
	}	
	
	header ('Location:../login.php?result_ok=1');
	return;
}

if (verificar_usuario($mysqli))
{
	sec_session_start($_SESSION['username']);
	$username = $_SESSION['username'];
	
	// Desconfigura todos los valores de sesi�n.
	$_SESSION = array();
 
	// Obtiene los par�metros de sesi�n.
	$params = session_get_cookie_params();
 
	// Borra el cookie actual.
	setcookie(session_name('sec_session_id_'.$_SESSION['username']),
        	'', time() - 42000, 
        	$params["path"], 
        	$params["domain"], 
        	$params["secure"], 
        	$params["httponly"]);
 
	// Destruye sesi�n. 
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
