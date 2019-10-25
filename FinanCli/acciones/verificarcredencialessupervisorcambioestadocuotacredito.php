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
		
		$idCredito=htmlspecialchars($_POST["idCredito"], ENT_QUOTES, 'UTF-8');
		$idCuotaCredito=htmlspecialchars($_POST["idCuotaCredito"], ENT_QUOTES, 'UTF-8');
		$estadoN=htmlspecialchars($_POST["estadoN"], ENT_QUOTES, 'UTF-8');
		$tokenVSCE=htmlspecialchars($_POST["tokenVSCE"], ENT_QUOTES, 'UTF-8');
				
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
					
					$ingresoToken = 0;
					if ($stmt351 = $mysqli->prepare("SELECT tas.fecha, tas.token, tas.duracion FROM finan_cli.token_autorizacion_supervisor tas WHERE tas.utilizado = 0 AND tas.fecha_utilizacion IS NULL AND tas.autorizado = ? AND tas.autorizante = ? ORDER BY tas.fecha DESC")) 
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
									
									if(!$stmt10 = $mysqli->prepare("UPDATE finan_cli.token_autorizacion_supervisor SET fecha_utilizacion = ?, utilizado = ?, id_motivo = ? WHERE utilizado = 0 AND fecha_utilizacion IS NULL AND autorizado = ? AND autorizante = ?"))
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
						if(!empty($tokenVSCE))
						{
							if($stmt650 = $mysqli->prepare("SELECT tcec.validado FROM finan_cli.token_cambio_estado_cuota tcec WHERE tcec.token = ? AND tcec.validado = ?"))
							{
								$validadoC = 0;
								$stmt650->bind_param('si', $tokenVSCE, $validadoC);
								$stmt650->execute();    
								$stmt650->store_result();
								
								$totR650 = $stmt650->num_rows;
								if($totR650 > 0)
								{
									$stmt650->free_result();
									$stmt650->close();
									
									if($stmt63 = $mysqli->prepare("SELECT cc.numero_cuota, cc.monto_cuota_original, c.cantidad_cuotas, ccli.documento, ccli.tipo_documento, cc.estado FROM finan_cli.cuota_credito cc, finan_cli.credito c, finan_cli.credito_cliente ccli WHERE c.id = ccli.id_credito AND c.id = cc.id_credito AND cc.id = ? AND cc.id_credito = ? AND cc.estado = ?"))
									{
										$estadoD = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
										$stmt63->bind_param('iis', $idCuotaCredito, $idCredito, $estadoD);
										$stmt63->execute();    
										$stmt63->store_result();
										
										$totR63 = $stmt63->num_rows;

										if($totR63 > 0)
										{
											$stmt63->bind_result($numero_cuota_db, $monto_cuota_original_db, $cantidad_cuotas_credito_db, $documento_cliente_credito_db, $tipo_documento_cliente_credito_db, $estado_cuota_credito_anterior_db);
											$stmt63->fetch();
															
											$stmt63->free_result();
											$stmt63->close();				
										}
										else 
										{
											echo translate('Msg_Unknown_Error',$GLOBALS['lang']).'ACA 8';
											return;				
										}
									}
									else
									{
										echo translate('Msg_Unknown_Error',$GLOBALS['lang']).'ACA 9';
										return;
									}		
									
									if($stmt64 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM finan_cli.mora_cuota_credito mcc WHERE mcc.id_cuota_credito = ?"))
									{
										$stmt64->bind_param('i', $idCuotaCredito);
										$stmt64->execute();    
										$stmt64->store_result();
										
										$totR64 = $stmt64->num_rows;
										$monto_interes_cuota_credito = 0;
										if($totR64 == 1)
										{
											$stmt64->bind_result($monto_interes_cuota_credito_db);
											$stmt64->fetch();
											
											$monto_interes_cuota_credito = $monto_interes_cuota_credito_db;
											
											$stmt64->free_result();
											$stmt64->close();				
										}
										else if($totR64 == 0) 
										{
											$monto_interes_cuota_credito = 0;			
										}
										else 
										{
											echo translate('Msg_Unknown_Error',$GLOBALS['lang']).'ACA 10';
											return;				
										}
									}
									else
									{
										echo translate('Msg_Unknown_Error',$GLOBALS['lang']).'ACA 11';
										return;
									}									
									
									$mysqli->autocommit(FALSE);
									$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
									
									if(!$stmt80 = $mysqli->prepare("UPDATE finan_cli.token_cambio_estado_cuota SET validado = ?, usuario_supervisor = ? WHERE token = ? AND validado = ?"))
									{
										echo $mysqli->error;
										$mysqli->autocommit(TRUE);
										return;
									}
									else
									{
										$validacionFI = 1;
										$stmt80->bind_param('issi', $validacionFI, $usuarioSupervisor, $tokenVSCE, $validadoC);
										if(!$stmt80->execute())
										{
											echo $mysqli->error;
											$mysqli->autocommit(TRUE);
											return;						
										}		
									}
									
									$esUltimaCuota = 0;
									
									if($numero_cuota_db == $cantidad_cuotas_credito_db) $esUltimaCuota = 1;
																		
									if(!$stmt43 = $mysqli->prepare("UPDATE finan_cli.cuota_credito SET estado = ? WHERE id = ?"))
									{
										echo $mysqli->error;
										$mysqli->rollback();
										$mysqli->autocommit(TRUE);
										return;
									}
									else
									{
										$date_registro_a_fpcc_db = date("YmdHis");
										$stmt43->bind_param('si', $estadoN, $idCuotaCredito);
										if(!$stmt43->execute())
										{
											echo $mysqli->error;
											$mysqli->rollback();
											$mysqli->autocommit(TRUE);
											return;						
										}

										$date_registro = date("YmdHis");				
										$valor_log_user = "UPDATE finan_cli.cuota_credito SET estado = ".$estadoN." WHERE id = ".$idCuotaCredito;

										if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
										{
											echo $mysqli->error;
											$mysqli->rollback();
											$mysqli->autocommit(TRUE);
											$stmt->free_result();
											$stmt->close();
											return;
										}
										else
										{
											$motivo2 = 80;
											$stmt->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo2, $valor_log_user);
											if(!$stmt->execute())
											{
												echo $mysqli->error;
												$mysqli->rollback();
												$mysqli->autocommit(TRUE);
												$stmt->free_result();
												$stmt->close();
												return;						
											}
										}							
									}
									
									if($esUltimaCuota == 1)
									{
										if($stmt17 = $mysqli->prepare("SELECT cc.estado FROM finan_cli.cuota_credito cc WHERE cc.id_credito = ? ORDER BY cc.numero_cuota"))
										{
											$stmt17->bind_param('i', $idCredito);
											$stmt17->execute();    
											$stmt17->store_result();
											
											$totR17 = $stmt17->num_rows;
											$cantidadEstadoP = 0;
											$cantidadEstadoI = 0;
											$cantidadEstadoC = 0;
											if($totR17 >= 1)
											{
												$stmt17->bind_result($estado_cuota_credito_control_f_db);
												while($stmt17->fetch())
												{
													if($estado_cuota_credito_control_f_db == translate('Lbl_Status_Fee_Condoned',$GLOBALS['lang'])) $cantidadEstadoC++;
													if($estado_cuota_credito_control_f_db == translate('Lbl_Status_Fee_Incobrable',$GLOBALS['lang'])) $cantidadEstadoI++;
													if($estado_cuota_credito_control_f_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang'])) $cantidadEstadoP++;
													
													$ultimo_estado_cuota_credito = $estado_cuota_credito_control_f_db;
												}
												
												if($totR17 == $cantidadEstadoC) $estadoF = translate('Lbl_Status_Fee_Condoned',$GLOBALS['lang']);
												else if($totR17 == $cantidadEstadoI) $estadoF = translate('Lbl_Status_Fee_Incobrable',$GLOBALS['lang']);
												else if($totR17 == $cantidadEstadoP) $estadoF = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
												else if($cantidadEstadoC >= 1 && $cantidadEstadoI >= 1 && $cantidadEstadoP >= 1) $estadoF = translate('Lbl_Status_Fee_Insolvent',$GLOBALS['lang']);
												else if($cantidadEstadoI == 0 && $cantidadEstadoP >= 1 && $cantidadEstadoC >= 1) $estadoF = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
												else if($cantidadEstadoC == 0 && $cantidadEstadoP >= 1 && $cantidadEstadoI >= 1 && $ultimo_estado_cuota_credito == translate('Lbl_Status_Fee_Incobrable',$GLOBALS['lang'])) $estadoF = translate('Lbl_Status_Fee_Insolvent',$GLOBALS['lang']);
												else $estadoF = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
												
												$stmt17->free_result();
												$stmt17->close();				
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
							
										if(!$stmt43 = $mysqli->prepare("UPDATE finan_cli.credito SET estado = ? WHERE id = ?"))
										{
											echo $mysqli->error;
											$mysqli->rollback();
											$mysqli->autocommit(TRUE);
											return;
										}
										else
										{
											$stmt43->bind_param('si', $estadoF, $idCredito);
											if(!$stmt43->execute())
											{
												echo $mysqli->error;
												$mysqli->rollback();
												$mysqli->autocommit(TRUE);
												return;						
											}

											$date_registro = date("YmdHis");				
											$valor_log_user = "UPDATE finan_cli.credito SET estado = ".$estadoN." WHERE id = ".$idCredito;

											if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
											{
												echo $mysqli->error;
												$mysqli->rollback();
												$mysqli->autocommit(TRUE);
												$stmt->free_result();
												$stmt->close();
												return;
											}
											else
											{
												$motivo2 = 81;
												$stmt->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo2, $valor_log_user);
												if(!$stmt->execute())
												{
													echo $mysqli->error;
													$mysqli->rollback();
													$mysqli->autocommit(TRUE);
													$stmt->free_result();
													$stmt->close();
													return;						
												}
											}								
										}						
									}
															
									$mysqli->commit();
									$mysqli->autocommit(TRUE);
									
									if($stmt353 = $mysqli->prepare("SELECT MAX(cc.numero_cuota) FROM finan_cli.cuota_credito cc WHERE cc.id_credito = ? AND cc.estado = ? HAVING MAX(cc.numero_cuota) IS NOT NULL"))
									{
										$estadoPagadoControlCuo = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
										$stmt353->bind_param('is', $idCredito, $estadoPagadoControlCuo);
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
										echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
										return;
									}									
									
									if($stmt65 = $mysqli->prepare("SELECT ptc.id FROM finan_cli.pago_total_credito ptc WHERE ptc.id_credito = ?"))
									{
										$stmt65->bind_param('i', $idCredito);
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
										$stmt68->bind_param('i', $idCredito);
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
											echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
											return;
										}								
									}
									else
									{
										echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
										return;
									}
									
									if($stmt62 = $mysqli->prepare("SELECT cc.id, cc.numero_cuota, cc.fecha_vencimiento, cc.monto_cuota_original, cc.estado, cc.fecha_pago FROM finan_cli.cuota_credito cc WHERE cc.id_credito = ? ORDER BY cc.numero_cuota"))
									{
										$stmt62->bind_param('i', $idCredito);
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
													$stmt67->bind_param('ii', $idCredito, $id_cuota_credito_db);
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
														$array[$posicion]['seleccioncuota'] = '<label class="switch"><input type="checkbox" id="seleccioncuotanro'.$numero_cuota_db_r.'" name="seleccioncuotanro'.$numero_cuota_db_r.'" /><span class="slider round"></span></label>';
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
																if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
																else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';																
															}
															else
															{	
																if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
																else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>';
															}
														}
														else
														{
															if($totR105 > 0)
															{	
																if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
																else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
															}
															else
															{
																if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnCambiarEstadoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
																else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';																
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
																if(($estado_credito_db_res == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_credito_db_res == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']) || $estado_credito_db_res == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang'])) && $numero_cuota_db_r == $ultimo_numero_cuota_pagada_db && ($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3))
																{
																	if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
																	else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';																	
																}
																else
																{																	
																	if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
																	else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
																}
															}
															else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
														}
														else
														{
															if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
															{
																if(($estado_credito_db_res == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_credito_db_res == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']) || $estado_credito_db_res == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang'])) && $numero_cuota_db_r == $ultimo_numero_cuota_pagada_db && ($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3))
																{
																	if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
																	else $array[$posicion]['accionesv'] = '<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';																	
																}																	
																else
																{																	
																	if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>';
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
																if(($estado_credito_db_res == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_credito_db_res == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']) || $estado_credito_db_res == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang'])) && $numero_cuota_db_r == $ultimo_numero_cuota_pagada_db && ($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3))
																{																	
																	if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
																	else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
																}
																else
																{
																	if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnVerAvisosDeudaCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
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
																if(($estado_credito_db_res == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_credito_db_res == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']) || $estado_credito_db_res == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang'])) && $numero_cuota_db_r == $ultimo_numero_cuota_pagada_db && ($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3))
																{
																	if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';
																	else $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnCancelarPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Pay_Fee_Credit',$GLOBALS['lang']).'" onclick="cancelarPagoCuotaCredito('.$id_cuota_credito_db.')"><i class="far fa-window-close"></i></button>';																	
																}
																else
																{
																	if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" id="btnVerInteresesCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuota'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" id="btnReimprimirPagoCuotaPDF'.$id_cuota_credito_db.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>';
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
									
									if($stmt69 = $mysqli->prepare("SELECT cc.fecha_vencimiento FROM finan_cli.cuota_credito cc WHERE cc.id_credito = ? AND cc.estado IN (?,?) ORDER BY cc.numero_cuota"))
									{
										$estado_p_1 = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
										$estado_p_2 = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
										$stmt69->bind_param('iss', $idCredito, $estado_p_1, $estado_p_2);
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
										echo translate('Msg_Unknown_Error',$GLOBALS['lang']).'ACA 1';
										return;
									}						
										
									$stmt650->free_result();
									$stmt650->close();
									
									echo translate('Msg_Supervisor_OK',$GLOBALS['lang']).'=:=:='.$estado_credito_db_res.'=::=::='.json_encode($array).'=:::=:::='.$totR69;
									return;																		
								}
								else
								{
									echo translate('Msg_Unknown_Error',$GLOBALS['lang']).'ACA 2';
									return;
								}
							}
							else
							{
								echo translate('Msg_Unknown_Error',$GLOBALS['lang']).'ACA 3';
								return;
							}
						}
						else
						{
							echo translate('Msg_Unknown_Error',$GLOBALS['lang']).'ACA 4';
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
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']).'ACA 5';
			return;				
		}
		
		return;
?>