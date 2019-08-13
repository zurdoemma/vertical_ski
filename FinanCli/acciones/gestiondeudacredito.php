<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');return;}
		if (!verificar_permisos_usuario()){header('Location:../sinautorizacion.php?activauto=1');return;}

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

		if ($stmt = $mysqli->prepare("SELECT c.id, c.monto_compra, cc.fecha, c.cantidad_cuotas, pc.nombre, cli.nombres, cli.apellidos, cli.id_titular, c.monto_credito_original, cli.tipo_documento, cli.documento, t.numero, cc.tipo_documento_adicional, cc.documento_adicional, c.estado FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.cliente cli, finan_cli.plan_credito pc, finan_cli.tipo_documento td, finan_cli.cliente_x_telefono cxt, finan_cli.telefono t WHERE cli.tipo_documento = cxt.tipo_documento AND cli.documento = cxt.documento AND cxt.id_telefono = t.id AND cxt.preferido = 1 AND pc.id = c.id_plan_credito AND c.id = cc.id_credito AND cc.tipo_documento = cli.tipo_documento AND cc.documento = cli.documento AND cc.tipo_documento = td.id AND c.id = ?")) 
		{
			$stmt->bind_param('i', $idCredito);
			$stmt->execute();    
			$stmt->store_result();
	 
			$stmt->bind_result($id_credit_client, $monto_compra_credito_cli, $fecha_cre_pi, $cantidad_cuotas_plan_credito_s_db, $nombre_plan_credito_s_db, $nombres_cliente_db, $apellidos_cliente_db, $id_titular_cliente_db, $montoTotalCredito, $nombre_tipo_documento_cliente_db, $documento, $numero_telefono_cliente_db, $tipo_documento_adicional_cliente_db, $documento_adicional_cliente_db, $estado_credito_cliente_db);			
			
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_Credit_Client_Not_Exist',$GLOBALS['lang']);
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
					$stmt62->bind_result($id_cuota_credito_db, $numero_cuota_db, $fecha_vencimiento_cuota_db, $monto_original_cuota_db, $estado_cuota_db, $fecha_pago_cuota_db);
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
			
			$stmt->fetch();

			$stmt->free_result();
			$stmt->close();
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;	
		}
				
		if(empty($id_titular_cliente_db)) $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang']);
		else $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang']);

		$montoIntereses = 0;
		if($stmt63 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM finan_cli.mora_cuota_credito mcc, finan_cli.cuota_credito cc WHERE mcc.id_cuota_credito = cc.id AND cc.id_credito = ?"))
		{
			$stmt63->bind_param('i', $idCredito);
			$stmt63->execute();    
			$stmt63->store_result();
			
			$totR63 = $stmt63->num_rows;

			if($totR63 > 0)
			{
				$stmt63->bind_result($monto_interes_cuota_credito_db);
				$stmt63->fetch();
				
				$montoIntereses = $monto_interes_cuota_credito_db;
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}			
		$montoTotalConInteresesCredito = $montoTotalCredito + $montoIntereses;
		
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

		
		echo translate('Msg_View_Credit_OK',$GLOBALS['lang']);	
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Lbl_View_Data_Credit',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_17"></div>';
		echo '			<form id="formularionc" role="form">';
		echo '				<div class="form-group form-inline">';
		echo '					<div class="form-group" id="tokenvalidsuppagocuotacre" style="display:none;">';
		echo '						<input class="form-control input-sm green-border" id="tokenvalidsuppagocuotacrei" name="tokenvalidsuppagocuotacrei" type="text" maxlength="128" />';
		echo '					</div>';
		echo '					<div class="form-group" id="tokenvalidsuppagocuotascre" style="display:none;">';
		echo '						<input class="form-control input-sm green-border" id="tokenvalidsuppagocuotascrei" name="tokenvalidsuppagocuotascrei" type="text" maxlength="128" />';
		echo '					</div>';
		echo '					<div class="form-group" id="tokenvalidsuppagototaldeudacre" style="display:none;">';
		echo '						<input class="form-control input-sm green-border" id="tokenvalidsuppagototaldeudacrei" name="tokenvalidsuppagototaldeudacrei" type="text" maxlength="128" />';
		echo '					</div>';		
		echo '					<div class="form-group" id="idcreditov" style="display:none;">';
		echo '						<input class="form-control input-sm green-border" id="idcreditovi" name="idcreditovi" type="text" maxlength="11" value="'.$idCredito.'" disabled />';
		echo '					</div>';
		echo '					&nbsp;<label class="control-label" for="numerocreditv">'.translate('Lbl_Credit_Number',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="numerocreditv">';
		echo '						<input class="form-control input-sm" id="numerocreditvi" name="numerocreditvi" type="text" maxlength="11" value="'.$idCredito.'" disabled />';
		echo '					</div>';
		echo '					<div class="form-group" id="tokenvalidsupcambioestadocuotacre" style="display:none;">';
		echo '						<input class="form-control input-sm green-border" id="tokenvalidsupcambioestadocuotacrei" name="tokenvalidsupcambioestadocuotacrei" type="text" maxlength="128" />';
		echo '					</div>';		
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="cantidadcuotascreditv">'.translate('Lbl_Fees_Print_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="cantidadcuotascreditv">';
		echo '						<input class="form-control input-sm" id="cantidadcuotascreditvi" name="cantidadcuotascreditvi" type="text" maxlength="11" value="'.$cantidad_cuotas_plan_credito_s_db.'" disabled/>';
		echo '					</div>';		
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="plancreditv">'.translate('Lbl_Name_Print_Credit_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="plancreditv">';
		echo '						<input class="form-control input-sm" id="plancreditvi" name="plancreditvi" type="text" maxlength="150" value="'.$nombre_plan_credito_s_db.'" disabled />';
		echo '					</div>';		
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';
		echo '					 &nbsp;&nbsp;<label class="control-label" for="montototalcreditv">'.translate('Lbl_Total_Amount_Credit',$GLOBALS['lang']).':</label>';
		echo '					 <div class="form-group" id="telefonoclientcreditv">';
		echo '						<input class="form-control input-sm" id="montototalcreditvi" name="montototalcreditvi" type="text" maxlength="11" value="'.round(($montoTotalConInteresesCredito/100.00),2).'" disabled />';
		echo '					 </div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="interesescreditv">'.translate('Lbl_Amount_Interests_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="interesescreditv">';
		echo '						<input class="form-control input-sm" id="interesescreditvi" name="interesescreditvi" type="text" maxlength="11" value="'.round(($montoIntereses/100.00),2).'" disabled />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="estadocreditv">'.translate('Lbl_State_Fee_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="estadocreditv">';
		echo '						<input class="form-control input-sm" id="estadocreditvi" name="estadocreditvi" type="text" maxlength="50" value="'.$estado_credito_cliente_db.'" disabled />';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline"><hr />';
		echo '					<div class="panel-group">				
									<div class="panel panel-default" style="width:1000px;">
										<div id="panel-title-header" class="panel-heading">
											<h3 class="panel-title">'.translate('Lbl_Fees_Credit',$GLOBALS['lang']).'</h3>
										</div>
										<div id="apDiv11" class="panel-body">
											<div id="tablefeescreditclientv" class="table-responsive">
												<table id="tablefeescreditclienttv" data-classes="table table-hover table-condensed"
													data-striped="true" data-pagination="true">
													<thead>
														<tr>
															<th class="col-xs-1 text-center" data-field="seleccioncuota" data-sortable="true">'.translate('Lbl_Selects_Fees_Credit',$GLOBALS['lang']).'</th>
															<th class="col-xs-1 text-center" data-field="nrocuota" data-sortable="true">'.translate('Lbl_Number_Fee_Credit',$GLOBALS['lang']).'</th>
															<th class="col-xs-1 text-center" data-field="fechavencimientov" data-sortable="true">'.translate('Lbl_Date_Expire_Print_Credit',$GLOBALS['lang']).'</th>
															<th class="col-xs-1 text-center" data-field="montototalcuotav" data-sortable="true">'.translate('Lbl_Amount_Fee_Credit',$GLOBALS['lang']).'</th>
															<th class="col-xs-1 text-center" data-field="interesescuotav" data-sortable="true">'.translate('Lbl_Amount_Interests_Credit',$GLOBALS['lang']).'</th>
															<th class="col-xs-1 text-center" data-field="fechapagov" data-sortable="true">'.translate('Lbl_Payment_Date_Fee_Credit',$GLOBALS['lang']).'</th>
															<th class="col-xs-3 text-center" data-field="accionesv" data-sortable="true">'.translate('Lbl_Actions_Fee_Credit',$GLOBALS['lang']).'</th>
															<th class="col-xs-1 text-center" data-field="estadov" data-sortable="true">'.translate('Lbl_State_Fee_Credit',$GLOBALS['lang']).'</th>														
														</tr>						
													</thead>
													<tbody>';
														$pasoPrimeraCuota = 0;
														$cantidadCuotasPendientes = 0;
														while($stmt62->fetch())
														{		
															if($stmt64 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM finan_cli.mora_cuota_credito mcc, finan_cli.cuota_credito cc WHERE mcc.id_cuota_credito = cc.id AND cc.id_credito = ? AND cc.id = ?"))
															{
																$stmt64->bind_param('ii', $idCredito, $id_cuota_credito_db);
																$stmt64->execute();    
																$stmt64->store_result();
																
																$totR64 = $stmt64->num_rows;

																if($totR64 > 0)
																{
																	$stmt64->bind_result($monto_interes_cuota_credito_db);
																	$stmt64->fetch();
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
															
															echo '<tr>';
																if($estado_cuota_db == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']))
																{
																	$cantidadCuotasPendientes++;
																	if($pasoPrimeraCuota == 0)
																	{
																		if($numero_cuota_db == $totR62 && $cantidadCuotasPendientes == 1) echo '<td>---</td>';
																		else echo '<td><label class="switch"><input type="checkbox" id="seleccioncuotanro'.$numero_cuota_db.'" name="seleccioncuotanro'.$numero_cuota_db.'" /><span class="slider round"></span></label></td>';
																		echo '<td>'.$numero_cuota_db.'</td>';
																		echo '<td>'.substr($fecha_vencimiento_cuota_db,6,2).'/'.substr($fecha_vencimiento_cuota_db,4,2).'/'.substr($fecha_vencimiento_cuota_db,0,4).'</td>';
																		echo '<td>$'.round((($monto_original_cuota_db+$monto_interes_cuota_credito_db)/100.00),2).'</td>';															
																		echo '<td>$'.round(($monto_interes_cuota_credito_db/100.00),2).'</td>';
																		if(!empty($fecha_pago_cuota_db)) echo '<td>'.substr($fecha_pago_cuota_db,6,2).'/'.substr($fecha_pago_cuota_db,4,2).'/'.substr($fecha_pago_cuota_db,0,4).'</td>';
																		else echo '<td>---</td>';
																		if($monto_interes_cuota_credito_db == 0)
																		{
																			if($totR105 > 0)
																			{
																				if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) echo '<td><button id="pagoCuotaNro'.$numero_cuota_db.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button></td>';
																				else echo '<td><button id="pagoCuotaNro'.$numero_cuota_db.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button></td>';
																			}
																			else
																			{	
																				if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) echo '<td><button id="pagoCuotaNro'.$numero_cuota_db.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button></td>';
																				else echo '<td><button id="pagoCuotaNro'.$numero_cuota_db.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button></td>';
																			}
																		}
																		else
																		{
																			if($totR105 > 0)
																			{	
																				if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) echo '<td><button id="pagoCuotaNro'.$numero_cuota_db.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button></td>';
																				else echo '<td><button id="pagoCuotaNro'.$numero_cuota_db.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button></td>';
																			}
																			else
																			{
																				if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) echo '<td><button id="pagoCuotaNro'.$numero_cuota_db.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button></td>';
																				else echo '<td><button id="pagoCuotaNro'.$numero_cuota_db.'" type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']).'" onclick="pagarCuotaCredito('.$idCredito.','.$id_cuota_credito_db.')"><i class="fas fa-cash-register"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button></td>';																				
																			}
																		}
																		$pasoPrimeraCuota = 1;
																	}
																	else
																	{
																		if($numero_cuota_db == $totR62 && $cantidadCuotasPendientes == 1) echo '<td>---</td>';
																		else echo '<td><label class="switch"><input type="checkbox" id="seleccioncuotanro'.$numero_cuota_db.'" name="seleccioncuotanro'.$numero_cuota_db.'" /><span class="slider round"></span></label></td>';
																		echo '<td>'.$numero_cuota_db.'</td>';
																		echo '<td>'.substr($fecha_vencimiento_cuota_db,6,2).'/'.substr($fecha_vencimiento_cuota_db,4,2).'/'.substr($fecha_vencimiento_cuota_db,0,4).'</td>';
																		echo '<td>$'.round((($monto_original_cuota_db+$monto_interes_cuota_credito_db)/100.00),2).'</td>';															
																		echo '<td>$'.round(($monto_interes_cuota_credito_db/100.00),2).'</td>';
																		if(!empty($fecha_pago_cuota_db)) echo '<td>'.substr($fecha_pago_cuota_db,6,2).'/'.substr($fecha_pago_cuota_db,4,2).'/'.substr($fecha_pago_cuota_db,0,4).'</td>';
																		else echo '<td>---</td>';
																		if($monto_interes_cuota_credito_db == 0)
																		{
																			//if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button></td>';
																			//else echo '<td>---</td>';
																			if($totR105 > 0)
																			{
																				echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button></td>';
																			}
																			else
																			{
																				echo '<td>---</td>';
																			}
																		}
																		else
																		{
																			//if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Change_State_Fee_Credit',$GLOBALS['lang']).'" onclick="cambiarEstadoCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-sign-in-alt"></i></button></td>';
																			if($totR105 > 0)
																			{
																				if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button></td>';
																				else echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button></td>';																				
																			}
																			else
																			{
																				if($estado_cuota_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button></td>';
																				else echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button></td>';
																			}
																		}																		
																	}
																	echo '<td>'.$estado_cuota_db.'</td>';
																}
																else
																{
																	echo '<td>---</td>';
																	echo '<td>'.$numero_cuota_db.'</td>';
																	echo '<td>'.substr($fecha_vencimiento_cuota_db,6,2).'/'.substr($fecha_vencimiento_cuota_db,4,2).'/'.substr($fecha_vencimiento_cuota_db,0,4).'</td>';
																	echo '<td>$'.round((($monto_original_cuota_db+$monto_interes_cuota_credito_db)/100.00),2).'</td>';															
																	echo '<td>$'.round(($monto_interes_cuota_credito_db/100.00),2).'</td>';
																	if(!empty($fecha_pago_cuota_db)) echo '<td>'.substr($fecha_pago_cuota_db,6,2).'/'.substr($fecha_pago_cuota_db,4,2).'/'.substr($fecha_pago_cuota_db,0,4).'</td>';
																	else echo '<td>---</td>';
																	if($monto_interes_cuota_credito_db == 0)
																	{
																		if($totR105 > 0)
																		{	
																			if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
																			{
																				if($totR65 == 0 || $totR66 == 0) echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_PDF_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button></td>';
																				else echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button></td>';
																			}
																			else echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button></td>';
																		}
																		else
																		{
																			if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
																			{
																				if($totR65 == 0 || $totR66 == 0) echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_PDF_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button></td>';
																				else echo '<td>---</td>';
																			}
																			else echo '<td>---</td>';																			
																		}
																	}
																	else
																	{
																		if($totR105 > 0)
																		{																			
																			if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
																			{
																				if($totR65 == 0 || $totR66 == 0) echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_PDF_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button></td>';
																				else echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button></td>';
																			}
																			else 
																			{
																				echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Debt_Notices',$GLOBALS['lang']).'" onclick="verAvisosDeuda('.$id_cuota_credito_db.')"><i class="fas fa-diagnoses"></i></button></td>';
																			}
																		}
																		else
																		{
																			if($estado_cuota_db == translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']))
																			{
																				if($totR65 == 0 || $totR66 == 0) echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_Credit',$GLOBALS['lang']).'" onclick="reImprimirPagoCuotaCreditoCliente('.$id_cuota_credito_db.')"><i class="fas fa-print"></i></button>&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Payment_Amount_Fee_PDF_Credit',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfpagocuotacredito.php?idCredito='.$idCredito.'&idCuotaCredito='.$id_cuota_credito_db.'\')"><i class="far fa-file-pdf"></i></button></td>';
																				else echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button></td>';
																			}
																			else 
																			{
																				echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Interest_Fee_Credit',$GLOBALS['lang']).'" onclick="verInteresesCuotaCredito('.$id_cuota_credito_db.')"><i class="fas fa-money-check-alt"></i></button></td>';
																			}																			
																		}
																	}
																	echo '<td>'.$estado_cuota_db.'</td>';																	
																}
															echo '</tr>';
															
															$stmt64->free_result();
															$stmt64->close();
															
															$stmt66->free_result();
															$stmt66->close();
														}
														$stmt62->free_result();
														$stmt62->close();														
		echo '  									</tbody>					
												</table>
											</div>
										</div>
									</div>
								</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';				
		if($estado_credito_cliente_db == translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) || $estado_credito_cliente_db == translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang'])) 
		{
			echo '				<input type="button" class="btn btn-primary pull-right" name="btnSalirCD" id="btnSalirCD" value="'.translate('Lbl_Exit',$GLOBALS['lang']).'" onClick="$(\'#dialogviewcredit\').dialog(\'close\');" style="margin-left:10px;" />';										
			if($cantidadCuotasPendientes > 1)
			{
				echo '			<input type="button" class="btn btn-primary pull-right" name="btnPagoSeleccionCD" id="btnPagoSeleccionCD" value="'.translate('Lbl_Payment_Selection_Fees_Credit',$GLOBALS['lang']).'" onClick="confirmar_accion_pago_seleccion_cuotas(\''.translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']).'\',\''.translate('Msg_Be_Sure_To_Pay_Fees_Credit_Selection',$GLOBALS['lang']).'\');" style="margin-left:10px;" disabled />';				
				echo '			<input type="button" class="btn btn-primary pull-left" name="btnPagoTotalCD" id="btnPagoTotalCD" value="'.translate('Lbl_Payment_Total_Amount_Fees_Credit',$GLOBALS['lang']).'" onClick="pagoTotalDeuda('.$idCredito.');" style="margin-right:10px;" />';
				echo '			<input type="button" class="btn btn-primary pull-left" name="btnReimpresionPagoTotalCD" id="btnReimpresionPagoTotalCD" value="'.translate('Lbl_Reprint_Payment_Total_Amount_Fees_Credit',$GLOBALS['lang']).'" onClick="reimprimirPagoTotalDeuda('.$idCredito.');" style="display:none; margin-right:10px;" disabled/>';
				echo '			<input type="button" class="btn btn-primary pull-left" name="btnPDFPagoTotalCD" id="btnPDFPagoTotalCD" value="'.translate('Lbl_PDF_Payment_Total_Amount_Fees_Credit',$GLOBALS['lang']).'" onClick="window.open(\'acciones/mostrarpdfpagototaldeudacredito.php?idCredito='.$idCredito.'\');" style="display:none;" disabled/>';				
			}
		}
		else
		{
			if($totR65 > 0)
			{
				echo '			<input type="button" class="btn btn-primary pull-left" name="btnReimpresionPagoTotalCD" id="btnReimpresionPagoTotalCD" value="'.translate('Lbl_Reprint_Payment_Total_Amount_Fees_Credit',$GLOBALS['lang']).'" onClick="reimprimirPagoTotalDeuda('.$idCredito.');" style="margin-right:10px;" />';
				echo '			<input type="button" class="btn btn-primary pull-left" name="btnPDFPagoTotalCD" id="btnPDFPagoTotalCD" value="'.translate('Lbl_PDF_Payment_Total_Amount_Fees_Credit',$GLOBALS['lang']).'" onClick="window.open(\'acciones/mostrarpdfpagototaldeudacredito.php?idCredito='.$idCredito.'\');" />';				
			}
			echo '				<input type="button" class="btn btn-primary pull-right" name="btnSalirCD" id="btnSalirCD" value="'.translate('Lbl_Exit',$GLOBALS['lang']).'" onClick="$(\'#dialogviewcredit\').dialog(\'close\');" style="margin-left:10px;" />';										
		}
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		return;
?>