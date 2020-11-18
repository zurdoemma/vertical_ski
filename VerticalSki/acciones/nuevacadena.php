<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');return;}
		if (!verificar_permisos_admin()){header('Location:../sinautorizacion.php?activauto=1');return;}

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
		echo '			<h3 class="panel-title">'.translate('Lbl_New_Chain',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_5"></div>';
		echo '			<form id="formularionu" role="form">';
		echo '				<div class="form-group form-inline">';							
		echo '					<label class="control-label" for="razonsocialchainn">'.translate('Lbl_Business Name_Chain',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="razonsocialchainn">';
		echo '						<input title="'.translate('Msg_A_Business_Name_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="razonsocialchainni" name="razonsocialchainni" type="text" maxlength="250" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="cuitcuilchainn">'.translate('Lbl_CUIT_CUIL_Chain',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="cuitcuilchainn">';
		echo '						<input title="'.translate('Msg_A_CUIT_CUIL_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="cuitcuilchainni" name="cuitcuilchainni" type="text" maxlength="20" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="emailchainn">'.translate('Lbl_Email_Chain',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="emailchainn">';
		echo '						<input title="'.translate('Msg_A_Chain_Email_Invalid',$GLOBALS['lang']).'" class="form-control input-sm" id="emailchainni" name="emailchainni" type="text" maxlength="150" />';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';							
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="telefonochainn">'.translate('Lbl_Phone_Chain',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="telefonochainn">';
		echo '						<input title="'.translate('Msg_A_Phone_Chain_Must_Enter_A_Whole',$GLOBALS['lang']).'" class="form-control input-sm" id="telefonochainni" name="telefonochainni" type="text" maxlength="20" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="nombrefantasiachainn">'.translate('Lbl_Fantasy_Name_Chain',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nombrefantasiachainn">';
		echo '						<input title="'.translate('Msg_A_Fantasy_Name_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nombrefantasiachainni" name="nombrefantasiachainni" type="text" maxlength="100" />';
		echo '					</div>';				
		echo '				</div>';	
		echo '				<div class="form-group form-inline">';				
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarN" id="btnCancelarN" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialognewchain\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargarN" id="btnCargarN" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarNuevaCadena(document.getElementById(\'formularionu\'));"/>';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		return;
?>