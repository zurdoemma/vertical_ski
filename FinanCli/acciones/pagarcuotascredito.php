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
		
		echo $idCredito." -- ".$cuotasCredito." -- ".$montoPago." -- ".$tokenVS;
		return;
		if($stmt63 = $mysqli->prepare("SELECT cc.numero_cuota, cc.monto_cuota_original, c.monto_compra, c.cantidad_cuotas, ccli.documento, ccli.tipo_documento, cc.fecha_pago, cc.monto_pago  FROM finan_cli.cuota_credito cc, finan_cli.credito c, finan_cli.credito_cliente ccli WHERE c.id = ccli.id_credito AND c.id = cc.id_credito AND cc.id = ? AND cc.id_credito = ? AND cc.estado IN (?,?)"))
		{
			$estadoU = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
			$estadoD = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
			$stmt63->bind_param('iiss', $idCuotaCredito, $idCredito, $estadoU, $estadoD);
			$stmt63->execute();    
			$stmt63->store_result();
			
			$totR63 = $stmt63->num_rows;

			if($totR63 > 0)
			{
				$stmt63->bind_result($numero_cuota_db, $monto_cuota_original_db, $monto_compra_orig_credito_db, $cantidad_cuotas_credito_db, $documento_cliente_credito_db, $tipo_documento_cliente_credito_db, $fecha_pago_cuota_credito_db, $monto_pago_cuota_credito_db);
				$stmt63->fetch();
				
				if(!empty($fecha_pago_cuota_credito_db) || !empty($monto_pago_cuota_credito_db))
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;					
				}
				
				if(($monto_compra_orig_credito_db/$cantidad_cuotas_credito_db) > $montoPago)
				{
					echo translate('The_Payment_Amount_Cannot_Be_Less_Than_The_Interest_Free_Installment_Pay_Fee_Credit',$GLOBALS['lang']);
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
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;				
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		
		if(!empty($tokenVS))
		{
			if($stmt65 = $mysqli->prepare("SELECT tpc.validado, tpc.usuario_supervisor FROM finan_cli.token_pago_cuota tpc WHERE tpc.token = ? AND tpc.documento = ? AND tpc.tipo_documento = ? AND fecha LIKE ?"))
			{
				$date_registro_a_c_db = date("Ymd").'%';
				$stmt65->bind_param('ssis', $tokenVS, $documento_cliente_credito_db, $tipo_documento_cliente_credito_db, $date_registro_a_c_db);
				$stmt65->execute();    
				$stmt65->store_result();
				
				$totR65 = $stmt65->num_rows;
				if($totR65 > 0)
				{
					$stmt65->bind_result($validacion_token_pago_cuota_db, $usuario_supervisor_token_pago_cuota_db);
					$stmt65->fetch();
					
					if($validacion_token_pago_cuota_db == 0)
					{
						echo translate('Msg_Need_Authorize_Pay_Fee_Credit',$GLOBALS['lang']).'=::=::=::'.$tokenVS.'=:::=:::';
						echo '<div class="panel-group">';				
						echo '	<div class="panel panel-default">';
						echo '		<div id="panel-title-header" class="panel-heading">';
						echo '			<h3 class="panel-title">'.translate('Lbl_Authorize_Pay_Fee_Credit',$GLOBALS['lang']).'</h3>';
						echo ' 		</div>';
						echo '		<div class="panel-body">';
						echo '			<form id="formularionaspcc" role="form">';		
						echo '				<div class="form-group form-inline">';
						echo '					<label class="control-label" for="usuariosupervisorn">'.translate('Lbl_User_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
						echo '					<div class="form-group" id="usuariosupervisorn">';
						echo '						<input title="'.translate('Msg_User_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="usuariosupervisorni" name="usuariosupervisorni" type="text" maxlength="50" />';
						echo '					</div>';
						echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="passwordsupervisorn">'.translate('Lbl_Password_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
						echo '					<div class="form-group" id="passwordsupervisorn">';
						echo '						<input title="'.translate('Msg_Password_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="passwordsupervisorni" name="passwordsupervisorni" type="password" maxlength="128" />';
						echo '					</div>';		
						echo '				</div>';
						echo '				<div class="form-group form-inline">';
						echo '					<div id="img_loader_13"></div>';		
						echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarVS" id="btnCancelarVS" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogvalidsuppagocuotacredit\').dialog(\'close\');" style="margin-left:10px;" />';
						echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarS" id="btnValidarS" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarAutorizacionSupervisorPagoCuota(document.getElementById(\'formularionaspcc\'));"/>';										
						echo '				</div>';				
						echo '			</form>';
						echo '		</div>';
						echo '	</div>';
						echo '</div>';
						
						$stmt65->free_result();
						$stmt65->close();
						
						return;
					}
					else
					{
						if($montoPago > ($monto_cuota_original_db+$monto_interes_cuota_credito))
						{
							echo translate('The_Payment_Amount_Cannot_Be_Greater_Than_The_Total_Amount_Of_The_Fee_Pay_Fee_Credit',$GLOBALS['lang']);
							return;			
						}
						
						$esUltimaCuota = 0;
						
						if($numero_cuota_db == $cantidad_cuotas_credito_db) $esUltimaCuota = 1;
						
						$mysqli->autocommit(FALSE);
						$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
						
						if(!$stmt43 = $mysqli->prepare("UPDATE finan_cli.cuota_credito SET fecha_pago = ?, monto_pago = ?, estado = ?, usuario_registro_pago = ? WHERE id = ?"))
						{
							echo $mysqli->error;
							$mysqli->autocommit(TRUE);
							return;
						}
						else
						{
							$date_registro_a_fpcc_db = date("YmdHis");
							$estadoP = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
							$stmt43->bind_param('sissi', $date_registro_a_fpcc_db, $montoPago, $estadoP, $_SESSION['username'], $idCuotaCredito);
							if(!$stmt43->execute())
							{
								echo $mysqli->error;
								$mysqli->autocommit(TRUE);
								return;						
							}

							$date_registro = date("YmdHis");				
							$valor_log_user = "UPDATE finan_cli.cuota_credito SET fecha_pago = ".$date_registro_a_fpcc_db.", monto_pago = ".$montoPago.", estado = ".$estadoP." WHERE id = ".$idCuotaCredito;

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
						
						if($esUltimaCuota == 1)
						{
							if(!$stmt43 = $mysqli->prepare("UPDATE finan_cli.credito SET estado = ? WHERE id = ?"))
							{
								echo $mysqli->error;
								$mysqli->rollback();
								$mysqli->autocommit(TRUE);
								return;
							}
							else
							{
								$estadoP = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
								$stmt43->bind_param('si', $estadoP, $idCredito);
								if(!$stmt43->execute())
								{
									echo $mysqli->error;
									$mysqli->rollback();
									$mysqli->autocommit(TRUE);
									return;						
								}

								$date_registro = date("YmdHis");				
								$valor_log_user = "UPDATE finan_cli.credito SET estado = ".$estadoP." WHERE id = ".$idCredito;

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
						
						if($montoPago < ($monto_cuota_original_db+$monto_interes_cuota_credito))
						{
							if($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3) $insertPPCuotaC = "INSERT INTO finan_cli.pago_parcial_cuota_credito(id_cuota_credito,fecha,monto,usuario,token) VALUES (?,?,?,?,?)";
							else $insertPPCuotaC = "INSERT INTO finan_cli.pago_parcial_cuota_credito(id_cuota_credito,fecha,monto,usuario,supervisor,token) VALUES (?,?,?,?,?,?)";
							if(!$stmt = $mysqli->prepare($insertPPCuotaC))
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
								$token2 = md5(uniqid(rand(), true));
								$token2 = hash('sha512', $token2);
								if($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3) $stmt->bind_param('isiss', $idCuotaCredito, $date_registro, $montoPago, $_SESSION['username'], $token2);
								else $stmt->bind_param('isisss', $idCuotaCredito, $date_registro, $montoPago, $_SESSION['username'], $usuario_supervisor_token_pago_cuota_db, $token2);
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
						
						$mysqli->commit();
						$mysqli->autocommit(TRUE);
						
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
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
												else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>';
											}
											else
											{
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
												else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
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
												//if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
												//else $array[$posicion]['accionesv'] = '---';
												$array[$posicion]['accionesv'] = '---';
											}
											else
											{
												//if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
												if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
												else $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
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
											if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
											{
												if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_PDF_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>';
												else $array[$posicion]['accionesv'] = '---';
											}
											else $array[$posicion]['accionesv'] = '---';
										}
										else
										{
											if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
											{
												if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_PDF_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>';
												else $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
											}
											else 
											{
												$array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
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

						$stmt65->free_result();
						$stmt65->close();
						
						if($stmt68 = $mysqli->prepare("SELECT c.estado, cli.id_titular, cli.nombres, cli.apellidos, s.nombre, cc.numero_cuota, cc.usuario_registro_pago, td.nombre, cli.documento, cc.monto_cuota_original FROM finan_cli.credito c, finan_cli.credito_cliente ccli, finan_cli.cliente cli, finan_cli.cuota_credito cc, finan_cli.sucursal s, finan_cli.tipo_documento td WHERE c.id = ccli.id_credito AND c.id = cc.id_credito AND ccli.tipo_documento = cli.tipo_documento AND ccli.documento = cli.documento AND ccli.id_sucursal = s.id AND cli.tipo_documento = td.id AND c.id = ? AND cc.id = ?"))
						{
							$stmt68->bind_param('ii', $idCredito, $idCuotaCredito);
							$stmt68->execute();    
							$stmt68->store_result();
							
							$totR68 = $stmt68->num_rows;

							if($totR68 > 0)
							{
								$stmt68->bind_result($estado_credito_db_res, $id_titular_cliente_db_res, $nombres_cliente_db_res, $apellidos_cliente_db_res, $nombre_sucursal_db_res, $numero_cuota_db_res, $usuario_registro_pago_cuota_db_res, $tipo_documento_cliente_db_res, $documento_cliente_db_res, $monto_cuota_original_db_res);
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
							echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
							return;
						}

						if($stmt70 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM finan_cli.mora_cuota_credito mcc, finan_cli.cuota_credito cc WHERE mcc.id_cuota_credito = cc.id AND cc.id_credito = ? AND cc.id = ?"))
						{
							$stmt70->bind_param('ii', $idCredito, $idCuotaCredito);
							$stmt70->execute();    
							$stmt70->store_result();
							
							$totR70 = $stmt70->num_rows;

							if($totR70 > 0)
							{
								$stmt70->bind_result($monto_interes_cuota_credito_db_res);
								$stmt70->fetch();
								
								$stmt70->free_result();
								$stmt70->close();
							}
							else $monto_interes_cuota_credito_db_res = 0;
						}
						else
						{
							echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
							return;
						}						
						
						if(empty($id_titular_cliente_db_res)) $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang']);
						else $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang']);	
						
						echo translate('Msg_Pay_Fee_Credit_OK',$GLOBALS['lang']).'=:=:='.$estado_credito_db_res.'=::=::='.$date_registro_a_fpcc_db.'|'.$idCredito.'|'.$numero_cuota_db_res.'|'.$tipo_cuenta_texto_cliente.'|'.$nombres_cliente_db_res.' '.$apellidos_cliente_db_res.'|'.$nombre_sucursal_db_res.'|'.$usuario_registro_pago_cuota_db_res.'|'.$montoPago.'|'.$fecha_vencimiento_cuota_db_res.'|'.$tipo_documento_cliente_db_res.'|'.$documento_cliente_db_res.'|'.$monto_cuota_original_db_res.'|'.$monto_interes_cuota_credito_db_res.'=:::=:::='.json_encode($array).'=::::=::::='.$totR69;
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

		
		if($montoPago > ($monto_cuota_original_db+$monto_interes_cuota_credito))
		{
			echo translate('The_Payment_Amount_Cannot_Be_Greater_Than_The_Total_Amount_Of_The_Fee_Pay_Fee_Credit',$GLOBALS['lang']);
			return;			
		}
		else if($montoPago < ($monto_cuota_original_db+$monto_interes_cuota_credito))
		{
			
			if($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3)
			{
				$esUltimaCuota = 0;
				
				if($numero_cuota_db == $cantidad_cuotas_credito_db) $esUltimaCuota = 1;
				
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
					$motivo = 72;
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
				
				if(!$stmt43 = $mysqli->prepare("UPDATE finan_cli.cuota_credito SET fecha_pago = ?, monto_pago = ?, estado = ?, usuario_registro_pago = ? WHERE id = ?"))
				{
					echo $mysqli->error;
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
					return;
				}
				else
				{
					$date_registro_a_fpcc_db = date("YmdHis");
					$estadoP = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
					$stmt43->bind_param('sissi', $date_registro_a_fpcc_db, $montoPago, $estadoP, $_SESSION['username'], $idCuotaCredito);
					if(!$stmt43->execute())
					{
						echo $mysqli->error;
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						return;						
					}

					$date_registro = date("YmdHis");				
					$valor_log_user = "UPDATE finan_cli.cuota_credito SET fecha_pago = ".$date_registro_a_fpcc_db.", monto_pago = ".$montoPago.", estado = ".$estadoP." WHERE id = ".$idCuotaCredito;

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
				
				if($esUltimaCuota == 1)
				{
					if(!$stmt43 = $mysqli->prepare("UPDATE finan_cli.credito SET estado = ? WHERE id = ?"))
					{
						echo $mysqli->error;
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						return;
					}
					else
					{
						$estadoP = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
						$stmt43->bind_param('si', $estadoP, $idCredito);
						if(!$stmt43->execute())
						{
							echo $mysqli->error;
							$mysqli->rollback();
							$mysqli->autocommit(TRUE);
							return;						
						}

						$date_registro = date("YmdHis");				
						$valor_log_user = "UPDATE finan_cli.credito SET estado = ".$estadoP." WHERE id = ".$idCredito;

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
				
				if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.pago_parcial_cuota_credito(id_cuota_credito,fecha,monto,usuario,token) VALUES (?,?,?,?,?)"))
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
					$token2 = md5(uniqid(rand(), true));
					$token2 = hash('sha512', $token2);
					$stmt->bind_param('isiss', $idCuotaCredito, $date_registro, $montoPago, $_SESSION['username'], $token2);
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
				
				$mysqli->commit();
				$mysqli->autocommit(TRUE);
				
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
										if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
										else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>';
									}
									else
									{
										if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
										else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
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
										//if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
										//else $array[$posicion]['accionesv'] = '---';
										$array[$posicion]['accionesv'] = '---';
									}
									else
									{
										//if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
										if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
										else $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
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
									if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
									{
										if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_PDF_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>';
										else $array[$posicion]['accionesv'] = '---';
									}
									else $array[$posicion]['accionesv'] = '---';
								}
								else
								{
									if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
									{
										if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_PDF_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>';
										else $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
									}
									else 
									{
										$array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
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
				
				if($stmt68 = $mysqli->prepare("SELECT c.estado, cli.id_titular, cli.nombres, cli.apellidos, s.nombre, cc.numero_cuota, cc.usuario_registro_pago, td.nombre, cli.documento, cc.monto_cuota_original FROM finan_cli.credito c, finan_cli.credito_cliente ccli, finan_cli.cliente cli, finan_cli.cuota_credito cc, finan_cli.sucursal s, finan_cli.tipo_documento td WHERE c.id = ccli.id_credito AND c.id = cc.id_credito AND ccli.tipo_documento = cli.tipo_documento AND ccli.documento = cli.documento AND ccli.id_sucursal = s.id AND cli.tipo_documento = td.id AND c.id = ? AND cc.id = ?"))
				{
					$stmt68->bind_param('ii', $idCredito, $idCuotaCredito);
					$stmt68->execute();    
					$stmt68->store_result();
					
					$totR68 = $stmt68->num_rows;

					if($totR68 > 0)
					{
						$stmt68->bind_result($estado_credito_db_res, $id_titular_cliente_db_res, $nombres_cliente_db_res, $apellidos_cliente_db_res, $nombre_sucursal_db_res, $numero_cuota_db_res, $usuario_registro_pago_cuota_db_res, $tipo_documento_cliente_db_res, $documento_cliente_db_res, $monto_cuota_original_db_res);
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
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;
				}

				if($stmt70 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM finan_cli.mora_cuota_credito mcc, finan_cli.cuota_credito cc WHERE mcc.id_cuota_credito = cc.id AND cc.id_credito = ? AND cc.id = ?"))
				{
					$stmt70->bind_param('ii', $idCredito, $idCuotaCredito);
					$stmt70->execute();    
					$stmt70->store_result();
					
					$totR70 = $stmt70->num_rows;

					if($totR70 > 0)
					{
						$stmt70->bind_result($monto_interes_cuota_credito_db_res);
						$stmt70->fetch();
						
						$stmt70->free_result();
						$stmt70->close();
					}
					else $monto_interes_cuota_credito_db_res = 0;
				}
				else
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;
				}						
				
				if(empty($id_titular_cliente_db_res)) $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang']);
				else $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang']);	
				
				echo translate('Msg_Pay_Fee_Credit_OK',$GLOBALS['lang']).'=:=:='.$estado_credito_db_res.'=::=::='.$date_registro_a_fpcc_db.'|'.$idCredito.'|'.$numero_cuota_db_res.'|'.$tipo_cuenta_texto_cliente.'|'.$nombres_cliente_db_res.' '.$apellidos_cliente_db_res.'|'.$nombre_sucursal_db_res.'|'.$usuario_registro_pago_cuota_db_res.'|'.$montoPago.'|'.$fecha_vencimiento_cuota_db_res.'|'.$tipo_documento_cliente_db_res.'|'.$documento_cliente_db_res.'|'.$monto_cuota_original_db_res.'|'.$monto_interes_cuota_credito_db_res.'=:::=:::='.json_encode($array).'=::::=::::='.$totR69;
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
					$motivo = 67;
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
				echo '			<h3 class="panel-title">'.translate('Lbl_Authorize_Pay_Fee_Credit',$GLOBALS['lang']).'</h3>';
				echo ' 		</div>';
				echo '		<div class="panel-body">';
				echo '			<form id="formularionaspcc" role="form">';		
				echo '				<div class="form-group form-inline">';
				echo '					<label class="control-label" for="usuariosupervisorn">'.translate('Lbl_User_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="usuariosupervisorn">';
				echo '						<input title="'.translate('Msg_User_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="usuariosupervisorni" name="usuariosupervisorni" type="text" maxlength="50" />';
				echo '					</div>';
				echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="passwordsupervisorn">'.translate('Lbl_Password_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="passwordsupervisorn">';
				echo '						<input title="'.translate('Msg_Password_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="passwordsupervisorni" name="passwordsupervisorni" type="password" maxlength="128" />';
				echo '					</div>';		
				echo '				</div>';
				echo '				<div class="form-group form-inline">';
				echo '					<div id="img_loader_13"></div>';		
				echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarVS" id="btnCancelarVS" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogvalidsuppagocuotacredit\').dialog(\'close\');" style="margin-left:10px;" />';
				echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarS" id="btnValidarS" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarAutorizacionSupervisorPagoCuota(document.getElementById(\'formularionaspcc\'));"/>';										
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
			$esUltimaCuota = 0;
			
			if($numero_cuota_db == $cantidad_cuotas_credito_db) $esUltimaCuota = 1;
			
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
				$motivo = 73;
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
			
			if(!$stmt43 = $mysqli->prepare("UPDATE finan_cli.cuota_credito SET fecha_pago = ?, monto_pago = ?, estado = ?, usuario_registro_pago = ? WHERE id = ?"))
			{
				echo $mysqli->error;
				$mysqli->rollback();
				$mysqli->autocommit(TRUE);
				return;
			}
			else
			{
				$date_registro_a_fpcc_db = date("YmdHis");
				$estadoP = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
				$stmt43->bind_param('sissi', $date_registro_a_fpcc_db, $montoPago, $estadoP, $_SESSION['username'], $idCuotaCredito);
				if(!$stmt43->execute())
				{
					echo $mysqli->error;
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
					return;						
				}

				$date_registro = date("YmdHis");				
				$valor_log_user = "UPDATE finan_cli.cuota_credito SET fecha_pago = ".$date_registro_a_fpcc_db.", monto_pago = ".$montoPago.", estado = ".$estadoP." WHERE id = ".$idCuotaCredito;

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
			
			if($esUltimaCuota == 1)
			{
				if(!$stmt43 = $mysqli->prepare("UPDATE finan_cli.credito SET estado = ? WHERE id = ?"))
				{
					echo $mysqli->error;
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
					return;
				}
				else
				{
					$estadoP = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
					$stmt43->bind_param('si', $estadoP, $idCredito);
					if(!$stmt43->execute())
					{
						echo $mysqli->error;
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						return;						
					}

					$date_registro = date("YmdHis");				
					$valor_log_user = "UPDATE finan_cli.credito SET estado = ".$estadoP." WHERE id = ".$idCredito;

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
						
			$mysqli->commit();
			$mysqli->autocommit(TRUE);
			
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
									if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
									else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>';
								}
								else
								{
									if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
									else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
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
									//if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
									//else $array[$posicion]['accionesv'] = '---';
									$array[$posicion]['accionesv'] = '---';
								}
								else
								{
									//if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
									if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
									else $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
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
								if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
								{
									if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_PDF_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>';
									else $array[$posicion]['accionesv'] = '---';
								}
								else $array[$posicion]['accionesv'] = '---';
							}
							else
							{
								if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
								{
									if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_PDF_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>';
									else $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
								}
								else 
								{
									$array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
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
			
			if($stmt68 = $mysqli->prepare("SELECT c.estado, cli.id_titular, cli.nombres, cli.apellidos, s.nombre, cc.numero_cuota, cc.usuario_registro_pago, td.nombre, cli.documento, cc.monto_cuota_original FROM finan_cli.credito c, finan_cli.credito_cliente ccli, finan_cli.cliente cli, finan_cli.cuota_credito cc, finan_cli.sucursal s, finan_cli.tipo_documento td WHERE c.id = ccli.id_credito AND c.id = cc.id_credito AND ccli.tipo_documento = cli.tipo_documento AND ccli.documento = cli.documento AND ccli.id_sucursal = s.id AND cli.tipo_documento = td.id AND c.id = ? AND cc.id = ?"))
			{
				$stmt68->bind_param('ii', $idCredito, $idCuotaCredito);
				$stmt68->execute();    
				$stmt68->store_result();
				
				$totR68 = $stmt68->num_rows;

				if($totR68 > 0)
				{
					$stmt68->bind_result($estado_credito_db_res, $id_titular_cliente_db_res, $nombres_cliente_db_res, $apellidos_cliente_db_res, $nombre_sucursal_db_res, $numero_cuota_db_res, $usuario_registro_pago_cuota_db_res, $tipo_documento_cliente_db_res, $documento_cliente_db_res, $monto_cuota_original_db_res);
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
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;
			}

			if($stmt70 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM finan_cli.mora_cuota_credito mcc, finan_cli.cuota_credito cc WHERE mcc.id_cuota_credito = cc.id AND cc.id_credito = ? AND cc.id = ?"))
			{
				$stmt70->bind_param('ii', $idCredito, $idCuotaCredito);
				$stmt70->execute();    
				$stmt70->store_result();
				
				$totR70 = $stmt70->num_rows;

				if($totR70 > 0)
				{
					$stmt70->bind_result($monto_interes_cuota_credito_db_res);
					$stmt70->fetch();
					
					$stmt70->free_result();
					$stmt70->close();
				}
				else $monto_interes_cuota_credito_db_res = 0;
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;
			}						
			
			if(empty($id_titular_cliente_db_res)) $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang']);
			else $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang']);	
			
			echo translate('Msg_Pay_Fee_Credit_OK',$GLOBALS['lang']).'=:=:='.$estado_credito_db_res.'=::=::='.$date_registro_a_fpcc_db.'|'.$idCredito.'|'.$numero_cuota_db_res.'|'.$tipo_cuenta_texto_cliente.'|'.$nombres_cliente_db_res.' '.$apellidos_cliente_db_res.'|'.$nombre_sucursal_db_res.'|'.$usuario_registro_pago_cuota_db_res.'|'.$montoPago.'|'.$fecha_vencimiento_cuota_db_res.'|'.$tipo_documento_cliente_db_res.'|'.$documento_cliente_db_res.'|'.$monto_cuota_original_db_res.'|'.$monto_interes_cuota_credito_db_res.'=:::=:::='.json_encode($array).'=::::=::::='.$totR69;
			return;
		}

		return;
?>