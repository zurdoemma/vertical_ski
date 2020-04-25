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
		$idCuotaCredito=htmlspecialchars($_GET["idCuotaCredito"], ENT_QUOTES, 'UTF-8');		

		if($stmt68 = $mysqli->prepare("SELECT c.estado, cli.id_titular, cli.nombres, cli.apellidos, s.nombre, cc.numero_cuota, cc.usuario_registro_pago, td.nombre, cli.documento, cc.monto_cuota_original, cc.fecha_pago, cc.monto_pago FROM ".$db_name.".credito c, ".$db_name.".credito_cliente ccli, ".$db_name.".cliente cli, ".$db_name.".cuota_credito cc, ".$db_name.".sucursal s, ".$db_name.".tipo_documento td WHERE c.id = ccli.id_credito AND c.id = cc.id_credito AND ccli.tipo_documento = cli.tipo_documento AND ccli.documento = cli.documento AND ccli.id_sucursal = s.id AND cli.tipo_documento = td.id AND c.id = ? AND cc.id = ?"))
		{
			$stmt68->bind_param('ii', $idCredito, $idCuotaCredito);
			$stmt68->execute();    
			$stmt68->store_result();
			
			$totR68 = $stmt68->num_rows;

			if($totR68 > 0)
			{
				$stmt68->bind_result($estado_credito_db_res, $id_titular_cliente_db_res, $nombres_cliente_db_res, $apellidos_cliente_db_res, $nombre_sucursal_db_res, $numero_cuota_db_res, $usuario_registro_pago_cuota_db_res, $tipo_documento_cliente_db_res, $documento_cliente_db_res, $monto_cuota_original_db_res, $fecha_pago_cuota_db_res, $monto_pago_cuota_db_res);
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
		
		if($stmt69 = $mysqli->prepare("SELECT cc.fecha_vencimiento FROM ".$db_name.".cuota_credito cc WHERE cc.id_credito = ? AND cc.estado IN (?,?) ORDER BY cc.numero_cuota"))
		{
			$estado_p_1 = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
			$estado_p_2 = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
			$stmt69->bind_param('iss', $idCredito, $estado_p_1, $estado_p_2);
			$stmt69->execute();    
			$stmt69->store_result();
			
			$totR69 = $stmt69->num_rows;

			if($totR69 > 0)
			{
				$stmt69->bind_result($fecha_vencimiento_cuota_db_res);
				$stmt69->fetch();
								
				$stmt69->free_result();
				$stmt69->close();
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}

		if($stmt70 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM ".$db_name.".mora_cuota_credito mcc, ".$db_name.".cuota_credito cc WHERE mcc.id_cuota_credito = cc.id AND cc.id_credito = ? AND cc.id = ?"))
		{
			$stmt70->bind_param('ii', $idCredito, $idCuotaCredito);
			$stmt70->execute();    
			$stmt70->store_result();
			
			$totR70 = $stmt70->num_rows;

			if($totR70 > 0)
			{
				$stmt70->bind_result($monto_interes_cuota_credito_db_res);
				$stmt70->fetch();
				
				$stmt70->free_result();
				$stmt70->close();
			}
			else $monto_interes_cuota_credito_db_res = 0;
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}						
		
		if(empty($id_titular_cliente_db_res)) $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang']);
		else $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang']);		
		
		$date_registro = date("YmdHis");				
		$valor_log_user = translate('Msg_Generation_PDF_Pay_Fee_Credit_Client_db',$GLOBALS['lang']).': '.$idCuotaCredito;		
			
		$mysqli->autocommit(FALSE);
		$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
	
		if(!$stmt75 = $mysqli->prepare("INSERT INTO ".$db_name.".log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
		{
			echo $mysqli->error;
			$mysqli->autocommit(TRUE);
			$stmt75->free_result();
			$stmt75->close();
			return;
		}
		else
		{
			$motivo = 71;
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
		$pdf->SetTitle(iconv('UTF-8', 'windows-1252', translate('Lbl_Pay_Fee_Credit_PDF_View',$GLOBALS['lang'])));
		//header
		$pdf->AddPage('P');
		//foter page
		$pdf->AliasNbPages();
		
		$pdf->SetFont('Helvetica','B',8);
		$pdf->Text(15,20,translate('Lbl_Date_Print_Credit',$GLOBALS['lang']).': '.substr($fecha_pago_cuota_db_res,6,2).'/'.substr($fecha_pago_cuota_db_res,4,2).'/'.substr($fecha_pago_cuota_db_res,0,4).' '.substr($fecha_pago_cuota_db_res,8,2).':'.substr($fecha_pago_cuota_db_res,10,2).':'.substr($fecha_pago_cuota_db_res,12,2));
		// Line break
		$pdf->Ln(2);

		$pdf->Text(5,25,'--------------------------------------------------------------');
		$pdf->Ln();
		$pdf->SetFont('Helvetica','B',10);
		$pdf->Text(17,30,iconv('UTF-8', 'windows-1252', translate('Lbl_Print_Type_Voucher_Pay_Fee_Credit',$GLOBALS['lang'])));
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
		$pdf->Text(5,55,translate('Lbl_Number_Fee_Credit',$GLOBALS['lang']).': '.$numero_cuota_db_res);
		$pdf->Ln();	
		$pdf->Text(5,60,translate('Lbl_Type_Client_Print',$GLOBALS['lang']).': '.$tipo_cuenta_texto_cliente);
		$pdf->Ln();	
		$pdf->Text(5,65,translate('Lbl_Name_Print_Client',$GLOBALS['lang']).': '.$nombres_cliente_db_res.' '.$apellidos_cliente_db_res);
		$pdf->Ln();
		$pdf->Text(5,70,translate('Lbl_Tender_Print',$GLOBALS['lang']).': '.$nombre_sucursal_db_res);
		$pdf->Ln();	
		$pdf->Text(5,75,translate('Lbl_User_Print',$GLOBALS['lang']).': '.$usuario_registro_pago_cuota_db_res);
		$pdf->Ln();
		$pdf->Text(5,80,'--------------------------------------------------------------');
		$pdf->Ln();		
		$pdf->Text(5,85,translate('Lbl_Amount_Original_Fee_Print_Credit',$GLOBALS['lang']).': $'.number_format(($monto_cuota_original_db_res/100.00), 2, ',', '.'));
		$pdf->Ln();
		if(empty($monto_interes_cuota_credito_db_res)) $pdf->Text(5,90,iconv('UTF-8', 'windows-1252',translate('Lbl_Amount_Interest_Print_Credit',$GLOBALS['lang'])).': $0,00');
		else $pdf->Text(5,90,iconv('UTF-8', 'windows-1252',translate('Lbl_Amount_Interest_Print_Credit',$GLOBALS['lang'])).': $'.number_format(($monto_interes_cuota_credito_db_res/100.00), 2, ',', '.'));
		$pdf->Text(5,95,iconv('UTF-8', 'windows-1252', translate('Lbl_Amount_Diferential_Fee_Print_Credit',$GLOBALS['lang'])).': $'.number_format((($monto_cuota_original_db_res-$monto_pago_cuota_db_res)/100.00), 2, ',', '.'));	
		$pdf->Text(10,100,'*******************************************');
		$pdf->Ln();
		$pdf->SetFont('Helvetica','B',10);	
		$pdf->Text(12,105,iconv('UTF-8', 'windows-1252', translate('Lbl_Amount_Pay_Print_Credit',$GLOBALS['lang'])).': $'.number_format(($monto_pago_cuota_db_res/100.00), 2, ',', '.'));
		$pdf->Ln();
		$pdf->SetFont('Helvetica','B',8);	
		$pdf->Text(10,110,'*******************************************');			
		$pdf->Ln();
		if(!empty($fecha_vencimiento_cuota_db_res)) 
		{
			$pdf->Text(5,115,iconv('UTF-8', 'windows-1252', translate('Lbl_Next_Paid_Print_Credit',$GLOBALS['lang'])).': '.substr($fecha_vencimiento_cuota_db_res,6,2).'/'.substr($fecha_vencimiento_cuota_db_res,4,2).'/'.substr($fecha_vencimiento_cuota_db_res,0,4));		
		    $pdf->Ln();
			$pdf->Text(5,120,iconv('UTF-8', 'windows-1252', translate('Msg_Fee_Pending',$GLOBALS['lang'])).': '.$totR69);			
		}
		else $pdf->Text(5,115,iconv('UTF-8', 'windows-1252', translate('Lbl_Next_Paid_Print_Credit',$GLOBALS['lang'])).': ---');		
		$pdf->Ln(2);
		$pdf->Text(5,135,'        --------------------------------------------');
		$pdf->Ln();	
		$pdf->Text(28,140,translate('Lbl_Sign_Print_Credit',$GLOBALS['lang']));
		$pdf->Ln(2);
		$pdf->Text(5,155,'        --------------------------------------------');
		$pdf->Ln();	
		$pdf->Text(25,160,iconv('UTF-8', 'windows-1252', translate('Lbl_Clarification_Print_Credit',$GLOBALS['lang'])));	
		$pdf->Ln();
		$pdf->Text(22,165,$tipo_documento_cliente_db_res.': '.$documento_cliente_db_res);	
		
		$pdf->Output();
	
	return;	
?>