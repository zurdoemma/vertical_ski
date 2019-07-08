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
						
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Lbl_New_Credit',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_11"></div>';
		echo '			<form id="formularionc" role="form">';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;<label class="control-label" for="tipodocumentocreditclientn">'.translate('Lbl_Type_Document_Credit2',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="tipodocumentocreditclientn">';
		echo '						<select class="form-control input-sm" name="tipodocumentocreditclientni" id="tipodocumentocreditclientni" style="width:190px;">';			 
										if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.tipo_documento")) 
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
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="documentoclientcreditn">'.translate('Lbl_Document_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="documentoclientcreditn">';
		echo '						<input title="'.translate('Msg_A_Document_Client_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm green-border" id="documentoclientcreditni" name="documentoclientcreditni" type="text" maxlength="20" />';
		echo '					</div>';			
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nombreclientcreditn">'.translate('Lbl_Names_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nombreclientcreditn">';
		echo '						<input class="form-control input-sm" id="nombreclientcreditni" name="nombreclientcreditni" type="text" maxlength="150" disabled />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="apellidoclientcreditn">'.translate('Lbl_Surnames_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="apellidoclientcreditn">';
		echo '						<input class="form-control input-sm" id="apellidoclientcreditni" name="apellidoclientcreditni" type="text" maxlength="150" disabled/>';
		echo '					</div>';		
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="tipoclientcreditn">'.translate('Lbl_Type_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="tipoclientcreditn">';
		echo '						<select class="form-control input-sm" name="tipoclientcreditni" id="tipoclientcreditni" style="width:190px;" disabled>';			 
		echo '							<option selected value="'.translate('Lbl_Type_Client_Headline',$GLOBALS['lang']).'">'.translate('Lbl_Type_Client_Headline',$GLOBALS['lang']).'</option>';
		echo '							<option value="'.translate('Lbl_Type_Client_Additional',$GLOBALS['lang']).'">'.translate('Lbl_Type_Client_Additional',$GLOBALS['lang']).'</option>';
		echo '						</select>';
		echo '					</div>';
		echo '					 &nbsp;&nbsp;<label class="control-label" for="telefonoclientcreditn">'.translate('Lbl_Number_Phone_Credit_Client',$GLOBALS['lang']).':</label>';
		echo '					 <div class="form-group" id="telefonoclientcreditn">';
		echo '						<input class="form-control input-sm" id="telefonoclientcreditni" name="telefonoclientcreditni" type="text" maxlength="20" disabled />';
		echo '					 </div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="montomaximoclientcreditn">'.translate('Lbl_Max_Amount_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="montomaximoclientcreditn">';
		echo '						<input class="form-control input-sm" id="montomaximoclientcreditni" name="montomaximoclientcreditni" type="text" maxlength="11" disabled />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="montocompraclientcreditn">'.translate('Lbl_Purchase_Amount_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="montomaximoclientcreditn">';
		echo '						<input class="form-control input-sm" id="montocompraclientcreditni" name="montocompraclientcreditni" type="text" maxlength="11" disabled />';
		echo '					</div>';		
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