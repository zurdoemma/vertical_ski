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
		
		$idSucursal=htmlspecialchars ($_POST["idSucursal"], ENT_QUOTES, 'UTF-8');
				
		if($stmt = $mysqli->prepare("SELECT s.id, s.codigo, s.nombre, s.id_domicilio, s.email, s.id_cadena FROM ".$db_name.".sucursal s WHERE s.id = ?"))
		{
			$stmt->bind_param('i', $idSucursal);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				$stmt->free_result();
				$stmt->close();
				echo translate('Msg_Tender_Not_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{				
				if($stmt2 = $mysqli->prepare("SELECT c.id FROM ".$db_name.".cadena c, ".$db_name.".sucursal s WHERE c.id = s.id_cadena AND s.id = ?"))
				{
					$stmt2->bind_param('i', $idSucursal);
					$stmt2->execute();    
					$stmt2->store_result();
				
					$totR = $stmt2->num_rows;

					if($totR > 0)
					{
						$stmt2->free_result();
						$stmt2->close();
						echo translate('Msg_Tender_Not_Remove_Because_Associated_Chain',$GLOBALS['lang']);
						return;
					}
					
					$stmt2->free_result();
					$stmt2->close();
				}
				else	
				{
					$stmt->free_result();
					$stmt->close();
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;	
				}

				if($stmt2 = $mysqli->prepare("SELECT u.id FROM ".$db_name.".usuario u, ".$db_name.".sucursal s WHERE u.id_sucursal = s.id AND s.id = ?"))
				{
					$stmt2->bind_param('i', $idSucursal);
					$stmt2->execute();    
					$stmt2->store_result();
				
					$totR = $stmt2->num_rows;

					if($totR > 0)
					{
						$stmt2->free_result();
						$stmt2->close();
						echo translate('Msg_Tender_Not_Remove_Because_Associated_User',$GLOBALS['lang']);
						return;
					}
					
					$stmt2->free_result();
					$stmt2->close();
				}
				else	
				{
					$stmt->free_result();
					$stmt->close();
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;	
				}

				if($stmt2 = $mysqli->prepare("SELECT cc.id_credito FROM ".$db_name.".credito_cliente cc, ".$db_name.".sucursal s WHERE cc.id_sucursal = s.id_cadena AND s.id = ?"))
				{
					$stmt2->bind_param('i', $idSucursal);
					$stmt2->execute();    
					$stmt2->store_result();
				
					$totR = $stmt2->num_rows;

					if($totR > 0)
					{
						$stmt2->free_result();
						$stmt2->close();
						echo translate('Msg_Tender_Not_Remove_Because_Associated_Credit',$GLOBALS['lang']);
						return;
					}
					
					$stmt2->free_result();
					$stmt2->close();
				}
				else	
				{
					$stmt->free_result();
					$stmt->close();
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;	
				}					
				
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				

				$stmt->bind_result($id_sucursal, $codigo_sucursal, $nombre_sucursal, $domicilio_sucursal, $email_sucursal, $cadena_sucursal);
				$stmt->fetch();
								
				if($stmt11 = $mysqli->prepare("SELECT d.id, d.calle, d.nro_calle, p.nombre, d.localidad, d.departamento, d.piso, d.codigo_postal, d.entre_calle_1, d.entre_calle_2 FROM ".$db_name.".domicilio d, ".$db_name.".provincia p WHERE p.id = d.id_provincia AND d.id = ?"))
				{
					$stmt11->bind_param('i', $domicilio_sucursal);
					if(!$stmt11->execute())
					{
						echo $mysqli->error;
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;						
					}						
					$stmt11->store_result();
					
					$stmt11->bind_result($id_domicilio_s, $calle_domicilio_s, $nro_calle_domicilio_s, $provincia_domicilio_s, $localidad_domicilio_s, $departamento_domicilio_s, $piso_domiclio_s, $codigo_postal_domicilio_s, $entre_calle1_domicilio_s, $entre_calle2_domicilio_s);
					$stmt11->fetch();					
				}
				else
				{
					echo $mysqli->error;
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
								
				if(!$stmt10 = $mysqli->prepare("DELETE FROM ".$db_name.".sucursal WHERE id = ?"))
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
					$stmt10->bind_param('i', $idSucursal);
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
	
				$date_registro = date("YmdHis");
				$date_registro2 = date("Y-m-d H:i:s");					
				$valor_log_user = "DELETE ".$db_name.".sucursal --> id: ".$id_sucursal." - Codigo: ".$codigo_sucursal." - Nombre: ".$nombre_sucursal." - id_domicilio: ".$domicilio_sucursal." - Email: ".(!empty($email_sucursal) ? "$email_sucursal" : "---")." - Cadena: ".(!empty($cadena_sucursal) ? "$cadena_sucursal" : "---");

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
					$motivo = 20;
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

				if(!$stmt10 = $mysqli->prepare("DELETE FROM ".$db_name.".domicilio WHERE id = ?"))
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
					$stmt10->bind_param('i', $domicilio_sucursal);
					if(!$stmt10->execute())
					{
						echo $mysqli->error;
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;						
					}
					
					$date_registro = date("YmdHis");
					$date_registro2 = date("Y-m-d H:i:s");					
					$valor_log_user = "DELETE ".$db_name.".domicilio --> id: ".$id_domicilio_s." - Calle: ".$calle_domicilio_s." - Nro. Calle: ".$nro_calle_domicilio_s." - Provincia: ".$provincia_domicilio_s." - Localidad: ".$localidad_domicilio_s." - Departamento: ".(!empty($departamento_domicilio_s) ? "$departamento_domicilio_s" : "---")." - Piso: ".(!empty($piso_domiclio_s) ? "$piso_domiclio_s" : "---")." - Codigo Postal: ".(!empty($codigo_postal_domicilio_s) ? "$codigo_postal_domicilio_s" : "---")." - Entre Calle 1: ".(!empty($entre_calle1_domicilio_s) ? "$entre_calle1_domicilio_s" : "---")." - Entre Calle 2: ".(!empty($entre_calle2_domicilio_s) ? "$entre_calle2_domicilio_s" : "---");

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
						$motivo = 21;
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
					
					echo translate('Msg_Remove_Tender_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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