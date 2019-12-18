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
		
		$idSucursal=htmlspecialchars($_POST["idSucursal"], ENT_QUOTES, 'UTF-8');
		
		$nombre=htmlspecialchars($_POST["nombre"], ENT_QUOTES, 'UTF-8');
		$codigo=htmlspecialchars($_POST["codigo"], ENT_QUOTES, 'UTF-8');
		$email=htmlspecialchars($_POST["email"], ENT_QUOTES, 'UTF-8');
		$cadena=htmlspecialchars($_POST["cadena"], ENT_QUOTES, 'UTF-8');

		if($cadena == -1) $cadena = "NULL";
		$email = !empty($email) ? "$email" : "---";
		
		$idDomicilio=htmlspecialchars($_POST["idDomicilio"], ENT_QUOTES, 'UTF-8');
		
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
		
		if(empty($idSucursal) || empty($idDomicilio))
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}	
		
		if($stmt = $mysqli->prepare("SELECT s.id, s.codigo, s.nombre, s.email, s.id_cadena, d.id, d.calle, d.nro_calle, p.nombre, d.localidad, d.departamento, d.piso, d.codigo_postal, d.entre_calle_1, d.entre_calle_2  FROM ".$db_name.".sucursal s, ".$db_name.".domicilio d, ".$db_name.".provincia p WHERE s.id_domicilio = d.id AND d.id_provincia = p.id AND s.codigo = ?"))
		{
			$stmt->bind_param('i', $codigo);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR > 1)
			{
				echo translate('Msg_Tender_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{				
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				if(!$stmt10 = $mysqli->prepare("UPDATE ".$db_name.".domicilio SET calle = ?, nro_calle = ?, id_provincia = ?, localidad = ?, departamento = ?, piso = ?, codigo_postal = ?, entre_calle_1 = ?, entre_calle_2 = ? WHERE id = ?"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$stmt10->bind_param('siississsi', $calle, $nroCalle, $provincia, $localidad, $departamento, $piso, $codigoPostal, $entreCalle1, $entreCalle2, $idDomicilio);
					if(!$stmt10->execute())
					{
						echo $mysqli->error;
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;						
					}
				}	

				$stmt->bind_result($id_tende_a, $codigo_tender_a, $nombre_tender_a, $email_tender_a, $cadena_tender_a, $id_domicilio_tender_a, $calle_tender_a, $nro_calle_tender_a, $provincia_tender_a, $localidad_ternder_a, $departamento_tender_a, $piso_tender_a, $codigo_postal_tender_a, $entre_calle1_tender_a, $entre_calle2_tender_a);
				$stmt->fetch();
				
				$date_registro = date("YmdHis");
				$date_registro2 = date("Y-m-d H:i:s");					
				$valor_log_user = "ANTERIOR: UPDATE ".$db_name.".domicilio SET calle = ".$calle_tender_a.", nro_calle = ".$nro_calle_tender_a.", provincia = ".$provincia_tender_a.", localidad = ".(!empty($localidad_ternder_a) ? "$localidad_ternder_a" : "---").", departamento = ".(!empty($departamento_tender_a) ? "$departamento_tender_a" : "---").", piso = ".(!empty($piso_tender_a) ? "$piso_tender_a" : "---").", codigo_postal = ".(!empty($codigo_postal_tender_a) ? "$codigo_postal_tender_a" : "---").", entre_calle_1 = ".(!empty($entre_calle1_tender_a) ? "$entre_calle1_tender_a" : "---").", entre_calle_2 = ".(!empty($entre_calle2_tender_a) ? "$entre_calle2_tender_a" : "---")." -- NUEVO: UPDATE ".$db_name.".domicilio SET calle = ".$calle.", nro_calle = ".$nroCalle.", provincia = ".$provincia.", localidad = ".$localidad.", departamento = ".$departamento.", piso = ".$piso.", codigo_postal = ".$codigoPostal.", entre_calle_1 = ".$entreCalle1.", entre_calle_2 = ".$entreCalle2;

				if(!$stmt12 = $mysqli->prepare("INSERT INTO ".$db_name.".log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
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
					$motivo = 23;
					$stmt12->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo, $valor_log_user);
					if(!$stmt12->execute())
					{
						echo $mysqli->error;
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;						
					}
				}				
				
				if($cadena == "NULL") $insertSucu = "UPDATE ".$db_name.".sucursal SET nombre = ?, codigo = ?, email = ?, id_domicilio = ?, id_cadena = NULL WHERE id = ?";
				else $insertSucu = "UPDATE ".$db_name.".sucursal SET nombre = ?, codigo = ?, email = ?, id_cadena = ?, id_domicilio = ? WHERE id = ?";
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
					if($cadena == "NULL") $stmt10->bind_param('sisii', $nombre, $codigo, $email, $idDomicilio, $idSucursal);
					else $stmt10->bind_param('sisiii', $nombre, $codigo, $email, $cadena, $idDomicilio, $idSucursal);
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
				$valor_log_user = "ANTERIOR: UPDATE ".$db_name.".sucursal SET id = ".$idSucursal.", nombre = ".$nombre_tender_a." codigo = ".$codigo_tender_a.", email = ".(!empty($email_tender_a) ? "$email_tender_a" : "---").", id_cadena = ".(!empty($cadena_tender_a) ? "$cadena_tender_a" : "---").", id_domicilio = ".$idDomicilio." -- NUEVO: UPDATE ".$db_name.".sucursal SET nombre = ".$nombre." codigo = ".$codigo.", email = ".$email.", id_cadena = ".$cadena.", id_domicilio = ".$idDomicilio;
					
				if(!$stmt = $mysqli->prepare("INSERT INTO ".$db_name.".log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
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
					$motivo = 24;
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
				
				if($stmt = $mysqli->prepare("SELECT s.id, s.codigo, s.nombre, c.razon_social FROM ".$db_name.".cadena c, ".$db_name.".sucursal s  WHERE c.id = s.id_cadena UNION  SELECT s.id, s.codigo, s.nombre, '".translate('Lbl_Select_Chain_Tender_None',$GLOBALS['lang'])."' FROM ".$db_name.".sucursal s WHERE s.id_cadena IS NULL ORDER BY 2"))
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
						
						$array[$posicion]['acciones'] = '<button type="button" id="borrarSucursal'.$id_tender.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Tender',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Removed_Tender',$GLOBALS['lang']).'\',\''.$id_tender.'\',\''.$nombre_tender.'\')"><i class="fas fa-trash-alt"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" id="modificarSucursal'.$id_tender.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Tender',$GLOBALS['lang']).'" onclick="modificarSucursal(\''.$id_tender.'\',\''.$codigo_tender.'\')"><i class="fas fa-edit"></i></button>';

						$posicion++;
					}
					
					echo translate('Msg_Modify_Tender_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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