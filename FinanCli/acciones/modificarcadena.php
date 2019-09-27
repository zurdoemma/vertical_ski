<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');return;}
		if (!verificar_permisos_admin()){header('Location:../sinautorizacion.php');return;}

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
		
		$idCadena=htmlspecialchars($_POST["idCadena"], ENT_QUOTES, 'UTF-8');

		if($stmt = $mysqli->prepare("SELECT c.id, c.razon_social, c.cuit_cuil, c.email, c.telefono, c.nombre_fantasia FROM finan_cli.cadena c WHERE c.id = ?"))
		{
			$stmt->bind_param('i', $idCadena);
			$stmt->execute();    
			$stmt->store_result();
			
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				$stmt->free_result();
				$stmt->close();
				echo translate('Msg_Chain_Not_Exist',$GLOBALS['lang']);
				return;
			}

			$stmt->bind_result($id_cadena, $cadena_razon_social, $cadena_cuit_cuil, $cadena_email, $cadena_telefono, $cadena_nombre_fantasia);
			$stmt->fetch();
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}
				
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Msg_Edit_Chain',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_9"></div>';
		echo '			<form id="formulariomc" role="form">';
		echo '				<div class="form-group form-inline">';
		echo '					<input class="form-control input-sm" id="idchaini" name="idchaini" type="text" maxlength="11" value="'.$id_cadena.'" style="display:none;" />';		
		echo '					<label class="control-label" for="razonsocialchain">'.translate('Lbl_Business Name_Chain',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="razonsocialchain">';
		echo '						<input title="'.translate('Msg_A_Business_Name_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="razonsocialchaini" name="razonsocialchaini" type="text" maxlength="250" value="'.$cadena_razon_social.'" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="cuitcuilchain">'.translate('Lbl_CUIT_CUIL_Chain',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="cuitcuilchain">';
		echo '						<input title="'.translate('Msg_A_CUIT_CUIL_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="cuitcuilchaini" name="cuitcuilchaini" type="text" maxlength="20" value="'.$cadena_cuit_cuil.'" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="emailchain">'.translate('Lbl_Email_Chain',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="emailchain">';
		echo '						<input title="'.translate('Msg_A_Chain_Email_Invalid',$GLOBALS['lang']).'" class="form-control input-sm" id="emailchaini" name="emailchaini" type="text" maxlength="150" value="'.$cadena_email.'" />';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';							
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="telefonochain">'.translate('Lbl_Phone_Chain',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="telefonochain">';
		echo '						<input title="'.translate('Msg_A_Phone_Chain_Must_Enter_A_Whole',$GLOBALS['lang']).'" class="form-control input-sm" id="telefonochaini" name="telefonochaini" type="text" maxlength="20" value="'.(!empty($cadena_telefono) ? "$cadena_telefono" : "").'" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="nombrefantasiachain">'.translate('Lbl_Fantasy_Name_Chain',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nombrefantasiachain">';
		echo '						<input title="'.translate('Msg_A_Fantasy_Name_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nombrefantasiachaini" name="nombrefantasiachaini" type="text" maxlength="100" value="'.$cadena_nombre_fantasia.'" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;<label class="control-label" for="tenderschain">'.translate('Lbl_View_Tenders_Chain',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="tenderschain">';
		echo '						<button type="button" id="btnVerSucursalesCadena" class="btn" title="'.translate('Lbl_View_Tenders_Chain',$GLOBALS['lang']).'" onclick="verSucursalesCadena(\''.$id_cadena.'\',\''.$cadena_razon_social.'\');"><i class="fa fa-eye"></i></button>';
		echo '					</div>';		
		echo '				</div>';	
		echo '				<div class="form-group form-inline">';				
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelar" id="btnCancelar" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogmodchain\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargar" id="btnCargar" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarModificacionCadena(document.getElementById(\'formulariomc\'),\''.$id_cadena.'\');"/>';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		$stmt->free_result();
		$stmt->close();		
		
		return;
?>