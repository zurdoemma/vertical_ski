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
		
		if($stmt63 = $mysqli->prepare("SELECT cc.numero_cuota, cc.monto_cuota_original FROM finan_cli.cuota_credito cc WHERE cc.id_credito = ? AND cc.estado IN (?,?)"))
		{
			$estadoU = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
			$estadoD = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
			$stmt63->bind_param('iss', $idCredito, $estadoU, $estadoD);
			$stmt63->execute();    
			$stmt63->store_result();
			
			$totR63 = $stmt63->num_rows;

			$montoCuotas = 0;
			$nrosCuotas = "";
			if($totR63 > 0)
			{
				$stmt63->bind_result($numero_cuota_db, $monto_cuota_original_db);
				while($stmt63->fetch())
				{
					$montoCuotas = $montoCuotas + $monto_cuota_original_db;
					if($nrosCuotas == "") $nrosCuotas = $numero_cuota_db;
					else $nrosCuotas = $nrosCuotas.",".$numero_cuota_db;
				}
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
		
		if($stmt64 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM finan_cli.mora_cuota_credito mcc WHERE mcc.id_cuota_credito IN ($nrosCuotas)"))
		{
			$stmt64->execute();    
			$stmt64->store_result();
			
			$totR64 = $stmt64->num_rows;
			$monto_interes_cuotas_credito = 0;
			if($totR64 == 1)
			{
				$stmt64->bind_result($monto_interes_cuotas_credito_db);
				$stmt64->fetch();
				
				$monto_interes_cuotas_credito = $monto_interes_cuotas_credito_db;
				
				$stmt64->free_result();
				$stmt64->close();				
			}
			else if($totR64 == 0) 
			{
				$monto_interes_cuotas_credito = 0;			
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

		echo translate('Msg_View_Fees_Credit_OK',$GLOBALS['lang']);	
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Msg_Collection_Data_Fee_Credit',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_18"></div>';
		echo '			<form id="formulariopccs" role="form">';
		echo '				<div class="form-group form-inline">';
		echo '					<div class="form-group" id="idcuotascreditov" style="display:none;">';
		echo '						<input class="form-control input-sm green-border" id="idcuotascreditovi" name="idcuotascreditovi" type="text" maxlength="50" value="'.$nrosCuotas.'" disabled />';
		echo '					</div>';
		echo '					<div class="form-group" id="idcreditosvc3" style="display:none;">';
		echo '						<input class="form-control input-sm green-border" id="idcreditosvc3i" name="idcreditosvc3i" type="text" maxlength="11" value="'.$idCredito.'" disabled />';
		echo '					</div>';		
		echo '					&nbsp;<label class="control-label" for="numerocuotascreditv">'.translate('Lbl_Number_Fees_Pay_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="numerocuotascreditv">';
		echo '						<input class="form-control input-sm" id="numerocuotascreditvi" name="numerocuotascreditvi" type="text" maxlength="11" value="'.$totR63.'" disabled />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="montooriginalcuotascreditv">'.translate('Lbl_Amount_Original_Fees_Print_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="montooriginalcuotascreditv">';
		echo '						<input class="form-control input-sm" id="montooriginalcuotacreditvi" name="montooriginalcuotacreditvi" type="text" maxlength="11" value="'.str_replace(",",".",round(($montoCuotas/100.00),2)).'" disabled/>';
		echo '					</div>';				
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="interescuotascreditv">'.translate('Lbl_Amount_Interest_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="interescuotascreditv">';
		echo '						<input class="form-control input-sm" id="interescuotascreditvi" name="interescuotascreditvi" type="text" maxlength="11" value="'.str_replace(",",".",round(($monto_interes_cuota_credito/100.00),2)).'" disabled />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="montototalcuotascreditv">'.translate('Lbl_Total_Amount_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="montototalcuotascreditv">';
		echo '						<input class="form-control input-sm" title="'.translate('Msg_A_Total_Amount_Payment_Fee_Credit_Must_Enter',$GLOBALS['lang']).'" id="montototalcuotascreditvi" name="montototalcuotascreditvi" type="text" maxlength="11" value="'.str_replace(",",".",round((($montoCuotas+$monto_interes_cuota_credito)/100.00),2)).'" />';
		echo '					</div>';				
		echo '				</div>';
		echo '				<div class="form-group form-inline">';				
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarNC2" id="btnCancelarNC2" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogviewfeescredit\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnConfirmarNC2" id="btnConfirmarNC2" value="'.translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']).'" onClick="guardarPagoCuotasCredito(document.getElementById(\'formulariopccs\'));"/>';										
		echo '				</div>';		
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';		

		return;
?>