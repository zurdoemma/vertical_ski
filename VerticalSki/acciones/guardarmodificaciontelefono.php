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
		
		$usuario=htmlspecialchars($_POST["usuario"], ENT_QUOTES, 'UTF-8');

		$idTelefono=htmlspecialchars($_POST["idTelefono"], ENT_QUOTES, 'UTF-8');
		$prefijoTelefono=htmlspecialchars($_POST["prefijoTelefono"], ENT_QUOTES, 'UTF-8');
		$nroTelefono=htmlspecialchars($_POST["nroTelefono"], ENT_QUOTES, 'UTF-8');
		$tipoTelefono=htmlspecialchars($_POST["tipoTelefono"], ENT_QUOTES, 'UTF-8');

		if($prefijoTelefono < 0 || $nroTelefono < 0)
		{
			echo translate('Negative_Numbers_Are_Not_Allowed',$GLOBALS['lang']);
			return;
		}
		
		if($stmt500 = $mysqli->prepare("SELECT c.id, u.id_perfil FROM ".$db_name.".cadena c, ".$db_name.".usuario u, ".$db_name.".sucursal s WHERE u.id_sucursal = s.id AND s.id_cadena = c.id AND u.id = ?")) 
		{
			$stmt500->bind_param('s', $_SESSION['username']);
			$stmt500->execute();    
			$stmt500->store_result();
	 
			$totR500 = $stmt500->num_rows;
			if($totR500 > 0)
			{
				$stmt500->bind_result($id_cadena_user, $id_perfil_usuario_logueado);
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

		if($id_perfil_usuario_logueado == 3)
		{
			if ($stmt531 = $mysqli->prepare("SELECT c.id FROM ".$db_name.".cadena c, ".$db_name.".usuario u, ".$db_name.".sucursal s WHERE u.id_sucursal = s.id AND s.id_cadena = c.id AND u.id = ?")) 
			{
				$stmt531->bind_param('s', $usuario);
				$stmt531->execute();    
				$stmt531->store_result();
		 
				$totR531 = $stmt531->num_rows;
				if($totR531 > 0)
				{
					$stmt531->bind_result($id_cadena_user_nuevo_telefono);
					$stmt531->fetch();

					$stmt531->free_result();
					$stmt531->close();				
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
			
			if($id_cadena_user != $id_cadena_user_nuevo_telefono)
			{
				echo translate('Msg_Action_Not_Allowed_User',$GLOBALS['lang']);
				return;			
			}
		}
		
		if($stmt = $mysqli->prepare("SELECT t.id, t.tipo_telefono, t.numero, t.digitos_prefijo FROM ".$db_name.".usuario u, ".$db_name.".telefono t, ".$db_name.".usuario_x_telefono ut WHERE ut.id_usuario = u.id AND ut.id_telefono = t.id AND u.id LIKE(?) AND t.id = ?"))
		{
			$stmt->bind_param('si', $usuario, $idTelefono);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_User_Or_Phone_Not_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{
				if($stmt4 = $mysqli->prepare("SELECT t.id FROM ".$db_name.".usuario u, ".$db_name.".telefono t, ".$db_name.".usuario_x_telefono ut WHERE u.id LIKE(?) AND ut.id_usuario = u.id AND ut.id_telefono = t.id AND t.numero = ?"))
				{
					$numTelFinCo = $prefijoTelefono.$nroTelefono;
					$stmt4->bind_param('si', $usuario, $numTelFinCo);
					$stmt4->execute();    
					$stmt4->store_result();
					
					$totR4 = $stmt4->num_rows;

					$stmt4->bind_result($id_telefono_user_con_r);				
					$stmt4->fetch();
					if($totR4 > 0 && $id_telefono_user_con_r != $idTelefono)
					{
						echo translate('Msg_Phone_Exist',$GLOBALS['lang']);
						return;
					}						
					
					$stmt4->free_result();
					$stmt4->close();
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
				
				if(!$stmt10 = $mysqli->prepare("UPDATE ".$db_name.".telefono SET tipo_telefono = ?, numero = ?, digitos_prefijo = ? WHERE id = ?"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$numTelFinU = $prefijoTelefono.$nroTelefono;
					$cantPrefiFN = strlen($prefijoTelefono);
					$stmt10->bind_param('iiii', $tipoTelefono, $numTelFinU, $cantPrefiFN, $idTelefono);
					if(!$stmt10->execute())
					{
						echo $mysqli->error;
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;
					}						
				}
	
				$date_registro = date("YmdHis");
				$date_registro2 = date("Y-m-d H:i:s");
				$stmt->bind_result($id_telefono_user, $user_tipo_telefono, $user_numero_telefono, $user_digitos_prefijo);				
				$stmt->fetch();
				$valor_log_user = "ANTERIOR: id = ".$id_telefono_user.", tipo_telefono = ".$user_tipo_telefono.", numero = ".$user_numero_telefono.", digitos_prefijo = ".$user_digitos_prefijo."  -- "."NUEVO: UPDATE ".$db_name.".telefono SET tipo_telefono = ".$tipoTelefono.", numero = ".$prefijoTelefono.$nroTelefono.", digitos_prefijo = ".strlen($prefijoTelefono)." WHERE id =".$idTelefono;

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
					$motivo = 12;
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
				
				if($stmt = $mysqli->prepare("SELECT t.id, tt.nombre, t.numero FROM ".$db_name.".telefono t, ".$db_name.".usuario u, ".$db_name.".tipo_telefono tt, ".$db_name.".usuario_x_telefono ut WHERE u.id LIKE(?) AND tt.id = t.tipo_telefono AND ut.id_usuario = u.id AND ut.id_telefono = t.id")) 
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
						
						$array[$posicion]['acciones'] = '<button type="button" id="btnBorrarTelefonoU'.$id_telefono.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Phone',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Remove_Telefono',$GLOBALS['lang']).'\',\''.$usuario.'\',\''.$id_telefono.'\')"><i class="fas fa-phone-slash"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" id="btnModificarTelefonoU'.$id_telefono.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Phone',$GLOBALS['lang']).'" onclick="modificarTelefono(\''.$usuario.'\',\''.$id_telefono.'\')"><i class="fas fa-phone-volume"></i></button>';
						
						$posicion++;
					}
					
					echo translate('Msg_Modify_Phone_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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