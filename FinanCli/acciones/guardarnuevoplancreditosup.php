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
		
		$nombre=htmlspecialchars($_POST["nombre"], ENT_QUOTES, 'UTF-8');
		$descripcion=htmlspecialchars($_POST["descripcion"], ENT_QUOTES, 'UTF-8');
		$cantidadCuotas=htmlspecialchars($_POST["cantidadCuotas"], ENT_QUOTES, 'UTF-8');
		$interesFijo=htmlspecialchars($_POST["interesFijo"], ENT_QUOTES, 'UTF-8');
		$tipoDiferimientoCuota=htmlspecialchars($_POST["tipoDiferimientoCuota"], ENT_QUOTES, 'UTF-8');
		$cadena=htmlspecialchars($_POST["cadena"], ENT_QUOTES, 'UTF-8');
		
		if($cantidadCuotas < 0 || $interesFijo < 0)
		{
			echo translate('Negative_Numbers_Are_Not_Allowed',$GLOBALS['lang']);
			return;
		}

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

		if ($id_cadena_user != $cadena) 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}	
			
		if($stmt = $mysqli->prepare("SELECT pc.id FROM finan_cli.plan_credito pc WHERE pc.nombre LIKE(?) AND pc.id_cadena = ?"))
		{
			$stmt->bind_param('si', $nombre, $id_cadena_user);
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
				
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.plan_credito(nombre,descripcion,cantidad_cuotas,interes_fijo,id_tipo_diferimiento_cuota,id_cadena) VALUES (?,?,?,?,?,?)"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else 
				{
					$stmt10->bind_param('ssiiii', $nombre, $descripcion, $cantidadCuotas, $interesFijo, $tipoDiferimientoCuota, $cadena);
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
				$valor_log_user = "INSERT INTO finan_cli.plan_credito(nombre,descripcion,cantidad_cuotas,interes_fijo,id_tipo_diferimiento_cuota,id_cadena) VALUES (".$nombre.",".str_replace('\'','',$descripcion).",".$cantidadCuotas.",".$interesFijo.",".$tipoDiferimientoCuota.",".$cadena.")";

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
					$motivo = 28;
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
						
						$array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Credit_Plan',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Removed_Credit_Plan',$GLOBALS['lang']).'\',\''.$id_credit_plan.'\',\''.$name_credit_plan.'\')"><i class="fas fa-trash-alt"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Credit_Plan',$GLOBALS['lang']).'" onclick="modificarPlanCredito(\''.$id_credit_plan.'\',\''.$name_credit_plan.'\')"><i class="fas fa-edit"></i></button>';
						
						$posicion++;
					}
					
					echo translate('Msg_New_Credit_Plan_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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