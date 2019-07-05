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

		$prefijoTelefono=htmlspecialchars($_POST["prefijoTelefono"], ENT_QUOTES, 'UTF-8');
		$nroTelefono=htmlspecialchars($_POST["nroTelefono"], ENT_QUOTES, 'UTF-8');
		$tipoTelefono=htmlspecialchars($_POST["tipoTelefono"], ENT_QUOTES, 'UTF-8');
		$preferido=htmlspecialchars($_POST["preferido"], ENT_QUOTES, 'UTF-8');
		$token=htmlspecialchars($_POST["token"], ENT_QUOTES, 'UTF-8');
		
		if($prefijoTelefono < 0 || $nroTelefono < 0)
		{
			echo translate('Negative_Numbers_Are_Not_Allowed',$GLOBALS['lang']);
			return;
		}
					
		if($stmt = $mysqli->prepare("SELECT c.id, c.tipo_documento, c.documento FROM finan_cli.cliente c WHERE c.id = ?"))
		{
			$stmt->bind_param('i', $idCliente);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_Client_Not_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{
				$stmt->bind_result($id_client, $client_tipo_documento, $client_documento);
				$stmt->fetch();
				
				$tipoDocumento = $client_tipo_documento;
				$documento = $client_documento;
				
				if($stmt3 = $mysqli->prepare("SELECT count(t.id) FROM finan_cli.cliente c, finan_cli.telefono t, finan_cli.cliente_x_telefono ct WHERE c.id = ? AND ct.tipo_documento = c.tipo_documento AND ct.documento = c.documento AND ct.id_telefono = t.id"))
				{
					$stmt3->bind_param('i', $idCliente);
					$stmt3->execute();    
					$stmt3->store_result();
					
					$stmt3->bind_result($cantidad_telefonos);
					$stmt3->fetch();
					
					if($stmt4 = $mysqli->prepare("SELECT t.id FROM finan_cli.cliente c, finan_cli.telefono t, finan_cli.cliente_x_telefono ct WHERE c.id = ? AND ct.tipo_documento = c.tipo_documento AND ct.documento = c.documento AND ct.id_telefono = t.id AND t.numero = ?"))
					{
						$numTelFinCo = $prefijoTelefono.$nroTelefono;
						$stmt4->bind_param('ii', $idCliente, $numTelFinCo);
						$stmt4->execute();    
						$stmt4->store_result();
						
						$totR4 = $stmt4->num_rows;

						if($totR4 > 0)
						{
							echo translate('Msg_Phone_Exist',$GLOBALS['lang']);
							return;
						}						
						
						$stmt4->free_result();
						$stmt4->close();
					}
					else 
					{
						$stmt3->free_result();
						$stmt3->close();
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;							
					}
					
					$pasoValidacionSMS = 0;
					
					if($stmt2 = $mysqli->prepare("SELECT tvc.id FROM finan_cli.token_validacion_celular tvc WHERE tvc.tipo_documento = ? AND tvc.documento = ? AND tvc.fecha like ? AND tvc.token = ? AND tvc.validado = 1 AND tvc.nro_telefono = ?"))
					{
						$date_registro_a_vcc = date("Ymd")."%";
						$stmt2->bind_param('isssi', $tipoDocumento, $documento, $date_registro_a_vcc, $token, $numTelFinCo);
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
					
					if($stmt4 = $mysqli->prepare("SELECT e.id FROM finan_cli.estado_cliente e WHERE e.tipo_documento = ? AND e.documento = ? AND e.fecha like ? AND e.id_motivo IN (?,?) AND e.nro_telefono = ?"))
					{
						$motivoValidacionSMS = 54;
						$motivoValidacionSMS2 = 55;
						$date_registro_a_s = date("Ymd")."%";
						$stmt4->bind_param('issiii', $tipoDocumento, $documento, $date_registro_a_s, $motivoValidacionSMS, $motivoValidacionSMS2, $numTelFinCo);
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
						echo translate('The_Client_Phone_Was_Not_Correctly_Validated',$GLOBALS['lang']);
						return;
					}
					
					
					if($stmt2 = $mysqli->prepare("SELECT valor FROM finan_cli.parametros WHERE nombre = 'cantidad_domicilios_x_usuario_cliente'"))
					{
						$stmt2->execute();    
						$stmt2->store_result();
						$stmt2->bind_result($cantidad_telefonos_db);
						$stmt2->fetch();
						if($cantidad_telefonos >= $cantidad_telefonos_db)
						{
							$stmt3->free_result();
							$stmt3->close();
							$stmt2->free_result();
							$stmt2->close();
							echo str_replace("%1",$cantidad_telefonos_db,translate('Msg_Limit_Phones_User',$GLOBALS['lang']));
							return;	
						}
						
						$stmt2->free_result();
						$stmt2->close();
					}
					else
					{
						$stmt3->free_result();
						$stmt3->close();
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;	
					}
				}
				else
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;	
				}
				
				$stmt3->free_result();
				$stmt3->close();				
				
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (?,?,?)"))
				{
					echo $mysqli->error;
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
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;
					}						
					$idTelefonoG = $mysqli->insert_id;
					
					if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.cliente_x_telefono(tipo_documento,documento,id_telefono,preferido) VALUES (?,?,?,?)"))
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
						if($preferido == 'true') $preferidoDB = 1;
						else $preferidoDB = 0;
						$stmt10->bind_param('isii', $client_tipo_documento, $client_documento, $idTelefonoG, $preferidoDB);
						if(!$stmt10->execute())
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
				$date_registro2 = date("Y-m-d H:i:s");					
				$valor_log_user = "INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo,preferido) VALUES (".$tipoTelefono.",".$prefijoTelefono.$nroTelefono.",".strlen($prefijoTelefono).",".$preferido.")";

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
				
				if($preferidoDB == 1)
				{
					if(!$stmt10 = $mysqli->prepare("UPDATE finan_cli.cliente_x_telefono SET preferido = 0 WHERE tipo_documento = ? AND documento = ? AND id_telefono <> ?"))
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
						$stmt10->bind_param('isi', $client_tipo_documento, $client_documento, $idTelefonoG);
						if(!$stmt10->execute())
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
										
				$mysqli->commit();
				$mysqli->autocommit(TRUE);
				
				if($stmt = $mysqli->prepare("SELECT t.id, tt.nombre, t.numero, ct.preferido FROM finan_cli.telefono t, finan_cli.cliente c, finan_cli.tipo_telefono tt, finan_cli.cliente_x_telefono ct WHERE c.id = ? AND tt.id = t.tipo_telefono AND ct.tipo_documento = c.tipo_documento AND ct.documento = c.documento AND ct.id_telefono = t.id")) 
				{
					$stmt->bind_param('i', $idCliente);
					$stmt->execute();    // Ejecuta la consulta preparada.
					$stmt->store_result();
			 
					// Obtiene las variables del resultado.
					$stmt->bind_result($id_telefono, $client_tipo_telefono, $client_numero_telefono, $client_preference_telefono);
										
					$array[0] = array();
					$posicion = 0;
					while($stmt->fetch())
					{
						$array[$posicion]['tipotelefono'] = $client_tipo_telefono;
						$array[$posicion]['nrotelefono'] = $client_numero_telefono;
						if($client_preference_telefono == 1) $array[$posicion]['preferencia'] = translate('Lbl_Button_YES',$GLOBALS['lang']);
						else $array[$posicion]['preferencia'] = translate('Lbl_Button_NO',$GLOBALS['lang']);
						
						$array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Phone',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Remove_Telefono',$GLOBALS['lang']).'\',\''.$idCliente.'\',\''.$id_telefono.'\')"><i class="fas fa-phone-slash"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Phone',$GLOBALS['lang']).'" onclick="modificarTelefono(\''.$idCliente.'\',\''.$id_telefono.'\')"><i class="fas fa-phone-volume"></i></button>';
						
						$posicion++;
					}
					
					echo translate('Msg_Add_Phone_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
				}
				else 
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;	
				}
				
				$stmt->free_result();
				$stmt->close();
				return;				
				
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}
?>