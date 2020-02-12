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
		$idCliente=htmlspecialchars($_POST["idCliente"], ENT_QUOTES, 'UTF-8');
		$genero=htmlspecialchars($_POST["genero"], ENT_QUOTES, 'UTF-8');
		$motivo=htmlspecialchars($_POST["motivo"], ENT_QUOTES, 'UTF-8');
		
		if ($stmt500 = $mysqli->prepare("SELECT c.id FROM ".$db_name.".cadena c, ".$db_name.".usuario u, ".$db_name.".sucursal s WHERE u.id_sucursal = s.id AND s.id_cadena = c.id AND u.id = ?")) 
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
				
		if($stmt4 = $mysqli->prepare("SELECT c.id, c.id_titular, c.tipo_documento, c.documento, c.cuil_cuit FROM ".$db_name.".cliente c WHERE c.id = ?"))
		{
			$stmt4->bind_param('i', $idCliente);
			$stmt4->execute();    
			$stmt4->store_result();
			
			$totR4 = $stmt4->num_rows;

			if($totR4 == 0)
			{
				echo translate('Msg_Client_Not_Exist',$GLOBALS['lang']);
				return;
			}			
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		
		$stmt4->bind_result($id_cliente_db, $tipo_cuenta_cliente_db, $tipo_documento_cliente_db, $documento_cliente_db, $cuil_cuit_cliente_db);
		$stmt4->fetch();
		
		$tipoDocumento=$tipo_documento_cliente_db;
		$documento=$documento_cliente_db;
		$cuitCuil=$cuil_cuit_cliente_db;
				
		$stmt4->free_result();
		$stmt4->close();
		
		if($stmt = $mysqli->prepare("SELECT cef.id FROM ".$db_name.".consulta_estado_financiero cef WHERE cef.tipo_documento = ? AND cef.documento = ? AND cef.token = ? AND cef.cuit_cuil = ? AND cef.validado = 1"))
		{
			$stmt->bind_param('issi', $tipoDocumento, $documento, $tokenVECC, $cuitCuil);
			$stmt->execute();    
			$stmt->store_result();
			
			$totR = $stmt->num_rows;

			if($totR > 0)
			{
				if($stmt85 = $mysqli->prepare("SELECT ec.id FROM ".$db_name.".estado_cliente ec WHERE ec.tipo_documento = ? AND ec.documento = ? AND ec.id_motivo IN (51,52) AND ec.fecha LIKE (?)"))
				{
					$date_registro_ec_db = date("Ymd").'%';
					$stmt85->bind_param('iss', $tipoDocumento, $documento, $date_registro_ec_db);
					$stmt85->execute();    
					$stmt85->store_result();
					
					$totR85 = $stmt85->num_rows;
					
					if($totR85 > 0)
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
			}			
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}

		
		if($stmt = $mysqli->prepare("SELECT cef.id, cef.fecha, cef.resultado_xml, cef.token, cef.validado FROM ".$db_name.".consulta_estado_financiero cef WHERE cef.tipo_documento = ? AND cef.documento = ? AND cef.cuit_cuil = ? ORDER BY cef.fecha DESC"))
		{
			$stmt->bind_param('isi', $tipoDocumento, $documento, $cuitCuil);
			$stmt->execute();    
			$stmt->store_result();
			
			$totR = $stmt->num_rows;

			if($totR > 0)
			{
				if($stmt41 = $mysqli->prepare("SELECT p.valor FROM ".$db_name.".parametros p WHERE p.nombre = 'cantidad_días_consulta_db_estado_financiero_clientes'"))
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
							$resultado_finan_cli_final = $resultado_xml_estado_financiero_cliente_db;
							$tokenECF = $token_estado_financiero_cliente_db;
						}
						else
						{
							$resultado_finan_cli_final = consulta_estado_financiero_cliente($tipoDocumento, $documento, $cuitCuil, $genero);
								
							if(strpos($resultado_finan_cli_final, translate('Msg_Financial_Statement_Was_Consulted_Successfully',$GLOBALS['lang'])) !== false && strpos($resultado_finan_cli_final, '560') === false)
							{
								$resultado_finan_cli_final = str_replace(translate('Msg_Financial_Statement_Was_Consulted_Successfully',$GLOBALS['lang']), "", $resultado_finan_cli_final);
								
								$mysqli->autocommit(FALSE);
								$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
						
								if(!$stmt10 = $mysqli->prepare("INSERT INTO ".$db_name.".consulta_estado_financiero(tipo_documento,documento,fecha,resultado_xml,usuario,cuit_cuil,token,validado,id_cadena) VALUES (?,?,?,?,?,?,?,?,?)"))
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
										echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
										$mysqli->autocommit(TRUE);
										$stmt->free_result();
										$stmt->close();
										return;						
									}
									
									$mysqli->commit();
									$mysqli->autocommit(TRUE);
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
				$resultado_finan_cli_final = consulta_estado_financiero_cliente($tipoDocumento, $documento, $cuitCuil, $genero);
					
				if(strpos($resultado_finan_cli_final, translate('Msg_Financial_Statement_Was_Consulted_Successfully',$GLOBALS['lang'])) !== false && strpos($resultado_finan_cli_final, '560') === false)
				{
					$resultado_finan_cli_final = str_replace(translate('Msg_Financial_Statement_Was_Consulted_Successfully',$GLOBALS['lang']), "", $resultado_finan_cli_final);
					
					$mysqli->autocommit(FALSE);
					$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
					
					if(!$stmt10 = $mysqli->prepare("INSERT INTO ".$db_name.".consulta_estado_financiero(tipo_documento,documento,fecha,resultado_xml,usuario,cuit_cuil,token,validado,id_cadena) VALUES (?,?,?,?,?,?,?,?,?)"))
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
		
		if(!empty($resultado_finan_cli_final))
		{			
			$estado_activa_supervisor = 0;
			if($stmt42 = $mysqli->prepare("SELECT p.valor FROM ".$db_name.".parametros p WHERE p.nombre = 'validar_estado_financiero_clientes_supervisor'"))
			{
				$stmt42->execute();    
				$stmt42->store_result();
				
				$totR42 = $stmt42->num_rows;

				if($totR42 > 0)
				{
					$stmt42->bind_result($valor_necesita_supervisor_cef_db);
					$stmt42->fetch();
					
					if($validacion_estado_financiero_cliente_db == 0 && $valor_necesita_supervisor_cef_db == 1)
					{
						if($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3)
						{
							$mysqli->autocommit(FALSE);
							$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
							
							if(!$stmt43 = $mysqli->prepare("INSERT INTO ".$db_name.".estado_cliente(fecha,tipo_documento,documento,id_motivo,usuario,token) VALUES (?,?,?,?,?,?)"))
							{
								echo $mysqli->error;
								$mysqli->autocommit(TRUE);
								$stmt->free_result();
								$stmt->close();
								return;
							}
							else
							{
								$date_registro_a_eccef_db = date("YmdHis");
								$tokenVECE = md5(uniqid(rand(), true));
								$tokenVECE = hash('sha512', $tokenVECE);
								$stmt43->bind_param('sisiss', $date_registro_a_eccef_db, $tipoDocumento, $documento, $motivo, $_SESSION['username'], $tokenVECE);
								if(!$stmt43->execute())
								{
									echo $mysqli->error;
									$mysqli->autocommit(TRUE);
									$stmt->free_result();
									$stmt->close();
									return;						
								}
													
								$mysqli->commit();
								$mysqli->autocommit(TRUE);		
							}

							$estado_fin_cli = new SimpleXMLElement($resultado_finan_cli_final);
							echo translate('Msg_Validation_Credit_Status_Client_OK',$GLOBALS['lang']).'=::=::=::'.$tokenECF.'=:=:=:';
							echo '<div class="panel-group">';				
							echo '	<div class="panel panel-default">';
							echo '		<div id="panel-title-header" class="panel-heading">';
							echo '			<h3 class="panel-title">'.translate('Lbl_Result_Financial_Statement_Client',$GLOBALS['lang']).'</h3>';
							echo ' 		</div>';
							echo '		<div class="panel-body">';
							echo '			<form id="formulariocefcm" role="form" onsubmit="buscarTextoEstadoFinancieroM(); return false;">';		
							echo '				<div class="form-group form-inline">';
							echo '					<div class="form-group" id="buscartextoestadocrediticioclientem">';
							echo '						<input class="form-control input-sm" id="buscartextoestadocrediticioclientemi" name="buscartextoestadocrediticioclientemi" type="text" maxlength="150" />';
							echo '						&nbsp;<button type="button" class="btn" id="btnBuscarEstadoFM" name="btnBuscarEstadoFM" title="'.translate('Lbl_Search_Text_Statement_Client',$GLOBALS['lang']).'" onclick="buscarTextoEstadoFinancieroM();"><i class="fas fa-search"></i></button>';									
							echo '					</div>';
							echo '				</div>';
							echo '				<div class="form-group form-inline">';					
							echo '					<div class="form-group" id="resultadoestadofinancieroclientem">';
							echo '						<textarea rows="3" cols="67" class="form-control input-sm" id="resultadoestadofinancieroclientemi" name="resultadoestadofinancieroclientemi">';
							echo '<EXISTENCIA_FISICA_ENTIDAD>&#013;&#010;';
							$contadorRecC1 = 0;
							foreach ($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row as $recEFC) 
							{
								$contadorRecC1++;
								if(!empty($recEFC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recEFC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
								if(!empty($recEFC->sexo)) echo '	<SEXO> '.$recEFC->sexo.' </SEXO>&#013;&#010;';
								if(!empty($recEFC->tipo_docu)) echo '	<TIPO_DOCUMENTO> '.$recEFC->tipo_docu.' </TIPO_DOCUMENTO>&#013;&#010;';
								if(!empty($recEFC->nume_doc)) echo '	<DOCUMENTO> '.$recEFC->nume_doc.' </DOCUMENTO>&#013;&#010;';
								if(!empty($recEFC->clase)) echo '	<CLASE> '.$recEFC->clase.' </CLASE>&#013;&#010;';
								if(!empty($recEFC->edad)) echo '	<EDAD> '.$recEFC->edad.' </EDAD>&#013;&#010;';
								if(!empty($recEFC->ocupacion)) echo '	<OCUPACION> '.$recEFC->ocupacion.' </OCUPACION>&#013;&#010;';
								if(!empty($recEFC->cdi_codigo_de_identificacion)) echo '	<CUIT_CUIL> '.$recEFC->cdi_codigo_de_identificacion.' </CUIT_CUIL>&#013;&#010;';								
								if(!empty($recEFC->fecha_nacimiento)) echo '	<FECHA_NACIMIENTO> '.$recEFC->fecha_nacimiento.' </FECHA_NACIMIENTO>&#013;&#010;';							
								if(!empty($recEFC->direc_calle)) echo '	<DIRECCION_CALLE> '.$recEFC->direc_calle.' </DIRECCION_CALLE>&#013;&#010;';							
								if(!empty($recEFC->localidad)) echo '	<LOCALIDAD> '.$recEFC->localidad.' </LOCALIDAD>&#013;&#010;';
								if(!empty($recEFC->departamento)) echo '	<DEPARTAMENTO> '.$recEFC->departamento.' </DEPARTAMENTO>&#013;&#010;';								
								if(!empty($recEFC->codigo_postal)) echo '	<CODIGO_POSTAL> '.$recEFC->codigo_postal.' </CODIGO_POSTAL>&#013;&#010;';							
								if(!empty($recEFC->provincia)) echo '	<PROVINCIA> '.$recEFC->provincia.' </PROVINCIA>&#013;&#010;';							
								if(!empty($recEFC->apellido_materno)) echo '	<APELLIDO_MATERNO> '.$recEFC->apellido_materno.' </APELLIDO_MATERNO>&#013;&#010;';
								if(!empty($recEFC->estado_civil)) echo '	<ESTADO_CIVIL> '.$recEFC->estado_civil.' </ESTADO_CIVIL>&#013;&#010;';								
								if(!empty($recEFC->fallecido)) echo '	<FALLECIDO> '.$recEFC->fallecido.' </FALLECIDO>&#013;&#010;';
								
								if(count($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row) > 1 && $contadorRecC1 < count($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row)) echo '&#013;&#010;';
							}							
							echo '</EXISTENCIA_FISICA_ENTIDAD>&#013;&#010;&#013;&#010;';
							
							if(!empty($estado_fin_cli->Score_[0]))
							{
								echo '<SCORE>&#013;&#010;';
								$contadorRecC2 = 0;
								foreach ($estado_fin_cli->Score_[0]->row as $recPIC) 
								{							
									$contadorRecC2++;
									if(!empty($recPIC->score)) echo '	<SCORE> '.$recPIC->score.' </SCORE>&#013;&#010;';
									
									if(count($estado_fin_cli->Score_[0]->row) > 1 && $contadorRecC2 < count($estado_fin_cli->Score_[0]->row)) echo '&#013;&#010;';
								}
								echo '</SCORE>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->Domi_xy_[0]))
							{
								echo '<DOMICILIO_XY>&#013;&#010;';
								$contadorRecC3 = 0;
								foreach ($estado_fin_cli->Domi_xy_[0]->row as $recTJQEC) 
								{								
									$contadorRecC3++;
									if(!empty($recTJQEC->nprovincia)) echo '	<N_PROVINCIA> '.$recTJQEC->nprovincia.' </N_PROVINCIA>&#013;&#010;';
									if(!empty($recTJQEC->npartido)) echo '	<N_PARTIDO> '.$recTJQEC->npartido.' </N_PARTIDO>&#013;&#010;';
									if(!empty($recTJQEC->nlocalidad)) echo '	<N_LOCALIDAD> '.$recTJQEC->nlocalidad.' </N_LOCALIDAD>&#013;&#010;';
									if(!empty($recTJQEC->nbarrio)) echo '	<N_BARRIO> '.$recTJQEC->nbarrio.' </N_BARRIO>&#013;&#010;';
									if(!empty($recTJQEC->calle)) echo '	<CALLE> '.$recTJQEC->calle.' </CALLE>&#013;&#010;';
									if(!empty($recTJQEC->ncp)) echo '	<NCP> '.$recTJQEC->ncp.' </NCP>&#013;&#010;';
									if(!empty($recTJQEC->geocordenadas)) echo '	<GEOCORDENADAS> '.$recTJQEC->geocordenadas.' </GEOCORDENADAS>&#013;&#010;';
									if(count($estado_fin_cli->Domi_xy_[0]->row) > 1 && $contadorRecC3 < count($estado_fin_cli->Domi_xy_[0]->row)) echo '&#013;&#010;';
								}
								echo '</DOMICILIO_XY>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->Situacion_BCRA[0]))
							{							
								echo '<SITUACION_BCRA>&#013;&#010;';
								$contadorRecC4 = 0;
								foreach ($estado_fin_cli->Situacion_BCRA[0]->row as $recDSF6M) 
								{								
									$contadorRecC4++;
									if(!empty($recDSF6M->entidad)) echo '	<ENTIDAD> '.$recDSF6M->entidad.' </ENTIDAD>&#013;&#010;';
									if(!empty($recDSF6M->fecha_sit)) echo '	<FECHA> '.$recDSF6M->fecha_sit.' </FECHA>&#013;&#010;';									
									if(!empty($recDSF6M->situacion)) echo '	<SITUACION> '.$recDSF6M->situacion.' </SITUACION>&#013;&#010;';
									if(!empty($recDSF6M->deuda_total))  echo '	<DEUDA_TOTAL> '.$recDSF6M->deuda_total.' </DEUDA_TOTAL>&#013;&#010;';

									if(count($estado_fin_cli->Situacion_BCRA[0]->row) > 1 && $contadorRecC4 < count($estado_fin_cli->Situacion_BCRA[0]->row)) echo '&#013;&#010;';								
								}
								echo '</SITUACION_BCRA>&#013;&#010;&#013;&#010;';	
							}
							
							if(!empty($estado_fin_cli->FraudesTarjeta_[0]))
							{
								echo '<FRAUDES_TARJETA>&#013;&#010;';
								$contadorRecC5 = 0;
								foreach ($estado_fin_cli->FraudesTarjeta_[0]->row as $recFTs) 
								{							
									$contadorRecC5++;
									if(!empty($recFTs->fraude)) echo '	<FRAUDE> '.$recFTs->fraude.' </FRAUDE>&#013;&#010;';
									
									if(count($estado_fin_cli->FraudesTarjeta_[0]->row) > 1 && $contadorRecC5 < count($estado_fin_cli->FraudesTarjeta_[0]->row)) echo '&#013;&#010;';
								}
								echo '</FRAUDES_TARJETA>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->CapacidadEndeudamiento_[0]))
							{
								echo '<CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;';
								$contadorRecC6 = 0;
								foreach ($estado_fin_cli->CapacidadEndeudamiento_[0]->row as $recCPEC) 
								{							
									$contadorRecC6++;
									if(!empty($recCPEC->capacidad_endeudamiento)) echo '	<CAPACIDAD_ENDEUDAMIENTO> '.$recCPEC->capacidad_endeudamiento.' </CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;';
									
									if(count($estado_fin_cli->CapacidadEndeudamiento_[0]->row) > 1 && $contadorRecC6 < count($estado_fin_cli->CapacidadEndeudamiento_[0]->row)) echo '&#013;&#010;';
								}
								echo '</CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;&#013;&#010;';
							}							
							
							if(!empty($estado_fin_cli->Ooss_personas_[0]))
							{							
								echo '<OBRA_SOCIAL_PERSONAS>&#013;&#010;';
								$contadorRecC7 = 0;
								foreach ($estado_fin_cli->Ooss_personas_[0]->row as $recDSF12M) 
								{								
									$contadorRecC7++;
									if(!empty($recDSF12M->cuil)) echo '	<CUIL> '.$recDSF12M->cuil.' </CUIL>&#013;&#010;';
									if(!empty($recDSF12M->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recDSF12M->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
									if(!empty($recDSF12M->nume_docu)) echo '	<DOCUMENTO> '.$recDSF12M->nume_docu.' </DOCUMENTO>&#013;&#010;';
									if(!empty($recDSF12M->sexo)) echo '	<SEXO> '.$recDSF12M->sexo.' </SEXO>&#013;&#010;';
									if(!empty($recDSF12M->domicilio)) echo '	<DOMICILIO> '.$recDSF12M->domicilio.' </DOMICILIO>&#013;&#010;';
									if(!empty($recDSF12M->localidad)) echo '	<LOCALIDAD> '.$recDSF12M->localidad.' </LOCALIDAD>&#013;&#010;';
									if(!empty($recDSF12M->provincia)) echo '	<PROVINCIA> '.$recDSF12M->provincia.' </PROVINCIA>&#013;&#010;';
									if(!empty($recDSF12M->cod_postal)) echo '	<CODIGO_POSTAL> '.$recDSF12M->cod_postal.' </CODIGO_POSTAL>&#013;&#010;';									

									if(count($estado_fin_cli->Ooss_personas_[0]->row) > 1 && $contadorRecC7 < count($estado_fin_cli->Ooss_personas_[0]->row)) echo '&#013;&#010;';								
								}
								echo '</OBRA_SOCIAL_PERSONAS>&#013;&#010;&#013;&#010;';	
							}
							
							if(!empty($estado_fin_cli->Obra_Social_Rel_[0]))
							{							
								echo '<OBRA_SOCIAL_RELACION>&#013;&#010;';
								$contadorRecC8 = 0;
								foreach ($estado_fin_cli->Obra_Social_Rel_[0]->row as $recDSF24M) 
								{								
									$contadorRecC8++;
									if(!empty($recDSF24M->codiobra)) echo '	<CODIGO> '.$recDSF24M->codiobra.' </CODIGO>&#013;&#010;';
									if(!empty($recDSF24M->descripcion_ooss)) echo '	<DESCRIPCION> '.$recDSF24M->descripcion_ooss.' </DESCRIPCION>&#013;&#010;';
									if(!empty($recDSF24M->sigla_ooss)) echo '	<SIGLA> '.$recDSF24M->sigla_ooss.' </SIGLA>&#013;&#010;';
									if(!empty($recDSF24M->cobertura)) echo '	<COBERTURA> '.$recDSF24M->cobertura.' </COBERTURA>&#013;&#010;';
									if(!empty($recDSF24M->conyugue)) echo '	<CONYUGUE> '.$recDSF24M->conyugue.' </CONYUGUE>&#013;&#010;';
									if(!empty($recDSF24M->hijos)) echo '	<HIJOS> '.$recDSF24M->hijos.' </HIJOS>&#013;&#010;';

									if(count($estado_fin_cli->Obra_Social_Rel_[0]->row) > 1 && $contadorRecC8 < count($estado_fin_cli->Obra_Social_Rel_[0]->row)) echo '&#013;&#010;';								
								}
								echo '</OBRA_SOCIAL_RELACION>&#013;&#010;&#013;&#010;';	
							}
							
							if(!empty($estado_fin_cli->Domicilio_Empresa_trabaja_[0]))
							{
								echo '<DOMICILIO_LABORAL>&#013;&#010;';
								$contadorRecC9 = 0;
								foreach ($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row as $recRDC) 
								{								
									$contadorRecC9++;
									if(!empty($recRDC->direccion)) echo '	<DIRECCION> '.$recRDC->ult_periodo.' </DIRECCION>&#013;&#010;';
									if(!empty($recRDC->localidad)) echo '	<LOCALIDAD> '.$recRDC->alta_trabajo_ultimo.' </LOCALIDAD>&#013;&#010;';
									if(!empty($recRDC->provincia)) echo '	<PROVINCIA> '.$recRDC->cuit.' </PROVINCIA>&#013;&#010;';
									if(!empty($recRDC->cp)) echo '	<CODIGO_POSTAL> '.$recRDC->razon_social.' </CODIGO_POSTAL>&#013;&#010;';

									if(count($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row) > 1 && $contadorRecC9 < count($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row)) echo '&#013;&#010;';								
								}
								echo '</DOMICILIO LABORAL>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->domicilio_otros_[0]))
							{							
								echo '<OTROS_DOMICILIOS>&#013;&#010;';
								$contadorRecC10 = 0;
								foreach ($estado_fin_cli->domicilio_otros_[0]->row as $recCIAC) 
								{								
									$contadorRecC10++;
									if(!empty($recCIAC->domicilio)) echo '	<DOMICILIO> '.$recCIAC->domicilio.' </DOMICILIO>&#013;&#010;';
									if(!empty($recCIAC->localidad)) echo '	<LOCALIDAD> '.$recCIAC->localidad.' </LOCALIDAD>&#013;&#010;';
									if(!empty($recCIAC->cp)) echo '	<CP> '.$recCIAC->cp.' </CP>&#013;&#010;';
									if(!empty($recCIAC->provincia)) echo '	<PROVINCIA> '.$recCIAC->provincia.' </PROVINCIA>&#013;&#010;';

									if(count($estado_fin_cli->domicilio_otros_[0]->row) > 1 && $contadorRecC10 < count($estado_fin_cli->domicilio_otros_[0]->row)) echo '&#013;&#010;';								
								}
								echo '</OTROS_DOMICILIOS>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->Domicilios_Juicios_[0]))
							{
								echo '<DOMICILIOS_JUICIOS>&#013;&#010;';
								$contadorRecC11 = 0;
								foreach ($estado_fin_cli->Domicilios_Juicios_[0]->row as $recDJD) 
								{							
									$contadorRecC11++;
									if(!empty($recDJD->domicilio)) echo '	<DOMICILIO> '.$recDJD->domicilio.' </DOMICILIO>&#013;&#010;';
									
									if(count($estado_fin_cli->Domicilios_Juicios_[0]->row) > 1 && $contadorRecC11 < count($estado_fin_cli->Domicilios_Juicios_[0]->row)) echo '&#013;&#010;';
								}
								echo '</DOMICILIOS_JUICIOS>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->Padronempresasf_[0]))
							{							
								echo '<PADRON_EMPRESAS>&#013;&#010;';
								$contadorRecC12 = 0;
								foreach ($estado_fin_cli->Padronempresasf_[0]->row as $recPMC) 
								{								
									$contadorRecC12++;
									if(!empty($recPMC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recPMC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
									if(!empty($recPMC->cuit)) echo '	<CUIT> '.$recPMC->cuit.' </CUIT>&#013;&#010;';
									if(!empty($recPMC->domicilio_fiscal)) echo '	<DOMICILIO_FISCAL> '.$recPMC->domicilio_fiscal.' </DOMICILIO_FISCAL>&#013;&#010;';
									if(!empty($recPMC->provincia)) echo '	<PROVINCIA> '.$recPMC->provincia.' </PROVINCIA>&#013;&#010;';
									if(!empty($recPMC->actividad_especifica)) echo '	<ACTIVIDAD_ESPECIFICA> '.$recPMC->actividad_especifica.' </ACTIVIDAD_ESPECIFICA>&#013;&#010;';
									if(!empty($recPMC->categoria_autonomo)) echo '	<CATEGORIA_AUTONOMO> '.$recPMC->categoria_autonomo.' </CATEGORIA_AUTONOMO>&#013;&#010;';

									if(count($estado_fin_cli->Padronempresasf_[0]->row) > 1 && $contadorRecC12 < count($estado_fin_cli->Padronempresasf_[0]->row)) echo '&#013;&#010;';									
								}
								echo '</PADRON_EMPRESAS>&#013;&#010;&#013;&#010;';							
							}
							
							if(!empty($estado_fin_cli->Gran_contrib_[0]))
							{
								echo '<GRANDES_CONTRIBUYENTES>&#013;&#010;';
								$contadorRecC13 = 0;
								foreach ($estado_fin_cli->Gran_contrib_[0]->row as $recGCD) 
								{							
									$contadorRecC13++;
									if(!empty($recGCD->gran_contr)) echo '	<CONTRIBUYENTE> '.$recGCD->gran_contr.' </CONTRIBUYENTE>&#013;&#010;';
									
									if(count($estado_fin_cli->Gran_contrib_[0]->row) > 1 && $contadorRecC13 < count($estado_fin_cli->Gran_contrib_[0]->row)) echo '&#013;&#010;';
								}
								echo '</GRANDES_CONTRIBUYENTES>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->Domialter_[0]))
							{							
								echo '<DOMICILIOS_ALTERNATIVOS>&#013;&#010;';
								$contadorRecC14 = 0;
								foreach ($estado_fin_cli->Domialter_[0]->row as $recDMAC) 
								{								
									$contadorRecC14++;
									if(!empty($recDMAC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recDMAC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
									if(!empty($recDMAC->cuit)) echo '	<CUIT> '.$recDMAC->cuit.' </CUIT>&#013;&#010;';
									if(!empty($recDMAC->domicilio_fiscal)) echo '	<DOMICILIO_FISCAL> '.$recDMAC->domicilio_fiscal.' </DOMICILIO_FISCAL>&#013;&#010;';
									if(!empty($recDMAC->provincia)) echo '	<PROVINCIA> '.$recDMAC->provincia.' </PROVINCIA>&#013;&#010;';
									if(!empty($recDMAC->actividad_especifica)) echo '	<ACTIVIDAD_ESPECIFICA> '.$recDMAC->actividad_especifica.' </ACTIVIDAD_ESPECIFICA>&#013;&#010;';

									if(count($estado_fin_cli->Domialter_[0]->row) > 1 && $contadorRecC14 < count($estado_fin_cli->Domialter_[0]->row)) echo '&#013;&#010;';									
								}
								echo '</DOMICILIOS_ALTERNATIVOS>&#013;&#010;&#013;&#010;';							
							}
							
							if(!empty($estado_fin_cli->Nise_[0]))
							{
								echo '<NISE>&#013;&#010;';
								$contadorRecC15 = 0;
								foreach ($estado_fin_cli->Nise_[0]->row as $recINC) 
								{							
									$contadorRecC15++;
									if(!empty($recINC->radio)) echo '	<RADIO> '.$recINC->radio.' </RADIO>&#013;&#010;';
									if(!empty($recINC->nise)) echo '	<NISE> '.$recINC->nise.' </NISE>&#013;&#010;';
									if(!empty($recINC->periodo)) echo '	<PERIODO> '.$recINC->periodo.' </PERIODO>&#013;&#010;';
									
									if(count($estado_fin_cli->Nise_[0]->row) > 1 && $contadorRecC15 < count($estado_fin_cli->Nise_[0]->row)) echo '&#013;&#010;';
								}
								echo '</NISE>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->Const_inscrip_[0]))
							{							
								echo '<CONSTANCIAS_INSCRIPCION>&#013;&#010;';
								$contadorRecC16 = 0;
								foreach ($estado_fin_cli->Const_inscrip_[0]->row as $recCICR) 
								{								
									$contadorRecC16++;
									if(!empty($recCICR->descrip_imp_ganancias)) echo '	<DESCRIPCION_IMP_GANANCIAS> '.$recCICR->descrip_imp_ganancias.' </DESCRIPCION_IMP_GANANCIAS>&#013;&#010;';
									if(!empty($recCICR->descrip_imp_iva)) echo '	<DESCRIPCION_IMP_IVA> '.$recCICR->descrip_imp_iva.' </DESCRIPCION_IMP_IVA>&#013;&#010;';
									if(!empty($recCICR->descrip_monotributo)) echo '	<DESCRIPCION_MONOTRIBUTO> '.$recCICR->descrip_monotributo.' </DESCRIPCION_MONOTRIBUTO>&#013;&#010;';
									if(!empty($recCICR->descrip_integrante_soc)) echo '	<DESCRIPCION_INTEGRANTE_SOC> '.$recCICR->descrip_integrante_soc.' </DESCRIPCION_INTEGRANTE_SOC>&#013;&#010;';
									if(!empty($recCICR->descrip_empleador)) echo '	<DESCRIPCION_EMPLEADOR> '.$recCICR->descrip_empleador.' </DESCRIPCION_EMPLEADOR>&#013;&#010;';
									if(!empty($recCICR->periodo)) echo '	<PERIODO> '.$recCICR->periodo.' </PERIODO>&#013;&#010;';

									if(count($estado_fin_cli->Const_inscrip_[0]->row) > 1 && $contadorRecC16 < count($estado_fin_cli->Const_inscrip_[0]->row)) echo '&#013;&#010;';									
								}
								echo '</CONSTANCIAS_INSCRIPCION>&#013;&#010;&#013;&#010;';							
							}
							
							if(!empty($estado_fin_cli->Nbi_[0]))
							{
								echo '<NBI>&#013;&#010;';
								$contadorRecC17 = 0;
								foreach ($estado_fin_cli->Nbi_[0]->row as $recINBC) 
								{							
									$contadorRecC17++;
									if(!empty($recINBC->radio)) echo '	<RADIO> '.$recINBC->radio.' </RADIO>&#013;&#010;';
									if(!empty($recINBC->nbi)) echo '	<NBI> '.$recINBC->nbi.' </NBI>&#013;&#010;';
									if(!empty($recINBC->periodo)) echo '	<PERIODO> '.$recINBC->periodo.' </PERIODO>&#013;&#010;';
									
									if(count($estado_fin_cli->Nbi_[0]->row) > 1 && $contadorRecC17 < count($estado_fin_cli->Nbi_[0]->row)) echo '&#013;&#010;';
								}
								echo '</NBI>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->Iped_[0]))
							{
								echo '<IPED>&#013;&#010;';
								$contadorRecC18 = 0;
								foreach ($estado_fin_cli->Iped_[0]->row as $recIIPC) 
								{							
									$contadorRecC18++;
									if(!empty($recIIPC->radio)) echo '	<RADIO> '.$recIIPC->radio.' </RADIO>&#013;&#010;';
									if(!empty($recIIPC->iped)) echo '	<IPED> '.$recIIPC->iped.' </IPED>&#013;&#010;';
									if(!empty($recIIPC->periodo)) echo '	<PERIODO> '.$recIIPC->periodo.' </PERIODO>&#013;&#010;';
									
									if(count($estado_fin_cli->Iped_[0]->row) > 1 && $contadorRecC18 < count($estado_fin_cli->Iped_[0]->row)) echo '&#013;&#010;';
								}
								echo '</IPED>&#013;&#010;&#013;&#010;';
							}							

							if(!empty($estado_fin_cli->Ib_[0]))
							{
								echo '<IB>&#013;&#010;';
								$contadorRecC19 = 0;
								foreach ($estado_fin_cli->Ib_[0]->row as $recIIBC) 
								{							
									$contadorRecC19++;
									if(!empty($recIIBC->radio)) echo '	<RADIO> '.$recIIBC->radio.' </RADIO>&#013;&#010;';
									if(!empty($recIIBC->ib)) echo '	<IB> '.$recIIBC->ib.' </IB>&#013;&#010;';
									if(!empty($recIIBC->periodo)) echo '	<PERIODO> '.$recIIBC->periodo.' </PERIODO>&#013;&#010;';
									
									if(count($estado_fin_cli->Ib_[0]->row) > 1 && $contadorRecC19 < count($estado_fin_cli->Ib_[0]->row)) echo '&#013;&#010;';
								}
								echo '</IB>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->Rel_Dependencia_Trabajador_[0]))
							{
								echo '<RELEACION_DEPENDENCIA_TRABAJADOR>&#013;&#010;';
								$contadorRecC20 = 0;
								foreach ($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row as $recRDTC) 
								{							
									$contadorRecC20++;
									if(!empty($recRDTC->cuil)) echo '	<CUIL> '.$recRDTC->cuil.' </CUIL>&#013;&#010;';
									if(!empty($recRDTC->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recRDTC->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
									if(!empty($recRDTC->domicilio)) echo '	<DOMICILIO> '.$recRDTC->domicilio.' </DOMICILIO>&#013;&#010;';
									if(!empty($recRDTC->localidad)) echo '	<LOCALIDAD> '.$recRDTC->localidad.' </LOCALIDAD>&#013;&#010;';
									if(!empty($recRDTC->cp)) echo '	<CODIGO_POSTAL> '.$recRDTC->cp.' </CODIGO_POSTAL>&#013;&#010;';
									if(!empty($recRDTC->provincia)) echo '	<PROVINCIA> '.$recRDTC->provincia.' </PROVINCIA>&#013;&#010;';
									if(!empty($recRDTC->fecha_nac)) echo '	<FECHA_NACIMIENTO> '.$recRDTC->fecha_nac.' </FECHA_NACIMIENTO>&#013;&#010;';
									if(!empty($recRDTC->sexo)) echo '	<SEXO> '.$recRDTC->sexo.' </SEXO>&#013;&#010;';
									if(!empty($recRDTC->doc)) echo '	<DOCUMENTO> '.$recRDTC->doc.' </DOCUMENTO>&#013;&#010;';
									
									if(count($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row) > 1 && $contadorRecC20 < count($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row)) echo '&#013;&#010;';
								}
								echo '</RELEACION_DEPENDENCIA_TRABAJADOR>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->Rel_Dependencia_Empleador_[0]))
							{
								echo '<RELEACION_DEPENDENCIA_EMPLEADOR>&#013;&#010;';
								$contadorRecC21 = 0;
								foreach ($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row as $recRDEC) 
								{							
									$contadorRecC21++;
									if(!empty($recRDEC->cuit)) echo '	<CUIT> '.$recRDEC->cuit.' </CUIT>&#013;&#010;';
									if(!empty($recRDEC->razon)) echo '	<RAZON_SOCIAL> '.$recRDEC->razon.' </RAZON_SOCIAL>&#013;&#010;';
									if(!empty($recRDEC->domicilio)) echo '	<DOMICILIO> '.$recRDEC->domicilio.' </DOMICILIO>&#013;&#010;';
									if(!empty($recRDEC->localidad)) echo '	<LOCALIDAD> '.$recRDEC->localidad.' </LOCALIDAD>&#013;&#010;';
									if(!empty($recRDEC->cp)) echo '	<CODIGO_POSTAL> '.$recRDEC->cp.' </CODIGO_POSTAL>&#013;&#010;';
									if(!empty($recRDEC->provincia)) echo '	<PROVINCIA> '.$recRDEC->provincia.' </PROVINCIA>&#013;&#010;';
									
									if(count($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row) > 1 && $contadorRecC21 < count($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row)) echo '&#013;&#010;';
								}
								echo '</RELEACION_DEPENDENCIA_EMPLEADOR>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->Historia_[0]))
							{
								echo '<HISTORIA_EMPRESAS>&#013;&#010;';
								$contadorRecC22 = 0;
								foreach ($estado_fin_cli->Historia_[0]->row as $recHEC) 
								{							
									$contadorRecC22++;
									if(!empty($recHEC->cuit)) echo '	<CUIT> '.$recHEC->cuit.' </CUIT>&#013;&#010;';
									if(!empty($recHEC->razon)) echo '	<RAZON_SOCIAL> '.$recHEC->razon.' </RAZON_SOCIAL>&#013;&#010;';
									if(!empty($recHEC->desde)) echo '	<DESDE> '.$recHEC->desde.' </DESDE>&#013;&#010;';
									if(!empty($recHEC->hasta)) echo '	<HASTA> '.$recHEC->hasta.' </HASTA>&#013;&#010;';
									
									if(count($estado_fin_cli->Historia_[0]->row) > 1 && $contadorRecC22 < count($estado_fin_cli->Historia_[0]->row)) echo '&#013;&#010;';
								}
								echo '</HISTORIA_EMPRESAS>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->Sysem_[0]))
							{
								echo '<SYSEM>&#013;&#010;';
								$contadorRecC23 = 0;
								foreach ($estado_fin_cli->Sysem_[0]->row as $recSYC) 
								{							
									$contadorRecC23++;
									if(!empty($recSYC->cant_emp)) echo '	<CANTIDAD_EMPLEADOS> '.$recSYC->cant_emp.' </CANTIDAD_EMPLEADOS>&#013;&#010;';
									if(!empty($recSYC->cuit)) echo '	<CUIT> '.$recSYC->cuit.' </CUIT>&#013;&#010;';
									if(!empty($recSYC->razon)) echo '	<RAZON_SOCIAL> '.$recSYC->razon.' </RAZON_SOCIAL>&#013;&#010;';
									if(!empty($recSYC->domicilio)) echo '	<DOMICILIO> '.$recSYC->domicilio.' </DOMICILIO>&#013;&#010;';
									if(!empty($recSYC->localidad)) echo '	<LOCALIDAD> '.$recSYC->localidad.' </LOCALIDAD>&#013;&#010;';
									if(!empty($recSYC->cp)) echo '	<CODIGO_POSTAL> '.$recSYC->cp.' </CODIGO_POSTAL>&#013;&#010;';
									if(!empty($recSYC->provincia)) echo '	<PROVINCIA> '.$recSYC->provincia.' </PROVINCIA>&#013;&#010;';
									
									if(count($estado_fin_cli->Sysem_[0]->row) > 1 && $contadorRecC23 < count($estado_fin_cli->Sysem_[0]->row)) echo '&#013;&#010;';
								}
								echo '</SYSEM>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->Jyj_mensual_[0]))
							{
								echo '<SYSEM>&#013;&#010;';
								$contadorRecC24 = 0;
								foreach ($estado_fin_cli->Jyj_mensual_[0]->row as $recJYJC) 
								{							
									$contadorRecC24++;
									if(!empty($recJYJC->cuil)) echo '	<CUIL> '.$recJYJC->cuil.' </CUIL>&#013;&#010;';
									if(!empty($recJYJC->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recJYJC->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
									if(!empty($recJYJC->rango_socio_econom)) echo '	<RANGO_SOCIO_ECONOMICO> '.$recJYJC->rango_socio_econom.' </RANGO_SOCIO_ECONOMICO>&#013;&#010;';
									
									if(count($estado_fin_cli->Jyj_mensual_[0]->row) > 1 && $contadorRecC24 < count($estado_fin_cli->Jyj_mensual_[0]->row)) echo '&#013;&#010;';
								}
								echo '</SYSEM>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->Situacionlaboral_[0]))
							{
								echo '<SITUACION_LABORAL>&#013;&#010;';
								$contadorRecC25 = 0;
								foreach ($estado_fin_cli->Situacionlaboral_[0]->row as $recSLC) 
								{							
									$contadorRecC25++;
									if(!empty($recSLC->situacionlaboral)) echo '	<ESTADO_LABORAL> '.$recSLC->situacionlaboral.' </ESTADO_LABORAL>&#013;&#010;';
									
									if(count($estado_fin_cli->Situacionlaboral_[0]->row) > 1 && $contadorRecC25 < count($estado_fin_cli->Situacionlaboral_[0]->row)) echo '&#013;&#010;';
								}
								echo '</SITUACION_LABORAL>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->Producto_financieros_[0]))
							{
								echo '<PRODUCTO_FINANCIEROS>&#013;&#010;';
								$contadorRecC26 = 0;
								foreach ($estado_fin_cli->Producto_financieros_[0]->row as $recPFC) 
								{							
									$contadorRecC26++;
									if(!empty($recPFC->producto_financieros)) echo '	<PRODUCTO> '.$recPFC->producto_financieros.' </PRODUCTO>&#013;&#010;';
									
									if(count($estado_fin_cli->Producto_financieros_[0]->row) > 1 && $contadorRecC26 < count($estado_fin_cli->Producto_financieros_[0]->row)) echo '&#013;&#010;';
								}
								echo '</PRODUCTO_FINANCIEROS>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->Bancarizado_[0]))
							{
								echo '<BANCARIZADO>&#013;&#010;';
								$contadorRecC27 = 0;
								foreach ($estado_fin_cli->Bancarizado_[0]->row as $recEBC) 
								{							
									$contadorRecC27++;
									if(!empty($recEBC->bancarizado)) echo '	<ESTADO> '.$recEBC->bancarizado.' </ESTADO>&#013;&#010;';
									
									if(count($estado_fin_cli->Bancarizado_[0]->row) > 1 && $contadorRecC27 < count($estado_fin_cli->Bancarizado_[0]->row)) echo '&#013;&#010;';
								}
								echo '</BANCARIZADO>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->FechaMaxSistFin_[0]))
							{
								echo '<FECHA_MAXIMA_SISTEMA_FINANCIERO>&#013;&#010;';
								$contadorRecC28 = 0;
								foreach ($estado_fin_cli->FechaMaxSistFin_[0]->row as $recFMSF) 
								{							
									$contadorRecC28++;
									if(!empty($recFMSF->fecha)) echo '	<FECHA> '.$recFMSF->fecha.' </FECHA>&#013;&#010;';
									
									if(count($estado_fin_cli->FechaMaxSistFin_[0]->row) > 1 && $contadorRecC28 < count($estado_fin_cli->FechaMaxSistFin_[0]->row)) echo '&#013;&#010;';
								}
								echo '</FECHA_MAXIMA_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
							}	

							if(!empty($estado_fin_cli->SistFinEvolucionDeuda_[0]))
							{
								echo '<EVOLUCION_DEUDA_SISTEMA_FINANCIERO>&#013;&#010;';
								$contadorRecC29 = 0;
								foreach ($estado_fin_cli->SistFinEvolucionDeuda_[0]->row as $recEDSF) 
								{							
									$contadorRecC29++;
									if(!empty($recEDSF->entidad)) echo '	<ENTIDAD> '.$recEDSF->entidad.' </ENTIDAD>&#013;&#010;';
									if(!empty($recEDSF->m1)) echo '	<MES1> '.$recEDSF->m1.' </MES1>&#013;&#010;';
									if(!empty($recEDSF->m2)) echo '	<MES2> '.$recEDSF->m2.' </MES2>&#013;&#010;';
									if(!empty($recEDSF->m3)) echo '	<MES3> '.$recEDSF->m3.' </MES3>&#013;&#010;';
									if(!empty($recEDSF->m4)) echo '	<MES4> '.$recEDSF->m4.' </MES4>&#013;&#010;';
									if(!empty($recEDSF->m5)) echo '	<MES5> '.$recEDSF->m5.' </MES5>&#013;&#010;';
									if(!empty($recEDSF->m6)) echo '	<MES6> '.$recEDSF->m6.' </MES6>&#013;&#010;';
									if(!empty($recEDSF->m7)) echo '	<MES7> '.$recEDSF->m7.' </MES7>&#013;&#010;';
									if(!empty($recEDSF->m8)) echo '	<MES8> '.$recEDSF->m8.' </MES8>&#013;&#010;';
									if(!empty($recEDSF->m9)) echo '	<MES9> '.$recEDSF->m9.' </MES9>&#013;&#010;';
									if(!empty($recEDSF->m10)) echo '	<MES10> '.$recEDSF->m10.' </MES10>&#013;&#010;';
									if(!empty($recEDSF->m11)) echo '	<MES11> '.$recEDSF->m11.' </MES11>&#013;&#010;';
									if(!empty($recEDSF->m12)) echo '	<MES12> '.$recEDSF->m12.' </MES12>&#013;&#010;';
									if(!empty($recEDSF->m13)) echo '	<MES13> '.$recEDSF->m13.' </MES13>&#013;&#010;';
									if(!empty($recEDSF->m14)) echo '	<MES14> '.$recEDSF->m14.' </MES14>&#013;&#010;';
									if(!empty($recEDSF->m15)) echo '	<MES15> '.$recEDSF->m15.' </MES15>&#013;&#010;';
									if(!empty($recEDSF->m16)) echo '	<MES16> '.$recEDSF->m16.' </MES16>&#013;&#010;';
									if(!empty($recEDSF->m17)) echo '	<MES17> '.$recEDSF->m17.' </MES17>&#013;&#010;';
									if(!empty($recEDSF->m18)) echo '	<MES18> '.$recEDSF->m18.' </MES18>&#013;&#010;';
									if(!empty($recEDSF->m19)) echo '	<MES19> '.$recEDSF->m19.' </MES19>&#013;&#010;';
									if(!empty($recEDSF->m20)) echo '	<MES20> '.$recEDSF->m20.' </MES20>&#013;&#010;';
									if(!empty($recEDSF->m21)) echo '	<MES21> '.$recEDSF->m21.' </MES21>&#013;&#010;';
									if(!empty($recEDSF->m22)) echo '	<MES22> '.$recEDSF->m22.' </MES22>&#013;&#010;';
									if(!empty($recEDSF->m23)) echo '	<MES23> '.$recEDSF->m23.' </MES23>&#013;&#010;';
									if(!empty($recEDSF->m24)) echo '	<MES24> '.$recEDSF->m24.' </MES24>&#013;&#010;';									
									if(!empty($recEDSF->deuda_actual)) echo '	<DEUDA_ACTUAL> '.$recEDSF->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
									if(!empty($recEDSF->monto_maximo)) echo '	<MONTO_MAXIMO> '.$recEDSF->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
									if(!empty($recEDSF->bco)) echo '	<BANCO> '.$recEDSF->bco.' </BANCO>&#013;&#010;';
									
									if(count($estado_fin_cli->SistFinEvolucionDeuda_[0]->row) > 1 && $contadorRecC29 < count($estado_fin_cli->SistFinEvolucionDeuda_[0]->row)) echo '&#013;&#010;';
								}
								echo '</EVOLUCION_DEUDA_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]))
							{
								echo '<EVOLUCION_DEUDA_PROPIO_SISTEMA_FINANCIERO>&#013;&#010;';
								$contadorRecC30 = 0;
								foreach ($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row as $recEDPSF) 
								{							
									$contadorRecC30++;
									if(!empty($recEDPSF->entidad)) echo '	<ENTIDAD> '.$recEDPSF->entidad.' </ENTIDAD>&#013;&#010;';
									if(!empty($recEDPSF->m1)) echo '	<MES1> '.$recEDPSF->m1.' </MES1>&#013;&#010;';
									if(!empty($recEDPSF->m2)) echo '	<MES2> '.$recEDPSF->m2.' </MES2>&#013;&#010;';
									if(!empty($recEDPSF->m3)) echo '	<MES3> '.$recEDPSF->m3.' </MES3>&#013;&#010;';
									if(!empty($recEDPSF->m4)) echo '	<MES4> '.$recEDPSF->m4.' </MES4>&#013;&#010;';
									if(!empty($recEDPSF->m5)) echo '	<MES5> '.$recEDPSF->m5.' </MES5>&#013;&#010;';
									if(!empty($recEDPSF->m6)) echo '	<MES6> '.$recEDPSF->m6.' </MES6>&#013;&#010;';
									if(!empty($recEDPSF->m7)) echo '	<MES7> '.$recEDPSF->m7.' </MES7>&#013;&#010;';
									if(!empty($recEDPSF->m8)) echo '	<MES8> '.$recEDPSF->m8.' </MES8>&#013;&#010;';
									if(!empty($recEDPSF->m9)) echo '	<MES9> '.$recEDPSF->m9.' </MES9>&#013;&#010;';
									if(!empty($recEDPSF->m10)) echo '	<MES10> '.$recEDPSF->m10.' </MES10>&#013;&#010;';
									if(!empty($recEDPSF->m11)) echo '	<MES11> '.$recEDPSF->m11.' </MES11>&#013;&#010;';
									if(!empty($recEDPSF->m12)) echo '	<MES12> '.$recEDPSF->m12.' </MES12>&#013;&#010;';
									if(!empty($recEDPSF->m13)) echo '	<MES13> '.$recEDPSF->m13.' </MES13>&#013;&#010;';
									if(!empty($recEDPSF->m14)) echo '	<MES14> '.$recEDPSF->m14.' </MES14>&#013;&#010;';
									if(!empty($recEDPSF->m15)) echo '	<MES15> '.$recEDPSF->m15.' </MES15>&#013;&#010;';
									if(!empty($recEDPSF->m16)) echo '	<MES16> '.$recEDPSF->m16.' </MES16>&#013;&#010;';
									if(!empty($recEDPSF->m17)) echo '	<MES17> '.$recEDPSF->m17.' </MES17>&#013;&#010;';
									if(!empty($recEDPSF->m18)) echo '	<MES18> '.$recEDPSF->m18.' </MES18>&#013;&#010;';
									if(!empty($recEDPSF->m19)) echo '	<MES19> '.$recEDPSF->m19.' </MES19>&#013;&#010;';
									if(!empty($recEDPSF->m20)) echo '	<MES20> '.$recEDPSF->m20.' </MES20>&#013;&#010;';
									if(!empty($recEDPSF->m21)) echo '	<MES21> '.$recEDPSF->m21.' </MES21>&#013;&#010;';
									if(!empty($recEDPSF->m22)) echo '	<MES22> '.$recEDPSF->m22.' </MES22>&#013;&#010;';
									if(!empty($recEDPSF->m23)) echo '	<MES23> '.$recEDPSF->m23.' </MES23>&#013;&#010;';
									if(!empty($recEDPSF->m24)) echo '	<MES24> '.$recEDPSF->m24.' </MES24>&#013;&#010;';									
									if(!empty($recEDPSF->deuda_actual)) echo '	<DEUDA_ACTUAL> '.$recEDPSF->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
									if(!empty($recEDPSF->monto_maximo)) echo '	<MONTO_MAXIMO> '.$recEDPSF->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
									if(!empty($recEDPSF->bco)) echo '	<BANCO> '.$recEDPSF->bco.' </BANCO>&#013;&#010;';
									
									if(count($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row) > 1 && $contadorRecC30 < count($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row)) echo '&#013;&#010;';
								}
								echo '</EVOLUCION_DEUDA_PROPIO_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
							}							
							
							if(!empty($estado_fin_cli->Bcra_Ultimas_Situaciones[0]))
							{
								echo '<BANCO_CENTRAL_ULTIMAS_SITUACIONES>&#013;&#010;';
								$contadorRecC31 = 0;
								foreach ($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row as $recBCUS) 
								{							
									$contadorRecC31++;
									if(!empty($recBCUS->entidad)) echo '	<ENTIDAD> '.$recBCUS->entidad.' </ENTIDAD>&#013;&#010;';
									if(!empty($recBCUS->periodo)) echo '	<PERIODO> '.$recBCUS->periodo.' </PERIODO>&#013;&#010;';
									if(!empty($recBCUS->situacion)) echo '	<SITUACION> '.$recBCUS->situacion.' </SITUACION>&#013;&#010;';
									if(!empty($recBCUS->monto)) echo '	<MONTO> '.$recBCUS->monto.' </MONTO>&#013;&#010;';
									
									if(count($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row) > 1 && $contadorRecC31 < count($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row)) echo '&#013;&#010;';
								}
								echo '</BANCO_CENTRAL_ULTIMAS_SITUACIONES>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->Bcra_Situaciones_Vigente[0]))
							{
								echo '<BANCO_CENTRAL_SITUACIONES_VIGENTES>&#013;&#010;';
								$contadorRecC32 = 0;
								foreach ($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row as $recBCSV) 
								{							
									$contadorRecC32++;
									if(!empty($recBCSV->entidad)) echo '	<ENTIDAD> '.$recBCSV->entidad.' </ENTIDAD>&#013;&#010;';
									if(!empty($recBCSV->periodos)) echo '	<PERIODOS> '.$recBCSV->periodos.' </PERIODOS>&#013;&#010;';
									if(!empty($recBCSV->situacion)) echo '	<SITUACION> '.$recBCSV->situacion.' </SITUACION>&#013;&#010;';
									if(!empty($recBCSV->monto)) echo '	<MONTO> '.$recBCSV->monto.' </MONTO>&#013;&#010;';
									
									if(count($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row) > 1 && $contadorRecC32 < count($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row)) echo '&#013;&#010;';
								}
								echo '</BANCO_CENTRAL_SITUACIONES_VIGENTES>&#013;&#010;&#013;&#010;';
							}							
							
							if(!empty($estado_fin_cli->Tipo_Actividad[0]))
							{							
								echo '<TIPO_ACTIVIDAD>&#013;&#010;';
								$contadorRecC33 = 0;
								foreach ($estado_fin_cli->Tipo_Actividad[0]->row as $recTAC) 
								{								
									$contadorRecC33++;
									echo '	<TIPO_ACTIVIDAD> '.$recTAC->tipo_actividad.' </TIPO_ACTIVIDAD>&#013;&#010;';

									if(count($estado_fin_cli->Tipo_Actividad[0]->row) > 1 && $contadorRecC33 < count($estado_fin_cli->Tipo_Actividad[0]->row)) echo '&#013;&#010;';								
								}
								echo '</TIPO_ACTIVIDAD>&#013;&#010;&#013;&#010;';	
							}								
					
							if(!empty($estado_fin_cli->Consultas_Individual_[0]))
							{
								echo '<CONSULTAS_INDIVIDUALES>&#013;&#010;';
								$contadorRecC34 = 0;
								foreach ($estado_fin_cli->Consultas_Individual_[0]->row as $recCINDC) 
								{							
									$contadorRecC34++;
									if(!empty($recCINDC->dia)) echo '	<DIA> '.$recCINDC->dia.' </DIA>&#013;&#010;';
									if(!empty($recCINDC->semana)) echo '	<SEMANA> '.$recCINDC->semana.' </SEMANA>&#013;&#010;';
									if(!empty($recCINDC->mes)) echo '	<MES> '.$recCINDC->mes.' </MES>&#013;&#010;';
									if(!empty($recCINDC->m24)) echo '	<M24> '.$recCINDC->m24.' </M24>&#013;&#010;';
									
									if(count($estado_fin_cli->Consultas_Individual_[0]->row) > 1 && $contadorRecC34 < count($estado_fin_cli->Consultas_Individual_[0]->row)) echo '&#013;&#010;';
								}
								echo '</CONSULTAS_INDIVIDUALES>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->Consultas_Grupo_[0]))
							{
								echo '<CONSULTAS_GRUPO>&#013;&#010;';
								$contadorRecC35 = 0;
								foreach ($estado_fin_cli->Consultas_Grupo_[0]->row as $recCGRUC) 
								{							
									$contadorRecC35++;
									if(!empty($recCGRUC->dia)) echo '	<DIA> '.$recCGRUC->dia.' </DIA>&#013;&#010;';
									if(!empty($recCGRUC->semana)) echo '	<SEMANA> '.$recCGRUC->semana.' </SEMANA>&#013;&#010;';
									if(!empty($recCGRUC->mes)) echo '	<MES> '.$recCGRUC->mes.' </MES>&#013;&#010;';
									if(!empty($recCGRUC->m24)) echo '	<M24> '.$recCGRUC->m24.' </M24>&#013;&#010;';
									
									if(count($estado_fin_cli->Consultas_Grupo_[0]->row) > 1 && $contadorRecC35 < count($estado_fin_cli->Consultas_Grupo_[0]->row)) echo '&#013;&#010;';
								}
								echo '</CONSULTAS_GRUPO>&#013;&#010;&#013;&#010;';
							}	

							if(!empty($estado_fin_cli->CONSULTADO_POR_[0]))
							{
								echo '<CONSULTADO_POR>&#013;&#010;';
								$contadorRecC36 = 0;
								foreach ($estado_fin_cli->CONSULTADO_POR_[0]->row as $recCCONP) 
								{							
									$contadorRecC36++;
									if(!empty($recCCONP->fecha)) echo '	<FECHA> '.$recCCONP->fecha.' </FECHA>&#013;&#010;';
									if(!empty($recCCONP->consultante)) echo '	<CONSULTANTE> '.$recCCONP->consultante.' </CONSULTANTE>&#013;&#010;';
									
									if(count($estado_fin_cli->CONSULTADO_POR_[0]->row) > 1 && $contadorRecC36 < count($estado_fin_cli->CONSULTADO_POR_[0]->row)) echo '&#013;&#010;';
								}
								echo '</CONSULTADO_POR>&#013;&#010;&#013;&#010;';
							}							
					
							if(!empty($estado_fin_cli->Independientes_[0]))
							{
								echo '<INDEPENDIENTES>&#013;&#010;';
								$contadorRecC37 = 0;
								foreach ($estado_fin_cli->Independientes_[0]->row as $recCONIND) 
								{							
									$contadorRecC37++;
									if(!empty($recCONIND->inscripto)) echo '	<INSCRIPTO> '.$recCONIND->inscripto.' </INSCRIPTO>&#013;&#010;';
									if(!empty($recCONIND->categoria)) echo '	<CATEGORIA> '.$recCONIND->categoria.' </CATEGORIA>&#013;&#010;';
									if(!empty($recCONIND->cuit)) echo '	<CUIT> '.$recCONIND->cuit.' </CUIT>&#013;&#010;';
									if(!empty($recCONIND->documento)) echo '	<DOCUMENTO> '.$recCONIND->documento.' </DOCUMENTO>&#013;&#010;';
									if(!empty($recCONIND->nombre)) echo '	<NOMBRE> '.$recCONIND->nombre.' </NOMBRE>&#013;&#010;';
									if(!empty($recCONIND->domicilio)) echo '	<DOMICILIO> '.$recCONIND->domicilio.' </DOMICILIO>&#013;&#010;';
									if(!empty($recCONIND->cod_postal)) echo '	<CODIGO_POSTAL> '.$recCONIND->cod_postal.' </CODIGO_POSTAL>&#013;&#010;';
									if(!empty($recCONIND->localidad)) echo '	<LOCALIDAD> '.$recCONIND->localidad.' </LOCALIDAD>&#013;&#010;';
									if(!empty($recCONIND->provincia)) echo '	<PROVINCIA> '.$recCONIND->provincia.' </PROVINCIA>&#013;&#010;';
									if(!empty($recCONIND->fecha_nac)) echo '	<FECHA_NACIMIENTO> '.$recCONIND->fecha_nac.' </FECHA_NACIMIENTO>&#013;&#010;';
									if(!empty($recCONIND->entidad)) echo '	<ENTIDAD> '.$recCONIND->entidad.' </ENTIDAD>&#013;&#010;';
									if(!empty($recCONIND->actividad)) echo '	<ACTIVIDAD> '.$recCONIND->actividad.' </ACTIVIDAD>&#013;&#010;';
									if(!empty($recCONIND->periodo)) echo '	<PERIODO> '.$recCONIND->periodo.' </PERIODO>&#013;&#010;';
									if(!empty($recCONIND->monto)) echo '	<MONTO> '.$recCONIND->monto.' </MONTO>&#013;&#010;';									
									
									if(count($estado_fin_cli->Independientes_[0]->row) > 1 && $contadorRecC37 < count($estado_fin_cli->Independientes_[0]->row)) echo '&#013;&#010;';
								}
								echo '</INDEPENDIENTES>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->Indep_apo_[0]))
							{
								echo '<APORTES_INDEPENDIENTE>&#013;&#010;';
								$contadorRecC38 = 0;
								foreach ($estado_fin_cli->Indep_apo_[0]->row as $recAPCI) 
								{							
									$contadorRecC38++;
									if(!empty($recAPCI->cuit)) echo '	<CUIT> '.$recAPCI->cuit.' </CUIT>&#013;&#010;';
									if(!empty($recAPCI->periodo)) echo '	<PERIODO> '.$recAPCI->periodo.' </PERIODO>&#013;&#010;';
									if(!empty($recAPCI->imp_pago)) echo '	<IMPORTE> '.$recAPCI->imp_pago.' </IMPORTE>&#013;&#010;';
									if(!empty($recAPCI->impuesto)) echo '	<IMPUESTO> '.$recAPCI->impuesto.' </IMPUESTO>&#013;&#010;';
									
									if(count($estado_fin_cli->Indep_apo_[0]->row) > 1 && $contadorRecC38 < count($estado_fin_cli->Indep_apo_[0]->row)) echo '&#013;&#010;';
								}
								echo '</APORTES_INDEPENDIENTE>&#013;&#010;&#013;&#010;';
							}							
							
							if(!empty($estado_fin_cli->Propiedades_[0]))
							{
								echo '<PROPIEDADES>&#013;&#010;';
								$contadorRecC39 = 0;
								foreach ($estado_fin_cli->Propiedades_[0]->row as $recIPROPC) 
								{							
									$contadorRecC39++;
									if(!empty($recIPROPC->titular)) echo '	<TITULAR> '.$recIPROPC->titular.' </TITULAR>&#013;&#010;';
									if(!empty($recIPROPC->folio)) echo '	<FOLIO> '.$recIPROPC->folio.' </FOLIO>&#013;&#010;';
									if(!empty($recIPROPC->ano_inscripcion)) echo '	<AÑO_INSCRIPCION> '.$recIPROPC->ano_inscripcion.' </AÑO_INSCRIPCION>&#013;&#010;';
									if(!empty($recIPROPC->ph)) echo '	<PH> '.$recIPROPC->ph.' </PH>&#013;&#010;';
									if(!empty($recIPROPC->condom)) echo '	<CONDOM> '.$recIPROPC->condom.' </CONDOM>&#013;&#010;';
									if(!empty($recIPROPC->codigo_departamento)) echo '	<CODIGO_DEPARTAMENTO> '.$recIPROPC->codigo_departamento.' </CODIGO_DEPARTAMENTO>&#013;&#010;';
									if(!empty($recIPROPC->pedania)) echo '	<PEDANIA> '.$recIPROPC->pedania.' </PEDANIA>&#013;&#010;';
									if(!empty($recIPROPC->pueblo)) echo '	<PUEBLO> '.$recIPROPC->pueblo.' </PUEBLO>&#013;&#010;';
									if(!empty($recIPROPC->circuncripcion)) echo '	<CIRCUNCRIPCION> '.$recIPROPC->circuncripcion.' </CIRCUNCRIPCION>&#013;&#010;';
									if(!empty($recIPROPC->seccion)) echo '	<SECCION> '.$recIPROPC->seccion.' </SECCION>&#013;&#010;';
									if(!empty($recIPROPC->manzana)) echo '	<MANZANA> '.$recIPROPC->manzana.' </MANZANA>&#013;&#010;';
									if(!empty($recIPROPC->parcela)) echo '	<PARCELA> '.$recIPROPC->parcela.' </PARCELA>&#013;&#010;';
									if(!empty($recIPROPC->escrituro)) echo '	<ESCRITURO> '.$recIPROPC->escrituro.' </ESCRITURO>&#013;&#010;';
									
									if(count($estado_fin_cli->Propiedades_[0]->row) > 1 && $contadorRecC39 < count($estado_fin_cli->Propiedades_[0]->row)) echo '&#013;&#010;';
								}
								echo '</PROPIEDADES>&#013;&#010;&#013;&#010;';
							}
							echo '						</textarea>';
							echo '					</div>';		
							echo '				</div>';							
						}
						else
						{
							if($stmt44 = $mysqli->prepare("SELECT e.id FROM ".$db_name.".estado_cliente e WHERE e.tipo_documento = ? AND e.documento = ? AND e.fecha like ? AND e.id_motivo IN (?,?)"))
							{
								$date_registro_c_ecef = date("Ymd")."%";
								$motivo2_u = 51;
								$stmt44->bind_param('issii', $tipoDocumento, $documento, $date_registro_c_ecef, $motivo, $motivo2_u);
								$stmt44->execute();    
								$stmt44->store_result();
								
								$totR44 = $stmt44->num_rows;

								if($totR44 == 0)
								{
									$estado_fin_cli = new SimpleXMLElement($resultado_finan_cli_final);
									echo translate('Msg_Validation_Credit_Status_Client_OK',$GLOBALS['lang']).'=::=::=::'.$tokenECF.'=:=:=:';
									echo '<div class="panel-group">';				
									echo '	<div class="panel panel-default">';
									echo '		<div id="panel-title-header" class="panel-heading">';
									echo '			<h3 class="panel-title">'.translate('Lbl_Result_Financial_Statement_Client',$GLOBALS['lang']).'</h3>';
									echo ' 		</div>';
									echo '		<div class="panel-body">';
									echo '			<form id="formulariocefcm" role="form" onsubmit="buscarTextoEstadoFinancieroM(); return false;">';		
									echo '				<div class="form-group form-inline">';
									echo '					<div class="form-group" id="buscartextoestadocrediticioclientem">';
									echo '						<input class="form-control input-sm" id="buscartextoestadocrediticioclientemi" name="buscartextoestadocrediticioclientemi" type="text" maxlength="150" />';
									echo '						&nbsp;<button type="button" class="btn" id="btnBuscarEstadoFM" name="btnBuscarEstadoFM" title="'.translate('Lbl_Search_Text_Statement_Client',$GLOBALS['lang']).'" onclick="buscarTextoEstadoFinancieroM();"><i class="fas fa-search"></i></button>';									
									echo '					</div>';
									echo '				</div>';
									echo '				<div class="form-group form-inline">';					
									echo '					<div class="form-group" id="resultadoestadofinancieroclientem">';
									echo '						<textarea rows="3" cols="67" class="form-control input-sm" id="resultadoestadofinancieroclientemi" name="resultadoestadofinancieroclientemi">';
									echo '<EXISTENCIA_FISICA_ENTIDAD>&#013;&#010;';
									$contadorRecC1 = 0;
									foreach ($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row as $recEFC) 
									{
										$contadorRecC1++;
										if(!empty($recEFC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recEFC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
										if(!empty($recEFC->sexo)) echo '	<SEXO> '.$recEFC->sexo.' </SEXO>&#013;&#010;';
										if(!empty($recEFC->tipo_docu)) echo '	<TIPO_DOCUMENTO> '.$recEFC->tipo_docu.' </TIPO_DOCUMENTO>&#013;&#010;';
										if(!empty($recEFC->nume_doc)) echo '	<DOCUMENTO> '.$recEFC->nume_doc.' </DOCUMENTO>&#013;&#010;';
										if(!empty($recEFC->clase)) echo '	<CLASE> '.$recEFC->clase.' </CLASE>&#013;&#010;';
										if(!empty($recEFC->edad)) echo '	<EDAD> '.$recEFC->edad.' </EDAD>&#013;&#010;';
										if(!empty($recEFC->ocupacion)) echo '	<OCUPACION> '.$recEFC->ocupacion.' </OCUPACION>&#013;&#010;';
										if(!empty($recEFC->cdi_codigo_de_identificacion)) echo '	<CUIT_CUIL> '.$recEFC->cdi_codigo_de_identificacion.' </CUIT_CUIL>&#013;&#010;';								
										if(!empty($recEFC->fecha_nacimiento)) echo '	<FECHA_NACIMIENTO> '.$recEFC->fecha_nacimiento.' </FECHA_NACIMIENTO>&#013;&#010;';							
										if(!empty($recEFC->direc_calle)) echo '	<DIRECCION_CALLE> '.$recEFC->direc_calle.' </DIRECCION_CALLE>&#013;&#010;';							
										if(!empty($recEFC->localidad)) echo '	<LOCALIDAD> '.$recEFC->localidad.' </LOCALIDAD>&#013;&#010;';
										if(!empty($recEFC->departamento)) echo '	<DEPARTAMENTO> '.$recEFC->departamento.' </DEPARTAMENTO>&#013;&#010;';								
										if(!empty($recEFC->codigo_postal)) echo '	<CODIGO_POSTAL> '.$recEFC->codigo_postal.' </CODIGO_POSTAL>&#013;&#010;';							
										if(!empty($recEFC->provincia)) echo '	<PROVINCIA> '.$recEFC->provincia.' </PROVINCIA>&#013;&#010;';							
										if(!empty($recEFC->apellido_materno)) echo '	<APELLIDO_MATERNO> '.$recEFC->apellido_materno.' </APELLIDO_MATERNO>&#013;&#010;';
										if(!empty($recEFC->estado_civil)) echo '	<ESTADO_CIVIL> '.$recEFC->estado_civil.' </ESTADO_CIVIL>&#013;&#010;';								
										if(!empty($recEFC->fallecido)) echo '	<FALLECIDO> '.$recEFC->fallecido.' </FALLECIDO>&#013;&#010;';
										
										if(count($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row) > 1 && $contadorRecC1 < count($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row)) echo '&#013;&#010;';
									}							
									echo '</EXISTENCIA_FISICA_ENTIDAD>&#013;&#010;&#013;&#010;';
									
									if(!empty($estado_fin_cli->Score_[0]))
									{
										echo '<SCORE>&#013;&#010;';
										$contadorRecC2 = 0;
										foreach ($estado_fin_cli->Score_[0]->row as $recPIC) 
										{							
											$contadorRecC2++;
											if(!empty($recPIC->score)) echo '	<SCORE> '.$recPIC->score.' </SCORE>&#013;&#010;';
											
											if(count($estado_fin_cli->Score_[0]->row) > 1 && $contadorRecC2 < count($estado_fin_cli->Score_[0]->row)) echo '&#013;&#010;';
										}
										echo '</SCORE>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->Domi_xy_[0]))
									{
										echo '<DOMICILIO_XY>&#013;&#010;';
										$contadorRecC3 = 0;
										foreach ($estado_fin_cli->Domi_xy_[0]->row as $recTJQEC) 
										{								
											$contadorRecC3++;
											if(!empty($recTJQEC->nprovincia)) echo '	<N_PROVINCIA> '.$recTJQEC->nprovincia.' </N_PROVINCIA>&#013;&#010;';
											if(!empty($recTJQEC->npartido)) echo '	<N_PARTIDO> '.$recTJQEC->npartido.' </N_PARTIDO>&#013;&#010;';
											if(!empty($recTJQEC->nlocalidad)) echo '	<N_LOCALIDAD> '.$recTJQEC->nlocalidad.' </N_LOCALIDAD>&#013;&#010;';
											if(!empty($recTJQEC->nbarrio)) echo '	<N_BARRIO> '.$recTJQEC->nbarrio.' </N_BARRIO>&#013;&#010;';
											if(!empty($recTJQEC->calle)) echo '	<CALLE> '.$recTJQEC->calle.' </CALLE>&#013;&#010;';
											if(!empty($recTJQEC->ncp)) echo '	<NCP> '.$recTJQEC->ncp.' </NCP>&#013;&#010;';
											if(!empty($recTJQEC->geocordenadas)) echo '	<GEOCORDENADAS> '.$recTJQEC->geocordenadas.' </GEOCORDENADAS>&#013;&#010;';
											if(count($estado_fin_cli->Domi_xy_[0]->row) > 1 && $contadorRecC3 < count($estado_fin_cli->Domi_xy_[0]->row)) echo '&#013;&#010;';
										}
										echo '</DOMICILIO_XY>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->Situacion_BCRA[0]))
									{							
										echo '<SITUACION_BCRA>&#013;&#010;';
										$contadorRecC4 = 0;
										foreach ($estado_fin_cli->Situacion_BCRA[0]->row as $recDSF6M) 
										{								
											$contadorRecC4++;
											if(!empty($recDSF6M->entidad)) echo '	<ENTIDAD> '.$recDSF6M->entidad.' </ENTIDAD>&#013;&#010;';
											if(!empty($recDSF6M->fecha_sit)) echo '	<FECHA> '.$recDSF6M->fecha_sit.' </FECHA>&#013;&#010;';									
											if(!empty($recDSF6M->situacion)) echo '	<SITUACION> '.$recDSF6M->situacion.' </SITUACION>&#013;&#010;';
											if(!empty($recDSF6M->deuda_total))  echo '	<DEUDA_TOTAL> '.$recDSF6M->deuda_total.' </DEUDA_TOTAL>&#013;&#010;';

											if(count($estado_fin_cli->Situacion_BCRA[0]->row) > 1 && $contadorRecC4 < count($estado_fin_cli->Situacion_BCRA[0]->row)) echo '&#013;&#010;';								
										}
										echo '</SITUACION_BCRA>&#013;&#010;&#013;&#010;';	
									}
									
									if(!empty($estado_fin_cli->FraudesTarjeta_[0]))
									{
										echo '<FRAUDES_TARJETA>&#013;&#010;';
										$contadorRecC5 = 0;
										foreach ($estado_fin_cli->FraudesTarjeta_[0]->row as $recFTs) 
										{							
											$contadorRecC5++;
											if(!empty($recFTs->fraude)) echo '	<FRAUDE> '.$recFTs->fraude.' </FRAUDE>&#013;&#010;';
											
											if(count($estado_fin_cli->FraudesTarjeta_[0]->row) > 1 && $contadorRecC5 < count($estado_fin_cli->FraudesTarjeta_[0]->row)) echo '&#013;&#010;';
										}
										echo '</FRAUDES_TARJETA>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->CapacidadEndeudamiento_[0]))
									{
										echo '<CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;';
										$contadorRecC6 = 0;
										foreach ($estado_fin_cli->CapacidadEndeudamiento_[0]->row as $recCPEC) 
										{							
											$contadorRecC6++;
											if(!empty($recCPEC->capacidad_endeudamiento)) echo '	<CAPACIDAD_ENDEUDAMIENTO> '.$recCPEC->capacidad_endeudamiento.' </CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;';
											
											if(count($estado_fin_cli->CapacidadEndeudamiento_[0]->row) > 1 && $contadorRecC6 < count($estado_fin_cli->CapacidadEndeudamiento_[0]->row)) echo '&#013;&#010;';
										}
										echo '</CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;&#013;&#010;';
									}							
									
									if(!empty($estado_fin_cli->Ooss_personas_[0]))
									{							
										echo '<OBRA_SOCIAL_PERSONAS>&#013;&#010;';
										$contadorRecC7 = 0;
										foreach ($estado_fin_cli->Ooss_personas_[0]->row as $recDSF12M) 
										{								
											$contadorRecC7++;
											if(!empty($recDSF12M->cuil)) echo '	<CUIL> '.$recDSF12M->cuil.' </CUIL>&#013;&#010;';
											if(!empty($recDSF12M->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recDSF12M->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
											if(!empty($recDSF12M->nume_docu)) echo '	<DOCUMENTO> '.$recDSF12M->nume_docu.' </DOCUMENTO>&#013;&#010;';
											if(!empty($recDSF12M->sexo)) echo '	<SEXO> '.$recDSF12M->sexo.' </SEXO>&#013;&#010;';
											if(!empty($recDSF12M->domicilio)) echo '	<DOMICILIO> '.$recDSF12M->domicilio.' </DOMICILIO>&#013;&#010;';
											if(!empty($recDSF12M->localidad)) echo '	<LOCALIDAD> '.$recDSF12M->localidad.' </LOCALIDAD>&#013;&#010;';
											if(!empty($recDSF12M->provincia)) echo '	<PROVINCIA> '.$recDSF12M->provincia.' </PROVINCIA>&#013;&#010;';
											if(!empty($recDSF12M->cod_postal)) echo '	<CODIGO_POSTAL> '.$recDSF12M->cod_postal.' </CODIGO_POSTAL>&#013;&#010;';									

											if(count($estado_fin_cli->Ooss_personas_[0]->row) > 1 && $contadorRecC7 < count($estado_fin_cli->Ooss_personas_[0]->row)) echo '&#013;&#010;';								
										}
										echo '</OBRA_SOCIAL_PERSONAS>&#013;&#010;&#013;&#010;';	
									}
									
									if(!empty($estado_fin_cli->Obra_Social_Rel_[0]))
									{							
										echo '<OBRA_SOCIAL_RELACION>&#013;&#010;';
										$contadorRecC8 = 0;
										foreach ($estado_fin_cli->Obra_Social_Rel_[0]->row as $recDSF24M) 
										{								
											$contadorRecC8++;
											if(!empty($recDSF24M->codiobra)) echo '	<CODIGO> '.$recDSF24M->codiobra.' </CODIGO>&#013;&#010;';
											if(!empty($recDSF24M->descripcion_ooss)) echo '	<DESCRIPCION> '.$recDSF24M->descripcion_ooss.' </DESCRIPCION>&#013;&#010;';
											if(!empty($recDSF24M->sigla_ooss)) echo '	<SIGLA> '.$recDSF24M->sigla_ooss.' </SIGLA>&#013;&#010;';
											if(!empty($recDSF24M->cobertura)) echo '	<COBERTURA> '.$recDSF24M->cobertura.' </COBERTURA>&#013;&#010;';
											if(!empty($recDSF24M->conyugue)) echo '	<CONYUGUE> '.$recDSF24M->conyugue.' </CONYUGUE>&#013;&#010;';
											if(!empty($recDSF24M->hijos)) echo '	<HIJOS> '.$recDSF24M->hijos.' </HIJOS>&#013;&#010;';

											if(count($estado_fin_cli->Obra_Social_Rel_[0]->row) > 1 && $contadorRecC8 < count($estado_fin_cli->Obra_Social_Rel_[0]->row)) echo '&#013;&#010;';								
										}
										echo '</OBRA_SOCIAL_RELACION>&#013;&#010;&#013;&#010;';	
									}
									
									if(!empty($estado_fin_cli->Domicilio_Empresa_trabaja_[0]))
									{
										echo '<DOMICILIO_LABORAL>&#013;&#010;';
										$contadorRecC9 = 0;
										foreach ($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row as $recRDC) 
										{								
											$contadorRecC9++;
											if(!empty($recRDC->direccion)) echo '	<DIRECCION> '.$recRDC->ult_periodo.' </DIRECCION>&#013;&#010;';
											if(!empty($recRDC->localidad)) echo '	<LOCALIDAD> '.$recRDC->alta_trabajo_ultimo.' </LOCALIDAD>&#013;&#010;';
											if(!empty($recRDC->provincia)) echo '	<PROVINCIA> '.$recRDC->cuit.' </PROVINCIA>&#013;&#010;';
											if(!empty($recRDC->cp)) echo '	<CODIGO_POSTAL> '.$recRDC->razon_social.' </CODIGO_POSTAL>&#013;&#010;';

											if(count($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row) > 1 && $contadorRecC9 < count($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row)) echo '&#013;&#010;';								
										}
										echo '</DOMICILIO LABORAL>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->domicilio_otros_[0]))
									{							
										echo '<OTROS_DOMICILIOS>&#013;&#010;';
										$contadorRecC10 = 0;
										foreach ($estado_fin_cli->domicilio_otros_[0]->row as $recCIAC) 
										{								
											$contadorRecC10++;
											if(!empty($recCIAC->domicilio)) echo '	<DOMICILIO> '.$recCIAC->domicilio.' </DOMICILIO>&#013;&#010;';
											if(!empty($recCIAC->localidad)) echo '	<LOCALIDAD> '.$recCIAC->localidad.' </LOCALIDAD>&#013;&#010;';
											if(!empty($recCIAC->cp)) echo '	<CP> '.$recCIAC->cp.' </CP>&#013;&#010;';
											if(!empty($recCIAC->provincia)) echo '	<PROVINCIA> '.$recCIAC->provincia.' </PROVINCIA>&#013;&#010;';

											if(count($estado_fin_cli->domicilio_otros_[0]->row) > 1 && $contadorRecC10 < count($estado_fin_cli->domicilio_otros_[0]->row)) echo '&#013;&#010;';								
										}
										echo '</OTROS_DOMICILIOS>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->Domicilios_Juicios_[0]))
									{
										echo '<DOMICILIOS_JUICIOS>&#013;&#010;';
										$contadorRecC11 = 0;
										foreach ($estado_fin_cli->Domicilios_Juicios_[0]->row as $recDJD) 
										{							
											$contadorRecC11++;
											if(!empty($recDJD->domicilio)) echo '	<DOMICILIO> '.$recDJD->domicilio.' </DOMICILIO>&#013;&#010;';
											
											if(count($estado_fin_cli->Domicilios_Juicios_[0]->row) > 1 && $contadorRecC11 < count($estado_fin_cli->Domicilios_Juicios_[0]->row)) echo '&#013;&#010;';
										}
										echo '</DOMICILIOS_JUICIOS>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->Padronempresasf_[0]))
									{							
										echo '<PADRON_EMPRESAS>&#013;&#010;';
										$contadorRecC12 = 0;
										foreach ($estado_fin_cli->Padronempresasf_[0]->row as $recPMC) 
										{								
											$contadorRecC12++;
											if(!empty($recPMC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recPMC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
											if(!empty($recPMC->cuit)) echo '	<CUIT> '.$recPMC->cuit.' </CUIT>&#013;&#010;';
											if(!empty($recPMC->domicilio_fiscal)) echo '	<DOMICILIO_FISCAL> '.$recPMC->domicilio_fiscal.' </DOMICILIO_FISCAL>&#013;&#010;';
											if(!empty($recPMC->provincia)) echo '	<PROVINCIA> '.$recPMC->provincia.' </PROVINCIA>&#013;&#010;';
											if(!empty($recPMC->actividad_especifica)) echo '	<ACTIVIDAD_ESPECIFICA> '.$recPMC->actividad_especifica.' </ACTIVIDAD_ESPECIFICA>&#013;&#010;';
											if(!empty($recPMC->categoria_autonomo)) echo '	<CATEGORIA_AUTONOMO> '.$recPMC->categoria_autonomo.' </CATEGORIA_AUTONOMO>&#013;&#010;';

											if(count($estado_fin_cli->Padronempresasf_[0]->row) > 1 && $contadorRecC12 < count($estado_fin_cli->Padronempresasf_[0]->row)) echo '&#013;&#010;';									
										}
										echo '</PADRON_EMPRESAS>&#013;&#010;&#013;&#010;';							
									}
									
									if(!empty($estado_fin_cli->Gran_contrib_[0]))
									{
										echo '<GRANDES_CONTRIBUYENTES>&#013;&#010;';
										$contadorRecC13 = 0;
										foreach ($estado_fin_cli->Gran_contrib_[0]->row as $recGCD) 
										{							
											$contadorRecC13++;
											if(!empty($recGCD->gran_contr)) echo '	<CONTRIBUYENTE> '.$recGCD->gran_contr.' </CONTRIBUYENTE>&#013;&#010;';
											
											if(count($estado_fin_cli->Gran_contrib_[0]->row) > 1 && $contadorRecC13 < count($estado_fin_cli->Gran_contrib_[0]->row)) echo '&#013;&#010;';
										}
										echo '</GRANDES_CONTRIBUYENTES>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->Domialter_[0]))
									{							
										echo '<DOMICILIOS_ALTERNATIVOS>&#013;&#010;';
										$contadorRecC14 = 0;
										foreach ($estado_fin_cli->Domialter_[0]->row as $recDMAC) 
										{								
											$contadorRecC14++;
											if(!empty($recDMAC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recDMAC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
											if(!empty($recDMAC->cuit)) echo '	<CUIT> '.$recDMAC->cuit.' </CUIT>&#013;&#010;';
											if(!empty($recDMAC->domicilio_fiscal)) echo '	<DOMICILIO_FISCAL> '.$recDMAC->domicilio_fiscal.' </DOMICILIO_FISCAL>&#013;&#010;';
											if(!empty($recDMAC->provincia)) echo '	<PROVINCIA> '.$recDMAC->provincia.' </PROVINCIA>&#013;&#010;';
											if(!empty($recDMAC->actividad_especifica)) echo '	<ACTIVIDAD_ESPECIFICA> '.$recDMAC->actividad_especifica.' </ACTIVIDAD_ESPECIFICA>&#013;&#010;';

											if(count($estado_fin_cli->Domialter_[0]->row) > 1 && $contadorRecC14 < count($estado_fin_cli->Domialter_[0]->row)) echo '&#013;&#010;';									
										}
										echo '</DOMICILIOS_ALTERNATIVOS>&#013;&#010;&#013;&#010;';							
									}
									
									if(!empty($estado_fin_cli->Nise_[0]))
									{
										echo '<NISE>&#013;&#010;';
										$contadorRecC15 = 0;
										foreach ($estado_fin_cli->Nise_[0]->row as $recINC) 
										{							
											$contadorRecC15++;
											if(!empty($recINC->radio)) echo '	<RADIO> '.$recINC->radio.' </RADIO>&#013;&#010;';
											if(!empty($recINC->nise)) echo '	<NISE> '.$recINC->nise.' </NISE>&#013;&#010;';
											if(!empty($recINC->periodo)) echo '	<PERIODO> '.$recINC->periodo.' </PERIODO>&#013;&#010;';
											
											if(count($estado_fin_cli->Nise_[0]->row) > 1 && $contadorRecC15 < count($estado_fin_cli->Nise_[0]->row)) echo '&#013;&#010;';
										}
										echo '</NISE>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->Const_inscrip_[0]))
									{							
										echo '<CONSTANCIAS_INSCRIPCION>&#013;&#010;';
										$contadorRecC16 = 0;
										foreach ($estado_fin_cli->Const_inscrip_[0]->row as $recCICR) 
										{								
											$contadorRecC16++;
											if(!empty($recCICR->descrip_imp_ganancias)) echo '	<DESCRIPCION_IMP_GANANCIAS> '.$recCICR->descrip_imp_ganancias.' </DESCRIPCION_IMP_GANANCIAS>&#013;&#010;';
											if(!empty($recCICR->descrip_imp_iva)) echo '	<DESCRIPCION_IMP_IVA> '.$recCICR->descrip_imp_iva.' </DESCRIPCION_IMP_IVA>&#013;&#010;';
											if(!empty($recCICR->descrip_monotributo)) echo '	<DESCRIPCION_MONOTRIBUTO> '.$recCICR->descrip_monotributo.' </DESCRIPCION_MONOTRIBUTO>&#013;&#010;';
											if(!empty($recCICR->descrip_integrante_soc)) echo '	<DESCRIPCION_INTEGRANTE_SOC> '.$recCICR->descrip_integrante_soc.' </DESCRIPCION_INTEGRANTE_SOC>&#013;&#010;';
											if(!empty($recCICR->descrip_empleador)) echo '	<DESCRIPCION_EMPLEADOR> '.$recCICR->descrip_empleador.' </DESCRIPCION_EMPLEADOR>&#013;&#010;';
											if(!empty($recCICR->periodo)) echo '	<PERIODO> '.$recCICR->periodo.' </PERIODO>&#013;&#010;';

											if(count($estado_fin_cli->Const_inscrip_[0]->row) > 1 && $contadorRecC16 < count($estado_fin_cli->Const_inscrip_[0]->row)) echo '&#013;&#010;';									
										}
										echo '</CONSTANCIAS_INSCRIPCION>&#013;&#010;&#013;&#010;';							
									}
									
									if(!empty($estado_fin_cli->Nbi_[0]))
									{
										echo '<NBI>&#013;&#010;';
										$contadorRecC17 = 0;
										foreach ($estado_fin_cli->Nbi_[0]->row as $recINBC) 
										{							
											$contadorRecC17++;
											if(!empty($recINBC->radio)) echo '	<RADIO> '.$recINBC->radio.' </RADIO>&#013;&#010;';
											if(!empty($recINBC->nbi)) echo '	<NBI> '.$recINBC->nbi.' </NBI>&#013;&#010;';
											if(!empty($recINBC->periodo)) echo '	<PERIODO> '.$recINBC->periodo.' </PERIODO>&#013;&#010;';
											
											if(count($estado_fin_cli->Nbi_[0]->row) > 1 && $contadorRecC17 < count($estado_fin_cli->Nbi_[0]->row)) echo '&#013;&#010;';
										}
										echo '</NBI>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->Iped_[0]))
									{
										echo '<IPED>&#013;&#010;';
										$contadorRecC18 = 0;
										foreach ($estado_fin_cli->Iped_[0]->row as $recIIPC) 
										{							
											$contadorRecC18++;
											if(!empty($recIIPC->radio)) echo '	<RADIO> '.$recIIPC->radio.' </RADIO>&#013;&#010;';
											if(!empty($recIIPC->iped)) echo '	<IPED> '.$recIIPC->iped.' </IPED>&#013;&#010;';
											if(!empty($recIIPC->periodo)) echo '	<PERIODO> '.$recIIPC->periodo.' </PERIODO>&#013;&#010;';
											
											if(count($estado_fin_cli->Iped_[0]->row) > 1 && $contadorRecC18 < count($estado_fin_cli->Iped_[0]->row)) echo '&#013;&#010;';
										}
										echo '</IPED>&#013;&#010;&#013;&#010;';
									}							

									if(!empty($estado_fin_cli->Ib_[0]))
									{
										echo '<IB>&#013;&#010;';
										$contadorRecC19 = 0;
										foreach ($estado_fin_cli->Ib_[0]->row as $recIIBC) 
										{							
											$contadorRecC19++;
											if(!empty($recIIBC->radio)) echo '	<RADIO> '.$recIIBC->radio.' </RADIO>&#013;&#010;';
											if(!empty($recIIBC->ib)) echo '	<IB> '.$recIIBC->ib.' </IB>&#013;&#010;';
											if(!empty($recIIBC->periodo)) echo '	<PERIODO> '.$recIIBC->periodo.' </PERIODO>&#013;&#010;';
											
											if(count($estado_fin_cli->Ib_[0]->row) > 1 && $contadorRecC19 < count($estado_fin_cli->Ib_[0]->row)) echo '&#013;&#010;';
										}
										echo '</IB>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->Rel_Dependencia_Trabajador_[0]))
									{
										echo '<RELEACION_DEPENDENCIA_TRABAJADOR>&#013;&#010;';
										$contadorRecC20 = 0;
										foreach ($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row as $recRDTC) 
										{							
											$contadorRecC20++;
											if(!empty($recRDTC->cuil)) echo '	<CUIL> '.$recRDTC->cuil.' </CUIL>&#013;&#010;';
											if(!empty($recRDTC->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recRDTC->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
											if(!empty($recRDTC->domicilio)) echo '	<DOMICILIO> '.$recRDTC->domicilio.' </DOMICILIO>&#013;&#010;';
											if(!empty($recRDTC->localidad)) echo '	<LOCALIDAD> '.$recRDTC->localidad.' </LOCALIDAD>&#013;&#010;';
											if(!empty($recRDTC->cp)) echo '	<CODIGO_POSTAL> '.$recRDTC->cp.' </CODIGO_POSTAL>&#013;&#010;';
											if(!empty($recRDTC->provincia)) echo '	<PROVINCIA> '.$recRDTC->provincia.' </PROVINCIA>&#013;&#010;';
											if(!empty($recRDTC->fecha_nac)) echo '	<FECHA_NACIMIENTO> '.$recRDTC->fecha_nac.' </FECHA_NACIMIENTO>&#013;&#010;';
											if(!empty($recRDTC->sexo)) echo '	<SEXO> '.$recRDTC->sexo.' </SEXO>&#013;&#010;';
											if(!empty($recRDTC->doc)) echo '	<DOCUMENTO> '.$recRDTC->doc.' </DOCUMENTO>&#013;&#010;';
											
											if(count($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row) > 1 && $contadorRecC20 < count($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row)) echo '&#013;&#010;';
										}
										echo '</RELEACION_DEPENDENCIA_TRABAJADOR>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->Rel_Dependencia_Empleador_[0]))
									{
										echo '<RELEACION_DEPENDENCIA_EMPLEADOR>&#013;&#010;';
										$contadorRecC21 = 0;
										foreach ($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row as $recRDEC) 
										{							
											$contadorRecC21++;
											if(!empty($recRDEC->cuit)) echo '	<CUIT> '.$recRDEC->cuit.' </CUIT>&#013;&#010;';
											if(!empty($recRDEC->razon)) echo '	<RAZON_SOCIAL> '.$recRDEC->razon.' </RAZON_SOCIAL>&#013;&#010;';
											if(!empty($recRDEC->domicilio)) echo '	<DOMICILIO> '.$recRDEC->domicilio.' </DOMICILIO>&#013;&#010;';
											if(!empty($recRDEC->localidad)) echo '	<LOCALIDAD> '.$recRDEC->localidad.' </LOCALIDAD>&#013;&#010;';
											if(!empty($recRDEC->cp)) echo '	<CODIGO_POSTAL> '.$recRDEC->cp.' </CODIGO_POSTAL>&#013;&#010;';
											if(!empty($recRDEC->provincia)) echo '	<PROVINCIA> '.$recRDEC->provincia.' </PROVINCIA>&#013;&#010;';
											
											if(count($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row) > 1 && $contadorRecC21 < count($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row)) echo '&#013;&#010;';
										}
										echo '</RELEACION_DEPENDENCIA_EMPLEADOR>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->Historia_[0]))
									{
										echo '<HISTORIA_EMPRESAS>&#013;&#010;';
										$contadorRecC22 = 0;
										foreach ($estado_fin_cli->Historia_[0]->row as $recHEC) 
										{							
											$contadorRecC22++;
											if(!empty($recHEC->cuit)) echo '	<CUIT> '.$recHEC->cuit.' </CUIT>&#013;&#010;';
											if(!empty($recHEC->razon)) echo '	<RAZON_SOCIAL> '.$recHEC->razon.' </RAZON_SOCIAL>&#013;&#010;';
											if(!empty($recHEC->desde)) echo '	<DESDE> '.$recHEC->desde.' </DESDE>&#013;&#010;';
											if(!empty($recHEC->hasta)) echo '	<HASTA> '.$recHEC->hasta.' </HASTA>&#013;&#010;';
											
											if(count($estado_fin_cli->Historia_[0]->row) > 1 && $contadorRecC22 < count($estado_fin_cli->Historia_[0]->row)) echo '&#013;&#010;';
										}
										echo '</HISTORIA_EMPRESAS>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->Sysem_[0]))
									{
										echo '<SYSEM>&#013;&#010;';
										$contadorRecC23 = 0;
										foreach ($estado_fin_cli->Sysem_[0]->row as $recSYC) 
										{							
											$contadorRecC23++;
											if(!empty($recSYC->cant_emp)) echo '	<CANTIDAD_EMPLEADOS> '.$recSYC->cant_emp.' </CANTIDAD_EMPLEADOS>&#013;&#010;';
											if(!empty($recSYC->cuit)) echo '	<CUIT> '.$recSYC->cuit.' </CUIT>&#013;&#010;';
											if(!empty($recSYC->razon)) echo '	<RAZON_SOCIAL> '.$recSYC->razon.' </RAZON_SOCIAL>&#013;&#010;';
											if(!empty($recSYC->domicilio)) echo '	<DOMICILIO> '.$recSYC->domicilio.' </DOMICILIO>&#013;&#010;';
											if(!empty($recSYC->localidad)) echo '	<LOCALIDAD> '.$recSYC->localidad.' </LOCALIDAD>&#013;&#010;';
											if(!empty($recSYC->cp)) echo '	<CODIGO_POSTAL> '.$recSYC->cp.' </CODIGO_POSTAL>&#013;&#010;';
											if(!empty($recSYC->provincia)) echo '	<PROVINCIA> '.$recSYC->provincia.' </PROVINCIA>&#013;&#010;';
											
											if(count($estado_fin_cli->Sysem_[0]->row) > 1 && $contadorRecC23 < count($estado_fin_cli->Sysem_[0]->row)) echo '&#013;&#010;';
										}
										echo '</SYSEM>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->Jyj_mensual_[0]))
									{
										echo '<SYSEM>&#013;&#010;';
										$contadorRecC24 = 0;
										foreach ($estado_fin_cli->Jyj_mensual_[0]->row as $recJYJC) 
										{							
											$contadorRecC24++;
											if(!empty($recJYJC->cuil)) echo '	<CUIL> '.$recJYJC->cuil.' </CUIL>&#013;&#010;';
											if(!empty($recJYJC->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recJYJC->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
											if(!empty($recJYJC->rango_socio_econom)) echo '	<RANGO_SOCIO_ECONOMICO> '.$recJYJC->rango_socio_econom.' </RANGO_SOCIO_ECONOMICO>&#013;&#010;';
											
											if(count($estado_fin_cli->Jyj_mensual_[0]->row) > 1 && $contadorRecC24 < count($estado_fin_cli->Jyj_mensual_[0]->row)) echo '&#013;&#010;';
										}
										echo '</SYSEM>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->Situacionlaboral_[0]))
									{
										echo '<SITUACION_LABORAL>&#013;&#010;';
										$contadorRecC25 = 0;
										foreach ($estado_fin_cli->Situacionlaboral_[0]->row as $recSLC) 
										{							
											$contadorRecC25++;
											if(!empty($recSLC->situacionlaboral)) echo '	<ESTADO_LABORAL> '.$recSLC->situacionlaboral.' </ESTADO_LABORAL>&#013;&#010;';
											
											if(count($estado_fin_cli->Situacionlaboral_[0]->row) > 1 && $contadorRecC25 < count($estado_fin_cli->Situacionlaboral_[0]->row)) echo '&#013;&#010;';
										}
										echo '</SITUACION_LABORAL>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->Producto_financieros_[0]))
									{
										echo '<PRODUCTO_FINANCIEROS>&#013;&#010;';
										$contadorRecC26 = 0;
										foreach ($estado_fin_cli->Producto_financieros_[0]->row as $recPFC) 
										{							
											$contadorRecC26++;
											if(!empty($recPFC->producto_financieros)) echo '	<PRODUCTO> '.$recPFC->producto_financieros.' </PRODUCTO>&#013;&#010;';
											
											if(count($estado_fin_cli->Producto_financieros_[0]->row) > 1 && $contadorRecC26 < count($estado_fin_cli->Producto_financieros_[0]->row)) echo '&#013;&#010;';
										}
										echo '</PRODUCTO_FINANCIEROS>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->Bancarizado_[0]))
									{
										echo '<BANCARIZADO>&#013;&#010;';
										$contadorRecC27 = 0;
										foreach ($estado_fin_cli->Bancarizado_[0]->row as $recEBC) 
										{							
											$contadorRecC27++;
											if(!empty($recEBC->bancarizado)) echo '	<ESTADO> '.$recEBC->bancarizado.' </ESTADO>&#013;&#010;';
											
											if(count($estado_fin_cli->Bancarizado_[0]->row) > 1 && $contadorRecC27 < count($estado_fin_cli->Bancarizado_[0]->row)) echo '&#013;&#010;';
										}
										echo '</BANCARIZADO>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->FechaMaxSistFin_[0]))
									{
										echo '<FECHA_MAXIMA_SISTEMA_FINANCIERO>&#013;&#010;';
										$contadorRecC28 = 0;
										foreach ($estado_fin_cli->FechaMaxSistFin_[0]->row as $recFMSF) 
										{							
											$contadorRecC28++;
											if(!empty($recFMSF->fecha)) echo '	<FECHA> '.$recFMSF->fecha.' </FECHA>&#013;&#010;';
											
											if(count($estado_fin_cli->FechaMaxSistFin_[0]->row) > 1 && $contadorRecC28 < count($estado_fin_cli->FechaMaxSistFin_[0]->row)) echo '&#013;&#010;';
										}
										echo '</FECHA_MAXIMA_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
									}	

									if(!empty($estado_fin_cli->SistFinEvolucionDeuda_[0]))
									{
										echo '<EVOLUCION_DEUDA_SISTEMA_FINANCIERO>&#013;&#010;';
										$contadorRecC29 = 0;
										foreach ($estado_fin_cli->SistFinEvolucionDeuda_[0]->row as $recEDSF) 
										{							
											$contadorRecC29++;
											if(!empty($recEDSF->entidad)) echo '	<ENTIDAD> '.$recEDSF->entidad.' </ENTIDAD>&#013;&#010;';
											if(!empty($recEDSF->m1)) echo '	<MES1> '.$recEDSF->m1.' </MES1>&#013;&#010;';
											if(!empty($recEDSF->m2)) echo '	<MES2> '.$recEDSF->m2.' </MES2>&#013;&#010;';
											if(!empty($recEDSF->m3)) echo '	<MES3> '.$recEDSF->m3.' </MES3>&#013;&#010;';
											if(!empty($recEDSF->m4)) echo '	<MES4> '.$recEDSF->m4.' </MES4>&#013;&#010;';
											if(!empty($recEDSF->m5)) echo '	<MES5> '.$recEDSF->m5.' </MES5>&#013;&#010;';
											if(!empty($recEDSF->m6)) echo '	<MES6> '.$recEDSF->m6.' </MES6>&#013;&#010;';
											if(!empty($recEDSF->m7)) echo '	<MES7> '.$recEDSF->m7.' </MES7>&#013;&#010;';
											if(!empty($recEDSF->m8)) echo '	<MES8> '.$recEDSF->m8.' </MES8>&#013;&#010;';
											if(!empty($recEDSF->m9)) echo '	<MES9> '.$recEDSF->m9.' </MES9>&#013;&#010;';
											if(!empty($recEDSF->m10)) echo '	<MES10> '.$recEDSF->m10.' </MES10>&#013;&#010;';
											if(!empty($recEDSF->m11)) echo '	<MES11> '.$recEDSF->m11.' </MES11>&#013;&#010;';
											if(!empty($recEDSF->m12)) echo '	<MES12> '.$recEDSF->m12.' </MES12>&#013;&#010;';
											if(!empty($recEDSF->m13)) echo '	<MES13> '.$recEDSF->m13.' </MES13>&#013;&#010;';
											if(!empty($recEDSF->m14)) echo '	<MES14> '.$recEDSF->m14.' </MES14>&#013;&#010;';
											if(!empty($recEDSF->m15)) echo '	<MES15> '.$recEDSF->m15.' </MES15>&#013;&#010;';
											if(!empty($recEDSF->m16)) echo '	<MES16> '.$recEDSF->m16.' </MES16>&#013;&#010;';
											if(!empty($recEDSF->m17)) echo '	<MES17> '.$recEDSF->m17.' </MES17>&#013;&#010;';
											if(!empty($recEDSF->m18)) echo '	<MES18> '.$recEDSF->m18.' </MES18>&#013;&#010;';
											if(!empty($recEDSF->m19)) echo '	<MES19> '.$recEDSF->m19.' </MES19>&#013;&#010;';
											if(!empty($recEDSF->m20)) echo '	<MES20> '.$recEDSF->m20.' </MES20>&#013;&#010;';
											if(!empty($recEDSF->m21)) echo '	<MES21> '.$recEDSF->m21.' </MES21>&#013;&#010;';
											if(!empty($recEDSF->m22)) echo '	<MES22> '.$recEDSF->m22.' </MES22>&#013;&#010;';
											if(!empty($recEDSF->m23)) echo '	<MES23> '.$recEDSF->m23.' </MES23>&#013;&#010;';
											if(!empty($recEDSF->m24)) echo '	<MES24> '.$recEDSF->m24.' </MES24>&#013;&#010;';									
											if(!empty($recEDSF->deuda_actual)) echo '	<DEUDA_ACTUAL> '.$recEDSF->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
											if(!empty($recEDSF->monto_maximo)) echo '	<MONTO_MAXIMO> '.$recEDSF->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
											if(!empty($recEDSF->bco)) echo '	<BANCO> '.$recEDSF->bco.' </BANCO>&#013;&#010;';
											
											if(count($estado_fin_cli->SistFinEvolucionDeuda_[0]->row) > 1 && $contadorRecC29 < count($estado_fin_cli->SistFinEvolucionDeuda_[0]->row)) echo '&#013;&#010;';
										}
										echo '</EVOLUCION_DEUDA_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]))
									{
										echo '<EVOLUCION_DEUDA_PROPIO_SISTEMA_FINANCIERO>&#013;&#010;';
										$contadorRecC30 = 0;
										foreach ($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row as $recEDPSF) 
										{							
											$contadorRecC30++;
											if(!empty($recEDPSF->entidad)) echo '	<ENTIDAD> '.$recEDPSF->entidad.' </ENTIDAD>&#013;&#010;';
											if(!empty($recEDPSF->m1)) echo '	<MES1> '.$recEDPSF->m1.' </MES1>&#013;&#010;';
											if(!empty($recEDPSF->m2)) echo '	<MES2> '.$recEDPSF->m2.' </MES2>&#013;&#010;';
											if(!empty($recEDPSF->m3)) echo '	<MES3> '.$recEDPSF->m3.' </MES3>&#013;&#010;';
											if(!empty($recEDPSF->m4)) echo '	<MES4> '.$recEDPSF->m4.' </MES4>&#013;&#010;';
											if(!empty($recEDPSF->m5)) echo '	<MES5> '.$recEDPSF->m5.' </MES5>&#013;&#010;';
											if(!empty($recEDPSF->m6)) echo '	<MES6> '.$recEDPSF->m6.' </MES6>&#013;&#010;';
											if(!empty($recEDPSF->m7)) echo '	<MES7> '.$recEDPSF->m7.' </MES7>&#013;&#010;';
											if(!empty($recEDPSF->m8)) echo '	<MES8> '.$recEDPSF->m8.' </MES8>&#013;&#010;';
											if(!empty($recEDPSF->m9)) echo '	<MES9> '.$recEDPSF->m9.' </MES9>&#013;&#010;';
											if(!empty($recEDPSF->m10)) echo '	<MES10> '.$recEDPSF->m10.' </MES10>&#013;&#010;';
											if(!empty($recEDPSF->m11)) echo '	<MES11> '.$recEDPSF->m11.' </MES11>&#013;&#010;';
											if(!empty($recEDPSF->m12)) echo '	<MES12> '.$recEDPSF->m12.' </MES12>&#013;&#010;';
											if(!empty($recEDPSF->m13)) echo '	<MES13> '.$recEDPSF->m13.' </MES13>&#013;&#010;';
											if(!empty($recEDPSF->m14)) echo '	<MES14> '.$recEDPSF->m14.' </MES14>&#013;&#010;';
											if(!empty($recEDPSF->m15)) echo '	<MES15> '.$recEDPSF->m15.' </MES15>&#013;&#010;';
											if(!empty($recEDPSF->m16)) echo '	<MES16> '.$recEDPSF->m16.' </MES16>&#013;&#010;';
											if(!empty($recEDPSF->m17)) echo '	<MES17> '.$recEDPSF->m17.' </MES17>&#013;&#010;';
											if(!empty($recEDPSF->m18)) echo '	<MES18> '.$recEDPSF->m18.' </MES18>&#013;&#010;';
											if(!empty($recEDPSF->m19)) echo '	<MES19> '.$recEDPSF->m19.' </MES19>&#013;&#010;';
											if(!empty($recEDPSF->m20)) echo '	<MES20> '.$recEDPSF->m20.' </MES20>&#013;&#010;';
											if(!empty($recEDPSF->m21)) echo '	<MES21> '.$recEDPSF->m21.' </MES21>&#013;&#010;';
											if(!empty($recEDPSF->m22)) echo '	<MES22> '.$recEDPSF->m22.' </MES22>&#013;&#010;';
											if(!empty($recEDPSF->m23)) echo '	<MES23> '.$recEDPSF->m23.' </MES23>&#013;&#010;';
											if(!empty($recEDPSF->m24)) echo '	<MES24> '.$recEDPSF->m24.' </MES24>&#013;&#010;';									
											if(!empty($recEDPSF->deuda_actual)) echo '	<DEUDA_ACTUAL> '.$recEDPSF->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
											if(!empty($recEDPSF->monto_maximo)) echo '	<MONTO_MAXIMO> '.$recEDPSF->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
											if(!empty($recEDPSF->bco)) echo '	<BANCO> '.$recEDPSF->bco.' </BANCO>&#013;&#010;';
											
											if(count($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row) > 1 && $contadorRecC30 < count($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row)) echo '&#013;&#010;';
										}
										echo '</EVOLUCION_DEUDA_PROPIO_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
									}							
									
									if(!empty($estado_fin_cli->Bcra_Ultimas_Situaciones[0]))
									{
										echo '<BANCO_CENTRAL_ULTIMAS_SITUACIONES>&#013;&#010;';
										$contadorRecC31 = 0;
										foreach ($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row as $recBCUS) 
										{							
											$contadorRecC31++;
											if(!empty($recBCUS->entidad)) echo '	<ENTIDAD> '.$recBCUS->entidad.' </ENTIDAD>&#013;&#010;';
											if(!empty($recBCUS->periodo)) echo '	<PERIODO> '.$recBCUS->periodo.' </PERIODO>&#013;&#010;';
											if(!empty($recBCUS->situacion)) echo '	<SITUACION> '.$recBCUS->situacion.' </SITUACION>&#013;&#010;';
											if(!empty($recBCUS->monto)) echo '	<MONTO> '.$recBCUS->monto.' </MONTO>&#013;&#010;';
											
											if(count($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row) > 1 && $contadorRecC31 < count($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row)) echo '&#013;&#010;';
										}
										echo '</BANCO_CENTRAL_ULTIMAS_SITUACIONES>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->Bcra_Situaciones_Vigente[0]))
									{
										echo '<BANCO_CENTRAL_SITUACIONES_VIGENTES>&#013;&#010;';
										$contadorRecC32 = 0;
										foreach ($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row as $recBCSV) 
										{							
											$contadorRecC32++;
											if(!empty($recBCSV->entidad)) echo '	<ENTIDAD> '.$recBCSV->entidad.' </ENTIDAD>&#013;&#010;';
											if(!empty($recBCSV->periodos)) echo '	<PERIODOS> '.$recBCSV->periodos.' </PERIODOS>&#013;&#010;';
											if(!empty($recBCSV->situacion)) echo '	<SITUACION> '.$recBCSV->situacion.' </SITUACION>&#013;&#010;';
											if(!empty($recBCSV->monto)) echo '	<MONTO> '.$recBCSV->monto.' </MONTO>&#013;&#010;';
											
											if(count($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row) > 1 && $contadorRecC32 < count($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row)) echo '&#013;&#010;';
										}
										echo '</BANCO_CENTRAL_SITUACIONES_VIGENTES>&#013;&#010;&#013;&#010;';
									}							
									
									if(!empty($estado_fin_cli->Tipo_Actividad[0]))
									{							
										echo '<TIPO_ACTIVIDAD>&#013;&#010;';
										$contadorRecC33 = 0;
										foreach ($estado_fin_cli->Tipo_Actividad[0]->row as $recTAC) 
										{								
											$contadorRecC33++;
											echo '	<TIPO_ACTIVIDAD> '.$recTAC->tipo_actividad.' </TIPO_ACTIVIDAD>&#013;&#010;';

											if(count($estado_fin_cli->Tipo_Actividad[0]->row) > 1 && $contadorRecC33 < count($estado_fin_cli->Tipo_Actividad[0]->row)) echo '&#013;&#010;';								
										}
										echo '</TIPO_ACTIVIDAD>&#013;&#010;&#013;&#010;';	
									}								
							
									if(!empty($estado_fin_cli->Consultas_Individual_[0]))
									{
										echo '<CONSULTAS_INDIVIDUALES>&#013;&#010;';
										$contadorRecC34 = 0;
										foreach ($estado_fin_cli->Consultas_Individual_[0]->row as $recCINDC) 
										{							
											$contadorRecC34++;
											if(!empty($recCINDC->dia)) echo '	<DIA> '.$recCINDC->dia.' </DIA>&#013;&#010;';
											if(!empty($recCINDC->semana)) echo '	<SEMANA> '.$recCINDC->semana.' </SEMANA>&#013;&#010;';
											if(!empty($recCINDC->mes)) echo '	<MES> '.$recCINDC->mes.' </MES>&#013;&#010;';
											if(!empty($recCINDC->m24)) echo '	<M24> '.$recCINDC->m24.' </M24>&#013;&#010;';
											
											if(count($estado_fin_cli->Consultas_Individual_[0]->row) > 1 && $contadorRecC34 < count($estado_fin_cli->Consultas_Individual_[0]->row)) echo '&#013;&#010;';
										}
										echo '</CONSULTAS_INDIVIDUALES>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->Consultas_Grupo_[0]))
									{
										echo '<CONSULTAS_GRUPO>&#013;&#010;';
										$contadorRecC35 = 0;
										foreach ($estado_fin_cli->Consultas_Grupo_[0]->row as $recCGRUC) 
										{							
											$contadorRecC35++;
											if(!empty($recCGRUC->dia)) echo '	<DIA> '.$recCGRUC->dia.' </DIA>&#013;&#010;';
											if(!empty($recCGRUC->semana)) echo '	<SEMANA> '.$recCGRUC->semana.' </SEMANA>&#013;&#010;';
											if(!empty($recCGRUC->mes)) echo '	<MES> '.$recCGRUC->mes.' </MES>&#013;&#010;';
											if(!empty($recCGRUC->m24)) echo '	<M24> '.$recCGRUC->m24.' </M24>&#013;&#010;';
											
											if(count($estado_fin_cli->Consultas_Grupo_[0]->row) > 1 && $contadorRecC35 < count($estado_fin_cli->Consultas_Grupo_[0]->row)) echo '&#013;&#010;';
										}
										echo '</CONSULTAS_GRUPO>&#013;&#010;&#013;&#010;';
									}	

									if(!empty($estado_fin_cli->CONSULTADO_POR_[0]))
									{
										echo '<CONSULTADO_POR>&#013;&#010;';
										$contadorRecC36 = 0;
										foreach ($estado_fin_cli->CONSULTADO_POR_[0]->row as $recCCONP) 
										{							
											$contadorRecC36++;
											if(!empty($recCCONP->fecha)) echo '	<FECHA> '.$recCCONP->fecha.' </FECHA>&#013;&#010;';
											if(!empty($recCCONP->consultante)) echo '	<CONSULTANTE> '.$recCCONP->consultante.' </CONSULTANTE>&#013;&#010;';
											
											if(count($estado_fin_cli->CONSULTADO_POR_[0]->row) > 1 && $contadorRecC36 < count($estado_fin_cli->CONSULTADO_POR_[0]->row)) echo '&#013;&#010;';
										}
										echo '</CONSULTADO_POR>&#013;&#010;&#013;&#010;';
									}							
							
									if(!empty($estado_fin_cli->Independientes_[0]))
									{
										echo '<INDEPENDIENTES>&#013;&#010;';
										$contadorRecC37 = 0;
										foreach ($estado_fin_cli->Independientes_[0]->row as $recCONIND) 
										{							
											$contadorRecC37++;
											if(!empty($recCONIND->inscripto)) echo '	<INSCRIPTO> '.$recCONIND->inscripto.' </INSCRIPTO>&#013;&#010;';
											if(!empty($recCONIND->categoria)) echo '	<CATEGORIA> '.$recCONIND->categoria.' </CATEGORIA>&#013;&#010;';
											if(!empty($recCONIND->cuit)) echo '	<CUIT> '.$recCONIND->cuit.' </CUIT>&#013;&#010;';
											if(!empty($recCONIND->documento)) echo '	<DOCUMENTO> '.$recCONIND->documento.' </DOCUMENTO>&#013;&#010;';
											if(!empty($recCONIND->nombre)) echo '	<NOMBRE> '.$recCONIND->nombre.' </NOMBRE>&#013;&#010;';
											if(!empty($recCONIND->domicilio)) echo '	<DOMICILIO> '.$recCONIND->domicilio.' </DOMICILIO>&#013;&#010;';
											if(!empty($recCONIND->cod_postal)) echo '	<CODIGO_POSTAL> '.$recCONIND->cod_postal.' </CODIGO_POSTAL>&#013;&#010;';
											if(!empty($recCONIND->localidad)) echo '	<LOCALIDAD> '.$recCONIND->localidad.' </LOCALIDAD>&#013;&#010;';
											if(!empty($recCONIND->provincia)) echo '	<PROVINCIA> '.$recCONIND->provincia.' </PROVINCIA>&#013;&#010;';
											if(!empty($recCONIND->fecha_nac)) echo '	<FECHA_NACIMIENTO> '.$recCONIND->fecha_nac.' </FECHA_NACIMIENTO>&#013;&#010;';
											if(!empty($recCONIND->entidad)) echo '	<ENTIDAD> '.$recCONIND->entidad.' </ENTIDAD>&#013;&#010;';
											if(!empty($recCONIND->actividad)) echo '	<ACTIVIDAD> '.$recCONIND->actividad.' </ACTIVIDAD>&#013;&#010;';
											if(!empty($recCONIND->periodo)) echo '	<PERIODO> '.$recCONIND->periodo.' </PERIODO>&#013;&#010;';
											if(!empty($recCONIND->monto)) echo '	<MONTO> '.$recCONIND->monto.' </MONTO>&#013;&#010;';									
											
											if(count($estado_fin_cli->Independientes_[0]->row) > 1 && $contadorRecC37 < count($estado_fin_cli->Independientes_[0]->row)) echo '&#013;&#010;';
										}
										echo '</INDEPENDIENTES>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->Indep_apo_[0]))
									{
										echo '<APORTES_INDEPENDIENTE>&#013;&#010;';
										$contadorRecC38 = 0;
										foreach ($estado_fin_cli->Indep_apo_[0]->row as $recAPCI) 
										{							
											$contadorRecC38++;
											if(!empty($recAPCI->cuit)) echo '	<CUIT> '.$recAPCI->cuit.' </CUIT>&#013;&#010;';
											if(!empty($recAPCI->periodo)) echo '	<PERIODO> '.$recAPCI->periodo.' </PERIODO>&#013;&#010;';
											if(!empty($recAPCI->imp_pago)) echo '	<IMPORTE> '.$recAPCI->imp_pago.' </IMPORTE>&#013;&#010;';
											if(!empty($recAPCI->impuesto)) echo '	<IMPUESTO> '.$recAPCI->impuesto.' </IMPUESTO>&#013;&#010;';
											
											if(count($estado_fin_cli->Indep_apo_[0]->row) > 1 && $contadorRecC38 < count($estado_fin_cli->Indep_apo_[0]->row)) echo '&#013;&#010;';
										}
										echo '</APORTES_INDEPENDIENTE>&#013;&#010;&#013;&#010;';
									}							
									
									if(!empty($estado_fin_cli->Propiedades_[0]))
									{
										echo '<PROPIEDADES>&#013;&#010;';
										$contadorRecC39 = 0;
										foreach ($estado_fin_cli->Propiedades_[0]->row as $recIPROPC) 
										{							
											$contadorRecC39++;
											if(!empty($recIPROPC->titular)) echo '	<TITULAR> '.$recIPROPC->titular.' </TITULAR>&#013;&#010;';
											if(!empty($recIPROPC->folio)) echo '	<FOLIO> '.$recIPROPC->folio.' </FOLIO>&#013;&#010;';
											if(!empty($recIPROPC->ano_inscripcion)) echo '	<AÑO_INSCRIPCION> '.$recIPROPC->ano_inscripcion.' </AÑO_INSCRIPCION>&#013;&#010;';
											if(!empty($recIPROPC->ph)) echo '	<PH> '.$recIPROPC->ph.' </PH>&#013;&#010;';
											if(!empty($recIPROPC->condom)) echo '	<CONDOM> '.$recIPROPC->condom.' </CONDOM>&#013;&#010;';
											if(!empty($recIPROPC->codigo_departamento)) echo '	<CODIGO_DEPARTAMENTO> '.$recIPROPC->codigo_departamento.' </CODIGO_DEPARTAMENTO>&#013;&#010;';
											if(!empty($recIPROPC->pedania)) echo '	<PEDANIA> '.$recIPROPC->pedania.' </PEDANIA>&#013;&#010;';
											if(!empty($recIPROPC->pueblo)) echo '	<PUEBLO> '.$recIPROPC->pueblo.' </PUEBLO>&#013;&#010;';
											if(!empty($recIPROPC->circuncripcion)) echo '	<CIRCUNCRIPCION> '.$recIPROPC->circuncripcion.' </CIRCUNCRIPCION>&#013;&#010;';
											if(!empty($recIPROPC->seccion)) echo '	<SECCION> '.$recIPROPC->seccion.' </SECCION>&#013;&#010;';
											if(!empty($recIPROPC->manzana)) echo '	<MANZANA> '.$recIPROPC->manzana.' </MANZANA>&#013;&#010;';
											if(!empty($recIPROPC->parcela)) echo '	<PARCELA> '.$recIPROPC->parcela.' </PARCELA>&#013;&#010;';
											if(!empty($recIPROPC->escrituro)) echo '	<ESCRITURO> '.$recIPROPC->escrituro.' </ESCRITURO>&#013;&#010;';
											
											if(count($estado_fin_cli->Propiedades_[0]->row) > 1 && $contadorRecC39 < count($estado_fin_cli->Propiedades_[0]->row)) echo '&#013;&#010;';
										}
										echo '</PROPIEDADES>&#013;&#010;&#013;&#010;';
									}
									echo '						</textarea>';
									echo '					</div>';		
									echo '				</div>';									
									echo '				<div class="form-group form-inline"><hr />';
									echo '					<label class="control-label" for="usuariosupervisorn30">'.translate('Lbl_User_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
									echo '					<div class="form-group" id="usuariosupervisorn30">';
									echo '						<input title="'.translate('Msg_User_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="usuariosupervisorn30i" name="usuariosupervisorn30i" type="text" maxlength="50" />';
									echo '					</div>';
									echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="passwordsupervisorn30">'.translate('Lbl_Password_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
									echo '					<div class="form-group" id="passwordsupervisorn30">';
									echo '						<input title="'.translate('Msg_Password_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="passwordsupervisorn30i" name="passwordsupervisorn30i" type="password" maxlength="128" />';
									echo '					</div>';		
									echo '				</div>';
									$estado_activa_supervisor = 1;
								}
								else
								{
									$estado_fin_cli = new SimpleXMLElement($resultado_finan_cli_final);
									echo translate('Msg_Validation_Credit_Status_Client_OK',$GLOBALS['lang']).'=::=::=::'.$tokenECF.'=:=:=:';
									echo '<div class="panel-group">';				
									echo '	<div class="panel panel-default">';
									echo '		<div id="panel-title-header" class="panel-heading">';
									echo '			<h3 class="panel-title">'.translate('Lbl_Result_Financial_Statement_Client',$GLOBALS['lang']).'</h3>';
									echo ' 		</div>';
									echo '		<div class="panel-body">';
									echo '			<form id="formulariocefcm" role="form" onsubmit="buscarTextoEstadoFinancieroM(); return false;">';		
									echo '				<div class="form-group form-inline">';
									echo '					<div class="form-group" id="buscartextoestadocrediticioclientem">';
									echo '						<input class="form-control input-sm" id="buscartextoestadocrediticioclientemi" name="buscartextoestadocrediticioclientemi" type="text" maxlength="150" />';
									echo '						&nbsp;<button type="button" class="btn" id="btnBuscarEstadoFM" name="btnBuscarEstadoFM" title="'.translate('Lbl_Search_Text_Statement_Client',$GLOBALS['lang']).'" onclick="buscarTextoEstadoFinancieroM();"><i class="fas fa-search"></i></button>';									
									echo '					</div>';
									echo '				</div>';
									echo '				<div class="form-group form-inline">';					
									echo '					<div class="form-group" id="resultadoestadofinancieroclientem">';
									echo '						<textarea rows="3" cols="67" class="form-control input-sm" id="resultadoestadofinancieroclientemi" name="resultadoestadofinancieroclientemi">';
									echo '<EXISTENCIA_FISICA_ENTIDAD>&#013;&#010;';
									$contadorRecC1 = 0;
									foreach ($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row as $recEFC) 
									{
										$contadorRecC1++;
										if(!empty($recEFC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recEFC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
										if(!empty($recEFC->sexo)) echo '	<SEXO> '.$recEFC->sexo.' </SEXO>&#013;&#010;';
										if(!empty($recEFC->tipo_docu)) echo '	<TIPO_DOCUMENTO> '.$recEFC->tipo_docu.' </TIPO_DOCUMENTO>&#013;&#010;';
										if(!empty($recEFC->nume_doc)) echo '	<DOCUMENTO> '.$recEFC->nume_doc.' </DOCUMENTO>&#013;&#010;';
										if(!empty($recEFC->clase)) echo '	<CLASE> '.$recEFC->clase.' </CLASE>&#013;&#010;';
										if(!empty($recEFC->edad)) echo '	<EDAD> '.$recEFC->edad.' </EDAD>&#013;&#010;';
										if(!empty($recEFC->ocupacion)) echo '	<OCUPACION> '.$recEFC->ocupacion.' </OCUPACION>&#013;&#010;';
										if(!empty($recEFC->cdi_codigo_de_identificacion)) echo '	<CUIT_CUIL> '.$recEFC->cdi_codigo_de_identificacion.' </CUIT_CUIL>&#013;&#010;';								
										if(!empty($recEFC->fecha_nacimiento)) echo '	<FECHA_NACIMIENTO> '.$recEFC->fecha_nacimiento.' </FECHA_NACIMIENTO>&#013;&#010;';							
										if(!empty($recEFC->direc_calle)) echo '	<DIRECCION_CALLE> '.$recEFC->direc_calle.' </DIRECCION_CALLE>&#013;&#010;';							
										if(!empty($recEFC->localidad)) echo '	<LOCALIDAD> '.$recEFC->localidad.' </LOCALIDAD>&#013;&#010;';
										if(!empty($recEFC->departamento)) echo '	<DEPARTAMENTO> '.$recEFC->departamento.' </DEPARTAMENTO>&#013;&#010;';								
										if(!empty($recEFC->codigo_postal)) echo '	<CODIGO_POSTAL> '.$recEFC->codigo_postal.' </CODIGO_POSTAL>&#013;&#010;';							
										if(!empty($recEFC->provincia)) echo '	<PROVINCIA> '.$recEFC->provincia.' </PROVINCIA>&#013;&#010;';							
										if(!empty($recEFC->apellido_materno)) echo '	<APELLIDO_MATERNO> '.$recEFC->apellido_materno.' </APELLIDO_MATERNO>&#013;&#010;';
										if(!empty($recEFC->estado_civil)) echo '	<ESTADO_CIVIL> '.$recEFC->estado_civil.' </ESTADO_CIVIL>&#013;&#010;';								
										if(!empty($recEFC->fallecido)) echo '	<FALLECIDO> '.$recEFC->fallecido.' </FALLECIDO>&#013;&#010;';
										
										if(count($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row) > 1 && $contadorRecC1 < count($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row)) echo '&#013;&#010;';
									}							
									echo '</EXISTENCIA_FISICA_ENTIDAD>&#013;&#010;&#013;&#010;';
									
									if(!empty($estado_fin_cli->Score_[0]))
									{
										echo '<SCORE>&#013;&#010;';
										$contadorRecC2 = 0;
										foreach ($estado_fin_cli->Score_[0]->row as $recPIC) 
										{							
											$contadorRecC2++;
											if(!empty($recPIC->score)) echo '	<SCORE> '.$recPIC->score.' </SCORE>&#013;&#010;';
											
											if(count($estado_fin_cli->Score_[0]->row) > 1 && $contadorRecC2 < count($estado_fin_cli->Score_[0]->row)) echo '&#013;&#010;';
										}
										echo '</SCORE>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->Domi_xy_[0]))
									{
										echo '<DOMICILIO_XY>&#013;&#010;';
										$contadorRecC3 = 0;
										foreach ($estado_fin_cli->Domi_xy_[0]->row as $recTJQEC) 
										{								
											$contadorRecC3++;
											if(!empty($recTJQEC->nprovincia)) echo '	<N_PROVINCIA> '.$recTJQEC->nprovincia.' </N_PROVINCIA>&#013;&#010;';
											if(!empty($recTJQEC->npartido)) echo '	<N_PARTIDO> '.$recTJQEC->npartido.' </N_PARTIDO>&#013;&#010;';
											if(!empty($recTJQEC->nlocalidad)) echo '	<N_LOCALIDAD> '.$recTJQEC->nlocalidad.' </N_LOCALIDAD>&#013;&#010;';
											if(!empty($recTJQEC->nbarrio)) echo '	<N_BARRIO> '.$recTJQEC->nbarrio.' </N_BARRIO>&#013;&#010;';
											if(!empty($recTJQEC->calle)) echo '	<CALLE> '.$recTJQEC->calle.' </CALLE>&#013;&#010;';
											if(!empty($recTJQEC->ncp)) echo '	<NCP> '.$recTJQEC->ncp.' </NCP>&#013;&#010;';
											if(!empty($recTJQEC->geocordenadas)) echo '	<GEOCORDENADAS> '.$recTJQEC->geocordenadas.' </GEOCORDENADAS>&#013;&#010;';
											if(count($estado_fin_cli->Domi_xy_[0]->row) > 1 && $contadorRecC3 < count($estado_fin_cli->Domi_xy_[0]->row)) echo '&#013;&#010;';
										}
										echo '</DOMICILIO_XY>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->Situacion_BCRA[0]))
									{							
										echo '<SITUACION_BCRA>&#013;&#010;';
										$contadorRecC4 = 0;
										foreach ($estado_fin_cli->Situacion_BCRA[0]->row as $recDSF6M) 
										{								
											$contadorRecC4++;
											if(!empty($recDSF6M->entidad)) echo '	<ENTIDAD> '.$recDSF6M->entidad.' </ENTIDAD>&#013;&#010;';
											if(!empty($recDSF6M->fecha_sit)) echo '	<FECHA> '.$recDSF6M->fecha_sit.' </FECHA>&#013;&#010;';									
											if(!empty($recDSF6M->situacion)) echo '	<SITUACION> '.$recDSF6M->situacion.' </SITUACION>&#013;&#010;';
											if(!empty($recDSF6M->deuda_total))  echo '	<DEUDA_TOTAL> '.$recDSF6M->deuda_total.' </DEUDA_TOTAL>&#013;&#010;';

											if(count($estado_fin_cli->Situacion_BCRA[0]->row) > 1 && $contadorRecC4 < count($estado_fin_cli->Situacion_BCRA[0]->row)) echo '&#013;&#010;';								
										}
										echo '</SITUACION_BCRA>&#013;&#010;&#013;&#010;';	
									}
									
									if(!empty($estado_fin_cli->FraudesTarjeta_[0]))
									{
										echo '<FRAUDES_TARJETA>&#013;&#010;';
										$contadorRecC5 = 0;
										foreach ($estado_fin_cli->FraudesTarjeta_[0]->row as $recFTs) 
										{							
											$contadorRecC5++;
											if(!empty($recFTs->fraude)) echo '	<FRAUDE> '.$recFTs->fraude.' </FRAUDE>&#013;&#010;';
											
											if(count($estado_fin_cli->FraudesTarjeta_[0]->row) > 1 && $contadorRecC5 < count($estado_fin_cli->FraudesTarjeta_[0]->row)) echo '&#013;&#010;';
										}
										echo '</FRAUDES_TARJETA>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->CapacidadEndeudamiento_[0]))
									{
										echo '<CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;';
										$contadorRecC6 = 0;
										foreach ($estado_fin_cli->CapacidadEndeudamiento_[0]->row as $recCPEC) 
										{							
											$contadorRecC6++;
											if(!empty($recCPEC->capacidad_endeudamiento)) echo '	<CAPACIDAD_ENDEUDAMIENTO> '.$recCPEC->capacidad_endeudamiento.' </CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;';
											
											if(count($estado_fin_cli->CapacidadEndeudamiento_[0]->row) > 1 && $contadorRecC6 < count($estado_fin_cli->CapacidadEndeudamiento_[0]->row)) echo '&#013;&#010;';
										}
										echo '</CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;&#013;&#010;';
									}							
									
									if(!empty($estado_fin_cli->Ooss_personas_[0]))
									{							
										echo '<OBRA_SOCIAL_PERSONAS>&#013;&#010;';
										$contadorRecC7 = 0;
										foreach ($estado_fin_cli->Ooss_personas_[0]->row as $recDSF12M) 
										{								
											$contadorRecC7++;
											if(!empty($recDSF12M->cuil)) echo '	<CUIL> '.$recDSF12M->cuil.' </CUIL>&#013;&#010;';
											if(!empty($recDSF12M->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recDSF12M->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
											if(!empty($recDSF12M->nume_docu)) echo '	<DOCUMENTO> '.$recDSF12M->nume_docu.' </DOCUMENTO>&#013;&#010;';
											if(!empty($recDSF12M->sexo)) echo '	<SEXO> '.$recDSF12M->sexo.' </SEXO>&#013;&#010;';
											if(!empty($recDSF12M->domicilio)) echo '	<DOMICILIO> '.$recDSF12M->domicilio.' </DOMICILIO>&#013;&#010;';
											if(!empty($recDSF12M->localidad)) echo '	<LOCALIDAD> '.$recDSF12M->localidad.' </LOCALIDAD>&#013;&#010;';
											if(!empty($recDSF12M->provincia)) echo '	<PROVINCIA> '.$recDSF12M->provincia.' </PROVINCIA>&#013;&#010;';
											if(!empty($recDSF12M->cod_postal)) echo '	<CODIGO_POSTAL> '.$recDSF12M->cod_postal.' </CODIGO_POSTAL>&#013;&#010;';									

											if(count($estado_fin_cli->Ooss_personas_[0]->row) > 1 && $contadorRecC7 < count($estado_fin_cli->Ooss_personas_[0]->row)) echo '&#013;&#010;';								
										}
										echo '</OBRA_SOCIAL_PERSONAS>&#013;&#010;&#013;&#010;';	
									}
									
									if(!empty($estado_fin_cli->Obra_Social_Rel_[0]))
									{							
										echo '<OBRA_SOCIAL_RELACION>&#013;&#010;';
										$contadorRecC8 = 0;
										foreach ($estado_fin_cli->Obra_Social_Rel_[0]->row as $recDSF24M) 
										{								
											$contadorRecC8++;
											if(!empty($recDSF24M->codiobra)) echo '	<CODIGO> '.$recDSF24M->codiobra.' </CODIGO>&#013;&#010;';
											if(!empty($recDSF24M->descripcion_ooss)) echo '	<DESCRIPCION> '.$recDSF24M->descripcion_ooss.' </DESCRIPCION>&#013;&#010;';
											if(!empty($recDSF24M->sigla_ooss)) echo '	<SIGLA> '.$recDSF24M->sigla_ooss.' </SIGLA>&#013;&#010;';
											if(!empty($recDSF24M->cobertura)) echo '	<COBERTURA> '.$recDSF24M->cobertura.' </COBERTURA>&#013;&#010;';
											if(!empty($recDSF24M->conyugue)) echo '	<CONYUGUE> '.$recDSF24M->conyugue.' </CONYUGUE>&#013;&#010;';
											if(!empty($recDSF24M->hijos)) echo '	<HIJOS> '.$recDSF24M->hijos.' </HIJOS>&#013;&#010;';

											if(count($estado_fin_cli->Obra_Social_Rel_[0]->row) > 1 && $contadorRecC8 < count($estado_fin_cli->Obra_Social_Rel_[0]->row)) echo '&#013;&#010;';								
										}
										echo '</OBRA_SOCIAL_RELACION>&#013;&#010;&#013;&#010;';	
									}
									
									if(!empty($estado_fin_cli->Domicilio_Empresa_trabaja_[0]))
									{
										echo '<DOMICILIO_LABORAL>&#013;&#010;';
										$contadorRecC9 = 0;
										foreach ($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row as $recRDC) 
										{								
											$contadorRecC9++;
											if(!empty($recRDC->direccion)) echo '	<DIRECCION> '.$recRDC->ult_periodo.' </DIRECCION>&#013;&#010;';
											if(!empty($recRDC->localidad)) echo '	<LOCALIDAD> '.$recRDC->alta_trabajo_ultimo.' </LOCALIDAD>&#013;&#010;';
											if(!empty($recRDC->provincia)) echo '	<PROVINCIA> '.$recRDC->cuit.' </PROVINCIA>&#013;&#010;';
											if(!empty($recRDC->cp)) echo '	<CODIGO_POSTAL> '.$recRDC->razon_social.' </CODIGO_POSTAL>&#013;&#010;';

											if(count($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row) > 1 && $contadorRecC9 < count($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row)) echo '&#013;&#010;';								
										}
										echo '</DOMICILIO LABORAL>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->domicilio_otros_[0]))
									{							
										echo '<OTROS_DOMICILIOS>&#013;&#010;';
										$contadorRecC10 = 0;
										foreach ($estado_fin_cli->domicilio_otros_[0]->row as $recCIAC) 
										{								
											$contadorRecC10++;
											if(!empty($recCIAC->domicilio)) echo '	<DOMICILIO> '.$recCIAC->domicilio.' </DOMICILIO>&#013;&#010;';
											if(!empty($recCIAC->localidad)) echo '	<LOCALIDAD> '.$recCIAC->localidad.' </LOCALIDAD>&#013;&#010;';
											if(!empty($recCIAC->cp)) echo '	<CP> '.$recCIAC->cp.' </CP>&#013;&#010;';
											if(!empty($recCIAC->provincia)) echo '	<PROVINCIA> '.$recCIAC->provincia.' </PROVINCIA>&#013;&#010;';

											if(count($estado_fin_cli->domicilio_otros_[0]->row) > 1 && $contadorRecC10 < count($estado_fin_cli->domicilio_otros_[0]->row)) echo '&#013;&#010;';								
										}
										echo '</OTROS_DOMICILIOS>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->Domicilios_Juicios_[0]))
									{
										echo '<DOMICILIOS_JUICIOS>&#013;&#010;';
										$contadorRecC11 = 0;
										foreach ($estado_fin_cli->Domicilios_Juicios_[0]->row as $recDJD) 
										{							
											$contadorRecC11++;
											if(!empty($recDJD->domicilio)) echo '	<DOMICILIO> '.$recDJD->domicilio.' </DOMICILIO>&#013;&#010;';
											
											if(count($estado_fin_cli->Domicilios_Juicios_[0]->row) > 1 && $contadorRecC11 < count($estado_fin_cli->Domicilios_Juicios_[0]->row)) echo '&#013;&#010;';
										}
										echo '</DOMICILIOS_JUICIOS>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->Padronempresasf_[0]))
									{							
										echo '<PADRON_EMPRESAS>&#013;&#010;';
										$contadorRecC12 = 0;
										foreach ($estado_fin_cli->Padronempresasf_[0]->row as $recPMC) 
										{								
											$contadorRecC12++;
											if(!empty($recPMC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recPMC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
											if(!empty($recPMC->cuit)) echo '	<CUIT> '.$recPMC->cuit.' </CUIT>&#013;&#010;';
											if(!empty($recPMC->domicilio_fiscal)) echo '	<DOMICILIO_FISCAL> '.$recPMC->domicilio_fiscal.' </DOMICILIO_FISCAL>&#013;&#010;';
											if(!empty($recPMC->provincia)) echo '	<PROVINCIA> '.$recPMC->provincia.' </PROVINCIA>&#013;&#010;';
											if(!empty($recPMC->actividad_especifica)) echo '	<ACTIVIDAD_ESPECIFICA> '.$recPMC->actividad_especifica.' </ACTIVIDAD_ESPECIFICA>&#013;&#010;';
											if(!empty($recPMC->categoria_autonomo)) echo '	<CATEGORIA_AUTONOMO> '.$recPMC->categoria_autonomo.' </CATEGORIA_AUTONOMO>&#013;&#010;';

											if(count($estado_fin_cli->Padronempresasf_[0]->row) > 1 && $contadorRecC12 < count($estado_fin_cli->Padronempresasf_[0]->row)) echo '&#013;&#010;';									
										}
										echo '</PADRON_EMPRESAS>&#013;&#010;&#013;&#010;';							
									}
									
									if(!empty($estado_fin_cli->Gran_contrib_[0]))
									{
										echo '<GRANDES_CONTRIBUYENTES>&#013;&#010;';
										$contadorRecC13 = 0;
										foreach ($estado_fin_cli->Gran_contrib_[0]->row as $recGCD) 
										{							
											$contadorRecC13++;
											if(!empty($recGCD->gran_contr)) echo '	<CONTRIBUYENTE> '.$recGCD->gran_contr.' </CONTRIBUYENTE>&#013;&#010;';
											
											if(count($estado_fin_cli->Gran_contrib_[0]->row) > 1 && $contadorRecC13 < count($estado_fin_cli->Gran_contrib_[0]->row)) echo '&#013;&#010;';
										}
										echo '</GRANDES_CONTRIBUYENTES>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->Domialter_[0]))
									{							
										echo '<DOMICILIOS_ALTERNATIVOS>&#013;&#010;';
										$contadorRecC14 = 0;
										foreach ($estado_fin_cli->Domialter_[0]->row as $recDMAC) 
										{								
											$contadorRecC14++;
											if(!empty($recDMAC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recDMAC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
											if(!empty($recDMAC->cuit)) echo '	<CUIT> '.$recDMAC->cuit.' </CUIT>&#013;&#010;';
											if(!empty($recDMAC->domicilio_fiscal)) echo '	<DOMICILIO_FISCAL> '.$recDMAC->domicilio_fiscal.' </DOMICILIO_FISCAL>&#013;&#010;';
											if(!empty($recDMAC->provincia)) echo '	<PROVINCIA> '.$recDMAC->provincia.' </PROVINCIA>&#013;&#010;';
											if(!empty($recDMAC->actividad_especifica)) echo '	<ACTIVIDAD_ESPECIFICA> '.$recDMAC->actividad_especifica.' </ACTIVIDAD_ESPECIFICA>&#013;&#010;';

											if(count($estado_fin_cli->Domialter_[0]->row) > 1 && $contadorRecC14 < count($estado_fin_cli->Domialter_[0]->row)) echo '&#013;&#010;';									
										}
										echo '</DOMICILIOS_ALTERNATIVOS>&#013;&#010;&#013;&#010;';							
									}
									
									if(!empty($estado_fin_cli->Nise_[0]))
									{
										echo '<NISE>&#013;&#010;';
										$contadorRecC15 = 0;
										foreach ($estado_fin_cli->Nise_[0]->row as $recINC) 
										{							
											$contadorRecC15++;
											if(!empty($recINC->radio)) echo '	<RADIO> '.$recINC->radio.' </RADIO>&#013;&#010;';
											if(!empty($recINC->nise)) echo '	<NISE> '.$recINC->nise.' </NISE>&#013;&#010;';
											if(!empty($recINC->periodo)) echo '	<PERIODO> '.$recINC->periodo.' </PERIODO>&#013;&#010;';
											
											if(count($estado_fin_cli->Nise_[0]->row) > 1 && $contadorRecC15 < count($estado_fin_cli->Nise_[0]->row)) echo '&#013;&#010;';
										}
										echo '</NISE>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->Const_inscrip_[0]))
									{							
										echo '<CONSTANCIAS_INSCRIPCION>&#013;&#010;';
										$contadorRecC16 = 0;
										foreach ($estado_fin_cli->Const_inscrip_[0]->row as $recCICR) 
										{								
											$contadorRecC16++;
											if(!empty($recCICR->descrip_imp_ganancias)) echo '	<DESCRIPCION_IMP_GANANCIAS> '.$recCICR->descrip_imp_ganancias.' </DESCRIPCION_IMP_GANANCIAS>&#013;&#010;';
											if(!empty($recCICR->descrip_imp_iva)) echo '	<DESCRIPCION_IMP_IVA> '.$recCICR->descrip_imp_iva.' </DESCRIPCION_IMP_IVA>&#013;&#010;';
											if(!empty($recCICR->descrip_monotributo)) echo '	<DESCRIPCION_MONOTRIBUTO> '.$recCICR->descrip_monotributo.' </DESCRIPCION_MONOTRIBUTO>&#013;&#010;';
											if(!empty($recCICR->descrip_integrante_soc)) echo '	<DESCRIPCION_INTEGRANTE_SOC> '.$recCICR->descrip_integrante_soc.' </DESCRIPCION_INTEGRANTE_SOC>&#013;&#010;';
											if(!empty($recCICR->descrip_empleador)) echo '	<DESCRIPCION_EMPLEADOR> '.$recCICR->descrip_empleador.' </DESCRIPCION_EMPLEADOR>&#013;&#010;';
											if(!empty($recCICR->periodo)) echo '	<PERIODO> '.$recCICR->periodo.' </PERIODO>&#013;&#010;';

											if(count($estado_fin_cli->Const_inscrip_[0]->row) > 1 && $contadorRecC16 < count($estado_fin_cli->Const_inscrip_[0]->row)) echo '&#013;&#010;';									
										}
										echo '</CONSTANCIAS_INSCRIPCION>&#013;&#010;&#013;&#010;';							
									}
									
									if(!empty($estado_fin_cli->Nbi_[0]))
									{
										echo '<NBI>&#013;&#010;';
										$contadorRecC17 = 0;
										foreach ($estado_fin_cli->Nbi_[0]->row as $recINBC) 
										{							
											$contadorRecC17++;
											if(!empty($recINBC->radio)) echo '	<RADIO> '.$recINBC->radio.' </RADIO>&#013;&#010;';
											if(!empty($recINBC->nbi)) echo '	<NBI> '.$recINBC->nbi.' </NBI>&#013;&#010;';
											if(!empty($recINBC->periodo)) echo '	<PERIODO> '.$recINBC->periodo.' </PERIODO>&#013;&#010;';
											
											if(count($estado_fin_cli->Nbi_[0]->row) > 1 && $contadorRecC17 < count($estado_fin_cli->Nbi_[0]->row)) echo '&#013;&#010;';
										}
										echo '</NBI>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->Iped_[0]))
									{
										echo '<IPED>&#013;&#010;';
										$contadorRecC18 = 0;
										foreach ($estado_fin_cli->Iped_[0]->row as $recIIPC) 
										{							
											$contadorRecC18++;
											if(!empty($recIIPC->radio)) echo '	<RADIO> '.$recIIPC->radio.' </RADIO>&#013;&#010;';
											if(!empty($recIIPC->iped)) echo '	<IPED> '.$recIIPC->iped.' </IPED>&#013;&#010;';
											if(!empty($recIIPC->periodo)) echo '	<PERIODO> '.$recIIPC->periodo.' </PERIODO>&#013;&#010;';
											
											if(count($estado_fin_cli->Iped_[0]->row) > 1 && $contadorRecC18 < count($estado_fin_cli->Iped_[0]->row)) echo '&#013;&#010;';
										}
										echo '</IPED>&#013;&#010;&#013;&#010;';
									}							

									if(!empty($estado_fin_cli->Ib_[0]))
									{
										echo '<IB>&#013;&#010;';
										$contadorRecC19 = 0;
										foreach ($estado_fin_cli->Ib_[0]->row as $recIIBC) 
										{							
											$contadorRecC19++;
											if(!empty($recIIBC->radio)) echo '	<RADIO> '.$recIIBC->radio.' </RADIO>&#013;&#010;';
											if(!empty($recIIBC->ib)) echo '	<IB> '.$recIIBC->ib.' </IB>&#013;&#010;';
											if(!empty($recIIBC->periodo)) echo '	<PERIODO> '.$recIIBC->periodo.' </PERIODO>&#013;&#010;';
											
											if(count($estado_fin_cli->Ib_[0]->row) > 1 && $contadorRecC19 < count($estado_fin_cli->Ib_[0]->row)) echo '&#013;&#010;';
										}
										echo '</IB>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->Rel_Dependencia_Trabajador_[0]))
									{
										echo '<RELEACION_DEPENDENCIA_TRABAJADOR>&#013;&#010;';
										$contadorRecC20 = 0;
										foreach ($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row as $recRDTC) 
										{							
											$contadorRecC20++;
											if(!empty($recRDTC->cuil)) echo '	<CUIL> '.$recRDTC->cuil.' </CUIL>&#013;&#010;';
											if(!empty($recRDTC->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recRDTC->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
											if(!empty($recRDTC->domicilio)) echo '	<DOMICILIO> '.$recRDTC->domicilio.' </DOMICILIO>&#013;&#010;';
											if(!empty($recRDTC->localidad)) echo '	<LOCALIDAD> '.$recRDTC->localidad.' </LOCALIDAD>&#013;&#010;';
											if(!empty($recRDTC->cp)) echo '	<CODIGO_POSTAL> '.$recRDTC->cp.' </CODIGO_POSTAL>&#013;&#010;';
											if(!empty($recRDTC->provincia)) echo '	<PROVINCIA> '.$recRDTC->provincia.' </PROVINCIA>&#013;&#010;';
											if(!empty($recRDTC->fecha_nac)) echo '	<FECHA_NACIMIENTO> '.$recRDTC->fecha_nac.' </FECHA_NACIMIENTO>&#013;&#010;';
											if(!empty($recRDTC->sexo)) echo '	<SEXO> '.$recRDTC->sexo.' </SEXO>&#013;&#010;';
											if(!empty($recRDTC->doc)) echo '	<DOCUMENTO> '.$recRDTC->doc.' </DOCUMENTO>&#013;&#010;';
											
											if(count($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row) > 1 && $contadorRecC20 < count($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row)) echo '&#013;&#010;';
										}
										echo '</RELEACION_DEPENDENCIA_TRABAJADOR>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->Rel_Dependencia_Empleador_[0]))
									{
										echo '<RELEACION_DEPENDENCIA_EMPLEADOR>&#013;&#010;';
										$contadorRecC21 = 0;
										foreach ($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row as $recRDEC) 
										{							
											$contadorRecC21++;
											if(!empty($recRDEC->cuit)) echo '	<CUIT> '.$recRDEC->cuit.' </CUIT>&#013;&#010;';
											if(!empty($recRDEC->razon)) echo '	<RAZON_SOCIAL> '.$recRDEC->razon.' </RAZON_SOCIAL>&#013;&#010;';
											if(!empty($recRDEC->domicilio)) echo '	<DOMICILIO> '.$recRDEC->domicilio.' </DOMICILIO>&#013;&#010;';
											if(!empty($recRDEC->localidad)) echo '	<LOCALIDAD> '.$recRDEC->localidad.' </LOCALIDAD>&#013;&#010;';
											if(!empty($recRDEC->cp)) echo '	<CODIGO_POSTAL> '.$recRDEC->cp.' </CODIGO_POSTAL>&#013;&#010;';
											if(!empty($recRDEC->provincia)) echo '	<PROVINCIA> '.$recRDEC->provincia.' </PROVINCIA>&#013;&#010;';
											
											if(count($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row) > 1 && $contadorRecC21 < count($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row)) echo '&#013;&#010;';
										}
										echo '</RELEACION_DEPENDENCIA_EMPLEADOR>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->Historia_[0]))
									{
										echo '<HISTORIA_EMPRESAS>&#013;&#010;';
										$contadorRecC22 = 0;
										foreach ($estado_fin_cli->Historia_[0]->row as $recHEC) 
										{							
											$contadorRecC22++;
											if(!empty($recHEC->cuit)) echo '	<CUIT> '.$recHEC->cuit.' </CUIT>&#013;&#010;';
											if(!empty($recHEC->razon)) echo '	<RAZON_SOCIAL> '.$recHEC->razon.' </RAZON_SOCIAL>&#013;&#010;';
											if(!empty($recHEC->desde)) echo '	<DESDE> '.$recHEC->desde.' </DESDE>&#013;&#010;';
											if(!empty($recHEC->hasta)) echo '	<HASTA> '.$recHEC->hasta.' </HASTA>&#013;&#010;';
											
											if(count($estado_fin_cli->Historia_[0]->row) > 1 && $contadorRecC22 < count($estado_fin_cli->Historia_[0]->row)) echo '&#013;&#010;';
										}
										echo '</HISTORIA_EMPRESAS>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->Sysem_[0]))
									{
										echo '<SYSEM>&#013;&#010;';
										$contadorRecC23 = 0;
										foreach ($estado_fin_cli->Sysem_[0]->row as $recSYC) 
										{							
											$contadorRecC23++;
											if(!empty($recSYC->cant_emp)) echo '	<CANTIDAD_EMPLEADOS> '.$recSYC->cant_emp.' </CANTIDAD_EMPLEADOS>&#013;&#010;';
											if(!empty($recSYC->cuit)) echo '	<CUIT> '.$recSYC->cuit.' </CUIT>&#013;&#010;';
											if(!empty($recSYC->razon)) echo '	<RAZON_SOCIAL> '.$recSYC->razon.' </RAZON_SOCIAL>&#013;&#010;';
											if(!empty($recSYC->domicilio)) echo '	<DOMICILIO> '.$recSYC->domicilio.' </DOMICILIO>&#013;&#010;';
											if(!empty($recSYC->localidad)) echo '	<LOCALIDAD> '.$recSYC->localidad.' </LOCALIDAD>&#013;&#010;';
											if(!empty($recSYC->cp)) echo '	<CODIGO_POSTAL> '.$recSYC->cp.' </CODIGO_POSTAL>&#013;&#010;';
											if(!empty($recSYC->provincia)) echo '	<PROVINCIA> '.$recSYC->provincia.' </PROVINCIA>&#013;&#010;';
											
											if(count($estado_fin_cli->Sysem_[0]->row) > 1 && $contadorRecC23 < count($estado_fin_cli->Sysem_[0]->row)) echo '&#013;&#010;';
										}
										echo '</SYSEM>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->Jyj_mensual_[0]))
									{
										echo '<SYSEM>&#013;&#010;';
										$contadorRecC24 = 0;
										foreach ($estado_fin_cli->Jyj_mensual_[0]->row as $recJYJC) 
										{							
											$contadorRecC24++;
											if(!empty($recJYJC->cuil)) echo '	<CUIL> '.$recJYJC->cuil.' </CUIL>&#013;&#010;';
											if(!empty($recJYJC->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recJYJC->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
											if(!empty($recJYJC->rango_socio_econom)) echo '	<RANGO_SOCIO_ECONOMICO> '.$recJYJC->rango_socio_econom.' </RANGO_SOCIO_ECONOMICO>&#013;&#010;';
											
											if(count($estado_fin_cli->Jyj_mensual_[0]->row) > 1 && $contadorRecC24 < count($estado_fin_cli->Jyj_mensual_[0]->row)) echo '&#013;&#010;';
										}
										echo '</SYSEM>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->Situacionlaboral_[0]))
									{
										echo '<SITUACION_LABORAL>&#013;&#010;';
										$contadorRecC25 = 0;
										foreach ($estado_fin_cli->Situacionlaboral_[0]->row as $recSLC) 
										{							
											$contadorRecC25++;
											if(!empty($recSLC->situacionlaboral)) echo '	<ESTADO_LABORAL> '.$recSLC->situacionlaboral.' </ESTADO_LABORAL>&#013;&#010;';
											
											if(count($estado_fin_cli->Situacionlaboral_[0]->row) > 1 && $contadorRecC25 < count($estado_fin_cli->Situacionlaboral_[0]->row)) echo '&#013;&#010;';
										}
										echo '</SITUACION_LABORAL>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->Producto_financieros_[0]))
									{
										echo '<PRODUCTO_FINANCIEROS>&#013;&#010;';
										$contadorRecC26 = 0;
										foreach ($estado_fin_cli->Producto_financieros_[0]->row as $recPFC) 
										{							
											$contadorRecC26++;
											if(!empty($recPFC->producto_financieros)) echo '	<PRODUCTO> '.$recPFC->producto_financieros.' </PRODUCTO>&#013;&#010;';
											
											if(count($estado_fin_cli->Producto_financieros_[0]->row) > 1 && $contadorRecC26 < count($estado_fin_cli->Producto_financieros_[0]->row)) echo '&#013;&#010;';
										}
										echo '</PRODUCTO_FINANCIEROS>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->Bancarizado_[0]))
									{
										echo '<BANCARIZADO>&#013;&#010;';
										$contadorRecC27 = 0;
										foreach ($estado_fin_cli->Bancarizado_[0]->row as $recEBC) 
										{							
											$contadorRecC27++;
											if(!empty($recEBC->bancarizado)) echo '	<ESTADO> '.$recEBC->bancarizado.' </ESTADO>&#013;&#010;';
											
											if(count($estado_fin_cli->Bancarizado_[0]->row) > 1 && $contadorRecC27 < count($estado_fin_cli->Bancarizado_[0]->row)) echo '&#013;&#010;';
										}
										echo '</BANCARIZADO>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->FechaMaxSistFin_[0]))
									{
										echo '<FECHA_MAXIMA_SISTEMA_FINANCIERO>&#013;&#010;';
										$contadorRecC28 = 0;
										foreach ($estado_fin_cli->FechaMaxSistFin_[0]->row as $recFMSF) 
										{							
											$contadorRecC28++;
											if(!empty($recFMSF->fecha)) echo '	<FECHA> '.$recFMSF->fecha.' </FECHA>&#013;&#010;';
											
											if(count($estado_fin_cli->FechaMaxSistFin_[0]->row) > 1 && $contadorRecC28 < count($estado_fin_cli->FechaMaxSistFin_[0]->row)) echo '&#013;&#010;';
										}
										echo '</FECHA_MAXIMA_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
									}	

									if(!empty($estado_fin_cli->SistFinEvolucionDeuda_[0]))
									{
										echo '<EVOLUCION_DEUDA_SISTEMA_FINANCIERO>&#013;&#010;';
										$contadorRecC29 = 0;
										foreach ($estado_fin_cli->SistFinEvolucionDeuda_[0]->row as $recEDSF) 
										{							
											$contadorRecC29++;
											if(!empty($recEDSF->entidad)) echo '	<ENTIDAD> '.$recEDSF->entidad.' </ENTIDAD>&#013;&#010;';
											if(!empty($recEDSF->m1)) echo '	<MES1> '.$recEDSF->m1.' </MES1>&#013;&#010;';
											if(!empty($recEDSF->m2)) echo '	<MES2> '.$recEDSF->m2.' </MES2>&#013;&#010;';
											if(!empty($recEDSF->m3)) echo '	<MES3> '.$recEDSF->m3.' </MES3>&#013;&#010;';
											if(!empty($recEDSF->m4)) echo '	<MES4> '.$recEDSF->m4.' </MES4>&#013;&#010;';
											if(!empty($recEDSF->m5)) echo '	<MES5> '.$recEDSF->m5.' </MES5>&#013;&#010;';
											if(!empty($recEDSF->m6)) echo '	<MES6> '.$recEDSF->m6.' </MES6>&#013;&#010;';
											if(!empty($recEDSF->m7)) echo '	<MES7> '.$recEDSF->m7.' </MES7>&#013;&#010;';
											if(!empty($recEDSF->m8)) echo '	<MES8> '.$recEDSF->m8.' </MES8>&#013;&#010;';
											if(!empty($recEDSF->m9)) echo '	<MES9> '.$recEDSF->m9.' </MES9>&#013;&#010;';
											if(!empty($recEDSF->m10)) echo '	<MES10> '.$recEDSF->m10.' </MES10>&#013;&#010;';
											if(!empty($recEDSF->m11)) echo '	<MES11> '.$recEDSF->m11.' </MES11>&#013;&#010;';
											if(!empty($recEDSF->m12)) echo '	<MES12> '.$recEDSF->m12.' </MES12>&#013;&#010;';
											if(!empty($recEDSF->m13)) echo '	<MES13> '.$recEDSF->m13.' </MES13>&#013;&#010;';
											if(!empty($recEDSF->m14)) echo '	<MES14> '.$recEDSF->m14.' </MES14>&#013;&#010;';
											if(!empty($recEDSF->m15)) echo '	<MES15> '.$recEDSF->m15.' </MES15>&#013;&#010;';
											if(!empty($recEDSF->m16)) echo '	<MES16> '.$recEDSF->m16.' </MES16>&#013;&#010;';
											if(!empty($recEDSF->m17)) echo '	<MES17> '.$recEDSF->m17.' </MES17>&#013;&#010;';
											if(!empty($recEDSF->m18)) echo '	<MES18> '.$recEDSF->m18.' </MES18>&#013;&#010;';
											if(!empty($recEDSF->m19)) echo '	<MES19> '.$recEDSF->m19.' </MES19>&#013;&#010;';
											if(!empty($recEDSF->m20)) echo '	<MES20> '.$recEDSF->m20.' </MES20>&#013;&#010;';
											if(!empty($recEDSF->m21)) echo '	<MES21> '.$recEDSF->m21.' </MES21>&#013;&#010;';
											if(!empty($recEDSF->m22)) echo '	<MES22> '.$recEDSF->m22.' </MES22>&#013;&#010;';
											if(!empty($recEDSF->m23)) echo '	<MES23> '.$recEDSF->m23.' </MES23>&#013;&#010;';
											if(!empty($recEDSF->m24)) echo '	<MES24> '.$recEDSF->m24.' </MES24>&#013;&#010;';									
											if(!empty($recEDSF->deuda_actual)) echo '	<DEUDA_ACTUAL> '.$recEDSF->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
											if(!empty($recEDSF->monto_maximo)) echo '	<MONTO_MAXIMO> '.$recEDSF->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
											if(!empty($recEDSF->bco)) echo '	<BANCO> '.$recEDSF->bco.' </BANCO>&#013;&#010;';
											
											if(count($estado_fin_cli->SistFinEvolucionDeuda_[0]->row) > 1 && $contadorRecC29 < count($estado_fin_cli->SistFinEvolucionDeuda_[0]->row)) echo '&#013;&#010;';
										}
										echo '</EVOLUCION_DEUDA_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]))
									{
										echo '<EVOLUCION_DEUDA_PROPIO_SISTEMA_FINANCIERO>&#013;&#010;';
										$contadorRecC30 = 0;
										foreach ($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row as $recEDPSF) 
										{							
											$contadorRecC30++;
											if(!empty($recEDPSF->entidad)) echo '	<ENTIDAD> '.$recEDPSF->entidad.' </ENTIDAD>&#013;&#010;';
											if(!empty($recEDPSF->m1)) echo '	<MES1> '.$recEDPSF->m1.' </MES1>&#013;&#010;';
											if(!empty($recEDPSF->m2)) echo '	<MES2> '.$recEDPSF->m2.' </MES2>&#013;&#010;';
											if(!empty($recEDPSF->m3)) echo '	<MES3> '.$recEDPSF->m3.' </MES3>&#013;&#010;';
											if(!empty($recEDPSF->m4)) echo '	<MES4> '.$recEDPSF->m4.' </MES4>&#013;&#010;';
											if(!empty($recEDPSF->m5)) echo '	<MES5> '.$recEDPSF->m5.' </MES5>&#013;&#010;';
											if(!empty($recEDPSF->m6)) echo '	<MES6> '.$recEDPSF->m6.' </MES6>&#013;&#010;';
											if(!empty($recEDPSF->m7)) echo '	<MES7> '.$recEDPSF->m7.' </MES7>&#013;&#010;';
											if(!empty($recEDPSF->m8)) echo '	<MES8> '.$recEDPSF->m8.' </MES8>&#013;&#010;';
											if(!empty($recEDPSF->m9)) echo '	<MES9> '.$recEDPSF->m9.' </MES9>&#013;&#010;';
											if(!empty($recEDPSF->m10)) echo '	<MES10> '.$recEDPSF->m10.' </MES10>&#013;&#010;';
											if(!empty($recEDPSF->m11)) echo '	<MES11> '.$recEDPSF->m11.' </MES11>&#013;&#010;';
											if(!empty($recEDPSF->m12)) echo '	<MES12> '.$recEDPSF->m12.' </MES12>&#013;&#010;';
											if(!empty($recEDPSF->m13)) echo '	<MES13> '.$recEDPSF->m13.' </MES13>&#013;&#010;';
											if(!empty($recEDPSF->m14)) echo '	<MES14> '.$recEDPSF->m14.' </MES14>&#013;&#010;';
											if(!empty($recEDPSF->m15)) echo '	<MES15> '.$recEDPSF->m15.' </MES15>&#013;&#010;';
											if(!empty($recEDPSF->m16)) echo '	<MES16> '.$recEDPSF->m16.' </MES16>&#013;&#010;';
											if(!empty($recEDPSF->m17)) echo '	<MES17> '.$recEDPSF->m17.' </MES17>&#013;&#010;';
											if(!empty($recEDPSF->m18)) echo '	<MES18> '.$recEDPSF->m18.' </MES18>&#013;&#010;';
											if(!empty($recEDPSF->m19)) echo '	<MES19> '.$recEDPSF->m19.' </MES19>&#013;&#010;';
											if(!empty($recEDPSF->m20)) echo '	<MES20> '.$recEDPSF->m20.' </MES20>&#013;&#010;';
											if(!empty($recEDPSF->m21)) echo '	<MES21> '.$recEDPSF->m21.' </MES21>&#013;&#010;';
											if(!empty($recEDPSF->m22)) echo '	<MES22> '.$recEDPSF->m22.' </MES22>&#013;&#010;';
											if(!empty($recEDPSF->m23)) echo '	<MES23> '.$recEDPSF->m23.' </MES23>&#013;&#010;';
											if(!empty($recEDPSF->m24)) echo '	<MES24> '.$recEDPSF->m24.' </MES24>&#013;&#010;';									
											if(!empty($recEDPSF->deuda_actual)) echo '	<DEUDA_ACTUAL> '.$recEDPSF->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
											if(!empty($recEDPSF->monto_maximo)) echo '	<MONTO_MAXIMO> '.$recEDPSF->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
											if(!empty($recEDPSF->bco)) echo '	<BANCO> '.$recEDPSF->bco.' </BANCO>&#013;&#010;';
											
											if(count($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row) > 1 && $contadorRecC30 < count($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row)) echo '&#013;&#010;';
										}
										echo '</EVOLUCION_DEUDA_PROPIO_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
									}							
									
									if(!empty($estado_fin_cli->Bcra_Ultimas_Situaciones[0]))
									{
										echo '<BANCO_CENTRAL_ULTIMAS_SITUACIONES>&#013;&#010;';
										$contadorRecC31 = 0;
										foreach ($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row as $recBCUS) 
										{							
											$contadorRecC31++;
											if(!empty($recBCUS->entidad)) echo '	<ENTIDAD> '.$recBCUS->entidad.' </ENTIDAD>&#013;&#010;';
											if(!empty($recBCUS->periodo)) echo '	<PERIODO> '.$recBCUS->periodo.' </PERIODO>&#013;&#010;';
											if(!empty($recBCUS->situacion)) echo '	<SITUACION> '.$recBCUS->situacion.' </SITUACION>&#013;&#010;';
											if(!empty($recBCUS->monto)) echo '	<MONTO> '.$recBCUS->monto.' </MONTO>&#013;&#010;';
											
											if(count($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row) > 1 && $contadorRecC31 < count($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row)) echo '&#013;&#010;';
										}
										echo '</BANCO_CENTRAL_ULTIMAS_SITUACIONES>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->Bcra_Situaciones_Vigente[0]))
									{
										echo '<BANCO_CENTRAL_SITUACIONES_VIGENTES>&#013;&#010;';
										$contadorRecC32 = 0;
										foreach ($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row as $recBCSV) 
										{							
											$contadorRecC32++;
											if(!empty($recBCSV->entidad)) echo '	<ENTIDAD> '.$recBCSV->entidad.' </ENTIDAD>&#013;&#010;';
											if(!empty($recBCSV->periodos)) echo '	<PERIODOS> '.$recBCSV->periodos.' </PERIODOS>&#013;&#010;';
											if(!empty($recBCSV->situacion)) echo '	<SITUACION> '.$recBCSV->situacion.' </SITUACION>&#013;&#010;';
											if(!empty($recBCSV->monto)) echo '	<MONTO> '.$recBCSV->monto.' </MONTO>&#013;&#010;';
											
											if(count($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row) > 1 && $contadorRecC32 < count($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row)) echo '&#013;&#010;';
										}
										echo '</BANCO_CENTRAL_SITUACIONES_VIGENTES>&#013;&#010;&#013;&#010;';
									}							
									
									if(!empty($estado_fin_cli->Tipo_Actividad[0]))
									{							
										echo '<TIPO_ACTIVIDAD>&#013;&#010;';
										$contadorRecC33 = 0;
										foreach ($estado_fin_cli->Tipo_Actividad[0]->row as $recTAC) 
										{								
											$contadorRecC33++;
											echo '	<TIPO_ACTIVIDAD> '.$recTAC->tipo_actividad.' </TIPO_ACTIVIDAD>&#013;&#010;';

											if(count($estado_fin_cli->Tipo_Actividad[0]->row) > 1 && $contadorRecC33 < count($estado_fin_cli->Tipo_Actividad[0]->row)) echo '&#013;&#010;';								
										}
										echo '</TIPO_ACTIVIDAD>&#013;&#010;&#013;&#010;';	
									}								
							
									if(!empty($estado_fin_cli->Consultas_Individual_[0]))
									{
										echo '<CONSULTAS_INDIVIDUALES>&#013;&#010;';
										$contadorRecC34 = 0;
										foreach ($estado_fin_cli->Consultas_Individual_[0]->row as $recCINDC) 
										{							
											$contadorRecC34++;
											if(!empty($recCINDC->dia)) echo '	<DIA> '.$recCINDC->dia.' </DIA>&#013;&#010;';
											if(!empty($recCINDC->semana)) echo '	<SEMANA> '.$recCINDC->semana.' </SEMANA>&#013;&#010;';
											if(!empty($recCINDC->mes)) echo '	<MES> '.$recCINDC->mes.' </MES>&#013;&#010;';
											if(!empty($recCINDC->m24)) echo '	<M24> '.$recCINDC->m24.' </M24>&#013;&#010;';
											
											if(count($estado_fin_cli->Consultas_Individual_[0]->row) > 1 && $contadorRecC34 < count($estado_fin_cli->Consultas_Individual_[0]->row)) echo '&#013;&#010;';
										}
										echo '</CONSULTAS_INDIVIDUALES>&#013;&#010;&#013;&#010;';
									}

									if(!empty($estado_fin_cli->Consultas_Grupo_[0]))
									{
										echo '<CONSULTAS_GRUPO>&#013;&#010;';
										$contadorRecC35 = 0;
										foreach ($estado_fin_cli->Consultas_Grupo_[0]->row as $recCGRUC) 
										{							
											$contadorRecC35++;
											if(!empty($recCGRUC->dia)) echo '	<DIA> '.$recCGRUC->dia.' </DIA>&#013;&#010;';
											if(!empty($recCGRUC->semana)) echo '	<SEMANA> '.$recCGRUC->semana.' </SEMANA>&#013;&#010;';
											if(!empty($recCGRUC->mes)) echo '	<MES> '.$recCGRUC->mes.' </MES>&#013;&#010;';
											if(!empty($recCGRUC->m24)) echo '	<M24> '.$recCGRUC->m24.' </M24>&#013;&#010;';
											
											if(count($estado_fin_cli->Consultas_Grupo_[0]->row) > 1 && $contadorRecC35 < count($estado_fin_cli->Consultas_Grupo_[0]->row)) echo '&#013;&#010;';
										}
										echo '</CONSULTAS_GRUPO>&#013;&#010;&#013;&#010;';
									}	

									if(!empty($estado_fin_cli->CONSULTADO_POR_[0]))
									{
										echo '<CONSULTADO_POR>&#013;&#010;';
										$contadorRecC36 = 0;
										foreach ($estado_fin_cli->CONSULTADO_POR_[0]->row as $recCCONP) 
										{							
											$contadorRecC36++;
											if(!empty($recCCONP->fecha)) echo '	<FECHA> '.$recCCONP->fecha.' </FECHA>&#013;&#010;';
											if(!empty($recCCONP->consultante)) echo '	<CONSULTANTE> '.$recCCONP->consultante.' </CONSULTANTE>&#013;&#010;';
											
											if(count($estado_fin_cli->CONSULTADO_POR_[0]->row) > 1 && $contadorRecC36 < count($estado_fin_cli->CONSULTADO_POR_[0]->row)) echo '&#013;&#010;';
										}
										echo '</CONSULTADO_POR>&#013;&#010;&#013;&#010;';
									}							
							
									if(!empty($estado_fin_cli->Independientes_[0]))
									{
										echo '<INDEPENDIENTES>&#013;&#010;';
										$contadorRecC37 = 0;
										foreach ($estado_fin_cli->Independientes_[0]->row as $recCONIND) 
										{							
											$contadorRecC37++;
											if(!empty($recCONIND->inscripto)) echo '	<INSCRIPTO> '.$recCONIND->inscripto.' </INSCRIPTO>&#013;&#010;';
											if(!empty($recCONIND->categoria)) echo '	<CATEGORIA> '.$recCONIND->categoria.' </CATEGORIA>&#013;&#010;';
											if(!empty($recCONIND->cuit)) echo '	<CUIT> '.$recCONIND->cuit.' </CUIT>&#013;&#010;';
											if(!empty($recCONIND->documento)) echo '	<DOCUMENTO> '.$recCONIND->documento.' </DOCUMENTO>&#013;&#010;';
											if(!empty($recCONIND->nombre)) echo '	<NOMBRE> '.$recCONIND->nombre.' </NOMBRE>&#013;&#010;';
											if(!empty($recCONIND->domicilio)) echo '	<DOMICILIO> '.$recCONIND->domicilio.' </DOMICILIO>&#013;&#010;';
											if(!empty($recCONIND->cod_postal)) echo '	<CODIGO_POSTAL> '.$recCONIND->cod_postal.' </CODIGO_POSTAL>&#013;&#010;';
											if(!empty($recCONIND->localidad)) echo '	<LOCALIDAD> '.$recCONIND->localidad.' </LOCALIDAD>&#013;&#010;';
											if(!empty($recCONIND->provincia)) echo '	<PROVINCIA> '.$recCONIND->provincia.' </PROVINCIA>&#013;&#010;';
											if(!empty($recCONIND->fecha_nac)) echo '	<FECHA_NACIMIENTO> '.$recCONIND->fecha_nac.' </FECHA_NACIMIENTO>&#013;&#010;';
											if(!empty($recCONIND->entidad)) echo '	<ENTIDAD> '.$recCONIND->entidad.' </ENTIDAD>&#013;&#010;';
											if(!empty($recCONIND->actividad)) echo '	<ACTIVIDAD> '.$recCONIND->actividad.' </ACTIVIDAD>&#013;&#010;';
											if(!empty($recCONIND->periodo)) echo '	<PERIODO> '.$recCONIND->periodo.' </PERIODO>&#013;&#010;';
											if(!empty($recCONIND->monto)) echo '	<MONTO> '.$recCONIND->monto.' </MONTO>&#013;&#010;';									
											
											if(count($estado_fin_cli->Independientes_[0]->row) > 1 && $contadorRecC37 < count($estado_fin_cli->Independientes_[0]->row)) echo '&#013;&#010;';
										}
										echo '</INDEPENDIENTES>&#013;&#010;&#013;&#010;';
									}
									
									if(!empty($estado_fin_cli->Indep_apo_[0]))
									{
										echo '<APORTES_INDEPENDIENTE>&#013;&#010;';
										$contadorRecC38 = 0;
										foreach ($estado_fin_cli->Indep_apo_[0]->row as $recAPCI) 
										{							
											$contadorRecC38++;
											if(!empty($recAPCI->cuit)) echo '	<CUIT> '.$recAPCI->cuit.' </CUIT>&#013;&#010;';
											if(!empty($recAPCI->periodo)) echo '	<PERIODO> '.$recAPCI->periodo.' </PERIODO>&#013;&#010;';
											if(!empty($recAPCI->imp_pago)) echo '	<IMPORTE> '.$recAPCI->imp_pago.' </IMPORTE>&#013;&#010;';
											if(!empty($recAPCI->impuesto)) echo '	<IMPUESTO> '.$recAPCI->impuesto.' </IMPUESTO>&#013;&#010;';
											
											if(count($estado_fin_cli->Indep_apo_[0]->row) > 1 && $contadorRecC38 < count($estado_fin_cli->Indep_apo_[0]->row)) echo '&#013;&#010;';
										}
										echo '</APORTES_INDEPENDIENTE>&#013;&#010;&#013;&#010;';
									}							
									
									if(!empty($estado_fin_cli->Propiedades_[0]))
									{
										echo '<PROPIEDADES>&#013;&#010;';
										$contadorRecC39 = 0;
										foreach ($estado_fin_cli->Propiedades_[0]->row as $recIPROPC) 
										{							
											$contadorRecC39++;
											if(!empty($recIPROPC->titular)) echo '	<TITULAR> '.$recIPROPC->titular.' </TITULAR>&#013;&#010;';
											if(!empty($recIPROPC->folio)) echo '	<FOLIO> '.$recIPROPC->folio.' </FOLIO>&#013;&#010;';
											if(!empty($recIPROPC->ano_inscripcion)) echo '	<AÑO_INSCRIPCION> '.$recIPROPC->ano_inscripcion.' </AÑO_INSCRIPCION>&#013;&#010;';
											if(!empty($recIPROPC->ph)) echo '	<PH> '.$recIPROPC->ph.' </PH>&#013;&#010;';
											if(!empty($recIPROPC->condom)) echo '	<CONDOM> '.$recIPROPC->condom.' </CONDOM>&#013;&#010;';
											if(!empty($recIPROPC->codigo_departamento)) echo '	<CODIGO_DEPARTAMENTO> '.$recIPROPC->codigo_departamento.' </CODIGO_DEPARTAMENTO>&#013;&#010;';
											if(!empty($recIPROPC->pedania)) echo '	<PEDANIA> '.$recIPROPC->pedania.' </PEDANIA>&#013;&#010;';
											if(!empty($recIPROPC->pueblo)) echo '	<PUEBLO> '.$recIPROPC->pueblo.' </PUEBLO>&#013;&#010;';
											if(!empty($recIPROPC->circuncripcion)) echo '	<CIRCUNCRIPCION> '.$recIPROPC->circuncripcion.' </CIRCUNCRIPCION>&#013;&#010;';
											if(!empty($recIPROPC->seccion)) echo '	<SECCION> '.$recIPROPC->seccion.' </SECCION>&#013;&#010;';
											if(!empty($recIPROPC->manzana)) echo '	<MANZANA> '.$recIPROPC->manzana.' </MANZANA>&#013;&#010;';
											if(!empty($recIPROPC->parcela)) echo '	<PARCELA> '.$recIPROPC->parcela.' </PARCELA>&#013;&#010;';
											if(!empty($recIPROPC->escrituro)) echo '	<ESCRITURO> '.$recIPROPC->escrituro.' </ESCRITURO>&#013;&#010;';
											
											if(count($estado_fin_cli->Propiedades_[0]->row) > 1 && $contadorRecC39 < count($estado_fin_cli->Propiedades_[0]->row)) echo '&#013;&#010;';
										}
										echo '</PROPIEDADES>&#013;&#010;&#013;&#010;';
									}
									echo '						</textarea>';
									echo '					</div>';		
									echo '				</div>';									
								}
								
								$stmt44->free_result();
								$stmt44->close();
							}
							else
							{
								echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
								return;
							}
						}
						echo '				<div class="form-group form-inline">';
						echo '					<div id="img_loader_13"></div>';		
						echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarEFCM" id="btnCancelarEFCM" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogvalidacionestadocrediticioclientem\').dialog(\'close\');" style="margin-left:10px;" />';
						if($estado_activa_supervisor == 1) echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarEFCM" id="btnValidarEFCM" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarAutorizacionSupervisorEstadoFinancieroClienteM(document.getElementById(\'formulariocefcm\'),'.$motivo.');"/>';										
						else echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarEFCM" id="btnValidarEFCM" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarSinSupervisorEstadoFinancieroClienteM('.$motivo.');"/>';
						echo '				</div>';				
						echo '			</form>';
						echo '		</div>';
						echo '	</div>';
						echo '</div>';
					}
					else
					{
						if($validacion_estado_financiero_cliente_db == 1 && $valor_necesita_supervisor_cef_db == 1)
						{
							if($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3)
							{
								$estado_fin_cli = new SimpleXMLElement($resultado_finan_cli_final);
								echo translate('Msg_Validation_Credit_Status_Client_OK',$GLOBALS['lang']).'=::=::=::'.$tokenECF.'=:=:=:';
								echo '<div class="panel-group">';				
								echo '	<div class="panel panel-default">';
								echo '		<div id="panel-title-header" class="panel-heading">';
								echo '			<h3 class="panel-title">'.translate('Lbl_Result_Financial_Statement_Client',$GLOBALS['lang']).'</h3>';
								echo ' 		</div>';
								echo '		<div class="panel-body">';
								echo '			<form id="formulariocefcm" role="form" onsubmit="buscarTextoEstadoFinancieroM(); return false;">';		
								echo '				<div class="form-group form-inline">';
								echo '					<div class="form-group" id="buscartextoestadocrediticioclientem">';
								echo '						<input class="form-control input-sm" id="buscartextoestadocrediticioclientemi" name="buscartextoestadocrediticioclientemi" type="text" maxlength="150" />';
								echo '						&nbsp;<button type="button" class="btn" id="btnBuscarEstadoFM" name="btnBuscarEstadoFM" title="'.translate('Lbl_Search_Text_Statement_Client',$GLOBALS['lang']).'" onclick="buscarTextoEstadoFinancieroM();"><i class="fas fa-search"></i></button>';									
								echo '					</div>';
								echo '				</div>';
								echo '				<div class="form-group form-inline">';					
								echo '					<div class="form-group" id="resultadoestadofinancieroclientem">';
								echo '						<textarea rows="3" cols="67" class="form-control input-sm" id="resultadoestadofinancieroclientemi" name="resultadoestadofinancieroclientemi">';
								echo '<EXISTENCIA_FISICA_ENTIDAD>&#013;&#010;';
								$contadorRecC1 = 0;
								foreach ($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row as $recEFC) 
								{
									$contadorRecC1++;
									if(!empty($recEFC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recEFC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
									if(!empty($recEFC->sexo)) echo '	<SEXO> '.$recEFC->sexo.' </SEXO>&#013;&#010;';
									if(!empty($recEFC->tipo_docu)) echo '	<TIPO_DOCUMENTO> '.$recEFC->tipo_docu.' </TIPO_DOCUMENTO>&#013;&#010;';
									if(!empty($recEFC->nume_doc)) echo '	<DOCUMENTO> '.$recEFC->nume_doc.' </DOCUMENTO>&#013;&#010;';
									if(!empty($recEFC->clase)) echo '	<CLASE> '.$recEFC->clase.' </CLASE>&#013;&#010;';
									if(!empty($recEFC->edad)) echo '	<EDAD> '.$recEFC->edad.' </EDAD>&#013;&#010;';
									if(!empty($recEFC->ocupacion)) echo '	<OCUPACION> '.$recEFC->ocupacion.' </OCUPACION>&#013;&#010;';
									if(!empty($recEFC->cdi_codigo_de_identificacion)) echo '	<CUIT_CUIL> '.$recEFC->cdi_codigo_de_identificacion.' </CUIT_CUIL>&#013;&#010;';								
									if(!empty($recEFC->fecha_nacimiento)) echo '	<FECHA_NACIMIENTO> '.$recEFC->fecha_nacimiento.' </FECHA_NACIMIENTO>&#013;&#010;';							
									if(!empty($recEFC->direc_calle)) echo '	<DIRECCION_CALLE> '.$recEFC->direc_calle.' </DIRECCION_CALLE>&#013;&#010;';							
									if(!empty($recEFC->localidad)) echo '	<LOCALIDAD> '.$recEFC->localidad.' </LOCALIDAD>&#013;&#010;';
									if(!empty($recEFC->departamento)) echo '	<DEPARTAMENTO> '.$recEFC->departamento.' </DEPARTAMENTO>&#013;&#010;';								
									if(!empty($recEFC->codigo_postal)) echo '	<CODIGO_POSTAL> '.$recEFC->codigo_postal.' </CODIGO_POSTAL>&#013;&#010;';							
									if(!empty($recEFC->provincia)) echo '	<PROVINCIA> '.$recEFC->provincia.' </PROVINCIA>&#013;&#010;';							
									if(!empty($recEFC->apellido_materno)) echo '	<APELLIDO_MATERNO> '.$recEFC->apellido_materno.' </APELLIDO_MATERNO>&#013;&#010;';
									if(!empty($recEFC->estado_civil)) echo '	<ESTADO_CIVIL> '.$recEFC->estado_civil.' </ESTADO_CIVIL>&#013;&#010;';								
									if(!empty($recEFC->fallecido)) echo '	<FALLECIDO> '.$recEFC->fallecido.' </FALLECIDO>&#013;&#010;';
									
									if(count($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row) > 1 && $contadorRecC1 < count($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row)) echo '&#013;&#010;';
								}							
								echo '</EXISTENCIA_FISICA_ENTIDAD>&#013;&#010;&#013;&#010;';
								
								if(!empty($estado_fin_cli->Score_[0]))
								{
									echo '<SCORE>&#013;&#010;';
									$contadorRecC2 = 0;
									foreach ($estado_fin_cli->Score_[0]->row as $recPIC) 
									{							
										$contadorRecC2++;
										if(!empty($recPIC->score)) echo '	<SCORE> '.$recPIC->score.' </SCORE>&#013;&#010;';
										
										if(count($estado_fin_cli->Score_[0]->row) > 1 && $contadorRecC2 < count($estado_fin_cli->Score_[0]->row)) echo '&#013;&#010;';
									}
									echo '</SCORE>&#013;&#010;&#013;&#010;';
								}
								
								if(!empty($estado_fin_cli->Domi_xy_[0]))
								{
									echo '<DOMICILIO_XY>&#013;&#010;';
									$contadorRecC3 = 0;
									foreach ($estado_fin_cli->Domi_xy_[0]->row as $recTJQEC) 
									{								
										$contadorRecC3++;
										if(!empty($recTJQEC->nprovincia)) echo '	<N_PROVINCIA> '.$recTJQEC->nprovincia.' </N_PROVINCIA>&#013;&#010;';
										if(!empty($recTJQEC->npartido)) echo '	<N_PARTIDO> '.$recTJQEC->npartido.' </N_PARTIDO>&#013;&#010;';
										if(!empty($recTJQEC->nlocalidad)) echo '	<N_LOCALIDAD> '.$recTJQEC->nlocalidad.' </N_LOCALIDAD>&#013;&#010;';
										if(!empty($recTJQEC->nbarrio)) echo '	<N_BARRIO> '.$recTJQEC->nbarrio.' </N_BARRIO>&#013;&#010;';
										if(!empty($recTJQEC->calle)) echo '	<CALLE> '.$recTJQEC->calle.' </CALLE>&#013;&#010;';
										if(!empty($recTJQEC->ncp)) echo '	<NCP> '.$recTJQEC->ncp.' </NCP>&#013;&#010;';
										if(!empty($recTJQEC->geocordenadas)) echo '	<GEOCORDENADAS> '.$recTJQEC->geocordenadas.' </GEOCORDENADAS>&#013;&#010;';
										if(count($estado_fin_cli->Domi_xy_[0]->row) > 1 && $contadorRecC3 < count($estado_fin_cli->Domi_xy_[0]->row)) echo '&#013;&#010;';
									}
									echo '</DOMICILIO_XY>&#013;&#010;&#013;&#010;';
								}
								
								if(!empty($estado_fin_cli->Situacion_BCRA[0]))
								{							
									echo '<SITUACION_BCRA>&#013;&#010;';
									$contadorRecC4 = 0;
									foreach ($estado_fin_cli->Situacion_BCRA[0]->row as $recDSF6M) 
									{								
										$contadorRecC4++;
										if(!empty($recDSF6M->entidad)) echo '	<ENTIDAD> '.$recDSF6M->entidad.' </ENTIDAD>&#013;&#010;';
										if(!empty($recDSF6M->fecha_sit)) echo '	<FECHA> '.$recDSF6M->fecha_sit.' </FECHA>&#013;&#010;';									
										if(!empty($recDSF6M->situacion)) echo '	<SITUACION> '.$recDSF6M->situacion.' </SITUACION>&#013;&#010;';
										if(!empty($recDSF6M->deuda_total))  echo '	<DEUDA_TOTAL> '.$recDSF6M->deuda_total.' </DEUDA_TOTAL>&#013;&#010;';

										if(count($estado_fin_cli->Situacion_BCRA[0]->row) > 1 && $contadorRecC4 < count($estado_fin_cli->Situacion_BCRA[0]->row)) echo '&#013;&#010;';								
									}
									echo '</SITUACION_BCRA>&#013;&#010;&#013;&#010;';	
								}
								
								if(!empty($estado_fin_cli->FraudesTarjeta_[0]))
								{
									echo '<FRAUDES_TARJETA>&#013;&#010;';
									$contadorRecC5 = 0;
									foreach ($estado_fin_cli->FraudesTarjeta_[0]->row as $recFTs) 
									{							
										$contadorRecC5++;
										if(!empty($recFTs->fraude)) echo '	<FRAUDE> '.$recFTs->fraude.' </FRAUDE>&#013;&#010;';
										
										if(count($estado_fin_cli->FraudesTarjeta_[0]->row) > 1 && $contadorRecC5 < count($estado_fin_cli->FraudesTarjeta_[0]->row)) echo '&#013;&#010;';
									}
									echo '</FRAUDES_TARJETA>&#013;&#010;&#013;&#010;';
								}
								
								if(!empty($estado_fin_cli->CapacidadEndeudamiento_[0]))
								{
									echo '<CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;';
									$contadorRecC6 = 0;
									foreach ($estado_fin_cli->CapacidadEndeudamiento_[0]->row as $recCPEC) 
									{							
										$contadorRecC6++;
										if(!empty($recCPEC->capacidad_endeudamiento)) echo '	<CAPACIDAD_ENDEUDAMIENTO> '.$recCPEC->capacidad_endeudamiento.' </CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;';
										
										if(count($estado_fin_cli->CapacidadEndeudamiento_[0]->row) > 1 && $contadorRecC6 < count($estado_fin_cli->CapacidadEndeudamiento_[0]->row)) echo '&#013;&#010;';
									}
									echo '</CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;&#013;&#010;';
								}							
								
								if(!empty($estado_fin_cli->Ooss_personas_[0]))
								{							
									echo '<OBRA_SOCIAL_PERSONAS>&#013;&#010;';
									$contadorRecC7 = 0;
									foreach ($estado_fin_cli->Ooss_personas_[0]->row as $recDSF12M) 
									{								
										$contadorRecC7++;
										if(!empty($recDSF12M->cuil)) echo '	<CUIL> '.$recDSF12M->cuil.' </CUIL>&#013;&#010;';
										if(!empty($recDSF12M->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recDSF12M->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
										if(!empty($recDSF12M->nume_docu)) echo '	<DOCUMENTO> '.$recDSF12M->nume_docu.' </DOCUMENTO>&#013;&#010;';
										if(!empty($recDSF12M->sexo)) echo '	<SEXO> '.$recDSF12M->sexo.' </SEXO>&#013;&#010;';
										if(!empty($recDSF12M->domicilio)) echo '	<DOMICILIO> '.$recDSF12M->domicilio.' </DOMICILIO>&#013;&#010;';
										if(!empty($recDSF12M->localidad)) echo '	<LOCALIDAD> '.$recDSF12M->localidad.' </LOCALIDAD>&#013;&#010;';
										if(!empty($recDSF12M->provincia)) echo '	<PROVINCIA> '.$recDSF12M->provincia.' </PROVINCIA>&#013;&#010;';
										if(!empty($recDSF12M->cod_postal)) echo '	<CODIGO_POSTAL> '.$recDSF12M->cod_postal.' </CODIGO_POSTAL>&#013;&#010;';									

										if(count($estado_fin_cli->Ooss_personas_[0]->row) > 1 && $contadorRecC7 < count($estado_fin_cli->Ooss_personas_[0]->row)) echo '&#013;&#010;';								
									}
									echo '</OBRA_SOCIAL_PERSONAS>&#013;&#010;&#013;&#010;';	
								}
								
								if(!empty($estado_fin_cli->Obra_Social_Rel_[0]))
								{							
									echo '<OBRA_SOCIAL_RELACION>&#013;&#010;';
									$contadorRecC8 = 0;
									foreach ($estado_fin_cli->Obra_Social_Rel_[0]->row as $recDSF24M) 
									{								
										$contadorRecC8++;
										if(!empty($recDSF24M->codiobra)) echo '	<CODIGO> '.$recDSF24M->codiobra.' </CODIGO>&#013;&#010;';
										if(!empty($recDSF24M->descripcion_ooss)) echo '	<DESCRIPCION> '.$recDSF24M->descripcion_ooss.' </DESCRIPCION>&#013;&#010;';
										if(!empty($recDSF24M->sigla_ooss)) echo '	<SIGLA> '.$recDSF24M->sigla_ooss.' </SIGLA>&#013;&#010;';
										if(!empty($recDSF24M->cobertura)) echo '	<COBERTURA> '.$recDSF24M->cobertura.' </COBERTURA>&#013;&#010;';
										if(!empty($recDSF24M->conyugue)) echo '	<CONYUGUE> '.$recDSF24M->conyugue.' </CONYUGUE>&#013;&#010;';
										if(!empty($recDSF24M->hijos)) echo '	<HIJOS> '.$recDSF24M->hijos.' </HIJOS>&#013;&#010;';

										if(count($estado_fin_cli->Obra_Social_Rel_[0]->row) > 1 && $contadorRecC8 < count($estado_fin_cli->Obra_Social_Rel_[0]->row)) echo '&#013;&#010;';								
									}
									echo '</OBRA_SOCIAL_RELACION>&#013;&#010;&#013;&#010;';	
								}
								
								if(!empty($estado_fin_cli->Domicilio_Empresa_trabaja_[0]))
								{
									echo '<DOMICILIO_LABORAL>&#013;&#010;';
									$contadorRecC9 = 0;
									foreach ($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row as $recRDC) 
									{								
										$contadorRecC9++;
										if(!empty($recRDC->direccion)) echo '	<DIRECCION> '.$recRDC->ult_periodo.' </DIRECCION>&#013;&#010;';
										if(!empty($recRDC->localidad)) echo '	<LOCALIDAD> '.$recRDC->alta_trabajo_ultimo.' </LOCALIDAD>&#013;&#010;';
										if(!empty($recRDC->provincia)) echo '	<PROVINCIA> '.$recRDC->cuit.' </PROVINCIA>&#013;&#010;';
										if(!empty($recRDC->cp)) echo '	<CODIGO_POSTAL> '.$recRDC->razon_social.' </CODIGO_POSTAL>&#013;&#010;';

										if(count($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row) > 1 && $contadorRecC9 < count($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row)) echo '&#013;&#010;';								
									}
									echo '</DOMICILIO LABORAL>&#013;&#010;&#013;&#010;';
								}
								
								if(!empty($estado_fin_cli->domicilio_otros_[0]))
								{							
									echo '<OTROS_DOMICILIOS>&#013;&#010;';
									$contadorRecC10 = 0;
									foreach ($estado_fin_cli->domicilio_otros_[0]->row as $recCIAC) 
									{								
										$contadorRecC10++;
										if(!empty($recCIAC->domicilio)) echo '	<DOMICILIO> '.$recCIAC->domicilio.' </DOMICILIO>&#013;&#010;';
										if(!empty($recCIAC->localidad)) echo '	<LOCALIDAD> '.$recCIAC->localidad.' </LOCALIDAD>&#013;&#010;';
										if(!empty($recCIAC->cp)) echo '	<CP> '.$recCIAC->cp.' </CP>&#013;&#010;';
										if(!empty($recCIAC->provincia)) echo '	<PROVINCIA> '.$recCIAC->provincia.' </PROVINCIA>&#013;&#010;';

										if(count($estado_fin_cli->domicilio_otros_[0]->row) > 1 && $contadorRecC10 < count($estado_fin_cli->domicilio_otros_[0]->row)) echo '&#013;&#010;';								
									}
									echo '</OTROS_DOMICILIOS>&#013;&#010;&#013;&#010;';
								}

								if(!empty($estado_fin_cli->Domicilios_Juicios_[0]))
								{
									echo '<DOMICILIOS_JUICIOS>&#013;&#010;';
									$contadorRecC11 = 0;
									foreach ($estado_fin_cli->Domicilios_Juicios_[0]->row as $recDJD) 
									{							
										$contadorRecC11++;
										if(!empty($recDJD->domicilio)) echo '	<DOMICILIO> '.$recDJD->domicilio.' </DOMICILIO>&#013;&#010;';
										
										if(count($estado_fin_cli->Domicilios_Juicios_[0]->row) > 1 && $contadorRecC11 < count($estado_fin_cli->Domicilios_Juicios_[0]->row)) echo '&#013;&#010;';
									}
									echo '</DOMICILIOS_JUICIOS>&#013;&#010;&#013;&#010;';
								}
								
								if(!empty($estado_fin_cli->Padronempresasf_[0]))
								{							
									echo '<PADRON_EMPRESAS>&#013;&#010;';
									$contadorRecC12 = 0;
									foreach ($estado_fin_cli->Padronempresasf_[0]->row as $recPMC) 
									{								
										$contadorRecC12++;
										if(!empty($recPMC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recPMC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
										if(!empty($recPMC->cuit)) echo '	<CUIT> '.$recPMC->cuit.' </CUIT>&#013;&#010;';
										if(!empty($recPMC->domicilio_fiscal)) echo '	<DOMICILIO_FISCAL> '.$recPMC->domicilio_fiscal.' </DOMICILIO_FISCAL>&#013;&#010;';
										if(!empty($recPMC->provincia)) echo '	<PROVINCIA> '.$recPMC->provincia.' </PROVINCIA>&#013;&#010;';
										if(!empty($recPMC->actividad_especifica)) echo '	<ACTIVIDAD_ESPECIFICA> '.$recPMC->actividad_especifica.' </ACTIVIDAD_ESPECIFICA>&#013;&#010;';
										if(!empty($recPMC->categoria_autonomo)) echo '	<CATEGORIA_AUTONOMO> '.$recPMC->categoria_autonomo.' </CATEGORIA_AUTONOMO>&#013;&#010;';

										if(count($estado_fin_cli->Padronempresasf_[0]->row) > 1 && $contadorRecC12 < count($estado_fin_cli->Padronempresasf_[0]->row)) echo '&#013;&#010;';									
									}
									echo '</PADRON_EMPRESAS>&#013;&#010;&#013;&#010;';							
								}
								
								if(!empty($estado_fin_cli->Gran_contrib_[0]))
								{
									echo '<GRANDES_CONTRIBUYENTES>&#013;&#010;';
									$contadorRecC13 = 0;
									foreach ($estado_fin_cli->Gran_contrib_[0]->row as $recGCD) 
									{							
										$contadorRecC13++;
										if(!empty($recGCD->gran_contr)) echo '	<CONTRIBUYENTE> '.$recGCD->gran_contr.' </CONTRIBUYENTE>&#013;&#010;';
										
										if(count($estado_fin_cli->Gran_contrib_[0]->row) > 1 && $contadorRecC13 < count($estado_fin_cli->Gran_contrib_[0]->row)) echo '&#013;&#010;';
									}
									echo '</GRANDES_CONTRIBUYENTES>&#013;&#010;&#013;&#010;';
								}
								
								if(!empty($estado_fin_cli->Domialter_[0]))
								{							
									echo '<DOMICILIOS_ALTERNATIVOS>&#013;&#010;';
									$contadorRecC14 = 0;
									foreach ($estado_fin_cli->Domialter_[0]->row as $recDMAC) 
									{								
										$contadorRecC14++;
										if(!empty($recDMAC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recDMAC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
										if(!empty($recDMAC->cuit)) echo '	<CUIT> '.$recDMAC->cuit.' </CUIT>&#013;&#010;';
										if(!empty($recDMAC->domicilio_fiscal)) echo '	<DOMICILIO_FISCAL> '.$recDMAC->domicilio_fiscal.' </DOMICILIO_FISCAL>&#013;&#010;';
										if(!empty($recDMAC->provincia)) echo '	<PROVINCIA> '.$recDMAC->provincia.' </PROVINCIA>&#013;&#010;';
										if(!empty($recDMAC->actividad_especifica)) echo '	<ACTIVIDAD_ESPECIFICA> '.$recDMAC->actividad_especifica.' </ACTIVIDAD_ESPECIFICA>&#013;&#010;';

										if(count($estado_fin_cli->Domialter_[0]->row) > 1 && $contadorRecC14 < count($estado_fin_cli->Domialter_[0]->row)) echo '&#013;&#010;';									
									}
									echo '</DOMICILIOS_ALTERNATIVOS>&#013;&#010;&#013;&#010;';							
								}
								
								if(!empty($estado_fin_cli->Nise_[0]))
								{
									echo '<NISE>&#013;&#010;';
									$contadorRecC15 = 0;
									foreach ($estado_fin_cli->Nise_[0]->row as $recINC) 
									{							
										$contadorRecC15++;
										if(!empty($recINC->radio)) echo '	<RADIO> '.$recINC->radio.' </RADIO>&#013;&#010;';
										if(!empty($recINC->nise)) echo '	<NISE> '.$recINC->nise.' </NISE>&#013;&#010;';
										if(!empty($recINC->periodo)) echo '	<PERIODO> '.$recINC->periodo.' </PERIODO>&#013;&#010;';
										
										if(count($estado_fin_cli->Nise_[0]->row) > 1 && $contadorRecC15 < count($estado_fin_cli->Nise_[0]->row)) echo '&#013;&#010;';
									}
									echo '</NISE>&#013;&#010;&#013;&#010;';
								}
								
								if(!empty($estado_fin_cli->Const_inscrip_[0]))
								{							
									echo '<CONSTANCIAS_INSCRIPCION>&#013;&#010;';
									$contadorRecC16 = 0;
									foreach ($estado_fin_cli->Const_inscrip_[0]->row as $recCICR) 
									{								
										$contadorRecC16++;
										if(!empty($recCICR->descrip_imp_ganancias)) echo '	<DESCRIPCION_IMP_GANANCIAS> '.$recCICR->descrip_imp_ganancias.' </DESCRIPCION_IMP_GANANCIAS>&#013;&#010;';
										if(!empty($recCICR->descrip_imp_iva)) echo '	<DESCRIPCION_IMP_IVA> '.$recCICR->descrip_imp_iva.' </DESCRIPCION_IMP_IVA>&#013;&#010;';
										if(!empty($recCICR->descrip_monotributo)) echo '	<DESCRIPCION_MONOTRIBUTO> '.$recCICR->descrip_monotributo.' </DESCRIPCION_MONOTRIBUTO>&#013;&#010;';
										if(!empty($recCICR->descrip_integrante_soc)) echo '	<DESCRIPCION_INTEGRANTE_SOC> '.$recCICR->descrip_integrante_soc.' </DESCRIPCION_INTEGRANTE_SOC>&#013;&#010;';
										if(!empty($recCICR->descrip_empleador)) echo '	<DESCRIPCION_EMPLEADOR> '.$recCICR->descrip_empleador.' </DESCRIPCION_EMPLEADOR>&#013;&#010;';
										if(!empty($recCICR->periodo)) echo '	<PERIODO> '.$recCICR->periodo.' </PERIODO>&#013;&#010;';

										if(count($estado_fin_cli->Const_inscrip_[0]->row) > 1 && $contadorRecC16 < count($estado_fin_cli->Const_inscrip_[0]->row)) echo '&#013;&#010;';									
									}
									echo '</CONSTANCIAS_INSCRIPCION>&#013;&#010;&#013;&#010;';							
								}
								
								if(!empty($estado_fin_cli->Nbi_[0]))
								{
									echo '<NBI>&#013;&#010;';
									$contadorRecC17 = 0;
									foreach ($estado_fin_cli->Nbi_[0]->row as $recINBC) 
									{							
										$contadorRecC17++;
										if(!empty($recINBC->radio)) echo '	<RADIO> '.$recINBC->radio.' </RADIO>&#013;&#010;';
										if(!empty($recINBC->nbi)) echo '	<NBI> '.$recINBC->nbi.' </NBI>&#013;&#010;';
										if(!empty($recINBC->periodo)) echo '	<PERIODO> '.$recINBC->periodo.' </PERIODO>&#013;&#010;';
										
										if(count($estado_fin_cli->Nbi_[0]->row) > 1 && $contadorRecC17 < count($estado_fin_cli->Nbi_[0]->row)) echo '&#013;&#010;';
									}
									echo '</NBI>&#013;&#010;&#013;&#010;';
								}
								
								if(!empty($estado_fin_cli->Iped_[0]))
								{
									echo '<IPED>&#013;&#010;';
									$contadorRecC18 = 0;
									foreach ($estado_fin_cli->Iped_[0]->row as $recIIPC) 
									{							
										$contadorRecC18++;
										if(!empty($recIIPC->radio)) echo '	<RADIO> '.$recIIPC->radio.' </RADIO>&#013;&#010;';
										if(!empty($recIIPC->iped)) echo '	<IPED> '.$recIIPC->iped.' </IPED>&#013;&#010;';
										if(!empty($recIIPC->periodo)) echo '	<PERIODO> '.$recIIPC->periodo.' </PERIODO>&#013;&#010;';
										
										if(count($estado_fin_cli->Iped_[0]->row) > 1 && $contadorRecC18 < count($estado_fin_cli->Iped_[0]->row)) echo '&#013;&#010;';
									}
									echo '</IPED>&#013;&#010;&#013;&#010;';
								}							

								if(!empty($estado_fin_cli->Ib_[0]))
								{
									echo '<IB>&#013;&#010;';
									$contadorRecC19 = 0;
									foreach ($estado_fin_cli->Ib_[0]->row as $recIIBC) 
									{							
										$contadorRecC19++;
										if(!empty($recIIBC->radio)) echo '	<RADIO> '.$recIIBC->radio.' </RADIO>&#013;&#010;';
										if(!empty($recIIBC->ib)) echo '	<IB> '.$recIIBC->ib.' </IB>&#013;&#010;';
										if(!empty($recIIBC->periodo)) echo '	<PERIODO> '.$recIIBC->periodo.' </PERIODO>&#013;&#010;';
										
										if(count($estado_fin_cli->Ib_[0]->row) > 1 && $contadorRecC19 < count($estado_fin_cli->Ib_[0]->row)) echo '&#013;&#010;';
									}
									echo '</IB>&#013;&#010;&#013;&#010;';
								}
								
								if(!empty($estado_fin_cli->Rel_Dependencia_Trabajador_[0]))
								{
									echo '<RELEACION_DEPENDENCIA_TRABAJADOR>&#013;&#010;';
									$contadorRecC20 = 0;
									foreach ($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row as $recRDTC) 
									{							
										$contadorRecC20++;
										if(!empty($recRDTC->cuil)) echo '	<CUIL> '.$recRDTC->cuil.' </CUIL>&#013;&#010;';
										if(!empty($recRDTC->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recRDTC->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
										if(!empty($recRDTC->domicilio)) echo '	<DOMICILIO> '.$recRDTC->domicilio.' </DOMICILIO>&#013;&#010;';
										if(!empty($recRDTC->localidad)) echo '	<LOCALIDAD> '.$recRDTC->localidad.' </LOCALIDAD>&#013;&#010;';
										if(!empty($recRDTC->cp)) echo '	<CODIGO_POSTAL> '.$recRDTC->cp.' </CODIGO_POSTAL>&#013;&#010;';
										if(!empty($recRDTC->provincia)) echo '	<PROVINCIA> '.$recRDTC->provincia.' </PROVINCIA>&#013;&#010;';
										if(!empty($recRDTC->fecha_nac)) echo '	<FECHA_NACIMIENTO> '.$recRDTC->fecha_nac.' </FECHA_NACIMIENTO>&#013;&#010;';
										if(!empty($recRDTC->sexo)) echo '	<SEXO> '.$recRDTC->sexo.' </SEXO>&#013;&#010;';
										if(!empty($recRDTC->doc)) echo '	<DOCUMENTO> '.$recRDTC->doc.' </DOCUMENTO>&#013;&#010;';
										
										if(count($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row) > 1 && $contadorRecC20 < count($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row)) echo '&#013;&#010;';
									}
									echo '</RELEACION_DEPENDENCIA_TRABAJADOR>&#013;&#010;&#013;&#010;';
								}

								if(!empty($estado_fin_cli->Rel_Dependencia_Empleador_[0]))
								{
									echo '<RELEACION_DEPENDENCIA_EMPLEADOR>&#013;&#010;';
									$contadorRecC21 = 0;
									foreach ($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row as $recRDEC) 
									{							
										$contadorRecC21++;
										if(!empty($recRDEC->cuit)) echo '	<CUIT> '.$recRDEC->cuit.' </CUIT>&#013;&#010;';
										if(!empty($recRDEC->razon)) echo '	<RAZON_SOCIAL> '.$recRDEC->razon.' </RAZON_SOCIAL>&#013;&#010;';
										if(!empty($recRDEC->domicilio)) echo '	<DOMICILIO> '.$recRDEC->domicilio.' </DOMICILIO>&#013;&#010;';
										if(!empty($recRDEC->localidad)) echo '	<LOCALIDAD> '.$recRDEC->localidad.' </LOCALIDAD>&#013;&#010;';
										if(!empty($recRDEC->cp)) echo '	<CODIGO_POSTAL> '.$recRDEC->cp.' </CODIGO_POSTAL>&#013;&#010;';
										if(!empty($recRDEC->provincia)) echo '	<PROVINCIA> '.$recRDEC->provincia.' </PROVINCIA>&#013;&#010;';
										
										if(count($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row) > 1 && $contadorRecC21 < count($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row)) echo '&#013;&#010;';
									}
									echo '</RELEACION_DEPENDENCIA_EMPLEADOR>&#013;&#010;&#013;&#010;';
								}

								if(!empty($estado_fin_cli->Historia_[0]))
								{
									echo '<HISTORIA_EMPRESAS>&#013;&#010;';
									$contadorRecC22 = 0;
									foreach ($estado_fin_cli->Historia_[0]->row as $recHEC) 
									{							
										$contadorRecC22++;
										if(!empty($recHEC->cuit)) echo '	<CUIT> '.$recHEC->cuit.' </CUIT>&#013;&#010;';
										if(!empty($recHEC->razon)) echo '	<RAZON_SOCIAL> '.$recHEC->razon.' </RAZON_SOCIAL>&#013;&#010;';
										if(!empty($recHEC->desde)) echo '	<DESDE> '.$recHEC->desde.' </DESDE>&#013;&#010;';
										if(!empty($recHEC->hasta)) echo '	<HASTA> '.$recHEC->hasta.' </HASTA>&#013;&#010;';
										
										if(count($estado_fin_cli->Historia_[0]->row) > 1 && $contadorRecC22 < count($estado_fin_cli->Historia_[0]->row)) echo '&#013;&#010;';
									}
									echo '</HISTORIA_EMPRESAS>&#013;&#010;&#013;&#010;';
								}

								if(!empty($estado_fin_cli->Sysem_[0]))
								{
									echo '<SYSEM>&#013;&#010;';
									$contadorRecC23 = 0;
									foreach ($estado_fin_cli->Sysem_[0]->row as $recSYC) 
									{							
										$contadorRecC23++;
										if(!empty($recSYC->cant_emp)) echo '	<CANTIDAD_EMPLEADOS> '.$recSYC->cant_emp.' </CANTIDAD_EMPLEADOS>&#013;&#010;';
										if(!empty($recSYC->cuit)) echo '	<CUIT> '.$recSYC->cuit.' </CUIT>&#013;&#010;';
										if(!empty($recSYC->razon)) echo '	<RAZON_SOCIAL> '.$recSYC->razon.' </RAZON_SOCIAL>&#013;&#010;';
										if(!empty($recSYC->domicilio)) echo '	<DOMICILIO> '.$recSYC->domicilio.' </DOMICILIO>&#013;&#010;';
										if(!empty($recSYC->localidad)) echo '	<LOCALIDAD> '.$recSYC->localidad.' </LOCALIDAD>&#013;&#010;';
										if(!empty($recSYC->cp)) echo '	<CODIGO_POSTAL> '.$recSYC->cp.' </CODIGO_POSTAL>&#013;&#010;';
										if(!empty($recSYC->provincia)) echo '	<PROVINCIA> '.$recSYC->provincia.' </PROVINCIA>&#013;&#010;';
										
										if(count($estado_fin_cli->Sysem_[0]->row) > 1 && $contadorRecC23 < count($estado_fin_cli->Sysem_[0]->row)) echo '&#013;&#010;';
									}
									echo '</SYSEM>&#013;&#010;&#013;&#010;';
								}

								if(!empty($estado_fin_cli->Jyj_mensual_[0]))
								{
									echo '<SYSEM>&#013;&#010;';
									$contadorRecC24 = 0;
									foreach ($estado_fin_cli->Jyj_mensual_[0]->row as $recJYJC) 
									{							
										$contadorRecC24++;
										if(!empty($recJYJC->cuil)) echo '	<CUIL> '.$recJYJC->cuil.' </CUIL>&#013;&#010;';
										if(!empty($recJYJC->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recJYJC->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
										if(!empty($recJYJC->rango_socio_econom)) echo '	<RANGO_SOCIO_ECONOMICO> '.$recJYJC->rango_socio_econom.' </RANGO_SOCIO_ECONOMICO>&#013;&#010;';
										
										if(count($estado_fin_cli->Jyj_mensual_[0]->row) > 1 && $contadorRecC24 < count($estado_fin_cli->Jyj_mensual_[0]->row)) echo '&#013;&#010;';
									}
									echo '</SYSEM>&#013;&#010;&#013;&#010;';
								}

								if(!empty($estado_fin_cli->Situacionlaboral_[0]))
								{
									echo '<SITUACION_LABORAL>&#013;&#010;';
									$contadorRecC25 = 0;
									foreach ($estado_fin_cli->Situacionlaboral_[0]->row as $recSLC) 
									{							
										$contadorRecC25++;
										if(!empty($recSLC->situacionlaboral)) echo '	<ESTADO_LABORAL> '.$recSLC->situacionlaboral.' </ESTADO_LABORAL>&#013;&#010;';
										
										if(count($estado_fin_cli->Situacionlaboral_[0]->row) > 1 && $contadorRecC25 < count($estado_fin_cli->Situacionlaboral_[0]->row)) echo '&#013;&#010;';
									}
									echo '</SITUACION_LABORAL>&#013;&#010;&#013;&#010;';
								}

								if(!empty($estado_fin_cli->Producto_financieros_[0]))
								{
									echo '<PRODUCTO_FINANCIEROS>&#013;&#010;';
									$contadorRecC26 = 0;
									foreach ($estado_fin_cli->Producto_financieros_[0]->row as $recPFC) 
									{							
										$contadorRecC26++;
										if(!empty($recPFC->producto_financieros)) echo '	<PRODUCTO> '.$recPFC->producto_financieros.' </PRODUCTO>&#013;&#010;';
										
										if(count($estado_fin_cli->Producto_financieros_[0]->row) > 1 && $contadorRecC26 < count($estado_fin_cli->Producto_financieros_[0]->row)) echo '&#013;&#010;';
									}
									echo '</PRODUCTO_FINANCIEROS>&#013;&#010;&#013;&#010;';
								}

								if(!empty($estado_fin_cli->Bancarizado_[0]))
								{
									echo '<BANCARIZADO>&#013;&#010;';
									$contadorRecC27 = 0;
									foreach ($estado_fin_cli->Bancarizado_[0]->row as $recEBC) 
									{							
										$contadorRecC27++;
										if(!empty($recEBC->bancarizado)) echo '	<ESTADO> '.$recEBC->bancarizado.' </ESTADO>&#013;&#010;';
										
										if(count($estado_fin_cli->Bancarizado_[0]->row) > 1 && $contadorRecC27 < count($estado_fin_cli->Bancarizado_[0]->row)) echo '&#013;&#010;';
									}
									echo '</BANCARIZADO>&#013;&#010;&#013;&#010;';
								}

								if(!empty($estado_fin_cli->FechaMaxSistFin_[0]))
								{
									echo '<FECHA_MAXIMA_SISTEMA_FINANCIERO>&#013;&#010;';
									$contadorRecC28 = 0;
									foreach ($estado_fin_cli->FechaMaxSistFin_[0]->row as $recFMSF) 
									{							
										$contadorRecC28++;
										if(!empty($recFMSF->fecha)) echo '	<FECHA> '.$recFMSF->fecha.' </FECHA>&#013;&#010;';
										
										if(count($estado_fin_cli->FechaMaxSistFin_[0]->row) > 1 && $contadorRecC28 < count($estado_fin_cli->FechaMaxSistFin_[0]->row)) echo '&#013;&#010;';
									}
									echo '</FECHA_MAXIMA_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
								}	

								if(!empty($estado_fin_cli->SistFinEvolucionDeuda_[0]))
								{
									echo '<EVOLUCION_DEUDA_SISTEMA_FINANCIERO>&#013;&#010;';
									$contadorRecC29 = 0;
									foreach ($estado_fin_cli->SistFinEvolucionDeuda_[0]->row as $recEDSF) 
									{							
										$contadorRecC29++;
										if(!empty($recEDSF->entidad)) echo '	<ENTIDAD> '.$recEDSF->entidad.' </ENTIDAD>&#013;&#010;';
										if(!empty($recEDSF->m1)) echo '	<MES1> '.$recEDSF->m1.' </MES1>&#013;&#010;';
										if(!empty($recEDSF->m2)) echo '	<MES2> '.$recEDSF->m2.' </MES2>&#013;&#010;';
										if(!empty($recEDSF->m3)) echo '	<MES3> '.$recEDSF->m3.' </MES3>&#013;&#010;';
										if(!empty($recEDSF->m4)) echo '	<MES4> '.$recEDSF->m4.' </MES4>&#013;&#010;';
										if(!empty($recEDSF->m5)) echo '	<MES5> '.$recEDSF->m5.' </MES5>&#013;&#010;';
										if(!empty($recEDSF->m6)) echo '	<MES6> '.$recEDSF->m6.' </MES6>&#013;&#010;';
										if(!empty($recEDSF->m7)) echo '	<MES7> '.$recEDSF->m7.' </MES7>&#013;&#010;';
										if(!empty($recEDSF->m8)) echo '	<MES8> '.$recEDSF->m8.' </MES8>&#013;&#010;';
										if(!empty($recEDSF->m9)) echo '	<MES9> '.$recEDSF->m9.' </MES9>&#013;&#010;';
										if(!empty($recEDSF->m10)) echo '	<MES10> '.$recEDSF->m10.' </MES10>&#013;&#010;';
										if(!empty($recEDSF->m11)) echo '	<MES11> '.$recEDSF->m11.' </MES11>&#013;&#010;';
										if(!empty($recEDSF->m12)) echo '	<MES12> '.$recEDSF->m12.' </MES12>&#013;&#010;';
										if(!empty($recEDSF->m13)) echo '	<MES13> '.$recEDSF->m13.' </MES13>&#013;&#010;';
										if(!empty($recEDSF->m14)) echo '	<MES14> '.$recEDSF->m14.' </MES14>&#013;&#010;';
										if(!empty($recEDSF->m15)) echo '	<MES15> '.$recEDSF->m15.' </MES15>&#013;&#010;';
										if(!empty($recEDSF->m16)) echo '	<MES16> '.$recEDSF->m16.' </MES16>&#013;&#010;';
										if(!empty($recEDSF->m17)) echo '	<MES17> '.$recEDSF->m17.' </MES17>&#013;&#010;';
										if(!empty($recEDSF->m18)) echo '	<MES18> '.$recEDSF->m18.' </MES18>&#013;&#010;';
										if(!empty($recEDSF->m19)) echo '	<MES19> '.$recEDSF->m19.' </MES19>&#013;&#010;';
										if(!empty($recEDSF->m20)) echo '	<MES20> '.$recEDSF->m20.' </MES20>&#013;&#010;';
										if(!empty($recEDSF->m21)) echo '	<MES21> '.$recEDSF->m21.' </MES21>&#013;&#010;';
										if(!empty($recEDSF->m22)) echo '	<MES22> '.$recEDSF->m22.' </MES22>&#013;&#010;';
										if(!empty($recEDSF->m23)) echo '	<MES23> '.$recEDSF->m23.' </MES23>&#013;&#010;';
										if(!empty($recEDSF->m24)) echo '	<MES24> '.$recEDSF->m24.' </MES24>&#013;&#010;';									
										if(!empty($recEDSF->deuda_actual)) echo '	<DEUDA_ACTUAL> '.$recEDSF->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
										if(!empty($recEDSF->monto_maximo)) echo '	<MONTO_MAXIMO> '.$recEDSF->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
										if(!empty($recEDSF->bco)) echo '	<BANCO> '.$recEDSF->bco.' </BANCO>&#013;&#010;';
										
										if(count($estado_fin_cli->SistFinEvolucionDeuda_[0]->row) > 1 && $contadorRecC29 < count($estado_fin_cli->SistFinEvolucionDeuda_[0]->row)) echo '&#013;&#010;';
									}
									echo '</EVOLUCION_DEUDA_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
								}

								if(!empty($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]))
								{
									echo '<EVOLUCION_DEUDA_PROPIO_SISTEMA_FINANCIERO>&#013;&#010;';
									$contadorRecC30 = 0;
									foreach ($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row as $recEDPSF) 
									{							
										$contadorRecC30++;
										if(!empty($recEDPSF->entidad)) echo '	<ENTIDAD> '.$recEDPSF->entidad.' </ENTIDAD>&#013;&#010;';
										if(!empty($recEDPSF->m1)) echo '	<MES1> '.$recEDPSF->m1.' </MES1>&#013;&#010;';
										if(!empty($recEDPSF->m2)) echo '	<MES2> '.$recEDPSF->m2.' </MES2>&#013;&#010;';
										if(!empty($recEDPSF->m3)) echo '	<MES3> '.$recEDPSF->m3.' </MES3>&#013;&#010;';
										if(!empty($recEDPSF->m4)) echo '	<MES4> '.$recEDPSF->m4.' </MES4>&#013;&#010;';
										if(!empty($recEDPSF->m5)) echo '	<MES5> '.$recEDPSF->m5.' </MES5>&#013;&#010;';
										if(!empty($recEDPSF->m6)) echo '	<MES6> '.$recEDPSF->m6.' </MES6>&#013;&#010;';
										if(!empty($recEDPSF->m7)) echo '	<MES7> '.$recEDPSF->m7.' </MES7>&#013;&#010;';
										if(!empty($recEDPSF->m8)) echo '	<MES8> '.$recEDPSF->m8.' </MES8>&#013;&#010;';
										if(!empty($recEDPSF->m9)) echo '	<MES9> '.$recEDPSF->m9.' </MES9>&#013;&#010;';
										if(!empty($recEDPSF->m10)) echo '	<MES10> '.$recEDPSF->m10.' </MES10>&#013;&#010;';
										if(!empty($recEDPSF->m11)) echo '	<MES11> '.$recEDPSF->m11.' </MES11>&#013;&#010;';
										if(!empty($recEDPSF->m12)) echo '	<MES12> '.$recEDPSF->m12.' </MES12>&#013;&#010;';
										if(!empty($recEDPSF->m13)) echo '	<MES13> '.$recEDPSF->m13.' </MES13>&#013;&#010;';
										if(!empty($recEDPSF->m14)) echo '	<MES14> '.$recEDPSF->m14.' </MES14>&#013;&#010;';
										if(!empty($recEDPSF->m15)) echo '	<MES15> '.$recEDPSF->m15.' </MES15>&#013;&#010;';
										if(!empty($recEDPSF->m16)) echo '	<MES16> '.$recEDPSF->m16.' </MES16>&#013;&#010;';
										if(!empty($recEDPSF->m17)) echo '	<MES17> '.$recEDPSF->m17.' </MES17>&#013;&#010;';
										if(!empty($recEDPSF->m18)) echo '	<MES18> '.$recEDPSF->m18.' </MES18>&#013;&#010;';
										if(!empty($recEDPSF->m19)) echo '	<MES19> '.$recEDPSF->m19.' </MES19>&#013;&#010;';
										if(!empty($recEDPSF->m20)) echo '	<MES20> '.$recEDPSF->m20.' </MES20>&#013;&#010;';
										if(!empty($recEDPSF->m21)) echo '	<MES21> '.$recEDPSF->m21.' </MES21>&#013;&#010;';
										if(!empty($recEDPSF->m22)) echo '	<MES22> '.$recEDPSF->m22.' </MES22>&#013;&#010;';
										if(!empty($recEDPSF->m23)) echo '	<MES23> '.$recEDPSF->m23.' </MES23>&#013;&#010;';
										if(!empty($recEDPSF->m24)) echo '	<MES24> '.$recEDPSF->m24.' </MES24>&#013;&#010;';									
										if(!empty($recEDPSF->deuda_actual)) echo '	<DEUDA_ACTUAL> '.$recEDPSF->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
										if(!empty($recEDPSF->monto_maximo)) echo '	<MONTO_MAXIMO> '.$recEDPSF->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
										if(!empty($recEDPSF->bco)) echo '	<BANCO> '.$recEDPSF->bco.' </BANCO>&#013;&#010;';
										
										if(count($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row) > 1 && $contadorRecC30 < count($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row)) echo '&#013;&#010;';
									}
									echo '</EVOLUCION_DEUDA_PROPIO_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
								}							
								
								if(!empty($estado_fin_cli->Bcra_Ultimas_Situaciones[0]))
								{
									echo '<BANCO_CENTRAL_ULTIMAS_SITUACIONES>&#013;&#010;';
									$contadorRecC31 = 0;
									foreach ($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row as $recBCUS) 
									{							
										$contadorRecC31++;
										if(!empty($recBCUS->entidad)) echo '	<ENTIDAD> '.$recBCUS->entidad.' </ENTIDAD>&#013;&#010;';
										if(!empty($recBCUS->periodo)) echo '	<PERIODO> '.$recBCUS->periodo.' </PERIODO>&#013;&#010;';
										if(!empty($recBCUS->situacion)) echo '	<SITUACION> '.$recBCUS->situacion.' </SITUACION>&#013;&#010;';
										if(!empty($recBCUS->monto)) echo '	<MONTO> '.$recBCUS->monto.' </MONTO>&#013;&#010;';
										
										if(count($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row) > 1 && $contadorRecC31 < count($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row)) echo '&#013;&#010;';
									}
									echo '</BANCO_CENTRAL_ULTIMAS_SITUACIONES>&#013;&#010;&#013;&#010;';
								}
								
								if(!empty($estado_fin_cli->Bcra_Situaciones_Vigente[0]))
								{
									echo '<BANCO_CENTRAL_SITUACIONES_VIGENTES>&#013;&#010;';
									$contadorRecC32 = 0;
									foreach ($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row as $recBCSV) 
									{							
										$contadorRecC32++;
										if(!empty($recBCSV->entidad)) echo '	<ENTIDAD> '.$recBCSV->entidad.' </ENTIDAD>&#013;&#010;';
										if(!empty($recBCSV->periodos)) echo '	<PERIODOS> '.$recBCSV->periodos.' </PERIODOS>&#013;&#010;';
										if(!empty($recBCSV->situacion)) echo '	<SITUACION> '.$recBCSV->situacion.' </SITUACION>&#013;&#010;';
										if(!empty($recBCSV->monto)) echo '	<MONTO> '.$recBCSV->monto.' </MONTO>&#013;&#010;';
										
										if(count($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row) > 1 && $contadorRecC32 < count($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row)) echo '&#013;&#010;';
									}
									echo '</BANCO_CENTRAL_SITUACIONES_VIGENTES>&#013;&#010;&#013;&#010;';
								}							
								
								if(!empty($estado_fin_cli->Tipo_Actividad[0]))
								{							
									echo '<TIPO_ACTIVIDAD>&#013;&#010;';
									$contadorRecC33 = 0;
									foreach ($estado_fin_cli->Tipo_Actividad[0]->row as $recTAC) 
									{								
										$contadorRecC33++;
										echo '	<TIPO_ACTIVIDAD> '.$recTAC->tipo_actividad.' </TIPO_ACTIVIDAD>&#013;&#010;';

										if(count($estado_fin_cli->Tipo_Actividad[0]->row) > 1 && $contadorRecC33 < count($estado_fin_cli->Tipo_Actividad[0]->row)) echo '&#013;&#010;';								
									}
									echo '</TIPO_ACTIVIDAD>&#013;&#010;&#013;&#010;';	
								}								
						
								if(!empty($estado_fin_cli->Consultas_Individual_[0]))
								{
									echo '<CONSULTAS_INDIVIDUALES>&#013;&#010;';
									$contadorRecC34 = 0;
									foreach ($estado_fin_cli->Consultas_Individual_[0]->row as $recCINDC) 
									{							
										$contadorRecC34++;
										if(!empty($recCINDC->dia)) echo '	<DIA> '.$recCINDC->dia.' </DIA>&#013;&#010;';
										if(!empty($recCINDC->semana)) echo '	<SEMANA> '.$recCINDC->semana.' </SEMANA>&#013;&#010;';
										if(!empty($recCINDC->mes)) echo '	<MES> '.$recCINDC->mes.' </MES>&#013;&#010;';
										if(!empty($recCINDC->m24)) echo '	<M24> '.$recCINDC->m24.' </M24>&#013;&#010;';
										
										if(count($estado_fin_cli->Consultas_Individual_[0]->row) > 1 && $contadorRecC34 < count($estado_fin_cli->Consultas_Individual_[0]->row)) echo '&#013;&#010;';
									}
									echo '</CONSULTAS_INDIVIDUALES>&#013;&#010;&#013;&#010;';
								}

								if(!empty($estado_fin_cli->Consultas_Grupo_[0]))
								{
									echo '<CONSULTAS_GRUPO>&#013;&#010;';
									$contadorRecC35 = 0;
									foreach ($estado_fin_cli->Consultas_Grupo_[0]->row as $recCGRUC) 
									{							
										$contadorRecC35++;
										if(!empty($recCGRUC->dia)) echo '	<DIA> '.$recCGRUC->dia.' </DIA>&#013;&#010;';
										if(!empty($recCGRUC->semana)) echo '	<SEMANA> '.$recCGRUC->semana.' </SEMANA>&#013;&#010;';
										if(!empty($recCGRUC->mes)) echo '	<MES> '.$recCGRUC->mes.' </MES>&#013;&#010;';
										if(!empty($recCGRUC->m24)) echo '	<M24> '.$recCGRUC->m24.' </M24>&#013;&#010;';
										
										if(count($estado_fin_cli->Consultas_Grupo_[0]->row) > 1 && $contadorRecC35 < count($estado_fin_cli->Consultas_Grupo_[0]->row)) echo '&#013;&#010;';
									}
									echo '</CONSULTAS_GRUPO>&#013;&#010;&#013;&#010;';
								}	

								if(!empty($estado_fin_cli->CONSULTADO_POR_[0]))
								{
									echo '<CONSULTADO_POR>&#013;&#010;';
									$contadorRecC36 = 0;
									foreach ($estado_fin_cli->CONSULTADO_POR_[0]->row as $recCCONP) 
									{							
										$contadorRecC36++;
										if(!empty($recCCONP->fecha)) echo '	<FECHA> '.$recCCONP->fecha.' </FECHA>&#013;&#010;';
										if(!empty($recCCONP->consultante)) echo '	<CONSULTANTE> '.$recCCONP->consultante.' </CONSULTANTE>&#013;&#010;';
										
										if(count($estado_fin_cli->CONSULTADO_POR_[0]->row) > 1 && $contadorRecC36 < count($estado_fin_cli->CONSULTADO_POR_[0]->row)) echo '&#013;&#010;';
									}
									echo '</CONSULTADO_POR>&#013;&#010;&#013;&#010;';
								}							
						
								if(!empty($estado_fin_cli->Independientes_[0]))
								{
									echo '<INDEPENDIENTES>&#013;&#010;';
									$contadorRecC37 = 0;
									foreach ($estado_fin_cli->Independientes_[0]->row as $recCONIND) 
									{							
										$contadorRecC37++;
										if(!empty($recCONIND->inscripto)) echo '	<INSCRIPTO> '.$recCONIND->inscripto.' </INSCRIPTO>&#013;&#010;';
										if(!empty($recCONIND->categoria)) echo '	<CATEGORIA> '.$recCONIND->categoria.' </CATEGORIA>&#013;&#010;';
										if(!empty($recCONIND->cuit)) echo '	<CUIT> '.$recCONIND->cuit.' </CUIT>&#013;&#010;';
										if(!empty($recCONIND->documento)) echo '	<DOCUMENTO> '.$recCONIND->documento.' </DOCUMENTO>&#013;&#010;';
										if(!empty($recCONIND->nombre)) echo '	<NOMBRE> '.$recCONIND->nombre.' </NOMBRE>&#013;&#010;';
										if(!empty($recCONIND->domicilio)) echo '	<DOMICILIO> '.$recCONIND->domicilio.' </DOMICILIO>&#013;&#010;';
										if(!empty($recCONIND->cod_postal)) echo '	<CODIGO_POSTAL> '.$recCONIND->cod_postal.' </CODIGO_POSTAL>&#013;&#010;';
										if(!empty($recCONIND->localidad)) echo '	<LOCALIDAD> '.$recCONIND->localidad.' </LOCALIDAD>&#013;&#010;';
										if(!empty($recCONIND->provincia)) echo '	<PROVINCIA> '.$recCONIND->provincia.' </PROVINCIA>&#013;&#010;';
										if(!empty($recCONIND->fecha_nac)) echo '	<FECHA_NACIMIENTO> '.$recCONIND->fecha_nac.' </FECHA_NACIMIENTO>&#013;&#010;';
										if(!empty($recCONIND->entidad)) echo '	<ENTIDAD> '.$recCONIND->entidad.' </ENTIDAD>&#013;&#010;';
										if(!empty($recCONIND->actividad)) echo '	<ACTIVIDAD> '.$recCONIND->actividad.' </ACTIVIDAD>&#013;&#010;';
										if(!empty($recCONIND->periodo)) echo '	<PERIODO> '.$recCONIND->periodo.' </PERIODO>&#013;&#010;';
										if(!empty($recCONIND->monto)) echo '	<MONTO> '.$recCONIND->monto.' </MONTO>&#013;&#010;';									
										
										if(count($estado_fin_cli->Independientes_[0]->row) > 1 && $contadorRecC37 < count($estado_fin_cli->Independientes_[0]->row)) echo '&#013;&#010;';
									}
									echo '</INDEPENDIENTES>&#013;&#010;&#013;&#010;';
								}
								
								if(!empty($estado_fin_cli->Indep_apo_[0]))
								{
									echo '<APORTES_INDEPENDIENTE>&#013;&#010;';
									$contadorRecC38 = 0;
									foreach ($estado_fin_cli->Indep_apo_[0]->row as $recAPCI) 
									{							
										$contadorRecC38++;
										if(!empty($recAPCI->cuit)) echo '	<CUIT> '.$recAPCI->cuit.' </CUIT>&#013;&#010;';
										if(!empty($recAPCI->periodo)) echo '	<PERIODO> '.$recAPCI->periodo.' </PERIODO>&#013;&#010;';
										if(!empty($recAPCI->imp_pago)) echo '	<IMPORTE> '.$recAPCI->imp_pago.' </IMPORTE>&#013;&#010;';
										if(!empty($recAPCI->impuesto)) echo '	<IMPUESTO> '.$recAPCI->impuesto.' </IMPUESTO>&#013;&#010;';
										
										if(count($estado_fin_cli->Indep_apo_[0]->row) > 1 && $contadorRecC38 < count($estado_fin_cli->Indep_apo_[0]->row)) echo '&#013;&#010;';
									}
									echo '</APORTES_INDEPENDIENTE>&#013;&#010;&#013;&#010;';
								}							
								
								if(!empty($estado_fin_cli->Propiedades_[0]))
								{
									echo '<PROPIEDADES>&#013;&#010;';
									$contadorRecC39 = 0;
									foreach ($estado_fin_cli->Propiedades_[0]->row as $recIPROPC) 
									{							
										$contadorRecC39++;
										if(!empty($recIPROPC->titular)) echo '	<TITULAR> '.$recIPROPC->titular.' </TITULAR>&#013;&#010;';
										if(!empty($recIPROPC->folio)) echo '	<FOLIO> '.$recIPROPC->folio.' </FOLIO>&#013;&#010;';
										if(!empty($recIPROPC->ano_inscripcion)) echo '	<AÑO_INSCRIPCION> '.$recIPROPC->ano_inscripcion.' </AÑO_INSCRIPCION>&#013;&#010;';
										if(!empty($recIPROPC->ph)) echo '	<PH> '.$recIPROPC->ph.' </PH>&#013;&#010;';
										if(!empty($recIPROPC->condom)) echo '	<CONDOM> '.$recIPROPC->condom.' </CONDOM>&#013;&#010;';
										if(!empty($recIPROPC->codigo_departamento)) echo '	<CODIGO_DEPARTAMENTO> '.$recIPROPC->codigo_departamento.' </CODIGO_DEPARTAMENTO>&#013;&#010;';
										if(!empty($recIPROPC->pedania)) echo '	<PEDANIA> '.$recIPROPC->pedania.' </PEDANIA>&#013;&#010;';
										if(!empty($recIPROPC->pueblo)) echo '	<PUEBLO> '.$recIPROPC->pueblo.' </PUEBLO>&#013;&#010;';
										if(!empty($recIPROPC->circuncripcion)) echo '	<CIRCUNCRIPCION> '.$recIPROPC->circuncripcion.' </CIRCUNCRIPCION>&#013;&#010;';
										if(!empty($recIPROPC->seccion)) echo '	<SECCION> '.$recIPROPC->seccion.' </SECCION>&#013;&#010;';
										if(!empty($recIPROPC->manzana)) echo '	<MANZANA> '.$recIPROPC->manzana.' </MANZANA>&#013;&#010;';
										if(!empty($recIPROPC->parcela)) echo '	<PARCELA> '.$recIPROPC->parcela.' </PARCELA>&#013;&#010;';
										if(!empty($recIPROPC->escrituro)) echo '	<ESCRITURO> '.$recIPROPC->escrituro.' </ESCRITURO>&#013;&#010;';
										
										if(count($estado_fin_cli->Propiedades_[0]->row) > 1 && $contadorRecC39 < count($estado_fin_cli->Propiedades_[0]->row)) echo '&#013;&#010;';
									}
									echo '</PROPIEDADES>&#013;&#010;&#013;&#010;';
								}
								echo '						</textarea>';
								echo '					</div>';		
								echo '				</div>';								
							}
							else
							{
								if($stmt44 = $mysqli->prepare("SELECT e.id FROM ".$db_name.".estado_cliente e WHERE e.tipo_documento = ? AND e.documento = ? AND e.fecha like ? AND e.id_motivo IN (?,?)"))
								{
									$date_registro_c_ecef = date("Ymd")."%";
									$motivo2_u = 51;
									$stmt44->bind_param('issii', $tipoDocumento, $documento, $date_registro_c_ecef, $motivo, $motivo2_u);
									$stmt44->execute();    
									$stmt44->store_result();
									
									$totR44 = $stmt44->num_rows;

									if($totR44 == 0)
									{
										$estado_fin_cli = new SimpleXMLElement($resultado_finan_cli_final);
										echo translate('Msg_Validation_Credit_Status_Client_OK',$GLOBALS['lang']).'=::=::=::'.$tokenECF.'=:=:=:';
										echo '<div class="panel-group">';				
										echo '	<div class="panel panel-default">';
										echo '		<div id="panel-title-header" class="panel-heading">';
										echo '			<h3 class="panel-title">'.translate('Lbl_Result_Financial_Statement_Client',$GLOBALS['lang']).'</h3>';
										echo ' 		</div>';
										echo '		<div class="panel-body">';
										echo '			<form id="formulariocefcm" role="form" onsubmit="buscarTextoEstadoFinancieroM(); return false;">';		
										echo '				<div class="form-group form-inline">';
										echo '					<div class="form-group" id="buscartextoestadocrediticioclientem">';
										echo '						<input class="form-control input-sm" id="buscartextoestadocrediticioclientemi" name="buscartextoestadocrediticioclientemi" type="text" maxlength="150" />';
										echo '						&nbsp;<button type="button" class="btn" id="btnBuscarEstadoFM" name="btnBuscarEstadoFM" title="'.translate('Lbl_Search_Text_Statement_Client',$GLOBALS['lang']).'" onclick="buscarTextoEstadoFinancieroM();"><i class="fas fa-search"></i></button>';									
										echo '					</div>';
										echo '				</div>';
										echo '				<div class="form-group form-inline">';					
										echo '					<div class="form-group" id="resultadoestadofinancieroclientem">';
										echo '						<textarea rows="3" cols="67" class="form-control input-sm" id="resultadoestadofinancieroclientemi" name="resultadoestadofinancieroclientemi">';
										echo '<EXISTENCIA_FISICA_ENTIDAD>&#013;&#010;';
										$contadorRecC1 = 0;
										foreach ($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row as $recEFC) 
										{
											$contadorRecC1++;
											if(!empty($recEFC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recEFC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
											if(!empty($recEFC->sexo)) echo '	<SEXO> '.$recEFC->sexo.' </SEXO>&#013;&#010;';
											if(!empty($recEFC->tipo_docu)) echo '	<TIPO_DOCUMENTO> '.$recEFC->tipo_docu.' </TIPO_DOCUMENTO>&#013;&#010;';
											if(!empty($recEFC->nume_doc)) echo '	<DOCUMENTO> '.$recEFC->nume_doc.' </DOCUMENTO>&#013;&#010;';
											if(!empty($recEFC->clase)) echo '	<CLASE> '.$recEFC->clase.' </CLASE>&#013;&#010;';
											if(!empty($recEFC->edad)) echo '	<EDAD> '.$recEFC->edad.' </EDAD>&#013;&#010;';
											if(!empty($recEFC->ocupacion)) echo '	<OCUPACION> '.$recEFC->ocupacion.' </OCUPACION>&#013;&#010;';
											if(!empty($recEFC->cdi_codigo_de_identificacion)) echo '	<CUIT_CUIL> '.$recEFC->cdi_codigo_de_identificacion.' </CUIT_CUIL>&#013;&#010;';								
											if(!empty($recEFC->fecha_nacimiento)) echo '	<FECHA_NACIMIENTO> '.$recEFC->fecha_nacimiento.' </FECHA_NACIMIENTO>&#013;&#010;';							
											if(!empty($recEFC->direc_calle)) echo '	<DIRECCION_CALLE> '.$recEFC->direc_calle.' </DIRECCION_CALLE>&#013;&#010;';							
											if(!empty($recEFC->localidad)) echo '	<LOCALIDAD> '.$recEFC->localidad.' </LOCALIDAD>&#013;&#010;';
											if(!empty($recEFC->departamento)) echo '	<DEPARTAMENTO> '.$recEFC->departamento.' </DEPARTAMENTO>&#013;&#010;';								
											if(!empty($recEFC->codigo_postal)) echo '	<CODIGO_POSTAL> '.$recEFC->codigo_postal.' </CODIGO_POSTAL>&#013;&#010;';							
											if(!empty($recEFC->provincia)) echo '	<PROVINCIA> '.$recEFC->provincia.' </PROVINCIA>&#013;&#010;';							
											if(!empty($recEFC->apellido_materno)) echo '	<APELLIDO_MATERNO> '.$recEFC->apellido_materno.' </APELLIDO_MATERNO>&#013;&#010;';
											if(!empty($recEFC->estado_civil)) echo '	<ESTADO_CIVIL> '.$recEFC->estado_civil.' </ESTADO_CIVIL>&#013;&#010;';								
											if(!empty($recEFC->fallecido)) echo '	<FALLECIDO> '.$recEFC->fallecido.' </FALLECIDO>&#013;&#010;';
											
											if(count($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row) > 1 && $contadorRecC1 < count($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row)) echo '&#013;&#010;';
										}							
										echo '</EXISTENCIA_FISICA_ENTIDAD>&#013;&#010;&#013;&#010;';
										
										if(!empty($estado_fin_cli->Score_[0]))
										{
											echo '<SCORE>&#013;&#010;';
											$contadorRecC2 = 0;
											foreach ($estado_fin_cli->Score_[0]->row as $recPIC) 
											{							
												$contadorRecC2++;
												if(!empty($recPIC->score)) echo '	<SCORE> '.$recPIC->score.' </SCORE>&#013;&#010;';
												
												if(count($estado_fin_cli->Score_[0]->row) > 1 && $contadorRecC2 < count($estado_fin_cli->Score_[0]->row)) echo '&#013;&#010;';
											}
											echo '</SCORE>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->Domi_xy_[0]))
										{
											echo '<DOMICILIO_XY>&#013;&#010;';
											$contadorRecC3 = 0;
											foreach ($estado_fin_cli->Domi_xy_[0]->row as $recTJQEC) 
											{								
												$contadorRecC3++;
												if(!empty($recTJQEC->nprovincia)) echo '	<N_PROVINCIA> '.$recTJQEC->nprovincia.' </N_PROVINCIA>&#013;&#010;';
												if(!empty($recTJQEC->npartido)) echo '	<N_PARTIDO> '.$recTJQEC->npartido.' </N_PARTIDO>&#013;&#010;';
												if(!empty($recTJQEC->nlocalidad)) echo '	<N_LOCALIDAD> '.$recTJQEC->nlocalidad.' </N_LOCALIDAD>&#013;&#010;';
												if(!empty($recTJQEC->nbarrio)) echo '	<N_BARRIO> '.$recTJQEC->nbarrio.' </N_BARRIO>&#013;&#010;';
												if(!empty($recTJQEC->calle)) echo '	<CALLE> '.$recTJQEC->calle.' </CALLE>&#013;&#010;';
												if(!empty($recTJQEC->ncp)) echo '	<NCP> '.$recTJQEC->ncp.' </NCP>&#013;&#010;';
												if(!empty($recTJQEC->geocordenadas)) echo '	<GEOCORDENADAS> '.$recTJQEC->geocordenadas.' </GEOCORDENADAS>&#013;&#010;';
												if(count($estado_fin_cli->Domi_xy_[0]->row) > 1 && $contadorRecC3 < count($estado_fin_cli->Domi_xy_[0]->row)) echo '&#013;&#010;';
											}
											echo '</DOMICILIO_XY>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->Situacion_BCRA[0]))
										{							
											echo '<SITUACION_BCRA>&#013;&#010;';
											$contadorRecC4 = 0;
											foreach ($estado_fin_cli->Situacion_BCRA[0]->row as $recDSF6M) 
											{								
												$contadorRecC4++;
												if(!empty($recDSF6M->entidad)) echo '	<ENTIDAD> '.$recDSF6M->entidad.' </ENTIDAD>&#013;&#010;';
												if(!empty($recDSF6M->fecha_sit)) echo '	<FECHA> '.$recDSF6M->fecha_sit.' </FECHA>&#013;&#010;';									
												if(!empty($recDSF6M->situacion)) echo '	<SITUACION> '.$recDSF6M->situacion.' </SITUACION>&#013;&#010;';
												if(!empty($recDSF6M->deuda_total))  echo '	<DEUDA_TOTAL> '.$recDSF6M->deuda_total.' </DEUDA_TOTAL>&#013;&#010;';

												if(count($estado_fin_cli->Situacion_BCRA[0]->row) > 1 && $contadorRecC4 < count($estado_fin_cli->Situacion_BCRA[0]->row)) echo '&#013;&#010;';								
											}
											echo '</SITUACION_BCRA>&#013;&#010;&#013;&#010;';	
										}
										
										if(!empty($estado_fin_cli->FraudesTarjeta_[0]))
										{
											echo '<FRAUDES_TARJETA>&#013;&#010;';
											$contadorRecC5 = 0;
											foreach ($estado_fin_cli->FraudesTarjeta_[0]->row as $recFTs) 
											{							
												$contadorRecC5++;
												if(!empty($recFTs->fraude)) echo '	<FRAUDE> '.$recFTs->fraude.' </FRAUDE>&#013;&#010;';
												
												if(count($estado_fin_cli->FraudesTarjeta_[0]->row) > 1 && $contadorRecC5 < count($estado_fin_cli->FraudesTarjeta_[0]->row)) echo '&#013;&#010;';
											}
											echo '</FRAUDES_TARJETA>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->CapacidadEndeudamiento_[0]))
										{
											echo '<CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;';
											$contadorRecC6 = 0;
											foreach ($estado_fin_cli->CapacidadEndeudamiento_[0]->row as $recCPEC) 
											{							
												$contadorRecC6++;
												if(!empty($recCPEC->capacidad_endeudamiento)) echo '	<CAPACIDAD_ENDEUDAMIENTO> '.$recCPEC->capacidad_endeudamiento.' </CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;';
												
												if(count($estado_fin_cli->CapacidadEndeudamiento_[0]->row) > 1 && $contadorRecC6 < count($estado_fin_cli->CapacidadEndeudamiento_[0]->row)) echo '&#013;&#010;';
											}
											echo '</CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;&#013;&#010;';
										}							
										
										if(!empty($estado_fin_cli->Ooss_personas_[0]))
										{							
											echo '<OBRA_SOCIAL_PERSONAS>&#013;&#010;';
											$contadorRecC7 = 0;
											foreach ($estado_fin_cli->Ooss_personas_[0]->row as $recDSF12M) 
											{								
												$contadorRecC7++;
												if(!empty($recDSF12M->cuil)) echo '	<CUIL> '.$recDSF12M->cuil.' </CUIL>&#013;&#010;';
												if(!empty($recDSF12M->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recDSF12M->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
												if(!empty($recDSF12M->nume_docu)) echo '	<DOCUMENTO> '.$recDSF12M->nume_docu.' </DOCUMENTO>&#013;&#010;';
												if(!empty($recDSF12M->sexo)) echo '	<SEXO> '.$recDSF12M->sexo.' </SEXO>&#013;&#010;';
												if(!empty($recDSF12M->domicilio)) echo '	<DOMICILIO> '.$recDSF12M->domicilio.' </DOMICILIO>&#013;&#010;';
												if(!empty($recDSF12M->localidad)) echo '	<LOCALIDAD> '.$recDSF12M->localidad.' </LOCALIDAD>&#013;&#010;';
												if(!empty($recDSF12M->provincia)) echo '	<PROVINCIA> '.$recDSF12M->provincia.' </PROVINCIA>&#013;&#010;';
												if(!empty($recDSF12M->cod_postal)) echo '	<CODIGO_POSTAL> '.$recDSF12M->cod_postal.' </CODIGO_POSTAL>&#013;&#010;';									

												if(count($estado_fin_cli->Ooss_personas_[0]->row) > 1 && $contadorRecC7 < count($estado_fin_cli->Ooss_personas_[0]->row)) echo '&#013;&#010;';								
											}
											echo '</OBRA_SOCIAL_PERSONAS>&#013;&#010;&#013;&#010;';	
										}
										
										if(!empty($estado_fin_cli->Obra_Social_Rel_[0]))
										{							
											echo '<OBRA_SOCIAL_RELACION>&#013;&#010;';
											$contadorRecC8 = 0;
											foreach ($estado_fin_cli->Obra_Social_Rel_[0]->row as $recDSF24M) 
											{								
												$contadorRecC8++;
												if(!empty($recDSF24M->codiobra)) echo '	<CODIGO> '.$recDSF24M->codiobra.' </CODIGO>&#013;&#010;';
												if(!empty($recDSF24M->descripcion_ooss)) echo '	<DESCRIPCION> '.$recDSF24M->descripcion_ooss.' </DESCRIPCION>&#013;&#010;';
												if(!empty($recDSF24M->sigla_ooss)) echo '	<SIGLA> '.$recDSF24M->sigla_ooss.' </SIGLA>&#013;&#010;';
												if(!empty($recDSF24M->cobertura)) echo '	<COBERTURA> '.$recDSF24M->cobertura.' </COBERTURA>&#013;&#010;';
												if(!empty($recDSF24M->conyugue)) echo '	<CONYUGUE> '.$recDSF24M->conyugue.' </CONYUGUE>&#013;&#010;';
												if(!empty($recDSF24M->hijos)) echo '	<HIJOS> '.$recDSF24M->hijos.' </HIJOS>&#013;&#010;';

												if(count($estado_fin_cli->Obra_Social_Rel_[0]->row) > 1 && $contadorRecC8 < count($estado_fin_cli->Obra_Social_Rel_[0]->row)) echo '&#013;&#010;';								
											}
											echo '</OBRA_SOCIAL_RELACION>&#013;&#010;&#013;&#010;';	
										}
										
										if(!empty($estado_fin_cli->Domicilio_Empresa_trabaja_[0]))
										{
											echo '<DOMICILIO_LABORAL>&#013;&#010;';
											$contadorRecC9 = 0;
											foreach ($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row as $recRDC) 
											{								
												$contadorRecC9++;
												if(!empty($recRDC->direccion)) echo '	<DIRECCION> '.$recRDC->ult_periodo.' </DIRECCION>&#013;&#010;';
												if(!empty($recRDC->localidad)) echo '	<LOCALIDAD> '.$recRDC->alta_trabajo_ultimo.' </LOCALIDAD>&#013;&#010;';
												if(!empty($recRDC->provincia)) echo '	<PROVINCIA> '.$recRDC->cuit.' </PROVINCIA>&#013;&#010;';
												if(!empty($recRDC->cp)) echo '	<CODIGO_POSTAL> '.$recRDC->razon_social.' </CODIGO_POSTAL>&#013;&#010;';

												if(count($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row) > 1 && $contadorRecC9 < count($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row)) echo '&#013;&#010;';								
											}
											echo '</DOMICILIO LABORAL>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->domicilio_otros_[0]))
										{							
											echo '<OTROS_DOMICILIOS>&#013;&#010;';
											$contadorRecC10 = 0;
											foreach ($estado_fin_cli->domicilio_otros_[0]->row as $recCIAC) 
											{								
												$contadorRecC10++;
												if(!empty($recCIAC->domicilio)) echo '	<DOMICILIO> '.$recCIAC->domicilio.' </DOMICILIO>&#013;&#010;';
												if(!empty($recCIAC->localidad)) echo '	<LOCALIDAD> '.$recCIAC->localidad.' </LOCALIDAD>&#013;&#010;';
												if(!empty($recCIAC->cp)) echo '	<CP> '.$recCIAC->cp.' </CP>&#013;&#010;';
												if(!empty($recCIAC->provincia)) echo '	<PROVINCIA> '.$recCIAC->provincia.' </PROVINCIA>&#013;&#010;';

												if(count($estado_fin_cli->domicilio_otros_[0]->row) > 1 && $contadorRecC10 < count($estado_fin_cli->domicilio_otros_[0]->row)) echo '&#013;&#010;';								
											}
											echo '</OTROS_DOMICILIOS>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->Domicilios_Juicios_[0]))
										{
											echo '<DOMICILIOS_JUICIOS>&#013;&#010;';
											$contadorRecC11 = 0;
											foreach ($estado_fin_cli->Domicilios_Juicios_[0]->row as $recDJD) 
											{							
												$contadorRecC11++;
												if(!empty($recDJD->domicilio)) echo '	<DOMICILIO> '.$recDJD->domicilio.' </DOMICILIO>&#013;&#010;';
												
												if(count($estado_fin_cli->Domicilios_Juicios_[0]->row) > 1 && $contadorRecC11 < count($estado_fin_cli->Domicilios_Juicios_[0]->row)) echo '&#013;&#010;';
											}
											echo '</DOMICILIOS_JUICIOS>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->Padronempresasf_[0]))
										{							
											echo '<PADRON_EMPRESAS>&#013;&#010;';
											$contadorRecC12 = 0;
											foreach ($estado_fin_cli->Padronempresasf_[0]->row as $recPMC) 
											{								
												$contadorRecC12++;
												if(!empty($recPMC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recPMC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
												if(!empty($recPMC->cuit)) echo '	<CUIT> '.$recPMC->cuit.' </CUIT>&#013;&#010;';
												if(!empty($recPMC->domicilio_fiscal)) echo '	<DOMICILIO_FISCAL> '.$recPMC->domicilio_fiscal.' </DOMICILIO_FISCAL>&#013;&#010;';
												if(!empty($recPMC->provincia)) echo '	<PROVINCIA> '.$recPMC->provincia.' </PROVINCIA>&#013;&#010;';
												if(!empty($recPMC->actividad_especifica)) echo '	<ACTIVIDAD_ESPECIFICA> '.$recPMC->actividad_especifica.' </ACTIVIDAD_ESPECIFICA>&#013;&#010;';
												if(!empty($recPMC->categoria_autonomo)) echo '	<CATEGORIA_AUTONOMO> '.$recPMC->categoria_autonomo.' </CATEGORIA_AUTONOMO>&#013;&#010;';

												if(count($estado_fin_cli->Padronempresasf_[0]->row) > 1 && $contadorRecC12 < count($estado_fin_cli->Padronempresasf_[0]->row)) echo '&#013;&#010;';									
											}
											echo '</PADRON_EMPRESAS>&#013;&#010;&#013;&#010;';							
										}
										
										if(!empty($estado_fin_cli->Gran_contrib_[0]))
										{
											echo '<GRANDES_CONTRIBUYENTES>&#013;&#010;';
											$contadorRecC13 = 0;
											foreach ($estado_fin_cli->Gran_contrib_[0]->row as $recGCD) 
											{							
												$contadorRecC13++;
												if(!empty($recGCD->gran_contr)) echo '	<CONTRIBUYENTE> '.$recGCD->gran_contr.' </CONTRIBUYENTE>&#013;&#010;';
												
												if(count($estado_fin_cli->Gran_contrib_[0]->row) > 1 && $contadorRecC13 < count($estado_fin_cli->Gran_contrib_[0]->row)) echo '&#013;&#010;';
											}
											echo '</GRANDES_CONTRIBUYENTES>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->Domialter_[0]))
										{							
											echo '<DOMICILIOS_ALTERNATIVOS>&#013;&#010;';
											$contadorRecC14 = 0;
											foreach ($estado_fin_cli->Domialter_[0]->row as $recDMAC) 
											{								
												$contadorRecC14++;
												if(!empty($recDMAC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recDMAC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
												if(!empty($recDMAC->cuit)) echo '	<CUIT> '.$recDMAC->cuit.' </CUIT>&#013;&#010;';
												if(!empty($recDMAC->domicilio_fiscal)) echo '	<DOMICILIO_FISCAL> '.$recDMAC->domicilio_fiscal.' </DOMICILIO_FISCAL>&#013;&#010;';
												if(!empty($recDMAC->provincia)) echo '	<PROVINCIA> '.$recDMAC->provincia.' </PROVINCIA>&#013;&#010;';
												if(!empty($recDMAC->actividad_especifica)) echo '	<ACTIVIDAD_ESPECIFICA> '.$recDMAC->actividad_especifica.' </ACTIVIDAD_ESPECIFICA>&#013;&#010;';

												if(count($estado_fin_cli->Domialter_[0]->row) > 1 && $contadorRecC14 < count($estado_fin_cli->Domialter_[0]->row)) echo '&#013;&#010;';									
											}
											echo '</DOMICILIOS_ALTERNATIVOS>&#013;&#010;&#013;&#010;';							
										}
										
										if(!empty($estado_fin_cli->Nise_[0]))
										{
											echo '<NISE>&#013;&#010;';
											$contadorRecC15 = 0;
											foreach ($estado_fin_cli->Nise_[0]->row as $recINC) 
											{							
												$contadorRecC15++;
												if(!empty($recINC->radio)) echo '	<RADIO> '.$recINC->radio.' </RADIO>&#013;&#010;';
												if(!empty($recINC->nise)) echo '	<NISE> '.$recINC->nise.' </NISE>&#013;&#010;';
												if(!empty($recINC->periodo)) echo '	<PERIODO> '.$recINC->periodo.' </PERIODO>&#013;&#010;';
												
												if(count($estado_fin_cli->Nise_[0]->row) > 1 && $contadorRecC15 < count($estado_fin_cli->Nise_[0]->row)) echo '&#013;&#010;';
											}
											echo '</NISE>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->Const_inscrip_[0]))
										{							
											echo '<CONSTANCIAS_INSCRIPCION>&#013;&#010;';
											$contadorRecC16 = 0;
											foreach ($estado_fin_cli->Const_inscrip_[0]->row as $recCICR) 
											{								
												$contadorRecC16++;
												if(!empty($recCICR->descrip_imp_ganancias)) echo '	<DESCRIPCION_IMP_GANANCIAS> '.$recCICR->descrip_imp_ganancias.' </DESCRIPCION_IMP_GANANCIAS>&#013;&#010;';
												if(!empty($recCICR->descrip_imp_iva)) echo '	<DESCRIPCION_IMP_IVA> '.$recCICR->descrip_imp_iva.' </DESCRIPCION_IMP_IVA>&#013;&#010;';
												if(!empty($recCICR->descrip_monotributo)) echo '	<DESCRIPCION_MONOTRIBUTO> '.$recCICR->descrip_monotributo.' </DESCRIPCION_MONOTRIBUTO>&#013;&#010;';
												if(!empty($recCICR->descrip_integrante_soc)) echo '	<DESCRIPCION_INTEGRANTE_SOC> '.$recCICR->descrip_integrante_soc.' </DESCRIPCION_INTEGRANTE_SOC>&#013;&#010;';
												if(!empty($recCICR->descrip_empleador)) echo '	<DESCRIPCION_EMPLEADOR> '.$recCICR->descrip_empleador.' </DESCRIPCION_EMPLEADOR>&#013;&#010;';
												if(!empty($recCICR->periodo)) echo '	<PERIODO> '.$recCICR->periodo.' </PERIODO>&#013;&#010;';

												if(count($estado_fin_cli->Const_inscrip_[0]->row) > 1 && $contadorRecC16 < count($estado_fin_cli->Const_inscrip_[0]->row)) echo '&#013;&#010;';									
											}
											echo '</CONSTANCIAS_INSCRIPCION>&#013;&#010;&#013;&#010;';							
										}
										
										if(!empty($estado_fin_cli->Nbi_[0]))
										{
											echo '<NBI>&#013;&#010;';
											$contadorRecC17 = 0;
											foreach ($estado_fin_cli->Nbi_[0]->row as $recINBC) 
											{							
												$contadorRecC17++;
												if(!empty($recINBC->radio)) echo '	<RADIO> '.$recINBC->radio.' </RADIO>&#013;&#010;';
												if(!empty($recINBC->nbi)) echo '	<NBI> '.$recINBC->nbi.' </NBI>&#013;&#010;';
												if(!empty($recINBC->periodo)) echo '	<PERIODO> '.$recINBC->periodo.' </PERIODO>&#013;&#010;';
												
												if(count($estado_fin_cli->Nbi_[0]->row) > 1 && $contadorRecC17 < count($estado_fin_cli->Nbi_[0]->row)) echo '&#013;&#010;';
											}
											echo '</NBI>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->Iped_[0]))
										{
											echo '<IPED>&#013;&#010;';
											$contadorRecC18 = 0;
											foreach ($estado_fin_cli->Iped_[0]->row as $recIIPC) 
											{							
												$contadorRecC18++;
												if(!empty($recIIPC->radio)) echo '	<RADIO> '.$recIIPC->radio.' </RADIO>&#013;&#010;';
												if(!empty($recIIPC->iped)) echo '	<IPED> '.$recIIPC->iped.' </IPED>&#013;&#010;';
												if(!empty($recIIPC->periodo)) echo '	<PERIODO> '.$recIIPC->periodo.' </PERIODO>&#013;&#010;';
												
												if(count($estado_fin_cli->Iped_[0]->row) > 1 && $contadorRecC18 < count($estado_fin_cli->Iped_[0]->row)) echo '&#013;&#010;';
											}
											echo '</IPED>&#013;&#010;&#013;&#010;';
										}							

										if(!empty($estado_fin_cli->Ib_[0]))
										{
											echo '<IB>&#013;&#010;';
											$contadorRecC19 = 0;
											foreach ($estado_fin_cli->Ib_[0]->row as $recIIBC) 
											{							
												$contadorRecC19++;
												if(!empty($recIIBC->radio)) echo '	<RADIO> '.$recIIBC->radio.' </RADIO>&#013;&#010;';
												if(!empty($recIIBC->ib)) echo '	<IB> '.$recIIBC->ib.' </IB>&#013;&#010;';
												if(!empty($recIIBC->periodo)) echo '	<PERIODO> '.$recIIBC->periodo.' </PERIODO>&#013;&#010;';
												
												if(count($estado_fin_cli->Ib_[0]->row) > 1 && $contadorRecC19 < count($estado_fin_cli->Ib_[0]->row)) echo '&#013;&#010;';
											}
											echo '</IB>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->Rel_Dependencia_Trabajador_[0]))
										{
											echo '<RELEACION_DEPENDENCIA_TRABAJADOR>&#013;&#010;';
											$contadorRecC20 = 0;
											foreach ($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row as $recRDTC) 
											{							
												$contadorRecC20++;
												if(!empty($recRDTC->cuil)) echo '	<CUIL> '.$recRDTC->cuil.' </CUIL>&#013;&#010;';
												if(!empty($recRDTC->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recRDTC->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
												if(!empty($recRDTC->domicilio)) echo '	<DOMICILIO> '.$recRDTC->domicilio.' </DOMICILIO>&#013;&#010;';
												if(!empty($recRDTC->localidad)) echo '	<LOCALIDAD> '.$recRDTC->localidad.' </LOCALIDAD>&#013;&#010;';
												if(!empty($recRDTC->cp)) echo '	<CODIGO_POSTAL> '.$recRDTC->cp.' </CODIGO_POSTAL>&#013;&#010;';
												if(!empty($recRDTC->provincia)) echo '	<PROVINCIA> '.$recRDTC->provincia.' </PROVINCIA>&#013;&#010;';
												if(!empty($recRDTC->fecha_nac)) echo '	<FECHA_NACIMIENTO> '.$recRDTC->fecha_nac.' </FECHA_NACIMIENTO>&#013;&#010;';
												if(!empty($recRDTC->sexo)) echo '	<SEXO> '.$recRDTC->sexo.' </SEXO>&#013;&#010;';
												if(!empty($recRDTC->doc)) echo '	<DOCUMENTO> '.$recRDTC->doc.' </DOCUMENTO>&#013;&#010;';
												
												if(count($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row) > 1 && $contadorRecC20 < count($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row)) echo '&#013;&#010;';
											}
											echo '</RELEACION_DEPENDENCIA_TRABAJADOR>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->Rel_Dependencia_Empleador_[0]))
										{
											echo '<RELEACION_DEPENDENCIA_EMPLEADOR>&#013;&#010;';
											$contadorRecC21 = 0;
											foreach ($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row as $recRDEC) 
											{							
												$contadorRecC21++;
												if(!empty($recRDEC->cuit)) echo '	<CUIT> '.$recRDEC->cuit.' </CUIT>&#013;&#010;';
												if(!empty($recRDEC->razon)) echo '	<RAZON_SOCIAL> '.$recRDEC->razon.' </RAZON_SOCIAL>&#013;&#010;';
												if(!empty($recRDEC->domicilio)) echo '	<DOMICILIO> '.$recRDEC->domicilio.' </DOMICILIO>&#013;&#010;';
												if(!empty($recRDEC->localidad)) echo '	<LOCALIDAD> '.$recRDEC->localidad.' </LOCALIDAD>&#013;&#010;';
												if(!empty($recRDEC->cp)) echo '	<CODIGO_POSTAL> '.$recRDEC->cp.' </CODIGO_POSTAL>&#013;&#010;';
												if(!empty($recRDEC->provincia)) echo '	<PROVINCIA> '.$recRDEC->provincia.' </PROVINCIA>&#013;&#010;';
												
												if(count($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row) > 1 && $contadorRecC21 < count($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row)) echo '&#013;&#010;';
											}
											echo '</RELEACION_DEPENDENCIA_EMPLEADOR>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->Historia_[0]))
										{
											echo '<HISTORIA_EMPRESAS>&#013;&#010;';
											$contadorRecC22 = 0;
											foreach ($estado_fin_cli->Historia_[0]->row as $recHEC) 
											{							
												$contadorRecC22++;
												if(!empty($recHEC->cuit)) echo '	<CUIT> '.$recHEC->cuit.' </CUIT>&#013;&#010;';
												if(!empty($recHEC->razon)) echo '	<RAZON_SOCIAL> '.$recHEC->razon.' </RAZON_SOCIAL>&#013;&#010;';
												if(!empty($recHEC->desde)) echo '	<DESDE> '.$recHEC->desde.' </DESDE>&#013;&#010;';
												if(!empty($recHEC->hasta)) echo '	<HASTA> '.$recHEC->hasta.' </HASTA>&#013;&#010;';
												
												if(count($estado_fin_cli->Historia_[0]->row) > 1 && $contadorRecC22 < count($estado_fin_cli->Historia_[0]->row)) echo '&#013;&#010;';
											}
											echo '</HISTORIA_EMPRESAS>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->Sysem_[0]))
										{
											echo '<SYSEM>&#013;&#010;';
											$contadorRecC23 = 0;
											foreach ($estado_fin_cli->Sysem_[0]->row as $recSYC) 
											{							
												$contadorRecC23++;
												if(!empty($recSYC->cant_emp)) echo '	<CANTIDAD_EMPLEADOS> '.$recSYC->cant_emp.' </CANTIDAD_EMPLEADOS>&#013;&#010;';
												if(!empty($recSYC->cuit)) echo '	<CUIT> '.$recSYC->cuit.' </CUIT>&#013;&#010;';
												if(!empty($recSYC->razon)) echo '	<RAZON_SOCIAL> '.$recSYC->razon.' </RAZON_SOCIAL>&#013;&#010;';
												if(!empty($recSYC->domicilio)) echo '	<DOMICILIO> '.$recSYC->domicilio.' </DOMICILIO>&#013;&#010;';
												if(!empty($recSYC->localidad)) echo '	<LOCALIDAD> '.$recSYC->localidad.' </LOCALIDAD>&#013;&#010;';
												if(!empty($recSYC->cp)) echo '	<CODIGO_POSTAL> '.$recSYC->cp.' </CODIGO_POSTAL>&#013;&#010;';
												if(!empty($recSYC->provincia)) echo '	<PROVINCIA> '.$recSYC->provincia.' </PROVINCIA>&#013;&#010;';
												
												if(count($estado_fin_cli->Sysem_[0]->row) > 1 && $contadorRecC23 < count($estado_fin_cli->Sysem_[0]->row)) echo '&#013;&#010;';
											}
											echo '</SYSEM>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->Jyj_mensual_[0]))
										{
											echo '<SYSEM>&#013;&#010;';
											$contadorRecC24 = 0;
											foreach ($estado_fin_cli->Jyj_mensual_[0]->row as $recJYJC) 
											{							
												$contadorRecC24++;
												if(!empty($recJYJC->cuil)) echo '	<CUIL> '.$recJYJC->cuil.' </CUIL>&#013;&#010;';
												if(!empty($recJYJC->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recJYJC->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
												if(!empty($recJYJC->rango_socio_econom)) echo '	<RANGO_SOCIO_ECONOMICO> '.$recJYJC->rango_socio_econom.' </RANGO_SOCIO_ECONOMICO>&#013;&#010;';
												
												if(count($estado_fin_cli->Jyj_mensual_[0]->row) > 1 && $contadorRecC24 < count($estado_fin_cli->Jyj_mensual_[0]->row)) echo '&#013;&#010;';
											}
											echo '</SYSEM>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->Situacionlaboral_[0]))
										{
											echo '<SITUACION_LABORAL>&#013;&#010;';
											$contadorRecC25 = 0;
											foreach ($estado_fin_cli->Situacionlaboral_[0]->row as $recSLC) 
											{							
												$contadorRecC25++;
												if(!empty($recSLC->situacionlaboral)) echo '	<ESTADO_LABORAL> '.$recSLC->situacionlaboral.' </ESTADO_LABORAL>&#013;&#010;';
												
												if(count($estado_fin_cli->Situacionlaboral_[0]->row) > 1 && $contadorRecC25 < count($estado_fin_cli->Situacionlaboral_[0]->row)) echo '&#013;&#010;';
											}
											echo '</SITUACION_LABORAL>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->Producto_financieros_[0]))
										{
											echo '<PRODUCTO_FINANCIEROS>&#013;&#010;';
											$contadorRecC26 = 0;
											foreach ($estado_fin_cli->Producto_financieros_[0]->row as $recPFC) 
											{							
												$contadorRecC26++;
												if(!empty($recPFC->producto_financieros)) echo '	<PRODUCTO> '.$recPFC->producto_financieros.' </PRODUCTO>&#013;&#010;';
												
												if(count($estado_fin_cli->Producto_financieros_[0]->row) > 1 && $contadorRecC26 < count($estado_fin_cli->Producto_financieros_[0]->row)) echo '&#013;&#010;';
											}
											echo '</PRODUCTO_FINANCIEROS>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->Bancarizado_[0]))
										{
											echo '<BANCARIZADO>&#013;&#010;';
											$contadorRecC27 = 0;
											foreach ($estado_fin_cli->Bancarizado_[0]->row as $recEBC) 
											{							
												$contadorRecC27++;
												if(!empty($recEBC->bancarizado)) echo '	<ESTADO> '.$recEBC->bancarizado.' </ESTADO>&#013;&#010;';
												
												if(count($estado_fin_cli->Bancarizado_[0]->row) > 1 && $contadorRecC27 < count($estado_fin_cli->Bancarizado_[0]->row)) echo '&#013;&#010;';
											}
											echo '</BANCARIZADO>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->FechaMaxSistFin_[0]))
										{
											echo '<FECHA_MAXIMA_SISTEMA_FINANCIERO>&#013;&#010;';
											$contadorRecC28 = 0;
											foreach ($estado_fin_cli->FechaMaxSistFin_[0]->row as $recFMSF) 
											{							
												$contadorRecC28++;
												if(!empty($recFMSF->fecha)) echo '	<FECHA> '.$recFMSF->fecha.' </FECHA>&#013;&#010;';
												
												if(count($estado_fin_cli->FechaMaxSistFin_[0]->row) > 1 && $contadorRecC28 < count($estado_fin_cli->FechaMaxSistFin_[0]->row)) echo '&#013;&#010;';
											}
											echo '</FECHA_MAXIMA_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
										}	

										if(!empty($estado_fin_cli->SistFinEvolucionDeuda_[0]))
										{
											echo '<EVOLUCION_DEUDA_SISTEMA_FINANCIERO>&#013;&#010;';
											$contadorRecC29 = 0;
											foreach ($estado_fin_cli->SistFinEvolucionDeuda_[0]->row as $recEDSF) 
											{							
												$contadorRecC29++;
												if(!empty($recEDSF->entidad)) echo '	<ENTIDAD> '.$recEDSF->entidad.' </ENTIDAD>&#013;&#010;';
												if(!empty($recEDSF->m1)) echo '	<MES1> '.$recEDSF->m1.' </MES1>&#013;&#010;';
												if(!empty($recEDSF->m2)) echo '	<MES2> '.$recEDSF->m2.' </MES2>&#013;&#010;';
												if(!empty($recEDSF->m3)) echo '	<MES3> '.$recEDSF->m3.' </MES3>&#013;&#010;';
												if(!empty($recEDSF->m4)) echo '	<MES4> '.$recEDSF->m4.' </MES4>&#013;&#010;';
												if(!empty($recEDSF->m5)) echo '	<MES5> '.$recEDSF->m5.' </MES5>&#013;&#010;';
												if(!empty($recEDSF->m6)) echo '	<MES6> '.$recEDSF->m6.' </MES6>&#013;&#010;';
												if(!empty($recEDSF->m7)) echo '	<MES7> '.$recEDSF->m7.' </MES7>&#013;&#010;';
												if(!empty($recEDSF->m8)) echo '	<MES8> '.$recEDSF->m8.' </MES8>&#013;&#010;';
												if(!empty($recEDSF->m9)) echo '	<MES9> '.$recEDSF->m9.' </MES9>&#013;&#010;';
												if(!empty($recEDSF->m10)) echo '	<MES10> '.$recEDSF->m10.' </MES10>&#013;&#010;';
												if(!empty($recEDSF->m11)) echo '	<MES11> '.$recEDSF->m11.' </MES11>&#013;&#010;';
												if(!empty($recEDSF->m12)) echo '	<MES12> '.$recEDSF->m12.' </MES12>&#013;&#010;';
												if(!empty($recEDSF->m13)) echo '	<MES13> '.$recEDSF->m13.' </MES13>&#013;&#010;';
												if(!empty($recEDSF->m14)) echo '	<MES14> '.$recEDSF->m14.' </MES14>&#013;&#010;';
												if(!empty($recEDSF->m15)) echo '	<MES15> '.$recEDSF->m15.' </MES15>&#013;&#010;';
												if(!empty($recEDSF->m16)) echo '	<MES16> '.$recEDSF->m16.' </MES16>&#013;&#010;';
												if(!empty($recEDSF->m17)) echo '	<MES17> '.$recEDSF->m17.' </MES17>&#013;&#010;';
												if(!empty($recEDSF->m18)) echo '	<MES18> '.$recEDSF->m18.' </MES18>&#013;&#010;';
												if(!empty($recEDSF->m19)) echo '	<MES19> '.$recEDSF->m19.' </MES19>&#013;&#010;';
												if(!empty($recEDSF->m20)) echo '	<MES20> '.$recEDSF->m20.' </MES20>&#013;&#010;';
												if(!empty($recEDSF->m21)) echo '	<MES21> '.$recEDSF->m21.' </MES21>&#013;&#010;';
												if(!empty($recEDSF->m22)) echo '	<MES22> '.$recEDSF->m22.' </MES22>&#013;&#010;';
												if(!empty($recEDSF->m23)) echo '	<MES23> '.$recEDSF->m23.' </MES23>&#013;&#010;';
												if(!empty($recEDSF->m24)) echo '	<MES24> '.$recEDSF->m24.' </MES24>&#013;&#010;';									
												if(!empty($recEDSF->deuda_actual)) echo '	<DEUDA_ACTUAL> '.$recEDSF->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
												if(!empty($recEDSF->monto_maximo)) echo '	<MONTO_MAXIMO> '.$recEDSF->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
												if(!empty($recEDSF->bco)) echo '	<BANCO> '.$recEDSF->bco.' </BANCO>&#013;&#010;';
												
												if(count($estado_fin_cli->SistFinEvolucionDeuda_[0]->row) > 1 && $contadorRecC29 < count($estado_fin_cli->SistFinEvolucionDeuda_[0]->row)) echo '&#013;&#010;';
											}
											echo '</EVOLUCION_DEUDA_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]))
										{
											echo '<EVOLUCION_DEUDA_PROPIO_SISTEMA_FINANCIERO>&#013;&#010;';
											$contadorRecC30 = 0;
											foreach ($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row as $recEDPSF) 
											{							
												$contadorRecC30++;
												if(!empty($recEDPSF->entidad)) echo '	<ENTIDAD> '.$recEDPSF->entidad.' </ENTIDAD>&#013;&#010;';
												if(!empty($recEDPSF->m1)) echo '	<MES1> '.$recEDPSF->m1.' </MES1>&#013;&#010;';
												if(!empty($recEDPSF->m2)) echo '	<MES2> '.$recEDPSF->m2.' </MES2>&#013;&#010;';
												if(!empty($recEDPSF->m3)) echo '	<MES3> '.$recEDPSF->m3.' </MES3>&#013;&#010;';
												if(!empty($recEDPSF->m4)) echo '	<MES4> '.$recEDPSF->m4.' </MES4>&#013;&#010;';
												if(!empty($recEDPSF->m5)) echo '	<MES5> '.$recEDPSF->m5.' </MES5>&#013;&#010;';
												if(!empty($recEDPSF->m6)) echo '	<MES6> '.$recEDPSF->m6.' </MES6>&#013;&#010;';
												if(!empty($recEDPSF->m7)) echo '	<MES7> '.$recEDPSF->m7.' </MES7>&#013;&#010;';
												if(!empty($recEDPSF->m8)) echo '	<MES8> '.$recEDPSF->m8.' </MES8>&#013;&#010;';
												if(!empty($recEDPSF->m9)) echo '	<MES9> '.$recEDPSF->m9.' </MES9>&#013;&#010;';
												if(!empty($recEDPSF->m10)) echo '	<MES10> '.$recEDPSF->m10.' </MES10>&#013;&#010;';
												if(!empty($recEDPSF->m11)) echo '	<MES11> '.$recEDPSF->m11.' </MES11>&#013;&#010;';
												if(!empty($recEDPSF->m12)) echo '	<MES12> '.$recEDPSF->m12.' </MES12>&#013;&#010;';
												if(!empty($recEDPSF->m13)) echo '	<MES13> '.$recEDPSF->m13.' </MES13>&#013;&#010;';
												if(!empty($recEDPSF->m14)) echo '	<MES14> '.$recEDPSF->m14.' </MES14>&#013;&#010;';
												if(!empty($recEDPSF->m15)) echo '	<MES15> '.$recEDPSF->m15.' </MES15>&#013;&#010;';
												if(!empty($recEDPSF->m16)) echo '	<MES16> '.$recEDPSF->m16.' </MES16>&#013;&#010;';
												if(!empty($recEDPSF->m17)) echo '	<MES17> '.$recEDPSF->m17.' </MES17>&#013;&#010;';
												if(!empty($recEDPSF->m18)) echo '	<MES18> '.$recEDPSF->m18.' </MES18>&#013;&#010;';
												if(!empty($recEDPSF->m19)) echo '	<MES19> '.$recEDPSF->m19.' </MES19>&#013;&#010;';
												if(!empty($recEDPSF->m20)) echo '	<MES20> '.$recEDPSF->m20.' </MES20>&#013;&#010;';
												if(!empty($recEDPSF->m21)) echo '	<MES21> '.$recEDPSF->m21.' </MES21>&#013;&#010;';
												if(!empty($recEDPSF->m22)) echo '	<MES22> '.$recEDPSF->m22.' </MES22>&#013;&#010;';
												if(!empty($recEDPSF->m23)) echo '	<MES23> '.$recEDPSF->m23.' </MES23>&#013;&#010;';
												if(!empty($recEDPSF->m24)) echo '	<MES24> '.$recEDPSF->m24.' </MES24>&#013;&#010;';									
												if(!empty($recEDPSF->deuda_actual)) echo '	<DEUDA_ACTUAL> '.$recEDPSF->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
												if(!empty($recEDPSF->monto_maximo)) echo '	<MONTO_MAXIMO> '.$recEDPSF->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
												if(!empty($recEDPSF->bco)) echo '	<BANCO> '.$recEDPSF->bco.' </BANCO>&#013;&#010;';
												
												if(count($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row) > 1 && $contadorRecC30 < count($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row)) echo '&#013;&#010;';
											}
											echo '</EVOLUCION_DEUDA_PROPIO_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
										}							
										
										if(!empty($estado_fin_cli->Bcra_Ultimas_Situaciones[0]))
										{
											echo '<BANCO_CENTRAL_ULTIMAS_SITUACIONES>&#013;&#010;';
											$contadorRecC31 = 0;
											foreach ($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row as $recBCUS) 
											{							
												$contadorRecC31++;
												if(!empty($recBCUS->entidad)) echo '	<ENTIDAD> '.$recBCUS->entidad.' </ENTIDAD>&#013;&#010;';
												if(!empty($recBCUS->periodo)) echo '	<PERIODO> '.$recBCUS->periodo.' </PERIODO>&#013;&#010;';
												if(!empty($recBCUS->situacion)) echo '	<SITUACION> '.$recBCUS->situacion.' </SITUACION>&#013;&#010;';
												if(!empty($recBCUS->monto)) echo '	<MONTO> '.$recBCUS->monto.' </MONTO>&#013;&#010;';
												
												if(count($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row) > 1 && $contadorRecC31 < count($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row)) echo '&#013;&#010;';
											}
											echo '</BANCO_CENTRAL_ULTIMAS_SITUACIONES>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->Bcra_Situaciones_Vigente[0]))
										{
											echo '<BANCO_CENTRAL_SITUACIONES_VIGENTES>&#013;&#010;';
											$contadorRecC32 = 0;
											foreach ($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row as $recBCSV) 
											{							
												$contadorRecC32++;
												if(!empty($recBCSV->entidad)) echo '	<ENTIDAD> '.$recBCSV->entidad.' </ENTIDAD>&#013;&#010;';
												if(!empty($recBCSV->periodos)) echo '	<PERIODOS> '.$recBCSV->periodos.' </PERIODOS>&#013;&#010;';
												if(!empty($recBCSV->situacion)) echo '	<SITUACION> '.$recBCSV->situacion.' </SITUACION>&#013;&#010;';
												if(!empty($recBCSV->monto)) echo '	<MONTO> '.$recBCSV->monto.' </MONTO>&#013;&#010;';
												
												if(count($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row) > 1 && $contadorRecC32 < count($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row)) echo '&#013;&#010;';
											}
											echo '</BANCO_CENTRAL_SITUACIONES_VIGENTES>&#013;&#010;&#013;&#010;';
										}							
										
										if(!empty($estado_fin_cli->Tipo_Actividad[0]))
										{							
											echo '<TIPO_ACTIVIDAD>&#013;&#010;';
											$contadorRecC33 = 0;
											foreach ($estado_fin_cli->Tipo_Actividad[0]->row as $recTAC) 
											{								
												$contadorRecC33++;
												echo '	<TIPO_ACTIVIDAD> '.$recTAC->tipo_actividad.' </TIPO_ACTIVIDAD>&#013;&#010;';

												if(count($estado_fin_cli->Tipo_Actividad[0]->row) > 1 && $contadorRecC33 < count($estado_fin_cli->Tipo_Actividad[0]->row)) echo '&#013;&#010;';								
											}
											echo '</TIPO_ACTIVIDAD>&#013;&#010;&#013;&#010;';	
										}								
								
										if(!empty($estado_fin_cli->Consultas_Individual_[0]))
										{
											echo '<CONSULTAS_INDIVIDUALES>&#013;&#010;';
											$contadorRecC34 = 0;
											foreach ($estado_fin_cli->Consultas_Individual_[0]->row as $recCINDC) 
											{							
												$contadorRecC34++;
												if(!empty($recCINDC->dia)) echo '	<DIA> '.$recCINDC->dia.' </DIA>&#013;&#010;';
												if(!empty($recCINDC->semana)) echo '	<SEMANA> '.$recCINDC->semana.' </SEMANA>&#013;&#010;';
												if(!empty($recCINDC->mes)) echo '	<MES> '.$recCINDC->mes.' </MES>&#013;&#010;';
												if(!empty($recCINDC->m24)) echo '	<M24> '.$recCINDC->m24.' </M24>&#013;&#010;';
												
												if(count($estado_fin_cli->Consultas_Individual_[0]->row) > 1 && $contadorRecC34 < count($estado_fin_cli->Consultas_Individual_[0]->row)) echo '&#013;&#010;';
											}
											echo '</CONSULTAS_INDIVIDUALES>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->Consultas_Grupo_[0]))
										{
											echo '<CONSULTAS_GRUPO>&#013;&#010;';
											$contadorRecC35 = 0;
											foreach ($estado_fin_cli->Consultas_Grupo_[0]->row as $recCGRUC) 
											{							
												$contadorRecC35++;
												if(!empty($recCGRUC->dia)) echo '	<DIA> '.$recCGRUC->dia.' </DIA>&#013;&#010;';
												if(!empty($recCGRUC->semana)) echo '	<SEMANA> '.$recCGRUC->semana.' </SEMANA>&#013;&#010;';
												if(!empty($recCGRUC->mes)) echo '	<MES> '.$recCGRUC->mes.' </MES>&#013;&#010;';
												if(!empty($recCGRUC->m24)) echo '	<M24> '.$recCGRUC->m24.' </M24>&#013;&#010;';
												
												if(count($estado_fin_cli->Consultas_Grupo_[0]->row) > 1 && $contadorRecC35 < count($estado_fin_cli->Consultas_Grupo_[0]->row)) echo '&#013;&#010;';
											}
											echo '</CONSULTAS_GRUPO>&#013;&#010;&#013;&#010;';
										}	

										if(!empty($estado_fin_cli->CONSULTADO_POR_[0]))
										{
											echo '<CONSULTADO_POR>&#013;&#010;';
											$contadorRecC36 = 0;
											foreach ($estado_fin_cli->CONSULTADO_POR_[0]->row as $recCCONP) 
											{							
												$contadorRecC36++;
												if(!empty($recCCONP->fecha)) echo '	<FECHA> '.$recCCONP->fecha.' </FECHA>&#013;&#010;';
												if(!empty($recCCONP->consultante)) echo '	<CONSULTANTE> '.$recCCONP->consultante.' </CONSULTANTE>&#013;&#010;';
												
												if(count($estado_fin_cli->CONSULTADO_POR_[0]->row) > 1 && $contadorRecC36 < count($estado_fin_cli->CONSULTADO_POR_[0]->row)) echo '&#013;&#010;';
											}
											echo '</CONSULTADO_POR>&#013;&#010;&#013;&#010;';
										}							
								
										if(!empty($estado_fin_cli->Independientes_[0]))
										{
											echo '<INDEPENDIENTES>&#013;&#010;';
											$contadorRecC37 = 0;
											foreach ($estado_fin_cli->Independientes_[0]->row as $recCONIND) 
											{							
												$contadorRecC37++;
												if(!empty($recCONIND->inscripto)) echo '	<INSCRIPTO> '.$recCONIND->inscripto.' </INSCRIPTO>&#013;&#010;';
												if(!empty($recCONIND->categoria)) echo '	<CATEGORIA> '.$recCONIND->categoria.' </CATEGORIA>&#013;&#010;';
												if(!empty($recCONIND->cuit)) echo '	<CUIT> '.$recCONIND->cuit.' </CUIT>&#013;&#010;';
												if(!empty($recCONIND->documento)) echo '	<DOCUMENTO> '.$recCONIND->documento.' </DOCUMENTO>&#013;&#010;';
												if(!empty($recCONIND->nombre)) echo '	<NOMBRE> '.$recCONIND->nombre.' </NOMBRE>&#013;&#010;';
												if(!empty($recCONIND->domicilio)) echo '	<DOMICILIO> '.$recCONIND->domicilio.' </DOMICILIO>&#013;&#010;';
												if(!empty($recCONIND->cod_postal)) echo '	<CODIGO_POSTAL> '.$recCONIND->cod_postal.' </CODIGO_POSTAL>&#013;&#010;';
												if(!empty($recCONIND->localidad)) echo '	<LOCALIDAD> '.$recCONIND->localidad.' </LOCALIDAD>&#013;&#010;';
												if(!empty($recCONIND->provincia)) echo '	<PROVINCIA> '.$recCONIND->provincia.' </PROVINCIA>&#013;&#010;';
												if(!empty($recCONIND->fecha_nac)) echo '	<FECHA_NACIMIENTO> '.$recCONIND->fecha_nac.' </FECHA_NACIMIENTO>&#013;&#010;';
												if(!empty($recCONIND->entidad)) echo '	<ENTIDAD> '.$recCONIND->entidad.' </ENTIDAD>&#013;&#010;';
												if(!empty($recCONIND->actividad)) echo '	<ACTIVIDAD> '.$recCONIND->actividad.' </ACTIVIDAD>&#013;&#010;';
												if(!empty($recCONIND->periodo)) echo '	<PERIODO> '.$recCONIND->periodo.' </PERIODO>&#013;&#010;';
												if(!empty($recCONIND->monto)) echo '	<MONTO> '.$recCONIND->monto.' </MONTO>&#013;&#010;';									
												
												if(count($estado_fin_cli->Independientes_[0]->row) > 1 && $contadorRecC37 < count($estado_fin_cli->Independientes_[0]->row)) echo '&#013;&#010;';
											}
											echo '</INDEPENDIENTES>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->Indep_apo_[0]))
										{
											echo '<APORTES_INDEPENDIENTE>&#013;&#010;';
											$contadorRecC38 = 0;
											foreach ($estado_fin_cli->Indep_apo_[0]->row as $recAPCI) 
											{							
												$contadorRecC38++;
												if(!empty($recAPCI->cuit)) echo '	<CUIT> '.$recAPCI->cuit.' </CUIT>&#013;&#010;';
												if(!empty($recAPCI->periodo)) echo '	<PERIODO> '.$recAPCI->periodo.' </PERIODO>&#013;&#010;';
												if(!empty($recAPCI->imp_pago)) echo '	<IMPORTE> '.$recAPCI->imp_pago.' </IMPORTE>&#013;&#010;';
												if(!empty($recAPCI->impuesto)) echo '	<IMPUESTO> '.$recAPCI->impuesto.' </IMPUESTO>&#013;&#010;';
												
												if(count($estado_fin_cli->Indep_apo_[0]->row) > 1 && $contadorRecC38 < count($estado_fin_cli->Indep_apo_[0]->row)) echo '&#013;&#010;';
											}
											echo '</APORTES_INDEPENDIENTE>&#013;&#010;&#013;&#010;';
										}							
										
										if(!empty($estado_fin_cli->Propiedades_[0]))
										{
											echo '<PROPIEDADES>&#013;&#010;';
											$contadorRecC39 = 0;
											foreach ($estado_fin_cli->Propiedades_[0]->row as $recIPROPC) 
											{							
												$contadorRecC39++;
												if(!empty($recIPROPC->titular)) echo '	<TITULAR> '.$recIPROPC->titular.' </TITULAR>&#013;&#010;';
												if(!empty($recIPROPC->folio)) echo '	<FOLIO> '.$recIPROPC->folio.' </FOLIO>&#013;&#010;';
												if(!empty($recIPROPC->ano_inscripcion)) echo '	<AÑO_INSCRIPCION> '.$recIPROPC->ano_inscripcion.' </AÑO_INSCRIPCION>&#013;&#010;';
												if(!empty($recIPROPC->ph)) echo '	<PH> '.$recIPROPC->ph.' </PH>&#013;&#010;';
												if(!empty($recIPROPC->condom)) echo '	<CONDOM> '.$recIPROPC->condom.' </CONDOM>&#013;&#010;';
												if(!empty($recIPROPC->codigo_departamento)) echo '	<CODIGO_DEPARTAMENTO> '.$recIPROPC->codigo_departamento.' </CODIGO_DEPARTAMENTO>&#013;&#010;';
												if(!empty($recIPROPC->pedania)) echo '	<PEDANIA> '.$recIPROPC->pedania.' </PEDANIA>&#013;&#010;';
												if(!empty($recIPROPC->pueblo)) echo '	<PUEBLO> '.$recIPROPC->pueblo.' </PUEBLO>&#013;&#010;';
												if(!empty($recIPROPC->circuncripcion)) echo '	<CIRCUNCRIPCION> '.$recIPROPC->circuncripcion.' </CIRCUNCRIPCION>&#013;&#010;';
												if(!empty($recIPROPC->seccion)) echo '	<SECCION> '.$recIPROPC->seccion.' </SECCION>&#013;&#010;';
												if(!empty($recIPROPC->manzana)) echo '	<MANZANA> '.$recIPROPC->manzana.' </MANZANA>&#013;&#010;';
												if(!empty($recIPROPC->parcela)) echo '	<PARCELA> '.$recIPROPC->parcela.' </PARCELA>&#013;&#010;';
												if(!empty($recIPROPC->escrituro)) echo '	<ESCRITURO> '.$recIPROPC->escrituro.' </ESCRITURO>&#013;&#010;';
												
												if(count($estado_fin_cli->Propiedades_[0]->row) > 1 && $contadorRecC39 < count($estado_fin_cli->Propiedades_[0]->row)) echo '&#013;&#010;';
											}
											echo '</PROPIEDADES>&#013;&#010;&#013;&#010;';
										}
										echo '						</textarea>';
										echo '					</div>';		
										echo '				</div>';									
										echo '				<div class="form-group form-inline"><hr />';
										echo '					<label class="control-label" for="usuariosupervisorn30">'.translate('Lbl_User_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
										echo '					<div class="form-group" id="usuariosupervisorn30">';
										echo '						<input title="'.translate('Msg_User_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="usuariosupervisorn30i" name="usuariosupervisorn30i" type="text" maxlength="50" />';
										echo '					</div>';
										echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="passwordsupervisorn30">'.translate('Lbl_Password_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
										echo '					<div class="form-group" id="passwordsupervisorn30">';
										echo '						<input title="'.translate('Msg_Password_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="passwordsupervisorn30i" name="passwordsupervisorn30i" type="password" maxlength="128" />';
										echo '					</div>';		
										echo '				</div>';
										$estado_activa_supervisor = 1;
									}
									else
									{
										$estado_fin_cli = new SimpleXMLElement($resultado_finan_cli_final);
										echo translate('Msg_Validation_Credit_Status_Client_OK',$GLOBALS['lang']).'=::=::=::'.$tokenECF.'=:=:=:';
										echo '<div class="panel-group">';				
										echo '	<div class="panel panel-default">';
										echo '		<div id="panel-title-header" class="panel-heading">';
										echo '			<h3 class="panel-title">'.translate('Lbl_Result_Financial_Statement_Client',$GLOBALS['lang']).'</h3>';
										echo ' 		</div>';
										echo '		<div class="panel-body">';
										echo '			<form id="formulariocefcm" role="form" onsubmit="buscarTextoEstadoFinancieroM(); return false;">';		
										echo '				<div class="form-group form-inline">';
										echo '					<div class="form-group" id="buscartextoestadocrediticioclientem">';
										echo '						<input class="form-control input-sm" id="buscartextoestadocrediticioclientemi" name="buscartextoestadocrediticioclientemi" type="text" maxlength="150" />';
										echo '						&nbsp;<button type="button" class="btn" id="btnBuscarEstadoFM" name="btnBuscarEstadoFM" title="'.translate('Lbl_Search_Text_Statement_Client',$GLOBALS['lang']).'" onclick="buscarTextoEstadoFinancieroM();"><i class="fas fa-search"></i></button>';									
										echo '					</div>';
										echo '				</div>';
										echo '				<div class="form-group form-inline">';					
										echo '					<div class="form-group" id="resultadoestadofinancieroclientem">';
										echo '						<textarea rows="3" cols="67" class="form-control input-sm" id="resultadoestadofinancieroclientemi" name="resultadoestadofinancieroclientemi">';
										echo '<EXISTENCIA_FISICA_ENTIDAD>&#013;&#010;';
										$contadorRecC1 = 0;
										foreach ($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row as $recEFC) 
										{
											$contadorRecC1++;
											if(!empty($recEFC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recEFC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
											if(!empty($recEFC->sexo)) echo '	<SEXO> '.$recEFC->sexo.' </SEXO>&#013;&#010;';
											if(!empty($recEFC->tipo_docu)) echo '	<TIPO_DOCUMENTO> '.$recEFC->tipo_docu.' </TIPO_DOCUMENTO>&#013;&#010;';
											if(!empty($recEFC->nume_doc)) echo '	<DOCUMENTO> '.$recEFC->nume_doc.' </DOCUMENTO>&#013;&#010;';
											if(!empty($recEFC->clase)) echo '	<CLASE> '.$recEFC->clase.' </CLASE>&#013;&#010;';
											if(!empty($recEFC->edad)) echo '	<EDAD> '.$recEFC->edad.' </EDAD>&#013;&#010;';
											if(!empty($recEFC->ocupacion)) echo '	<OCUPACION> '.$recEFC->ocupacion.' </OCUPACION>&#013;&#010;';
											if(!empty($recEFC->cdi_codigo_de_identificacion)) echo '	<CUIT_CUIL> '.$recEFC->cdi_codigo_de_identificacion.' </CUIT_CUIL>&#013;&#010;';								
											if(!empty($recEFC->fecha_nacimiento)) echo '	<FECHA_NACIMIENTO> '.$recEFC->fecha_nacimiento.' </FECHA_NACIMIENTO>&#013;&#010;';							
											if(!empty($recEFC->direc_calle)) echo '	<DIRECCION_CALLE> '.$recEFC->direc_calle.' </DIRECCION_CALLE>&#013;&#010;';							
											if(!empty($recEFC->localidad)) echo '	<LOCALIDAD> '.$recEFC->localidad.' </LOCALIDAD>&#013;&#010;';
											if(!empty($recEFC->departamento)) echo '	<DEPARTAMENTO> '.$recEFC->departamento.' </DEPARTAMENTO>&#013;&#010;';								
											if(!empty($recEFC->codigo_postal)) echo '	<CODIGO_POSTAL> '.$recEFC->codigo_postal.' </CODIGO_POSTAL>&#013;&#010;';							
											if(!empty($recEFC->provincia)) echo '	<PROVINCIA> '.$recEFC->provincia.' </PROVINCIA>&#013;&#010;';							
											if(!empty($recEFC->apellido_materno)) echo '	<APELLIDO_MATERNO> '.$recEFC->apellido_materno.' </APELLIDO_MATERNO>&#013;&#010;';
											if(!empty($recEFC->estado_civil)) echo '	<ESTADO_CIVIL> '.$recEFC->estado_civil.' </ESTADO_CIVIL>&#013;&#010;';								
											if(!empty($recEFC->fallecido)) echo '	<FALLECIDO> '.$recEFC->fallecido.' </FALLECIDO>&#013;&#010;';
											
											if(count($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row) > 1 && $contadorRecC1 < count($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row)) echo '&#013;&#010;';
										}							
										echo '</EXISTENCIA_FISICA_ENTIDAD>&#013;&#010;&#013;&#010;';
										
										if(!empty($estado_fin_cli->Score_[0]))
										{
											echo '<SCORE>&#013;&#010;';
											$contadorRecC2 = 0;
											foreach ($estado_fin_cli->Score_[0]->row as $recPIC) 
											{							
												$contadorRecC2++;
												if(!empty($recPIC->score)) echo '	<SCORE> '.$recPIC->score.' </SCORE>&#013;&#010;';
												
												if(count($estado_fin_cli->Score_[0]->row) > 1 && $contadorRecC2 < count($estado_fin_cli->Score_[0]->row)) echo '&#013;&#010;';
											}
											echo '</SCORE>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->Domi_xy_[0]))
										{
											echo '<DOMICILIO_XY>&#013;&#010;';
											$contadorRecC3 = 0;
											foreach ($estado_fin_cli->Domi_xy_[0]->row as $recTJQEC) 
											{								
												$contadorRecC3++;
												if(!empty($recTJQEC->nprovincia)) echo '	<N_PROVINCIA> '.$recTJQEC->nprovincia.' </N_PROVINCIA>&#013;&#010;';
												if(!empty($recTJQEC->npartido)) echo '	<N_PARTIDO> '.$recTJQEC->npartido.' </N_PARTIDO>&#013;&#010;';
												if(!empty($recTJQEC->nlocalidad)) echo '	<N_LOCALIDAD> '.$recTJQEC->nlocalidad.' </N_LOCALIDAD>&#013;&#010;';
												if(!empty($recTJQEC->nbarrio)) echo '	<N_BARRIO> '.$recTJQEC->nbarrio.' </N_BARRIO>&#013;&#010;';
												if(!empty($recTJQEC->calle)) echo '	<CALLE> '.$recTJQEC->calle.' </CALLE>&#013;&#010;';
												if(!empty($recTJQEC->ncp)) echo '	<NCP> '.$recTJQEC->ncp.' </NCP>&#013;&#010;';
												if(!empty($recTJQEC->geocordenadas)) echo '	<GEOCORDENADAS> '.$recTJQEC->geocordenadas.' </GEOCORDENADAS>&#013;&#010;';
												if(count($estado_fin_cli->Domi_xy_[0]->row) > 1 && $contadorRecC3 < count($estado_fin_cli->Domi_xy_[0]->row)) echo '&#013;&#010;';
											}
											echo '</DOMICILIO_XY>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->Situacion_BCRA[0]))
										{							
											echo '<SITUACION_BCRA>&#013;&#010;';
											$contadorRecC4 = 0;
											foreach ($estado_fin_cli->Situacion_BCRA[0]->row as $recDSF6M) 
											{								
												$contadorRecC4++;
												if(!empty($recDSF6M->entidad)) echo '	<ENTIDAD> '.$recDSF6M->entidad.' </ENTIDAD>&#013;&#010;';
												if(!empty($recDSF6M->fecha_sit)) echo '	<FECHA> '.$recDSF6M->fecha_sit.' </FECHA>&#013;&#010;';									
												if(!empty($recDSF6M->situacion)) echo '	<SITUACION> '.$recDSF6M->situacion.' </SITUACION>&#013;&#010;';
												if(!empty($recDSF6M->deuda_total))  echo '	<DEUDA_TOTAL> '.$recDSF6M->deuda_total.' </DEUDA_TOTAL>&#013;&#010;';

												if(count($estado_fin_cli->Situacion_BCRA[0]->row) > 1 && $contadorRecC4 < count($estado_fin_cli->Situacion_BCRA[0]->row)) echo '&#013;&#010;';								
											}
											echo '</SITUACION_BCRA>&#013;&#010;&#013;&#010;';	
										}
										
										if(!empty($estado_fin_cli->FraudesTarjeta_[0]))
										{
											echo '<FRAUDES_TARJETA>&#013;&#010;';
											$contadorRecC5 = 0;
											foreach ($estado_fin_cli->FraudesTarjeta_[0]->row as $recFTs) 
											{							
												$contadorRecC5++;
												if(!empty($recFTs->fraude)) echo '	<FRAUDE> '.$recFTs->fraude.' </FRAUDE>&#013;&#010;';
												
												if(count($estado_fin_cli->FraudesTarjeta_[0]->row) > 1 && $contadorRecC5 < count($estado_fin_cli->FraudesTarjeta_[0]->row)) echo '&#013;&#010;';
											}
											echo '</FRAUDES_TARJETA>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->CapacidadEndeudamiento_[0]))
										{
											echo '<CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;';
											$contadorRecC6 = 0;
											foreach ($estado_fin_cli->CapacidadEndeudamiento_[0]->row as $recCPEC) 
											{							
												$contadorRecC6++;
												if(!empty($recCPEC->capacidad_endeudamiento)) echo '	<CAPACIDAD_ENDEUDAMIENTO> '.$recCPEC->capacidad_endeudamiento.' </CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;';
												
												if(count($estado_fin_cli->CapacidadEndeudamiento_[0]->row) > 1 && $contadorRecC6 < count($estado_fin_cli->CapacidadEndeudamiento_[0]->row)) echo '&#013;&#010;';
											}
											echo '</CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;&#013;&#010;';
										}							
										
										if(!empty($estado_fin_cli->Ooss_personas_[0]))
										{							
											echo '<OBRA_SOCIAL_PERSONAS>&#013;&#010;';
											$contadorRecC7 = 0;
											foreach ($estado_fin_cli->Ooss_personas_[0]->row as $recDSF12M) 
											{								
												$contadorRecC7++;
												if(!empty($recDSF12M->cuil)) echo '	<CUIL> '.$recDSF12M->cuil.' </CUIL>&#013;&#010;';
												if(!empty($recDSF12M->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recDSF12M->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
												if(!empty($recDSF12M->nume_docu)) echo '	<DOCUMENTO> '.$recDSF12M->nume_docu.' </DOCUMENTO>&#013;&#010;';
												if(!empty($recDSF12M->sexo)) echo '	<SEXO> '.$recDSF12M->sexo.' </SEXO>&#013;&#010;';
												if(!empty($recDSF12M->domicilio)) echo '	<DOMICILIO> '.$recDSF12M->domicilio.' </DOMICILIO>&#013;&#010;';
												if(!empty($recDSF12M->localidad)) echo '	<LOCALIDAD> '.$recDSF12M->localidad.' </LOCALIDAD>&#013;&#010;';
												if(!empty($recDSF12M->provincia)) echo '	<PROVINCIA> '.$recDSF12M->provincia.' </PROVINCIA>&#013;&#010;';
												if(!empty($recDSF12M->cod_postal)) echo '	<CODIGO_POSTAL> '.$recDSF12M->cod_postal.' </CODIGO_POSTAL>&#013;&#010;';									

												if(count($estado_fin_cli->Ooss_personas_[0]->row) > 1 && $contadorRecC7 < count($estado_fin_cli->Ooss_personas_[0]->row)) echo '&#013;&#010;';								
											}
											echo '</OBRA_SOCIAL_PERSONAS>&#013;&#010;&#013;&#010;';	
										}
										
										if(!empty($estado_fin_cli->Obra_Social_Rel_[0]))
										{							
											echo '<OBRA_SOCIAL_RELACION>&#013;&#010;';
											$contadorRecC8 = 0;
											foreach ($estado_fin_cli->Obra_Social_Rel_[0]->row as $recDSF24M) 
											{								
												$contadorRecC8++;
												if(!empty($recDSF24M->codiobra)) echo '	<CODIGO> '.$recDSF24M->codiobra.' </CODIGO>&#013;&#010;';
												if(!empty($recDSF24M->descripcion_ooss)) echo '	<DESCRIPCION> '.$recDSF24M->descripcion_ooss.' </DESCRIPCION>&#013;&#010;';
												if(!empty($recDSF24M->sigla_ooss)) echo '	<SIGLA> '.$recDSF24M->sigla_ooss.' </SIGLA>&#013;&#010;';
												if(!empty($recDSF24M->cobertura)) echo '	<COBERTURA> '.$recDSF24M->cobertura.' </COBERTURA>&#013;&#010;';
												if(!empty($recDSF24M->conyugue)) echo '	<CONYUGUE> '.$recDSF24M->conyugue.' </CONYUGUE>&#013;&#010;';
												if(!empty($recDSF24M->hijos)) echo '	<HIJOS> '.$recDSF24M->hijos.' </HIJOS>&#013;&#010;';

												if(count($estado_fin_cli->Obra_Social_Rel_[0]->row) > 1 && $contadorRecC8 < count($estado_fin_cli->Obra_Social_Rel_[0]->row)) echo '&#013;&#010;';								
											}
											echo '</OBRA_SOCIAL_RELACION>&#013;&#010;&#013;&#010;';	
										}
										
										if(!empty($estado_fin_cli->Domicilio_Empresa_trabaja_[0]))
										{
											echo '<DOMICILIO_LABORAL>&#013;&#010;';
											$contadorRecC9 = 0;
											foreach ($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row as $recRDC) 
											{								
												$contadorRecC9++;
												if(!empty($recRDC->direccion)) echo '	<DIRECCION> '.$recRDC->ult_periodo.' </DIRECCION>&#013;&#010;';
												if(!empty($recRDC->localidad)) echo '	<LOCALIDAD> '.$recRDC->alta_trabajo_ultimo.' </LOCALIDAD>&#013;&#010;';
												if(!empty($recRDC->provincia)) echo '	<PROVINCIA> '.$recRDC->cuit.' </PROVINCIA>&#013;&#010;';
												if(!empty($recRDC->cp)) echo '	<CODIGO_POSTAL> '.$recRDC->razon_social.' </CODIGO_POSTAL>&#013;&#010;';

												if(count($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row) > 1 && $contadorRecC9 < count($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row)) echo '&#013;&#010;';								
											}
											echo '</DOMICILIO LABORAL>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->domicilio_otros_[0]))
										{							
											echo '<OTROS_DOMICILIOS>&#013;&#010;';
											$contadorRecC10 = 0;
											foreach ($estado_fin_cli->domicilio_otros_[0]->row as $recCIAC) 
											{								
												$contadorRecC10++;
												if(!empty($recCIAC->domicilio)) echo '	<DOMICILIO> '.$recCIAC->domicilio.' </DOMICILIO>&#013;&#010;';
												if(!empty($recCIAC->localidad)) echo '	<LOCALIDAD> '.$recCIAC->localidad.' </LOCALIDAD>&#013;&#010;';
												if(!empty($recCIAC->cp)) echo '	<CP> '.$recCIAC->cp.' </CP>&#013;&#010;';
												if(!empty($recCIAC->provincia)) echo '	<PROVINCIA> '.$recCIAC->provincia.' </PROVINCIA>&#013;&#010;';

												if(count($estado_fin_cli->domicilio_otros_[0]->row) > 1 && $contadorRecC10 < count($estado_fin_cli->domicilio_otros_[0]->row)) echo '&#013;&#010;';								
											}
											echo '</OTROS_DOMICILIOS>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->Domicilios_Juicios_[0]))
										{
											echo '<DOMICILIOS_JUICIOS>&#013;&#010;';
											$contadorRecC11 = 0;
											foreach ($estado_fin_cli->Domicilios_Juicios_[0]->row as $recDJD) 
											{							
												$contadorRecC11++;
												if(!empty($recDJD->domicilio)) echo '	<DOMICILIO> '.$recDJD->domicilio.' </DOMICILIO>&#013;&#010;';
												
												if(count($estado_fin_cli->Domicilios_Juicios_[0]->row) > 1 && $contadorRecC11 < count($estado_fin_cli->Domicilios_Juicios_[0]->row)) echo '&#013;&#010;';
											}
											echo '</DOMICILIOS_JUICIOS>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->Padronempresasf_[0]))
										{							
											echo '<PADRON_EMPRESAS>&#013;&#010;';
											$contadorRecC12 = 0;
											foreach ($estado_fin_cli->Padronempresasf_[0]->row as $recPMC) 
											{								
												$contadorRecC12++;
												if(!empty($recPMC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recPMC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
												if(!empty($recPMC->cuit)) echo '	<CUIT> '.$recPMC->cuit.' </CUIT>&#013;&#010;';
												if(!empty($recPMC->domicilio_fiscal)) echo '	<DOMICILIO_FISCAL> '.$recPMC->domicilio_fiscal.' </DOMICILIO_FISCAL>&#013;&#010;';
												if(!empty($recPMC->provincia)) echo '	<PROVINCIA> '.$recPMC->provincia.' </PROVINCIA>&#013;&#010;';
												if(!empty($recPMC->actividad_especifica)) echo '	<ACTIVIDAD_ESPECIFICA> '.$recPMC->actividad_especifica.' </ACTIVIDAD_ESPECIFICA>&#013;&#010;';
												if(!empty($recPMC->categoria_autonomo)) echo '	<CATEGORIA_AUTONOMO> '.$recPMC->categoria_autonomo.' </CATEGORIA_AUTONOMO>&#013;&#010;';

												if(count($estado_fin_cli->Padronempresasf_[0]->row) > 1 && $contadorRecC12 < count($estado_fin_cli->Padronempresasf_[0]->row)) echo '&#013;&#010;';									
											}
											echo '</PADRON_EMPRESAS>&#013;&#010;&#013;&#010;';							
										}
										
										if(!empty($estado_fin_cli->Gran_contrib_[0]))
										{
											echo '<GRANDES_CONTRIBUYENTES>&#013;&#010;';
											$contadorRecC13 = 0;
											foreach ($estado_fin_cli->Gran_contrib_[0]->row as $recGCD) 
											{							
												$contadorRecC13++;
												if(!empty($recGCD->gran_contr)) echo '	<CONTRIBUYENTE> '.$recGCD->gran_contr.' </CONTRIBUYENTE>&#013;&#010;';
												
												if(count($estado_fin_cli->Gran_contrib_[0]->row) > 1 && $contadorRecC13 < count($estado_fin_cli->Gran_contrib_[0]->row)) echo '&#013;&#010;';
											}
											echo '</GRANDES_CONTRIBUYENTES>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->Domialter_[0]))
										{							
											echo '<DOMICILIOS_ALTERNATIVOS>&#013;&#010;';
											$contadorRecC14 = 0;
											foreach ($estado_fin_cli->Domialter_[0]->row as $recDMAC) 
											{								
												$contadorRecC14++;
												if(!empty($recDMAC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recDMAC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
												if(!empty($recDMAC->cuit)) echo '	<CUIT> '.$recDMAC->cuit.' </CUIT>&#013;&#010;';
												if(!empty($recDMAC->domicilio_fiscal)) echo '	<DOMICILIO_FISCAL> '.$recDMAC->domicilio_fiscal.' </DOMICILIO_FISCAL>&#013;&#010;';
												if(!empty($recDMAC->provincia)) echo '	<PROVINCIA> '.$recDMAC->provincia.' </PROVINCIA>&#013;&#010;';
												if(!empty($recDMAC->actividad_especifica)) echo '	<ACTIVIDAD_ESPECIFICA> '.$recDMAC->actividad_especifica.' </ACTIVIDAD_ESPECIFICA>&#013;&#010;';

												if(count($estado_fin_cli->Domialter_[0]->row) > 1 && $contadorRecC14 < count($estado_fin_cli->Domialter_[0]->row)) echo '&#013;&#010;';									
											}
											echo '</DOMICILIOS_ALTERNATIVOS>&#013;&#010;&#013;&#010;';							
										}
										
										if(!empty($estado_fin_cli->Nise_[0]))
										{
											echo '<NISE>&#013;&#010;';
											$contadorRecC15 = 0;
											foreach ($estado_fin_cli->Nise_[0]->row as $recINC) 
											{							
												$contadorRecC15++;
												if(!empty($recINC->radio)) echo '	<RADIO> '.$recINC->radio.' </RADIO>&#013;&#010;';
												if(!empty($recINC->nise)) echo '	<NISE> '.$recINC->nise.' </NISE>&#013;&#010;';
												if(!empty($recINC->periodo)) echo '	<PERIODO> '.$recINC->periodo.' </PERIODO>&#013;&#010;';
												
												if(count($estado_fin_cli->Nise_[0]->row) > 1 && $contadorRecC15 < count($estado_fin_cli->Nise_[0]->row)) echo '&#013;&#010;';
											}
											echo '</NISE>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->Const_inscrip_[0]))
										{							
											echo '<CONSTANCIAS_INSCRIPCION>&#013;&#010;';
											$contadorRecC16 = 0;
											foreach ($estado_fin_cli->Const_inscrip_[0]->row as $recCICR) 
											{								
												$contadorRecC16++;
												if(!empty($recCICR->descrip_imp_ganancias)) echo '	<DESCRIPCION_IMP_GANANCIAS> '.$recCICR->descrip_imp_ganancias.' </DESCRIPCION_IMP_GANANCIAS>&#013;&#010;';
												if(!empty($recCICR->descrip_imp_iva)) echo '	<DESCRIPCION_IMP_IVA> '.$recCICR->descrip_imp_iva.' </DESCRIPCION_IMP_IVA>&#013;&#010;';
												if(!empty($recCICR->descrip_monotributo)) echo '	<DESCRIPCION_MONOTRIBUTO> '.$recCICR->descrip_monotributo.' </DESCRIPCION_MONOTRIBUTO>&#013;&#010;';
												if(!empty($recCICR->descrip_integrante_soc)) echo '	<DESCRIPCION_INTEGRANTE_SOC> '.$recCICR->descrip_integrante_soc.' </DESCRIPCION_INTEGRANTE_SOC>&#013;&#010;';
												if(!empty($recCICR->descrip_empleador)) echo '	<DESCRIPCION_EMPLEADOR> '.$recCICR->descrip_empleador.' </DESCRIPCION_EMPLEADOR>&#013;&#010;';
												if(!empty($recCICR->periodo)) echo '	<PERIODO> '.$recCICR->periodo.' </PERIODO>&#013;&#010;';

												if(count($estado_fin_cli->Const_inscrip_[0]->row) > 1 && $contadorRecC16 < count($estado_fin_cli->Const_inscrip_[0]->row)) echo '&#013;&#010;';									
											}
											echo '</CONSTANCIAS_INSCRIPCION>&#013;&#010;&#013;&#010;';							
										}
										
										if(!empty($estado_fin_cli->Nbi_[0]))
										{
											echo '<NBI>&#013;&#010;';
											$contadorRecC17 = 0;
											foreach ($estado_fin_cli->Nbi_[0]->row as $recINBC) 
											{							
												$contadorRecC17++;
												if(!empty($recINBC->radio)) echo '	<RADIO> '.$recINBC->radio.' </RADIO>&#013;&#010;';
												if(!empty($recINBC->nbi)) echo '	<NBI> '.$recINBC->nbi.' </NBI>&#013;&#010;';
												if(!empty($recINBC->periodo)) echo '	<PERIODO> '.$recINBC->periodo.' </PERIODO>&#013;&#010;';
												
												if(count($estado_fin_cli->Nbi_[0]->row) > 1 && $contadorRecC17 < count($estado_fin_cli->Nbi_[0]->row)) echo '&#013;&#010;';
											}
											echo '</NBI>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->Iped_[0]))
										{
											echo '<IPED>&#013;&#010;';
											$contadorRecC18 = 0;
											foreach ($estado_fin_cli->Iped_[0]->row as $recIIPC) 
											{							
												$contadorRecC18++;
												if(!empty($recIIPC->radio)) echo '	<RADIO> '.$recIIPC->radio.' </RADIO>&#013;&#010;';
												if(!empty($recIIPC->iped)) echo '	<IPED> '.$recIIPC->iped.' </IPED>&#013;&#010;';
												if(!empty($recIIPC->periodo)) echo '	<PERIODO> '.$recIIPC->periodo.' </PERIODO>&#013;&#010;';
												
												if(count($estado_fin_cli->Iped_[0]->row) > 1 && $contadorRecC18 < count($estado_fin_cli->Iped_[0]->row)) echo '&#013;&#010;';
											}
											echo '</IPED>&#013;&#010;&#013;&#010;';
										}							

										if(!empty($estado_fin_cli->Ib_[0]))
										{
											echo '<IB>&#013;&#010;';
											$contadorRecC19 = 0;
											foreach ($estado_fin_cli->Ib_[0]->row as $recIIBC) 
											{							
												$contadorRecC19++;
												if(!empty($recIIBC->radio)) echo '	<RADIO> '.$recIIBC->radio.' </RADIO>&#013;&#010;';
												if(!empty($recIIBC->ib)) echo '	<IB> '.$recIIBC->ib.' </IB>&#013;&#010;';
												if(!empty($recIIBC->periodo)) echo '	<PERIODO> '.$recIIBC->periodo.' </PERIODO>&#013;&#010;';
												
												if(count($estado_fin_cli->Ib_[0]->row) > 1 && $contadorRecC19 < count($estado_fin_cli->Ib_[0]->row)) echo '&#013;&#010;';
											}
											echo '</IB>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->Rel_Dependencia_Trabajador_[0]))
										{
											echo '<RELEACION_DEPENDENCIA_TRABAJADOR>&#013;&#010;';
											$contadorRecC20 = 0;
											foreach ($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row as $recRDTC) 
											{							
												$contadorRecC20++;
												if(!empty($recRDTC->cuil)) echo '	<CUIL> '.$recRDTC->cuil.' </CUIL>&#013;&#010;';
												if(!empty($recRDTC->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recRDTC->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
												if(!empty($recRDTC->domicilio)) echo '	<DOMICILIO> '.$recRDTC->domicilio.' </DOMICILIO>&#013;&#010;';
												if(!empty($recRDTC->localidad)) echo '	<LOCALIDAD> '.$recRDTC->localidad.' </LOCALIDAD>&#013;&#010;';
												if(!empty($recRDTC->cp)) echo '	<CODIGO_POSTAL> '.$recRDTC->cp.' </CODIGO_POSTAL>&#013;&#010;';
												if(!empty($recRDTC->provincia)) echo '	<PROVINCIA> '.$recRDTC->provincia.' </PROVINCIA>&#013;&#010;';
												if(!empty($recRDTC->fecha_nac)) echo '	<FECHA_NACIMIENTO> '.$recRDTC->fecha_nac.' </FECHA_NACIMIENTO>&#013;&#010;';
												if(!empty($recRDTC->sexo)) echo '	<SEXO> '.$recRDTC->sexo.' </SEXO>&#013;&#010;';
												if(!empty($recRDTC->doc)) echo '	<DOCUMENTO> '.$recRDTC->doc.' </DOCUMENTO>&#013;&#010;';
												
												if(count($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row) > 1 && $contadorRecC20 < count($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row)) echo '&#013;&#010;';
											}
											echo '</RELEACION_DEPENDENCIA_TRABAJADOR>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->Rel_Dependencia_Empleador_[0]))
										{
											echo '<RELEACION_DEPENDENCIA_EMPLEADOR>&#013;&#010;';
											$contadorRecC21 = 0;
											foreach ($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row as $recRDEC) 
											{							
												$contadorRecC21++;
												if(!empty($recRDEC->cuit)) echo '	<CUIT> '.$recRDEC->cuit.' </CUIT>&#013;&#010;';
												if(!empty($recRDEC->razon)) echo '	<RAZON_SOCIAL> '.$recRDEC->razon.' </RAZON_SOCIAL>&#013;&#010;';
												if(!empty($recRDEC->domicilio)) echo '	<DOMICILIO> '.$recRDEC->domicilio.' </DOMICILIO>&#013;&#010;';
												if(!empty($recRDEC->localidad)) echo '	<LOCALIDAD> '.$recRDEC->localidad.' </LOCALIDAD>&#013;&#010;';
												if(!empty($recRDEC->cp)) echo '	<CODIGO_POSTAL> '.$recRDEC->cp.' </CODIGO_POSTAL>&#013;&#010;';
												if(!empty($recRDEC->provincia)) echo '	<PROVINCIA> '.$recRDEC->provincia.' </PROVINCIA>&#013;&#010;';
												
												if(count($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row) > 1 && $contadorRecC21 < count($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row)) echo '&#013;&#010;';
											}
											echo '</RELEACION_DEPENDENCIA_EMPLEADOR>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->Historia_[0]))
										{
											echo '<HISTORIA_EMPRESAS>&#013;&#010;';
											$contadorRecC22 = 0;
											foreach ($estado_fin_cli->Historia_[0]->row as $recHEC) 
											{							
												$contadorRecC22++;
												if(!empty($recHEC->cuit)) echo '	<CUIT> '.$recHEC->cuit.' </CUIT>&#013;&#010;';
												if(!empty($recHEC->razon)) echo '	<RAZON_SOCIAL> '.$recHEC->razon.' </RAZON_SOCIAL>&#013;&#010;';
												if(!empty($recHEC->desde)) echo '	<DESDE> '.$recHEC->desde.' </DESDE>&#013;&#010;';
												if(!empty($recHEC->hasta)) echo '	<HASTA> '.$recHEC->hasta.' </HASTA>&#013;&#010;';
												
												if(count($estado_fin_cli->Historia_[0]->row) > 1 && $contadorRecC22 < count($estado_fin_cli->Historia_[0]->row)) echo '&#013;&#010;';
											}
											echo '</HISTORIA_EMPRESAS>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->Sysem_[0]))
										{
											echo '<SYSEM>&#013;&#010;';
											$contadorRecC23 = 0;
											foreach ($estado_fin_cli->Sysem_[0]->row as $recSYC) 
											{							
												$contadorRecC23++;
												if(!empty($recSYC->cant_emp)) echo '	<CANTIDAD_EMPLEADOS> '.$recSYC->cant_emp.' </CANTIDAD_EMPLEADOS>&#013;&#010;';
												if(!empty($recSYC->cuit)) echo '	<CUIT> '.$recSYC->cuit.' </CUIT>&#013;&#010;';
												if(!empty($recSYC->razon)) echo '	<RAZON_SOCIAL> '.$recSYC->razon.' </RAZON_SOCIAL>&#013;&#010;';
												if(!empty($recSYC->domicilio)) echo '	<DOMICILIO> '.$recSYC->domicilio.' </DOMICILIO>&#013;&#010;';
												if(!empty($recSYC->localidad)) echo '	<LOCALIDAD> '.$recSYC->localidad.' </LOCALIDAD>&#013;&#010;';
												if(!empty($recSYC->cp)) echo '	<CODIGO_POSTAL> '.$recSYC->cp.' </CODIGO_POSTAL>&#013;&#010;';
												if(!empty($recSYC->provincia)) echo '	<PROVINCIA> '.$recSYC->provincia.' </PROVINCIA>&#013;&#010;';
												
												if(count($estado_fin_cli->Sysem_[0]->row) > 1 && $contadorRecC23 < count($estado_fin_cli->Sysem_[0]->row)) echo '&#013;&#010;';
											}
											echo '</SYSEM>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->Jyj_mensual_[0]))
										{
											echo '<SYSEM>&#013;&#010;';
											$contadorRecC24 = 0;
											foreach ($estado_fin_cli->Jyj_mensual_[0]->row as $recJYJC) 
											{							
												$contadorRecC24++;
												if(!empty($recJYJC->cuil)) echo '	<CUIL> '.$recJYJC->cuil.' </CUIL>&#013;&#010;';
												if(!empty($recJYJC->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recJYJC->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
												if(!empty($recJYJC->rango_socio_econom)) echo '	<RANGO_SOCIO_ECONOMICO> '.$recJYJC->rango_socio_econom.' </RANGO_SOCIO_ECONOMICO>&#013;&#010;';
												
												if(count($estado_fin_cli->Jyj_mensual_[0]->row) > 1 && $contadorRecC24 < count($estado_fin_cli->Jyj_mensual_[0]->row)) echo '&#013;&#010;';
											}
											echo '</SYSEM>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->Situacionlaboral_[0]))
										{
											echo '<SITUACION_LABORAL>&#013;&#010;';
											$contadorRecC25 = 0;
											foreach ($estado_fin_cli->Situacionlaboral_[0]->row as $recSLC) 
											{							
												$contadorRecC25++;
												if(!empty($recSLC->situacionlaboral)) echo '	<ESTADO_LABORAL> '.$recSLC->situacionlaboral.' </ESTADO_LABORAL>&#013;&#010;';
												
												if(count($estado_fin_cli->Situacionlaboral_[0]->row) > 1 && $contadorRecC25 < count($estado_fin_cli->Situacionlaboral_[0]->row)) echo '&#013;&#010;';
											}
											echo '</SITUACION_LABORAL>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->Producto_financieros_[0]))
										{
											echo '<PRODUCTO_FINANCIEROS>&#013;&#010;';
											$contadorRecC26 = 0;
											foreach ($estado_fin_cli->Producto_financieros_[0]->row as $recPFC) 
											{							
												$contadorRecC26++;
												if(!empty($recPFC->producto_financieros)) echo '	<PRODUCTO> '.$recPFC->producto_financieros.' </PRODUCTO>&#013;&#010;';
												
												if(count($estado_fin_cli->Producto_financieros_[0]->row) > 1 && $contadorRecC26 < count($estado_fin_cli->Producto_financieros_[0]->row)) echo '&#013;&#010;';
											}
											echo '</PRODUCTO_FINANCIEROS>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->Bancarizado_[0]))
										{
											echo '<BANCARIZADO>&#013;&#010;';
											$contadorRecC27 = 0;
											foreach ($estado_fin_cli->Bancarizado_[0]->row as $recEBC) 
											{							
												$contadorRecC27++;
												if(!empty($recEBC->bancarizado)) echo '	<ESTADO> '.$recEBC->bancarizado.' </ESTADO>&#013;&#010;';
												
												if(count($estado_fin_cli->Bancarizado_[0]->row) > 1 && $contadorRecC27 < count($estado_fin_cli->Bancarizado_[0]->row)) echo '&#013;&#010;';
											}
											echo '</BANCARIZADO>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->FechaMaxSistFin_[0]))
										{
											echo '<FECHA_MAXIMA_SISTEMA_FINANCIERO>&#013;&#010;';
											$contadorRecC28 = 0;
											foreach ($estado_fin_cli->FechaMaxSistFin_[0]->row as $recFMSF) 
											{							
												$contadorRecC28++;
												if(!empty($recFMSF->fecha)) echo '	<FECHA> '.$recFMSF->fecha.' </FECHA>&#013;&#010;';
												
												if(count($estado_fin_cli->FechaMaxSistFin_[0]->row) > 1 && $contadorRecC28 < count($estado_fin_cli->FechaMaxSistFin_[0]->row)) echo '&#013;&#010;';
											}
											echo '</FECHA_MAXIMA_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
										}	

										if(!empty($estado_fin_cli->SistFinEvolucionDeuda_[0]))
										{
											echo '<EVOLUCION_DEUDA_SISTEMA_FINANCIERO>&#013;&#010;';
											$contadorRecC29 = 0;
											foreach ($estado_fin_cli->SistFinEvolucionDeuda_[0]->row as $recEDSF) 
											{							
												$contadorRecC29++;
												if(!empty($recEDSF->entidad)) echo '	<ENTIDAD> '.$recEDSF->entidad.' </ENTIDAD>&#013;&#010;';
												if(!empty($recEDSF->m1)) echo '	<MES1> '.$recEDSF->m1.' </MES1>&#013;&#010;';
												if(!empty($recEDSF->m2)) echo '	<MES2> '.$recEDSF->m2.' </MES2>&#013;&#010;';
												if(!empty($recEDSF->m3)) echo '	<MES3> '.$recEDSF->m3.' </MES3>&#013;&#010;';
												if(!empty($recEDSF->m4)) echo '	<MES4> '.$recEDSF->m4.' </MES4>&#013;&#010;';
												if(!empty($recEDSF->m5)) echo '	<MES5> '.$recEDSF->m5.' </MES5>&#013;&#010;';
												if(!empty($recEDSF->m6)) echo '	<MES6> '.$recEDSF->m6.' </MES6>&#013;&#010;';
												if(!empty($recEDSF->m7)) echo '	<MES7> '.$recEDSF->m7.' </MES7>&#013;&#010;';
												if(!empty($recEDSF->m8)) echo '	<MES8> '.$recEDSF->m8.' </MES8>&#013;&#010;';
												if(!empty($recEDSF->m9)) echo '	<MES9> '.$recEDSF->m9.' </MES9>&#013;&#010;';
												if(!empty($recEDSF->m10)) echo '	<MES10> '.$recEDSF->m10.' </MES10>&#013;&#010;';
												if(!empty($recEDSF->m11)) echo '	<MES11> '.$recEDSF->m11.' </MES11>&#013;&#010;';
												if(!empty($recEDSF->m12)) echo '	<MES12> '.$recEDSF->m12.' </MES12>&#013;&#010;';
												if(!empty($recEDSF->m13)) echo '	<MES13> '.$recEDSF->m13.' </MES13>&#013;&#010;';
												if(!empty($recEDSF->m14)) echo '	<MES14> '.$recEDSF->m14.' </MES14>&#013;&#010;';
												if(!empty($recEDSF->m15)) echo '	<MES15> '.$recEDSF->m15.' </MES15>&#013;&#010;';
												if(!empty($recEDSF->m16)) echo '	<MES16> '.$recEDSF->m16.' </MES16>&#013;&#010;';
												if(!empty($recEDSF->m17)) echo '	<MES17> '.$recEDSF->m17.' </MES17>&#013;&#010;';
												if(!empty($recEDSF->m18)) echo '	<MES18> '.$recEDSF->m18.' </MES18>&#013;&#010;';
												if(!empty($recEDSF->m19)) echo '	<MES19> '.$recEDSF->m19.' </MES19>&#013;&#010;';
												if(!empty($recEDSF->m20)) echo '	<MES20> '.$recEDSF->m20.' </MES20>&#013;&#010;';
												if(!empty($recEDSF->m21)) echo '	<MES21> '.$recEDSF->m21.' </MES21>&#013;&#010;';
												if(!empty($recEDSF->m22)) echo '	<MES22> '.$recEDSF->m22.' </MES22>&#013;&#010;';
												if(!empty($recEDSF->m23)) echo '	<MES23> '.$recEDSF->m23.' </MES23>&#013;&#010;';
												if(!empty($recEDSF->m24)) echo '	<MES24> '.$recEDSF->m24.' </MES24>&#013;&#010;';									
												if(!empty($recEDSF->deuda_actual)) echo '	<DEUDA_ACTUAL> '.$recEDSF->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
												if(!empty($recEDSF->monto_maximo)) echo '	<MONTO_MAXIMO> '.$recEDSF->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
												if(!empty($recEDSF->bco)) echo '	<BANCO> '.$recEDSF->bco.' </BANCO>&#013;&#010;';
												
												if(count($estado_fin_cli->SistFinEvolucionDeuda_[0]->row) > 1 && $contadorRecC29 < count($estado_fin_cli->SistFinEvolucionDeuda_[0]->row)) echo '&#013;&#010;';
											}
											echo '</EVOLUCION_DEUDA_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]))
										{
											echo '<EVOLUCION_DEUDA_PROPIO_SISTEMA_FINANCIERO>&#013;&#010;';
											$contadorRecC30 = 0;
											foreach ($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row as $recEDPSF) 
											{							
												$contadorRecC30++;
												if(!empty($recEDPSF->entidad)) echo '	<ENTIDAD> '.$recEDPSF->entidad.' </ENTIDAD>&#013;&#010;';
												if(!empty($recEDPSF->m1)) echo '	<MES1> '.$recEDPSF->m1.' </MES1>&#013;&#010;';
												if(!empty($recEDPSF->m2)) echo '	<MES2> '.$recEDPSF->m2.' </MES2>&#013;&#010;';
												if(!empty($recEDPSF->m3)) echo '	<MES3> '.$recEDPSF->m3.' </MES3>&#013;&#010;';
												if(!empty($recEDPSF->m4)) echo '	<MES4> '.$recEDPSF->m4.' </MES4>&#013;&#010;';
												if(!empty($recEDPSF->m5)) echo '	<MES5> '.$recEDPSF->m5.' </MES5>&#013;&#010;';
												if(!empty($recEDPSF->m6)) echo '	<MES6> '.$recEDPSF->m6.' </MES6>&#013;&#010;';
												if(!empty($recEDPSF->m7)) echo '	<MES7> '.$recEDPSF->m7.' </MES7>&#013;&#010;';
												if(!empty($recEDPSF->m8)) echo '	<MES8> '.$recEDPSF->m8.' </MES8>&#013;&#010;';
												if(!empty($recEDPSF->m9)) echo '	<MES9> '.$recEDPSF->m9.' </MES9>&#013;&#010;';
												if(!empty($recEDPSF->m10)) echo '	<MES10> '.$recEDPSF->m10.' </MES10>&#013;&#010;';
												if(!empty($recEDPSF->m11)) echo '	<MES11> '.$recEDPSF->m11.' </MES11>&#013;&#010;';
												if(!empty($recEDPSF->m12)) echo '	<MES12> '.$recEDPSF->m12.' </MES12>&#013;&#010;';
												if(!empty($recEDPSF->m13)) echo '	<MES13> '.$recEDPSF->m13.' </MES13>&#013;&#010;';
												if(!empty($recEDPSF->m14)) echo '	<MES14> '.$recEDPSF->m14.' </MES14>&#013;&#010;';
												if(!empty($recEDPSF->m15)) echo '	<MES15> '.$recEDPSF->m15.' </MES15>&#013;&#010;';
												if(!empty($recEDPSF->m16)) echo '	<MES16> '.$recEDPSF->m16.' </MES16>&#013;&#010;';
												if(!empty($recEDPSF->m17)) echo '	<MES17> '.$recEDPSF->m17.' </MES17>&#013;&#010;';
												if(!empty($recEDPSF->m18)) echo '	<MES18> '.$recEDPSF->m18.' </MES18>&#013;&#010;';
												if(!empty($recEDPSF->m19)) echo '	<MES19> '.$recEDPSF->m19.' </MES19>&#013;&#010;';
												if(!empty($recEDPSF->m20)) echo '	<MES20> '.$recEDPSF->m20.' </MES20>&#013;&#010;';
												if(!empty($recEDPSF->m21)) echo '	<MES21> '.$recEDPSF->m21.' </MES21>&#013;&#010;';
												if(!empty($recEDPSF->m22)) echo '	<MES22> '.$recEDPSF->m22.' </MES22>&#013;&#010;';
												if(!empty($recEDPSF->m23)) echo '	<MES23> '.$recEDPSF->m23.' </MES23>&#013;&#010;';
												if(!empty($recEDPSF->m24)) echo '	<MES24> '.$recEDPSF->m24.' </MES24>&#013;&#010;';									
												if(!empty($recEDPSF->deuda_actual)) echo '	<DEUDA_ACTUAL> '.$recEDPSF->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
												if(!empty($recEDPSF->monto_maximo)) echo '	<MONTO_MAXIMO> '.$recEDPSF->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
												if(!empty($recEDPSF->bco)) echo '	<BANCO> '.$recEDPSF->bco.' </BANCO>&#013;&#010;';
												
												if(count($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row) > 1 && $contadorRecC30 < count($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row)) echo '&#013;&#010;';
											}
											echo '</EVOLUCION_DEUDA_PROPIO_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
										}							
										
										if(!empty($estado_fin_cli->Bcra_Ultimas_Situaciones[0]))
										{
											echo '<BANCO_CENTRAL_ULTIMAS_SITUACIONES>&#013;&#010;';
											$contadorRecC31 = 0;
											foreach ($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row as $recBCUS) 
											{							
												$contadorRecC31++;
												if(!empty($recBCUS->entidad)) echo '	<ENTIDAD> '.$recBCUS->entidad.' </ENTIDAD>&#013;&#010;';
												if(!empty($recBCUS->periodo)) echo '	<PERIODO> '.$recBCUS->periodo.' </PERIODO>&#013;&#010;';
												if(!empty($recBCUS->situacion)) echo '	<SITUACION> '.$recBCUS->situacion.' </SITUACION>&#013;&#010;';
												if(!empty($recBCUS->monto)) echo '	<MONTO> '.$recBCUS->monto.' </MONTO>&#013;&#010;';
												
												if(count($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row) > 1 && $contadorRecC31 < count($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row)) echo '&#013;&#010;';
											}
											echo '</BANCO_CENTRAL_ULTIMAS_SITUACIONES>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->Bcra_Situaciones_Vigente[0]))
										{
											echo '<BANCO_CENTRAL_SITUACIONES_VIGENTES>&#013;&#010;';
											$contadorRecC32 = 0;
											foreach ($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row as $recBCSV) 
											{							
												$contadorRecC32++;
												if(!empty($recBCSV->entidad)) echo '	<ENTIDAD> '.$recBCSV->entidad.' </ENTIDAD>&#013;&#010;';
												if(!empty($recBCSV->periodos)) echo '	<PERIODOS> '.$recBCSV->periodos.' </PERIODOS>&#013;&#010;';
												if(!empty($recBCSV->situacion)) echo '	<SITUACION> '.$recBCSV->situacion.' </SITUACION>&#013;&#010;';
												if(!empty($recBCSV->monto)) echo '	<MONTO> '.$recBCSV->monto.' </MONTO>&#013;&#010;';
												
												if(count($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row) > 1 && $contadorRecC32 < count($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row)) echo '&#013;&#010;';
											}
											echo '</BANCO_CENTRAL_SITUACIONES_VIGENTES>&#013;&#010;&#013;&#010;';
										}							
										
										if(!empty($estado_fin_cli->Tipo_Actividad[0]))
										{							
											echo '<TIPO_ACTIVIDAD>&#013;&#010;';
											$contadorRecC33 = 0;
											foreach ($estado_fin_cli->Tipo_Actividad[0]->row as $recTAC) 
											{								
												$contadorRecC33++;
												echo '	<TIPO_ACTIVIDAD> '.$recTAC->tipo_actividad.' </TIPO_ACTIVIDAD>&#013;&#010;';

												if(count($estado_fin_cli->Tipo_Actividad[0]->row) > 1 && $contadorRecC33 < count($estado_fin_cli->Tipo_Actividad[0]->row)) echo '&#013;&#010;';								
											}
											echo '</TIPO_ACTIVIDAD>&#013;&#010;&#013;&#010;';	
										}								
								
										if(!empty($estado_fin_cli->Consultas_Individual_[0]))
										{
											echo '<CONSULTAS_INDIVIDUALES>&#013;&#010;';
											$contadorRecC34 = 0;
											foreach ($estado_fin_cli->Consultas_Individual_[0]->row as $recCINDC) 
											{							
												$contadorRecC34++;
												if(!empty($recCINDC->dia)) echo '	<DIA> '.$recCINDC->dia.' </DIA>&#013;&#010;';
												if(!empty($recCINDC->semana)) echo '	<SEMANA> '.$recCINDC->semana.' </SEMANA>&#013;&#010;';
												if(!empty($recCINDC->mes)) echo '	<MES> '.$recCINDC->mes.' </MES>&#013;&#010;';
												if(!empty($recCINDC->m24)) echo '	<M24> '.$recCINDC->m24.' </M24>&#013;&#010;';
												
												if(count($estado_fin_cli->Consultas_Individual_[0]->row) > 1 && $contadorRecC34 < count($estado_fin_cli->Consultas_Individual_[0]->row)) echo '&#013;&#010;';
											}
											echo '</CONSULTAS_INDIVIDUALES>&#013;&#010;&#013;&#010;';
										}

										if(!empty($estado_fin_cli->Consultas_Grupo_[0]))
										{
											echo '<CONSULTAS_GRUPO>&#013;&#010;';
											$contadorRecC35 = 0;
											foreach ($estado_fin_cli->Consultas_Grupo_[0]->row as $recCGRUC) 
											{							
												$contadorRecC35++;
												if(!empty($recCGRUC->dia)) echo '	<DIA> '.$recCGRUC->dia.' </DIA>&#013;&#010;';
												if(!empty($recCGRUC->semana)) echo '	<SEMANA> '.$recCGRUC->semana.' </SEMANA>&#013;&#010;';
												if(!empty($recCGRUC->mes)) echo '	<MES> '.$recCGRUC->mes.' </MES>&#013;&#010;';
												if(!empty($recCGRUC->m24)) echo '	<M24> '.$recCGRUC->m24.' </M24>&#013;&#010;';
												
												if(count($estado_fin_cli->Consultas_Grupo_[0]->row) > 1 && $contadorRecC35 < count($estado_fin_cli->Consultas_Grupo_[0]->row)) echo '&#013;&#010;';
											}
											echo '</CONSULTAS_GRUPO>&#013;&#010;&#013;&#010;';
										}	

										if(!empty($estado_fin_cli->CONSULTADO_POR_[0]))
										{
											echo '<CONSULTADO_POR>&#013;&#010;';
											$contadorRecC36 = 0;
											foreach ($estado_fin_cli->CONSULTADO_POR_[0]->row as $recCCONP) 
											{							
												$contadorRecC36++;
												if(!empty($recCCONP->fecha)) echo '	<FECHA> '.$recCCONP->fecha.' </FECHA>&#013;&#010;';
												if(!empty($recCCONP->consultante)) echo '	<CONSULTANTE> '.$recCCONP->consultante.' </CONSULTANTE>&#013;&#010;';
												
												if(count($estado_fin_cli->CONSULTADO_POR_[0]->row) > 1 && $contadorRecC36 < count($estado_fin_cli->CONSULTADO_POR_[0]->row)) echo '&#013;&#010;';
											}
											echo '</CONSULTADO_POR>&#013;&#010;&#013;&#010;';
										}							
								
										if(!empty($estado_fin_cli->Independientes_[0]))
										{
											echo '<INDEPENDIENTES>&#013;&#010;';
											$contadorRecC37 = 0;
											foreach ($estado_fin_cli->Independientes_[0]->row as $recCONIND) 
											{							
												$contadorRecC37++;
												if(!empty($recCONIND->inscripto)) echo '	<INSCRIPTO> '.$recCONIND->inscripto.' </INSCRIPTO>&#013;&#010;';
												if(!empty($recCONIND->categoria)) echo '	<CATEGORIA> '.$recCONIND->categoria.' </CATEGORIA>&#013;&#010;';
												if(!empty($recCONIND->cuit)) echo '	<CUIT> '.$recCONIND->cuit.' </CUIT>&#013;&#010;';
												if(!empty($recCONIND->documento)) echo '	<DOCUMENTO> '.$recCONIND->documento.' </DOCUMENTO>&#013;&#010;';
												if(!empty($recCONIND->nombre)) echo '	<NOMBRE> '.$recCONIND->nombre.' </NOMBRE>&#013;&#010;';
												if(!empty($recCONIND->domicilio)) echo '	<DOMICILIO> '.$recCONIND->domicilio.' </DOMICILIO>&#013;&#010;';
												if(!empty($recCONIND->cod_postal)) echo '	<CODIGO_POSTAL> '.$recCONIND->cod_postal.' </CODIGO_POSTAL>&#013;&#010;';
												if(!empty($recCONIND->localidad)) echo '	<LOCALIDAD> '.$recCONIND->localidad.' </LOCALIDAD>&#013;&#010;';
												if(!empty($recCONIND->provincia)) echo '	<PROVINCIA> '.$recCONIND->provincia.' </PROVINCIA>&#013;&#010;';
												if(!empty($recCONIND->fecha_nac)) echo '	<FECHA_NACIMIENTO> '.$recCONIND->fecha_nac.' </FECHA_NACIMIENTO>&#013;&#010;';
												if(!empty($recCONIND->entidad)) echo '	<ENTIDAD> '.$recCONIND->entidad.' </ENTIDAD>&#013;&#010;';
												if(!empty($recCONIND->actividad)) echo '	<ACTIVIDAD> '.$recCONIND->actividad.' </ACTIVIDAD>&#013;&#010;';
												if(!empty($recCONIND->periodo)) echo '	<PERIODO> '.$recCONIND->periodo.' </PERIODO>&#013;&#010;';
												if(!empty($recCONIND->monto)) echo '	<MONTO> '.$recCONIND->monto.' </MONTO>&#013;&#010;';									
												
												if(count($estado_fin_cli->Independientes_[0]->row) > 1 && $contadorRecC37 < count($estado_fin_cli->Independientes_[0]->row)) echo '&#013;&#010;';
											}
											echo '</INDEPENDIENTES>&#013;&#010;&#013;&#010;';
										}
										
										if(!empty($estado_fin_cli->Indep_apo_[0]))
										{
											echo '<APORTES_INDEPENDIENTE>&#013;&#010;';
											$contadorRecC38 = 0;
											foreach ($estado_fin_cli->Indep_apo_[0]->row as $recAPCI) 
											{							
												$contadorRecC38++;
												if(!empty($recAPCI->cuit)) echo '	<CUIT> '.$recAPCI->cuit.' </CUIT>&#013;&#010;';
												if(!empty($recAPCI->periodo)) echo '	<PERIODO> '.$recAPCI->periodo.' </PERIODO>&#013;&#010;';
												if(!empty($recAPCI->imp_pago)) echo '	<IMPORTE> '.$recAPCI->imp_pago.' </IMPORTE>&#013;&#010;';
												if(!empty($recAPCI->impuesto)) echo '	<IMPUESTO> '.$recAPCI->impuesto.' </IMPUESTO>&#013;&#010;';
												
												if(count($estado_fin_cli->Indep_apo_[0]->row) > 1 && $contadorRecC38 < count($estado_fin_cli->Indep_apo_[0]->row)) echo '&#013;&#010;';
											}
											echo '</APORTES_INDEPENDIENTE>&#013;&#010;&#013;&#010;';
										}							
										
										if(!empty($estado_fin_cli->Propiedades_[0]))
										{
											echo '<PROPIEDADES>&#013;&#010;';
											$contadorRecC39 = 0;
											foreach ($estado_fin_cli->Propiedades_[0]->row as $recIPROPC) 
											{							
												$contadorRecC39++;
												if(!empty($recIPROPC->titular)) echo '	<TITULAR> '.$recIPROPC->titular.' </TITULAR>&#013;&#010;';
												if(!empty($recIPROPC->folio)) echo '	<FOLIO> '.$recIPROPC->folio.' </FOLIO>&#013;&#010;';
												if(!empty($recIPROPC->ano_inscripcion)) echo '	<AÑO_INSCRIPCION> '.$recIPROPC->ano_inscripcion.' </AÑO_INSCRIPCION>&#013;&#010;';
												if(!empty($recIPROPC->ph)) echo '	<PH> '.$recIPROPC->ph.' </PH>&#013;&#010;';
												if(!empty($recIPROPC->condom)) echo '	<CONDOM> '.$recIPROPC->condom.' </CONDOM>&#013;&#010;';
												if(!empty($recIPROPC->codigo_departamento)) echo '	<CODIGO_DEPARTAMENTO> '.$recIPROPC->codigo_departamento.' </CODIGO_DEPARTAMENTO>&#013;&#010;';
												if(!empty($recIPROPC->pedania)) echo '	<PEDANIA> '.$recIPROPC->pedania.' </PEDANIA>&#013;&#010;';
												if(!empty($recIPROPC->pueblo)) echo '	<PUEBLO> '.$recIPROPC->pueblo.' </PUEBLO>&#013;&#010;';
												if(!empty($recIPROPC->circuncripcion)) echo '	<CIRCUNCRIPCION> '.$recIPROPC->circuncripcion.' </CIRCUNCRIPCION>&#013;&#010;';
												if(!empty($recIPROPC->seccion)) echo '	<SECCION> '.$recIPROPC->seccion.' </SECCION>&#013;&#010;';
												if(!empty($recIPROPC->manzana)) echo '	<MANZANA> '.$recIPROPC->manzana.' </MANZANA>&#013;&#010;';
												if(!empty($recIPROPC->parcela)) echo '	<PARCELA> '.$recIPROPC->parcela.' </PARCELA>&#013;&#010;';
												if(!empty($recIPROPC->escrituro)) echo '	<ESCRITURO> '.$recIPROPC->escrituro.' </ESCRITURO>&#013;&#010;';
												
												if(count($estado_fin_cli->Propiedades_[0]->row) > 1 && $contadorRecC39 < count($estado_fin_cli->Propiedades_[0]->row)) echo '&#013;&#010;';
											}
											echo '</PROPIEDADES>&#013;&#010;&#013;&#010;';
										}
										echo '						</textarea>';
										echo '					</div>';		
										echo '				</div>';									
									}
									
									$stmt44->free_result();
									$stmt44->close();
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
							$estado_fin_cli = new SimpleXMLElement($resultado_finan_cli_final);
							echo translate('Msg_Validation_Credit_Status_Client_OK',$GLOBALS['lang']).'=::=::=::'.$tokenECF.'=:=:=:';
							echo '<div class="panel-group">';				
							echo '	<div class="panel panel-default">';
							echo '		<div id="panel-title-header" class="panel-heading">';
							echo '			<h3 class="panel-title">'.translate('Lbl_Result_Financial_Statement_Client',$GLOBALS['lang']).'</h3>';
							echo ' 		</div>';
							echo '		<div class="panel-body">';
							echo '			<form id="formulariocefcm" role="form" onsubmit="buscarTextoEstadoFinancieroM(); return false;">';		
							echo '				<div class="form-group form-inline">';
							echo '					<div class="form-group" id="buscartextoestadocrediticioclientem">';
							echo '						<input class="form-control input-sm" id="buscartextoestadocrediticioclientemi" name="buscartextoestadocrediticioclientemi" type="text" maxlength="150" />';
							echo '						&nbsp;<button type="button" class="btn" id="btnBuscarEstadoFM" name="btnBuscarEstadoFM" title="'.translate('Lbl_Search_Text_Statement_Client',$GLOBALS['lang']).'" onclick="buscarTextoEstadoFinancieroM();"><i class="fas fa-search"></i></button>';									
							echo '					</div>';
							echo '				</div>';
							echo '				<div class="form-group form-inline">';					
							echo '					<div class="form-group" id="resultadoestadofinancieroclientem">';
							echo '						<textarea rows="3" cols="67" class="form-control input-sm" id="resultadoestadofinancieroclientemi" name="resultadoestadofinancieroclientemi">';
							echo '<EXISTENCIA_FISICA_ENTIDAD>&#013;&#010;';
							$contadorRecC1 = 0;
							foreach ($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row as $recEFC) 
							{
								$contadorRecC1++;
								if(!empty($recEFC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recEFC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
								if(!empty($recEFC->sexo)) echo '	<SEXO> '.$recEFC->sexo.' </SEXO>&#013;&#010;';
								if(!empty($recEFC->tipo_docu)) echo '	<TIPO_DOCUMENTO> '.$recEFC->tipo_docu.' </TIPO_DOCUMENTO>&#013;&#010;';
								if(!empty($recEFC->nume_doc)) echo '	<DOCUMENTO> '.$recEFC->nume_doc.' </DOCUMENTO>&#013;&#010;';
								if(!empty($recEFC->clase)) echo '	<CLASE> '.$recEFC->clase.' </CLASE>&#013;&#010;';
								if(!empty($recEFC->edad)) echo '	<EDAD> '.$recEFC->edad.' </EDAD>&#013;&#010;';
								if(!empty($recEFC->ocupacion)) echo '	<OCUPACION> '.$recEFC->ocupacion.' </OCUPACION>&#013;&#010;';
								if(!empty($recEFC->cdi_codigo_de_identificacion)) echo '	<CUIT_CUIL> '.$recEFC->cdi_codigo_de_identificacion.' </CUIT_CUIL>&#013;&#010;';								
								if(!empty($recEFC->fecha_nacimiento)) echo '	<FECHA_NACIMIENTO> '.$recEFC->fecha_nacimiento.' </FECHA_NACIMIENTO>&#013;&#010;';							
								if(!empty($recEFC->direc_calle)) echo '	<DIRECCION_CALLE> '.$recEFC->direc_calle.' </DIRECCION_CALLE>&#013;&#010;';							
								if(!empty($recEFC->localidad)) echo '	<LOCALIDAD> '.$recEFC->localidad.' </LOCALIDAD>&#013;&#010;';
								if(!empty($recEFC->departamento)) echo '	<DEPARTAMENTO> '.$recEFC->departamento.' </DEPARTAMENTO>&#013;&#010;';								
								if(!empty($recEFC->codigo_postal)) echo '	<CODIGO_POSTAL> '.$recEFC->codigo_postal.' </CODIGO_POSTAL>&#013;&#010;';							
								if(!empty($recEFC->provincia)) echo '	<PROVINCIA> '.$recEFC->provincia.' </PROVINCIA>&#013;&#010;';							
								if(!empty($recEFC->apellido_materno)) echo '	<APELLIDO_MATERNO> '.$recEFC->apellido_materno.' </APELLIDO_MATERNO>&#013;&#010;';
								if(!empty($recEFC->estado_civil)) echo '	<ESTADO_CIVIL> '.$recEFC->estado_civil.' </ESTADO_CIVIL>&#013;&#010;';								
								if(!empty($recEFC->fallecido)) echo '	<FALLECIDO> '.$recEFC->fallecido.' </FALLECIDO>&#013;&#010;';
								
								if(count($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row) > 1 && $contadorRecC1 < count($estado_fin_cli->ExistenciaFisicaEntidad_[0]->row)) echo '&#013;&#010;';
							}							
							echo '</EXISTENCIA_FISICA_ENTIDAD>&#013;&#010;&#013;&#010;';
							
							if(!empty($estado_fin_cli->Score_[0]))
							{
								echo '<SCORE>&#013;&#010;';
								$contadorRecC2 = 0;
								foreach ($estado_fin_cli->Score_[0]->row as $recPIC) 
								{							
									$contadorRecC2++;
									if(!empty($recPIC->score)) echo '	<SCORE> '.$recPIC->score.' </SCORE>&#013;&#010;';
									
									if(count($estado_fin_cli->Score_[0]->row) > 1 && $contadorRecC2 < count($estado_fin_cli->Score_[0]->row)) echo '&#013;&#010;';
								}
								echo '</SCORE>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->Domi_xy_[0]))
							{
								echo '<DOMICILIO_XY>&#013;&#010;';
								$contadorRecC3 = 0;
								foreach ($estado_fin_cli->Domi_xy_[0]->row as $recTJQEC) 
								{								
									$contadorRecC3++;
									if(!empty($recTJQEC->nprovincia)) echo '	<N_PROVINCIA> '.$recTJQEC->nprovincia.' </N_PROVINCIA>&#013;&#010;';
									if(!empty($recTJQEC->npartido)) echo '	<N_PARTIDO> '.$recTJQEC->npartido.' </N_PARTIDO>&#013;&#010;';
									if(!empty($recTJQEC->nlocalidad)) echo '	<N_LOCALIDAD> '.$recTJQEC->nlocalidad.' </N_LOCALIDAD>&#013;&#010;';
									if(!empty($recTJQEC->nbarrio)) echo '	<N_BARRIO> '.$recTJQEC->nbarrio.' </N_BARRIO>&#013;&#010;';
									if(!empty($recTJQEC->calle)) echo '	<CALLE> '.$recTJQEC->calle.' </CALLE>&#013;&#010;';
									if(!empty($recTJQEC->ncp)) echo '	<NCP> '.$recTJQEC->ncp.' </NCP>&#013;&#010;';
									if(!empty($recTJQEC->geocordenadas)) echo '	<GEOCORDENADAS> '.$recTJQEC->geocordenadas.' </GEOCORDENADAS>&#013;&#010;';
									if(count($estado_fin_cli->Domi_xy_[0]->row) > 1 && $contadorRecC3 < count($estado_fin_cli->Domi_xy_[0]->row)) echo '&#013;&#010;';
								}
								echo '</DOMICILIO_XY>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->Situacion_BCRA[0]))
							{							
								echo '<SITUACION_BCRA>&#013;&#010;';
								$contadorRecC4 = 0;
								foreach ($estado_fin_cli->Situacion_BCRA[0]->row as $recDSF6M) 
								{								
									$contadorRecC4++;
									if(!empty($recDSF6M->entidad)) echo '	<ENTIDAD> '.$recDSF6M->entidad.' </ENTIDAD>&#013;&#010;';
									if(!empty($recDSF6M->fecha_sit)) echo '	<FECHA> '.$recDSF6M->fecha_sit.' </FECHA>&#013;&#010;';									
									if(!empty($recDSF6M->situacion)) echo '	<SITUACION> '.$recDSF6M->situacion.' </SITUACION>&#013;&#010;';
									if(!empty($recDSF6M->deuda_total))  echo '	<DEUDA_TOTAL> '.$recDSF6M->deuda_total.' </DEUDA_TOTAL>&#013;&#010;';

									if(count($estado_fin_cli->Situacion_BCRA[0]->row) > 1 && $contadorRecC4 < count($estado_fin_cli->Situacion_BCRA[0]->row)) echo '&#013;&#010;';								
								}
								echo '</SITUACION_BCRA>&#013;&#010;&#013;&#010;';	
							}
							
							if(!empty($estado_fin_cli->FraudesTarjeta_[0]))
							{
								echo '<FRAUDES_TARJETA>&#013;&#010;';
								$contadorRecC5 = 0;
								foreach ($estado_fin_cli->FraudesTarjeta_[0]->row as $recFTs) 
								{							
									$contadorRecC5++;
									if(!empty($recFTs->fraude)) echo '	<FRAUDE> '.$recFTs->fraude.' </FRAUDE>&#013;&#010;';
									
									if(count($estado_fin_cli->FraudesTarjeta_[0]->row) > 1 && $contadorRecC5 < count($estado_fin_cli->FraudesTarjeta_[0]->row)) echo '&#013;&#010;';
								}
								echo '</FRAUDES_TARJETA>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->CapacidadEndeudamiento_[0]))
							{
								echo '<CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;';
								$contadorRecC6 = 0;
								foreach ($estado_fin_cli->CapacidadEndeudamiento_[0]->row as $recCPEC) 
								{							
									$contadorRecC6++;
									if(!empty($recCPEC->capacidad_endeudamiento)) echo '	<CAPACIDAD_ENDEUDAMIENTO> '.$recCPEC->capacidad_endeudamiento.' </CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;';
									
									if(count($estado_fin_cli->CapacidadEndeudamiento_[0]->row) > 1 && $contadorRecC6 < count($estado_fin_cli->CapacidadEndeudamiento_[0]->row)) echo '&#013;&#010;';
								}
								echo '</CAPACIDAD_ENDEUDAMIENTO>&#013;&#010;&#013;&#010;';
							}							
							
							if(!empty($estado_fin_cli->Ooss_personas_[0]))
							{							
								echo '<OBRA_SOCIAL_PERSONAS>&#013;&#010;';
								$contadorRecC7 = 0;
								foreach ($estado_fin_cli->Ooss_personas_[0]->row as $recDSF12M) 
								{								
									$contadorRecC7++;
									if(!empty($recDSF12M->cuil)) echo '	<CUIL> '.$recDSF12M->cuil.' </CUIL>&#013;&#010;';
									if(!empty($recDSF12M->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recDSF12M->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
									if(!empty($recDSF12M->nume_docu)) echo '	<DOCUMENTO> '.$recDSF12M->nume_docu.' </DOCUMENTO>&#013;&#010;';
									if(!empty($recDSF12M->sexo)) echo '	<SEXO> '.$recDSF12M->sexo.' </SEXO>&#013;&#010;';
									if(!empty($recDSF12M->domicilio)) echo '	<DOMICILIO> '.$recDSF12M->domicilio.' </DOMICILIO>&#013;&#010;';
									if(!empty($recDSF12M->localidad)) echo '	<LOCALIDAD> '.$recDSF12M->localidad.' </LOCALIDAD>&#013;&#010;';
									if(!empty($recDSF12M->provincia)) echo '	<PROVINCIA> '.$recDSF12M->provincia.' </PROVINCIA>&#013;&#010;';
									if(!empty($recDSF12M->cod_postal)) echo '	<CODIGO_POSTAL> '.$recDSF12M->cod_postal.' </CODIGO_POSTAL>&#013;&#010;';									

									if(count($estado_fin_cli->Ooss_personas_[0]->row) > 1 && $contadorRecC7 < count($estado_fin_cli->Ooss_personas_[0]->row)) echo '&#013;&#010;';								
								}
								echo '</OBRA_SOCIAL_PERSONAS>&#013;&#010;&#013;&#010;';	
							}
							
							if(!empty($estado_fin_cli->Obra_Social_Rel_[0]))
							{							
								echo '<OBRA_SOCIAL_RELACION>&#013;&#010;';
								$contadorRecC8 = 0;
								foreach ($estado_fin_cli->Obra_Social_Rel_[0]->row as $recDSF24M) 
								{								
									$contadorRecC8++;
									if(!empty($recDSF24M->codiobra)) echo '	<CODIGO> '.$recDSF24M->codiobra.' </CODIGO>&#013;&#010;';
									if(!empty($recDSF24M->descripcion_ooss)) echo '	<DESCRIPCION> '.$recDSF24M->descripcion_ooss.' </DESCRIPCION>&#013;&#010;';
									if(!empty($recDSF24M->sigla_ooss)) echo '	<SIGLA> '.$recDSF24M->sigla_ooss.' </SIGLA>&#013;&#010;';
									if(!empty($recDSF24M->cobertura)) echo '	<COBERTURA> '.$recDSF24M->cobertura.' </COBERTURA>&#013;&#010;';
									if(!empty($recDSF24M->conyugue)) echo '	<CONYUGUE> '.$recDSF24M->conyugue.' </CONYUGUE>&#013;&#010;';
									if(!empty($recDSF24M->hijos)) echo '	<HIJOS> '.$recDSF24M->hijos.' </HIJOS>&#013;&#010;';

									if(count($estado_fin_cli->Obra_Social_Rel_[0]->row) > 1 && $contadorRecC8 < count($estado_fin_cli->Obra_Social_Rel_[0]->row)) echo '&#013;&#010;';								
								}
								echo '</OBRA_SOCIAL_RELACION>&#013;&#010;&#013;&#010;';	
							}
							
							if(!empty($estado_fin_cli->Domicilio_Empresa_trabaja_[0]))
							{
								echo '<DOMICILIO_LABORAL>&#013;&#010;';
								$contadorRecC9 = 0;
								foreach ($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row as $recRDC) 
								{								
									$contadorRecC9++;
									if(!empty($recRDC->direccion)) echo '	<DIRECCION> '.$recRDC->ult_periodo.' </DIRECCION>&#013;&#010;';
									if(!empty($recRDC->localidad)) echo '	<LOCALIDAD> '.$recRDC->alta_trabajo_ultimo.' </LOCALIDAD>&#013;&#010;';
									if(!empty($recRDC->provincia)) echo '	<PROVINCIA> '.$recRDC->cuit.' </PROVINCIA>&#013;&#010;';
									if(!empty($recRDC->cp)) echo '	<CODIGO_POSTAL> '.$recRDC->razon_social.' </CODIGO_POSTAL>&#013;&#010;';

									if(count($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row) > 1 && $contadorRecC9 < count($estado_fin_cli->Domicilio_Empresa_trabaja_[0]->row)) echo '&#013;&#010;';								
								}
								echo '</DOMICILIO LABORAL>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->domicilio_otros_[0]))
							{							
								echo '<OTROS_DOMICILIOS>&#013;&#010;';
								$contadorRecC10 = 0;
								foreach ($estado_fin_cli->domicilio_otros_[0]->row as $recCIAC) 
								{								
									$contadorRecC10++;
									if(!empty($recCIAC->domicilio)) echo '	<DOMICILIO> '.$recCIAC->domicilio.' </DOMICILIO>&#013;&#010;';
									if(!empty($recCIAC->localidad)) echo '	<LOCALIDAD> '.$recCIAC->localidad.' </LOCALIDAD>&#013;&#010;';
									if(!empty($recCIAC->cp)) echo '	<CP> '.$recCIAC->cp.' </CP>&#013;&#010;';
									if(!empty($recCIAC->provincia)) echo '	<PROVINCIA> '.$recCIAC->provincia.' </PROVINCIA>&#013;&#010;';

									if(count($estado_fin_cli->domicilio_otros_[0]->row) > 1 && $contadorRecC10 < count($estado_fin_cli->domicilio_otros_[0]->row)) echo '&#013;&#010;';								
								}
								echo '</OTROS_DOMICILIOS>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->Domicilios_Juicios_[0]))
							{
								echo '<DOMICILIOS_JUICIOS>&#013;&#010;';
								$contadorRecC11 = 0;
								foreach ($estado_fin_cli->Domicilios_Juicios_[0]->row as $recDJD) 
								{							
									$contadorRecC11++;
									if(!empty($recDJD->domicilio)) echo '	<DOMICILIO> '.$recDJD->domicilio.' </DOMICILIO>&#013;&#010;';
									
									if(count($estado_fin_cli->Domicilios_Juicios_[0]->row) > 1 && $contadorRecC11 < count($estado_fin_cli->Domicilios_Juicios_[0]->row)) echo '&#013;&#010;';
								}
								echo '</DOMICILIOS_JUICIOS>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->Padronempresasf_[0]))
							{							
								echo '<PADRON_EMPRESAS>&#013;&#010;';
								$contadorRecC12 = 0;
								foreach ($estado_fin_cli->Padronempresasf_[0]->row as $recPMC) 
								{								
									$contadorRecC12++;
									if(!empty($recPMC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recPMC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
									if(!empty($recPMC->cuit)) echo '	<CUIT> '.$recPMC->cuit.' </CUIT>&#013;&#010;';
									if(!empty($recPMC->domicilio_fiscal)) echo '	<DOMICILIO_FISCAL> '.$recPMC->domicilio_fiscal.' </DOMICILIO_FISCAL>&#013;&#010;';
									if(!empty($recPMC->provincia)) echo '	<PROVINCIA> '.$recPMC->provincia.' </PROVINCIA>&#013;&#010;';
									if(!empty($recPMC->actividad_especifica)) echo '	<ACTIVIDAD_ESPECIFICA> '.$recPMC->actividad_especifica.' </ACTIVIDAD_ESPECIFICA>&#013;&#010;';
									if(!empty($recPMC->categoria_autonomo)) echo '	<CATEGORIA_AUTONOMO> '.$recPMC->categoria_autonomo.' </CATEGORIA_AUTONOMO>&#013;&#010;';

									if(count($estado_fin_cli->Padronempresasf_[0]->row) > 1 && $contadorRecC12 < count($estado_fin_cli->Padronempresasf_[0]->row)) echo '&#013;&#010;';									
								}
								echo '</PADRON_EMPRESAS>&#013;&#010;&#013;&#010;';							
							}
							
							if(!empty($estado_fin_cli->Gran_contrib_[0]))
							{
								echo '<GRANDES_CONTRIBUYENTES>&#013;&#010;';
								$contadorRecC13 = 0;
								foreach ($estado_fin_cli->Gran_contrib_[0]->row as $recGCD) 
								{							
									$contadorRecC13++;
									if(!empty($recGCD->gran_contr)) echo '	<CONTRIBUYENTE> '.$recGCD->gran_contr.' </CONTRIBUYENTE>&#013;&#010;';
									
									if(count($estado_fin_cli->Gran_contrib_[0]->row) > 1 && $contadorRecC13 < count($estado_fin_cli->Gran_contrib_[0]->row)) echo '&#013;&#010;';
								}
								echo '</GRANDES_CONTRIBUYENTES>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->Domialter_[0]))
							{							
								echo '<DOMICILIOS_ALTERNATIVOS>&#013;&#010;';
								$contadorRecC14 = 0;
								foreach ($estado_fin_cli->Domialter_[0]->row as $recDMAC) 
								{								
									$contadorRecC14++;
									if(!empty($recDMAC->ape_nom)) echo '	<APELLIDO_Y_NOMBRE> '.$recDMAC->ape_nom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
									if(!empty($recDMAC->cuit)) echo '	<CUIT> '.$recDMAC->cuit.' </CUIT>&#013;&#010;';
									if(!empty($recDMAC->domicilio_fiscal)) echo '	<DOMICILIO_FISCAL> '.$recDMAC->domicilio_fiscal.' </DOMICILIO_FISCAL>&#013;&#010;';
									if(!empty($recDMAC->provincia)) echo '	<PROVINCIA> '.$recDMAC->provincia.' </PROVINCIA>&#013;&#010;';
									if(!empty($recDMAC->actividad_especifica)) echo '	<ACTIVIDAD_ESPECIFICA> '.$recDMAC->actividad_especifica.' </ACTIVIDAD_ESPECIFICA>&#013;&#010;';

									if(count($estado_fin_cli->Domialter_[0]->row) > 1 && $contadorRecC14 < count($estado_fin_cli->Domialter_[0]->row)) echo '&#013;&#010;';									
								}
								echo '</DOMICILIOS_ALTERNATIVOS>&#013;&#010;&#013;&#010;';							
							}
							
							if(!empty($estado_fin_cli->Nise_[0]))
							{
								echo '<NISE>&#013;&#010;';
								$contadorRecC15 = 0;
								foreach ($estado_fin_cli->Nise_[0]->row as $recINC) 
								{							
									$contadorRecC15++;
									if(!empty($recINC->radio)) echo '	<RADIO> '.$recINC->radio.' </RADIO>&#013;&#010;';
									if(!empty($recINC->nise)) echo '	<NISE> '.$recINC->nise.' </NISE>&#013;&#010;';
									if(!empty($recINC->periodo)) echo '	<PERIODO> '.$recINC->periodo.' </PERIODO>&#013;&#010;';
									
									if(count($estado_fin_cli->Nise_[0]->row) > 1 && $contadorRecC15 < count($estado_fin_cli->Nise_[0]->row)) echo '&#013;&#010;';
								}
								echo '</NISE>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->Const_inscrip_[0]))
							{							
								echo '<CONSTANCIAS_INSCRIPCION>&#013;&#010;';
								$contadorRecC16 = 0;
								foreach ($estado_fin_cli->Const_inscrip_[0]->row as $recCICR) 
								{								
									$contadorRecC16++;
									if(!empty($recCICR->descrip_imp_ganancias)) echo '	<DESCRIPCION_IMP_GANANCIAS> '.$recCICR->descrip_imp_ganancias.' </DESCRIPCION_IMP_GANANCIAS>&#013;&#010;';
									if(!empty($recCICR->descrip_imp_iva)) echo '	<DESCRIPCION_IMP_IVA> '.$recCICR->descrip_imp_iva.' </DESCRIPCION_IMP_IVA>&#013;&#010;';
									if(!empty($recCICR->descrip_monotributo)) echo '	<DESCRIPCION_MONOTRIBUTO> '.$recCICR->descrip_monotributo.' </DESCRIPCION_MONOTRIBUTO>&#013;&#010;';
									if(!empty($recCICR->descrip_integrante_soc)) echo '	<DESCRIPCION_INTEGRANTE_SOC> '.$recCICR->descrip_integrante_soc.' </DESCRIPCION_INTEGRANTE_SOC>&#013;&#010;';
									if(!empty($recCICR->descrip_empleador)) echo '	<DESCRIPCION_EMPLEADOR> '.$recCICR->descrip_empleador.' </DESCRIPCION_EMPLEADOR>&#013;&#010;';
									if(!empty($recCICR->periodo)) echo '	<PERIODO> '.$recCICR->periodo.' </PERIODO>&#013;&#010;';

									if(count($estado_fin_cli->Const_inscrip_[0]->row) > 1 && $contadorRecC16 < count($estado_fin_cli->Const_inscrip_[0]->row)) echo '&#013;&#010;';									
								}
								echo '</CONSTANCIAS_INSCRIPCION>&#013;&#010;&#013;&#010;';							
							}
							
							if(!empty($estado_fin_cli->Nbi_[0]))
							{
								echo '<NBI>&#013;&#010;';
								$contadorRecC17 = 0;
								foreach ($estado_fin_cli->Nbi_[0]->row as $recINBC) 
								{							
									$contadorRecC17++;
									if(!empty($recINBC->radio)) echo '	<RADIO> '.$recINBC->radio.' </RADIO>&#013;&#010;';
									if(!empty($recINBC->nbi)) echo '	<NBI> '.$recINBC->nbi.' </NBI>&#013;&#010;';
									if(!empty($recINBC->periodo)) echo '	<PERIODO> '.$recINBC->periodo.' </PERIODO>&#013;&#010;';
									
									if(count($estado_fin_cli->Nbi_[0]->row) > 1 && $contadorRecC17 < count($estado_fin_cli->Nbi_[0]->row)) echo '&#013;&#010;';
								}
								echo '</NBI>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->Iped_[0]))
							{
								echo '<IPED>&#013;&#010;';
								$contadorRecC18 = 0;
								foreach ($estado_fin_cli->Iped_[0]->row as $recIIPC) 
								{							
									$contadorRecC18++;
									if(!empty($recIIPC->radio)) echo '	<RADIO> '.$recIIPC->radio.' </RADIO>&#013;&#010;';
									if(!empty($recIIPC->iped)) echo '	<IPED> '.$recIIPC->iped.' </IPED>&#013;&#010;';
									if(!empty($recIIPC->periodo)) echo '	<PERIODO> '.$recIIPC->periodo.' </PERIODO>&#013;&#010;';
									
									if(count($estado_fin_cli->Iped_[0]->row) > 1 && $contadorRecC18 < count($estado_fin_cli->Iped_[0]->row)) echo '&#013;&#010;';
								}
								echo '</IPED>&#013;&#010;&#013;&#010;';
							}							

							if(!empty($estado_fin_cli->Ib_[0]))
							{
								echo '<IB>&#013;&#010;';
								$contadorRecC19 = 0;
								foreach ($estado_fin_cli->Ib_[0]->row as $recIIBC) 
								{							
									$contadorRecC19++;
									if(!empty($recIIBC->radio)) echo '	<RADIO> '.$recIIBC->radio.' </RADIO>&#013;&#010;';
									if(!empty($recIIBC->ib)) echo '	<IB> '.$recIIBC->ib.' </IB>&#013;&#010;';
									if(!empty($recIIBC->periodo)) echo '	<PERIODO> '.$recIIBC->periodo.' </PERIODO>&#013;&#010;';
									
									if(count($estado_fin_cli->Ib_[0]->row) > 1 && $contadorRecC19 < count($estado_fin_cli->Ib_[0]->row)) echo '&#013;&#010;';
								}
								echo '</IB>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->Rel_Dependencia_Trabajador_[0]))
							{
								echo '<RELEACION_DEPENDENCIA_TRABAJADOR>&#013;&#010;';
								$contadorRecC20 = 0;
								foreach ($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row as $recRDTC) 
								{							
									$contadorRecC20++;
									if(!empty($recRDTC->cuil)) echo '	<CUIL> '.$recRDTC->cuil.' </CUIL>&#013;&#010;';
									if(!empty($recRDTC->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recRDTC->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
									if(!empty($recRDTC->domicilio)) echo '	<DOMICILIO> '.$recRDTC->domicilio.' </DOMICILIO>&#013;&#010;';
									if(!empty($recRDTC->localidad)) echo '	<LOCALIDAD> '.$recRDTC->localidad.' </LOCALIDAD>&#013;&#010;';
									if(!empty($recRDTC->cp)) echo '	<CODIGO_POSTAL> '.$recRDTC->cp.' </CODIGO_POSTAL>&#013;&#010;';
									if(!empty($recRDTC->provincia)) echo '	<PROVINCIA> '.$recRDTC->provincia.' </PROVINCIA>&#013;&#010;';
									if(!empty($recRDTC->fecha_nac)) echo '	<FECHA_NACIMIENTO> '.$recRDTC->fecha_nac.' </FECHA_NACIMIENTO>&#013;&#010;';
									if(!empty($recRDTC->sexo)) echo '	<SEXO> '.$recRDTC->sexo.' </SEXO>&#013;&#010;';
									if(!empty($recRDTC->doc)) echo '	<DOCUMENTO> '.$recRDTC->doc.' </DOCUMENTO>&#013;&#010;';
									
									if(count($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row) > 1 && $contadorRecC20 < count($estado_fin_cli->Rel_Dependencia_Trabajador_[0]->row)) echo '&#013;&#010;';
								}
								echo '</RELEACION_DEPENDENCIA_TRABAJADOR>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->Rel_Dependencia_Empleador_[0]))
							{
								echo '<RELEACION_DEPENDENCIA_EMPLEADOR>&#013;&#010;';
								$contadorRecC21 = 0;
								foreach ($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row as $recRDEC) 
								{							
									$contadorRecC21++;
									if(!empty($recRDEC->cuit)) echo '	<CUIT> '.$recRDEC->cuit.' </CUIT>&#013;&#010;';
									if(!empty($recRDEC->razon)) echo '	<RAZON_SOCIAL> '.$recRDEC->razon.' </RAZON_SOCIAL>&#013;&#010;';
									if(!empty($recRDEC->domicilio)) echo '	<DOMICILIO> '.$recRDEC->domicilio.' </DOMICILIO>&#013;&#010;';
									if(!empty($recRDEC->localidad)) echo '	<LOCALIDAD> '.$recRDEC->localidad.' </LOCALIDAD>&#013;&#010;';
									if(!empty($recRDEC->cp)) echo '	<CODIGO_POSTAL> '.$recRDEC->cp.' </CODIGO_POSTAL>&#013;&#010;';
									if(!empty($recRDEC->provincia)) echo '	<PROVINCIA> '.$recRDEC->provincia.' </PROVINCIA>&#013;&#010;';
									
									if(count($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row) > 1 && $contadorRecC21 < count($estado_fin_cli->Rel_Dependencia_Empleador_[0]->row)) echo '&#013;&#010;';
								}
								echo '</RELEACION_DEPENDENCIA_EMPLEADOR>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->Historia_[0]))
							{
								echo '<HISTORIA_EMPRESAS>&#013;&#010;';
								$contadorRecC22 = 0;
								foreach ($estado_fin_cli->Historia_[0]->row as $recHEC) 
								{							
									$contadorRecC22++;
									if(!empty($recHEC->cuit)) echo '	<CUIT> '.$recHEC->cuit.' </CUIT>&#013;&#010;';
									if(!empty($recHEC->razon)) echo '	<RAZON_SOCIAL> '.$recHEC->razon.' </RAZON_SOCIAL>&#013;&#010;';
									if(!empty($recHEC->desde)) echo '	<DESDE> '.$recHEC->desde.' </DESDE>&#013;&#010;';
									if(!empty($recHEC->hasta)) echo '	<HASTA> '.$recHEC->hasta.' </HASTA>&#013;&#010;';
									
									if(count($estado_fin_cli->Historia_[0]->row) > 1 && $contadorRecC22 < count($estado_fin_cli->Historia_[0]->row)) echo '&#013;&#010;';
								}
								echo '</HISTORIA_EMPRESAS>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->Sysem_[0]))
							{
								echo '<SYSEM>&#013;&#010;';
								$contadorRecC23 = 0;
								foreach ($estado_fin_cli->Sysem_[0]->row as $recSYC) 
								{							
									$contadorRecC23++;
									if(!empty($recSYC->cant_emp)) echo '	<CANTIDAD_EMPLEADOS> '.$recSYC->cant_emp.' </CANTIDAD_EMPLEADOS>&#013;&#010;';
									if(!empty($recSYC->cuit)) echo '	<CUIT> '.$recSYC->cuit.' </CUIT>&#013;&#010;';
									if(!empty($recSYC->razon)) echo '	<RAZON_SOCIAL> '.$recSYC->razon.' </RAZON_SOCIAL>&#013;&#010;';
									if(!empty($recSYC->domicilio)) echo '	<DOMICILIO> '.$recSYC->domicilio.' </DOMICILIO>&#013;&#010;';
									if(!empty($recSYC->localidad)) echo '	<LOCALIDAD> '.$recSYC->localidad.' </LOCALIDAD>&#013;&#010;';
									if(!empty($recSYC->cp)) echo '	<CODIGO_POSTAL> '.$recSYC->cp.' </CODIGO_POSTAL>&#013;&#010;';
									if(!empty($recSYC->provincia)) echo '	<PROVINCIA> '.$recSYC->provincia.' </PROVINCIA>&#013;&#010;';
									
									if(count($estado_fin_cli->Sysem_[0]->row) > 1 && $contadorRecC23 < count($estado_fin_cli->Sysem_[0]->row)) echo '&#013;&#010;';
								}
								echo '</SYSEM>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->Jyj_mensual_[0]))
							{
								echo '<SYSEM>&#013;&#010;';
								$contadorRecC24 = 0;
								foreach ($estado_fin_cli->Jyj_mensual_[0]->row as $recJYJC) 
								{							
									$contadorRecC24++;
									if(!empty($recJYJC->cuil)) echo '	<CUIL> '.$recJYJC->cuil.' </CUIL>&#013;&#010;';
									if(!empty($recJYJC->apenom)) echo '	<APELLIDO_Y_NOMBRE> '.$recJYJC->apenom.' </APELLIDO_Y_NOMBRE>&#013;&#010;';
									if(!empty($recJYJC->rango_socio_econom)) echo '	<RANGO_SOCIO_ECONOMICO> '.$recJYJC->rango_socio_econom.' </RANGO_SOCIO_ECONOMICO>&#013;&#010;';
									
									if(count($estado_fin_cli->Jyj_mensual_[0]->row) > 1 && $contadorRecC24 < count($estado_fin_cli->Jyj_mensual_[0]->row)) echo '&#013;&#010;';
								}
								echo '</SYSEM>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->Situacionlaboral_[0]))
							{
								echo '<SITUACION_LABORAL>&#013;&#010;';
								$contadorRecC25 = 0;
								foreach ($estado_fin_cli->Situacionlaboral_[0]->row as $recSLC) 
								{							
									$contadorRecC25++;
									if(!empty($recSLC->situacionlaboral)) echo '	<ESTADO_LABORAL> '.$recSLC->situacionlaboral.' </ESTADO_LABORAL>&#013;&#010;';
									
									if(count($estado_fin_cli->Situacionlaboral_[0]->row) > 1 && $contadorRecC25 < count($estado_fin_cli->Situacionlaboral_[0]->row)) echo '&#013;&#010;';
								}
								echo '</SITUACION_LABORAL>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->Producto_financieros_[0]))
							{
								echo '<PRODUCTO_FINANCIEROS>&#013;&#010;';
								$contadorRecC26 = 0;
								foreach ($estado_fin_cli->Producto_financieros_[0]->row as $recPFC) 
								{							
									$contadorRecC26++;
									if(!empty($recPFC->producto_financieros)) echo '	<PRODUCTO> '.$recPFC->producto_financieros.' </PRODUCTO>&#013;&#010;';
									
									if(count($estado_fin_cli->Producto_financieros_[0]->row) > 1 && $contadorRecC26 < count($estado_fin_cli->Producto_financieros_[0]->row)) echo '&#013;&#010;';
								}
								echo '</PRODUCTO_FINANCIEROS>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->Bancarizado_[0]))
							{
								echo '<BANCARIZADO>&#013;&#010;';
								$contadorRecC27 = 0;
								foreach ($estado_fin_cli->Bancarizado_[0]->row as $recEBC) 
								{							
									$contadorRecC27++;
									if(!empty($recEBC->bancarizado)) echo '	<ESTADO> '.$recEBC->bancarizado.' </ESTADO>&#013;&#010;';
									
									if(count($estado_fin_cli->Bancarizado_[0]->row) > 1 && $contadorRecC27 < count($estado_fin_cli->Bancarizado_[0]->row)) echo '&#013;&#010;';
								}
								echo '</BANCARIZADO>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->FechaMaxSistFin_[0]))
							{
								echo '<FECHA_MAXIMA_SISTEMA_FINANCIERO>&#013;&#010;';
								$contadorRecC28 = 0;
								foreach ($estado_fin_cli->FechaMaxSistFin_[0]->row as $recFMSF) 
								{							
									$contadorRecC28++;
									if(!empty($recFMSF->fecha)) echo '	<FECHA> '.$recFMSF->fecha.' </FECHA>&#013;&#010;';
									
									if(count($estado_fin_cli->FechaMaxSistFin_[0]->row) > 1 && $contadorRecC28 < count($estado_fin_cli->FechaMaxSistFin_[0]->row)) echo '&#013;&#010;';
								}
								echo '</FECHA_MAXIMA_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
							}	

							if(!empty($estado_fin_cli->SistFinEvolucionDeuda_[0]))
							{
								echo '<EVOLUCION_DEUDA_SISTEMA_FINANCIERO>&#013;&#010;';
								$contadorRecC29 = 0;
								foreach ($estado_fin_cli->SistFinEvolucionDeuda_[0]->row as $recEDSF) 
								{							
									$contadorRecC29++;
									if(!empty($recEDSF->entidad)) echo '	<ENTIDAD> '.$recEDSF->entidad.' </ENTIDAD>&#013;&#010;';
									if(!empty($recEDSF->m1)) echo '	<MES1> '.$recEDSF->m1.' </MES1>&#013;&#010;';
									if(!empty($recEDSF->m2)) echo '	<MES2> '.$recEDSF->m2.' </MES2>&#013;&#010;';
									if(!empty($recEDSF->m3)) echo '	<MES3> '.$recEDSF->m3.' </MES3>&#013;&#010;';
									if(!empty($recEDSF->m4)) echo '	<MES4> '.$recEDSF->m4.' </MES4>&#013;&#010;';
									if(!empty($recEDSF->m5)) echo '	<MES5> '.$recEDSF->m5.' </MES5>&#013;&#010;';
									if(!empty($recEDSF->m6)) echo '	<MES6> '.$recEDSF->m6.' </MES6>&#013;&#010;';
									if(!empty($recEDSF->m7)) echo '	<MES7> '.$recEDSF->m7.' </MES7>&#013;&#010;';
									if(!empty($recEDSF->m8)) echo '	<MES8> '.$recEDSF->m8.' </MES8>&#013;&#010;';
									if(!empty($recEDSF->m9)) echo '	<MES9> '.$recEDSF->m9.' </MES9>&#013;&#010;';
									if(!empty($recEDSF->m10)) echo '	<MES10> '.$recEDSF->m10.' </MES10>&#013;&#010;';
									if(!empty($recEDSF->m11)) echo '	<MES11> '.$recEDSF->m11.' </MES11>&#013;&#010;';
									if(!empty($recEDSF->m12)) echo '	<MES12> '.$recEDSF->m12.' </MES12>&#013;&#010;';
									if(!empty($recEDSF->m13)) echo '	<MES13> '.$recEDSF->m13.' </MES13>&#013;&#010;';
									if(!empty($recEDSF->m14)) echo '	<MES14> '.$recEDSF->m14.' </MES14>&#013;&#010;';
									if(!empty($recEDSF->m15)) echo '	<MES15> '.$recEDSF->m15.' </MES15>&#013;&#010;';
									if(!empty($recEDSF->m16)) echo '	<MES16> '.$recEDSF->m16.' </MES16>&#013;&#010;';
									if(!empty($recEDSF->m17)) echo '	<MES17> '.$recEDSF->m17.' </MES17>&#013;&#010;';
									if(!empty($recEDSF->m18)) echo '	<MES18> '.$recEDSF->m18.' </MES18>&#013;&#010;';
									if(!empty($recEDSF->m19)) echo '	<MES19> '.$recEDSF->m19.' </MES19>&#013;&#010;';
									if(!empty($recEDSF->m20)) echo '	<MES20> '.$recEDSF->m20.' </MES20>&#013;&#010;';
									if(!empty($recEDSF->m21)) echo '	<MES21> '.$recEDSF->m21.' </MES21>&#013;&#010;';
									if(!empty($recEDSF->m22)) echo '	<MES22> '.$recEDSF->m22.' </MES22>&#013;&#010;';
									if(!empty($recEDSF->m23)) echo '	<MES23> '.$recEDSF->m23.' </MES23>&#013;&#010;';
									if(!empty($recEDSF->m24)) echo '	<MES24> '.$recEDSF->m24.' </MES24>&#013;&#010;';									
									if(!empty($recEDSF->deuda_actual)) echo '	<DEUDA_ACTUAL> '.$recEDSF->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
									if(!empty($recEDSF->monto_maximo)) echo '	<MONTO_MAXIMO> '.$recEDSF->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
									if(!empty($recEDSF->bco)) echo '	<BANCO> '.$recEDSF->bco.' </BANCO>&#013;&#010;';
									
									if(count($estado_fin_cli->SistFinEvolucionDeuda_[0]->row) > 1 && $contadorRecC29 < count($estado_fin_cli->SistFinEvolucionDeuda_[0]->row)) echo '&#013;&#010;';
								}
								echo '</EVOLUCION_DEUDA_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]))
							{
								echo '<EVOLUCION_DEUDA_PROPIO_SISTEMA_FINANCIERO>&#013;&#010;';
								$contadorRecC30 = 0;
								foreach ($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row as $recEDPSF) 
								{							
									$contadorRecC30++;
									if(!empty($recEDPSF->entidad)) echo '	<ENTIDAD> '.$recEDPSF->entidad.' </ENTIDAD>&#013;&#010;';
									if(!empty($recEDPSF->m1)) echo '	<MES1> '.$recEDPSF->m1.' </MES1>&#013;&#010;';
									if(!empty($recEDPSF->m2)) echo '	<MES2> '.$recEDPSF->m2.' </MES2>&#013;&#010;';
									if(!empty($recEDPSF->m3)) echo '	<MES3> '.$recEDPSF->m3.' </MES3>&#013;&#010;';
									if(!empty($recEDPSF->m4)) echo '	<MES4> '.$recEDPSF->m4.' </MES4>&#013;&#010;';
									if(!empty($recEDPSF->m5)) echo '	<MES5> '.$recEDPSF->m5.' </MES5>&#013;&#010;';
									if(!empty($recEDPSF->m6)) echo '	<MES6> '.$recEDPSF->m6.' </MES6>&#013;&#010;';
									if(!empty($recEDPSF->m7)) echo '	<MES7> '.$recEDPSF->m7.' </MES7>&#013;&#010;';
									if(!empty($recEDPSF->m8)) echo '	<MES8> '.$recEDPSF->m8.' </MES8>&#013;&#010;';
									if(!empty($recEDPSF->m9)) echo '	<MES9> '.$recEDPSF->m9.' </MES9>&#013;&#010;';
									if(!empty($recEDPSF->m10)) echo '	<MES10> '.$recEDPSF->m10.' </MES10>&#013;&#010;';
									if(!empty($recEDPSF->m11)) echo '	<MES11> '.$recEDPSF->m11.' </MES11>&#013;&#010;';
									if(!empty($recEDPSF->m12)) echo '	<MES12> '.$recEDPSF->m12.' </MES12>&#013;&#010;';
									if(!empty($recEDPSF->m13)) echo '	<MES13> '.$recEDPSF->m13.' </MES13>&#013;&#010;';
									if(!empty($recEDPSF->m14)) echo '	<MES14> '.$recEDPSF->m14.' </MES14>&#013;&#010;';
									if(!empty($recEDPSF->m15)) echo '	<MES15> '.$recEDPSF->m15.' </MES15>&#013;&#010;';
									if(!empty($recEDPSF->m16)) echo '	<MES16> '.$recEDPSF->m16.' </MES16>&#013;&#010;';
									if(!empty($recEDPSF->m17)) echo '	<MES17> '.$recEDPSF->m17.' </MES17>&#013;&#010;';
									if(!empty($recEDPSF->m18)) echo '	<MES18> '.$recEDPSF->m18.' </MES18>&#013;&#010;';
									if(!empty($recEDPSF->m19)) echo '	<MES19> '.$recEDPSF->m19.' </MES19>&#013;&#010;';
									if(!empty($recEDPSF->m20)) echo '	<MES20> '.$recEDPSF->m20.' </MES20>&#013;&#010;';
									if(!empty($recEDPSF->m21)) echo '	<MES21> '.$recEDPSF->m21.' </MES21>&#013;&#010;';
									if(!empty($recEDPSF->m22)) echo '	<MES22> '.$recEDPSF->m22.' </MES22>&#013;&#010;';
									if(!empty($recEDPSF->m23)) echo '	<MES23> '.$recEDPSF->m23.' </MES23>&#013;&#010;';
									if(!empty($recEDPSF->m24)) echo '	<MES24> '.$recEDPSF->m24.' </MES24>&#013;&#010;';									
									if(!empty($recEDPSF->deuda_actual)) echo '	<DEUDA_ACTUAL> '.$recEDPSF->deuda_actual.' </DEUDA_ACTUAL>&#013;&#010;';
									if(!empty($recEDPSF->monto_maximo)) echo '	<MONTO_MAXIMO> '.$recEDPSF->monto_maximo.' </MONTO_MAXIMO>&#013;&#010;';
									if(!empty($recEDPSF->bco)) echo '	<BANCO> '.$recEDPSF->bco.' </BANCO>&#013;&#010;';
									
									if(count($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row) > 1 && $contadorRecC30 < count($estado_fin_cli->SistFinEvolucionDeudaPropio_[0]->row)) echo '&#013;&#010;';
								}
								echo '</EVOLUCION_DEUDA_PROPIO_SISTEMA_FINANCIERO>&#013;&#010;&#013;&#010;';
							}							
							
							if(!empty($estado_fin_cli->Bcra_Ultimas_Situaciones[0]))
							{
								echo '<BANCO_CENTRAL_ULTIMAS_SITUACIONES>&#013;&#010;';
								$contadorRecC31 = 0;
								foreach ($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row as $recBCUS) 
								{							
									$contadorRecC31++;
									if(!empty($recBCUS->entidad)) echo '	<ENTIDAD> '.$recBCUS->entidad.' </ENTIDAD>&#013;&#010;';
									if(!empty($recBCUS->periodo)) echo '	<PERIODO> '.$recBCUS->periodo.' </PERIODO>&#013;&#010;';
									if(!empty($recBCUS->situacion)) echo '	<SITUACION> '.$recBCUS->situacion.' </SITUACION>&#013;&#010;';
									if(!empty($recBCUS->monto)) echo '	<MONTO> '.$recBCUS->monto.' </MONTO>&#013;&#010;';
									
									if(count($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row) > 1 && $contadorRecC31 < count($estado_fin_cli->Bcra_Ultimas_Situaciones[0]->row)) echo '&#013;&#010;';
								}
								echo '</BANCO_CENTRAL_ULTIMAS_SITUACIONES>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->Bcra_Situaciones_Vigente[0]))
							{
								echo '<BANCO_CENTRAL_SITUACIONES_VIGENTES>&#013;&#010;';
								$contadorRecC32 = 0;
								foreach ($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row as $recBCSV) 
								{							
									$contadorRecC32++;
									if(!empty($recBCSV->entidad)) echo '	<ENTIDAD> '.$recBCSV->entidad.' </ENTIDAD>&#013;&#010;';
									if(!empty($recBCSV->periodos)) echo '	<PERIODOS> '.$recBCSV->periodos.' </PERIODOS>&#013;&#010;';
									if(!empty($recBCSV->situacion)) echo '	<SITUACION> '.$recBCSV->situacion.' </SITUACION>&#013;&#010;';
									if(!empty($recBCSV->monto)) echo '	<MONTO> '.$recBCSV->monto.' </MONTO>&#013;&#010;';
									
									if(count($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row) > 1 && $contadorRecC32 < count($estado_fin_cli->Bcra_Situaciones_Vigente[0]->row)) echo '&#013;&#010;';
								}
								echo '</BANCO_CENTRAL_SITUACIONES_VIGENTES>&#013;&#010;&#013;&#010;';
							}							
							
							if(!empty($estado_fin_cli->Tipo_Actividad[0]))
							{							
								echo '<TIPO_ACTIVIDAD>&#013;&#010;';
								$contadorRecC33 = 0;
								foreach ($estado_fin_cli->Tipo_Actividad[0]->row as $recTAC) 
								{								
									$contadorRecC33++;
									echo '	<TIPO_ACTIVIDAD> '.$recTAC->tipo_actividad.' </TIPO_ACTIVIDAD>&#013;&#010;';

									if(count($estado_fin_cli->Tipo_Actividad[0]->row) > 1 && $contadorRecC33 < count($estado_fin_cli->Tipo_Actividad[0]->row)) echo '&#013;&#010;';								
								}
								echo '</TIPO_ACTIVIDAD>&#013;&#010;&#013;&#010;';	
							}								
					
							if(!empty($estado_fin_cli->Consultas_Individual_[0]))
							{
								echo '<CONSULTAS_INDIVIDUALES>&#013;&#010;';
								$contadorRecC34 = 0;
								foreach ($estado_fin_cli->Consultas_Individual_[0]->row as $recCINDC) 
								{							
									$contadorRecC34++;
									if(!empty($recCINDC->dia)) echo '	<DIA> '.$recCINDC->dia.' </DIA>&#013;&#010;';
									if(!empty($recCINDC->semana)) echo '	<SEMANA> '.$recCINDC->semana.' </SEMANA>&#013;&#010;';
									if(!empty($recCINDC->mes)) echo '	<MES> '.$recCINDC->mes.' </MES>&#013;&#010;';
									if(!empty($recCINDC->m24)) echo '	<M24> '.$recCINDC->m24.' </M24>&#013;&#010;';
									
									if(count($estado_fin_cli->Consultas_Individual_[0]->row) > 1 && $contadorRecC34 < count($estado_fin_cli->Consultas_Individual_[0]->row)) echo '&#013;&#010;';
								}
								echo '</CONSULTAS_INDIVIDUALES>&#013;&#010;&#013;&#010;';
							}

							if(!empty($estado_fin_cli->Consultas_Grupo_[0]))
							{
								echo '<CONSULTAS_GRUPO>&#013;&#010;';
								$contadorRecC35 = 0;
								foreach ($estado_fin_cli->Consultas_Grupo_[0]->row as $recCGRUC) 
								{							
									$contadorRecC35++;
									if(!empty($recCGRUC->dia)) echo '	<DIA> '.$recCGRUC->dia.' </DIA>&#013;&#010;';
									if(!empty($recCGRUC->semana)) echo '	<SEMANA> '.$recCGRUC->semana.' </SEMANA>&#013;&#010;';
									if(!empty($recCGRUC->mes)) echo '	<MES> '.$recCGRUC->mes.' </MES>&#013;&#010;';
									if(!empty($recCGRUC->m24)) echo '	<M24> '.$recCGRUC->m24.' </M24>&#013;&#010;';
									
									if(count($estado_fin_cli->Consultas_Grupo_[0]->row) > 1 && $contadorRecC35 < count($estado_fin_cli->Consultas_Grupo_[0]->row)) echo '&#013;&#010;';
								}
								echo '</CONSULTAS_GRUPO>&#013;&#010;&#013;&#010;';
							}	

							if(!empty($estado_fin_cli->CONSULTADO_POR_[0]))
							{
								echo '<CONSULTADO_POR>&#013;&#010;';
								$contadorRecC36 = 0;
								foreach ($estado_fin_cli->CONSULTADO_POR_[0]->row as $recCCONP) 
								{							
									$contadorRecC36++;
									if(!empty($recCCONP->fecha)) echo '	<FECHA> '.$recCCONP->fecha.' </FECHA>&#013;&#010;';
									if(!empty($recCCONP->consultante)) echo '	<CONSULTANTE> '.$recCCONP->consultante.' </CONSULTANTE>&#013;&#010;';
									
									if(count($estado_fin_cli->CONSULTADO_POR_[0]->row) > 1 && $contadorRecC36 < count($estado_fin_cli->CONSULTADO_POR_[0]->row)) echo '&#013;&#010;';
								}
								echo '</CONSULTADO_POR>&#013;&#010;&#013;&#010;';
							}							
					
							if(!empty($estado_fin_cli->Independientes_[0]))
							{
								echo '<INDEPENDIENTES>&#013;&#010;';
								$contadorRecC37 = 0;
								foreach ($estado_fin_cli->Independientes_[0]->row as $recCONIND) 
								{							
									$contadorRecC37++;
									if(!empty($recCONIND->inscripto)) echo '	<INSCRIPTO> '.$recCONIND->inscripto.' </INSCRIPTO>&#013;&#010;';
									if(!empty($recCONIND->categoria)) echo '	<CATEGORIA> '.$recCONIND->categoria.' </CATEGORIA>&#013;&#010;';
									if(!empty($recCONIND->cuit)) echo '	<CUIT> '.$recCONIND->cuit.' </CUIT>&#013;&#010;';
									if(!empty($recCONIND->documento)) echo '	<DOCUMENTO> '.$recCONIND->documento.' </DOCUMENTO>&#013;&#010;';
									if(!empty($recCONIND->nombre)) echo '	<NOMBRE> '.$recCONIND->nombre.' </NOMBRE>&#013;&#010;';
									if(!empty($recCONIND->domicilio)) echo '	<DOMICILIO> '.$recCONIND->domicilio.' </DOMICILIO>&#013;&#010;';
									if(!empty($recCONIND->cod_postal)) echo '	<CODIGO_POSTAL> '.$recCONIND->cod_postal.' </CODIGO_POSTAL>&#013;&#010;';
									if(!empty($recCONIND->localidad)) echo '	<LOCALIDAD> '.$recCONIND->localidad.' </LOCALIDAD>&#013;&#010;';
									if(!empty($recCONIND->provincia)) echo '	<PROVINCIA> '.$recCONIND->provincia.' </PROVINCIA>&#013;&#010;';
									if(!empty($recCONIND->fecha_nac)) echo '	<FECHA_NACIMIENTO> '.$recCONIND->fecha_nac.' </FECHA_NACIMIENTO>&#013;&#010;';
									if(!empty($recCONIND->entidad)) echo '	<ENTIDAD> '.$recCONIND->entidad.' </ENTIDAD>&#013;&#010;';
									if(!empty($recCONIND->actividad)) echo '	<ACTIVIDAD> '.$recCONIND->actividad.' </ACTIVIDAD>&#013;&#010;';
									if(!empty($recCONIND->periodo)) echo '	<PERIODO> '.$recCONIND->periodo.' </PERIODO>&#013;&#010;';
									if(!empty($recCONIND->monto)) echo '	<MONTO> '.$recCONIND->monto.' </MONTO>&#013;&#010;';									
									
									if(count($estado_fin_cli->Independientes_[0]->row) > 1 && $contadorRecC37 < count($estado_fin_cli->Independientes_[0]->row)) echo '&#013;&#010;';
								}
								echo '</INDEPENDIENTES>&#013;&#010;&#013;&#010;';
							}
							
							if(!empty($estado_fin_cli->Indep_apo_[0]))
							{
								echo '<APORTES_INDEPENDIENTE>&#013;&#010;';
								$contadorRecC38 = 0;
								foreach ($estado_fin_cli->Indep_apo_[0]->row as $recAPCI) 
								{							
									$contadorRecC38++;
									if(!empty($recAPCI->cuit)) echo '	<CUIT> '.$recAPCI->cuit.' </CUIT>&#013;&#010;';
									if(!empty($recAPCI->periodo)) echo '	<PERIODO> '.$recAPCI->periodo.' </PERIODO>&#013;&#010;';
									if(!empty($recAPCI->imp_pago)) echo '	<IMPORTE> '.$recAPCI->imp_pago.' </IMPORTE>&#013;&#010;';
									if(!empty($recAPCI->impuesto)) echo '	<IMPUESTO> '.$recAPCI->impuesto.' </IMPUESTO>&#013;&#010;';
									
									if(count($estado_fin_cli->Indep_apo_[0]->row) > 1 && $contadorRecC38 < count($estado_fin_cli->Indep_apo_[0]->row)) echo '&#013;&#010;';
								}
								echo '</APORTES_INDEPENDIENTE>&#013;&#010;&#013;&#010;';
							}							
							
							if(!empty($estado_fin_cli->Propiedades_[0]))
							{
								echo '<PROPIEDADES>&#013;&#010;';
								$contadorRecC39 = 0;
								foreach ($estado_fin_cli->Propiedades_[0]->row as $recIPROPC) 
								{							
									$contadorRecC39++;
									if(!empty($recIPROPC->titular)) echo '	<TITULAR> '.$recIPROPC->titular.' </TITULAR>&#013;&#010;';
									if(!empty($recIPROPC->folio)) echo '	<FOLIO> '.$recIPROPC->folio.' </FOLIO>&#013;&#010;';
									if(!empty($recIPROPC->ano_inscripcion)) echo '	<AÑO_INSCRIPCION> '.$recIPROPC->ano_inscripcion.' </AÑO_INSCRIPCION>&#013;&#010;';
									if(!empty($recIPROPC->ph)) echo '	<PH> '.$recIPROPC->ph.' </PH>&#013;&#010;';
									if(!empty($recIPROPC->condom)) echo '	<CONDOM> '.$recIPROPC->condom.' </CONDOM>&#013;&#010;';
									if(!empty($recIPROPC->codigo_departamento)) echo '	<CODIGO_DEPARTAMENTO> '.$recIPROPC->codigo_departamento.' </CODIGO_DEPARTAMENTO>&#013;&#010;';
									if(!empty($recIPROPC->pedania)) echo '	<PEDANIA> '.$recIPROPC->pedania.' </PEDANIA>&#013;&#010;';
									if(!empty($recIPROPC->pueblo)) echo '	<PUEBLO> '.$recIPROPC->pueblo.' </PUEBLO>&#013;&#010;';
									if(!empty($recIPROPC->circuncripcion)) echo '	<CIRCUNCRIPCION> '.$recIPROPC->circuncripcion.' </CIRCUNCRIPCION>&#013;&#010;';
									if(!empty($recIPROPC->seccion)) echo '	<SECCION> '.$recIPROPC->seccion.' </SECCION>&#013;&#010;';
									if(!empty($recIPROPC->manzana)) echo '	<MANZANA> '.$recIPROPC->manzana.' </MANZANA>&#013;&#010;';
									if(!empty($recIPROPC->parcela)) echo '	<PARCELA> '.$recIPROPC->parcela.' </PARCELA>&#013;&#010;';
									if(!empty($recIPROPC->escrituro)) echo '	<ESCRITURO> '.$recIPROPC->escrituro.' </ESCRITURO>&#013;&#010;';
									
									if(count($estado_fin_cli->Propiedades_[0]->row) > 1 && $contadorRecC39 < count($estado_fin_cli->Propiedades_[0]->row)) echo '&#013;&#010;';
								}
								echo '</PROPIEDADES>&#013;&#010;&#013;&#010;';
							}
							echo '						</textarea>';
							echo '					</div>';		
							echo '				</div>';						
						}
						echo '				<div class="form-group form-inline">';
						echo '					<div id="img_loader_13"></div>';		
						echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarEFCM" id="btnCancelarEFCM" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogvalidacionestadocrediticioclientem\').dialog(\'close\');" style="margin-left:10px;" />';
						if($estado_activa_supervisor == 1) echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarEFCM" id="btnValidarEFCM" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarAutorizacionSupervisorEstadoFinancieroClienteM(document.getElementById(\'formulariocefcm\'),'.$motivo.');"/>';										
						else echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarEFCM" id="btnValidarEFCM" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarSinSupervisorEstadoFinancieroClienteM('.$motivo.');"/>';
						echo '				</div>';				
						echo '			</form>';
						echo '		</div>';
						echo '	</div>';
						echo '</div>';
					}
				}
				else
				{
					echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
					return;
				}
			}
			else
			{
				echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
				return;
			}
		}
		else
		{
			echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']);
			return;			
		}
			
		return;
?>