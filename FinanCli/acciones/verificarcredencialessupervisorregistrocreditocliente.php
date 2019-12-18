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
				
		$token=htmlspecialchars($_POST["token"], ENT_QUOTES, 'UTF-8');
		$tokenVS=htmlspecialchars($_POST["token2"], ENT_QUOTES, 'UTF-8');
				
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
						if($stmt47 = $mysqli->prepare("SELECT c.id, c.estado, c.id_titular, c.monto_maximo_credito, c.nombres, c.apellidos, t.numero, c.id_perfil_credito FROM ".$db_name.".cliente c, ".$db_name.".telefono t, ".$db_name.".cliente_x_telefono ct WHERE ct.tipo_documento = c.tipo_documento AND ct.documento = c.documento AND t.id = ct.id_telefono AND c.tipo_documento = ? AND c.documento = ?"))
						{
							$stmt47->bind_param('is', $tipoDocumento, $documento);
							$stmt47->execute();    
							$stmt47->store_result();
							
							$totR47 = $stmt47->num_rows;

							if($totR47 == 0)
							{
								echo translate('Msg_Client_Not_Exist',$GLOBALS['lang']);
								return;
							}
							else
							{
								$stmt47->bind_result($id_cliente_db, $estado_cliente_db, $id_titular_cliente_db, $monto_maximo_credito_cliente_db, $nombres_cliente_db, $apellidos_cliente_db, $telefono_cliente_db, $id_perfil_credito_cliente_db);
								$stmt47->fetch();
								
								if($estado_cliente_db != translate('State_User',$GLOBALS['lang']))
								{
									echo translate('Msg_Disable_Client',$GLOBALS['lang']);
									return;
								}
								else
								{
									if(!empty($id_titular_cliente_db))
									{
										if($stmt48 = $mysqli->prepare("SELECT c.id, c.estado, c.tipo_documento, c.documento FROM ".$db_name.".cliente c WHERE c.id = ?"))
										{
											$stmt48->bind_param('i', $id_titular_cliente_db);
											$stmt48->execute();    
											$stmt48->store_result();
											
											$totR48 = $stmt48->num_rows;

											if($totR48 > 0)
											{
												$stmt48->bind_result($id_cliente_titular_db, $estado_cliente_titular_db, $tipo_documento_cliente_titular_db, $documento_cliente_titular_db);
												$stmt48->fetch();
												
												if($estado_cliente_titular_db != translate('State_User',$GLOBALS['lang']))
												{
													echo translate('Msg_Disable_Client_Headline',$GLOBALS['lang']);
													return;
												}
												
												$stmt48->free_result();
												$stmt48->close();
											}
											else
											{
												echo translate('Msg_Client_Headline_Not_Exist',$GLOBALS['lang']);
												return;
											}
										}
										else
										{
											echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
											return;
										}
									}
								}
							}				
						}
						else
						{
							echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
							return;
						}

						if(empty($id_titular_cliente_db)) $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang']);
						else $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang']);
						
						$selectMCC = "SELECT SUM(cu.monto_cuota_original) FROM ".$db_name.".credito c, ".$db_name.".credito_cliente cc, ".$db_name.".cuota_credito cu WHERE c.id = cc.id_credito AND c.id = cu.id_credito AND cc.tipo_documento = ? AND cc.documento = ? AND c.estado IN (?,?)";
						if($stmt49 = $mysqli->prepare($selectMCC))
						{
							$est_pend = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
							$est_mora = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
							if(empty($id_titular_cliente_db)) $stmt49->bind_param('isss', $tipoDocumento, $documento, $est_pend, $est_mora);
							else $stmt49->bind_param('isss', $tipo_documento_cliente_titular_db, $documento_cliente_titular_db, $est_pend, $est_mora);
							$stmt49->execute();    
							$stmt49->store_result();
							
							$totR49 = $stmt49->num_rows;

							if($totR49 > 0)
							{
								$stmt49->bind_result($monto_credito_utilizado_db);
								$stmt49->fetch();
								
								$monto_credito_disponible = $monto_maximo_credito_cliente_db - $monto_credito_utilizado_db;
								if($monto_credito_disponible < 0) $monto_credito_disponible = 0;
								
								$stmt49->free_result();
								$stmt49->close();
							}
							else
							{
								$monto_credito_disponible = $monto_maximo_credito_cliente_db;
							}
						}
						else
						{
							echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
							return;
						}				
								
						$stmt47->free_result();
						$stmt47->close();						
						
						
						$mysqli->autocommit(FALSE);
						$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
						
						if(empty($id_cliente_titular_db)) $insertVCSRC = "INSERT INTO ".$db_name.".estado_cliente(fecha,tipo_documento,documento,id_motivo,usuario,usuario_supervisor,token) VALUES (?,?,?,?,?,?,?)";
						else $insertVCSRC = "INSERT INTO ".$db_name.".estado_cliente(fecha,tipo_documento,documento,id_motivo,usuario,usuario_supervisor,token,tipo_documento_adicional, documento_adicional) VALUES (?,?,?,?,?,?,?,?,?)";
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
							$tokenECRC = md5(uniqid(rand(), true));
							$tokenECRC = hash('sha512', $tokenECRC);
							if(empty($id_cliente_titular_db)) $stmt10->bind_param('sisisss', $date_registro_a_s_db, $tipoDocumento, $documento, $motivo, $_SESSION['username'], $usuarioSupervisor, $tokenECRC);
							else $stmt10->bind_param('sisisssis', $date_registro_a_s_db, $tipo_documento_cliente_titular_db, $documento_cliente_titular_db, $motivo, $_SESSION['username'], $usuarioSupervisor, $tokenECRC, $tipoDocumento, $documento);
							if(!$stmt10->execute())
							{
								$mysqli->autocommit(TRUE);
								$stmt->free_result();
								$stmt->close();
								echo translate('Msg_Supervisor_Not_OK',$GLOBALS['lang']);
								return;						
							}
							
							if($motivo == 59 || $motivo == 60)
							{
								if(empty($id_titular_cliente_db)) $upEstFinCli = "UPDATE ".$db_name.".consulta_estado_financiero SET validado = 1 WHERE tipo_documento = ? AND documento = ? AND token = ? AND validado = 0";
								else $upEstFinCli = "UPDATE ".$db_name.".consulta_estado_financiero SET validado = 1 WHERE tipo_documento = ? AND documento = ? AND token = ? AND tipo_documento_adicional = ? AND documento_adicional = ? AND validado = 0";
								if(!$stmt11 = $mysqli->prepare($upEstFinCli))
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
									if(empty($id_titular_cliente_db)) $stmt11->bind_param('iss', $tipoDocumento, $documento, $token);
									else $stmt11->bind_param('issis', $tipo_documento_cliente_titular_db, $documento_cliente_titular_db, $token, $tipoDocumento, $documento);
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
								
								if(empty($id_titular_cliente_db)) $selectMCC = "SELECT ef.token FROM ".$db_name.".consulta_estado_financiero ef WHERE ef.tipo_documento = ? AND ef.documento = ? AND ef.token = ?";
								else $selectMCC = "SELECT ef.token FROM ".$db_name.".consulta_estado_financiero ef WHERE ef.tipo_documento = ? AND ef.documento = ? AND ef.token = ? AND ef.tipo_documento_adicional = ? AND ef.documento_adicional = ?";
								if($stmt55 = $mysqli->prepare($selectMCC))
								{
									if(empty($id_titular_cliente_db)) $stmt55->bind_param('iss', $tipoDocumento, $documento, $token);
									else $stmt55->bind_param('issis', $tipo_documento_cliente_titular_db, $documento_cliente_titular_db, $token, $tipoDocumento, $documento);
									$stmt55->execute();    
									$stmt55->store_result();
									
									$totR55 = $stmt55->num_rows;

									if($totR55 > 0)
									{
										$stmt55->bind_result($token_estado_financiero_db);
										$stmt55->fetch();
										
										$tokenVEC = $token_estado_financiero_db;
										
										$stmt55->free_result();
										$stmt55->close();
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
							}
							
							$mysqli->commit();
							$mysqli->autocommit(TRUE);
							$stmt->free_result();
							$stmt->close();
							
							
							if($stmt61 = $mysqli->prepare("SELECT s.id_cadena FROM ".$db_name.".usuario u, ".$db_name.".sucursal s WHERE u.id_sucursal = s.id AND u.id = ?"))
							{
								$stmt61->bind_param('s', $_SESSION['username']);
								$stmt61->execute();    
								$stmt61->store_result();
								
								$totR61 = $stmt61->num_rows;

								if($totR61 > 0)
								{
									$stmt61->bind_result($id_cadena_usuario);
									$stmt61->fetch();
													
									$stmt61->free_result();
									$stmt61->close();
								}
								else
								{
									echo translate('There_Is_ No_Chain_Associated_With_The_User',$GLOBALS['lang']);
									return;
								}
							}
							else
							{
								echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
								return;
							}						
							
							if($stmt62 = $mysqli->prepare("SELECT pc.id, pc.nombre FROM ".$db_name.".perfil_credito_x_plan pcxp, ".$db_name.".plan_credito pc, ".$db_name.".cadena c, ".$db_name.".perfil_credito pcre WHERE pcxp.id_plan_credito = pc.id AND pcxp.id_perfil_credito = pcre.id AND pc.id_cadena = c.id AND pcre.id = ? AND c.id = ?"))
							{
								$stmt62->bind_param('ii', $id_perfil_credito_cliente_db, $id_cadena_usuario);
								$stmt62->execute();    
								$stmt62->store_result();
								
								$totR62 = $stmt62->num_rows;

								if($totR62 > 0)
								{
									$stmt62->bind_result($id_plan_credito_s_db, $nombre_plan_credito_s_db);
									
									while($stmt62->fetch())
									{
										if(empty($planesCreditoCli)) $planesCreditoCli = $id_plan_credito_s_db.'|'.$nombre_plan_credito_s_db;
										else $planesCreditoCli = $planesCreditoCli.';;'.$id_plan_credito_s_db.'|'.$nombre_plan_credito_s_db;
									}
									
									$stmt62->free_result();
									$stmt62->close();
									
									if(empty($tokenVEC)) $tokenVEC = $token;
									echo translate('Msg_Supervisor_OK',$GLOBALS['lang']).'=::=::'.$tokenECRC.'=:::=:::'.$tokenVEC.'=::::=::::'.$planesCreditoCli.'=:=:'.$nombres_cliente_db.'|'.$apellidos_cliente_db.'|'.$tipo_cuenta_texto_cliente.'|'.$telefono_cliente_db.'|'.$monto_credito_disponible;
									return;							
								}
								else
								{
									echo translate('No_Credit_Plans_Associated_With_The_Customer_Credit_Profile',$GLOBALS['lang']);
									return;
								}
							}
							else
							{
								echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
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