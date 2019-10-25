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
		
		$tokenVECC=htmlspecialchars($_POST["token"], ENT_QUOTES, 'UTF-8');
		$tokenVS=htmlspecialchars($_POST["token2"], ENT_QUOTES, 'UTF-8');
		$tipoDocumento=htmlspecialchars($_POST["tipoDocumento"], ENT_QUOTES, 'UTF-8');
		$documento=htmlspecialchars($_POST["documento"], ENT_QUOTES, 'UTF-8');
		
		$motivo=htmlspecialchars($_POST["motivo"], ENT_QUOTES, 'UTF-8');
		
		if ($stmt500 = $mysqli->prepare("SELECT c.id FROM finan_cli.cadena c, finan_cli.usuario u, finan_cli.sucursal s WHERE u.id_sucursal = s.id AND s.id_cadena = c.id AND u.id = ?")) 
		{
			$stmt500->bind_param('s', $_SESSION['username']);
			$stmt500->execute();    
			$stmt500->store_result();
	 
			$totR500 = $stmt500->num_rows;
			if($totR500 > 0)
			{
				$stmt500->bind_result($id_cadena_user);
				$stmt500->fetch();

				$stmt500->free_result();
				$stmt500->close();				
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
		
		if($stmt47 = $mysqli->prepare("SELECT c.id, c.estado, c.id_titular, c.monto_maximo_credito, c.nombres, c.apellidos, t.numero, c.cuil_cuit, c.id_perfil_credito FROM finan_cli.cliente c, finan_cli.telefono t, finan_cli.cliente_x_telefono ct WHERE ct.tipo_documento = c.tipo_documento AND ct.documento = c.documento AND t.id = ct.id_telefono AND ct.preferido = 1 AND c.tipo_documento = ? AND c.documento = ?"))
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
				$stmt47->bind_result($id_cliente_db, $estado_cliente_db, $id_titular_cliente_db, $monto_maximo_credito_cliente_db, $nombres_cliente_db, $apellidos_cliente_db, $telefono_cliente_db, $cuil_cuit_cliente_db, $id_perfil_credito_cliente_db);
				$stmt47->fetch();
				
				$cuitCuil = $cuil_cuit_cliente_db;
				if($estado_cliente_db != translate('State_User',$GLOBALS['lang']))
				{
					echo translate('Msg_Disable_Client',$GLOBALS['lang']);
					return;
				}
				else
				{
					if(!empty($id_titular_cliente_db))
					{
						if($stmt48 = $mysqli->prepare("SELECT c.id, c.estado, c.tipo_documento, c.documento FROM finan_cli.cliente c WHERE c.id = ?"))
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
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']).$mysqli->error;
			return;
		}
		
		if(empty($id_titular_cliente_db)) $selectExisteDC = "SELECT cc.id FROM finan_cli.credito_cliente ccli, finan_cli.cuota_credito cc WHERE ccli.id_credito = cc.id_credito AND cc.estado = ? AND ccli.tipo_documento = ? AND ccli.documento = ?";
		else $selectExisteDC = "SELECT cc.id FROM finan_cli.credito_cliente ccli, finan_cli.cuota_credito cc WHERE ccli.id_credito = cc.id_credito AND cc.estado = ? AND ccli.tipo_documento_adicional = ? AND ccli.documento_adicional = ?";
		if ($stmt928 = $mysqli->prepare($selectExisteDC)) 
		{
			$estadoENM = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
			$stmt928->bind_param('sis', $estadoENM, $tipoDocumento, $documento);
			$stmt928->execute();    
			$stmt928->store_result();
	 
			$totR928 = $stmt928->num_rows;
			if($totR928 > 0)
			{
				$stmt928->bind_result($id_cadena_user);
				$stmt928->fetch();

				echo translate('Msg_Client_Must_Regularize_His_Debt_To_Request_New_Credits',$GLOBALS['lang']);
				return;
				
				$stmt928->free_result();
				$stmt928->close();				
			}	
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}
		
		if(empty($id_titular_cliente_db)) $selectCreditoC = "SELECT MAX(cc.fecha) FROM finan_cli.credito_cliente cc WHERE cc.tipo_documento = ? AND cc.documento = ? HAVING MAX(cc.fecha) IS NOT NULL";
		else $selectCreditoC = "SELECT MAX(cc.fecha) FROM finan_cli.credito_cliente cc WHERE cc.tipo_documento_adicional = ? AND cc.documento_adicional = ? HAVING MAX(cc.fecha) IS NOT NULL";
		if ($stmt702 = $mysqli->prepare($selectCreditoC)) 
		{
			$stmt702->bind_param('is', $tipoDocumento, $documento); 
			$stmt702->execute();    
			$stmt702->store_result();
	 
			$totR702 = $stmt702->num_rows;
			if($totR702 > 0)
			{
				$stmt702->bind_result($ultima_fecha_credito_cliente);
				$stmt702->fetch();

				$fechaObtUCCDB = substr($ultima_fecha_credito_cliente, 0, 4).'-'.substr($ultima_fecha_credito_cliente, 4, 2).'-'.substr($ultima_fecha_credito_cliente, 6, 2).' '.substr($ultima_fecha_credito_cliente, 8, 2).':'.substr($ultima_fecha_credito_cliente, 10, 2).':'.substr($ultima_fecha_credito_cliente, 12, 2);
				$fechaUCCDB = new DateTime($fechaObtUCCDB);
				$fechaAct = new DateTime();	
				$difDias = $fechaAct->diff($fechaUCCDB);
				
				if($stmt703 = $mysqli->prepare("SELECT p.valor FROM finan_cli.parametros p WHERE p.nombre = 'cantidad_dias_actualizacion_datos_cliente'"))
				{
					$stmt703->execute();    
					$stmt703->store_result();
					
					$totR703 = $stmt703->num_rows;
					if($totR703 > 0)
					{						
						$stmt703->bind_result($cantidad_dias_act_datos_clientes_db);
						$stmt703->fetch();
						
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
				
				if($difDias->days >= $cantidad_dias_act_datos_clientes_db)
				{
					if($stmt703 = $mysqli->prepare("SELECT MAX(vdc.fecha) FROM finan_cli.verificacion_datos_cliente vdc WHERE vdc.tipo_documento = ? AND vdc.documento = ? HAVING MAX(vdc.fecha) IS NOT NULL"))
					{
						$stmt703->bind_param('is', $tipoDocumento, $documento);
						$stmt703->execute();    
						$stmt703->store_result();
						
						$totR703 = $stmt703->num_rows;
						if($totR703 > 0)
						{						
							$stmt703->bind_result($ultima_fecha_control_cliente);
							$stmt703->fetch();
							
							$fechaActCVC = date('Ymd');
							if($ultima_fecha_control_cliente != $fechaActCVC)							
							{
								$mysqli->autocommit(FALSE);
								$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
								
								$insertEFCDB = "INSERT INTO finan_cli.verificacion_datos_cliente(tipo_documento,documento,fecha) VALUES (?,?,?)";
								if(!$stmt10 = $mysqli->prepare($insertEFCDB))
								{
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
									return;
								}
								else
								{
									$date_registro_cef_db = date("Ymd");
									$stmt10->bind_param('iss', $tipoDocumento, $documento, $date_registro_cef_db);
									if(!$stmt10->execute())
									{
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
										return;						
									}
									
									$mysqli->commit();
									$mysqli->autocommit(TRUE);
								}
								
								echo translate('Msg_Please_Verify_Customer_Information',$GLOBALS['lang']);
								return;
							}
							
							$stmt703->free_result();
							$stmt703->close();
						}
						else
						{
							$mysqli->autocommit(FALSE);
							$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
							
							$insertEFCDB = "INSERT INTO finan_cli.verificacion_datos_cliente(tipo_documento,documento,fecha) VALUES (?,?,?)";
							if(!$stmt10 = $mysqli->prepare($insertEFCDB))
							{
								$mysqli->autocommit(TRUE);
								$stmt->free_result();
								$stmt->close();
								echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
								return;
							}
							else
							{
								$date_registro_cef_db = date("Ymd");
								$stmt10->bind_param('iss', $tipoDocumento, $documento, $date_registro_cef_db);
								if(!$stmt10->execute())
								{
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
									return;						
								}
								
								$mysqli->commit();
								$mysqli->autocommit(TRUE);
							}
							
							echo translate('Msg_Please_Verify_Customer_Information',$GLOBALS['lang']);
							return;
						}
					}
					else 
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;				
					}					
				}	
				
				$stmt702->free_result();
				$stmt702->close();				
			}
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}
			
		if(empty($id_titular_cliente_db)) $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang']);
		else $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang']);
		
		$selectMCC = "SELECT SUM(cu.monto_cuota_original) FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.cuota_credito cu WHERE c.id = cc.id_credito AND c.id = cu.id_credito AND cc.tipo_documento = ? AND cc.documento = ? AND c.estado IN (?,?)";
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
		
		if(!empty($id_cliente_titular_db))
		{
			if($stmt40 = $mysqli->prepare("SELECT c.tipo_documento, c.documento, c.cuil_cuit, c.id_genero FROM finan_cli.cliente c WHERE c.id = ?"))
			{
				$stmt40->bind_param('i', $id_cliente_titular_db);
				$stmt40->execute();    
				$stmt40->store_result();
				
				$totR40 = $stmt40->num_rows;

				if($totR40 > 0)
				{
					$stmt40->bind_result($tipo_documento_titular, $documento_titular, $cuit_cuil_titular, $id_genero_titular);
					$stmt40->fetch();
					
					$tipoDocumentoTitular = $tipo_documento_titular;
					$documentoTitular = $documento_titular;
					$cuitCuilTitular = $cuit_cuil_titular;
					$idGeneroTitular = $id_genero_titular;
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
		
		if(!empty($id_cliente_titular_db)) $consEFCAyT = "SELECT cef.id FROM finan_cli.consulta_estado_financiero cef WHERE cef.tipo_documento = ? AND cef.documento = ? AND cef.token = ? AND cef.cuit_cuil = ? AND  cef.tipo_documento_adicional = ? AND cef.documento_adicional = ? AND cef.validado = 1";
		else $consEFCAyT = "SELECT cef.id FROM finan_cli.consulta_estado_financiero cef WHERE cef.tipo_documento = ? AND cef.documento = ? AND cef.token = ? AND cef.cuit_cuil = ? AND cef.validado = 1";
		if($stmt = $mysqli->prepare($consEFCAyT))
		{
			if(!empty($id_cliente_titular_db)) $stmt->bind_param('issiis', $tipoDocumentoTitular, $documentoTitular, $tokenVECC, $cuitCuilTitular, $tipoDocumento, $documento);
			else $stmt->bind_param('issi', $tipoDocumento, $documento, $tokenVECC, $cuitCuil);
			$stmt->execute();    
			$stmt->store_result();
			
			$totR = $stmt->num_rows;

			if($totR > 0)
			{
				$selectVCS = "SELECT e.id, e.token FROM finan_cli.estado_cliente e WHERE e.tipo_documento = ? AND e.documento = ? AND e.fecha like ? AND e.id_motivo IN (?,?) AND e.token = ?";
				if($stmt51 = $mysqli->prepare($selectVCS))
				{
					$date_registro_a_s = date("Ymd")."%";
					$motivo2 = 60;
					if(empty($id_cliente_titular_db)) $stmt51->bind_param('issiis', $tipoDocumento, $documento, $date_registro_a_s, $motivo, $motivo2, $tokenVS);
					else $stmt51->bind_param('issiis', $tipo_documento_cliente_titular_db, $documento_cliente_titular_db, $date_registro_a_s, $motivo, $motivo2, $tokenVS);
					$stmt51->execute();    
					$stmt51->store_result();
					
					$totR51 = $stmt51->num_rows;

					if($totR51 > 0)
					{						
						if($stmt61 = $mysqli->prepare("SELECT s.id_cadena FROM finan_cli.usuario u, finan_cli.sucursal s WHERE u.id_sucursal = s.id AND u.id = ?"))
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
						
						if($stmt62 = $mysqli->prepare("SELECT pc.id, pc.nombre FROM finan_cli.perfil_credito_x_plan pcxp, finan_cli.plan_credito pc, finan_cli.cadena c, finan_cli.perfil_credito pcre WHERE pcxp.id_plan_credito = pc.id AND pcxp.id_perfil_credito = pcre.id AND pc.id_cadena = c.id AND pcre.id = ? AND c.id = ?"))
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
								
								echo translate('Msg_Validation_Credit_Status_Client_Is_Not_Necessary',$GLOBALS['lang']).'=::=::'.$tokenVECC.'=:::=:::'.$tokenVS.'=::::=::::'.$planesCreditoCli.'=:=:'.$nombres_cliente_db.'|'.$apellidos_cliente_db.'|'.$tipo_cuenta_texto_cliente.'|'.$telefono_cliente_db.'|'.$monto_credito_disponible;
								$stmt51->free_result();
								$stmt51->close();
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

					$stmt51->free_result();
					$stmt51->close();					
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
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}

		
		if($stmt = $mysqli->prepare("SELECT cef.id, cef.fecha, cef.resultado_xml, cef.token, cef.validado FROM finan_cli.consulta_estado_financiero cef WHERE cef.tipo_documento = ? AND cef.documento = ? AND cef.cuit_cuil = ? ORDER BY cef.fecha DESC"))
		{
			if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $stmt->bind_param('isi', $tipoDocumentoTitular, $documentoTitular, $cuit_cuil_titular);
			else $stmt->bind_param('isi', $tipoDocumento, $documento, $cuitCuil);
			$stmt->execute();    
			$stmt->store_result();
			
			$totR = $stmt->num_rows;

			if($totR > 0)
			{
				if($stmt41 = $mysqli->prepare("SELECT p.valor FROM finan_cli.parametros p WHERE p.nombre = 'cantidad_días_consulta_db_estado_financiero_clientes'"))
				{
					$stmt41->execute();    
					$stmt41->store_result();
					
					$totR41 = $stmt41->num_rows;

					if($totR41 > 0)
					{
						$stmt->bind_result($id_estado_financiero_cliente_db, $fecha_estado_financiero_cliente_db, $resultado_xml_estado_financiero_cliente_db, $token_estado_financiero_cliente_db, $validacion_estado_financiero_cliente_db);
						$stmt->fetch();
						
						$stmt41->bind_result($cantidad_dias_est_financiero_db);
						$stmt41->fetch();
						
						$fechaObtDB = substr($fecha_estado_financiero_cliente_db, 0, 4).'-'.substr($fecha_estado_financiero_cliente_db, 4, 2).'-'.substr($fecha_estado_financiero_cliente_db, 6, 2).' '.substr($fecha_estado_financiero_cliente_db, 8, 2).':'.substr($fecha_estado_financiero_cliente_db, 10, 2).':'.substr($fecha_estado_financiero_cliente_db, 12, 2);
						$fechaInfDB = new DateTime($fechaObtDB);
						$fechaAct = new DateTime();
						$difDias = $fechaAct->diff($fechaInfDB);

						if($cantidad_dias_est_financiero_db > $difDias->days)
						{
							$resultado_finan_cli_final = $resultado_xml_estado_financiero_cliente_db;
							$tokenECF = $token_estado_financiero_cliente_db;
						}
						else
						{
							if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $resultado_finan_cli_final = consulta_estado_financiero_cliente($tipoDocumentoTitular, $documentoTitular, $cuitCuilTitular, $idGeneroTitular);
							else $resultado_finan_cli_final = consulta_estado_financiero_cliente($tipoDocumento, $documento, $cuitCuil, $genero);
							
							if(strpos($resultado_finan_cli_final, translate('Msg_Financial_Statement_Was_Consulted_Successfully',$GLOBALS['lang'])) !== false && strpos($resultado_finan_cli_final, '560') === false)
							{
								$resultado_finan_cli_final = str_replace(translate('Msg_Financial_Statement_Was_Consulted_Successfully',$GLOBALS['lang']), "", $resultado_finan_cli_final);
								
								$mysqli->autocommit(FALSE);
								$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
								
								if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $insertEFCDB = "INSERT INTO finan_cli.consulta_estado_financiero(tipo_documento,documento,fecha,resultado_xml,usuario,cuit_cuil,token,tipo_documento_adicional,documento_adicional,validado,id_cadena) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
								else $insertEFCDB = "INSERT INTO finan_cli.consulta_estado_financiero(tipo_documento,documento,fecha,resultado_xml,usuario,cuit_cuil,token,validado,id_cadena) VALUES (?,?,?,?,?,?,?,?,?)";
								if(!$stmt10 = $mysqli->prepare($insertEFCDB))
								{
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
									return;
								}
								else
								{
									$date_registro_cef_db = date("YmdHis");
									$tokenECF = md5(uniqid(rand(), true));
									$tokenECF = hash('sha512', $tokenECF);
									$validadoECF = 0;
									if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $stmt10->bind_param('issssisisii', $tipoDocumentoTitular, $documentoTitular, $date_registro_cef_db, $resultado_finan_cli_final, $_SESSION['username'], $cuitCuilTitular, $tokenECF, $tipoDocumento, $documento, $validadoECF, $id_cadena_user);
									else $stmt10->bind_param('issssisii', $tipoDocumento, $documento, $date_registro_cef_db, $resultado_finan_cli_final, $_SESSION['username'], $cuitCuil, $tokenECF, $validadoECF, $id_cadena_user);
									if(!$stmt10->execute())
									{
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
										return;						
									}
									
									$mysqli->commit();
									$mysqli->autocommit(TRUE);
								}
							}
							else
							{
								echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
								return;
							}
						}
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
			else
			{
				if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $resultado_finan_cli_final = consulta_estado_financiero_cliente($tipoDocumentoTitular, $documentoTitular, $cuitCuilTitular, $idGeneroTitular);
				else $resultado_finan_cli_final = consulta_estado_financiero_cliente($tipoDocumento, $documento, $cuitCuil, $genero);
								
				if(strpos($resultado_finan_cli_final, translate('Msg_Financial_Statement_Was_Consulted_Successfully',$GLOBALS['lang'])) !== false && strpos($resultado_finan_cli_final, '560') === false)
				{
					$resultado_finan_cli_final = str_replace(translate('Msg_Financial_Statement_Was_Consulted_Successfully',$GLOBALS['lang']), "", $resultado_finan_cli_final);
					
					$mysqli->autocommit(FALSE);
					$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
					
					
					if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $insertEFCDB2 = "INSERT INTO finan_cli.consulta_estado_financiero(tipo_documento,documento,fecha,resultado_xml,usuario,cuit_cuil,token,tipo_documento_adicional,documento_adicional,validado,id_cadena) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
					else $insertEFCDB2 = "INSERT INTO finan_cli.consulta_estado_financiero(tipo_documento,documento,fecha,resultado_xml,usuario,cuit_cuil,token,validado,id_cadena) VALUES (?,?,?,?,?,?,?,?,?)";
					if(!$stmt10 = $mysqli->prepare($insertEFCDB2))
					{
						echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;
					}
					else
					{
						$date_registro_cef_db = date("YmdHis");
						$tokenECF = md5(uniqid(rand(), true));
						$tokenECF = hash('sha512', $tokenECF);
						$validadoECF = 0;
						if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $stmt10->bind_param('issssisisii', $tipoDocumentoTitular, $documentoTitular, $date_registro_cef_db, $resultado_finan_cli_final, $_SESSION['username'], $cuitCuilTitular, $tokenECF, $tipoDocumento, $documento, $validadoECF, $id_cadena_user);
						else $stmt10->bind_param('issssisii', $tipoDocumento, $documento, $date_registro_cef_db, $resultado_finan_cli_final, $_SESSION['username'], $cuitCuil, $tokenECF, $validadoECF, $id_cadena_user);
						if(!$stmt10->execute())
						{
							echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']).$mysqli->error;
							$mysqli->autocommit(TRUE);
							$stmt->free_result();
							$stmt->close();
							return;						
						}
						
						$mysqli->commit();
						$mysqli->autocommit(TRUE);
					}
				}
				else
				{
					echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
					return;
				}				
			}				
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		
		if(!empty($resultado_finan_cli_final))
		{			
			$estado_activa_supervisor = 0;
			if($stmt42 = $mysqli->prepare("SELECT p.valor FROM finan_cli.parametros p WHERE p.nombre = 'validar_estado_financiero_clientes_supervisor'"))
			{
				$stmt42->execute();    
				$stmt42->store_result();
				
				$totR42 = $stmt42->num_rows;

				if($totR42 > 0)
				{
					$stmt42->bind_result($valor_necesita_supervisor_cef_db);
					$stmt42->fetch();
					
					if($validacion_estado_financiero_cliente_db == 0 && $valor_necesita_supervisor_cef_db == 1)
					{
						if($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3)
						{
							$mysqli->autocommit(FALSE);
							$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
							
							if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $insertECCOP = "INSERT INTO finan_cli.estado_cliente(fecha,tipo_documento,documento,id_motivo,usuario,tipo_documento_adicional,documento_adicional,token) VALUES (?,?,?,?,?,?,?,?)";
							else $insertECCOP = "INSERT INTO finan_cli.estado_cliente(fecha,tipo_documento,documento,id_motivo,usuario,token) VALUES (?,?,?,?,?,?)";
							if(!$stmt43 = $mysqli->prepare($insertECCOP))
							{
								echo $mysqli->error;
								$mysqli->autocommit(TRUE);
								$stmt->free_result();
								$stmt->close();
								return;
							}
							else
							{
								$date_registro_a_eccef_db = date("YmdHis");
								$tokenECRC = md5(uniqid(rand(), true));
								$tokenECRC = hash('sha512', $tokenECRC);
								if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $stmt43->bind_param('sisisiss', $date_registro_a_eccef_db, $tipoDocumentoTitular, $documentoTitular, $motivo, $_SESSION['username'], $tipoDocumento, $documento, $tokenECRC);
								else $stmt43->bind_param('sisiss', $date_registro_a_eccef_db, $tipoDocumento, $documento, $motivo, $_SESSION['username'], $tokenECRC);
								if(!$stmt43->execute())
								{
									echo $mysqli->error;
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;						
								}
													
								$mysqli->commit();
								$mysqli->autocommit(TRUE);		
							}

							$estado_fin_cli = new SimpleXMLElement($resultado_finan_cli_final);
							if(empty($tokenECF)) $tokenECF = $tokenVECC;
							if(empty($tokenECRC)) $tokenECRC = $tokenVS;
							echo translate('Msg_Validation_Credit_Status_Client_OK',$GLOBALS['lang']).'=::=::=::'.$tokenECF.'=:::=:::'.$tokenECRC.'=:=:=:';
							echo '<div class="panel-group">';				
							echo '	<div class="panel panel-default">';
							echo '		<div id="panel-title-header" class="panel-heading">';
							echo '			<h3 class="panel-title">'.translate('Lbl_Result_Financial_Statement_Client',$GLOBALS['lang']).'</h3>';
							echo ' 		</div>';
							echo '		<div class="panel-body">';
							echo '			<form id="formulariocefc" role="form" onsubmit="buscarTextoEstadoFinanciero(); return false;">';		
							echo '				<div class="form-group form-inline">';
							echo '					<div class="form-group" id="buscartextoestadocrediticiocliente">';
							echo '						<input class="form-control input-sm" id="buscartextoestadocrediticioclientei" name="buscartextoestadocrediticioclientei" type="text" maxlength="150" />';
							echo '						&nbsp;<button type="button" class="btn" id="btnBuscarEstadoF" name="btnBuscarEstadoF" title="'.translate('Lbl_Search_Text_Statement_Client',$GLOBALS['lang']).'" onclick="buscarTextoEstadoFinanciero();"><i class="fas fa-search"></i></button>';									
							echo '					</div>';
							echo '				</div>';
							echo '				<div class="form-group form-inline">';					
							echo '					<div class="form-group" id="resultadoestadofinancierocliente">';
							echo '						<textarea rows="3" cols="67" class="form-control input-sm" id="resultadoestadofinancieroclientei" name="resultadoestadofinancieroclientei">';
							echo '<EXISTENCIA_FISICA>&#013;&#010;';
							$contadorRecC1 = 0;
							foreach ($estado_fin_cli->Existencia_Fisica_Resu[0]->row as $recEFC) 
							{
								$contadorRecC1++;
								echo '	<APELLIDO_Y_NOMBRE> '.$recEFC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
								echo '	<CUIT_CUIL> '.$recEFC->cdi.' </CUIT_CUIL>&#013;&#010;';								
								echo '	<FECHA_NACIMIENTO> '.$recEFC->fecha_nacimiento.' </FECHA_NACIMIENTO>&#013;&#010;';							
								echo '	<DIRECCION_CALLE> '.$recEFC->direc_calle.' </DIRECCION_CALLE>&#013;&#010;';							
								echo '	<LOCALIDAD> '.$recEFC->localidad.' </LOCALIDAD>&#013;&#010;';							
								echo '	<CODIGO_POSTAL> '.$recEFC->codigo_postal.' </CODIGO_POSTAL>&#013;&#010;';							
								echo '	<PROVINCIA> '.$recEFC->provincia.' </PROVINCIA>&#013;&#010;';							
								echo '	<TIPO_DOCUMENTO> '.$recEFC->t_docu.' </TIPO_DOCUMENTO>&#013;&#010;';							
								echo '	<FALLECIDO> '.$recEFC->fallecido.' </FALLECIDO>&#013;&#010;';
								
								if(count($estado_fin_cli->Existencia_Fisica_Resu[0]->row) > 1 && $contadorRecC1 < count($estado_fin_cli->Existencia_Fisica_Resu[0]->row)) echo '&#013;&#010;';
							}							
							echo '</EXISTENCIA_FISICA>&#013;&#010;&#013;&#010;';
							echo '<PREDICTOR_INGRESOS>&#013;&#010;';
							$contadorRecC2 = 0;
							foreach ($estado_fin_cli->predictor_ingreso[0]->row as $recPIC) 
							{							
								$contadorRecC2++;
								echo '	<PREDICTOR> '.$recPIC->predictor_ingresos.' </PREDICTOR>&#013;&#010;';
								
								if(count($estado_fin_cli->predictor_ingreso[0]->row) > 1 && $contadorRecC2 < count($estado_fin_cli->predictor_ingreso[0]->row)) echo '&#013;&#010;';
							}
							echo '</PREDICTOR_INGRESOS>&#013;&#010;&#013;&#010;';
							echo '<TIENE_JUI_QUI_EJEC>&#013;&#010;';
							$contadorRecC3 = 0;
							foreach ($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row as $recTJQEC) 
							{								
								$contadorRecC3++;
								echo '	<TIENE_JUICIO> '.$recTJQEC->tiene_juicio.' </TIENE_JUICIO>&#013;&#010;';
								
								if(count($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row) > 1 && $contadorRecC3 < count($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row)) echo '&#013;&#010;';
							}
							echo '</TIENE_JUI_QUI_EJEC>&#013;&#010;&#013;&#010;';
							echo '<DEUDA_SISTEMA_FINANCIERO_6M>&#013;&#010;';
							$contadorRecC4 = 0;
							foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row as $recDSF6M) 
							{								
								$contadorRecC4++;
								echo '	<ENTIDAD> '.$recDSF6M->entidad.' </ENTIDAD>&#013;&#010;';
								echo '	<SITUACION> '.$recDSF6M->situacion.' </SITUACION>&#013;&#010;';
								echo '	<MONTO_MAXIMO> '.$recDSF6M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
								echo '	<DEUDA_ACTUAL> '.$recDSF6M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
								echo '	<FECHA> '.$recDSF6M->fecha.' </FECHA>&#013;&#010;';	

								if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row) > 1 && $contadorRecC4 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row)) echo '&#013;&#010;';								
							}
							echo '</DEUDA_SISTEMA_FINANCIERO_6M>&#013;&#010;&#013;&#010;';	
							echo '<DEUDA_SISTEMA_FINANCIERO_12M>&#013;&#010;';
							$contadorRecC5 = 0;
							foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row as $recDSF12M) 
							{								
								$contadorRecC5++;
								echo '	<ENTIDAD> '.$recDSF12M->entidad.' </ENTIDAD>&#013;&#010;';
								echo '	<SITUACION> '.$recDSF12M->situacion.' </SITUACION>&#013;&#010;';
								echo '	<MONTO_MAXIMO> '.$recDSF12M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
								echo '	<DEUDA_ACTUAL> '.$recDSF12M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
								echo '	<FECHA> '.$recDSF12M->fecha.' </FECHA>&#013;&#010;';	

								if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row) > 1 && $contadorRecC5 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row)) echo '&#013;&#010;';								
							}
							echo '</DEUDA_SISTEMA_FINANCIERO_12M>&#013;&#010;&#013;&#010;';	
							echo '<DEUDA_SISTEMA_FINANCIERO_24M>&#013;&#010;';
							$contadorRecC6 = 0;
							foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row as $recDSF24M) 
							{								
								$contadorRecC6++;
								echo '	<ENTIDAD> '.$recDSF24M->entidad.' </ENTIDAD>&#013;&#010;';
								echo '	<SITUACION> '.$recDSF24M->situacion.' </SITUACION>&#013;&#010;';
								echo '	<MONTO_MAXIMO> '.$recDSF24M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
								echo '	<DEUDA_ACTUAL> '.$recDSF24M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
								echo '	<FECHA> '.$recDSF24M->fecha.' </FECHA>&#013;&#010;';

								if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row) > 1 && $contadorRecC6 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row)) echo '&#013;&#010;';								
							}
							echo '</DEUDA_SISTEMA_FINANCIERO_24M>&#013;&#010;&#013;&#010;';	
							echo '<RELACION_DEPENDENCIA>&#013;&#010;';
							$contadorRecC7 = 0;
							foreach ($estado_fin_cli->RELACION_DEPENDENCIA[0]->row as $recRDC) 
							{								
								$contadorRecC7++;
								echo '	<ULTIMO_PERIODO> '.$recRDC->ult_periodo.' </ULTIMO_PERIODO>&#013;&#010;';
								echo '	<ALTA_ULTIMO_TRABAJO> '.$recRDC->alta_trabajo_ultimo.' </ALTA_ULTIMO_TRABAJO>&#013;&#010;';
								echo '	<CUIT> '.$recRDC->cuit.' </CUIT>&#013;&#010;';
								echo '	<RAZON_SOCIAL> '.$recRDC->razon_social.' </RAZON_SOCIAL>&#013;&#010;';
								echo '	<SITUACION_LABORAL_ACTUAL> '.$recRDC->situacion_laboral_actual.' </SITUACION_LABORAL_ACTUAL>&#013;&#010;';

								if(count($estado_fin_cli->RELACION_DEPENDENCIA[0]->row) > 1 && $contadorRecC7 < count($estado_fin_cli->RELACION_DEPENDENCIA[0]->row)) echo '&#013;&#010;';								
							}
							echo '</RELACION_DEPENDENCIA>&#013;&#010;&#013;&#010;';
							echo '<CONSTANCIA_DE_INSCRIPCION_AFIP>&#013;&#010;';
							$contadorRecC8 = 0;
							foreach ($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row as $recCIAC) 
							{								
								$contadorRecC8++;
								echo '	<CUIT> '.$recCIAC->cuit.' </CUIT>&#013;&#010;';
								echo '	<DENOMINACION> '.$recCIAC->denominacion.' </DENOMINACION>&#013;&#010;';
								echo '	<FECHA_CONTRATO_SOCIAL> '.$recCIAC->fecha_contrato_social.' </FECHA_CONTRATO_SOCIAL>&#013;&#010;';
								echo '	<MES_CIERRE> '.$recCIAC->mes_cierre.' </MES_CIERRE>&#013;&#010;';
								echo '	<CATEGORIA> '.$recCIAC->categoria.' </CATEGORIA>&#013;&#010;';	
								echo '	<FECHA_INICIO_ACTIVIDADES> '.$recCIAC->fecha_inicio_actividades.' </FECHA_INICIO_ACTIVIDADES>&#013;&#010;';
								echo '	<DESCRIPCION> '.$recCIAC->descripcion.' </DESCRIPCION>&#013;&#010;';
								echo '	<DIRECCION> '.$recCIAC->direccion.' </DIRECCION>&#013;&#010;';
								echo '	<LOCALIDAD> '.$recCIAC->localidad.' </LOCALIDAD>&#013;&#010;';
								echo '	<PROVINCIA> '.$recCIAC->provincia.' </PROVINCIA>&#013;&#010;';	
								echo '	<CODIGO_POSTAL> '.$recCIAC->cp.' </CODIGO_POSTAL>&#013;&#010;';
								echo '	<ANTIGUEDAD_MESES> '.$recCIAC->antiguedad_meses.' </ANTIGUEDAD_MESES>&#013;&#010;';	

								if(count($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row) > 1 && $contadorRecC8 < count($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row)) echo '&#013;&#010;';								
							}
							echo '</CONSTANCIA_DE_INSCRIPCION_AFIP>&#013;&#010;&#013;&#010;';
							echo '<TIPO_ACTIVIDAD>&#013;&#010;';
							$contadorRecC9 = 0;
							foreach ($estado_fin_cli->Tipo_Actividad[0]->row as $recTAC) 
							{								
								$contadorRecC9++;
								echo '	<TIPO_ACTIVIDAD> '.$recTAC->tipo_actividad.' </TIPO_ACTIVIDAD>&#013;&#010;';

								if(count($estado_fin_cli->Tipo_Actividad[0]->row) > 1 && $contadorRecC9 < count($estado_fin_cli->Tipo_Actividad[0]->row)) echo '&#013;&#010;';								
							}
							echo '</TIPO_ACTIVIDAD>&#013;&#010;&#013;&#010;';				
							echo '<POSEE_MOVILES>&#013;&#010;';
							$contadorRecC10 = 0;
							foreach ($estado_fin_cli->Moviles_posee[0]->row as $recPMC) 
							{								
								$contadorRecC10++;
								echo '	<POSEE_AUTOS> '.$recPMC->posee_autos.' </POSEE_AUTOS>&#013;&#010;';
								echo '	<CANTIDAD_AUTOS> '.$recPMC->cantidad_autos.' </CANTIDAD_AUTOS>&#013;&#010;';

								if(count($estado_fin_cli->Moviles_posee[0]->row) > 1 && $contadorRecC10 < count($estado_fin_cli->Moviles_posee[0]->row)) echo '&#013;&#010;';									
							}
							echo '</POSEE_MOVILES>&#013;&#010;&#013;&#010;';					
							echo '<INFO_LABORAL_HISTORICA>&#013;&#010;';
							$contadorRecC11 = 0;
							foreach ($estado_fin_cli->inf_lab_hist_fecha_[0]->row as $recILHC) 
							{								
								$contadorRecC11++;
								echo '	<CUIT> '.$recILHC->inf_lab_cuit_.' </CUIT>&#013;&#010;';
								echo '	<RAZON_SOCIAL> '.$recILHC->inf_lab_razon_.' </RAZON_SOCIAL>&#013;&#010;';
								echo '	<RELACION_DESDE> '.$recILHC->relacion_desde_.' </RELACION_DESDE>&#013;&#010;';
								echo '	<RELACION_HASTA> '.$recILHC->relacion_hasta_.' </RELACION_HASTA>&#013;&#010;';

								if(count($estado_fin_cli->inf_lab_hist_fecha_[0]->row) > 1 && $contadorRecC11 < count($estado_fin_cli->inf_lab_hist_fecha_[0]->row)) echo '&#013;&#010;';								
							}
							echo '</INFO_LABORAL_HISTORICA>&#013;&#010;&#013;&#010;';								
							echo '<POSEE_JUICIOS_EMBARGO>&#013;&#010;';
							$contadorRecC12 = 0;
							foreach ($estado_fin_cli->Juicios_Posee_Embargo[0]->row as $recPJEC) 
							{								
								$contadorRecC12++;
								echo '	<TIPO_JUICIO> '.$recPJEC->juicios_posee_tipo.' </TIPO_JUICIO>&#013;&#010;';

								if(count($estado_fin_cli->Juicios_Posee_Embargo[0]->row) > 1 && $contadorRecC12 < count($estado_fin_cli->Juicios_Posee_Embargo[0]->row)) echo '&#013;&#010;';								
							}
							echo '</POSEE_JUICIOS_EMBARGO>&#013;&#010;&#013;&#010;';	
							echo '<POSEE_JUICIOS_INHABILITACION>&#013;&#010;';
							$contadorRecC13 = 0;
							foreach ($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row as $recPJIC) 
							{								
								$contadorRecC13++;
								echo '	<TIPO_JUICIO> '.$recPJIC->juicios_posee_tipo.' </TIPO_JUICIO>&#013;&#010;';

								if(count($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row) > 1 && $contadorRecC13 < count($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row)) echo '&#013;&#010;';								
							}
							echo '</POSEE_JUICIOS_INHABILITACION>&#013;&#010;&#013;&#010;';	
							echo '<JUBILADO>&#013;&#010;';
							$contadorRecC14 = 0;
							foreach ($estado_fin_cli->JUBILADO[0]->row as $recJC) 
							{								
								$contadorRecC14++;
								echo '	<NUMERO_BENEFICIARIO> '.$recJC->numero_beneficiario.' </NUMERO_BENEFICIARIO>&#013;&#010;';
								echo '	<CLASE_BENEFICIO> '.$recJC->beneficio_clase.' </CLASE_BENEFICIO>&#013;&#010;';
								echo '	<ESTADO> '.$recJC->estado.' </ESTADO>&#013;&#010;';

								if(count($estado_fin_cli->JUBILADO[0]->row) > 1 && $contadorRecC14 < count($estado_fin_cli->JUBILADO[0]->row)) echo '&#013;&#010;';								
							}
							echo '</JUBILADO>&#013;&#010;&#013;&#010;';	
							echo '<VALIDA_MORAS>&#013;&#010;';
							$contadorRecC15 = 0;
							foreach ($estado_fin_cli->Moras_Valida[0]->row as $recMVC) 
							{								
								$contadorRecC15++;
								echo '	<VALIDA> '.$recMVC->valida.' </VALIDA>&#013;&#010;';
								echo '	<ENTIDADES> '.$recMVC->entidades.' </ENTIDADES>&#013;&#010;';
								echo '	<MAX_ATRASO> '.$recMVC->max_atraso.' </MAX_ATRASO>&#013;&#010;';

								if(count($estado_fin_cli->Moras_Valida[0]->row) > 1 && $contadorRecC15 < count($estado_fin_cli->Moras_Valida[0]->row)) echo '&#013;&#010;';								
							}
							echo '</VALIDA_MORAS>&#013;&#010;&#013;&#010;';	
							echo '<SCORE>&#013;&#010;';
							$contadorRecC16 = 0;
							foreach ($estado_fin_cli->Score[0]->row as $recSCC) 
							{								
								$contadorRecC16++;
								echo '	<SCORE> '.$recSCC->score.' </SCORE>&#013;&#010;';

								if(count($estado_fin_cli->Score[0]->row) > 1 && $contadorRecC16 < count($estado_fin_cli->Score[0]->row)) echo '&#013;&#010;';									
							}
							echo '</SCORE>&#013;&#010;&#013;&#010;';									
							echo '						</textarea>';
							echo '					</div>';		
							echo '				</div>';							
						}
						else
						{
							if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $selectECCEF = "SELECT e.id FROM finan_cli.estado_cliente e WHERE e.tipo_documento = ? AND e.documento = ? AND e.fecha like ? AND e.id_motivo IN (?,?) AND e.tipo_documento_adicional = ? AND e.documento_adicional = ?";
							else $selectECCEF = "SELECT e.id FROM finan_cli.estado_cliente e WHERE e.tipo_documento = ? AND e.documento = ? AND e.fecha like ? AND e.id_motivo IN (?,?)";
							if($stmt44 = $mysqli->prepare($selectECCEF))
							{
								$date_registro_c_ecef = date("Ymd")."%";
								$motivo2_u = 38;
								if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $stmt44->bind_param('issiiis', $tipoDocumentoTitular, $documentoTitular, $date_registro_c_ecef, $motivo, $motivo2_u, $tipoDocumento, $documento);
								else $stmt44->bind_param('issii', $tipoDocumento, $documento, $date_registro_c_ecef, $motivo, $motivo2_u);
								$stmt44->execute();    
								$stmt44->store_result();
								
								$totR44 = $stmt44->num_rows;

								if($totR44 == 0)
								{
									$estado_fin_cli = new SimpleXMLElement($resultado_finan_cli_final);
									if(empty($tokenECF)) $tokenECF = $tokenVECC;
									if(empty($tokenECRC)) $tokenECRC = $tokenVS;
									echo translate('Msg_Validation_Credit_Status_Client_OK',$GLOBALS['lang']).'=::=::=::'.$tokenECF.'=:::=:::'.$tokenECRC.'=:=:=:';
									echo '<div class="panel-group">';				
									echo '	<div class="panel panel-default">';
									echo '		<div id="panel-title-header" class="panel-heading">';
									echo '			<h3 class="panel-title">'.translate('Lbl_Result_Financial_Statement_Client',$GLOBALS['lang']).'</h3>';
									echo ' 		</div>';
									echo '		<div class="panel-body">';
									echo '			<form id="formulariocefc" role="form" onsubmit="buscarTextoEstadoFinanciero(); return false;">';		
									echo '				<div class="form-group form-inline">';
									echo '					<div class="form-group" id="buscartextoestadocrediticiocliente">';
									echo '						<input class="form-control input-sm" id="buscartextoestadocrediticioclientei" name="buscartextoestadocrediticioclientei" type="text" maxlength="150" />';
									echo '						&nbsp;<button type="button" class="btn" id="btnBuscarEstadoF" name="btnBuscarEstadoF" title="'.translate('Lbl_Search_Text_Statement_Client',$GLOBALS['lang']).'" onclick="buscarTextoEstadoFinanciero();"><i class="fas fa-search"></i></button>';									
									echo '					</div>';
									echo '				</div>';
									echo '				<div class="form-group form-inline">';					
									echo '					<div class="form-group" id="resultadoestadofinancierocliente">';
									echo '						<textarea rows="3" cols="67" class="form-control input-sm" id="resultadoestadofinancieroclientei" name="resultadoestadofinancieroclientei">';
									echo '<EXISTENCIA_FISICA>&#013;&#010;';
									$contadorRecC1 = 0;
									foreach ($estado_fin_cli->Existencia_Fisica_Resu[0]->row as $recEFC) 
									{
										$contadorRecC1++;
										echo '	<APELLIDO_Y_NOMBRE> '.$recEFC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
										echo '	<CUIT_CUIL> '.$recEFC->cdi.' </CUIT_CUIL>&#013;&#010;';								
										echo '	<FECHA_NACIMIENTO> '.$recEFC->fecha_nacimiento.' </FECHA_NACIMIENTO>&#013;&#010;';							
										echo '	<DIRECCION_CALLE> '.$recEFC->direc_calle.' </DIRECCION_CALLE>&#013;&#010;';							
										echo '	<LOCALIDAD> '.$recEFC->localidad.' </LOCALIDAD>&#013;&#010;';							
										echo '	<CODIGO_POSTAL> '.$recEFC->codigo_postal.' </CODIGO_POSTAL>&#013;&#010;';							
										echo '	<PROVINCIA> '.$recEFC->provincia.' </PROVINCIA>&#013;&#010;';							
										echo '	<TIPO_DOCUMENTO> '.$recEFC->t_docu.' </TIPO_DOCUMENTO>&#013;&#010;';							
										echo '	<FALLECIDO> '.$recEFC->fallecido.' </FALLECIDO>&#013;&#010;';
										
										if(count($estado_fin_cli->Existencia_Fisica_Resu[0]->row) > 1 && $contadorRecC1 < count($estado_fin_cli->Existencia_Fisica_Resu[0]->row)) echo '&#013;&#010;';
									}							
									echo '</EXISTENCIA_FISICA>&#013;&#010;&#013;&#010;';
									echo '<PREDICTOR_INGRESOS>&#013;&#010;';
									$contadorRecC2 = 0;
									foreach ($estado_fin_cli->predictor_ingreso[0]->row as $recPIC) 
									{							
										$contadorRecC2++;
										echo '	<PREDICTOR> '.$recPIC->predictor_ingresos.' </PREDICTOR>&#013;&#010;';
										
										if(count($estado_fin_cli->predictor_ingreso[0]->row) > 1 && $contadorRecC2 < count($estado_fin_cli->predictor_ingreso[0]->row)) echo '&#013;&#010;';
									}
									echo '</PREDICTOR_INGRESOS>&#013;&#010;&#013;&#010;';
									echo '<TIENE_JUI_QUI_EJEC>&#013;&#010;';
									$contadorRecC3 = 0;
									foreach ($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row as $recTJQEC) 
									{								
										$contadorRecC3++;
										echo '	<TIENE_JUICIO> '.$recTJQEC->tiene_juicio.' </TIENE_JUICIO>&#013;&#010;';
										
										if(count($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row) > 1 && $contadorRecC3 < count($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row)) echo '&#013;&#010;';
									}
									echo '</TIENE_JUI_QUI_EJEC>&#013;&#010;&#013;&#010;';
									echo '<DEUDA_SISTEMA_FINANCIERO_6M>&#013;&#010;';
									$contadorRecC4 = 0;
									foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row as $recDSF6M) 
									{								
										$contadorRecC4++;
										echo '	<ENTIDAD> '.$recDSF6M->entidad.' </ENTIDAD>&#013;&#010;';
										echo '	<SITUACION> '.$recDSF6M->situacion.' </SITUACION>&#013;&#010;';
										echo '	<MONTO_MAXIMO> '.$recDSF6M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
										echo '	<DEUDA_ACTUAL> '.$recDSF6M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
										echo '	<FECHA> '.$recDSF6M->fecha.' </FECHA>&#013;&#010;';	

										if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row) > 1 && $contadorRecC4 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row)) echo '&#013;&#010;';								
									}
									echo '</DEUDA_SISTEMA_FINANCIERO_6M>&#013;&#010;&#013;&#010;';	
									echo '<DEUDA_SISTEMA_FINANCIERO_12M>&#013;&#010;';
									$contadorRecC5 = 0;
									foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row as $recDSF12M) 
									{								
										$contadorRecC5++;
										echo '	<ENTIDAD> '.$recDSF12M->entidad.' </ENTIDAD>&#013;&#010;';
										echo '	<SITUACION> '.$recDSF12M->situacion.' </SITUACION>&#013;&#010;';
										echo '	<MONTO_MAXIMO> '.$recDSF12M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
										echo '	<DEUDA_ACTUAL> '.$recDSF12M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
										echo '	<FECHA> '.$recDSF12M->fecha.' </FECHA>&#013;&#010;';	

										if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row) > 1 && $contadorRecC5 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row)) echo '&#013;&#010;';								
									}
									echo '</DEUDA_SISTEMA_FINANCIERO_12M>&#013;&#010;&#013;&#010;';	
									echo '<DEUDA_SISTEMA_FINANCIERO_24M>&#013;&#010;';
									$contadorRecC6 = 0;
									foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row as $recDSF24M) 
									{								
										$contadorRecC6++;
										echo '	<ENTIDAD> '.$recDSF24M->entidad.' </ENTIDAD>&#013;&#010;';
										echo '	<SITUACION> '.$recDSF24M->situacion.' </SITUACION>&#013;&#010;';
										echo '	<MONTO_MAXIMO> '.$recDSF24M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
										echo '	<DEUDA_ACTUAL> '.$recDSF24M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
										echo '	<FECHA> '.$recDSF24M->fecha.' </FECHA>&#013;&#010;';

										if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row) > 1 && $contadorRecC6 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row)) echo '&#013;&#010;';								
									}
									echo '</DEUDA_SISTEMA_FINANCIERO_24M>&#013;&#010;&#013;&#010;';	
									echo '<RELACION_DEPENDENCIA>&#013;&#010;';
									$contadorRecC7 = 0;
									foreach ($estado_fin_cli->RELACION_DEPENDENCIA[0]->row as $recRDC) 
									{								
										$contadorRecC7++;
										echo '	<ULTIMO_PERIODO> '.$recRDC->ult_periodo.' </ULTIMO_PERIODO>&#013;&#010;';
										echo '	<ALTA_ULTIMO_TRABAJO> '.$recRDC->alta_trabajo_ultimo.' </ALTA_ULTIMO_TRABAJO>&#013;&#010;';
										echo '	<CUIT> '.$recRDC->cuit.' </CUIT>&#013;&#010;';
										echo '	<RAZON_SOCIAL> '.$recRDC->razon_social.' </RAZON_SOCIAL>&#013;&#010;';
										echo '	<SITUACION_LABORAL_ACTUAL> '.$recRDC->situacion_laboral_actual.' </SITUACION_LABORAL_ACTUAL>&#013;&#010;';

										if(count($estado_fin_cli->RELACION_DEPENDENCIA[0]->row) > 1 && $contadorRecC7 < count($estado_fin_cli->RELACION_DEPENDENCIA[0]->row)) echo '&#013;&#010;';								
									}
									echo '</RELACION_DEPENDENCIA>&#013;&#010;&#013;&#010;';
									echo '<CONSTANCIA_DE_INSCRIPCION_AFIP>&#013;&#010;';
									$contadorRecC8 = 0;
									foreach ($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row as $recCIAC) 
									{								
										$contadorRecC8++;
										echo '	<CUIT> '.$recCIAC->cuit.' </CUIT>&#013;&#010;';
										echo '	<DENOMINACION> '.$recCIAC->denominacion.' </DENOMINACION>&#013;&#010;';
										echo '	<FECHA_CONTRATO_SOCIAL> '.$recCIAC->fecha_contrato_social.' </FECHA_CONTRATO_SOCIAL>&#013;&#010;';
										echo '	<MES_CIERRE> '.$recCIAC->mes_cierre.' </MES_CIERRE>&#013;&#010;';
										echo '	<CATEGORIA> '.$recCIAC->categoria.' </CATEGORIA>&#013;&#010;';	
										echo '	<FECHA_INICIO_ACTIVIDADES> '.$recCIAC->fecha_inicio_actividades.' </FECHA_INICIO_ACTIVIDADES>&#013;&#010;';
										echo '	<DESCRIPCION> '.$recCIAC->descripcion.' </DESCRIPCION>&#013;&#010;';
										echo '	<DIRECCION> '.$recCIAC->direccion.' </DIRECCION>&#013;&#010;';
										echo '	<LOCALIDAD> '.$recCIAC->localidad.' </LOCALIDAD>&#013;&#010;';
										echo '	<PROVINCIA> '.$recCIAC->provincia.' </PROVINCIA>&#013;&#010;';	
										echo '	<CODIGO_POSTAL> '.$recCIAC->cp.' </CODIGO_POSTAL>&#013;&#010;';
										echo '	<ANTIGUEDAD_MESES> '.$recCIAC->antiguedad_meses.' </ANTIGUEDAD_MESES>&#013;&#010;';	

										if(count($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row) > 1 && $contadorRecC8 < count($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row)) echo '&#013;&#010;';								
									}
									echo '</CONSTANCIA_DE_INSCRIPCION_AFIP>&#013;&#010;&#013;&#010;';
									echo '<TIPO_ACTIVIDAD>&#013;&#010;';
									$contadorRecC9 = 0;
									foreach ($estado_fin_cli->Tipo_Actividad[0]->row as $recTAC) 
									{								
										$contadorRecC9++;
										echo '	<TIPO_ACTIVIDAD> '.$recTAC->tipo_actividad.' </TIPO_ACTIVIDAD>&#013;&#010;';

										if(count($estado_fin_cli->Tipo_Actividad[0]->row) > 1 && $contadorRecC9 < count($estado_fin_cli->Tipo_Actividad[0]->row)) echo '&#013;&#010;';								
									}
									echo '</TIPO_ACTIVIDAD>&#013;&#010;&#013;&#010;';				
									echo '<POSEE_MOVILES>&#013;&#010;';
									$contadorRecC10 = 0;
									foreach ($estado_fin_cli->Moviles_posee[0]->row as $recPMC) 
									{								
										$contadorRecC10++;
										echo '	<POSEE_AUTOS> '.$recPMC->posee_autos.' </POSEE_AUTOS>&#013;&#010;';
										echo '	<CANTIDAD_AUTOS> '.$recPMC->cantidad_autos.' </CANTIDAD_AUTOS>&#013;&#010;';

										if(count($estado_fin_cli->Moviles_posee[0]->row) > 1 && $contadorRecC10 < count($estado_fin_cli->Moviles_posee[0]->row)) echo '&#013;&#010;';									
									}
									echo '</POSEE_MOVILES>&#013;&#010;&#013;&#010;';					
									echo '<INFO_LABORAL_HISTORICA>&#013;&#010;';
									$contadorRecC11 = 0;
									foreach ($estado_fin_cli->inf_lab_hist_fecha_[0]->row as $recILHC) 
									{								
										$contadorRecC11++;
										echo '	<CUIT> '.$recILHC->inf_lab_cuit_.' </CUIT>&#013;&#010;';
										echo '	<RAZON_SOCIAL> '.$recILHC->inf_lab_razon_.' </RAZON_SOCIAL>&#013;&#010;';
										echo '	<RELACION_DESDE> '.$recILHC->relacion_desde_.' </RELACION_DESDE>&#013;&#010;';
										echo '	<RELACION_HASTA> '.$recILHC->relacion_hasta_.' </RELACION_HASTA>&#013;&#010;';

										if(count($estado_fin_cli->inf_lab_hist_fecha_[0]->row) > 1 && $contadorRecC11 < count($estado_fin_cli->inf_lab_hist_fecha_[0]->row)) echo '&#013;&#010;';								
									}
									echo '</INFO_LABORAL_HISTORICA>&#013;&#010;&#013;&#010;';								
									echo '<POSEE_JUICIOS_EMBARGO>&#013;&#010;';
									$contadorRecC12 = 0;
									foreach ($estado_fin_cli->Juicios_Posee_Embargo[0]->row as $recPJEC) 
									{								
										$contadorRecC12++;
										echo '	<TIPO_JUICIO> '.$recPJEC->juicios_posee_tipo.' </TIPO_JUICIO>&#013;&#010;';

										if(count($estado_fin_cli->Juicios_Posee_Embargo[0]->row) > 1 && $contadorRecC12 < count($estado_fin_cli->Juicios_Posee_Embargo[0]->row)) echo '&#013;&#010;';								
									}
									echo '</POSEE_JUICIOS_EMBARGO>&#013;&#010;&#013;&#010;';	
									echo '<POSEE_JUICIOS_INHABILITACION>&#013;&#010;';
									$contadorRecC13 = 0;
									foreach ($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row as $recPJIC) 
									{								
										$contadorRecC13++;
										echo '	<TIPO_JUICIO> '.$recPJIC->juicios_posee_tipo.' </TIPO_JUICIO>&#013;&#010;';

										if(count($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row) > 1 && $contadorRecC13 < count($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row)) echo '&#013;&#010;';								
									}
									echo '</POSEE_JUICIOS_INHABILITACION>&#013;&#010;&#013;&#010;';	
									echo '<JUBILADO>&#013;&#010;';
									$contadorRecC14 = 0;
									foreach ($estado_fin_cli->JUBILADO[0]->row as $recJC) 
									{								
										$contadorRecC14++;
										echo '	<NUMERO_BENEFICIARIO> '.$recJC->numero_beneficiario.' </NUMERO_BENEFICIARIO>&#013;&#010;';
										echo '	<CLASE_BENEFICIO> '.$recJC->beneficio_clase.' </CLASE_BENEFICIO>&#013;&#010;';
										echo '	<ESTADO> '.$recJC->estado.' </ESTADO>&#013;&#010;';

										if(count($estado_fin_cli->JUBILADO[0]->row) > 1 && $contadorRecC14 < count($estado_fin_cli->JUBILADO[0]->row)) echo '&#013;&#010;';								
									}
									echo '</JUBILADO>&#013;&#010;&#013;&#010;';	
									echo '<VALIDA_MORAS>&#013;&#010;';
									$contadorRecC15 = 0;
									foreach ($estado_fin_cli->Moras_Valida[0]->row as $recMVC) 
									{								
										$contadorRecC15++;
										echo '	<VALIDA> '.$recMVC->valida.' </VALIDA>&#013;&#010;';
										echo '	<ENTIDADES> '.$recMVC->entidades.' </ENTIDADES>&#013;&#010;';
										echo '	<MAX_ATRASO> '.$recMVC->max_atraso.' </MAX_ATRASO>&#013;&#010;';

										if(count($estado_fin_cli->Moras_Valida[0]->row) > 1 && $contadorRecC15 < count($estado_fin_cli->Moras_Valida[0]->row)) echo '&#013;&#010;';								
									}
									echo '</VALIDA_MORAS>&#013;&#010;&#013;&#010;';	
									echo '<SCORE>&#013;&#010;';
									$contadorRecC16 = 0;
									foreach ($estado_fin_cli->Score[0]->row as $recSCC) 
									{								
										$contadorRecC16++;
										echo '	<SCORE> '.$recSCC->score.' </SCORE>&#013;&#010;';

										if(count($estado_fin_cli->Score[0]->row) > 1 && $contadorRecC16 < count($estado_fin_cli->Score[0]->row)) echo '&#013;&#010;';									
									}
									echo '</SCORE>&#013;&#010;&#013;&#010;';							
									echo '						</textarea>';
									echo '					</div>';		
									echo '				</div>';									
									echo '				<div class="form-group form-inline"><hr />';
									echo '					<label class="control-label" for="usuariosupervisorn3">'.translate('Lbl_User_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
									echo '					<div class="form-group" id="usuariosupervisorn3">';
									echo '						<input title="'.translate('Msg_User_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="usuariosupervisorn3i" name="usuariosupervisorn3i" type="text" maxlength="50" />';
									echo '					</div>';
									echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="passwordsupervisorn3">'.translate('Lbl_Password_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
									echo '					<div class="form-group" id="passwordsupervisorn3">';
									echo '						<input title="'.translate('Msg_Password_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="passwordsupervisorn3i" name="passwordsupervisorn3i" type="password" maxlength="128" />';
									echo '					</div>';		
									echo '				</div>';
									$estado_activa_supervisor = 1;
								}
								else
								{
									$estado_fin_cli = new SimpleXMLElement($resultado_finan_cli_final);
									if(empty($tokenECF)) $tokenECF = $tokenVECC;
									if(empty($tokenECRC)) $tokenECRC = $tokenVS;
									echo translate('Msg_Validation_Credit_Status_Client_OK',$GLOBALS['lang']).'=::=::=::'.$tokenECF.'=:::=:::'.$tokenECRC.'=:=:=:';
									echo '<div class="panel-group">';				
									echo '	<div class="panel panel-default">';
									echo '		<div id="panel-title-header" class="panel-heading">';
									echo '			<h3 class="panel-title">'.translate('Lbl_Result_Financial_Statement_Client',$GLOBALS['lang']).'</h3>';
									echo ' 		</div>';
									echo '		<div class="panel-body">';
									echo '			<form id="formulariocefc" role="form" onsubmit="buscarTextoEstadoFinanciero(); return false;">';		
									echo '				<div class="form-group form-inline">';
									echo '					<div class="form-group" id="buscartextoestadocrediticiocliente">';
									echo '						<input class="form-control input-sm" id="buscartextoestadocrediticioclientei" name="buscartextoestadocrediticioclientei" type="text" maxlength="150" />';
									echo '						&nbsp;<button type="button" class="btn" id="btnBuscarEstadoF" name="btnBuscarEstadoF" title="'.translate('Lbl_Search_Text_Statement_Client',$GLOBALS['lang']).'" onclick="buscarTextoEstadoFinanciero();"><i class="fas fa-search"></i></button>';									
									echo '					</div>';
									echo '				</div>';
									echo '				<div class="form-group form-inline">';					
									echo '					<div class="form-group" id="resultadoestadofinancierocliente">';
									echo '						<textarea rows="3" cols="67" class="form-control input-sm" id="resultadoestadofinancieroclientei" name="resultadoestadofinancieroclientei">';
									echo '<EXISTENCIA_FISICA>&#013;&#010;';
									$contadorRecC1 = 0;
									foreach ($estado_fin_cli->Existencia_Fisica_Resu[0]->row as $recEFC) 
									{
										$contadorRecC1++;
										echo '	<APELLIDO_Y_NOMBRE> '.$recEFC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
										echo '	<CUIT_CUIL> '.$recEFC->cdi.' </CUIT_CUIL>&#013;&#010;';								
										echo '	<FECHA_NACIMIENTO> '.$recEFC->fecha_nacimiento.' </FECHA_NACIMIENTO>&#013;&#010;';							
										echo '	<DIRECCION_CALLE> '.$recEFC->direc_calle.' </DIRECCION_CALLE>&#013;&#010;';							
										echo '	<LOCALIDAD> '.$recEFC->localidad.' </LOCALIDAD>&#013;&#010;';							
										echo '	<CODIGO_POSTAL> '.$recEFC->codigo_postal.' </CODIGO_POSTAL>&#013;&#010;';							
										echo '	<PROVINCIA> '.$recEFC->provincia.' </PROVINCIA>&#013;&#010;';							
										echo '	<TIPO_DOCUMENTO> '.$recEFC->t_docu.' </TIPO_DOCUMENTO>&#013;&#010;';							
										echo '	<FALLECIDO> '.$recEFC->fallecido.' </FALLECIDO>&#013;&#010;';
										
										if(count($estado_fin_cli->Existencia_Fisica_Resu[0]->row) > 1 && $contadorRecC1 < count($estado_fin_cli->Existencia_Fisica_Resu[0]->row)) echo '&#013;&#010;';
									}							
									echo '</EXISTENCIA_FISICA>&#013;&#010;&#013;&#010;';
									echo '<PREDICTOR_INGRESOS>&#013;&#010;';
									$contadorRecC2 = 0;
									foreach ($estado_fin_cli->predictor_ingreso[0]->row as $recPIC) 
									{							
										$contadorRecC2++;
										echo '	<PREDICTOR> '.$recPIC->predictor_ingresos.' </PREDICTOR>&#013;&#010;';
										
										if(count($estado_fin_cli->predictor_ingreso[0]->row) > 1 && $contadorRecC2 < count($estado_fin_cli->predictor_ingreso[0]->row)) echo '&#013;&#010;';
									}
									echo '</PREDICTOR_INGRESOS>&#013;&#010;&#013;&#010;';
									echo '<TIENE_JUI_QUI_EJEC>&#013;&#010;';
									$contadorRecC3 = 0;
									foreach ($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row as $recTJQEC) 
									{								
										$contadorRecC3++;
										echo '	<TIENE_JUICIO> '.$recTJQEC->tiene_juicio.' </TIENE_JUICIO>&#013;&#010;';
										
										if(count($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row) > 1 && $contadorRecC3 < count($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row)) echo '&#013;&#010;';
									}
									echo '</TIENE_JUI_QUI_EJEC>&#013;&#010;&#013;&#010;';
									echo '<DEUDA_SISTEMA_FINANCIERO_6M>&#013;&#010;';
									$contadorRecC4 = 0;
									foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row as $recDSF6M) 
									{								
										$contadorRecC4++;
										echo '	<ENTIDAD> '.$recDSF6M->entidad.' </ENTIDAD>&#013;&#010;';
										echo '	<SITUACION> '.$recDSF6M->situacion.' </SITUACION>&#013;&#010;';
										echo '	<MONTO_MAXIMO> '.$recDSF6M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
										echo '	<DEUDA_ACTUAL> '.$recDSF6M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
										echo '	<FECHA> '.$recDSF6M->fecha.' </FECHA>&#013;&#010;';	

										if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row) > 1 && $contadorRecC4 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row)) echo '&#013;&#010;';								
									}
									echo '</DEUDA_SISTEMA_FINANCIERO_6M>&#013;&#010;&#013;&#010;';	
									echo '<DEUDA_SISTEMA_FINANCIERO_12M>&#013;&#010;';
									$contadorRecC5 = 0;
									foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row as $recDSF12M) 
									{								
										$contadorRecC5++;
										echo '	<ENTIDAD> '.$recDSF12M->entidad.' </ENTIDAD>&#013;&#010;';
										echo '	<SITUACION> '.$recDSF12M->situacion.' </SITUACION>&#013;&#010;';
										echo '	<MONTO_MAXIMO> '.$recDSF12M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
										echo '	<DEUDA_ACTUAL> '.$recDSF12M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
										echo '	<FECHA> '.$recDSF12M->fecha.' </FECHA>&#013;&#010;';	

										if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row) > 1 && $contadorRecC5 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row)) echo '&#013;&#010;';								
									}
									echo '</DEUDA_SISTEMA_FINANCIERO_12M>&#013;&#010;&#013;&#010;';	
									echo '<DEUDA_SISTEMA_FINANCIERO_24M>&#013;&#010;';
									$contadorRecC6 = 0;
									foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row as $recDSF24M) 
									{								
										$contadorRecC6++;
										echo '	<ENTIDAD> '.$recDSF24M->entidad.' </ENTIDAD>&#013;&#010;';
										echo '	<SITUACION> '.$recDSF24M->situacion.' </SITUACION>&#013;&#010;';
										echo '	<MONTO_MAXIMO> '.$recDSF24M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
										echo '	<DEUDA_ACTUAL> '.$recDSF24M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
										echo '	<FECHA> '.$recDSF24M->fecha.' </FECHA>&#013;&#010;';

										if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row) > 1 && $contadorRecC6 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row)) echo '&#013;&#010;';								
									}
									echo '</DEUDA_SISTEMA_FINANCIERO_24M>&#013;&#010;&#013;&#010;';	
									echo '<RELACION_DEPENDENCIA>&#013;&#010;';
									$contadorRecC7 = 0;
									foreach ($estado_fin_cli->RELACION_DEPENDENCIA[0]->row as $recRDC) 
									{								
										$contadorRecC7++;
										echo '	<ULTIMO_PERIODO> '.$recRDC->ult_periodo.' </ULTIMO_PERIODO>&#013;&#010;';
										echo '	<ALTA_ULTIMO_TRABAJO> '.$recRDC->alta_trabajo_ultimo.' </ALTA_ULTIMO_TRABAJO>&#013;&#010;';
										echo '	<CUIT> '.$recRDC->cuit.' </CUIT>&#013;&#010;';
										echo '	<RAZON_SOCIAL> '.$recRDC->razon_social.' </RAZON_SOCIAL>&#013;&#010;';
										echo '	<SITUACION_LABORAL_ACTUAL> '.$recRDC->situacion_laboral_actual.' </SITUACION_LABORAL_ACTUAL>&#013;&#010;';

										if(count($estado_fin_cli->RELACION_DEPENDENCIA[0]->row) > 1 && $contadorRecC7 < count($estado_fin_cli->RELACION_DEPENDENCIA[0]->row)) echo '&#013;&#010;';								
									}
									echo '</RELACION_DEPENDENCIA>&#013;&#010;&#013;&#010;';
									echo '<CONSTANCIA_DE_INSCRIPCION_AFIP>&#013;&#010;';
									$contadorRecC8 = 0;
									foreach ($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row as $recCIAC) 
									{								
										$contadorRecC8++;
										echo '	<CUIT> '.$recCIAC->cuit.' </CUIT>&#013;&#010;';
										echo '	<DENOMINACION> '.$recCIAC->denominacion.' </DENOMINACION>&#013;&#010;';
										echo '	<FECHA_CONTRATO_SOCIAL> '.$recCIAC->fecha_contrato_social.' </FECHA_CONTRATO_SOCIAL>&#013;&#010;';
										echo '	<MES_CIERRE> '.$recCIAC->mes_cierre.' </MES_CIERRE>&#013;&#010;';
										echo '	<CATEGORIA> '.$recCIAC->categoria.' </CATEGORIA>&#013;&#010;';	
										echo '	<FECHA_INICIO_ACTIVIDADES> '.$recCIAC->fecha_inicio_actividades.' </FECHA_INICIO_ACTIVIDADES>&#013;&#010;';
										echo '	<DESCRIPCION> '.$recCIAC->descripcion.' </DESCRIPCION>&#013;&#010;';
										echo '	<DIRECCION> '.$recCIAC->direccion.' </DIRECCION>&#013;&#010;';
										echo '	<LOCALIDAD> '.$recCIAC->localidad.' </LOCALIDAD>&#013;&#010;';
										echo '	<PROVINCIA> '.$recCIAC->provincia.' </PROVINCIA>&#013;&#010;';	
										echo '	<CODIGO_POSTAL> '.$recCIAC->cp.' </CODIGO_POSTAL>&#013;&#010;';
										echo '	<ANTIGUEDAD_MESES> '.$recCIAC->antiguedad_meses.' </ANTIGUEDAD_MESES>&#013;&#010;';	

										if(count($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row) > 1 && $contadorRecC8 < count($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row)) echo '&#013;&#010;';								
									}
									echo '</CONSTANCIA_DE_INSCRIPCION_AFIP>&#013;&#010;&#013;&#010;';
									echo '<TIPO_ACTIVIDAD>&#013;&#010;';
									$contadorRecC9 = 0;
									foreach ($estado_fin_cli->Tipo_Actividad[0]->row as $recTAC) 
									{								
										$contadorRecC9++;
										echo '	<TIPO_ACTIVIDAD> '.$recTAC->tipo_actividad.' </TIPO_ACTIVIDAD>&#013;&#010;';

										if(count($estado_fin_cli->Tipo_Actividad[0]->row) > 1 && $contadorRecC9 < count($estado_fin_cli->Tipo_Actividad[0]->row)) echo '&#013;&#010;';								
									}
									echo '</TIPO_ACTIVIDAD>&#013;&#010;&#013;&#010;';				
									echo '<POSEE_MOVILES>&#013;&#010;';
									$contadorRecC10 = 0;
									foreach ($estado_fin_cli->Moviles_posee[0]->row as $recPMC) 
									{								
										$contadorRecC10++;
										echo '	<POSEE_AUTOS> '.$recPMC->posee_autos.' </POSEE_AUTOS>&#013;&#010;';
										echo '	<CANTIDAD_AUTOS> '.$recPMC->cantidad_autos.' </CANTIDAD_AUTOS>&#013;&#010;';

										if(count($estado_fin_cli->Moviles_posee[0]->row) > 1 && $contadorRecC10 < count($estado_fin_cli->Moviles_posee[0]->row)) echo '&#013;&#010;';									
									}
									echo '</POSEE_MOVILES>&#013;&#010;&#013;&#010;';					
									echo '<INFO_LABORAL_HISTORICA>&#013;&#010;';
									$contadorRecC11 = 0;
									foreach ($estado_fin_cli->inf_lab_hist_fecha_[0]->row as $recILHC) 
									{								
										$contadorRecC11++;
										echo '	<CUIT> '.$recILHC->inf_lab_cuit_.' </CUIT>&#013;&#010;';
										echo '	<RAZON_SOCIAL> '.$recILHC->inf_lab_razon_.' </RAZON_SOCIAL>&#013;&#010;';
										echo '	<RELACION_DESDE> '.$recILHC->relacion_desde_.' </RELACION_DESDE>&#013;&#010;';
										echo '	<RELACION_HASTA> '.$recILHC->relacion_hasta_.' </RELACION_HASTA>&#013;&#010;';

										if(count($estado_fin_cli->inf_lab_hist_fecha_[0]->row) > 1 && $contadorRecC11 < count($estado_fin_cli->inf_lab_hist_fecha_[0]->row)) echo '&#013;&#010;';								
									}
									echo '</INFO_LABORAL_HISTORICA>&#013;&#010;&#013;&#010;';								
									echo '<POSEE_JUICIOS_EMBARGO>&#013;&#010;';
									$contadorRecC12 = 0;
									foreach ($estado_fin_cli->Juicios_Posee_Embargo[0]->row as $recPJEC) 
									{								
										$contadorRecC12++;
										echo '	<TIPO_JUICIO> '.$recPJEC->juicios_posee_tipo.' </TIPO_JUICIO>&#013;&#010;';

										if(count($estado_fin_cli->Juicios_Posee_Embargo[0]->row) > 1 && $contadorRecC12 < count($estado_fin_cli->Juicios_Posee_Embargo[0]->row)) echo '&#013;&#010;';								
									}
									echo '</POSEE_JUICIOS_EMBARGO>&#013;&#010;&#013;&#010;';	
									echo '<POSEE_JUICIOS_INHABILITACION>&#013;&#010;';
									$contadorRecC13 = 0;
									foreach ($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row as $recPJIC) 
									{								
										$contadorRecC13++;
										echo '	<TIPO_JUICIO> '.$recPJIC->juicios_posee_tipo.' </TIPO_JUICIO>&#013;&#010;';

										if(count($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row) > 1 && $contadorRecC13 < count($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row)) echo '&#013;&#010;';								
									}
									echo '</POSEE_JUICIOS_INHABILITACION>&#013;&#010;&#013;&#010;';	
									echo '<JUBILADO>&#013;&#010;';
									$contadorRecC14 = 0;
									foreach ($estado_fin_cli->JUBILADO[0]->row as $recJC) 
									{								
										$contadorRecC14++;
										echo '	<NUMERO_BENEFICIARIO> '.$recJC->numero_beneficiario.' </NUMERO_BENEFICIARIO>&#013;&#010;';
										echo '	<CLASE_BENEFICIO> '.$recJC->beneficio_clase.' </CLASE_BENEFICIO>&#013;&#010;';
										echo '	<ESTADO> '.$recJC->estado.' </ESTADO>&#013;&#010;';

										if(count($estado_fin_cli->JUBILADO[0]->row) > 1 && $contadorRecC14 < count($estado_fin_cli->JUBILADO[0]->row)) echo '&#013;&#010;';								
									}
									echo '</JUBILADO>&#013;&#010;&#013;&#010;';	
									echo '<VALIDA_MORAS>&#013;&#010;';
									$contadorRecC15 = 0;
									foreach ($estado_fin_cli->Moras_Valida[0]->row as $recMVC) 
									{								
										$contadorRecC15++;
										echo '	<VALIDA> '.$recMVC->valida.' </VALIDA>&#013;&#010;';
										echo '	<ENTIDADES> '.$recMVC->entidades.' </ENTIDADES>&#013;&#010;';
										echo '	<MAX_ATRASO> '.$recMVC->max_atraso.' </MAX_ATRASO>&#013;&#010;';

										if(count($estado_fin_cli->Moras_Valida[0]->row) > 1 && $contadorRecC15 < count($estado_fin_cli->Moras_Valida[0]->row)) echo '&#013;&#010;';								
									}
									echo '</VALIDA_MORAS>&#013;&#010;&#013;&#010;';	
									echo '<SCORE>&#013;&#010;';
									$contadorRecC16 = 0;
									foreach ($estado_fin_cli->Score[0]->row as $recSCC) 
									{								
										$contadorRecC16++;
										echo '	<SCORE> '.$recSCC->score.' </SCORE>&#013;&#010;';

										if(count($estado_fin_cli->Score[0]->row) > 1 && $contadorRecC16 < count($estado_fin_cli->Score[0]->row)) echo '&#013;&#010;';									
									}
									echo '</SCORE>&#013;&#010;&#013;&#010;';																	
									echo '						</textarea>';
									echo '					</div>';		
									echo '				</div>';									
								}
								
								$stmt44->free_result();
								$stmt44->close();
							}
							else
							{
								echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
								return;
							}
						}
						echo '				<div class="form-group form-inline">';
						echo '					<div id="img_loader_13"></div>';		
						echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarEFC" id="btnCancelarEFC" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogsearchclientcredit\').dialog(\'close\');" style="margin-left:10px;" />';
						if($estado_activa_supervisor == 1) echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarEFC" id="btnValidarEFC" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarAutorizacionSupervisorEstadoFinancieroCliente(document.getElementById(\'formulariocefc\'),'.$motivo.');"/>';										
						else echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarEFC" id="btnValidarEFC" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarSinSupervisorEstadoFinancieroCliente('.$motivo.');"/>';
						echo '				</div>';				
						echo '			</form>';
						echo '		</div>';
						echo '	</div>';
						echo '</div>';
					}
					else
					{
						if($validacion_estado_financiero_cliente_db == 1 && $valor_necesita_supervisor_cef_db == 1)
						{
							if($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3)
							{
								$estado_fin_cli = new SimpleXMLElement($resultado_finan_cli_final);
								if(empty($tokenECF)) $tokenECF = $tokenVECC;
								if(empty($tokenECRC)) $tokenECRC = $tokenVS;
								echo translate('Msg_Validation_Credit_Status_Client_OK',$GLOBALS['lang']).'=::=::=::'.$tokenECF.'=:::=:::'.$tokenECRC.'=:=:=:';
								echo '<div class="panel-group">';				
								echo '	<div class="panel panel-default">';
								echo '		<div id="panel-title-header" class="panel-heading">';
								echo '			<h3 class="panel-title">'.translate('Lbl_Result_Financial_Statement_Client',$GLOBALS['lang']).'</h3>';
								echo ' 		</div>';
								echo '		<div class="panel-body">';
								echo '			<form id="formulariocefc" role="form" onsubmit="buscarTextoEstadoFinanciero(); return false;">';		
								echo '				<div class="form-group form-inline">';
								echo '					<div class="form-group" id="buscartextoestadocrediticiocliente">';
								echo '						<input class="form-control input-sm" id="buscartextoestadocrediticioclientei" name="buscartextoestadocrediticioclientei" type="text" maxlength="150" />';
								echo '						&nbsp;<button type="button" class="btn" id="btnBuscarEstadoF" name="btnBuscarEstadoF" title="'.translate('Lbl_Search_Text_Statement_Client',$GLOBALS['lang']).'" onclick="buscarTextoEstadoFinanciero();"><i class="fas fa-search"></i></button>';									
								echo '					</div>';
								echo '				</div>';
								echo '				<div class="form-group form-inline">';					
								echo '					<div class="form-group" id="resultadoestadofinancierocliente">';
								echo '						<textarea rows="3" cols="67" class="form-control input-sm" id="resultadoestadofinancieroclientei" name="resultadoestadofinancieroclientei">';
								echo '<EXISTENCIA_FISICA>&#013;&#010;';
								$contadorRecC1 = 0;
								foreach ($estado_fin_cli->Existencia_Fisica_Resu[0]->row as $recEFC) 
								{
									$contadorRecC1++;
									echo '	<APELLIDO_Y_NOMBRE> '.$recEFC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
									echo '	<CUIT_CUIL> '.$recEFC->cdi.' </CUIT_CUIL>&#013;&#010;';								
									echo '	<FECHA_NACIMIENTO> '.$recEFC->fecha_nacimiento.' </FECHA_NACIMIENTO>&#013;&#010;';							
									echo '	<DIRECCION_CALLE> '.$recEFC->direc_calle.' </DIRECCION_CALLE>&#013;&#010;';							
									echo '	<LOCALIDAD> '.$recEFC->localidad.' </LOCALIDAD>&#013;&#010;';							
									echo '	<CODIGO_POSTAL> '.$recEFC->codigo_postal.' </CODIGO_POSTAL>&#013;&#010;';							
									echo '	<PROVINCIA> '.$recEFC->provincia.' </PROVINCIA>&#013;&#010;';							
									echo '	<TIPO_DOCUMENTO> '.$recEFC->t_docu.' </TIPO_DOCUMENTO>&#013;&#010;';							
									echo '	<FALLECIDO> '.$recEFC->fallecido.' </FALLECIDO>&#013;&#010;';
									
									if(count($estado_fin_cli->Existencia_Fisica_Resu[0]->row) > 1 && $contadorRecC1 < count($estado_fin_cli->Existencia_Fisica_Resu[0]->row)) echo '&#013;&#010;';
								}							
								echo '</EXISTENCIA_FISICA>&#013;&#010;&#013;&#010;';
								echo '<PREDICTOR_INGRESOS>&#013;&#010;';
								$contadorRecC2 = 0;
								foreach ($estado_fin_cli->predictor_ingreso[0]->row as $recPIC) 
								{							
									$contadorRecC2++;
									echo '	<PREDICTOR> '.$recPIC->predictor_ingresos.' </PREDICTOR>&#013;&#010;';
									
									if(count($estado_fin_cli->predictor_ingreso[0]->row) > 1 && $contadorRecC2 < count($estado_fin_cli->predictor_ingreso[0]->row)) echo '&#013;&#010;';
								}
								echo '</PREDICTOR_INGRESOS>&#013;&#010;&#013;&#010;';
								echo '<TIENE_JUI_QUI_EJEC>&#013;&#010;';
								$contadorRecC3 = 0;
								foreach ($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row as $recTJQEC) 
								{								
									$contadorRecC3++;
									echo '	<TIENE_JUICIO> '.$recTJQEC->tiene_juicio.' </TIENE_JUICIO>&#013;&#010;';
									
									if(count($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row) > 1 && $contadorRecC3 < count($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row)) echo '&#013;&#010;';
								}
								echo '</TIENE_JUI_QUI_EJEC>&#013;&#010;&#013;&#010;';
								echo '<DEUDA_SISTEMA_FINANCIERO_6M>&#013;&#010;';
								$contadorRecC4 = 0;
								foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row as $recDSF6M) 
								{								
									$contadorRecC4++;
									echo '	<ENTIDAD> '.$recDSF6M->entidad.' </ENTIDAD>&#013;&#010;';
									echo '	<SITUACION> '.$recDSF6M->situacion.' </SITUACION>&#013;&#010;';
									echo '	<MONTO_MAXIMO> '.$recDSF6M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
									echo '	<DEUDA_ACTUAL> '.$recDSF6M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
									echo '	<FECHA> '.$recDSF6M->fecha.' </FECHA>&#013;&#010;';	

									if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row) > 1 && $contadorRecC4 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row)) echo '&#013;&#010;';								
								}
								echo '</DEUDA_SISTEMA_FINANCIERO_6M>&#013;&#010;&#013;&#010;';	
								echo '<DEUDA_SISTEMA_FINANCIERO_12M>&#013;&#010;';
								$contadorRecC5 = 0;
								foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row as $recDSF12M) 
								{								
									$contadorRecC5++;
									echo '	<ENTIDAD> '.$recDSF12M->entidad.' </ENTIDAD>&#013;&#010;';
									echo '	<SITUACION> '.$recDSF12M->situacion.' </SITUACION>&#013;&#010;';
									echo '	<MONTO_MAXIMO> '.$recDSF12M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
									echo '	<DEUDA_ACTUAL> '.$recDSF12M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
									echo '	<FECHA> '.$recDSF12M->fecha.' </FECHA>&#013;&#010;';	

									if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row) > 1 && $contadorRecC5 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row)) echo '&#013;&#010;';								
								}
								echo '</DEUDA_SISTEMA_FINANCIERO_12M>&#013;&#010;&#013;&#010;';	
								echo '<DEUDA_SISTEMA_FINANCIERO_24M>&#013;&#010;';
								$contadorRecC6 = 0;
								foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row as $recDSF24M) 
								{								
									$contadorRecC6++;
									echo '	<ENTIDAD> '.$recDSF24M->entidad.' </ENTIDAD>&#013;&#010;';
									echo '	<SITUACION> '.$recDSF24M->situacion.' </SITUACION>&#013;&#010;';
									echo '	<MONTO_MAXIMO> '.$recDSF24M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
									echo '	<DEUDA_ACTUAL> '.$recDSF24M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
									echo '	<FECHA> '.$recDSF24M->fecha.' </FECHA>&#013;&#010;';

									if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row) > 1 && $contadorRecC6 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row)) echo '&#013;&#010;';								
								}
								echo '</DEUDA_SISTEMA_FINANCIERO_24M>&#013;&#010;&#013;&#010;';	
								echo '<RELACION_DEPENDENCIA>&#013;&#010;';
								$contadorRecC7 = 0;
								foreach ($estado_fin_cli->RELACION_DEPENDENCIA[0]->row as $recRDC) 
								{								
									$contadorRecC7++;
									echo '	<ULTIMO_PERIODO> '.$recRDC->ult_periodo.' </ULTIMO_PERIODO>&#013;&#010;';
									echo '	<ALTA_ULTIMO_TRABAJO> '.$recRDC->alta_trabajo_ultimo.' </ALTA_ULTIMO_TRABAJO>&#013;&#010;';
									echo '	<CUIT> '.$recRDC->cuit.' </CUIT>&#013;&#010;';
									echo '	<RAZON_SOCIAL> '.$recRDC->razon_social.' </RAZON_SOCIAL>&#013;&#010;';
									echo '	<SITUACION_LABORAL_ACTUAL> '.$recRDC->situacion_laboral_actual.' </SITUACION_LABORAL_ACTUAL>&#013;&#010;';

									if(count($estado_fin_cli->RELACION_DEPENDENCIA[0]->row) > 1 && $contadorRecC7 < count($estado_fin_cli->RELACION_DEPENDENCIA[0]->row)) echo '&#013;&#010;';								
								}
								echo '</RELACION_DEPENDENCIA>&#013;&#010;&#013;&#010;';
								echo '<CONSTANCIA_DE_INSCRIPCION_AFIP>&#013;&#010;';
								$contadorRecC8 = 0;
								foreach ($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row as $recCIAC) 
								{								
									$contadorRecC8++;
									echo '	<CUIT> '.$recCIAC->cuit.' </CUIT>&#013;&#010;';
									echo '	<DENOMINACION> '.$recCIAC->denominacion.' </DENOMINACION>&#013;&#010;';
									echo '	<FECHA_CONTRATO_SOCIAL> '.$recCIAC->fecha_contrato_social.' </FECHA_CONTRATO_SOCIAL>&#013;&#010;';
									echo '	<MES_CIERRE> '.$recCIAC->mes_cierre.' </MES_CIERRE>&#013;&#010;';
									echo '	<CATEGORIA> '.$recCIAC->categoria.' </CATEGORIA>&#013;&#010;';	
									echo '	<FECHA_INICIO_ACTIVIDADES> '.$recCIAC->fecha_inicio_actividades.' </FECHA_INICIO_ACTIVIDADES>&#013;&#010;';
									echo '	<DESCRIPCION> '.$recCIAC->descripcion.' </DESCRIPCION>&#013;&#010;';
									echo '	<DIRECCION> '.$recCIAC->direccion.' </DIRECCION>&#013;&#010;';
									echo '	<LOCALIDAD> '.$recCIAC->localidad.' </LOCALIDAD>&#013;&#010;';
									echo '	<PROVINCIA> '.$recCIAC->provincia.' </PROVINCIA>&#013;&#010;';	
									echo '	<CODIGO_POSTAL> '.$recCIAC->cp.' </CODIGO_POSTAL>&#013;&#010;';
									echo '	<ANTIGUEDAD_MESES> '.$recCIAC->antiguedad_meses.' </ANTIGUEDAD_MESES>&#013;&#010;';	

									if(count($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row) > 1 && $contadorRecC8 < count($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row)) echo '&#013;&#010;';								
								}
								echo '</CONSTANCIA_DE_INSCRIPCION_AFIP>&#013;&#010;&#013;&#010;';
								echo '<TIPO_ACTIVIDAD>&#013;&#010;';
								$contadorRecC9 = 0;
								foreach ($estado_fin_cli->Tipo_Actividad[0]->row as $recTAC) 
								{								
									$contadorRecC9++;
									echo '	<TIPO_ACTIVIDAD> '.$recTAC->tipo_actividad.' </TIPO_ACTIVIDAD>&#013;&#010;';

									if(count($estado_fin_cli->Tipo_Actividad[0]->row) > 1 && $contadorRecC9 < count($estado_fin_cli->Tipo_Actividad[0]->row)) echo '&#013;&#010;';								
								}
								echo '</TIPO_ACTIVIDAD>&#013;&#010;&#013;&#010;';				
								echo '<POSEE_MOVILES>&#013;&#010;';
								$contadorRecC10 = 0;
								foreach ($estado_fin_cli->Moviles_posee[0]->row as $recPMC) 
								{								
									$contadorRecC10++;
									echo '	<POSEE_AUTOS> '.$recPMC->posee_autos.' </POSEE_AUTOS>&#013;&#010;';
									echo '	<CANTIDAD_AUTOS> '.$recPMC->cantidad_autos.' </CANTIDAD_AUTOS>&#013;&#010;';

									if(count($estado_fin_cli->Moviles_posee[0]->row) > 1 && $contadorRecC10 < count($estado_fin_cli->Moviles_posee[0]->row)) echo '&#013;&#010;';									
								}
								echo '</POSEE_MOVILES>&#013;&#010;&#013;&#010;';					
								echo '<INFO_LABORAL_HISTORICA>&#013;&#010;';
								$contadorRecC11 = 0;
								foreach ($estado_fin_cli->inf_lab_hist_fecha_[0]->row as $recILHC) 
								{								
									$contadorRecC11++;
									echo '	<CUIT> '.$recILHC->inf_lab_cuit_.' </CUIT>&#013;&#010;';
									echo '	<RAZON_SOCIAL> '.$recILHC->inf_lab_razon_.' </RAZON_SOCIAL>&#013;&#010;';
									echo '	<RELACION_DESDE> '.$recILHC->relacion_desde_.' </RELACION_DESDE>&#013;&#010;';
									echo '	<RELACION_HASTA> '.$recILHC->relacion_hasta_.' </RELACION_HASTA>&#013;&#010;';

									if(count($estado_fin_cli->inf_lab_hist_fecha_[0]->row) > 1 && $contadorRecC11 < count($estado_fin_cli->inf_lab_hist_fecha_[0]->row)) echo '&#013;&#010;';								
								}
								echo '</INFO_LABORAL_HISTORICA>&#013;&#010;&#013;&#010;';								
								echo '<POSEE_JUICIOS_EMBARGO>&#013;&#010;';
								$contadorRecC12 = 0;
								foreach ($estado_fin_cli->Juicios_Posee_Embargo[0]->row as $recPJEC) 
								{								
									$contadorRecC12++;
									echo '	<TIPO_JUICIO> '.$recPJEC->juicios_posee_tipo.' </TIPO_JUICIO>&#013;&#010;';

									if(count($estado_fin_cli->Juicios_Posee_Embargo[0]->row) > 1 && $contadorRecC12 < count($estado_fin_cli->Juicios_Posee_Embargo[0]->row)) echo '&#013;&#010;';								
								}
								echo '</POSEE_JUICIOS_EMBARGO>&#013;&#010;&#013;&#010;';	
								echo '<POSEE_JUICIOS_INHABILITACION>&#013;&#010;';
								$contadorRecC13 = 0;
								foreach ($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row as $recPJIC) 
								{								
									$contadorRecC13++;
									echo '	<TIPO_JUICIO> '.$recPJIC->juicios_posee_tipo.' </TIPO_JUICIO>&#013;&#010;';

									if(count($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row) > 1 && $contadorRecC13 < count($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row)) echo '&#013;&#010;';								
								}
								echo '</POSEE_JUICIOS_INHABILITACION>&#013;&#010;&#013;&#010;';	
								echo '<JUBILADO>&#013;&#010;';
								$contadorRecC14 = 0;
								foreach ($estado_fin_cli->JUBILADO[0]->row as $recJC) 
								{								
									$contadorRecC14++;
									echo '	<NUMERO_BENEFICIARIO> '.$recJC->numero_beneficiario.' </NUMERO_BENEFICIARIO>&#013;&#010;';
									echo '	<CLASE_BENEFICIO> '.$recJC->beneficio_clase.' </CLASE_BENEFICIO>&#013;&#010;';
									echo '	<ESTADO> '.$recJC->estado.' </ESTADO>&#013;&#010;';

									if(count($estado_fin_cli->JUBILADO[0]->row) > 1 && $contadorRecC14 < count($estado_fin_cli->JUBILADO[0]->row)) echo '&#013;&#010;';								
								}
								echo '</JUBILADO>&#013;&#010;&#013;&#010;';	
								echo '<VALIDA_MORAS>&#013;&#010;';
								$contadorRecC15 = 0;
								foreach ($estado_fin_cli->Moras_Valida[0]->row as $recMVC) 
								{								
									$contadorRecC15++;
									echo '	<VALIDA> '.$recMVC->valida.' </VALIDA>&#013;&#010;';
									echo '	<ENTIDADES> '.$recMVC->entidades.' </ENTIDADES>&#013;&#010;';
									echo '	<MAX_ATRASO> '.$recMVC->max_atraso.' </MAX_ATRASO>&#013;&#010;';

									if(count($estado_fin_cli->Moras_Valida[0]->row) > 1 && $contadorRecC15 < count($estado_fin_cli->Moras_Valida[0]->row)) echo '&#013;&#010;';								
								}
								echo '</VALIDA_MORAS>&#013;&#010;&#013;&#010;';	
								echo '<SCORE>&#013;&#010;';
								$contadorRecC16 = 0;
								foreach ($estado_fin_cli->Score[0]->row as $recSCC) 
								{								
									$contadorRecC16++;
									echo '	<SCORE> '.$recSCC->score.' </SCORE>&#013;&#010;';

									if(count($estado_fin_cli->Score[0]->row) > 1 && $contadorRecC16 < count($estado_fin_cli->Score[0]->row)) echo '&#013;&#010;';									
								}
								echo '</SCORE>&#013;&#010;&#013;&#010;';					
								echo '						</textarea>';
								echo '					</div>';		
								echo '				</div>';								
							}
							else
							{
								if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $selectECCEF = "SELECT e.id FROM finan_cli.estado_cliente e WHERE e.tipo_documento = ? AND e.documento = ? AND e.fecha like ? AND e.id_motivo IN (?,?) AND e.tipo_documento_adicional = ? AND e.documento_adicional = ?";
								else $selectECCEF = "SELECT e.id FROM finan_cli.estado_cliente e WHERE e.tipo_documento = ? AND e.documento = ? AND e.fecha like ? AND e.id_motivo IN (?,?)";
								if($stmt44 = $mysqli->prepare($selectECCEF))
								{
									$date_registro_c_ecef = date("Ymd")."%";
									$motivo2_u = 38;
									if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $stmt44->bind_param('issiiis', $tipoDocumentoTitular, $documentoTitular, $date_registro_c_ecef, $motivo, $motivo2_u, $tipoDocumento, $documento);
									else $stmt44->bind_param('issii', $tipoDocumento, $documento, $date_registro_c_ecef, $motivo, $motivo2_u);
									$stmt44->execute();    
									$stmt44->store_result();
									
									$totR44 = $stmt44->num_rows;

									if($totR44 == 0)
									{
										$estado_fin_cli = new SimpleXMLElement($resultado_finan_cli_final);
										if(empty($tokenECF)) $tokenECF = $tokenVECC;
										if(empty($tokenECRC)) $tokenECRC = $tokenVS;
										echo translate('Msg_Validation_Credit_Status_Client_OK',$GLOBALS['lang']).'=::=::=::'.$tokenECF.'=:::=:::'.$tokenECRC.'=:=:=:';
										echo '<div class="panel-group">';				
										echo '	<div class="panel panel-default">';
										echo '		<div id="panel-title-header" class="panel-heading">';
										echo '			<h3 class="panel-title">'.translate('Lbl_Result_Financial_Statement_Client',$GLOBALS['lang']).'</h3>';
										echo ' 		</div>';
										echo '		<div class="panel-body">';
										echo '			<form id="formulariocefc" role="form" onsubmit="buscarTextoEstadoFinanciero(); return false;">';		
										echo '				<div class="form-group form-inline">';
										echo '					<div class="form-group" id="buscartextoestadocrediticiocliente">';
										echo '						<input class="form-control input-sm" id="buscartextoestadocrediticioclientei" name="buscartextoestadocrediticioclientei" type="text" maxlength="150" />';
										echo '						&nbsp;<button type="button" class="btn" id="btnBuscarEstadoF" name="btnBuscarEstadoF" title="'.translate('Lbl_Search_Text_Statement_Client',$GLOBALS['lang']).'" onclick="buscarTextoEstadoFinanciero();"><i class="fas fa-search"></i></button>';									
										echo '					</div>';
										echo '				</div>';
										echo '				<div class="form-group form-inline">';					
										echo '					<div class="form-group" id="resultadoestadofinancierocliente">';
										echo '						<textarea rows="3" cols="67" class="form-control input-sm" id="resultadoestadofinancieroclientei" name="resultadoestadofinancieroclientei">';
										echo '<EXISTENCIA_FISICA>&#013;&#010;';
										$contadorRecC1 = 0;
										foreach ($estado_fin_cli->Existencia_Fisica_Resu[0]->row as $recEFC) 
										{
											$contadorRecC1++;
											echo '	<APELLIDO_Y_NOMBRE> '.$recEFC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
											echo '	<CUIT_CUIL> '.$recEFC->cdi.' </CUIT_CUIL>&#013;&#010;';								
											echo '	<FECHA_NACIMIENTO> '.$recEFC->fecha_nacimiento.' </FECHA_NACIMIENTO>&#013;&#010;';							
											echo '	<DIRECCION_CALLE> '.$recEFC->direc_calle.' </DIRECCION_CALLE>&#013;&#010;';							
											echo '	<LOCALIDAD> '.$recEFC->localidad.' </LOCALIDAD>&#013;&#010;';							
											echo '	<CODIGO_POSTAL> '.$recEFC->codigo_postal.' </CODIGO_POSTAL>&#013;&#010;';							
											echo '	<PROVINCIA> '.$recEFC->provincia.' </PROVINCIA>&#013;&#010;';							
											echo '	<TIPO_DOCUMENTO> '.$recEFC->t_docu.' </TIPO_DOCUMENTO>&#013;&#010;';							
											echo '	<FALLECIDO> '.$recEFC->fallecido.' </FALLECIDO>&#013;&#010;';
											
											if(count($estado_fin_cli->Existencia_Fisica_Resu[0]->row) > 1 && $contadorRecC1 < count($estado_fin_cli->Existencia_Fisica_Resu[0]->row)) echo '&#013;&#010;';
										}							
										echo '</EXISTENCIA_FISICA>&#013;&#010;&#013;&#010;';
										echo '<PREDICTOR_INGRESOS>&#013;&#010;';
										$contadorRecC2 = 0;
										foreach ($estado_fin_cli->predictor_ingreso[0]->row as $recPIC) 
										{							
											$contadorRecC2++;
											echo '	<PREDICTOR> '.$recPIC->predictor_ingresos.' </PREDICTOR>&#013;&#010;';
											
											if(count($estado_fin_cli->predictor_ingreso[0]->row) > 1 && $contadorRecC2 < count($estado_fin_cli->predictor_ingreso[0]->row)) echo '&#013;&#010;';
										}
										echo '</PREDICTOR_INGRESOS>&#013;&#010;&#013;&#010;';
										echo '<TIENE_JUI_QUI_EJEC>&#013;&#010;';
										$contadorRecC3 = 0;
										foreach ($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row as $recTJQEC) 
										{								
											$contadorRecC3++;
											echo '	<TIENE_JUICIO> '.$recTJQEC->tiene_juicio.' </TIENE_JUICIO>&#013;&#010;';
											
											if(count($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row) > 1 && $contadorRecC3 < count($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row)) echo '&#013;&#010;';
										}
										echo '</TIENE_JUI_QUI_EJEC>&#013;&#010;&#013;&#010;';
										echo '<DEUDA_SISTEMA_FINANCIERO_6M>&#013;&#010;';
										$contadorRecC4 = 0;
										foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row as $recDSF6M) 
										{								
											$contadorRecC4++;
											echo '	<ENTIDAD> '.$recDSF6M->entidad.' </ENTIDAD>&#013;&#010;';
											echo '	<SITUACION> '.$recDSF6M->situacion.' </SITUACION>&#013;&#010;';
											echo '	<MONTO_MAXIMO> '.$recDSF6M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
											echo '	<DEUDA_ACTUAL> '.$recDSF6M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
											echo '	<FECHA> '.$recDSF6M->fecha.' </FECHA>&#013;&#010;';	

											if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row) > 1 && $contadorRecC4 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row)) echo '&#013;&#010;';								
										}
										echo '</DEUDA_SISTEMA_FINANCIERO_6M>&#013;&#010;&#013;&#010;';	
										echo '<DEUDA_SISTEMA_FINANCIERO_12M>&#013;&#010;';
										$contadorRecC5 = 0;
										foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row as $recDSF12M) 
										{								
											$contadorRecC5++;
											echo '	<ENTIDAD> '.$recDSF12M->entidad.' </ENTIDAD>&#013;&#010;';
											echo '	<SITUACION> '.$recDSF12M->situacion.' </SITUACION>&#013;&#010;';
											echo '	<MONTO_MAXIMO> '.$recDSF12M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
											echo '	<DEUDA_ACTUAL> '.$recDSF12M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
											echo '	<FECHA> '.$recDSF12M->fecha.' </FECHA>&#013;&#010;';	

											if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row) > 1 && $contadorRecC5 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row)) echo '&#013;&#010;';								
										}
										echo '</DEUDA_SISTEMA_FINANCIERO_12M>&#013;&#010;&#013;&#010;';	
										echo '<DEUDA_SISTEMA_FINANCIERO_24M>&#013;&#010;';
										$contadorRecC6 = 0;
										foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row as $recDSF24M) 
										{								
											$contadorRecC6++;
											echo '	<ENTIDAD> '.$recDSF24M->entidad.' </ENTIDAD>&#013;&#010;';
											echo '	<SITUACION> '.$recDSF24M->situacion.' </SITUACION>&#013;&#010;';
											echo '	<MONTO_MAXIMO> '.$recDSF24M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
											echo '	<DEUDA_ACTUAL> '.$recDSF24M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
											echo '	<FECHA> '.$recDSF24M->fecha.' </FECHA>&#013;&#010;';

											if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row) > 1 && $contadorRecC6 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row)) echo '&#013;&#010;';								
										}
										echo '</DEUDA_SISTEMA_FINANCIERO_24M>&#013;&#010;&#013;&#010;';	
										echo '<RELACION_DEPENDENCIA>&#013;&#010;';
										$contadorRecC7 = 0;
										foreach ($estado_fin_cli->RELACION_DEPENDENCIA[0]->row as $recRDC) 
										{								
											$contadorRecC7++;
											echo '	<ULTIMO_PERIODO> '.$recRDC->ult_periodo.' </ULTIMO_PERIODO>&#013;&#010;';
											echo '	<ALTA_ULTIMO_TRABAJO> '.$recRDC->alta_trabajo_ultimo.' </ALTA_ULTIMO_TRABAJO>&#013;&#010;';
											echo '	<CUIT> '.$recRDC->cuit.' </CUIT>&#013;&#010;';
											echo '	<RAZON_SOCIAL> '.$recRDC->razon_social.' </RAZON_SOCIAL>&#013;&#010;';
											echo '	<SITUACION_LABORAL_ACTUAL> '.$recRDC->situacion_laboral_actual.' </SITUACION_LABORAL_ACTUAL>&#013;&#010;';

											if(count($estado_fin_cli->RELACION_DEPENDENCIA[0]->row) > 1 && $contadorRecC7 < count($estado_fin_cli->RELACION_DEPENDENCIA[0]->row)) echo '&#013;&#010;';								
										}
										echo '</RELACION_DEPENDENCIA>&#013;&#010;&#013;&#010;';
										echo '<CONSTANCIA_DE_INSCRIPCION_AFIP>&#013;&#010;';
										$contadorRecC8 = 0;
										foreach ($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row as $recCIAC) 
										{								
											$contadorRecC8++;
											echo '	<CUIT> '.$recCIAC->cuit.' </CUIT>&#013;&#010;';
											echo '	<DENOMINACION> '.$recCIAC->denominacion.' </DENOMINACION>&#013;&#010;';
											echo '	<FECHA_CONTRATO_SOCIAL> '.$recCIAC->fecha_contrato_social.' </FECHA_CONTRATO_SOCIAL>&#013;&#010;';
											echo '	<MES_CIERRE> '.$recCIAC->mes_cierre.' </MES_CIERRE>&#013;&#010;';
											echo '	<CATEGORIA> '.$recCIAC->categoria.' </CATEGORIA>&#013;&#010;';	
											echo '	<FECHA_INICIO_ACTIVIDADES> '.$recCIAC->fecha_inicio_actividades.' </FECHA_INICIO_ACTIVIDADES>&#013;&#010;';
											echo '	<DESCRIPCION> '.$recCIAC->descripcion.' </DESCRIPCION>&#013;&#010;';
											echo '	<DIRECCION> '.$recCIAC->direccion.' </DIRECCION>&#013;&#010;';
											echo '	<LOCALIDAD> '.$recCIAC->localidad.' </LOCALIDAD>&#013;&#010;';
											echo '	<PROVINCIA> '.$recCIAC->provincia.' </PROVINCIA>&#013;&#010;';	
											echo '	<CODIGO_POSTAL> '.$recCIAC->cp.' </CODIGO_POSTAL>&#013;&#010;';
											echo '	<ANTIGUEDAD_MESES> '.$recCIAC->antiguedad_meses.' </ANTIGUEDAD_MESES>&#013;&#010;';	

											if(count($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row) > 1 && $contadorRecC8 < count($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row)) echo '&#013;&#010;';								
										}
										echo '</CONSTANCIA_DE_INSCRIPCION_AFIP>&#013;&#010;&#013;&#010;';
										echo '<TIPO_ACTIVIDAD>&#013;&#010;';
										$contadorRecC9 = 0;
										foreach ($estado_fin_cli->Tipo_Actividad[0]->row as $recTAC) 
										{								
											$contadorRecC9++;
											echo '	<TIPO_ACTIVIDAD> '.$recTAC->tipo_actividad.' </TIPO_ACTIVIDAD>&#013;&#010;';

											if(count($estado_fin_cli->Tipo_Actividad[0]->row) > 1 && $contadorRecC9 < count($estado_fin_cli->Tipo_Actividad[0]->row)) echo '&#013;&#010;';								
										}
										echo '</TIPO_ACTIVIDAD>&#013;&#010;&#013;&#010;';				
										echo '<POSEE_MOVILES>&#013;&#010;';
										$contadorRecC10 = 0;
										foreach ($estado_fin_cli->Moviles_posee[0]->row as $recPMC) 
										{								
											$contadorRecC10++;
											echo '	<POSEE_AUTOS> '.$recPMC->posee_autos.' </POSEE_AUTOS>&#013;&#010;';
											echo '	<CANTIDAD_AUTOS> '.$recPMC->cantidad_autos.' </CANTIDAD_AUTOS>&#013;&#010;';

											if(count($estado_fin_cli->Moviles_posee[0]->row) > 1 && $contadorRecC10 < count($estado_fin_cli->Moviles_posee[0]->row)) echo '&#013;&#010;';									
										}
										echo '</POSEE_MOVILES>&#013;&#010;&#013;&#010;';					
										echo '<INFO_LABORAL_HISTORICA>&#013;&#010;';
										$contadorRecC11 = 0;
										foreach ($estado_fin_cli->inf_lab_hist_fecha_[0]->row as $recILHC) 
										{								
											$contadorRecC11++;
											echo '	<CUIT> '.$recILHC->inf_lab_cuit_.' </CUIT>&#013;&#010;';
											echo '	<RAZON_SOCIAL> '.$recILHC->inf_lab_razon_.' </RAZON_SOCIAL>&#013;&#010;';
											echo '	<RELACION_DESDE> '.$recILHC->relacion_desde_.' </RELACION_DESDE>&#013;&#010;';
											echo '	<RELACION_HASTA> '.$recILHC->relacion_hasta_.' </RELACION_HASTA>&#013;&#010;';

											if(count($estado_fin_cli->inf_lab_hist_fecha_[0]->row) > 1 && $contadorRecC11 < count($estado_fin_cli->inf_lab_hist_fecha_[0]->row)) echo '&#013;&#010;';								
										}
										echo '</INFO_LABORAL_HISTORICA>&#013;&#010;&#013;&#010;';								
										echo '<POSEE_JUICIOS_EMBARGO>&#013;&#010;';
										$contadorRecC12 = 0;
										foreach ($estado_fin_cli->Juicios_Posee_Embargo[0]->row as $recPJEC) 
										{								
											$contadorRecC12++;
											echo '	<TIPO_JUICIO> '.$recPJEC->juicios_posee_tipo.' </TIPO_JUICIO>&#013;&#010;';

											if(count($estado_fin_cli->Juicios_Posee_Embargo[0]->row) > 1 && $contadorRecC12 < count($estado_fin_cli->Juicios_Posee_Embargo[0]->row)) echo '&#013;&#010;';								
										}
										echo '</POSEE_JUICIOS_EMBARGO>&#013;&#010;&#013;&#010;';	
										echo '<POSEE_JUICIOS_INHABILITACION>&#013;&#010;';
										$contadorRecC13 = 0;
										foreach ($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row as $recPJIC) 
										{								
											$contadorRecC13++;
											echo '	<TIPO_JUICIO> '.$recPJIC->juicios_posee_tipo.' </TIPO_JUICIO>&#013;&#010;';

											if(count($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row) > 1 && $contadorRecC13 < count($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row)) echo '&#013;&#010;';								
										}
										echo '</POSEE_JUICIOS_INHABILITACION>&#013;&#010;&#013;&#010;';	
										echo '<JUBILADO>&#013;&#010;';
										$contadorRecC14 = 0;
										foreach ($estado_fin_cli->JUBILADO[0]->row as $recJC) 
										{								
											$contadorRecC14++;
											echo '	<NUMERO_BENEFICIARIO> '.$recJC->numero_beneficiario.' </NUMERO_BENEFICIARIO>&#013;&#010;';
											echo '	<CLASE_BENEFICIO> '.$recJC->beneficio_clase.' </CLASE_BENEFICIO>&#013;&#010;';
											echo '	<ESTADO> '.$recJC->estado.' </ESTADO>&#013;&#010;';

											if(count($estado_fin_cli->JUBILADO[0]->row) > 1 && $contadorRecC14 < count($estado_fin_cli->JUBILADO[0]->row)) echo '&#013;&#010;';								
										}
										echo '</JUBILADO>&#013;&#010;&#013;&#010;';	
										echo '<VALIDA_MORAS>&#013;&#010;';
										$contadorRecC15 = 0;
										foreach ($estado_fin_cli->Moras_Valida[0]->row as $recMVC) 
										{								
											$contadorRecC15++;
											echo '	<VALIDA> '.$recMVC->valida.' </VALIDA>&#013;&#010;';
											echo '	<ENTIDADES> '.$recMVC->entidades.' </ENTIDADES>&#013;&#010;';
											echo '	<MAX_ATRASO> '.$recMVC->max_atraso.' </MAX_ATRASO>&#013;&#010;';

											if(count($estado_fin_cli->Moras_Valida[0]->row) > 1 && $contadorRecC15 < count($estado_fin_cli->Moras_Valida[0]->row)) echo '&#013;&#010;';								
										}
										echo '</VALIDA_MORAS>&#013;&#010;&#013;&#010;';	
										echo '<SCORE>&#013;&#010;';
										$contadorRecC16 = 0;
										foreach ($estado_fin_cli->Score[0]->row as $recSCC) 
										{								
											$contadorRecC16++;
											echo '	<SCORE> '.$recSCC->score.' </SCORE>&#013;&#010;';

											if(count($estado_fin_cli->Score[0]->row) > 1 && $contadorRecC16 < count($estado_fin_cli->Score[0]->row)) echo '&#013;&#010;';									
										}
										echo '</SCORE>&#013;&#010;&#013;&#010;';							
										echo '						</textarea>';
										echo '					</div>';		
										echo '				</div>';									
										echo '				<div class="form-group form-inline"><hr />';
										echo '					<label class="control-label" for="usuariosupervisorn3">'.translate('Lbl_User_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
										echo '					<div class="form-group" id="usuariosupervisorn3">';
										echo '						<input title="'.translate('Msg_User_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="usuariosupervisorn3i" name="usuariosupervisorn3i" type="text" maxlength="50" />';
										echo '					</div>';
										echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="passwordsupervisorn3">'.translate('Lbl_Password_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
										echo '					<div class="form-group" id="passwordsupervisorn3">';
										echo '						<input title="'.translate('Msg_Password_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="passwordsupervisorn3i" name="passwordsupervisorn3i" type="password" maxlength="128" />';
										echo '					</div>';		
										echo '				</div>';
										$estado_activa_supervisor = 1;
									}
									else
									{
										$estado_fin_cli = new SimpleXMLElement($resultado_finan_cli_final);
										if(empty($tokenECF)) $tokenECF = $tokenVECC;
										if(empty($tokenECRC)) $tokenECRC = $tokenVS;
										echo translate('Msg_Validation_Credit_Status_Client_OK',$GLOBALS['lang']).'=::=::=::'.$tokenECF.'=:::=:::'.$tokenECRC.'=:=:=:';
										echo '<div class="panel-group">';				
										echo '	<div class="panel panel-default">';
										echo '		<div id="panel-title-header" class="panel-heading">';
										echo '			<h3 class="panel-title">'.translate('Lbl_Result_Financial_Statement_Client',$GLOBALS['lang']).'</h3>';
										echo ' 		</div>';
										echo '		<div class="panel-body">';
										echo '			<form id="formulariocefc" role="form" onsubmit="buscarTextoEstadoFinanciero(); return false;">';		
										echo '				<div class="form-group form-inline">';
										echo '					<div class="form-group" id="buscartextoestadocrediticiocliente">';
										echo '						<input class="form-control input-sm" id="buscartextoestadocrediticioclientei" name="buscartextoestadocrediticioclientei" type="text" maxlength="150" />';
										echo '						&nbsp;<button type="button" class="btn" id="btnBuscarEstadoF" name="btnBuscarEstadoF" title="'.translate('Lbl_Search_Text_Statement_Client',$GLOBALS['lang']).'" onclick="buscarTextoEstadoFinanciero();"><i class="fas fa-search"></i></button>';									
										echo '					</div>';
										echo '				</div>';
										echo '				<div class="form-group form-inline">';					
										echo '					<div class="form-group" id="resultadoestadofinancierocliente">';
										echo '						<textarea rows="3" cols="67" class="form-control input-sm" id="resultadoestadofinancieroclientei" name="resultadoestadofinancieroclientei">';
										echo '<EXISTENCIA_FISICA>&#013;&#010;';
										$contadorRecC1 = 0;
										foreach ($estado_fin_cli->Existencia_Fisica_Resu[0]->row as $recEFC) 
										{
											$contadorRecC1++;
											echo '	<APELLIDO_Y_NOMBRE> '.$recEFC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
											echo '	<CUIT_CUIL> '.$recEFC->cdi.' </CUIT_CUIL>&#013;&#010;';								
											echo '	<FECHA_NACIMIENTO> '.$recEFC->fecha_nacimiento.' </FECHA_NACIMIENTO>&#013;&#010;';							
											echo '	<DIRECCION_CALLE> '.$recEFC->direc_calle.' </DIRECCION_CALLE>&#013;&#010;';							
											echo '	<LOCALIDAD> '.$recEFC->localidad.' </LOCALIDAD>&#013;&#010;';							
											echo '	<CODIGO_POSTAL> '.$recEFC->codigo_postal.' </CODIGO_POSTAL>&#013;&#010;';							
											echo '	<PROVINCIA> '.$recEFC->provincia.' </PROVINCIA>&#013;&#010;';							
											echo '	<TIPO_DOCUMENTO> '.$recEFC->t_docu.' </TIPO_DOCUMENTO>&#013;&#010;';							
											echo '	<FALLECIDO> '.$recEFC->fallecido.' </FALLECIDO>&#013;&#010;';
											
											if(count($estado_fin_cli->Existencia_Fisica_Resu[0]->row) > 1 && $contadorRecC1 < count($estado_fin_cli->Existencia_Fisica_Resu[0]->row)) echo '&#013;&#010;';
										}							
										echo '</EXISTENCIA_FISICA>&#013;&#010;&#013;&#010;';
										echo '<PREDICTOR_INGRESOS>&#013;&#010;';
										$contadorRecC2 = 0;
										foreach ($estado_fin_cli->predictor_ingreso[0]->row as $recPIC) 
										{							
											$contadorRecC2++;
											echo '	<PREDICTOR> '.$recPIC->predictor_ingresos.' </PREDICTOR>&#013;&#010;';
											
											if(count($estado_fin_cli->predictor_ingreso[0]->row) > 1 && $contadorRecC2 < count($estado_fin_cli->predictor_ingreso[0]->row)) echo '&#013;&#010;';
										}
										echo '</PREDICTOR_INGRESOS>&#013;&#010;&#013;&#010;';
										echo '<TIENE_JUI_QUI_EJEC>&#013;&#010;';
										$contadorRecC3 = 0;
										foreach ($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row as $recTJQEC) 
										{								
											$contadorRecC3++;
											echo '	<TIENE_JUICIO> '.$recTJQEC->tiene_juicio.' </TIENE_JUICIO>&#013;&#010;';
											
											if(count($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row) > 1 && $contadorRecC3 < count($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row)) echo '&#013;&#010;';
										}
										echo '</TIENE_JUI_QUI_EJEC>&#013;&#010;&#013;&#010;';
										echo '<DEUDA_SISTEMA_FINANCIERO_6M>&#013;&#010;';
										$contadorRecC4 = 0;
										foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row as $recDSF6M) 
										{								
											$contadorRecC4++;
											echo '	<ENTIDAD> '.$recDSF6M->entidad.' </ENTIDAD>&#013;&#010;';
											echo '	<SITUACION> '.$recDSF6M->situacion.' </SITUACION>&#013;&#010;';
											echo '	<MONTO_MAXIMO> '.$recDSF6M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
											echo '	<DEUDA_ACTUAL> '.$recDSF6M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
											echo '	<FECHA> '.$recDSF6M->fecha.' </FECHA>&#013;&#010;';	

											if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row) > 1 && $contadorRecC4 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row)) echo '&#013;&#010;';								
										}
										echo '</DEUDA_SISTEMA_FINANCIERO_6M>&#013;&#010;&#013;&#010;';	
										echo '<DEUDA_SISTEMA_FINANCIERO_12M>&#013;&#010;';
										$contadorRecC5 = 0;
										foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row as $recDSF12M) 
										{								
											$contadorRecC5++;
											echo '	<ENTIDAD> '.$recDSF12M->entidad.' </ENTIDAD>&#013;&#010;';
											echo '	<SITUACION> '.$recDSF12M->situacion.' </SITUACION>&#013;&#010;';
											echo '	<MONTO_MAXIMO> '.$recDSF12M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
											echo '	<DEUDA_ACTUAL> '.$recDSF12M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
											echo '	<FECHA> '.$recDSF12M->fecha.' </FECHA>&#013;&#010;';	

											if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row) > 1 && $contadorRecC5 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row)) echo '&#013;&#010;';								
										}
										echo '</DEUDA_SISTEMA_FINANCIERO_12M>&#013;&#010;&#013;&#010;';	
										echo '<DEUDA_SISTEMA_FINANCIERO_24M>&#013;&#010;';
										$contadorRecC6 = 0;
										foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row as $recDSF24M) 
										{								
											$contadorRecC6++;
											echo '	<ENTIDAD> '.$recDSF24M->entidad.' </ENTIDAD>&#013;&#010;';
											echo '	<SITUACION> '.$recDSF24M->situacion.' </SITUACION>&#013;&#010;';
											echo '	<MONTO_MAXIMO> '.$recDSF24M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
											echo '	<DEUDA_ACTUAL> '.$recDSF24M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
											echo '	<FECHA> '.$recDSF24M->fecha.' </FECHA>&#013;&#010;';

											if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row) > 1 && $contadorRecC6 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row)) echo '&#013;&#010;';								
										}
										echo '</DEUDA_SISTEMA_FINANCIERO_24M>&#013;&#010;&#013;&#010;';	
										echo '<RELACION_DEPENDENCIA>&#013;&#010;';
										$contadorRecC7 = 0;
										foreach ($estado_fin_cli->RELACION_DEPENDENCIA[0]->row as $recRDC) 
										{								
											$contadorRecC7++;
											echo '	<ULTIMO_PERIODO> '.$recRDC->ult_periodo.' </ULTIMO_PERIODO>&#013;&#010;';
											echo '	<ALTA_ULTIMO_TRABAJO> '.$recRDC->alta_trabajo_ultimo.' </ALTA_ULTIMO_TRABAJO>&#013;&#010;';
											echo '	<CUIT> '.$recRDC->cuit.' </CUIT>&#013;&#010;';
											echo '	<RAZON_SOCIAL> '.$recRDC->razon_social.' </RAZON_SOCIAL>&#013;&#010;';
											echo '	<SITUACION_LABORAL_ACTUAL> '.$recRDC->situacion_laboral_actual.' </SITUACION_LABORAL_ACTUAL>&#013;&#010;';

											if(count($estado_fin_cli->RELACION_DEPENDENCIA[0]->row) > 1 && $contadorRecC7 < count($estado_fin_cli->RELACION_DEPENDENCIA[0]->row)) echo '&#013;&#010;';								
										}
										echo '</RELACION_DEPENDENCIA>&#013;&#010;&#013;&#010;';
										echo '<CONSTANCIA_DE_INSCRIPCION_AFIP>&#013;&#010;';
										$contadorRecC8 = 0;
										foreach ($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row as $recCIAC) 
										{								
											$contadorRecC8++;
											echo '	<CUIT> '.$recCIAC->cuit.' </CUIT>&#013;&#010;';
											echo '	<DENOMINACION> '.$recCIAC->denominacion.' </DENOMINACION>&#013;&#010;';
											echo '	<FECHA_CONTRATO_SOCIAL> '.$recCIAC->fecha_contrato_social.' </FECHA_CONTRATO_SOCIAL>&#013;&#010;';
											echo '	<MES_CIERRE> '.$recCIAC->mes_cierre.' </MES_CIERRE>&#013;&#010;';
											echo '	<CATEGORIA> '.$recCIAC->categoria.' </CATEGORIA>&#013;&#010;';	
											echo '	<FECHA_INICIO_ACTIVIDADES> '.$recCIAC->fecha_inicio_actividades.' </FECHA_INICIO_ACTIVIDADES>&#013;&#010;';
											echo '	<DESCRIPCION> '.$recCIAC->descripcion.' </DESCRIPCION>&#013;&#010;';
											echo '	<DIRECCION> '.$recCIAC->direccion.' </DIRECCION>&#013;&#010;';
											echo '	<LOCALIDAD> '.$recCIAC->localidad.' </LOCALIDAD>&#013;&#010;';
											echo '	<PROVINCIA> '.$recCIAC->provincia.' </PROVINCIA>&#013;&#010;';	
											echo '	<CODIGO_POSTAL> '.$recCIAC->cp.' </CODIGO_POSTAL>&#013;&#010;';
											echo '	<ANTIGUEDAD_MESES> '.$recCIAC->antiguedad_meses.' </ANTIGUEDAD_MESES>&#013;&#010;';	

											if(count($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row) > 1 && $contadorRecC8 < count($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row)) echo '&#013;&#010;';								
										}
										echo '</CONSTANCIA_DE_INSCRIPCION_AFIP>&#013;&#010;&#013;&#010;';
										echo '<TIPO_ACTIVIDAD>&#013;&#010;';
										$contadorRecC9 = 0;
										foreach ($estado_fin_cli->Tipo_Actividad[0]->row as $recTAC) 
										{								
											$contadorRecC9++;
											echo '	<TIPO_ACTIVIDAD> '.$recTAC->tipo_actividad.' </TIPO_ACTIVIDAD>&#013;&#010;';

											if(count($estado_fin_cli->Tipo_Actividad[0]->row) > 1 && $contadorRecC9 < count($estado_fin_cli->Tipo_Actividad[0]->row)) echo '&#013;&#010;';								
										}
										echo '</TIPO_ACTIVIDAD>&#013;&#010;&#013;&#010;';				
										echo '<POSEE_MOVILES>&#013;&#010;';
										$contadorRecC10 = 0;
										foreach ($estado_fin_cli->Moviles_posee[0]->row as $recPMC) 
										{								
											$contadorRecC10++;
											echo '	<POSEE_AUTOS> '.$recPMC->posee_autos.' </POSEE_AUTOS>&#013;&#010;';
											echo '	<CANTIDAD_AUTOS> '.$recPMC->cantidad_autos.' </CANTIDAD_AUTOS>&#013;&#010;';

											if(count($estado_fin_cli->Moviles_posee[0]->row) > 1 && $contadorRecC10 < count($estado_fin_cli->Moviles_posee[0]->row)) echo '&#013;&#010;';									
										}
										echo '</POSEE_MOVILES>&#013;&#010;&#013;&#010;';					
										echo '<INFO_LABORAL_HISTORICA>&#013;&#010;';
										$contadorRecC11 = 0;
										foreach ($estado_fin_cli->inf_lab_hist_fecha_[0]->row as $recILHC) 
										{								
											$contadorRecC11++;
											echo '	<CUIT> '.$recILHC->inf_lab_cuit_.' </CUIT>&#013;&#010;';
											echo '	<RAZON_SOCIAL> '.$recILHC->inf_lab_razon_.' </RAZON_SOCIAL>&#013;&#010;';
											echo '	<RELACION_DESDE> '.$recILHC->relacion_desde_.' </RELACION_DESDE>&#013;&#010;';
											echo '	<RELACION_HASTA> '.$recILHC->relacion_hasta_.' </RELACION_HASTA>&#013;&#010;';

											if(count($estado_fin_cli->inf_lab_hist_fecha_[0]->row) > 1 && $contadorRecC11 < count($estado_fin_cli->inf_lab_hist_fecha_[0]->row)) echo '&#013;&#010;';								
										}
										echo '</INFO_LABORAL_HISTORICA>&#013;&#010;&#013;&#010;';								
										echo '<POSEE_JUICIOS_EMBARGO>&#013;&#010;';
										$contadorRecC12 = 0;
										foreach ($estado_fin_cli->Juicios_Posee_Embargo[0]->row as $recPJEC) 
										{								
											$contadorRecC12++;
											echo '	<TIPO_JUICIO> '.$recPJEC->juicios_posee_tipo.' </TIPO_JUICIO>&#013;&#010;';

											if(count($estado_fin_cli->Juicios_Posee_Embargo[0]->row) > 1 && $contadorRecC12 < count($estado_fin_cli->Juicios_Posee_Embargo[0]->row)) echo '&#013;&#010;';								
										}
										echo '</POSEE_JUICIOS_EMBARGO>&#013;&#010;&#013;&#010;';	
										echo '<POSEE_JUICIOS_INHABILITACION>&#013;&#010;';
										$contadorRecC13 = 0;
										foreach ($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row as $recPJIC) 
										{								
											$contadorRecC13++;
											echo '	<TIPO_JUICIO> '.$recPJIC->juicios_posee_tipo.' </TIPO_JUICIO>&#013;&#010;';

											if(count($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row) > 1 && $contadorRecC13 < count($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row)) echo '&#013;&#010;';								
										}
										echo '</POSEE_JUICIOS_INHABILITACION>&#013;&#010;&#013;&#010;';	
										echo '<JUBILADO>&#013;&#010;';
										$contadorRecC14 = 0;
										foreach ($estado_fin_cli->JUBILADO[0]->row as $recJC) 
										{								
											$contadorRecC14++;
											echo '	<NUMERO_BENEFICIARIO> '.$recJC->numero_beneficiario.' </NUMERO_BENEFICIARIO>&#013;&#010;';
											echo '	<CLASE_BENEFICIO> '.$recJC->beneficio_clase.' </CLASE_BENEFICIO>&#013;&#010;';
											echo '	<ESTADO> '.$recJC->estado.' </ESTADO>&#013;&#010;';

											if(count($estado_fin_cli->JUBILADO[0]->row) > 1 && $contadorRecC14 < count($estado_fin_cli->JUBILADO[0]->row)) echo '&#013;&#010;';								
										}
										echo '</JUBILADO>&#013;&#010;&#013;&#010;';	
										echo '<VALIDA_MORAS>&#013;&#010;';
										$contadorRecC15 = 0;
										foreach ($estado_fin_cli->Moras_Valida[0]->row as $recMVC) 
										{								
											$contadorRecC15++;
											echo '	<VALIDA> '.$recMVC->valida.' </VALIDA>&#013;&#010;';
											echo '	<ENTIDADES> '.$recMVC->entidades.' </ENTIDADES>&#013;&#010;';
											echo '	<MAX_ATRASO> '.$recMVC->max_atraso.' </MAX_ATRASO>&#013;&#010;';

											if(count($estado_fin_cli->Moras_Valida[0]->row) > 1 && $contadorRecC15 < count($estado_fin_cli->Moras_Valida[0]->row)) echo '&#013;&#010;';								
										}
										echo '</VALIDA_MORAS>&#013;&#010;&#013;&#010;';	
										echo '<SCORE>&#013;&#010;';
										$contadorRecC16 = 0;
										foreach ($estado_fin_cli->Score[0]->row as $recSCC) 
										{								
											$contadorRecC16++;
											echo '	<SCORE> '.$recSCC->score.' </SCORE>&#013;&#010;';

											if(count($estado_fin_cli->Score[0]->row) > 1 && $contadorRecC16 < count($estado_fin_cli->Score[0]->row)) echo '&#013;&#010;';									
										}
										echo '</SCORE>&#013;&#010;&#013;&#010;';																	
										echo '						</textarea>';
										echo '					</div>';		
										echo '				</div>';									
									}
									
									$stmt44->free_result();
									$stmt44->close();
								}
								else
								{
									echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
									return;
								}
							}
						}
						else
						{
							$estado_fin_cli = new SimpleXMLElement($resultado_finan_cli_final);
							if(empty($tokenECF)) $tokenECF = $tokenVECC;
							if(empty($tokenECRC)) $tokenECRC = $tokenVS;
							echo translate('Msg_Validation_Credit_Status_Client_OK',$GLOBALS['lang']).'=::=::=::'.$tokenECF.'=:::=:::'.$tokenECRC.'=:=:=:';
							echo '<div class="panel-group">';				
							echo '	<div class="panel panel-default">';
							echo '		<div id="panel-title-header" class="panel-heading">';
							echo '			<h3 class="panel-title">'.translate('Lbl_Result_Financial_Statement_Client',$GLOBALS['lang']).'</h3>';
							echo ' 		</div>';
							echo '		<div class="panel-body">';
							echo '			<form id="formulariocefc" role="form" onsubmit="buscarTextoEstadoFinanciero(); return false;">';		
							echo '				<div class="form-group form-inline">';
							echo '					<div class="form-group" id="buscartextoestadocrediticiocliente">';
							echo '						<input class="form-control input-sm" id="buscartextoestadocrediticioclientei" name="buscartextoestadocrediticioclientei" type="text" maxlength="150" />';
							echo '						&nbsp;<button type="button" class="btn" id="btnBuscarEstadoF" name="btnBuscarEstadoF" title="'.translate('Lbl_Search_Text_Statement_Client',$GLOBALS['lang']).'" onclick="buscarTextoEstadoFinanciero();"><i class="fas fa-search"></i></button>';									
							echo '					</div>';
							echo '				</div>';
							echo '				<div class="form-group form-inline">';					
							echo '					<div class="form-group" id="resultadoestadofinancierocliente">';
							echo '						<textarea rows="3" cols="67" class="form-control input-sm" id="resultadoestadofinancieroclientei" name="resultadoestadofinancieroclientei">';
							echo '<EXISTENCIA_FISICA>&#013;&#010;';
							$contadorRecC1 = 0;
							foreach ($estado_fin_cli->Existencia_Fisica_Resu[0]->row as $recEFC) 
							{
								$contadorRecC1++;
								echo '	<APELLIDO_Y_NOMBRE> '.$recEFC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
								echo '	<CUIT_CUIL> '.$recEFC->cdi.' </CUIT_CUIL>&#013;&#010;';								
								echo '	<FECHA_NACIMIENTO> '.$recEFC->fecha_nacimiento.' </FECHA_NACIMIENTO>&#013;&#010;';							
								echo '	<DIRECCION_CALLE> '.$recEFC->direc_calle.' </DIRECCION_CALLE>&#013;&#010;';							
								echo '	<LOCALIDAD> '.$recEFC->localidad.' </LOCALIDAD>&#013;&#010;';							
								echo '	<CODIGO_POSTAL> '.$recEFC->codigo_postal.' </CODIGO_POSTAL>&#013;&#010;';							
								echo '	<PROVINCIA> '.$recEFC->provincia.' </PROVINCIA>&#013;&#010;';							
								echo '	<TIPO_DOCUMENTO> '.$recEFC->t_docu.' </TIPO_DOCUMENTO>&#013;&#010;';							
								echo '	<FALLECIDO> '.$recEFC->fallecido.' </FALLECIDO>&#013;&#010;';
								
								if(count($estado_fin_cli->Existencia_Fisica_Resu[0]->row) > 1 && $contadorRecC1 < count($estado_fin_cli->Existencia_Fisica_Resu[0]->row)) echo '&#013;&#010;';
							}							
							echo '</EXISTENCIA_FISICA>&#013;&#010;&#013;&#010;';
							echo '<PREDICTOR_INGRESOS>&#013;&#010;';
							$contadorRecC2 = 0;
							foreach ($estado_fin_cli->predictor_ingreso[0]->row as $recPIC) 
							{							
								$contadorRecC2++;
								echo '	<PREDICTOR> '.$recPIC->predictor_ingresos.' </PREDICTOR>&#013;&#010;';
								
								if(count($estado_fin_cli->predictor_ingreso[0]->row) > 1 && $contadorRecC2 < count($estado_fin_cli->predictor_ingreso[0]->row)) echo '&#013;&#010;';
							}
							echo '</PREDICTOR_INGRESOS>&#013;&#010;&#013;&#010;';
							echo '<TIENE_JUI_QUI_EJEC>&#013;&#010;';
							$contadorRecC3 = 0;
							foreach ($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row as $recTJQEC) 
							{								
								$contadorRecC3++;
								echo '	<TIENE_JUICIO> '.$recTJQEC->tiene_juicio.' </TIENE_JUICIO>&#013;&#010;';
								
								if(count($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row) > 1 && $contadorRecC3 < count($estado_fin_cli->TIENE_JUI_QUI_EJEC[0]->row)) echo '&#013;&#010;';
							}
							echo '</TIENE_JUI_QUI_EJEC>&#013;&#010;&#013;&#010;';
							echo '<DEUDA_SISTEMA_FINANCIERO_6M>&#013;&#010;';
							$contadorRecC4 = 0;
							foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row as $recDSF6M) 
							{								
								$contadorRecC4++;
								echo '	<ENTIDAD> '.$recDSF6M->entidad.' </ENTIDAD>&#013;&#010;';
								echo '	<SITUACION> '.$recDSF6M->situacion.' </SITUACION>&#013;&#010;';
								echo '	<MONTO_MAXIMO> '.$recDSF6M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
								echo '	<DEUDA_ACTUAL> '.$recDSF6M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
								echo '	<FECHA> '.$recDSF6M->fecha.' </FECHA>&#013;&#010;';	

								if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row) > 1 && $contadorRecC4 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_6M[0]->row)) echo '&#013;&#010;';								
							}
							echo '</DEUDA_SISTEMA_FINANCIERO_6M>&#013;&#010;&#013;&#010;';	
							echo '<DEUDA_SISTEMA_FINANCIERO_12M>&#013;&#010;';
							$contadorRecC5 = 0;
							foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row as $recDSF12M) 
							{								
								$contadorRecC5++;
								echo '	<ENTIDAD> '.$recDSF12M->entidad.' </ENTIDAD>&#013;&#010;';
								echo '	<SITUACION> '.$recDSF12M->situacion.' </SITUACION>&#013;&#010;';
								echo '	<MONTO_MAXIMO> '.$recDSF12M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
								echo '	<DEUDA_ACTUAL> '.$recDSF12M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
								echo '	<FECHA> '.$recDSF12M->fecha.' </FECHA>&#013;&#010;';	

								if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row) > 1 && $contadorRecC5 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_12M[0]->row)) echo '&#013;&#010;';								
							}
							echo '</DEUDA_SISTEMA_FINANCIERO_12M>&#013;&#010;&#013;&#010;';	
							echo '<DEUDA_SISTEMA_FINANCIERO_24M>&#013;&#010;';
							$contadorRecC6 = 0;
							foreach ($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row as $recDSF24M) 
							{								
								$contadorRecC6++;
								echo '	<ENTIDAD> '.$recDSF24M->entidad.' </ENTIDAD>&#013;&#010;';
								echo '	<SITUACION> '.$recDSF24M->situacion.' </SITUACION>&#013;&#010;';
								echo '	<MONTO_MAXIMO> '.$recDSF24M->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
								echo '	<DEUDA_ACTUAL> '.$recDSF24M->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
								echo '	<FECHA> '.$recDSF24M->fecha.' </FECHA>&#013;&#010;';

								if(count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row) > 1 && $contadorRecC6 < count($estado_fin_cli->DEUDA_SISTEMA_FINANCIERO_24M[0]->row)) echo '&#013;&#010;';								
							}
							echo '</DEUDA_SISTEMA_FINANCIERO_24M>&#013;&#010;&#013;&#010;';	
							echo '<RELACION_DEPENDENCIA>&#013;&#010;';
							$contadorRecC7 = 0;
							foreach ($estado_fin_cli->RELACION_DEPENDENCIA[0]->row as $recRDC) 
							{								
								$contadorRecC7++;
								echo '	<ULTIMO_PERIODO> '.$recRDC->ult_periodo.' </ULTIMO_PERIODO>&#013;&#010;';
								echo '	<ALTA_ULTIMO_TRABAJO> '.$recRDC->alta_trabajo_ultimo.' </ALTA_ULTIMO_TRABAJO>&#013;&#010;';
								echo '	<CUIT> '.$recRDC->cuit.' </CUIT>&#013;&#010;';
								echo '	<RAZON_SOCIAL> '.$recRDC->razon_social.' </RAZON_SOCIAL>&#013;&#010;';
								echo '	<SITUACION_LABORAL_ACTUAL> '.$recRDC->situacion_laboral_actual.' </SITUACION_LABORAL_ACTUAL>&#013;&#010;';

								if(count($estado_fin_cli->RELACION_DEPENDENCIA[0]->row) > 1 && $contadorRecC7 < count($estado_fin_cli->RELACION_DEPENDENCIA[0]->row)) echo '&#013;&#010;';								
							}
							echo '</RELACION_DEPENDENCIA>&#013;&#010;&#013;&#010;';
							echo '<CONSTANCIA_DE_INSCRIPCION_AFIP>&#013;&#010;';
							$contadorRecC8 = 0;
							foreach ($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row as $recCIAC) 
							{								
								$contadorRecC8++;
								echo '	<CUIT> '.$recCIAC->cuit.' </CUIT>&#013;&#010;';
								echo '	<DENOMINACION> '.$recCIAC->denominacion.' </DENOMINACION>&#013;&#010;';
								echo '	<FECHA_CONTRATO_SOCIAL> '.$recCIAC->fecha_contrato_social.' </FECHA_CONTRATO_SOCIAL>&#013;&#010;';
								echo '	<MES_CIERRE> '.$recCIAC->mes_cierre.' </MES_CIERRE>&#013;&#010;';
								echo '	<CATEGORIA> '.$recCIAC->categoria.' </CATEGORIA>&#013;&#010;';	
								echo '	<FECHA_INICIO_ACTIVIDADES> '.$recCIAC->fecha_inicio_actividades.' </FECHA_INICIO_ACTIVIDADES>&#013;&#010;';
								echo '	<DESCRIPCION> '.$recCIAC->descripcion.' </DESCRIPCION>&#013;&#010;';
								echo '	<DIRECCION> '.$recCIAC->direccion.' </DIRECCION>&#013;&#010;';
								echo '	<LOCALIDAD> '.$recCIAC->localidad.' </LOCALIDAD>&#013;&#010;';
								echo '	<PROVINCIA> '.$recCIAC->provincia.' </PROVINCIA>&#013;&#010;';	
								echo '	<CODIGO_POSTAL> '.$recCIAC->cp.' </CODIGO_POSTAL>&#013;&#010;';
								echo '	<ANTIGUEDAD_MESES> '.$recCIAC->antiguedad_meses.' </ANTIGUEDAD_MESES>&#013;&#010;';	

								if(count($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row) > 1 && $contadorRecC8 < count($estado_fin_cli->CONSTANCIA_DE_INSCRIPCION_AFIP[0]->row)) echo '&#013;&#010;';								
							}
							echo '</CONSTANCIA_DE_INSCRIPCION_AFIP>&#013;&#010;&#013;&#010;';
							echo '<TIPO_ACTIVIDAD>&#013;&#010;';
							$contadorRecC9 = 0;
							foreach ($estado_fin_cli->Tipo_Actividad[0]->row as $recTAC) 
							{								
								$contadorRecC9++;
								echo '	<TIPO_ACTIVIDAD> '.$recTAC->tipo_actividad.' </TIPO_ACTIVIDAD>&#013;&#010;';

								if(count($estado_fin_cli->Tipo_Actividad[0]->row) > 1 && $contadorRecC9 < count($estado_fin_cli->Tipo_Actividad[0]->row)) echo '&#013;&#010;';								
							}
							echo '</TIPO_ACTIVIDAD>&#013;&#010;&#013;&#010;';				
							echo '<POSEE_MOVILES>&#013;&#010;';
							$contadorRecC10 = 0;
							foreach ($estado_fin_cli->Moviles_posee[0]->row as $recPMC) 
							{								
								$contadorRecC10++;
								echo '	<POSEE_AUTOS> '.$recPMC->posee_autos.' </POSEE_AUTOS>&#013;&#010;';
								echo '	<CANTIDAD_AUTOS> '.$recPMC->cantidad_autos.' </CANTIDAD_AUTOS>&#013;&#010;';

								if(count($estado_fin_cli->Moviles_posee[0]->row) > 1 && $contadorRecC10 < count($estado_fin_cli->Moviles_posee[0]->row)) echo '&#013;&#010;';									
							}
							echo '</POSEE_MOVILES>&#013;&#010;&#013;&#010;';					
							echo '<INFO_LABORAL_HISTORICA>&#013;&#010;';
							$contadorRecC11 = 0;
							foreach ($estado_fin_cli->inf_lab_hist_fecha_[0]->row as $recILHC) 
							{								
								$contadorRecC11++;
								echo '	<CUIT> '.$recILHC->inf_lab_cuit_.' </CUIT>&#013;&#010;';
								echo '	<RAZON_SOCIAL> '.$recILHC->inf_lab_razon_.' </RAZON_SOCIAL>&#013;&#010;';
								echo '	<RELACION_DESDE> '.$recILHC->relacion_desde_.' </RELACION_DESDE>&#013;&#010;';
								echo '	<RELACION_HASTA> '.$recILHC->relacion_hasta_.' </RELACION_HASTA>&#013;&#010;';

								if(count($estado_fin_cli->inf_lab_hist_fecha_[0]->row) > 1 && $contadorRecC11 < count($estado_fin_cli->inf_lab_hist_fecha_[0]->row)) echo '&#013;&#010;';								
							}
							echo '</INFO_LABORAL_HISTORICA>&#013;&#010;&#013;&#010;';								
							echo '<POSEE_JUICIOS_EMBARGO>&#013;&#010;';
							$contadorRecC12 = 0;
							foreach ($estado_fin_cli->Juicios_Posee_Embargo[0]->row as $recPJEC) 
							{								
								$contadorRecC12++;
								echo '	<TIPO_JUICIO> '.$recPJEC->juicios_posee_tipo.' </TIPO_JUICIO>&#013;&#010;';

								if(count($estado_fin_cli->Juicios_Posee_Embargo[0]->row) > 1 && $contadorRecC12 < count($estado_fin_cli->Juicios_Posee_Embargo[0]->row)) echo '&#013;&#010;';								
							}
							echo '</POSEE_JUICIOS_EMBARGO>&#013;&#010;&#013;&#010;';	
							echo '<POSEE_JUICIOS_INHABILITACION>&#013;&#010;';
							$contadorRecC13 = 0;
							foreach ($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row as $recPJIC) 
							{								
								$contadorRecC13++;
								echo '	<TIPO_JUICIO> '.$recPJIC->juicios_posee_tipo.' </TIPO_JUICIO>&#013;&#010;';

								if(count($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row) > 1 && $contadorRecC13 < count($estado_fin_cli->Juicios_Posee_Inhabilitacion[0]->row)) echo '&#013;&#010;';								
							}
							echo '</POSEE_JUICIOS_INHABILITACION>&#013;&#010;&#013;&#010;';	
							echo '<JUBILADO>&#013;&#010;';
							$contadorRecC14 = 0;
							foreach ($estado_fin_cli->JUBILADO[0]->row as $recJC) 
							{								
								$contadorRecC14++;
								echo '	<NUMERO_BENEFICIARIO> '.$recJC->numero_beneficiario.' </NUMERO_BENEFICIARIO>&#013;&#010;';
								echo '	<CLASE_BENEFICIO> '.$recJC->beneficio_clase.' </CLASE_BENEFICIO>&#013;&#010;';
								echo '	<ESTADO> '.$recJC->estado.' </ESTADO>&#013;&#010;';

								if(count($estado_fin_cli->JUBILADO[0]->row) > 1 && $contadorRecC14 < count($estado_fin_cli->JUBILADO[0]->row)) echo '&#013;&#010;';								
							}
							echo '</JUBILADO>&#013;&#010;&#013;&#010;';	
							echo '<VALIDA_MORAS>&#013;&#010;';
							$contadorRecC15 = 0;
							foreach ($estado_fin_cli->Moras_Valida[0]->row as $recMVC) 
							{								
								$contadorRecC15++;
								echo '	<VALIDA> '.$recMVC->valida.' </VALIDA>&#013;&#010;';
								echo '	<ENTIDADES> '.$recMVC->entidades.' </ENTIDADES>&#013;&#010;';
								echo '	<MAX_ATRASO> '.$recMVC->max_atraso.' </MAX_ATRASO>&#013;&#010;';

								if(count($estado_fin_cli->Moras_Valida[0]->row) > 1 && $contadorRecC15 < count($estado_fin_cli->Moras_Valida[0]->row)) echo '&#013;&#010;';								
							}
							echo '</VALIDA_MORAS>&#013;&#010;&#013;&#010;';	
							echo '<SCORE>&#013;&#010;';
							$contadorRecC16 = 0;
							foreach ($estado_fin_cli->Score[0]->row as $recSCC) 
							{								
								$contadorRecC16++;
								echo '	<SCORE> '.$recSCC->score.' </SCORE>&#013;&#010;';

								if(count($estado_fin_cli->Score[0]->row) > 1 && $contadorRecC16 < count($estado_fin_cli->Score[0]->row)) echo '&#013;&#010;';									
							}
							echo '</SCORE>&#013;&#010;&#013;&#010;';					
							echo '						</textarea>';
							echo '					</div>';		
							echo '				</div>';						
						}
						echo '				<div class="form-group form-inline">';
						echo '					<div id="img_loader_13"></div>';		
						echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarEFC" id="btnCancelarEFC" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogsearchclientcredit\').dialog(\'close\');" style="margin-left:10px;" />';
						if($estado_activa_supervisor == 1) echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarEFC" id="btnValidarEFC" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarAutorizacionSupervisorEstadoFinancieroCliente(document.getElementById(\'formulariocefc\'),'.$motivo.');"/>';										
						else echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarEFC" id="btnValidarEFC" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarSinSupervisorEstadoFinancieroCliente('.$motivo.');"/>';
						echo '				</div>';				
						echo '			</form>';
						echo '		</div>';
						echo '	</div>';
						echo '</div>';
					}
				}
				else
				{
					echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
					return;
				}
			}
			else
			{
				echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
				return;
			}
		}
		else
		{
			echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
			return;			
		}
			
		return;
?>