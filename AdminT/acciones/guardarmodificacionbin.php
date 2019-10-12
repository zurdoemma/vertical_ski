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
		
		$idTarjeta=htmlspecialchars($_POST["idTarjeta"], ENT_QUOTES, 'UTF-8');
		$largoBin=htmlspecialchars($_POST["largoBin"], ENT_QUOTES, 'UTF-8');
		$rangoDesde=htmlspecialchars($_POST["rangoDesde"], ENT_QUOTES, 'UTF-8');
		$rangoHasta=htmlspecialchars($_POST["rangoHasta"], ENT_QUOTES, 'UTF-8');
		
		if($idTarjeta < 0 || $largoBin < 0 || $rangoDesde < 0 || $rangoHasta < 0)
		{
			echo translate('Negative_Numbers_Are_Not_Allowed',$GLOBALS['lang']);
			return;
		}
		
		if(empty($idTarjeta))
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;	
		}		

		if(empty($largoBin))
		{
			echo translate('Msg_A_Bin_Length_Must_Enter',$GLOBALS['lang']);
			return;	
		}
		
		if(empty($rangoDesde))
		{
			echo translate('Msg_A_Range_From_Must_Enter',$GLOBALS['lang']);
			return;	
		}

		if(empty($rangoHasta))
		{
			echo translate('Msg_A_Range_To_Must_Enter',$GLOBALS['lang']);
			return;	
		}

		if(!is_int(intval($largoBin)))
		{
			echo translate('Msg_A_Bin_Length_Must_Enter_A_Whole',$GLOBALS['lang']);
			return;	
		}
		
		if(!is_numeric((double)$rangoDesde))
		{
			echo translate('Msg_A_Range_From_Must_Enter_A_Whole',$GLOBALS['lang']);
			return;	
		}

		if(!is_numeric((double)$rangoHasta))
		{
			echo translate('Msg_A_Range_To_Must_Enter_A_Whole',$GLOBALS['lang']);
			return;	
		}

		if(strlen($rangoDesde) < strlen($largoBin) || strlen($rangoHasta) < strlen($largoBin))
		{
			echo translate('Msg_Range_From_Or_To_Must_Least_Length_Bin_Entered',$GLOBALS['lang']);
			return;				
		}
		
		if(((double)$rangoHasta) < ((double)$rangoDesde))
		{
			echo translate('Msg_Range_From_Bin_Cant_be_older_To',$GLOBALS['lang']);
			return;				
		}

		if(strlen($rangoDesde) != strlen($rangoHasta))
		{
			echo translate('Msg_Range_From_Must_Have_Same_Number_Of_Numbers',$GLOBALS['lang']);
			return;				
		}		
		
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
		
		if($stmt2 = $mysqli->prepare("SELECT b.bin_id FROM tef.bines b WHERE b.bin_id <> ? AND b.payment_method_id IN (SELECT b2.payment_method_id FROM tef.bines b2 WHERE b2.payment_method_id = b.payment_method_id AND CAST(b2.range_from AS INT) <= ? AND CAST(b2.range_to AS INT) >= ? AND b2.bin_id <> ?)"))
		{
			$stmt2->bind_param('iiii', $idBin, $rangoDesde, $rangoHasta, $idBin);
			$stmt2->execute();    
			$stmt2->store_result();
		
			$totR2 = $stmt2->num_rows;

			if($totR2 > 0)
			{
				echo translate('Msg_Range_From_And_To_Overlap_Other_Exists',$GLOBALS['lang']);
				return;
			}
			else
			{					
				$stmt2->free_result();
				$stmt2->close();
	
				$mysqli->autocommit(FALSE);
				
				if(!$stmt10 = $mysqli->prepare("UPDATE tef.bines SET range_from = ?, range_to = ?, payment_method_id = ?, bin_length = ?, update_timestamp = ? WHERE bin_id = ?"))
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
					$stmt10->bind_param('ssiisi', $rangoDesde, $rangoHasta, $idTarjeta, $largoBin, $updateTimestamp, $idBin);
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
				
				if($stmt = $mysqli->prepare("SELECT b.bin_id, pm.payment_method_id, pm.payment_method_description, b.range_from, b.range_to, b.bin_length FROM tef.bines b, tef.paymentmethods pm  WHERE b.payment_method_id = pm.payment_method_id ORDER BY CAST(b.range_from AS INT) LIMIT 5000"))
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
					
					echo translate('Msg_Modify_Bin_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']).$mysqli->error;
			return;				
		}
?>