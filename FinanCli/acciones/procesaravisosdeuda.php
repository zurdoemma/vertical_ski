<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		// ¡Oh, no! Existe un error 'connect_errno', fallando así el intento de conexión
		if ($mysqli->connect_errno) 
		{
			echo translate('Msg_Connect_DB_Error',$GLOBALS['lang']);
			return;
		}
		
		$tokenProceso=htmlspecialchars($_GET["tokenProceso"], ENT_QUOTES, 'UTF-8');
			
		if(empty($tokenProceso))
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}		

		if ($stmt72 = $mysqli->prepare("SELECT valor FROM finan_cli.parametros WHERE nombre = ?")) 
		{
			$nombreValPar = 'cantidad_horas_entre_procesos_auto';
			$stmt72->bind_param('s', $nombreValPar);
			$stmt72->execute(); 
			$stmt72->store_result();
			
			$totR72 = $stmt72->num_rows;

			if($totR72 > 0)
			{
				$stmt72->bind_result($cantidad_horas_entre_procesos_db);
				$stmt72->fetch();	

				$stmt72->free_result();
				$stmt72->close();				
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
		
		if ($stmt73 = $mysqli->prepare("SELECT id FROM finan_cli.parametros WHERE nombre = ? AND valor = ?")) 
		{
			$nombreValPar = 'token_proceso_automatico';
			$stmt73->bind_param('ss', $nombreValPar, $tokenProceso);
			$stmt73->execute();    
			$stmt73->store_result();
	 			
			$totR73 = $stmt73->num_rows;

			if($totR73 > 0)
			{
				$stmt73->bind_result($parameter_id);
				$stmt73->fetch();

				$stmt73->free_result();
				$stmt73->close();				
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
		
		if ($stmt75 = $mysqli->prepare("SELECT valor FROM finan_cli.parametros WHERE nombre = ?")) 
		{
			$nombreValPar = 'cantidad_dias_avisos_x_mora';
			$stmt75->bind_param('s', $nombreValPar);
			$stmt75->execute();    
			$stmt75->store_result();
	 			
			$totR75 = $stmt75->num_rows;

			if($totR75 > 0)
			{
				$stmt75->bind_result($cantidad_dias_permitidos_aviso_x_mora_db);
				$stmt75->fetch();

				$stmt75->free_result();
				$stmt75->close();				
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
		
		if ($stmt74 = $mysqli->prepare("SELECT MAX(fecha) FROM finan_cli.ejecucion_procesos_auto WHERE tipo = 2")) 
		{
			$stmt74->execute();
			$stmt74->store_result();
	 			
			$totR74 = $stmt74->num_rows;

			if($totR74 > 0)
			{
				$stmt74->bind_result($fecha_ultimo_proceso);
				$stmt74->fetch();
			
				$fechaObtDB = substr($fecha_ultimo_proceso, 0, 4).'-'.substr($fecha_ultimo_proceso, 4, 2).'-'.substr($fecha_ultimo_proceso, 6, 2).' '.substr($fecha_ultimo_proceso, 8, 2).':'.substr($fecha_ultimo_proceso, 10, 2).':'.substr($fecha_ultimo_proceso, 12, 2);
				$fechaInfDB = new DateTime($fechaObtDB);
				$fechaAct = new DateTime();
				$difHoras = $fechaAct->diff($fechaInfDB);
				
				if($difHoras->h < $cantidad_horas_entre_procesos_db && $difHoras->days == 0)
				{
					echo str_replace("%1",$cantidad_horas_entre_procesos_db,translate('Msg_The_Automatic_Process_Runs_Every_Hours',$GLOBALS['lang']));
					return;
				}
				
				$stmt74->free_result();
				$stmt74->close();
			}			
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}

		if($stmt51 = $mysqli->prepare("SELECT axm.id, axm.mensaje, axm.id_cuota_credito, axm.estado FROM finan_cli.aviso_x_mora axm WHERE axm.estado IN (?,?) ORDER BY axm.fecha"))
		{
			$estadoAXMPend = translate('Lbl_State_Pending_Default_Notice',$GLOBALS['lang']);
			$estadoAXMCread = translate('Lbl_State_Create_Default_Notice',$GLOBALS['lang']);
			$stmt51->bind_param('ss', $estadoAXMPend, $estadoAXMCread);
			$stmt51->execute();    
			$stmt51->store_result();
			
			$totR51 = $stmt51->num_rows;

			if($totR51 > 0)
			{
				$stmt51->bind_result($id_aviso_x_mora_db, $mensaje_aviso_x_mora_db, $id_cuota_credito_aviso_x_mora_db, $estado_aviso_x_mora_db);
				while($stmt51->fetch())
				{
					
				}
				
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
									
				$date_registro = date("YmdHis");					
				if(!$stmt2 = $mysqli->prepare("INSERT INTO finan_cli.ejecucion_procesos_auto(fecha,comentario,tipo) VALUES (?,?,?)"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$tipoProcesoA = 2;
					$comentario = translate('Msg_The_Automatic_Process_Was_Executed_Correctly',$GLOBALS['lang']);
					$stmt2->bind_param('ssi', $date_registro, $comentario, $tipoProcesoA);
					if(!$stmt2->execute())
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
			}
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}		
		
		
		//ver en que momento grabar si avisos pendientes de procesar
		$mysqli->autocommit(FALSE);
		$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
							
		$date_registro = date("YmdHis");					
		if(!$stmt2 = $mysqli->prepare("INSERT INTO finan_cli.ejecucion_procesos_auto(fecha,comentario,tipo) VALUES (?,?,?)"))
		{
			echo $mysqli->error;
			$mysqli->autocommit(TRUE);
			$stmt->free_result();
			$stmt->close();
			return;
		}
		else
		{
			$tipoProcesoA = 2;
			$comentario = translate('Msg_No_Notice_Debt_Was_Found_To_Process',$GLOBALS['lang']);
			$stmt2->bind_param('ssi', $date_registro, $comentario, $tipoProcesoA);
			if(!$stmt2->execute())
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
		
		return;
?>