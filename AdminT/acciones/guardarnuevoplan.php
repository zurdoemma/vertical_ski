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
		
		$idSucursal=htmlspecialchars($_POST["idSucursal"], ENT_QUOTES, 'UTF-8');
		$idTarjeta=htmlspecialchars($_POST["idTarjeta"], ENT_QUOTES, 'UTF-8');
		$idPlan=htmlspecialchars($_POST["idPlan"], ENT_QUOTES, 'UTF-8');
		$descripcionPlan=htmlspecialchars($_POST["descripcionPlan"], ENT_QUOTES, 'UTF-8');
		$cuotaDesde=htmlspecialchars($_POST["cuotaDesde"], ENT_QUOTES, 'UTF-8');
		$cuotaHasta=htmlspecialchars($_POST["cuotaHasta"], ENT_QUOTES, 'UTF-8');
		$nroComercio=htmlspecialchars($_POST["nroComercio"], ENT_QUOTES, 'UTF-8');
		$nodo=htmlspecialchars($_POST["nodo"], ENT_QUOTES, 'UTF-8');
		$porcentajeRecargo=htmlspecialchars($_POST["porcentajeRecargo"], ENT_QUOTES, 'UTF-8');
		$planEspecial=htmlspecialchars($_POST["planEspecial"], ENT_QUOTES, 'UTF-8');
		$codigoDP=htmlspecialchars($_POST["codigoDP"], ENT_QUOTES, 'UTF-8');
		$poolID=htmlspecialchars($_POST["poolID"], ENT_QUOTES, 'UTF-8');
		$soportaCashback=htmlspecialchars($_POST["soportaCashback"], ENT_QUOTES, 'UTF-8');
		$minCompraCashback=htmlspecialchars($_POST["minCompraCashback"], ENT_QUOTES, 'UTF-8');
		$maxExtraccionCashback=htmlspecialchars($_POST["maxExtraccionCashback"], ENT_QUOTES, 'UTF-8');
		$montoDesde=htmlspecialchars($_POST["montoDesde"], ENT_QUOTES, 'UTF-8');
		$montoHasta=htmlspecialchars($_POST["montoHasta"], ENT_QUOTES, 'UTF-8');
		$planISO=htmlspecialchars($_POST["planISO"], ENT_QUOTES, 'UTF-8');
		
		if(empty($planISO)) $planISO = '0';
		
		if($idSucursal < 0 || $idTarjeta < 0 || $cuotaDesde < 0 || $cuotaHasta < 0 || $nodo < 0 || $porcentajeRecargo < 0 || $codigoDP < 0 || $minCompraCashback < 0 || $maxExtraccionCashback < 0 || $montoDesde < 0 || $montoHasta < 0)
		{
			echo translate('Negative_Numbers_Are_Not_Allowed',$GLOBALS['lang']);
			return;
		}
		
		if(empty($idSucursal) || empty($idTarjeta) || empty($idPlan) || empty($descripcionPlan) || empty($nroComercio) || empty($nodo) || empty($porcentajeRecargo) || empty($planEspecial) || empty($poolID) || empty($soportaCashback))
		{
			if($porcentajeRecargo != 0 && $planEspecial != 0 && $soportaCashback != 0) 
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;
			}				
		}		

		if(!is_int(intval($cuotaDesde)))
		{
			echo translate('Msg_A_Number_Fee_From_Must_Enter',$GLOBALS['lang']);
			return;	
		}
		
		if(!is_int(intval($cuotaHasta)))
		{
			echo translate('Msg_A_Number_Fee_To_Must_Enter',$GLOBALS['lang']);
			return;	
		}

		if(intval($cuotaHasta) < intval($cuotaDesde))
		{
			echo translate('Msg_Fee_From_Plan_Cant_be_older_To',$GLOBALS['lang']);
			return;				
		}
		
		if(!is_int(intval($codigoDP)))
		{
			echo translate('Msg_A_Code_Plan_DP_Must_Enter_Number_Whole',$GLOBALS['lang']);
			return;	
		}
		
		if(!is_numeric((double)$minCompraCashback))
		{
			echo translate('Msg_A_Min_Buy_Cashback_Must_Enter_Number',$GLOBALS['lang']);
			return;	
		}		

		if(!is_numeric((double)$maxExtraccionCashback))
		{
			echo translate('Msg_A_Max_Amount_Cashback_Must_Enter_Number',$GLOBALS['lang']);
			return;	
		}
		
		if(!is_numeric((double)$montoDesde))
		{
			echo translate('Msg_A_Amount_From_Must_Enter_Number',$GLOBALS['lang']);
			return;	
		}
		
		if(!is_numeric((double)$montoHasta))
		{
			echo translate('Msg_A_Amount_To_Must_Enter_Number',$GLOBALS['lang']);
			return;	
		}
		
		if(((double)$montoHasta) < ((double)$montoDesde))
		{
			echo translate('Msg_Amount_From_Plan_Cant_be_older_To',$GLOBALS['lang']);
			return;				
		}
		
		if($stmt = $mysqli->prepare("SELECT p.plan_id FROM tef.plans p WHERE p.plan_id = ? AND p.branch_id = ? AND p.payment_method_id = ?"))
		{
			$stmt->bind_param('sii', $idPlan, $idSucursal, $idTarjeta);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR > 0)
			{
				echo translate('Msg_Plan_Exists',$GLOBALS['lang']);
				return;
			}
			else
			{	
				$mysqli->autocommit(FALSE);
				
				if(!$stmt10 = $mysqli->prepare("INSERT INTO tef.plans(company_id, operation_mode, branch_id, payment_method_id, plan_id, currency_id, host_facility_type_id, host_facility_type_description, facility_payments_from, facility_payments_to, plan_description, merchant_id, host_id, offline_max_amount, exclusive_online_mode, charge_percentage, category, create_timestamp, update_datetime, config_version, foreign_identifier, deferral_allowed, deferral_min_days, deferral_max_days, cashback_allowed, cashback_min_buy_amount_allowed, cashback_max_amount_allowed, dynamic_plan, tna, tem, branch_group_id, terminal_mode, pool_id, amount_from, amount_to, ticket_safe_mode, preference) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else 
				{
					$companyID = 1;
					$operationMode = 57;
					$currencyId = 1;
					$hostFacilityTypeId = $planISO;
					$offlineMaxAmount = 0.00;
					$exclusiveOnlineMode = 'S';
					$configVersion = 0;
					$deferralAllow = 'N';
					$deferralMinDay = 0;
					$deferralMaxDay = 0;
					$dynamicPlan = 0;
					$tna = 0.00;
					$tem = 0.00;
					$branchGroupId = 0;
					$createTimestamp = date("Y-m-d H:i:s");
					$updateTimestamp = $createTimestamp;
					$terminalMode = 'F';
					$poolID = '0';
					$ticketSafeMode = 255;
					$preference = 0;
					
					if($soportaCashback == '1') 
					{
						$soportaCashback = 'S';
						$minCompraCashback = ((double)$minCompraCashback)/100.00;
						$maxExtraccionCashback = ((double)$maxExtraccionCashback)/100.00;
					}
					else 
					{
						$soportaCashback = 'N';
						$minCompraCashback = 0.00;
						$maxExtraccionCashback = 0.00;
					}
					
					if($planEspecial == '0')
					{
						$codigoDP = '0';
					}					
					
					$porcentajeRecargo = ((double)$porcentajeRecargo)/100.00;
					$montoDesde = ((double)$montoDesde)/100.00;
					$montoHasta = ((double)$montoHasta)/100.00;
					
					$stmt10->bind_param('iiiisissiissidsdississiisddiddissddii', $companyID, $operationMode, $idSucursal, $idTarjeta, $idPlan, $currencyId, $hostFacilityTypeId, $descripcionPlan, $cuotaDesde, $cuotaHasta, $descripcionPlan, $nroComercio, $nodo, $offlineMaxAmount, $exclusiveOnlineMode, $porcentajeRecargo, $planEspecial, $createTimestamp, $updateTimestamp, $configVersion, $codigoDP, $deferralAllow, $deferralMinDay, $deferralMaxDay, $soportaCashback, $minCompraCashback, $maxExtraccionCashback, $dynamicPlan, $tna, $tem, $branchGroupId, $terminalMode, $poolID, $montoDesde, $montoHasta, $ticketSafeMode, $preference);
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
					
					echo translate('Msg_New_Plan_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
				}
				else 
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;	
				}
				
				$stmt->free_result();
				$stmt->close();
				return;					
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}
		
?>