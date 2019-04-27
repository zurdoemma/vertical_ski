<?php 		
		include ('../utiles/funciones.php');
		sec_session_start();
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');}
		if (!verificar_permisos_admin()){header('Location:../sinautorizacion.php');}

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
		
		$usuario=$_POST["usuario"];	

		if($stmt = $mysqli->prepare("SELECT u.id, u.nombre, u.apellido, u.tipo_documento, u.documento, u.email, u.id_perfil, u.id_sucursal, u.clave  FROM finan_cli.usuario u WHERE id LIKE(?)"))
		{
			$stmt->bind_param('s', $usuario);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_User_Not_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{
				$stmt->bind_result($user_id, $user_nombre, $user_apellido, $user_tipo_doc, $user_doc, $user_email, $user_perfil, $user_sucursal, $user_clave);
				$stmt->fetch();
				
				echo '<div class="panel-group">';				
				echo '	<div class="panel panel-default">';
				echo '		<div id="panel-title-header" class="panel-heading">';
				echo '			<h3 class="panel-title">'.translate('Msg_Edit_User',$GLOBALS['lang']).'</h3>';
				echo ' 		</div>';
				echo '		<div class="panel-body">';
				echo '			<div id="img_loader_2"></div>';
				echo '			<form id="formulariomu" role="form">';
				echo '				<div class="form-group form-inline">';
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="iduser">'.translate('Lbl_User',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="iduser">';
				echo '						<input title="'.translate('Msg_A_User_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="iduseri" name="iduseri" type="text" maxlength="50" value="'.$user_id.'" disabled/>';
				echo '					</div>';							
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nameuser">'.translate('Lbl_Name_User',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="nameuser">';
				echo '						<input title="'.translate('Msg_A_User_Name_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nameuseri" name="nameuseri" type="text" maxlength="100" value="'.$user_nombre.'" />';
				echo '					</div>';
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="surnameuser">'.translate('Lbl_Surname_User',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="nameuser">';
				echo '						<input title="'.translate('Msg_A_User_Surname_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="surnameuseri" name="surnameuseri" type="text" maxlength="100" value="'.$user_apellido.'" />';
				echo '					</div>';				
				echo '				</div>';
				echo '				<div class="form-group form-inline">';
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="tipodocuser">'.translate('Lbl_Type_Document_User',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="tipodocuser">';
				echo '						<select class="form-control input-sm" name="tipodocuseri" id="tipodocuseri" style="width:190px;">';			 
												if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.tipo_documento")) 
												{ 
													$stmt->execute();    
													$stmt->store_result();
											 
													$stmt->bind_result($id_tipo_doc,$name_tipo_doc);
													while($stmt->fetch())
													{
														if($id_tipo_doc == $user_tipo_doc)
														{
															echo '<option selected value="'.$id_tipo_doc.'">'.$name_tipo_doc.'</option>';
														}
														else echo '<option value="'.$id_tipo_doc.'">'.$name_tipo_doc.'</option>';
													}
												}
												else  
												{
													echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
													return;			
												}
				echo '						</select>';
				echo '					</div>';							
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="documentuser">'.translate('Lbl_Document_User',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="documentuser">';
				echo '						<input title="'.translate('Msg_A_User_Document_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="documentuseri" name="documentuseri" type="text" maxlength="20" value="'.$user_doc.'" />';
				echo '					</div>';
				echo '					&nbsp;&nbsp;<label class="control-label" for="homeaddressuser">'.translate('Lbl_Home_Address_And_Phone',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="homeaddressuser">';
				echo '						<button type="button" class="btn" title="'.translate('Lbl_View_Home_Address',$GLOBALS['lang']).'" onclick="window.open(\'domiciliosusers.php?usuario='.$user_id.'\',\'_blank\');"><i class="fa fa-eye"></i></button>';
				echo '						&nbsp;&nbsp<button type="button" class="btn" title="'.translate('Lbl_View_Phones',$GLOBALS['lang']).'" onclick="window.open(\'phonesusers.php?usuario='.$user_id.'\',\'_blank\');"><i class="fa fa-phone"></i></button>';
				echo '					</div>';				
				echo '				</div>';
				echo '				<div class="form-group form-inline">';
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="emailuser">'.translate('Lbl_Email_User',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="emailuser">';
				echo '						<input title="'.translate('Msg_A_User_Email_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="emailuseri" name="emailuseri" type="text" maxlength="250" value="'.$user_email.'" />';
				echo '					</div>';				
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="perfiluser">'.translate('Lbl_Perfil_User',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="perfiluser">';
				echo '						<select class="form-control input-sm" name="perfiluseri" id="perfiluseri" style="width:190px;">';			 
												if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.perfil")) 
												{ 
													$stmt->execute();    
													$stmt->store_result();
											 
													$stmt->bind_result($id_perfil,$name_perfil);
													while($stmt->fetch())
													{
														if($id_perfil == $user_perfil)
														{
															echo '<option selected value="'.$id_perfil.'">'.$name_perfil.'</option>';
														}
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
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="sucursaluser">'.translate('Lbl_Tender_User',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="sucursaluser">';
				echo '						<select class="form-control input-sm" name="sucursaluseri" id="sucursaluseri" style="width:190px;">';			 
												if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.sucursal")) 
												{ 
													$stmt->execute();    
													$stmt->store_result();
											 
													$stmt->bind_result($id_sucursal,$name_sucursal);
													while($stmt->fetch())
													{
														if($id_sucursal == $user_sucursal)
														{
															echo '<option selected value="'.$id_sucursal.'">'.$name_sucursal.'</option>';
														}
														else echo '<option value="'.$id_sucursal.'">'.$name_sucursal.'</option>';
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
				echo '					<label class="control-label" for="claveactualuser">'.translate('Lbl_Current_Password',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="claveactualuser">';
				echo '						<input class="form-control input-sm" id="claveactualuseri" name="claveactualuseri" type="password" maxlength="128" disabled />';
				echo '					</div>';				
				echo '					&nbsp;&nbsp;<label class="control-label" for="claveuser">'.translate('Lbl_New_Password',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="claveuser">';
				echo '						<input title="'.translate('Msg_A_User_New_Password_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="claveuseri" name="claveuseri" type="password" maxlength="128" />';
				echo '					</div>';
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="rclaveuser">'.translate('Lbl_Repeat_Password',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="rclaveuser">';
				echo '						<input title="'.translate('Msg_A_User_Confirm_New_Password_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="rclaveuseri" name="rclaveuseri" type="password" maxlength="128" />';
				echo '					</div>';				
				echo '				</div>';
				echo '				<div class="form-group form-inline">';				
				echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelar" id="btnCancelar" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogmoduser\').dialog(\'close\');" style="margin-left:10px;" />';
				echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargar" id="btnCargar" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarModificacionUsuario(document.getElementById(\'formulariomu\'));"/>';										
				echo '				</div>';				
				echo '			</form>';
				echo '		</div>';
				echo '	</div>';
				echo '</div>';
				return;
			}

		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}
?>