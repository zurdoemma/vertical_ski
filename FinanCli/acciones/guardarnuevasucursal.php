<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');return;}
		if (!verificar_permisos_admin()){header('Location:../sinautorizacion.php?activauto=1');return;}

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
		
		$nombre=htmlspecialchars($_POST["nombre"], ENT_QUOTES, 'UTF-8');
		$codigo=htmlspecialchars($_POST["codigo"], ENT_QUOTES, 'UTF-8');
		$email=htmlspecialchars($_POST["email"], ENT_QUOTES, 'UTF-8');
		$cadena=htmlspecialchars($_POST["cadena"], ENT_QUOTES, 'UTF-8');

		if($cadena == -1) $cadena = "NULL";
		$email = !empty($email) ? "$email" : "---";
		
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
				
		if($stmt = $mysqli->prepare("SELECT s.id FROM finan_cli.sucursal s WHERE s.codigo = ?"))
		{
			$stmt->bind_param('i', $codigo);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR > 0)
			{
				echo translate('Msg_Tender_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{				
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
					else $idDomicilioTender = $mysqli->insert_id;
				}	

				$date_registro = date("YmdHis");
				$date_registro2 = date("Y-m-d H:i:s");					
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
					$motivo = 4;
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
				
				if($cadena == "NULL") $insertSucu = "INSERT INTO finan_cli.sucursal (nombre,codigo,email,id_domicilio) VALUES(?,?,?,?)";
				else $insertSucu = "INSERT INTO finan_cli.sucursal (nombre,codigo,email,id_cadena,id_domicilio) VALUES(?,?,?,?,?)";
				if(!$stmt10 = $mysqli->prepare($insertSucu))
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
					if($cadena == "NULL") $stmt10->bind_param('sisi', $nombre, $codigo, $email, $idDomicilioTender);
					else $stmt10->bind_param('sisii', $nombre, $codigo, $email, $cadena, $idDomicilioTender);
					if(!$stmt10->execute())
					{
						echo $mysqli->error.$cadena;
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;						
					}
				}
	
				$date_registro = date("YmdHis");
				$date_registro2 = date("Y-m-d H:i:s");
				$valor_log_user = "INSERT INTO finan_cli.sucursal (nombre,codigo,email,id_cadena,id_domicilio) VALUES(".$nombre.",".$codigo.",".$email.",".$cadena.",".$idDomicilioTender.")";
					
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
					$motivo = 19;
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
				
				if($stmt = $mysqli->prepare("SELECT s.id, s.codigo, s.nombre, c.razon_social FROM finan_cli.cadena c, finan_cli.sucursal s  WHERE c.id = s.id_cadena UNION  SELECT s.id, s.codigo, s.nombre, '".translate('Lbl_Select_Chain_Tender_None',$GLOBALS['lang'])."' FROM finan_cli.sucursal s WHERE s.id_cadena IS NULL ORDER BY 2"))
				{
					$stmt->execute();    
					$stmt->store_result();
					
					$stmt->bind_result($id_tender, $codigo_tender, $nombre_tender, $nombre_cadena_tender);
										
					$array[0] = array();
					$posicion = 0;
					while($stmt->fetch())
					{
						$array[$posicion]['codigo'] = $codigo_tender;
						$array[$posicion]['nombre'] = $nombre_tender;
						$array[$posicion]['cadena'] = $nombre_cadena_tender;
						
						$array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Tender',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Removed_Tender',$GLOBALS['lang']).'\',\''.$id_tender.'\',\''.$nombre_tender.'\')"><i class="fas fa-trash-alt"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Tender',$GLOBALS['lang']).'" onclick="modificarCadena(\''.$id_tender.'\',\''.$codigo_tender.'\')"><i class="fas fa-edit"></i></button>';

						$posicion++;
					}
					
					echo translate('Msg_New_Tender_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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