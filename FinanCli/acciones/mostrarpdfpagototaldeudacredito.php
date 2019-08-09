<?php 		
		include ('../utiles/funciones.php');
		include ('../fpdf/fpdf.php');
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
		
		$idCredito=htmlspecialchars($_GET["idCredito"], ENT_QUOTES, 'UTF-8');
	
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
				
				$date_registro = date("YmdHis");				
				$valor_log_user = translate('Msg_Generation_PDF_Pay_Total_Amount_Debt_Credit_Client_db',$GLOBALS['lang']).': '.$idCredito;		
					
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
			
				if(!$stmt75 = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt75->free_result();
					$stmt75->close();
					return;
				}
				else
				{
					$motivo = 79;
					$stmt75->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo, $valor_log_user);
					if(!$stmt75->execute())
					{
						echo $mysqli->error;
						$mysqli->autocommit(TRUE);
						$stmt75->free_result();
						$stmt75->close();
						return;						
					}
				}			
						
				$mysqli->commit();
				$mysqli->autocommit(TRUE);
			
				
				class PDF extends FPDF
				{
					// Page header
					function Header()
					{
						// Logo
						//$this->Image('../recursosimg/rusia2018.jpg',30,1,70);
						//$this->Image('../recursosimg/pelotainforme.png',270,100,20);
						$this->SetFont('Helvetica','B',12);
						$this->Text(23,15,translate('Lbl_Title_Text_Print_Credit',$GLOBALS['lang']));
						$this->Ln();					
					}
					 
					// Page footer
					function Footer()
					{
						/**
						// Position at 1.5 cm from bottom
						$this->SetY(-15);
						// Arial italic 8
						$this->SetFont('Helvetica','I',8);
						// Page number
						$this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
						*/
					}
				}
			
				$pdf = new PDF();
				$pdf->SetMargins(15, 10 , 15);
				$pdf->SetTitle(iconv('UTF-8', 'windows-1252', translate('Lbl_Pay_Total_Amount_Debt_Credit_PDF_View',$GLOBALS['lang'])));
				//header
				$pdf->AddPage('P');
				//foter page
				$pdf->AliasNbPages();
				
				$pdf->SetFont('Helvetica','B',8);
				$pdf->Text(15,20,translate('Lbl_Date_Print_Credit',$GLOBALS['lang']).': '.substr($fecha_pago_total_db,6,2).'/'.substr($fecha_pago_total_db,4,2).'/'.substr($fecha_pago_total_db,0,4).' '.substr($fecha_pago_total_db,8,2).':'.substr($fecha_pago_total_db,10,2).':'.substr($fecha_pago_total_db,12,2));
				// Line break
				$pdf->Ln(2);

				$pdf->Text(5,25,'--------------------------------------------------------------');
				$pdf->Ln();
				$pdf->SetFont('Helvetica','B',10);
				$pdf->Text(17,30,iconv('UTF-8', 'windows-1252', translate('Lbl_Print_Type_Voucher_Pay_Total_Amount_Debt_Credit',$GLOBALS['lang'])));
				$pdf->Ln();
				$pdf->SetFont('Helvetica','B',8);			
				$pdf->Text(28,35,translate('Lbl_Print_Voucher_Copy',$GLOBALS['lang']));
				$pdf->Ln();
				$fechaRIC = date("d-m-Y H:i:s");
				$pdf->Text(20,40,str_replace("-","/",$fechaRIC));
				$pdf->Ln();
				$pdf->Text(5,45,'--------------------------------------------------------------');			
				$pdf->Ln(2);

				$pdf->Text(5,50,iconv('UTF-8', 'windows-1252', translate('Lbl_Credit_Number',$GLOBALS['lang'])).': '.$idCredito);
				$pdf->Ln();	
				$pdf->Text(5,55,translate('Lbl_Number_Fees_Pay_Credit',$GLOBALS['lang']).': '.count($idCuotasCreditoRec));
				$pdf->Ln();	
				$pdf->Text(5,60,translate('Lbl_Type_Client_Print',$GLOBALS['lang']).': '.$tipo_cuenta_texto_cliente);
				$pdf->Ln();	
				$pdf->Text(5,65,translate('Lbl_Name_Print_Client',$GLOBALS['lang']).': '.$nombres_cliente_db_res.' '.$apellidos_cliente_db_res);
				$pdf->Ln();
				$pdf->Text(5,70,translate('Lbl_Tender_Print',$GLOBALS['lang']).': '.$nombre_sucursal_db_res);
				$pdf->Ln();	
				$pdf->Text(5,75,translate('Lbl_User_Print',$GLOBALS['lang']).': '.$usuario_pago_total_db);
				$pdf->Ln();
				$pdf->Text(5,80,'--------------------------------------------------------------');
				$pdf->Ln();
				$pdf->SetFont('Helvetica','B',10);
				$pdf->Text(20,85,translate('Lbl_Fees_Pay_Print_Credit',$GLOBALS['lang']));				
				$pdf->Ln(75);
				$pdf->SetLeftMargin(7);			
				$pdf->SetFont('Helvetica','B',8);
				
				$cuotasArr = explode("!",$datosCuotasPagadas);
				$posicionYC = 104;
				$montoTotalCuotasOrig = 0;
				$montoTotalIntereses = 0;				
				for ($i = 0; $i < count($cuotasArr); $i++) 
				{
					$pdf->Ln();
					$datosCuotX = explode("¡",$cuotasArr[$i]);
					
					if($i == 0)
					{
						$pdf->Cell(17,5,translate('Lbl_Fee_Print_Credit',$GLOBALS['lang']),1,0,'C');
						$pdf->Cell(20,5,translate('Lbl_Amount_Original_Fee_Print_Credit',$GLOBALS['lang']),1,0, 'C');
						$pdf->Cell(17,5,iconv('UTF-8', 'windows-1252',translate('Lbl_Amount_Interest_Fee_Print_Credit',$GLOBALS['lang'])),1,0, 'C');
						$pdf->Ln();					
					}
					else $posicionYC = $posicionYC + 5;
					
					$pdf->Cell(17,5,$datosCuotX[0],1,0, 'C');
					$pdf->Cell(20,5,'$'.number_format(($datosCuotX[1]/100.00), 2, ',', '.'),1,0, 'C');
					$pdf->Cell(17,5,'$'.number_format(($datosCuotX[2]/100.00), 2, ',', '.'),1,0, 'C');
					
					$montoTotalCuotasOrig = $montoTotalCuotasOrig + $datosCuotX[3];
					$montoTotalIntereses = $montoTotalIntereses + $datosCuotX[2];
				}
				$pdf->SetMargins(15, 10 , 15);				
				$pdf->Ln();	
				$pdf->Text(5,$posicionYC,'--------------------------------------------------------------');
				$pdf->Ln();
				$pdf->Text(5,$posicionYC+5,translate('Lbl_Amount_Original_Fees_Print_Credit',$GLOBALS['lang']).': $'.number_format(($montoTotalCuotasOrig/100.00), 2, ',', '.'));
				$pdf->Ln();
				if(empty($montoTotalIntereses)) $pdf->Text(5,$posicionYC+10,iconv('UTF-8', 'windows-1252',translate('Lbl_Amount_Interest_Print_Credit',$GLOBALS['lang'])).': $0,00');
				else $pdf->Text(5,$posicionYC+10,iconv('UTF-8', 'windows-1252',translate('Lbl_Amount_Interest_Print_Credit',$GLOBALS['lang'])).': $'.number_format(($montoTotalIntereses/100.00), 2, ',', '.'));
				$pdf->Text(5,$posicionYC+15,iconv('UTF-8', 'windows-1252', translate('Lbl_Amount_Diferential_Fee_Print_Credit',$GLOBALS['lang'])).': $'.number_format(((($montoTotalCuotasOrig+$montoTotalIntereses)-$montoPago)/100.00), 2, ',', '.'));	
				$pdf->Text(10,$posicionYC+20,'*******************************************');
				$pdf->Ln();
				$pdf->SetFont('Helvetica','B',10);	
				$pdf->Text(12,$posicionYC+25,iconv('UTF-8', 'windows-1252', translate('Lbl_Amount_Pay_Print_Credit',$GLOBALS['lang'])).': $'.number_format(($montoPago/100.00), 2, ',', '.'));
				$pdf->Ln();
				$pdf->SetFont('Helvetica','B',8);	
				$pdf->Text(10,$posicionYC+30,'*******************************************');					
				$pdf->Ln(2);
				$pdf->Text(5,$posicionYC+45,'        --------------------------------------------');
				$pdf->Ln();	
				$pdf->Text(28,$posicionYC+50,translate('Lbl_Sign_Print_Credit',$GLOBALS['lang']));
				$pdf->Ln(2);
				$pdf->Text(5,$posicionYC+60,'        --------------------------------------------');
				$pdf->Ln();	
				$pdf->Text(25,$posicionYC+65,iconv('UTF-8', 'windows-1252', translate('Lbl_Clarification_Print_Credit',$GLOBALS['lang'])));	
				$pdf->Ln();
				$pdf->Text(22,$posicionYC+70,$tipo_documento_cliente_db_res.': '.$documento_cliente_db_res);	
				
				$pdf->Output();								
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