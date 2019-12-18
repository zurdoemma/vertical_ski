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

		if ($stmt72 = $mysqli->prepare("SELECT valor FROM ".$db_name.".parametros WHERE nombre = ?")) 
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
		
		if ($stmt73 = $mysqli->prepare("SELECT id FROM ".$db_name.".parametros WHERE nombre = ? AND valor = ?")) 
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
		
		if ($stmt174 = $mysqli->prepare("SELECT MAX(fecha) FROM ".$db_name.".control_ejecucion_procesos WHERE tipo_proceso = 2 HAVING MAX(fecha) IS NOT NULL")) 
		{
			$stmt174->execute();
			$stmt174->store_result();
	 			
			$totR174 = $stmt174->num_rows;

			if($totR174 > 0)
			{
				$stmt174->bind_result($fecha_ultimo_proceso_para);
				$stmt174->fetch();
			
				$fechaObtDB = substr($fecha_ultimo_proceso_para, 0, 4).'-'.substr($fecha_ultimo_proceso_para, 4, 2).'-'.substr($fecha_ultimo_proceso_para, 6, 2).' '.substr($fecha_ultimo_proceso_para, 8, 2).':'.substr($fecha_ultimo_proceso_para, 10, 2).':'.substr($fecha_ultimo_proceso_para, 12, 2);
				$fechaInfDB = new DateTime($fechaObtDB);
				$fechaAct = new DateTime();
				$difHoras = $fechaAct->diff($fechaInfDB);
				
				if($difHoras->h <= $cantidad_horas_entre_procesos_db && $difHoras->days == 0)
				{
					echo str_replace("%1",$cantidad_horas_entre_procesos_db,translate('Msg_The_Automatic_Process_Runs_Every_Hours',$GLOBALS['lang']));
					return;
				}
				else
				{
					$mysqli->autocommit(FALSE);
					$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
										
					$date_registro = date("YmdHis");					
					if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".control_ejecucion_procesos SET fecha = ? WHERE tipo_proceso = ?"))
					{
						echo $mysqli->error;
						$mysqli->autocommit(TRUE);
						return;
					}
					else
					{
						$tipoProcesoCP = 2;
						$stmt20->bind_param('si', $date_registro, $tipoProcesoCP);
						if(!$stmt20->execute())
						{
							echo $mysqli->error;
							$mysqli->autocommit(TRUE);
							return;						
						}
					}
																
					$mysqli->commit();
					$mysqli->autocommit(TRUE);			
				}
				
				$stmt174->free_result();
				$stmt174->close();
			}
			else
			{
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
									
				$date_registro = date("YmdHis");					
				if(!$stmt20 = $mysqli->prepare("INSERT INTO ".$db_name.".control_ejecucion_procesos(fecha, tipo_proceso) VALUES(?,?)"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					return;
				}
				else
				{
					$tipoProcesoCP = 2;
					$stmt20->bind_param('si', $date_registro, $tipoProcesoCP);
					if(!$stmt20->execute())
					{
						echo $mysqli->error;
						$mysqli->autocommit(TRUE);
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
		
		if ($stmt75 = $mysqli->prepare("SELECT valor FROM ".$db_name.".parametros WHERE nombre = ?")) 
		{
			$nombreValPar = 'cantidad_dias_avisos_x_mora';
			$stmt75->bind_param('s', $nombreValPar);
			$stmt75->execute();    
			$stmt75->store_result();
	 			
			$totR75 = $stmt75->num_rows;

			if($totR75 > 0)
			{
				$stmt75->bind_result($cantidad_dias_permitidos_aviso_x_mora_db);
				$stmt75->fetch();

				$stmt75->free_result();
				$stmt75->close();				
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
		
		if ($stmt74 = $mysqli->prepare("SELECT MAX(fecha) FROM ".$db_name.".ejecucion_procesos_auto WHERE tipo = 2 HAVING MAX(fecha) IS NOT NULL")) 
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

		if($stmt51 = $mysqli->prepare("SELECT axm.id, axm.mensaje, axm.id_cuota_credito, axm.estado, axm.id_tipo_aviso, axm.id_credito, axm.fecha, axm.fecha_modificacion FROM ".$db_name.".aviso_x_mora axm WHERE axm.estado IN (?,?) ORDER BY axm.fecha"))
		{
			$estadoAXMPend = translate('Lbl_State_Pending_Default_Notice',$GLOBALS['lang']);
			$estadoAXMCread = translate('Lbl_State_Create_Default_Notice',$GLOBALS['lang']);
			$stmt51->bind_param('ss', $estadoAXMPend, $estadoAXMCread);
			$stmt51->execute();    
			$stmt51->store_result();
			
			$totR51 = $stmt51->num_rows;

			if($totR51 > 0)
			{
				$stmt51->bind_result($id_aviso_x_mora_db, $mensaje_aviso_x_mora_db, $id_cuota_credito_aviso_x_mora_db, $estado_aviso_x_mora_db, $id_tipo_aviso_x_mora_db, $id_credito_aviso_x_mora_db, $fecha_aviso_x_mora_control_db, $fecha_modificacion_aviso_x_mora_control_db);
				while($stmt51->fetch())
				{						
					if($stmt152 = $mysqli->prepare("SELECT id, estado FROM ".$db_name.".cuota_credito WHERE id = ? AND estado IN (?,?,?)"))
					{
						$estadoIncobrable = translate('Lbl_Status_Fee_Incobrable',$GLOBALS['lang']);
						$estadoCondonada = translate('Lbl_Status_Fee_Condoned',$GLOBALS['lang']);
						$estadoPagada = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
						$stmt152->bind_param('isss', $id_cuota_credito_aviso_x_mora_db, $estadoIncobrable, $estadoCondonada, $estadoPagada);
						$stmt152->execute();    
						$stmt152->store_result();
						
						$totR152 = $stmt152->num_rows;

						if($totR152 > 0)
						{
							$stmt152->bind_result($id_cambio_estado_cuota_credito_db, $estado_final_cuota_credito_db);
							$stmt152->fetch();

							$mysqli->autocommit(FALSE);
							$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
												
							$date_registro = date("YmdHis");					
							if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".aviso_x_mora SET estado = ?, comentario = ?, fecha_modificacion = ? WHERE id = ?"))
							{
								echo $mysqli->error;
								$mysqli->autocommit(TRUE);
								$stmt51->free_result();
								$stmt51->close();
								return;
							}
							else
							{
								$estadoFinalizado = translate('Lbl_State_Finished_Default_Notice',$GLOBALS['lang']);
								$comentario3 = translate('The_Quota_Has_Changed_The_Final_State',$GLOBALS['lang']).': '.$estado_final_cuota_credito_db;
								$stmt20->bind_param('sssi', $estadoFinalizado, $comentario3, $date_registro, $id_aviso_x_mora_db);
								if(!$stmt20->execute())
								{
									echo $mysqli->error;
									$mysqli->autocommit(TRUE);
									$stmt51->free_result();
									$stmt51->close();
									return;						
								}
							}
							
							$date_registro = date("YmdHis");					
							if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".envio_sms SET estado = ?, comentario = ?, fecha_modificacion = ? WHERE id_aviso_x_mora = ? AND estado <> ?"))
							{
								echo $mysqli->error;
								$mysqli->rollback();
								$mysqli->autocommit(TRUE);
								$stmt51->free_result();
								$stmt51->close();
								return;
							}
							else
							{
								$estadoFinalizado = translate('Lbl_State_Finished_Default_Notice',$GLOBALS['lang']);
								$comentario3 = translate('The_Quota_Has_Changed_The_Final_State',$GLOBALS['lang']).': '.$estado_final_cuota_credito_db;
								$stmt20->bind_param('sssis', $estadoFinalizado, $comentario3, $date_registro, $id_aviso_x_mora_db, $estadoFinalizado);
								if(!$stmt20->execute())
								{
									echo $mysqli->error;
									$mysqli->rollback();
									$mysqli->autocommit(TRUE);
									$stmt51->free_result();
									$stmt51->close();
									return;						
								}
							}										
													
							$mysqli->commit();
							$mysqli->autocommit(TRUE);								
							
							$stmt152->free_result();
							$stmt152->close();
							continue;
						}					
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;
					}					
					
					
					
					if(!empty($fecha_modificacion_aviso_x_mora_control_db)) $fecha_comparacion_final = $fecha_modificacion_aviso_x_mora_control_db;
					else $fecha_comparacion_final = $fecha_aviso_x_mora_control_db;
						
					$fechaObtDB = substr($fecha_comparacion_final, 0, 4).'-'.substr($fecha_comparacion_final, 4, 2).'-'.substr($fecha_comparacion_final, 6, 2).' '.substr($fecha_comparacion_final, 8, 2).':'.substr($fecha_comparacion_final, 10, 2).':'.substr($fecha_comparacion_final, 12, 2);
					$fechaInfDB = new DateTime($fechaObtDB);
					$fechaAct = new DateTime();
					$difDays = $fechaAct->diff($fechaInfDB);
					
					if($difDays->days > $cantidad_dias_permitidos_aviso_x_mora_db)
					{
						$mysqli->autocommit(FALSE);
						$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
											
						$date_registro = date("YmdHis");					
						if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".aviso_x_mora SET estado = ?, comentario = ?, fecha_modificacion = ? WHERE id = ?"))
						{
							echo $mysqli->error;
							$mysqli->autocommit(TRUE);
							$stmt51->free_result();
							$stmt51->close();
							return;
						}
						else
						{
							$estadoFinalizado = translate('Lbl_State_Finished_Default_Notice',$GLOBALS['lang']);
							$comentario3 = translate('Msg_Allowed_Days_Of_Validity_For_The_Notice_Were_Exceeded',$GLOBALS['lang']);
							$stmt20->bind_param('sssi', $estadoFinalizado, $comentario3, $date_registro, $id_aviso_x_mora_db);
							if(!$stmt20->execute())
							{
								echo $mysqli->error;
								$mysqli->autocommit(TRUE);
								$stmt51->free_result();
								$stmt51->close();
								return;						
							}
						}
						
						$date_registro = date("YmdHis");					
						if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".envio_sms SET estado = ?, comentario = ?, fecha_modificacion = ? WHERE id_aviso_x_mora = ? AND estado <> ?"))
						{
							echo $mysqli->error;
							$mysqli->rollback();
							$mysqli->autocommit(TRUE);
							$stmt51->free_result();
							$stmt51->close();
							return;
						}
						else
						{
							$estadoFinalizado = translate('Lbl_State_Finished_Default_Notice',$GLOBALS['lang']);
							$comentario3 = translate('Msg_Allowed_Days_Of_Validity_For_The_Notice_Were_Exceeded',$GLOBALS['lang']);
							$stmt20->bind_param('sssis', $estadoFinalizado, $comentario3, $date_registro, $id_aviso_x_mora_db, $estadoFinalizado);
							if(!$stmt20->execute())
							{
								echo $mysqli->error;
								$mysqli->rollback();
								$mysqli->autocommit(TRUE);
								$stmt51->free_result();
								$stmt51->close();
								return;						
							}
						}										
												
						$mysqli->commit();
						$mysqli->autocommit(TRUE);									
						continue;
					}
							
					if($estado_aviso_x_mora_db == translate('Lbl_State_Create_Default_Notice',$GLOBALS['lang']))
					{
						if($id_tipo_aviso_x_mora_db == 1)
						{
							if ($stmt30 = $mysqli->prepare("SELECT t.id, t.numero FROM ".$db_name.".credito c, ".$db_name.".credito_cliente cc, ".$db_name.".cliente_x_telefono cxt, ".$db_name.".telefono t WHERE c.id = cc.id_credito AND cc.tipo_documento = cxt.tipo_documento AND cc.documento = cxt.documento AND cxt.id_telefono = t.id AND c.id = ? AND cxt.preferido = ? AND t.tipo_telefono = ?")) 
							{
								$telPrefe = 1;
								$tipoTelPre = 1;
								$stmt30->bind_param('iii', $id_credito_aviso_x_mora_db, $telPrefe, $tipoTelPre);
								$stmt30->execute();
								$stmt30->store_result();
									
								$totR30 = $stmt30->num_rows;

								if($totR30 > 0)
								{
									$stmt30->bind_result($id_telefono_aviso_x_mora_db, $numero_telefono_aviso_x_mora_db);
									$stmt30->fetch();
								
									$resultadoEnvioSMS = envio_sms_auto(translate('Lbl_From_SMS_ID_Sent',$GLOBALS['lang']), $numero_telefono_aviso_x_mora_db, $mensaje_aviso_x_mora_db);
									$codigoRespuestaEnvSMS = substr($resultadoEnvioSMS, strpos($resultadoEnvioSMS, '=:=:=')+5);
									$resultadoEnvioSMS = str_replace('=:=:='.$codigoRespuestaEnvSMS, "", $resultadoEnvioSMS);
								
									$p = xml_parser_create();
									xml_parse_into_struct($p, $resultadoEnvioSMS, $vals, $index);
									xml_parser_free($p);
									//echo $vals[2]['value'].' -- '.$codigoRespuestaEnvSMS;
									
									if($codigoRespuestaEnvSMS == 200)
									{
										$mysqli->autocommit(FALSE);
										$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
															
										$date_registro = date("YmdHis");					
										if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".aviso_x_mora SET estado = ?, comentario = ?, fecha_modificacion = ? WHERE id = ?"))
										{
											echo $mysqli->error;
											$mysqli->autocommit(TRUE);
											$stmt51->free_result();
											$stmt51->close();
											return;
										}
										else
										{
											$estadoPendiente = translate('Lbl_State_Pending_Default_Notice',$GLOBALS['lang']);
											$comentario = translate('Msg_Was_Sent_Default_Notice',$GLOBALS['lang']);
											$stmt20->bind_param('sssi', $estadoPendiente, $comentario, $date_registro, $id_aviso_x_mora_db);
											if(!$stmt20->execute())
											{
												echo $mysqli->error;
												$mysqli->autocommit(TRUE);
												$stmt51->free_result();
												$stmt51->close();
												return;						
											}
										}
										
										$date_registro = date("YmdHis");					
										if(!$stmt20 = $mysqli->prepare("INSERT ".$db_name.".envio_sms(id_aviso_x_mora,id_telefono,estado,comentario,codigo_respuesta,fecha,cantidad_reintentos,id_sms) VALUES(?,?,?,?,?,?,?,?)"))
										{
											echo $mysqli->error;
											$mysqli->rollback();
											$mysqli->autocommit(TRUE);
											$stmt51->free_result();
											$stmt51->close();
											return;
										}
										else
										{
											$estadoEnviado = translate('Lbl_State_Sended_Default_Notice',$GLOBALS['lang']);
											$comentario = translate('Msg_Message_Sent_Succesfully',$GLOBALS['lang']);
											$codigoRespuestaSMS = 200;
											$cantidadReintSMS = 0;
											$stmt20->bind_param('iissisis', $id_aviso_x_mora_db, $id_telefono_aviso_x_mora_db, $estadoEnviado, $comentario, $codigoRespuestaSMS, $date_registro, $cantidadReintSMS, $vals[2]['value']);
											if(!$stmt20->execute())
											{
												echo $mysqli->error;
												$mysqli->rollback();
												$mysqli->autocommit(TRUE);
												$stmt51->free_result();
												$stmt51->close();
												return;						
											}
										}										
																
										$mysqli->commit();
										$mysqli->autocommit(TRUE);										
									}
									else
									{
										if($codigoRespuestaEnvSMS == 103) 
										{
											$codigoRespuestaSMS = 103;
											$comentario = translate('Msg_Erroneous_Parameters',$GLOBALS['lang']);
										}
										else if($codigoRespuestaEnvSMS == 109)
										{
											$codigoRespuestaSMS = 109;
											$comentario = translate('Msg_Mandatory_Parameter_Omitted',$GLOBALS['lang']);
										}
										else if($codigoRespuestaEnvSMS == 401)
										{
											$codigoRespuestaSMS = 401;
											$comentario = translate('Msg_Unauthorized_Authentication_Error_Check_Token',$GLOBALS['lang']);
										}											
										else if($codigoRespuestaEnvSMS == 402) 
										{
											$codigoRespuestaSMS = 402;
											$comentario = translate('Msg_Payment_Required_Insufficient_Balance_For_Sending_SMS',$GLOBALS['lang']);										
										}
										else if($codigoRespuestaEnvSMS == 412)
										{
											$codigoRespuestaSMS = 412;
											$comentario = translate('Msg_Precondition_Failed_Unrecognized_Error',$GLOBALS['lang']);
										}
										else if($codigoRespuestaEnvSMS == 404)
										{
											$codigoRespuestaSMS = 404;
											$comentario = translate('Msg_Not_Found_SMS_ID_Sent',$GLOBALS['lang']);
										}
										else 
										{
											$codigoRespuestaSMS = -1;
											$comentario = translate('Msg_Unknown_Error',$GLOBALS['lang']);										
										}
										
										$mysqli->autocommit(FALSE);
										$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
															
										$date_registro = date("YmdHis");					
										if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".aviso_x_mora SET estado = ?, comentario = ?, fecha_modificacion = ? WHERE id = ?"))
										{
											echo $mysqli->error;
											$mysqli->autocommit(TRUE);
											$stmt51->free_result();
											$stmt51->close();
											return;
										}
										else
										{
											$estadoPendiente = translate('Lbl_State_Pending_Default_Notice',$GLOBALS['lang']);
											$comentario2 = translate('Msg_Pending_Shipment_Default_Notice',$GLOBALS['lang']);
											$stmt20->bind_param('sssi', $estadoPendiente, $comentario2, $date_registro, $id_aviso_x_mora_db);
											if(!$stmt20->execute())
											{
												echo $mysqli->error;
												$mysqli->autocommit(TRUE);
												$stmt51->free_result();
												$stmt51->close();
												return;						
											}
										}
										
										$date_registro = date("YmdHis");					
										if(!$stmt20 = $mysqli->prepare("INSERT ".$db_name.".envio_sms(id_aviso_x_mora,id_telefono,estado,comentario,codigo_respuesta,fecha,cantidad_reintentos) VALUES(?,?,?,?,?,?,?)"))
										{
											echo $mysqli->error;
											$mysqli->rollback();
											$mysqli->autocommit(TRUE);
											$stmt51->free_result();
											$stmt51->close();
											return;
										}
										else
										{
											$estadoError = translate('Lbl_State_Error_Default_Notice',$GLOBALS['lang']);
											$cantidadReintSMS = 0;
											$stmt20->bind_param('iissisi', $id_aviso_x_mora_db, $id_telefono_aviso_x_mora_db, $estadoError, $comentario, $codigoRespuestaSMS, $date_registro, $cantidadReintSMS);
											if(!$stmt20->execute())
											{
												echo $mysqli->error;
												$mysqli->rollback();
												$mysqli->autocommit(TRUE);
												$stmt51->free_result();
												$stmt51->close();
												return;						
											}
										}										
																
										$mysqli->commit();
										$mysqli->autocommit(TRUE);											
									}
									
									$stmt30->free_result();
									$stmt30->close();
								}
								else
								{
									$mysqli->autocommit(FALSE);
									$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
														
									$date_registro = date("YmdHis");					
									if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".aviso_x_mora SET estado = ?, comentario = ?, fecha_modificacion = ? WHERE id = ?"))
									{
										echo $mysqli->error;
										$mysqli->autocommit(TRUE);
										$stmt51->free_result();
										$stmt51->close();
										return;
									}
									else
									{
										$estadoError = translate('Lbl_State_Error_Default_Notice',$GLOBALS['lang']);
										$comentario = translate('Msg_Cannot_Be_Sent_Because_The_Client_Does_Not_Have_Phone_To_Send_SMS',$GLOBALS['lang']);
										$stmt20->bind_param('sssi', $estadoError, $comentario, $date_registro, $id_aviso_x_mora_db);
										if(!$stmt20->execute())
										{
											echo $mysqli->error;
											$mysqli->autocommit(TRUE);
											$stmt51->free_result();
											$stmt51->close();
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
					else if($estado_aviso_x_mora_db == translate('Lbl_State_Pending_Default_Notice',$GLOBALS['lang']))
					{
						if($id_tipo_aviso_x_mora_db == 1)
						{
							if ($stmt77 = $mysqli->prepare("SELECT es.id, es.id_telefono, es.estado, es.codigo_respuesta, es.cantidad_reintentos, es.id_sms, t.numero FROM ".$db_name.".envio_sms es, ".$db_name.".telefono t WHERE es.id_telefono = t.id AND es.id_aviso_x_mora = ? AND es.estado IN (?,?,?)")) 
							{
								$estadoError = translate('Lbl_State_Error_Default_Notice',$GLOBALS['lang']);
								$estadoEnviado = translate('Lbl_State_Sended_Default_Notice',$GLOBALS['lang']);
								$estadoReenviado = translate('Lbl_State_Forwarded_Default_Notice',$GLOBALS['lang']);
								$stmt77->bind_param('isss', $id_aviso_x_mora_db, $estadoError, $estadoEnviado, $estadoReenviado);
								$stmt77->execute();
								$stmt77->store_result();
									
								$totR77 = $stmt77->num_rows;

								if($totR77 > 0)
								{
									$stmt77->bind_result($id_envio_sms_db, $id_telefono_envio_sms_db, $estado_envio_sms_db, $codigo_respuesta_envio_sms_db, $cantidad_reintentos_envio_sms_db, $id_sms_envio_service_db, $numero_telefono_envio_sms_db);
									$stmt77->fetch();
									
									if ($stmt105 = $mysqli->prepare("SELECT valor FROM ".$db_name.".parametros WHERE nombre = ?")) 
									{
										$nombreValPar = 'cantidad_reintentos_envio_sms';
										$stmt105->bind_param('s', $nombreValPar);
										$stmt105->execute();
										$stmt105->store_result();
											
										$totR105 = $stmt105->num_rows;

										if($totR105 > 0)
										{
											$stmt105->bind_result($cantidad_reintentos_sms_parametros_db);
											$stmt105->fetch();
																						
											$stmt105->free_result();
											$stmt105->close();
										}			
									}
									else 
									{
										echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
										return;
									}									
								
									if($estado_envio_sms_db == translate('Lbl_State_Sended_Default_Notice',$GLOBALS['lang']))
									{										
										$resultadoEstadoEnvioSMS = status_envio_sms($id_sms_envio_service_db);
										$ps = xml_parser_create();
										xml_parse_into_struct($ps, $resultadoEstadoEnvioSMS, $valss, $indexs);
										xml_parser_free($ps);
										
										$fechaEntregaSMS = $valss[2]['value'];
										$resultadoEstadoEnvioSMS = $valss[1]['value'];

										if(strcasecmp($resultadoEstadoEnvioSMS, translate('Lbl_State_Sended_SMS_Default_Notice_OK',$GLOBALS['lang'])) == 0)
										{
											$mysqli->autocommit(FALSE);
											$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
																
											$date_registro = date("YmdHis");					
											if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".aviso_x_mora SET estado = ?, comentario = ?, fecha_modificacion = ? WHERE id = ?"))
											{
												echo $mysqli->error;
												$mysqli->autocommit(TRUE);
												$stmt51->free_result();
												$stmt51->close();
												return;
											}
											else
											{
												$estadoFinalizado = translate('Lbl_State_Finished_Default_Notice',$GLOBALS['lang']);
												$comentario = translate('Msg_Was_Sent_OK_Default_Notice',$GLOBALS['lang']);
												$stmt20->bind_param('sssi', $estadoFinalizado, $comentario, $date_registro, $id_aviso_x_mora_db);
												if(!$stmt20->execute())
												{
													echo $mysqli->error;
													$mysqli->autocommit(TRUE);
													$stmt51->free_result();
													$stmt51->close();
													return;						
												}
											}
											
											$date_registro = date("YmdHis");					
											if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".envio_sms SET estado = ?, comentario = ?, fecha_modificacion = ? WHERE id = ?"))
											{
												echo $mysqli->error;
												$mysqli->rollback();
												$mysqli->autocommit(TRUE);
												$stmt51->free_result();
												$stmt51->close();
												return;
											}
											else
											{
												$estadoFinalizado = translate('Lbl_State_Finished_Default_Notice',$GLOBALS['lang']);
												$comentario = translate('Msg_Was_Sent_OK_Default_Notice',$GLOBALS['lang']);
												$stmt20->bind_param('sssi', $estadoFinalizado, $comentario, $date_registro, $id_envio_sms_db);
												if(!$stmt20->execute())
												{
													echo $mysqli->error;
													$mysqli->rollback();
													$mysqli->autocommit(TRUE);
													$stmt51->free_result();
													$stmt51->close();
													return;						
												}
											}											
																	
											$mysqli->commit();
											$mysqli->autocommit(TRUE);
										}
										else if(strcasecmp($resultadoEstadoEnvioSMS, translate('Lbl_State_Sended_SMS_Default_Notice_ERROR',$GLOBALS['lang'])) == 0)
										{
											$mysqli->autocommit(FALSE);
											$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
																
											$date_registro = date("YmdHis");					
											if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".aviso_x_mora SET comentario = ?, fecha_modificacion = ? WHERE id = ?"))
											{
												echo $mysqli->error;
												$mysqli->autocommit(TRUE);
												$stmt51->free_result();
												$stmt51->close();
												return;
											}
											else
											{
												$comentario = translate('Msg_Error_State_Sent_SMS_Default_Notice',$GLOBALS['lang']);
												$stmt20->bind_param('ssi', $comentario, $date_registro, $id_aviso_x_mora_db);
												if(!$stmt20->execute())
												{
													echo $mysqli->error;
													$mysqli->autocommit(TRUE);
													$stmt51->free_result();
													$stmt51->close();
													return;						
												}
											}
											
											$date_registro = date("YmdHis");					
											if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".envio_sms SET estado = ?, comentario = ?, fecha_modificacion = ? WHERE id = ?"))
											{
												echo $mysqli->error;
												$mysqli->rollback();
												$mysqli->autocommit(TRUE);
												$stmt51->free_result();
												$stmt51->close();
												return;
											}
											else
											{
												$estadoError = translate('Lbl_State_Error_Default_Notice',$GLOBALS['lang']);
												$comentario = translate('Msg_Error_State_Sent_SMS_Default_Notice',$GLOBALS['lang']);
												$stmt20->bind_param('sssi', $estadoError, $comentario, $date_registro, $id_envio_sms_db);
												if(!$stmt20->execute())
												{
													echo $mysqli->error;
													$mysqli->rollback();
													$mysqli->autocommit(TRUE);
													$stmt51->free_result();
													$stmt51->close();
													return;						
												}
											}											
																	
											$mysqli->commit();
											$mysqli->autocommit(TRUE);											
										}
										else if(strcasecmp($resultadoEstadoEnvioSMS, translate('Lbl_State_Sended_SMS_Default_Notice_PENDING',$GLOBALS['lang'])) == 0)
										{
											$mysqli->autocommit(FALSE);
											$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
																
											$date_registro = date("YmdHis");					
											if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".aviso_x_mora SET comentario = ?, fecha_modificacion = ? WHERE id = ?"))
											{
												echo $mysqli->error;
												$mysqli->autocommit(TRUE);
												$stmt51->free_result();
												$stmt51->close();
												return;
											}
											else
											{
												$comentario = translate('Msg_Pending_State_Sent_SMS_Default_Notice',$GLOBALS['lang']);
												$stmt20->bind_param('ssi', $comentario, $date_registro, $id_aviso_x_mora_db);
												if(!$stmt20->execute())
												{
													echo $mysqli->error;
													$mysqli->autocommit(TRUE);
													$stmt51->free_result();
													$stmt51->close();
													return;						
												}
											}
											
											$date_registro = date("YmdHis");					
											if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".envio_sms SET estado = ?, comentario = ?, fecha_modificacion = ? WHERE id = ?"))
											{
												echo $mysqli->error;
												$mysqli->rollback();
												$mysqli->autocommit(TRUE);
												$stmt51->free_result();
												$stmt51->close();
												return;
											}
											else
											{
												$estadoError = translate('Lbl_State_Error_Default_Notice',$GLOBALS['lang']);
												$comentario = translate('Msg_Pending_State_Sent_SMS_Default_Notice',$GLOBALS['lang']);
												$stmt20->bind_param('sssi', $estadoError, $comentario, $date_registro, $id_envio_sms_db);
												if(!$stmt20->execute())
												{
													echo $mysqli->error;
													$mysqli->rollback();
													$mysqli->autocommit(TRUE);
													$stmt51->free_result();
													$stmt51->close();
													return;						
												}
											}											
																	
											$mysqli->commit();
											$mysqli->autocommit(TRUE);											
										}
										else
										{
											$mysqli->autocommit(FALSE);
											$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
																
											$date_registro = date("YmdHis");					
											if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".aviso_x_mora SET comentario = ?, fecha_modificacion = ? WHERE id = ?"))
											{
												echo $mysqli->error;
												$mysqli->autocommit(TRUE);
												$stmt51->free_result();
												$stmt51->close();
												return;
											}
											else
											{
												$comentario = translate('Msg_State_Sent_SMS_Unknow_Default_Notice',$GLOBALS['lang']);
												$stmt20->bind_param('ssi', $comentario, $date_registro, $id_aviso_x_mora_db);
												if(!$stmt20->execute())
												{
													echo $mysqli->error;
													$mysqli->autocommit(TRUE);
													$stmt51->free_result();
													$stmt51->close();
													return;						
												}
											}
											
											$date_registro = date("YmdHis");					
											if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".envio_sms SET estado = ?, comentario = ?, fecha_modificacion = ? WHERE id = ?"))
											{
												echo $mysqli->error;
												$mysqli->rollback();
												$mysqli->autocommit(TRUE);
												$stmt51->free_result();
												$stmt51->close();
												return;
											}
											else
											{
												$estadoError = translate('Lbl_State_Error_Default_Notice',$GLOBALS['lang']);
												$comentario = translate('Msg_State_Sent_SMS_Unknow_Default_Notice',$GLOBALS['lang']);
												$stmt20->bind_param('sssi', $estadoError, $comentario, $date_registro, $id_envio_sms_db);
												if(!$stmt20->execute())
												{
													echo $mysqli->error;
													$mysqli->rollback();
													$mysqli->autocommit(TRUE);
													$stmt51->free_result();
													$stmt51->close();
													return;						
												}
											}											
																	
											$mysqli->commit();
											$mysqli->autocommit(TRUE);											
										}
									}
									else
									{
										if($estado_envio_sms_db == translate('Lbl_State_Forwarded_Default_Notice',$GLOBALS['lang']))
										{
											if($cantidad_reintentos_envio_sms_db < $cantidad_reintentos_sms_parametros_db)
											{
												$resultadoEstadoEnvioSMS = status_envio_sms($id_sms_envio_service_db);
												$ps = xml_parser_create();
												xml_parse_into_struct($ps, $resultadoEstadoEnvioSMS, $valss, $indexs);
												xml_parser_free($ps);
												
												//echo $resultadoEstadoEnvioSMS.' -- '.$id_sms_envio_service_db.'</br>';
												$fechaEntregaSMS = $valss[2]['value'];
												$resultadoEstadoEnvioSMS = $valss[1]['value'];
												 
												//print_r($valss);
												//return;
										
												$reenviarMensajeSMS = 0;
												if(strcasecmp($resultadoEstadoEnvioSMS, translate('Lbl_State_Sended_SMS_Default_Notice_OK',$GLOBALS['lang'])) == 0)
												{
													$mysqli->autocommit(FALSE);
													$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
																		
													$date_registro = date("YmdHis");					
													if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".aviso_x_mora SET estado = ?, comentario = ?, fecha_modificacion = ? WHERE id = ?"))
													{
														echo $mysqli->error;
														$mysqli->autocommit(TRUE);
														$stmt51->free_result();
														$stmt51->close();
														return;
													}
													else
													{
														$estadoFinalizado = translate('Lbl_State_Finished_Default_Notice',$GLOBALS['lang']);
														$comentario = translate('Msg_Was_Sent_OK_Default_Notice',$GLOBALS['lang']);
														$stmt20->bind_param('sssi', $estadoFinalizado, $comentario, $date_registro, $id_aviso_x_mora_db);
														if(!$stmt20->execute())
														{
															echo $mysqli->error;
															$mysqli->autocommit(TRUE);
															$stmt51->free_result();
															$stmt51->close();
															return;						
														}
													}
													
													$date_registro = date("YmdHis");					
													if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".envio_sms SET estado = ?, comentario = ?, fecha_modificacion = ? WHERE id = ?"))
													{
														echo $mysqli->error;
														$mysqli->rollback();
														$mysqli->autocommit(TRUE);
														$stmt51->free_result();
														$stmt51->close();
														return;
													}
													else
													{
														$estadoFinalizado = translate('Lbl_State_Finished_Default_Notice',$GLOBALS['lang']);
														$comentario = translate('Msg_Was_Sent_OK_Default_Notice',$GLOBALS['lang']);
														$stmt20->bind_param('sssi', $estadoFinalizado, $comentario, $date_registro, $id_envio_sms_db);
														if(!$stmt20->execute())
														{
															echo $mysqli->error;
															$mysqli->rollback();
															$mysqli->autocommit(TRUE);
															$stmt51->free_result();
															$stmt51->close();
															return;						
														}
													}											
																			
													$mysqli->commit();
													$mysqli->autocommit(TRUE);
												}
												else
												{
													$reenviarMensajeSMS = 1;										
												}
												
												if($reenviarMensajeSMS == 1)
												{													
													$resultadoEnvioSMS = envio_sms_auto(translate('Lbl_From_SMS_ID_Sent',$GLOBALS['lang']), $numero_telefono_envio_sms_db, $mensaje_aviso_x_mora_db);
													$codigoRespuestaEnvSMS = substr($resultadoEnvioSMS, strpos($resultadoEnvioSMS, '=:=:=')+5);
													$resultadoEnvioSMS = str_replace('=:=:='.$codigoRespuestaEnvSMS, "", $resultadoEnvioSMS);
													
													$p = xml_parser_create();
													xml_parse_into_struct($p, $resultadoEnvioSMS, $vals, $index);
													xml_parser_free($p);
													
													if($codigoRespuestaEnvSMS == 200)
													{
														$mysqli->autocommit(FALSE);
														$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
																			
														$date_registro = date("YmdHis");					
														if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".aviso_x_mora SET estado = ?, comentario = ?, fecha_modificacion = ? WHERE id = ?"))
														{
															echo $mysqli->error;
															$mysqli->autocommit(TRUE);
															$stmt51->free_result();
															$stmt51->close();
															return;
														}
														else
														{
															$estadoPendiente = translate('Lbl_State_Pending_Default_Notice',$GLOBALS['lang']);
															$comentario = translate('Msg_Was_Resent_Default_Notice',$GLOBALS['lang']);
															$stmt20->bind_param('sssi', $estadoPendiente, $comentario, $date_registro, $id_aviso_x_mora_db);
															if(!$stmt20->execute())
															{
																echo $mysqli->error;
																$mysqli->autocommit(TRUE);
																$stmt51->free_result();
																$stmt51->close();
																return;						
															}
														}
														
														$date_registro = date("YmdHis");					
														if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".envio_sms SET estado = ?, comentario = ?, fecha_modificacion = ?, cantidad_reintentos = cantidad_reintentos + 1, id_sms = ?, codigo_respuesta = ? WHERE id = ?"))
														{
															echo $mysqli->error;
															$mysqli->rollback();
															$mysqli->autocommit(TRUE);
															$stmt51->free_result();
															$stmt51->close();
															return;
														}
														else
														{
															$estadoReEnviado = translate('Lbl_State_Forwarded_Default_Notice',$GLOBALS['lang']);
															$comentario = translate('Msg_Was_Resent_Default_Notice',$GLOBALS['lang']);
															$codigoRespuestaSMS = 200;
															$stmt20->bind_param('ssssii', $estadoReEnviado, $comentario, $date_registro, $vals[2]['value'], $codigoRespuestaSMS, $id_envio_sms_db);
															if(!$stmt20->execute())
															{
																echo $mysqli->error;
																$mysqli->rollback();
																$mysqli->autocommit(TRUE);
																$stmt51->free_result();
																$stmt51->close();
																return;						
															}
														}										
																				
														$mysqli->commit();
														$mysqli->autocommit(TRUE);										
													}
													else
													{
														if($codigoRespuestaEnvSMS == 103) 
														{
															$codigoRespuestaSMS = 103;
															$comentario = translate('Msg_Erroneous_Parameters',$GLOBALS['lang']);
														}
														else if($codigoRespuestaEnvSMS == 109)
														{
															$codigoRespuestaSMS = 109;
															$comentario = translate('Msg_Mandatory_Parameter_Omitted',$GLOBALS['lang']);
														}
														else if($codigoRespuestaEnvSMS == 401)
														{
															$codigoRespuestaSMS = 401;
															$comentario = translate('Msg_Unauthorized_Authentication_Error_Check_Token',$GLOBALS['lang']);
														}											
														else if($codigoRespuestaEnvSMS == 402) 
														{
															$codigoRespuestaSMS = 402;
															$comentario = translate('Msg_Payment_Required_Insufficient_Balance_For_Sending_SMS',$GLOBALS['lang']);										
														}
														else if($codigoRespuestaEnvSMS == 412)
														{
															$codigoRespuestaSMS = 412;
															$comentario = translate('Msg_Precondition_Failed_Unrecognized_Error',$GLOBALS['lang']);
														}
														else if($codigoRespuestaEnvSMS == 404)
														{
															$codigoRespuestaSMS = 404;
															$comentario = translate('Msg_Not_Found_SMS_ID_Sent',$GLOBALS['lang']);
														}
														else 
														{
															$codigoRespuestaSMS = -1;
															$comentario = translate('Msg_Unknown_Error',$GLOBALS['lang']);										
														}
														
														$mysqli->autocommit(FALSE);
														$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
																			
														$date_registro = date("YmdHis");					
														if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".aviso_x_mora SET comentario = ?, fecha_modificacion = ? WHERE id = ?"))
														{
															echo $mysqli->error;
															$mysqli->autocommit(TRUE);
															$stmt51->free_result();
															$stmt51->close();
															return;
														}
														else
														{
															$comentario2 = translate('Msg_Was_Resent_Default_Notice',$GLOBALS['lang']);
															$stmt20->bind_param('ssi', $comentario2, $date_registro, $id_aviso_x_mora_db);
															if(!$stmt20->execute())
															{
																echo $mysqli->error;
																$mysqli->autocommit(TRUE);
																$stmt51->free_result();
																$stmt51->close();
																return;						
															}
														}
														
														$date_registro = date("YmdHis");					
														if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".envio_sms SET estado = ?, comentario = ?, fecha_modificacion = ?, cantidad_reintentos = cantidad_reintentos + 1, id_sms = ?, codigo_respuesta = ? WHERE id = ?"))
														{
															echo $mysqli->error;
															$mysqli->rollback();
															$mysqli->autocommit(TRUE);
															$stmt51->free_result();
															$stmt51->close();
															return;
														}
														else
														{
															$estadoReEnviado = translate('Lbl_State_Forwarded_Default_Notice',$GLOBALS['lang']);
															$stmt20->bind_param('sssiii', $estadoReEnviado, $comentario, $date_registro, $vals[2]['value'], $codigoRespuestaSMS, $id_envio_sms_db);
															if(!$stmt20->execute())
															{
																echo $mysqli->error;
																$mysqli->rollback();
																$mysqli->autocommit(TRUE);
																$stmt51->free_result();
																$stmt51->close();
																return;						
															}
														}										
																				
														$mysqli->commit();
														$mysqli->autocommit(TRUE);											
													}
												}
											}
											else
											{
												$mysqli->autocommit(FALSE);
												$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
																	
												$date_registro = date("YmdHis");					
												if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".aviso_x_mora SET estado = ?, comentario = ?, fecha_modificacion = ? WHERE id = ?"))
												{
													echo $mysqli->error;
													$mysqli->autocommit(TRUE);
													$stmt51->free_result();
													$stmt51->close();
													return;
												}
												else
												{
													$estadoError = translate('Lbl_State_Error_Default_Notice',$GLOBALS['lang']);
													$comentario = translate('Msg_The_SMS_Message_Has_Exceeded_The_Number_Of_Resends',$GLOBALS['lang']);
													$stmt20->bind_param('sssi', $estadoError, $comentario, $date_registro, $id_aviso_x_mora_db);
													if(!$stmt20->execute())
													{
														echo $mysqli->error;
														$mysqli->autocommit(TRUE);
														$stmt51->free_result();
														$stmt51->close();
														return;						
													}
												}
												
												$date_registro = date("YmdHis");					
												if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".envio_sms SET estado = ?, comentario = ?, fecha_modificacion = ? WHERE id = ?"))
												{
													echo $mysqli->error;
													$mysqli->rollback();
													$mysqli->autocommit(TRUE);
													$stmt51->free_result();
													$stmt51->close();
													return;
												}
												else
												{
													$estadoFinalizado = translate('Lbl_State_Finished_Default_Notice',$GLOBALS['lang']);
													$comentario = translate('Msg_The_SMS_Message_Has_Exceeded_The_Number_Of_Resends',$GLOBALS['lang']);
													$stmt20->bind_param('sssi', $estadoFinalizado, $comentario, $date_registro, $id_envio_sms_db);
													if(!$stmt20->execute())
													{
														echo $mysqli->error;
														$mysqli->rollback();
														$mysqli->autocommit(TRUE);
														$stmt51->free_result();
														$stmt51->close();
														return;						
													}
												}											
																		
												$mysqli->commit();
												$mysqli->autocommit(TRUE);
											}
										}
										else
										{
											$resultadoEstadoEnvioSMS = status_envio_sms($id_sms_envio_service_db);
											$ps = xml_parser_create();
											xml_parse_into_struct($ps, $resultadoEstadoEnvioSMS, $valss, $indexs);
											xml_parser_free($ps);
											
											$fechaEntregaSMS = $valss[2]['value'];
											$resultadoEstadoEnvioSMS = $valss[1]['value'];
											
											$reenviarMensajeSMS = 0;
											if(strcasecmp($resultadoEstadoEnvioSMS, translate('Lbl_State_Sended_SMS_Default_Notice_OK',$GLOBALS['lang'])) == 0)
											{
												$mysqli->autocommit(FALSE);
												$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
																	
												$date_registro = date("YmdHis");					
												if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".aviso_x_mora SET estado = ?, comentario = ?, fecha_modificacion = ? WHERE id = ?"))
												{
													echo $mysqli->error;
													$mysqli->autocommit(TRUE);
													$stmt51->free_result();
													$stmt51->close();
													return;
												}
												else
												{
													$estadoFinalizado = translate('Lbl_State_Finished_Default_Notice',$GLOBALS['lang']);
													$comentario = translate('Msg_Was_Sent_OK_Default_Notice',$GLOBALS['lang']);
													$stmt20->bind_param('sssi', $estadoFinalizado, $comentario, $date_registro, $id_aviso_x_mora_db);
													if(!$stmt20->execute())
													{
														echo $mysqli->error;
														$mysqli->autocommit(TRUE);
														$stmt51->free_result();
														$stmt51->close();
														return;						
													}
												}
												
												$date_registro = date("YmdHis");					
												if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".envio_sms SET estado = ?, comentario = ?, fecha_modificacion = ?, cantidad_reintentos = cantidad_reintentos + 1 WHERE id = ?"))
												{
													echo $mysqli->error;
													$mysqli->rollback();
													$mysqli->autocommit(TRUE);
													$stmt51->free_result();
													$stmt51->close();
													return;
												}
												else
												{
													$estadoFinalizado = translate('Lbl_State_Finished_Default_Notice',$GLOBALS['lang']);
													$comentario = translate('Msg_Was_Sent_OK_Default_Notice',$GLOBALS['lang']);
													$stmt20->bind_param('sssi', $estadoFinalizado, $comentario, $date_registro, $id_envio_sms_db);
													if(!$stmt20->execute())
													{
														echo $mysqli->error;
														$mysqli->rollback();
														$mysqli->autocommit(TRUE);
														$stmt51->free_result();
														$stmt51->close();
														return;						
													}
												}											
																		
												$mysqli->commit();
												$mysqli->autocommit(TRUE);
											}
											else
											{
												$reenviarMensajeSMS = 1;										
											}
											
											if($reenviarMensajeSMS == 1)
											{													
												$resultadoEnvioSMS = envio_sms_auto(translate('Lbl_From_SMS_ID_Sent',$GLOBALS['lang']), $numero_telefono_envio_sms_db, $mensaje_aviso_x_mora_db);
												$codigoRespuestaEnvSMS = substr($resultadoEnvioSMS, strpos($resultadoEnvioSMS, '=:=:=')+5);
												$resultadoEnvioSMS = str_replace('=:=:='.$codigoRespuestaEnvSMS, "", $resultadoEnvioSMS);
											
												$p = xml_parser_create();
												xml_parse_into_struct($p, $resultadoEnvioSMS, $vals, $index);
												xml_parser_free($p);
												
												if($codigoRespuestaEnvSMS == 200)
												{
													$mysqli->autocommit(FALSE);
													$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
																		
													$date_registro = date("YmdHis");					
													if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".aviso_x_mora SET estado = ?, comentario = ?, fecha_modificacion = ? WHERE id = ?"))
													{
														echo $mysqli->error;
														$mysqli->autocommit(TRUE);
														$stmt51->free_result();
														$stmt51->close();
														return;
													}
													else
													{
														$estadoPendiente = translate('Lbl_State_Pending_Default_Notice',$GLOBALS['lang']);
														$comentario = translate('Msg_Was_Resent_Default_Notice',$GLOBALS['lang']);
														$stmt20->bind_param('sssi', $estadoPendiente, $comentario, $date_registro, $id_aviso_x_mora_db);
														if(!$stmt20->execute())
														{
															echo $mysqli->error;
															$mysqli->autocommit(TRUE);
															$stmt51->free_result();
															$stmt51->close();
															return;						
														}
													}
													
													$date_registro = date("YmdHis");					
													if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".envio_sms SET estado = ?, comentario = ?, fecha_modificacion = ?, cantidad_reintentos = cantidad_reintentos + 1, id_sms = ?, codigo_respuesta = ? WHERE id = ?"))
													{
														echo $mysqli->error;
														$mysqli->rollback();
														$mysqli->autocommit(TRUE);
														$stmt51->free_result();
														$stmt51->close();
														return;
													}
													else
													{
														$estadoReEnviado = translate('Lbl_State_Forwarded_Default_Notice',$GLOBALS['lang']);
														$comentario = translate('Msg_Was_Resent_Default_Notice',$GLOBALS['lang']);
														$codigoRespuestaSMS = 200;
														$stmt20->bind_param('ssssii', $estadoReEnviado, $comentario, $date_registro, $vals[2]['value'], $codigoRespuestaSMS, $id_envio_sms_db);
														if(!$stmt20->execute())
														{
															echo $mysqli->error;
															$mysqli->rollback();
															$mysqli->autocommit(TRUE);
															$stmt51->free_result();
															$stmt51->close();
															return;						
														}
													}										
																			
													$mysqli->commit();
													$mysqli->autocommit(TRUE);										
												}
												else
												{
													if($codigoRespuestaEnvSMS == 103) 
													{
														$codigoRespuestaSMS = 103;
														$comentario = translate('Msg_Erroneous_Parameters',$GLOBALS['lang']);
													}
													else if($codigoRespuestaEnvSMS == 109)
													{
														$codigoRespuestaSMS = 109;
														$comentario = translate('Msg_Mandatory_Parameter_Omitted',$GLOBALS['lang']);
													}
													else if($codigoRespuestaEnvSMS == 401)
													{
														$codigoRespuestaSMS = 401;
														$comentario = translate('Msg_Unauthorized_Authentication_Error_Check_Token',$GLOBALS['lang']);
													}											
													else if($codigoRespuestaEnvSMS == 402) 
													{
														$codigoRespuestaSMS = 402;
														$comentario = translate('Msg_Payment_Required_Insufficient_Balance_For_Sending_SMS',$GLOBALS['lang']);										
													}
													else if($codigoRespuestaEnvSMS == 412)
													{
														$codigoRespuestaSMS = 412;
														$comentario = translate('Msg_Precondition_Failed_Unrecognized_Error',$GLOBALS['lang']);
													}
													else if($codigoRespuestaEnvSMS == 404)
													{
														$codigoRespuestaSMS = 404;
														$comentario = translate('Msg_Not_Found_SMS_ID_Sent',$GLOBALS['lang']);
													}
													else 
													{
														$codigoRespuestaSMS = -1;
														$comentario = translate('Msg_Unknown_Error',$GLOBALS['lang']);										
													}
													
													$mysqli->autocommit(FALSE);
													$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
																		
													$date_registro = date("YmdHis");					
													if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".aviso_x_mora SET comentario = ?, fecha_modificacion = ? WHERE id = ?"))
													{
														echo $mysqli->error;
														$mysqli->autocommit(TRUE);
														$stmt51->free_result();
														$stmt51->close();
														return;
													}
													else
													{
														$comentario2 = translate('Msg_Was_Resent_Default_Notice',$GLOBALS['lang']);
														$stmt20->bind_param('ssi', $comentario2, $date_registro, $id_aviso_x_mora_db);
														if(!$stmt20->execute())
														{
															echo $mysqli->error;
															$mysqli->autocommit(TRUE);
															$stmt51->free_result();
															$stmt51->close();
															return;						
														}
													}
													
													$date_registro = date("YmdHis");					
													if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".envio_sms SET estado = ?, comentario = ?, fecha_modificacion = ?, cantidad_reintentos = cantidad_reintentos + 1, id_sms = ?, codigo_respuesta = ? WHERE id = ?"))
													{
														echo $mysqli->error;
														$mysqli->rollback();
														$mysqli->autocommit(TRUE);
														$stmt51->free_result();
														$stmt51->close();
														return;
													}
													else
													{
														$estadoReEnviado = translate('Lbl_State_Forwarded_Default_Notice',$GLOBALS['lang']);
														$stmt20->bind_param('sssiii', $estadoReEnviado, $comentario, $date_registro, $vals[2]['value'], $codigoRespuestaSMS, $id_envio_sms_db);
														if(!$stmt20->execute())
														{
															echo $mysqli->error;
															$mysqli->rollback();
															$mysqli->autocommit(TRUE);
															$stmt51->free_result();
															$stmt51->close();
															return;						
														}
													}										
																			
													$mysqli->commit();
													$mysqli->autocommit(TRUE);											
												}
											}											
										}
									}
									
									$stmt77->free_result();
									$stmt77->close();
								}
								else
								{
									$mysqli->autocommit(FALSE);
									$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
														
									$date_registro = date("YmdHis");					
									if(!$stmt20 = $mysqli->prepare("UPDATE ".$db_name.".aviso_x_mora SET estado = ?, comentario = ?, fecha_modificacion = ? WHERE id = ?"))
									{
										echo $mysqli->error;
										$mysqli->autocommit(TRUE);
										$stmt51->free_result();
										$stmt51->close();
										return;
									}
									else
									{
										$estadoError = translate('Lbl_State_Error_Default_Notice',$GLOBALS['lang']);
										$comentario = translate('Msg_There_Is_No_SMS_Sending_Related_To_The_Late_Notice',$GLOBALS['lang']);
										$stmt20->bind_param('sssi', $estadoError, $comentario, $date_registro, $id_aviso_x_mora_db);
										if(!$stmt20->execute())
										{
											echo $mysqli->error;
											$mysqli->autocommit(TRUE);
											$stmt51->free_result();
											$stmt51->close();
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
				}
				$huboAvisosXMoraProcesados = 1;
			}
			else $huboAvisosXMoraProcesados = 0;
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}		
		
		if ($stmt107 = $mysqli->prepare("SELECT axm.id, axm.id_credito, axm.id_cuota_credito, axm.fecha, axm.fecha_modificacion, axm.id_tipo_aviso, cc.numero_cuota, cc.monto_cuota_original, cad.razon_social FROM ".$db_name.".aviso_x_mora axm, ".$db_name.".cuota_credito cc, ".$db_name.".credito_cliente ccli, ".$db_name.".sucursal suc, ".$db_name.".cadena cad WHERE axm.id_cuota_credito = cc.id AND ccli.id_sucursal = suc.id AND ccli.id_credito = cc.id_credito AND suc.id_cadena = cad.id AND axm.estado IN (?,?) AND cc.estado IN (?,?) AND axm.id = (SELECT MAX(axm2.id) FROM ".$db_name.".aviso_x_mora axm2 WHERE axm2.id_cuota_credito = axm.id_cuota_credito HAVING MAX(axm2.id) IS NOT NULL) GROUP BY axm.id, axm.id_credito, axm.id_cuota_credito, axm.fecha, axm.fecha_modificacion, axm.id_tipo_aviso, cc.numero_cuota, cc.monto_cuota_original, cad.razon_social")) 
		{
			$estadoError = translate('Lbl_State_Error_Default_Notice',$GLOBALS['lang']);
			$estadoFinalizado = translate('Lbl_State_Finished_Default_Notice',$GLOBALS['lang']);
			$estadoEnMora = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
			$estadoPendiente = translate('Lbl_State_Pending_Default_Notice',$GLOBALS['lang']);
			$stmt107->bind_param('ssss', $estadoError, $estadoFinalizado, $estadoPendiente, $estadoEnMora);
			$stmt107->execute();
			$stmt107->store_result();
	 			
			$totR107 = $stmt107->num_rows;

			if($totR107 > 0)
			{
				$stmt107->bind_result($id_revision_aviso_x_mora_db, $id_credito_revision_aviso_x_mora_db, $id_cuota_credito_revision_aviso_x_mora_db, $fecha_revision_aviso_x_mora_db, $fecha_modificacion_revision_aviso_x_mora_db, $id_tipo_aviso_revision_aviso_x_mora_db, $numero_cuota_revision_aviso_x_mora_db, $monto_cuota_original_revision_aviso_x_mora_db, $nombre_cadena_revision_aviso_x_mora_db);
				while($stmt107->fetch())
				{
					if ($stmt117 = $mysqli->prepare("SELECT axm.id FROM ".$db_name.".aviso_x_mora axm WHERE axm.estado IN (?,?) AND axm.id_cuota_credito = ?")) 
					{
						$estadoCreado = translate('Lbl_State_Create_Default_Notice',$GLOBALS['lang']);
						$estadoPendiente = translate('Lbl_State_Pending_Default_Notice',$GLOBALS['lang']);
						$stmt117->bind_param('ssi', $estadoCreado, $estadoPendiente, $id_cuota_credito_revision_aviso_x_mora_db);
						$stmt117->execute();
						$stmt117->store_result();
							
						$totR117 = $stmt117->num_rows;

						if($totR117 > 0)
						{
							$stmt117->bind_result($id_aviso_x_mora_sin_efecto_db);
							$stmt117->fetch();
							
							$stmt117->free_result();
							$stmt117->close();
						}
						else
						{
							if ($stmt118 = $mysqli->prepare("SELECT id FROM ".$db_name.".parametros WHERE nombre = ?")) 
							{
								$nombreValPar = 'cantidad_dias_reactivacion_avisos_x_mora';
								$stmt118->bind_param('s', $nombreValPar);
								$stmt118->execute();    
								$stmt118->store_result();
									
								$totR118 = $stmt118->num_rows;

								if($totR118 > 0)
								{
									$stmt118->bind_result($cantidad_dias_reactivacion_aviso_x_mora);
									$stmt118->fetch();

									$stmt118->free_result();
									$stmt118->close();				
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
							
							if(!empty($fecha_modificacion_revision_aviso_x_mora_db)) $fecha_comparacion_final_rev = $fecha_modificacion_revision_aviso_x_mora_db;
							else $fecha_comparacion_final_rev = $fecha_revision_aviso_x_mora_db;
								
							$fechaObtDB = substr($fecha_comparacion_final_rev, 0, 4).'-'.substr($fecha_comparacion_final_rev, 4, 2).'-'.substr($fecha_comparacion_final_rev, 6, 2).' '.substr($fecha_comparacion_final_rev, 8, 2).':'.substr($fecha_comparacion_final_rev, 10, 2).':'.substr($fecha_comparacion_final_rev, 12, 2);
							$fechaInfDB = new DateTime($fechaObtDB);
							$fechaAct = new DateTime();
							$difDias = $fechaAct->diff($fechaInfDB);
							
							if($difDias->days > $cantidad_dias_reactivacion_aviso_x_mora)
							{
								$mysqli->autocommit(FALSE);
								$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
													
								$date_registro = date("YmdHis");					
								if(!$stmt20 = $mysqli->prepare("INSERT INTO ".$db_name.".aviso_x_mora(id_credito,fecha,estado,id_cuota_credito,mensaje,id_tipo_aviso,comentario) VALUES(?,?,?,?,?,?,?)"))
								{
									echo $mysqli->error;
									$mysqli->autocommit(TRUE);
									$stmt51->free_result();
									$stmt51->close();
									return;
								}
								else
								{
									if($stmt64 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM ".$db_name.".mora_cuota_credito mcc WHERE mcc.id_cuota_credito = ?"))
									{
										$stmt64->bind_param('i', $id_cuota_credito_revision_aviso_x_mora_db);
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
									
									$estadoCreado = translate('Lbl_State_Create_Default_Notice',$GLOBALS['lang']);
									$comentario = translate('Msg_Notice_Late_Payment_Fee_Reactivated',$GLOBALS['lang']).': '.$id_revision_aviso_x_mora_db;
									$mensajeNuevo = str_replace("%1",$numero_cuota_revision_aviso_x_mora_db,translate('Msg_Reactivated_Reported_Installment_Has_Pending_Debt',$GLOBALS['lang']));
									$mensajeNuevo = str_replace("%2",$id_credito_revision_aviso_x_mora_db,$mensajeNuevo);
									$mensajeNuevo = str_replace("%3",number_format((($monto_cuota_original_revision_aviso_x_mora_db+$monto_interes_anterior_cuota_credito_db)/100.00), 2, ',', '.'),$mensajeNuevo);								
									$mensajeNuevo = str_replace("%4",$nombre_cadena_revision_aviso_x_mora_db,$mensajeNuevo);
									$stmt20->bind_param('issisis', $id_credito_revision_aviso_x_mora_db, $date_registro, $estadoCreado, $id_cuota_credito_revision_aviso_x_mora_db, $mensajeNuevo, $id_tipo_aviso_revision_aviso_x_mora_db, $comentario);
									if(!$stmt20->execute())
									{
										echo $mysqli->error;
										$mysqli->autocommit(TRUE);
										$stmt51->free_result();
										$stmt51->close();
										return;						
									}
								}
														
								$mysqli->commit();
								$mysqli->autocommit(TRUE);								
							}						
						}
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;					
					}
				}		
				$stmt107->free_result();
				$stmt107->close();
			}			
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}		
		
		
		if($huboAvisosXMoraProcesados == 1)
		{
			$mysqli->autocommit(FALSE);
			$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
								
			$date_registro = date("YmdHis");					
			if(!$stmt2 = $mysqli->prepare("INSERT INTO ".$db_name.".ejecucion_procesos_auto(fecha,comentario,tipo) VALUES (?,?,?)"))
			{
				echo $mysqli->error;
				$mysqli->autocommit(TRUE);
				$stmt->free_result();
				$stmt->close();
				return;
			}
			else
			{
				$tipoProcesoA = 2;
				$comentario = translate('Msg_The_Automatic_Process_Was_Executed_Correctly',$GLOBALS['lang']);
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
		}
		else
		{
			$mysqli->autocommit(FALSE);
			$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
								
			$date_registro = date("YmdHis");					
			if(!$stmt2 = $mysqli->prepare("INSERT INTO ".$db_name.".ejecucion_procesos_auto(fecha,comentario,tipo) VALUES (?,?,?)"))
			{
				echo $mysqli->error;
				$mysqli->autocommit(TRUE);
				$stmt->free_result();
				$stmt->close();
				return;
			}
			else
			{
				$tipoProcesoA = 2;
				$comentario = translate('Msg_No_Notice_Debt_Was_Found_To_Process',$GLOBALS['lang']);
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
		}			
		
		return;
?>