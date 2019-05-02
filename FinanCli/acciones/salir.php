<?php
include ('../utiles/funciones.php');
require("../../parametrosbasedatosfc.php");
$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
mysqli_set_charset($mysqli,"utf8");

if($_GET['result_ok'] == 1)
{ 
	if(session_status() !== PHP_SESSION_ACTIVE) sec_session_start();
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
		$motivo = 2;
		$descripM = translate('Msg_Log_Out',$GLOBALS['lang']).$date_registro2;
		$stmt10->bind_param('ssis', $username, $date_registro, $motivo, $descripM);
		if(!$stmt10->execute())
		{
			printf("Error: %s\n", $mysqli->error);
		}
	}
	
	header ('Location:../login.php?result_ok=1');
	return;			
}

if($_GET['expired_session'] == 200)
{ 
	if(session_status() !== PHP_SESSION_ACTIVE) sec_session_start();
 	$username = $_SESSION['username'];
	
	$date_registro = date("YmdHis");
	$date_registro2 = date("Y-m-d H:i:s");
	
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $GLOBALS['time_expire_sesion'])) 
	{							
		if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
		{
			printf("Error: %s\n", $mysqli->error);
		}
		else
		{
			$motivo = 15;
			$parComp = translate('Msg_Close_Sesion_Time_Expired_Db',$GLOBALS['lang']).$date_registro2;
			$stmt10->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo, $parComp);
			if(!$stmt10->execute())
			{
				printf("Error: %s\n", $mysqli->error);
			}
		}
			
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
	
		header('Location:../login.php?result_ok=3');
		return;
	}	
	else
	{
		header ('Location:/FinanCli/sesionusuario.php');
		return;
	}
}

if (verificar_usuario($mysqli))
{
	if(session_status() !== PHP_SESSION_ACTIVE) sec_session_start();
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
		$motivo = 2;
		$descripM = translate('Msg_Log_Out',$GLOBALS['lang']).$date_registro2;
		$stmt10->bind_param('ssis', $username, $date_registro, $motivo, $descripM);
		if(!$stmt10->execute())
		{
			printf("Error: %s\n", $mysqli->error);
		}
	}
	
	header ('Location:../login.php?result_ok=2');
	return;		
} 
else 
{
	header ('Location:../login.php');
	return;
}
?>
