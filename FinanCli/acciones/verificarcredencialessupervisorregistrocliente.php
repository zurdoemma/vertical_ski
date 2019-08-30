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
		
		$motivo=htmlspecialchars($_POST["motivo"], ENT_QUOTES, 'UTF-8');
		$usuarioSupervisor=htmlspecialchars($_POST["usuarioSupervisor"], ENT_QUOTES, 'UTF-8');
		$claveSupervisor=htmlspecialchars($_POST["claveSupervisor"], ENT_QUOTES, 'UTF-8');
		
		$tipoDocumento=htmlspecialchars($_POST["tipoDocumento"], ENT_QUOTES, 'UTF-8');
		$documento=htmlspecialchars($_POST["documento"], ENT_QUOTES, 'UTF-8');
		
		$tipoDocumentoTitular=htmlspecialchars($_POST["tipoDocumentoTitular"], ENT_QUOTES, 'UTF-8');
		$documentoTitular=htmlspecialchars($_POST["documentoTitular"], ENT_QUOTES, 'UTF-8');

		$tipoDocumentoAdicional=htmlspecialchars($_POST["tipoDocumentoAdicional"], ENT_QUOTES, 'UTF-8');
		$documentoAdicional=htmlspecialchars($_POST["documentoAdicional"], ENT_QUOTES, 'UTF-8');		
		
		$tokenECC2=htmlspecialchars($_POST["tokenECC2"], ENT_QUOTES, 'UTF-8');
				
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
					
					if ($stmt702 = $mysqli->prepare("SELECT c.id FROM finan_cli.cadena c, finan_cli.usuario u, finan_cli.sucursal s WHERE u.id_sucursal = s.id AND s.id_cadena = c.id AND u.id = ?")) 
					{
						$stmt702->bind_param('s', $_SESSION['username']);
						$stmt702->execute();    
						$stmt702->store_result();
				 
						$totR702 = $stmt702->num_rows;
						if($totR702 > 0)
						{
							$stmt702->bind_result($id_cadena_user);
							$stmt702->fetch();
							
							if ($stmt703 = $mysqli->prepare("SELECT c.id FROM finan_cli.cadena c, finan_cli.usuario u, finan_cli.sucursal s WHERE u.id_sucursal = s.id AND s.id_cadena = c.id AND u.id = ?")) 
							{
								$stmt703->bind_param('s', $usuarioSupervisor);
								$stmt703->execute();    
								$stmt703->store_result();
						 
								$totR703 = $stmt703->num_rows;
								if($totR703 > 0)
								{
									$stmt703->bind_result($id_cadena_user_supervisor);
									$stmt703->fetch();
									
									if($id_cadena_user != $id_cadena_user_supervisor)
									{
										echo translate('Msg_Uer_Supervisor_Is_Incorrect',$GLOBALS['lang']);
										return;											
									}

									$stmt703->free_result();
									$stmt703->close();				
								}
								else 
								{
									echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
									return;				
								}								
							}
							else 
							{
								echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
								return;				
							}							

							$stmt702->free_result();
							$stmt702->close();				
						}
						else 
						{
							echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
							return;				
						}	
					}
					else 
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;				
					}
					
					if ($db_password == $password) 
					{
						$mysqli->autocommit(FALSE);
						$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
						
						if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $insertVCSRC = "INSERT INTO finan_cli.estado_cliente(fecha,tipo_documento,documento,id_motivo,usuario,usuario_supervisor,tipo_documento_adicional,documento_adicional,token) VALUES (?,?,?,?,?,?,?,?,?)";
						else $insertVCSRC = "INSERT INTO finan_cli.estado_cliente(fecha,tipo_documento,documento,id_motivo,usuario,usuario_supervisor,token) VALUES (?,?,?,?,?,?,?)";
						if(!$stmt10 = $mysqli->prepare($insertVCSRC))
						{
							$mysqli->autocommit(TRUE);
							$stmt->free_result();
							$stmt->close();
							echo translate('Msg_Supervisor_Not_OK',$GLOBALS['lang']);
							return;
						}
						else
						{
							$date_registro_a_s_db = date("YmdHis");
							$tokenRC = md5(uniqid(rand(), true));
							$tokenRC = hash('sha512', $tokenRC);
							if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) 
							{
								if($motivo == 37 || $motivo == 38) $stmt10->bind_param('sisississ', $date_registro_a_s_db, $tipoDocumentoTitular, $documentoTitular, $motivo, $_SESSION['username'], $usuarioSupervisor, $tipoDocumentoAdicional, $documentoAdicional, $tokenRC);
								else $stmt10->bind_param('sisississ', $date_registro_a_s_db, $tipoDocumento, $documento, $motivo, $_SESSION['username'], $usuarioSupervisor, $tipoDocumento, $documento, $tokenRC);
							}
							else $stmt10->bind_param('sisisss', $date_registro_a_s_db, $tipoDocumento, $documento, $motivo, $_SESSION['username'], $usuarioSupervisor, $tokenRC);
							if(!$stmt10->execute())
							{
								echo translate('Msg_Supervisor_Not_OK',$GLOBALS['lang']);
								$mysqli->autocommit(TRUE);
								$stmt->free_result();
								$stmt->close();
								return;						
							}
							
							if($motivo == 37 || $motivo == 38)
							{
								if(!$stmt11 = $mysqli->prepare("UPDATE finan_cli.consulta_estado_financiero SET validado = 1 WHERE tipo_documento = ? AND documento = ? AND token = ? AND validado = 0"))
								{
									$mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									echo translate('Msg_Supervisor_Not_OK',$GLOBALS['lang']);
									return;
								}
								else
								{
									$stmt11->bind_param('iss', $tipoDocumento, $documento, $tokenECC2);
									if(!$stmt11->execute())
									{
										$mysqli->rollback();
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										echo translate('Msg_Supervisor_Not_OK',$GLOBALS['lang']);
										return;						
									}
								}
							}								
												
							$mysqli->commit();
							$mysqli->autocommit(TRUE);
							$stmt->free_result();
							$stmt->close();
								
							echo translate('Msg_Supervisor_OK',$GLOBALS['lang']);
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