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
		
		$usuario=htmlspecialchars ( $_POST["usuario"], ENT_QUOTES, 'UTF-8' );
		$idDomicilio=htmlspecialchars ( $_POST["id_domicilio"], ENT_QUOTES, 'UTF-8' );
				
		if($stmt = $mysqli->prepare("SELECT d.id, d.calle, d.nro_calle, p.nombre, d.localidad, d.departamento, d.piso, d.codigo_postal, d.entre_calle_1, d.entre_calle_2 FROM ".$db_name.".usuario u, ".$db_name.".domicilio d, ".$db_name.".usuario_x_domicilio ud, ".$db_name.".provincia p WHERE d.id_provincia = p.id AND u.id LIKE(?) AND u.id = ud.id_usuario AND d.id = ud.id_domicilio AND d.id = ?"))
		{
			$stmt->bind_param('si', $usuario, $idDomicilio);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				$stmt->free_result();
				$stmt->close();
				echo translate('Msg_User_Or_Address_Not_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{
				if($_SESSION['username'] != $usuario)
				{
					echo translate('Msg_Edit_User_Not_Match_User_Logged',$GLOBALS['lang']);
					return;						
				}				
				
				if($stmt2 = $mysqli->prepare("SELECT d.id, d.calle, d.nro_calle, p.nombre, d.localidad, d.departamento, d.piso, d.codigo_postal, d.entre_calle_1, d.entre_calle_2 FROM ".$db_name.".usuario u, ".$db_name.".domicilio d, ".$db_name.".usuario_x_domicilio ud, ".$db_name.".provincia p WHERE d.id_provincia = p.id AND u.id LIKE(?) AND u.id = ud.id_usuario AND d.id = ud.id_domicilio"))
				$stmt2->bind_param('s', $usuario);
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
				
				
				$stmt->bind_result($id_domicilio_user, $user_dom_calle, $user_dom_nro_calle, $user_dom_provincia, $user_dom_localidad, $user_dom_departamento, $user_dom_piso, $user_dom_codigo_postal, $user_entre_calle_1, $user_entre_calle_2);
				
				if(!$stmt10 = $mysqli->prepare("DELETE FROM ".$db_name.".usuario_x_domicilio WHERE id_usuario = ? AND id_domicilio = ?"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$stmt10->bind_param('si', $usuario, $idDomicilio);
					if(!$stmt10->execute())
					{
						echo $mysqli->error;
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;						
					}
					
					if(!$stmt10 = $mysqli->prepare("DELETE FROM ".$db_name.".domicilio WHERE id = ?"))
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
				$stmt->fetch();
				$valor_log_user = "DELETE ".$db_name.".domicilio --> id: ".$id_domicilio_user." - Calle: ".$user_dom_calle." - Nro. Calle: ".$user_dom_nro_calle." - Provincia: ".$user_dom_provincia." - Localidad: ".$user_dom_localidad." - Departamento: ".(!empty($user_dom_departamento) ? "$user_dom_departamento" : "NULL")." - Piso: ".(!empty($user_dom_piso) ? "$user_dom_piso" : "NULL")." - Codigo Postal: ".(!empty($user_dom_codigo_postal) ? "$user_dom_codigo_postal" : "NULL")." - Entre Calle 1: ".(!empty($user_entre_calle_1) ? "$user_entre_calle_1" : "NULL")." - Entre Calle 2: ".(!empty($user_entre_calle_2) ? "$user_entre_calle_2" : "NULL");

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
					$motivo = 5;
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
				
				if($stmt = $mysqli->prepare("SELECT d.id, d.calle, d.nro_calle, p.nombre, d.localidad, d.departamento, d.piso, d.codigo_postal FROM ".$db_name.".usuario u, ".$db_name.".domicilio d, ".$db_name.".provincia p, ".$db_name.".usuario_x_domicilio ud WHERE d.id_provincia = p.id AND u.id LIKE(?) AND ud.id_usuario = u.id AND ud.id_domicilio = d.id"))
				{
					$stmt->bind_param('s', $usuario);
					$stmt->execute();    
					$stmt->store_result();
					
					$stmt->bind_result($id_domicilio_user, $user_dom_calle, $user_dom_nro_calle, $user_dom_provincia, $user_dom_localidad, $user_dom_departamento, $user_dom_piso, $user_dom_codigo_postal);
										
					$array[0] = array();
					$posicion = 0;
					while($stmt->fetch())
					{
						$array[$posicion]['calle'] = $user_dom_calle;
						$array[$posicion]['nrocalle'] = $user_dom_nro_calle;
						$array[$posicion]['provincia'] = $user_dom_provincia;
						$array[$posicion]['localidad'] = $user_dom_localidad;
						
						if(empty($user_dom_departamento)) $array[$posicion]['departamento'] = '---';
						else $array[$posicion]['departamento'] = $user_dom_departamento;
						if(empty($user_dom_piso)) $array[$posicion]['piso'] = '---';
						else $array[$posicion]['piso'] = $user_dom_piso;
						if(empty($user_dom_codigo_postal)) $array[$posicion]['codigopostal'] = '---';
						else $array[$posicion]['codigopostal'] = $user_dom_codigo_postal;
						
						$array[$posicion]['acciones'] = '<button type="button" id="borrarDomicilio'.$id_domicilio_user.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Address',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Remove_Domicilio',$GLOBALS['lang']).'\',\''.$usuario.'\',\''.$id_domicilio_user.'\')"><i class="fas fa-trash-alt"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" id="modificarDomicilio'.$id_domicilio_user.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Address',$GLOBALS['lang']).'" onclick="modificarDomicilio(\''.$usuario.'\',\''.$id_domicilio_user.'\')"><i class="fas fa-edit"></i></button>';
						
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