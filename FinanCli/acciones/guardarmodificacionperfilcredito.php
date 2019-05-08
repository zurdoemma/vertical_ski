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
		
		$idPerfilCredito=htmlspecialchars($_POST["idPerfilCredito"], ENT_QUOTES, 'UTF-8');
		
		$nombre=htmlspecialchars($_POST["nombre"], ENT_QUOTES, 'UTF-8');
		$descripcion=htmlspecialchars($_POST["descripcion"], ENT_QUOTES, 'UTF-8');
		$montoMaximo=htmlspecialchars($_POST["montoMaximo"], ENT_QUOTES, 'UTF-8');
			
		if($stmt2 = $mysqli->prepare("SELECT pc.id FROM finan_cli.perfil_credito pc WHERE pc.nombre LIKE(?) AND pc.id <> ?"))
		{
			$stmt2->bind_param('si', $nombre, $idPerfilCredito);
			$stmt2->execute();    
			$stmt2->store_result();
		
			$totR = $stmt2->num_rows;

			if($totR > 0)
			{
				echo translate('Msg_A_Profile_Credit_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{					
				if($stmt = $mysqli->prepare("SELECT pc.id, pc.nombre, pc.descripcion, pc.monto_maximo FROM finan_cli.perfil_credito pc WHERE pc.id = ?"))
				{
					$stmt->bind_param('i', $idPerfilCredito);
					$stmt->execute();    
					$stmt->store_result();
					
					$totR2 = $stmt->num_rows;
					
					if($totR2 == 0)
					{
						echo translate('Msg_Profile_Credit_Not_Exist',$GLOBALS['lang']);
						return;
					}
					else
					{
						$mysqli->autocommit(FALSE);
						$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
						
						if(!$stmt10 = $mysqli->prepare("UPDATE finan_cli.perfil_credito SET nombre = ?, descripcion = ?, monto_maximo = ? WHERE id = ?"))
						{
							echo $mysqli->error;
							$mysqli->autocommit(TRUE);
							$stmt->free_result();
							$stmt->close();
							return;
						}
						else 
						{
							$stmt10->bind_param('ssii', $nombre, $descripcion, $montoMaximo, $idPerfilCredito);
							if(!$stmt10->execute())
							{
								echo $mysqli->error;
								$mysqli->autocommit(TRUE);
								$stmt->free_result();
								$stmt->close();
								return;						
							}						
						}

						$stmt->bind_result($id_perfil_credito_a, $name_perfil_credito_a, $descripcion_perfil_credito_a, $monto_maximo_perfil_credito_a);
						$stmt->fetch();
						$date_registro = date("YmdHis");
						$date_registro2 = date("Y-m-d H:i:s");					
						$valor_log_user = "ANTERIOR: UPDATE finan_cli.perfil_credito SET nombre = ".$name_perfil_credito_a.", descripcion = ".$descripcion_perfil_credito_a.", monto_maximo = ".$monto_maximo_perfil_credito_a." WHERE id = ".$id_perfil_credito_a." - NUEVO: UPDATE finan_cli.perfil_credito SET nombre = ".$nombre.", descripcion = ".$descripcion.", monto_maximo = ".$montoMaximo." WHERE id = ".$idPerfilCredito;

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
							$motivo = 26;
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
						
						if($stmt = $mysqli->prepare("SELECT pc.id, pc.nombre, pc.descripcion, pc.monto_maximo FROM finan_cli.perfil_credito pc ORDER BY pc.id"))
						{
							$stmt->execute();    
							$stmt->store_result();
							
							$stmt->bind_result($id_perfil_credito, $name_perfil_credito, $descripcion_perfil_credito, $monto_maximo_perfil_credito);
												
							$array[0] = array();
							$posicion = 0;
							while($stmt->fetch())
							{
								$array[$posicion]['nombre'] = $name_perfil_credito;
								$array[$posicion]['descripcion'] = $descripcion_perfil_credito;
								$array[$posicion]['montomaximo'] = '$'.number_format(($monto_maximo_perfil_credito/100.00),2);
								
								$array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Profile_Credit',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Removed_Profile_Credit',$GLOBALS['lang']).'\',\''.$id_perfil_credito.'\',\''.$name_perfil_credito.'\')"><i class="fas fa-trash-alt"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Profile_Credit',$GLOBALS['lang']).'" onclick="modificarPerfilCredito(\''.$id_perfil_credito.'\',\''.$name_perfil_credito.'\')"><i class="fas fa-edit"></i></button>';
								
								$posicion++;
							}
							
							echo translate('Msg_Modify_Profile_Credit_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
						}
						else 
						{
							echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
							return;	
						}

						$stmt2->free_result();
						$stmt2->close();						
						$stmt->free_result();
						$stmt->close();
						return;
					}
				}					
				else
				{
					$stmt2->free_result();
					$stmt2->close();
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;					
				}
			}

		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}
?>