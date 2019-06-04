<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');return;}
		if (!verificar_permisos_admin()){header('Location:../sinautorizacion.php?activauto=1');return;}

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
		
		$cantidadDias=htmlspecialchars($_POST["cantidadDias"], ENT_QUOTES, 'UTF-8');
		$interesXMora=htmlspecialchars($_POST["interes"], ENT_QUOTES, 'UTF-8');
		$planCredito=htmlspecialchars($_POST["planCredito"], ENT_QUOTES, 'UTF-8');
		
		if($cantidadDias < 0 || $interesXMora < 0)
		{
			echo translate('Negative_Numbers_Are_Not_Allowed',$GLOBALS['lang']);
			return;
		}		
		
		if($stmt = $mysqli->prepare("SELECT ixm.id FROM finan_cli.interes_x_mora ixm WHERE ixm.cantidad_dias = ? AND ixm.id_plan_credito = ?"))
		{
			$stmt->bind_param('ii', $cantidadDias, $planCredito);
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
				
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.interes_x_mora(cantidad_dias,interes,id_plan_credito) VALUES (?,?,?)"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else 
				{
					$stmt10->bind_param('iii', $cantidadDias, $interesXMora, $planCredito);
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
				$valor_log_user = "INSERT INTO finan_cli.interes_x_mora(cantidad_dias,interes,id_plan_credito) VALUES (".$cantidadDias.",".$interesXMora.",".$planCredito.")";

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
					$motivo = 33;
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
				
				if($stmt = $mysqli->prepare("SELECT ixm.id, ixm.cantidad_dias, ixm.interes, pc.nombre FROM finan_cli.interes_x_mora ixm, finan_cli.plan_credito pc WHERE pc.id = ixm.id_plan_credito ORDER BY pc.cantidad_cuotas, ixm.cantidad_dias")) 
				{
					$stmt->execute();    
					$stmt->store_result();
					
					$stmt->bind_result($id_interes_x_mora, $cantidad_dias_interes_x_mora, $interes_x_mora, $plan_credito_interes_x_mora);
										
					$array[0] = array();
					$posicion = 0;
					while($stmt->fetch())
					{
						$array[$posicion]['cantidaddias'] = $cantidad_dias_interes_x_mora;
						$array[$posicion]['interes'] = $interes_x_mora;
						$array[$posicion]['plancredito'] = $plan_credito_interes_x_mora;
						
						$array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Interest_For_Late_Payment',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Removed_Interest_For_Late_Payment',$GLOBALS['lang']).'\',\''.$id_interes_x_mora.'\')"><i class="fas fa-trash-alt"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Interest_For_Late_Payment',$GLOBALS['lang']).'" onclick="modificarInteresXMora(\''.$id_interes_x_mora.'\')"><i class="fas fa-edit"></i></button>';
						
						$posicion++;
					}
					
					echo translate('Msg_New_Interest_For_Late_Payment_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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