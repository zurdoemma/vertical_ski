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

		if ($stmt = $mysqli->prepare("SELECT c.id, cc.fecha, c.cantidad_cuotas, pc.nombre, cli.nombres, cli.apellidos, cli.id_titular, c.monto_credito_original, td.nombre, cli.documento, c.monto_compra, cc.id_usuario FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.cliente cli, finan_cli.plan_credito pc, finan_cli.tipo_documento td WHERE pc.id = c.id_plan_credito AND c.id = cc.id_credito AND cc.tipo_documento = cli.tipo_documento AND cc.documento = cli.documento AND cc.tipo_documento = td.id AND c.id = ? AND c.estado IN (?,?)")) 
		{
			$estado_p_1 = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
			$estado_p_2 = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
			$stmt->bind_param('iss', $idCredito, $estado_p_1, $estado_p_2);
			$stmt->execute();    
			$stmt->store_result();
	 
			$stmt->bind_result($id_credit_client, $fecha_cre_pi, $cantidad_cuotas_plan_credito_s_db, $nombre_plan_credito_s_db, $nombres_cliente_db, $apellidos_cliente_db, $id_titular_cliente_db, $montoTotalCredito, $nombre_tipo_documento_cliente_db, $documento, $montoCompra, $usuario_otorga_credito);			
			
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_Credit_Client_Not_Exist',$GLOBALS['lang']);
				return;	
			}					
			
			
			if($stmt62 = $mysqli->prepare("SELECT cc.fecha_vencimiento FROM finan_cli.cuota_credito cc WHERE cc.id_credito = ? AND cc.estado IN (?,?) ORDER BY cc.numero_cuota"))
			{
				$stmt62->bind_param('iss', $idCredito, $estado_p_1, $estado_p_2);
				$stmt62->execute();    
				$stmt62->store_result();
				
				$totR62 = $stmt62->num_rows;

				if($totR62 > 0)
				{
					$stmt62->bind_result($fecha_vencimiento_cuota_db);
					$stmt62->fetch();
									
					$stmt62->free_result();
					$stmt62->close();
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
			
			if($stmt63 = $mysqli->prepare("SELECT cc.numero_cuota, cc.fecha_vencimiento, cc.monto_cuota_original FROM finan_cli.cuota_credito cc WHERE cc.id_credito = ? ORDER BY cc.numero_cuota"))
			{
				$stmt63->bind_param('i', $idCredito);
				$stmt63->execute();    
				$stmt63->store_result();
				
				$totR63 = $stmt63->num_rows;

				if($totR63 > 0)
				{
					$stmt63->bind_result($numero_cuota_f_db, $fecha_vencimiento_cuota_f_db, $monto_cuota_f_db);
					$cuotas_credito_plan_s = '';
					while($stmt63->fetch())
					{
						if(!empty($cuotas_credito_plan_s) && $cuotas_credito_plan_s != '') $cuotas_credito_plan_s = $cuotas_credito_plan_s.':';
						$cuotas_credito_plan_s = $cuotas_credito_plan_s.$numero_cuota_f_db.'!';
						$cuotas_credito_plan_s = $cuotas_credito_plan_s.$fecha_vencimiento_cuota_f_db.'!';
						$cuotas_credito_plan_s = $cuotas_credito_plan_s.$monto_cuota_f_db;
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

			$date_registro = date("YmdHis");				
			$valor_log_user = translate('Msg_Generate_PDF_Credit_Client_db',$GLOBALS['lang']).': '.$idCredito;

			
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
				$motivo = 66;
				$stmt75->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo, $valor_log_user);
				if(!$stmt->execute())
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
		
			$stmt->fetch();
			$fecha_cre_pi = substr($fecha_cre_pi,6,2).'-'.substr($fecha_cre_pi,4,2).'-'.substr($fecha_cre_pi,0,4).' '.substr($fecha_cre_pi,8,2).':'.substr($fecha_cre_pi,10,2).':'.substr($fecha_cre_pi,12,2);
			if(empty($id_titular_cliente_db)) $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang']);
			else $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang']);			
			
			$datosDeImpresion = $idCredito.'|'.$fecha_cre_pi.'|'.$nombre_sucursal_usuario.'|'.$cantidad_cuotas_plan_credito_s_db.'|'.$fecha_vencimiento_cuota_db.'|'.$nombre_plan_credito_s_db.'|'.$nombres_cliente_db.' '.$apellidos_cliente_db.'|'.$tipo_cuenta_texto_cliente.'|'.$montoTotalCredito.'|'.$nombre_tipo_documento_cliente_db.'|'.$documento.'|'.$cuotas_credito_plan_s.'|'.$montoCompra.'|'.$montoTotalCredito-$montoCompra; 
			$stmt->free_result();
			$stmt->close();
			

			
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
			$pdf->SetTitle(iconv('UTF-8', 'windows-1252', translate('Lbl_Credit_PDF_View',$GLOBALS['lang'])));
			//header
			$pdf->AddPage('P');
			//foter page
			$pdf->AliasNbPages();
			
			$pdf->SetFont('Helvetica','B',8);
			$pdf->Text(15,20,translate('Lbl_Date_Print_Credit',$GLOBALS['lang']).': '.str_replace("-","/",$fecha_cre_pi));
			// Line break
			$pdf->Ln(2);

			$pdf->Text(5,25,'--------------------------------------------------------------');
			$pdf->Ln();
			$pdf->Text(28,30,translate('Lbl_Print_Voucher_Copy',$GLOBALS['lang']));
			$pdf->Ln();
			$fechaRIC = date("d-m-Y H:i:s");
			$pdf->Text(20,35,str_replace("-","/",$fechaRIC));
			$pdf->Ln();
			$pdf->Text(5,40,'--------------------------------------------------------------');			
			$pdf->Ln(2);

			$pdf->Text(5,45,iconv('UTF-8', 'windows-1252', translate('Lbl_Credit_Number',$GLOBALS['lang'])).': '.$idCredito);
			$pdf->Ln();	
			$pdf->Text(5,50,translate('Lbl_Name_Print_Credit_Plan',$GLOBALS['lang']).': '.$nombre_plan_credito_s_db);
			$pdf->Ln();	
			$pdf->Text(5,55,translate('Lbl_Type_Client_Print',$GLOBALS['lang']).': '.$tipo_cuenta_texto_cliente);
			$pdf->Ln();	
			$pdf->Text(5,60,translate('Lbl_Name_Print_Client',$GLOBALS['lang']).': '.$nombres_cliente_db.' '.$apellidos_cliente_db);
			$pdf->Ln();
			$pdf->Text(5,65,translate('Lbl_Tender_Print',$GLOBALS['lang']).': '.$nombre_sucursal_usuario);
			$pdf->Ln();	
			$pdf->Text(5,70,translate('Lbl_User_Print',$GLOBALS['lang']).': '.$usuario_otorga_credito);
			$pdf->Ln();
			$pdf->Text(5,75,translate('Lbl_Fees_Print_Credit',$GLOBALS['lang']).': '.$cantidad_cuotas_plan_credito_s_db);
			$pdf->Ln();	
			$fecha_cre_pi = str_replace("-","",$fecha_cre_pi);
			$pdf->Text(5,80,iconv('UTF-8', 'windows-1252', translate('Lbl_Next_Paid_Print_Credit',$GLOBALS['lang'])).': '.substr($fecha_vencimiento_cuota_db,6,2).'/'.substr($fecha_vencimiento_cuota_db,4,2).'/'.substr($fecha_vencimiento_cuota_db,0,4));
			$pdf->Ln();			

			$pdf->Text(5,85,'--------------------------------------------------------------');
			$pdf->Ln();		
			$pdf->SetFont('Helvetica','B',10);
			$pdf->Text(27,90,translate('Lbl_Fees_Print_Credit',$GLOBALS['lang']));
			$pdf->Ln(80);
			$pdf->SetLeftMargin(12);			
			$pdf->SetFont('Helvetica','B',8);
			
			$cuotasArr = explode(":",$cuotas_credito_plan_s);
			$posicionYC = 107;
			for ($i = 0; $i < count($cuotasArr); $i++) 
			{
				$pdf->Ln();
				$datosCuotX = explode("!",$cuotasArr[$i]);
				
				if($i == 0)
				{
					$pdf->Cell(15,5,translate('Lbl_Fee_Print_Credit',$GLOBALS['lang']),1,0,'C');
					$pdf->Cell(15,5,translate('Lbl_Date_Expire_Print_Credit',$GLOBALS['lang']),1,0, 'C');
					$pdf->Cell(15,5,translate('Lbl_Amount_Fee_Print_Credit',$GLOBALS['lang']),1,0, 'C');
					$pdf->Ln();					
				}
				else $posicionYC = $posicionYC + 5;
				$pdf->Cell(15,5,$datosCuotX[0],1,0, 'C');
				$pdf->Cell(15,5,substr($datosCuotX[1],6,2).'/'.substr($datosCuotX[1],4,2).'/'.substr($datosCuotX[1],0,4),1,0, 'C');
				$pdf->Cell(15,5,'$'.number_format(($datosCuotX[2]/100.00), 2, ',', '.'),1,0, 'C');
			}
			
			$pdf->SetMargins(15, 10 , 15);			
			$pdf->Ln();
			$pdf->Text(5,$posicionYC,'--------------------------------------------------------------');
			$pdf->Ln();
			$pdf->Text(5,$posicionYC+5,translate('Lbl_Amount_Purchase_Print_Credit',$GLOBALS['lang']).': $'.number_format(($montoCompra/100.00), 2, ',', '.'));
			$pdf->Ln();
			$pdf->Text(5,$posicionYC+10,iconv('UTF-8', 'windows-1252', translate('Lbl_Amount_Interest_Print_Credit',$GLOBALS['lang'])).': $'.number_format((($montoTotalCredito-$montoCompra)/100.00), 2, ',', '.'));	
			$pdf->Text(10,$posicionYC+15,'*******************************************');
			$pdf->Ln();
			$pdf->SetFont('Helvetica','B',10);	
			$pdf->Text(12,$posicionYC+20,iconv('UTF-8', 'windows-1252', translate('Lbl_Amount_Print_Credit',$GLOBALS['lang'])).': $'.number_format(($montoTotalCredito/100.00), 2, ',', '.'));
			$pdf->Ln();
			$pdf->SetFont('Helvetica','B',8);	
			$pdf->Text(10,$posicionYC+25,'*******************************************');		
			$textoAcR = explode("-",iconv('UTF-8', 'windows-1252', translate('Msg_Accordance_Print_Credit_57',$GLOBALS['lang'])));
			for ($j = 0; $j < count($textoAcR); $j++) 
			{
				if($j != 0) $posicionYC = $posicionYC + 5;
				else $posicionYC = $posicionYC + 30;
				$pdf->Ln();
				$pdf->Text(5,$posicionYC,$textoAcR[$j]);
			}	
			$pdf->Ln(2);
			$pdf->Text(5,$posicionYC+14,'------------------------------------------------------------');
			$pdf->Ln();	
			$pdf->Text(28,$posicionYC+17,translate('Lbl_Sign_Print_Credit',$GLOBALS['lang']));
			$pdf->Ln(2);
			$pdf->Text(5,$posicionYC+30,'------------------------------------------------------------');
			$pdf->Ln();	
			$pdf->Text(25,$posicionYC+33,iconv('UTF-8', 'windows-1252', translate('Lbl_Clarification_Print_Credit',$GLOBALS['lang'])));	
			$pdf->Ln();
			$pdf->Text(22,$posicionYC+36,$nombre_tipo_documento_cliente_db.': '.$documento);	
			
			$pdf->Output();

		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;	
		}
	
	return;	
?>