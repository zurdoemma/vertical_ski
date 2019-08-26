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
		$idTelefono=htmlspecialchars($_POST["idTelefono"], ENT_QUOTES, 'UTF-8');
		$prefijoTelefono=htmlspecialchars($_POST["prefijoTelefono"], ENT_QUOTES, 'UTF-8');
		$nroTelefono=htmlspecialchars($_POST["nroTelefono"], ENT_QUOTES, 'UTF-8');
		
		if($stmt44 = $mysqli->prepare("SELECT c.id, c.tipo_documento, c.documento FROM finan_cli.cliente c, finan_cli.telefono t, finan_cli.cliente_x_telefono ct WHERE c.id = ? AND c.tipo_documento = ct.tipo_documento AND c.documento = ct.documento AND ct.id_telefono = t.id AND t.id = ?"))
		{
			$stmt44->bind_param('ii', $idCliente, $idTelefono);
			$stmt44->execute();    
			$stmt44->store_result();
			
			$totR44 = $stmt44->num_rows;

			if($totR44 == 0)
			{
				echo translate('Msg_Client_Or_Phone_Not_Exist',$GLOBALS['lang']);
				return;
			}			
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		
		$stmt44->bind_result($id_client, $type_document_client, $document_client);
		$stmt44->fetch();
		
		$tipoDocumento = $type_document_client;
		$documento = $document_client;
		
		$stmt44->free_result();
		$stmt44->close();
			
		if($stmt4 = $mysqli->prepare("SELECT e.id FROM finan_cli.estado_cliente e WHERE e.tipo_documento = ? AND e.documento = ? AND e.fecha like ? AND e.id_motivo = ? AND e.nro_telefono = ?"))
		{
			$date_registro_a_s = date("Ymd")."%";
			$telefonoFin = $prefijoTelefono.$nroTelefono;
			$stmt4->bind_param('issii', $tipoDocumento, $documento, $date_registro_a_s, $motivo, $telefonoFin);
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
			
			if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.estado_cliente(fecha,tipo_documento,documento,id_motivo,usuario,nro_telefono,token) VALUES (?,?,?,?,?,?,?)"))
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
				$tokenVCST2 = md5(uniqid(rand(), true));
				$tokenVCST2 = hash('sha512', $tokenVCST2);
				$stmt10->bind_param('sisisis', $date_registro_a_s_db, $tipoDocumento, $documento, $motivo, $_SESSION['username'], $telefonoFin, $tokenVCST2);
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

		echo translate('Msg_Must_Authorize_Phone_Modify',$GLOBALS['lang']);
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Lbl_Authorize_Phone_Modify',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<form id="formularionacrtm" role="form">';		
		echo '				<div class="form-group form-inline">';
		echo '					<label class="control-label" for="usuariosupervisorn2m">'.translate('Lbl_User_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="usuariosupervisorn2m">';
		echo '						<input title="'.translate('Msg_User_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="usuariosupervisorn2mi" name="usuariosupervisorn2mi" type="text" maxlength="50" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="passwordsupervisorn2m">'.translate('Lbl_Password_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="passwordsupervisorn2m">';
		echo '						<input title="'.translate('Msg_Password_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="passwordsupervisorn2mi" name="passwordsupervisorn2mi" type="password" maxlength="128" />';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					<div id="img_loader_13"></div>';		
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarVS2M" id="btnCancelarVS2M" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogautorizacionregistrotelefonom\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarS2M" id="btnValidarS2M" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarAutorizacionSupervisorModificacionTelefono(document.getElementById(\'formularionacrtm\'),'.$motivo.','.$idTelefono.');"/>';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';		
		
		return;
?>