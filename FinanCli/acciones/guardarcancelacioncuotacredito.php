<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');return;}
		if (!verificar_permisos_supervisor()){header('Location:../sinautorizacion.php?activauto=1');return;}

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
		
		$idCuotaCredito=htmlspecialchars($_POST["idCuotaCredito"], ENT_QUOTES, 'UTF-8');
		$motivoCancelacion=htmlspecialchars($_POST["motivoCancelacion"], ENT_QUOTES, 'UTF-8');
		
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
				echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 1: ".$mysqli->error;
				return;				
			}	
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 2: ".$mysqli->error;
			return;				
		}		
		
		if($stmt61 = $mysqli->prepare("SELECT s.id_cadena, s.id, s.nombre FROM finan_cli.usuario u, finan_cli.sucursal s WHERE u.id_sucursal = s.id AND u.id = ?"))
		{
			$stmt61->bind_param('s', $_SESSION['username']);
			$stmt61->execute();    
			$stmt61->store_result();
			
			$totR61 = $stmt61->num_rows;

			if($totR61 > 0)
			{
				$stmt61->bind_result($id_cadena_usuario, $id_sucursal_usuario, $nombre_sucursal_usuario);
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
			echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 3: ".$mysqli->error;
			return;
		}		

		if ($stmt = $mysqli->prepare("SELECT cc.id, cc.monto_cuota_original, cc.estado FROM finan_cli.cuota_credito cc WHERE cc.id = ? AND cc.estado = ?")) 
		{
			$estContrCPagada = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
			$stmt->bind_param('is', $idCuotaCredito, $estContrCPagada);
			$stmt->execute();    
			$stmt->store_result();
	 
			$stmt->bind_result($id_fee_credit, $montoTotalCuotaCredito, $estado_fee_credit_client_a);			
			
			$totR = $stmt->num_rows;
			if($totR == 0)
			{
				echo translate('Msg_Credit_Client_Fee_Not_Exist',$GLOBALS['lang']);
				return;	
			}								
			
			$stmt->fetch();

			$stmt->free_result();
			$stmt->close();
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 4: ".$mysqli->error;
			return;	
		}		
		
						
		if($stmt = $mysqli->prepare("SELECT u.id_perfil FROM finan_cli.usuario u WHERE u.id LIKE(?)"))
		{
			$stmt->bind_param('s', $_SESSION['username']);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR > 0)
			{
				$stmt->bind_result($perfil_usuario_control);
				$stmt->fetch();
				
				if($perfil_usuario_control != 1 && $perfil_usuario_control != 3)
				{
					echo translate('Msg_Restricted_Access',$GLOBALS['lang']);
					return;
				}
				
				
				$estadoFormaPagoCuota = "I";
				if($stmt161 = $mysqli->prepare("SELECT pt.id_pago_total_credito FROM finan_cli.pago_total_credito_x_cuota pt WHERE pt.id_cuota_credito = ?"))
				{
					$stmt161->bind_param('i', $idCuotaCredito);
					$stmt161->execute();    
					$stmt161->store_result();
					
					$totR161 = $stmt161->num_rows;

					if($totR161 > 0)
					{
						$stmt161->bind_result($id_pago_total_credito_db);
						$stmt161->fetch();
										
						$estadoFormaPagoCuota = "T";
						$stmt161->free_result();
						$stmt161->close();
					}
				}
				else
				{
					$estadoFormaPagoCuota = "E";
				}				
				
				
				if($estadoFormaPagoCuota == "I")
				{
					if($stmt162 = $mysqli->prepare("SELECT ps.id, c.id FROM finan_cli.pago_seleccion_cuotas_credito ps, finan_cli.cuota_credito cc, finan_cli.credito c WHERE ps.id_cuota_credito = cc.id AND cc.id_credito = c.id AND ps.id_cuota_credito = ?"))
					{
						$stmt162->bind_param('i', $idCuotaCredito);
						$stmt162->execute();    
						$stmt162->store_result();
						
						$totR162 = $stmt162->num_rows;

						if($totR162 > 0)
						{
							$stmt162->bind_result($id_pago_seleccion_cuotas_credito_db, $id_credito_pago_seleccion_cuotas_db);
							$stmt162->fetch();
											
							$estadoFormaPagoCuota = "S";
							$stmt162->free_result();
							$stmt162->close();
						}
					}
					else
					{
						$estadoFormaPagoCuota = "E";
					}					
				}
				
				if($estadoFormaPagoCuota == "I")
				{
					if($stmt162 = $mysqli->prepare("SELECT pu.fecha_pago, pu.monto_pago, pu.usuario_registro_pago FROM finan_cli.cuota_credito pu WHERE pu.id = ? AND pu.fecha_pago IS NOT NULL AND pu.monto_pago IS NOT NULL AND pu.usuario_registro_pago IS NOT NULL"))
					{
						$stmt162->bind_param('i', $idCuotaCredito);
						$stmt162->execute();    
						$stmt162->store_result();
						
						$totR162 = $stmt162->num_rows;

						if($totR162 > 0)
						{
							$stmt162->bind_result($fecha_pago_unico_db, $monto_pago_unico_db, $usuario_registro_pago_unico_db);
							$stmt162->fetch();
											
							$estadoFormaPagoCuota = "U";
							$stmt162->free_result();
							$stmt162->close();
						}
						else
						{
							$estadoFormaPagoCuota = "E";
						}
					}
					else
					{
						$estadoFormaPagoCuota = "E";
					}					
				}
				
				if($estadoFormaPagoCuota == 'I' || $estadoFormaPagoCuota == 'E')
				{
					echo translate('Msg_Unable_To_Get_The_Form_Of_Payment_Fee',$GLOBALS['lang']);
					return;					
				}
				
				if($estadoFormaPagoCuota == 'T')
				{					
					if($stmt163 = $mysqli->prepare("SELECT ptxc.id_cuota_credito, pt.fecha, pt.monto, pt.usuario, pt.supervisor, pt.token, cc.id_credito, cc.numero_cuota, cc.fecha_vencimiento, cc.monto_cuota_original, cc.estado, cc.fecha_pago, cc.monto_pago, cc.usuario_registro_pago, c.estado FROM finan_cli.pago_total_credito pt, finan_cli.pago_total_credito_x_cuota ptxc, finan_cli.cuota_credito cc, finan_cli.credito c WHERE ptxc.id_cuota_credito = cc.id AND cc.id_credito = c.id AND ptxc.id_pago_total_credito = pt.id AND ptxc.id_pago_total_credito = ? AND cc.estado = ?"))
					{
						$estContrCPagada = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
						$stmt163->bind_param('is', $id_pago_total_credito_db, $estContrCPagada);
						$stmt163->execute();    
						$stmt163->store_result();
						
						$totR163 = $stmt163->num_rows;

						if($totR163 > 0)
						{
							$stmt163->bind_result($id_cuota_credito_anular_pago_db, $fecha_pago_total_borrar_db, $monto_pago_total_borrar_db, $usuario_pago_total_borrar_db, $supervisor_pago_total_borrar_db, $token_pago_total_borrar_db, $id_credito_anular_pago_db, $numero_cuota_anular_pago_db, $fecha_vencimiento_anular_pago_db, $monto_cuota_original_anular_pago_db, $estado_cuota_anular_pago_db, $fecha_pago_anular_db, $monto_pago_anular_db, $usuario_registro_pago_anular_db, $estado_credito_pago_anular_db);
							$mysqli->autocommit(FALSE);
							$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
							
							$posWhileR = 0;
							
							if($stmt39 = $mysqli->prepare("SELECT p.valor FROM finan_cli.parametros p WHERE p.nombre = ?"))
							{
								$nombreValPara = 'cantidad_dias_x_mora';
								$stmt39->bind_param('s', $nombreValPara);
								$stmt39->execute();    
								$stmt39->store_result();
								
								$totR39 = $stmt39->num_rows;

								if($totR39 > 0)
								{
									$stmt39->bind_result($cantidad_dias_para_mora_db);
									$stmt39->fetch();
									
									$stmt39->free_result();
									$stmt39->close();
								}
								else
								{
									echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 5: ".$mysqli->error;
									return;					
								}					
							}
							else
							{
								echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 6: ".$mysqli->error;
								return;
							}
							
							while($stmt163->fetch())
							{
								$fechaObtDB = substr($fecha_vencimiento_anular_pago_db, 0, 4).'-'.substr($fecha_vencimiento_anular_pago_db, 4, 2).'-'.substr($fecha_vencimiento_anular_pago_db, 6, 2).' '.substr($fecha_vencimiento_anular_pago_db, 8, 2).':'.substr($fecha_vencimiento_anular_pago_db, 10, 2).':'.substr($fecha_vencimiento_anular_pago_db, 12, 2);
								$fechaInfDB = new DateTime($fechaObtDB);
								$fechaAct = new DateTime();
								$difDias = $fechaAct->diff($fechaInfDB);
								$fechaActNumber = strtotime(date("Y-m-d H:i:s"));
								$fechaVencimCuotaAc = strtotime($fechaObtDB);

								if($fechaActNumber > $fechaVencimCuotaAc && $difDias->days >= $cantidad_dias_para_mora_db)
								{
									$estSCENM = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
								}
								else
								{
									$estSCENM = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
								}
								
								if(!$stmt10 = $mysqli->prepare("UPDATE finan_cli.cuota_credito SET estado = ?, fecha_pago = ?, monto_pago = ?, usuario_registro_pago = ? WHERE id = ?"))
								{
									echo $mysqli->error;
									if($posWhileR != 0) $mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;
								}
								else
								{
									$fechaPagoN = NULL;
									$montoPagoN = NULL;
									$usuarioRegistroPagoN = NULL;
									$stmt10->bind_param('ssssi', $estSCENM, $fechaPagoN, $montoPagoN, $usuarioRegistroPagoN, $id_cuota_credito_anular_pago_db);
									if(!$stmt10->execute())
									{
										echo $mysqli->error;
										if($posWhileR != 0) $mysqli->rollback();
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										return;						
									}
								}
					
								$date_registro = date("YmdHis");
								$valor_log_user = "ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = ".$estado_cuota_anular_pago_db.", fecha_pago = ".$fecha_pago_anular_db.", monto_pago = ".$monto_pago_anular_db.", usuario_registro_pago = ".$usuario_registro_pago_anular_db." WHERE id = ".$id_cuota_credito_anular_pago_db." -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = ".$estSCENM.", fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = ".$id_cuota_credito_anular_pago_db;
									
								if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
								{
									echo $mysqli->error;
									if($posWhileR != 0) $mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;
								}
								else
								{
									$motivo = 90;
									$stmt->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo, $valor_log_user);
									if(!$stmt->execute())
									{
										echo $mysqli->error;
										if($posWhileR != 0) $mysqli->rollback();
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										return;						
									}
								}

								if($stmt164 = $mysqli->prepare("SELECT ppc.id, ppc.fecha, ppc.monto, ppc.usuario, ppc.supervisor, ppc.token FROM finan_cli.pago_parcial_cuota_credito ppc WHERE ppc.id_cuota_credito = ?"))
								{
									$stmt164->bind_param('i', $id_cuota_credito_anular_pago_db);
									$stmt164->execute();    
									$stmt164->store_result();
									
									$totR164 = $stmt164->num_rows;

									if($totR164 > 0)
									{
										$stmt164->bind_result($id_pago_parcial_cuota_credito_db, $fecha_pago_parcial_cuota_credito_db, $monto_pago_parcial_cuota_credito_db, $usuario_pago_parcial_cuota_credito_db, $supervisor_pago_parcial_cuota_credito_db, $token_pago_parcial_cuota_credito_db);
										$stmt164->fetch();
										
										if(!$stmt10 = $mysqli->prepare("DELETE FROM finan_cli.pago_parcial_cuota_credito WHERE id_cuota_credito = ?"))
										{
											echo $mysqli->error;
											if($posWhileR != 0) $mysqli->rollback();
											$mysqli->autocommit(TRUE);
											$stmt->free_result();
											$stmt->close();
											return;
										}
										else
										{
											
											$stmt10->bind_param('i', $id_cuota_credito_anular_pago_db);
											if(!$stmt10->execute())
											{
												echo $mysqli->error;
												if($posWhileR != 0) $mysqli->rollback();
												$mysqli->autocommit(TRUE);
												$stmt->free_result();
												$stmt->close();
												return;						
											}
										}
							
										$date_registro = date("YmdHis");
										$valor_log_user = "ANTERIOR: finan_cli.pago_parcial_cuota_credito SET id = ".$id_pago_parcial_cuota_credito_db.", fecha = ".$fecha_pago_parcial_cuota_credito_db.", monto = ".$monto_pago_parcial_cuota_credito_db.", usuario = ".$usuario_pago_parcial_cuota_credito_db.", supervisor = ".$supervisor_pago_parcial_cuota_credito_db.", token = ".$token_pago_parcial_cuota_credito_db." WHERE id_cuota_credito = ".$id_cuota_credito_anular_pago_db." -- DELETE finan_cli.pago_parcial_cuota_credito WHERE id_cuota_credito = ".$id_cuota_credito_anular_pago_db;
											
										if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
										{
											echo $mysqli->error;
											if($posWhileR != 0) $mysqli->rollback();
											$mysqli->autocommit(TRUE);
											$stmt->free_result();
											$stmt->close();
											return;
										}
										else
										{
											$motivo = 91;
											$stmt->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo, $valor_log_user);
											if(!$stmt->execute())
											{
												echo $mysqli->error;
												if($posWhileR != 0) $mysqli->rollback();
												$mysqli->autocommit(TRUE);
												$stmt->free_result();
												$stmt->close();
												return;						
											}
										}										
										
										$stmt164->free_result();
										$stmt164->close();
									}
								}
								else
								{
									echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 7: ".$mysqli->error;
									return;
								}
								
								if($stmt165 = $mysqli->prepare("SELECT ptcxc.id_pago_total_credito, ptcxc.id_cuota_credito FROM finan_cli.pago_total_credito_x_cuota ptcxc WHERE ptcxc.id_cuota_credito = ?"))
								{
									$stmt165->bind_param('i', $id_cuota_credito_anular_pago_db);
									$stmt165->execute();    
									$stmt165->store_result();
									
									$totR165 = $stmt165->num_rows;

									if($totR165 > 0)
									{
										$stmt165->bind_result($id_pago_total_credito_borrar_db, $id_cuota_credito_borrar_db);
										$stmt165->fetch();
										
										if(!$stmt10 = $mysqli->prepare("DELETE FROM finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = ?"))
										{
											echo $mysqli->error;
											if($posWhileR != 0) $mysqli->rollback();
											$mysqli->autocommit(TRUE);
											$stmt->free_result();
											$stmt->close();
											return;
										}
										else
										{
											
											$stmt10->bind_param('i', $id_cuota_credito_anular_pago_db);
											if(!$stmt10->execute())
											{
												echo $mysqli->error;
												if($posWhileR != 0) $mysqli->rollback();
												$mysqli->autocommit(TRUE);
												$stmt->free_result();
												$stmt->close();
												return;						
											}
										}
							
										$date_registro = date("YmdHis");
										$valor_log_user = "ANTERIOR: finan_cli.pago_total_credito_x_cuota SET id_pago_total_credito = ".$id_pago_total_credito_borrar_db.", id_cuota_credito = ".$id_cuota_credito_borrar_db." WHERE id_cuota_credito = ".$id_cuota_credito_anular_pago_db." -- DELETE finan_cli.pago_total_credito_x_cuota WHERE id_cuota_credito = ".$id_cuota_credito_anular_pago_db;
											
										if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
										{
											echo $mysqli->error;
											if($posWhileR != 0) $mysqli->rollback();
											$mysqli->autocommit(TRUE);
											$stmt->free_result();
											$stmt->close();
											return;
										}
										else
										{
											$motivo = 92;
											$stmt->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo, $valor_log_user);
											if(!$stmt->execute())
											{
												echo $mysqli->error;
												if($posWhileR != 0) $mysqli->rollback();
												$mysqli->autocommit(TRUE);
												$stmt->free_result();
												$stmt->close();
												return;						
											}
										}										
										
										$stmt165->free_result();
										$stmt165->close();
									}
								}
								else
								{
									echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 8: ".$mysqli->error;
									return;
								}								

								if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.token_anulacion_cuota_credito(fecha,id_cuota_credito,usuario,token,comentario,forma_pago_original) VALUES (?,?,?,?,?,?)"))
								{
									echo $mysqli->error;
									if($posWhileR != 0) $mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;
								}
								else
								{
									$date_registro = date("YmdHis");
									$tokenCC = md5(uniqid(rand(), true));
									$tokenCC = hash('sha512', $tokenCC);
									$formaPagOrg = 'T';
									$stmt10->bind_param('sissss', $date_registro, $id_cuota_credito_anular_pago_db, $_SESSION['username'], $tokenCC, $motivoCancelacion, $formaPagOrg);
									if(!$stmt10->execute())
									{
										echo $mysqli->error;
										if($posWhileR != 0) $mysqli->rollback();
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										return;						
									}
								}								
								
								$posWhileR++;
							}
							
							if(!$stmt10 = $mysqli->prepare("DELETE FROM finan_cli.pago_total_credito WHERE id_credito = ?"))
							{
								echo $mysqli->error;
								if($posWhileR != 0) $mysqli->rollback();
								$mysqli->autocommit(TRUE);
								$stmt->free_result();
								$stmt->close();
								return;
							}
							else
							{
								
								$stmt10->bind_param('i', $id_credito_anular_pago_db);
								if(!$stmt10->execute())
								{
									echo $mysqli->error;
									if($posWhileR != 0) $mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;						
								}
							}
				
							$date_registro = date("YmdHis");
							$valor_log_user = "ANTERIOR: finan_cli.pago_total_credito SET id = ".$id_pago_total_credito_borrar_db.", id_credito = ".$id_credito_anular_pago_db.", fecha = ".$fecha_pago_total_borrar_db.", monto = ".$monto_pago_total_borrar_db.", usuario = ".$usuario_pago_total_borrar_db.", supervisor = ".$supervisor_pago_total_borrar_db.", token = ".$token_pago_total_borrar_db." WHERE id_credito = ".$id_credito_anular_pago_db." -- DELETE finan_cli.pago_total_credito WHERE id_credito = ".$id_credito_anular_pago_db;
								
							if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
							{
								echo $mysqli->error;
								if($posWhileR != 0) $mysqli->rollback();
								$mysqli->autocommit(TRUE);
								$stmt->free_result();
								$stmt->close();
								return;
							}
							else
							{
								$motivo = 93;
								$stmt->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo, $valor_log_user);
								if(!$stmt->execute())
								{
									echo $mysqli->error;
									if($posWhileR != 0) $mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;						
								}
							}

							if(!$stmt10 = $mysqli->prepare("UPDATE finan_cli.credito SET estado = ? WHERE id = ?"))
							{
								echo $mysqli->error;
								if($posWhileR != 0) $mysqli->rollback();
								$mysqli->autocommit(TRUE);
								$stmt->free_result();
								$stmt->close();
								return;
							}
							else
							{
								$estNewCred = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
								$stmt10->bind_param('si', $estNewCred, $id_credito_anular_pago_db);
								if(!$stmt10->execute())
								{
									echo $mysqli->error;
									if($posWhileR != 0) $mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;						
								}
							}
				
							$date_registro = date("YmdHis");
							$valor_log_user = "ANTERIOR: finan_cli.credito SET estado = ".$estado_credito_pago_anular_db." WHERE id = ".$id_credito_anular_pago_db." -- UPDATE finan_cli.credito SET estado = ".$estNewCred." WHERE id = ".$id_credito_anular_pago_db;
								
							if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
							{
								echo $mysqli->error;
								if($posWhileR != 0) $mysqli->rollback();
								$mysqli->autocommit(TRUE);
								$stmt->free_result();
								$stmt->close();
								return;
							}
							else
							{
								$motivo = 94;
								$stmt->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo, $valor_log_user);
								if(!$stmt->execute())
								{
									echo $mysqli->error;
									if($posWhileR != 0) $mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;						
								}
							}
							
							$mysqli->commit();
							$mysqli->autocommit(TRUE);
							
							$stmt163->free_result();
							$stmt163->close();
						}
						else
						{
							echo translate('Msg_Unable_To_Get_The_Form_Of_Payment_Fee',$GLOBALS['lang']);
							return;							
						}
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 9: ".$mysqli->error;
						return;						
					}
				
					if($stmt353 = $mysqli->prepare("SELECT MAX(cc.numero_cuota) FROM finan_cli.cuota_credito cc WHERE cc.id_credito = ? AND cc.estado = ? HAVING MAX(cc.numero_cuota) IS NOT NULL"))
					{
						$estadoPagadoControlCuo = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
						$stmt353->bind_param('is', $id_credito_anular_pago_db, $estadoPagadoControlCuo);
						$stmt353->execute();    
						$stmt353->store_result();
						
						$totR353 = $stmt353->num_rows;

						if($totR353 > 0)
						{
							$stmt353->bind_result($ultimo_numero_cuota_pagada_db);
							$stmt353->fetch();
							
							$stmt353->free_result();
							$stmt353->close();
						}								
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 10: ".$mysqli->error;
						return;
					}			
					
					if($stmt65 = $mysqli->prepare("SELECT ptc.id FROM finan_cli.pago_total_credito ptc WHERE ptc.id_credito = ?"))
					{
						$stmt65->bind_param('i', $id_credito_anular_pago_db);
						$stmt65->execute();    
						$stmt65->store_result();
						
						$totR65 = $stmt65->num_rows;
						
						$stmt65->free_result();
						$stmt65->close();			
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;
					}
					
					if($stmt68 = $mysqli->prepare("SELECT c.estado FROM finan_cli.credito c WHERE c.id = ?"))
					{
						$stmt68->bind_param('i', $id_credito_anular_pago_db);
						$stmt68->execute();    
						$stmt68->store_result();
						
						$totR68 = $stmt68->num_rows;

						if($totR68 > 0)
						{
							$stmt68->bind_result($estado_credito_db_res);
							$stmt68->fetch();
							
							$stmt68->free_result();
							$stmt68->close();
						}
						else
						{
							echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 11: ".$mysqli->error;
							return;
						}								
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 12: ".$mysqli->error;
						return;
					}
					
					if($stmt69 = $mysqli->prepare("SELECT cc.fecha_vencimiento FROM finan_cli.cuota_credito cc WHERE cc.id_credito = ? AND cc.estado IN (?,?) ORDER BY cc.numero_cuota"))
					{
						$estado_p_1 = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
						$estado_p_2 = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
						$stmt69->bind_param('iss', $id_credito_anular_pago_db, $estado_p_1, $estado_p_2);
						$stmt69->execute();    
						$stmt69->store_result();
						
						$totR69 = $stmt69->num_rows;

						if($totR69 > 0)
						{
							$stmt69->bind_result($fecha_vencimiento_cuota_db_res);
							$stmt69->fetch();
											
							$stmt69->free_result();
							$stmt69->close();
						}
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;
					}
					
					if($stmt62 = $mysqli->prepare("SELECT cc.id, cc.numero_cuota, cc.fecha_vencimiento, cc.monto_cuota_original, cc.estado, cc.fecha_pago FROM finan_cli.cuota_credito cc WHERE cc.id_credito = ? ORDER BY cc.numero_cuota"))
					{
						$stmt62->bind_param('i', $id_credito_anular_pago_db);
						$stmt62->execute();    
						$stmt62->store_result();
						
						$totR62 = $stmt62->num_rows;

						if($totR62 > 0)
						{
							$stmt62->bind_result($id_cuota_credito_db, $numero_cuota_db_r, $fecha_vencimiento_cuota_db, $monto_original_cuota_db, $estado_cuota_db, $fecha_pago_cuota_db);
							
							$pasoPrimeraCuota = 0;
							$array[0] = array();
							$posicion = 0;
							while($stmt62->fetch())
							{		
								if($stmt67 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM finan_cli.mora_cuota_credito mcc, finan_cli.cuota_credito cc WHERE mcc.id_cuota_credito = cc.id AND cc.id_credito = ? AND cc.id = ?"))
								{
									$stmt67->bind_param('ii', $id_credito_anular_pago_db, $id_cuota_credito_db);
									$stmt67->execute();    
									$stmt67->store_result();
									
									$totR67 = $stmt67->num_rows;

									if($totR67 > 0)
									{
										$stmt67->bind_result($monto_interes_cuota_credito_db);
										$stmt67->fetch();
									}
									else $monto_interes_cuota_credito_db = 0;
								}
								else
								{
									echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
									return;
								}

								if($stmt66 = $mysqli->prepare("SELECT ptcxc.id_cuota_credito FROM finan_cli.pago_total_credito_x_cuota ptcxc WHERE ptcxc.id_cuota_credito = ?"))
								{
									$stmt66->bind_param('i', $id_cuota_credito_db);
									$stmt66->execute();    
									$stmt66->store_result();
									
									$totR66 = $stmt66->num_rows;
								}
								else
								{
									echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
									return;
								}

								if($stmt105 = $mysqli->prepare("SELECT axm.id_cuota_credito FROM finan_cli.aviso_x_mora axm WHERE axm.id_cuota_credito = ?"))
								{
									$stmt105->bind_param('i', $id_cuota_credito_db);
									$stmt105->execute();    
									$stmt105->store_result();
									
									$totR105 = $stmt105->num_rows;
								}
								else
								{
									echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
									return;
								}						
								
								if($estado_cuota_db == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']))
								{
									if($pasoPrimeraCuota == 0)
									{
										if($totR69 == 1) $array[$posicion]['seleccioncuota'] = '---';
										else $array[$posicion]['seleccioncuota'] = '<label class="switch"><input type="checkbox" id="seleccioncuotanro'.$numero_cuota_db_r.'" name="seleccioncuotanro'.$numero_cuota_db_r.'" /><span class="slider round"></span></label>';
										$array[$posicion]['nrocuota'] = $numero_cuota_db_r;
										$array[$posicion]['fechavencimientov'] = substr($fecha_vencimiento_cuota_db,6,2).'/'.substr($fecha_vencimiento_cuota_db,4,2).'/'.substr($fecha_vencimiento_cuota_db,0,4);
										$array[$posicion]['montototalcuotav'] = '$'.round((($monto_original_cuota_db+$monto_interes_cuota_credito_db)/100.00),2);															
										$array[$posicion]['interesescuotav'] = '$'.round(($monto_interes_cuota_credito_db/100.00),2);
										if(!empty($fecha_pago_cuota_db)) $array[$posicion]['fechapagov'] = substr($fecha_pago_cuota_db,6,2).'/'.substr($fecha_pago_cuota_db,4,2).'/'.substr($fecha_pago_cuota_db,0,4);
										else $array[$posicion]['fechapagov'] = '---';
										if($monto_interes_cuota_credito_db == 0)
										{
											if($totR105 > 0)
											{
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
												else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';										
											}
											else
											{
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
												else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>';
											}
										}
										else
										{
											if($totR105 > 0)
											{									
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
												else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
											}
											else
											{
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
												else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';										
											}
										}
										$pasoPrimeraCuota = 1;
									}
									else
									{
										$array[$posicion]['seleccioncuota'] = '<label class="switch"><input type="checkbox" id="seleccioncuotanro'.$numero_cuota_db_r.'" name="seleccioncuotanro'.$numero_cuota_db_r.'" /><span class="slider round"></span></label>';
										$array[$posicion]['nrocuota'] = $numero_cuota_db_r;
										$array[$posicion]['fechavencimientov'] = substr($fecha_vencimiento_cuota_db,6,2).'/'.substr($fecha_vencimiento_cuota_db,4,2).'/'.substr($fecha_vencimiento_cuota_db,0,4);
										$array[$posicion]['montototalcuotav'] = '$'.round((($monto_original_cuota_db+$monto_interes_cuota_credito_db)/100.00),2);															
										$array[$posicion]['interesescuotav'] = '$'.round(($monto_interes_cuota_credito_db/100.00),2);
										if(!empty($fecha_pago_cuota_db)) $array[$posicion]['fechapagov'] = substr($fecha_pago_cuota_db,6,2).'/'.substr($fecha_pago_cuota_db,4,2).'/'.substr($fecha_pago_cuota_db,0,4);
										else $array[$posicion]['fechapagov'] = '---';
										if($monto_interes_cuota_credito_db == 0)
										{
											//if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
											//else $array[$posicion]['accionesv'] = '---';
											if($totR105 > 0)
											{
												$array[$posicion]['accionesv'] = '<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
											}
											else
											{									
												$array[$posicion]['accionesv'] = '---';
											}
										}
										else
										{
											//if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
											if($totR105 > 0)
											{
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
												else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';										
											}
											else
											{
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
												else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
											}
										}																		
									}
									$array[$posicion]['estadov'] = $estado_cuota_db;
								}
								else
								{
									$array[$posicion]['seleccioncuotanro'] = '---';
									$array[$posicion]['nrocuota'] = $numero_cuota_db_r;
									$array[$posicion]['fechavencimientov'] = substr($fecha_vencimiento_cuota_db,6,2).'/'.substr($fecha_vencimiento_cuota_db,4,2).'/'.substr($fecha_vencimiento_cuota_db,0,4);
									$array[$posicion]['montototalcuotav'] = '$'.round((($monto_original_cuota_db+$monto_interes_cuota_credito_db)/100.00),2);															
									$array[$posicion]['interesescuotav'] = '$'.round(($monto_interes_cuota_credito_db/100.00),2);
									if(!empty($fecha_pago_cuota_db)) $array[$posicion]['fechapagov'] = substr($fecha_pago_cuota_db,6,2).'/'.substr($fecha_pago_cuota_db,4,2).'/'.substr($fecha_pago_cuota_db,0,4);
									else $array[$posicion]['fechapagov'] = '---';
									if($monto_interes_cuota_credito_db == 0)
									{
										if($totR105 > 0)
										{
											if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
											{
												if(($estado_credito_db_res == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_credito_db_res == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) && $numero_cuota_db_r == $ultimo_numero_cuota_pagada_db && ($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3))
												{
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';											
												}
												else
												{
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
												}
											}
											else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';									
										}
										else
										{
											if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
											{
												if(($estado_credito_db_res == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_credito_db_res == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) && $numero_cuota_db_r == $ultimo_numero_cuota_pagada_db && ($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3))
												{
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
												}
												else
												{
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>';
													else $array[$posicion]['accionesv'] = '---';
												}
											}
											else $array[$posicion]['accionesv'] = '---';
										}
									}
									else
									{
										if($totR105 > 0)
										{	
											if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
											{
												if(($estado_credito_db_res == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_credito_db_res == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) && $numero_cuota_db_r == $ultimo_numero_cuota_pagada_db && ($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3))
												{	
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
												}
												else
												{
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';											
												}
											}
											else 
											{
												$array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
											}							
										}
										else
										{
											if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
											{
												if(($estado_credito_db_res == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_credito_db_res == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) && $numero_cuota_db_r == $ultimo_numero_cuota_pagada_db && ($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3))
												{
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';											
												}
												else
												{											
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
												}
											}
											else 
											{
												$array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
											}
										}
									}
									$array[$posicion]['estadov'] = $estado_cuota_db;																	
								}
								
								$stmt67->free_result();
								$stmt67->close();
								
								$stmt66->free_result();
								$stmt66->close();
								
								$posicion++;
							}
							$stmt62->free_result();
							$stmt62->close();
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
										
					echo translate('Msg_Cancel_Total_Amount_Debt_Credit_Client_OK',$GLOBALS['lang']).'=:=:='.$estado_credito_db_res.'=::=::='.json_encode($array).'=::::=::::='.$totR69;
					return;						
				}
				
				if($estadoFormaPagoCuota == 'S')
				{					
					if($stmt163 = $mysqli->prepare("SELECT pscc.id_cuota_credito, pscc.fecha, pscc.monto, pscc.usuario, cc.id_credito, cc.numero_cuota, cc.fecha_vencimiento, cc.monto_cuota_original, cc.estado, cc.fecha_pago, cc.monto_pago, cc.usuario_registro_pago, c.estado FROM finan_cli.pago_seleccion_cuotas_credito pscc, finan_cli.cuota_credito cc, finan_cli.credito c WHERE pscc.id_cuota_credito = cc.id AND cc.id_credito = c.id AND cc.id_credito = ? AND cc.estado = ?"))
					{
						$estContrCPagada = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
						$stmt163->bind_param('is', $id_credito_pago_seleccion_cuotas_db, $estContrCPagada);
						$stmt163->execute();    
						$stmt163->store_result();
						
						$totR163 = $stmt163->num_rows;

						if($totR163 > 0)
						{
							$stmt163->bind_result($id_cuota_credito_anular_pago_db, $fecha_pago_seleccion_borrar_db, $monto_pago_seleccion_borrar_db, $usuario_pago_seleccion_borrar_db, $id_credito_anular_pago_db, $numero_cuota_anular_pago_db, $fecha_vencimiento_anular_pago_db, $monto_cuota_original_anular_pago_db, $estado_cuota_anular_pago_db, $fecha_pago_anular_db, $monto_pago_anular_db, $usuario_registro_pago_anular_db, $estado_credito_pago_anular_db);
							$mysqli->autocommit(FALSE);
							$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
							
							$posWhileR = 0;
							
							if($stmt39 = $mysqli->prepare("SELECT p.valor FROM finan_cli.parametros p WHERE p.nombre = ?"))
							{
								$nombreValPara = 'cantidad_dias_x_mora';
								$stmt39->bind_param('s', $nombreValPara);
								$stmt39->execute();    
								$stmt39->store_result();
								
								$totR39 = $stmt39->num_rows;

								if($totR39 > 0)
								{
									$stmt39->bind_result($cantidad_dias_para_mora_db);
									$stmt39->fetch();
									
									$stmt39->free_result();
									$stmt39->close();
								}
								else
								{
									echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 5: ".$mysqli->error;
									return;					
								}					
							}
							else
							{
								echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 6: ".$mysqli->error;
								return;
							}
							
							while($stmt163->fetch())
							{
								$fechaObtDB = substr($fecha_vencimiento_anular_pago_db, 0, 4).'-'.substr($fecha_vencimiento_anular_pago_db, 4, 2).'-'.substr($fecha_vencimiento_anular_pago_db, 6, 2).' '.substr($fecha_vencimiento_anular_pago_db, 8, 2).':'.substr($fecha_vencimiento_anular_pago_db, 10, 2).':'.substr($fecha_vencimiento_anular_pago_db, 12, 2);
								$fechaInfDB = new DateTime($fechaObtDB);
								$fechaAct = new DateTime();
								$difDias = $fechaAct->diff($fechaInfDB);
								$fechaActNumber = strtotime(date("Y-m-d H:i:s"));
								$fechaVencimCuotaAc = strtotime($fechaObtDB);

								if($fechaActNumber > $fechaVencimCuotaAc && $difDias->days >= $cantidad_dias_para_mora_db)
								{
									$estSCENM = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
								}
								else
								{
									$estSCENM = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
								}
								
								if(!$stmt10 = $mysqli->prepare("UPDATE finan_cli.cuota_credito SET estado = ?, fecha_pago = ?, monto_pago = ?, usuario_registro_pago = ? WHERE id = ?"))
								{
									echo $mysqli->error;
									if($posWhileR != 0) $mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;
								}
								else
								{
									$fechaPagoN = NULL;
									$montoPagoN = NULL;
									$usuarioRegistroPagoN = NULL;
									$stmt10->bind_param('ssssi', $estSCENM, $fechaPagoN, $montoPagoN, $usuarioRegistroPagoN, $id_cuota_credito_anular_pago_db);
									if(!$stmt10->execute())
									{
										echo $mysqli->error;
										if($posWhileR != 0) $mysqli->rollback();
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										return;						
									}
								}
					
								$date_registro = date("YmdHis");
								$valor_log_user = "ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = ".$estado_cuota_anular_pago_db.", fecha_pago = ".$fecha_pago_anular_db.", monto_pago = ".$monto_pago_anular_db.", usuario_registro_pago = ".$usuario_registro_pago_anular_db." WHERE id = ".$id_cuota_credito_anular_pago_db." -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = ".$estSCENM.", fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = ".$id_cuota_credito_anular_pago_db;
									
								if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
								{
									echo $mysqli->error;
									if($posWhileR != 0) $mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;
								}
								else
								{
									$motivo = 95;
									$stmt->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo, $valor_log_user);
									if(!$stmt->execute())
									{
										echo $mysqli->error;
										if($posWhileR != 0) $mysqli->rollback();
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										return;						
									}
								}

								if($stmt164 = $mysqli->prepare("SELECT ppc.id, ppc.fecha, ppc.monto, ppc.usuario, ppc.supervisor, ppc.token FROM finan_cli.pago_parcial_cuota_credito ppc WHERE ppc.id_cuota_credito = ?"))
								{
									$stmt164->bind_param('i', $id_cuota_credito_anular_pago_db);
									$stmt164->execute();    
									$stmt164->store_result();
									
									$totR164 = $stmt164->num_rows;

									if($totR164 > 0)
									{
										$stmt164->bind_result($id_pago_parcial_cuota_credito_db, $fecha_pago_parcial_cuota_credito_db, $monto_pago_parcial_cuota_credito_db, $usuario_pago_parcial_cuota_credito_db, $supervisor_pago_parcial_cuota_credito_db, $token_pago_parcial_cuota_credito_db);
										$stmt164->fetch();
										
										if(!$stmt10 = $mysqli->prepare("DELETE FROM finan_cli.pago_parcial_cuota_credito WHERE id_cuota_credito = ?"))
										{
											echo $mysqli->error;
											if($posWhileR != 0) $mysqli->rollback();
											$mysqli->autocommit(TRUE);
											$stmt->free_result();
											$stmt->close();
											return;
										}
										else
										{
											
											$stmt10->bind_param('i', $id_cuota_credito_anular_pago_db);
											if(!$stmt10->execute())
											{
												echo $mysqli->error;
												if($posWhileR != 0) $mysqli->rollback();
												$mysqli->autocommit(TRUE);
												$stmt->free_result();
												$stmt->close();
												return;						
											}
										}
							
										$date_registro = date("YmdHis");
										$valor_log_user = "ANTERIOR: finan_cli.pago_parcial_cuota_credito SET id = ".$id_pago_parcial_cuota_credito_db.", fecha = ".$fecha_pago_parcial_cuota_credito_db.", monto = ".$monto_pago_parcial_cuota_credito_db.", usuario = ".$usuario_pago_parcial_cuota_credito_db.", supervisor = ".$supervisor_pago_parcial_cuota_credito_db.", token = ".$token_pago_parcial_cuota_credito_db." WHERE id_cuota_credito = ".$id_cuota_credito_anular_pago_db." -- DELETE finan_cli.pago_parcial_cuota_credito WHERE id_cuota_credito = ".$id_cuota_credito_anular_pago_db;
											
										if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
										{
											echo $mysqli->error;
											if($posWhileR != 0) $mysqli->rollback();
											$mysqli->autocommit(TRUE);
											$stmt->free_result();
											$stmt->close();
											return;
										}
										else
										{
											$motivo = 91;
											$stmt->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo, $valor_log_user);
											if(!$stmt->execute())
											{
												echo $mysqli->error;
												if($posWhileR != 0) $mysqli->rollback();
												$mysqli->autocommit(TRUE);
												$stmt->free_result();
												$stmt->close();
												return;						
											}
										}										
										
										$stmt164->free_result();
										$stmt164->close();
									}
								}
								else
								{
									echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 7: ".$mysqli->error;
									return;
								}
								
								if(!$stmt10 = $mysqli->prepare("DELETE FROM finan_cli.pago_seleccion_cuotas_credito WHERE id_cuota_credito = ?"))
								{
									echo $mysqli->error;
									if($posWhileR != 0) $mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;
								}
								else
								{
									
									$stmt10->bind_param('i', $id_cuota_credito_anular_pago_db);
									if(!$stmt10->execute())
									{
										echo $mysqli->error;
										if($posWhileR != 0) $mysqli->rollback();
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										return;						
									}
								}
					
								$date_registro = date("YmdHis");
								$valor_log_user = "ANTERIOR: finan_cli.pago_seleccion_cuotas_credito SET id_cuota_credito = ".$id_cuota_credito_borrar_db.", fecha = ".$fecha_pago_seleccion_borrar_db.", monto = ".$monto_pago_seleccion_borrar_db.", usuario = ".$usuario_pago_seleccion_borrar_db." WHERE id_cuota_credito = ".$id_cuota_credito_anular_pago_db." -- DELETE finan_cli.pago_seleccion_cuotas_credito WHERE id_cuota_credito = ".$id_cuota_credito_anular_pago_db;
									
								if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
								{
									echo $mysqli->error;
									if($posWhileR != 0) $mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;
								}
								else
								{
									$motivo = 96;
									$stmt->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo, $valor_log_user);
									if(!$stmt->execute())
									{
										echo $mysqli->error;
										if($posWhileR != 0) $mysqli->rollback();
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										return;						
									}
								}
										
								if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.token_anulacion_cuota_credito(fecha,id_cuota_credito,usuario,token,comentario,forma_pago_original) VALUES (?,?,?,?,?,?)"))
								{
									echo $mysqli->error;
									if($posWhileR != 0) $mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;
								}
								else
								{
									$date_registro = date("YmdHis");
									$tokenCC = md5(uniqid(rand(), true));
									$tokenCC = hash('sha512', $tokenCC);
									$formaPagOrg = 'S';
									$stmt10->bind_param('sissss', $date_registro, $id_cuota_credito_anular_pago_db, $_SESSION['username'], $tokenCC, $motivoCancelacion, $formaPagOrg);
									if(!$stmt10->execute())
									{
										echo $mysqli->error;
										if($posWhileR != 0) $mysqli->rollback();
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										return;						
									}
								}								
								
								$posWhileR++;
							}
							
							if($estado_credito_pago_anular_db != translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']))
							{
								if(!$stmt10 = $mysqli->prepare("UPDATE finan_cli.credito SET estado = ? WHERE id = ?"))
								{
									echo $mysqli->error;
									if($posWhileR != 0) $mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;
								}
								else
								{
									$estNewCred = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
									$stmt10->bind_param('si', $estNewCred, $id_credito_anular_pago_db);
									if(!$stmt10->execute())
									{
										echo $mysqli->error;
										if($posWhileR != 0) $mysqli->rollback();
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										return;						
									}
								}
					
								$date_registro = date("YmdHis");
								$valor_log_user = "ANTERIOR: finan_cli.credito SET estado = ".$estado_credito_pago_anular_db." WHERE id = ".$id_credito_anular_pago_db." -- UPDATE finan_cli.credito SET estado = ".$estNewCred." WHERE id = ".$id_credito_anular_pago_db;
									
								if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
								{
									echo $mysqli->error;
									if($posWhileR != 0) $mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;
								}
								else
								{
									$motivo = 97;
									$stmt->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo, $valor_log_user);
									if(!$stmt->execute())
									{
										echo $mysqli->error;
										if($posWhileR != 0) $mysqli->rollback();
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										return;						
									}
								}
							}
							
							$mysqli->commit();
							$mysqli->autocommit(TRUE);
							
							$stmt163->free_result();
							$stmt163->close();
						}
						else
						{
							echo translate('Msg_Unable_To_Get_The_Form_Of_Payment_Fee',$GLOBALS['lang']);
							return;							
						}
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 9: ".$mysqli->error;
						return;						
					}
				
					if($stmt353 = $mysqli->prepare("SELECT MAX(cc.numero_cuota) FROM finan_cli.cuota_credito cc WHERE cc.id_credito = ? AND cc.estado = ? HAVING MAX(cc.numero_cuota) IS NOT NULL"))
					{
						$estadoPagadoControlCuo = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
						$stmt353->bind_param('is', $id_credito_anular_pago_db, $estadoPagadoControlCuo);
						$stmt353->execute();    
						$stmt353->store_result();
						
						$totR353 = $stmt353->num_rows;

						if($totR353 > 0)
						{
							$stmt353->bind_result($ultimo_numero_cuota_pagada_db);
							$stmt353->fetch();
							
							$stmt353->free_result();
							$stmt353->close();
						}								
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 10: ".$mysqli->error;
						return;
					}			
					
					if($stmt65 = $mysqli->prepare("SELECT ptc.id FROM finan_cli.pago_total_credito ptc WHERE ptc.id_credito = ?"))
					{
						$stmt65->bind_param('i', $id_credito_anular_pago_db);
						$stmt65->execute();    
						$stmt65->store_result();
						
						$totR65 = $stmt65->num_rows;
						
						$stmt65->free_result();
						$stmt65->close();			
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;
					}
					
					if($stmt68 = $mysqli->prepare("SELECT c.estado FROM finan_cli.credito c WHERE c.id = ?"))
					{
						$stmt68->bind_param('i', $id_credito_anular_pago_db);
						$stmt68->execute();    
						$stmt68->store_result();
						
						$totR68 = $stmt68->num_rows;

						if($totR68 > 0)
						{
							$stmt68->bind_result($estado_credito_db_res);
							$stmt68->fetch();
							
							$stmt68->free_result();
							$stmt68->close();
						}
						else
						{
							echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 11: ".$mysqli->error;
							return;
						}								
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 12: ".$mysqli->error;
						return;
					}
					
					if($stmt69 = $mysqli->prepare("SELECT cc.fecha_vencimiento FROM finan_cli.cuota_credito cc WHERE cc.id_credito = ? AND cc.estado IN (?,?) ORDER BY cc.numero_cuota"))
					{
						$estado_p_1 = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
						$estado_p_2 = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
						$stmt69->bind_param('iss', $id_credito_anular_pago_db, $estado_p_1, $estado_p_2);
						$stmt69->execute();    
						$stmt69->store_result();
						
						$totR69 = $stmt69->num_rows;

						if($totR69 > 0)
						{
							$stmt69->bind_result($fecha_vencimiento_cuota_db_res);
							$stmt69->fetch();
											
							$stmt69->free_result();
							$stmt69->close();
						}
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;
					}
					
					if($stmt62 = $mysqli->prepare("SELECT cc.id, cc.numero_cuota, cc.fecha_vencimiento, cc.monto_cuota_original, cc.estado, cc.fecha_pago FROM finan_cli.cuota_credito cc WHERE cc.id_credito = ? ORDER BY cc.numero_cuota"))
					{
						$stmt62->bind_param('i', $id_credito_anular_pago_db);
						$stmt62->execute();    
						$stmt62->store_result();
						
						$totR62 = $stmt62->num_rows;

						if($totR62 > 0)
						{
							$stmt62->bind_result($id_cuota_credito_db, $numero_cuota_db_r, $fecha_vencimiento_cuota_db, $monto_original_cuota_db, $estado_cuota_db, $fecha_pago_cuota_db);
							
							$pasoPrimeraCuota = 0;
							$array[0] = array();
							$posicion = 0;
							while($stmt62->fetch())
							{		
								if($stmt67 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM finan_cli.mora_cuota_credito mcc, finan_cli.cuota_credito cc WHERE mcc.id_cuota_credito = cc.id AND cc.id_credito = ? AND cc.id = ?"))
								{
									$stmt67->bind_param('ii', $id_credito_anular_pago_db, $id_cuota_credito_db);
									$stmt67->execute();    
									$stmt67->store_result();
									
									$totR67 = $stmt67->num_rows;

									if($totR67 > 0)
									{
										$stmt67->bind_result($monto_interes_cuota_credito_db);
										$stmt67->fetch();
									}
									else $monto_interes_cuota_credito_db = 0;
								}
								else
								{
									echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
									return;
								}

								if($stmt66 = $mysqli->prepare("SELECT ptcxc.id_cuota_credito FROM finan_cli.pago_total_credito_x_cuota ptcxc WHERE ptcxc.id_cuota_credito = ?"))
								{
									$stmt66->bind_param('i', $id_cuota_credito_db);
									$stmt66->execute();    
									$stmt66->store_result();
									
									$totR66 = $stmt66->num_rows;
								}
								else
								{
									echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
									return;
								}

								if($stmt105 = $mysqli->prepare("SELECT axm.id_cuota_credito FROM finan_cli.aviso_x_mora axm WHERE axm.id_cuota_credito = ?"))
								{
									$stmt105->bind_param('i', $id_cuota_credito_db);
									$stmt105->execute();    
									$stmt105->store_result();
									
									$totR105 = $stmt105->num_rows;
								}
								else
								{
									echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
									return;
								}						
								
								if($estado_cuota_db == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']))
								{
									if($pasoPrimeraCuota == 0)
									{
										if($totR69 == 1) $array[$posicion]['seleccioncuota'] = '---';
										else $array[$posicion]['seleccioncuota'] = '<label class="switch"><input type="checkbox" id="seleccioncuotanro'.$numero_cuota_db_r.'" name="seleccioncuotanro'.$numero_cuota_db_r.'" /><span class="slider round"></span></label>';
										$array[$posicion]['nrocuota'] = $numero_cuota_db_r;
										$array[$posicion]['fechavencimientov'] = substr($fecha_vencimiento_cuota_db,6,2).'/'.substr($fecha_vencimiento_cuota_db,4,2).'/'.substr($fecha_vencimiento_cuota_db,0,4);
										$array[$posicion]['montototalcuotav'] = '$'.round((($monto_original_cuota_db+$monto_interes_cuota_credito_db)/100.00),2);															
										$array[$posicion]['interesescuotav'] = '$'.round(($monto_interes_cuota_credito_db/100.00),2);
										if(!empty($fecha_pago_cuota_db)) $array[$posicion]['fechapagov'] = substr($fecha_pago_cuota_db,6,2).'/'.substr($fecha_pago_cuota_db,4,2).'/'.substr($fecha_pago_cuota_db,0,4);
										else $array[$posicion]['fechapagov'] = '---';
										if($monto_interes_cuota_credito_db == 0)
										{
											if($totR105 > 0)
											{
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
												else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';										
											}
											else
											{
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
												else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>';
											}
										}
										else
										{
											if($totR105 > 0)
											{									
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
												else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
											}
											else
											{
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
												else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';										
											}
										}
										$pasoPrimeraCuota = 1;
									}
									else
									{
										$array[$posicion]['seleccioncuota'] = '<label class="switch"><input type="checkbox" id="seleccioncuotanro'.$numero_cuota_db_r.'" name="seleccioncuotanro'.$numero_cuota_db_r.'" /><span class="slider round"></span></label>';
										$array[$posicion]['nrocuota'] = $numero_cuota_db_r;
										$array[$posicion]['fechavencimientov'] = substr($fecha_vencimiento_cuota_db,6,2).'/'.substr($fecha_vencimiento_cuota_db,4,2).'/'.substr($fecha_vencimiento_cuota_db,0,4);
										$array[$posicion]['montototalcuotav'] = '$'.round((($monto_original_cuota_db+$monto_interes_cuota_credito_db)/100.00),2);															
										$array[$posicion]['interesescuotav'] = '$'.round(($monto_interes_cuota_credito_db/100.00),2);
										if(!empty($fecha_pago_cuota_db)) $array[$posicion]['fechapagov'] = substr($fecha_pago_cuota_db,6,2).'/'.substr($fecha_pago_cuota_db,4,2).'/'.substr($fecha_pago_cuota_db,0,4);
										else $array[$posicion]['fechapagov'] = '---';
										if($monto_interes_cuota_credito_db == 0)
										{
											//if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
											//else $array[$posicion]['accionesv'] = '---';
											if($totR105 > 0)
											{
												$array[$posicion]['accionesv'] = '<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
											}
											else
											{									
												$array[$posicion]['accionesv'] = '---';
											}
										}
										else
										{
											//if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
											if($totR105 > 0)
											{
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
												else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';										
											}
											else
											{
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
												else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
											}
										}																		
									}
									$array[$posicion]['estadov'] = $estado_cuota_db;
								}
								else
								{
									$array[$posicion]['seleccioncuotanro'] = '---';
									$array[$posicion]['nrocuota'] = $numero_cuota_db_r;
									$array[$posicion]['fechavencimientov'] = substr($fecha_vencimiento_cuota_db,6,2).'/'.substr($fecha_vencimiento_cuota_db,4,2).'/'.substr($fecha_vencimiento_cuota_db,0,4);
									$array[$posicion]['montototalcuotav'] = '$'.round((($monto_original_cuota_db+$monto_interes_cuota_credito_db)/100.00),2);															
									$array[$posicion]['interesescuotav'] = '$'.round(($monto_interes_cuota_credito_db/100.00),2);
									if(!empty($fecha_pago_cuota_db)) $array[$posicion]['fechapagov'] = substr($fecha_pago_cuota_db,6,2).'/'.substr($fecha_pago_cuota_db,4,2).'/'.substr($fecha_pago_cuota_db,0,4);
									else $array[$posicion]['fechapagov'] = '---';
									if($monto_interes_cuota_credito_db == 0)
									{
										if($totR105 > 0)
										{
											if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
											{
												if(($estado_credito_db_res == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_credito_db_res == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) && $numero_cuota_db_r == $ultimo_numero_cuota_pagada_db && ($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3))
												{
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';											
												}
												else
												{
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
												}
											}
											else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';									
										}
										else
										{
											if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
											{
												if(($estado_credito_db_res == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_credito_db_res == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) && $numero_cuota_db_r == $ultimo_numero_cuota_pagada_db && ($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3))
												{
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
												}
												else
												{
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>';
													else $array[$posicion]['accionesv'] = '---';
												}
											}
											else $array[$posicion]['accionesv'] = '---';
										}
									}
									else
									{
										if($totR105 > 0)
										{	
											if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
											{
												if(($estado_credito_db_res == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_credito_db_res == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) && $numero_cuota_db_r == $ultimo_numero_cuota_pagada_db && ($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3))
												{	
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
												}
												else
												{
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';											
												}
											}
											else 
											{
												$array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
											}							
										}
										else
										{
											if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
											{
												if(($estado_credito_db_res == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_credito_db_res == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) && $numero_cuota_db_r == $ultimo_numero_cuota_pagada_db && ($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3))
												{
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';											
												}
												else
												{											
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
												}
											}
											else 
											{
												$array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
											}
										}
									}
									$array[$posicion]['estadov'] = $estado_cuota_db;																	
								}
								
								$stmt67->free_result();
								$stmt67->close();
								
								$stmt66->free_result();
								$stmt66->close();
								
								$posicion++;
							}
							$stmt62->free_result();
							$stmt62->close();
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
										
					echo translate('Msg_Cancel_Selection_Amount_Debt_Credit_Client_OK',$GLOBALS['lang']).'=:=:='.$estado_credito_db_res.'=::=::='.json_encode($array).'=::::=::::='.$totR69;
					return;						
				}				
				
				if($estadoFormaPagoCuota == 'U')
				{					
					if($stmt163 = $mysqli->prepare("SELECT cc.id, cc.id_credito, cc.numero_cuota, cc.fecha_vencimiento, cc.monto_cuota_original, cc.estado, cc.fecha_pago, cc.monto_pago, cc.usuario_registro_pago, c.estado FROM finan_cli.cuota_credito cc, finan_cli.credito c WHERE cc.id_credito = c.id AND cc.id = ? AND cc.estado = ?"))
					{
						$estContrCPagada = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
						$stmt163->bind_param('is', $idCuotaCredito, $estContrCPagada);
						$stmt163->execute();    
						$stmt163->store_result();
						
						$totR163 = $stmt163->num_rows;

						if($totR163 > 0)
						{
							$stmt163->bind_result($id_cuota_credito_anular_pago_db, $id_credito_anular_pago_db, $numero_cuota_anular_pago_db, $fecha_vencimiento_anular_pago_db, $monto_cuota_original_anular_pago_db, $estado_cuota_anular_pago_db, $fecha_pago_anular_db, $monto_pago_anular_db, $usuario_registro_pago_anular_db, $estado_credito_pago_anular_db);
							$mysqli->autocommit(FALSE);
							$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
							
							$posWhileR = 0;
							
							if($stmt39 = $mysqli->prepare("SELECT p.valor FROM finan_cli.parametros p WHERE p.nombre = ?"))
							{
								$nombreValPara = 'cantidad_dias_x_mora';
								$stmt39->bind_param('s', $nombreValPara);
								$stmt39->execute();    
								$stmt39->store_result();
								
								$totR39 = $stmt39->num_rows;

								if($totR39 > 0)
								{
									$stmt39->bind_result($cantidad_dias_para_mora_db);
									$stmt39->fetch();
									
									$stmt39->free_result();
									$stmt39->close();
								}
								else
								{
									echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 5: ".$mysqli->error;
									return;					
								}					
							}
							else
							{
								echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 6: ".$mysqli->error;
								return;
							}
							
							while($stmt163->fetch())
							{
								$fechaObtDB = substr($fecha_vencimiento_anular_pago_db, 0, 4).'-'.substr($fecha_vencimiento_anular_pago_db, 4, 2).'-'.substr($fecha_vencimiento_anular_pago_db, 6, 2).' '.substr($fecha_vencimiento_anular_pago_db, 8, 2).':'.substr($fecha_vencimiento_anular_pago_db, 10, 2).':'.substr($fecha_vencimiento_anular_pago_db, 12, 2);
								$fechaInfDB = new DateTime($fechaObtDB);
								$fechaAct = new DateTime();
								$difDias = $fechaAct->diff($fechaInfDB);
								$fechaActNumber = strtotime(date("Y-m-d H:i:s"));
								$fechaVencimCuotaAc = strtotime($fechaObtDB);

								if($fechaActNumber > $fechaVencimCuotaAc && $difDias->days >= $cantidad_dias_para_mora_db)
								{
									$estSCENM = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
								}
								else
								{
									$estSCENM = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
								}
								
								if(!$stmt10 = $mysqli->prepare("UPDATE finan_cli.cuota_credito SET estado = ?, fecha_pago = ?, monto_pago = ?, usuario_registro_pago = ? WHERE id = ?"))
								{
									echo $mysqli->error;
									if($posWhileR != 0) $mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;
								}
								else
								{
									$fechaPagoN = NULL;
									$montoPagoN = NULL;
									$usuarioRegistroPagoN = NULL;
									$stmt10->bind_param('ssssi', $estSCENM, $fechaPagoN, $montoPagoN, $usuarioRegistroPagoN, $id_cuota_credito_anular_pago_db);
									if(!$stmt10->execute())
									{
										echo $mysqli->error;
										if($posWhileR != 0) $mysqli->rollback();
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										return;						
									}
								}
					
								$date_registro = date("YmdHis");
								$valor_log_user = "ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = ".$estado_cuota_anular_pago_db.", fecha_pago = ".$fecha_pago_anular_db.", monto_pago = ".$monto_pago_anular_db.", usuario_registro_pago = ".$usuario_registro_pago_anular_db." WHERE id = ".$id_cuota_credito_anular_pago_db." -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = ".$estSCENM.", fecha_pago = NULL, monto_pago = NULL, usuario_registro_pago = NULL WHERE id = ".$id_cuota_credito_anular_pago_db;
									
								if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
								{
									echo $mysqli->error;
									if($posWhileR != 0) $mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;
								}
								else
								{
									$motivo = 98;
									$stmt->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo, $valor_log_user);
									if(!$stmt->execute())
									{
										echo $mysqli->error;
										if($posWhileR != 0) $mysqli->rollback();
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										return;						
									}
								}

								if($stmt164 = $mysqli->prepare("SELECT ppc.id, ppc.fecha, ppc.monto, ppc.usuario, ppc.supervisor, ppc.token FROM finan_cli.pago_parcial_cuota_credito ppc WHERE ppc.id_cuota_credito = ?"))
								{
									$stmt164->bind_param('i', $id_cuota_credito_anular_pago_db);
									$stmt164->execute();    
									$stmt164->store_result();
									
									$totR164 = $stmt164->num_rows;

									if($totR164 > 0)
									{
										$stmt164->bind_result($id_pago_parcial_cuota_credito_db, $fecha_pago_parcial_cuota_credito_db, $monto_pago_parcial_cuota_credito_db, $usuario_pago_parcial_cuota_credito_db, $supervisor_pago_parcial_cuota_credito_db, $token_pago_parcial_cuota_credito_db);
										$stmt164->fetch();
										
										if(!$stmt10 = $mysqli->prepare("DELETE FROM finan_cli.pago_parcial_cuota_credito WHERE id_cuota_credito = ?"))
										{
											echo $mysqli->error;
											if($posWhileR != 0) $mysqli->rollback();
											$mysqli->autocommit(TRUE);
											$stmt->free_result();
											$stmt->close();
											return;
										}
										else
										{
											
											$stmt10->bind_param('i', $id_cuota_credito_anular_pago_db);
											if(!$stmt10->execute())
											{
												echo $mysqli->error;
												if($posWhileR != 0) $mysqli->rollback();
												$mysqli->autocommit(TRUE);
												$stmt->free_result();
												$stmt->close();
												return;						
											}
										}
							
										$date_registro = date("YmdHis");
										$valor_log_user = "ANTERIOR: finan_cli.pago_parcial_cuota_credito SET id = ".$id_pago_parcial_cuota_credito_db.", fecha = ".$fecha_pago_parcial_cuota_credito_db.", monto = ".$monto_pago_parcial_cuota_credito_db.", usuario = ".$usuario_pago_parcial_cuota_credito_db.", supervisor = ".$supervisor_pago_parcial_cuota_credito_db.", token = ".$token_pago_parcial_cuota_credito_db." WHERE id_cuota_credito = ".$id_cuota_credito_anular_pago_db." -- DELETE finan_cli.pago_parcial_cuota_credito WHERE id_cuota_credito = ".$id_cuota_credito_anular_pago_db;
											
										if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
										{
											echo $mysqli->error;
											if($posWhileR != 0) $mysqli->rollback();
											$mysqli->autocommit(TRUE);
											$stmt->free_result();
											$stmt->close();
											return;
										}
										else
										{
											$motivo = 91;
											$stmt->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo, $valor_log_user);
											if(!$stmt->execute())
											{
												echo $mysqli->error;
												if($posWhileR != 0) $mysqli->rollback();
												$mysqli->autocommit(TRUE);
												$stmt->free_result();
												$stmt->close();
												return;						
											}
										}										
										
										$stmt164->free_result();
										$stmt164->close();
									}
								}
								else
								{
									echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 7: ".$mysqli->error;
									return;
								}
																		
								if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.token_anulacion_cuota_credito(fecha,id_cuota_credito,usuario,token,comentario,forma_pago_original) VALUES (?,?,?,?,?,?)"))
								{
									echo $mysqli->error;
									if($posWhileR != 0) $mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;
								}
								else
								{
									$date_registro = date("YmdHis");
									$tokenCC = md5(uniqid(rand(), true));
									$tokenCC = hash('sha512', $tokenCC);
									$formaPagOrg = 'S';
									$stmt10->bind_param('sissss', $date_registro, $id_cuota_credito_anular_pago_db, $_SESSION['username'], $tokenCC, $motivoCancelacion, $formaPagOrg);
									if(!$stmt10->execute())
									{
										echo $mysqli->error;
										if($posWhileR != 0) $mysqli->rollback();
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										return;						
									}
								}								
								
								$posWhileR++;
							}
							
							if($estado_credito_pago_anular_db != translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']))
							{
								if(!$stmt10 = $mysqli->prepare("UPDATE finan_cli.credito SET estado = ? WHERE id = ?"))
								{
									echo $mysqli->error;
									if($posWhileR != 0) $mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;
								}
								else
								{
									$estNewCred = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
									$stmt10->bind_param('si', $estNewCred, $id_credito_anular_pago_db);
									if(!$stmt10->execute())
									{
										echo $mysqli->error;
										if($posWhileR != 0) $mysqli->rollback();
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										return;						
									}
								}
					
								$date_registro = date("YmdHis");
								$valor_log_user = "ANTERIOR: finan_cli.credito SET estado = ".$estado_credito_pago_anular_db." WHERE id = ".$id_credito_anular_pago_db." -- UPDATE finan_cli.credito SET estado = ".$estNewCred." WHERE id = ".$id_credito_anular_pago_db;
									
								if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
								{
									echo $mysqli->error;
									if($posWhileR != 0) $mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;
								}
								else
								{
									$motivo = 99;
									$stmt->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo, $valor_log_user);
									if(!$stmt->execute())
									{
										echo $mysqli->error;
										if($posWhileR != 0) $mysqli->rollback();
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										return;						
									}
								}
							}
							
							$mysqli->commit();
							$mysqli->autocommit(TRUE);
							
							$stmt163->free_result();
							$stmt163->close();
						}
						else
						{
							echo translate('Msg_Unable_To_Get_The_Form_Of_Payment_Fee',$GLOBALS['lang']);
							return;							
						}
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 9: ".$mysqli->error;
						return;						
					}
				
					if($stmt353 = $mysqli->prepare("SELECT MAX(cc.numero_cuota) FROM finan_cli.cuota_credito cc WHERE cc.id_credito = ? AND cc.estado = ? HAVING MAX(cc.numero_cuota) IS NOT NULL"))
					{
						$estadoPagadoControlCuo = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
						$stmt353->bind_param('is', $id_credito_anular_pago_db, $estadoPagadoControlCuo);
						$stmt353->execute();    
						$stmt353->store_result();
						
						$totR353 = $stmt353->num_rows;

						if($totR353 > 0)
						{
							$stmt353->bind_result($ultimo_numero_cuota_pagada_db);
							$stmt353->fetch();
							
							$stmt353->free_result();
							$stmt353->close();
						}								
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 10: ".$mysqli->error;
						return;
					}			
					
					if($stmt65 = $mysqli->prepare("SELECT ptc.id FROM finan_cli.pago_total_credito ptc WHERE ptc.id_credito = ?"))
					{
						$stmt65->bind_param('i', $id_credito_anular_pago_db);
						$stmt65->execute();    
						$stmt65->store_result();
						
						$totR65 = $stmt65->num_rows;
						
						$stmt65->free_result();
						$stmt65->close();			
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;
					}
					
					if($stmt68 = $mysqli->prepare("SELECT c.estado FROM finan_cli.credito c WHERE c.id = ?"))
					{
						$stmt68->bind_param('i', $id_credito_anular_pago_db);
						$stmt68->execute();    
						$stmt68->store_result();
						
						$totR68 = $stmt68->num_rows;

						if($totR68 > 0)
						{
							$stmt68->bind_result($estado_credito_db_res);
							$stmt68->fetch();
							
							$stmt68->free_result();
							$stmt68->close();
						}
						else
						{
							echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 11: ".$mysqli->error;
							return;
						}								
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang'])."ACAA 12: ".$mysqli->error;
						return;
					}
					
					if($stmt69 = $mysqli->prepare("SELECT cc.fecha_vencimiento FROM finan_cli.cuota_credito cc WHERE cc.id_credito = ? AND cc.estado IN (?,?) ORDER BY cc.numero_cuota"))
					{
						$estado_p_1 = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
						$estado_p_2 = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
						$stmt69->bind_param('iss', $id_credito_anular_pago_db, $estado_p_1, $estado_p_2);
						$stmt69->execute();    
						$stmt69->store_result();
						
						$totR69 = $stmt69->num_rows;

						if($totR69 > 0)
						{
							$stmt69->bind_result($fecha_vencimiento_cuota_db_res);
							$stmt69->fetch();
											
							$stmt69->free_result();
							$stmt69->close();
						}
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;
					}
					
					if($stmt62 = $mysqli->prepare("SELECT cc.id, cc.numero_cuota, cc.fecha_vencimiento, cc.monto_cuota_original, cc.estado, cc.fecha_pago FROM finan_cli.cuota_credito cc WHERE cc.id_credito = ? ORDER BY cc.numero_cuota"))
					{
						$stmt62->bind_param('i', $id_credito_anular_pago_db);
						$stmt62->execute();    
						$stmt62->store_result();
						
						$totR62 = $stmt62->num_rows;

						if($totR62 > 0)
						{
							$stmt62->bind_result($id_cuota_credito_db, $numero_cuota_db_r, $fecha_vencimiento_cuota_db, $monto_original_cuota_db, $estado_cuota_db, $fecha_pago_cuota_db);
							
							$pasoPrimeraCuota = 0;
							$array[0] = array();
							$posicion = 0;
							while($stmt62->fetch())
							{		
								if($stmt67 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM finan_cli.mora_cuota_credito mcc, finan_cli.cuota_credito cc WHERE mcc.id_cuota_credito = cc.id AND cc.id_credito = ? AND cc.id = ?"))
								{
									$stmt67->bind_param('ii', $id_credito_anular_pago_db, $id_cuota_credito_db);
									$stmt67->execute();    
									$stmt67->store_result();
									
									$totR67 = $stmt67->num_rows;

									if($totR67 > 0)
									{
										$stmt67->bind_result($monto_interes_cuota_credito_db);
										$stmt67->fetch();
									}
									else $monto_interes_cuota_credito_db = 0;
								}
								else
								{
									echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
									return;
								}

								if($stmt66 = $mysqli->prepare("SELECT ptcxc.id_cuota_credito FROM finan_cli.pago_total_credito_x_cuota ptcxc WHERE ptcxc.id_cuota_credito = ?"))
								{
									$stmt66->bind_param('i', $id_cuota_credito_db);
									$stmt66->execute();    
									$stmt66->store_result();
									
									$totR66 = $stmt66->num_rows;
								}
								else
								{
									echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
									return;
								}

								if($stmt105 = $mysqli->prepare("SELECT axm.id_cuota_credito FROM finan_cli.aviso_x_mora axm WHERE axm.id_cuota_credito = ?"))
								{
									$stmt105->bind_param('i', $id_cuota_credito_db);
									$stmt105->execute();    
									$stmt105->store_result();
									
									$totR105 = $stmt105->num_rows;
								}
								else
								{
									echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
									return;
								}						
								
								if($estado_cuota_db == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']))
								{
									if($pasoPrimeraCuota == 0)
									{
										if($totR69 == 1) $array[$posicion]['seleccioncuota'] = '---';
										else $array[$posicion]['seleccioncuota'] = '<label class="switch"><input type="checkbox" id="seleccioncuotanro'.$numero_cuota_db_r.'" name="seleccioncuotanro'.$numero_cuota_db_r.'" /><span class="slider round"></span></label>';
										$array[$posicion]['nrocuota'] = $numero_cuota_db_r;
										$array[$posicion]['fechavencimientov'] = substr($fecha_vencimiento_cuota_db,6,2).'/'.substr($fecha_vencimiento_cuota_db,4,2).'/'.substr($fecha_vencimiento_cuota_db,0,4);
										$array[$posicion]['montototalcuotav'] = '$'.round((($monto_original_cuota_db+$monto_interes_cuota_credito_db)/100.00),2);															
										$array[$posicion]['interesescuotav'] = '$'.round(($monto_interes_cuota_credito_db/100.00),2);
										if(!empty($fecha_pago_cuota_db)) $array[$posicion]['fechapagov'] = substr($fecha_pago_cuota_db,6,2).'/'.substr($fecha_pago_cuota_db,4,2).'/'.substr($fecha_pago_cuota_db,0,4);
										else $array[$posicion]['fechapagov'] = '---';
										if($monto_interes_cuota_credito_db == 0)
										{
											if($totR105 > 0)
											{
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
												else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';										
											}
											else
											{
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
												else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>';
											}
										}
										else
										{
											if($totR105 > 0)
											{									
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
												else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
											}
											else
											{
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
												else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$id_credito_anular_pago_db.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';										
											}
										}
										$pasoPrimeraCuota = 1;
									}
									else
									{
										$array[$posicion]['seleccioncuota'] = '<label class="switch"><input type="checkbox" id="seleccioncuotanro'.$numero_cuota_db_r.'" name="seleccioncuotanro'.$numero_cuota_db_r.'" /><span class="slider round"></span></label>';
										$array[$posicion]['nrocuota'] = $numero_cuota_db_r;
										$array[$posicion]['fechavencimientov'] = substr($fecha_vencimiento_cuota_db,6,2).'/'.substr($fecha_vencimiento_cuota_db,4,2).'/'.substr($fecha_vencimiento_cuota_db,0,4);
										$array[$posicion]['montototalcuotav'] = '$'.round((($monto_original_cuota_db+$monto_interes_cuota_credito_db)/100.00),2);															
										$array[$posicion]['interesescuotav'] = '$'.round(($monto_interes_cuota_credito_db/100.00),2);
										if(!empty($fecha_pago_cuota_db)) $array[$posicion]['fechapagov'] = substr($fecha_pago_cuota_db,6,2).'/'.substr($fecha_pago_cuota_db,4,2).'/'.substr($fecha_pago_cuota_db,0,4);
										else $array[$posicion]['fechapagov'] = '---';
										if($monto_interes_cuota_credito_db == 0)
										{
											//if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
											//else $array[$posicion]['accionesv'] = '---';
											if($totR105 > 0)
											{
												$array[$posicion]['accionesv'] = '<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
											}
											else
											{									
												$array[$posicion]['accionesv'] = '---';
											}
										}
										else
										{
											//if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
											if($totR105 > 0)
											{
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
												else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';										
											}
											else
											{
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
												else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
											}
										}																		
									}
									$array[$posicion]['estadov'] = $estado_cuota_db;
								}
								else
								{
									$array[$posicion]['seleccioncuotanro'] = '---';
									$array[$posicion]['nrocuota'] = $numero_cuota_db_r;
									$array[$posicion]['fechavencimientov'] = substr($fecha_vencimiento_cuota_db,6,2).'/'.substr($fecha_vencimiento_cuota_db,4,2).'/'.substr($fecha_vencimiento_cuota_db,0,4);
									$array[$posicion]['montototalcuotav'] = '$'.round((($monto_original_cuota_db+$monto_interes_cuota_credito_db)/100.00),2);															
									$array[$posicion]['interesescuotav'] = '$'.round(($monto_interes_cuota_credito_db/100.00),2);
									if(!empty($fecha_pago_cuota_db)) $array[$posicion]['fechapagov'] = substr($fecha_pago_cuota_db,6,2).'/'.substr($fecha_pago_cuota_db,4,2).'/'.substr($fecha_pago_cuota_db,0,4);
									else $array[$posicion]['fechapagov'] = '---';
									if($monto_interes_cuota_credito_db == 0)
									{
										if($totR105 > 0)
										{
											if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
											{
												if(($estado_credito_db_res == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_credito_db_res == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) && $numero_cuota_db_r == $ultimo_numero_cuota_pagada_db && ($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3))
												{
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';											
												}
												else
												{
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
												}
											}
											else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';									
										}
										else
										{
											if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
											{
												if(($estado_credito_db_res == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_credito_db_res == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) && $numero_cuota_db_r == $ultimo_numero_cuota_pagada_db && ($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3))
												{
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
												}
												else
												{
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>';
													else $array[$posicion]['accionesv'] = '---';
												}
											}
											else $array[$posicion]['accionesv'] = '---';
										}
									}
									else
									{
										if($totR105 > 0)
										{	
											if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
											{
												if(($estado_credito_db_res == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_credito_db_res == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) && $numero_cuota_db_r == $ultimo_numero_cuota_pagada_db && ($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3))
												{	
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
												}
												else
												{
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';											
												}
											}
											else 
											{
												$array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
											}							
										}
										else
										{
											if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
											{
												if(($estado_credito_db_res == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_credito_db_res == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) && $numero_cuota_db_r == $ultimo_numero_cuota_pagada_db && ($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3))
												{
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';											
												}
												else
												{											
													if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$id_credito_anular_pago_db.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>';
													else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
												}
											}
											else 
											{
												$array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
											}
										}
									}
									$array[$posicion]['estadov'] = $estado_cuota_db;																	
								}
								
								$stmt67->free_result();
								$stmt67->close();
								
								$stmt66->free_result();
								$stmt66->close();
								
								$posicion++;
							}
							$stmt62->free_result();
							$stmt62->close();
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
										
					echo translate('Msg_Cancel_Credit_Fee_Client_OK',$GLOBALS['lang']).'=:=:='.$estado_credito_db_res.'=::=::='.json_encode($array).'=::::=::::='.$totR69;
					return;						
				}
				
				$stmt->free_result();
				$stmt->close();
				return;				
				
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
?>