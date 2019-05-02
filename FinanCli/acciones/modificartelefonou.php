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
		$idTelefono=htmlspecialchars($_POST["id_telefono"], ENT_QUOTES, 'UTF-8');
		
		if($_SESSION['username'] != $usuario)
		{
			echo translate('Msg_Edit_User_Not_Match_User_Logged',$GLOBALS['lang']);
			return;						
		}		
		
		if($stmt = $mysqli->prepare("SELECT t.id, t.tipo_telefono, t.numero, t.digitos_prefijo FROM finan_cli.usuario u, finan_cli.telefono t, finan_cli.usuario_x_telefono ut WHERE u.id LIKE(?) AND u.id = ut.id_usuario AND t.id = ut.id_telefono AND t.id = ?"))
		{
			$stmt->bind_param('si', $usuario, $idTelefono);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				$stmt->free_result();
				$stmt->close();
				echo translate('Msg_User_Or_Phone_Not_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{
				$stmt->bind_result($id_telefono_user, $user_tipo_telefono, $user_numero_telefono, $user_digitos_prefijo);			
				$stmt->fetch();
				
				echo '<div class="panel-group">';				
				echo '	<div class="panel panel-default">';
				echo '		<div id="panel-title-header" class="panel-heading">';
				echo '			<h3 class="panel-title">'.translate('Lbl_New_Phone',$GLOBALS['lang']).'</h3>';
				echo ' 		</div>';
				echo '		<div class="panel-body">';
				echo '			<div id="img_loader_7"></div>';
				echo '			<form id="formulariomtu" role="form">';
				echo '				<div class="form-group form-inline">';
				echo '					<label class="control-label" for="tipotelefonom">'.translate('Lbl_Type_Phone',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="tipotelefonom">';
				echo '						<select class="form-control input-sm" name="tipotelefonomi" id="tipotelefonomi" style="width:190px;">';			 
												if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.tipo_telefono")) 
												{ 
													$stmt->execute();    
													$stmt->store_result();
											 
													$stmt->bind_result($id_tipo_telefono,$tipo_telefono);
													while($stmt->fetch())
													{
														if($user_tipo_telefono == $id_tipo_telefono)
														{
															echo '<option selected value="'.$id_tipo_telefono.'">'.$tipo_telefono.'</option>';
														}
														else echo '<option value="'.$id_tipo_telefono.'">'.$tipo_telefono.'</option>';
													}
												}
												else  
												{
													echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
													return;			
												}
				echo '						</select>';
				echo '					</div>';
				echo '					&nbsp;&nbsp;<label class="control-label" for="prefijotelefonom">'.translate('Lbl_Pre_Number_Phone',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="prefijotelefonom">';
				echo '						<input title="'.translate('Msg_A_Pre_Number_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="prefijotelefonomi" name="prefijotelefonomi" type="text" maxlength="4" value="'.substr($user_numero_telefono,0,$user_digitos_prefijo).'" />';
				echo '					</div>';							
				echo '					&nbsp;&nbsp;<label class="control-label" for="nrotelefonom">'.translate('Lbl_Number_Phone',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="nrotelefonom">';
				echo '						<input title="'.translate('Msg_A_Number_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nrotelefonomi" name="nrotelefonomi" type="text" maxlength="16" value="'.substr($user_numero_telefono,$user_digitos_prefijo).'" />';
				echo '					</div>';		
				echo '				</div>';		
				echo '				<div class="form-group form-inline">';				
				echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarm" id="btnCancelarm" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogmodifyphone\').dialog(\'close\');" style="margin-left:10px;" />';
				echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargarm" id="btnCargarm" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarModificacionTelefono(document.getElementById(\'formulariomtu\'),'.$idTelefono.');"/>';											
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