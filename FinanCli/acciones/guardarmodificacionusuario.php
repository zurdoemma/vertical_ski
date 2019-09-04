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
		
		$horarioIngreso=htmlspecialchars($_POST["horarioIngreso"], ENT_QUOTES, 'UTF-8');
		$horarioEgreso=htmlspecialchars($_POST["horarioEgreso"], ENT_QUOTES, 'UTF-8');
		$trabLunes=htmlspecialchars($_POST["trabLunes"], ENT_QUOTES, 'UTF-8');
		$trabMartes=htmlspecialchars($_POST["trabMartes"], ENT_QUOTES, 'UTF-8');
		$trabMiercoles=htmlspecialchars($_POST["trabMiercoles"], ENT_QUOTES, 'UTF-8');
		$trabJueves=htmlspecialchars($_POST["trabJueves"], ENT_QUOTES, 'UTF-8');
		$trabViernes=htmlspecialchars($_POST["trabViernes"], ENT_QUOTES, 'UTF-8');
		$trabSabado=htmlspecialchars($_POST["trabSabado"], ENT_QUOTES, 'UTF-8');
		$trabDomingo=htmlspecialchars($_POST["trabDomingo"], ENT_QUOTES, 'UTF-8');
		
		if($perfil == 2 && (empty($horarioIngreso) || empty($horarioEgreso) || ($trabLunes == 'false' && $trabMartes == 'false' && $trabMiercoles == 'false' && $trabJueves == 'false' && $trabViernes == 'false' && $trabSabado == 'false' && $trabDomingo == 'false')))
		{
			//echo $perfil.' - '.$horarioIngreso.' - '.$horarioEgreso.' - '.$trabLunes.' - '.$trabMartes.' - '.$trabMiercoles.' - '.$trabJueves.' - '.$trabViernes.' - '.$trabSabado.' - '.$trabDomingo;
			echo translate('Msg_You_Must_Correctly_Load_The_User_Work_Schedule',$GLOBALS['lang']);
			return;				
		}		
				
		if($stmt = $mysqli->prepare("SELECT u.id, u.nombre, u.apellido, td.nombre, u.documento, u.email, p.nombre, s.nombre, u.clave, u.salt FROM finan_cli.usuario u, finan_cli.tipo_documento td, finan_cli.perfil p, finan_cli.sucursal s WHERE u.tipo_documento = td.id AND u.id_perfil = p.id AND u.id_sucursal = s.id AND u.id LIKE(?)"))
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
				$stmt->bind_result($id_user, $user_name, $user_surname, $user_type_document, $user_document, $user_email, $user_perfil, $user_sucursal, $user_clave, $user_salt);				
				$stmt->fetch();
				
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				$clavefu = '';
				$saltu = '';
				
				if(!empty($nclaveu))
				{
					if (strlen($nclaveu) != 128)
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;	
					}
					
					$saltu = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
					$clavefu = hash('sha512', $nclaveu . $saltu);
				}	
				else
				{
					$clavefu = $user_clave;
					$saltu = $user_salt;
				}
				
				if(!$stmt10 = $mysqli->prepare("UPDATE finan_cli.usuario SET nombre = ?, apellido = ?, tipo_documento = ?, documento = ?, email = ?, id_perfil = ?, id_sucursal = ?, clave = ?, salt = ? WHERE id = ?"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$stmt10->bind_param('ssissiisss', $nombre, $apellido, $tipoDocumento, $documento, $email, $perfil, $sucursal, $clavefu, $saltu, $usuario);
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
				if(empty($nclaveu)) $valor_log_user = "ANTERIOR: id = ".$id_user.", nombre = ".$user_name.", apellido = ".$user_surname.", tipo_documento = ".$user_type_document.", documento = ".$user_document.", email = ".$user_email.", perfil = ".$user_perfil.", sucursal = ".$user_sucursal."  -- "."NUEVO: UPDATE finan_cli.usuario SET nombre = ".$nombre.", apellido = ".$apellido.", tipo_documento = ".$tipoDocumento.", documento = ".$documento.", email = ".$email.", id_perfil = ".$perfil.", id_sucursal = ".$sucursal." WHERE id =".$usuario;
				else $valor_log_user = "ANTERIOR: id = ".$id_user.", nombre = ".$user_name.", apellido = ".$user_surname.", tipo_documento = ".$user_type_document.", documento = ".$user_document.", email = ".$user_email.", perfil = ".$user_perfil.", sucursal = ".$user_sucursal."  -- "."NUEVO: UPDATE finan_cli.usuario SET nombre = ".$nombre.", apellido = ".$apellido.", tipo_documento = ".$tipoDocumento.", documento = ".$documento.", email = ".$email.", id_perfil = ".$perfil.", id_sucursal = ".$sucursal.", clave =".$clavefu.", salt =".$saltu." WHERE id =".$usuario;
					
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
					$motivo = 7;
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
				
				if($perfil == 2)
				{
					if ($stmt401 = $mysqli->prepare("SELECT hl.id_usuario, hl.horario_ingreso, hl.horario_salida, hl.lunes, hl.martes, hl.miercoles, hl.jueves, hl.viernes, hl.sabado, hl.domingo FROM finan_cli.horario_laboral_x_usuario hl WHERE hl.id_usuario = ?")) 
					{
						$stmt401->bind_param('s', $usuario);
						$stmt401->execute();    
						$stmt401->store_result();
				 
						$tieneHorarioLaboralDB = 0;
						$totR401 = $stmt401->num_rows;
						if($totR401 > 0)
						{
							$stmt401->bind_result($id_usuario_horario_laboral, $horario_ingreso_horario_laboral_a, $horario_egreso_horario_laboral_a, $lunes_horario_laboral_a, $martes_horario_laboral_a, $miercoles_horario_laboral_a, $jueves_horario_laboral_a, $viernes_horario_laboral_a, $sabado_horario_laboral_a, $domingo_horario_laboral_a);
							$stmt401->fetch();
							
							$tieneHorarioLaboralDB = 1;
							
							$stmt401->free_result();
							$stmt401->close();				
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
					
					if($tieneHorarioLaboralDB == 1)
					{
						if(!$stmt10 = $mysqli->prepare("UPDATE finan_cli.horario_laboral_x_usuario SET horario_ingreso = ?, horario_salida = ?, lunes = ?, martes = ?, miercoles = ?, jueves = ?, viernes = ?, sabado = ?, domingo = ? WHERE id_usuario = ?"))
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
							$horarioIngresDB = '20190904'.str_replace(":","",$horarioIngreso).'00';
							$horarioEgresDB = '20190904'.str_replace(":","",$horarioEgreso).'00';
							if($trabLunes == 'true') $trabLunesDB = 1;
							else $trabLunesDB = 0;
							if($trabMartes == 'true') $trabMartesDB = 1;
							else $trabMartesDB = 0;
							if($trabMiercoles == 'true') $trabMiercolesDB = 1;
							else $trabMiercolesDB = 0;
							if($trabJueves == 'true') $trabJuevesDB = 1;
							else $trabJuevesDB = 0;
							if($trabViernes == 'true') $trabViernesDB = 1;
							else $trabViernesDB = 0;
							if($trabSabado == 'true') $trabSabadoDB = 1;
							else $trabSabadoDB = 0;
							if($trabDomingo == 'true') $trabDomingoDB = 1;
							else $trabDomingoDB = 0;							
							$stmt10->bind_param('ssiiiiiiis', $horarioIngresDB, $horarioEgresDB, $trabLunesDB, $trabMartesDB, $trabMiercolesDB, $trabJuevesDB, $trabViernesDB, $trabSabadoDB, $trabDomingoDB, $usuario);
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
						$valor_log_user = "ANTERIOR: UPDATE finan_cli.horario_laboral_x_usuario SET horario_ingreso = ".$horario_ingreso_horario_laboral_a.", horario_salida = ".$horario_egreso_horario_laboral_a.", lunes = ".$lunes_horario_laboral_a.", martes = ".$martes_horario_laboral_a.", miercoles = ".$miercoles_horario_laboral_a.", jueves = ".$jueves_horario_laboral_a.", viernes = ".$viernes_horario_laboral_a.", sabado = ".$sabado_horario_laboral_a.", domingo = ".$domingo_horario_laboral_a." WHERE id_usuario = ".$usuario." -- "."NUEVO: UPDATE finan_cli.horario_laboral_x_usuario SET horario_ingreso = ".$horarioIngresDB.", horario_salida = ".$horarioEgresDB.", lunes = ".$trabLunesDB.", martes = ".$trabMartesDB.", miercoles = ".$trabMiercolesDB.", jueves = ".$trabJuevesDB.", viernes = ".$trabViernesDB.", sabado = ".$trabSabadoDB.", domingo = ".$trabDomingoDB." WHERE id_usuario = ".$usuario;
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
							$motivo = 88;
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
					else
					{
						if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.horario_laboral_x_usuario (id_usuario,horario_ingreso,horario_salida,lunes,martes,miercoles,jueves,viernes,sabado,domingo) VALUES (?,?,?,?,?,?,?,?,?,?)"))
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
							$horarioIngresDB = '20190904'.str_replace(":","",$horarioIngreso).'00';
							$horarioEgresDB = '20190904'.str_replace(":","",$horarioEgreso).'00';
							if($trabLunes == 'true') $trabLunesDB = 1;
							else $trabLunesDB = 0;
							if($trabMartes == 'true') $trabMartesDB = 1;
							else $trabMartesDB = 0;
							if($trabMiercoles == 'true') $trabMiercolesDB = 1;
							else $trabMiercolesDB = 0;
							if($trabJueves == 'true') $trabJuevesDB = 1;
							else $trabJuevesDB = 0;
							if($trabViernes == 'true') $trabViernesDB = 1;
							else $trabViernesDB = 0;
							if($trabSabado == 'true') $trabSabadoDB = 1;
							else $trabSabadoDB = 0;
							if($trabDomingo == 'true') $trabDomingoDB = 1;
							else $trabDomingoDB = 0;							
							$stmt10->bind_param('sssiiiiiii', $usuario, $horarioIngresDB, $horarioEgresDB, $trabLunesDB, $trabMartesDB, $trabMiercolesDB, $trabJuevesDB, $trabViernesDB, $trabSabadoDB, $trabDomingoDB);
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
						$valor_log_user = "INSERT INTO finan_cli.horario_laboral_x_usuario (id_usuario,horario_ingreso,horario_salida,lunes,martes,miercoles,jueves,viernes,sabado,domingo) VALUES (".$usuario.",".$horarioIngresDB.",".$horarioEgresDB.",".$trabLunesDB.",".$trabMartesDB.",".$trabMiercolesDB.",".$trabJuevesDB.",".$trabViernesDB.",".$trabSabadoDB.",".$trabDomingoDB.")";
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
							$motivo = 87;
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
					
					echo translate('Msg_Modify_User_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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