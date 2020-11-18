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
		
		$idCliente=htmlspecialchars($_POST["idCliente"], ENT_QUOTES, 'UTF-8');	
		
		if($stmt = $mysqli->prepare("SELECT count(t.id) FROM ".$db_name.".cliente c, ".$db_name.".telefono t, ".$db_name.".cliente_x_telefono ct WHERE c.id = ? AND ct.tipo_documento = c.tipo_documento AND ct.documento = c.documento AND ct.id_telefono = t.id"))
		{
			$stmt->bind_param('i', $idCliente);
			$stmt->execute();    
			$stmt->store_result();
			
			$stmt->bind_result($cantidad_telefonos);
			$stmt->fetch();
			
			if($stmt2 = $mysqli->prepare("SELECT valor FROM ".$db_name.".parametros WHERE nombre = 'cantidad_telefonos_x_usuario_cliente'"))
			{
				$stmt2->execute();    
				$stmt2->store_result();
				$stmt2->bind_result($cantidad_telefonos_db);
				$stmt2->fetch();
				if($cantidad_telefonos >= $cantidad_telefonos_db)
				{
					$stmt->free_result();
					$stmt->close();
					$stmt2->free_result();
					$stmt2->close();
					echo str_replace("%1",$cantidad_telefonos_db,translate('Msg_Limit_Phones_User',$GLOBALS['lang']));
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
		echo '			<h3 class="panel-title">'.translate('Lbl_New_Phone',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_15"></div>';
		echo '			<form id="formulariontc" role="form">';
		echo '				<div class="form-group form-inline text-center" id="validartelefonocliente" name="validartelefonocliente">';
		echo '					<label class="control-label" for="validarclienten">'.translate('Lbl_Valid_Client_Phone_Mobile',$GLOBALS['lang']).':</label>';			
		echo '					<div class="form-group" id="validarclienten">';	
		echo '						<label class="switch">';
		echo '						  <input type="checkbox" id="validarclienteni" name="validarclienteni" checked />';
		echo '						  <span class="slider round"></span>';
		echo '						</label>';
		echo '					</div>';		
		echo '				</div>';		
		echo '				<div class="form-group form-inline"><hr />';
		echo '					<div class="form-group" id="tokenvcc" style="display:none;">';
		echo '							<input class="form-control input-sm green-border" id="tokenvcci" name="tokenvcci" type="text" maxlength="128" />';
		echo '					</div>';		
		echo '					<label class="control-label" for="tipotelefono">'.translate('Lbl_Type_Phone',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="tipotelefono">';
		echo '						<select class="form-control input-sm" name="tipotelefonoi" id="tipotelefonoi" style="width:190px;">';			 
										if ($stmt = $mysqli->prepare("SELECT id, nombre FROM ".$db_name.".tipo_telefono")) 
										{ 
											$stmt->execute();    
											$stmt->store_result();
									 
											$stmt->bind_result($id_tipo_telefono,$tipo_telefono);
											while($stmt->fetch())
											{
												echo '<option value="'.$id_tipo_telefono.'">'.$tipo_telefono.'</option>';
											}
										}
										else  
										{
											echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
											return;			
										}
		echo '						</select>';
		echo '					</div>';
		echo '					&nbsp;&nbsp;<label class="control-label" for="prefijotelefono">'.translate('Lbl_Pre_Number_Phone',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="prefijotelefono">';
		echo '						<input title="'.translate('Msg_A_Pre_Number_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="prefijotelefonoi" name="prefijotelefonoi" type="text" maxlength="4" />';
		echo '					</div>';									
		echo '				</div>';
		echo '				<div class="form-group form-inline">';							
		echo '					<label class="control-label" for="nrotelefono">'.translate('Lbl_Number_Phone',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nrotelefono">';
		echo '						<input title="'.translate('Msg_A_Number_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nrotelefonoi" name="nrotelefonoi" type="text" maxlength="16" />';
		echo '					</div>';		
		echo '					<div id="telefonopreferido" class="form-group">';				
		echo '						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="telefonopreferidocliente">'.translate('Lbl_Preference_Address',$GLOBALS['lang']).':</label>';			
		echo '						<div class="form-group" id="telefonopreferidocliente">';	
		echo '							<label class="switch">';
		echo '						  	<input type="checkbox" id="telefonopreferidoclientei" name="telefonopreferidoclientei" />';
		echo '						  	<span class="slider round"></span>';
		echo '							</label>';
		echo '						</div>';
		echo '					</div>';		
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';				
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarN" id="btnCancelarN" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialognewphone\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargarN" id="btnCargarN" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarNuevoTelefono(document.getElementById(\'formulariontc\'));"/>';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		$stmt->free_result();
		$stmt->close();
		return;

?>