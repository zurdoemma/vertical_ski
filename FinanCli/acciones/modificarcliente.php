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
		
		if($stmt = $mysqli->prepare("SELECT c.id, c.tipo_documento, c.documento, c.nombres, c.apellidos, c.cuil_cuit, c.fecha_nacimiento, c.email, c.estado, c.observaciones, c.monto_maximo_credito, c.id_perfil_credito, c.id_genero, CASE WHEN c.id_titular IS NOT NULL THEN '".translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang'])."' ELSE '".translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang'])."' END AS tipoCuenta FROM finan_cli.cliente c WHERE c.id = ?"))
		{
			$stmt->bind_param('i', $idCliente);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_Client_Not_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{
				$stmt->bind_result($id_client, $type_document_client, $document_client, $name_client, $surname_client, $cuit_cuil_client, $fecha_nacimiento_client, $email_client, $state_client, $observaciones_client, $monto_maximo_credito_client, $perfil_credito_client, $genero_client, $type_account_client);
				$stmt->fetch();
				
				echo '<div class="panel-group">';				
				echo '	<div class="panel panel-default">';
				echo '		<div id="panel-title-header" class="panel-heading">';
				echo '			<h3 class="panel-title">'.translate('Lbl_Edit_Client',$GLOBALS['lang']).'</h3>';
				echo ' 		</div>';
				echo '		<div class="panel-body">';
				echo '			<form id="formulariomc" role="form">';
				if($type_account_client != translate('Lbl_Type_Client_Headline',$GLOBALS['lang']))
				{
					echo '			<div class="form-group form-inline">';
					echo '				&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="tipoclientm">'.translate('Lbl_Type_Client',$GLOBALS['lang']).':</label>';
					echo '				<div class="form-group" id="tipoclientm">';
					echo '					<select class="form-control input-sm" name="tipoclientmi" id="tipoclientmi" style="width:190px;">';			 
					echo '						<option value="'.translate('Lbl_Type_Client_Headline',$GLOBALS['lang']).'">'.translate('Lbl_Type_Client_Headline',$GLOBALS['lang']).'</option>';
					echo '						<option selected value="'.translate('Lbl_Type_Client_Additional',$GLOBALS['lang']).'">'.translate('Lbl_Type_Client_Additional',$GLOBALS['lang']).'</option>';
					echo '					</select>';
					echo '				</div>';
					echo '			</div>';
				}
				else
				{
					echo '			<div class="form-group form-inline">';
					echo '				&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="tipoclientm">'.translate('Lbl_Type_Client',$GLOBALS['lang']).':</label>';
					echo '				<div class="form-group" id="tipoclientm">';
					echo '					<select class="form-control input-sm" name="tipoclientmi" id="tipoclientmi" style="width:190px;" disabled>';			 
					echo '						<option selected value="'.translate('Lbl_Type_Client_Headline',$GLOBALS['lang']).'">'.translate('Lbl_Type_Client_Headline',$GLOBALS['lang']).'</option>';
					echo '					</select>';
					echo '				</div>';
					echo '			</div>';
				}				
				echo '				<div class="form-group form-inline">';		
				echo '					<div class="form-group" id="tokenveccm" style="display:none;">';
				echo '						<input class="form-control input-sm green-border" id="tokenvecci" name="tokenvecci" type="text" maxlength="128" />';
				echo '					</div>';
				echo '					<div class="form-group" id="tokenvctc" style="display:none;">';
				echo '						<input class="form-control input-sm green-border" id="tokenvctci" name="tokenvctci" type="text" maxlength="128" />';
				echo '					</div>';				
				echo '				</div>';		
				echo '				<div class="form-group form-inline text-center"><hr />';
				echo '					<div id="validarestadocrediticiocliente">';				
				echo '						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="validarstatuscreditcliente">'.translate('Lbl_Valid_Status_Credit_Client',$GLOBALS['lang']).':</label>';			
				echo '						<div class="form-group" id="validarstatuscreditcliente">';	
				echo '							<label class="switch">';
				echo '						  	<input type="checkbox" id="validarstatuscreditclientei" name="validarstatuscreditclientei" />';
				echo '						  	<span class="slider round"></span>';
				echo '							</label>';
				echo '						</div>';
				echo '					</div>';				
				echo '				</div>';
				echo '				<div class="form-group form-inline"><hr />';
				echo '					<div class="form-group" id="idclientem" style="display:none;">';
				echo '						<input class="form-control input-sm green-border" id="idclientemi" name="idclientemi" type="text" maxlength="11" value="'.$id_client.'" />';
				echo '					</div>';				
				echo '					&nbsp;&nbsp;<label class="control-label" for="tipodocumentoclient">'.translate('Lbl_Type_Document_Client2',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="tipodocumentoclient">';
				echo '						<select class="form-control input-sm" name="tipodocumentoclienti" id="tipodocumentoclienti" style="width:190px;">';			 
												if ($stmt10 = $mysqli->prepare("SELECT id, nombre FROM finan_cli.tipo_documento")) 
												{ 
													$stmt10->execute();    
													$stmt10->store_result();
											 
													$stmt10->bind_result($id_tipo_doc,$nombre_tipo_doc);
													while($stmt10->fetch())
													{
														if($type_document_client == $id_tipo_doc)
														{
															echo '<option selected value="'.$id_tipo_doc.'">'.$nombre_tipo_doc.'</option>';
														}
														else echo '<option value="'.$id_tipo_doc.'">'.$nombre_tipo_doc.'</option>';
													}
												}
												else  
												{
													echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
													return;			
												}
				echo '						</select>';
				echo '					</div>';		
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="documento">'.translate('Lbl_Document_Client',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="documento">';
				echo '						<input title="'.translate('Msg_A_Document_Client_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="documentoi" name="documentoi" type="text" maxlength="20" value="'.$document_client.'" />';
				echo '					</div>';											
				echo '				</div>';
				echo '				<div class="form-group form-inline">';
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nombreclient">'.translate('Lbl_Names_Client',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="nombreclient">';
				echo '						<input title="'.translate('Msg_A_Name_Client_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nombreclienti" name="nombreclienti" type="text" maxlength="150" value="'.$name_client.'" />';
				echo '					</div>';
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="apellidoclient">'.translate('Lbl_Surnames_Client',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="apellidoclient">';
				echo '						<input title="'.translate('Msg_A_Surname_Client_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="apellidoclienti" name="apellidoclienti" type="text" maxlength="150" value="'.$surname_client.'" />';
				echo '					</div>';		
				echo '				</div>';		
				echo '				<div class="form-group form-inline">';
										$fechaNacimiento = substr($fecha_nacimiento_client, 6, 2).'/'.substr($fecha_nacimiento_client, 4, 2).'/'.substr($fecha_nacimiento_client, 0, 4);
				echo '					&nbsp;<label class="control-label" for="fechanacimientoclient">'.translate('Lbl_Date_Birthday_Client',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="fechanacimientoclientn">';
				echo '						<div class="input-group date" id="datetimepickerfechanacimientoclient">';		
				echo '							<input title="'.translate('Msg_A_Date_Birthday_Client_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="fechanacimientoclienti" name="fechanacimientoclienti" type="text" maxlength="10" placeholder="'.translate('Lbl_Format_Date_Birthday_Client',$GLOBALS['lang']).'"  style="width: 152px;" value="'.$fechaNacimiento.'" />';
				echo '							<span class="input-group-addon">';
				echo '								<span class="glyphicon glyphicon-calendar"></span>';
				echo '							</span>';		
				echo '					    </div>';
				echo '					</div>';		
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="cuitcuilclient">'.translate('Lbl_Cuit_Cuil_Client',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="cuitcuilclient">';
				echo '						<input title="'.translate('Msg_A_Cuit_Cuil_Client_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="cuitcuilclienti" name="cuitcuilclienti" type="text" maxlength="20" value="'.$cuit_cuil_client.'" />';
				echo '					</div>';		
				echo '				</div>';
				echo '				<div class="form-group form-inline">';
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="emailclient">'.translate('Lbl_Email_Client',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="emailclient">';
											if($email_client == '---') $email_client_f = '';
											else $email_client_f = $email_client;
				echo '						<input title="'.translate('Msg_A_Emial_Client_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="emailclienti" name="emailclienti" type="text" maxlength="250" value="'.$email_client_f.'" />';
				echo '					</div>';
				echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="montomaximoclient">'.translate('Lbl_Max_Amount_Client',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="montomaximoclient">';
				echo '						<input title="'.translate('Msg_A_Max_Amount_Client_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="montomaximoclienti" name="montomaximoclienti" type="text" maxlength="11" value="'.number_format(($monto_maximo_credito_client/100.00),2).'" />';
				echo '					</div>';		
				echo '				</div>';
				echo '				<div class="form-group form-inline">';
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="generoclient">'.translate('Lbl_Gender_Client',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="generoclient">';
				echo '						<select class="form-control input-sm" name="generoclienti" id="generoclienti" style="width:190px;">';			 
												if ($stmt10 = $mysqli->prepare("SELECT id, nombre FROM finan_cli.genero")) 
												{ 
													$stmt10->execute();    
													$stmt10->store_result();
											 
													$stmt10->bind_result($id_genero,$nombre_genero);
													while($stmt10->fetch())
													{
														if($genero_client == $id_genero)
														{
															echo '<option selected value="'.$id_genero.'">'.$nombre_genero.'</option>';
														}
														else echo '<option value="'.$id_genero.'">'.$nombre_genero.'</option>';
													}
												}
												else  
												{
													echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
													return;			
												}
				echo '						</select>';
				echo '					</div>';	
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="homeaddressandphoneclient">'.translate('Lbl_Home_Address_And_Phone',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="homeaddressandphoneclient">';
				echo '						<button type="button" class="btn" id="btnCargaDomicilioC" name="btnCargaDomicilioC" title="'.translate('Lbl_View_Home_Address',$GLOBALS['lang']).'" onclick="adminDomiciliosC(\''.$type_document_client.'\',\''.$document_client.'\');"><i class="fa fa-eye"></i></button>';
				echo '						&nbsp;<button type="button" class="btn" id="btnCargaTelefonoC" name="btnCargaTelefonoC" title="'.translate('Lbl_View_Phones',$GLOBALS['lang']).'" onclick="adminTelefonosC(\''.$type_document_client.'\',\''.$document_client.'\');"><i class="fas fa-phone"></i></button>';
				echo '					</div>';
				echo '				</div>';
				if($type_account_client == translate('Lbl_Type_Client_Headline',$GLOBALS['lang']))
				{
					echo '			<div class="form-group form-inline" id="diviperfilcreditoclient" name="diviperfilcreditoclient">';
					echo '				&nbsp;&nbsp;&nbsp;<label class="control-label" for="perfilcreditoclient" id="labelperfilcreditoclient" name="labelperfilcreditoclient" >'.translate('Lbl_Profile_Credit_Client',$GLOBALS['lang']).':</label>';
					echo '				<div class="form-group" id="perfilcreditoclient">';
					echo '					<select class="form-control input-sm" name="perfilcreditoclienti" id="perfilcreditoclienti" style="width:190px;">';			 
												if ($stmt10 = $mysqli->prepare("SELECT id, nombre FROM finan_cli.perfil_credito")) 
												{ 
													$stmt10->execute();    
													$stmt10->store_result();
												 
													$stmt10->bind_result($id_perfil_credito,$nombre_perfil_credito);
													while($stmt10->fetch())
													{
														if($perfil_credito_client == $id_perfil_credito)
														{
															echo '<option selected value="'.$id_perfil_credito.'">'.$nombre_perfil_credito.'</option>';
														}
														else echo '<option value="'.$id_perfil_credito.'">'.$nombre_perfil_credito.'</option>';
													}
												}
												else  
												{
													echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
													return;			
												}
					echo '					</select>';
					echo '				</div>';			
					echo '			</div>';	
				}				
				else
				{
					echo '			<div class="form-group form-inline" id="diviperfilcreditoclient" name="diviperfilcreditoclient" style="display:none;">';
					echo '				&nbsp;&nbsp;&nbsp;<label class="control-label" for="perfilcreditoclient" id="labelperfilcreditoclient" name="labelperfilcreditoclient" >'.translate('Lbl_Profile_Credit_Client',$GLOBALS['lang']).':</label>';
					echo '				<div class="form-group" id="perfilcreditoclient">';
					echo '					<select class="form-control input-sm" name="perfilcreditoclienti" id="perfilcreditoclienti" style="width:190px;">';			 
												if ($stmt10 = $mysqli->prepare("SELECT id, nombre FROM finan_cli.perfil_credito")) 
												{ 
													$stmt10->execute();    
													$stmt10->store_result();
												 
													$stmt10->bind_result($id_perfil_credito,$nombre_perfil_credito);
													while($stmt10->fetch())
													{
														if($perfil_credito_client == $id_perfil_credito)
														{
															echo '<option selected value="'.$id_perfil_credito.'">'.$nombre_perfil_credito.'</option>';
														}
														else echo '<option value="'.$id_perfil_credito.'">'.$nombre_perfil_credito.'</option>';
													}
												}
												else  
												{
													echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
													return;			
												}
					echo '					</select>';
					echo '				</div>';			
					echo '			</div>';	
				}					
				echo '				<div class="form-group form-inline">';
				echo '					<label class="control-label" for="observacionclient">'.translate('Lbl_Observations_Client',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="observacionclient">';
				echo '						<textarea rows="3" cols="67" class="form-control input-sm" id="observacionclienti" name="observacionclienti" type="text" maxlength="500">'.$observaciones_client.'</textarea>';
				echo '					</div>';		
				echo '				</div>';						
				echo '				<div class="form-group form-inline">';
				echo '					<div id="img_loader_12"></div>';		
				echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarC" id="btnCancelarC" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogmodifyclient\').dialog(\'close\');" style="margin-left:10px;" />';
				echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargarC" id="btnCargarC" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarModificacionCliente(document.getElementById(\'formulariomc\',\''.$id_client.'\'));"/>';										
				echo '				</div>';				
				echo '			</form>';
				echo '		</div>';
				echo '	</div>';
				echo '</div>';
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;	
		}
		return;
?>