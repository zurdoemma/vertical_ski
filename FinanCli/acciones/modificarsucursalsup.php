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
		
		$idSucursal=htmlspecialchars ( $_POST["idSucursal"], ENT_QUOTES, 'UTF-8' );
		
		if ($stmt500 = $mysqli->prepare("SELECT c.id FROM ".$db_name.".cadena c, ".$db_name.".usuario u, ".$db_name.".sucursal s WHERE u.id_sucursal = s.id AND s.id_cadena = c.id AND u.id = ?")) 
		{
			$stmt500->bind_param('s', $_SESSION['username']);
			$stmt500->execute();    
			$stmt500->store_result();
	 
			$totR500 = $stmt500->num_rows;
			if($totR500 > 0)
			{
				$stmt500->bind_result($id_cadena_user);
				$stmt500->fetch();

				$stmt500->free_result();
				$stmt500->close();				
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
		
		if($stmt = $mysqli->prepare("SELECT s.id, s.codigo, s.nombre, s.id_domicilio, s.email, s.id_cadena FROM ".$db_name.".sucursal s WHERE s.id = ? AND s.id_cadena = ?"))
		{
			$stmt->bind_param('ii', $idSucursal, $id_cadena_user);
			$stmt->execute();    
			$stmt->store_result();
			
			$stmt->bind_result($id_sucursal, $codigo_sucursal, $nombre_sucursal, $domicilio_sucursal, $email_sucursal, $cadena_sucursal);
			$stmt->fetch();
			
			
			if($stmt2 = $mysqli->prepare("SELECT d.id, d.calle, d.nro_calle, d.id_provincia, d.localidad, d.departamento, d.piso, d.codigo_postal, d.entre_calle_1, d.entre_calle_2 FROM ".$db_name.".domicilio d WHERE d.id = ?"))
			{
				$stmt2->bind_param('i', $domicilio_sucursal);
				$stmt2->execute();    
				$stmt2->store_result();
				
				$stmt2->bind_result($id_domicilio_s, $calle_domicilio_s, $nro_calle_domicilio_s, $provincia_domicilio_s, $localidad_domicilio_s, $departamento_domicilio_s, $piso_domiclio_s, $codigo_postal_domicilio_s, $entre_calle1_domicilio_s, $entre_calle2_domicilio_s);
				$stmt2->fetch();
			}			
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;		
			}
			
			echo '<div class="panel-group">';				
			echo '	<div class="panel panel-default">';
			echo '		<div id="panel-title-header" class="panel-heading">';
			echo '			<h3 class="panel-title">'.translate('Msg_Edit_Tender',$GLOBALS['lang']).'</h3>';
			echo ' 		</div>';
			echo '		<div class="panel-body">';
			echo '			<div id="img_loader_5"></div>';
			echo '			<form id="formulariomu" role="form">';
			echo '				<div class="form-group form-inline">';
			echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nombretender">'.translate('Lbl_Name_Tender',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="nombretender">';
			echo '						<input title="'.translate('Msg_A_Name_Tender_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nombretenderi" name="nombretenderi" type="text" maxlength="150" value="'.$nombre_sucursal.'" />';
			echo '					</div>';							
			echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="codigotender">'.translate('Lbl_Code_Tender',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="codigotender">';
			echo '						<input title="'.translate('Msg_A_Code_Tender_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="codigotenderi" name="codigotenderi" type="text" maxlength="11" value="'.$codigo_sucursal.'" />';
			echo '					</div>';				
			echo '				</div>';
			echo '				<div class="form-group form-inline">';
			echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="emailtender">'.translate('Lbl_Email_Tender',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="emailtender">';
			if((!empty($email_sucursal) ? "$email_sucursal" : "") == "---") $email_sucursal_fin = "";
			else $email_sucursal_fin = $email_sucursal;
			echo '						<input title="'.translate('Msg_A_Tender_Email_Invalid',$GLOBALS['lang']).'" class="form-control input-sm" id="emailtenderi" name="emailtenderi" type="text" maxlength="250" value="'.$email_sucursal_fin.'" />';
			echo '					</div>';		
			echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="cadenatender">'.translate('Lbl_Chain_Tender',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="cadenatender">';
			echo '						<select class="form-control input-sm" name="cadenatenderi" id="cadenatenderi" style="width:190px;" disabled >';			 
											if ($stmt = $mysqli->prepare("SELECT id, razon_social FROM ".$db_name.".cadena WHERE id = ?")) 
											{ 
												$stmt->bind_param('i', $id_cadena_user);
												$stmt->execute();    
												$stmt->store_result();
										 
												$stmt->bind_result($id_cadena,$razon_social_cadena);
												while($stmt->fetch())
												{
													if($cadena_sucursal == $id_cadena)
													{
														echo '<option selected value="'.$id_cadena.'">'.$razon_social_cadena.'</option>';
													}
													else echo '<option value="'.$id_cadena.'">'.$razon_social_cadena.'</option>';
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
			echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="homeaddressuser">'.translate('Lbl_Home_Address',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="homeaddressuser">';
			echo '						<button type="button" class="btn" id="btnCargaDomicilioU" name="btnCargaDomicilioU" title="'.translate('Lbl_New_Home_Address_User',$GLOBALS['lang']).'" onclick="verDomicilioU();"><i class="fa fa-eye"></i></button>';
			echo '					</div>';
			echo '				</div>';		
			echo '              <div id="mostrarDomicilioCarga" style="display:none;"><hr />';
			echo '				  <div class="form-group form-inline">';
			echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="calle">'.translate('Lbl_Street',$GLOBALS['lang']).':</label>';
			echo '					  <div class="form-group" id="calle">';
			echo '						  <input title="'.translate('Msg_A_Street_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="callei" name="callei" type="text" maxlength="150" value="'.$calle_domicilio_s.'" />';
			echo '					  </div>';							
			echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nrocalle">'.translate('Lbl_Number_Street',$GLOBALS['lang']).':</label>';
			echo '					  <div class="form-group" id="nrocalle">';
			echo '						  <input title="'.translate('Msg_A_Street_Number_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nrocallei" name="nrocallei" type="text" maxlength="11" value="'.$nro_calle_domicilio_s.'" />';
			echo '					  </div>';		
			echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domprovincia">'.translate('Lbl_State',$GLOBALS['lang']).':</label>';
			echo '					  <div class="form-group" id="domprovincia">';
			echo '						  <select class="form-control input-sm" name="domprovinciai" id="domprovinciai" style="width:190px;">';			 
											  if ($stmt = $mysqli->prepare("SELECT id, nombre FROM ".$db_name.".provincia")) 
											  { 
												  $stmt->execute();    
												  $stmt->store_result();
										 
												  $stmt->bind_result($id_provincia,$provincia);
												  while($stmt->fetch())
												  {
														if($provincia_domicilio_s == $id_provincia)
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
			echo '						  </select>';
			echo '					  </div>';				
			echo '				  </div>';
			echo '				  <div class="form-group form-inline">';
			echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domlocalidad">'.translate('Lbl_City',$GLOBALS['lang']).':</label>';
			echo '					  <div class="form-group" id="domlocalidad">';
			echo '						  <input title="'.translate('Msg_A_City_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="domlocalidadi" name="domlocalidadi" type="text" maxlength="150" value="'.(!empty($localidad_domicilio_s) ? "$localidad_domicilio_s" : "").'" />';
			echo '					  </div>';							
			echo '					  &nbsp;&nbsp;<label class="control-label" for="domdepartamento">'.translate('Lbl_Departament',$GLOBALS['lang']).':</label>';
			echo '					  <div class="form-group" id="domdepartamento">';
			echo '						  <input class="form-control input-sm" id="domdepartamentoi" name="domdepartamentoi" type="text" maxlength="10" value="'.(!empty($departamento_domicilio_s) ? "$departamento_domicilio_s" : "").'" />';
			echo '					  </div>';
			echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domfloor">'.translate('Lbl_Floor',$GLOBALS['lang']).':</label>';
			echo '					  <div class="form-group" id="domfloor">';
			echo '						  <input title="'.translate('Msg_A_Floor_Number_Must_Enter_A_Whole',$GLOBALS['lang']).'" class="form-control input-sm" id="domfloori" name="domfloori" type="text" maxlength="11" value="'.(!empty($piso_domiclio_s) ? "$piso_domiclio_s" : "").'" />';
			echo '					  </div>';				
			echo '				  </div>';
			echo '				  <div class="form-group form-inline">';
			echo '					  <label class="control-label" for="zipcode">'.translate('Lbl_Zip_Code',$GLOBALS['lang']).':</label>';
			echo '					  <div class="form-group" id="zipcode">';
			echo '						  <input class="form-control input-sm" id="zipcodei" name="zipcodei" type="text" maxlength="10" value="'.(!empty($codigo_postal_domicilio_s) ? "$codigo_postal_domicilio_s" : "").'" />';
			echo '					  </div>';				
			echo '					  &nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="entrecalle1">'.translate('Msg_Between_Street_1',$GLOBALS['lang']).':</label>';
			echo '					  <div class="form-group" id="entrecalle1">';
			echo '						  <input class="form-control input-sm" id="entrecalle1i" name="entrecalle1i" type="text" maxlength="150" value="'.(!empty($entre_calle1_domicilio_s) ? "$entre_calle1_domicilio_s" : "").'" />';
			echo '					  </div>';							
			echo '					  &nbsp;&nbsp;&nbsp;<label class="control-label" for="entrecalle2">'.translate('Msg_Between_Street_2',$GLOBALS['lang']).':</label>';
			echo '					  <div class="form-group" id="entrecalle2">';
			echo '						  <input class="form-control input-sm" id="entrecalle2i" name="entrecalle2i" type="text" maxlength="150" value="'.(!empty($entre_calle2_domicilio_s) ? "$entre_calle2_domicilio_s" : "").'" />';
			echo '					  </div>';				
			echo '				  </div>';		
			echo '              </div>';		
			echo '				<div class="form-group form-inline">';				
			echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarS" id="btnCancelarS" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogmodifytender\').dialog(\'close\');" style="margin-left:10px;" />';
			echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargarS" id="btnCargarS" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarModificacionSucursal(document.getElementById(\'formulariomu\'),\''.$id_sucursal.'\',\''.$id_domicilio_s.'\');"/>';										
			echo '				</div>';				
			echo '			</form>';
			echo '		</div>';
			echo '	</div>';
			echo '</div>';			
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;		
		}
		
		return;
?>