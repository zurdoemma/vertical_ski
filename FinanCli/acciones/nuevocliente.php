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
		echo '			<h3 class="panel-title">'.translate('Lbl_New_Client',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<form id="formularionc" role="form">';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="tipoclientn">'.translate('Lbl_Type_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="tipoclientn">';
		echo '						<select class="form-control input-sm" name="tipoclientni" id="tipoclientni" style="width:190px;">';			 
		echo '							<option selected value="'.translate('Lbl_Type_Client_Headline',$GLOBALS['lang']).'">'.translate('Lbl_Type_Client_Headline',$GLOBALS['lang']).'</option>';
		echo '							<option value="'.translate('Lbl_Type_Client_Additional',$GLOBALS['lang']).'">'.translate('Lbl_Type_Client_Additional',$GLOBALS['lang']).'</option>';
		echo '						</select>';
		echo '					</div>';
		echo '				</div>';
		echo '				<div class="form-group form-inline">';		
		echo '					<div id="busquedatitular" style="display:none;">';
		echo '						<div class="form-group" id="tokenas" style="display:none;">';
		echo '							<input class="form-control input-sm green-border" id="tokenasi" name="tokenasi" type="text" maxlength="128" />';
		echo '						</div>';
		echo '						<div class="form-group" id="tokenvcc" style="display:none;">';
		echo '							<input class="form-control input-sm green-border" id="tokenvcci" name="tokenvcci" type="text" maxlength="128" />';
		echo '						</div>';
		echo '						<div class="form-group" id="tokenvecc" style="display:none;">';
		echo '							<input class="form-control input-sm green-border" id="tokenvecci" name="tokenvecci" type="text" maxlength="128" />';
		echo '						</div>';		
		echo '						&nbsp;<label class="control-label" for="tipodocumentoclientnb">'.translate('Lbl_Type_Document_Client2',$GLOBALS['lang']).':</label>';
		echo '						<div class="form-group" id="tipodocumentoclientnb">';
		echo '							<select class="form-control input-sm" name="tipodocumentoclientnbi" id="tipodocumentoclientnbi" style="width:190px;">';			 
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
		echo '							</select>';
		echo '						</div>';		
		echo '						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="documentonb">'.translate('Lbl_Document_Client',$GLOBALS['lang']).':</label>';
		echo '						<div class="form-group" id="documentonb">';
		echo '							<input title="'.translate('Msg_A_Document_Client_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm green-border" id="documentonbi" name="documentonbi" type="text" maxlength="20" />';
		echo '						</div>';
		echo '					</div>';		
		echo '				</div>';		
		echo '				<div class="form-group form-inline text-center"><hr />';
		echo '					<label class="control-label" for="validarclienten">'.translate('Lbl_Valid_Client',$GLOBALS['lang']).':</label>';			
		echo '					<div class="form-group" id="validarclienten">';	
		echo '						<label class="switch">';
		echo '						  <input type="checkbox" id="validarclienteni" name="validarclienten" checked />';
		echo '						  <span class="slider round"></span>';
		echo '						</label>';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="validarstatuscreditclienten">'.translate('Lbl_Valid_Status_Credit_Client',$GLOBALS['lang']).':</label>';			
		echo '					<div class="form-group" id="validarstatuscreditclienten">';	
		echo '						<label class="switch">';
		echo '						  <input type="checkbox" id="validarstatuscreditclienteni" name="validarstatuscreditclienten" checked />';
		echo '						  <span class="slider round"></span>';
		echo '						</label>';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline"><hr />';
		echo '					&nbsp;&nbsp;<label class="control-label" for="tipodocumentoclientn">'.translate('Lbl_Type_Document_Client2',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="tipodocumentoclientn">';
		echo '						<select class="form-control input-sm" name="tipodocumentoclientni" id="tipodocumentoclientni" style="width:190px;">';			 
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
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="documenton">'.translate('Lbl_Document_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="documenton">';
		echo '						<input title="'.translate('Msg_A_Document_Client_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="documentoni" name="documentoni" type="text" maxlength="20" />';
		echo '					</div>';											
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nombreclientn">'.translate('Lbl_Names_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nombreclientn">';
		echo '						<input title="'.translate('Msg_A_Name_Client_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nombreclientni" name="nombreclientni" type="text" maxlength="150" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="apellidoclientn">'.translate('Lbl_Surnames_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="apellidoclientn">';
		echo '						<input title="'.translate('Msg_A_Surname_Client_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="apellidoclientni" name="apellidoclientni" type="text" maxlength="150" />';
		echo '					</div>';		
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;<label class="control-label" for="fechanacimientoclientn">'.translate('Lbl_Date_Birthday_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="fechanacimientoclientn">';
		echo '						<div class="input-group date" id="datetimepickerfechanacimientoclientn">';		
		echo '							<input title="'.translate('Msg_A_Date_Birthday_Client_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="fechanacimientoclientni" name="fechanacimientoclientni" type="text" maxlength="10" placeholder="'.translate('Lbl_Format_Date_Birthday_Client',$GLOBALS['lang']).'"  style="width: 152px;" />';
		echo '							<span class="input-group-addon">';
		echo '								<span class="glyphicon glyphicon-calendar"></span>';
		echo '							</span>';		
		echo '					    </div>';
		echo '					</div>';		
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="cuitcuilclientn">'.translate('Lbl_Cuit_Cuil_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="cuitcuilclientn">';
		echo '						<input title="'.translate('Msg_A_Cuit_Cuil_Client_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="cuitcuilclientni" name="cuitcuilclientni" type="text" maxlength="20" />';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="emailclientn">'.translate('Lbl_Email_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="emailclientn">';
		echo '						<input title="'.translate('Msg_A_Emial_Client_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="emailclientni" name="emailclientni" type="text" maxlength="250" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="montomaximoclientn">'.translate('Lbl_Max_Amount_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="montomaximoclientn">';
		echo '						<input title="'.translate('Msg_A_Max_Amount_Client_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="montomaximoclientni" name="montomaximoclientni" type="text" maxlength="11" />';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="perfilcreditoclientn">'.translate('Lbl_Profile_Credit_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="perfilcreditoclientn">';
		echo '						<select class="form-control input-sm" name="perfilcreditoclientni" id="perfilcreditoclientni" style="width:190px;">';			 
										if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.perfil_credito")) 
										{ 
											$stmt->execute();    
											$stmt->store_result();
									 
											$stmt->bind_result($id_perfil_credito,$nombre_perfil_credito);
											while($stmt->fetch())
											{
												echo '<option value="'.$id_perfil_credito.'">'.$nombre_perfil_credito.'</option>';
											}
										}
										else  
										{
											echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
											return;			
										}
		echo '						</select>';
		echo '					</div>';		
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="homeaddressandphoneclientn">'.translate('Lbl_Home_Address_And_Phone',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="homeaddressandphoneclientn">';
		echo '						<button type="button" class="btn" id="btnCargaDomicilioCN" name="btnCargaDomicilioCN" title="'.translate('Lbl_New_Home_Address_User',$GLOBALS['lang']).'" onclick="verDomicilioNC();"><i class="fa fa-eye"></i></button>';
		echo '						&nbsp;<button type="button" class="btn" id="btnCargaTelefonoCN" name="btnCargaTelefonoCN" title="'.translate('Lbl_New_Phone_Client',$GLOBALS['lang']).'" onclick="verTelefonoNC();"><i class="fas fa-phone"></i></button>';
		echo '					</div>';
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';
		echo '					<label class="control-label" for="observacionclientn">'.translate('Lbl_Observations_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="observacionclientn">';
		echo '						<textarea rows="5" cols="67" class="form-control input-sm" id="observacionclientni" name="observacionclientni" type="text" maxlength="500" />';
		echo '					</div>';		
		echo '				</div>';				
		echo '              <div id="mostrarDomicilioCargaN" style="display:none;"><hr />';
		echo '				  <div class="form-group form-inline">';
		echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="callen">'.translate('Lbl_Street',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="callen">';
		echo '						  <input title="'.translate('Msg_A_Street_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="calleni" name="calleni" type="text" maxlength="150" />';
		echo '					  </div>';							
		echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nrocallen">'.translate('Lbl_Number_Street',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="nrocallen">';
		echo '						  <input title="'.translate('Msg_A_Street_Number_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nrocalleni" name="nrocalleni" type="text" maxlength="11" />';
		echo '					  </div>';		
		echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domprovincian">'.translate('Lbl_State',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="domprovincian">';
		echo '						  <select class="form-control input-sm" name="domprovinciani" id="domprovinciani" style="width:190px;">';			 
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
		echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domlocalidadn">'.translate('Lbl_City',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="domlocalidadn">';
		echo '						  <input title="'.translate('Msg_A_City_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="domlocalidadni" name="domlocalidadni" type="text" maxlength="150" />';
		echo '					  </div>';							
		echo '					  &nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domdepartamenton">'.translate('Lbl_Departament',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="domdepartamenton">';
		echo '						  <input class="form-control input-sm" id="domdepartamentoni" name="domdepartamentoni" type="text" maxlength="10" />';
		echo '					  </div>';
		echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domfloorn">'.translate('Lbl_Floor',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="domfloorn">';
		echo '						  <input title="'.translate('Msg_A_Floor_Number_Must_Enter_A_Whole',$GLOBALS['lang']).'" class="form-control input-sm" id="domfloorni" name="domfloorni" type="text" maxlength="11" />';
		echo '					  </div>';				
		echo '				  </div>';
		echo '				  <div class="form-group form-inline">';
		echo '					  &nbsp;<label class="control-label" for="zipcoden">'.translate('Lbl_Zip_Code',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="zipcoden">';
		echo '						  <input class="form-control input-sm" id="zipcodeni" name="zipcodeni" type="text" maxlength="10" />';
		echo '					  </div>';				
		echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="entrecalle1n">'.translate('Msg_Between_Street_1',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="entrecalle1n">';
		echo '						  <input class="form-control input-sm" id="entrecalle1ni" name="entrecalle1ni" type="text" maxlength="150" />';
		echo '					  </div>';							
		echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="entrecalle2n">'.translate('Msg_Between_Street_2',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="entrecalle2n">';
		echo '						  <input class="form-control input-sm" id="entrecalle2ni" name="entrecalle2ni" type="text" maxlength="150" />';
		echo '					  </div>';				
		echo '				  </div>';		
		echo '              </div>';
		echo '              <div id="mostrarTelefonoCargaN" style="display:none;"><hr />';
		echo '				 <div class="form-group form-inline">';
		echo '					 &nbsp;&nbsp;<label class="control-label" for="tipotelefono">'.translate('Lbl_Type_Phone2',$GLOBALS['lang']).':</label>';
		echo '					 <div class="form-group" id="tipotelefono">';
		echo '						 <select class="form-control input-sm" name="tipotelefonoi" id="tipotelefonoi" style="width:190px;">';			 
										if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.tipo_telefono")) 
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
		echo '						 </select>';
		echo '					 </div>';
		echo '					 &nbsp;&nbsp;<label class="control-label" for="prefijotelefono">'.translate('Lbl_Pre_Number_Phone',$GLOBALS['lang']).':</label>';
		echo '					 <div class="form-group" id="prefijotelefono">';
		echo '						<input title="'.translate('Msg_A_Pre_Number_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="prefijotelefonoi" name="prefijotelefonoi" type="text" maxlength="4" />';
		echo '					 </div>';							
		echo '					 &nbsp;&nbsp;<label class="control-label" for="nrotelefono">'.translate('Lbl_Number_Phone',$GLOBALS['lang']).':</label>';
		echo '					 <div class="form-group" id="nrotelefono">';
		echo '						 <input title="'.translate('Msg_A_Number_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nrotelefonoi" name="nrotelefonoi" type="text" maxlength="16" />';
		echo '					 </div>';		
		echo '				 </div>';
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';
		echo '					<div id="img_loader_12"></div>';		
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarNC" id="btnCancelarNC" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialognewclient\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargarNC" id="btnCargarNC" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarNuevoCliente(document.getElementById(\'formularionc\'));"/>';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		return;
?>