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
		$idCliente=htmlspecialchars($_POST["idCliente"], ENT_QUOTES, 'UTF-8');
		$idTelefono=htmlspecialchars($_POST["idTelefono"], ENT_QUOTES, 'UTF-8');
		$prefijoTelefono=htmlspecialchars($_POST["prefijoTelefono"], ENT_QUOTES, 'UTF-8');
		$nroTelefono=htmlspecialchars($_POST["nroTelefono"], ENT_QUOTES, 'UTF-8');		
		
		if($stmt44 = $mysqli->prepare("SELECT c.id, c.tipo_documento, c.documento FROM ".$db_name.".cliente c, ".$db_name.".telefono t, ".$db_name.".cliente_x_telefono ct WHERE c.id = ? AND c.tipo_documento = ct.tipo_documento AND c.documento = ct.documento AND ct.id_telefono = t.id AND t.id = ?"))
		{
			$stmt44->bind_param('ii', $idCliente, $idTelefono);
			$stmt44->execute();    
			$stmt44->store_result();
			
			$totR44 = $stmt44->num_rows;

			if($totR44 == 0)
			{
				echo translate('Msg_Client_Or_Phone_Not_Exist',$GLOBALS['lang']);
				return;
			}			
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		
		$stmt44->bind_result($id_client, $type_document_client, $document_client);
		$stmt44->fetch();
		
		$tipoDocumento = $type_document_client;
		$documento = $document_client;
		
		$stmt44->free_result();
		$stmt44->close();
				
		if ($stmt = $mysqli->prepare("SELECT id, clave, salt, id_perfil, estado  FROM ".$db_name.".usuario WHERE id = ? AND id_perfil IN (1,3) LIMIT 1")) 
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
					
					if ($stmt702 = $mysqli->prepare("SELECT c.id FROM ".$db_name.".cadena c, ".$db_name.".usuario u, ".$db_name.".sucursal s WHERE u.id_sucursal = s.id AND s.id_cadena = c.id AND u.id = ?")) 
					{
						$stmt702->bind_param('s', $_SESSION['username']);
						$stmt702->execute();    
						$stmt702->store_result();
				 
						$totR702 = $stmt702->num_rows;
						if($totR702 > 0)
						{
							$stmt702->bind_result($id_cadena_user);
							$stmt702->fetch();
							
							if ($stmt703 = $mysqli->prepare("SELECT c.id FROM ".$db_name.".cadena c, ".$db_name.".usuario u, ".$db_name.".sucursal s WHERE u.id_sucursal = s.id AND s.id_cadena = c.id AND u.id = ?")) 
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
					
					$ingresoToken = 0;
					if ($stmt351 = $mysqli->prepare("SELECT tas.fecha, tas.token, tas.duracion FROM ".$db_name.".token_autorizacion_supervisor tas WHERE tas.utilizado = 0 AND tas.fecha_utilizacion IS NULL AND tas.autorizado = ? AND tas.autorizante = ? ORDER BY tas.fecha DESC")) 
					{
						$stmt351->bind_param('ss', $_SESSION['username'], $usuarioSupervisor);
						$stmt351->execute();    
						$stmt351->store_result();
				 
						$totR351 = $stmt351->num_rows;
						if($totR351 > 0)
						{
							$stmt351->bind_result($fecha_tas, $token_tas, $duracion_token_tas);
							$stmt351->fetch();

							$fechaObtDB = substr($fecha_tas, 0, 4).'-'.substr($fecha_tas, 4, 2).'-'.substr($fecha_tas, 6, 2).' '.substr($fecha_tas, 8, 2).':'.substr($fecha_tas, 10, 2).':'.substr($fecha_tas, 12, 2);
							$fechaInfDB = new DateTime($fechaObtDB);
							$fechaAct = new DateTime();
							$difMinutos = $fechaAct->diff($fechaInfDB);
							
							if($difMinutos->i > $duracion_token_tas)
							{
								$ingresoToken = 0;
							}
							else
							{
								if($token_tas == $password)
								{
									$mysqli->autocommit(FALSE);
									$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
									
									if(!$stmt10 = $mysqli->prepare("UPDATE ".$db_name.".token_autorizacion_supervisor SET fecha_utilizacion = ?, utilizado = ?, id_motivo = ? WHERE utilizado = 0 AND fecha_utilizacion IS NULL AND autorizado = ? AND autorizante = ?"))
									{
										echo $mysqli->error;
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										return;
									}
									else
									{
										$date_registro = date("YmdHis");
										$utilizadoT = 1;
										
										$stmt10->bind_param('siiss', $date_registro, $utilizadoT, $motivo, $_SESSION['username'], $usuarioSupervisor);
										if(!$stmt10->execute())
										{
											echo $mysqli->error;
											$mysqli->autocommit(TRUE);
											$stmt->free_result();
											$stmt->close();
											return;						
										}
									}
									
									$mysqli->commit();
									$mysqli->autocommit(TRUE);
									$ingresoToken = 1;
								}
								else $ingresoToken = 0;
							}
							
							$stmt351->free_result();
							$stmt351->close();				
						}
					}
					
					if ($db_password == $password || $ingresoToken == 1) 
					{
						$mysqli->autocommit(FALSE);
						$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
						
						if(!$stmt10 = $mysqli->prepare("INSERT INTO ".$db_name.".estado_cliente(fecha,tipo_documento,documento,id_motivo,usuario,usuario_supervisor,nro_telefono,token) VALUES (?,?,?,?,?,?,?,?)"))
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
							$telefonoFin = $prefijoTelefono.$nroTelefono;
							$tokenRT2 = md5(uniqid(rand(), true));
							$tokenRT2 = hash('sha512', $tokenRT2);
							$stmt10->bind_param('sisissis', $date_registro_a_s_db, $tipoDocumento, $documento, $motivo, $_SESSION['username'], $usuarioSupervisor, $telefonoFin, $tokenRT2);
							if(!$stmt10->execute())
							{
								$mysqli->autocommit(TRUE);
								$stmt->free_result();
								$stmt->close();
								echo translate('Msg_Supervisor_Not_OK',$GLOBALS['lang']);
								return;						
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