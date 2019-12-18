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
		
		$idCredito=htmlspecialchars($_POST["idCredito"], ENT_QUOTES, 'UTF-8');
		
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

		if ($stmt = $mysqli->prepare("SELECT c.id, c.monto_credito_original FROM ".$db_name.".credito c WHERE c.id = ?")) 
		{
			$stmt->bind_param('i', $idCredito);
			$stmt->execute();    
			$stmt->store_result();
	 
			$stmt->bind_result($id_credit_client, $montoTotalCredito);			
			
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_Credit_Client_Not_Exist',$GLOBALS['lang']);
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
						
		echo translate('Msg_View_Cancel_Credit_OK',$GLOBALS['lang']);	
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Lbl_Cancelation_Credit',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_23"></div>';
		echo '			<form id="formulariocc" role="form">';
		echo '				<div class="form-group form-inline">';		
		echo '					&nbsp;<label class="control-label" for="idcreditcancel">'.translate('Lbl_Number_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="idcreditcancel">';
		echo '						<input class="form-control input-sm green-border" id="idcreditcanceli" name="idcreditcanceli" type="text" maxlength="11" value="'.$idCredito.'" disabled/>';
		echo '					</div>';
		echo '					&nbsp;&nbsp;<label class="control-label" for="amountcreditcancel">'.translate('Lbl_Amount_Print_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="amountcreditcancel">';
		echo '						<input class="form-control input-sm green-border" id="amountcreditcanceli" name="amountcreditcanceli" type="text" maxlength="11" value="'.number_format(($montoTotalCredito/100.00),2).'" disabled/>';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';		
		echo '					<label class="control-label" for="motivocancelcredit">'.translate('Lbl_Reason_Cancel_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="motivocancelcredit">';
		echo '						<textarea title="'.translate('Msg_A_Reason_Cancel_Credit_Must_Enter',$GLOBALS['lang']).'" rows="4" cols="58" class="form-control input-sm" id="motivocancelcrediti" name="motivocancelcrediti" type="text" maxlength="500"></textarea>';
		echo '					</div>';		
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';				
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarCC" id="btnCancelarCC" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogcancelcredit\').dialog(\'close\');" style="margin-left:10px;" />';										
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnGuardarCC" id="btnGuardarCC" value="'.translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']).'" onClick="confirmar_accion_cancelar_credito_cliente(\''.translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']).'\',\''.translate('Msg_Be_Sure_To_Cancel_The_Credit',$GLOBALS['lang']).'\',document.getElementById(\'formulariocc\'),'.$idCredito.');"/>';													
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		return;
?>