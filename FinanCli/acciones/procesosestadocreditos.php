<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		// ¡Oh, no! Existe un error 'connect_errno', fallando así el intento de conexión
		if ($mysqli->connect_errno) 
		{
			echo translate('Msg_Connect_DB_Error',$GLOBALS['lang']);
			return;
		}
		
		$tokenProceso=htmlspecialchars($_GET["tokenProceso"], ENT_QUOTES, 'UTF-8');
			
		if(empty($tokenProceso))
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}		

		if ($stmt72 = $mysqli->prepare("SELECT valor FROM finan_cli.parametros WHERE nombre = ?")) 
		{
			$nombreValPar = 'cantidad_horas_entre_procesos_auto';
			$stmt72->bind_param('s', $nombreValPar);
			$stmt72->execute(); 
			$stmt72->store_result();
			
			$totR72 = $stmt72->num_rows;

			if($totR72 > 0)
			{
				$stmt72->bind_result($cantidad_horas_entre_procesos_db);
				$stmt72->fetch();

				$stmt72->free_result();
				$stmt72->close();				
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
		
		if ($stmt73 = $mysqli->prepare("SELECT id FROM finan_cli.parametros WHERE nombre = ? AND valor = ?")) 
		{
			$nombreValPar = 'token_proceso_automatico';
			$stmt73->bind_param('ss', $nombreValPar, $tokenProceso);
			$stmt73->execute();    
			$stmt73->store_result();
	 			
			$totR73 = $stmt73->num_rows;

			if($totR73 > 0)
			{
				$stmt73->bind_result($parameter_id);
				$stmt73->fetch();

				$stmt73->free_result();
				$stmt73->close();				
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
		
		if ($stmt74 = $mysqli->prepare("SELECT MAX(fecha) FROM finan_cli.ejecucion_procesos_auto WHERE tipo = 1")) 
		{
			$stmt74->execute();
			$stmt74->store_result();
	 			
			$totR74 = $stmt74->num_rows;

			if($totR74 > 0)
			{
				$stmt74->bind_result($fecha_ultimo_proceso);
				$stmt74->fetch();
			
				$fechaObtDB = substr($fecha_ultimo_proceso, 0, 4).'-'.substr($fecha_ultimo_proceso, 4, 2).'-'.substr($fecha_ultimo_proceso, 6, 2).' '.substr($fecha_ultimo_proceso, 8, 2).':'.substr($fecha_ultimo_proceso, 10, 2).':'.substr($fecha_ultimo_proceso, 12, 2);
				$fechaInfDB = new DateTime($fechaObtDB);
				$fechaAct = new DateTime();
				$difHoras = $fechaAct->diff($fechaInfDB);
				
				if($difHoras->h < $cantidad_horas_entre_procesos_db && $difHoras->days == 0)
				{
					echo str_replace("%1",$cantidad_horas_entre_procesos_db,translate('Msg_The_Automatic_Process_Runs_Every_Hours',$GLOBALS['lang']));
					return;
				}
				
				$stmt74->free_result();
				$stmt74->close();
			}			
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}

		if($stmt = $mysqli->prepare("SELECT cc.id, cc.estado, cc.fecha_vencimiento, cc.numero_cuota, c.cantidad_cuotas, c.id_plan_credito, cc.monto_cuota_original, c.id FROM finan_cli.cuota_credito cc, finan_cli.credito c WHERE cc.id_credito = c.id AND cc.estado IN (?,?) ORDER BY cc.fecha_vencimiento"))
		{
			$estadoEM = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
			$estadoPEND = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
			$stmt->bind_param('ss', $estadoEM, $estadoPEND);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
									
				$date_registro = date("YmdHis");					
				if(!$stmt2 = $mysqli->prepare("INSERT INTO finan_cli.ejecucion_procesos_auto(fecha,comentario,tipo) VALUES (?,?,?)"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$tipoProcesoA = 1;
					$comentario = translate('Msg_There_Are_No_Debts_To_Process',$GLOBALS['lang']);
					$stmt2->bind_param('ssi', $date_registro, $comentario, $tipoProcesoA);
					if(!$stmt2->execute())
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
				
				return;
			}
			else
			{
				$stmt->bind_result($id_cuota_credito_db, $estado_cuota_credito_db, $fecha_vencimiento_cuota_credito_db, $numero_cuota_credito_db, $cantidad_cuotas_credito_db, $id_plan_credito_db, $monto_cuota_original_db, $id_credito_db);
				
				$pasoDiasVencidosCuota = 0;
				while($stmt->fetch())
				{
					$fechaObtDB = substr($fecha_vencimiento_cuota_credito_db, 0, 4).'-'.substr($fecha_vencimiento_cuota_credito_db, 4, 2).'-'.substr($fecha_vencimiento_cuota_credito_db, 6, 2).' '.substr($fecha_vencimiento_cuota_credito_db, 8, 2).':'.substr($fecha_vencimiento_cuota_credito_db, 10, 2).':'.substr($fecha_vencimiento_cuota_credito_db, 12, 2);
					$fechaInfDB = new DateTime($fechaObtDB);
					$fechaAct = new DateTime();
					$difDias = $fechaAct->diff($fechaInfDB);
					$fechaActNumber = strtotime(date("Y-m-d H:i:s"));
					$fechaVencimCuotaAc = strtotime($fechaObtDB);
					
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
							echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
							return;					
						}					
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;
					}					
					
					//echo $numero_cuota_credito_db.' - '.$id_cuota_credito_db.' - '.$fecha_vencimiento_cuota_credito_db.' - '.$difDias->days.' - '.$fechaActNumber.'</br>';
					/**
					if($id_cuota_credito_db == 11)
					{
						echo $numero_cuota_credito_db.' - '.$id_cuota_credito_db.' - '.$fecha_vencimiento_cuota_credito_db.' - '.$difDias->days.'</br>';
						echo $fechaActNumber.' - '.$fechaVencimCuotaAc.' - '.$difDias->days.' - '.$cantidad_dias_para_mora_db.'</br>';
						return;
					}
					*/
					
					if($fechaActNumber > $fechaVencimCuotaAc && $difDias->days >= $cantidad_dias_para_mora_db)
					{
						$pasoDiasVencidosCuota = 1;
						if($stmt79 = $mysqli->prepare("SELECT p.valor FROM finan_cli.parametros p WHERE p.nombre = ?"))
						{
							$nombreValPara = 'cantidad_dias_cuota_incobrable';
							$stmt79->bind_param('s', $nombreValPara);
							$stmt79->execute();    
							$stmt79->store_result();
							
							$totR79 = $stmt79->num_rows;

							if($totR79 > 0)
							{
								$stmt79->bind_result($cantidad_dias_para_cuota_incobrable_db);
								$stmt79->fetch();
																
								$stmt79->free_result();
								$stmt79->close();
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
						
						if($difDias->days >= $cantidad_dias_para_cuota_incobrable_db)
						{
							$esUltimaCuota = 0;
							if($numero_cuota_credito_db == $cantidad_cuotas_credito_db) $esUltimaCuota = 1;
							
							$mysqli->autocommit(FALSE);
							$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
							
							if(!$stmt2 = $mysqli->prepare("UPDATE finan_cli.cuota_credito SET estado = ? WHERE id = ?"))
							{
								echo $mysqli->error;
								$mysqli->autocommit(TRUE);
								$stmt->free_result();
								$stmt->close();
								return;
							}
							else
							{
								$estadoCInc = translate('Lbl_Status_Fee_Incobrable',$GLOBALS['lang']);
								$stmt2->bind_param('si', $estadoCInc, $id_cuota_credito_db);
								if(!$stmt2->execute())
								{
									echo $mysqli->error;
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;						
								}
							}
							
							if($esUltimaCuota == 1)
							{
								if($stmt17 = $mysqli->prepare("SELECT cc.estado FROM finan_cli.cuota_credito cc WHERE cc.id_credito = ? ORDER BY cc.numero_cuota"))
								{
									$stmt17->bind_param('i', $id_credito_db);
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
									$stmt43->bind_param('si', $estadoF, $id_credito_db);
									if(!$stmt43->execute())
									{
										echo $mysqli->error;
										$mysqli->rollback();
										$mysqli->autocommit(TRUE);
										return;						
									}

									$date_registro = date("YmdHis");				
									$valor_log_user = "UPDATE finan_cli.credito SET estado = ".$estadoF." WHERE id = ".$id_credito_db;

									if(!$stmt2 = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
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
										$motivo2 = 69;
										$usuarioP = translate('Lbl_User_Automatic',$GLOBALS['lang']);
										$stmt2->bind_param('ssis', $usuarioP, $date_registro, $motivo2, $valor_log_user);
										if(!$stmt2->execute())
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
						}
						else
						{
							if($stmt40 = $mysqli->prepare("SELECT ixm.interes, ixm.cantidad_dias FROM finan_cli.interes_x_mora ixm WHERE ixm.id_plan_credito = ? AND ixm.cantidad_dias <= ?"))
							{
								$stmt40->bind_param('ii', $id_plan_credito_db, $difDias->days);
								$stmt40->execute();    
								$stmt40->store_result();
								
								$totR40 = $stmt40->num_rows;

								if($totR40 > 0)
								{
									$stmt40->bind_result($interes_x_mora_db, $cantidad_dias_interes_x_mora_db);
									while($stmt40->fetch())
									{
										if($stmt41 = $mysqli->prepare("SELECT ixmcc.id, ixmcc.fecha FROM finan_cli.interes_x_mora_cuota_credito ixmcc WHERE ixmcc.cantidad_dias_mora = ?"))
										{
											$stmt41->bind_param('i', $cantidad_dias_interes_x_mora_db);
											$stmt41->execute();    
											$stmt41->store_result();
											
											$totR41 = $stmt41->num_rows;

											if($totR41 > 0)
											{
												$stmt41->bind_result($id_interes_x_mora_cc_db, $fecha_interes_x_mora_cc_db);
												$stmt41->fetch();
												
												$mysqli->autocommit(FALSE);
												$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
																	
												if(!$stmt2 = $mysqli->prepare("UPDATE finan_cli.cuota_credito SET estado = ? WHERE id = ?"))
												{
													echo $mysqli->error;
													$mysqli->autocommit(TRUE);
													$stmt->free_result();
													$stmt->close();
													return;
												}
												else
												{
													$stmt2->bind_param('si', $estadoEM, $id_cuota_credito_db);
													if(!$stmt2->execute())
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
												
												$stmt41->free_result();
												$stmt41->close();
											}
											else
											{
												$montoInteresAplicado = round((($monto_cuota_original_db*$interes_x_mora_db)/100));
												
												if($stmt64 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM finan_cli.mora_cuota_credito mcc WHERE mcc.id_cuota_credito = ?"))
												{
													$stmt64->bind_param('i', $id_cuota_credito_db);
													$stmt64->execute();    
													$stmt64->store_result();
													
													$totR64 = $stmt64->num_rows;
													if($totR64 == 1)
													{
														$stmt64->bind_result($monto_interes_anterior_cuota_credito_db);
														$stmt64->fetch();
																											
														$stmt64->free_result();
														$stmt64->close();				
													}
													else $monto_interes_anterior_cuota_credito_db = 0;
												}
												else
												{
													echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
													return;
												}	
												
												$mysqli->autocommit(FALSE);
												$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
																	
												if(!$stmt2 = $mysqli->prepare("UPDATE finan_cli.cuota_credito SET estado = ? WHERE id = ?"))
												{
													echo $mysqli->error;
													$mysqli->autocommit(TRUE);
													$stmt->free_result();
													$stmt->close();
													return;
												}
												else
												{
													$stmt2->bind_param('si', $estadoEM, $id_cuota_credito_db);
													if(!$stmt2->execute())
													{
														echo $mysqli->error;
														$mysqli->autocommit(TRUE);
														$stmt->free_result();
														$stmt->close();
														return;						
													}
												}
												
												if(!$stmt2 = $mysqli->prepare("INSERT INTO finan_cli.interes_x_mora_cuota_credito(fecha,id_cuota_credito,cantidad_dias_mora,interes_x_mora,id_plan_credito,cantidad_dias_en_mora) VALUES (?,?,?,?,?,?)"))
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
													$date_registro = date("YmdHis");
													$stmt2->bind_param('siiiii', $date_registro, $id_cuota_credito_db, $cantidad_dias_interes_x_mora_db, $interes_x_mora_db, $id_plan_credito_db, $difDias->days);
													if(!$stmt2->execute())
													{
														echo $mysqli->error;
														$mysqli->rollback();
														$mysqli->autocommit(TRUE);
														$stmt->free_result();
														$stmt->close();
														return;						
													}
												}

												if(!$stmt2 = $mysqli->prepare("INSERT INTO finan_cli.mora_cuota_credito(id_cuota_credito,fecha_interes,monto_interes,porcentaje_interes) VALUES (?,?,?,?)"))
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
													$date_registro = date("YmdHis");
													$stmt2->bind_param('isii', $id_cuota_credito_db, $date_registro, $montoInteresAplicado, $interes_x_mora_db);
													if(!$stmt2->execute())
													{
														echo $mysqli->error;
														$mysqli->rollback();
														$mysqli->autocommit(TRUE);
														$stmt->free_result();
														$stmt->close();
														return;						
													}
												}

												if($stmt51 = $mysqli->prepare("SELECT axm.id, axm.mensaje FROM finan_cli.aviso_x_mora axm WHERE axm.id_cuota_credito = ? AND axm.estado IN (?,?)"))
												{
													$estadoAXMPend = translate('Lbl_State_Pending_Default_Notice',$GLOBALS['lang']);
													$estadoAXMCread = translate('Lbl_State_Create_Default_Notice',$GLOBALS['lang']);
													$stmt51->bind_param('iss', $id_cuota_credito_db, $estadoAXMPend, $estadoAXMCread);
													$stmt51->execute();    
													$stmt51->store_result();
													
													$totR51 = $stmt51->num_rows;

													if($totR51 > 0)
													{
														$stmt51->bind_result($id_aviso_x_mora_db, $mensaje_aviso_x_mora_db);
														$stmt51->fetch();
														
														if(!$stmt2 = $mysqli->prepare("UPDATE finan_cli.aviso_x_mora SET fecha_modificacion = ?, mensaje = ? WHERE id = ?"))
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
															$date_registro = date("YmdHis");
															$mensajeNuevo = str_replace("%1",$numero_cuota_credito_db,translate('Msg_It_Is_Reported_Installment_Has_Pending_Debt',$GLOBALS['lang']));
															$mensajeNuevo = str_replace("%2",$id_credito_db,$mensajeNuevo);
															$mensajeNuevo = str_replace("%3",number_format((($monto_cuota_original_db+$montoInteresAplicado+$monto_interes_anterior_cuota_credito_db)/100.00), 2, ',', '.'),$mensajeNuevo);
															$stmt2->bind_param('ssi', $date_registro, $mensajeNuevo, $id_aviso_x_mora_db);
															if(!$stmt2->execute())
															{
																echo $mysqli->error;
																$mysqli->rollback();
																$mysqli->autocommit(TRUE);
																$stmt->free_result();
																$stmt->close();
																return;						
															}
														}

														$date_registro = date("YmdHis");				
														$valor_log_user = "ANTERIOR: UPDATE finan_cli.aviso_x_mora SET mensaje = ".$mensaje_aviso_x_mora_db." WHERE id = ".$id_aviso_x_mora_db." -- NUEVO: UPDATE finan_cli.aviso_x_mora SET mensaje = ".$mensajeNuevo." WHERE id = ".$id_aviso_x_mora_db;
														if(!$stmt2 = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
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
															$motivo = 85;
															$usuarioP = translate('Lbl_User_Automatic',$GLOBALS['lang']);
															$stmt2->bind_param('ssis', $usuarioP, $date_registro, $motivo, $valor_log_user);
															if(!$stmt2->execute())
															{
																echo $mysqli->error;
																$mysqli->rollback();
																$mysqli->autocommit(TRUE);
																$stmt->free_result();
																$stmt->close();
																return;						
															}
														}													
														
														$stmt51->free_result();
														$stmt51->close();
													}
													else
													{
														if(!$stmt2 = $mysqli->prepare("INSERT INTO finan_cli.aviso_x_mora(id_credito,fecha,estado,id_cuota_credito,mensaje,id_tipo_aviso) VALUES (?,?,?,?,?,?)"))
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
															$date_registro = date("YmdHis");
															$mensajeNuevo = str_replace("%1",$numero_cuota_credito_db,translate('Msg_It_Is_Reported_Installment_Has_Pending_Debt',$GLOBALS['lang']));
															$mensajeNuevo = str_replace("%2",$id_credito_db,$mensajeNuevo);
															$mensajeNuevo = str_replace("%3",number_format((($monto_cuota_original_db+$montoInteresAplicado+$monto_interes_anterior_cuota_credito_db)/100.00), 2, ',', '.'),$mensajeNuevo);
															$estadoAXMCr = translate('Lbl_State_Create_Default_Notice',$GLOBALS['lang']);
															$tipoAviso = 1;
															$stmt2->bind_param('issisi', $id_credito_db, $date_registro, $estadoAXMCr, $id_cuota_credito_db, $mensajeNuevo, $tipoAviso);
															if(!$stmt2->execute())
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
												else
												{
													echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
													return;
												}
																												
												$mysqli->commit();
												$mysqli->autocommit(TRUE);											
											}					
										}
										else
										{
											echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
											return;
										}									
									}
									
									$stmt40->free_result();
									$stmt40->close();
								}
								else
								{
									$mysqli->autocommit(FALSE);
									$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
														
									if(!$stmt2 = $mysqli->prepare("UPDATE finan_cli.cuota_credito SET estado = ? WHERE id = ?"))
									{
										echo $mysqli->error;
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										return;
									}
									else
									{
										$stmt2->bind_param('si', $estadoEM, $id_cuota_credito_db);
										if(!$stmt2->execute())
										{
											echo $mysqli->error;
											$mysqli->autocommit(TRUE);
											$stmt->free_result();
											$stmt->close();
											return;						
										}
									}
									
									$date_registro = date("YmdHis");				
									$valor_log_user = str_replace("%1",$id_cuota_credito_db,translate('Msg_Credit_Plan_Does_Not_Have_Defined_Surcharge',$GLOBALS['lang']));
									$valor_log_user = str_replace("%2",$cantidad_dias_para_mora_db,$valor_log_user);
									if(!$stmt2 = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
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
										$motivo = 84;
										$usuarioP = translate('Lbl_User_Automatic',$GLOBALS['lang']);
										$stmt2->bind_param('ssis', $usuarioP, $date_registro, $motivo, $valor_log_user);
										if(!$stmt2->execute())
										{
											echo $mysqli->error;
											$mysqli->rollback();
											$mysqli->autocommit(TRUE);
											$stmt->free_result();
											$stmt->close();
											return;						
										}
									}
															
									$mysqli->commit();
									$mysqli->autocommit(TRUE);												
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
						if($estado_cuota_credito_db == $estadoEM)
						{
							$mysqli->autocommit(FALSE);
							$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
												
							if(!$stmt2 = $mysqli->prepare("UPDATE finan_cli.cuota_credito SET estado = ? WHERE id = ?"))
							{
								echo $mysqli->error;
								$mysqli->autocommit(TRUE);
								$stmt->free_result();
								$stmt->close();
								return;
							}
							else
							{
								$stmt2->bind_param('si', $estadoPEND, $id_cuota_credito_db);
								if(!$stmt2->execute())
								{
									echo $mysqli->error;
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;						
								}
							}
							
							$date_registro = date("YmdHis");				
							$valor_log_user = "ANTERIOR: UPDATE finan_cli.cuota_credito SET estado = ".$estado_cuota_credito_db." WHERE id = ".$id_cuota_credito_db." -- NUEVO: UPDATE finan_cli.cuota_credito SET estado = ".$estadoPEND." WHERE id = ".$id_cuota_credito_db;
							if(!$stmt2 = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
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
								$motivo = 83;
								$usuarioP = translate('Lbl_User_Automatic',$GLOBALS['lang']);
								$stmt2->bind_param('ssis', $usuarioP, $date_registro, $motivo, $valor_log_user);
								if(!$stmt2->execute())
								{
									echo $mysqli->error;
									$mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;						
								}
							}
													
							$mysqli->commit();
							$mysqli->autocommit(TRUE);							
						}
					}
				}
				
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
									
				$date_registro = date("YmdHis");					
				if(!$stmt2 = $mysqli->prepare("INSERT INTO finan_cli.ejecucion_procesos_auto(fecha,comentario,tipo) VALUES (?,?,?)"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$tipoProcesoA = 1;
					if($pasoDiasVencidosCuota == 1) $comentario = translate('Msg_The_Automatic_Process_Was_Executed_Correctly',$GLOBALS['lang']);
					else $comentario = translate('Msg_No_Debt_Was_Found_To_Process',$GLOBALS['lang']);
					$stmt2->bind_param('ssi', $date_registro, $comentario, $tipoProcesoA);
					if(!$stmt2->execute())
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
				
				$stmt->free_result();
				$stmt->close();
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