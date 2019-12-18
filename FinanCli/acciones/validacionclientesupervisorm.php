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
		
		$motivo=htmlspecialchars($_POST["motivo"], ENT_QUOTES, 'UTF-8');
		$idCliente=htmlspecialchars($_POST["idCliente"], ENT_QUOTES, 'UTF-8');		
		

		if($stmt40 = $mysqli->prepare("SELECT c.id, c.id_titular, c.tipo_documento, c.documento FROM ".$db_name.".cliente c WHERE c.id = ?"))
		{
			$stmt40->bind_param('i', $idCliente);
			$stmt40->execute();    
			$stmt40->store_result();
			
			$totR40 = $stmt40->num_rows;

			if($totR40 == 0)
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
		
		$stmt40->bind_result($id_cliente_db, $tipo_cuenta_cliente_db, $tipo_documento_cliente_db, $documento_cliente_db);
		$stmt40->fetch();
		$tipoDocumento=$tipo_documento_cliente_db;
		$documento=$documento_cliente_db;
		
		if($stmt4 = $mysqli->prepare("SELECT e.id FROM ".$db_name.".estado_cliente e WHERE e.tipo_documento = ? AND e.documento = ? AND e.fecha like ? AND e.id_motivo = ?"))
		{
			$date_registro_a_s = date("Ymd")."%";
			$stmt4->bind_param('issi', $tipoDocumento, $documento, $date_registro_a_s, $motivo);
			$stmt4->execute();    
			$stmt4->store_result();
			
			$totR4 = $stmt4->num_rows;

			if($totR4 > 0)
			{
				echo translate('Msg_It_Is_Not_Necessary_To_Authorize',$GLOBALS['lang']);
				return;
			}			
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		
		$stmt4->free_result();
		$stmt4->close();
		
		if($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3)
		{
			$mysqli->autocommit(FALSE);
			$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
			
			if(!$stmt10 = $mysqli->prepare("INSERT INTO ".$db_name.".estado_cliente(fecha,tipo_documento,documento,id_motivo,usuario,token) VALUES (?,?,?,?,?,?)"))
			{
				echo $mysqli->error;
				$mysqli->autocommit(TRUE);
				$stmt->free_result();
				$stmt->close();
				return;
			}
			else
			{
				$date_registro_a_s_db = date("YmdHis");
				$tokenVCS2 = md5(uniqid(rand(), true));
				$tokenVCS2 = hash('sha512', $tokenVCS2);
				$stmt10->bind_param('sisiss', $date_registro_a_s_db, $tipoDocumento, $documento, $motivo, $_SESSION['username'], $tokenVCS2);
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
				
				echo translate('Msg_It_Is_Not_Necessary_To_Authorize',$GLOBALS['lang']);
				return;				
			}				
		}							

		echo translate('Msg_Must_Authorize_Client_Modification',$GLOBALS['lang']);
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Lbl_Authorize_Client_Modification',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<form id="formularionacm" role="form">';		
		echo '				<div class="form-group form-inline">';
		echo '					<label class="control-label" for="usuariosupervisorn21">'.translate('Lbl_User_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="usuariosupervisorn21">';
		echo '						<input title="'.translate('Msg_User_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="usuariosupervisorn21i" name="usuariosupervisorn21i" type="text" maxlength="50" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="passwordsupervisorn21">'.translate('Lbl_Password_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="passwordsupervisorn21">';
		echo '						<input title="'.translate('Msg_Password_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="passwordsupervisorn21i" name="passwordsupervisorn21i" type="password" maxlength="128" />';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					<div id="img_loader_13"></div>';		
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarVSM2" id="btnCancelarVSM2" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogautorizacionmodificacioncliente\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarSM2" id="btnValidarSM2" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarAutorizacionSupervisorModificacionCliente(document.getElementById(\'formularionacm\'),'.$motivo.');"/>';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';		
		
		return;
?>