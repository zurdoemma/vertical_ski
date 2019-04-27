<?php 		
		include ('../utiles/funciones.php');
		sec_session_start();
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');}
		if (!verificar_permisos_admin()){header('Location:../sinautorizacion.php');}

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
		
		$usuario=$_POST["usuario"];	

		if($stmt = $mysqli->prepare("SELECT id, estado FROM finan_cli.usuario WHERE id LIKE(?)"))
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
				$stmt->bind_result($id_user, $state_user_db);
				$stmt->fetch();
				if($id_user == $_SESSION['username'])
				{
					echo translate('Msg_The_Requested_Action_Can_Not_Be_Performed_On_The_Same_User_Logged_In',$GLOBALS['lang']);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$mysqli->autocommit(FALSE);
					$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
					
					$habOD = 0;
					if($state_user_db == translate('State_User_Disabled',$GLOBALS['lang']))
					{
						$updateF = "UPDATE finan_cli.usuario SET estado = '".translate('State_User',$GLOBALS['lang'])."' WHERE id = '".$id_user."'";
						$habOD = 1;
					}
					else $updateF = "UPDATE finan_cli.usuario SET estado = '".translate('State_User_Disabled',$GLOBALS['lang'])."' WHERE id = '".$id_user."'";
						
					if(!$mysqli->query($updateF))
					{
						
						echo $mysqli->error;
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;
					}					

					$date_registro = date("YmdHis");
					$date_registro2 = date("Y-m-d H:i:s");

					if($habOD == 0)
					{						
						$valor_log_user = translate('Msg_Disabled_For_User_%1_to_%2_for_user_%3',$GLOBALS['lang']);
						$valor_log_user = str_replace("%1",$usuario,$valor_log_user);
						$valor_log_user = str_replace("%2",$date_registro2,$valor_log_user);
						$valor_log_user = str_replace("%3",$_SESSION['username'],$valor_log_user);
						
						$insertLEUF = "INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES ('".$_SESSION['username']."','$date_registro',3,'".$valor_log_user."')";
					}
					else
					{
						$valor_log_user = translate('Msg_Enabled_For_User_%1_to_%2_for_user_%3',$GLOBALS['lang']);
						$valor_log_user = str_replace("%1",$usuario,$valor_log_user);
						$valor_log_user = str_replace("%2",$date_registro2,$valor_log_user);
						$valor_log_user = str_replace("%3",$_SESSION['username'],$valor_log_user);	

						$insertLEUF = "INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES ('".$_SESSION['username']."','$date_registro',9,'".$valor_log_user."')";						
					}

					if(!$mysqli->query($insertLEUF))
					{
						echo $mysqli->error;
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;
					}
											
					$mysqli->commit();
					$mysqli->autocommit(TRUE);
					
					if($stmt = $mysqli->prepare("SELECT u.id, u.nombre, u.apellido, u.documento, p.nombre, s.nombre, u.estado FROM finan_cli.usuario u, finan_cli.tipo_documento td, finan_cli.perfil p, finan_cli.sucursal s WHERE u.tipo_documento = td.id AND u.id_perfil = p.id AND u.id_sucursal = s.id"))
					{
						$stmt->execute();    
						$stmt->store_result();
						
						$stmt->bind_result($id_user_a, $user_name_a, $user_surname_a, $user_document_a, $user_perfil_a, $user_sucursal_a, $user_state_a);				
											
						$array[0] = array();
						$posicion = 0;
						while($stmt->fetch())
						{
							$array[$posicion]['usuario'] = $id_user_a;
							$array[$posicion]['nombre'] = $user_name_a;
							$array[$posicion]['apellido'] = $user_surname_a;
							$array[$posicion]['documento'] = $user_document_a;
							$array[$posicion]['perfil'] = $user_perfil_a;
							$array[$posicion]['sucursal'] = $user_sucursal_a;
							$array[$posicion]['estado'] = $user_state_a;
							
							if($id_user_a != 'admin_sys')
							{
								if($user_state_a == translate('State_User',$GLOBALS['lang'])) $array[$posicion]['acciones'] = '<button class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Lbl_Disable_User',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Disabled_User',$GLOBALS['lang']).'\',\''.$id_user.'\')"><i class="fas fa-user-slash"></i></button>&nbsp;&nbsp;&nbsp;<button class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_User',$GLOBALS['lang']).'" onclick="modificarUsuario(\''.$id_user_a.'\')"><i class="fas fa-user-edit"></i></button>';
								else $array[$posicion]['acciones'] = '<button class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Enable_User',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Enabled_User',$GLOBALS['lang']).'\',\''.$id_user.'\')"><i class="fas fa-user-check"></i></button>&nbsp;&nbsp;&nbsp;<button class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_User',$GLOBALS['lang']).'" onclick="modificarUsuario(\''.$id_user_a.'\')"><i class="fas fa-user-edit"></i></button>';
							}						
							else $array[$posicion]['acciones'] = '---';
							
							$posicion++;
						}
					}						
					
					if($habOD == 0) echo translate('Msg_Disabled_User_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
					else echo translate('Msg_Enabled_User_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
					$stmt->free_result();
					$stmt->close();
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