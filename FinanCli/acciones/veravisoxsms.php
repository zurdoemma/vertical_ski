<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');return;}
		if (!verificar_permisos_usuario()){header('Location:../sinautorizacion.php?activauto=1');return;}

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
		
		$idAvisoXMora=htmlspecialchars($_POST["idAvisoXMora"], ENT_QUOTES, 'UTF-8');
		
		if ($stmt = $mysqli->prepare("SELECT e.fecha, t.numero, e.cantidad_reintentos, e.estado, e.comentario FROM finan_cli.envio_sms e, finan_cli.telefono t WHERE e.id_telefono = t.id AND e.id_aviso_x_mora = ?")) 
		{
			$stmt->bind_param('i', $idAvisoXMora);
			$stmt->execute();    
			$stmt->store_result();
	 
			$stmt->bind_result($date_default_notice_sms, $numero_telefono_default_notice_sms, $reintentos_default_notice_sms, $estado_default_notice_sms, $comentario_default_notice_sms);			
			
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_Default_Notice_SMS_Not_Exist',$GLOBALS['lang']);
				return;	
			}					
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;	
		}
					
		echo translate('Msg_Search_Default_Notices_SMS_OK',$GLOBALS['lang']);	
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Lbl_View_Data_Default_Notice_SMS',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_16"></div>';
		echo '			<form id="formularioiaxmsms" role="form">';			
		echo '				<div class="form-group form-inline">';
		echo '						<div id="tablesendmsgsmsv" class="table-responsive">
									<table id="tablesendmsgsmst" data-classes="table table-hover table-condensed"
										data-striped="true" data-pagination="true" style="width:700px;">
										<thead>
											<tr>
												<th class="col-xs-1 text-center" data-field="fecha" data-sortable="true">'.translate('Lbl_Date_Print_Credit',$GLOBALS['lang']).'</th>
												<th class="col-xs-1 text-center" data-field="telefono" data-sortable="true">'.translate('Lbl_Phone_Chain',$GLOBALS['lang']).'</th>
												<th class="col-xs-1 text-center" data-field="reintentos" data-sortable="true">'.translate('Lbl_Retries_Amount_Default_Notice',$GLOBALS['lang']).'</th>
												<th class="col-xs-1 text-center" data-field="estado" data-sortable="true">'.translate('Lbl_State_Fee_Credit',$GLOBALS['lang']).'</th>		
												<th class="col-xs-3 text-center" data-field="comentario" data-sortable="true">'.translate('Lbl_Commentary_Default_Notice',$GLOBALS['lang']).'</th>																	
											</tr>						
										</thead>
										<tbody>';
											while($stmt->fetch())
											{		
												echo '<tr>';
												echo '<td>'.substr($date_default_notice_sms,6,2).'/'.substr($date_default_notice_sms,4,2).'/'.substr($date_default_notice_sms,0,4).' '.substr($date_default_notice_sms,8,2).':'.substr($date_default_notice_sms,10,2).':'.substr($date_default_notice_sms,12,2).'</td>';
												echo '<td>'.$numero_telefono_default_notice_sms.'</td>';
												echo '<td>'.$reintentos_default_notice_sms.'</td>';															
												echo '<td>'.$estado_default_notice_sms.'</td>';
												echo '<td>'.$comentario_default_notice_sms.'</td>';
												echo '</tr>';
											}
											$stmt->free_result();
											$stmt->close();														
		echo '							</tbody>					
									</table>
								</div>';
		echo '				</div>';								
		echo '				<div class="form-group form-inline">';				
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnSalirVCSMS" id="btnSalirVCSMS" value="'.translate('Lbl_Exit',$GLOBALS['lang']).'" onClick="$(\'#dialogviewdefaultnoticesms\').dialog(\'close\');" style="margin-left:10px;" />';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		return;
?>