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
		
		$usuario=htmlspecialchars($_POST["usuario"], ENT_QUOTES, 'UTF-8');	
				
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Lbl_New_Users',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_5"></div>';
		echo '			<form id="formularionu" role="form">';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="idusern">'.translate('Lbl_User',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="idusern">';
		echo '						<input title="'.translate('Msg_A_User_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="iduserni" name="iduserni" type="text" maxlength="50" />';
		echo '					</div>';							
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nameusern">'.translate('Lbl_Name_User',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nameusern">';
		echo '						<input title="'.translate('Msg_A_User_Name_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nameuserni" name="nameuserni" type="text" maxlength="100" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="surnameusern">'.translate('Lbl_Surname_User',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nameusern">';
		echo '						<input title="'.translate('Msg_A_User_Surname_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="surnameuserni" name="surnameuserni" type="text" maxlength="100" />';
		echo '					</div>';				
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="tipodocusern">'.translate('Lbl_Type_Document_User',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="tipodocusern">';
		echo '						<select class="form-control input-sm" name="tipodocuserni" id="tipodocuserni" style="width:190px;">';			 
										if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.tipo_documento")) 
										{ 
											$stmt->execute();    
											$stmt->store_result();
									 
											$stmt->bind_result($id_tipo_doc,$name_tipo_doc);
											while($stmt->fetch())
											{
												echo '<option value="'.$id_tipo_doc.'">'.$name_tipo_doc.'</option>';
											}
										}
										else  
										{
											echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
											return;			
										}
		echo '						</select>';
		echo '					</div>';							
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="documentusern">'.translate('Lbl_Document_User',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="documentusern">';
		echo '						<input title="'.translate('Msg_A_User_Document_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="documentuserni" name="documentuserni" type="text" maxlength="20" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="emailusern">'.translate('Lbl_Email_User',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="emailusern">';
		echo '						<input title="'.translate('Msg_A_User_Email_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="emailuserni" name="emailuserni" type="text" maxlength="250" />';
		echo '					</div>';				
		echo '				</div>';
		echo '				<div class="form-group form-inline">';				
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="perfilusern">'.translate('Lbl_Perfil_User',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="perfilusern">';
		echo '						<select class="form-control input-sm" name="perfiluserni" id="perfiluserni" style="width:190px;">';			 
										if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.perfil")) 
										{ 
											$stmt->execute();    
											$stmt->store_result();
									 
											$stmt->bind_result($id_perfil,$name_perfil);
											while($stmt->fetch())
											{
												if($id_perfil == 2) echo '<option selected value="'.$id_perfil.'">'.$name_perfil.'</option>';
												else echo '<option value="'.$id_perfil.'">'.$name_perfil.'</option>';
											}
										}
										else  
										{
											echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
											return;			
										}
		echo '						</select>';
		echo '					</div>';							
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="sucursalusern">'.translate('Lbl_Tender_User',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="sucursalusern">';
		echo '						<select class="form-control input-sm" name="sucursaluserni" id="sucursaluserni" style="width:190px;">';			 
										if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.sucursal")) 
										{ 
											$stmt->execute();    
											$stmt->store_result();
									 
											$stmt->bind_result($id_sucursal,$name_sucursal);
											while($stmt->fetch())
											{
												echo '<option value="'.$id_sucursal.'">'.$name_sucursal.'</option>';
											}
										}
										else  
										{
											echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
											return;			
										}
		echo '						</select>';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="homeaddressusern">'.translate('Lbl_Home_Address',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="homeaddressusern">';
		echo '						<button type="button" class="btn" id="btnCargaDomicilioU" name="btnCargaDomicilioU" title="'.translate('Lbl_New_Home_Address_User',$GLOBALS['lang']).'" onclick="verDomicilioNU();"><i class="fa fa-eye"></i></button>';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';				
		echo '					&nbsp;&nbsp;<label class="control-label" for="claveusern">'.translate('Lbl_New_Password',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="claveusern">';
		echo '						<input title="'.translate('Msg_A_Add_User_New_Password_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="claveuserni" name="claveuserni" type="password" maxlength="128" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="rclaveusern">'.translate('Lbl_Repeat_Password',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="rclaveusern">';
		echo '						<input title="'.translate('Msg_A_Add_User_Confirm_New_Password_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="rclaveuserni" name="rclaveuserni" type="password" maxlength="128" />';
		echo '					</div>';				
		echo '				</div>';
		echo '				<div class="form-group form-inline"><hr />';
		echo '					&nbsp;<label class="control-label" for="horarioingresousern">'.translate('Lbl_Entry_Time_User',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="horarioingresousern">';
		echo '						<div class="input-group date" id="datetimepickerhorarioingresousern">';		
		echo '							<input title="'.translate('Msg_You_Must_Enter_The_User_Entry_Time',$GLOBALS['lang']).'" class="form-control input-sm" id="horarioingresouserni" name="horarioingresouserni" type="text" maxlength="8" placeholder="'.translate('Lbl_Format_Hour_Entry_And_Departure_User',$GLOBALS['lang']).'"  style="width: 132px;" />';
		echo '							<span class="input-group-addon">';
		echo '								<span class="glyphicon glyphicon-time"></span>';
		echo '							</span>';		
		echo '					    </div>';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="horarioegresousern">'.translate('Lbl_Departure_Time_User',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="horarioegresousern">';
		echo '						<div class="input-group date" id="datetimepickerhorarioegresousern">';		
		echo '							<input title="'.translate('Msg_You_Must_Enter_The_User_Departure_Time',$GLOBALS['lang']).'" class="form-control input-sm" id="horarioegresouserni" name="horarioegresouserni" type="text" maxlength="8" placeholder="'.translate('Lbl_Format_Hour_Entry_And_Departure_User',$GLOBALS['lang']).'"  style="width: 142px;" />';
		echo '							<span class="input-group-addon">';
		echo '								<span class="glyphicon glyphicon-time"></span>';
		echo '							</span>';		
		echo '					    </div>';
		echo '					</div>';				
		echo '				</div>';
		echo '				<div class="form-group form-inline">';				
		echo '				  <div class="form-group" id="luneshorariolaboralusern">';
		echo '					  &nbsp;<input class="form-control input-sm" id="luneshorariolaboraluserni" name="luneshorariolaboraluserni" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="luneshorariolaboralusern">'.translate('Lbl_Day_Monday_Check_Work_User',$GLOBALS['lang']).'</label>';
		echo '				  </div>';
		echo '				  <div class="form-group" id="marteshorariolaboralusern">';
		echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="marteshorariolaboraluserni" name="marteshorariolaboraluserni" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="marteshorariolaboralusern">'.translate('Lbl_Day_Thursday_Check_Work_User',$GLOBALS['lang']).'</label>';
		echo '				  </div>';	
		echo '				  <div class="form-group" id="miercoleshorariolaboralusern">';
		echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="miercoleshorariolaboraluserni" name="miercoleshorariolaboraluserni" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="miercoleshorariolaboralusern">'.translate('Lbl_Day_Wednesday_Check_Work_User',$GLOBALS['lang']).'</label>';
		echo '				  </div>';
		echo '				  <div class="form-group" id="jueveshorariolaboralusern">';
		echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="jueveshorariolaboraluserni" name="jueveshorariolaboraluserni" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="jueveshorariolaboralusern">'.translate('Lbl_Day_Tuesday_Check_Work_User',$GLOBALS['lang']).'</label>';
		echo '				  </div>';
		echo '				  <div class="form-group" id="vierneshorariolaboralusern">';
		echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="vierneshorariolaboraluserni" name="vierneshorariolaboraluserni" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="vierneshorariolaboralusern">'.translate('Lbl_Day_Friday_Check_Work_User',$GLOBALS['lang']).'</label>';
		echo '				  </div>';	
		echo '				  <div class="form-group" id="sabadohorariolaboralusern">';
		echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="sabadohorariolaboraluserni" name="sabadohorariolaboraluserni" type="checkbox" style="width:20px;"/>&nbsp;<label class="control-label" for="sabadohorariolaboralusern">'.translate('Lbl_Day_Saturday_Check_Work_User',$GLOBALS['lang']).'</label>';
		echo '				  </div>';
		echo '				  <div class="form-group" id="domingohorariolaboralusern">';
		echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="domingohorariolaboraluserni" name="domingohorariolaboraluserni" type="checkbox" style="width:20px;"/>&nbsp;<label class="control-label" for="domingohorariolaboralusern">'.translate('Lbl_Day_Sunday_Check_Work_User',$GLOBALS['lang']).'</label>';
		echo '				  </div>';		
		echo '				</div>';		
		echo '              <div id="mostrarDomicilioCarga" style="display:none;"><hr />';
		echo '				  <div class="form-group form-inline">';
		echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="calle">'.translate('Lbl_Street',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="calle">';
		echo '						  <input title="'.translate('Msg_A_Street_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="callei" name="callei" type="text" maxlength="150" />';
		echo '					  </div>';							
		echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nrocalle">'.translate('Lbl_Number_Street',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="nrocalle">';
		echo '						  <input title="'.translate('Msg_A_Street_Number_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nrocallei" name="nrocallei" type="text" maxlength="11" />';
		echo '					  </div>';		
		echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domprovincia">'.translate('Lbl_State',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="domprovincia">';
		echo '						  <select class="form-control input-sm" name="domprovinciai" id="domprovinciai" style="width:190px;">';			 
										  if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.provincia")) 
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
		echo '						  </select>';
		echo '					  </div>';				
		echo '				  </div>';
		echo '				  <div class="form-group form-inline">';
		echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domlocalidad">'.translate('Lbl_City',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="domlocalidad">';
		echo '						  <input title="'.translate('Msg_A_City_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="domlocalidadi" name="domlocalidadi" type="text" maxlength="150" />';
		echo '					  </div>';							
		echo '					  &nbsp;&nbsp;<label class="control-label" for="domdepartamento">'.translate('Lbl_Departament',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="domdepartamento">';
		echo '						  <input class="form-control input-sm" id="domdepartamentoi" name="domdepartamentoi" type="text" maxlength="10" />';
		echo '					  </div>';
		echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domfloor">'.translate('Lbl_Floor',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="domfloor">';
		echo '						  <input title="'.translate('Msg_A_Floor_Number_Must_Enter_A_Whole',$GLOBALS['lang']).'" class="form-control input-sm" id="domfloori" name="domfloori" type="text" maxlength="11" />';
		echo '					  </div>';				
		echo '				  </div>';
		echo '				  <div class="form-group form-inline">';
		echo '					  <label class="control-label" for="zipcode">'.translate('Lbl_Zip_Code',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="zipcode">';
		echo '						  <input class="form-control input-sm" id="zipcodei" name="zipcodei" type="text" maxlength="10" />';
		echo '					  </div>';				
		echo '					  &nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="entrecalle1">'.translate('Msg_Between_Street_1',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="entrecalle1">';
		echo '						  <input class="form-control input-sm" id="entrecalle1i" name="entrecalle1i" type="text" maxlength="150" />';
		echo '					  </div>';							
		echo '					  &nbsp;&nbsp;&nbsp;<label class="control-label" for="entrecalle2">'.translate('Msg_Between_Street_2',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="entrecalle2">';
		echo '						  <input class="form-control input-sm" id="entrecalle2i" name="entrecalle2i" type="text" maxlength="150" />';
		echo '					  </div>';				
		echo '				  </div>';		
		echo '              </div>';		
		echo '				<div class="form-group form-inline">';				
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarN" id="btnCancelarN" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialognewuser\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargarN" id="btnCargarN" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarNuevoUsuario(document.getElementById(\'formularionu\'));"/>';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		return;
?>