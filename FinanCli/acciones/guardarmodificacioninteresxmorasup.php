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
		
		$idInteresXMora=htmlspecialchars($_POST["idInteresXMora"], ENT_QUOTES, 'UTF-8');
		
		$cantidadDias=htmlspecialchars($_POST["cantidadDias"], ENT_QUOTES, 'UTF-8');
		$interesXMora=htmlspecialchars($_POST["interes"], ENT_QUOTES, 'UTF-8');
		$planCredito=htmlspecialchars($_POST["planCredito"], ENT_QUOTES, 'UTF-8');
		$recurrente=htmlspecialchars($_POST["recurrente"], ENT_QUOTES, 'UTF-8');
		
		if($cantidadDias < 0 || $interesXMora < 0)
		{
			echo translate('Negative_Numbers_Are_Not_Allowed',$GLOBALS['lang']);
			return;
		}
	
		if($recurrente == 'true')
		{
			$esRecurrente = 1;
		}
		else $esRecurrente = 0; 
			
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

		if ($stmt501 = $mysqli->prepare("SELECT pc.id FROM finan_cli.plan_credito pc WHERE pc.id = ? AND pc.id_cadena = ?")) 
		{
			$stmt501->bind_param('ii', $planCredito, $id_cadena_user);
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
		
		if($stmt = $mysqli->prepare("SELECT ixm.id FROM finan_cli.interes_x_mora ixm WHERE ixm.cantidad_dias = ? AND ixm.id_plan_credito = ? AND ixm.id <> ?"))
		{
			$stmt->bind_param('iii', $cantidadDias, $planCredito, $idInteresXMora);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR > 0)
			{
				echo translate('Msg_A_Interest_For_Late_Payment_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{					
				if($stmt2 = $mysqli->prepare("SELECT p.valor FROM finan_cli.parametros p WHERE p.nombre = 'maxima_cantidad_dias_interes_x_mora'"))
				{
					$stmt2->execute();    
					$stmt2->store_result();
				
					$stmt2->bind_result($cantidad_dias_permitidos_interes_x_mora_parametro);
					$stmt2->fetch();

					$totR2 = $stmt2->num_rows;
					if($totR2 == 0)
					{
						$stmt->free_result();
						$stmt->close();
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;	
					}
					
					if($cantidadDias > $cantidad_dias_permitidos_interes_x_mora_parametro)
					{
						$stmt->free_result();
						$stmt->close();
						echo translate('Msg_Amount_Days_Limit_Exceeded_Interest_For_Late_Payment',$GLOBALS['lang']);
						return;	
					}
				}
				else
				{
					$stmt->free_result();
					$stmt->close();
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;	
				}
				$stmt2->free_result();
				$stmt2->close();
				
				if($stmt3 = $mysqli->prepare("SELECT p.valor FROM finan_cli.parametros p WHERE p.nombre = 'maximo_interes_x_mora'"))
				{
					$stmt3->execute();    
					$stmt3->store_result();
				
					$stmt3->bind_result($interes_permitido_interes_x_mora_parametro);
					$stmt3->fetch();

					$totR3 = $stmt3->num_rows;
					if($totR3 == 0)
					{
						$stmt->free_result();
						$stmt->close();
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;	
					}
					
					if($interesXMora > $interes_permitido_interes_x_mora_parametro)
					{
						$stmt->free_result();
						$stmt->close();
						echo translate('Msg_Limit_Exceeded_Interest_For_Late_Payment',$GLOBALS['lang']);
						return;	
					}
				}
				else
				{
					$stmt->free_result();
					$stmt->close();
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;	
				}
				$stmt3->free_result();
				$stmt3->close();

				if($stmt4 = $mysqli->prepare("SELECT ixm.id, ixm.cantidad_dias, ixm.interes, pc.nombre, ixm.recurrente FROM finan_cli.interes_x_mora ixm, finan_cli.plan_credito pc WHERE ixm.id_plan_credito = pc.id AND ixm.id = ?"))
				{
					$stmt4->bind_param('i', $idInteresXMora);
					$stmt4->execute();    
					$stmt4->store_result();
					
					$stmt4->bind_result($id_interes_x_mora_a, $cantidad_dias_interes_x_mora_a, $interes_x_mora_a, $plan_credito_interes_x_mora_a,  $recurrente_interes_x_mora_a);									
				}
				else
				{
					$stmt->free_result();
					$stmt->close();
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;			
				}
				
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				if(!$stmt10 = $mysqli->prepare("UPDATE finan_cli.interes_x_mora SET cantidad_dias = ?, interes = ?, id_plan_credito = ?, recurrente = ? WHERE id = ?"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else 
				{
					$stmt10->bind_param('iiiii', $cantidadDias, $interesXMora, $planCredito, $esRecurrente, $idInteresXMora);
					if(!$stmt10->execute())
					{
						echo $mysqli->error;
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;						
					}						
				}

				$date_registro = date("YmdHis");
				$date_registro2 = date("Y-m-d H:i:s");
				$stmt4->fetch();
				$valor_log_user = "ANTERIOR: UPDATE finan_cli.interes_x_mora SET cantidad_dias = ".$cantidad_dias_interes_x_mora_a.", interes = ".$interes_x_mora_a.", id_plan_credito = ".$plan_credito_interes_x_mora_a.", recurrente = ".$recurrente_interes_x_mora_a." WHERE id = ".$idInteresXMora. " - NUEVO: UPDATE finan_cli.interes_x_mora SET cantidad_dias = ".$cantidadDias.", interes = ".$interesXMora.", id_plan_credito = ".$planCredito.", recurrente = ".$esRecurrente." WHERE id = ".$idInteresXMora;

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
					$motivo = 35;
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
				
				$stmt4->free_result();
				$stmt4->close();
				
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
						
						$array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Interest_For_Late_Payment',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Removed_Interest_For_Late_Payment',$GLOBALS['lang']).'\',\''.$id_interes_x_mora.'\')"><i class="fas fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Interest_For_Late_Payment',$GLOBALS['lang']).'" onclick="modificarInteresXMora(\''.$id_interes_x_mora.'\')"><i class="fas fa-edit"></i></button>';
						
						$posicion++;
					}
					
					echo translate('Msg_Modify_Interest_For_Late_Payment_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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