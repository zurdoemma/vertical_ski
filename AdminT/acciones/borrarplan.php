<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosta.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
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
		
		$idPlan=htmlspecialchars($_POST["idPlan"], ENT_QUOTES, 'UTF-8');
				
		$idPlanDB = explode("--",$idPlan);
		if(count($idPlanDB) != 3)
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
				
		if($stmt = $mysqli->prepare("SELECT plan_id FROM tef.plans WHERE branch_id = ? AND payment_method_id = ? AND plan_id = ?"))
		{
			$stmt->bind_param('iis', $idPlanDB[0], $idPlanDB[1], $idPlanDB[2]);
			$stmt->execute();    
			$stmt->store_result();
			
			$totR = $stmt->num_rows;
			
			if($totR > 0)
			{
				if($stmt2 = $mysqli->prepare("SELECT company_id, operation_mode, branch_id, payment_method_id, plan_id, currency_id, host_facility_type_id, host_facility_type_description, facility_payments_from, facility_payments_to, plan_description, merchant_id, host_id, offline_max_amount, exclusive_online_mode, charge_percentage, category, create_timestamp, update_datetime, config_version, foreign_identifier, deferral_allowed, deferral_min_days, deferral_max_days, cashback_allowed, cashback_min_buy_amount_allowed, cashback_max_amount_allowed, cashback_min_percentage_allowed, cashback_max_percentage_allowed, host_facility_payments_id, dynamic_plan, tna, tem, branch_group_id, terminal_mode, pool_id, amount_from, amount_to, ticket_safe_mode, preference FROM tef.plans"))
				{
					$stmt2->execute();    
					$stmt2->store_result();
					
					$totR2 = $stmt2->num_rows;
					
					if($totR2 > 0)
					{
						$delimiter = ",";
						$filename = "./backup/backup_planes_" . date('YmdHis') . ".csv";
						
						$f = fopen($filename, 'w+');
						
						$fields = array('company_id', 'operation_mode', 'branch_id', 'payment_method_id', 'plan_id', 'currency_id', 'host_facility_type_id', 'host_facility_type_description', 'facility_payments_from', 'facility_payments_to', 'plan_description', 'merchant_id', 'host_id', 'offline_max_amount', 'exclusive_online_mode', 'charge_percentage', 'category', 'create_timestamp', 'update_datetime', 'config_version', 'foreign_identifier', 'deferral_allowed', 'deferral_min_days', 'deferral_max_days', 'cashback_allowed', 'cashback_min_buy_amount_allowed', 'cashback_max_amount_allowed', 'cashback_min_percentage_allowed', 'cashback_max_percentage_allowed', 'host_facility_payments_id', 'dynamic_plan', 'tna', 'tem', 'branch_group_id', 'terminal_mode', 'pool_id', 'amount_from', 'amount_to', 'ticket_safe_mode', 'preference');
						fputcsv($f, $fields, $delimiter);
						
						$stmt2->bind_result($company_id, $operation_mode, $branch_id, $payment_method_id, $plan_id, $currency_id, $host_facility_type_id, $host_facility_type_description, $facility_payments_from, $facility_payments_to, $plan_description, $merchant_id, $host_id, $offline_max_amount, $exclusive_online_mode, $charge_percentage, $category, $create_timestamp, $update_datetime, $config_version, $foreign_identifier, $deferral_allowed, $deferral_min_days, $deferral_max_days, $cashback_allowed, $cashback_min_buy_amount_allowed, $cashback_max_amount_allowed, $cashback_min_percentage_allowed, $cashback_max_percentage_allowed, $host_facility_payments_id, $dynamic_plan, $tna, $tem, $branch_group_id, $terminal_mode, $pool_id, $amount_from, $amount_to, $ticket_safe_mode, $preference);
						while($stmt2->fetch())
						{
							$lineData = array($company_id, $operation_mode, $branch_id, $payment_method_id, $plan_id, $currency_id, $host_facility_type_id, $host_facility_type_description, $facility_payments_from, $facility_payments_to, $plan_description, $merchant_id, $host_id, $offline_max_amount, $exclusive_online_mode, $charge_percentage, $category, $create_timestamp, $update_datetime, $config_version, $foreign_identifier, $deferral_allowed, $deferral_min_days, $deferral_max_days, $cashback_allowed, $cashback_min_buy_amount_allowed, $cashback_max_amount_allowed, $cashback_min_percentage_allowed, $cashback_max_percentage_allowed, $host_facility_payments_id, $dynamic_plan, $tna, $tem, $branch_group_id, $terminal_mode, $pool_id, $amount_from, $amount_to, $ticket_safe_mode, $preference);
							fputcsv($f, $lineData, $delimiter);
						}
						
						fseek($f, 0);
						fclose($f);
						
						$stmt2->free_result();
						$stmt2->close();
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
		
		$mysqli->autocommit(FALSE);
		
		if(!$stmt10 = $mysqli->prepare("DELETE FROM tef.plans WHERE branch_id = ? AND payment_method_id = ? AND plan_id = ?"))
		{
			echo $mysqli->error;
			$mysqli->autocommit(TRUE);
			$stmt->free_result();
			$stmt->close();
			return;
		}
		else 
		{
			$updateTimestamp = date("Y-m-d H:i:s");					
			$stmt10->bind_param('iis', $idPlanDB[0], $idPlanDB[1], $idPlanDB[2]);
			if(!$stmt10->execute())
			{
				echo $mysqli->error;
				$mysqli->autocommit(TRUE);
				$stmt->free_result();
				$stmt->close();
				return;						
			}						
		}
				
		$mysqli->commit();
		$mysqli->autocommit(TRUE);
		
		if($stmt = $mysqli->prepare("SELECT p.branch_id, pm.payment_method_id, pm.payment_method_description, p.plan_id, p.plan_description, p.merchant_id, p.facility_payments_from, n.host_name, p.charge_percentage, p.cashback_allowed FROM tef.plans p, tef.paymentmethods pm, tef.hosts n  WHERE p.payment_method_id = pm.payment_method_id AND pm.host_id = n.host_id ORDER BY p.branch_id, p.payment_method_id, p.plan_id, p.facility_payments_from LIMIT 5000"))
		{
			$stmt->execute();    
			$stmt->store_result();
			
			$stmt->bind_result($id_branch_plan, $id_payment_method, $name_payment_method, $id_plan, $name_plan, $number_merchant_plan, $count_fees_plan, $name_node_plan, $percentaje_charge_plan, $allowed_cashback_plan);
								
			$array[0] = array();
			$posicion = 0;
			while($stmt->fetch())
			{
				$array[$posicion]['sucursal'] = $id_branch_plan;
				$array[$posicion]['idtarjeta'] = $id_payment_method;
				$array[$posicion]['tarjeta'] = $name_payment_method;
				$array[$posicion]['idplan'] = $id_plan;
				$array[$posicion]['plan'] = $name_plan;
				$array[$posicion]['nrocomercio'] = $number_merchant_plan;
				$array[$posicion]['cuotas'] = $count_fees_plan;
				$array[$posicion]['nodo'] = $name_node_plan;
				$array[$posicion]['porcrecargo'] = $percentaje_charge_plan;
				$array[$posicion]['sopcashback'] = $allowed_cashback_plan;
				
				$array[$posicion]['acciones'] = '<button type="button" id="borrarPlan'.$id_branch_plan.'--'.$id_payment_method.'--'.$id_plan.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Plan',$GLOBALS['lang']).'" onclick="confirmar_accion_plan(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Remove_Plan',$GLOBALS['lang']).'\',\''.$id_branch_plan.'--'.$id_payment_method.'--'.$id_plan.'\',\''.$id_plan.'\',\''.$name_payment_method.'\')"><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" id="btnModificarPlan'.$id_branch_plan.'--'.$id_payment_method.'--'.$id_plan.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Modify_Plan',$GLOBALS['lang']).'" onclick="modificarPlan(\''.$id_branch_plan.'--'.$id_payment_method.'--'.$id_plan.'\')"><i class="far fa-edit"></i></button>';
				
				$posicion++;
			}
			
			echo translate('Msg_Remove_Plan_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;	
		}
		
		$stmt->free_result();
		$stmt->close();
		return;
?>