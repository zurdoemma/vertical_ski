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
		
		$usuario=htmlspecialchars($_POST["usuario"], ENT_QUOTES, 'UTF-8');

		if($_SESSION['username'] != $usuario)
		{
			echo translate('Msg_Edit_User_Not_Match_User_Logged',$GLOBALS['lang']);
			return;						
		}

		if($stmt = $mysqli->prepare("SELECT count(d.id) FROM ".$db_name.".usuario u, ".$db_name.".domicilio d, ".$db_name.".provincia p, ".$db_name.".usuario_x_domicilio ud WHERE d.id_provincia = p.id AND u.id LIKE(?) AND ud.id_usuario = u.id AND ud.id_domicilio = d.id"))
		{
			$stmt->bind_param('s', $usuario);
			$stmt->execute();    
			$stmt->store_result();
			
			$stmt->bind_result($cantidad_domicilios);
			$stmt->fetch();
			
			if($stmt2 = $mysqli->prepare("SELECT valor FROM ".$db_name.".parametros WHERE nombre = 'cantidad_domicilios_x_usuario_cliente'"))
			{
				$stmt2->execute();    
				$stmt2->store_result();
				$stmt2->bind_result($cantidad_domicilios_db);
				$stmt2->fetch();
				if($cantidad_domicilios >= $cantidad_domicilios_db)
				{
					$stmt->free_result();
					$stmt->close();
					$stmt2->free_result();
					$stmt2->close();
					echo str_replace("%1",$cantidad_domicilios_db,translate('Msg_Limit_Address_User',$GLOBALS['lang']));
					return;	
				}
				
				$stmt2->free_result();
				$stmt2->close();
			}
			else
			{
				$stmt->free_result();
				$stmt->close();
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
		echo '			<h3 class="panel-title">'.translate('Lbl_New_Home_Address',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_3"></div>';
		echo '			<form id="formulariondu" role="form">';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="calle">'.translate('Lbl_Street',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="calle">';
		echo '						<input title="'.translate('Msg_A_Street_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="callei" name="callei" type="text" maxlength="150" />';
		echo '					</div>';							
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nrocalle">'.translate('Lbl_Number_Street',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nrocalle">';
		echo '						<input title="'.translate('Msg_A_Street_Number_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nrocallei" name="nrocallei" type="text" maxlength="11" />';
		echo '					</div>';		
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domprovincia">'.translate('Lbl_State',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="domprovincia">';
		echo '						<select class="form-control input-sm" name="domprovinciai" id="domprovinciai" style="width:190px;">';			 
										if ($stmt = $mysqli->prepare("SELECT id, nombre FROM ".$db_name.".provincia")) 
										{ 
											$stmt->execute();    
											$stmt->store_result();
									 
											$stmt->bind_result($id_provincia,$provincia);
											while($stmt->fetch())
											{
												echo '<option value="'.$id_provincia.'">'.$provincia.'</option>';
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
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domlocalidad">'.translate('Lbl_City',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="domlocalidad">';
		echo '						<input title="'.translate('Msg_A_City_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="domlocalidadi" name="domlocalidadi" type="text" maxlength="150" />';
		echo '					</div>';							
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domdepartamento">'.translate('Lbl_Departament',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="domdepartamento">';
		echo '						<input class="form-control input-sm" id="domdepartamentoi" name="domdepartamentoi" type="text" maxlength="10" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domfloor">'.translate('Lbl_Floor',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="domfloor">';
		echo '						<input title="'.translate('Msg_A_Floor_Number_Must_Enter_A_Whole',$GLOBALS['lang']).'" class="form-control input-sm" id="domfloori" name="domfloori" type="text" maxlength="11" />';
		echo '					</div>';				
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					<label class="control-label" for="zipcode">'.translate('Lbl_Zip_Code',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="zipcode">';
		echo '						<input class="form-control input-sm" id="zipcodei" name="zipcodei" type="text" maxlength="10" />';
		echo '					</div>';				
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="entrecalle1">'.translate('Msg_Between_Street_1',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="entrecalle1">';
		echo '						<input class="form-control input-sm" id="entrecalle1i" name="entrecalle1i" type="text" maxlength="150" />';
		echo '					</div>';							
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="entrecalle2">'.translate('Msg_Between_Street_2',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="entrecalle2">';
		echo '						<input class="form-control input-sm" id="entrecalle2i" name="entrecalle2i" type="text" maxlength="150" />';
		echo '					</div>';				
		echo '				</div>';
		echo '				<div class="form-group form-inline">';				
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelar" id="btnCancelar" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialognewaddress\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargar" id="btnCargar" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarNuevoDomicilio(document.getElementById(\'formulariondu\'));"/>';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		$stmt->free_result();
		$stmt->close();
		return;

?>