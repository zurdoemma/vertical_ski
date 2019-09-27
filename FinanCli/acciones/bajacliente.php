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

		if($stmt = $mysqli->prepare("SELECT id, estado, id_titular FROM finan_cli.cliente WHERE id = ?"))
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
				$stmt->bind_result($id_client_db, $state_client_db, $id_titular_cliente_db);
				$stmt->fetch();
				
				if(!empty($id_titular_cliente_db) && $state_client_db == translate('State_User_Disabled',$GLOBALS['lang']))
				{
					if($stmt10 = $mysqli->prepare("SELECT id, estado FROM finan_cli.cliente WHERE id = ?"))
					{
						$stmt10->bind_param('i', $id_titular_cliente_db);
						$stmt10->execute();    
						$stmt10->store_result();
					
						$totR10 = $stmt10->num_rows;

						if($totR10 == 0)
						{
							echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
							return;	
						}
						else
						{
							$stmt10->bind_result($id_client_tit_db, $state_client_tit_db);
							$stmt10->fetch();
							
							if($state_client_tit_db == translate('State_User_Disabled',$GLOBALS['lang']))
							{
								echo translate('Can_Not_Enable_An_Additional_Client_That_Has_The_Holder_Disabled',$GLOBALS['lang']);
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

				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				$habOD = 0;
				if($state_client_db == translate('State_User_Disabled',$GLOBALS['lang']))
				{
					$updateF = "UPDATE finan_cli.cliente SET estado = '".translate('State_User',$GLOBALS['lang'])."' WHERE id = '".$id_client_db."'";
					$habOD = 1;
					
					if(empty($id_titular_cliente_db))
					{
						$updateFA = "UPDATE finan_cli.cliente SET estado = '".translate('State_User',$GLOBALS['lang'])."' WHERE id_titular = '".$id_client_db."'";
					}
				}
				else 
				{
					$updateF = "UPDATE finan_cli.cliente SET estado = '".translate('State_User_Disabled',$GLOBALS['lang'])."' WHERE id = '".$id_client_db."'";
				
					if(empty($id_titular_cliente_db))
					{
						$updateFA = "UPDATE finan_cli.cliente SET estado = '".translate('State_User_Disabled',$GLOBALS['lang'])."' WHERE id_titular = '".$id_client_db."'";
					}
				}
				if(!$mysqli->query($updateF))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					if(empty($id_titular_cliente_db))
					{
						if(!$mysqli->query($updateFA))
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

				if($habOD == 0)
				{						
					$valor_log_user = translate('Msg_Disabled_For_Client_%1_to_%2_for_user_%3',$GLOBALS['lang']);
					$valor_log_user = str_replace("%1",$idCliente,$valor_log_user);
					$valor_log_user = str_replace("%2",$date_registro2,$valor_log_user);
					$valor_log_user = str_replace("%3",$_SESSION['username'],$valor_log_user);
					
					if(empty($id_titular_cliente_db)) 
					{
						$insertLEUF = "INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,46,?)";
					}
					else $insertLEUF = "INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,48,?)";
				}
				else
				{
					$valor_log_user = translate('Msg_Enabled_For_Client_%1_to_%2_for_user_%3',$GLOBALS['lang']);
					$valor_log_user = str_replace("%1",$idCliente,$valor_log_user);
					$valor_log_user = str_replace("%2",$date_registro2,$valor_log_user);
					$valor_log_user = str_replace("%3",$_SESSION['username'],$valor_log_user);	

					if(empty($id_titular_cliente_db)) 
					{
						$insertLEUF = "INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,47,?)";
					}
					else $insertLEUF = "INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,49,?)";
				}

				if(!$stmt = $mysqli->prepare($insertLEUF))
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
					$stmt->bind_param('sss', $_SESSION['username'], $date_registro, $valor_log_user);
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
									if($state_client == translate('State_User',$GLOBALS['lang'])) $array[$posicion]['acciones'] = '<button type="button" id="desactivarCliente'.$id_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Deactivate_Client',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Deactivate_Client',$GLOBALS['lang']).'\',\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-times"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" id="btnModificarCliente'.$id_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Client',$GLOBALS['lang']).'" onclick="modificarCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-cog"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" id="btnVerAdicionalesCliente'.$id_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_See_Additional_Client',$GLOBALS['lang']).'" onclick="verAdicionalesCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-eye"></i></button>';
									else $array[$posicion]['acciones'] = '<button type="button" id="activarCliente'.$id_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Activate_Client',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Activate_Client',$GLOBALS['lang']).'\',\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-check"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" id="btnModificarCliente'.$id_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Client',$GLOBALS['lang']).'" onclick="modificarCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-cog"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" id="btnVerAdicionalesCliente'.$id_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_See_Additional_Client',$GLOBALS['lang']).'" onclick="verAdicionalesCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-eye"></i></button>';									
								}
								else
								{
									if($state_client == translate('State_User',$GLOBALS['lang'])) $array[$posicion]['acciones'] = '<button type="button" id="desactivarCliente'.$id_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Deactivate_Client',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Deactivate_Client',$GLOBALS['lang']).'\',\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-times"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" id="btnModificarCliente'.$id_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Client',$GLOBALS['lang']).'" onclick="modificarCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-cog"></i></button>';
									else $array[$posicion]['acciones'] = '<button type="button" id="activarCliente'.$id_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Activate_Client',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Activate_Client',$GLOBALS['lang']).'\',\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-check"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" id="btnModificarCliente'.$id_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Client',$GLOBALS['lang']).'" onclick="modificarCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-cog"></i></button>';												
								}
							}
							
							$stmt90->free_result();
							$stmt90->close();
						}
						else
						{
							if($state_client == translate('State_User',$GLOBALS['lang'])) $array[$posicion]['acciones'] = '<button type="button" id="desactivarCliente'.$id_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Deactivate_Client',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Deactivate_Client',$GLOBALS['lang']).'\',\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-times"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" id="btnModificarCliente'.$id_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Client',$GLOBALS['lang']).'" onclick="modificarCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-cog"></i></button>';
							else $array[$posicion]['acciones'] = '<button type="button" id="activarCliente'.$id_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Activate_Client',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Activate_Client',$GLOBALS['lang']).'\',\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-check"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" id="btnModificarCliente'.$id_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Client',$GLOBALS['lang']).'" onclick="modificarCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-cog"></i></button>';
						}
						$posicion++;
					}
				}
				else 
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;	
				}						
				
				if($habOD == 0) echo translate('Msg_Disabled_Client_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
				else echo translate('Msg_Enabled_Client_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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