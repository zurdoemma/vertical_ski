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
		echo '			<h3 class="panel-title">'.translate('Lbl_New_Tender',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_5"></div>';
		echo '			<form id="formularionu" role="form">';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nombretendern">'.translate('Lbl_Name_Tender',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nombretendern">';
		echo '						<input title="'.translate('Msg_A_Name_Tender_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nombretenderni" name="nombretenderni" type="text" maxlength="150" />';
		echo '					</div>';							
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="codigotendern">'.translate('Lbl_Code_Tender',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="codigotendern">';
		echo '						<input title="'.translate('Msg_A_Code_Tender_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="codigotenderni" name="codigotenderni" type="text" maxlength="11" />';
		echo '					</div>';				
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="emailtendern">'.translate('Lbl_Email_Tender',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="emailtendern">';
		echo '						<input title="'.translate('Msg_A_Tender_Email_Invalid',$GLOBALS['lang']).'" class="form-control input-sm" id="emailtenderni" name="emailtenderni" type="text" maxlength="250" />';
		echo '					</div>';		
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="cadenatendern">'.translate('Lbl_Chain_Tender',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="cadenatendern">';
		echo '						<select class="form-control input-sm" name="cadenatenderni" id="cadenatenderni" style="width:190px;" disabled>';			 
										if ($stmt = $mysqli->prepare("SELECT id, razon_social FROM finan_cli.cadena WHERE id = ?")) 
										{ 
											$stmt->bind_param('i', $id_cadena_user);
											$stmt->execute();    
											$stmt->store_result();
									 
											$stmt->bind_result($id_cadena,$razon_social_cadena);
											while($stmt->fetch())
											{
												echo '<option value="'.$id_cadena.'">'.$razon_social_cadena.'</option>';
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
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="homeaddressusern">'.translate('Lbl_Home_Address',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="homeaddressusern">';
		echo '						<button type="button" class="btn" id="btnCargaDomicilioUN" name="btnCargaDomicilioUN" title="'.translate('Lbl_New_Home_Address_User',$GLOBALS['lang']).'" onclick="verDomicilioNU();"><i class="fa fa-eye"></i></button>';
		echo '					</div>';
		echo '				</div>';		
		echo '              <div id="mostrarDomicilioCargaN" style="display:none;"><hr />';
		echo '				  <div class="form-group form-inline">';
		echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="callen">'.translate('Lbl_Street',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="callen">';
		echo '						  <input title="'.translate('Msg_A_Street_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="calleni" name="calleni" type="text" maxlength="150" />';
		echo '					  </div>';							
		echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nrocallen">'.translate('Lbl_Number_Street',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="nrocallen">';
		echo '						  <input title="'.translate('Msg_A_Street_Number_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nrocalleni" name="nrocalleni" type="text" maxlength="11" />';
		echo '					  </div>';		
		echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domprovincian">'.translate('Lbl_State',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="domprovincian">';
		echo '						  <select class="form-control input-sm" name="domprovinciani" id="domprovinciani" style="width:190px;">';			 
										  if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.provincia")) 
										  { 
											  $stmt->execute();    
											  $stmt->store_result();
									 
											  $stmt->bind_result($id_provincia,$provincia);
											  while($stmt->fetch())
											  {
												  echo '<option value="'.$id_provincia.'">'.$provincia.'</option>';
											  }
										  }
										  else  
										  {
											  echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
											  return;			
										  }
		echo '						  </select>';
		echo '					  </div>';				
		echo '				  </div>';
		echo '				  <div class="form-group form-inline">';
		echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domlocalidadn">'.translate('Lbl_City',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="domlocalidadn">';
		echo '						  <input title="'.translate('Msg_A_City_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="domlocalidadni" name="domlocalidadni" type="text" maxlength="150" />';
		echo '					  </div>';							
		echo '					  &nbsp;&nbsp;<label class="control-label" for="domdepartamenton">'.translate('Lbl_Departament',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="domdepartamenton">';
		echo '						  <input class="form-control input-sm" id="domdepartamentoni" name="domdepartamentoni" type="text" maxlength="10" />';
		echo '					  </div>';
		echo '					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="domfloorn">'.translate('Lbl_Floor',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="domfloorn">';
		echo '						  <input title="'.translate('Msg_A_Floor_Number_Must_Enter_A_Whole',$GLOBALS['lang']).'" class="form-control input-sm" id="domfloorni" name="domfloorni" type="text" maxlength="11" />';
		echo '					  </div>';				
		echo '				  </div>';
		echo '				  <div class="form-group form-inline">';
		echo '					  <label class="control-label" for="zipcoden">'.translate('Lbl_Zip_Code',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="zipcoden">';
		echo '						  <input class="form-control input-sm" id="zipcodeni" name="zipcodeni" type="text" maxlength="10" />';
		echo '					  </div>';				
		echo '					  &nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="entrecalle1n">'.translate('Msg_Between_Street_1',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="entrecalle1n">';
		echo '						  <input class="form-control input-sm" id="entrecalle1ni" name="entrecalle1ni" type="text" maxlength="150" />';
		echo '					  </div>';							
		echo '					  &nbsp;&nbsp;&nbsp;<label class="control-label" for="entrecalle2n">'.translate('Msg_Between_Street_2',$GLOBALS['lang']).':</label>';
		echo '					  <div class="form-group" id="entrecalle2n">';
		echo '						  <input class="form-control input-sm" id="entrecalle2ni" name="entrecalle2ni" type="text" maxlength="150" />';
		echo '					  </div>';				
		echo '				  </div>';		
		echo '              </div>';		
		echo '				<div class="form-group form-inline">';				
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarNS" id="btnCancelarNS" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialognewtender\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargarNS" id="btnCargarNS" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarNuevaSucursal(document.getElementById(\'formularionu\'));"/>';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		return;
?>