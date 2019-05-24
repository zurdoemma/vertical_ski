<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');return;}
		if (!verificar_permisos_usuario()){header('Location:../sinautorizacion.php');return;}

		// ¡Oh, no! Existe un error 'connect_errno', fallando así el intento de conexión
		if ($mysqli->connect_errno) 
		{

    			//echo "Lo sentimos, este sitio web está experimentando problemas.";

    			// Algo que no se debería de hacer en un sitio público, aunque este ejemplo lo mostrará
    			// de todas formas, es imprimir información relacionada con errores de MySQL -- se podría registrar
    			//echo "Error: Fallo al conectarse a MySQL debido a: \n";
    			//echo "Errno: " . $mysqli->connect_errno . "\n";
    			//echo "Error: " . $mysqli->connect_error . "\n";
				header('Location:../login.php?error_l=9');
				return;
		}
		
		$tipoDocumento=htmlspecialchars($_POST["tipoDocumento"], ENT_QUOTES, 'UTF-8');
		$documento=htmlspecialchars($_POST["documento"], ENT_QUOTES, 'UTF-8');
		
		if($stmt = $mysqli->prepare("SELECT c.id, c.estado FROM finan_cli.cliente c WHERE c.tipo_documento = ? AND c.documento = ? AND c.id_titular IS NULL"))
		{
			$stmt->bind_param('is', $tipoDocumento, $documento);
			$stmt->execute();    
			$stmt->store_result();
			
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				$stmt->free_result();
				$stmt->close();
				echo translate('Msg_A_Client_Does_Not_Exist_As_A_Proprietor',$GLOBALS['lang']);
				return;
			}

			$stmt->bind_result($id_cliente_t, $estado_t);
			$stmt->fetch();
			
			if($estado_t != translate('State_User',$GLOBALS['lang']))
			{
				echo translate('Msg_Client_Holder_Is_Disabled',$GLOBALS['lang']);
				return;
			}
			
			echo translate('Msg_A_Client_Exist_As_A_Proprietor',$GLOBALS['lang']);
			return;
				
			$stmt->free_result();
			$stmt->close();				
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}
		
		return;
?>