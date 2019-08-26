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
		
		$tokenVCC=htmlspecialchars($_POST["tokenVCC"], ENT_QUOTES, 'UTF-8');
		$idCliente=htmlspecialchars($_POST["idCliente"], ENT_QUOTES, 'UTF-8');
		$idTelefono=htmlspecialchars($_POST["idTelefono"], ENT_QUOTES, 'UTF-8');

		$tipoTelefono=htmlspecialchars($_POST["tipoTelefono"], ENT_QUOTES, 'UTF-8');		
		$prefijoTelefono=htmlspecialchars($_POST["prefijoTelefono"], ENT_QUOTES, 'UTF-8');
		$nroTelefono=htmlspecialchars($_POST["nroTelefono"], ENT_QUOTES, 'UTF-8');
		
		if ($stmt500 = $mysqli->prepare("SELECT c.id, c.razon_social FROM finan_cli.cadena c, finan_cli.usuario u, finan_cli.sucursal s WHERE u.id_sucursal = s.id AND s.id_cadena = c.id AND u.id = ?")) 
		{
			$stmt500->bind_param('s', $_SESSION['username']);
			$stmt500->execute();    
			$stmt500->store_result();
	 
			$totR500 = $stmt500->num_rows;
			if($totR500 > 0)
			{
				$stmt500->bind_result($id_cadena_user, $nombre_cadena_validacion_celular);
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
		
		if($stmt4 = $mysqli->prepare("SELECT c.id, c.tipo_documento, c.documento FROM finan_cli.cliente c, finan_cli.telefono t, finan_cli.cliente_x_telefono ct WHERE c.id = ? AND c.tipo_documento = ct.tipo_documento AND c.documento = ct.documento AND t.id = ct.id_telefono AND t.id = ?"))
		{
			$stmt4->bind_param('ii', $idCliente, $idTelefono);
			$stmt4->execute();    
			$stmt4->store_result();
			
			$totR4 = $stmt4->num_rows;

			if($totR4 == 0)
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
		
		$stmt4->bind_result($id_client, $type_document_client, $document_client);
		$stmt4->fetch();
		
		$tipoDocumento = $type_document_client;
		$documento = $document_client;
		
		$stmt4->free_result();
		$stmt4->close();		
		
		if($tipoTelefono != 1)
		{
			echo translate('Msg_Only_Mobile_Phones_Can_Be_Validated',$GLOBALS['lang']);
			return;
		}
		
		if($stmt = $mysqli->prepare("SELECT tvc.id FROM finan_cli.token_validacion_celular tvc WHERE tvc.tipo_documento = ? AND tvc.documento = ? AND tvc.fecha like ? AND tvc.token = ? AND tvc.validado = 0 AND tvc.nro_telefono = ?"))
		{
			$date_registro_a_vcc = date("Ymd")."%";
			$telefonoFin = $prefijoTelefono.$nroTelefono;
			$stmt->bind_param('isssi', $tipoDocumento, $documento, $date_registro_a_vcc, $tokenVCC, $telefonoFin);
			$stmt->execute();    
			$stmt->store_result();
			
			$totR = $stmt->num_rows;

			if($totR > 0)
			{
				echo translate('Msg_Mobile_Phones_Not_Validated',$GLOBALS['lang']);
				return;
			}			
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}

		if($stmt2 = $mysqli->prepare("SELECT tvc.id FROM finan_cli.token_validacion_celular tvc WHERE tvc.tipo_documento = ? AND tvc.documento = ? AND tvc.fecha like ? AND tvc.token = ? AND tvc.validado = 1 AND tvc.nro_telefono = ?"))
		{
			$date_registro_a_vcc = date("Ymd")."%";
			$stmt2->bind_param('isssi', $tipoDocumento, $documento, $date_registro_a_vcc, $tokenVCC, $telefonoFin);
			$stmt2->execute();    
			$stmt2->store_result();
			
			$totR2 = $stmt2->num_rows;

			if($totR2 > 0)
			{
				echo translate('Msg_Validation_Mobile_Is_Not_Necessary',$GLOBALS['lang']);
				return;
			}			
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		
		$digits = 4;
		$codigo_validacion_vcc = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);		
		$resultado_env = envio_sms(translate('Lbl_From_SMS_ID_Sent',$GLOBALS['lang']), $prefijoTelefono.$nroTelefono, str_replace("%4",$nombre_cadena_validacion_celular,translate('Msg_Client_Modification_Phone_Verification_Code',$GLOBALS['lang'])).': '.$codigo_validacion_vcc);
		
		if(translate('Msg_Message_Sent_Succesfully',$GLOBALS['lang']) == $resultado_env)
		{			
			$tokenVCe = md5(uniqid(rand(), true));
			$tokenVCe = hash('sha512', $tokenVCe);
			
			$mysqli->autocommit(FALSE);
			$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
			
			if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.token_validacion_celular(fecha,tipo_documento,documento,token,codigo,usuario,validado,nro_telefono) VALUES (?,?,?,?,?,?,?,?)"))
			{
				echo $mysqli->error;
				$mysqli->autocommit(TRUE);
				$stmt->free_result();
				$stmt->close();
				return;
			}
			else
			{
				$date_registro_a_vcc = date("YmdHis");
				$validado_vcc = 0;
				$stmt10->bind_param('sissssii', $date_registro_a_vcc, $tipoDocumento, $documento, $tokenVCe, $codigo_validacion_vcc, $_SESSION['username'], $validado_vcc, $telefonoFin);
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
				
				echo translate('Msg_Validation_Mobile_Client_OK',$GLOBALS['lang']).'=::=::=::'.$tokenVCe.'=:=:=:';
				echo '<div class="panel-group">';				
				echo '	<div class="panel panel-default">';
				echo '		<div id="panel-title-header" class="panel-heading">';
				echo '			<h3 class="panel-title">'.translate('Lbl_Valid_Code_SMS',$GLOBALS['lang']).'</h3>';
				echo ' 		</div>';
				echo '		<div class="panel-body">';
				echo '			<form id="formulariovsmsm" role="form">';		
				echo '				<div class="form-group form-inline">';
				echo '					<label class="control-label" for="codigovalidsmsm">'.translate('Lbl_Verification_Code_SMS',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="codigovalidsmsm">';
				echo '						<input title="'.translate('Msg_Verification_Code_SMS_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="codigovalidsmsmi" name="codigovalidsmsmi" type="text" maxlength="4" />';
				echo '					</div>';		
				echo '				</div>';
				echo '				<div class="form-group form-inline">';
				echo '					<div id="img_loader_14"></div>';		
				echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarVSMSM" id="btnCancelarVSMSM" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogvalidacioncelularclientem\').dialog(\'close\');" style="margin-left:10px;" />';
				echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarVSMSM" id="btnValidarVSMSM" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="verificarValidacionSMSModificacionTelefono(document.getElementById(\'formulariovsmsm\'),'.$idTelefono.');"/>';										
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
			echo translate('Msg_Mobile_Phones_Not_Validated',$GLOBALS['lang']);
			return;			
		}
?>