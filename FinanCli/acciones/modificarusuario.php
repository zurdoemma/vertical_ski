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
		
		$usuario=htmlspecialchars($_POST["usuario"], ENT_QUOTES, 'UTF-8');	

		if($stmt = $mysqli->prepare("SELECT u.id, u.nombre, u.apellido, u.tipo_documento, u.documento, u.email, u.id_perfil, u.id_sucursal, u.clave  FROM finan_cli.usuario u WHERE id LIKE(?)"))
		{
			$stmt->bind_param('s', $usuario);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_User_Not_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{
				$stmt->bind_result($user_id, $user_nombre, $user_apellido, $user_tipo_doc, $user_doc, $user_email, $user_perfil, $user_sucursal, $user_clave);
				$stmt->fetch();
				
				echo '<div class="panel-group">';				
				echo '	<div class="panel panel-default">';
				echo '		<div id="panel-title-header" class="panel-heading">';
				echo '			<h3 class="panel-title">'.translate('Msg_Edit_User',$GLOBALS['lang']).'</h3>';
				echo ' 		</div>';
				echo '		<div class="panel-body">';
				echo '			<div id="img_loader_2"></div>';
				echo '			<form id="formulariomu" role="form">';
				echo '				<div class="form-group form-inline">';
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="iduser">'.translate('Lbl_User',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="iduser">';
				echo '						<input title="'.translate('Msg_A_User_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="iduseri" name="iduseri" type="text" maxlength="50" value="'.$user_id.'" disabled/>';
				echo '					</div>';							
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nameuser">'.translate('Lbl_Name_User',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="nameuser">';
				echo '						<input title="'.translate('Msg_A_User_Name_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nameuseri" name="nameuseri" type="text" maxlength="100" value="'.$user_nombre.'" />';
				echo '					</div>';
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="surnameuser">'.translate('Lbl_Surname_User',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="nameuser">';
				echo '						<input title="'.translate('Msg_A_User_Surname_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="surnameuseri" name="surnameuseri" type="text" maxlength="100" value="'.$user_apellido.'" />';
				echo '					</div>';				
				echo '				</div>';
				echo '				<div class="form-group form-inline">';
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="tipodocuser">'.translate('Lbl_Type_Document_User',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="tipodocuser">';
				echo '						<select class="form-control input-sm" name="tipodocuseri" id="tipodocuseri" style="width:190px;">';			 
												if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.tipo_documento")) 
												{ 
													$stmt->execute();    
													$stmt->store_result();
											 
													$stmt->bind_result($id_tipo_doc,$name_tipo_doc);
													while($stmt->fetch())
													{
														if($id_tipo_doc == $user_tipo_doc)
														{
															echo '<option selected value="'.$id_tipo_doc.'">'.$name_tipo_doc.'</option>';
														}
														else echo '<option value="'.$id_tipo_doc.'">'.$name_tipo_doc.'</option>';
													}
												}
												else  
												{
													echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
													return;			
												}
				echo '						</select>';
				echo '					</div>';							
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="documentuser">'.translate('Lbl_Document_User',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="documentuser">';
				echo '						<input title="'.translate('Msg_A_User_Document_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="documentuseri" name="documentuseri" type="text" maxlength="20" value="'.$user_doc.'" />';
				echo '					</div>';
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="homeaddressuser">'.translate('Lbl_Home_Address_And_Phone',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="homeaddressuser">';
				echo '						<button type="button" class="btn" title="'.translate('Lbl_View_Home_Address',$GLOBALS['lang']).'" onclick="window.open(\'domiciliosusers.php?usuario='.$user_id.'\',\'_blank\');"><i class="fa fa-eye"></i></button>';
				echo '						&nbsp;<button type="button" class="btn" title="'.translate('Lbl_View_Phones',$GLOBALS['lang']).'" onclick="window.open(\'phonesusers.php?usuario='.$user_id.'\',\'_blank\');"><i class="fa fa-phone"></i></button>';
				echo '					</div>';				
				echo '				</div>';
				echo '				<div class="form-group form-inline">';
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="emailuser">'.translate('Lbl_Email_User',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="emailuser">';
				echo '						<input title="'.translate('Msg_A_User_Email_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="emailuseri" name="emailuseri" type="text" maxlength="250" value="'.$user_email.'" />';
				echo '					</div>';				
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="perfiluser">'.translate('Lbl_Perfil_User',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="perfiluser">';
				echo '						<select class="form-control input-sm" name="perfiluseri" id="perfiluseri" style="width:190px;">';			 
												if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.perfil")) 
												{ 
													$stmt->execute();    
													$stmt->store_result();
											 
													$stmt->bind_result($id_perfil,$name_perfil);
													while($stmt->fetch())
													{
														if($id_perfil == $user_perfil)
														{
															echo '<option selected value="'.$id_perfil.'">'.$name_perfil.'</option>';
														}
														else echo '<option value="'.$id_perfil.'">'.$name_perfil.'</option>';
													}
												}
												else  
												{
													echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
													return;			
												}
				echo '						</select>';
				echo '					</div>';							
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="sucursaluser">'.translate('Lbl_Tender_User',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="sucursaluser">';
				echo '						<select class="form-control input-sm" name="sucursaluseri" id="sucursaluseri" style="width:190px;">';			 
												if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.sucursal")) 
												{ 
													$stmt->execute();    
													$stmt->store_result();
											 
													$stmt->bind_result($id_sucursal,$name_sucursal);
													while($stmt->fetch())
													{
														if($id_sucursal == $user_sucursal)
														{
															echo '<option selected value="'.$id_sucursal.'">'.$name_sucursal.'</option>';
														}
														else echo '<option value="'.$id_sucursal.'">'.$name_sucursal.'</option>';
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
				echo '					<label class="control-label" for="claveactualuser">'.translate('Lbl_Current_Password',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="claveactualuser">';
				echo '						<input class="form-control input-sm" id="claveactualuseri" name="claveactualuseri" type="password" maxlength="128" disabled />';
				echo '					</div>';				
				echo '					&nbsp;&nbsp;<label class="control-label" for="claveuser">'.translate('Lbl_New_Password',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="claveuser">';
				echo '						<input title="'.translate('Msg_A_User_New_Password_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="claveuseri" name="claveuseri" type="password" maxlength="128" />';
				echo '					</div>';
				echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="rclaveuser">'.translate('Lbl_Repeat_Password',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="rclaveuser">';
				echo '						<input title="'.translate('Msg_A_User_Confirm_New_Password_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="rclaveuseri" name="rclaveuseri" type="password" maxlength="128" />';
				echo '					</div>';				
				echo '				</div>';
				if($user_perfil == 2)
				{
					if ($stmt59 = $mysqli->prepare("SELECT horario_ingreso, horario_salida, lunes, martes, miercoles, jueves, viernes, sabado, domingo, cambio_dia FROM finan_cli.horario_laboral_x_usuario WHERE id_usuario = ?")) 
					{ 
						$stmt59->bind_param('s', $usuario);
						$stmt59->execute();    
						$stmt59->store_result();
				 
						$totR59 = $stmt59->num_rows;

						if($totR59 > 0)
						{				 
							$stmt59->bind_result($horario_ingreso_usuario_db, $horario_egreso_usuario_db, $trabaja_lunes_usuario_db, $trabaja_martes_usuario_db, $trabaja_miercoles_usuario_db, $trabaja_jueves_usuario_db, $trabaja_viernes_usuario_db, $trabaja_sabado_usuario_db, $trabaja_domingo_usuario_db, $cambio_dia_usuario_db);
							$stmt59->fetch();
							
							$stmt59->free_result();
							$stmt59->close();	
						}
					}
					else  
					{
						echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
						return;			
					}					
					
					if($totR59 > 0)
					{
						$horaIngreso = substr($horario_ingreso_usuario_db, 8, 2).':'.substr($horario_ingreso_usuario_db, 10, 2);
						$horaEgreso = substr($horario_egreso_usuario_db, 8, 2).':'.substr($horario_egreso_usuario_db, 10, 2);
						
						echo '				<div class="form-group form-inline" id="horariolaboralusuario" name="horariolaboralusuario" ><hr />';
						echo '					&nbsp;<label class="control-label" for="horarioingresouser">'.translate('Lbl_Entry_Time_User',$GLOBALS['lang']).':</label>';
						echo '					<div class="form-group" id="horarioingresouser">';
						echo '						<div class="input-group date" id="datetimepickerhorarioingresouser">';		
						echo '							<input title="'.translate('Msg_You_Must_Enter_The_User_Entry_Time',$GLOBALS['lang']).'" class="form-control input-sm" id="horarioingresouseri" name="horarioingresouseri" type="text" maxlength="8" placeholder="'.translate('Lbl_Format_Hour_Entry_And_Departure_User',$GLOBALS['lang']).'"  style="width: 132px;" value="'.$horaIngreso.'" />';
						echo '							<span class="input-group-addon">';
						echo '								<span class="glyphicon glyphicon-time"></span>';
						echo '							</span>';		
						echo '					    </div>';
						echo '					</div>';
						echo '					&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="horarioegresouser">'.translate('Lbl_Departure_Time_User',$GLOBALS['lang']).':</label>';
						echo '					<div class="form-group" id="horarioegresouser">';
						echo '						<div class="input-group date" id="datetimepickerhorarioegresouser">';		
						echo '							<input title="'.translate('Msg_You_Must_Enter_The_User_Departure_Time',$GLOBALS['lang']).'" class="form-control input-sm" id="horarioegresouseri" name="horarioegresouseri" type="text" maxlength="8" placeholder="'.translate('Lbl_Format_Hour_Entry_And_Departure_User',$GLOBALS['lang']).'"  style="width: 126px;" value="'.$horaEgreso.'" />';
						echo '							<span class="input-group-addon">';
						echo '								<span class="glyphicon glyphicon-time"></span>';
						echo '							</span>';		
						echo '					    </div>';
						echo '					</div>';
						echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="validarcambiodedia">'.translate('Msg_Change_Day_Time_Work',$GLOBALS['lang']).':</label>';			
						echo '					<div class="form-group" id="validarcambiodedia">';	
						echo '						<label class="switch">';
						if($cambio_dia_usuario_db == 1) echo '						  <input type="checkbox" id="validarcambiodediai" name="validarcambiodediai" checked />';
						else echo '						  <input type="checkbox" id="validarcambiodediai" name="validarcambiodediai" />';
						echo '						  <span class="slider round"></span>';
						echo '						</label>';
						echo '					</div>';						
						echo '				</div>';
						echo '				<div class="form-group form-inline" id="diaslaboralesusuario" name="diaslaboralesusuario" >';				
						echo '				  <div class="form-group" id="luneshorariolaboraluser">';
						if($trabaja_lunes_usuario_db == 1)	echo '					  &nbsp;<input class="form-control input-sm" id="luneshorariolaboraluseri" name="luneshorariolaboraluseri" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="luneshorariolaboraluser">'.translate('Lbl_Day_Monday_Check_Work_User',$GLOBALS['lang']).'</label>';
						else echo '					  &nbsp;<input class="form-control input-sm" id="luneshorariolaboraluseri" name="luneshorariolaboraluseri" type="checkbox" style="width:20px;" />&nbsp;<label class="control-label" for="luneshorariolaboraluser">'.translate('Lbl_Day_Monday_Check_Work_User',$GLOBALS['lang']).'</label>';
						echo '				  </div>';
						echo '				  <div class="form-group" id="marteshorariolaboraluser">';
						if($trabaja_martes_usuario_db == 1) echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="marteshorariolaboraluseri" name="marteshorariolaboraluseri" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="marteshorariolaboraluser">'.translate('Lbl_Day_Thursday_Check_Work_User',$GLOBALS['lang']).'</label>';
						else echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="marteshorariolaboraluseri" name="marteshorariolaboraluseri" type="checkbox" style="width:20px;" />&nbsp;<label class="control-label" for="marteshorariolaboraluser">'.translate('Lbl_Day_Thursday_Check_Work_User',$GLOBALS['lang']).'</label>';
						echo '				  </div>';	
						echo '				  <div class="form-group" id="miercoleshorariolaboraluser">';
						if($trabaja_miercoles_usuario_db == 1) echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="miercoleshorariolaboraluseri" name="miercoleshorariolaboraluseri" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="miercoleshorariolaboraluser">'.translate('Lbl_Day_Wednesday_Check_Work_User',$GLOBALS['lang']).'</label>';
						else echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="miercoleshorariolaboraluseri" name="miercoleshorariolaboraluseri" type="checkbox" style="width:20px;" />&nbsp;<label class="control-label" for="miercoleshorariolaboraluser">'.translate('Lbl_Day_Wednesday_Check_Work_User',$GLOBALS['lang']).'</label>';
						echo '				  </div>';
						echo '				  <div class="form-group" id="jueveshorariolaboraluser">';
						if($trabaja_jueves_usuario_db == 1) echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="jueveshorariolaboraluseri" name="jueveshorariolaboraluseri" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="jueveshorariolaboraluser">'.translate('Lbl_Day_Tuesday_Check_Work_User',$GLOBALS['lang']).'</label>';
						else echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="jueveshorariolaboraluseri" name="jueveshorariolaboraluseri" type="checkbox" style="width:20px;" />&nbsp;<label class="control-label" for="jueveshorariolaboraluser">'.translate('Lbl_Day_Tuesday_Check_Work_User',$GLOBALS['lang']).'</label>';
						echo '				  </div>';
						echo '				  <div class="form-group" id="vierneshorariolaboraluser">';
						if($trabaja_viernes_usuario_db == 1) echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="vierneshorariolaboraluseri" name="vierneshorariolaboraluseri" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="vierneshorariolaboraluser">'.translate('Lbl_Day_Friday_Check_Work_User',$GLOBALS['lang']).'</label>';
						else echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="vierneshorariolaboraluseri" name="vierneshorariolaboraluseri" type="checkbox" style="width:20px;" />&nbsp;<label class="control-label" for="vierneshorariolaboraluser">'.translate('Lbl_Day_Friday_Check_Work_User',$GLOBALS['lang']).'</label>';
						echo '				  </div>';	
						echo '				  <div class="form-group" id="sabadohorariolaboraluser">';
						if($trabaja_sabado_usuario_db == 1) echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="sabadohorariolaboraluseri" name="sabadohorariolaboraluseri" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="sabadohorariolaboraluser">'.translate('Lbl_Day_Saturday_Check_Work_User',$GLOBALS['lang']).'</label>';
						else echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="sabadohorariolaboraluseri" name="sabadohorariolaboraluseri" type="checkbox" style="width:20px;" />&nbsp;<label class="control-label" for="sabadohorariolaboraluser">'.translate('Lbl_Day_Saturday_Check_Work_User',$GLOBALS['lang']).'</label>';
						echo '				  </div>';
						echo '				  <div class="form-group" id="domingohorariolaboraluser">';
						if($trabaja_domingo_usuario_db == 1) echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="domingohorariolaboraluseri" name="domingohorariolaboraluseri" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="domingohorariolaboraluser">'.translate('Lbl_Day_Sunday_Check_Work_User',$GLOBALS['lang']).'</label>';
						else echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="domingohorariolaboraluseri" name="domingohorariolaboraluseri" type="checkbox" style="width:20px;" />&nbsp;<label class="control-label" for="domingohorariolaboraluser">'.translate('Lbl_Day_Sunday_Check_Work_User',$GLOBALS['lang']).'</label>';
						echo '				  </div>';		
						echo '				</div>';						
					}
					else
					{
						echo '				<div class="form-group form-inline" id="horariolaboralusuario" name="horariolaboralusuario" ><hr />';
						echo '					&nbsp;<label class="control-label" for="horarioingresouser">'.translate('Lbl_Entry_Time_User',$GLOBALS['lang']).':</label>';
						echo '					<div class="form-group" id="horarioingresouser">';
						echo '						<div class="input-group date" id="datetimepickerhorarioingresouser">';		
						echo '							<input title="'.translate('Msg_You_Must_Enter_The_User_Entry_Time',$GLOBALS['lang']).'" class="form-control input-sm" id="horarioingresouseri" name="horarioingresouseri" type="text" maxlength="8" placeholder="'.translate('Lbl_Format_Hour_Entry_And_Departure_User',$GLOBALS['lang']).'"  style="width: 132px;" />';
						echo '							<span class="input-group-addon">';
						echo '								<span class="glyphicon glyphicon-time"></span>';
						echo '							</span>';		
						echo '					    </div>';
						echo '					</div>';
						echo '					&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="horarioegresouser">'.translate('Lbl_Departure_Time_User',$GLOBALS['lang']).':</label>';
						echo '					<div class="form-group" id="horarioegresouser">';
						echo '						<div class="input-group date" id="datetimepickerhorarioegresouser">';		
						echo '							<input title="'.translate('Msg_You_Must_Enter_The_User_Departure_Time',$GLOBALS['lang']).'" class="form-control input-sm" id="horarioegresouseri" name="horarioegresouseri" type="text" maxlength="8" placeholder="'.translate('Lbl_Format_Hour_Entry_And_Departure_User',$GLOBALS['lang']).'"  style="width: 126px;" />';
						echo '							<span class="input-group-addon">';
						echo '								<span class="glyphicon glyphicon-time"></span>';
						echo '							</span>';		
						echo '					    </div>';
						echo '					</div>';				
						echo '				</div>';
						echo '				<div class="form-group form-inline" id="diaslaboralesusuario" name="diaslaboralesusuario" >';				
						echo '				  <div class="form-group" id="luneshorariolaboraluser">';
						echo '					  &nbsp;<input class="form-control input-sm" id="luneshorariolaboraluseri" name="luneshorariolaboraluseri" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="luneshorariolaboraluser">'.translate('Lbl_Day_Monday_Check_Work_User',$GLOBALS['lang']).'</label>';
						echo '				  </div>';
						echo '				  <div class="form-group" id="marteshorariolaboraluser">';
						echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="marteshorariolaboraluseri" name="marteshorariolaboraluseri" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="marteshorariolaboraluser">'.translate('Lbl_Day_Thursday_Check_Work_User',$GLOBALS['lang']).'</label>';
						echo '				  </div>';	
						echo '				  <div class="form-group" id="miercoleshorariolaboraluser">';
						echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="miercoleshorariolaboraluseri" name="miercoleshorariolaboraluseri" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="miercoleshorariolaboraluser">'.translate('Lbl_Day_Wednesday_Check_Work_User',$GLOBALS['lang']).'</label>';
						echo '				  </div>';
						echo '				  <div class="form-group" id="jueveshorariolaboraluser">';
						echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="jueveshorariolaboraluseri" name="jueveshorariolaboraluseri" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="jueveshorariolaboraluser">'.translate('Lbl_Day_Tuesday_Check_Work_User',$GLOBALS['lang']).'</label>';
						echo '				  </div>';
						echo '				  <div class="form-group" id="vierneshorariolaboraluser">';
						echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="vierneshorariolaboraluseri" name="vierneshorariolaboraluseri" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="vierneshorariolaboraluser">'.translate('Lbl_Day_Friday_Check_Work_User',$GLOBALS['lang']).'</label>';
						echo '				  </div>';	
						echo '				  <div class="form-group" id="sabadohorariolaboraluser">';
						echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="sabadohorariolaboraluseri" name="sabadohorariolaboraluseri" type="checkbox" style="width:20px;"/>&nbsp;<label class="control-label" for="sabadohorariolaboraluser">'.translate('Lbl_Day_Saturday_Check_Work_User',$GLOBALS['lang']).'</label>';
						echo '				  </div>';
						echo '				  <div class="form-group" id="domingohorariolaboraluser">';
						echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="domingohorariolaboraluseri" name="domingohorariolaboraluseri" type="checkbox" style="width:20px;"/>&nbsp;<label class="control-label" for="domingohorariolaboraluser">'.translate('Lbl_Day_Sunday_Check_Work_User',$GLOBALS['lang']).'</label>';
						echo '				  </div>';		
						echo '				</div>';
					}
				}
				else
				{
					echo '				<div class="form-group form-inline" id="horariolaboralusuario" name="horariolaboralusuario" style="display:none;" ><hr />';
					echo '					&nbsp;<label class="control-label" for="horarioingresouser">'.translate('Lbl_Entry_Time_User',$GLOBALS['lang']).':</label>';
					echo '					<div class="form-group" id="horarioingresouser">';
					echo '						<div class="input-group date" id="datetimepickerhorarioingresouser">';		
					echo '							<input title="'.translate('Msg_You_Must_Enter_The_User_Entry_Time',$GLOBALS['lang']).'" class="form-control input-sm" id="horarioingresouseri" name="horarioingresouseri" type="text" maxlength="8" placeholder="'.translate('Lbl_Format_Hour_Entry_And_Departure_User',$GLOBALS['lang']).'"  style="width: 132px;" />';
					echo '							<span class="input-group-addon">';
					echo '								<span class="glyphicon glyphicon-time"></span>';
					echo '							</span>';		
					echo '					    </div>';
					echo '					</div>';
					echo '					&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="horarioegresouser">'.translate('Lbl_Departure_Time_User',$GLOBALS['lang']).':</label>';
					echo '					<div class="form-group" id="horarioegresouser">';
					echo '						<div class="input-group date" id="datetimepickerhorarioegresouser">';		
					echo '							<input title="'.translate('Msg_You_Must_Enter_The_User_Departure_Time',$GLOBALS['lang']).'" class="form-control input-sm" id="horarioegresouseri" name="horarioegresouseri" type="text" maxlength="8" placeholder="'.translate('Lbl_Format_Hour_Entry_And_Departure_User',$GLOBALS['lang']).'"  style="width: 126px;" />';
					echo '							<span class="input-group-addon">';
					echo '								<span class="glyphicon glyphicon-time"></span>';
					echo '							</span>';		
					echo '					    </div>';
					echo '					</div>';				
					echo '				</div>';
					echo '				<div class="form-group form-inline" id="diaslaboralesusuario" name="diaslaboralesusuario" style="display:none;" >';				
					echo '				  <div class="form-group" id="luneshorariolaboraluser">';
					echo '					  &nbsp;<input class="form-control input-sm" id="luneshorariolaboraluseri" name="luneshorariolaboraluseri" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="luneshorariolaboraluser">'.translate('Lbl_Day_Monday_Check_Work_User',$GLOBALS['lang']).'</label>';
					echo '				  </div>';
					echo '				  <div class="form-group" id="marteshorariolaboraluser">';
					echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="marteshorariolaboraluseri" name="marteshorariolaboraluseri" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="marteshorariolaboraluser">'.translate('Lbl_Day_Thursday_Check_Work_User',$GLOBALS['lang']).'</label>';
					echo '				  </div>';	
					echo '				  <div class="form-group" id="miercoleshorariolaboraluser">';
					echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="miercoleshorariolaboraluseri" name="miercoleshorariolaboraluseri" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="miercoleshorariolaboraluser">'.translate('Lbl_Day_Wednesday_Check_Work_User',$GLOBALS['lang']).'</label>';
					echo '				  </div>';
					echo '				  <div class="form-group" id="jueveshorariolaboraluser">';
					echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="jueveshorariolaboraluseri" name="jueveshorariolaboraluseri" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="jueveshorariolaboraluser">'.translate('Lbl_Day_Tuesday_Check_Work_User',$GLOBALS['lang']).'</label>';
					echo '				  </div>';
					echo '				  <div class="form-group" id="vierneshorariolaboraluser">';
					echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="vierneshorariolaboraluseri" name="vierneshorariolaboraluseri" type="checkbox" style="width:20px;" checked />&nbsp;<label class="control-label" for="vierneshorariolaboraluser">'.translate('Lbl_Day_Friday_Check_Work_User',$GLOBALS['lang']).'</label>';
					echo '				  </div>';	
					echo '				  <div class="form-group" id="sabadohorariolaboraluser">';
					echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="sabadohorariolaboraluseri" name="sabadohorariolaboraluseri" type="checkbox" style="width:20px;"/>&nbsp;<label class="control-label" for="sabadohorariolaboraluser">'.translate('Lbl_Day_Saturday_Check_Work_User',$GLOBALS['lang']).'</label>';
					echo '				  </div>';
					echo '				  <div class="form-group" id="domingohorariolaboraluser">';
					echo '					  &nbsp;&nbsp;&nbsp;<input class="form-control input-sm" id="domingohorariolaboraluseri" name="domingohorariolaboraluseri" type="checkbox" style="width:20px;"/>&nbsp;<label class="control-label" for="domingohorariolaboraluser">'.translate('Lbl_Day_Sunday_Check_Work_User',$GLOBALS['lang']).'</label>';
					echo '				  </div>';		
					echo '				</div>';					
				}				
				echo '				<div class="form-group form-inline">';				
				echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelar" id="btnCancelar" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogmoduser\').dialog(\'close\');" style="margin-left:10px;" />';
				echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargar" id="btnCargar" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarModificacionUsuario(document.getElementById(\'formulariomu\'));"/>';										
				echo '				</div>';				
				echo '			</form>';
				echo '		</div>';
				echo '	</div>';
				echo '</div>';
				return;
			}

		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}
?>