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

		$calle=htmlspecialchars($_POST["calle"], ENT_QUOTES, 'UTF-8');
		$nroCalle=htmlspecialchars($_POST["nroCalle"], ENT_QUOTES, 'UTF-8');
		$provincia=htmlspecialchars($_POST["provincia"], ENT_QUOTES, 'UTF-8');
		$localidad=htmlspecialchars($_POST["localidad"], ENT_QUOTES, 'UTF-8');
		$departamento=htmlspecialchars($_POST["departamento"], ENT_QUOTES, 'UTF-8');
		$piso=htmlspecialchars($_POST["piso"], ENT_QUOTES, 'UTF-8');
		$codigoPostal=htmlspecialchars($_POST["codigoPostal"], ENT_QUOTES, 'UTF-8');
		$entreCalle1=htmlspecialchars($_POST["entreCalle1"], ENT_QUOTES, 'UTF-8');
		$entreCalle2=htmlspecialchars($_POST["entreCalle2"], ENT_QUOTES, 'UTF-8');
		$preferido=htmlspecialchars($_POST["preferido"], ENT_QUOTES, 'UTF-8');
		
		$departamento = !empty($departamento) ? "$departamento" : "---";
		$piso = !empty($piso) ? "$piso" : "NULL";
		$codigoPostal = !empty($codigoPostal) ? "$codigoPostal" : "---";
		$entreCalle1 = !empty($entreCalle1) ? "$entreCalle1" : "---";
		$entreCalle2 = !empty($entreCalle2) ? "$entreCalle2" : "---";
		
		if($nroCalle < 0)
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
		
		if($stmt = $mysqli->prepare("SELECT c.id, c.documento, c.tipo_documento FROM finan_cli.cliente c WHERE c.id = ?"))
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
				$stmt->bind_result($id_cliente_db, $documento_cliente_db, $tipo_documento_cliente_db);
				$stmt->fetch();
				
				if($stmt3 = $mysqli->prepare("SELECT count(d.id) FROM finan_cli.cliente c, finan_cli.domicilio d, finan_cli.provincia p, finan_cli.cliente_x_domicilio cd WHERE d.id_provincia = p.id AND c.id = ? AND cd.tipo_documento = c.tipo_documento AND cd.documento = c.documento AND cd.id_domicilio = d.id"))
				{
					$stmt3->bind_param('i', $idCliente);
					$stmt3->execute();    
					$stmt3->store_result();
					
					$stmt3->bind_result($cantidad_domicilios);
					$stmt3->fetch();
					
					if($stmt2 = $mysqli->prepare("SELECT valor FROM finan_cli.parametros WHERE nombre = 'cantidad_domicilios_x_usuario_cliente'"))
					{
						$stmt2->execute();    
						$stmt2->store_result();
						$stmt2->bind_result($cantidad_domicilios_db);
						$stmt2->fetch();
						if($cantidad_domicilios >= $cantidad_domicilios_db)
						{
							$stmt3->free_result();
							$stmt3->close();
							$stmt2->free_result();
							$stmt2->close();
							echo str_replace("%1",$cantidad_domicilios_db,translate('Msg_Limit_Address_User',$GLOBALS['lang']));
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
					$idDomicilioI = $mysqli->insert_id;
					
					if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.cliente_x_domicilio(tipo_documento, documento, id_domicilio, preferido) VALUES (?,?,?,?)"))
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
						$stmt10->bind_param('isii', $tipo_documento_cliente_db, $documento_cliente_db, $idDomicilioI, $preferidoDB);
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
				$valor_log_user = "INSERT INTO finan_cli.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2) VALUES (".$calle.",".$nroCalle.",".$provincia.",".$localidad.",".str_replace('\'','',$departamento).",".$piso.",".str_replace('\'','',$codigoPostal).",".str_replace('\'','',$entreCalle1).",".str_replace('\'','',$entreCalle2).") - preferido = ".$preferidoDB;

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
				
				if($preferidoDB == 1)
				{
					if(!$stmt10 = $mysqli->prepare("UPDATE finan_cli.cliente_x_domicilio SET preferido = 0 WHERE tipo_documento = ? AND documento = ? AND id_domicilio <> ?"))
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
						$stmt10->bind_param('isi', $tipo_documento_cliente_db, $documento_cliente_db, $idDomicilioI);
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
				
				if($stmt = $mysqli->prepare("SELECT d.id, d.calle, d.nro_calle, p.nombre, d.localidad, d.departamento, d.piso, d.codigo_postal, d.entre_calle_1, d.entre_calle_2, cd.preferido FROM finan_cli.domicilio d, finan_cli.cliente c, finan_cli.provincia p, finan_cli.cliente_x_domicilio cd WHERE c.id = ? AND cd.tipo_documento = c.tipo_documento AND cd.documento = c.documento AND p.id = d.id_provincia AND cd.id_domicilio = d.id")) 
				{
					$stmt->bind_param('i', $idCliente);
					$stmt->execute();    // Ejecuta la consulta preparada.
					$stmt->store_result();
			 
					// Obtiene las variables del resultado.
					$stmt->bind_result($id_domicilio, $client_domicilio_calle, $client_domicilio_nro_calle, $client_domicilio_id_provincia, $client_domicilio_localidad, $client_domicilio_departamento, $client_domicilio_piso, $client_domicilio_codigo_postal, $client_domicilio_entre_calles_1, $client_domicilio_entre_calles_2, $client_preference_domicilio);
									
					$array[0] = array();
					$posicion = 0;
					while($stmt->fetch())
					{
						$array[$posicion]['calle'] = $client_domicilio_calle;
						$array[$posicion]['nrocalle'] = $client_domicilio_nro_calle;
						$array[$posicion]['provincia'] = $client_domicilio_id_provincia;
						$array[$posicion]['localidad'] = $client_domicilio_localidad;
						
						if(empty($client_domicilio_departamento)) $array[$posicion]['departamento'] = '---';
						else $array[$posicion]['departamento'] = $client_domicilio_departamento;
						if(empty($client_domicilio_piso)) $array[$posicion]['piso'] = '---';
						else $array[$posicion]['piso'] = $client_domicilio_piso;
						if(empty($client_domicilio_codigo_postal)) $array[$posicion]['codigopostal'] = '---';
						else $array[$posicion]['codigopostal'] = $client_domicilio_codigo_postal;
						if($client_preference_domicilio == 1) $preferenciaDom = translate('Lbl_Button_YES',$GLOBALS['lang']);
						else $preferenciaDom = translate('Lbl_Button_NO',$GLOBALS['lang']);
						$array[$posicion]['preferencia'] = $preferenciaDom;
						
						$array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Address',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Remove_Domicilio',$GLOBALS['lang']).'\',\''.$idCliente.'\',\''.$id_domicilio.'\')"><i class="fas fa-trash-alt"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Address',$GLOBALS['lang']).'" onclick="modificarDomicilio(\''.$idCliente.'\',\''.$id_domicilio.'\')"><i class="fas fa-edit"></i></button>';
						
						$posicion++;
					}
					
					echo translate('Msg_Add_Address_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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