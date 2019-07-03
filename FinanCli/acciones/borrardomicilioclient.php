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
		
		$idCliente=htmlspecialchars ( $_POST["idCliente"], ENT_QUOTES, 'UTF-8' );
		$idDomicilio=htmlspecialchars ( $_POST["id_domicilio"], ENT_QUOTES, 'UTF-8' );
		
		
				
		if($stmt = $mysqli->prepare("SELECT d.id, d.calle, d.nro_calle, p.id, d.localidad, d.departamento, d.piso, d.codigo_postal, d.entre_calle_1, d.entre_calle_2, cd.preferido, c.tipo_documento, c.documento FROM finan_cli.cliente c, finan_cli.domicilio d, finan_cli.cliente_x_domicilio cd, finan_cli.provincia p WHERE d.id_provincia = p.id AND c.id = ? AND c.tipo_documento = cd.tipo_documento AND c.documento = cd.documento AND d.id = cd.id_domicilio AND d.id = ?"))
		{
			$stmt->bind_param('ii', $idCliente, $idDomicilio);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				$stmt->free_result();
				$stmt->close();
				echo translate('Msg_Client_Or_Address_Not_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{
				$stmt->bind_result($id_domicilio_client, $client_dom_calle, $client_dom_nro_calle, $client_dom_provincia, $client_dom_localidad, $client_dom_departamento, $client_dom_piso, $client_dom_codigo_postal, $client_entre_calle_1, $client_entre_calle_2, $client_preference, $client_tipo_doc, $client_document);				
				$stmt->fetch();
				
				if($client_preference == 1)
				{
					echo translate('Msg_Can_Not_Delete_The_Preferred_Address',$GLOBALS['lang']);
					return;
				}
				
				if($stmt2 = $mysqli->prepare("SELECT d.id, d.calle, d.nro_calle, p.id, d.localidad, d.departamento, d.piso, d.codigo_postal, d.entre_calle_1, d.entre_calle_2, cd.preferido, c.tipo_documento, c.documento FROM finan_cli.cliente c, finan_cli.domicilio d, finan_cli.cliente_x_domicilio cd, finan_cli.provincia p WHERE d.id_provincia = p.id AND c.id = ? AND c.tipo_documento = cd.tipo_documento AND c.documento = cd.documento AND d.id = cd.id_domicilio"))
				$stmt2->bind_param('i', $idCliente);
				$stmt2->execute();    
				$stmt2->store_result();
			
				$totR2 = $stmt2->num_rows;
				if($totR2 == 1)
				{
					$stmt2->free_result();
					$stmt2->close();
					echo translate('Msg_Limit_Remove_Address',$GLOBALS['lang']);
					return;
				}
				$stmt2->free_result();
				$stmt2->close();
				
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
								
				if(!$stmt10 = $mysqli->prepare("DELETE FROM finan_cli.cliente_x_domicilio WHERE tipo_documento = ? AND documento = ? AND id_domicilio = ?"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$stmt10->bind_param('isi', $client_tipo_doc, $client_document, $idDomicilio);
					if(!$stmt10->execute())
					{
						echo $mysqli->error;
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;
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
						$stmt10->bind_param('i', $idDomicilio);
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
				$valor_log_user = "DELETE finan_cli.domicilio --> id: ".$id_domicilio_client." - Calle: ".$client_dom_calle." - Nro. Calle: ".$client_dom_nro_calle." - Provincia: ".$client_dom_provincia." - Localidad: ".$client_dom_localidad." - Departamento: ".(!empty($client_dom_departamento) ? "$client_dom_departamento" : "---")." - Piso: ".(!empty($client_dom_piso) ? "$client_dom_piso" : "---")." - Codigo Postal: ".(!empty($client_dom_codigo_postal) ? "$client_dom_codigo_postal" : "---")." - Entre Calle 1: ".(!empty($client_entre_calle_1) ? "$client_entre_calle_1" : "---")." - Entre Calle 2: ".(!empty($client_entre_calle_2) ? "$client_entre_calle_2" : "---");

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
					$motivo = 41;
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
					
					echo translate('Msg_Remove_Address_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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