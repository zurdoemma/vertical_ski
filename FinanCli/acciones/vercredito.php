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

		if ($stmt = $mysqli->prepare("SELECT c.id, c.monto_compra, cc.fecha, c.cantidad_cuotas, pc.id, cli.nombres, cli.apellidos, cli.id_titular, c.monto_credito_original, cli.tipo_documento, cli.documento, t.numero, cc.tipo_documento_adicional, cc.documento_adicional, c.abona_primera_cuota, c.minimo_entrega FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.cliente cli, finan_cli.plan_credito pc, finan_cli.tipo_documento td, finan_cli.cliente_x_telefono cxt, finan_cli.telefono t WHERE cli.tipo_documento = cxt.tipo_documento AND cli.documento = cxt.documento AND cxt.id_telefono = t.id AND cxt.preferido = 1 AND pc.id = c.id_plan_credito AND c.id = cc.id_credito AND cc.tipo_documento = cli.tipo_documento AND cc.documento = cli.documento AND cc.tipo_documento = td.id AND c.id = ?")) 
		{
			$stmt->bind_param('i', $idCredito);
			$stmt->execute();    
			$stmt->store_result();
	 
			$stmt->bind_result($id_credit_client, $monto_compra_credito_cli, $fecha_cre_pi, $cantidad_cuotas_plan_credito_s_db, $nombre_plan_credito_s_db, $nombres_cliente_db, $apellidos_cliente_db, $id_titular_cliente_db, $montoTotalCredito, $nombre_tipo_documento_cliente_db, $documento, $numero_telefono_cliente_db, $tipo_documento_adicional_cliente_db, $documento_adicional_cliente_db, $abona_primera_cuota_cliente_db, $minimo_entrega_cliente_db);			
			
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_Credit_Client_Not_Exist',$GLOBALS['lang']);
				return;	
			}					
			
			
			if($stmt62 = $mysqli->prepare("SELECT cc.numero_cuota, cc.fecha_vencimiento, cc.monto_cuota_original, cc.estado FROM finan_cli.cuota_credito cc WHERE cc.id_credito = ? ORDER BY cc.numero_cuota"))
			{
				$stmt62->bind_param('i', $idCredito);
				$stmt62->execute();    
				$stmt62->store_result();
				
				$totR62 = $stmt62->num_rows;

				if($totR62 > 0)
				{
					$stmt62->bind_result($numero_cuota_db, $fecha_vencimiento_cuota_db, $monto_original_cuota_db, $estado_cuota_db);
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
				
		if(empty($tipo_documento_adicional_cliente_db) && empty($documento_adicional_cliente_db)) $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang']);
		else $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang']);	
		
		echo translate('Msg_View_Credit_OK',$GLOBALS['lang']);	
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Lbl_View_Data_Credit',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_16"></div>';
		echo '			<form id="formularionc" role="form">';
		if(!empty($id_titular_cliente_db) || (!empty($tipo_documento_adicional_cliente_db) && !empty($documento_adicional_cliente_db))) 
		{
			echo '				<div class="form-group form-inline">';		
			echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="tipodocumentocreditclientvt">'.translate('Lbl_Type_Document_Credit_Headline2',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="tipodocumentocreditclientvt">';
			echo '						<select class="form-control input-sm" name="tipodocumentocreditclientvti" id="tipodocumentocreditclientvti" style="width:190px;" disabled>';			 
											if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.tipo_documento")) 
											{ 
												$stmt->execute();    
												$stmt->store_result();
											 
												$stmt->bind_result($id_tipo_doc,$nombre_tipo_doc);
												while($stmt->fetch())
												{
													if($nombre_tipo_documento_cliente_db == $id_tipo_doc)
													{
														echo '<option selected value="'.$id_tipo_doc.'">'.$nombre_tipo_doc.'</option>';
													}
													else echo '<option value="'.$id_tipo_doc.'">'.$nombre_tipo_doc.'</option>';
												}
											}
											else  
											{
												echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
												return;			
											}
			echo '						</select>';
			echo '					</div>';
			echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="documentoclientcreditvt">'.translate('Lbl_Document_Credit_Headline',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="documentoclientcreditvt">';
			echo '						<input class="form-control input-sm green-border" id="documentoclientcreditvti" name="documentoclientcreditvti" type="text" maxlength="20" value="'.$documento.'" disabled/>';
			echo '					</div>';			
			echo '				</div>';
			echo '				<div class="form-group form-inline">';		
			echo '					&nbsp;<label class="control-label" for="tipodocumentocreditclientv">'.translate('Lbl_Type_Document_Credit2',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="tipodocumentocreditclientv">';
			echo '						<select class="form-control input-sm" name="tipodocumentocreditclientvi" id="tipodocumentocreditclientvi" style="width:190px;" disabled>';			 
											if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.tipo_documento")) 
											{ 
												$stmt->execute();    
												$stmt->store_result();
											 
												$stmt->bind_result($id_tipo_doc,$nombre_tipo_doc);
												while($stmt->fetch())
												{
													if($tipo_documento_adicional_cliente_db == $id_tipo_doc)
													{
														echo '<option selected value="'.$id_tipo_doc.'">'.$nombre_tipo_doc.'</option>';
													}
													else echo '<option value="'.$id_tipo_doc.'">'.$nombre_tipo_doc.'</option>';
												}
											}
											else  
											{
												echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
												return;			
											}
			echo '						</select>';
			echo '					</div>';
			echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="documentoclientcreditv">'.translate('Lbl_Document_Credit',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="documentoclientcreditv">';
			echo '						<input class="form-control input-sm green-border" id="documentoclientcreditvi" name="documentoclientcreditvi" type="text" maxlength="20" value="'.$documento_adicional_cliente_db.'" disabled/>';
			echo '					</div>';			
			echo '				</div>';			
		}
		else
		{
			echo '				<div class="form-group form-inline">';		
			echo '					&nbsp;<label class="control-label" for="tipodocumentocreditclientv">'.translate('Lbl_Type_Document_Credit2',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="tipodocumentocreditclientv">';
			echo '						<select class="form-control input-sm" name="tipodocumentocreditclientvi" id="tipodocumentocreditclientvi" style="width:190px;" disabled>';			 
											if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.tipo_documento")) 
											{ 
												$stmt->execute();    
												$stmt->store_result();
											 
												$stmt->bind_result($id_tipo_doc,$nombre_tipo_doc);
												while($stmt->fetch())
												{
													if($nombre_tipo_documento_cliente_db == $id_tipo_doc)
													{
														echo '<option selected value="'.$id_tipo_doc.'">'.$nombre_tipo_doc.'</option>';
													}
													else echo '<option value="'.$id_tipo_doc.'">'.$nombre_tipo_doc.'</option>';
												}
											}
											else  
											{
												echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
												return;			
											}
			echo '						</select>';
			echo '					</div>';
			echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="documentoclientcreditv">'.translate('Lbl_Document_Credit',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="documentoclientcreditv">';
			echo '						<input title="'.translate('Msg_A_Document_Client_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm green-border" id="documentoclientcreditvi" name="documentoclientcreditvi" type="text" maxlength="20" value="'.$documento.'" disabled/>';
			echo '					</div>';			
			echo '				</div>';			
		}
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nombreclientcreditv">'.translate('Lbl_Names_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nombreclientcreditv">';
		echo '						<input class="form-control input-sm" id="nombreclientcreditvi" name="nombreclientcreditvi" type="text" maxlength="150" value="'.$nombres_cliente_db.'" disabled />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="apellidoclientcreditv">'.translate('Lbl_Surnames_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="apellidoclientcreditv">';
		echo '						<input class="form-control input-sm" id="apellidoclientcreditvi" name="apellidoclientcreditvi" type="text" maxlength="150" value="'.$apellidos_cliente_db.'" disabled/>';
		echo '					</div>';		
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="tipoclientcreditv">'.translate('Lbl_Type_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="tipoclientcreditv">';
		echo '						<select class="form-control input-sm" name="tipoclientcreditvi" id="tipoclientcreditvi" style="width:190px;" disabled>';			 
		if($tipo_cuenta_texto_cliente ==  translate('Lbl_Type_Client_Headline',$GLOBALS['lang'])) echo '<option selected value="'.translate('Lbl_Type_Client_Headline',$GLOBALS['lang']).'">'.translate('Lbl_Type_Client_Headline',$GLOBALS['lang']).'</option>';
		else echo '						<option selected value="'.translate('Lbl_Type_Client_Additional',$GLOBALS['lang']).'">'.translate('Lbl_Type_Client_Additional',$GLOBALS['lang']).'</option>';
		echo '						</select>';
		echo '					</div>';
		echo '					 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="telefonoclientcreditv">'.translate('Lbl_Number_Phone_Credit_Client',$GLOBALS['lang']).':</label>';
		echo '					 <div class="form-group" id="telefonoclientcreditv">';
		echo '						<input class="form-control input-sm" id="telefonoclientcreditvi" name="telefonoclientcreditvi" type="text" maxlength="20" value="'.$numero_telefono_cliente_db.'" disabled />';
		echo '					 </div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="numeroclientcreditv">'.translate('Lbl_Number_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="numeroclientcreditv">';
		echo '						<input class="form-control input-sm" id="numeroclientcreditvi" name="numeroclientcreditvi" type="text" maxlength="11" value="'.$id_credit_client.'" disabled />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;<label class="control-label" for="montocompraclientcreditv">'.translate('Lbl_Purchase_Amount_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="montomaximoclientcreditv">';
		echo '						<input class="form-control input-sm" id="montocompraclientcreditvi" name="montocompraclientcreditvi" type="text" maxlength="11" value="'.str_replace(",",".",round(($monto_compra_credito_cli/100.00),2)).'" disabled />';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="montocreditoclientcreditv">'.translate('Lbl_Amount_Credit_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="montocreditoclientcreditv">';
		echo '						<input class="form-control input-sm" id="montocreditoclientcreditvi" name="montocreditoclientcreditvi" type="text" maxlength="11" value="'.str_replace(",",".",round(($montoTotalCredito/100.00),2)).'" disabled />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="plancreditclientv">'.translate('Lbl_Name_Plan_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="plancreditclientv">';
		echo '						<select class="form-control input-sm" name="plancreditclientvi" id="plancreditclientvi" style="width:190px;" disabled >';			 
										if ($stmt = $mysqli->prepare("SELECT pc.id, pc.nombre FROM finan_cli.plan_credito pc, finan_cli.cadena c WHERE pc.id_cadena = c.id AND c.id = ?")) 
										{ 
											$stmt->bind_param('i', $id_cadena_usuario);
											$stmt->execute();    
											$stmt->store_result();
										 
											$stmt->bind_result($id_plan_credito,$nombre_plan_credito);
											while($stmt->fetch())
											{
												if($nombre_plan_credito_s_db == $id_plan_credito)
												{
													echo '<option selected value="'.$id_plan_credito.'">'.$nombre_plan_credito.'</option>';
												}												
												echo '<option value="'.$id_plan_credito.'">'.$nombre_plan_credito.'</option>';
											}
										}
										else  
										{
											echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
											return;			
										}
		echo '						</select>';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';		
		echo '					&nbsp;&nbsp;<label class="control-label" for="validarpagoprimeracuotan">'.translate('Msg_Pay_First_Fee',$GLOBALS['lang']).':</label>';			
		echo '					<div class="form-group" id="validarpagoprimeracuotan">';	
		echo '						<label class="switch">';
		if($abona_primera_cuota_cliente_db == 1) echo '						  <input type="checkbox" id="validarpagoprimeracuotani" name="validarpagoprimeracuotani" checked disabled />';
		else echo '						  <input type="checkbox" id="validarpagoprimeracuotani" name="validarpagoprimeracuotani" disabled />';
		echo '						  <span class="slider round"></span>';
		echo '						</label>';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="minimoentregaclientcreditv">'.translate('Lbl_Minimum_Delivery',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="minimoentregaclientcreditv">';
		echo '						<input class="form-control input-sm" id="minimoentregaclientcreditvi" name="minimoentregaclientcreditvi" type="text" maxlength="11" value="'.str_replace(",",".",round(($minimo_entrega_cliente_db/100.00),2)).'" disabled />';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline"><hr />';
		echo '					<div class="panel-group">				
									<div class="panel panel-default" style="width:630px;">
										<div id="panel-title-header" class="panel-heading">
											<h3 class="panel-title">'.translate('Lbl_Fees_Credit',$GLOBALS['lang']).'</h3>
										</div>
										<div id="apDiv11" class="panel-body">
											<div id="tablefeescreditclientv" class="table-responsive">
												<table id="tablefeescreditclienttv" data-classes="table table-hover table-condensed"
													data-striped="true" data-pagination="true">
													<thead>
														<tr>
															<th class="col-xs-1 text-center" data-field="nrocuota" data-sortable="true">'.translate('Lbl_Number_Fee_Credit',$GLOBALS['lang']).'</th>
															<th class="col-xs-2 text-center" data-field="fechavencimientov" data-sortable="true">'.translate('Lbl_Date_Expired_Fee_Credit',$GLOBALS['lang']).'</th>
															<th class="col-xs-1 text-center" data-field="montocuotav" data-sortable="true">'.translate('Lbl_Amount_Fee_Credit',$GLOBALS['lang']).'</th>
															<th class="col-xs-1 text-center" data-field="estadov" data-sortable="true">'.translate('Lbl_State_Fee_Credit',$GLOBALS['lang']).'</th>														
														</tr>						
													</thead>
													<tbody>';
														while($stmt62->fetch())
														{		
															echo '<tr>';
															echo '<td>'.$numero_cuota_db.'</td>';
															echo '<td>'.substr($fecha_vencimiento_cuota_db,6,2).'/'.substr($fecha_vencimiento_cuota_db,4,2).'/'.substr($fecha_vencimiento_cuota_db,0,4).'</td>';
															echo '<td>$'.round(($monto_original_cuota_db/100.00),2).'</td>';															
															echo '<td>'.$estado_cuota_db.'</td>';
															echo '</tr>';
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
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnSalirVC" id="btnSalirVC" value="'.translate('Lbl_Exit',$GLOBALS['lang']).'" onClick="$(\'#dialogviewcredit\').dialog(\'close\');" style="margin-left:10px;" />';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		return;
?>