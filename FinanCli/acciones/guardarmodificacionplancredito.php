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
		
		$idPlanCredito=htmlspecialchars($_POST["idPlanCredito"], ENT_QUOTES, 'UTF-8');
		
		$nombre=htmlspecialchars($_POST["nombre"], ENT_QUOTES, 'UTF-8');
		$descripcion=htmlspecialchars($_POST["descripcion"], ENT_QUOTES, 'UTF-8');
		$cantidadCuotas=htmlspecialchars($_POST["cantidadCuotas"], ENT_QUOTES, 'UTF-8');
		$interesFijo=htmlspecialchars($_POST["interesFijo"], ENT_QUOTES, 'UTF-8');
		$tipoDiferimientoCuota=htmlspecialchars($_POST["tipoDiferimientoCuota"], ENT_QUOTES, 'UTF-8');
		$cadena=htmlspecialchars($_POST["cadena"], ENT_QUOTES, 'UTF-8');
			
		if($stmt = $mysqli->prepare("SELECT pc.id FROM finan_cli.plan_credito pc WHERE pc.nombre LIKE(?) AND pc.id <> ?"))
		{
			$stmt->bind_param('si', $nombre, $idPlanCredito);
			$stmt->execute();    
			$stmt->store_result();
								
			$totR = $stmt->num_rows;

			if($totR > 0)
			{
				echo translate('Msg_A_Credit_Plan_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{					
				if($stmt2 = $mysqli->prepare("SELECT p.valor FROM finan_cli.parametros p WHERE p.nombre = 'maxima_cantidad_cuotas_plan_credito'"))
				{
					$stmt2->execute();    
					$stmt2->store_result();
				
					$stmt2->bind_result($cantidad_cuotas_permitidas_plan_credito_parametro);
					$stmt2->fetch();

					$totR2 = $stmt2->num_rows;
					if($totR2 == 0)
					{
						$stmt->free_result();
						$stmt->close();
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;	
					}
					
					if($cantidadCuotas > $cantidad_cuotas_permitidas_plan_credito_parametro)
					{
						$stmt->free_result();
						$stmt->close();
						echo translate('Msg_Quota_Limit_Exceeded_Credit_Plan',$GLOBALS['lang']);
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
				
				if($stmt3 = $mysqli->prepare("SELECT pc.id, pc.nombre, pc.descripcion, pc.cantidad_cuotas, pc.interes_fijo, pc.id_tipo_diferimiento_cuota, pc.id_cadena FROM finan_cli.plan_credito pc WHERE pc.id = ?"))
				{
					$stmt3->bind_param('i', $idPlanCredito);
					$stmt3->execute();    
					$stmt3->store_result();
					
					$stmt3->bind_result($id_credit_plan_a, $name_credit_plan_a, $description_credit_plan_a, $cantidad_cuotas_credit_plan_a, $interes_fijo_credit_plan_a, $diferimiento_cuota_credit_plan_a, $cadena_credit_plan_a);				
				}
				else
				{
					$stmt->free_result();
					$stmt->close();
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);					
				}
				
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				if(!$stmt10 = $mysqli->prepare("UPDATE finan_cli.plan_credito SET nombre = ?, descripcion = ?, cantidad_cuotas = ?, interes_fijo = ?, id_tipo_diferimiento_cuota = ?, id_cadena = ? WHERE id = ?"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else 
				{
					$stmt10->bind_param('ssiiiii', $nombre, $descripcion, $cantidadCuotas, $interesFijo, $tipoDiferimientoCuota, $cadena, $idPlanCredito);
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
				$stmt3->fetch();					
				$valor_log_user = "NUEVO: UPDATE finan_cli.plan_credito SET nombre = ".$nombre.", descripcion = ".$descripcion.", cantidad_cuotas = ".$cantidadCuotas.", interes_fijo = ".$interesFijo.", id_tipo_diferimiento_cuota = ".$tipoDiferimientoCuota.", id_cadena = ".$cadena." WHERE id = ".$idPlanCredito." -- ANTERIOR: UPDATE finan_cli.plan_credito SET nombre = ".$name_credit_plan_a.", descripcion = ".$description_credit_plan_a.", cantidad_cuotas = ".$cantidad_cuotas_credit_plan_a.", interes_fijo = ".$interes_fijo_credit_plan_a.", id_tipo_diferimiento_cuota = ".$diferimiento_cuota_credit_plan_a.", id_cadena = ".$cadena_credit_plan_a." WHERE id = ".$idPlanCredito;

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
					$motivo = 29;
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
				
				$stmt3->free_result();
				$stmt3->close();				
				
				if($stmt = $mysqli->prepare("SELECT pc.id, pc.nombre, pc.descripcion, pc.cantidad_cuotas, pc.interes_fijo, par.valor, c.razon_social FROM finan_cli.plan_credito pc, finan_cli.cadena c, finan_cli.parametros par WHERE pc.id_cadena = c.id AND pc.id_tipo_diferimiento_cuota = par.id ORDER BY pc.cantidad_cuotas")) 
				{
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
						
						$array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Credit_Plan',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Removed_Credit_Plan',$GLOBALS['lang']).'\',\''.$id_credit_plan.'\',\''.$name_credit_plan.'\')"><i class="fas fa-trash-alt"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Credit_Plan',$GLOBALS['lang']).'" onclick="modificarPlanCredito(\''.$id_credit_plan.'\',\''.$name_credit_plan.'\')"><i class="fas fa-edit"></i></button>';
						
						$posicion++;
					}
					
					echo translate('Msg_Modify_Credit_Plan_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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