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
		$nrosCuotasCredito=htmlspecialchars($_POST["nrosCuotasCredito"], ENT_QUOTES, 'UTF-8');
				
		$nrosCuotasCreditoControl = explode("|",$nrosCuotasCredito);
		$nrosCuotasCreditoCDB = str_replace("|",",",$nrosCuotasCredito);
		
		if(count($nrosCuotasCreditoControl) == 1)
		{
			echo translate('Msg_Selected_Quotas_Have_Inconsistencies',$GLOBALS['lang']);
			return;			
		}
		
		if($stmt93 = $mysqli->prepare("SELECT cc.numero_cuota FROM finan_cli.cuota_credito cc, finan_cli.credito c, finan_cli.credito_cliente ccli WHERE c.id = ccli.id_credito AND c.id = cc.id_credito AND cc.id_credito = ? AND cc.estado IN (?,?) AND cc.numero_cuota IN (".$nrosCuotasCreditoCDB.")"))
		{
			$estadoU = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
			$estadoD = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
			$stmt93->bind_param('iss', $idCredito, $estadoU, $estadoD);
			$stmt93->execute();    
			$stmt93->store_result();
			
			$totR93 = $stmt93->num_rows;

			if($totR93 > 0)
			{
				$stmt93->bind_result($numero_cuota_control_db);
				$stmt93->fetch();
				
				$stmt93->free_result();
				$stmt93->close();				
			}
			else 
			{
				echo translate('Msg_Selected_Quotas_Have_Inconsistencies',$GLOBALS['lang']);
				return;				
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']).$mysqli->error;
			return;
		}		
		
		$mysqli->autocommit(FALSE);
		$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
		$cuotasRecursive = explode("|",$nrosCuotasCredito);
		$datosCuotasPagadas = "";
		$montoPagoFinal = 0;
		for($i = 0; $i < count($cuotasRecursive); $i++) 
		{
			if($datosCuotasPagadas != "") $datosCuotasPagadas = $datosCuotasPagadas.'!';
			if($stmt63 = $mysqli->prepare("SELECT cc.numero_cuota, cc.monto_cuota_original, c.monto_compra, c.cantidad_cuotas, ccli.documento, ccli.tipo_documento, cc.fecha_pago, cc.monto_pago, cc.id  FROM finan_cli.cuota_credito cc, finan_cli.credito c, finan_cli.credito_cliente ccli WHERE c.id = ccli.id_credito AND c.id = cc.id_credito AND cc.numero_cuota = ? AND cc.id_credito = ? AND cc.estado IN (?,?)"))
			{
				$estadoU = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
				$estadoD = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
				$stmt63->bind_param('iiss', $cuotasRecursive[$i], $idCredito, $estadoU, $estadoD);
				$stmt63->execute();    
				$stmt63->store_result();
				
				$totR63 = $stmt63->num_rows;

				if($totR63 > 0)
				{
					$stmt63->bind_result($numero_cuota_db, $monto_cuota_original_db, $monto_compra_orig_credito_db, $cantidad_cuotas_credito_db, $documento_cliente_credito_db, $tipo_documento_cliente_credito_db, $fecha_pago_cuota_credito_db, $monto_pago_cuota_credito_db, $idCuotaCredito);
					$stmt63->fetch();
					
					$montoPago = $monto_cuota_original_db;
					
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
			
			if($stmt83 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM finan_cli.mora_cuota_credito mcc, finan_cli.cuota_credito cc WHERE mcc.id_cuota_credito = cc.id AND cc.id_credito = ? AND cc.id = ?"))
			{
				$stmt83->bind_param('ii', $idCredito, $idCuotaCredito);
				$stmt83->execute();    
				$stmt83->store_result();
				
				$totR83 = $stmt83->num_rows;

				if($totR83 > 0)
				{
					$stmt83->bind_result($monto_interes_cuota_credito_db_e);
					$stmt83->fetch();
				}
				else $monto_interes_cuota_credito_db_e = 0;
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;
			}			
			
			if(!empty($monto_interes_cuota_credito_db_e)) $datosCuotasPagadas = $datosCuotasPagadas.$numero_cuota_db.'¡'.$monto_cuota_original_db.'¡'.$monto_interes_cuota_credito_db_e;
			else $datosCuotasPagadas = $datosCuotasPagadas.$numero_cuota_db.'¡'.$monto_cuota_original_db.'¡0';
			$montoPago = $montoPago + $monto_interes_cuota_credito_db_e;
			$montoPagoFinal = $montoPagoFinal + $montoPago;
			$esUltimaCuota = 0;
			
			if($numero_cuota_db == $cantidad_cuotas_credito_db) $esUltimaCuota = 1;
						
			if(!$stmt80 = $mysqli->prepare("INSERT INTO finan_cli.token_pago_cuota(fecha,tipo_documento,documento,id_motivo,token,usuario,validado) VALUES (?,?,?,?,?,?,?)"))
			{
				echo $mysqli->error;
				$mysqli->rollback();
				$mysqli->autocommit(TRUE);
				return;
			}
			else
			{
				$date_registro_a_pcc_db = date("YmdHis");
				$motivo = 74;
				$token = md5(uniqid(rand(), true));
				$token = hash('sha512', $token);
				$validacionI = 1;
				$stmt80->bind_param('sisissi', $date_registro_a_pcc_db, $tipo_documento_cliente_credito_db, $documento_cliente_credito_db, $motivo, $token, $_SESSION['username'], $validacionI);
				if(!$stmt80->execute())
				{
					echo $mysqli->error;
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
					return;						
				}		
			}
			
			if(!$stmt81 = $mysqli->prepare("INSERT INTO finan_cli.pago_seleccion_cuotas_credito(id_cuota_credito,fecha,monto,usuario) VALUES (?,?,?,?)"))
			{
				echo $mysqli->error;
				$mysqli->rollback();
				$mysqli->autocommit(TRUE);
				return;
			}
			else
			{
				$date_registro_a_ppcc_db = date("YmdHis");
				$stmt81->bind_param('isis', $idCuotaCredito, $date_registro_a_ppcc_db, $monto_cuota_original_db, $_SESSION['username']);
				if(!$stmt81->execute())
				{
					echo $mysqli->error;
					$mysqli->rollback();
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
									if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
									else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';									
								}
								else
								{									
									if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
									else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>';
								}
							}
							else
							{
								if($totR105 > 0)
								{	
									if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
									else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
								}
								else
								{
									if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
									else $array[$posicion]['accionesv'] = '<button id="pagoCuotaNro'.$numero_cuota_db_r.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';									
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
								//if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
								//else $array[$posicion]['accionesv'] = '---';
								if($totR105 > 0)
								{	
									$array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
								}
								else
								{
									$array[$posicion]['accionesv'] = '---';
								}
							}
							else
							{
								//if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>';
								if($totR105 > 0)
								{									
									if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
									else $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
								}
								else
								{
									if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';
									else $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>';									
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
									if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_PDF_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
									else $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
								}
								else $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';								
							}
							else
							{
								if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
								{
									if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_PDF_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>';
									else $array[$posicion]['accionesv'] = '---';
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
									if($totR65 == 0 || $totR66 == 0) $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_PDF_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
									else $array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
								}
								else 
								{
									$array[$posicion]['accionesv'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button>';
								}
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
		
		echo translate('Msg_Pay_Fees_Credit_Selection_OK',$GLOBALS['lang']).'=:=:='.$estado_credito_db_res.'=::=::='.$date_registro_a_fpcc_db.'|'.$idCredito.'|'.count($nrosCuotasCreditoControl).'|'.$tipo_cuenta_texto_cliente.'|'.$nombres_cliente_db_res.' '.$apellidos_cliente_db_res.'|'.$nombre_sucursal_db_res.'|'.$_SESSION['username'].'|'.$montoPagoFinal.'|'.$fecha_vencimiento_cuota_db_res.'|'.$tipo_documento_cliente_db_res.'|'.$documento_cliente_db_res.'|'.$datosCuotasPagadas.'=:::=:::='.json_encode($array).'=::::=::::='.$totR69;
		return;
?>