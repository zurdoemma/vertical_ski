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
				
			if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang'])) $selectReportID3 = "SELECT s.codigo, s.nombre, AVG(c.monto_credito_original) FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s WHERE c.id = cc.id_credito AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? GROUP BY s.codigo, s.nombre HAVING AVG(c.monto_credito_original) IS NOT NULL";
			else $selectReportID3 = "SELECT s.codigo, s.nombre, AVG(c.monto_credito_original) FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s WHERE c.id = cc.id_credito AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? GROUP BY s.codigo, s.nombre HAVING AVG(c.monto_credito_original) IS NOT NULL";
				
			if ($stmt = $mysqli->prepare($selectReportID3)) 
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


		if($id_reporte_db == 4)
		{
			if(!empty($_GET["fechaDesde"]) && !empty($_GET["fechaHasta"]) && !empty($_GET["sucursal"]) && !empty($_GET["tipoDocumento"])) 
			{
				$fechaDesde=htmlspecialchars($_GET["fechaDesde"], ENT_QUOTES, 'UTF-8');
				$fechaHasta=htmlspecialchars($_GET["fechaHasta"], ENT_QUOTES, 'UTF-8');
				
				$sucursal=htmlspecialchars($_GET["sucursal"], ENT_QUOTES, 'UTF-8');
				$tipoDocumentoCli=htmlspecialchars($_GET["tipoDocumento"], ENT_QUOTES, 'UTF-8');
				$documentoCli=htmlspecialchars($_GET["documento"], ENT_QUOTES, 'UTF-8');
			}
			else
			{
				$fechaDesde=htmlspecialchars($_POST["fechaDesde"], ENT_QUOTES, 'UTF-8');
				$fechaHasta=htmlspecialchars($_POST["fechaHasta"], ENT_QUOTES, 'UTF-8');

				$sucursal=htmlspecialchars($_POST["sucursal"], ENT_QUOTES, 'UTF-8');
				$tipoDocumentoCli=htmlspecialchars($_POST["tipoDocumento"], ENT_QUOTES, 'UTF-8');
				$documentoCli=htmlspecialchars($_POST["documento"], ENT_QUOTES, 'UTF-8');
			}
			
			if(!empty($documentoCli))
			{
				if($stmt47 = $mysqli->prepare("SELECT c.id_titular, c.nombres, c.apellidos FROM finan_cli.cliente c WHERE c.tipo_documento = ? AND c.documento = ?"))
				{
					$stmt47->bind_param('is', $tipoDocumentoCli, $documentoCli);
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
						$stmt47->bind_result($id_titular_cliente_db, $nombres_cliente_db, $apellidos_cliente_db);
						$stmt47->fetch();
						
						$stmt47->free_result();
						$stmt47->close();
					}
				}
				else
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;				
				}
			}
			
			if(empty($id_titular_cliente_db))
			{
				if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) 
				{
					$selectReportID4 = "SELECT s.codigo, s.nombre, AVG(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento AND cli.documento = cc.documento AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING AVG(c.monto_credito_original) IS NOT NULL UNION ALL SELECT s.codigo, s.nombre, AVG(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING AVG(c.monto_credito_original)";
				}
				else if($sucursal == translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli))
				{
					$selectReportID4 = "SELECT AVG(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento AND cli.documento = cc.documento AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING AVG(c.monto_credito_original) IS NOT NULL UNION ALL SELECT AVG(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING AVG(c.monto_credito_original) IS NOT NULL";
				}
				else if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && empty($documentoCli)) 
				{
					$selectReportID4 = "SELECT s.codigo, s.nombre, AVG(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento AND cli.documento = cc.documento AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING AVG(c.monto_credito_original) IS NOT NULL UNION ALL SELECT s.codigo, s.nombre, AVG(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING AVG(c.monto_credito_original) IS NOT NULL";
				}
				else 
				{
					$selectReportID4 = "SELECT AVG(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento AND cli.documento = cc.documento AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? GROUP BY cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING AVG(c.monto_credito_original) IS NOT NULL UNION ALL SELECT AVG(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? GROUP BY cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING AVG(c.monto_credito_original) IS NOT NULL";
				}
			}
			else
			{
				if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $selectReportID4 = "SELECT s.codigo, s.nombre, AVG(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos HAVING AVG(c.monto_credito_original) IS NOT NULL";
				else if($sucursal == translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $selectReportID4 = "SELECT AVG(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY cli.nombres, cli.apellidos HAVING AVG(c.monto_credito_original) IS NOT NULL";
				else if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && empty($documentoCli)) $selectReportID4 = "SELECT s.codigo, s.nombre, AVG(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos HAVING AVG(c.monto_credito_original) IS NOT NULL";
				else $selectReportID4 = "SELECT AVG(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? GROUP BY cli.nombres, cli.apellidos HAVING AVG(c.monto_credito_original) IS NOT NULL";				
			}
			
			if ($stmt = $mysqli->prepare($selectReportID4)) 
			{
				$fechaDesde = substr($fechaDesde, 6, 4).substr($fechaDesde, 3, 2).substr($fechaDesde, 0, 2).'000000';
				$fechaHasta = substr($fechaHasta, 6, 4).substr($fechaHasta, 3, 2).substr($fechaHasta, 0, 2).'235959';
				$estadoCanceladoS = translate('Lbl_Status_Fee_Canceled',$GLOBALS['lang']);
				
				if(empty($id_titular_cliente_db))
				{
					if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $stmt->bind_param('sissiississiis', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal, $tipoDocumentoCli, $documentoCli, $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal, $tipoDocumentoCli, $documentoCli);
					else if($sucursal == translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $stmt->bind_param('sissississis', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $tipoDocumentoCli, $documentoCli, $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $tipoDocumentoCli, $documentoCli);
					else if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && empty($documentoCli)) $stmt->bind_param('sissisissi', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal, $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal);
					else $stmt->bind_param('sisssiss', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta);

				}
				else
				{
					if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $stmt->bind_param('sissiis', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal, $tipoDocumentoCli, $documentoCli);
					else if($sucursal == translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $stmt->bind_param('sissis', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $tipoDocumentoCli, $documentoCli);
					else if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && empty($documentoCli)) $stmt->bind_param('sissi', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal);
					else $stmt->bind_param('siss', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta);
				}
				
				$stmt->execute();    
				$stmt->store_result();

				if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang'])) $stmt->bind_result($codigo_sucursal_reporte, $nombre_sucursal_reporte, $monto_promedio_creditos_sucursal_reporte, $nombres_cliente_reporte, $apellidos_cliente_reporte, $id_titular_cliente_reporte, $tipo_documento_cliente_reporte, $documento_cliente_reporte);
				else $stmt->bind_result($monto_promedio_creditos_sucursal_reporte, $nombres_cliente_reporte, $apellidos_cliente_reporte, $id_titular_cliente_reporte, $tipo_documento_cliente_reporte, $documento_cliente_reporte);
			
				$totR = $stmt->num_rows;
				if($totR == 0)
				{
					echo translate('Msg_Report_PDF_Not_Data_View',$GLOBALS['lang']);
					return;	
				}					
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']).$mysqli->error;
				return;	
			}			
		}

		if($id_reporte_db == 5)
		{
			if(!empty($_GET["fechaDesde"]) && !empty($_GET["fechaHasta"]) && !empty($_GET["sucursal"]) && !empty($_GET["tipoDocumento"])) 
			{
				$fechaDesde=htmlspecialchars($_GET["fechaDesde"], ENT_QUOTES, 'UTF-8');
				$fechaHasta=htmlspecialchars($_GET["fechaHasta"], ENT_QUOTES, 'UTF-8');
				
				$sucursal=htmlspecialchars($_GET["sucursal"], ENT_QUOTES, 'UTF-8');
				$tipoDocumentoCli=htmlspecialchars($_GET["tipoDocumento"], ENT_QUOTES, 'UTF-8');
				$documentoCli=htmlspecialchars($_GET["documento"], ENT_QUOTES, 'UTF-8');
			}
			else
			{
				$fechaDesde=htmlspecialchars($_POST["fechaDesde"], ENT_QUOTES, 'UTF-8');
				$fechaHasta=htmlspecialchars($_POST["fechaHasta"], ENT_QUOTES, 'UTF-8');

				$sucursal=htmlspecialchars($_POST["sucursal"], ENT_QUOTES, 'UTF-8');
				$tipoDocumentoCli=htmlspecialchars($_POST["tipoDocumento"], ENT_QUOTES, 'UTF-8');
				$documentoCli=htmlspecialchars($_POST["documento"], ENT_QUOTES, 'UTF-8');
			}
			
			if(!empty($documentoCli))
			{
				if($stmt47 = $mysqli->prepare("SELECT c.id_titular, c.nombres, c.apellidos FROM finan_cli.cliente c WHERE c.tipo_documento = ? AND c.documento = ?"))
				{
					$stmt47->bind_param('is', $tipoDocumentoCli, $documentoCli);
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
						$stmt47->bind_result($id_titular_cliente_db, $nombres_cliente_db, $apellidos_cliente_db);
						$stmt47->fetch();
						
						$stmt47->free_result();
						$stmt47->close();
					}
				}
				else
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;				
				}
			}
			
			if(empty($id_titular_cliente_db))
			{
				if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) 
				{
					$selectReportID5 = "SELECT s.codigo, s.nombre, COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento AND cli.documento = cc.documento AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING COUNT(c.monto_credito_original) IS NOT NULL UNION ALL SELECT s.codigo, s.nombre, COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING COUNT(c.monto_credito_original)";
				}
				else if($sucursal == translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli))
				{
					$selectReportID5 = "SELECT COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento AND cli.documento = cc.documento AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING COUNT(c.monto_credito_original) IS NOT NULL UNION ALL SELECT COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING COUNT(c.monto_credito_original) IS NOT NULL";
				}
				else if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && empty($documentoCli)) 
				{
					$selectReportID5 = "SELECT s.codigo, s.nombre, COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento AND cli.documento = cc.documento AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING COUNT(c.monto_credito_original) IS NOT NULL UNION ALL SELECT s.codigo, s.nombre, COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING COUNT(c.monto_credito_original) IS NOT NULL";
				}
				else 
				{
					$selectReportID5 = "SELECT COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento AND cli.documento = cc.documento AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? GROUP BY cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING COUNT(c.monto_credito_original) IS NOT NULL UNION ALL SELECT COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? GROUP BY cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING COUNT(c.monto_credito_original) IS NOT NULL";
				}
			}
			else
			{
				if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $selectReportID5 = "SELECT s.codigo, s.nombre, COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos HAVING COUNT(c.monto_credito_original) IS NOT NULL";
				else if($sucursal == translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $selectReportID5 = "SELECT COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY cli.nombres, cli.apellidos HAVING COUNT(c.monto_credito_original) IS NOT NULL";
				else if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && empty($documentoCli)) $selectReportID5 = "SELECT s.codigo, s.nombre, COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos HAVING COUNT(c.monto_credito_original) IS NOT NULL";
				else $selectReportID5 = "SELECT COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? GROUP BY cli.nombres, cli.apellidos HAVING COUNT(c.monto_credito_original) IS NOT NULL";				
			}
			
			if ($stmt = $mysqli->prepare($selectReportID5)) 
			{
				$fechaDesde = substr($fechaDesde, 6, 4).substr($fechaDesde, 3, 2).substr($fechaDesde, 0, 2).'000000';
				$fechaHasta = substr($fechaHasta, 6, 4).substr($fechaHasta, 3, 2).substr($fechaHasta, 0, 2).'235959';
				$estadoCanceladoS = translate('Lbl_Status_Fee_Canceled',$GLOBALS['lang']);
				
				if(empty($id_titular_cliente_db))
				{
					if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $stmt->bind_param('sissiississiis', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal, $tipoDocumentoCli, $documentoCli, $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal, $tipoDocumentoCli, $documentoCli);
					else if($sucursal == translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $stmt->bind_param('sissississis', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $tipoDocumentoCli, $documentoCli, $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $tipoDocumentoCli, $documentoCli);
					else if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && empty($documentoCli)) $stmt->bind_param('sissisissi', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal, $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal);
					else $stmt->bind_param('sisssiss', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta);

				}
				else
				{
					if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $stmt->bind_param('sissiis', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal, $tipoDocumentoCli, $documentoCli);
					else if($sucursal == translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $stmt->bind_param('sissis', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $tipoDocumentoCli, $documentoCli);
					else if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && empty($documentoCli)) $stmt->bind_param('sissi', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal);
					else $stmt->bind_param('siss', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta);
				}
				
				$stmt->execute();    
				$stmt->store_result();

				if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang'])) $stmt->bind_result($codigo_sucursal_reporte, $nombre_sucursal_reporte, $monto_promedio_creditos_sucursal_reporte, $nombres_cliente_reporte, $apellidos_cliente_reporte, $id_titular_cliente_reporte, $tipo_documento_cliente_reporte, $documento_cliente_reporte);
				else $stmt->bind_result($monto_promedio_creditos_sucursal_reporte, $nombres_cliente_reporte, $apellidos_cliente_reporte, $id_titular_cliente_reporte, $tipo_documento_cliente_reporte, $documento_cliente_reporte);
			
				$totR = $stmt->num_rows;
				if($totR == 0)
				{
					echo translate('Msg_Report_PDF_Not_Data_View',$GLOBALS['lang']);
					return;	
				}					
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']).$mysqli->error;
				return;	
			}			
		}		

		if($id_reporte_db == 6)
		{
			if(!empty($_GET["fechaDesde"]) && !empty($_GET["fechaHasta"]) && !empty($_GET["sucursal"]) && !empty($_GET["tipoDocumento"])) 
			{
				$fechaDesde=htmlspecialchars($_GET["fechaDesde"], ENT_QUOTES, 'UTF-8');
				$fechaHasta=htmlspecialchars($_GET["fechaHasta"], ENT_QUOTES, 'UTF-8');
				
				$sucursal=htmlspecialchars($_GET["sucursal"], ENT_QUOTES, 'UTF-8');
				$tipoDocumentoCli=htmlspecialchars($_GET["tipoDocumento"], ENT_QUOTES, 'UTF-8');
				$documentoCli=htmlspecialchars($_GET["documento"], ENT_QUOTES, 'UTF-8');
			}
			else
			{
				$fechaDesde=htmlspecialchars($_POST["fechaDesde"], ENT_QUOTES, 'UTF-8');
				$fechaHasta=htmlspecialchars($_POST["fechaHasta"], ENT_QUOTES, 'UTF-8');

				$sucursal=htmlspecialchars($_POST["sucursal"], ENT_QUOTES, 'UTF-8');
				$tipoDocumentoCli=htmlspecialchars($_POST["tipoDocumento"], ENT_QUOTES, 'UTF-8');
				$documentoCli=htmlspecialchars($_POST["documento"], ENT_QUOTES, 'UTF-8');
			}
			
			if(!empty($documentoCli))
			{
				if($stmt47 = $mysqli->prepare("SELECT c.id_titular, c.nombres, c.apellidos FROM finan_cli.cliente c WHERE c.tipo_documento = ? AND c.documento = ?"))
				{
					$stmt47->bind_param('is', $tipoDocumentoCli, $documentoCli);
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
						$stmt47->bind_result($id_titular_cliente_db, $nombres_cliente_db, $apellidos_cliente_db);
						$stmt47->fetch();
						
						$stmt47->free_result();
						$stmt47->close();
					}
				}
				else
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;				
				}
			}
			
			if(empty($id_titular_cliente_db))
			{
				if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) 
				{
					$selectReportID6 = "SELECT s.codigo, s.nombre, COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento AND cli.documento = cc.documento AND cc.id_sucursal = s.id AND c.estado = ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING COUNT(c.monto_credito_original) IS NOT NULL UNION ALL SELECT s.codigo, s.nombre, COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado = ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING COUNT(c.monto_credito_original)";
				}
				else if($sucursal == translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli))
				{
					$selectReportID6 = "SELECT COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento AND cli.documento = cc.documento AND cc.id_sucursal = s.id AND c.estado = ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING COUNT(c.monto_credito_original) IS NOT NULL UNION ALL SELECT COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado = ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING COUNT(c.monto_credito_original) IS NOT NULL";
				}
				else if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && empty($documentoCli)) 
				{
					$selectReportID6 = "SELECT s.codigo, s.nombre, COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento AND cli.documento = cc.documento AND cc.id_sucursal = s.id AND c.estado = ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING COUNT(c.monto_credito_original) IS NOT NULL UNION ALL SELECT s.codigo, s.nombre, COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado = ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING COUNT(c.monto_credito_original) IS NOT NULL";
				}
				else 
				{
					$selectReportID6 = "SELECT COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento AND cli.documento = cc.documento AND cc.id_sucursal = s.id AND c.estado = ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? GROUP BY cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING COUNT(c.monto_credito_original) IS NOT NULL UNION ALL SELECT COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado = ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? GROUP BY cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING COUNT(c.monto_credito_original) IS NOT NULL";
				}
			}
			else
			{
				if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $selectReportID6 = "SELECT s.codigo, s.nombre, COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado = ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos HAVING COUNT(c.monto_credito_original) IS NOT NULL";
				else if($sucursal == translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $selectReportID6 = "SELECT COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado = ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY cli.nombres, cli.apellidos HAVING COUNT(c.monto_credito_original) IS NOT NULL";
				else if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && empty($documentoCli)) $selectReportID6 = "SELECT s.codigo, s.nombre, COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado = ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos HAVING COUNT(c.monto_credito_original) IS NOT NULL";
				else $selectReportID6 = "SELECT COUNT(c.monto_credito_original), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado = ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? GROUP BY cli.nombres, cli.apellidos HAVING COUNT(c.monto_credito_original) IS NOT NULL";				
			}
			
			if ($stmt = $mysqli->prepare($selectReportID6)) 
			{
				$fechaDesde = substr($fechaDesde, 6, 4).substr($fechaDesde, 3, 2).substr($fechaDesde, 0, 2).'000000';
				$fechaHasta = substr($fechaHasta, 6, 4).substr($fechaHasta, 3, 2).substr($fechaHasta, 0, 2).'235959';
				$estadoCanceladoS = translate('Lbl_Status_Fee_Canceled',$GLOBALS['lang']);
				
				if(empty($id_titular_cliente_db))
				{
					if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $stmt->bind_param('sissiississiis', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal, $tipoDocumentoCli, $documentoCli, $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal, $tipoDocumentoCli, $documentoCli);
					else if($sucursal == translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $stmt->bind_param('sissississis', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $tipoDocumentoCli, $documentoCli, $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $tipoDocumentoCli, $documentoCli);
					else if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && empty($documentoCli)) $stmt->bind_param('sissisissi', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal, $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal);
					else $stmt->bind_param('sisssiss', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta);

				}
				else
				{
					if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $stmt->bind_param('sissiis', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal, $tipoDocumentoCli, $documentoCli);
					else if($sucursal == translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $stmt->bind_param('sissis', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $tipoDocumentoCli, $documentoCli);
					else if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && empty($documentoCli)) $stmt->bind_param('sissi', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal);
					else $stmt->bind_param('siss', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta);
				}
				
				$stmt->execute();    
				$stmt->store_result();

				if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang'])) $stmt->bind_result($codigo_sucursal_reporte, $nombre_sucursal_reporte, $monto_promedio_creditos_sucursal_reporte, $nombres_cliente_reporte, $apellidos_cliente_reporte, $id_titular_cliente_reporte, $tipo_documento_cliente_reporte, $documento_cliente_reporte);
				else $stmt->bind_result($monto_promedio_creditos_sucursal_reporte, $nombres_cliente_reporte, $apellidos_cliente_reporte, $id_titular_cliente_reporte, $tipo_documento_cliente_reporte, $documento_cliente_reporte);
			
				$totR = $stmt->num_rows;
				if($totR == 0)
				{
					echo translate('Msg_Report_PDF_Not_Data_View',$GLOBALS['lang']);
					return;	
				}					
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']).$mysqli->error;
				return;	
			}			
		}
		
		if($id_reporte_db == 7)
		{
			if(!empty($_GET["fechaDesde"]) && !empty($_GET["fechaHasta"]) && !empty($_GET["tipoDocumento2"]) && !empty($_GET["documento2"])) 
			{
				$fechaDesde=htmlspecialchars($_GET["fechaDesde"], ENT_QUOTES, 'UTF-8');
				$fechaHasta=htmlspecialchars($_GET["fechaHasta"], ENT_QUOTES, 'UTF-8');
				
				$tipoDocumentoCli=htmlspecialchars($_GET["tipoDocumento2"], ENT_QUOTES, 'UTF-8');
				$documentoCli=htmlspecialchars($_GET["documento2"], ENT_QUOTES, 'UTF-8');
			}
			else
			{
				$fechaDesde=htmlspecialchars($_POST["fechaDesde"], ENT_QUOTES, 'UTF-8');
				$fechaHasta=htmlspecialchars($_POST["fechaHasta"], ENT_QUOTES, 'UTF-8');

				$tipoDocumentoCli=htmlspecialchars($_POST["tipoDocumento2"], ENT_QUOTES, 'UTF-8');
				$documentoCli=htmlspecialchars($_POST["documento2"], ENT_QUOTES, 'UTF-8');
			}
			
			if(!empty($documentoCli))
			{
				if($stmt47 = $mysqli->prepare("SELECT c.id_titular, c.nombres, c.apellidos FROM finan_cli.cliente c WHERE c.tipo_documento = ? AND c.documento = ?"))
				{
					$stmt47->bind_param('is', $tipoDocumentoCli, $documentoCli);
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
						$stmt47->bind_result($id_titular_cliente_db, $nombres_cliente_db, $apellidos_cliente_db);
						$stmt47->fetch();
						
						$stmt47->free_result();
						$stmt47->close();
					}
				}
				else
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;				
				}
			}
			
			if(empty($id_titular_cliente_db))
			{
				$selectReportID7 = "SELECT c.id, c.cantidad_cuotas, ccre.numero_cuota, ccre.fecha_vencimiento, ccre.fecha_pago, ccre.monto_pago, ccre.usuario_registro_pago FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.cliente cli, finan_cli.cuota_credito ccre, finan_cli.sucursal s WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento AND cli.documento = cc.documento AND c.id = ccre.id_credito AND cc.id_sucursal = s.id AND ccre.estado = ? AND s.id_cadena = ? AND ccre.fecha_pago BETWEEN ? AND ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY c.id, c.cantidad_cuotas, ccre.numero_cuota, ccre.fecha_vencimiento, ccre.monto_pago, ccre.usuario_registro_pago ORDER BY ccre.fecha_pago";
			}
			else
			{
				$selectReportID7 = "SELECT c.id, c.cantidad_cuotas, ccre.numero_cuota, ccre.fecha_vencimiento, ccre.fecha_pago, ccre.monto_pago, ccre.usuario_registro_pago FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.cliente cli, finan_cli.cuota_credito ccre, finan_cli.sucursal s WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND c.id = ccre.id_credito AND cc.id_sucursal = s.id AND ccre.estado = ? AND s.id_cadena = ? AND ccre.fecha_pago BETWEEN ? AND ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY c.id, c.cantidad_cuotas, ccre.numero_cuota, ccre.fecha_vencimiento, ccre.monto_pago, ccre.usuario_registro_pago ORDER BY ccre.fecha_pago";			
			}
			
			if ($stmt = $mysqli->prepare($selectReportID7)) 
			{
				$fechaDesde = substr($fechaDesde, 6, 4).substr($fechaDesde, 3, 2).substr($fechaDesde, 0, 2).'000000';
				$fechaHasta = substr($fechaHasta, 6, 4).substr($fechaHasta, 3, 2).substr($fechaHasta, 0, 2).'235959';
				$estadoPagadoS = translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']);
				

				$stmt->bind_param('sissis', $estadoPagadoS, $id_cadena_user, $fechaDesde, $fechaHasta, $tipoDocumentoCli, $documentoCli);				
				$stmt->execute();    
				$stmt->store_result();

				$stmt->bind_result($id_credito_reporte, $cantidad_cuotas_credito_reporte, $numero_cuota_pagada_reporte, $fecha_vencimiento_cuota_pagada_reporte, $fecha_pago_cuota_reporte, $monto_pago_cuota_reporte, $usuario_registra_pago_cuota_reporte);
			
				$totR = $stmt->num_rows;
				if($totR == 0)
				{
					echo translate('Msg_Report_PDF_Not_Data_View',$GLOBALS['lang']);
					return;	
				}					
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']).$mysqli->error;
				return;	
			}			
		}

		if($id_reporte_db == 8)
		{
			if(!empty($_GET["fechaDesde"]) && !empty($_GET["fechaHasta"]) && !empty($_GET["tipoDocumento2"]) && !empty($_GET["documento2"])) 
			{
				$fechaDesde=htmlspecialchars($_GET["fechaDesde"], ENT_QUOTES, 'UTF-8');
				$fechaHasta=htmlspecialchars($_GET["fechaHasta"], ENT_QUOTES, 'UTF-8');
				
				$tipoDocumentoCli=htmlspecialchars($_GET["tipoDocumento2"], ENT_QUOTES, 'UTF-8');
				$documentoCli=htmlspecialchars($_GET["documento2"], ENT_QUOTES, 'UTF-8');
			}
			else
			{
				$fechaDesde=htmlspecialchars($_POST["fechaDesde"], ENT_QUOTES, 'UTF-8');
				$fechaHasta=htmlspecialchars($_POST["fechaHasta"], ENT_QUOTES, 'UTF-8');

				$tipoDocumentoCli=htmlspecialchars($_POST["tipoDocumento2"], ENT_QUOTES, 'UTF-8');
				$documentoCli=htmlspecialchars($_POST["documento2"], ENT_QUOTES, 'UTF-8');
			}
			
			if(!empty($documentoCli))
			{
				if($stmt47 = $mysqli->prepare("SELECT c.id_titular, c.nombres, c.apellidos FROM finan_cli.cliente c WHERE c.tipo_documento = ? AND c.documento = ?"))
				{
					$stmt47->bind_param('is', $tipoDocumentoCli, $documentoCli);
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
						$stmt47->bind_result($id_titular_cliente_db, $nombres_cliente_db, $apellidos_cliente_db);
						$stmt47->fetch();
						
						$stmt47->free_result();
						$stmt47->close();
					}
				}
				else
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;				
				}
			}
			
			if(empty($id_titular_cliente_db))
			{
				$selectReportID8 = "SELECT c.id, c.cantidad_cuotas, ccre.numero_cuota, ccre.estado, ccre.fecha_vencimiento, ccre.monto_cuota_original, cc.id_usuario FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.cliente cli, finan_cli.cuota_credito ccre, finan_cli.sucursal s WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento AND cli.documento = cc.documento AND c.id = ccre.id_credito AND cc.id_sucursal = s.id AND ccre.estado IN (?,?) AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY c.id, c.cantidad_cuotas, ccre.numero_cuota, ccre.fecha_vencimiento, ccre.monto_pago, ccre.usuario_registro_pago ORDER BY c.id, ccre.numero_cuota, cc.fecha";
			}
			else
			{
				$selectReportID8 = "SELECT c.id, c.cantidad_cuotas, ccre.numero_cuota, ccre.estado, ccre.fecha_vencimiento, ccre.monto_cuota_original, cc.id_usuario FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.cliente cli, finan_cli.cuota_credito ccre, finan_cli.sucursal s WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND c.id = ccre.id_credito AND cc.id_sucursal = s.id AND ccre.estado IN (?,?) AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY c.id, c.cantidad_cuotas, ccre.numero_cuota, ccre.fecha_vencimiento, ccre.monto_pago, ccre.usuario_registro_pago ORDER BY c.id, ccre.numero_cuota, cc.fecha";			
			}
			
			if ($stmt = $mysqli->prepare($selectReportID8)) 
			{
				$fechaDesde = substr($fechaDesde, 6, 4).substr($fechaDesde, 3, 2).substr($fechaDesde, 0, 2).'000000';
				$fechaHasta = substr($fechaHasta, 6, 4).substr($fechaHasta, 3, 2).substr($fechaHasta, 0, 2).'235959';
				$estadoPendienteS = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
				$estadoEnMoraS = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
				

				$stmt->bind_param('ssissis', $estadoPendienteS, $estadoEnMoraS, $id_cadena_user, $fechaDesde, $fechaHasta, $tipoDocumentoCli, $documentoCli);				
				$stmt->execute();    
				$stmt->store_result();

				$stmt->bind_result($id_credito_reporte, $cantidad_cuotas_credito_reporte, $numero_cuota_pendiente_reporte, $estado_cuota_pendiente_reporte, $fecha_vencimiento_cuota_pendiente_reporte, $monto_cuota_pendiente_reporte, $usuario_registra_credito_reporte);
			
				$totR = $stmt->num_rows;
				if($totR == 0)
				{
					echo translate('Msg_Report_PDF_Not_Data_View',$GLOBALS['lang']);
					return;	
				}					
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']).$mysqli->error;
				return;	
			}			
		}		
		
		if($id_reporte_db == 9)
		{
			if(!empty($_GET["fechaDesde"]) && !empty($_GET["fechaHasta"]) && !empty($_GET["sucursal"]) && !empty($_GET["tipoDocumento"])) 
			{
				$fechaDesde=htmlspecialchars($_GET["fechaDesde"], ENT_QUOTES, 'UTF-8');
				$fechaHasta=htmlspecialchars($_GET["fechaHasta"], ENT_QUOTES, 'UTF-8');
				
				$sucursal=htmlspecialchars($_GET["sucursal"], ENT_QUOTES, 'UTF-8');
				$tipoDocumentoCli=htmlspecialchars($_GET["tipoDocumento"], ENT_QUOTES, 'UTF-8');
				$documentoCli=htmlspecialchars($_GET["documento"], ENT_QUOTES, 'UTF-8');
			}
			else
			{
				$fechaDesde=htmlspecialchars($_POST["fechaDesde"], ENT_QUOTES, 'UTF-8');
				$fechaHasta=htmlspecialchars($_POST["fechaHasta"], ENT_QUOTES, 'UTF-8');

				$sucursal=htmlspecialchars($_POST["sucursal"], ENT_QUOTES, 'UTF-8');
				$tipoDocumentoCli=htmlspecialchars($_POST["tipoDocumento"], ENT_QUOTES, 'UTF-8');
				$documentoCli=htmlspecialchars($_POST["documento"], ENT_QUOTES, 'UTF-8');
			}
			
			if(!empty($documentoCli))
			{
				if($stmt47 = $mysqli->prepare("SELECT c.id_titular, c.nombres, c.apellidos FROM finan_cli.cliente c WHERE c.tipo_documento = ? AND c.documento = ?"))
				{
					$stmt47->bind_param('is', $tipoDocumentoCli, $documentoCli);
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
						$stmt47->bind_result($id_titular_cliente_db, $nombres_cliente_db, $apellidos_cliente_db);
						$stmt47->fetch();
						
						$stmt47->free_result();
						$stmt47->close();
					}
				}
				else
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;				
				}
			}
			
			if(empty($id_titular_cliente_db))
			{
				if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) 
				{
					$selectReportID4 = "SELECT s.codigo, s.nombre, AVG(c.monto_compra), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento AND cli.documento = cc.documento AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING AVG(c.monto_compra) IS NOT NULL UNION ALL SELECT s.codigo, s.nombre, AVG(c.monto_compra), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING AVG(c.monto_compra)";
				}
				else if($sucursal == translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli))
				{
					$selectReportID4 = "SELECT AVG(c.monto_compra), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento AND cli.documento = cc.documento AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING AVG(c.monto_compra) IS NOT NULL UNION ALL SELECT AVG(c.monto_compra), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING AVG(c.monto_compra) IS NOT NULL";
				}
				else if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && empty($documentoCli)) 
				{
					$selectReportID4 = "SELECT s.codigo, s.nombre, AVG(c.monto_compra), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento AND cli.documento = cc.documento AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING AVG(c.monto_compra) IS NOT NULL UNION ALL SELECT s.codigo, s.nombre, AVG(c.monto_compra), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING AVG(c.monto_compra) IS NOT NULL";
				}
				else 
				{
					$selectReportID4 = "SELECT AVG(c.monto_compra), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento AND cli.documento = cc.documento AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? GROUP BY cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING AVG(c.monto_compra) IS NOT NULL UNION ALL SELECT AVG(c.monto_compra), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? GROUP BY cli.nombres, cli.apellidos, cli.tipo_documento, cli.documento HAVING AVG(c.monto_compra) IS NOT NULL";
				}
			}
			else
			{
				if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $selectReportID4 = "SELECT s.codigo, s.nombre, AVG(c.monto_compra), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos HAVING AVG(c.monto_compra) IS NOT NULL";
				else if($sucursal == translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $selectReportID4 = "SELECT AVG(c.monto_compra), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND cli.tipo_documento = ? AND cli.documento = ? GROUP BY cli.nombres, cli.apellidos HAVING AVG(c.monto_compra) IS NOT NULL";
				else if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && empty($documentoCli)) $selectReportID4 = "SELECT s.codigo, s.nombre, AVG(c.monto_compra), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? AND s.codigo = ? GROUP BY s.codigo, s.nombre, cli.nombres, cli.apellidos HAVING AVG(c.monto_compra) IS NOT NULL";
				else $selectReportID4 = "SELECT AVG(c.monto_compra), cli.nombres, cli.apellidos, cli.id_titular, cli.tipo_documento, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.cliente cli WHERE c.id = cc.id_credito AND cli.tipo_documento = cc.tipo_documento_adicional AND cli.documento = cc.documento_adicional AND cc.id_sucursal = s.id AND c.estado <> ? AND s.id_cadena = ? AND cc.fecha BETWEEN ? AND ? GROUP BY cli.nombres, cli.apellidos HAVING AVG(c.monto_compra) IS NOT NULL";				
			}
			
			if ($stmt = $mysqli->prepare($selectReportID4)) 
			{
				$fechaDesde = substr($fechaDesde, 6, 4).substr($fechaDesde, 3, 2).substr($fechaDesde, 0, 2).'000000';
				$fechaHasta = substr($fechaHasta, 6, 4).substr($fechaHasta, 3, 2).substr($fechaHasta, 0, 2).'235959';
				$estadoCanceladoS = translate('Lbl_Status_Fee_Canceled',$GLOBALS['lang']);
				
				if(empty($id_titular_cliente_db))
				{
					if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $stmt->bind_param('sissiississiis', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal, $tipoDocumentoCli, $documentoCli, $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal, $tipoDocumentoCli, $documentoCli);
					else if($sucursal == translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $stmt->bind_param('sissississis', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $tipoDocumentoCli, $documentoCli, $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $tipoDocumentoCli, $documentoCli);
					else if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && empty($documentoCli)) $stmt->bind_param('sissisissi', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal, $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal);
					else $stmt->bind_param('sisssiss', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta);

				}
				else
				{
					if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $stmt->bind_param('sissiis', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal, $tipoDocumentoCli, $documentoCli);
					else if($sucursal == translate('Lbl_All_Selection',$GLOBALS['lang']) && !empty($documentoCli)) $stmt->bind_param('sissis', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $tipoDocumentoCli, $documentoCli);
					else if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']) && empty($documentoCli)) $stmt->bind_param('sissi', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal);
					else $stmt->bind_param('siss', $estadoCanceladoS, $id_cadena_user, $fechaDesde, $fechaHasta);
				}
				
				$stmt->execute();    
				$stmt->store_result();

				if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang'])) $stmt->bind_result($codigo_sucursal_reporte, $nombre_sucursal_reporte, $monto_promedio_compra_creditos_sucursal_reporte, $nombres_cliente_reporte, $apellidos_cliente_reporte, $id_titular_cliente_reporte, $tipo_documento_cliente_reporte, $documento_cliente_reporte);
				else $stmt->bind_result($monto_promedio_compra_creditos_sucursal_reporte, $nombres_cliente_reporte, $apellidos_cliente_reporte, $id_titular_cliente_reporte, $tipo_documento_cliente_reporte, $documento_cliente_reporte);
			
				$totR = $stmt->num_rows;
				if($totR == 0)
				{
					echo translate('Msg_Report_PDF_Not_Data_View',$GLOBALS['lang']);
					return;	
				}					
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']).$mysqli->error;
				return;	
			}			
		}
		
		if($id_reporte_db == 10)
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
				
			if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang'])) $selectReportID10 = "SELECT s.codigo, s.nombre, COUNT(es.id) FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.aviso_x_mora axm, finan_cli.envio_sms es WHERE axm.id = es.id_aviso_x_mora AND axm.id_credito = c.id AND c.id = cc.id_credito AND cc.id_sucursal = s.id AND s.id_cadena = ? AND es.fecha BETWEEN ? AND ? AND s.codigo = ? GROUP BY s.codigo, s.nombre HAVING COUNT(es.id) IS NOT NULL";
			else $selectReportID10 = "SELECT cad.nombre_fantasia, COUNT(es.id) FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.aviso_x_mora axm, finan_cli.envio_sms es, finan_cli.cadena cad WHERE axm.id = es.id_aviso_x_mora AND axm.id_credito = c.id AND c.id = cc.id_credito AND cc.id_sucursal = s.id AND cad.id = s.id_cadena AND cad.id = ? AND es.fecha BETWEEN ? AND ? GROUP BY cad.nombre_fantasia HAVING COUNT(es.id) IS NOT NULL";
				
			if ($stmt = $mysqli->prepare($selectReportID10)) 
			{
				$fechaDesde = substr($fechaDesde, 6, 4).substr($fechaDesde, 3, 2).substr($fechaDesde, 0, 2).'000000';
				$fechaHasta = substr($fechaHasta, 6, 4).substr($fechaHasta, 3, 2).substr($fechaHasta, 0, 2).'235959';
				
				if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang'])) $stmt->bind_param('issi', $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal);
				else $stmt->bind_param('iss', $id_cadena_user, $fechaDesde, $fechaHasta);
				$stmt->execute();    
				$stmt->store_result();

				if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang'])) $stmt->bind_result($codigo_sucursal_reporte, $nombre_sucursal_reporte, $cantidad_registros_sms_reporte);				
				else $stmt->bind_result($nombre_cadena_reporte, $cantidad_registros_sms_reporte); 
					
				$totR = $stmt->num_rows;

				if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang'])) $selectReportID10o2 = "SELECT s.codigo, s.nombre, COUNT(tvc.id) FROM finan_cli.sucursal s, finan_cli.token_validacion_celular tvc, finan_cli.usuario u WHERE tvc.usuario = u.id AND u.id_sucursal = s.id AND s.id_cadena = ? AND tvc.fecha BETWEEN ? AND ? AND s.codigo = ? GROUP BY s.codigo, s.nombre HAVING COUNT(tvc.id) IS NOT NULL";
				else $selectReportID10o2 = "SELECT cad.nombre_fantasia, COUNT(tvc.id) FROM finan_cli.sucursal s, finan_cli.token_validacion_celular tvc, finan_cli.usuario u, finan_cli.cadena cad WHERE tvc.usuario = u.id AND u.id_sucursal = s.id AND cad.id = s.id_cadena AND cad.id = ? AND tvc.fecha BETWEEN ? AND ? GROUP BY cad.nombre_fantasia HAVING COUNT(tvc.id) IS NOT NULL";				
				
				if ($stmto2 = $mysqli->prepare($selectReportID10o2)) 
				{
					if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang'])) $stmto2->bind_param('issi', $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal);
					else $stmto2->bind_param('iss', $id_cadena_user, $fechaDesde, $fechaHasta);
					$stmto2->execute();    
					$stmto2->store_result();

					if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang'])) $stmto2->bind_result($codigo_sucursalo2_reporte, $nombre_sucursalo2_reporte, $cantidad_registros_smso2_reporte);				
					else $stmto2->bind_result($nombre_cadenao2_reporte, $cantidad_registros_smso2_reporte); 
						
					$totRo2 = $stmto2->num_rows;
					
					if($totRo2 > 0)
					{
						$stmto2->fetch();
							
						$stmto2->free_result();
						$stmto2->close();
					}
				}
				else
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']).$mysqli->error;
					return;	
				}
				
				if($totR == 0 && $totRo2 == 0)
				{
					echo translate('Msg_Report_PDF_Not_Data_View',$GLOBALS['lang']);
					return;	
				}					
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']).$mysqli->error;
				return;	
			}			
		}

		if($id_reporte_db == 11)
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
				
			if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang'])) $selectReportID11 = "SELECT s.codigo, s.nombre, COUNT(cef.id) FROM finan_cli.sucursal s, finan_cli.consulta_estado_financiero cef WHERE cef.id_cadena = s.id_cadena AND s.id_cadena = ? AND cef.fecha BETWEEN ? AND ? AND s.codigo = ? GROUP BY s.codigo, s.nombre HAVING COUNT(cef.id) IS NOT NULL";
			else $selectReportID11 = "SELECT cad.nombre_fantasia, COUNT(cef.id) FROM finan_cli.consulta_estado_financiero cef, finan_cli.cadena cad WHERE cef.id_cadena = cad.id AND cad.id = ? AND cef.fecha BETWEEN ? AND ? GROUP BY cad.nombre_fantasia HAVING COUNT(cef.id) IS NOT NULL";
				
			if ($stmt = $mysqli->prepare($selectReportID11)) 
			{
				$fechaDesde = substr($fechaDesde, 6, 4).substr($fechaDesde, 3, 2).substr($fechaDesde, 0, 2).'000000';
				$fechaHasta = substr($fechaHasta, 6, 4).substr($fechaHasta, 3, 2).substr($fechaHasta, 0, 2).'235959';
				
				if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang'])) $stmt->bind_param('issi', $id_cadena_user, $fechaDesde, $fechaHasta, $sucursal);
				else $stmt->bind_param('iss', $id_cadena_user, $fechaDesde, $fechaHasta);
				$stmt->execute();    
				$stmt->store_result();

				if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang'])) $stmt->bind_result($codigo_sucursal_reporte, $nombre_sucursal_reporte, $cantidad_consultas_ef_reporte);				
				else $stmt->bind_result($nombre_cadena_reporte, $cantidad_consultas_ef_reporte); 
					
				$totR = $stmt->num_rows;

				if($totR == 0)
				{
					echo translate('Msg_Report_PDF_Not_Data_View',$GLOBALS['lang']);
					return;	
				}					
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']).$mysqli->error;
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
				if($_GET["idReporte"] == 4 || $_GET["idReporte"] == 5 || $_GET["idReporte"] == 6 || $_GET["idReporte"] == 9) 
				{
					if(!empty($_GET["documento"])) $this->Cell(240,11,'  '.translate('Lbl_Date_Since_Report',$GLOBALS['lang']).': '.$_GET["fechaDesde"].'  ||  '.translate('Lbl_Date_Until_Report',$GLOBALS['lang']).': '.$_GET["fechaHasta"].'  ||  '.translate('Lbl_Tender_User',$GLOBALS['lang']).': '.$_GET["nombreSucursal"].'  ||  '.translate('Lbl_Type_Document_User',$GLOBALS['lang']).': '.$_GET["nombreTipoDocumento"].'  ||  '.translate('Lbl_Document_Client',$GLOBALS['lang']).': '.$_GET["documento"].'  ||  '.translate('Lbl_User_Print',$GLOBALS['lang']).': '.$_SESSION['username'],1,0,'L',True);
					else $this->Cell(240,11,'  '.translate('Lbl_Date_Since_Report',$GLOBALS['lang']).': '.$_GET["fechaDesde"].'  ||  '.translate('Lbl_Date_Until_Report',$GLOBALS['lang']).': '.$_GET["fechaHasta"].'  ||  '.translate('Lbl_Tender_User',$GLOBALS['lang']).': '.$_GET["nombreSucursal"].'  ||  '.translate('Lbl_Type_Document_User',$GLOBALS['lang']).': '.translate('Lbl_All_Selection2',$GLOBALS['lang']).'  ||  '.translate('Lbl_Document_Client',$GLOBALS['lang']).': '.translate('Lbl_All_Selection2',$GLOBALS['lang']).'  ||  '.translate('Lbl_User_Print',$GLOBALS['lang']).': '.$_SESSION['username'],1,0,'L',True);
				}
				if($_GET["idReporte"] == 7 || $_GET["idReporte"] == 8) 
				{
					$this->Cell(240,11,'  '.translate('Lbl_Date_Since_Report',$GLOBALS['lang']).': '.$_GET["fechaDesde"].'  ||  '.translate('Lbl_Date_Until_Report',$GLOBALS['lang']).': '.$_GET["fechaHasta"].'  ||  '.translate('Lbl_Type_Document_User',$GLOBALS['lang']).': '.$_GET["nombreTipoDocumento"].'  ||  '.translate('Lbl_Document_Client',$GLOBALS['lang']).': '.$_GET["documento2"].'  ||  '.translate('Lbl_User_Print',$GLOBALS['lang']).': '.$_SESSION['username'],1,0,'L',True);
				}
				if($_GET["idReporte"] == 10 || $_GET["idReporte"] == 11) $this->Cell(230,11,'  '.translate('Lbl_Date_Since_Report',$GLOBALS['lang']).': '.$_GET["fechaDesde"].'  ||  '.translate('Lbl_Date_Until_Report',$GLOBALS['lang']).': '.$_GET["fechaHasta"].'  ||  '.translate('Lbl_Tender_User',$GLOBALS['lang']).': '.$_GET["nombreSucursal"].'  ||  '.translate('Lbl_User_Print',$GLOBALS['lang']).': '.$_SESSION['username'],1,0,'L',True);
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

		if($id_reporte_db == 4 || $id_reporte_db == 5 || $id_reporte_db == 6 || $id_reporte_db == 9)
		{
			if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']))
			{
				$pdf->SetFillColor(17,58,154);
				$pdf->SetTextColor(255,255,255);
				$pdf->Cell(15,10,'CÓDIGO',1,0,'C',True);
				$pdf->Cell(35,10,'NOMBRE SUCURSAL',1,0, 'C',True);
				$pdf->Cell(30,10,'TIPO DOCUMENTO',1,0,'C',True);
				$pdf->Cell(25,10,'DOCUMENTO',1,0,'C',True);
				$pdf->Cell(40,10,'NOMBRES',1,0,'C',True);
				$pdf->Cell(35,10,'APELLIDOS',1,0, 'C',True);
				$pdf->Cell(25,10,'TIPO CUENTA',1,0, 'C',True);
				if($id_reporte_db == 4) $pdf->Cell(35,10,'MONTO PROMEDIO',1, 0,'C',True);
				else if($id_reporte_db == 9) $pdf->Cell(35,10,'MONTO PROMEDIO',1, 0,'C',True);
				else $pdf->Cell(35,10,'CANTIDAD CRÉDITOS',1, 0,'C',True);
				$pdf->SetTextColor(0,0,0);
				
				while($stmt->fetch()) 
				{
					$pdf->Ln();
					$pdf->Cell(15,10,$codigo_sucursal_reporte,1,0,'C');
					$pdf->Cell(35,10,iconv('UTF-8', 'windows-1252', $nombre_sucursal_reporte),1,0,'C');
					$pdf->Cell(30,10,iconv('UTF-8', 'windows-1252', $tipo_documento_cliente_reporte),1,0,'C');
					$pdf->Cell(25,10,$documento_cliente_reporte,1,0,'C');					
					$pdf->Cell(40,10,iconv('UTF-8', 'windows-1252', $nombres_cliente_reporte),1,0,'C');
					$pdf->Cell(35,10,iconv('UTF-8', 'windows-1252', $apellidos_cliente_reporte),1,0,'C');
					if(empty($id_titular_cliente_reporte)) $pdf->Cell(25,10,iconv('UTF-8', 'windows-1252', translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang'])),1,0,'C');
					else $pdf->Cell(25,10,iconv('UTF-8', 'windows-1252', translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang'])),1,0,'C');
					if($id_reporte_db == 4) $pdf->Cell(35,10,'$'.number_format(($monto_promedio_creditos_sucursal_reporte/100.00), 2, ',', '.'),1,0,'C');
					else if($id_reporte_db == 9) $pdf->Cell(35,10,'$'.number_format(($monto_promedio_compra_creditos_sucursal_reporte/100.00), 2, ',', '.'),1,0,'C');
					else $pdf->Cell(35,10,$monto_promedio_creditos_sucursal_reporte,1,0,'C');
				}
			}
			else
			{
				$pdf->SetFillColor(17,58,154);
				$pdf->SetTextColor(255,255,255);
				$pdf->Cell(30,10,'TIPO DOCUMENTO',1,0,'C',True);
				$pdf->Cell(30,10,'DOCUMENTO',1,0,'C',True);
				$pdf->Cell(45,10,'NOMBRES',1,0,'C',True);
				$pdf->Cell(45,10,'APELLIDOS',1,0, 'C',True);
				$pdf->Cell(40,10,'TIPO CUENTA',1,0, 'C',True);
				if($id_reporte_db == 4) $pdf->Cell(50,10,'MONTO PROMEDIO',1, 0,'C',True);
				else if($id_reporte_db == 9) $pdf->Cell(50,10,'MONTO PROMEDIO',1, 0,'C',True);
				else $pdf->Cell(50,10,'CANTIDAD CRÉDITOS',1, 0,'C',True);
				$pdf->SetTextColor(0,0,0);
				
				while($stmt->fetch()) 
				{
					$pdf->Ln();
					$pdf->Cell(30,10,iconv('UTF-8', 'windows-1252', $tipo_documento_cliente_reporte),1,0,'C');
					$pdf->Cell(30,10,$documento_cliente_reporte,1,0,'C');
					$pdf->Cell(45,10,iconv('UTF-8', 'windows-1252', $nombres_cliente_reporte),1,0,'C');
					$pdf->Cell(45,10,iconv('UTF-8', 'windows-1252', $apellidos_cliente_reporte),1,0,'C');
					if(empty($id_titular_cliente_reporte)) $pdf->Cell(40,10,iconv('UTF-8', 'windows-1252', translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang'])),1,0,'C');
					else $pdf->Cell(40,10,iconv('UTF-8', 'windows-1252', translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang'])),1,0,'C');					
					if($id_reporte_db == 4) $pdf->Cell(50,10,'$'.number_format(($monto_promedio_creditos_sucursal_reporte/100.00), 2, ',', '.'),1,0,'C');
					else if($id_reporte_db == 9) $pdf->Cell(50,10,'$'.number_format(($monto_promedio_compra_creditos_sucursal_reporte/100.00), 2, ',', '.'),1,0,'C');
					else $pdf->Cell(50,10,$monto_promedio_creditos_sucursal_reporte,1,0,'C');
				}
			}
		}			
		
		if($id_reporte_db == 7)
		{
			$pdf->SetFillColor(17,58,154);
			$pdf->SetTextColor(255,255,255);
						
			$pdf->Cell(30,10,'CRÉDITO',1,0,'C',True);
			$pdf->Cell(30,10,'CUOTAS',1,0, 'C',True);
			$pdf->Cell(30,10,'NRO. CUOTA',1, 0,'C',True);			
			$pdf->Cell(40,10,'FECHA VENC.',1,0,'C',True);
			$pdf->Cell(40,10,'FECHA PAGO',1,0, 'C',True);
			$pdf->Cell(30,10,'MONTO PAGO',1, 0,'C',True);
			$pdf->Cell(40,10,'USUARIO',1, 0,'C',True);
			$pdf->SetTextColor(0,0,0);
			
			while($stmt->fetch()) 
			{
				$pdf->Ln();
				$pdf->Cell(30,10,$id_credito_reporte,1,0,'C');
				$pdf->Cell(30,10,$cantidad_cuotas_credito_reporte,1,0,'C');
				$pdf->Cell(30,10,$numero_cuota_pagada_reporte,1,0,'C');
				$pdf->Cell(40,10,substr($fecha_vencimiento_cuota_pagada_reporte, 6, 2).'-'.substr($fecha_vencimiento_cuota_pagada_reporte, 4, 2).'-'.substr($fecha_vencimiento_cuota_pagada_reporte, 0, 4).' '.substr($fecha_vencimiento_cuota_pagada_reporte, 8, 2).':'.substr($fecha_vencimiento_cuota_pagada_reporte, 10, 2).':'.substr($fecha_vencimiento_cuota_pagada_reporte, 12, 2),1,0,'C');
				$pdf->Cell(40,10,substr($fecha_pago_cuota_reporte, 6, 2).'-'.substr($fecha_pago_cuota_reporte, 4, 2).'-'.substr($fecha_pago_cuota_reporte, 0, 4).' '.substr($fecha_pago_cuota_reporte, 8, 2).':'.substr($fecha_pago_cuota_reporte, 10, 2).':'.substr($fecha_pago_cuota_reporte, 12, 2),1,0,'C');
				$pdf->Cell(30,10,'$'.number_format(($monto_pago_cuota_reporte/100.00), 2, ',', '.'),1,0,'C');
				$pdf->Cell(40,10,iconv('UTF-8', 'windows-1252', $usuario_registra_pago_cuota_reporte),1,0,'C');
			}
		}
		
		if($id_reporte_db == 8)
		{
			$pdf->SetFillColor(17,58,154);
			$pdf->SetTextColor(255,255,255);
						
			$pdf->Cell(30,10,'CRÉDITO',1,0,'C',True);
			$pdf->Cell(30,10,'CUOTAS',1,0, 'C',True);
			$pdf->Cell(30,10,'NRO. CUOTA',1, 0,'C',True);			
			$pdf->Cell(40,10,'ESTADO',1,0,'C',True);
			$pdf->Cell(40,10,'FECHA VENC.',1,0, 'C',True);
			$pdf->Cell(30,10,'MONTO CUOTA',1, 0,'C',True);
			$pdf->Cell(40,10,'USUARIO',1, 0,'C',True);
			$pdf->SetTextColor(0,0,0);
			
			while($stmt->fetch()) 
			{
				$pdf->Ln();
				$pdf->Cell(30,10,$id_credito_reporte,1,0,'C');
				$pdf->Cell(30,10,$cantidad_cuotas_credito_reporte,1,0,'C');
				$pdf->Cell(30,10,$numero_cuota_pendiente_reporte,1,0,'C');
				$pdf->Cell(40,10,iconv('UTF-8', 'windows-1252', $estado_cuota_pendiente_reporte),1,0,'C');
				$pdf->Cell(40,10,substr($fecha_vencimiento_cuota_pendiente_reporte, 6, 2).'-'.substr($fecha_vencimiento_cuota_pendiente_reporte, 4, 2).'-'.substr($fecha_vencimiento_cuota_pendiente_reporte, 0, 4).' '.substr($fecha_vencimiento_cuota_pendiente_reporte, 8, 2).':'.substr($fecha_vencimiento_cuota_pendiente_reporte, 10, 2).':'.substr($fecha_vencimiento_cuota_pendiente_reporte, 12, 2),1,0,'C');
				$pdf->Cell(30,10,'$'.number_format(($monto_cuota_pendiente_reporte/100.00), 2, ',', '.'),1,0,'C');
				$pdf->Cell(40,10,iconv('UTF-8', 'windows-1252', $usuario_registra_credito_reporte),1,0,'C');
			}
		}
		
		if($id_reporte_db == 10)
		{
			$pdf->SetFillColor(17,58,154);
			$pdf->SetTextColor(255,255,255);
			
			if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']))
			{				
				$pdf->Cell(50,10,'CÓDIGO',1,0,'C',True);
				$pdf->Cell(120,10,'NOMBRE',1,0, 'C',True);
				$pdf->Cell(70,10,'CANTIDAD SMS',1, 0,'C',True);
				$pdf->SetTextColor(0,0,0);
				
				while($stmt->fetch()) 
				{
					$selectReportID10ADIC = "SELECT s.codigo, s.nombre, SUM(es.cantidad_reintentos), COUNT(es.id) FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.aviso_x_mora axm, finan_cli.envio_sms es WHERE es.cantidad_reintentos <> 0 AND axm.id = es.id_aviso_x_mora AND axm.id_credito = c.id AND c.id = cc.id_credito AND cc.id_sucursal = s.id AND s.id_cadena = ? AND es.fecha BETWEEN ? AND ? AND s.codigo = ? GROUP BY s.codigo, s.nombre HAVING COUNT(es.id) IS NOT NULL AND SUM(es.cantidad_reintentos) IS NOT NULL";
					if($stmt470 = $mysqli->prepare($selectReportID10ADIC))
					{
						$stmt470->bind_param('issi', $id_cadena_user, $fechaDesde, $fechaHasta, $codigo_sucursal_reporte);
						$stmt470->execute();    
						$stmt470->store_result();
						
						$totR470 = $stmt470->num_rows;

						if($totR470 > 0)
						{
							$stmt470->bind_result($codigo_sucursal_control2_reporte, $nombre_sucursal_control2_reporte, $sumatoria_reintentos_sms_reporte, $cantidad_sms_control2_reporte);
							$stmt470->fetch();
							
							$stmt470->free_result();
							$stmt470->close();
						}
						else
						{
							$sumatoria_reintentos_sms_reporte = 0; 
							$cantidad_sms_control2_reporte = 0;
						}
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']).'ACA 2';
						return;				
					}
					
					$selectReportID10o2 = "SELECT s.codigo, s.nombre, COUNT(tvc.id) FROM finan_cli.sucursal s, finan_cli.token_validacion_celular tvc, finan_cli.usuario u WHERE tvc.usuario = u.id AND u.id_sucursal = s.id AND s.id_cadena = ? AND tvc.fecha BETWEEN ? AND ? AND s.codigo = ? GROUP BY s.codigo, s.nombre HAVING COUNT(tvc.id) IS NOT NULL";
					if ($stmto2 = $mysqli->prepare($selectReportID10o2)) 
					{
						$stmto2->bind_param('issi', $id_cadena_user, $fechaDesde, $fechaHasta, $codigo_sucursal_reporte);
						$stmto2->execute();    
						$stmto2->store_result();

						$stmto2->bind_result($codigo_sucursalo2_reporte, $nombre_sucursalo2_reporte, $cantidad_registros_smso2_reporte);				
							
						$totRo2 = $stmto2->num_rows;
						
						if($totRo2 > 0)
						{
							$stmto2->fetch();
							
							$cantidad_registros_sms_reporte = $cantidad_registros_sms_reporte + $cantidad_registros_smso2_reporte;
							$stmto2->free_result();
							$stmto2->close();
						}
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']).$mysqli->error;
						return;	
					}					
					
					$pdf->Ln();
					$pdf->Cell(50,10,$codigo_sucursal_reporte,1,0,'C');
					$pdf->Cell(120,10,iconv('UTF-8', 'windows-1252', $nombre_sucursal_reporte),1,0,'C');
					$cantidadSMSFinal = $cantidad_registros_sms_reporte + $sumatoria_reintentos_sms_reporte;				
					$pdf->Cell(70,10,$cantidadSMSFinal,1,0,'C');
				}
			}
			else
			{				
				$pdf->Cell(110,10,'CADENA',1,0,'C',True);
				$pdf->Cell(130,10,'CANTIDAD SMS',1, 0,'C',True);
				$pdf->SetTextColor(0,0,0);
				
				while($stmt->fetch()) 
				{
					$selectReportID10ADIC = "SELECT cad.nombre_fantasia, SUM(es.cantidad_reintentos), COUNT(es.id) FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.sucursal s, finan_cli.aviso_x_mora axm, finan_cli.envio_sms es, finan_cli.cadena cad WHERE es.cantidad_reintentos <> 0 AND axm.id = es.id_aviso_x_mora AND axm.id_credito = c.id AND c.id = cc.id_credito AND cc.id_sucursal = s.id AND cad.id = s.id_cadena AND s.id_cadena = ? AND es.fecha BETWEEN ? AND ? AND cad.nombre_fantasia = ? GROUP BY cad.nombre_fantasia HAVING COUNT(es.id) IS NOT NULL AND SUM(es.cantidad_reintentos) IS NOT NULL";
					if($stmt470 = $mysqli->prepare($selectReportID10ADIC))
					{
						$stmt470->bind_param('isss', $id_cadena_user, $fechaDesde, $fechaHasta, $nombre_cadena_reporte);
						$stmt470->execute();    
						$stmt470->store_result();
						
						$totR470 = $stmt470->num_rows;
						if($totR470 > 0)
						{
							$stmt470->bind_result($nombre_cadena_control2_reporte, $sumatoria_reintentos_sms_reporte, $cantidad_sms_control2_reporte);
							$stmt470->fetch();
							
							$stmt470->free_result();
							$stmt470->close();
						}
						else
						{
							$sumatoria_reintentos_sms_reporte = 0; 
							$cantidad_sms_control2_reporte = 0;
						}
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']).'ACA 1';
						return;				
					}					
					
					$selectReportID10o2 = "SELECT cad.nombre_fantasia, COUNT(tvc.id) FROM finan_cli.sucursal s, finan_cli.token_validacion_celular tvc, finan_cli.usuario u, finan_cli.cadena cad WHERE tvc.usuario = u.id AND u.id_sucursal = s.id AND cad.id = s.id_cadena AND cad.nombre_fantasia = ? AND tvc.fecha BETWEEN ? AND ? GROUP BY cad.nombre_fantasia HAVING COUNT(tvc.id) IS NOT NULL";				
					if ($stmto2 = $mysqli->prepare($selectReportID10o2)) 
					{
						$stmto2->bind_param('sss', $nombre_cadena_reporte, $fechaDesde, $fechaHasta);
						$stmto2->execute();    
						$stmto2->store_result();

						$stmto2->bind_result($nombre_cadenao2_reporte, $cantidad_registros_smso2_reporte); 
							
						$totRo2 = $stmto2->num_rows;
						
						if($totRo2 > 0)
						{
							$stmto2->fetch();
							
							$cantidad_registros_sms_reporte = $cantidad_registros_sms_reporte + $cantidad_registros_smso2_reporte;
							$stmto2->free_result();
							$stmto2->close();
						}
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']).$mysqli->error;
						return;	
					}
					
					$pdf->Ln();
					$pdf->Cell(110,10,iconv('UTF-8', 'windows-1252', $nombre_cadena_reporte),1,0,'C');
					$cantidadSMSFinal = $cantidad_registros_sms_reporte + $sumatoria_reintentos_sms_reporte;
					$pdf->Cell(130,10,($cantidadSMSFinal),1,0,'C');
				}			
			
			}
		}
		
		if($id_reporte_db == 11)
		{
			$pdf->SetFillColor(17,58,154);
			$pdf->SetTextColor(255,255,255);
			
			if($sucursal != translate('Lbl_All_Selection',$GLOBALS['lang']))
			{				
				$pdf->Cell(50,10,'CÓDIGO',1,0,'C',True);
				$pdf->Cell(120,10,'NOMBRE',1,0, 'C',True);
				$pdf->Cell(70,10,'CANTIDAD INFORMES',1, 0,'C',True);
				$pdf->SetTextColor(0,0,0);
				
				while($stmt->fetch()) 
				{				
					$pdf->Ln();
					$pdf->Cell(50,10,$codigo_sucursal_reporte,1,0,'C');
					$pdf->Cell(120,10,iconv('UTF-8', 'windows-1252', $nombre_sucursal_reporte),1,0,'C');
					$pdf->Cell(70,10,$cantidad_consultas_ef_reporte,1,0,'C');
				}
			}
			else
			{				
				$pdf->Cell(110,10,'CADENA',1,0,'C',True);
				$pdf->Cell(130,10,'CANTIDAD INFORMES',1, 0,'C',True);
				$pdf->SetTextColor(0,0,0);
				
				while($stmt->fetch()) 
				{
					$pdf->Ln();
					$pdf->Cell(110,10,iconv('UTF-8', 'windows-1252', $nombre_cadena_reporte),1,0,'C');
					$pdf->Cell(130,10,$cantidad_consultas_ef_reporte,1,0,'C');
				}			
			
			}
		}		
		$stmt->free_result();
		$stmt->close();
		
		$pdf->Output();
	
	return;	
?>