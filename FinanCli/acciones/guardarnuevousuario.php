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
		
		$usuario=htmlspecialchars($_POST["usuario"], ENT_QUOTES, 'UTF-8');

		$nombre=htmlspecialchars($_POST["nombre"], ENT_QUOTES, 'UTF-8');
		$apellido=htmlspecialchars($_POST["apellido"], ENT_QUOTES, 'UTF-8');
		$tipoDocumento=htmlspecialchars($_POST["tipoDocumento"], ENT_QUOTES, 'UTF-8');
		$documento=htmlspecialchars($_POST["documento"], ENT_QUOTES, 'UTF-8');
		$email=htmlspecialchars($_POST["email"], ENT_QUOTES, 'UTF-8');
		$perfil=htmlspecialchars($_POST["perfil"], ENT_QUOTES, 'UTF-8');
		$sucursal=htmlspecialchars($_POST["sucursal"], ENT_QUOTES, 'UTF-8');
		$nclaveu=htmlspecialchars($_POST["claveu"], ENT_QUOTES, 'UTF-8');
		
		$calle=htmlspecialchars($_POST["calle"], ENT_QUOTES, 'UTF-8');
		$nroCalle=htmlspecialchars($_POST["nroCalle"], ENT_QUOTES, 'UTF-8');
		$provincia=htmlspecialchars($_POST["provincia"], ENT_QUOTES, 'UTF-8');
		$localidad=htmlspecialchars($_POST["localidad"], ENT_QUOTES, 'UTF-8');
		$departamento=htmlspecialchars($_POST["departamento"], ENT_QUOTES, 'UTF-8');
		$piso=htmlspecialchars($_POST["piso"], ENT_QUOTES, 'UTF-8');
		$codigoPostal=htmlspecialchars($_POST["codigoPostal"], ENT_QUOTES, 'UTF-8');
		$entreCalle1=htmlspecialchars($_POST["entreCalle1"], ENT_QUOTES, 'UTF-8');
		$entreCalle2=htmlspecialchars($_POST["entreCalle2"], ENT_QUOTES, 'UTF-8');
		
		$departamento = !empty($departamento) ? "$departamento" : "---";
		$piso = !empty($piso) ? "$piso" : "NULL";
		$codigoPostal = !empty($codigoPostal) ? "$codigoPostal" : "---";
		$entreCalle1 = !empty($entreCalle1) ? "$entreCalle1" : "---";
		$entreCalle2 = !empty($entreCalle2) ? "$entreCalle2" : "---";		
				
		if($stmt = $mysqli->prepare("SELECT u.id FROM finan_cli.usuario u WHERE u.id LIKE(?)"))
		{
			$stmt->bind_param('s', $usuario);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR > 0)
			{
				echo translate('Msg_User_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{				
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.domicilio(calle,nro_calle,id_provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2) VALUES (?,?,?,?,?,?,?,?,?)"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$stmt10->bind_param('siississs', $calle, $nroCalle, $provincia, $localidad, $departamento, $piso, $codigoPostal, $entreCalle1, $entreCalle2);
					if(!$stmt10->execute())
					{
						echo $mysqli->error;
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;						
					}
					else $idDomicilioUser = $mysqli->insert_id;
				}	

				$date_registro = date("YmdHis");
				$date_registro2 = date("Y-m-d H:i:s");					
				$valor_log_user = "INSERT INTO finan_cli.domicilio(calle,nro_calle,provincia,localidad,departamento,piso,codigo_postal,entre_calle_1,entre_calle_2) VALUES (".$calle.",".$nroCalle.",".$provincia.",".$localidad.",".str_replace('\'','',$departamento).",".$piso.",".str_replace('\'','',$codigoPostal).",".str_replace('\'','',$entreCalle1).",".str_replace('\'','',$entreCalle2).")";

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
					$motivo = 4;
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
								
				if (strlen($nclaveu) != 128)
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;	
				}
				
				$saltu = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
				$clavefu = hash('sha512', $nclaveu . $saltu);	
				
				if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.usuario (id,nombre,apellido,tipo_documento,documento,email,id_perfil,id_sucursal,estado,clave,salt) VALUES(?,?,?,?,?,?,?,?,?,?,?)"))
				{
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$estUI = translate('State_User',$GLOBALS['lang']);
					$stmt10->bind_param('sssissiisss', $usuario, $nombre, $apellido, $tipoDocumento, $documento, $email, $perfil, $sucursal, $estUI, $clavefu, $saltu);
					if(!$stmt10->execute())
					{
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;						
					}
					
					if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.usuario_x_domicilio(id_usuario,id_domicilio) VALUES (?,?)"))
					{
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;
					}
					else
					{
						$stmt10->bind_param('si', $usuario, $idDomicilioUser);
						if(!$stmt10->execute())
						{	
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
				$valor_log_user = "INSERT INTO finan_cli.usuario (id,nombre,apellido,tipo_documento,documento,email,id_perfil,id_sucursal,estado) VALUES(".$usuario.",".$nombre.",".$apellido.",".$tipoDocumento.",".$documento.",".$email.",".$perfil.",".$sucursal.",".translate('State_User',$GLOBALS['lang']).")";
					
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
					$motivo = 8;
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
							else $array[$posicion]['acciones'] = '<button class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Enable_User',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Disabled_User',$GLOBALS['lang']).'\',\''.$id_user.'\')"><i class="fas fa-user-plus"></i></button>&nbsp;&nbsp;&nbsp;<button class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_User',$GLOBALS['lang']).'" onclick="modificarUsuario(\''.$id_user_a.'\')"><i class="fas fa-user-edit"></i></button>';
						}						
						else $array[$posicion]['acciones'] = '---';
						
						$posicion++;
					}
					
					echo translate('Msg_New_User_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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