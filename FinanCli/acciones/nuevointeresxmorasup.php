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
		
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Lbl_New_Interest_For_Late_Payment',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_22"></div>';
		echo '			<form id="formularionpc" role="form">';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="cantidaddiasinteresxmoran">'.translate('Lbl_Amount_Days_Interest_For_Late_Payment',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="cantidaddiasinteresxmoran">';
		echo '						<input title="'.translate('Msg_Amount_Days_Interest_For_Late_Payment_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="cantidaddiasinteresxmorani" name="cantidaddiasinteresxmorani" type="text" maxlength="11" />&nbsp;&nbsp;<input type="checkbox" class="form-control" id="recurrenciainteresxmorani" name="recurrenciainteresxmorani" style="width:29px;" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="interesxmoran">'.translate('Lbl_Interest_For_Late_Payment',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="interesxmoran">';
		echo '						<input title="'.translate('Msg_Interest_For_Late_Payment_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="interesxmorani" name="interesxmorani" type="text" maxlength="11" />';
		echo '					</div>';			
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					<label class="control-label" for="plancreditointeresxmoran">'.translate('Lbl_Credit_Plan_Interest_For_Late_Payment',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="plancreditointeresxmoran">';
		echo '						<select class="form-control input-sm" name="plancreditointeresxmorani" id="plancreditointeresxmorani" style="width:193px;">';			 
										if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.plan_credito WHERE id_cadena = ?")) 
										{ 
											$stmt->bind_param('i', $id_cadena_user);
											$stmt->execute();    
											$stmt->store_result();
									 
											$stmt->bind_result($id_plan_credito,$nombre_plan_credito);
											while($stmt->fetch())
											{
												echo '<option value="'.$id_plan_credito.'">'.$nombre_plan_credito.'</option>';
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
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarNPC" id="btnCancelarNPC" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialognewinteresxmora\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargarNPC" id="btnCargarNPC" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarNuevoInteresXMora(document.getElementById(\'formularionpc\'));"/>';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		return;
?>