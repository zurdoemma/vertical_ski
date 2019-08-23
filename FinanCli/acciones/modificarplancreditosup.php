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
		
		$idPlanCredito=htmlspecialchars($_POST["idPlanCredito"], ENT_QUOTES, 'UTF-8');
		
		if ($stmt500 = $mysqli->prepare("SELECT c.id FROM finan_cli.cadena c, finan_cli.usuario u, finan_cli.sucursal s WHERE u.id_sucursal = s.id AND s.id_cadena = c.id AND u.id = ?")) 
		{
			$stmt500->bind_param('s', $_SESSION['username']);
			$stmt500->execute();    
			$stmt500->store_result();
	 
			$totR500 = $stmt500->num_rows;
			if($totR500 > 0)
			{
				$stmt500->bind_result($id_cadena_user);
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
		
		if ($stmt = $mysqli->prepare("SELECT pc.id, pc.nombre, pc.descripcion, pc.cantidad_cuotas, pc.interes_fijo, pc.id_tipo_diferimiento_cuota, pc.id_cadena FROM finan_cli.plan_credito pc WHERE pc.id = ?")) 
		{
			$stmt->bind_param('i', $idPlanCredito);
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->store_result();
	 
			// Obtiene las variables del resultado.
			$stmt->bind_result($id_credit_plan, $name_credit_plan, $description_credit_plan, $cantidad_cuotas_credit_plan, $interes_fijo_credit_plan, $diferimiento_cuota_credit_plan, $cadena_credit_plan);
			
			$stmt->fetch();
					
			echo '<div class="panel-group">';				
			echo '	<div class="panel panel-default">';
			echo '		<div id="panel-title-header" class="panel-heading">';
			echo '			<h3 class="panel-title">'.translate('Msg_Edit_Credit_Plan',$GLOBALS['lang']).'</h3>';
			echo ' 		</div>';
			echo '		<div class="panel-body">';
			echo '			<div id="img_loader_11"></div>';
			echo '			<form id="formularionpc" role="form">';
			echo '				<div class="form-group form-inline">';
			echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nombreplancredit">'.translate('Lbl_Name_Credit_Plan',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="nombreplancredit">';
			echo '						<input title="'.translate('Msg_A_Name_Profile_Credit_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nombreplancrediti" name="nombreplancrediti" type="text" maxlength="150" value="'.$name_credit_plan.'" />';
			echo '					</div>';
			echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="cantidadcuotasplancredit">'.translate('Lbl_Amount_Fees_Credit_Plan',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="cantidadcuotasplancredit">';
			echo '						<input title="'.translate('Msg_A_Amount_Limit_Profile_Credit_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="cantidadcuotasplancrediti" name="cantidadcuotasplancrediti" type="text" maxlength="11" value="'.$cantidad_cuotas_credit_plan.'" />';
			echo '					</div>';			
			echo '				</div>';
			echo '				<div class="form-group form-inline">';
			echo '					&nbsp;<label class="control-label" for="interesfijoplancredit">'.translate('Lbl_Fixed_Interest_Credit_Plan',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="interesfijoplancredit">';
			echo '						<input title="'.translate('Msg_A_Amount_Limit_Profile_Credit_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="interesfijoplancrediti" name="interesfijoplancrediti" type="text" maxlength="11" value="'.$interes_fijo_credit_plan.'" />';
			echo '					</div>';
			echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="tipodiferimientocuotasplancredit">'.translate('Lbl_Deferred_Installment_Credit_Plan',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="tipodiferimientocuotasplancredit">';
			echo '						<select class="form-control input-sm" name="tipodiferimientocuotasplancrediti" id="tipodiferimientocuotasplancrediti" style="width:190px;">';			 
											if ($stmt = $mysqli->prepare("SELECT id, valor FROM finan_cli.parametros WHERE nombre LIKE 'tipo_diferimiento_cuota_%'")) 
											{ 
												$stmt->execute();    
												$stmt->store_result();
										 
												$stmt->bind_result($id_tipo_diferimiento_cuota,$tipo_diferimiento_cuota);
												while($stmt->fetch())
												{
													if($id_tipo_diferimiento_cuota == $diferimiento_cuota_credit_plan) echo '<option selected value="'.$id_tipo_diferimiento_cuota.'">'.$tipo_diferimiento_cuota.'</option>';
													else echo '<option value="'.$id_tipo_diferimiento_cuota.'">'.$tipo_diferimiento_cuota.'</option>';
												}
											}
											else  
											{
												echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
												return;			
											}
			echo '						</select>';
			echo '					</div>';		
			echo '				</div>';		
			echo '				<div class="form-group form-inline">';
			echo '					<label class="control-label" for="descripcionplancredit">'.translate('Lbl_Description_Credit_Plan',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="descripcionplancredit">';
			echo '						<textarea rows="5" cols="70" title="'.translate('Msg_A_Description_Profile_Credit_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="descripcionplancrediti" name="descripcionplancrediti" type="text" maxlength="500">'.$description_credit_plan.'</textarea>';
			echo '					</div>';		
			echo '				</div>';
			echo '				<div class="form-group form-inline">';
			echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="cadenaplancredit">'.translate('Lbl_Chain_Credit_Plan',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="cadenaplancredit">';
			echo '						<select class="form-control input-sm" name="cadenaplancrediti" id="cadenaplancrediti" style="width:190px;" disabled >';			 
											if ($stmt = $mysqli->prepare("SELECT id, razon_social FROM finan_cli.cadena WHERE id = ?")) 
											{ 
												$stmt->bind_param('i', $id_cadena_user);
												$stmt->execute();    
												$stmt->store_result();
										 
												$stmt->bind_result($id_cadena,$razon_social_cadena);
												while($stmt->fetch())
												{
													if($cadena_credit_plan == $id_cadena) echo '<option selected value="'.$id_cadena.'">'.$razon_social_cadena.'</option>';
													else echo '<option value="'.$id_cadena.'">'.$razon_social_cadena.'</option>';
												}
											}
											else  
											{
												echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
												return;			
											}
			echo '						</select>';
			echo '					</div>';		
			echo '				</div>';		
			echo '				<div class="form-group form-inline">';				
			echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarPC" id="btnCancelarPC" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogmodifycreditplan\').dialog(\'close\');" style="margin-left:10px;" />';
			echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargarPC" id="btnCargarPC" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarModificacionPlanCredito(document.getElementById(\'formularionpc\'),\''.$idPlanCredito.'\');"/>';										
			echo '				</div>';				
			echo '			</form>';
			echo '		</div>';
			echo '	</div>';
			echo '</div>';
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}
		
		return;
?>