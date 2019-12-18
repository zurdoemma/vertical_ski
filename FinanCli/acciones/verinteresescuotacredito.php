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
		
		$idCuotaCredito=htmlspecialchars($_POST["idCuotaCredito"], ENT_QUOTES, 'UTF-8');
		
	

		if ($stmt = $mysqli->prepare("SELECT cc.fecha_vencimiento, cc.numero_cuota, cc.monto_cuota_original  FROM ".$db_name.".cuota_credito cc WHERE cc.id = ?")) 
		{
			$stmt->bind_param('i', $idCuotaCredito);
			$stmt->execute();    
			$stmt->store_result();
	 
			$stmt->bind_result($fecha_vencimiento_cuota_orig_db, $numero_cuota_orig_db, $monto_cuota_orig_db);			
			
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_Fee_Credit_Client_Not_Exist',$GLOBALS['lang']);
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
				
		if($stmt63 = $mysqli->prepare("SELECT mcc.monto_interes, mcc.fecha_interes FROM ".$db_name.".mora_cuota_credito mcc, ".$db_name.".cuota_credito cc WHERE mcc.id_cuota_credito = cc.id AND cc.id = ?"))
		{
			$stmt63->bind_param('i', $idCuotaCredito);
			$stmt63->execute();    
			$stmt63->store_result();
			
			$totR63 = $stmt63->num_rows;

			$montoIntereses = 0;
			if($totR63 > 0)
			{
				$stmt63->bind_result($monto_interes_cuota_credito_db, $fecha_interes_cuota_credito_db);
				$datosTabla = "";
				while($stmt63->fetch())
				{
					if($datosTabla != "") $datosTabla = $datosTabla.'!';
					$montoIntereses = $montoIntereses + $monto_interes_cuota_credito_db;
					if($datosTabla == "") $datosTabla = $fecha_interes_cuota_credito_db.'¡'.$monto_interes_cuota_credito_db;
					else $datosTabla = $datosTabla.$fecha_interes_cuota_credito_db.'¡'.$monto_interes_cuota_credito_db;
				}
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
		$montoTotalConInteresesCuotaCredito = $monto_cuota_orig_db + $montoIntereses;
				
		echo translate('Msg_View_Interests_Fee_Credit_OK',$GLOBALS['lang']);	
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Lbl_View_Data_Interest_Fee_Credit',$GLOBALS['lang']).': '.$numero_cuota_orig_db.'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<form id="formularionicc" role="form">';
		echo '				<div class="form-group form-inline">';		
		echo '					&nbsp;&nbsp;<label class="control-label" for="fechavencimientocuotav">'.translate('Lbl_Date_Expire_Print_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="fechavencimientocuotav">';
		echo '						<input class="form-control input-sm" id="fechavencimientocuotavi" name="fechavencimientocuotavi" type="text" maxlength="10" value="'.substr($fecha_vencimiento_cuota_orig_db,6,2).'/'.substr($fecha_vencimiento_cuota_orig_db,4,2).'/'.substr($fecha_vencimiento_cuota_orig_db,0,4).'" disabled/>';
		echo '					</div>';		
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="montocuotaorigv">'.translate('Lbl_Amount_Original_Fee_Print_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="montocuotaorigv">';
		echo '						<input class="form-control input-sm" id="montocuotaorigvi" name="montocuotaorigvi" type="text" maxlength="11" value="'.str_replace(",",".",round(($monto_cuota_orig_db/100.00),2)).'" disabled />';
		echo '					</div>';		
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;<label class="control-label" for="interesescuotacreditv">'.translate('Lbl_Amount_Interests_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="interesescuotacreditv">';
		echo '						<input class="form-control input-sm" id="interesescuotacreditvi" name="interesescuotacreditvi" type="text" maxlength="11" value="'.str_replace(",",".",round(($montoIntereses/100.00),2)).'" disabled />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="montototalcuotacreditv">'.translate('Lbl_Total_Amount_Credit',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="montototalcuotacreditv">';
		echo '						<input class="form-control input-sm" id="montototalcuotacreditvi" name="montototalcuotacreditvi" type="text" maxlength="11" value="'.str_replace(",",".",round(($montoTotalConInteresesCuotaCredito/100.00),2)).'" disabled />';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline"><hr />';
		echo '					<div class="panel-group">				
									<div class="panel panel-default" style="width:572px;">
										<div id="panel-title-header" class="panel-heading">
											<h3 class="panel-title">'.translate('Lbl_Interest_Aplied_Fees_Credit',$GLOBALS['lang']).'</h3>
										</div>
										<div id="apDiv11" class="panel-body">
											<div id="tablefeesinterestfeecreditclientv" class="table-responsive">
												<table id="tableinterestfeecreditclienttv" data-classes="table table-hover table-condensed"
													data-striped="true" data-pagination="true">
													<thead>
														<tr>
															<th class="col-xs-2 text-center" data-field="fechainteres" data-sortable="true">'.translate('Lbl_Date_Print_Credit',$GLOBALS['lang']).'</th>
															<th class="col-xs-1 text-center" data-field="montointeres" data-sortable="true">'.translate('Lbl_Amount_Interest_Print_Credit',$GLOBALS['lang']).'</th>													
														</tr>						
													</thead>
													<tbody>';
														$datosTablaRec = explode("!",$datosTabla);
														for($i = 0; $i < count($datosTablaRec); $i++)
														{		
															$datosFileTabla = explode("¡",$datosTablaRec[$i]);
															
															echo '<tr>';
															echo	 '<td>'.substr($datosFileTabla[0],6,2).'/'.substr($datosFileTabla[0],4,2).'/'.substr($datosFileTabla[0],0,4).'</td>';
															echo	 '<td>'.round(($datosFileTabla[1]/100.00),2).'</td>';
															echo '</tr>';
														}														
		echo '  									</tbody>					
												</table>
											</div>
										</div>
									</div>
								</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';				
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnSalirVICC" id="btnSalirVICC" value="'.translate('Lbl_Exit',$GLOBALS['lang']).'" onClick="$(\'#dialogviewinterestfeecredit\').dialog(\'close\');" style="margin-left:10px;" />';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		return;
?>