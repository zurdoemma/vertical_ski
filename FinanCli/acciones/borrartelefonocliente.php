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
		$idTelefono=htmlspecialchars($_POST["idTelefono"], ENT_QUOTES, 'UTF-8');
				
		if($stmt = $mysqli->prepare("SELECT t.id, t.tipo_telefono, t.numero, c.tipo_documento, c.documento, ct.preferido  FROM finan_cli.cliente c, finan_cli.telefono t, finan_cli.cliente_x_telefono ct WHERE c.id = ? AND c.tipo_documento = ct.tipo_documento AND c.documento = ct.documento AND t.id = ct.id_telefono AND t.id = ?"))
		{
			$stmt->bind_param('ii', $idCliente, $idTelefono);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				$stmt->free_result();
				$stmt->close();
				echo translate('Msg_Client_Or_Phone_Not_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{				
				$stmt->bind_result($id_telefono_client, $client_tipo_telefono, $client_numero_telefono, $client_tipo_documento, $client_documento, $client_preference);
				$stmt->fetch();
				
				if($client_preference == 1)
				{
					echo translate('Msg_Can_Not_Delete_The_Preferred_Address',$GLOBALS['lang']);
					return;
				}
				
				if($stmt2 = $mysqli->prepare("SELECT t.id, t.tipo_telefono, t.numero, c.tipo_documento, c.documento, ct.preferido  FROM finan_cli.cliente c, finan_cli.telefono t, finan_cli.cliente_x_telefono ct WHERE c.id = ? AND c.tipo_documento = ct.tipo_documento AND c.documento = ct.documento AND t.id = ct.id_telefono"))
				{
					$stmt2->bind_param('i', $idCliente);
					$stmt2->execute();    
					$stmt2->store_result();
				
					$totR2 = $stmt2->num_rows;

					if($totR2 == 1)
					{
						$stmt2->free_result();
						$stmt2->close();
						echo translate('Msg_Limit_Remove_Phone',$GLOBALS['lang']);
						return;
					}
				}
				else
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;	
				}
				
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
								
				
				if(!$stmt10 = $mysqli->prepare("DELETE FROM finan_cli.cliente_x_telefono WHERE tipo_documento = ? AND documento = ? AND id_telefono = ?"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$stmt10->bind_param('isi', $client_tipo_documento, $client_documento, $idTelefono);
					if(!$stmt10->execute())
					{
						echo $mysqli->error;
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;						
					}
					
					if(!$stmt10 = $mysqli->prepare("DELETE FROM finan_cli.telefono WHERE id = ?"))
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
						$stmt10->bind_param('i', $idTelefono);
						if(!$stmt10->execute())
						{
							echo $mysqli->error;
							$mysqli->autocommit(TRUE);
							$stmt->free_result();
							$stmt->close();
							return;						
						}						
					}
				}	

				$date_registro = date("YmdHis");
				$date_registro2 = date("Y-m-d H:i:s");					
				$valor_log_user = "DELETE finan_cli.telefono --> id: ".$id_telefono_client." - Tipo Telefono: ".$client_tipo_telefono." - Nro. Telefono: ".$client_numero_telefono. " - Preferido: ".$client_preference;

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
					$motivo = 44;
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
				
				if($stmt = $mysqli->prepare("SELECT t.id, tt.nombre, t.numero, ct.preferido FROM finan_cli.telefono t, finan_cli.cliente c, finan_cli.tipo_telefono tt, finan_cli.cliente_x_telefono ct WHERE c.id = ? AND tt.id = t.tipo_telefono AND ct.tipo_documento = c.tipo_documento AND ct.documento = c.documento AND ct.id_telefono = t.id")) 
				{
					$stmt->bind_param('i', $idCliente);
					$stmt->execute();
					$stmt->store_result();
			 
					$stmt->bind_result($id_telefono, $client_tipo_telefono, $client_numero_telefono, $client_preference_telefono_c);
										
					$array[0] = array();
					$posicion = 0;
					while($stmt->fetch())
					{
						$array[$posicion]['tipotelefono'] = $client_tipo_telefono;
						$array[$posicion]['nrotelefono'] = $client_numero_telefono;
						if($client_preference_telefono_c == 1) $array[$posicion]['preferencia'] = translate('Lbl_Button_YES',$GLOBALS['lang']);
						else $array[$posicion]['preferencia'] = translate('Lbl_Button_NO',$GLOBALS['lang']);
						
						$array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Phone',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Remove_Telefono',$GLOBALS['lang']).'\',\''.$idCliente.'\',\''.$id_telefono.'\')"><i class="fas fa-phone-slash"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Phone',$GLOBALS['lang']).'" onclick="modificarTelefono(\''.$idCliente.'\',\''.$id_telefono.'\')"><i class="fas fa-phone-volume"></i></button>';
						
						$posicion++;
					}
					
					echo translate('Msg_Remove_Phone_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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