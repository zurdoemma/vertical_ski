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
		
		$codigo=htmlspecialchars($_POST["codigo"], ENT_QUOTES, 'UTF-8');
		$token=htmlspecialchars($_POST["token"], ENT_QUOTES, 'UTF-8');
		$idCliente=htmlspecialchars($_POST["idCliente"], ENT_QUOTES, 'UTF-8');
		
		$prefijoTelefono=htmlspecialchars($_POST["prefijoTelefono"], ENT_QUOTES, 'UTF-8');
		$nroTelefono=htmlspecialchars($_POST["nroTelefono"], ENT_QUOTES, 'UTF-8');		
		
		if($stmt4 = $mysqli->prepare("SELECT c.id, c.tipo_documento, c.documento FROM finan_cli.cliente c WHERE c.id = ?"))
		{
			$stmt4->bind_param('i', $idCliente);
			$stmt4->execute();    
			$stmt4->store_result();
			
			$totR4 = $stmt4->num_rows;

			if($totR4 == 0)
			{
				echo translate('Msg_Client_Not_Exist',$GLOBALS['lang']);
				return;
			}			
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		
		$stmt4->bind_result($id_client, $type_document_client, $document_client);
		$stmt4->fetch();
		
		$tipoDocumento = $type_document_client;
		$documento = $document_client;
		
		$stmt4->free_result();
		$stmt4->close();
				
		if ($stmt = $mysqli->prepare("SELECT tvc.id FROM finan_cli.token_validacion_celular tvc WHERE tvc.tipo_documento = ? AND tvc.documento = ? AND tvc.fecha like ? AND tvc.token = ? AND tvc.codigo = ? AND tvc.nro_telefono = ?"))
		{
			$telefonoFin = $prefijoTelefono.$nroTelefono;
			$date_registro_a_vcc = date("Ymd")."%";
			$stmt->bind_param('issssi', $tipoDocumento, $documento, $date_registro_a_vcc, $token, $codigo, $telefonoFin); 
			$stmt->execute();   
			$stmt->store_result();
	 
			if ($stmt->num_rows == 1) 
			{
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				if(!$stmt10 = $mysqli->prepare("UPDATE finan_cli.token_validacion_celular SET validado = ? WHERE tipo_documento = ? AND documento = ? AND fecha like ? AND token = ? AND codigo = ? AND nro_telefono = ?"))
				{
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					echo translate('Msg_SMS_Code_Validated_Not_OK',$GLOBALS['lang']);
					return;
				}
				else
				{
					$validado_vcc = 1;
					$stmt10->bind_param('iissssi', $validado_vcc, $tipoDocumento, $documento, $date_registro_a_vcc, $token, $codigo, $telefonoFin);
					if(!$stmt10->execute())
					{
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						echo translate('Msg_SMS_Code_Validated_Not_OK',$GLOBALS['lang']);
						return;						
					}
										
					$mysqli->commit();
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
						
					echo translate('Msg_SMS_Code_Validated_OK',$GLOBALS['lang']);
					return;							
				}
			}
			else
			{
				echo translate('Msg_Incorrect_SMS_Code',$GLOBALS['lang']);
				return;
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}
		
		return;
?>