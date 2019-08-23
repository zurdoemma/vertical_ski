<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');return;}
		if (!verificar_permisos_supervisor()){header('Location:../sinautorizacion.php?activauto=1');return;}

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
		
		if ($stmt500 = $mysqli->prepare("SELECT c.id FROM finan_cli.cadena c, finan_cli.usuario u, finan_cli.sucursal s WHERE u.id_sucursal = s.id AND s.id_cadena = c.id AND u.id = ?")) 
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

		if ($stmt501 = $mysqli->prepare("SELECT s.id FROM finan_cli.sucursal s WHERE s.id = ? AND s.id_cadena = ?")) 
		{
			$stmt501->bind_param('ii', $idSucursal, $id_cadena_user);
			$stmt501->execute();    
			$stmt501->store_result();
	 
			$totR501 = $stmt501->num_rows;
			if($totR501 > 0)
			{
				$stmt501->bind_result($id_sucursal_valid_user);
				$stmt501->fetch();

				$stmt501->free_result();
				$stmt501->close();				
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
				
		if($stmt = $mysqli->prepare("SELECT s.id, s.codigo, s.nombre, s.id_domicilio, s.email, s.id_cadena FROM finan_cli.sucursal s WHERE s.id = ?"))
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
				if($stmt2 = $mysqli->prepare("SELECT u.id FROM finan_cli.usuario u, finan_cli.sucursal s WHERE u.id_sucursal = s.id AND s.id = ?"))
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

				if($stmt2 = $mysqli->prepare("SELECT cc.id_credito FROM finan_cli.credito_cliente cc, finan_cli.sucursal s WHERE cc.id_sucursal = s.id_cadena AND s.id = ?"))
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
								
				if($stmt11 = $mysqli->prepare("SELECT d.id, d.calle, d.nro_calle, p.nombre, d.localidad, d.departamento, d.piso, d.codigo_postal, d.entre_calle_1, d.entre_calle_2 FROM finan_cli.domicilio d, finan_cli.provincia p WHERE p.id = d.id_provincia AND d.id = ?"))
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
								
				if(!$stmt10 = $mysqli->prepare("DELETE FROM finan_cli.sucursal WHERE id = ?"))
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
				$valor_log_user = "DELETE finan_cli.sucursal --> id: ".$id_sucursal." - Codigo: ".$codigo_sucursal." - Nombre: ".$nombre_sucursal." - id_domicilio: ".$domicilio_sucursal." - Email: ".(!empty($email_sucursal) ? "$email_sucursal" : "---")." - Cadena: ".(!empty($cadena_sucursal) ? "$cadena_sucursal" : "---");

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

				if(!$stmt10 = $mysqli->prepare("DELETE FROM finan_cli.domicilio WHERE id = ?"))
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
					$valor_log_user = "DELETE finan_cli.domicilio --> id: ".$id_domicilio_s." - Calle: ".$calle_domicilio_s." - Nro. Calle: ".$nro_calle_domicilio_s." - Provincia: ".$provincia_domicilio_s." - Localidad: ".$localidad_domicilio_s." - Departamento: ".(!empty($departamento_domicilio_s) ? "$departamento_domicilio_s" : "---")." - Piso: ".(!empty($piso_domiclio_s) ? "$piso_domiclio_s" : "---")." - Codigo Postal: ".(!empty($codigo_postal_domicilio_s) ? "$codigo_postal_domicilio_s" : "---")." - Entre Calle 1: ".(!empty($entre_calle1_domicilio_s) ? "$entre_calle1_domicilio_s" : "---")." - Entre Calle 2: ".(!empty($entre_calle2_domicilio_s) ? "$entre_calle2_domicilio_s" : "---");

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
				
				if($stmt = $mysqli->prepare("SELECT s.id, s.codigo, s.nombre, c.razon_social FROM finan_cli.cadena c, finan_cli.sucursal s  WHERE c.id = s.id_cadena AND c.id = ?"))
				{
					$stmt->bind_param('i', $id_cadena_user);
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
						
						$array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Tender',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Removed_Tender',$GLOBALS['lang']).'\',\''.$id_tender.'\',\''.$nombre_tender.'\')"><i class="fas fa-trash-alt"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Tender',$GLOBALS['lang']).'" onclick="modificarSucursal(\''.$id_tender.'\',\''.$codigo_tender.'\')"><i class="fas fa-edit"></i></button>';

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