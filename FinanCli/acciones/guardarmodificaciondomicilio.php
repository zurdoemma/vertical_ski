<?php 		
		include ('../utiles/funciones.php');
		sec_session_start();
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');}
		if (!verificar_permisos_admin()){header('Location:../sinautorizacion.php?activauto=1');}

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

		$idDomicilio=htmlspecialchars($_POST["idDomicilio"], ENT_QUOTES, 'UTF-8');
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
		
		if($stmt = $mysqli->prepare("SELECT d.id, d.calle, d.nro_calle, p.nombre, d.localidad, d.departamento, d.piso, d.codigo_postal, d.entre_calle_1, d.entre_calle_2 FROM finan_cli.usuario u, finan_cli.domicilio d, finan_cli.usuario_x_domicilio ud, finan_cli.provincia p WHERE ud.id_usuario = u.id AND ud.id_domicilio = d.id AND d.id_provincia = p.id AND u.id LIKE(?) AND d.id = ?"))
		{
			$stmt->bind_param('si', $usuario, $idDomicilio);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_User_Or_Address_Not_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				if(!$stmt10 = $mysqli->prepare("UPDATE finan_cli.domicilio SET calle = ?, nro_calle = ?, id_provincia = ?, localidad = ?, departamento = ?, piso = ?, codigo_postal = ?, entre_calle_1 = ?, entre_calle_2 = ? WHERE id = ?"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$stmt10->bind_param('siississsi', $calle, $nroCalle, $provincia, $localidad, $departamento, $piso, $codigoPostal, $entreCalle1, $entreCalle2, $idDomicilio);
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
				$stmt->bind_result($id_domicilio_user, $user_dom_calle, $user_dom_nro_calle, $user_dom_provincia, $user_dom_localidad, $user_dom_departamento, $user_dom_piso, $user_dom_codigo_postal, $user_entre_calle_1, $user_entre_calle_2);				
				$stmt->fetch();
				$valor_log_user = "ANTERIOR: id = ".$id_domicilio_user.", calle = ".$user_dom_calle.", nro_calle = ".$user_dom_nro_calle.", provincia = ".$user_dom_provincia.", localidad = ".$user_dom_localidad.", departamento = ".$user_dom_departamento.", piso = ".$user_dom_piso.", codigo_postal = ".$user_dom_codigo_postal.", entre_calle_1 = ".$user_entre_calle_1.", entre_calle_2 = ".$user_entre_calle_2."  -- "."NUEVO: UPDATE finan_cli.domicilio SET calle = ".$calle.", nro_calle = ".$nroCalle.", id_provincia = ".$provincia.", localidad = ".$localidad.", departamento = ".str_replace('\'','',$departamento).", piso = ".$piso.", codigo_postal = ".str_replace('\'','',$codigoPostal).", entre_calle_1 = ".str_replace('\'','',$entreCalle1).", entre_calle_2 = ".str_replace('\'','',$entreCalle2)." WHERE id =".$idDomicilio;

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
					$motivo = 6;
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
				
				if($stmt = $mysqli->prepare("SELECT d.id, d.calle, d.nro_calle, p.nombre, d.localidad, d.departamento, d.piso, d.codigo_postal FROM finan_cli.usuario u, finan_cli.domicilio d, finan_cli.provincia p, finan_cli.usuario_x_domicilio ud WHERE d.id_provincia = p.id AND u.id LIKE(?) AND ud.id_usuario = u.id AND ud.id_domicilio = d.id"))
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
						
						$array[$posicion]['acciones'] = '<button class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Address',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Remove_Domicilio',$GLOBALS['lang']).'\',\''.$usuario.'\',\''.$id_domicilio_user.'\')"><i class="fas fa-trash-alt"></i></button>&nbsp;&nbsp;&nbsp;<button class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Address',$GLOBALS['lang']).'" onclick="modificarDomicilio(\''.$usuario.'\',\''.$id_domicilio_user.'\')"><i class="fas fa-edit"></i></button>';
						
						$posicion++;
					}
					
					echo translate('Msg_Modify_Address_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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