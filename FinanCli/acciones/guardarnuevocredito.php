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
		
		$tokenVECC=htmlspecialchars($_POST["token2"], ENT_QUOTES, 'UTF-8');
		$tokenVS=htmlspecialchars($_POST["token"], ENT_QUOTES, 'UTF-8');
		$token3=htmlspecialchars($_POST["token3"], ENT_QUOTES, 'UTF-8');
		$tipoDocumento=htmlspecialchars($_POST["tipoDocumento"], ENT_QUOTES, 'UTF-8');
		$documento=htmlspecialchars($_POST["documento"], ENT_QUOTES, 'UTF-8');
		
		$montoCompra=htmlspecialchars($_POST["montoCompra"], ENT_QUOTES, 'UTF-8');
		$montoMaximoCompra=htmlspecialchars($_POST["montoMaximoCompra"], ENT_QUOTES, 'UTF-8');
		$planCredito=htmlspecialchars($_POST["planCredito"], ENT_QUOTES, 'UTF-8');
		
		if($montoCompra < 0)
		{
			echo translate('Negative_Numbers_Are_Not_Allowed',$GLOBALS['lang']);
			return;
		}		

		if ($stmt73 = $mysqli->prepare("SELECT id, nombre, valor FROM finan_cli.parametros WHERE nombre = ?")) 
		{
			$nombreValPar = 'monto_minimo_compra_para_credito';
			$stmt73->bind_param('s', $nombreValPar);
			$stmt73->execute();    // Ejecuta la consulta preparada.
			$stmt73->store_result();
	 
			// Obtiene las variables del resultado.
			$stmt73->bind_result($parameter_id, $parameter_name, $parameter_value);
			$stmt73->fetch();
			
			$totR73 = $stmt73->num_rows;

			if($totR73 > 0)
			{
				if($parameter_value > $montoCompra)
				{
					echo str_replace('%1',''.round(($parameter_value/100.00),2),translate('Msg_Minimum_Amount_Credit_Not_Allowed',$GLOBALS['lang']));
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
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		
		if($stmt47 = $mysqli->prepare("SELECT c.id, c.estado, c.id_titular, c.monto_maximo_credito, c.nombres, c.apellidos, t.numero, c.cuil_cuit, c.id_perfil_credito, td.nombre FROM finan_cli.cliente c, finan_cli.telefono t, finan_cli.cliente_x_telefono ct, finan_cli.tipo_documento td WHERE c.tipo_documento = td.id AND ct.tipo_documento = c.tipo_documento AND ct.documento = c.documento AND t.id = ct.id_telefono AND c.tipo_documento = ? AND c.documento = ?"))
		{
			$stmt47->bind_param('is', $tipoDocumento, $documento);
			$stmt47->execute();    
			$stmt47->store_result();
			
			$totR47 = $stmt47->num_rows;

			if($totR47 == 0)
			{
				echo translate('Msg_Client_Not_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{
				$stmt47->bind_result($id_cliente_db, $estado_cliente_db, $id_titular_cliente_db, $monto_maximo_credito_cliente_db, $nombres_cliente_db, $apellidos_cliente_db, $telefono_cliente_db, $cuil_cuit_cliente_db, $id_perfil_credito_cliente_db, $nombre_tipo_documento_cliente_db);
				$stmt47->fetch();
				
				$cuitCuil = $cuil_cuit_cliente_db;
				if($estado_cliente_db != translate('State_User',$GLOBALS['lang']))
				{
					echo translate('Msg_Disable_Client',$GLOBALS['lang']);
					return;
				}
				else
				{
					if(!empty($id_titular_cliente_db))
					{
						if($stmt48 = $mysqli->prepare("SELECT c.id, c.estado, c.tipo_documento, c.documento FROM finan_cli.cliente c WHERE c.id = ?"))
						{
							$stmt48->bind_param('i', $id_titular_cliente_db);
							$stmt48->execute();    
							$stmt48->store_result();
							
							$totR48 = $stmt48->num_rows;

							if($totR48 > 0)
							{
								$stmt48->bind_result($id_cliente_titular_db, $estado_cliente_titular_db, $tipo_documento_cliente_titular_db, $documento_cliente_titular_db);
								$stmt48->fetch();
								
								if($estado_cliente_titular_db != translate('State_User',$GLOBALS['lang']))
								{
									echo translate('Msg_Disable_Client_Headline',$GLOBALS['lang']);
									return;
								}
								
								$stmt48->free_result();
								$stmt48->close();
							}
							else
							{
								echo translate('Msg_Client_Headline_Not_Exist',$GLOBALS['lang']);
								return;
							}
						}
						else
						{
							echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
							return;
						}
					}
				}
			}				
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']).$mysqli->error;
			return;
		}
			
		if(empty($id_titular_cliente_db)) $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang']);
		else $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang']);
		
		$selectMCC = "SELECT SUM(cu.monto_cuota_original) FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.cuota_credito cu WHERE c.id = cc.id_credito AND c.id = cu.id_credito AND cc.tipo_documento = ? AND cc.documento = ? AND c.estado IN (?,?)";
		if($stmt49 = $mysqli->prepare($selectMCC))
		{
			$est_pend = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
			$est_mora = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
			if(empty($id_titular_cliente_db)) $stmt49->bind_param('isss', $tipoDocumento, $documento, $est_pend, $est_mora);
			else $stmt49->bind_param('isss', $tipo_documento_cliente_titular_db, $documento_cliente_titular_db, $est_pend, $est_mora);
			$stmt49->execute();    
			$stmt49->store_result();
			
			$totR49 = $stmt49->num_rows;

			if($totR49 > 0)
			{
				$stmt49->bind_result($monto_credito_utilizado_db);
				$stmt49->fetch();
				
				$monto_credito_disponible = $monto_maximo_credito_cliente_db - $monto_credito_utilizado_db;
				if($monto_credito_disponible < 0) $monto_credito_disponible = 0;
				
				$stmt49->free_result();
				$stmt49->close();
			}
			else
			{
				$monto_credito_disponible = $monto_maximo_credito_cliente_db;
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}				
				
		$stmt47->free_result();
		$stmt47->close();
		
		if(!empty($id_cliente_titular_db))
		{
			if($stmt40 = $mysqli->prepare("SELECT c.tipo_documento, c.documento, c.cuil_cuit, c.id_genero FROM finan_cli.cliente c WHERE c.id = ?"))
			{
				$stmt40->bind_param('i', $id_cliente_titular_db);
				$stmt40->execute();    
				$stmt40->store_result();
				
				$totR40 = $stmt40->num_rows;

				if($totR40 > 0)
				{
					$stmt40->bind_result($tipo_documento_titular, $documento_titular, $cuit_cuil_titular, $id_genero_titular);
					$stmt40->fetch();
					
					$tipoDocumentoTitular = $tipo_documento_titular;
					$documentoTitular = $documento_titular;
					$cuitCuilTitular = $cuit_cuil_titular;
					$idGeneroTitular = $id_genero_titular;
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
		
		$paso_validacion_estado_financiero = 0;
		
		if($validacionEC == 'true')
		{
			if(!empty($id_cliente_titular_db)) $consEFCAyT = "SELECT cef.id FROM finan_cli.consulta_estado_financiero cef WHERE cef.tipo_documento = ? AND cef.documento = ? AND cef.token = ? AND cef.cuit_cuil = ? AND  cef.tipo_documento_adicional = ? AND cef.documento_adicional = ? AND cef.validado = 1";
			else $consEFCAyT = "SELECT cef.id FROM finan_cli.consulta_estado_financiero cef WHERE cef.tipo_documento = ? AND cef.documento = ? AND cef.token = ? AND cef.cuit_cuil = ? AND cef.validado = 1";
			if($stmt = $mysqli->prepare($consEFCAyT))
			{
				if(!empty($id_cliente_titular_db)) $stmt->bind_param('issiis', $tipoDocumentoTitular, $documentoTitular, $tokenVECC, $cuitCuilTitular, $tipoDocumento, $documento);
				else $stmt->bind_param('issi', $tipoDocumento, $documento, $tokenVECC, $cuitCuil);
				$stmt->execute();    
				$stmt->store_result();
				
				$totR = $stmt->num_rows;

				if($totR > 0)
				{
					if(empty($tokenVS)) $selectVCS = "SELECT e.id FROM finan_cli.estado_cliente e WHERE e.tipo_documento = ? AND e.documento = ? AND e.fecha like ? AND e.id_motivo IN (?,?,?)";
					else $selectVCS = "SELECT e.id, e.token FROM finan_cli.estado_cliente e WHERE e.tipo_documento = ? AND e.documento = ? AND e.fecha like ? AND e.id_motivo IN (?,?,?) AND e.token = ?";
					if($stmt51 = $mysqli->prepare($selectVCS))
					{
						$date_registro_a_s = date("Ymd")."%";
						$motivo = 58;
						$motivo2 = 59;
						$motivo3 = 60;
						if(empty($id_cliente_titular_db)) 
						{
							if(empty($tokenVS)) $stmt51->bind_param('issiii', $tipoDocumento, $documento, $date_registro_a_s, $motivo, $motivo2, $motivo3);
							else $stmt51->bind_param('issiiis', $tipoDocumento, $documento, $date_registro_a_s, $motivo, $motivo2, $motivo3, $tokenVS);
						}
						else
						{ 
							if(empty($tokenVS)) $stmt51->bind_param('issiii', $tipo_documento_cliente_titular_db, $documento_cliente_titular_db, $date_registro_a_s, $motivo, $motivo2, $motivo3);
							else $stmt51->bind_param('issiiis', $tipo_documento_cliente_titular_db, $documento_cliente_titular_db, $date_registro_a_s, $motivo, $motivo2, $motivo3, $tokenVS);
						}
						$stmt51->execute();    
						$stmt51->store_result();
						
						$totR51 = $stmt51->num_rows;

						if($totR51 > 0)
						{						
							$paso_validacion_estado_financiero = 1;
							$stmt51->free_result();
							$stmt51->close();
						}				
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
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
			//if(empty($tokenVS)) $selectVCS = "SELECT e.id FROM finan_cli.estado_cliente e WHERE e.tipo_documento = ? AND e.documento = ? AND e.fecha like ? AND e.id_motivo IN (?,?,?)";
			$selectVCS = "SELECT e.id, e.token FROM finan_cli.estado_cliente e WHERE e.tipo_documento = ? AND e.documento = ? AND e.fecha like ? AND e.id_motivo IN (?,?,?) AND e.token = ?";
			if($stmt51 = $mysqli->prepare($selectVCS))
			{
				$date_registro_a_s = date("Ymd")."%";
				$motivo = 58;
				$motivo2 = 59;
				$motivo3 = 60;
				if(empty($id_cliente_titular_db)) 
				{
					//if(empty($tokenVS)) $stmt51->bind_param('issiii', $tipoDocumento, $documento, $date_registro_a_s, $motivo, $motivo2, $motivo3);
					$stmt51->bind_param('issiiis', $tipoDocumento, $documento, $date_registro_a_s, $motivo, $motivo2, $motivo3, $tokenVS);
				}
				else
				{ 
					//if(empty($tokenVS)) $stmt51->bind_param('issiii', $tipo_documento_cliente_titular_db, $documento_cliente_titular_db, $date_registro_a_s, $motivo, $motivo2, $motivo3);
					$stmt51->bind_param('issiiis', $tipo_documento_cliente_titular_db, $documento_cliente_titular_db, $date_registro_a_s, $motivo, $motivo2, $motivo3, $tokenVS);
				}
				$stmt51->execute();    
				$stmt51->store_result();
				
				$totR51 = $stmt51->num_rows;

				if($totR51 > 0)
				{						
					$paso_validacion_estado_financiero = 1;
					$stmt51->free_result();
					$stmt51->close();
				}				
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;
			}			
		}

		if($paso_validacion_estado_financiero == 0)
		{
			echo translate('Msg_Not_Validated_Status_Credit_Client',$GLOBALS['lang']).$tipoDocumento.'-'.$documento.'-'.$date_registro_a_s.'-'.$motivo.'-'.$motivo2.'-'.$motivo3.'-'.$tokenVECC;
			return;
		}
		
		if($stmt61 = $mysqli->prepare("SELECT s.id_cadena, s.id, s.nombre FROM finan_cli.usuario u, finan_cli.sucursal s WHERE u.id_sucursal = s.id AND u.id = ?"))
		{
			$stmt61->bind_param('s', $_SESSION['username']);
			$stmt61->execute();    
			$stmt61->store_result();
			
			$totR61 = $stmt61->num_rows;

			if($totR61 > 0)
			{
				$stmt61->bind_result($id_cadena_usuario, $id_sucursal_usuario, $nombre_sucursal_usuario);
				$stmt61->fetch();
								
				$stmt61->free_result();
				$stmt61->close();
			}
			else
			{
				echo translate('There_Is_ No_Chain_Associated_With_The_User',$GLOBALS['lang']);
				return;
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		
		$paso_seleccion_plan = 0;
		if($stmt62 = $mysqli->prepare("SELECT pc.cantidad_cuotas, pc.interes_fijo, pc.id_tipo_diferimiento_cuota, pc.nombre FROM finan_cli.perfil_credito_x_plan pcxp, finan_cli.plan_credito pc, finan_cli.cadena c, finan_cli.perfil_credito pcre WHERE pcxp.id_plan_credito = pc.id AND pcxp.id_perfil_credito = pcre.id AND pc.id_cadena = c.id AND pcre.id = ? AND c.id = ? AND pc.id = ?"))
		{
			$stmt62->bind_param('iii', $id_perfil_credito_cliente_db, $id_cadena_usuario, $planCredito);
			$stmt62->execute();    
			$stmt62->store_result();
			
			$totR62 = $stmt62->num_rows;

			if($totR62 > 0)
			{
				$stmt62->bind_result($cantidad_cuotas_plan_credito_s_db, $interes_fijo_plan_credito_s_db, $id_tipo_diferiemiento_cuota_plan_credito_s_db, $nombre_plan_credito_s_db);
				$stmt62->fetch();
				
				$paso_seleccion_plan = 1;
				$stmt62->free_result();
				$stmt62->close();
			}
			else
			{
				echo translate('The_Selected_Credit_Plan_Is_Inconsistent_User_Client',$GLOBALS['lang']).$id_perfil_credito_cliente_db.'-'.$id_cadena_usuario.'-'.$planCredito;
				return;
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}		
		
		if($paso_seleccion_plan == 0)
		{
			echo translate('The_Selected_Credit_Plan_Is_Inconsistent_User_Client',$GLOBALS['lang']);
			return;
		}
		
		if($monto_credito_disponible != $montoMaximoCompra)
		{
			echo translate('The_Amount_Of_Available_Credit_Is_Incosistent_Re_Register_Credit',$GLOBALS['lang']);
			return;
		}
		
		$montoTotalCredito = $montoCompra + ($montoCompra * ($interes_fijo_plan_credito_s_db/100.00));
		
		if($montoTotalCredito > $monto_credito_disponible)
		{
			$selectVCS = "SELECT e.id, e.token FROM finan_cli.estado_cliente e WHERE e.tipo_documento = ? AND e.documento = ? AND e.fecha like ? AND e.id_motivo = ? AND e.token = ?";
			if($stmt44 = $mysqli->prepare($selectVCS))
			{
				$date_registro_a_s_44 = date("Ymd")."%";
				$motivoAE = 64;
				if(empty($id_cliente_titular_db)) $stmt44->bind_param('issis', $tipoDocumento, $documento, $date_registro_a_s_44, $motivoAE, $token3);
				else $stmt44->bind_param('issis', $tipo_documento_cliente_titular_db, $documento_cliente_titular_db, $date_registro_a_s_44, $motivoAE, $token3);
				$stmt44->execute();    
				$stmt44->store_result();
				
				$totR44 = $stmt44->num_rows;

				if($totR44 == 0)
				{
					$stmt44->free_result();
					$stmt44->close();	
			
					echo translate('Msg_Max_Amount_Credit_Client_Exceeded_Not_Validated',$GLOBALS['lang']);
					return;
				}			
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;
			}
			
			$stmt44->free_result();
			$stmt44->close();			
		}
		
		$array[0] = array();
		$monto_x_cuota = round((($montoTotalCredito/100.00)/$cantidad_cuotas_plan_credito_s_db),2);
		$monto_acum_cuotas = 0;
		$cuotas_credito_plan_s = '';
		for ($i = 1; $i <= $cantidad_cuotas_plan_credito_s_db; $i++) 
		{
			if(!empty($cuotas_credito_plan_s) && $cuotas_credito_plan_s != '') $cuotas_credito_plan_s = $cuotas_credito_plan_s.':';
			if($i != $cantidad_cuotas_plan_credito_s_db)
			{
				$array[$i-1]['cuota'] = $i;
				$cuotas_credito_plan_s = $cuotas_credito_plan_s.$i.'!';
				if($i == 1)
				{
					$resulFunF = obtenerFechaInicialCuotaCredito($id_tipo_diferiemiento_cuota_plan_credito_s_db, $mysqli);
					if($resulFunF != translate('Msg_Unknown_Error',$GLOBALS['lang'])) $array[$i-1]['fechavencimiento'] = $resulFunF;
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;
					}
				}					
				else
				{					
					$resulFunF = obtenerFechaSiguienteCuotaCredito($array[$i-2]['fechavencimiento']);
					if($resulFunF != translate('Msg_Unknown_Error',$GLOBALS['lang'])) $array[$i-1]['fechavencimiento'] = $resulFunF;
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;
					}					
				}
				$cuotas_credito_plan_s = $cuotas_credito_plan_s.$array[$i-1]['fechavencimiento'].'!';
				$array[$i-1]['montocuota'] = round(($monto_x_cuota*100.00),2);
				$cuotas_credito_plan_s = $cuotas_credito_plan_s.$array[$i-1]['montocuota'];
				$monto_acum_cuotas = $monto_acum_cuotas + $array[$i-1]['montocuota'];
			}
			else
			{
				$array[$i-1]['cuota'] = $i;
				$cuotas_credito_plan_s = $cuotas_credito_plan_s.$i.'!';
				if($i == 1)
				{
					$resulFunF = obtenerFechaInicialCuotaCredito($id_tipo_diferiemiento_cuota_plan_credito_s_db, $mysqli);
					if($resulFunF != translate('Msg_Unknown_Error',$GLOBALS['lang'])) $array[$i-1]['fechavencimiento'] = $resulFunF;
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;
					}
				}					
				else
				{					
					$resulFunF = obtenerFechaSiguienteCuotaCredito($array[$i-2]['fechavencimiento']);
					if($resulFunF != translate('Msg_Unknown_Error',$GLOBALS['lang'])) $array[$i-1]['fechavencimiento'] = $resulFunF;
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;
					}					
				}
				$cuotas_credito_plan_s = $cuotas_credito_plan_s.$array[$i-1]['fechavencimiento'] + '!';
				$array[$i-1]['montocuota'] = (round((($montoTotalCredito/100.00) - ($monto_acum_cuotas/100.00)),2)*100);
				$cuotas_credito_plan_s = $cuotas_credito_plan_s.$array[$i-1]['montocuota'];
			}
		}
		
		$mysqli->autocommit(FALSE);
		$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
		
		if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado) VALUES (?,?,?,?,?,?)"))
		{
			echo $mysqli->error;
			$mysqli->autocommit(TRUE);
			$stmt->free_result();
			$stmt->close();
			return;
		}
		else
		{
			$estadoIC = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
			$stmt10->bind_param('iiiiis', $cantidad_cuotas_plan_credito_s_db, $montoCompra, $planCredito, $interes_fijo_plan_credito_s_db, $montoTotalCredito, $estadoIC);
			if(!$stmt10->execute())
			{
				echo $mysqli->error;
				$mysqli->autocommit(TRUE);
				$stmt->free_result();
				$stmt->close();
				return;						
			}
			else $idCreditoCliente = $mysqli->insert_id;
		}	

		$date_registro = date("YmdHis");				
		$valor_log_user = "INSERT INTO finan_cli.credito(cantidad_cuotas,monto_compra,id_plan_credito,interes_fijo_plan_credito,monto_credito_original,estado) VALUES (".$cantidad_cuotas_plan_credito_s_db.",".$montoCompra.",".$planCredito.",".$interes_fijo_plan_credito_s_db.",".$montoTotalCredito.",".$estadoIC.")";

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
			$motivo = 61;
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
						
		if(empty($id_cliente_titular_db)) $insertCredCli = "INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (?,?,?,?,?,?)";
		else $insertCredCli = "INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal,tipo_documento_adicional,documento_adicional) VALUES (?,?,?,?,?,?,?,?)";
		if(!$stmt10 = $mysqli->prepare($insertCredCli))
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
			$date_registro = date("YmdHis");
			if(empty($id_cliente_titular_db)) $stmt10->bind_param('isissi', $idCreditoCliente, $date_registro, $tipoDocumento, $documento, $_SESSION['username'], $id_sucursal_usuario);
			else $stmt10->bind_param('isissiis', $idCreditoCliente, $date_registro, $tipoDocumentoTitular, $documentoTitular, $_SESSION['username'], $id_sucursal_usuario, $tipoDocumento, $documento);
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
						
					
		if(empty($id_cliente_titular_db)) $valor_log_user = "INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal) VALUES (".$idCreditoCliente.",".$date_registro.",".$tipoDocumento.",".$documento.",".$_SESSION['username'].",".$id_sucursal_usuario.")";
		else $valor_log_user = "INSERT INTO finan_cli.credito_cliente(id_credito,fecha,tipo_documento,documento,id_usuario,id_sucursal,tipo_documento_adicional,documento_adicional) VALUES (".$idCreditoCliente.",".$date_registro.",".$tipoDocumentoTitular.",".$documentoTitular.",".$_SESSION['username'].",".$id_sucursal_usuario.",".$tipoDocumento.",".$documento.")";
		$date_registro = date("YmdHis");
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
			$motivo = 62;
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
		
		for ($i = 1; $i <= $cantidad_cuotas_plan_credito_s_db; $i++) 
		{
			$fechaVencCuota = str_replace('-','',$array[$i-1]['fechavencimiento']).'235959';
			
			
			if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (?,?,?,?,?)"))
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
				$estadoIC = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
				$stmt10->bind_param('iisis', $idCreditoCliente, $array[$i-1]['cuota'], $fechaVencCuota, $array[$i-1]['montocuota'], $estadoIC);
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
			$valor_log_user = "INSERT INTO finan_cli.cuota_credito(id_credito,numero_cuota,fecha_vencimiento,monto_cuota_original,estado) VALUES (".$idCreditoCliente.",".$array[$i-1]['cuota'].",".$fechaVencCuota.",".$array[$i-1]['montocuota'].",".$estadoIC.")";

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
				$motivo = 63;
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
		}
		
		$mysqli->commit();
		$mysqli->autocommit(TRUE);

		if ($stmt = $mysqli->prepare("SELECT c.id, cc.fecha, td.nombre, cc.documento, c.monto_credito_original, pc.nombre, c.cantidad_cuotas, c.estado FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.cliente cli, finan_cli.plan_credito pc, finan_cli.tipo_documento td WHERE pc.id = c.id_plan_credito AND c.id = cc.id_credito AND cc.tipo_documento = cli.tipo_documento AND cc.documento = cli.documento AND cc.tipo_documento = td.id ORDER BY cc.fecha DESC LIMIT 10")) 
		{
			$stmt->execute();    
			$stmt->store_result();
	 
			$stmt->bind_result($id_credit_client, $date_credit_client, $type_documento_credit_client, $document_credit_client, $amount_credit_client, $name_credit_plan_client, $fees_credit_client, $state_credit_client);
								
			
			$arrayC[0] = array();
			$posicion = 0;
			while($stmt->fetch())
			{
				$arrayC[$posicion]['fecha'] = substr($date_credit_client,6,2).'/'.substr($date_credit_client,4,2).'/'.substr($date_credit_client,0,4);
				$arrayC[$posicion]['tipodocumento'] = $type_documento_credit_client;
				$arrayC[$posicion]['documento'] = $document_credit_client;
				$arrayC[$posicion]['monto'] = '$'.round(($amount_credit_client/100.00),2);
				$arrayC[$posicion]['plancredito'] = $name_credit_plan_client;
				$arrayC[$posicion]['cuotas'] = $fees_credit_client;
				$arrayC[$posicion]['estado'] = $state_credit_client;
				
				if(translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) == $state_credit_client || translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']) == $state_credit_client) $arrayC[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Credit_Client',$GLOBALS['lang']).'" onclick="verCredito('.$id_credit_client.')"><i class="fas fa-eye"></i></button>&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Credit_Client',$GLOBALS['lang']).'" onclick="reImprimirCreditoCliente('.$id_credit_client.')"><i class="fas fa-print"></i></button>&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Generate_PDF_Credit_Client',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfcredito.php?idCredito='.$id_credit_client.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Lbl_Debt_Management2',$GLOBALS['lang']).'" onclick="window.open(\'gestiondeuda.php?doc='.$document_credit_client.'\')"><i class="fas fa-link"></i></button>';
				else $arrayC[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Credit_Client',$GLOBALS['lang']).'" onclick="verCredito('.$id_credit_client.')"><i class="fas fa-eye"></i></button>';												

				$posicion++;
			}
			
			$fecha_cre_pi = date("d-m-Y H:i:s");
			$montoInteresF = $montoTotalCredito-$montoCompra;
			$datosDeImpresion = $idCreditoCliente.'|'.$fecha_cre_pi.'|'.$nombre_sucursal_usuario.'|'.$cantidad_cuotas_plan_credito_s_db.'|'.$array[0]['fechavencimiento'].'|'.$nombre_plan_credito_s_db.'|'.$nombres_cliente_db.' '.$apellidos_cliente_db.'|'.$tipo_cuenta_texto_cliente.'|'.$montoTotalCredito.'|'.$nombre_tipo_documento_cliente_db.'|'.$documento.'|'.$cuotas_credito_plan_s.'|'.$montoCompra.'|'.$montoInteresF; 
			echo translate('Msg_New_Credit_Client_OK',$GLOBALS['lang']).'=:=:=:'.$datosDeImpresion.'=::=::=::'.json_encode($arrayC);
			return;
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;	
		}

		return;

?>