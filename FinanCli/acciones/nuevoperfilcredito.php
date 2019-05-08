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
		echo '			<h3 class="panel-title">'.translate('Lbl_New_Profile_Credit',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_11"></div>';
		echo '			<form id="formularionpc" role="form">';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nombreprofilecreditn">'.translate('Lbl_Name_Profile_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nombreprofilecreditn">';
		echo '						<input title="'.translate('Msg_A_Name_Profile_Credit_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nombreprofilecreditni" name="nombreprofilecreditni" type="text" maxlength="100" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="montomaximoprofilecreditn">'.translate('Lbl_Limit_Amount_Profile_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="montomaximoprofilecreditn">';
		echo '						<input title="'.translate('Msg_A_Amount_Limit_Profile_Credit_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="montomaximoprofilecreditni" name="montomaximoprofilecreditni" type="text" maxlength="11" />';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					<label class="control-label" for="descripcionprofilecreditn">'.translate('Lbl_Description_Profile_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="descripcionprofilecreditn">';
		echo '						<textarea rows="5" cols="67" title="'.translate('Msg_A_Description_Profile_Credit_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="descripcionprofilecreditni" name="descripcionprofilecreditni" type="text" maxlength="500" />';
		echo '					</div>';		
		echo '				</div>';			
		echo '				<div class="form-group form-inline">';				
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarNPC" id="btnCancelarNPC" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialognewprofilecredit\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargarNPC" id="btnCargarNPC" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarNuevoPerfilCredito(document.getElementById(\'formularionpc\'));"/>';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		return;
?>