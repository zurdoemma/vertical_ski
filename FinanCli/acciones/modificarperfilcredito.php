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
		
		$idPerfilCredito=htmlspecialchars($_POST["idPerfilCredito"], ENT_QUOTES, 'UTF-8');
		
		if($stmt = $mysqli->prepare("SELECT pc.id, pc.nombre, pc.descripcion, pc.monto_maximo FROM finan_cli.perfil_credito pc WHERE pc.id = ?"))
		{
			$stmt->bind_param('i', $idPerfilCredito);
			$stmt->execute();    
			$stmt->store_result();
			
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				$stmt->free_result();
				$stmt->close();
				echo translate('Msg_Profile_Credit_Not_Exist',$GLOBALS['lang']);
				return;
			}

			$stmt->bind_result($id_perfil_credito, $nombre_perfil_credito, $descripcion_perfil_credito, $monto_maximo_perfil_credito);
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
		echo '			<h3 class="panel-title">'.translate('Msg_Edit_Profile_Credit',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_11"></div>';
		echo '			<form id="formulariompc" role="form">';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nombreprofilecredit">'.translate('Lbl_Name_Profile_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nombreprofilecredit">';
		echo '						<input title="'.translate('Msg_A_Name_Profile_Credit_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nombreprofilecrediti" name="nombreprofilecrediti" type="text" maxlength="100" value="'.$nombre_perfil_credito.'" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="montomaximoprofilecredit">'.translate('Lbl_Limit_Amount_Profile_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="montomaximoprofilecredit">';
		echo '						<input title="'.translate('Msg_A_Amount_Limit_Profile_Credit_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="montomaximoprofilecrediti" name="montomaximoprofilecrediti" type="text" maxlength="11" value="'.number_format(($monto_maximo_perfil_credito/100.00),2).'" />';
		echo '					</div>';						
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="creditplansxprofile">'.translate('Lbl_View_Credit_Plan_X_Profile',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id=creditplansxprofile">';
		echo '						<button type="button" class="btn" title="'.translate('Msg_View_Credit_Plan_X_Profile',$GLOBALS['lang']).'" onclick="verPlanesCredito(\''.$id_perfil_credito.'\',\''.$nombre_perfil_credito.'\');"><i class="fa fa-eye"></i></button>';
		echo '					</div>';		
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';
		echo '					<label class="control-label" for="descripcionprofilecredit">'.translate('Lbl_Description_Profile_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="descripcionprofilecredit">';
		echo '						<textarea rows="5" cols="67" title="'.translate('Msg_A_Description_Profile_Credit_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="descripcionprofilecrediti" name="descripcionprofilecrediti" type="text" maxlength="500" >'.$descripcion_perfil_credito.'</textarea>';
		echo '					</div>';		
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';				
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarPC" id="btnCancelarPC" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogmodifyprofilecredit\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargarPC" id="btnCargarPC" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarModificacionPerfilCredito(document.getElementById(\'formulariompc\'),\''.$id_perfil_credito.'\');"/>';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		return;
?>