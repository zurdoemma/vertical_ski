<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');return;}
		if (!verificar_permisos_supervisor()){header('Location:../sinautorizacion.php?activauto=1');return;}

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
		$idDomicilio=htmlspecialchars($_POST["id_domicilio"], ENT_QUOTES, 'UTF-8');
				
		if($stmt = $mysqli->prepare("SELECT d.id, d.calle, d.nro_calle, p.id, d.localidad, d.departamento, d.piso, d.codigo_postal, d.entre_calle_1, d.entre_calle_2 FROM finan_cli.usuario u, finan_cli.domicilio d, finan_cli.usuario_x_domicilio ud, finan_cli.provincia p WHERE d.id_provincia = p.id AND u.id LIKE(?) AND u.id = ud.id_usuario AND d.id = ud.id_domicilio AND d.id = ?"))
		{
			$stmt->bind_param('si', $usuario, $idDomicilio);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				$stmt->free_result();
				$stmt->close();
				echo translate('Msg_User_Or_Address_Not_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{
				$stmt->bind_result($id_domicilio_user, $user_dom_calle, $user_dom_nro_calle, $user_dom_provincia, $user_dom_localidad, $user_dom_departamento, $user_dom_piso, $user_dom_codigo_postal, $user_entre_calle_1, $user_entre_calle_2);			
				$stmt->fetch();
				echo '<div class="panel-group">';				
				echo '	<div class="panel panel-default">';
				echo '		<div id="panel-title-header" class="panel-heading">';
				echo '			<h3 class="panel-title">'.translate('Msg_Edit_Address',$GLOBALS['lang']).'</h3>';
				echo ' 		</div>';
				echo '		<div class="panel-body">';
				echo '			<div id="img_loader_4"></div>';
				echo '			<form id="formularionmdu" role="form">';
				echo '				<div class="form-group form-inline">';
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="callem">'.translate('Lbl_Street',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="callem">';
				echo '						<input title="'.translate('Msg_A_Street_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="callemi" name="callemi" type="text" maxlength="150" value="'.$user_dom_calle.'" />';
				echo '					</div>';							
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nrocallem">'.translate('Lbl_Number_Street',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="nrocallem">';
				echo '						<input title="'.translate('Msg_A_Street_Number_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nrocallemi" name="nrocallemi" type="text" maxlength="11" value="'.$user_dom_nro_calle.'" />';
				echo '					</div>';		
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domprovinciam">'.translate('Lbl_State',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="domprovinciam">';
				echo '						<select class="form-control input-sm" name="domprovinciai" id="domprovinciami" style="width:190px;">';			 
												if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.provincia")) 
												{ 
													$stmt->execute();    
													$stmt->store_result();
											 
													$stmt->bind_result($id_provincia,$provincia);
													while($stmt->fetch())
													{
														if($user_dom_provincia == $id_provincia)
														{
															echo '<option selected value="'.$id_provincia.'">'.$provincia.'</option>';
														}
														else echo '<option value="'.$id_provincia.'">'.$provincia.'</option>';
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
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domlocalidadm">'.translate('Lbl_City',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="domlocalidadm">';
				echo '						<input title="'.translate('Msg_A_City_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="domlocalidadmi" name="domlocalidadmi" type="text" maxlength="150" value="'.$user_dom_localidad.'" />';
				echo '					</div>';							
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domdepartamentom">'.translate('Lbl_Departament',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="domdepartamentom">';
				echo '						<input class="form-control input-sm" id="domdepartamentomi" name="domdepartamentomi" type="text" maxlength="10" value="'.$user_dom_departamento.'" />';
				echo '					</div>';
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domfloorm">'.translate('Lbl_Floor',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="domfloorm">';
				echo '						<input title="'.translate('Msg_A_Floor_Number_Must_Enter_A_Whole',$GLOBALS['lang']).'" class="form-control input-sm" id="domfloormi" name="domfloormi" type="text" maxlength="11" value="'.(!empty($user_dom_piso) ? "$user_dom_piso" : "").'" />';
				echo '					</div>';				
				echo '				</div>';
				echo '				<div class="form-group form-inline">';
				echo '					<label class="control-label" for="zipcodem">'.translate('Lbl_Zip_Code',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="zipcodem">';
				echo '						<input class="form-control input-sm" id="zipcodemi" name="zipcodemi" type="text" maxlength="10" value="'.$user_dom_codigo_postal.'" />';
				echo '					</div>';				
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="entrecalle1m">'.translate('Msg_Between_Street_1',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="entrecalle1m">';
				echo '						<input class="form-control input-sm" id="entrecalle1mi" name="entrecalle1mi" type="text" maxlength="150" value="'.$user_entre_calle_1.'" />';
				echo '					</div>';							
				echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="entrecalle2m">'.translate('Msg_Between_Street_2',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="entrecalle2m">';
				echo '						<input class="form-control input-sm" id="entrecalle2mi" name="entrecalle2mi" type="text" maxlength="150" value="'.$user_entre_calle_2.'" />';
				echo '					</div>';				
				echo '				</div>';
				echo '				<div class="form-group form-inline">';				
				echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarm" id="btnCancelarm" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogmodifyaddress\').dialog(\'close\');" style="margin-left:10px;" />';
				echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargarm" id="btnCargarm" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarModificacionDomicilio(document.getElementById(\'formularionmdu\'),'.$idDomicilio.');"/>';										
				echo '				</div>';				
				echo '			</form>';
				echo '		</div>';
				echo '	</div>';
				echo '</div>';	
				
				$stmt->free_result();
				$stmt->close();
			}

		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}		
		
		return;

?>