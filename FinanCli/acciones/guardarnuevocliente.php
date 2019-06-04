<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');return;}
		if (!verificar_permisos_usuario()){header('Location:../sinautorizacion.php?activauto=1');return;}

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
		
		$tipoDocumentoTitular=htmlspecialchars($_POST["tipoDocumentoTitular"], ENT_QUOTES, 'UTF-8');
		$documentoTitular=htmlspecialchars($_POST["documentoTitular"], ENT_QUOTES, 'UTF-8');
		$tipoDocumento=htmlspecialchars($_POST["tipoDocumento"], ENT_QUOTES, 'UTF-8');
		$documento=htmlspecialchars($_POST["documento"], ENT_QUOTES, 'UTF-8');
		$nombre=htmlspecialchars($_POST["nombre"], ENT_QUOTES, 'UTF-8');
		$apellido=htmlspecialchars($_POST["apellido"], ENT_QUOTES, 'UTF-8');
		$fechaNacimiento=htmlspecialchars($_POST["fechaNacimiento"], ENT_QUOTES, 'UTF-8');
		$cuitCuil=htmlspecialchars($_POST["cuitCuil"], ENT_QUOTES, 'UTF-8');
		$email=htmlspecialchars($_POST["email"], ENT_QUOTES, 'UTF-8');
		$montoMaximo=htmlspecialchars($_POST["montoMaximo"], ENT_QUOTES, 'UTF-8');		
		$perfilCredito=htmlspecialchars($_POST["perfilCredito"], ENT_QUOTES, 'UTF-8');
		$observaciones=htmlspecialchars($_POST["observaciones"], ENT_QUOTES, 'UTF-8');
		$genero=htmlspecialchars($_POST["genero"], ENT_QUOTES, 'UTF-8');
		$tokenA=htmlspecialchars($_POST["tokenA"], ENT_QUOTES, 'UTF-8');
		$tokenVC=htmlspecialchars($_POST["tokenVC"], ENT_QUOTES, 'UTF-8');
		$tokenVECC=htmlspecialchars($_POST["tokenVECC"], ENT_QUOTES, 'UTF-8');
		
		$email = !empty($email) ? "$email" : "---";
		$observaciones = !empty($observaciones) ? "$observaciones" : "---";
		
		$calle=htmlspecialchars($_POST["calle"], ENT_QUOTES, 'UTF-8');
		$nroCalle=htmlspecialchars($_POST["nroCalle"], ENT_QUOTES, 'UTF-8');
		$provincia=htmlspecialchars($_POST["provincia"], ENT_QUOTES, 'UTF-8');
		$localidad=htmlspecialchars($_POST["localidad"], ENT_QUOTES, 'UTF-8');
		$departamento=htmlspecialchars($_POST["departamento"], ENT_QUOTES, 'UTF-8');
		$piso=htmlspecialchars($_POST["piso"], ENT_QUOTES, 'UTF-8');
		$codigoPostal=htmlspecialchars($_POST["codigoPostal"], ENT_QUOTES, 'UTF-8');
		$entreCalle1=htmlspecialchars($_POST["entreCalle1"], ENT_QUOTES, 'UTF-8');
		$entreCalle2=htmlspecialchars($_POST["entreCalle2"], ENT_QUOTES, 'UTF-8');
		
		$departamento = !empty($departamento) ? "$departamento" : "---";
		$piso = !empty($piso) ? "$piso" : "NULL";
		$codigoPostal = !empty($codigoPostal) ? "$codigoPostal" : "---";
		$entreCalle1 = !empty($entreCalle1) ? "$entreCalle1" : "---";
		$entreCalle2 = !empty($entreCalle2) ? "$entreCalle2" : "---";

		$prefijoTelefono=htmlspecialchars($_POST["prefijoTelefono"], ENT_QUOTES, 'UTF-8');
		$nroTelefono=htmlspecialchars($_POST["nroTelefono"], ENT_QUOTES, 'UTF-8');
		$tipoTelefono=htmlspecialchars($_POST["tipoTelefono"], ENT_QUOTES, 'UTF-8');
		
		if(!empty($tipoDocumentoTitular) && !empty($documentoTitular))
		{
			if($stmt41 = $mysqli->prepare("SELECT p.valor FROM finan_cli.parametros p WHERE p.nombre = 'edad_permitida_cliente_adicional'"))
			{
				$stmt41->execute();    
				$stmt41->store_result();
				
				$totR41 = $stmt41->num_rows;

				if($totR41 > 0)
				{					
					$stmt41->bind_result($edad_permitida_adicional_db);
					$stmt41->fetch();
					
					$fechaObtParC = substr($fechaNacimiento, 6, 4).'-'.substr($fechaNacimiento, 3, 2).'-'.substr($fechaNacimiento, 0, 2).' 00:00:00';
					$fechaInParC = new DateTime($fechaObtParC);
					$fechaAct = new DateTime();
					$difACli = $fechaAct->diff($fechaInParC);

					if($edad_permitida_adicional_db > $difACli->y)
					{
						echo translate('Age_Not_Allowed_To_Register_Additional_Client_Holder',$GLOBALS['lang']);
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
			if($stmt41 = $mysqli->prepare("SELECT p.valor FROM finan_cli.parametros p WHERE p.nombre = 'edad_permitida_cliente_titular'"))
			{
				$stmt41->execute();    
				$stmt41->store_result();
				
				$totR41 = $stmt41->num_rows;

				if($totR41 > 0)
				{					
					$stmt41->bind_result($edad_permitida_titular_db);
					$stmt41->fetch();
					
					$fechaObtParC = substr($fechaNacimiento, 6, 4).'-'.substr($fechaNacimiento, 3, 2).'-'.substr($fechaNacimiento, 0, 2).' 00:00:00';
					$fechaInParC = new DateTime($fechaObtParC);
					$fechaAct = new DateTime();
					$difACli = $fechaAct->diff($fechaInParC);

					if($edad_permitida_titular_db > $difACli->y)
					{
						echo translate('Age_Not_Allowed_To_Register_Client_Holder',$GLOBALS['lang']);
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
		
		if($cuitCuil < 0 || $nroCalle < 0)
		{
			echo translate('Negative_Numbers_Are_Not_Allowed',$GLOBALS['lang']);
			return;
		}
		
		if(!empty($piso))
		{
			if($piso < 0)
			{
				echo translate('Negative_Numbers_Are_Not_Allowed',$GLOBALS['lang']);
				return;
			}
		}
		
		if($stmt41 = $mysqli->prepare("SELECT p.valor FROM finan_cli.parametros p WHERE p.nombre = 'monto_maximo_credito_cliente'"))
		{
			$stmt41->execute();    
			$stmt41->store_result();
			
			$totR41 = $stmt41->num_rows;

			if($totR41 > 0)
			{					
				$stmt41->bind_result($monto_maximo_permitido_cliente_db);
				$stmt41->fetch();

				if(($monto_maximo_permitido_cliente_db*100) < $montoMaximo)
				{
					echo translate('Maximum_Amount_Not_Allowed',$GLOBALS['lang']);
					return;
				}
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}

		if($stmt41 = $mysqli->prepare("SELECT p.valor FROM finan_cli.parametros p WHERE p.nombre = 'monto_minimo_credito_cliente'"))
		{
			$stmt41->execute();    
			$stmt41->store_result();
			
			$totR41 = $stmt41->num_rows;

			if($totR41 > 0)
			{					
				$stmt41->bind_result($monto_minimo_permitido_cliente_db);
				$stmt41->fetch();

				if(($monto_minimo_permitido_cliente_db*100) > $montoMaximo)
				{
					echo translate('Maximum_Amount_Not_Allowed',$GLOBALS['lang']);
					return;
				}
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}

		if($stmt41 = $mysqli->prepare("SELECT p.valor FROM finan_cli.parametros p WHERE p.nombre = 'monto_maximo_perfil_credito'"))
		{
			$stmt41->execute();    
			$stmt41->store_result();
			
			$totR41 = $stmt41->num_rows;

			if($totR41 > 0)
			{					
				$stmt41->bind_result($monto_maximo_perfil_credito_db);
				$stmt41->fetch();

				if(($monto_maximo_perfil_credito_db*100) < $montoMaximo)
				{
					echo translate('Amount_Entered_Exceeds_The_Maximum_Amount_Allowed_For_The_Selected_Credit_Profile',$GLOBALS['lang']);
					return;
				}
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}		
		
		if($stmt = $mysqli->prepare("SELECT c.id FROM finan_cli.cliente c WHERE c.tipo_documento = ? AND c.documento = ?"))
		{
			$stmt->bind_param('is', $tipoDocumento, $documento);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR > 0)
			{
				echo translate('Msg_Client_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{				
				if(!empty($tokenA))
				{
					if($stmt = $mysqli->prepare("SELECT tac.id FROM finan_cli.token_adicional_cuenta tac WHERE tac.token = ? AND tac.tipo_documento = ? AND tac.documento = ? AND tac.documento_titular = ? AND tac.fecha like ?"))
					{
						$date_registro_a_s = date("Ymd")."%";
						$stmt->bind_param('sisss', $tokenA, $tipoDocumento, $documento, $documentoTitular, $date_registro_a_s);
						$stmt->execute();    
						$stmt->store_result();
					
						$totR = $stmt->num_rows;
						
						if($totR == 0)
						{
							echo translate('Msg_No_Additional_Authorization_By_Supervisor',$GLOBALS['lang']);
							return;
						}
						
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;
					}
				}

				$pasoValidacionSMS = 0;
				$pasoValidacionEstadoCrediticio = 0;
				
				

				if($stmt2 = $mysqli->prepare("SELECT tvc.id FROM finan_cli.token_validacion_celular tvc WHERE tvc.tipo_documento = ? AND tvc.documento = ? AND tvc.fecha like ? AND tvc.token = ? AND tvc.validado = 1"))
				{
					$date_registro_a_vcc = date("Ymd")."%";
					$stmt2->bind_param('isss', $tipoDocumento, $documento, $date_registro_a_vcc, $tokenVC);
					$stmt2->execute();    
					$stmt2->store_result();
					
					$totR2 = $stmt2->num_rows;

					if($totR2 > 0)
					{
						$pasoValidacionSMS++;
					}			
				}
				else
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;
				}
				
				if($stmt4 = $mysqli->prepare("SELECT e.id FROM finan_cli.estado_cliente e WHERE e.tipo_documento = ? AND e.documento = ? AND e.fecha like ? AND e.id_motivo = ?"))
				{
					$motivoValidacionSMS = 36;
					$date_registro_a_s = date("Ymd")."%";
					$stmt4->bind_param('issi', $tipoDocumento, $documento, $date_registro_a_s, $motivoValidacionSMS);
					$stmt4->execute();    
					$stmt4->store_result();
					
					$totR4 = $stmt4->num_rows;

					if($totR4 > 0)
					{
						$pasoValidacionSMS++;
					}			
				}
				else
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;
				}				
				
				if($pasoValidacionSMS == 0)
				{
					echo translate('The_Client_Mobile_Phone_Was_Not_Correctly_Validated',$GLOBALS['lang']);
					return;
				}
				
						
				if(!empty($tipoDocumentoTitular) && !empty($documentoTitular))
				{
					if($stmt40 = $mysqli->prepare("SELECT c.id, c.cuil_cuit FROM finan_cli.cliente c WHERE c.tipo_documento = ? AND c.documento = ?"))
					{
						$stmt40->bind_param('is', $tipoDocumentoTitular, $documentoTitular);
						$stmt40->execute();    
						$stmt40->store_result();
						
						$totR40 = $stmt40->num_rows;

						if($totR40 > 0)
						{
							$stmt40->bind_result($id_cliente_titular, $cuit_cuil_titular);
							$stmt40->fetch();
							
							$cuitCuilTitular = $cuit_cuil_titular;
							$idClienteTitular = $id_cliente_titular;
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
		
				if($stmt = $mysqli->prepare("SELECT cef.id FROM finan_cli.consulta_estado_financiero cef WHERE cef.tipo_documento = ? AND cef.documento = ? AND cef.token = ? AND cef.cuit_cuil = ? AND cef.validado = 1"))
				{
					if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $stmt->bind_param('issi', $tipoDocumentoTitular, $documentoTitular, $tokenVECC, $cuitCuilTitular);
					else $stmt->bind_param('issi', $tipoDocumento, $documento, $tokenVECC, $cuitCuil);
					$stmt->execute();    
					$stmt->store_result();
					
					$totR = $stmt->num_rows;

					if($totR > 0)
					{
						$pasoValidacionEstadoCrediticio++;
					}			
				}
				else
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;
				}
				
				if($stmt41 = $mysqli->prepare("SELECT e.id FROM finan_cli.estado_cliente e WHERE e.tipo_documento = ? AND e.documento = ? AND e.fecha like ? AND e.id_motivo IN (?,?)"))
				{
					$motivoValidacionECC = 37;
					$motivoValidacionECC2 = 38;
					$date_registro_a_s = date("Ymd")."%";
					if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $stmt41->bind_param('issi', $tipoDocumentoTitular, $documentoTitular, $date_registro_a_s, $motivoValidacionECC, $motivoValidacionECC2);
					else $stmt41->bind_param('issi', $tipoDocumento, $documento, $date_registro_a_s, $motivoValidacionECC, $motivoValidacionECC2);
					$stmt41->execute();    
					$stmt41->store_result();
					
					$totR41 = $stmt41->num_rows;

					if($totR41 > 0)
					{
						$pasoValidacionEstadoCrediticio++;
					}			
				}
				else
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;
				}

				if($pasoValidacionEstadoCrediticio == 0)
				{
					echo translate('The_Client_Credit_Status_Was_Not_Correctly_Validated',$GLOBALS['lang']);
					return;
				}				
				
				$idClienteTitular = !empty($idClienteTitular) ? "$idClienteTitular" : "NULL";				
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.domicilio(calle,nro_calle,id_provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2) VALUES (?,?,?,?,?,?,?,?,?)"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$stmt10->bind_param('siississs', $calle, $nroCalle, $provincia, $localidad, $departamento, $piso, $codigoPostal, $entreCalle1, $entreCalle2);
					if(!$stmt10->execute())
					{
						echo $mysqli->error;
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;						
					}
					else $idDomicilioClient = $mysqli->insert_id;
				}	

				$date_registro = date("YmdHis");				
				$valor_log_user = "INSERT INTO finan_cli.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2) VALUES (".$calle.",".$nroCalle.",".$provincia.",".$localidad.",".str_replace('\'','',$departamento).",".$piso.",".str_replace('\'','',$codigoPostal).",".str_replace('\'','',$entreCalle1).",".str_replace('\'','',$entreCalle2).")";

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
					$motivo = 39;
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
								

				if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (?,?,?)"))
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
					$numTelFinI = $prefijoTelefono.$nroTelefono;
					$cantPrefiFN = strlen($prefijoTelefono);
					$stmt10->bind_param('iii', $tipoTelefono, $numTelFinI, $cantPrefiFN);
					if(!$stmt10->execute())
					{
						echo $mysqli->error;
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;
					}
					else $idTelefonoClient = $mysqli->insert_id;
				}
								
				$date_registro = date("YmdHis");					
				$valor_log_user = "INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (".$tipoTelefono.",".$prefijoTelefono.$nroTelefono.",".strlen($prefijoTelefono).")";
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
					$motivo = 42;
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
				
				
				if(!$stmt20 = $mysqli->prepare("INSERT INTO finan_cli.cliente (tipo_documento,documento,nombres,apellidos,cuil_cuit,fecha_nacimiento,email,fecha_alta,estado,id_titular,observaciones,monto_maximo_credito,id_perfil_credito,id_genero) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)"))
				{
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$date_registro_alta_cliente = date("YmdHis");
					$fechaNacimiento = substr($fechaNacimiento, 6, 4).substr($fechaNacimiento, 3, 2).substr($fechaNacimiento, 0, 2).'000000';
					$estado_cliente_ini = translate('State_User',$GLOBALS['lang']);
					$stmt20->bind_param('isssissssisiii', $tipoDocumento, $documento, $nombres, $apellidos, $cuitCuil, $fechaNacimiento, $email, $date_registro_alta_cliente, $estado_cliente_ini, $idClienteTitular, $observaciones, $montoMaximo, $perfilCredito, $genero);
					if(!$stmt20->execute())
					{
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;						
					}
					
					if(!$stmt21 = $mysqli->prepare("INSERT INTO finan_cli.cliente_x_domicilio(tipo_documento, documento, id_domicilio) VALUES (?,?,?)"))
					{
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;
					}
					else
					{
						$stmt21->bind_param('isi', $tipoDocumento, $documento, $idDomicilioClient);
						if(!$stmt21->execute())
						{	
							$mysqli->rollback();
							$mysqli->autocommit(TRUE);
							$stmt->free_result();
							$stmt->close();
							return;					
						}					
					}
					
					if(!$stmt22 = $mysqli->prepare("INSERT INTO finan_cli.cliente_x_telefono(tipo_documento, documento, id_telefono) VALUES (?,?,?)"))
					{
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;
					}
					else
					{
						$stmt22->bind_param('isi', $tipoDocumento, $documento, $idTelefonoClient);
						if(!$stmt22->execute())
						{	
							$mysqli->rollback();
							$mysqli->autocommit(TRUE);
							$stmt->free_result();
							$stmt->close();
							return;					
						}					
					}					
				}
	
				$date_registro = date("YmdHis");
				$valor_log_user = "INSERT INTO finan_cli.cliente (tipo_documento,documento,nombres,apellidos,cuil_cuit,fecha_nacimiento,email,fecha_alta,estado,id_titular,observaciones,monto_maximo_credito,id_perfil_credito,id_genero) VALUES(".$tipoDocumento.",".$documento.",".$nombres.",".$apellidos.",".$cuitCuil.",".$fechaNacimiento.",".str_replace('\'','',$email).",".$date_registro_alta_cliente.",".$estado_cliente_ini.",".$idClienteTitular.",".str_replace('\'','',$observaciones).",".$montoMaximo.",".$perfilCredito.",".$genero.")";
					
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
					$motivo = 45;
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
										
				$mysqli->commit();
				$mysqli->autocommit(TRUE);
				
				echo translate('Msg_New_Client_OK',$GLOBALS['lang']);
				return;				
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}
?>