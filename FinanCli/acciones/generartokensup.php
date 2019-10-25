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
		$duracion=htmlspecialchars($_POST["duracion"], ENT_QUOTES, 'UTF-8');	
		
		if ($stmt500 = $mysqli->prepare("SELECT c.id, u.salt FROM finan_cli.cadena c, finan_cli.usuario u, finan_cli.sucursal s WHERE u.id_sucursal = s.id AND s.id_cadena = c.id AND u.id = ?")) 
		{
			$stmt500->bind_param('s', $_SESSION['username']);
			$stmt500->execute();    
			$stmt500->store_result();
	 
			$totR500 = $stmt500->num_rows;
			if($totR500 > 0)
			{
				$stmt500->bind_result($id_cadena_user, $salt_user_auto);
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
		
		if ($stmt50 = $mysqli->prepare("SELECT c.id FROM finan_cli.cadena c, finan_cli.usuario u, finan_cli.sucursal s WHERE u.id_sucursal = s.id AND s.id_cadena = c.id AND u.id = ? AND s.id_cadena = ?")) 
		{
			$stmt50->bind_param('si', $usuario, $id_cadena_user);
			$stmt50->execute();    
			$stmt50->store_result();
	 
			$totR50 = $stmt50->num_rows;
			if($totR50 > 0)
			{
				$stmt50->bind_result($id_cadena_user_e);
				$stmt50->fetch();

				$stmt50->free_result();
				$stmt50->close();				
			}
			else 
			{
				echo translate('Msg_User_Entered_Is_Invalid',$GLOBALS['lang']);
				return;				
			}	
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}

		if(empty($duracion))
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		
		if ($stmt51 = $mysqli->prepare("SELECT tas.fecha, tas.token, tas.duracion FROM finan_cli.token_autorizacion_supervisor tas WHERE tas.utilizado = 0 AND tas.fecha_utilizacion IS NULL AND tas.autorizado = ? AND tas.autorizante = ? ORDER BY tas.fecha DESC")) 
		{
			$stmt51->bind_param('ss', $usuario, $_SESSION['username']);
			$stmt51->execute();    
			$stmt51->store_result();
	 
			$totR51 = $stmt51->num_rows;
			if($totR51 > 0)
			{
				$stmt51->bind_result($fecha_tas, $token_tas, $duracion_token_tas);
				$stmt51->fetch();

				$fechaObtDB = substr($fecha_tas, 0, 4).'-'.substr($fecha_tas, 4, 2).'-'.substr($fecha_tas, 6, 2).' '.substr($fecha_tas, 8, 2).':'.substr($fecha_tas, 10, 2).':'.substr($fecha_tas, 12, 2);
				$fechaInfDB = new DateTime($fechaObtDB);
				$fechaAct = new DateTime();
				$difMinutos = $fechaAct->diff($fechaInfDB);
				
				if($difMinutos->i > $duracion_token_tas)
				{
					$mysqli->autocommit(FALSE);
					$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
					
					if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (?,?,?,?,?,?)"))
					{
						echo $mysqli->error;
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;
					}
					else
					{
						$codigoGeneradoN = mt_rand(100000, 999999);
						$codigoGeneradoCod = hash('sha512', $codigoGeneradoN);
						$codigoGeneradoCod = hash('sha512', $codigoGeneradoCod . $salt_user_auto);
						$date_registro = date("YmdHis");
						$utilizadoT = 0;
						
						$stmt10->bind_param('sssisi', $_SESSION['username'], $usuario, $date_registro, $utilizadoT, $codigoGeneradoCod, $duracion);
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
					$valor_log_user = "INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (".$_SESSION['username'].",".$usuario.",".$date_registro.",".$utilizadoT.",".$codigoGeneradoCod.",".$duracion.")";

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
						$motivo = 100;
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
					
					echo '<div class="input-group col-xs-2" style="width:300px;">';
					echo ' &nbsp;&nbsp;&nbsp;&nbsp;<input id="mostrartoken" type="text" class="form-control" name="mostrartoken" value="'.$codigoGeneradoN.'" readonly>';
					echo '</div>';
					return;
				}
				else
				{
					echo translate('Msg_There_Is_Already_Current_Token_Generated_Selected_User',$GLOBALS['lang']);
					return;
				}
				
				$stmt51->free_result();
				$stmt51->close();				
			}
			else 
			{
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (?,?,?,?,?,?)"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$codigoGeneradoN = mt_rand(100000, 999999);
					$codigoGeneradoCod = hash('sha512', $codigoGeneradoN);
					$codigoGeneradoCod = hash('sha512', $codigoGeneradoCod . $salt_user_auto);
					$date_registro = date("YmdHis");
					$utilizadoT = 0;
					
					$stmt10->bind_param('sssisi', $_SESSION['username'], $usuario, $date_registro, $utilizadoT, $codigoGeneradoCod, $duracion);
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
				$valor_log_user = "INSERT INTO finan_cli.token_autorizacion_supervisor(autorizante,autorizado,fecha,utilizado,token,duracion) VALUES (".$_SESSION['username'].",".$usuario.",".$date_registro.",".$utilizadoT.",".$codigoGeneradoCod.",".$duracion.")";

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
					$motivo = 100;
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
				
				echo '<div class="input-group col-xs-2" style="width:300px;">';
				echo ' &nbsp;&nbsp;&nbsp;&nbsp;<input id="mostrartoken" type="text" class="form-control" name="mostrartoken" value="'.$codigoGeneradoN.'" readonly>';
				echo '</div>';
				return;
			}	
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}
		
		
		
		return;
?>