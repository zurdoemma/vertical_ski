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
		
		
		$idCliente=htmlspecialchars($_POST["idCliente"], ENT_QUOTES, 'UTF-8');
		$tipoCuenta=htmlspecialchars($_POST["tipoCuenta"], ENT_QUOTES, 'UTF-8');
		$tipoDocumento=htmlspecialchars($_POST["tipoDocumento"], ENT_QUOTES, 'UTF-8');
		$documento=htmlspecialchars($_POST["documento"], ENT_QUOTES, 'UTF-8');
		$nombres=htmlspecialchars($_POST["nombre"], ENT_QUOTES, 'UTF-8');
		$apellidos=htmlspecialchars($_POST["apellido"], ENT_QUOTES, 'UTF-8');
		$fechaNacimiento=htmlspecialchars($_POST["fechaNacimiento"], ENT_QUOTES, 'UTF-8');
		$cuitCuil=htmlspecialchars($_POST["cuitCuil"], ENT_QUOTES, 'UTF-8');
		$email=htmlspecialchars($_POST["email"], ENT_QUOTES, 'UTF-8');
		$montoMaximo=htmlspecialchars($_POST["montoMaximo"], ENT_QUOTES, 'UTF-8');		
		$perfilCredito=htmlspecialchars($_POST["perfilCredito"], ENT_QUOTES, 'UTF-8');
		$observaciones=htmlspecialchars($_POST["observaciones"], ENT_QUOTES, 'UTF-8');
		$genero=htmlspecialchars($_POST["genero"], ENT_QUOTES, 'UTF-8');
		$tokenCTC=htmlspecialchars($_POST["tokenCTC"], ENT_QUOTES, 'UTF-8');
		$tokenVECC=htmlspecialchars($_POST["tokenVECC"], ENT_QUOTES, 'UTF-8');
		
		$email = !empty($email) ? "$email" : "---";
		$observaciones = !empty($observaciones) ? "$observaciones" : "---";
		
		
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
		
		if($stmt37 = $mysqli->prepare("SELECT c.id, c.id_titular, c.tipo_documento, c.documento FROM finan_cli.cliente c WHERE c.id = ?"))
		{
			$stmt37->bind_param('i', $idCliente);
			$stmt37->execute();    
			$stmt37->store_result();
			
			$totR37 = $stmt37->num_rows;

			if($totR37 == 0)
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
		
		$stmt37->bind_result($id_cliente_db, $tipo_cuenta_cliente_db, $tipo_documento_cliente_db, $documento_cliente_db);
		$stmt37->fetch();
		
		$tipoDocumentoDB=$tipo_documento_cliente_db;
		$documentoDB=$documento_cliente_db;
		
		if(empty($tipo_cuenta_cliente_db)) $tipoCuentaCliente = translate('Lbl_Type_Client_Headline',$GLOBALS['lang']);
		else $tipoCuentaCliente = translate('Lbl_Type_Client_Additional',$GLOBALS['lang']);
		
		if($tipoCuenta != $tipoCuentaCliente)
		{
			if($tipoCuenta == translate('Lbl_Type_Client_Additional',$GLOBALS['lang']))
			{
				echo translate('Msg_It_Is_Not_Possible_To_Change_From_Holder_To_Additional_Account',$GLOBALS['lang']);
				return;
			}
			
			if($stmt = $mysqli->prepare("SELECT tcc.id FROM finan_cli.token_cambio_cuenta tcc WHERE tcc.token = ? AND tcc.tipo_documento = ? AND tcc.documento = ? AND tcc.fecha like ? AND tcc.validado = 1"))
			{
				$date_registro_a_s = date("Ymd")."%";
				$stmt->bind_param('siss', $tokenCTC, $tipoDocumentoDB, $documentoDB, $date_registro_a_s);
				$stmt->execute();    
				$stmt->store_result();
			
				$totR = $stmt->num_rows;
				
				if($totR == 0)
				{
					echo translate('Msg_Error_Authorize_Client_Change_Type_Account',$GLOBALS['lang']);
					return;
				}
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;
			}
		}
		
		if($stmt = $mysqli->prepare("SELECT ec.id FROM finan_cli.estado_cliente ec WHERE ec.tipo_documento = ? AND ec.documento = ? AND ec.fecha like ? AND ec.id_motivo IN (50,51,52)"))
		{
			$date_registro_a_s2 = date("Ymd")."%";
			$stmt->bind_param('iss', $tipoDocumentoDB, $documentoDB, $date_registro_a_s2);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;
			
			if($totR == 0)
			{
				echo translate('Msg_Error_Authorize_Client_Credit_Status',$GLOBALS['lang']);
				return;
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
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
		
								
		if($tipoCuentaCliente == translate('Lbl_Type_Client_Additional',$GLOBALS['lang']))
		{
			if($stmt40 = $mysqli->prepare("SELECT c.id, c.cuil_cuit, c.id_perfil_credito, c.monto_maximo_credito FROM finan_cli.cliente c WHERE c.id = ?"))
			{
				$stmt40->bind_param('i', $tipo_cuenta_cliente_db);
				$stmt40->execute();    
				$stmt40->store_result();
				
				$totR40 = $stmt40->num_rows;

				if($totR40 > 0)
				{
					$stmt40->bind_result($id_cliente_titular, $cuit_cuil_titular, $perfil_credito_titular, $monto_maximo_credito_titular);
					$stmt40->fetch();
					
					$cuitCuilTitular = $cuit_cuil_titular;
					$idClienteTitular = $id_cliente_titular;
					$idPerfilCreditoTitular = $perfil_credito_titular; 
					
					if($montoMaximo > $monto_maximo_credito_titular)
					{
						echo translate('Msg_Maximum_Amount_Of_Credit_Of_The_Additional_Can_Not_Be_Greater_Than_The_Holder',$GLOBALS['lang']);
						return;
					}
					
					if($stmt41 = $mysqli->prepare("SELECT pc.monto_maximo FROM finan_cli.perfil_credito pc WHERE pc.id = ?"))
					{
						$stmt41->bind_param('i', $idPerfilCreditoTitular);
						$stmt41->execute();    
						$stmt41->store_result();
						
						$totR41 = $stmt41->num_rows;

						if($totR41 > 0)
						{					
							$stmt41->bind_result($monto_maximo_perfil_c_db);
							$stmt41->fetch();

							if($monto_maximo_perfil_c_db < $montoMaximo)
							{
								echo translate('Msg_The_Maximum_Amount_Can_Not_Be_Greater_Than_The_Credit_Profile',$GLOBALS['lang']);
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
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;
			}
		}
		else
		{
			if($stmt41 = $mysqli->prepare("SELECT pc.monto_maximo FROM finan_cli.perfil_credito pc WHERE pc.id = ?"))
			{
				$stmt41->bind_param('i', $perfilCredito);
				$stmt41->execute();    
				$stmt41->store_result();
				
				$totR41 = $stmt41->num_rows;

				if($totR41 > 0)
				{					
					$stmt41->bind_result($monto_maximo_perfil_c_db);
					$stmt41->fetch();

					if($monto_maximo_perfil_c_db < $montoMaximo)
					{
						echo translate('Msg_The_Maximum_Amount_Can_Not_Be_Greater_Than_The_Credit_Profile',$GLOBALS['lang']);
						return;
					}
				}
			}
			else
			{
				echo $mysqli->error;
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']).'ACAAA';
				return;
			}					
		}
		
		sss

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
		
		if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $selectEFCCIni = "SELECT e.id FROM finan_cli.estado_cliente e WHERE e.tipo_documento = ? AND e.documento = ? AND e.fecha like ? AND e.id_motivo IN (?,?) AND e.tipo_documento_adicional = ? AND e.documento_adicional = ?";
		else $selectEFCCIni = "SELECT e.id FROM finan_cli.estado_cliente e WHERE e.tipo_documento = ? AND e.documento = ? AND e.fecha like ? AND e.id_motivo IN (?,?)";
		if($stmt41 = $mysqli->prepare($selectEFCCIni))
		{
			$motivoValidacionECC = 37;
			$motivoValidacionECC2 = 38;
			$date_registro_a_s = date("Ymd")."%";
			if(!empty($tipoDocumentoTitular) && !empty($documentoTitular)) $stmt41->bind_param('issiiis', $tipoDocumentoTitular, $documentoTitular, $date_registro_a_s, $motivoValidacionECC, $motivoValidacionECC2, $tipoDocumento, $documento);
			else $stmt41->bind_param('issii', $tipoDocumento, $documento, $date_registro_a_s, $motivoValidacionECC, $motivoValidacionECC2);
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
		
		if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.domicilio(calle,nro_calle,id_provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2,preferido) VALUES (?,?,?,?,?,?,?,?,?,?)"))
		{
			echo $mysqli->error;
			$mysqli->autocommit(TRUE);
			$stmt->free_result();
			$stmt->close();
			return;
		}
		else
		{
			$preferidoD = 1;
			$stmt10->bind_param('siississsi', $calle, $nroCalle, $provincia, $localidad, $departamento, $piso, $codigoPostal, $entreCalle1, $entreCalle2, $preferidoD);
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
		$valor_log_user = "INSERT INTO finan_cli.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2,preferido) VALUES (".$calle.",".$nroCalle.",".$provincia.",".$localidad.",".str_replace('\'','',$departamento).",".$piso.",".str_replace('\'','',$codigoPostal).",".str_replace('\'','',$entreCalle1).",".str_replace('\'','',$entreCalle2).",".$preferidoD.")";

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
						

		if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo,preferido) VALUES (?,?,?,?)"))
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
			$preferidoT = 1;
			$numTelFinI = $prefijoTelefono.$nroTelefono;
			$cantPrefiFN = strlen($prefijoTelefono);
			$stmt10->bind_param('iiii', $tipoTelefono, $numTelFinI, $cantPrefiFN, $preferidoT);
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
		$valor_log_user = "INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo,preferido) VALUES (".$tipoTelefono.",".$prefijoTelefono.$nroTelefono.",".strlen($prefijoTelefono).",".$preferidoT.")";
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
		
		if(empty($idClienteTitular) || $idClienteTitular == 0) $sqlInserClient = "INSERT INTO finan_cli.cliente (tipo_documento,documento,nombres,apellidos,cuil_cuit,fecha_nacimiento,email,fecha_alta,estado,observaciones,monto_maximo_credito,id_perfil_credito,id_genero) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
		else $sqlInserClient = "INSERT INTO finan_cli.cliente (tipo_documento,documento,nombres,apellidos,cuil_cuit,fecha_nacimiento,email,fecha_alta,estado,id_titular,observaciones,monto_maximo_credito,id_perfil_credito,id_genero) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		if(!$stmt20 = $mysqli->prepare($sqlInserClient))
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
			$date_registro_alta_cliente = date("YmdHis");
			$fechaNacimiento = substr($fechaNacimiento, 6, 4).substr($fechaNacimiento, 3, 2).substr($fechaNacimiento, 0, 2).'000000';
			$estado_cliente_ini = translate('State_User',$GLOBALS['lang']);
			if(empty($idClienteTitular) || $idClienteTitular == 0) $stmt20->bind_param('isssisssssiii', $tipoDocumento, $documento, $nombres, $apellidos, $cuitCuil, $fechaNacimiento, $email, $date_registro_alta_cliente, $estado_cliente_ini, $observaciones, $montoMaximo, $perfilCredito, $genero);
			else 
			{
				if(!empty($idPerfilCreditoTitular)) $stmt20->bind_param('isssissssisiii', $tipoDocumento, $documento, $nombres, $apellidos, $cuitCuil, $fechaNacimiento, $email, $date_registro_alta_cliente, $estado_cliente_ini, $idClienteTitular, $observaciones, $montoMaximo, $idPerfilCreditoTitular, $genero);
				else $stmt20->bind_param('isssissssisiii', $tipoDocumento, $documento, $nombres, $apellidos, $cuitCuil, $fechaNacimiento, $email, $date_registro_alta_cliente, $estado_cliente_ini, $idClienteTitular, $observaciones, $montoMaximo, $perfilCredito, $genero);
			}
			if(!$stmt20->execute())
			{
				echo $mysqli->error;
				$mysqli->rollback();
				$mysqli->autocommit(TRUE);
				$stmt->free_result();
				$stmt->close();
				return;						
			}
			
			if(!$stmt21 = $mysqli->prepare("INSERT INTO finan_cli.cliente_x_domicilio(tipo_documento, documento, id_domicilio) VALUES (?,?,?)"))
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
				$stmt21->bind_param('isi', $tipoDocumento, $documento, $idDomicilioClient);
				if(!$stmt21->execute())
				{	
					echo $mysqli->error;
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;					
				}					
			}
			
			if(!$stmt22 = $mysqli->prepare("INSERT INTO finan_cli.cliente_x_telefono(tipo_documento, documento, id_telefono) VALUES (?,?,?)"))
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
				$stmt22->bind_param('isi', $tipoDocumento, $documento, $idTelefonoClient);
				if(!$stmt22->execute())
				{	
					echo $mysqli->error;
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
		
		
		if ($stmt = $mysqli->prepare("SELECT c.id, td.nombre, c.documento, c.nombres, c.apellidos, c.estado, CASE WHEN c.id_titular IS NOT NULL THEN '".translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang'])."' ELSE '".translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang'])."' END AS tipoCuenta FROM finan_cli.cliente c, finan_cli.tipo_documento td  WHERE c.tipo_documento = td.id ORDER BY c.documento")) 
		{
			$stmt->execute();    
			$stmt->store_result();
	 
			$stmt->bind_result($id_client, $type_document_client, $document_client, $name_client, $surname_client, $state_client, $type_account_client);
			
			$array[0] = array();
			$posicion = 0;
			while($stmt->fetch())
			{
				$array[$posicion]['tipodocumento'] = $type_document_client;
				$array[$posicion]['documento'] = $document_client;
				$array[$posicion]['nombre'] = $name_client;
				$array[$posicion]['apellido'] = $surname_client;
				$array[$posicion]['estado'] = $state_client;
				$array[$posicion]['tipocuenta'] = $type_account_client;
				
				if($type_account_client == translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang']))
				{
					if ($stmt90 = $mysqli->prepare("SELECT c.id FROM finan_cli.cliente c WHERE c.id_titular = ?")) 
					{
						$stmt90->bind_param('i', $id_client);
						$stmt90->execute();   
						$stmt90->store_result();
				 
						$totR90 = $stmt90->num_rows;

						if($totR90 > 0)
						{
							if($state_client == translate('State_User',$GLOBALS['lang'])) $array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Deactivate_Client',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Deactivate_Client',$GLOBALS['lang']).'\',\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-times"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Client',$GLOBALS['lang']).'" onclick="modificarCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-cog"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_See_Additional_Client',$GLOBALS['lang']).'" onclick="verAdicionalesCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-eye"></i></button>';
							else $array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Activate_Client',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Activate_Client',$GLOBALS['lang']).'\',\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-check"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Client',$GLOBALS['lang']).'" onclick="modificarCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-cog"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_See_Additional_Client',$GLOBALS['lang']).'" onclick="verAdicionalesCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-eye"></i></button>';									
						}
						else
						{
							if($state_client == translate('State_User',$GLOBALS['lang'])) $array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Deactivate_Client',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Deactivate_Client',$GLOBALS['lang']).'\',\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-times"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Client',$GLOBALS['lang']).'" onclick="modificarCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-cog"></i></button>';
							else $array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Activate_Client',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Activate_Client',$GLOBALS['lang']).'\',\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-check"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Client',$GLOBALS['lang']).'" onclick="modificarCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-cog"></i></button>';												
						}
					}
					
					$stmt90->free_result();
					$stmt90->close();
				}
				else
				{
					if($state_client == translate('State_User',$GLOBALS['lang'])) $array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Deactivate_Client',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Deactivate_Client',$GLOBALS['lang']).'\',\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-times"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Client',$GLOBALS['lang']).'" onclick="modificarCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-cog"></i></button>';
					else $array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Activate_Client',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Activate_Client',$GLOBALS['lang']).'\',\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-check"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Client',$GLOBALS['lang']).'" onclick="modificarCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-cog"></i></button>';
				}
				$posicion++;
			}
			
			echo translate('Msg_New_Client_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
			return;
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;	
		}				
?>