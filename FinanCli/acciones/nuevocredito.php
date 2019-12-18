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
		
		if($stmt48 = $mysqli->prepare("SELECT s.id_cadena FROM ".$db_name.".usuario u, ".$db_name.".sucursal s WHERE u.id_sucursal = s.id AND u.id = ?"))
		{
			$stmt48->bind_param('s', $_SESSION['username']);
			$stmt48->execute();    
			$stmt48->store_result();
			
			$totR48 = $stmt48->num_rows;

			if($totR48 > 0)
			{
				$stmt48->bind_result($id_cadena_usuario);
				$stmt48->fetch();
								
				$stmt48->free_result();
				$stmt48->close();
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
		
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Lbl_New_Credit',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_16"></div>';
		echo '			<form id="formularionc" role="form">';
		echo '				<div class="form-group form-inline text-center">';		
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="validarstatuscreditclientecren">'.translate('Lbl_Valid_Status_Credit_Client',$GLOBALS['lang']).':</label>';			
		echo '					<div class="form-group" id="validarstatuscreditclientecren">';	
		echo '						<label class="switch">';
		echo '						  <input type="checkbox" id="validarstatuscreditclientecreni" name="validarstatuscreditclientecreni" checked />';
		echo '						  <span class="slider round"></span>';
		echo '						</label>';
		echo '					</div>';
		echo '				</div>';
		echo '				<div class="form-group form-inline"><hr />';
		echo '					<div class="form-group" id="tokenveccredit" style="display:none;">';
		echo '						<input class="form-control input-sm green-border" id="tokenveccrediti" name="tokenveccrediti" type="text" maxlength="128" />';
		echo '					</div>';
		echo '					<div class="form-group" id="tokenvalidsupcre" style="display:none;">';
		echo '						<input class="form-control input-sm green-border" id="tokenvalidsupcrei" name="tokenvalidsupcrei" type="text" maxlength="128" />';
		echo '					</div>';
		echo '					<div class="form-group" id="tokenvalidexcesom" style="display:none;">';
		echo '						<input class="form-control input-sm green-border" id="tokenvalidexcesomi" name="tokenvalidexcesomi" type="text" maxlength="128" />';
		echo '					</div>';		
		echo '					&nbsp;<label class="control-label" for="tipodocumentocreditclientn">'.translate('Lbl_Type_Document_Credit2',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="tipodocumentocreditclientn">';
		echo '						<select class="form-control input-sm" name="tipodocumentocreditclientni" id="tipodocumentocreditclientni" style="width:190px;">';			 
										if ($stmt = $mysqli->prepare("SELECT id, nombre FROM ".$db_name.".tipo_documento")) 
										{ 
											$stmt->execute();    
											$stmt->store_result();
										 
											$stmt->bind_result($id_tipo_doc,$nombre_tipo_doc);
											while($stmt->fetch())
											{
												echo '<option value="'.$id_tipo_doc.'">'.$nombre_tipo_doc.'</option>';
											}
										}
										else  
										{
											echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
											return;			
										}
		echo '						</select>';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="documentoclientcreditn">'.translate('Lbl_Document_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="documentoclientcreditn">';
		echo '						<input title="'.translate('Msg_A_Document_Client_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm green-border" id="documentoclientcreditni" name="documentoclientcreditni" type="text" maxlength="20" />';
		echo '					</div>';			
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nombreclientcreditn">'.translate('Lbl_Names_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nombreclientcreditn">';
		echo '						<input class="form-control input-sm" id="nombreclientcreditni" name="nombreclientcreditni" type="text" maxlength="150" disabled />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="apellidoclientcreditn">'.translate('Lbl_Surnames_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="apellidoclientcreditn">';
		echo '						<input class="form-control input-sm" id="apellidoclientcreditni" name="apellidoclientcreditni" type="text" maxlength="150" disabled/>';
		echo '					</div>';		
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="tipoclientcreditn">'.translate('Lbl_Type_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="tipoclientcreditn">';
		echo '						<select class="form-control input-sm" name="tipoclientcreditni" id="tipoclientcreditni" style="width:190px;" disabled>';			 
		echo '							<option selected value="'.translate('Lbl_Type_Client_Headline',$GLOBALS['lang']).'">'.translate('Lbl_Type_Client_Headline',$GLOBALS['lang']).'</option>';
		echo '							<option value="'.translate('Lbl_Type_Client_Additional',$GLOBALS['lang']).'">'.translate('Lbl_Type_Client_Additional',$GLOBALS['lang']).'</option>';
		echo '						</select>';
		echo '					</div>';
		echo '					 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="telefonoclientcreditn">'.translate('Lbl_Number_Phone_Credit_Client',$GLOBALS['lang']).':</label>';
		echo '					 <div class="form-group" id="telefonoclientcreditn">';
		echo '						<input class="form-control input-sm" id="telefonoclientcreditni" name="telefonoclientcreditni" type="text" maxlength="20" disabled />';
		echo '					 </div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="montomaximoclientcreditn">'.translate('Lbl_Max_Amount_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="montomaximoclientcreditn">';
		echo '						<input class="form-control input-sm" id="montomaximoclientcreditni" name="montomaximoclientcreditni" type="text" maxlength="11" disabled />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;<label class="control-label" for="montocompraclientcreditn">'.translate('Lbl_Purchase_Amount_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="montocompraclientcreditn">';
		echo '						<input class="form-control input-sm" id="montocompraclientcreditni" name="montocompraclientcreditni" type="text" maxlength="11" disabled />';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="montocreditoclientcreditn">'.translate('Lbl_Amount_Credit_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="montocreditoclientcreditn">';
		echo '						<input class="form-control input-sm" id="montocreditoclientcreditni" name="montocreditoclientcreditni" type="text" maxlength="11" disabled />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="plancreditclientn">'.translate('Lbl_Name_Plan_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="plancreditclientn">';
		echo '						<select class="form-control input-sm" name="plancreditclientni" id="plancreditclientni" style="width:190px;" disabled >';			 
										/**
										if ($stmt = $mysqli->prepare("SELECT pc.id, pc.nombre FROM ".$db_name.".plan_credito pc, ".$db_name.".cadena c WHERE pc.id_cadena = c.id AND c.id = ?")) 
										{ 
											$stmt->bind_param('i', $id_cadena_usuario);
											$stmt->execute();    
											$stmt->store_result();
										 
											$stmt->bind_result($id_plan_credito,$nombre_plan_credito);
											while($stmt->fetch())
											{
												echo '<option value="'.$id_plan_credito.'">'.$nombre_plan_credito.'</option>';
											}
										}
										else  
										{
											echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
											return;			
										}
										*/
		echo '						</select>';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';		
		echo '					&nbsp;&nbsp;<label class="control-label" for="validarpagoprimeracuotan">'.translate('Msg_Pay_First_Fee',$GLOBALS['lang']).':</label>';			
		echo '					<div class="form-group" id="validarpagoprimeracuotan">';	
		echo '						<label class="switch">';
		echo '						  <input type="checkbox" id="validarpagoprimeracuotani" name="validarpagoprimeracuotani" disabled />';
		echo '						  <span class="slider round"></span>';
		echo '						</label>';
		echo '					</div>';
		echo '					<div class="form-group" id="minimoentregaclientcreditn" style="display:none;">';
		echo '						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="minimoentregaclientcreditn">'.translate('Lbl_Minimum_Delivery',$GLOBALS['lang']).':</label>';		
		echo '						<input class="form-control input-sm" id="minimoentregaclientcreditni" name="minimoentregaclientcreditni" type="text" maxlength="11" disabled />';
		echo '					</div>';		
		echo '				</div>';		
		echo '				<div class="form-group form-inline"><hr />';
		echo '					<div class="panel-group">				
									<div class="panel panel-default" style="width:630px;">
										<div id="panel-title-header" class="panel-heading">
											<h3 class="panel-title">'.translate('Lbl_Fees_Credit',$GLOBALS['lang']).'</h3>
										</div>
										<div id="apDiv11" class="panel-body">
											<div id="tablefeescreditclient" class="table-responsive">
												<table id="tablefeescreditclientt" data-classes="table table-hover table-condensed"
													data-striped="true" data-pagination="true">
													<thead>
														<tr>
															<th class="col-xs-1 text-center" data-field="cuota" data-sortable="true">'.translate('Lbl_Fee_Credit',$GLOBALS['lang']).'</th>
															<th class="col-xs-2 text-center" data-field="fechavencimiento" data-sortable="true">'.translate('Lbl_Date_Expired_Fee_Credit',$GLOBALS['lang']).'</th>
															<th class="col-xs-1 text-center" data-field="montocuota" data-sortable="true">'.translate('Lbl_Amount_Fee_Credit',$GLOBALS['lang']).'</th>
														</tr>						
													</thead>
													<tbody>';						
		echo '  									</tbody>					
												</table>
											</div>
										</div>
									</div>
								</div>';		
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';				
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarNC" id="btnCancelarNC" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialognewcredit\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargarNC" id="btnCargarNC" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarNuevoCredito(document.getElementById(\'formularionc\'));"/>';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		return;
?>