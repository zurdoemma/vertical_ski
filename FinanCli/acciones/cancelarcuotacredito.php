<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');return;}
		if (!verificar_permisos_supervisor()){header('Location:../sinautorizacion.php?activauto=1');return;}

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
		
		$idCuotaCredito=htmlspecialchars($_POST["idCuotaCredito"], ENT_QUOTES, 'UTF-8');
		
		if($stmt61 = $mysqli->prepare("SELECT s.id_cadena, s.id, s.nombre FROM ".$db_name.".usuario u, ".$db_name.".sucursal s WHERE u.id_sucursal = s.id AND u.id = ?"))
		{
			$stmt61->bind_param('s', $_SESSION['username']);
			$stmt61->execute();    
			$stmt61->store_result();
			
			$totR61 = $stmt61->num_rows;

			if($totR61 > 0)
			{
				$stmt61->bind_result($id_cadena_usuario, $id_sucursal_usuario, $nombre_sucursal_usuario);
				$stmt61->fetch();
								
				$stmt61->free_result();
				$stmt61->close();
			}
			else
			{
				echo translate('There_Is_ No_Chain_Associated_With_The_User',$GLOBALS['lang']);
				return;
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}		

		if ($stmt = $mysqli->prepare("SELECT cc.numero_cuota, cc.monto_cuota_original FROM ".$db_name.".cuota_credito cc WHERE cc.id = ? AND cc.estado = ?")) 
		{
			$estContrCPagada = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
			$stmt->bind_param('is', $idCuotaCredito, $estContrCPagada);
			$stmt->execute();    
			$stmt->store_result();
	 
			$stmt->bind_result($number_fee_credit_client, $montoCuotaCredito);			
			
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_Credit_Client_Fee_Not_Exist',$GLOBALS['lang']);
				return;	
			}								
			
			$stmt->fetch();

			$stmt->free_result();
			$stmt->close();
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;	
		}
		
		if($stmt63 = $mysqli->prepare("SELECT id_pago_total_credito FROM ".$db_name.".pago_total_credito_x_cuota WHERE id_cuota_credito = ?"))
		{
			$stmt63->bind_param('i', $idCuotaCredito);
			$stmt63->execute();    
			$stmt63->store_result();
			
			$totR63 = $stmt63->num_rows;

			if($totR63 > 0)
			{
				$stmt63->bind_result($id_pago_total_cuota_credito_db);
				$stmt63->fetch();
								
				$stmt63->free_result();
				$stmt63->close();
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		
		if($stmt64 = $mysqli->prepare("SELECT cc.monto_cuota_original FROM ".$db_name.".pago_total_credito_x_cuota ptxc, ".$db_name.".pago_total_credito pt, ".$db_name.".cuota_credito cc WHERE pt.id = ptxc.id_pago_total_credito AND pt.id_credito = cc.id_credito AND ptxc.id_cuota_credito = ?"))
		{
			$stmt64->bind_param('i', $idCuotaCredito);
			$stmt64->execute();    
			$stmt64->store_result();
			
			$totR64 = $stmt64->num_rows;

			$montoTotalCuoPT = 0;
			if($totR64 > 0)
			{
				$stmt64->bind_result($monto_cuota_original_acum_pago_total_db);
				while($stmt64->fetch())
				{
					$montoTotalCuoPT = $montoTotalCuoPT + $monto_cuota_original_acum_pago_total_db;
				}			
				$stmt64->free_result();
				$stmt64->close();
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}		
		
		if($stmt162 = $mysqli->prepare("SELECT ps.id, c.id FROM ".$db_name.".pago_seleccion_cuotas_credito ps, ".$db_name.".cuota_credito cc, ".$db_name.".credito c WHERE ps.id_cuota_credito = cc.id AND cc.id_credito = c.id AND ps.id_cuota_credito = ?"))
		{
			$stmt162->bind_param('i', $idCuotaCredito);
			$stmt162->execute();    
			$stmt162->store_result();
			
			$totR162 = $stmt162->num_rows;

			if($totR162 > 0)
			{
				$stmt162->bind_result($id_pago_seleccion_cuotas_credito_db, $id_credito_pago_seleccion_db);
				$stmt162->fetch();
								
				$stmt162->free_result();
				$stmt162->close();
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}	
		
		if($totR162 > 0)
		{
			if($stmt164 = $mysqli->prepare("SELECT cc.monto_cuota_original FROM ".$db_name.".cuota_credito cc, ".$db_name.".pago_seleccion_cuotas_credito pscc WHERE cc.id = pscc.id_cuota_credito AND cc.id_credito = ?"))
			{
				$stmt164->bind_param('i', $id_credito_pago_seleccion_db);
				$stmt164->execute();    
				$stmt164->store_result();
				
				$totR164 = $stmt164->num_rows;

				$montoTotalCuoPS = 0;
				if($totR164 > 0)
				{
					$stmt164->bind_result($monto_cuota_original_acum_pago_seleccion_db);
					while($stmt164->fetch())
					{
						$montoTotalCuoPS = $montoTotalCuoPS + $monto_cuota_original_acum_pago_seleccion_db;
					}			
					$stmt164->free_result();
					$stmt164->close();
				}
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;
			}	
		}
		
		if($totR63 > 0 && $totR162 > 0)
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;			
		}
		echo translate('Msg_View_Cancel_Fee_Credit_OK',$GLOBALS['lang']);	
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Lbl_Cancelation_Fee_Credit',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_23"></div>';
		echo '			<form id="formularioccc" role="form">';
		if($totR63 > 0)
		{
			echo '				<div class="alert alert-warning">';
			echo '					<strong>'.translate('Lbl_Warning',$GLOBALS['lang']).'!</strong> '.translate('Msg_Payment_Of_All_Fees_Included_In_The_Total_Payment_Is_Canceled',$GLOBALS['lang']).'.';
			echo '				</div>';
		}
		if($totR162 > 0)
		{
			echo '				<div class="alert alert-warning">';
			echo '					<strong>'.translate('Lbl_Warning',$GLOBALS['lang']).'!</strong> '.translate('Msg_Payment_Of_All_Fees_Included_In_The_Selection_Payment_Is_Canceled',$GLOBALS['lang']).'.';
			echo '				</div>';
		}		
		echo '				<div class="form-group form-inline">';		
		echo '					&nbsp;<label class="control-label" for="nrofeecreditcancel">'.translate('Lbl_Number_Fee_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nrofeecreditcancel">';
		echo '						<input class="form-control input-sm green-border" id="nrofeecreditcanceli" name="nrofeecreditcanceli" type="text" maxlength="11" value="'.$number_fee_credit_client.'" disabled/>';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="amountfeecreditcancel">'.translate('Lbl_Amount_Fee_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="amountfeecreditcancel">';
		echo '						<input class="form-control input-sm green-border" id="amountfeecreditcanceli" name="amountfeecreditcanceli" type="text" maxlength="11" value="'.number_format(($montoCuotaCredito/100.00),2).'" disabled/>';
		echo '					</div>';		
		echo '				</div>';
		if($totR63 > 0)
		{
			echo '				<div class="form-group form-inline">';		
			echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="cantidadfeescreditcancel">'.translate('Lbl_Fees_Print_Credit',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="cantidadfeescreditcancel">';
			echo '						<input class="form-control input-sm green-border" id="cantidadfeescreditcanceli" name="cantidadfeescreditcanceli" type="text" maxlength="11" value="'.$totR64.'" disabled/>';
			echo '					</div>';
			echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="amountfeescreditcancel">'.translate('Lbl_Amount_Original_Fees_Print_Credit',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="amountfeescreditcancel">';
			echo '						<input class="form-control input-sm green-border" id="amountfeescreditcanceli" name="amountfeescreditcanceli" type="text" maxlength="11" value="'.number_format(($montoTotalCuoPT/100.00),2).'" disabled/>';
			echo '					</div>';		
			echo '				</div>';			
		}
		if($totR162 > 0)
		{
			echo '				<div class="form-group form-inline">';		
			echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="cantidadfeescreditcancel">'.translate('Lbl_Fees_Print_Credit',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="cantidadfeescreditcancel">';
			echo '						<input class="form-control input-sm green-border" id="cantidadfeescreditcanceli" name="cantidadfeescreditcanceli" type="text" maxlength="11" value="'.$totR164.'" disabled/>';
			echo '					</div>';
			echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="amountfeescreditcancel">'.translate('Lbl_Amount_Original_Fees_Print_Credit',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="amountfeescreditcancel">';
			echo '						<input class="form-control input-sm green-border" id="amountfeescreditcanceli" name="amountfeescreditcanceli" type="text" maxlength="11" value="'.number_format(($montoTotalCuoPS/100.00),2).'" disabled/>';
			echo '					</div>';		
			echo '				</div>';			
		}		
		echo '				<div class="form-group form-inline">';		
		echo '					<label class="control-label" for="motivocancelfeecredit">'.translate('Lbl_Reason_Cancel_Fee_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="motivocancelfeecredit">';
		echo '						<textarea title="'.translate('Msg_A_Reason_Cancel_Fee_Credit_Must_Enter',$GLOBALS['lang']).'" rows="4" cols="58" class="form-control input-sm" id="motivocancelfeecrediti" name="motivocancelfeecrediti" type="text" maxlength="500"></textarea>';
		echo '					</div>';		
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';				
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarCCC" id="btnCancelarCCC" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogcancelfeecredit\').dialog(\'close\');" style="margin-left:10px;" />';										
		if($totR63 > 0)
		{
			echo '					<input type="button" class="btn btn-primary pull-right" name="btnGuardarCCC" id="btnGuardarCCC" value="'.translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']).'" onClick="confirmar_accion_cancelar_cuota_credito(\''.translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']).'\',\''.translate('Msg_Be_Sure_To_Cancel_Total_Amount_Fees_Debt_The_Credit',$GLOBALS['lang']).'\',document.getElementById(\'formularioccc\'),'.$idCuotaCredito.');"/>';
		}
		else if($totR162 > 0)
		{
			echo '					<input type="button" class="btn btn-primary pull-right" name="btnGuardarCCC" id="btnGuardarCCC" value="'.translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']).'" onClick="confirmar_accion_cancelar_cuota_credito(\''.translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']).'\',\''.translate('Msg_Be_Sure_To_Cancel_Selection_Amount_Fees_Debt_The_Credit',$GLOBALS['lang']).'\',document.getElementById(\'formularioccc\'),'.$idCuotaCredito.');"/>';
		}		
		else
		{
			echo '					<input type="button" class="btn btn-primary pull-right" name="btnGuardarCCC" id="btnGuardarCCC" value="'.translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']).'" onClick="confirmar_accion_cancelar_cuota_credito(\''.translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']).'\',\''.translate('Msg_Be_Sure_To_Cancel_Fee_The_Credit',$GLOBALS['lang']).'\',document.getElementById(\'formularioccc\'),'.$idCuotaCredito.');"/>';
		}
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		return;
?>