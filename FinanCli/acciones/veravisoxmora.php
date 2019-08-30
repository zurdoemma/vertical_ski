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
		
		$idAvisoXMora=htmlspecialchars($_POST["idAvisoXMora"], ENT_QUOTES, 'UTF-8');
		
		if ($stmt = $mysqli->prepare("SELECT axm.fecha, cli.nombres, cli.apellidos, c.id, ccre.numero_cuota, ccre.id, axm.estado, axm.comentario, axm.mensaje FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.cliente cli, finan_cli.cuota_credito ccre, finan_cli.aviso_x_mora axm WHERE axm.id_credito = c.id AND c.id = ccre.id_credito AND c.id = cc.id_credito AND cc.tipo_documento = cli.tipo_documento AND cc.documento = cli.documento AND axm.id_cuota_credito = ccre.id AND axm.id = ?")) 
		{
			$stmt->bind_param('i', $idAvisoXMora);
			$stmt->execute();    
			$stmt->store_result();
	 
			$stmt->bind_result($date_default_notice, $nombre_cli_default_notice, $apellido_cli_default_notice, $id_credito_default_notice, $numero_cuota_credito_default_notice, $id_cuota_credito_default_notice, $estado_default_notice, $comentario_default_notice, $mensaje_default_notice);			
			
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_Default_Notice_Not_Exist',$GLOBALS['lang']);
				return;	
			}					
			
			$stmt->fetch();
			if($stmt62 = $mysqli->prepare("SELECT cc.monto_cuota_original FROM finan_cli.cuota_credito cc WHERE cc.id = ?"))
			{
				$stmt62->bind_param('i', $id_cuota_credito_default_notice);
				$stmt62->execute();    
				$stmt62->store_result();
				
				$totR62 = $stmt62->num_rows;

				if($totR62 > 0)
				{
					$stmt62->bind_result($monto_original_cuota_db);
					$stmt62->fetch();
					
					$stmt62->free_result();
					$stmt62->close();
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
			
			$stmt->free_result();
			$stmt->close();
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;	
		}
		
		$montoIntereses = 0;
		if($stmt63 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM finan_cli.mora_cuota_credito mcc, finan_cli.cuota_credito cc WHERE mcc.id_cuota_credito = cc.id AND cc.id = ?"))
		{
			$stmt63->bind_param('i', $id_cuota_credito_default_notice);
			$stmt63->execute();    
			$stmt63->store_result();
			
			$totR63 = $stmt63->num_rows;

			if($totR63 > 0)
			{
				$stmt63->bind_result($monto_interes_cuota_credito_db);
				$stmt63->fetch();
				
				$montoIntereses = $monto_interes_cuota_credito_db;
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}			
		$montoTotalConInteresesCredito = $monto_original_cuota_db + $montoIntereses;		
			
		echo translate('Msg_Search_Default_Notices_OK',$GLOBALS['lang']);	
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Lbl_View_Data_Default_Notice',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_16"></div>';
		echo '			<form id="formularioiaxm" role="form">';			
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="fechadefaultnoticev">'.translate('Lbl_Date',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="fechadefaultnoticev">';
		echo '						<input class="form-control input-sm green-border" id="fechadefaultnoticevi" name="fechadefaultnoticevi" type="text" maxlength="20" value="'.substr($date_default_notice,6,2).'/'.substr($date_default_notice,4,2).'/'.substr($date_default_notice,0,4).' '.substr($date_default_notice,8,2).':'.substr($date_default_notice,10,2).':'.substr($date_default_notice,12,2).'" disabled/>';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nombreclientdefaultnoticev">'.translate('Lbl_Names_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nombreclientdefaultnoticev">';
		echo '						<input class="form-control input-sm" id="nombreclientdefaultnoticevi" name="nombreclientdefaultnoticevi" type="text" maxlength="150" value="'.$nombre_cli_default_notice.'" disabled />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="apellidoclientdefaultnoticev">'.translate('Lbl_Surnames_Client',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="apellidoclientdefaultnoticev">';
		echo '						<input class="form-control input-sm" id="apellidoclientdefaultnoticevi" name="apellidoclientdefaultnoticevi" type="text" maxlength="150" value="'.$apellido_cli_default_notice.'" disabled/>';
		echo '					</div>';		
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="idcreditodefaultnoticev">'.translate('Lbl_ID_Credit_Default_Notice',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="idcreditodefaultnoticev">';
		echo '						<input class="form-control input-sm" id="idcreditodefaultnoticevi" name="idcreditodefaultnoticevi" type="text" maxlength="11" value="'.$id_credito_default_notice.'" disabled />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="numerocuotadefaultnoticev">'.translate('Lbl_Number_Fee_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="numerocuotadefaultnoticev">';
		echo '						<input class="form-control input-sm" id="numerocuotadefaultnoticevi" name="numerocuotadefaultnoticevi" type="text" maxlength="11" value="'.$numero_cuota_credito_default_notice.'" disabled/>';
		echo '					</div>';
		echo '					 &nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="montodeudacuotacredito">'.translate('Lbl_Total_Amount_Debt_Default_Notice',$GLOBALS['lang']).':</label>';
		echo '					 <div class="form-group" id="montodeudacuotacredito">';
		echo '						<input class="form-control input-sm" id="montodeudacuotacreditoi" name="montodeudacuotacreditoi" type="text" maxlength="11" value="'.round(($montoTotalConInteresesCredito/100.00),2).'" disabled />';
		echo '					 </div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="estadodefaultnoticev">'.translate('Lbl_State_User',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="estadodefaultnoticev">';
		echo '						<input class="form-control input-sm" id="estadodefaultnoticevi" name="estadodefaultnoticevi" type="text" maxlength="50" value="'.$estado_default_notice.'" disabled />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="tiposavisodefaultnoticev">'.translate('Lbl_Types_Of_Notice',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="tiposavisodefaultnoticev">';
		echo '						<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_See_SMS_Shipments_Default_Notice',$GLOBALS['lang']).'" onclick="verEnviosSMS('.$idAvisoXMora.')"><i class="fas fa-sms"></i></button>';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline"><hr />';
		echo '					<label class="control-label" for="comentariodefaultnoticev">'.translate('Msg_Comments_Default_Notice',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="comentariodefaultnoticev">';
		echo '						<textarea rows="5" cols="48" class="form-control input-sm" id="comentariodefaultnoticevi" name="comentariodefaultnoticevi" type="text" maxlength="500" readonly>'.$comentario_default_notice.'</textarea>';
		echo '					</div>';		
		echo '					&nbsp;&nbsp;<label class="control-label" for="mensajedefaultnoticev">'.translate('Msg_Default_Notice',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="mensajedefaultnoticev">';
		echo '						<textarea rows="5" cols="48" class="form-control input-sm" id="mensajedefaultnoticevi" name="mensajedefaultnoticevi" type="text" maxlength="500" readonly>'.$mensaje_default_notice.'</textarea>';
		echo '					</div>';
		echo '				</div>';
		echo '				<div class="form-group form-inline">';				
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnSalirVC" id="btnSalirVC" value="'.translate('Lbl_Exit',$GLOBALS['lang']).'" onClick="$(\'#dialogviewdefaultnotice\').dialog(\'close\');" style="margin-left:10px;" />';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		return;
?>