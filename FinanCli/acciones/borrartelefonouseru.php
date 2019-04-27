<?php 		
		include ('../utiles/funciones.php');
		sec_session_start();
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');}
		if (!verificar_permisos_usuario()){header('Location:../sinautorizacion.php');}

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
		$idTelefono=$_POST["id_telefono"];
		
		if($_SESSION['username'] != $usuario)
		{
			echo translate('Msg_Edit_User_Not_Match_User_Logged',$GLOBALS['lang']);
			return;						
		}			
				
		if($stmt = $mysqli->prepare("SELECT t.id, t.tipo_telefono, t.numero FROM finan_cli.usuario u, finan_cli.telefono t, finan_cli.usuario_x_telefono ut WHERE u.id LIKE(?) AND u.id = ut.id_usuario AND t.id = ut.id_telefono AND t.id = ?"))
		{
			$stmt->bind_param('si', $usuario, $idTelefono);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				$stmt->free_result();
				$stmt->close();
				echo translate('Msg_User_Not_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{				
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
								
				$stmt->bind_result($id_telefono_user, $user_tipo_telefono, $user_numero_telefono);
				
				if(!$mysqli->query("DELETE FROM finan_cli.usuario_x_telefono WHERE id_usuario = '".$usuario."' AND id_telefono = ".$idTelefono))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					if(!$mysqli->query("DELETE FROM finan_cli.telefono WHERE id = ".$idTelefono))
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
				$stmt->fetch();
				$valor_log_user = "DELETE finan_cli.telefono --> id: ".$id_telefono_user." - Tipo Telefono: ".$user_tipo_telefono." - Nro. Telefono: ".$user_numero_telefono;

				if(!$mysqli->query("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES ('".$_SESSION['username']."','$date_registro',11,'".$valor_log_user."')"))
				{
					echo $mysqli->error;
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
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