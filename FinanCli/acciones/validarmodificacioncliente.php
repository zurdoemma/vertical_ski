<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');return;}
		if (!verificar_permisos_usuario()){header('Location:../sinautorizacion.php');return;}

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
		
		$tokenCTC=htmlspecialchars($_POST["tokenCTC"], ENT_QUOTES, 'UTF-8');
		$idCliente=htmlspecialchars($_POST["idCliente"], ENT_QUOTES, 'UTF-8');
		$tipoCuenta=htmlspecialchars($_POST["tipoCuenta"], ENT_QUOTES, 'UTF-8');

		if($stmt4 = $mysqli->prepare("SELECT c.id, c.id_titular, c.tipo_documento, c.documento FROM ".$db_name.".cliente c WHERE c.id = ?"))
		{
			$stmt4->bind_param('i', $idCliente);
			$stmt4->execute();    
			$stmt4->store_result();
			
			$totR4 = $stmt4->num_rows;

			if($totR4 == 0)
			{
				echo translate('Msg_Client_Not_Exist',$GLOBALS['lang']);
				return;
			}			
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		
		$stmt4->bind_result($id_cliente_db, $tipo_cuenta_cliente_db, $tipo_documento_cliente_db, $documento_cliente_db);
		$stmt4->fetch();
		
		$tipoDocumento=$tipo_documento_cliente_db;
		$documento=$documento_cliente_db;
		
		if(empty($tipo_cuenta_cliente_db)) $tipoCuentaCliente = translate('Lbl_Type_Client_Headline',$GLOBALS['lang']);
		else $tipoCuentaCliente = translate('Lbl_Type_Client_Additional',$GLOBALS['lang']);
		
		if($tipoCuenta != $tipoCuentaCliente)
		{
			if($tipoCuenta == translate('Lbl_Type_Client_Additional',$GLOBALS['lang']))
			{
				echo translate('Msg_It_Is_Not_Possible_To_Change_From_Holder_To_Additional_Account',$GLOBALS['lang']);
				return;
			}
			
			if($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3)
			{
				if($stmt = $mysqli->prepare("SELECT tcc.id FROM ".$db_name.".token_cambio_cuenta tcc WHERE tcc.token = ? AND tcc.tipo_documento = ? AND tcc.documento = ? AND tcc.fecha like ? AND tcc.validado = 1"))
				{
					$date_registro_a_s = date("Ymd")."%";
					$stmt->bind_param('siss', $tokenCTC, $tipoDocumento, $documento, $date_registro_a_s);
					$stmt->execute();    
					$stmt->store_result();
				
					$totR = $stmt->num_rows;
					
					if($totR > 0)
					{
						echo translate('Msg_It_Is_Not_Necessary_To_Authorize',$GLOBALS['lang']);
						return;
					}
					else
					{
						$mysqli->autocommit(FALSE);
						$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
						
						if(!$stmt10 = $mysqli->prepare("INSERT INTO ".$db_name.".token_cambio_cuenta(fecha,documento,tipo_documento,token,usuario,validado) VALUES (?,?,?,?,?,?)"))
						{
							echo $mysqli->error;
							$mysqli->autocommit(TRUE);
							$stmt->free_result();
							$stmt->close();
							return;
						}
						else
						{
							$date_registro_c_t_c_db = date("YmdHis");
							$tokenCTCDB = md5(uniqid(rand(), true));
							$tokenCTCDB = hash('sha512', $tokenCTCDB);
							$validadoDB = 1;
							$stmt10->bind_param('ssissi', $date_registro_c_t_c_db, $documento, $tipoDocumento, $tokenCTCDB, $_SESSION['username'], $validadoDB);
							if(!$stmt10->execute())
							{
								echo $mysqli->error;
								$mysqli->autocommit(TRUE);
								$stmt->free_result();
								$stmt->close();
								return;						
							}
							
							$mysqli->commit();
							$mysqli->autocommit(TRUE);
						}
					}
					
					$stmt->free_result();
					$stmt->close();
					
				}
				else
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;
				}				
				
				echo translate('Msg_It_Is_Not_Necessary_To_Authorize',$GLOBALS['lang']);
				return;
			}
			
			if(!empty($tokenCTC))
			{
				if($stmt5 = $mysqli->prepare("SELECT tcc.id, tcc.validado FROM ".$db_name.".token_cambio_cuenta tcc WHERE tcc.token = ? AND tcc.tipo_documento = ? AND tcc.documento = ? AND tcc.fecha like ?"))
				{
					$date_registro_a_s = date("Ymd")."%";
					$stmt5->bind_param('siss', $tokenCTC, $tipoDocumento, $documento, $date_registro_a_s);
					$stmt5->execute();    
					$stmt5->store_result();
				
					$totR5 = $stmt5->num_rows;
					
					if($totR5 > 0)
					{
						$stmt5->bind_result($id_tcc,$validado_tcc);
						$stmt5->fetch();
						
						if($validado_tcc == 1)
						{
							echo translate('Msg_It_Is_Not_Necessary_To_Authorize',$GLOBALS['lang']);
							return;
						}
						else
						{
							echo translate('Msg_Must_Authorize_Change_Type_Account',$GLOBALS['lang']);

							echo '<div class="panel-group">';				
							echo '	<div class="panel panel-default">';
							echo '		<div id="panel-title-header" class="panel-heading">';
							echo '			<h3 class="panel-title">'.translate('Lbl_Authorize_Change_Account',$GLOBALS['lang']).'</h3>';
							echo ' 		</div>';
							echo '		<div class="panel-body">';
							echo '			<form id="formularioctc" role="form">';		
							echo '				<div class="form-group form-inline">';
							echo '					<label class="control-label" for="usuariosupervisor">'.translate('Lbl_User_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
							echo '					<div class="form-group" id="usuariosupervisor">';
							echo '						<input title="'.translate('Msg_User_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="usuariosupervisori" name="usuariosupervisori" type="text" maxlength="50" />';
							echo '					</div>';
							echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="passwordsupervisor">'.translate('Lbl_Password_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
							echo '					<div class="form-group" id="passwordsupervisor">';
							echo '						<input title="'.translate('Msg_Password_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="passwordsupervisori" name="passwordsupervisori" type="password" maxlength="128" />';
							echo '					</div>';		
							echo '				</div>';
							echo '				<div class="form-group form-inline">';
							echo '					<div id="img_loader_13"></div>';		
							echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarVS" id="btnCancelarVS" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogautorizarcambiotipocuenta\').dialog(\'close\');" style="margin-left:10px;" />';
							echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarS" id="btnValidarS" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarAutorizacionSupervisorCambioCuenta(document.getElementById(\'formularioctc\'));"/>';										
							echo '				</div>';				
							echo '			</form>';
							echo '		</div>';
							echo '	</div>';
							echo '</div>';
						}
					}
					else
					{
						echo translate('Msg_Must_Authorize_Change_Type_Account',$GLOBALS['lang']);

						echo '<div class="panel-group">';				
						echo '	<div class="panel panel-default">';
						echo '		<div id="panel-title-header" class="panel-heading">';
						echo '			<h3 class="panel-title">'.translate('Lbl_Authorize_Change_Account',$GLOBALS['lang']).'</h3>';
						echo ' 		</div>';
						echo '		<div class="panel-body">';
						echo '			<form id="formularioctc" role="form">';		
						echo '				<div class="form-group form-inline">';
						echo '					<label class="control-label" for="usuariosupervisor">'.translate('Lbl_User_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
						echo '					<div class="form-group" id="usuariosupervisor">';
						echo '						<input title="'.translate('Msg_User_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="usuariosupervisori" name="usuariosupervisori" type="text" maxlength="50" />';
						echo '					</div>';
						echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="passwordsupervisor">'.translate('Lbl_Password_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
						echo '					<div class="form-group" id="passwordsupervisor">';
						echo '						<input title="'.translate('Msg_Password_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="passwordsupervisori" name="passwordsupervisori" type="password" maxlength="128" />';
						echo '					</div>';		
						echo '				</div>';
						echo '				<div class="form-group form-inline">';
						echo '					<div id="img_loader_13"></div>';		
						echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarVS" id="btnCancelarVS" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogautorizarcambiotipocuenta\').dialog(\'close\');" style="margin-left:10px;" />';
						echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarS" id="btnValidarS" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarAutorizacionSupervisorCambioCuenta(document.getElementById(\'formularioctc\'));"/>';										
						echo '				</div>';				
						echo '			</form>';
						echo '		</div>';
						echo '	</div>';
						echo '</div>';		
						
						return;
					}
					
					$stmt5->free_result();
					$stmt5->close();
					
				}
				else
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;
				}
			}
			else
			{
				echo translate('Msg_Must_Authorize_Change_Type_Account',$GLOBALS['lang']);

				echo '<div class="panel-group">';				
				echo '	<div class="panel panel-default">';
				echo '		<div id="panel-title-header" class="panel-heading">';
				echo '			<h3 class="panel-title">'.translate('Lbl_Authorize_Change_Account',$GLOBALS['lang']).'</h3>';
				echo ' 		</div>';
				echo '		<div class="panel-body">';
				echo '			<form id="formularioctc" role="form">';		
				echo '				<div class="form-group form-inline">';
				echo '					<label class="control-label" for="usuariosupervisor">'.translate('Lbl_User_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="usuariosupervisor">';
				echo '						<input title="'.translate('Msg_User_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="usuariosupervisori" name="usuariosupervisori" type="text" maxlength="50" />';
				echo '					</div>';
				echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="passwordsupervisor">'.translate('Lbl_Password_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="passwordsupervisor">';
				echo '						<input title="'.translate('Msg_Password_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="passwordsupervisori" name="passwordsupervisori" type="password" maxlength="128" />';
				echo '					</div>';		
				echo '				</div>';
				echo '				<div class="form-group form-inline">';
				echo '					<div id="img_loader_13"></div>';		
				echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarVS" id="btnCancelarVS" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogautorizarcambiotipocuenta\').dialog(\'close\');" style="margin-left:10px;" />';
				echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarS" id="btnValidarS" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarAutorizacionSupervisorCambioCuenta(document.getElementById(\'formularioctc\'));"/>';										
				echo '				</div>';				
				echo '			</form>';
				echo '		</div>';
				echo '	</div>';
				echo '</div>';				
			}
		}
		else
		{
			echo translate('Msg_It_Is_Not_Necessary_To_Authorize',$GLOBALS['lang']);
			return;
		}
		
		return;
?>