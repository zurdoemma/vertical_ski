<?php 		
		include ('../utiles/funciones.php');
		sec_session_start();
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');}
		if (!verificar_permisos_usuario()){header('Location:../sinautorizacion.php?activauto=1');}

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
		
		$usuario=htmlspecialchars($_POST["usuario"], ENT_QUOTES, 'UTF-8');
		
		if($_SESSION['username'] != $usuario)
		{
			echo translate('Msg_Edit_User_Not_Match_User_Logged',$GLOBALS['lang']);
			return;						
		}		

		$prefijoTelefono=htmlspecialchars($_POST["prefijoTelefono"], ENT_QUOTES, 'UTF-8');
		$nroTelefono=htmlspecialchars($_POST["nroTelefono"], ENT_QUOTES, 'UTF-8');
		$tipoTelefono=htmlspecialchars($_POST["tipoTelefono"], ENT_QUOTES, 'UTF-8');
			
		if($stmt = $mysqli->prepare("SELECT u.id FROM finan_cli.usuario u WHERE u.id LIKE(?)"))
		{
			$stmt->bind_param('s', $usuario);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_User_Not_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{
				if($stmt3 = $mysqli->prepare("SELECT count(t.id) FROM finan_cli.usuario u, finan_cli.telefono t, finan_cli.usuario_x_telefono ut WHERE u.id LIKE(?) AND ut.id_usuario = u.id AND ut.id_telefono = t.id"))
				{
					$stmt3->bind_param('s', $usuario);
					$stmt3->execute();    
					$stmt3->store_result();
					
					$stmt3->bind_result($cantidad_telefonos);
					$stmt3->fetch();
					
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
					$stmt10->bind_param('iii', $tipoTelefono, $prefijoTelefono.$nroTelefonom, strlen($prefijoTelefono));
					if(!$stmt10->execute())
					{
						echo $mysqli->error;
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;
					}						
					
					if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.usuario_x_telefono(id_usuario,id_telefono) VALUES (?,?)"))
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
						$stmt10->bind_param('si', $usuario, $mysqli->insert_id);
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
				$valor_log_user = "INSERT INTO finan_cli.telefono(tipo_telefono,numero,digitos_prefijo) VALUES (".$tipoTelefono.",".$prefijoTelefono.$nroTelefono.",".strlen($prefijoTelefono).")";

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
					$motivo = 10;
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
				
				if($stmt = $mysqli->prepare("SELECT t.id, tt.nombre, t.numero FROM finan_cli.telefono t, finan_cli.usuario u, finan_cli.tipo_telefono tt, finan_cli.usuario_x_telefono ut WHERE u.id LIKE(?) AND tt.id = t.tipo_telefono AND ut.id_usuario = u.id AND ut.id_telefono = t.id")) 
				{
					$stmt->bind_param('s', $usuario);
					$stmt->execute();    
					$stmt->store_result();
					
					$stmt->bind_result($id_telefono, $user_tipo_telefono, $user_numero_telefono);
										
					$array[0] = array();
					$posicion = 0;
					while($stmt->fetch())
					{
						$array[$posicion]['tipotelefono'] = $user_tipo_telefono;
						$array[$posicion]['nrotelefono'] = $user_numero_telefono;
						
						$array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Phone',$GLOBALS['lang']).'" onclick="confirmar_accion_2(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Remove_Telefono',$GLOBALS['lang']).'\',\''.$usuario.'\',\''.$id_telefono.'\')"><i class="fas fa-phone-slash"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Phone',$GLOBALS['lang']).'" onclick="modificarTelefono(\''.$usuario.'\',\''.$id_telefono.'\')"><i class="fas fa-phone-volume"></i></button>';
						
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