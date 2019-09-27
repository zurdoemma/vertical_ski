<?php 		
		include ('../utiles/funciones.php');
		include ('../fpdf/fpdf.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');return;}
		if (!verificar_permisos_supervisor()){header('Location:../sinautorizacion.php');return;}

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
		
		$idReporte=htmlspecialchars($_POST["idReporte"], ENT_QUOTES, 'UTF-8');
		if(empty($idReporte))
		{			
			$idReporte=htmlspecialchars($_GET["idReporte"], ENT_QUOTES, 'UTF-8'); 
		}
				
		if($stmt61 = $mysqli->prepare("SELECT r.id, r.nombre FROM finan_cli.reportes r WHERE r.id = ?"))
		{
			$stmt61->bind_param('i', $idReporte);
			$stmt61->execute();    
			$stmt61->store_result();
			
			$totR61 = $stmt61->num_rows;

			if($totR61 > 0)
			{
				$stmt61->bind_result($id_reporte_db, $nombre_reporte_db);
				$stmt61->fetch();
								
				$stmt61->free_result();
				$stmt61->close();
			}
			else
			{
				echo translate('Msg_Report_PDF_Not_Exist',$GLOBALS['lang']);
				return;
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
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
		
		if($id_reporte_db == 1)
		{
			$selectReportID1 = "SELECT s.codigo, s.nombre, COUNT(c.id), SUM(c.monto_credito_original) FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s WHERE c.id = cc.id_credito AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? GROUP BY s.codigo, s.nombre HAVING SUM(c.monto_credito_original) IS NOT NULL";
			if ($stmt = $mysqli->prepare($selectReportID1)) 
			{
				if(!empty($_GET["fechaDesde"]) && !empty($_GET["fechaHasta"])) 
				{
					$fechaDesde=htmlspecialchars($_GET["fechaDesde"], ENT_QUOTES, 'UTF-8');
					$fechaHasta=htmlspecialchars($_GET["fechaHasta"], ENT_QUOTES, 'UTF-8');
				}
				else
				{
					$fechaDesde=htmlspecialchars($_POST["fechaDesde"], ENT_QUOTES, 'UTF-8');
					$fechaHasta=htmlspecialchars($_POST["fechaHasta"], ENT_QUOTES, 'UTF-8');					
				}
				$fechaDesde = substr($fechaDesde, 6, 4).substr($fechaDesde, 3, 2).substr($fechaDesde, 0, 2).'000000';
				$fechaHasta = substr($fechaHasta, 6, 4).substr($fechaHasta, 3, 2).substr($fechaHasta, 0, 2).'235959';
				$estadoCanceladoS = translate('Lbl_Status_Fee_Canceled',$GLOBALS['lang']);
				
				$stmt->bind_param('siss', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta);

				$stmt->execute();    
				$stmt->store_result();
		 
				$stmt->bind_result($codigo_sucursal_reporte, $nombre_sucursal_reporte, $cantidad_creditos_sucursal_reporte, $monto_creditos_sucursal_reporte);				
				$totR = $stmt->num_rows;

				if($totR == 0)
				{
					echo translate('Msg_Report_PDF_Not_Data_View',$GLOBALS['lang']);
					return;	
				}					
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;	
			}
		}


		if($id_reporte_db == 2)
		{
			if(!empty($_GET["fechaDesde"]) && !empty($_GET["fechaHasta"]) && !empty($_GET["sucursal"]) && !empty($_GET["planCredito"])) 
			{
				$fechaDesde=htmlspecialchars($_GET["fechaDesde"], ENT_QUOTES, 'UTF-8');
				$fechaHasta=htmlspecialchars($_GET["fechaHasta"], ENT_QUOTES, 'UTF-8');
				
				$sucursal=htmlspecialchars($_GET["sucursal"], ENT_QUOTES, 'UTF-8');
				$planCredito=htmlspecialchars($_GET["planCredito"], ENT_QUOTES, 'UTF-8');
			}
			else
			{
				$fechaDesde=htmlspecialchars($_POST["fechaDesde"], ENT_QUOTES, 'UTF-8');
				$fechaHasta=htmlspecialchars($_POST["fechaHasta"], ENT_QUOTES, 'UTF-8');

				$sucursal=htmlspecialchars($_POST["sucursal"], ENT_QUOTES, 'UTF-8');
				$planCredito=htmlspecialchars($_POST["planCredito"], ENT_QUOTES, 'UTF-8');
			}
				
			if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && $planCredito != translate('Lbl_All_Selection2',$GLOBALS['lang'])) $selectReportID2 = "SELECT s.codigo, s.nombre, p.nombre, COUNT(c.id), SUM(c.monto_credito_original) FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.plan_credito p WHERE c.id_plan_credito = p.id AND c.id = cc.id_credito AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? AND p.id = ? GROUP BY s.codigo, s.nombre, p.nombre HAVING SUM(c.monto_credito_original) IS NOT NULL";
			else if($sucursal == translate('Lbl_All_Selection',$GLOBALS['lang']) && $planCredito != translate('Lbl_All_Selection2',$GLOBALS['lang'])) $selectReportID2 = "SELECT s.codigo, s.nombre, p.nombre, COUNT(c.id), SUM(c.monto_credito_original) FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.plan_credito p WHERE c.id_plan_credito = p.id AND c.id = cc.id_credito AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND p.id = ? GROUP BY s.codigo, s.nombre, p.nombre HAVING SUM(c.monto_credito_original) IS NOT NULL";
			else if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && $planCredito == translate('Lbl_All_Selection2',$GLOBALS['lang'])) $selectReportID2 = "SELECT s.codigo, s.nombre, p.nombre, COUNT(c.id), SUM(c.monto_credito_original) FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.plan_credito p WHERE c.id_plan_credito = p.id AND c.id = cc.id_credito AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? GROUP BY s.codigo, s.nombre, p.nombre HAVING SUM(c.monto_credito_original) IS NOT NULL";
			else $selectReportID2 = "SELECT s.codigo, s.nombre, p.nombre, COUNT(c.id), SUM(c.monto_credito_original) FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.plan_credito p WHERE c.id_plan_credito = p.id AND c.id = cc.id_credito AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? GROUP BY s.codigo, s.nombre, p.nombre HAVING SUM(c.monto_credito_original) IS NOT NULL";
				
			if ($stmt = $mysqli->prepare($selectReportID2)) 
			{
				$fechaDesde = substr($fechaDesde, 6, 4).substr($fechaDesde, 3, 2).substr($fechaDesde, 0, 2).'000000';
				$fechaHasta = substr($fechaHasta, 6, 4).substr($fechaHasta, 3, 2).substr($fechaHasta, 0, 2).'235959';
				$estadoCanceladoS = translate('Lbl_Status_Fee_Canceled',$GLOBALS['lang']);
				
				if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && $planCredito != translate('Lbl_All_Selection2',$GLOBALS['lang'])) $stmt->bind_param('sissii', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal, $planCredito);
				else if($sucursal == translate('Lbl_All_Selection',$GLOBALS['lang']) && $planCredito != translate('Lbl_All_Selection2',$GLOBALS['lang'])) $stmt->bind_param('sissi', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $planCredito);
				else if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && $planCredito == translate('Lbl_All_Selection2',$GLOBALS['lang'])) $stmt->bind_param('sissi', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal);
				else $stmt->bind_param('siss', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta);
				$stmt->execute();    
				$stmt->store_result();

				$stmt->bind_result($codigo_sucursal_reporte, $nombre_sucursal_reporte, $nombre_plan_credito, $cantidad_creditos_sucursal_reporte, $monto_creditos_sucursal_reporte);				
				$totR = $stmt->num_rows;

				if($totR == 0)
				{
					echo translate('Msg_Report_PDF_Not_Data_View',$GLOBALS['lang']);
					return;	
				}					
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;	
			}			
		}		

		if($id_reporte_db == 3)
		{
			if(!empty($_GET["fechaDesde"]) && !empty($_GET["fechaHasta"]) && !empty($_GET["sucursal"])) 
			{
				$fechaDesde=htmlspecialchars($_GET["fechaDesde"], ENT_QUOTES, 'UTF-8');
				$fechaHasta=htmlspecialchars($_GET["fechaHasta"], ENT_QUOTES, 'UTF-8');
				
				$sucursal=htmlspecialchars($_GET["sucursal"], ENT_QUOTES, 'UTF-8');
			}
			else
			{
				$fechaDesde=htmlspecialchars($_POST["fechaDesde"], ENT_QUOTES, 'UTF-8');
				$fechaHasta=htmlspecialchars($_POST["fechaHasta"], ENT_QUOTES, 'UTF-8');

				$sucursal=htmlspecialchars($_POST["sucursal"], ENT_QUOTES, 'UTF-8');
			}
				
			if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang'])) $selectReportID2 = "SELECT s.codigo, s.nombre, AVG(c.monto_credito_original) FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s WHERE c.id = cc.id_credito AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? GROUP BY s.codigo, s.nombre HAVING AVG(c.monto_credito_original) IS NOT NULL";
			else $selectReportID2 = "SELECT s.codigo, s.nombre, AVG(c.monto_credito_original) FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s WHERE c.id = cc.id_credito AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? GROUP BY s.codigo, s.nombre HAVING AVG(c.monto_credito_original) IS NOT NULL";
				
			if ($stmt = $mysqli->prepare($selectReportID2)) 
			{
				$fechaDesde = substr($fechaDesde, 6, 4).substr($fechaDesde, 3, 2).substr($fechaDesde, 0, 2).'000000';
				$fechaHasta = substr($fechaHasta, 6, 4).substr($fechaHasta, 3, 2).substr($fechaHasta, 0, 2).'235959';
				$estadoCanceladoS = translate('Lbl_Status_Fee_Canceled',$GLOBALS['lang']);
				
				if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang'])) $stmt->bind_param('sissi', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal);
				else $stmt->bind_param('siss', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta);
				$stmt->execute();    
				$stmt->store_result();

				$stmt->bind_result($codigo_sucursal_reporte, $nombre_sucursal_reporte, $monto_promedio_creditos_sucursal_reporte);				
				$totR = $stmt->num_rows;

				if($totR == 0)
				{
					echo translate('Msg_Report_PDF_Not_Data_View',$GLOBALS['lang']);
					return;	
				}					
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;	
			}			
		}		

		if(!empty($_POST["idReporte"]))
		{
			echo translate('Msg_Generate_Report_PDF_OK',$GLOBALS['lang']);
			return;				
		}
		
		class PDF extends FPDF
		{
			// Page header
			function Header()
			{
				// Logo
				$this->Image('../images/imgReportes.png',25,4,35);
				//$this->Image('../recursosimg/pelotainforme.png',270,100,20);
				$this->SetFont('Helvetica','B',12);
				// Move to the right
				$this->Cell(30);
				// Title
				$this->SetFillColor(17,58,154);
					
				$fechaReporte = date("d-m-Y H:i:s");
				$this->SetTextColor(255,255,255);
				$this->Cell(200,11,iconv('UTF-8', 'windows-1252', $_GET["nombreReporte"]).' - '.translate('Lbl_Date_Credit',$GLOBALS['lang']).': '.$fechaReporte,1,0,'C',True);
				$this->Ln(20);
				$this->SetFont('Helvetica','B',10);
				if($_GET["idReporte"] == 1) $this->Cell(200,11,'  '.translate('Lbl_Date_Since_Report',$GLOBALS['lang']).': '.$_GET["fechaDesde"].'  ||  '.translate('Lbl_Date_Until_Report',$GLOBALS['lang']).': '.$_GET["fechaHasta"].'  ||  '.translate('Lbl_User_Print',$GLOBALS['lang']).': '.$_SESSION['username'],1,0,'L',True);
				if($_GET["idReporte"] == 2) $this->Cell(230,11,'  '.translate('Lbl_Date_Since_Report',$GLOBALS['lang']).': '.$_GET["fechaDesde"].'  ||  '.translate('Lbl_Date_Until_Report',$GLOBALS['lang']).': '.$_GET["fechaHasta"].'  ||  '.translate('Lbl_Tender_User',$GLOBALS['lang']).': '.$_GET["nombreSucursal"].'  ||  '.iconv('UTF-8', 'windows-1252', translate('Lbl_Name_Plan_Credit_2',$GLOBALS['lang'])).': '.$_GET["nombrePlanCredito"].'  ||  '.translate('Lbl_User_Print',$GLOBALS['lang']).': '.$_SESSION['username'],1,0,'L',True);
				if($_GET["idReporte"] == 3) $this->Cell(230,11,'  '.translate('Lbl_Date_Since_Report',$GLOBALS['lang']).': '.$_GET["fechaDesde"].'  ||  '.translate('Lbl_Date_Until_Report',$GLOBALS['lang']).': '.$_GET["fechaHasta"].'  ||  '.translate('Lbl_Tender_User',$GLOBALS['lang']).': '.$_GET["nombreSucursal"].'  ||  '.translate('Lbl_User_Print',$GLOBALS['lang']).': '.$_SESSION['username'],1,0,'L',True);
				// Line break
				$this->Ln(20);					
			}
			 
			// Page footer
			function Footer()
			{
				// Position at 1.5 cm from bottom
				$this->SetY(-15);
				// Arial italic 8
				$this->SetFont('Helvetica','I',8);
				// Page number
				$this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
			}
		}
		
		$pdf = new PDF();
		$pdf->SetTitle(iconv('UTF-8', 'windows-1252', translate('Lbl_Report_PDF_Credits',$GLOBALS['lang'])));		
		$pdf->SetMargins(29, 25 , 30);
		//header
		$pdf->AddPage('L');
		//foter page
		$pdf->AliasNbPages();
		$pdf->SetFont('Helvetica','B',8);
		
		if($id_reporte_db == 1)
		{
			$pdf->SetFillColor(17,58,154);
			$pdf->SetTextColor(255,255,255);
			$pdf->Cell(50,10,'CÓDIGO',1,0,'C',True);
			$pdf->Cell(100,10,'NOMBRE',1,0, 'C',True);
			$pdf->Cell(50,10,'CANTIDAD',1,0, 'C',True);
			$pdf->Cell(50,10,'MONTO',1, 0,'C',True);
			$pdf->SetTextColor(0,0,0);
			
			while($stmt->fetch()) 
			{
				$pdf->Ln();
				$pdf->Cell(50,10,$codigo_sucursal_reporte,1,0,'C');
				$pdf->Cell(100,10,iconv('UTF-8', 'windows-1252', $nombre_sucursal_reporte),1,0,'C');
				$pdf->Cell(50,10,$cantidad_creditos_sucursal_reporte,1,0,'C');
				$pdf->Cell(50,10,'$'.number_format(($monto_creditos_sucursal_reporte/100.00), 2, ',', '.'),1,0,'C');
			}
		}

		if($id_reporte_db == 2)
		{
			$pdf->SetFillColor(17,58,154);
			$pdf->SetTextColor(255,255,255);
			$pdf->Cell(40,10,'CÓDIGO',1,0,'C',True);
			$pdf->Cell(70,10,'NOMBRE',1,0, 'C',True);
			$pdf->Cell(70,10,'PLAN CRÉDITO',1,0, 'C',True);
			$pdf->Cell(30,10,'CANTIDAD',1,0, 'C',True);
			$pdf->Cell(40,10,'MONTO',1, 0,'C',True);
			$pdf->SetTextColor(0,0,0);
			
			while($stmt->fetch()) 
			{
				$pdf->Ln();
				$pdf->Cell(40,10,$codigo_sucursal_reporte,1,0,'C');
				$pdf->Cell(70,10,iconv('UTF-8', 'windows-1252', $nombre_sucursal_reporte),1,0,'C');
				$pdf->Cell(70,10,iconv('UTF-8', 'windows-1252', $nombre_plan_credito),1,0,'C');
				$pdf->Cell(30,10,$cantidad_creditos_sucursal_reporte,1,0,'C');
				$pdf->Cell(40,10,'$'.number_format(($monto_creditos_sucursal_reporte/100.00), 2, ',', '.'),1,0,'C');
			}
		}

		if($id_reporte_db == 3)
		{
			$pdf->SetFillColor(17,58,154);
			$pdf->SetTextColor(255,255,255);
			$pdf->Cell(50,10,'CÓDIGO',1,0,'C',True);
			$pdf->Cell(120,10,'NOMBRE',1,0, 'C',True);
			$pdf->Cell(70,10,'MONTO PROMEDIO',1, 0,'C',True);
			$pdf->SetTextColor(0,0,0);
			
			while($stmt->fetch()) 
			{
				$pdf->Ln();
				$pdf->Cell(50,10,$codigo_sucursal_reporte,1,0,'C');
				$pdf->Cell(120,10,iconv('UTF-8', 'windows-1252', $nombre_sucursal_reporte),1,0,'C');
				$pdf->Cell(70,10,'$'.number_format(($monto_promedio_creditos_sucursal_reporte/100.00), 2, ',', '.'),1,0,'C');
			}
		}		
		
		$stmt->free_result();
		$stmt->close();
		
		$pdf->Output();
	
	return;	
?>