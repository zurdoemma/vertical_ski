<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');return;}
		if (!verificar_permisos_usuario()){header('Location:../sinautorizacion.php');return;}

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
		
		$tokenVECC=htmlspecialchars($_POST["tokenVECC"], ENT_QUOTES, 'UTF-8');
		$tipoDocumento=htmlspecialchars($_POST["tipoDocumento"], ENT_QUOTES, 'UTF-8');
		$documento=htmlspecialchars($_POST["documento"], ENT_QUOTES, 'UTF-8');
		
		$genero=htmlspecialchars($_POST["genero"], ENT_QUOTES, 'UTF-8');
		
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
		
		if($stmt4 = $mysqli->prepare("SELECT c.id FROM finan_cli.cliente c WHERE c.tipo_documento = ? AND c.documento = ?"))
		{
			$stmt4->bind_param('is', $tipoDocumento, $documento);
			$stmt4->execute();    
			$stmt4->store_result();
			
			$totR4 = $stmt4->num_rows;

			if($totR4 > 0)
			{
				echo translate('Msg_Client_Exist',$GLOBALS['lang']);
				return;
			}			
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		
		$stmt4->free_result();
		$stmt4->close();

		$consEFCAyT = "SELECT cef.id, cef.resultado_xml FROM finan_cli.consulta_estado_financiero cef WHERE cef.tipo_documento = ? AND cef.documento = ? AND cef.token = ? AND cef.validado IN (0,1)";
		if($stmt = $mysqli->prepare($consEFCAyT))
		{
			$stmt->bind_param('iss', $tipoDocumento, $documento, $tokenVECC);
			$stmt->execute();    
			$stmt->store_result();
			
			$totR = $stmt->num_rows;

			if($totR > 0)
			{
				$stmt->bind_result($id_estado_financiero_cliente, $resultado_xml_estado_financiero_cliente);
				$stmt->fetch();
				
				$estado_fin_cli = new SimpleXMLElement($resultado_xml_estado_financiero_cliente);
				foreach ($estado_fin_cli->Existencia_Fisica_Resu[0]->row as $recEFCAC) 
				{
					$camposAutoC = $recEFCAC->t_docu.'|'.$recEFCAC->ape_nom.'|'.$recEFCAC->fecha_nacimiento.'|'.$recEFCAC->cdi.'|'.$recEFCAC->direc_calle.'|'.$recEFCAC->provincia.'|'.$recEFCAC->localidad.'|'.$recEFCAC->codigo_postal;
					break;
				}
				foreach ($estado_fin_cli->EMAILS[0]->row as $recEMAILSAC) 
				{
					$camposAutoC = $camposAutoC.'|'.$recEMAILSAC->email;
					break;
				}
				
				echo translate('Msg_Auto_Complete_Data_Client_OK',$GLOBALS['lang']).'=:=:=:'.$camposAutoC.'=::=::=::'.$tokenVECC;
				return;
			}			
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		
		if($stmt = $mysqli->prepare("SELECT cef.id, cef.fecha, cef.resultado_xml, cef.token, cef.validado FROM finan_cli.consulta_estado_financiero cef WHERE cef.tipo_documento = ? AND cef.documento = ? ORDER BY cef.fecha DESC"))
		{
			$stmt->bind_param('is', $tipoDocumento, $documento);
			$stmt->execute();    
			$stmt->store_result();
			
			$totR = $stmt->num_rows;

			if($totR > 0)
			{
				if($stmt41 = $mysqli->prepare("SELECT p.valor FROM finan_cli.parametros p WHERE p.nombre = 'cantidad_días_consulta_db_estado_financiero_clientes'"))
				{
					$stmt41->execute();    
					$stmt41->store_result();
					
					$totR41 = $stmt41->num_rows;

					if($totR41 > 0)
					{
						$stmt->bind_result($id_estado_financiero_cliente_db, $fecha_estado_financiero_cliente_db, $resultado_xml_estado_financiero_cliente_db, $token_estado_financiero_cliente_db, $validacion_estado_financiero_cliente_db);
						$stmt->fetch();
						
						$stmt41->bind_result($cantidad_dias_est_financiero_db);
						$stmt41->fetch();
						
						$fechaObtDB = substr($fecha_estado_financiero_cliente_db, 0, 4).'-'.substr($fecha_estado_financiero_cliente_db, 4, 2).'-'.substr($fecha_estado_financiero_cliente_db, 6, 2).' '.substr($fecha_estado_financiero_cliente_db, 8, 2).':'.substr($fecha_estado_financiero_cliente_db, 10, 2).':'.substr($fecha_estado_financiero_cliente_db, 12, 2);
						$fechaInfDB = new DateTime($fechaObtDB);
						$fechaAct = new DateTime();
						$difDias = $fechaAct->diff($fechaInfDB);

						if($cantidad_dias_est_financiero_db > $difDias->days)
						{
							$estado_fin_cli = new SimpleXMLElement($resultado_xml_estado_financiero_cliente_db);
							foreach ($estado_fin_cli->Existencia_Fisica_Resu[0]->row as $recEFCAC) 
							{
								$camposAutoC = $recEFCAC->t_docu.'|'.$recEFCAC->ape_nom.'|'.$recEFCAC->fecha_nacimiento.'|'.$recEFCAC->cdi.'|'.$recEFCAC->direc_calle.'|'.$recEFCAC->provincia.'|'.$recEFCAC->localidad.'|'.$recEFCAC->codigo_postal;
								break;
							}
							foreach ($estado_fin_cli->EMAILS[0]->row as $recEMAILSAC) 
							{
								$camposAutoC = $camposAutoC.'|'.$recEMAILSAC->email;
								break;
							}
							
							echo translate('Msg_Auto_Complete_Data_Client_OK',$GLOBALS['lang']).'=:=:=:'.$camposAutoC.'=::=::=::'.$token_estado_financiero_cliente_db;
							return;
						}
						else
						{
							$resultado_finan_cli_final = consulta_estado_financiero_cliente($tipoDocumento, $documento, 9999999999, $genero);
								
							if(strpos($resultado_finan_cli_final, translate('Msg_Financial_Statement_Was_Consulted_Successfully',$GLOBALS['lang'])) !== false && strpos($resultado_finan_cli_final, '560') === false)
							{
								$resultado_finan_cli_final = str_replace(translate('Msg_Financial_Statement_Was_Consulted_Successfully',$GLOBALS['lang']), "", $resultado_finan_cli_final);
								
								$estado_fin_cli = new SimpleXMLElement($resultado_finan_cli_final);
								foreach ($estado_fin_cli->Existencia_Fisica_Resu[0]->row as $recEFCAC) 
								{
									$camposAutoC = $recEFCAC->t_docu.'|'.$recEFCAC->ape_nom.'|'.$recEFCAC->fecha_nacimiento.'|'.$recEFCAC->cdi.'|'.$recEFCAC->direc_calle.'|'.$recEFCAC->provincia.'|'.$recEFCAC->localidad.'|'.$recEFCAC->codigo_postal;
									$cuitCuil = $recEFCAC->cdi;
									break;
								}
								foreach ($estado_fin_cli->EMAILS[0]->row as $recEMAILSAC) 
								{
									$camposAutoC = $camposAutoC.'|'.$recEMAILSAC->email;
									break;
								}								
								$mysqli->autocommit(FALSE);
								$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
								
								$insertEFCDB = "INSERT INTO finan_cli.consulta_estado_financiero(tipo_documento,documento,fecha,resultado_xml,usuario,cuit_cuil,token,validado,id_cadena) VALUES (?,?,?,?,?,?,?,?,?)";
								if(!$stmt10 = $mysqli->prepare($insertEFCDB))
								{
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
									return;
								}
								else
								{
									$date_registro_cef_db = date("YmdHis");
									$tokenECF = md5(uniqid(rand(), true));
									$tokenECF = hash('sha512', $tokenECF);
									$validadoECF = 0;
									$stmt10->bind_param('issssisii', $tipoDocumento, $documento, $date_registro_cef_db, $resultado_finan_cli_final, $_SESSION['username'], $cuitCuil, $tokenECF, $validadoECF, $id_cadena_user);
									if(!$stmt10->execute())
									{
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
										return;						
									}
									
									$mysqli->commit();
									$mysqli->autocommit(TRUE);
									
									echo translate('Msg_Auto_Complete_Data_Client_OK',$GLOBALS['lang']).'=:=:=:'.$camposAutoC.'=::=::=::'.$tokenECF;
									return;
								}
							}
							else
							{
								echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
								return;
							}
						}
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
			}
			else
			{
				$resultado_finan_cli_final = consulta_estado_financiero_cliente($tipoDocumento, $documento, 9999999999, $genero);
					
				if(strpos($resultado_finan_cli_final, translate('Msg_Financial_Statement_Was_Consulted_Successfully',$GLOBALS['lang'])) !== false && strpos($resultado_finan_cli_final, '560') === false)
				{
					$resultado_finan_cli_final = str_replace(translate('Msg_Financial_Statement_Was_Consulted_Successfully',$GLOBALS['lang']), "", $resultado_finan_cli_final);
					
					$estado_fin_cli = new SimpleXMLElement($resultado_finan_cli_final);
					foreach ($estado_fin_cli->Existencia_Fisica_Resu[0]->row as $recEFCAC) 
					{
						$camposAutoC = $recEFCAC->t_docu.'|'.$recEFCAC->ape_nom.'|'.$recEFCAC->fecha_nacimiento.'|'.$recEFCAC->cdi.'|'.$recEFCAC->direc_calle.'|'.$recEFCAC->provincia.'|'.$recEFCAC->localidad.'|'.$recEFCAC->codigo_postal;
						$cuitCuil = $recEFCAC->cdi;
						break;
					}
					foreach ($estado_fin_cli->EMAILS[0]->row as $recEMAILSAC) 
					{
						$camposAutoC = $camposAutoC.'|'.$recEMAILSAC->email;
						break;
					}
					
					$mysqli->autocommit(FALSE);
					$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
					
					$insertEFCDB2 = "INSERT INTO finan_cli.consulta_estado_financiero(tipo_documento,documento,fecha,resultado_xml,usuario,cuit_cuil,token,validado,id_cadena) VALUES (?,?,?,?,?,?,?,?,?)";
					if(!$stmt10 = $mysqli->prepare($insertEFCDB2))
					{
						echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;
					}
					else
					{
						$date_registro_cef_db = date("YmdHis");
						$tokenECF = md5(uniqid(rand(), true));
						$tokenECF = hash('sha512', $tokenECF);
						$validadoECF = 0;
						$stmt10->bind_param('issssisii', $tipoDocumento, $documento, $date_registro_cef_db, $resultado_finan_cli_final, $_SESSION['username'], $cuitCuil, $tokenECF, $validadoECF, $id_cadena_user);
						if(!$stmt10->execute())
						{
							$mysqli->autocommit(TRUE);
							$stmt->free_result();
							$stmt->close();
							echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
							return;						
						}
						
						$mysqli->commit();
						$mysqli->autocommit(TRUE);
						
						echo translate('Msg_Auto_Complete_Data_Client_OK',$GLOBALS['lang']).'=:=:=:'.$camposAutoC.'=::=::=::'.$tokenECF;
						return;						
					}
				}
				else
				{
					echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
					return;
				}				
			}				
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
			
		return;
?>