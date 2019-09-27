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
		
		$idCredito=htmlspecialchars($_POST["idCredito"], ENT_QUOTES, 'UTF-8');
		$cuotasCredito=htmlspecialchars($_POST["cuotasCredito"], ENT_QUOTES, 'UTF-8');
		$montoPago=htmlspecialchars($_POST["montoPago"], ENT_QUOTES, 'UTF-8');
		$tokenVS=htmlspecialchars($_POST["tokenVSS"], ENT_QUOTES, 'UTF-8');
				
		if($montoPago < 0)
		{
			echo translate('Negative_Numbers_Are_Not_Allowed',$GLOBALS['lang']);
			return;
		}
		
		if($montoPago == 0)
		{
			echo translate('The_Value_Entered_Is_Not_Allowed_Pay_Fee_Credit',$GLOBALS['lang']);
			return;
		}		
		
		if($stmt63 = $mysqli->prepare("SELECT cc.id, cc.numero_cuota, cc.monto_cuota_original, c.monto_compra, c.cantidad_cuotas, ccli.documento, ccli.tipo_documento, cc.fecha_pago, cc.monto_pago  FROM finan_cli.cuota_credito cc, finan_cli.credito c, finan_cli.credito_cliente ccli WHERE c.id = ccli.id_credito AND c.id = cc.id_credito AND cc.numero_cuota IN ($cuotasCredito) AND cc.id_credito = ? AND cc.estado IN (?,?)"))
		{
			$estadoU = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
			$estadoD = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
			$stmt63->bind_param('iss', $idCredito, $estadoU, $estadoD);
			$stmt63->execute();    
			$stmt63->store_result();
			
			$totR63 = $stmt63->num_rows;

			if($totR63 > 0)
			{
				$stmt63->bind_result($id_cuota_credito_db_e, $numero_cuota_db, $monto_cuota_original_db, $monto_compra_orig_credito_db, $cantidad_cuotas_credito_db, $documento_cliente_credito_db, $tipo_documento_cliente_credito_db, $fecha_pago_cuota_credito_db, $monto_pago_cuota_credito_db);
				$idCuotasCredito = "";
				$monto_cuotas_original_db = 0;
				while($stmt63->fetch())
				{
					if(!empty($fecha_pago_cuota_credito_db) || !empty($monto_pago_cuota_credito_db))
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;					
					}
					
					$monto_cuotas_original_db = $monto_cuotas_original_db + $monto_cuota_original_db;
					if($idCuotasCredito == "") $idCuotasCredito = $id_cuota_credito_db_e;
					else $idCuotasCredito = $idCuotasCredito.",".$id_cuota_credito_db_e;
				}
				
				if((($monto_compra_orig_credito_db/$cantidad_cuotas_credito_db)*$totR63) > $montoPago)
				{
					echo translate('The_Payment_Amount_Cannot_Be_Less_Than_The_Interest_Free_Installment_Pay_Fees_Credit',$GLOBALS['lang']);
					return;	
				}
				
				$stmt63->free_result();
				$stmt63->close();				
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

		
		$idCuotasCreditoRec = explode(",",$idCuotasCredito);
		$monto_interes_cuotas_credito = 0;
		for($i = 0; $i < count($idCuotasCreditoRec); $i++)
		{
			if($stmt64 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM finan_cli.mora_cuota_credito mcc WHERE mcc.id_cuota_credito = ?"))
			{
				$stmt64->bind_param('i', $idCuotasCreditoRec[$i]);
				$stmt64->execute();    
				$stmt64->store_result();
				
				$totR64 = $stmt64->num_rows;
				if($totR64 == 1)
				{
					$stmt64->bind_result($monto_interes_cuota_credito_db);
					$stmt64->fetch();
					
					$monto_interes_cuotas_credito = $monto_interes_cuotas_credito + $monto_interes_cuota_credito_db;
					
					$stmt64->free_result();
					$stmt64->close();				
				}
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;
			}
		}
		
		if(!empty($tokenVS))
		{
			if($stmt650 = $mysqli->prepare("SELECT tpc.validado, tpc.usuario_supervisor FROM finan_cli.token_pago_cuota tpc WHERE tpc.token = ? AND tpc.documento = ? AND tpc.tipo_documento = ? AND fecha LIKE ?"))
			{
				$date_registro_a_c_db = date("Ymd").'%';
				$stmt650->bind_param('ssis', $tokenVS, $documento_cliente_credito_db, $tipo_documento_cliente_credito_db, $date_registro_a_c_db);
				$stmt650->execute();    
				$stmt650->store_result();
				
				$totR650 = $stmt650->num_rows;
				if($totR650 > 0)
				{
					$stmt650->bind_result($validacion_token_pago_cuota_db, $usuario_supervisor_token_pago_cuota_db);
					$stmt650->fetch();
					
					if($validacion_token_pago_cuota_db == 0)
					{
						echo translate('Msg_Need_Authorize_Pay_Fee_Credit',$GLOBALS['lang']).'=::=::=::'.$tokenVS.'=:::=:::';
						echo '<div class="panel-group">';				
						echo '	<div class="panel panel-default">';
						echo '		<div id="panel-title-header" class="panel-heading">';
						echo '			<h3 class="panel-title">'.translate('Lbl_Authorize_Pay_Total_Amount_Debt_Credit',$GLOBALS['lang']).'</h3>';
						echo ' 		</div>';
						echo '		<div class="panel-body">';
						echo '			<form id="formularionasptd" role="form">';		
						echo '				<div class="form-group form-inline">';
						echo '					<label class="control-label" for="usuariosupervisorn2">'.translate('Lbl_User_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
						echo '					<div class="form-group" id="usuariosupervisorn2">';
						echo '						<input title="'.translate('Msg_User_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="usuariosupervisorn2i" name="usuariosupervisorn2i" type="text" maxlength="50" />';
						echo '					</div>';
						echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="passwordsupervisorn2">'.translate('Lbl_Password_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
						echo '					<div class="form-group" id="passwordsupervisorn2">';
						echo '						<input title="'.translate('Msg_Password_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="passwordsupervisorn2i" name="passwordsupervisorn2i" type="password" maxlength="128" />';
						echo '					</div>';		
						echo '				</div>';
						echo '				<div class="form-group form-inline">';
						echo '					<div id="img_loader_13"></div>';		
						echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarVS2" id="btnCancelarVS2" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogvalidsuppagototaldeudacredit\').dialog(\'close\');" style="margin-left:10px;" />';
						echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarS2" id="btnValidarS2" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarAutorizacionSupervisorPagoTotalDeuda(document.getElementById(\'formularionasptd\'));"/>';										
						echo '				</div>';				
						echo '			</form>';
						echo '		</div>';
						echo '	</div>';
						echo '</div>';	
												
						$stmt650->free_result();
						$stmt650->close();
						
						return;
					}
					else
					{
						if($montoPago > ($monto_cuotas_original_db+$monto_interes_cuotas_credito))
						{
							echo translate('The_Payment_Amount_Cannot_Be_Greater_Than_The_Total_Amount_Of_The_Fees_Pay_Fee_Credit',$GLOBALS['lang']);
							return;			
						}
						
						$esUltimaCuota = 0;
						
						$mysqli->autocommit(FALSE);
						$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
						
						$monto_x_cuota = round(($montoPago/count($idCuotasCreditoRec)), 0);
						$monto_pago_acum_cuotas = 0;
						
						$monto_interes_x_cuota = round(($monto_interes_cuotas_credito/count($idCuotasCreditoRec)), 0);
						$monto_interes_acum_cuotas = 0;						
						
						$date_registro_a_fpcc_db = date("YmdHis");
						if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.pago_total_credito(id_credito,fecha,monto,usuario,supervisor,token) VALUES (?,?,?,?,?,?)"))
						{
							echo $mysqli->error;
							$mysqli->autocommit(TRUE);
							$stmt->free_result();
							$stmt->close();
							return;
						}
						else
						{
							$stmt->bind_param('isisss', $idCredito, $date_registro_a_fpcc_db, $montoPago, $_SESSION['username'], $usuario_supervisor_token_pago_cuota_db, $tokenVS);
							if(!$stmt->execute())
							{
								echo $mysqli->error;
								$mysqli->autocommit(TRUE);
								$stmt->free_result();
								$stmt->close();
								return;						
							}
							else $id_monto_pago_total_deuda = $mysqli->insert_id;
						}
						
						$datosCuotasPagadas = "";						
						for($i = 0; $i < count($idCuotasCreditoRec); $i++)
						{	
							if($datosCuotasPagadas != "") $datosCuotasPagadas = $datosCuotasPagadas.'!';
							
							if($stmt91 = $mysqli->prepare("SELECT cc.numero_cuota, cc.monto_cuota_original FROM finan_cli.cuota_credito cc WHERE cc.id = ?"))
							{
								$stmt91->bind_param('i', $idCuotasCreditoRec[$i]);
								$stmt91->execute();    
								$stmt91->store_result();
								
								$totR91 = $stmt91->num_rows;
								if($totR91 == 1)
								{
									$stmt91->bind_result($numero_cuota_db_e, $monto_cuota_original_db_e);
									$stmt91->fetch();
									
									$stmt91->free_result();
									$stmt91->close();				
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
							if($numero_cuota_db_e == $cantidad_cuotas_credito_db) $esUltimaCuota = 1;
														
							if(!$stmt43 = $mysqli->prepare("UPDATE finan_cli.cuota_credito SET fecha_pago = ?, monto_pago = ?, estado = ?, usuario_registro_pago = ? WHERE id = ?"))
							{
								echo $mysqli->error;
								$mysqli->rollback();
								$mysqli->autocommit(TRUE);
								return;
							}
							else
							{
								if(($i+1) == count($idCuotasCreditoRec))
								{
									$monto_pago_cuota_r = $montoPago - $monto_pago_acum_cuotas;
									$monto_interes_cuota_r = $monto_interes_cuotas_credito - $monto_interes_acum_cuotas;
									$monto_pago_acum_cuotas = $monto_pago_acum_cuotas + $monto_pago_cuota_r;
									$monto_interes_acum_cuotas = $monto_interes_acum_cuotas + $monto_interes_cuota_r;
									
									if(!empty($monto_interes_cuota_r)) $datosCuotasPagadas = $datosCuotasPagadas.$numero_cuota_db_e.'¡'.$monto_pago_cuota_r.'¡'.$monto_interes_cuota_r.'¡'.$monto_cuota_original_db_e;
									else $datosCuotasPagadas = $datosCuotasPagadas.$numero_cuota_db_e.'¡'.$monto_pago_cuota_r.'¡0'.'¡'.$monto_cuota_original_db_e;
								}									
								else 
								{
									$monto_pago_cuota_r = $monto_x_cuota;
									$monto_pago_acum_cuotas = $monto_pago_acum_cuotas + $monto_pago_cuota_r;
									
									$monto_interes_cuota_r = $monto_interes_x_cuota;
									$monto_interes_acum_cuotas = $monto_interes_acum_cuotas + $monto_interes_cuota_r;

									if(!empty($monto_interes_cuota_r)) $datosCuotasPagadas = $datosCuotasPagadas.$numero_cuota_db_e.'¡'.$monto_pago_cuota_r.'¡'.$monto_interes_cuota_r.'¡'.$monto_cuota_original_db_e;
									else $datosCuotasPagadas = $datosCuotasPagadas.$numero_cuota_db_e.'¡'.$monto_pago_cuota_r.'¡0'.'¡'.$monto_cuota_original_db_e;									
								}
								
								$estadoP = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
								$stmt43->bind_param('sissi', $date_registro_a_fpcc_db, $monto_pago_cuota_r, $estadoP, $_SESSION['username'], $idCuotasCreditoRec[$i]);
								if(!$stmt43->execute())
								{
									echo $mysqli->error;
									$mysqli->rollback();
									$mysqli->autocommit(TRUE);
									return;						
								}

								$date_registro = date("YmdHis");				
								$valor_log_user = "UPDATE finan_cli.cuota_credito SET fecha_pago = ".$date_registro_a_fpcc_db.", monto_pago = ".$monto_pago_cuota_r.", estado = ".$estadoP." WHERE id = ".$idCuotasCreditoRec[$i];

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
									$motivo2 = 68;
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
							
							if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.pago_total_credito_x_cuota(id_pago_total_credito,id_cuota_credito) VALUES (?,?)"))
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
								$stmt->bind_param('ii', $id_monto_pago_total_deuda, $idCuotasCreditoRec[$i]);
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
									$valor_log_user = "UPDATE finan_cli.credito SET estado = ".$estadoF." WHERE id = ".$idCredito;

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
										$motivo2 = 69;
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
						
						if($stmt68 = $mysqli->prepare("SELECT c.estado, cli.id_titular, cli.nombres, cli.apellidos, s.nombre, ptc.usuario, td.nombre, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente ccli, finan_cli.cliente cli, finan_cli.pago_total_credito ptc, finan_cli.sucursal s, finan_cli.tipo_documento td WHERE c.id = ccli.id_credito AND c.id = ptc.id_credito AND ccli.tipo_documento = cli.tipo_documento AND ccli.documento = cli.documento AND ccli.id_sucursal = s.id AND cli.tipo_documento = td.id AND c.id = ?"))
						{
							$stmt68->bind_param('i', $idCredito);
							$stmt68->execute();    
							$stmt68->store_result();
							
							$totR68 = $stmt68->num_rows;

							if($totR68 > 0)
							{
								$stmt68->bind_result($estado_credito_db_res, $id_titular_cliente_db_res, $nombres_cliente_db_res, $apellidos_cliente_db_res, $nombre_sucursal_db_res, $usuario_registro_pago_cuota_db_res, $tipo_documento_cliente_db_res, $documento_cliente_db_res);
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

						$stmt650->free_result();
						$stmt650->close();
												
						if(empty($id_titular_cliente_db_res)) $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang']);
						else $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang']);	
						
						echo translate('Msg_Pay_Total_Amount_Debt_Credit_OK',$GLOBALS['lang']).'=:=:='.$estado_credito_db_res.'=::=::='.$date_registro_a_fpcc_db.'|'.$idCredito.'|'.count($idCuotasCreditoRec).'|'.$tipo_cuenta_texto_cliente.'|'.$nombres_cliente_db_res.' '.$apellidos_cliente_db_res.'|'.$nombre_sucursal_db_res.'|'.$usuario_registro_pago_cuota_db_res.'|'.$montoPago.'|'.$tipo_documento_cliente_db_res.'|'.$documento_cliente_db_res.'|'.$datosCuotasPagadas.'=:::=:::='.json_encode($array);
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
		
		if($montoPago > ($monto_cuotas_original_db+$monto_interes_cuotas_credito))
		{
			echo translate('The_Payment_Amount_Cannot_Be_Greater_Than_The_Total_Amount_Of_The_Fees_Pay_Fee_Credit',$GLOBALS['lang']);
			return;			
		}
		else if($montoPago < ($monto_cuotas_original_db+$monto_interes_cuotas_credito))
		{
			
			if($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3)
			{				
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				if(!$stmt80 = $mysqli->prepare("INSERT INTO finan_cli.token_pago_cuota(fecha,tipo_documento,documento,id_motivo,token,usuario,usuario_supervisor,validado) VALUES (?,?,?,?,?,?,?,?)"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					return;
				}
				else
				{
					$date_registro_a_pcc_db = date("YmdHis");
					$motivo = 76;
					$token = md5(uniqid(rand(), true));
					$token = hash('sha512', $token);
					$validacionI = 1;
					$stmt80->bind_param('sisisssi', $date_registro_a_pcc_db, $tipo_documento_cliente_credito_db, $documento_cliente_credito_db, $motivo, $token, $_SESSION['username'], $_SESSION['username'], $validacionI);
					if(!$stmt80->execute())
					{
						echo $mysqli->error;
						$mysqli->autocommit(TRUE);
						return;						
					}		
				}
				
				$esUltimaCuota = 0;				
				$monto_x_cuota = round(($montoPago/count($idCuotasCreditoRec)), 0);
				$monto_pago_acum_cuotas = 0;
				
				$monto_interes_x_cuota = round(($monto_interes_cuotas_credito/count($idCuotasCreditoRec)), 0);
				$monto_interes_acum_cuotas = 0;						
				
				$date_registro_a_fpcc_db = date("YmdHis");
				if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.pago_total_credito(id_credito,fecha,monto,usuario,supervisor,token) VALUES (?,?,?,?,?,?)"))
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
					$token2 = md5(uniqid(rand(), true));
					$token2 = hash('sha512', $token);
					$stmt->bind_param('isisss', $idCredito, $date_registro_a_fpcc_db, $montoPago, $_SESSION['username'], $_SESSION['username'], $token2);
					if(!$stmt->execute())
					{
						echo $mysqli->error;
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;						
					}
					else $id_monto_pago_total_deuda = $mysqli->insert_id;
				}
				
				$datosCuotasPagadas = "";						
				for($i = 0; $i < count($idCuotasCreditoRec); $i++)
				{	
					if($datosCuotasPagadas != "") $datosCuotasPagadas = $datosCuotasPagadas.'!';
					
					if($stmt91 = $mysqli->prepare("SELECT cc.numero_cuota, cc.monto_cuota_original FROM finan_cli.cuota_credito cc WHERE cc.id = ?"))
					{
						$stmt91->bind_param('i', $idCuotasCreditoRec[$i]);
						$stmt91->execute();    
						$stmt91->store_result();
						
						$totR91 = $stmt91->num_rows;
						if($totR91 == 1)
						{
							$stmt91->bind_result($numero_cuota_db_e, $monto_cuota_original_db_e);
							$stmt91->fetch();
							
							$stmt91->free_result();
							$stmt91->close();				
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
					if($numero_cuota_db_e == $cantidad_cuotas_credito_db) $esUltimaCuota = 1;
												
					if(!$stmt43 = $mysqli->prepare("UPDATE finan_cli.cuota_credito SET fecha_pago = ?, monto_pago = ?, estado = ?, usuario_registro_pago = ? WHERE id = ?"))
					{
						echo $mysqli->error;
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						return;
					}
					else
					{
						if(($i+1) == count($idCuotasCreditoRec))
						{
							$monto_pago_cuota_r = $montoPago - $monto_pago_acum_cuotas;
							$monto_interes_cuota_r = $monto_interes_cuotas_credito - $monto_interes_acum_cuotas;
							$monto_pago_acum_cuotas = $monto_pago_acum_cuotas + $monto_pago_cuota_r;
							$monto_interes_acum_cuotas = $monto_interes_acum_cuotas + $monto_interes_cuota_r;
							
							if(!empty($monto_interes_cuota_r)) $datosCuotasPagadas = $datosCuotasPagadas.$numero_cuota_db_e.'¡'.$monto_pago_cuota_r.'¡'.$monto_interes_cuota_r.'¡'.$monto_cuota_original_db_e;
							else $datosCuotasPagadas = $datosCuotasPagadas.$numero_cuota_db_e.'¡'.$monto_pago_cuota_r.'¡0'.'¡'.$monto_cuota_original_db_e;
						}									
						else 
						{
							$monto_pago_cuota_r = $monto_x_cuota;
							$monto_pago_acum_cuotas = $monto_pago_acum_cuotas + $monto_pago_cuota_r;
							
							$monto_interes_cuota_r = $monto_interes_x_cuota;
							$monto_interes_acum_cuotas = $monto_interes_acum_cuotas + $monto_interes_cuota_r;

							if(!empty($monto_interes_cuota_r)) $datosCuotasPagadas = $datosCuotasPagadas.$numero_cuota_db_e.'¡'.$monto_pago_cuota_r.'¡'.$monto_interes_cuota_r.'¡'.$monto_cuota_original_db_e;
							else $datosCuotasPagadas = $datosCuotasPagadas.$numero_cuota_db_e.'¡'.$monto_pago_cuota_r.'¡0'.'¡'.$monto_cuota_original_db_e;									
						}
						
						$estadoP = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
						$stmt43->bind_param('sissi', $date_registro_a_fpcc_db, $monto_pago_cuota_r, $estadoP, $_SESSION['username'], $idCuotasCreditoRec[$i]);
						if(!$stmt43->execute())
						{
							echo $mysqli->error;
							$mysqli->rollback();
							$mysqli->autocommit(TRUE);
							return;						
						}

						$date_registro = date("YmdHis");				
						$valor_log_user = "UPDATE finan_cli.cuota_credito SET fecha_pago = ".$date_registro_a_fpcc_db.", monto_pago = ".$monto_pago_cuota_r.", estado = ".$estadoP." WHERE id = ".$idCuotasCreditoRec[$i];

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
							$motivo2 = 68;
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
					
					if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.pago_total_credito_x_cuota(id_pago_total_credito,id_cuota_credito) VALUES (?,?)"))
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
						$stmt->bind_param('ii', $id_monto_pago_total_deuda, $idCuotasCreditoRec[$i]);
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
							$valor_log_user = "UPDATE finan_cli.credito SET estado = ".$estadoF." WHERE id = ".$idCredito;

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
								$motivo2 = 69;
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
				
				if($stmt68 = $mysqli->prepare("SELECT c.estado, cli.id_titular, cli.nombres, cli.apellidos, s.nombre, ptc.usuario, td.nombre, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente ccli, finan_cli.cliente cli, finan_cli.pago_total_credito ptc, finan_cli.sucursal s, finan_cli.tipo_documento td WHERE c.id = ccli.id_credito AND c.id = ptc.id_credito AND ccli.tipo_documento = cli.tipo_documento AND ccli.documento = cli.documento AND ccli.id_sucursal = s.id AND cli.tipo_documento = td.id AND c.id = ?"))
				{
					$stmt68->bind_param('i', $idCredito);
					$stmt68->execute();    
					$stmt68->store_result();
					
					$totR68 = $stmt68->num_rows;

					if($totR68 > 0)
					{
						$stmt68->bind_result($estado_credito_db_res, $id_titular_cliente_db_res, $nombres_cliente_db_res, $apellidos_cliente_db_res, $nombre_sucursal_db_res, $usuario_registro_pago_cuota_db_res, $tipo_documento_cliente_db_res, $documento_cliente_db_res);
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
												
				if(empty($id_titular_cliente_db_res)) $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang']);
				else $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang']);	
				
				echo translate('Msg_Pay_Total_Amount_Debt_Credit_OK',$GLOBALS['lang']).'=:=:='.$estado_credito_db_res.'=::=::='.$date_registro_a_fpcc_db.'|'.$idCredito.'|'.count($idCuotasCreditoRec).'|'.$tipo_cuenta_texto_cliente.'|'.$nombres_cliente_db_res.' '.$apellidos_cliente_db_res.'|'.$nombre_sucursal_db_res.'|'.$usuario_registro_pago_cuota_db_res.'|'.$montoPago.'|'.$tipo_documento_cliente_db_res.'|'.$documento_cliente_db_res.'|'.$datosCuotasPagadas.'=:::=:::='.json_encode($array);
				return;
			}
			else
			{
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				if(!$stmt43 = $mysqli->prepare("INSERT INTO finan_cli.token_pago_cuota(fecha,tipo_documento,documento,id_motivo,token,usuario,validado) VALUES (?,?,?,?,?,?,?)"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$date_registro_a_pcc_db = date("YmdHis");
					$motivo = 75;
					$token = md5(uniqid(rand(), true));
					$token = hash('sha512', $token);
					$validacionI = 0;
					$stmt43->bind_param('sisissi', $date_registro_a_pcc_db, $tipo_documento_cliente_credito_db, $documento_cliente_credito_db, $motivo, $token, $_SESSION['username'], $validacionI);
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
				
				echo translate('Msg_Need_Authorize_Pay_Fee_Credit',$GLOBALS['lang']).'=::=::=::'.$token.'=:::=:::';
				echo '<div class="panel-group">';				
				echo '	<div class="panel panel-default">';
				echo '		<div id="panel-title-header" class="panel-heading">';
				echo '			<h3 class="panel-title">'.translate('Lbl_Authorize_Pay_Total_Amount_Debt_Credit',$GLOBALS['lang']).'</h3>';
				echo ' 		</div>';
				echo '		<div class="panel-body">';
				echo '			<form id="formularionasptd" role="form">';		
				echo '				<div class="form-group form-inline">';
				echo '					<label class="control-label" for="usuariosupervisorn2">'.translate('Lbl_User_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="usuariosupervisorn2">';
				echo '						<input title="'.translate('Msg_User_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="usuariosupervisorn2i" name="usuariosupervisorn2i" type="text" maxlength="50" />';
				echo '					</div>';
				echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="passwordsupervisorn2">'.translate('Lbl_Password_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="passwordsupervisorn2">';
				echo '						<input title="'.translate('Msg_Password_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="passwordsupervisorn2i" name="passwordsupervisorn2i" type="password" maxlength="128" />';
				echo '					</div>';		
				echo '				</div>';
				echo '				<div class="form-group form-inline">';
				echo '					<div id="img_loader_13"></div>';		
				echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarVS2" id="btnCancelarVS2" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogvalidsuppagototaldeudacredit\').dialog(\'close\');" style="margin-left:10px;" />';
				echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarS2" id="btnValidarS2" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarAutorizacionSupervisorPagoTotalDeuda(document.getElementById(\'formularionasptd\'));"/>';										
				echo '				</div>';				
				echo '			</form>';
				echo '		</div>';
				echo '	</div>';
				echo '</div>';
			}

			return;
		}
		else
		{
			$mysqli->autocommit(FALSE);
			$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
			
			if(!$stmt80 = $mysqli->prepare("INSERT INTO finan_cli.token_pago_cuota(fecha,tipo_documento,documento,id_motivo,token,usuario,validado) VALUES (?,?,?,?,?,?,?)"))
			{
				echo $mysqli->error;
				$mysqli->autocommit(TRUE);
				return;
			}
			else
			{
				$date_registro_a_pcc_db = date("YmdHis");
				$motivo = 77;
				$token = md5(uniqid(rand(), true));
				$token = hash('sha512', $token);
				$validacionI = 1;
				$stmt80->bind_param('sisissi', $date_registro_a_pcc_db, $tipo_documento_cliente_credito_db, $documento_cliente_credito_db, $motivo, $token, $_SESSION['username'], $validacionI);
				if(!$stmt80->execute())
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					return;						
				}		
			}
			
			$esUltimaCuota = 0;				
			$monto_x_cuota = round(($montoPago/count($idCuotasCreditoRec)), 0);
			$monto_pago_acum_cuotas = 0;
			
			$monto_interes_x_cuota = round(($monto_interes_cuotas_credito/count($idCuotasCreditoRec)), 0);
			$monto_interes_acum_cuotas = 0;						
			
			$date_registro_a_fpcc_db = date("YmdHis");
			if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.pago_total_credito(id_credito,fecha,monto,usuario,token) VALUES (?,?,?,?,?)"))
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
				$token2 = md5(uniqid(rand(), true));
				$token2 = hash('sha512', $token);
				$stmt->bind_param('isiss', $idCredito, $date_registro_a_fpcc_db, $montoPago, $_SESSION['username'], $token2);
				if(!$stmt->execute())
				{
					echo $mysqli->error;
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;						
				}
				else $id_monto_pago_total_deuda = $mysqli->insert_id;
			}
			
			$datosCuotasPagadas = "";						
			for($i = 0; $i < count($idCuotasCreditoRec); $i++)
			{	
				if($datosCuotasPagadas != "") $datosCuotasPagadas = $datosCuotasPagadas.'!';
				
				if($stmt91 = $mysqli->prepare("SELECT cc.numero_cuota, cc.monto_cuota_original FROM finan_cli.cuota_credito cc WHERE cc.id = ?"))
				{
					$stmt91->bind_param('i', $idCuotasCreditoRec[$i]);
					$stmt91->execute();    
					$stmt91->store_result();
					
					$totR91 = $stmt91->num_rows;
					if($totR91 == 1)
					{
						$stmt91->bind_result($numero_cuota_db_e, $monto_cuota_original_db_e);
						$stmt91->fetch();
						
						$stmt91->free_result();
						$stmt91->close();				
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;
					}

					if($stmt92 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM finan_cli.mora_cuota_credito mcc, finan_cli.cuota_credito cc WHERE mcc.id_cuota_credito = cc.id AND cc.id_credito = ? AND cc.id = ?"))
					{
						$stmt92->bind_param('ii', $idCredito, $idCuotasCreditoRec[$i]);
						$stmt92->execute();    
						$stmt92->store_result();
						
						$totR92 = $stmt92->num_rows;

						if($totR92 > 0)
						{
							$stmt92->bind_result($monto_interes_cuota_credito_db_e);
							$stmt92->fetch();
						}
						else $monto_interes_cuota_credito_db_e = 0;
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
				if($numero_cuota_db_e == $cantidad_cuotas_credito_db) $esUltimaCuota = 1;
											
				if(!$stmt43 = $mysqli->prepare("UPDATE finan_cli.cuota_credito SET fecha_pago = ?, monto_pago = ?, estado = ?, usuario_registro_pago = ? WHERE id = ?"))
				{
					echo $mysqli->error;
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
					return;
				}
				else
				{
					$monto_pago_cuota_r = $monto_cuota_original_db_e + $monto_interes_cuota_credito_db_e;
					$monto_interes_cuota_r = $monto_interes_cuota_credito_db_e;
					$monto_pago_acum_cuotas = $monto_pago_acum_cuotas + $monto_pago_cuota_r;
					$monto_interes_acum_cuotas = $monto_interes_acum_cuotas + $monto_interes_cuota_r;					
						
					if(!empty($monto_interes_cuota_r)) $datosCuotasPagadas = $datosCuotasPagadas.$numero_cuota_db_e.'¡'.$monto_pago_cuota_r.'¡'.$monto_interes_cuota_r.'¡'.$monto_cuota_original_db_e;
					else $datosCuotasPagadas = $datosCuotasPagadas.$numero_cuota_db_e.'¡'.$monto_pago_cuota_r.'¡0'.'¡'.$monto_cuota_original_db_e;
					
					$estadoP = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
					$stmt43->bind_param('sissi', $date_registro_a_fpcc_db, $monto_pago_cuota_r, $estadoP, $_SESSION['username'], $idCuotasCreditoRec[$i]);
					if(!$stmt43->execute())
					{
						echo $mysqli->error;
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						return;						
					}

					$date_registro = date("YmdHis");				
					$valor_log_user = "UPDATE finan_cli.cuota_credito SET fecha_pago = ".$date_registro_a_fpcc_db.", monto_pago = ".$monto_pago_cuota_r.", estado = ".$estadoP." WHERE id = ".$idCuotasCreditoRec[$i];

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
						$motivo2 = 68;
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
				
				if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.pago_total_credito_x_cuota(id_pago_total_credito,id_cuota_credito) VALUES (?,?)"))
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
					$stmt->bind_param('ii', $id_monto_pago_total_deuda, $idCuotasCreditoRec[$i]);
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
						$valor_log_user = "UPDATE finan_cli.credito SET estado = ".$estadoF." WHERE id = ".$idCredito;

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
							$motivo2 = 69;
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
						
			if($stmt68 = $mysqli->prepare("SELECT c.estado, cli.id_titular, cli.nombres, cli.apellidos, s.nombre, ptc.usuario, td.nombre, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente ccli, finan_cli.cliente cli, finan_cli.pago_total_credito ptc, finan_cli.sucursal s, finan_cli.tipo_documento td WHERE c.id = ccli.id_credito AND c.id = ptc.id_credito AND ccli.tipo_documento = cli.tipo_documento AND ccli.documento = cli.documento AND ccli.id_sucursal = s.id AND cli.tipo_documento = td.id AND c.id = ?"))
			{
				$stmt68->bind_param('i', $idCredito);
				$stmt68->execute();    
				$stmt68->store_result();
				
				$totR68 = $stmt68->num_rows;

				if($totR68 > 0)
				{
					$stmt68->bind_result($estado_credito_db_res, $id_titular_cliente_db_res, $nombres_cliente_db_res, $apellidos_cliente_db_res, $nombre_sucursal_db_res, $usuario_registro_pago_cuota_db_res, $tipo_documento_cliente_db_res, $documento_cliente_db_res);
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
									
			if(empty($id_titular_cliente_db_res)) $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang']);
			else $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang']);	
			
			echo translate('Msg_Pay_Total_Amount_Debt_Credit_OK',$GLOBALS['lang']).'=:=:='.$estado_credito_db_res.'=::=::='.$date_registro_a_fpcc_db.'|'.$idCredito.'|'.count($idCuotasCreditoRec).'|'.$tipo_cuenta_texto_cliente.'|'.$nombres_cliente_db_res.' '.$apellidos_cliente_db_res.'|'.$nombre_sucursal_db_res.'|'.$usuario_registro_pago_cuota_db_res.'|'.$montoPago.'|'.$tipo_documento_cliente_db_res.'|'.$documento_cliente_db_res.'|'.$datosCuotasPagadas.'=:::=:::='.json_encode($array);
			return;
		}

		return;
?>