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
		

		$idCredito=htmlspecialchars($_POST["idCredito"], ENT_QUOTES, 'UTF-8');
				
		if ($stmt = $mysqli->prepare("SELECT id, fecha, usuario, monto FROM finan_cli.pago_total_credito WHERE id_credito = ?")) 
		{
			$stmt->bind_param('i', $idCredito);  
			$stmt->execute();   
			$stmt->store_result();
	 

			$stmt->bind_result($id_pago_total_db, $fecha_pago_total_db, $usuario_pago_total_db, $montoPago);
			$stmt->fetch();
	 
			if ($stmt->num_rows == 1) 
			{				
				if($stmt93 = $mysqli->prepare("SELECT cc.numero_cuota FROM finan_cli.cuota_credito cc, finan_cli.pago_total_credito ptc, finan_cli.pago_total_credito_x_cuota ptcxc WHERE cc.id = ptcxc.id_cuota_credito AND ptcxc.id_pago_total_credito = ptc.id AND ptc.id = ?"))
				{
					$stmt93->bind_param('i', $id_pago_total_db);
					$stmt93->execute();    
					$stmt93->store_result();
					
					$totR93 = $stmt93->num_rows;
					$cuotasCredito = "";
					if($totR93 > 1)
					{
						$stmt93->bind_result($numero_cuota_pago_total_db);
						while($stmt93->fetch())
						{
							if($cuotasCredito == "") $cuotasCredito = $numero_cuota_pago_total_db;
							else $cuotasCredito = $cuotasCredito.",".$numero_cuota_pago_total_db;
						}
						$stmt93->free_result();
						$stmt93->close();				
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

				if($stmt63 = $mysqli->prepare("SELECT cc.id, cc.numero_cuota, cc.monto_cuota_original, cc.monto_pago FROM finan_cli.cuota_credito cc, finan_cli.credito c, finan_cli.credito_cliente ccli WHERE c.id = ccli.id_credito AND c.id = cc.id_credito AND cc.numero_cuota IN ($cuotasCredito) AND cc.id_credito = ? AND cc.estado = ?"))
				{
					$estadoP = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
					$stmt63->bind_param('is', $idCredito, $estadoP);
					$stmt63->execute();    
					$stmt63->store_result();
					
					$totR63 = $stmt63->num_rows;

					if($totR63 > 0)
					{
						$stmt63->bind_result($id_cuota_credito_db_e, $numero_cuota_db, $monto_cuota_original_db, $monto_pago_cuota_credito_db);
						$idCuotasCredito = "";
						$monto_cuotas_original_db = 0;
						while($stmt63->fetch())
						{
							if(empty($monto_pago_cuota_credito_db))
							{
								echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
								return;					
							}
							
							$monto_cuotas_original_db = $monto_cuotas_original_db + $monto_cuota_original_db;
							if($idCuotasCredito == "") $idCuotasCredito = $id_cuota_credito_db_e;
							else $idCuotasCredito = $idCuotasCredito.",".$id_cuota_credito_db_e;
						}
						
						$stmt63->free_result();
						$stmt63->close();				
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

				
				$idCuotasCreditoRec = explode(",",$idCuotasCredito);
				$monto_interes_cuotas_credito = 0;
				for($i = 0; $i < count($idCuotasCreditoRec); $i++)
				{
					if($stmt64 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM finan_cli.mora_cuota_credito mcc WHERE mcc.id_cuota_credito = ?"))
					{
						$stmt64->bind_param('i', $idCuotasCreditoRec[$i]);
						$stmt64->execute();    
						$stmt64->store_result();
						
						$totR64 = $stmt64->num_rows;
						if($totR64 == 1)
						{
							$stmt64->bind_result($monto_interes_cuota_credito_db);
							$stmt64->fetch();
							
							$monto_interes_cuotas_credito = $monto_interes_cuotas_credito + $monto_interes_cuota_credito_db;
							
							$stmt64->free_result();
							$stmt64->close();				
						}
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;
					}
				}						
				
				$monto_x_cuota = round(($montoPago/count($idCuotasCreditoRec)), 0);
				$monto_pago_acum_cuotas = 0;
				
				$monto_interes_x_cuota = round(($monto_interes_cuotas_credito/count($idCuotasCreditoRec)), 0);
				$monto_interes_acum_cuotas = 0;						
								
				$datosCuotasPagadas = "";						
				for($i = 0; $i < count($idCuotasCreditoRec); $i++)
				{	
					if($datosCuotasPagadas != "") $datosCuotasPagadas = $datosCuotasPagadas.'!';
					
					if($stmt91 = $mysqli->prepare("SELECT cc.numero_cuota, cc.monto_cuota_original FROM finan_cli.cuota_credito cc WHERE cc.id = ?"))
					{
						$stmt91->bind_param('i', $idCuotasCreditoRec[$i]);
						$stmt91->execute();    
						$stmt91->store_result();
						
						$totR91 = $stmt91->num_rows;
						if($totR91 == 1)
						{
							$stmt91->bind_result($numero_cuota_db_e, $monto_cuota_original_db_e);
							$stmt91->fetch();
							
							$stmt91->free_result();
							$stmt91->close();				
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
					
					if(($i+1) == count($idCuotasCreditoRec))
					{
						$monto_pago_cuota_r = $montoPago - $monto_pago_acum_cuotas;
						$monto_pago_acum_cuotas = $monto_pago_acum_cuotas + $monto_pago_cuota_r;
						$monto_interes_cuota_r = $monto_interes_cuotas_credito - $monto_interes_acum_cuotas;
						$monto_interes_acum_cuotas = $monto_interes_acum_cuotas + $monto_interes_cuota_r;
						
						if(!empty($monto_interes_cuota_r)) $datosCuotasPagadas = $datosCuotasPagadas.$numero_cuota_db_e.'¡'.$monto_pago_cuota_r.'¡'.$monto_interes_cuota_r.'¡'.$monto_cuota_original_db_e;
						else $datosCuotasPagadas = $datosCuotasPagadas.$numero_cuota_db_e.'¡'.$monto_pago_cuota_r.'¡0'.'¡'.$monto_cuota_original_db_e;
					}									
					else 
					{
						$monto_pago_cuota_r = $monto_x_cuota;
						$monto_pago_acum_cuotas = $monto_pago_acum_cuotas + $monto_pago_cuota_r;
						
						$monto_interes_cuota_r = $monto_interes_x_cuota;
						$monto_interes_acum_cuotas = $monto_interes_acum_cuotas + $monto_interes_cuota_r;

						if(!empty($monto_interes_cuota_r)) $datosCuotasPagadas = $datosCuotasPagadas.$numero_cuota_db_e.'¡'.$monto_pago_cuota_r.'¡'.$monto_interes_cuota_r.'¡'.$monto_cuota_original_db_e;
						else $datosCuotasPagadas = $datosCuotasPagadas.$numero_cuota_db_e.'¡'.$monto_pago_cuota_r.'¡0'.'¡'.$monto_cuota_original_db_e;									
					}					
				}
				
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				$date_registro = date("YmdHis");				
				$valor_log_user = translate('Msg_Reprint_Pay_Total_Amount_Debt_Credit_Client_db',$GLOBALS['lang']).': '.$idCredito;

				if(!$stmt = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$motivo2 = 78;
					$stmt->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo2, $valor_log_user);
					if(!$stmt->execute())
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
											
				if($stmt68 = $mysqli->prepare("SELECT c.estado, cli.id_titular, cli.nombres, cli.apellidos, s.nombre, ptc.usuario, td.nombre, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente ccli, finan_cli.cliente cli, finan_cli.pago_total_credito ptc, finan_cli.sucursal s, finan_cli.tipo_documento td WHERE c.id = ccli.id_credito AND c.id = ptc.id_credito AND ccli.tipo_documento = cli.tipo_documento AND ccli.documento = cli.documento AND ccli.id_sucursal = s.id AND cli.tipo_documento = td.id AND c.id = ?"))
				{
					$stmt68->bind_param('i', $idCredito);
					$stmt68->execute();    
					$stmt68->store_result();
					
					$totR68 = $stmt68->num_rows;

					if($totR68 > 0)
					{
						$stmt68->bind_result($estado_credito_db_res, $id_titular_cliente_db_res, $nombres_cliente_db_res, $apellidos_cliente_db_res, $nombre_sucursal_db_res, $usuario_registro_pago_cuota_db_res, $tipo_documento_cliente_db_res, $documento_cliente_db_res);
						$stmt68->fetch();
						
						$stmt68->free_result();
						$stmt68->close();
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
								
				if(empty($id_titular_cliente_db_res)) $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang']);
				else $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang']);	
				
				echo translate('Msg_Reprint_Pay_Total_Amount_Debt_Credit_Client_OK',$GLOBALS['lang']).'=:=:=:'.$fecha_pago_total_db.'|'.$idCredito.'|'.count($idCuotasCreditoRec).'|'.$tipo_cuenta_texto_cliente.'|'.$nombres_cliente_db_res.' '.$apellidos_cliente_db_res.'|'.$nombre_sucursal_db_res.'|'.$usuario_pago_total_db.'|'.$montoPago.'|'.$tipo_documento_cliente_db_res.'|'.$documento_cliente_db_res.'|'.$datosCuotasPagadas;
				return;									
			}
			else
			{
				echo translate('Msg_Not_Exist_Voucher_Total_Amount_Debt_Credit',$GLOBALS['lang']);
				return;	
			}

		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}
		
		return;
?>