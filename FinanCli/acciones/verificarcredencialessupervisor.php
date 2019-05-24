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
		
		$usuarioSupervisor=htmlspecialchars($_POST["usuarioSupervisor"], ENT_QUOTES, 'UTF-8');
		$claveSupervisor=htmlspecialchars($_POST["claveSupervisor"], ENT_QUOTES, 'UTF-8');
		
		$tipoDocumento=htmlspecialchars($_POST["tipoDocumento"], ENT_QUOTES, 'UTF-8');
		$documento=htmlspecialchars($_POST["documento"], ENT_QUOTES, 'UTF-8');
		$tipoDocumentoTitular=htmlspecialchars($_POST["tipoDocumentoTitular"], ENT_QUOTES, 'UTF-8');
		$documentoTitular=htmlspecialchars($_POST["documentoTitular"], ENT_QUOTES, 'UTF-8');
				
		if ($stmt = $mysqli->prepare("SELECT id, clave, salt, id_perfil, estado  FROM finan_cli.usuario WHERE id = ? AND id_perfil IN (1,3) LIMIT 1")) 
		{
			$stmt->bind_param('s', $usuarioSupervisor);  
			$stmt->execute();   
			$stmt->store_result();
	 

			$stmt->bind_result($user_id, $db_password, $salt, $permiso, $estado_user);
			$stmt->fetch();
	 
			$password = hash('sha512', $claveSupervisor . $salt);
			if ($stmt->num_rows == 1) 
			{
				if (checkbrute($user_id, $mysqli) == true) 
				{
					echo translate('Msg_Block_User',$GLOBALS['lang']);
					return;
				} 
				else 
				{
					if(empty($estado_user) || $estado_user != translate('State_User',$GLOBALS['lang']))
					{
						echo translate('Msg_Disable_User',$GLOBALS['lang']);
						return;
					}
					if ($db_password == $password) 
					{
						$date_registro_a_s = date("YmdHis");						
						$date_registro_a_s_b = date("Ymd")."%";
						
						if($stmt2 = $mysqli->prepare("SELECT tac.token FROM finan_cli.token_adicional_cuenta tac WHERE tac.tipo_documento = ? AND tac.documento = ? AND tac.tipo_documento_titular = ?, tac.documento_titular = ? AND tac.fecha like ?"))
						{
							$stmt2->bind_param('isiss', $tipoDocumento, $documento, $tipoDocumentoTitular, $documentoTitular, $date_registro_a_s_b);
							$stmt2->execute();    
							$stmt2->store_result();
						
							$totR2 = $stmt2->num_rows;
							
							if($totR2 > 0)
							{
								$stmt2->bind_result($token_client_additional);
								$stmt2->fetch();
								
								echo translate('Msg_Supervisor_OK',$GLOBALS['lang']).'=:=:=:'.$token_client_additional;
								
								$stmt->free_result();
								$stmt->close();
								
								$stmt2->free_result();
								$stmt2->close();								
								return;
							}	
						}
						else
						{
							echo translate('Msg_Supervisor_Not_OK',$GLOBALS['lang']);
							return;
						}						
						$stmt2->free_result();
						$stmt2->close();
						
						$mysqli->autocommit(FALSE);
						$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
						
						if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.token_adicional_cuenta(fecha,tipo_documento,documento,tipo_documento_titular,documento_titular,token,usuario,usuario_supervisor) VALUES (?,?,?,?,?,?,?,?)"))
						{
							echo $mysqli->error;
							$mysqli->autocommit(TRUE);
							$stmt->free_result();
							$stmt->close();
							return;
						}
						else
						{
							$token = md5(uniqid(rand(), true));
							$token = hash('sha512', $token);
							$stmt10->bind_param('sisissss', $date_registro_a_s, $tipoDocumento, $documento, $tipoDocumentoTitular, $documentoTitular, $token, $_SESSION['username'], $usuarioSupervisor);
							if(!$stmt10->execute())
							{
								echo $mysqli->error;
								$mysqli->autocommit(TRUE);
								$stmt->free_result();
								$stmt->close();
								return;						
							}
												
							$mysqli->commit();
							$mysqli->autocommit(TRUE);
							
							echo translate('Msg_Supervisor_OK',$GLOBALS['lang']).'=:=:=:'.$token;
							return;
						}
					}
					else
					{
						echo translate('Msg_Supervisor_Not_OK',$GLOBALS['lang']);
						return;
					}
				}
			}
			else
			{
				echo translate('Msg_Supervisor_Not_OK',$GLOBALS['lang']);
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