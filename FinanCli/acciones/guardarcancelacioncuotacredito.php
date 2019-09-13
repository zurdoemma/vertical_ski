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
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;				
			}	
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
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
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
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
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
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
					if($stmt162 = $mysqli->prepare("SELECT pt.id FROM finan_cli.pago_seleccion_cuotas_credito ps WHERE ps.id_cuota_credito = ?"))
					{
						$stmt162->bind_param('i', $idCuotaCredito);
						$stmt162->execute();    
						$stmt162->store_result();
						
						$totR162 = $stmt162->num_rows;

						if($totR162 > 0)
						{
							$stmt162->bind_result($id_pago_seleccion_cuotas_credito_db);
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
							$stmt162->bind_result($id_pago_seleccion_cuotas_credito_db);
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
					if($stmt163 = $mysqli->prepare("SELECT pt.id_cuota_credito, pt.fecha, pt.monto, pt.usuario, pt.supervisor, pt.token, cc.id_credito, cc.numero_cuota, cc.fecha_vencimiento, cc.monto_cuota_original, cc.estado, cc.fecha_pago, cc.monto_pago, cc.usuario_registro_pago FROM finan_cli.pago_total_credito_x_cuota pt, finan_cli.cuota_credito cc WHERE pt.id_cuota_credito = cc.id AND pt.id_pago_total_credito = ? AND cc.estado = ?"))
					{
						$estContrCPagada = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
						$stmt163->bind_param('is', $id_pago_total_credito_db, $estContrCPagada);
						$stmt163->execute();    
						$stmt163->store_result();
						
						$totR163 = $stmt163->num_rows;

						if($totR163 > 0)
						{
							$stmt163->bind_result($id_cuota_credito_anular_pago_db, $fecha_pago_total_borrar_db, $monto_pago_total_borrar_db, $usuario_pago_total_borrar_db, $supervisor_pago_total_borrar_db, $token_pago_total_borrar_db, $id_credito_anular_pago_db, $numero_cuota_anular_pago_db, $fecha_vencimiento_anular_pago_db, $monto_cuota_original_anular_pago_db, $estado_cuota_anular_pago_db, $fecha_pago_anular_db, $monto_pago_anular_db, $usuario_registro_pago_anular_db);
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
									echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
									return;					
								}					
							}
							else
							{
								echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
								return;
							}
							
							while($stmt163->fetch())
							{
								$fechaObtDB = substr($fecha_vencimiento_anular_pago_db, 0, 4).'-'.substr($fecha_vencimiento_anular_pago_db, 4, 2).'-'.substr($fecha_vencimiento_anular_pago_db, 6, 2).' '.substr($fecha_vencimiento_anular_pago_db, 10, 2).':'.substr($fecha_vencimiento_anular_pago_db, 12, 2);		
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
									
									$stmt10->bind_param('ssssi', $estSCENM, NULL, NULL, NULL, $id_cuota_credito_anular_pago_db);
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
										$valor_log_user = "ANTERIOR: finan_cli.pago_parcial_cuota_credito SET id = ".$id_pago_parcial_cuota_credito_db.", fecha = ".$fecha_pago_parcial_cuota_credito_db.", monto = ".$monto_pago_parcial_cuota_credito_db.", usuario = ".$usuario_pago_parcial_cuota_credito_db.", supervisor = ".$supervisor_pago_parcial_cuota_credito_db.", token = ".$token_pago_parcial_cuota_credito_db." WHERE id = ".$id_cuota_credito_anular_pago_db." -- DELETE finan_cli.pago_parcial_cuota_credito WHERE id_cuota_credito = ".$id_cuota_credito_anular_pago_db;
											
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
									echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
									return;
								}
																
								$posWhileR++;
							}
							
							if(!$stmt10 = $mysqli->prepare("DELETE FROM finan_cli.pago_total WHERE id_cuota_credito = ?"))
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
							$valor_log_user = "ANTERIOR: finan_cli.pago_parcial_cuota_credito SET id = ".$id_pago_parcial_cuota_credito_db.", fecha = ".$fecha_pago_parcial_cuota_credito_db.", monto = ".$monto_pago_parcial_cuota_credito_db.", usuario = ".$usuario_pago_parcial_cuota_credito_db.", supervisor = ".$supervisor_pago_parcial_cuota_credito_db.", token = ".$token_pago_parcial_cuota_credito_db." WHERE id = ".$id_cuota_credito_anular_pago_db." -- DELETE finan_cli.pago_parcial_cuota_credito WHERE id_cuota_credito = ".$id_cuota_credito_anular_pago_db;
								
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
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;						
					}					
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