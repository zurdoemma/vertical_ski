<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosta.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		// ¡Oh, no! Existe un error 'connect_errno', fallando así el intento de conexión
		if ($mysqli->connect_errno) 
		{
			//echo "Lo sentimos, este sitio web está experimentando problemas.";

			//echo "Error: Fallo al conectarse a MySQL debido a: \n";
			//echo "Errno: " . $mysqli->connect_errno . "\n";
			//echo "Error: " . $mysqli->connect_error . "\n";
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}				
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Lbl_New_Data_Plan',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_5"></div>';
		echo '			<form id="formularionp" role="form">';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="sucursalplann">'.translate('Lbl_Tender',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="sucursalplann">';
		echo '						<select class="form-control input-sm" name="sucursalplanni" id="sucursalplanni" style="width:193px;">';			 
										if ($stmt = $mysqli->prepare("SELECT b.branch_id, b.description FROM tef.branches b ORDER BY b.branch_id")) 
										{ 
											$stmt->execute();    
											$stmt->store_result();
									 
											$stmt->bind_result($id_branch, $descripcion);
											$ii = 0;
											while($stmt->fetch())
											{
												if($ii == 0) echo '<option selected value="'.$id_branch.'">'.$descripcion.'</option>';
												else echo '<option value="'.$id_branch.'">'.$descripcion.'</option>';
												$ii++;
											}
										}
										else  
										{
											echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
											return;			
										}
		echo '						</select>';
		echo '					</div>';		
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="tarjetaplann">'.translate('Lbl_Card',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="tarjetaplann">';
		echo '						<select class="form-control input-sm" name="tarjetaplanni" id="tarjetaplanni" style="width:193px;">';			 
										if ($stmt = $mysqli->prepare("SELECT pm.payment_method_id, pm.payment_method_description FROM tef.paymentmethods pm ORDER BY pm.payment_method_id")) 
										{ 
											$stmt->execute();    
											$stmt->store_result();
									 
											$stmt->bind_result($id_payment_method_id, $nombre_payment_method);
											$ii = 0;
											while($stmt->fetch())
											{
												if($ii == 0) echo '<option selected value="'.$id_payment_method_id.'">'.$id_payment_method_id.' - '.$nombre_payment_method.'</option>';
												else echo '<option value="'.$id_payment_method_id.'">'.$id_payment_method_id.' - '.$nombre_payment_method.'</option>';
												
												$ii++;
											}
										}
										else  
										{
											echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
											return;			
										}
		echo '						</select>';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="planidn">'.translate('Lbl_Plan_Id',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="planidn">';
		echo '						<input title="'.translate('Msg_A_Plan_Id_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="planidni" name="planidni" type="text" maxlength="30" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="nameplann">'.translate('Lbl_Description_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nameplann">';
		echo '						<input title="'.translate('Msg_A_Plan_Name_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nameplanni" name="nameplanni" type="text" maxlength="30" />';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="cuotadesdeplann">'.translate('Lbl_Number_Fee_From_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="cuotadesdeplann">';
		echo '						<input title="'.translate('Msg_A_Number_Fee_From_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="cuotadesdeplanni" name="cuotadesdeplanni" type="text" maxlength="11" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="cuotahastaplann">'.translate('Lbl_Number_Fee_To_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="cuotahastaplann">';
		echo '						<input title="'.translate('Msg_A_Number_Fee_To_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="cuotahastaplanni" name="cuotahastaplanni" type="text" maxlength="11" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nrocomercion">'.translate('Lbl_Number_Merchant_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nrocomercion">';
		echo '						<input title="'.translate('Msg_A_Number_Merchant_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nrocomercioni" name="nrocomercioni" type="text" maxlength="15" />';
		echo '					</div>';		
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nodoplann">'.translate('Lbl_Name_Node_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nodoplann">';
		echo '						<select class="form-control input-sm" name="nodoplanni" id="nodoplanni" style="width:193px;">';			 
										if ($stmt = $mysqli->prepare("SELECT n.host_id, n.host_name FROM tef.hosts n ORDER BY n.host_id")) 
										{ 
											$stmt->execute();    
											$stmt->store_result();
									 
											$stmt->bind_result($id_host, $descripcion_host);
											$ii = 0;
											while($stmt->fetch())
											{
												if($ii == 0) echo '<option selected value="'.$id_host.'">'.$descripcion_host.'</option>';
												else echo '<option value="'.$id_host.'">'.$descripcion_host.'</option>';
												$ii++;
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
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="recargoplann">'.translate('Lbl_Percentage_Charge_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="recargoplann">';
		echo '						<input title="'.translate('Msg_A_Percentage_Charge_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="recargoplanni" name="recargoplanni" type="text" maxlength="14" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="planespecialplann">'.translate('Lbl_Special_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="planespecialplann">';
		echo '						<select class="form-control input-sm" name="planespecialplanni" id="planespecialplanni" style="width:193px;">';			 
		echo '							<option selected value="0">'.translate('Lbl_Button_NO',$GLOBALS['lang']).'</option>';
		echo '							<option value="1">'.translate('Lbl_Button_YES',$GLOBALS['lang']).'</option>';
		echo '						</select>';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="codigoplandpn">'.translate('Lbl_Code_Plan_DP',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="codigoplandpn">';
		echo '						<input title="'.translate('Msg_A_Code_Plan_DP_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="codigoplandpni" name="codigoplandpni" type="text" maxlength="32" disabled />';
		echo '					</div>';		
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="poolterminalsplann">'.translate('Lbl_Pool_ID_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="poolterminalsplann">';
		echo '						<select class="form-control input-sm" name="poolterminalsplanni" id="poolterminalsplanni" style="width:193px;">';			 
										if ($stmt = $mysqli->prepare("SELECT tp.host_id, tp.merchant_id, tp.terminal_id, tp.pool_id FROM tef.terminalspools tp ORDER BY tp.host_id, tp.merchant_id, tp.terminal_id, tp.pool_id")) 
										{ 
											$stmt->execute();    
											$stmt->store_result();
									 
											$stmt->bind_result($id_host, $merchant_id, $terminal_id, $pool_id);
											$ii = 0;
											while($stmt->fetch())
											{
												if($ii == 0) echo '<option selected value="'.$id_host.'--'.$merchant_id.'--'.$terminal_id.'--'.$pool_id.'">'.$pool_id.'</option>';
												else echo '<option value="'.$id_host.'--'.$merchant_id.'--'.$terminal_id.'--'.$pool_id.'">'.$pool_id.'</option>';
												$ii++;
											}
											if($ii == 0) echo '<option value="-1">'.translate('Lbl_No_Data',$GLOBALS['lang']).'</option>';
										}
										else  
										{
											echo '<option value="-1">'.translate('Lbl_No_Data',$GLOBALS['lang']).'</option>';
											return;			
										}
		echo '						</select>';
		echo '					</div>';				
		echo '				</div>';
		echo '				<div class="form-group form-inline">';		
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="soportacashbackplann">'.translate('Lbl_Allowed_Cashback_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="soportacashbackplann">';
		echo '						<select class="form-control input-sm" name="soportacashbackplanni" id="soportacashbackplanni" style="width:193px;">';			 
		echo '							<option selected value="0">'.translate('Lbl_Button_NO',$GLOBALS['lang']).'</option>';
		echo '							<option value="1">'.translate('Lbl_Button_YES',$GLOBALS['lang']).'</option>';
		echo '						</select>';
		echo '					</div>';
		echo '					&nbsp;&nbsp;<label class="control-label" for="minimocompracashbackplann">'.translate('Lbl_Min_Buy_Cashback',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="minimocompracashbackplann">';
		echo '						<input title="'.translate('Msg_A_Min_Buy_Cashback_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="minimocompracashbackplanni" name="minimocompracashbackplanni" type="text" maxlength="14" disabled />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;<label class="control-label" for="maximoextraccioncashbackplann">'.translate('Lbl_Max_Amount_Cashback',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="maximoextraccioncashbackplann">';
		echo '						<input title="'.translate('Msg_A_Max_Amount_Cashback_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="maximoextraccioncashbackplanni" name="maximoextraccioncashbackplanni" type="text" maxlength="14" disabled />';
		echo '					</div>';
		echo '					&nbsp;<label class="control-label" for="montodesdeplann">'.translate('Lbl_Amount_From_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="montodesdeplann">';
		echo '						<input title="'.translate('Msg_A_Amount_From_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="montodesdeplanni" name="montodesdeplanni" type="text" maxlength="14" />';
		echo '					</div>';		
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';		
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="montohastaplann">'.translate('Lbl_Amount_To_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="montohastaplann">';
		echo '						<input title="'.translate('Msg_A_Amount_To_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="montohastaplanni" name="montohastaplanni" type="text" maxlength="14" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="planison">'.translate('Lbl_ISO_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="planison">';
		echo '						<input title="'.translate('Msg_A_ISO_Plan_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="planisoni" name="planisoni" type="text" maxlength="1" />';
		echo '					</div>';		
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';				
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarNP" id="btnCancelarNP" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialognewplan\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargarNP" id="btnCargarNP" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarNuevoPlan(document.getElementById(\'formularionp\'));"/>';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		return;
?>