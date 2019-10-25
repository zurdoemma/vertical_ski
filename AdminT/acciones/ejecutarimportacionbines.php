<?php 
		//set_time_limit(10);	  
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosta.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
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
		
		if(file_exists("./importar/importarBinesUso.txt"))
		{
			echo translate('Msg_Ongoing_Load_Import_Bins_Wait_Finish',$GLOBALS['lang']);
			return;
		}

		if(!file_exists("./importar/importarBinesNew.txt"))
		{
			echo translate('Msg_You_Must_Upload_File_With_What_Want_Upload',$GLOBALS['lang']);
			return;
		}
				
		rename("./importar/importarBinesNew.txt", "./importar/importarBinesUso.txt");					
		$file = fopen("./importar/importarBinesUso.txt", "r");

		$fechFileLog = date( 'YmdHis' );
		$fileLog = fopen("./importar/log/logImportarBinesIniciado.txt", 'a' );
		
		grabarLog($fileLog, '*******************************************************************************');
		grabarLog($fileLog, " Comienza Importación de Bines");
		grabarLog($fileLog, '*******************************************************************************');
		$numLinea = 1;
		$cantLineasEBM = 0;
		$lineasInsertadas = 0;
		$mysqli->autocommit(FALSE);
		while(!feof($file))
		{
			$linea = trim(fgets($file));
			grabarLog($fileLog, " Linea de Datos a Importar: ".$linea);			
			if(strcmp(trim($linea),'') == 0) 
			{
				$numLinea++;
				$cantLineasEBM++;
				if($cantLineasEBM > 10)
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']).' - '.$cantLineasEBM;
				
					grabarLog($fileLog, ' '.translate('Msg_Unknown_Error',$GLOBALS['lang']).' - '.$cantLineasEBM);
					grabarLog($fileLog, '*******************************************************************************');
					grabarLog($fileLog, ' Fin Importacion de Bines');
					grabarLog($fileLog, '*******************************************************************************');
					fclose($file);
					$fechaimportaciont = date('dmYhis');
					rename("./importar/importarBinesUso.txt", "./importar/importarBinesConError".$fechaimportaciont.".txt");
					
					grabarLog($fileLog, '------------------------------------------------------------------------');
					grabarLog($fileLog, " ARCHIVO PROCESADO: ./importar/importarBinesConError".$fechaimportaciont.".txt");
					grabarLog($fileLog, '------------------------------------------------------------------------');
					
					fclose($fileLog);
					rename("./importar/log/logImportarBinesIniciado.txt", "./importar/log/logImportarBinesFINConError".$fechFileLog.".txt");

					if($lineasInsertadas >= 1) 
					{
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
					}
					else $mysqli->autocommit(TRUE);
					return;
				}
				else continue;
			}

			$lineaM = strtolower($linea);
			if(strpos($lineaM, "insert") === false)
			{
				echo str_replace("%1", $numLinea, translate('Msg_The_Line_Does_Not_Contain_Command_INSERT',$GLOBALS['lang']));
				
				grabarLog($fileLog, iconv("UTF-8", "Windows-1252", ' '.str_replace("%1", $numLinea, translate('Msg_The_Line_Does_Not_Contain_Command_INSERT',$GLOBALS['lang']))));
				grabarLog($fileLog, '*******************************************************************************');
				grabarLog($fileLog, ' Fin Importacion de Bines');
				grabarLog($fileLog, '*******************************************************************************');
				fclose($file);
				$fechaimportaciont = date('dmYhis');
				rename("./importar/importarBinesUso.txt", "./importar/importarBinesConError".$fechaimportaciont.".txt");
				
				grabarLog($fileLog, '------------------------------------------------------------------------');
				grabarLog($fileLog, " ARCHIVO PROCESADO: ./importar/importarBinesConError".$fechaimportaciont.".txt");
				grabarLog($fileLog, '------------------------------------------------------------------------');
				
				fclose($fileLog);
				rename("./importar/log/logImportarBinesIniciado.txt", "./importar/log/logImportarBinesFINConError".$fechFileLog.".txt");				
				if($lineasInsertadas >= 1) 
				{
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
				}
				else $mysqli->autocommit(TRUE);
				return;
			}
			else if(strpos($lineaM, "tef.bines") === false)
			{
				echo str_replace("%1", $numLinea, translate('Msg_The_Line_Does_Not_Contain_Table_Bines',$GLOBALS['lang']));

				grabarLog($fileLog, ' '.iconv("UTF-8", "Windows-1252", str_replace("%1", $numLinea, translate('Msg_The_Line_Does_Not_Contain_Table_Bines',$GLOBALS['lang']))));
				grabarLog($fileLog, '*******************************************************************************');
				grabarLog($fileLog, ' Fin Importacion de Bines');
				grabarLog($fileLog, '*******************************************************************************');
				fclose($file);
				$fechaimportaciont = date('dmYhis');
				rename("./importar/importarBinesUso.txt", "./importar/importarBinesConError".$fechaimportaciont.".txt");
				
				grabarLog($fileLog, '------------------------------------------------------------------------');
				grabarLog($fileLog, " ARCHIVO PROCESADO: ./importar/importarBinesConError".$fechaimportaciont.".txt");
				grabarLog($fileLog, '------------------------------------------------------------------------');
				
				fclose($fileLog);
				rename("./importar/log/logImportarBinesIniciado.txt", "./importar/log/logImportarBinesFINConError".$fechFileLog.".txt");				
				if($lineasInsertadas >= 1) 
				{
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
				}
				else $mysqli->autocommit(TRUE);
				return;				
			}
			else if(strpos($lineaM, "alter table") !== false || strpos($lineaM, "truncate table") !== false || strpos($lineaM, "delete") !== false || strpos($lineaM, "update tef.") !== false)
			{
				echo str_replace("%1", $numLinea, translate('Msg_The_Line_Has_SQL_Commands_Not_Allowed',$GLOBALS['lang'])).strpos($lineaM, "alter");

				grabarLog($fileLog, ' '.iconv("UTF-8", "Windows-1252", str_replace("%1", $numLinea, translate('Msg_The_Line_Has_SQL_Commands_Not_Allowed',$GLOBALS['lang']))));
				grabarLog($fileLog, '*******************************************************************************');
				grabarLog($fileLog, ' Fin Importacion de Bines');
				grabarLog($fileLog, '*******************************************************************************');
				fclose($file);
				$fechaimportaciont = date('dmYhis');
				rename("./importar/importarBinesUso.txt", "./importar/importarBinesConError".$fechaimportaciont.".txt");
				
				grabarLog($fileLog, '------------------------------------------------------------------------');
				grabarLog($fileLog, " ARCHIVO PROCESADO: ./importar/importarBinesConError".$fechaimportaciont.".txt");
				grabarLog($fileLog, '------------------------------------------------------------------------');
				
				fclose($fileLog);
				rename("./importar/log/logImportarBinesIniciado.txt", "./importar/log/logImportarBinesFINConError".$fechFileLog.".txt");				
				if($lineasInsertadas >= 1) 
				{
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
				}
				else $mysqli->autocommit(TRUE);
				return;					
			}			
			else if(strpos($lineaM, "create table") !== false || strpos($lineaM, "drop table") !== false || strpos($lineaM, "comment on") !== false || strpos($lineaM, "rename table") !== false)
			{
				echo str_replace("%1", $numLinea, translate('Msg_The_Line_Has_SQL_Commands_Not_Allowed',$GLOBALS['lang'])).'ACA 2';

				grabarLog($fileLog, ' '.iconv("UTF-8", "Windows-1252", str_replace("%1", $numLinea, translate('Msg_The_Line_Has_SQL_Commands_Not_Allowed',$GLOBALS['lang']))));
				grabarLog($fileLog, '*******************************************************************************');
				grabarLog($fileLog, ' Fin Importacion de Bines');
				grabarLog($fileLog, '*******************************************************************************');
				fclose($file);
				$fechaimportaciont = date('dmYhis');
				rename("./importar/importarBinesUso.txt", "./importar/importarBinesConError".$fechaimportaciont.".txt");
				
				grabarLog($fileLog, '------------------------------------------------------------------------');
				grabarLog($fileLog, " ARCHIVO PROCESADO: ./importar/importarBinesConError".$fechaimportaciont.".txt");
				grabarLog($fileLog, '------------------------------------------------------------------------');
				
				fclose($fileLog);
				rename("./importar/log/logImportarBinesIniciado.txt", "./importar/log/logImportarBinesFINConError".$fechFileLog.".txt");				
				if($lineasInsertadas >= 1) 
				{
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
				}
				else $mysqli->autocommit(TRUE);
				return;				
			}
			else if(strpos($lineaM, "select") !== false || strpos($lineaM, "merge") !== false || strpos($lineaM, "call") !== false || strpos($lineaM, "explain plan") !== false)
			{
				echo str_replace("%1", $numLinea, translate('Msg_The_Line_Has_SQL_Commands_Not_Allowed',$GLOBALS['lang'])).'ACA 3';

				grabarLog($fileLog, ' '.iconv("UTF-8", "Windows-1252", str_replace("%1", $numLinea, translate('Msg_The_Line_Has_SQL_Commands_Not_Allowed',$GLOBALS['lang']))));
				grabarLog($fileLog, '*******************************************************************************');
				grabarLog($fileLog, ' Fin Importacion de Bines');
				grabarLog($fileLog, '*******************************************************************************');
				fclose($file);
				$fechaimportaciont = date('dmYhis');
				rename("./importar/importarBinesUso.txt", "./importar/importarBinesConError".$fechaimportaciont.".txt");
				
				grabarLog($fileLog, '------------------------------------------------------------------------');
				grabarLog($fileLog, " ARCHIVO PROCESADO: ./importar/importarBinesConError".$fechaimportaciont.".txt");
				grabarLog($fileLog, '------------------------------------------------------------------------');
				
				fclose($fileLog);
				rename("./importar/log/logImportarBinesIniciado.txt", "./importar/log/logImportarBinesFINConError".$fechFileLog.".txt");				
				if($lineasInsertadas >= 1) 
				{
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
				}
				else $mysqli->autocommit(TRUE);
				return;					
			}			
			else if(strpos($lineaM, "lock table") !== false || strpos($lineaM, "grant") !== false || strpos($lineaM, "revoke") !== false || strpos($lineaM, "connect") !== false)
			{
				echo str_replace("%1", $numLinea, translate('Msg_The_Line_Has_SQL_Commands_Not_Allowed',$GLOBALS['lang'])).'ACA 4';

				grabarLog($fileLog, ' '.iconv("UTF-8", "Windows-1252", str_replace("%1", $numLinea, translate('Msg_The_Line_Has_SQL_Commands_Not_Allowed',$GLOBALS['lang']))));
				grabarLog($fileLog, '*******************************************************************************');
				grabarLog($fileLog, ' Fin Importacion de Bines');
				grabarLog($fileLog, '*******************************************************************************');
				fclose($file);
				$fechaimportaciont = date('dmYhis');
				rename("./importar/importarBinesUso.txt", "./importar/importarBinesConError".$fechaimportaciont.".txt");
				
				grabarLog($fileLog, '------------------------------------------------------------------------');
				grabarLog($fileLog, " ARCHIVO PROCESADO: ./importar/importarBinesConError".$fechaimportaciont.".txt");
				grabarLog($fileLog, '------------------------------------------------------------------------');
				
				fclose($fileLog);
				rename("./importar/log/logImportarBinesIniciado.txt", "./importar/log/logImportarBinesFINConError".$fechFileLog.".txt");				
				if($lineasInsertadas >= 1) 
				{
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
				}
				else $mysqli->autocommit(TRUE);
				return;					
			}
			else if(strpos($lineaM, "usage") !== false || strpos($lineaM, "commit") !== false || strpos($lineaM, "rollback") !== false || strpos($lineaM, "savepoint") !== false)
			{
				echo str_replace("%1", $numLinea, translate('Msg_The_Line_Has_SQL_Commands_Not_Allowed',$GLOBALS['lang'])).'ACA 5';

				grabarLog($fileLog, ' '.iconv("UTF-8", "Windows-1252", str_replace("%1", $numLinea, translate('Msg_The_Line_Has_SQL_Commands_Not_Allowed',$GLOBALS['lang']))));
				grabarLog($fileLog, '*******************************************************************************');
				grabarLog($fileLog, ' Fin Importacion de Bines');
				grabarLog($fileLog, '*******************************************************************************');
				fclose($file);
				$fechaimportaciont = date('dmYhis');
				rename("./importar/importarBinesUso.txt", "./importar/importarBinesConError".$fechaimportaciont.".txt");
				
				grabarLog($fileLog, '------------------------------------------------------------------------');
				grabarLog($fileLog, " ARCHIVO PROCESADO: ./importar/importarBinesConError".$fechaimportaciont.".txt");
				grabarLog($fileLog, '------------------------------------------------------------------------');
				
				fclose($fileLog);
				rename("./importar/log/logImportarBinesIniciado.txt", "./importar/log/logImportarBinesFINConError".$fechFileLog.".txt");				
				if($lineasInsertadas >= 1) 
				{
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
				}
				else $mysqli->autocommit(TRUE);
				return;					
			}
			else if(strpos($lineaM, "set transaction") !== false)
			{
				echo str_replace("%1", $numLinea, translate('Msg_The_Line_Has_SQL_Commands_Not_Allowed',$GLOBALS['lang'])).'ACA 6';

				grabarLog($fileLog, ' '.iconv("UTF-8", "Windows-1252", str_replace("%1", $numLinea, translate('Msg_The_Line_Has_SQL_Commands_Not_Allowed',$GLOBALS['lang']))));
				grabarLog($fileLog, '*******************************************************************************');
				grabarLog($fileLog, ' Fin Importacion de Bines');
				grabarLog($fileLog, '*******************************************************************************');
				fclose($file);
				$fechaimportaciont = date('dmYhis');
				rename("./importar/importarBinesUso.txt", "./importar/importarBinesConError".$fechaimportaciont.".txt");
				
				grabarLog($fileLog, '------------------------------------------------------------------------');
				grabarLog($fileLog, " ARCHIVO PROCESADO: ./importar/importarBinesConError".$fechaimportaciont.".txt");
				grabarLog($fileLog, '------------------------------------------------------------------------');
				
				fclose($fileLog);
				rename("./importar/log/logImportarBinesIniciado.txt", "./importar/log/logImportarBinesFINConError".$fechFileLog.".txt");				
				if($lineasInsertadas >= 1) 
				{
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
				}
				else $mysqli->autocommit(TRUE);
				return;					
			}
			
			$posInicialC = strpos($lineaM,"(");
			$cabecerasInsert = substr($lineaM, ($posInicialC+1), (strpos($lineaM,")") - $posInicialC) - 1);
			$arrayCabercerasI = explode(",",$cabecerasInsert);
			
			$posCIRangeT = -1;
			$posCIRangeF = -1;
			
			$posRF = 0;
			foreach($arrayCabercerasI as $cabecIR)
			{
				if(strpos(strtolower($cabecIR),"range_to") !== false) $posCIRangeT = $posRF;
				if(strpos(strtolower($cabecIR),"range_from") !== false) $posCIRangeF = $posRF;
				$posRF++;
			}
			
			$posInicialV = strrpos($lineaM,"(");
			$valoresInsert = substr($lineaM, ($posInicialV+1), (strrpos($lineaM,")") - $posInicialV) - 1);
			$arrayValoresI = explode(",",$valoresInsert);
			
			$rangoDesde = trim(str_replace('\'', '', $arrayValoresI[$posCIRangeF]));
			$rangoHasta = trim(str_replace('\'', '', $arrayValoresI[$posCIRangeT]));
			
			if($stmt = $mysqli->prepare("SELECT b.bin_id FROM tef.bines b WHERE b.range_from = ? OR b.range_to = ?"))
			{
				$stmt->bind_param('ss', $rangoDesde, $rangoHasta);
				$stmt->execute();    
				$stmt->store_result();
			
				$totR = $stmt->num_rows;

				if($totR > 0)
				{
					echo translate('Msg_Range_From_And_To_Exists',$GLOBALS['lang']);

					grabarLog($fileLog, ' '.translate('Msg_Range_From_And_To_Exists',$GLOBALS['lang']));
					grabarLog($fileLog, '*******************************************************************************');
					grabarLog($fileLog, ' Fin Importacion de Bines');
					grabarLog($fileLog, '*******************************************************************************');
					fclose($file);
					$fechaimportaciont = date('dmYhis');
					rename("./importar/importarBinesUso.txt", "./importar/importarBinesConError".$fechaimportaciont.".txt");
					
					grabarLog($fileLog, '------------------------------------------------------------------------');
					grabarLog($fileLog, " ARCHIVO PROCESADO: ./importar/importarBinesConError".$fechaimportaciont.".txt");
					grabarLog($fileLog, '------------------------------------------------------------------------');
					
					fclose($fileLog);
					rename("./importar/log/logImportarBinesIniciado.txt", "./importar/log/logImportarBinesFINConError".$fechFileLog.".txt");				
					if($lineasInsertadas >= 1) 
					{
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
					}
					else $mysqli->autocommit(TRUE);
					return;	
				}
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				
				grabarLog($fileLog, ' '.translate('Msg_Unknown_Error',$GLOBALS['lang']));
				grabarLog($fileLog, '*******************************************************************************');
				grabarLog($fileLog, ' Fin Importacion de Bines');
				grabarLog($fileLog, '*******************************************************************************');
				fclose($file);
				$fechaimportaciont = date('dmYhis');
				rename("./importar/importarBinesUso.txt", "./importar/importarBinesConError".$fechaimportaciont.".txt");
				
				grabarLog($fileLog, '------------------------------------------------------------------------');
				grabarLog($fileLog, " ARCHIVO PROCESADO: ./importar/importarBinesConError".$fechaimportaciont.".txt");
				grabarLog($fileLog, '------------------------------------------------------------------------');
				
				fclose($fileLog);
				rename("./importar/log/logImportarBinesIniciado.txt", "./importar/log/logImportarBinesFINConError".$fechFileLog.".txt");				
				if($lineasInsertadas >= 1) 
				{
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
				}
				else $mysqli->autocommit(TRUE);
				return;					
			}
			
			if($stmt2 = $mysqli->prepare("SELECT b.bin_id FROM tef.bines b WHERE b.payment_method_id IN (SELECT b2.payment_method_id FROM tef.bines b2 WHERE b2.payment_method_id = b.payment_method_id AND CAST(b2.range_from AS INT) <= ? AND CAST(b2.range_to AS INT) >= ?)"))
			{
				$stmt2->bind_param('ii', $rangoDesde, $rangoHasta);
				$stmt2->execute();    
				$stmt2->store_result();
			
				$totR2 = $stmt2->num_rows;

				if($totR2 > 0)
				{
					echo translate('Msg_Range_From_And_To_Overlap_Other_Exists',$GLOBALS['lang']);

					grabarLog($fileLog, ' '.translate('Msg_Range_From_And_To_Overlap_Other_Exists',$GLOBALS['lang']));
					grabarLog($fileLog, '*******************************************************************************');
					grabarLog($fileLog, ' Fin Importacion de Bines');
					grabarLog($fileLog, '*******************************************************************************');
					fclose($file);
					$fechaimportaciont = date('dmYhis');
					rename("./importar/importarBinesUso.txt", "./importar/importarBinesConError".$fechaimportaciont.".txt");
					
					grabarLog($fileLog, '------------------------------------------------------------------------');
					grabarLog($fileLog, " ARCHIVO PROCESADO: ./importar/importarBinesConError".$fechaimportaciont.".txt");
					grabarLog($fileLog, '------------------------------------------------------------------------');
					
					fclose($fileLog);
					rename("./importar/log/logImportarBinesIniciado.txt", "./importar/log/logImportarBinesFINConError".$fechFileLog.".txt");				
					if($lineasInsertadas >= 1) 
					{
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
					}
					else $mysqli->autocommit(TRUE);
					return;
				}
				else
				{					
					$stmt2->free_result();
					$stmt2->close();
									
					if(!$stmt10 = $mysqli->prepare($linea))
					{
						echo $mysqli->error;
						grabarLog($fileLog, ' '.$mysqli->error);
						if($lineasInsertadas >= 1) $mysqli->rollback();
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						
						grabarLog($fileLog, '*******************************************************************************');
						grabarLog($fileLog, ' Fin Importacion de Bines');
						grabarLog($fileLog, '*******************************************************************************');
						fclose($file);
						$fechaimportaciont = date('dmYhis');
						rename("./importar/importarBinesUso.txt", "./importar/importarBinesConError".$fechaimportaciont.".txt");
						
						grabarLog($fileLog, '------------------------------------------------------------------------');
						grabarLog($fileLog, " ARCHIVO PROCESADO: ./importar/importarBinesConError".$fechaimportaciont.".txt");
						grabarLog($fileLog, '------------------------------------------------------------------------');
						
						fclose($fileLog);
						rename("./importar/log/logImportarBinesIniciado.txt", "./importar/log/logImportarBinesFINConError".$fechFileLog.".txt");				
						return;						
					}
					else 
					{						
						if(!$stmt10->execute())
						{
							echo $mysqli->error;
							grabarLog($fileLog, ' '.$mysqli->error);
							if($lineasInsertadas >= 1) $mysqli->rollback();
							$mysqli->autocommit(TRUE);
							$stmt->free_result();
							$stmt->close();
							
							grabarLog($fileLog, '*******************************************************************************');
							grabarLog($fileLog, ' Fin Importacion de Bines');
							grabarLog($fileLog, '*******************************************************************************');
							fclose($file);
							$fechaimportaciont = date('dmYhis');
							rename("./importar/importarBinesUso.txt", "./importar/importarBinesConError".$fechaimportaciont.".txt");
							
							grabarLog($fileLog, '------------------------------------------------------------------------');
							grabarLog($fileLog, " ARCHIVO PROCESADO: ./importar/importarBinesConError".$fechaimportaciont.".txt");
							grabarLog($fileLog, '------------------------------------------------------------------------');
							
							fclose($fileLog);
							rename("./importar/log/logImportarBinesIniciado.txt", "./importar/log/logImportarBinesFINConError".$fechFileLog.".txt");				
							return;								
						}
						$lineasInsertadas++;
					}
				}
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);

				grabarLog($fileLog, ' '.translate('Msg_Unknown_Error',$GLOBALS['lang']));
				grabarLog($fileLog, '*******************************************************************************');
				grabarLog($fileLog, ' Fin Importacion de Bines');
				grabarLog($fileLog, '*******************************************************************************');
				fclose($file);
				$fechaimportaciont = date('dmYhis');
				rename("./importar/importarBinesUso.txt", "./importar/importarBinesConError".$fechaimportaciont.".txt");
				
				grabarLog($fileLog, '------------------------------------------------------------------------');
				grabarLog($fileLog, " ARCHIVO PROCESADO: ./importar/importarBinesConError".$fechaimportaciont.".txt");
				grabarLog($fileLog, '------------------------------------------------------------------------');
				
				fclose($fileLog);
				rename("./importar/log/logImportarBinesIniciado.txt", "./importar/log/logImportarBinesFINConError".$fechFileLog.".txt");				
				if($lineasInsertadas >= 1) 
				{
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
				}
				else $mysqli->autocommit(TRUE);
				return;				
			}			
			$numLinea++;
		}
		
		$mysqli->commit();
		$mysqli->autocommit(TRUE);
		
		grabarLog($fileLog, '*******************************************************************************');
		grabarLog($fileLog, ' Fin Importacion de Bines');
		grabarLog($fileLog, '*******************************************************************************');
		
		fclose($file);
		$fechaimportaciont = date('dmYhis');
		rename("./importar/importarBinesUso.txt", "./importar/importarBinesProcesado".$fechaimportaciont.".txt");
		
		grabarLog($fileLog, '------------------------------------------------------------------------');
		grabarLog($fileLog, " ARCHIVO PROCESADO: ./importar/importarBinesProcesado".$fechaimportaciont.".txt");
		grabarLog($fileLog, '------------------------------------------------------------------------');
		
		fclose($fileLog);
		rename("./importar/log/logImportarBinesIniciado.txt", "./importar/log/logImportarBinesFIN".$fechFileLog.".txt");
		
		if($stmt = $mysqli->prepare("SELECT b.bin_id, pm.payment_method_id, pm.payment_method_description, b.range_from, b.range_to, b.bin_length FROM tef.bines b, tef.paymentmethods pm  WHERE b.payment_method_id = pm.payment_method_id ORDER BY CAST(b.range_from AS INT) LIMIT 5000"))
		{
			$stmt->execute();    
			$stmt->store_result();
			
			$stmt->bind_result($id_bin, $id_payment_method, $name_payment_method, $range_from_bin, $range_to_bin, $bin_length);
								
			$array[0] = array();
			$posicion = 0;
			while($stmt->fetch())
			{
				$array[$posicion]['idtarjeta'] = $id_payment_method;
				$array[$posicion]['tarjeta'] = $name_payment_method;
				$array[$posicion]['rangodesde'] = $range_from_bin;
				$array[$posicion]['rangohasta'] = $range_to_bin;
				$array[$posicion]['largobin'] = $bin_length;
				
				$array[$posicion]['acciones'] = '<button type="button" id="borrarBin'.$id_bin.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Bin',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Remove_Bin',$GLOBALS['lang']).'\',\''.$id_bin.'\',\''.$range_from_bin.'\',\''.$name_payment_method.'\')"><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" id="btnModificarBin'.$id_bin.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Modify_Bin',$GLOBALS['lang']).'" onclick="modificarBin(\''.$id_bin.'\')"><i class="far fa-edit"></i></button>';
				
				$posicion++;
			}
			
			echo translate('Msg_Import_Bins_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;	
		}
		
		return;		
?>