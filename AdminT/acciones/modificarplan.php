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

		$idPlan=htmlspecialchars($_POST["idPlan"], ENT_QUOTES, 'UTF-8');
				
		$idPlanDB = explode("--",$idPlan);
		if(count($idPlanDB) != 3)
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		
		if ($stmt = $mysqli->prepare("SELECT p.branch_id, p.payment_method_id, p.plan_id, p.facility_payments_from, p.facility_payments_to, p.plan_description, p.merchant_id, p.host_id, p.charge_percentage, p.category, p.foreign_identifier, p.cashback_allowed, p.cashback_min_buy_amount_allowed, p.cashback_max_amount_allowed, p.amount_from, p.amount_to, p.host_facility_type_id FROM tef.plans p WHERE p.branch_id = ? AND p.payment_method_id = ? AND p.plan_id = ?")) 
		{
			$stmt->bind_param('iis', $idPlanDB[0], $idPlanDB[1], $idPlanDB[2]);
			$stmt->execute();    
			$stmt->store_result();
	 
			$stmt->bind_result($branch_id_a, $payment_method_id_a, $plan_id_a, $facility_payments_from_a, $facility_payments_to_a, $plan_description_a, $merchant_id_a, $host_id_a, $charge_percentage_a, $category_a, $foreign_identifier_a, $cashback_allowed_a, $cashback_min_buy_amount_allowed_a, $cashback_max_amount_allowed_a, $amount_from_a, $amount_to_a, $codigo_plan_iso_a);
			$totR = $stmt->num_rows;

			if($totR > 0)
			{
				$stmt->fetch();
				
				$stmt->free_result();
				$stmt->close();
			}
			else
			{
				echo translate('Msg_Plan_Selected_Not_Exist',$GLOBALS['lang']);
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
		echo '			<h3 class="panel-title">'.translate('Lbl_New_Data_Plan',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_5"></div>';
		echo '			<form id="formulariomp" role="form">';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="sucursalplan">'.translate('Lbl_Tender',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="sucursalplan">';
		echo '						<select class="form-control input-sm" name="sucursalplani" id="sucursalplani" style="width:193px;">';			 
										if ($stmt = $mysqli->prepare("SELECT b.branch_id, b.description FROM tef.branches b ORDER BY b.branch_id")) 
										{ 
											$stmt->execute();    
											$stmt->store_result();
									 
											$stmt->bind_result($id_branch, $descripcion);
											while($stmt->fetch())
											{
												if($branch_id_a == $id_branch) echo '<option selected value="'.$id_branch.'">'.$descripcion.'</option>';
												else echo '<option value="'.$id_branch.'">'.$descripcion.'</option>';
											}
										}
										else  
										{
											echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
											return;			
										}
		echo '						</select>';
		echo '					</div>';		
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="tarjetaplan">'.translate('Lbl_Card',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="tarjetaplan">';
		echo '						<select class="form-control input-sm" name="tarjetaplani" id="tarjetaplani" style="width:193px;">';			 
										if ($stmt = $mysqli->prepare("SELECT pm.payment_method_id, pm.payment_method_description FROM tef.paymentmethods pm ORDER BY pm.payment_method_id")) 
										{ 
											$stmt->execute();    
											$stmt->store_result();
									 
											$stmt->bind_result($id_payment_method_id, $nombre_payment_method);
											while($stmt->fetch())
											{
												if($id_payment_method_id == $payment_method_id_a) echo '<option selected value="'.$id_payment_method_id.'">'.$id_payment_method_id.' - '.$nombre_payment_method.'</option>';
												else echo '<option value="'.$id_payment_method_id.'">'.$id_payment_method_id.' - '.$nombre_payment_method.'</option>';
											}
										}
										else  
										{
											echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
											return;			
										}
		echo '						</select>';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="planid">'.translate('Lbl_Plan_Id',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="planid">';
		echo '						<input title="'.translate('Msg_A_Plan_Id_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="planidi" name="planidi" type="text" maxlength="30" value="'.$plan_id_a.'" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="nameplan">'.translate('Lbl_Description_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nameplann">';
		echo '						<input title="'.translate('Msg_A_Plan_Name_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nameplani" name="nameplani" type="text" maxlength="30" value="'.$plan_description_a.'" />';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="cuotadesdeplan">'.translate('Lbl_Number_Fee_From_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="cuotadesdeplan">';
		echo '						<input title="'.translate('Msg_A_Number_Fee_From_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="cuotadesdeplani" name="cuotadesdeplani" type="text" maxlength="11" value="'.$facility_payments_from_a.'" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="cuotahastaplan">'.translate('Lbl_Number_Fee_To_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="cuotahastaplann">';
		echo '						<input title="'.translate('Msg_A_Number_Fee_To_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="cuotahastaplani" name="cuotahastaplani" type="text" maxlength="11" value="'.$facility_payments_to_a.'" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nrocomercio">'.translate('Lbl_Number_Merchant_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nrocomercion">';
		echo '						<input title="'.translate('Msg_A_Number_Merchant_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="nrocomercioi" name="nrocomercioi" type="text" maxlength="15" value="'.$merchant_id_a.'" />';
		echo '					</div>';		
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nodoplan">'.translate('Lbl_Name_Node_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="nodoplann">';
		echo '						<select class="form-control input-sm" name="nodoplani" id="nodoplani" style="width:193px;">';			 
										if ($stmt = $mysqli->prepare("SELECT n.host_id, n.host_name FROM tef.hosts n ORDER BY n.host_id")) 
										{ 
											$stmt->execute();    
											$stmt->store_result();
									 
											$stmt->bind_result($id_host, $descripcion_host);
											while($stmt->fetch())
											{
												if($host_id_a == $id_host) echo '<option selected value="'.$id_host.'">'.$descripcion_host.'</option>';
												else echo '<option value="'.$id_host.'">'.$descripcion_host.'</option>';
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
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="recargoplan">'.translate('Lbl_Percentage_Charge_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="recargoplan">';
		echo '						<input title="'.translate('Msg_A_Percentage_Charge_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="recargoplani" name="recargoplani" type="text" maxlength="14" value="'.$charge_percentage_a.'" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="planespecialplan">'.translate('Lbl_Special_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="planespecialplan">';
		echo '						<select class="form-control input-sm" name="planespecialplani" id="planespecialplani" style="width:193px;">';			 
		if($category_a != 1) 
		{
			echo '		<option selected value="0">'.translate('Lbl_Button_NO',$GLOBALS['lang']).'</option>';
			echo '		<option value="1">'.translate('Lbl_Button_YES',$GLOBALS['lang']).'</option>';
		}
		else 
		{
			echo '						<option selected value="1">'.translate('Lbl_Button_YES',$GLOBALS['lang']).'</option>';
			echo '						<option value="0">'.translate('Lbl_Button_NO',$GLOBALS['lang']).'</option>';
		}
		echo '						</select>';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="codigoplandp">'.translate('Lbl_Code_Plan_DP',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="codigoplandp">';
		if($category_a == 1) echo '						<input title="'.translate('Msg_A_Code_Plan_DP_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="codigoplandpi" name="codigoplandpi" type="text" maxlength="32" value="'.$foreign_identifier_a.'" />';
		else echo '						<input title="'.translate('Msg_A_Code_Plan_DP_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="codigoplandpi" name="codigoplandpi" type="text" maxlength="32" value="'.$foreign_identifier_a.'" disabled />';
		echo '					</div>';		
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="poolterminalsplan">'.translate('Lbl_Pool_ID_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="poolterminalsplann">';
		echo '						<select class="form-control input-sm" name="poolterminalsplani" id="poolterminalsplani" style="width:193px;">';			 
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
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="soportacashbackplan">'.translate('Lbl_Allowed_Cashback_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="soportacashbackplan">';
		echo '						<select class="form-control input-sm" name="soportacashbackplani" id="soportacashbackplani" style="width:193px;">';			 
		if($cashback_allowed_a != 'S') 
		{
			echo '<option selected value="0">'.translate('Lbl_Button_NO',$GLOBALS['lang']).'</option>';
			echo '<option value="1">'.translate('Lbl_Button_YES',$GLOBALS['lang']).'</option>';
		}
		else 
		{
			echo '							 <option selected value="1">'.translate('Lbl_Button_YES',$GLOBALS['lang']).'</option>';
			echo '							 <option value="0">'.translate('Lbl_Button_NO',$GLOBALS['lang']).'</option>';
		}
		echo '						</select>';
		echo '					</div>';
		echo '					&nbsp;&nbsp;<label class="control-label" for="minimocompracashbackplan">'.translate('Lbl_Min_Buy_Cashback',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="minimocompracashbackplan">';
		if($cashback_allowed_a == 'S') echo '						<input title="'.translate('Msg_A_Min_Buy_Cashback_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="minimocompracashbackplani" name="minimocompracashbackplani" type="text" maxlength="14" value="'.$cashback_min_buy_amount_allowed_a.'" />';
		else echo '						<input title="'.translate('Msg_A_Min_Buy_Cashback_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="minimocompracashbackplani" name="minimocompracashbackplani" type="text" maxlength="14" value="'.$cashback_min_buy_amount_allowed_a.'" disabled />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;<label class="control-label" for="maximoextraccioncashbackplan">'.translate('Lbl_Max_Amount_Cashback',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="maximoextraccioncashbackplan">';
		if($cashback_allowed_a == 'S') echo '						<input title="'.translate('Msg_A_Max_Amount_Cashback_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="maximoextraccioncashbackplani" name="maximoextraccioncashbackplani" type="text" maxlength="14" value="'.$cashback_max_amount_allowed_a.'" />';
		else echo '						<input title="'.translate('Msg_A_Max_Amount_Cashback_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="maximoextraccioncashbackplani" name="maximoextraccioncashbackplani" type="text" maxlength="14" value="'.$cashback_max_amount_allowed_a.'" disabled />';
		echo '					</div>';
		echo '					&nbsp;<label class="control-label" for="montodesdeplan">'.translate('Lbl_Amount_From_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="montodesdeplan">';
		echo '						<input title="'.translate('Msg_A_Amount_From_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="montodesdeplani" name="montodesdeplani" type="text" maxlength="14" value="'.$amount_from_a.'" />';
		echo '					</div>';		
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';		
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="montohastaplan">'.translate('Lbl_Amount_To_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="montohastaplan">';
		echo '						<input title="'.translate('Msg_A_Amount_To_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="montohastaplani" name="montohastaplani" type="text" maxlength="14" value="'.$amount_to_a.'" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="planiso">'.translate('Lbl_ISO_Plan',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="planiso">';
		echo '						<input title="'.translate('Msg_A_ISO_Plan_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="planisoi" name="planisoi" type="text" maxlength="1" value="'.$codigo_plan_iso_a.'" />';
		echo '					</div>';		
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';				
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarMP" id="btnCancelarMP" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogmodifyplan\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargarMP" id="btnCargarMP" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarModificacionPlan(document.getElementById(\'formulariomp\'),\''.$branch_id_a.'--'.$payment_method_id_a.'--'.$plan_id_a.'\');"/>';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		return;
?>