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
		
		$idCredito=htmlspecialchars($_POST["idCredito"], ENT_QUOTES, 'UTF-8');
		$idCuotaCredito=htmlspecialchars($_POST["idCuotaCredito"], ENT_QUOTES, 'UTF-8');
		
		if($stmt63 = $mysqli->prepare("SELECT cc.numero_cuota, cc.monto_cuota_original, cc.estado FROM ".$db_name.".cuota_credito cc WHERE cc.id = ? AND cc.id_credito = ? AND cc.estado = ?"))
		{
			$estadoD = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
			$stmt63->bind_param('iis', $idCuotaCredito, $idCredito, $estadoD);
			$stmt63->execute();    
			$stmt63->store_result();
			
			$totR63 = $stmt63->num_rows;

			if($totR63 > 0)
			{
				$stmt63->bind_result($numero_cuota_db, $monto_cuota_original_db, $estado_cuota_db);
				$stmt63->fetch();
				
				$stmt63->free_result();
				$stmt63->close();				
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
		
		if($stmt64 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM ".$db_name.".mora_cuota_credito mcc WHERE mcc.id_cuota_credito = ?"))
		{
			$stmt64->bind_param('i', $idCuotaCredito);
			$stmt64->execute();    
			$stmt64->store_result();
			
			$totR64 = $stmt64->num_rows;
			$monto_interes_cuota_credito = 0;
			if($totR64 == 1)
			{
				$stmt64->bind_result($monto_interes_cuota_credito_db);
				$stmt64->fetch();
				
				$monto_interes_cuota_credito = $monto_interes_cuota_credito_db;
				
				$stmt64->free_result();
				$stmt64->close();				
			}
			else if($totR64 == 0) 
			{
				$monto_interes_cuota_credito = 0;			
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

		echo translate('Msg_View_State_Fee_Credit_OK',$GLOBALS['lang']);	
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Msg_State_Fee_Credito',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_19"></div>';
		echo '			<form id="formulariocecc" role="form">';
		echo '				<div class="form-group form-inline">';
		echo '					<div class="form-group" id="idcuotacreditocev" style="display:none;">';
		echo '						<input class="form-control input-sm green-border" id="idcuotacreditocevi" name="idcuotacreditocevi" type="text" maxlength="11" value="'.$idCuotaCredito.'" disabled />';
		echo '					</div>';
		echo '					<div class="form-group" id="idcreditovcec2" style="display:none;">';
		echo '						<input class="form-control input-sm green-border" id="idcreditovcec2i" name="idcreditovcec2i" type="text" maxlength="11" value="'.$idCredito.'" disabled />';
		echo '					</div>';
		echo '					<div class="form-group" id="numerocuotacreditvce" style="display:none;">';
		echo '						<input class="form-control input-sm" id="numerocuotacreditvcei" name="numerocuotacreditvcei" type="text" maxlength="11" value="'.$numero_cuota_db.'" disabled />';
		echo '					</div>';
		echo '					<div class="form-group" id="montototalcuotacreditvce" style="display:none;">';
		echo '						<input class="form-control input-sm" id="montototalcuotacreditvcei" name="montototalcuotacreditvcei" type="text" maxlength="11" value="'.($monto_cuota_original_db+$monto_interes_cuota_credito).'" disabled />';
		echo '					</div>';		
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="estadocuotacreditv">'.translate('Lbl_Current_State_Fee_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="estadocuotacreditv">';
		echo '						<input class="form-control input-sm" id="estadocuotacreditvi" name="estadocuotacreditvi" type="text" maxlength="50" value="'.$estado_cuota_db.'" disabled/>';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nuevoestadocuotacreditv">'.translate('Lbl_New_State_Fee_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nuevoestadocuotacreditv">';
		echo '						<select class="form-control input-sm" name="nuevoestadocuotacreditvi" id="nuevoestadocuotacreditvi" style="width:190px;">';		
		echo '							<option selected value="'.translate('Lbl_Status_Fee_Condoned',$GLOBALS['lang']).'">'.translate('Lbl_Status_Fee_Condoned',$GLOBALS['lang']).'</option>';		
		echo '							<option selected value="'.translate('Lbl_Status_Fee_Incobrable',$GLOBALS['lang']).'">'.translate('Lbl_Status_Fee_Incobrable',$GLOBALS['lang']).'</option>';
		echo '						</select>';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';				
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarNCE" id="btnCancelarNCE" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogviewfeestatuschangecredit\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnConfirmarNCE" id="btnConfirmarNCE" value="'.translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']).'" onClick="guardarCambioEstadoCuotaCredito(document.getElementById(\'formulariocecc\'));"/>';										
		echo '				</div>';		
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';		

		return;
?>