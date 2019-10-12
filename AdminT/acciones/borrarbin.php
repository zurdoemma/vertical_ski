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
		
		$idBin=htmlspecialchars($_POST["idBin"], ENT_QUOTES, 'UTF-8');
				
		if($stmt = $mysqli->prepare("SELECT b.bin_id FROM tef.bines b WHERE b.bin_id = ?"))
		{
			$stmt->bind_param('i', $idBin);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_Bin_Selected_Not_Exist',$GLOBALS['lang']);
				return;
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}
		
		if($stmt2 = $mysqli->prepare("SELECT company_id, range_from, range_to, payment_method_id, bin_length, facility_payments_allowed, bin_id, create_timestamp, update_timestamp, config_version, enabled, priority FROM tef.bines"))
		{
			$stmt2->execute();    
			$stmt2->store_result();
			
			$totR2 = $stmt2->num_rows;
			
			if($totR2 > 0)
			{
				$delimiter = ",";
				$filename = "./backup/backup_bines_" . date('YmdHis') . ".csv";
				
				$f = fopen($filename, 'w+');
				
				$fields = array('company_id', 'range_from', 'range_to', 'payment_method_id', 'bin_length', 'facility_payments_allowed', 'bin_id', 'create_timestamp', 'update_timestamp', 'config_version', 'enabled', 'priority');
				fputcsv($f, $fields, $delimiter);
				
				$stmt2->bind_result($company_id_b, $range_from_b, $range_to_b, $payment_method_id_b, $bin_length_b, $facility_payments_allowed_b, $bin_id_b, $create_timestamp_b, $update_timestamp_b, $config_version_b, $enabled_b, $priority_b);
				while($stmt2->fetch())
				{
					$lineData = array($company_id_b, $range_from_b, $range_to_b, $payment_method_id_b, $bin_length_b, $facility_payments_allowed_b, $bin_id_b, $create_timestamp_b, $update_timestamp_b, $config_version_b, $enabled_b, $priority_b);
					fputcsv($f, $lineData, $delimiter);
				}
				
				fseek($f, 0);
				fclose($f);
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
		
		$mysqli->autocommit(FALSE);
		
		if(!$stmt10 = $mysqli->prepare("DELETE FROM tef.bines WHERE bin_id = ?"))
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
			$stmt10->bind_param('i', $idBin);
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
		
		if($stmt = $mysqli->prepare("SELECT b.bin_id, pm.payment_method_id, pm.payment_method_description, b.range_from, b.range_to, b.bin_length FROM tef.bines b, tef.paymentmethods pm  WHERE b.payment_method_id = pm.payment_method_id ORDER BY pm.payment_method_id, b.bin_length, b.range_from LIMIT 5000"))
		{
			$stmt->execute();    
			$stmt->store_result();
			
			$stmt->bind_result($id_bin, $id_payment_method, $name_payment_method, $range_from_bin, $range_to_bin, $bin_length);
								
			$array[0] = array();
			$posicion = 0;
			while($stmt->fetch())
			{
				$array[$posicion]['idtarjeta'] = $id_payment_method;
				$array[$posicion]['tarjeta'] = $name_payment_method;
				$array[$posicion]['rangodesde'] = $range_from_bin;
				$array[$posicion]['rangohasta'] = $range_to_bin;
				$array[$posicion]['largobin'] = $bin_length;
				
				$array[$posicion]['acciones'] = '<button type="button" id="borrarBin'.$id_bin.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Bin',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Remove_Bin',$GLOBALS['lang']).'\',\''.$id_bin.'\',\''.$range_from_bin.'\',\''.$name_payment_method.'\')"><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" id="btnModificarBin'.$id_bin.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Modify_Bin',$GLOBALS['lang']).'" onclick="modificarBin(\''.$id_bin.'\')"><i class="far fa-edit"></i></button>';
				
				$posicion++;
			}
			
			echo translate('Msg_Remove_Bin_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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