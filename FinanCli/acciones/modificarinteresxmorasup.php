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
		
		$idInteresXMora=htmlspecialchars($_POST["idInteresXMora"], ENT_QUOTES, 'UTF-8');		
		
		if ($stmt500 = $mysqli->prepare("SELECT c.id FROM ".$db_name.".cadena c, ".$db_name.".usuario u, ".$db_name.".sucursal s WHERE u.id_sucursal = s.id AND s.id_cadena = c.id AND u.id = ?")) 
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
		
		if ($stmt = $mysqli->prepare("SELECT ixm.id, ixm.cantidad_dias, ixm.interes, ixm.id_plan_credito, ixm.recurrente FROM ".$db_name.".interes_x_mora ixm WHERE ixm.id = ?")) 
		{
			$stmt->bind_param('i', $idInteresXMora);
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->store_result();
	 
			// Obtiene las variables del resultado.
			$stmt->bind_result($id_interes_x_mora, $cantidad_dias_interes_x_mora, $interes_x_mora, $plan_credito_interes_x_mora, $recurrente_interes_x_mora);
			
			$stmt->fetch();

			if ($stmt501 = $mysqli->prepare("SELECT pc.id FROM ".$db_name.".plan_credito pc WHERE pc.id = ? AND pc.id_cadena = ?")) 
			{
				$stmt501->bind_param('ii', $plan_credito_interes_x_mora, $id_cadena_user);
				$stmt501->execute();    
				$stmt501->store_result();
		 
				$totR501 = $stmt501->num_rows;
				if($totR501 > 0)
				{
					$stmt501->bind_result($id_sucursal_valid_user);
					$stmt501->fetch();

					$stmt501->free_result();
					$stmt501->close();				
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
						
			echo '<div class="panel-group">';				
			echo '	<div class="panel panel-default">';
			echo '		<div id="panel-title-header" class="panel-heading">';
			echo '			<h3 class="panel-title">'.translate('Lbl_New_Interest_For_Late_Payment',$GLOBALS['lang']).'</h3>';
			echo ' 		</div>';
			echo '		<div class="panel-body">';
			echo '			<div id="img_loader_22"></div>';
			echo '			<form id="formulariompc" role="form">';
			echo '				<div class="form-group form-inline">';
			echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="cantidaddiasinteresxmora">'.translate('Lbl_Amount_Days_Interest_For_Late_Payment',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="cantidaddiasinteresxmora">';
			if($recurrente_interes_x_mora == 0) echo '						<input title="'.translate('Msg_Amount_Days_Interest_For_Late_Payment_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="cantidaddiasinteresxmorai" name="cantidaddiasinteresxmorai" type="text" maxlength="11" value="'.$cantidad_dias_interes_x_mora.'" />&nbsp;&nbsp;<input type="checkbox" class="form-control" id="recurrenciainteresxmorai" name="recurrenciainteresxmorai" style="width:29px;" />';
			else echo '						<input title="'.translate('Msg_Amount_Days_Interest_For_Late_Payment_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="cantidaddiasinteresxmorai" name="cantidaddiasinteresxmorai" type="text" maxlength="11" value="'.$cantidad_dias_interes_x_mora.'" />&nbsp;&nbsp;<input type="checkbox" class="form-control" id="recurrenciainteresxmorai" name="recurrenciainteresxmorai" style="width:29px;" checked />';
			echo '					</div>';
			echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="interesxmora">'.translate('Lbl_Interest_For_Late_Payment',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="interesxmora">';
			echo '						<input title="'.translate('Msg_Interest_For_Late_Payment_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="interesxmorai" name="interesxmorai" type="text" maxlength="11" value="'.$interes_x_mora.'" />';
			echo '					</div>';			
			echo '				</div>';
			echo '				<div class="form-group form-inline">';
			echo '					<label class="control-label" for="plancreditointeresxmora">'.translate('Lbl_Credit_Plan_Interest_For_Late_Payment',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="plancreditointeresxmora">';
			echo '						<select class="form-control input-sm" name="plancreditointeresxmorai" id="plancreditointeresxmorai" style="width:193px;">';			 
											if ($stmt = $mysqli->prepare("SELECT id, nombre FROM ".$db_name.".plan_credito WHERE id_cadena = ?")) 
											{ 
												$stmt->bind_param('i', $id_cadena_user);
												$stmt->execute();    
												$stmt->store_result();
										 
												$stmt->bind_result($id_plan_credito,$nombre_plan_credito);
												while($stmt->fetch())
												{
													if($plan_credito_interes_x_mora == $id_plan_credito) echo '<option selected value="'.$id_plan_credito.'">'.$nombre_plan_credito.'</option>';
													else echo '<option value="'.$id_plan_credito.'">'.$nombre_plan_credito.'</option>';
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
			echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarPC" id="btnCancelarPC" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogmodifyinteresxmora\').dialog(\'close\');" style="margin-left:10px;" />';
			echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargarPC" id="btnCargarPC" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarModificacionInteresXMora(document.getElementById(\'formulariompc\'),\''.$id_interes_x_mora.'\');"/>';										
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