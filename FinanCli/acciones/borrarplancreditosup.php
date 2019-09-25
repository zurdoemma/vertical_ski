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
		
		$idPlanCredito=htmlspecialchars ($_POST["idPlanCredito"], ENT_QUOTES, 'UTF-8');
		
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
		
		if($stmt = $mysqli->prepare("SELECT pc.id, pc.nombre, pc.descripcion, pc.cantidad_cuotas, pc.interes_fijo, pc.id_tipo_diferimiento_cuota, pc.id_cadena FROM finan_cli.plan_credito pc WHERE pc.id = ?"))
		{
			$stmt->bind_param('i', $idPlanCredito);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				$stmt->free_result();
				$stmt->close();
				echo translate('Msg_A_Credit_Plan_Not_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{				
				if($stmt2 = $mysqli->prepare("SELECT c.id FROM finan_cli.credito c WHERE c.id_plan_credito = ?"))
				{
					$stmt2->bind_param('i', $idPlanCredito);
					$stmt2->execute();    
					$stmt2->store_result();
				
					$totR2 = $stmt2->num_rows;

					if($totR2 > 0)
					{
						$stmt2->free_result();
						$stmt2->close();
						echo translate('Msg_Credit_Plan_Not_Remove_Because_Associated_Credit',$GLOBALS['lang']);
						return;
					}
					
					$stmt2->free_result();
					$stmt2->close();
				}
				else	
				{
					$stmt->free_result();
					$stmt->close();
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;	
				}

				if($stmt4 = $mysqli->prepare("SELECT pcp.id_plan_credito FROM finan_cli.perfil_credito_x_plan pcp WHERE pcp.id_plan_credito = ?"))
				{
					$stmt4->bind_param('i', $idPlanCredito);
					$stmt4->execute();    
					$stmt4->store_result();
				
					$totR4 = $stmt4->num_rows;

					if($totR4 > 0)
					{
						$stmt->free_result();
						$stmt->close();
						echo translate('Msg_Profile_Credit_Not_Remove_Because_Associated_Profile_Credit',$GLOBALS['lang']);
						return;
					}
					
					$stmt4->free_result();
					$stmt4->close();
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
				
				$stmt->bind_result($id_credit_plan_a, $name_credit_plan_a, $description_credit_plan_a, $cantidad_cuotas_credit_plan_a, $interes_fijo_credit_plan_a, $diferimiento_cuota_credit_plan_a, $cadena_credit_plan_a);	
				
				if(!$stmt10 = $mysqli->prepare("DELETE FROM finan_cli.plan_credito WHERE id = ?"))
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
					$stmt10->bind_param('i', $idPlanCredito);
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
				$valor_log_user = "DELETE finan_cli.plan_credito --> id: ".$id_credit_plan_a." - Nombre: ".$name_credit_plan_a." - Descripcion: ".$description_credit_plan_a." - cantidad_cuotas = ".$cantidad_cuotas_credit_plan_a." - interes_fijo = ".$interes_fijo_credit_plan_a." - id_tipo_diferimiento_cuota = ".$diferimiento_cuota_credit_plan_a." - id_cadena = ".$cadena_credit_plan_a." WHERE id = ".$idPlanCredito;

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
					$motivo = 30;
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
				
				if($stmt = $mysqli->prepare("SELECT pc.id, pc.nombre, pc.descripcion, pc.cantidad_cuotas, pc.interes_fijo, par.valor, c.razon_social FROM finan_cli.plan_credito pc, finan_cli.cadena c, finan_cli.parametros par WHERE pc.id_cadena = c.id AND pc.id_tipo_diferimiento_cuota = par.id AND c.id = ? ORDER BY pc.cantidad_cuotas")) 
				{
					$stmt->bind_param('i', $id_cadena_user);
					$stmt->execute();    
					$stmt->store_result();
					
					$stmt->bind_result($id_credit_plan, $name_credit_plan, $description_credit_plan, $cantidad_cuotas_credit_plan, $interes_fijo_credit_plan, $diferimiento_cuota_credit_plan, $cadena_credit_plan);
										
					$array[0] = array();
					$posicion = 0;
					while($stmt->fetch())
					{
						$array[$posicion]['nombre'] = $name_credit_plan;
						$array[$posicion]['cantidadcuotas'] = $cantidad_cuotas_credit_plan;
						$array[$posicion]['interesfijo'] = $interes_fijo_credit_plan;
						$array[$posicion]['diferimientocuotas'] = $diferimiento_cuota_credit_plan;
						$array[$posicion]['cadena'] = $cadena_credit_plan;
						
						$array[$posicion]['acciones'] = '<button type="button" id="borrarPlanCredito'.$id_credit_plan.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Credit_Plan',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Removed_Credit_Plan',$GLOBALS['lang']).'\',\''.$id_credit_plan.'\',\''.$name_credit_plan.'\')"><i class="fas fa-trash-alt"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" id="modificarPlanCredito'.$id_credit_plan.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Credit_Plan',$GLOBALS['lang']).'" onclick="modificarPlanCredito(\''.$id_credit_plan.'\',\''.$name_credit_plan.'\')"><i class="fas fa-edit"></i></button>';
						
						$posicion++;
					}
					
					echo translate('Msg_Remove_Credit_Plan_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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