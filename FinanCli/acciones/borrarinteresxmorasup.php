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
		
		$idInteresXMora=htmlspecialchars ($_POST["idInteresXMora"], ENT_QUOTES, 'UTF-8');
		
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

		if($stmt502 = $mysqli->prepare("SELECT pc.id FROM finan_cli.interes_x_mora ixm, finan_cli.plan_credito pc WHERE pc.id = ixm.id_plan_credito AND ixm.id = ?"))
		{
			$stmt502->bind_param('i', $idInteresXMora);
			$stmt502->execute();    
			$stmt502->store_result();
		
			$totR502 = $stmt502->num_rows;

			if($totR502 > 0)
			{
				$stmt502->bind_result($id_plan_credito_control);
				$stmt502->fetch();

				$stmt502->free_result();
				$stmt502->close();				
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
		
		if ($stmt501 = $mysqli->prepare("SELECT pc.id FROM finan_cli.plan_credito pc WHERE pc.id = ? AND pc.id_cadena = ?")) 
		{
			$stmt501->bind_param('ii', $id_plan_credito_control, $id_cadena_user);
			$stmt501->execute();    
			$stmt501->store_result();
	 
			$totR501 = $stmt501->num_rows;
			if($totR501 > 0)
			{
				$stmt501->bind_result($id_sucursal_valid_user);
				$stmt501->fetch();

				$stmt501->free_result();
				$stmt501->close();				
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
		
		if($stmt = $mysqli->prepare("SELECT ixm.id, ixm.cantidad_dias, ixm.interes, pc.nombre FROM finan_cli.interes_x_mora ixm, finan_cli.plan_credito pc WHERE pc.id = ixm.id_plan_credito AND ixm.id = ?"))
		{
			$stmt->bind_param('i', $idInteresXMora);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				$stmt->free_result();
				$stmt->close();
				echo translate('Msg_A_Interest_For_Late_Payment_Not_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{								
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				$stmt->bind_result($id_interes_x_mora_a, $cantidad_dias_interes_x_mora_a, $interes_x_mora_a, $plan_credito_interes_x_mora_a);	
				
				if(!$stmt10 = $mysqli->prepare("DELETE FROM finan_cli.interes_x_mora WHERE id = ?"))
				{
					echo $mysqli->error;
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$stmt10->bind_param('i', $idInteresXMora);
					if(!$stmt10->execute())
					{
						echo $mysqli->error;
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;						
					}
				}
	
				$date_registro = date("YmdHis");
				$date_registro2 = date("Y-m-d H:i:s");					
				$stmt->fetch();
				$valor_log_user = "DELETE finan_cli.interes_x_mora --> id: ".$id_interes_x_mora_a." - Cantidad Dias: ".$cantidad_dias_interes_x_mora_a." - Interes: ".$interes_x_mora_a." - Plan Credito = ".$plan_credito_interes_x_mora_a." WHERE id = ".$idInteresXMora;

				if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
				{
					echo $mysqli->error;
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$motivo = 34;
					$stmt->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo, $valor_log_user);
					if(!$stmt->execute())
					{
						echo $mysqli->error;
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;						
					}
				}				
										
				$mysqli->commit();
				$mysqli->autocommit(TRUE);
				
				if($stmt = $mysqli->prepare("SELECT ixm.id, ixm.cantidad_dias, ixm.interes, pc.nombre, ixm.recurrente FROM finan_cli.interes_x_mora ixm, finan_cli.plan_credito pc WHERE pc.id = ixm.id_plan_credito AND pc.id_cadena = ? ORDER BY pc.cantidad_cuotas, ixm.cantidad_dias")) 
				{
					$stmt->bind_param('i', $id_cadena_user);
					$stmt->execute();    
					$stmt->store_result();
					
					$stmt->bind_result($id_interes_x_mora, $cantidad_dias_interes_x_mora, $interes_x_mora, $plan_credito_interes_x_mora, $recurrente_interes_x_mora);
										
					$array[0] = array();
					$posicion = 0;
					while($stmt->fetch())
					{
						$array[$posicion]['cantidaddias'] = $cantidad_dias_interes_x_mora;
						$array[$posicion]['interes'] = $interes_x_mora;
						$array[$posicion]['plancredito'] = $plan_credito_interes_x_mora;
						if($recurrente_interes_x_mora == 1) $array[$posicion]['recurrente'] = translate('Lbl_Button_YES',$GLOBALS['lang']);
						else $array[$posicion]['recurrente'] = translate('Lbl_Button_NO',$GLOBALS['lang']);
						
						$array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Interest_For_Late_Payment',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Removed_Interest_For_Late_Payment',$GLOBALS['lang']).'\',\''.$id_interes_x_mora.'\')"><i class="fas fa-trash-alt"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Interest_For_Late_Payment',$GLOBALS['lang']).'" onclick="modificarInteresXMora(\''.$id_interes_x_mora.'\')"><i class="fas fa-edit"></i></button>';
						
						$posicion++;
					}
					
					echo translate('Msg_Remove_Interest_For_Late_Payment_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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