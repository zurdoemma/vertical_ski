<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');return;}
		if (!verificar_permisos_admin()){header('Location:../sinautorizacion.php?activauto=1');return;}

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
		
		if ($stmt = $mysqli->prepare("SELECT ixm.id, ixm.cantidad_dias, ixm.interes, ixm.id_plan_credito FROM finan_cli.interes_x_mora ixm WHERE ixm.id = ?")) 
		{
			$stmt->bind_param('i', $idInteresXMora);
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->store_result();
	 
			// Obtiene las variables del resultado.
			$stmt->bind_result($id_interes_x_mora, $cantidad_dias_interes_x_mora, $interes_x_mora, $plan_credito_interes_x_mora);
			
			$stmt->fetch();		
						
			echo '<div class="panel-group">';				
			echo '	<div class="panel panel-default">';
			echo '		<div id="panel-title-header" class="panel-heading">';
			echo '			<h3 class="panel-title">'.translate('Lbl_New_Interest_For_Late_Payment',$GLOBALS['lang']).'</h3>';
			echo ' 		</div>';
			echo '		<div class="panel-body">';
			echo '			<div id="img_loader_11"></div>';
			echo '			<form id="formulariompc" role="form">';
			echo '				<div class="form-group form-inline">';
			echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="cantidaddiasinteresxmora">'.translate('Lbl_Amount_Days_Interest_For_Late_Payment',$GLOBALS['lang']).':</label>';
			echo '					<div class="form-group" id="cantidaddiasinteresxmora">';
			echo '						<input title="'.translate('Msg_Amount_Days_Interest_For_Late_Payment_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="cantidaddiasinteresxmorai" name="cantidaddiasinteresxmorai" type="text" maxlength="11" value="'.$cantidad_dias_interes_x_mora.'" />';
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
											if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.plan_credito")) 
											{ 
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