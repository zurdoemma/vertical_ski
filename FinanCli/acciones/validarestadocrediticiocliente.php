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
		$tipoDocumentoTitular=htmlspecialchars($_POST["tipoDocumentoTitular"], ENT_QUOTES, 'UTF-8');
		$documentoTitular=htmlspecialchars($_POST["documentoTitular"], ENT_QUOTES, 'UTF-8');
		$tipoDocumento=htmlspecialchars($_POST["tipoDocumento"], ENT_QUOTES, 'UTF-8');
		$documento=htmlspecialchars($_POST["documento"], ENT_QUOTES, 'UTF-8');
		
		$cuitCuil=htmlspecialchars($_POST["cuitCuil"], ENT_QUOTES, 'UTF-8');
		$genero=htmlspecialchars($_POST["genero"], ENT_QUOTES, 'UTF-8');
		
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

		if(!empty($tipoDocumentoTitular) && !empty($documentoTitular))
		{
			if($stmt40 = $mysqli->prepare("SELECT c.cuil_cuit, c.id_genero FROM finan_cli.cliente c WHERE c.tipo_documento = ? AND c.documento = ?"))
			{
				$stmt40->bind_param('is', $tipoDocumento, $documento);
				$stmt40->execute();    
				$stmt40->store_result();
				
				$totR40 = $stmt40->num_rows;

				if($totR40 > 0)
				{
					$stmt40->bind_result($cuit_cuil_titular, $id_genero_titular);
					$stmt40->fetch();
					
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
				
		if($stmt = $mysqli->prepare("SELECT cef.id FROM finan_cli.consulta_estado_financiero cef WHERE cef.tipo_documento = ? AND cef.documento = ? AND cef.token = ? AND cef.cuit_cuil = ?"))
		{
			if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $stmt->bind_param('issi', $tipoDocumentoTitular, $documentoTitular, $tokenVECC, $cuitCuilTitular);
			else $stmt->bind_param('issi', $tipoDocumento, $documento, $tokenVECC, $cuitCuil);
			$stmt->execute();    
			$stmt->store_result();
			
			$totR = $stmt->num_rows;

			if($totR > 0)
			{
				echo translate('Msg_Validation_Credit_Status_Client_Is_Not_Necessary',$GLOBALS['lang']);
				return;
			}			
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}

		$stmt40->free_result();
		$stmt40->close();
		
		if($stmt = $mysqli->prepare("SELECT cef.id, cef.fecha, cef.resultado_xml FROM finan_cli.consulta_estado_financiero cef WHERE cef.tipo_documento = ? AND cef.documento = ? AND cef.cuit_cuil = ?"))
		{
			if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $stmt->bind_param('issi', $tipoDocumentoTitular, $documentoTitular, $tokenVECC, $cuit_cuil_titular);
			else $stmt->bind_param('isi', $tipoDocumento, $documento, $cuitCuil);
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
						$stmt->bind_result($id_estado_financiero_cliente_db, $fecha_estado_financiero_cliente_db, $resultado_xml_estado_financiero_cliente_db);
						$stmt->fetch();
						
						$stmt41->bind_result($cantidad_dias_est_financiero_db);
						$stmt41->fetch();
						
						$fechaObtDB = substr($fecha_estado_financiero_cliente_db, 4, 4).'-'.substr($fecha_estado_financiero_cliente_db, 2, 2).'-'.substr($fecha_estado_financiero_cliente_db, 0, 2).' '.substr($fecha_estado_financiero_cliente_db, 8, 2).':'.substr($fecha_estado_financiero_cliente_db, 10, 2).':'.substr($fecha_estado_financiero_cliente_db, 12, 2);
						$fechaInfDB = new DateTime($fechaObtDB);
						$fechaAct = new DateTime();
						$difDias = $fechaAct->diff($date2);

						if($cantidad_dias_est_financiero_db > $difDias->days)
						{
							$resultado_finan_cli_final = $resultado_xml_estado_financiero_cliente_db;
						}
						else
						{
							if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $resultado_finan_cli_final = consultado_estado_financiero_cliente($tipoDocumentoTitular, $documentoTitular, $cuitCuilTitular, $idGeneroTitular);
							else $resultado_finan_cli_final = consultado_estado_financiero_cliente($tipoDocumento, $documento, $cuitCuil, $genero);
								
							if(strpos($resultado_finan_cli_final, translate('Msg_Financial_Statement_Was_Consulted_Successfully',$GLOBALS['lang'])) !== false)
							{
								$resultado_finan_cli_final = str_replace(translate('Msg_Financial_Statement_Was_Consulted_Successfully',$GLOBALS['lang']), "", $resultado_finan_cli_final);
								//GUARDAR CONSULTA DE ESTADO FINANCIERO
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
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}

		$stmt40->free_result();
		$stmt40->close();
		
		if(!empty($resultado_finan_cli_final))
		{
			$estado_fin_cli = new SimpleXMLElement($resultado_finan_cli_final);
			$estado_fin_cli->Existencia_Fisica_Resu[0]->row[0]->ape_nom;
			// PROCESAR RESULTADO EN PANTALLA Y VALIDAR SI ES NECESARIO SUPERVISOR -- VER SI PREVIAMENTE NO FUE VALIDADO POR SUPERVISOR
		}
		else
		{
			echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
			return;			
		}
			
		return;
?>