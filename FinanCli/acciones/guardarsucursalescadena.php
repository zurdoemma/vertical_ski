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
		
		$idCadenaT=htmlspecialchars($_POST["idCadena"], ENT_QUOTES, 'UTF-8');
		$sucursales=htmlspecialchars($_POST["idSucursales"], ENT_QUOTES, 'UTF-8');
				
		if($stmt = $mysqli->prepare("SELECT u.id FROM finan_cli.usuario u WHERE u.id LIKE(?)"))
		{
			$stmt->bind_param('s', $_SESSION['username']);
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
				$idSucursales = explode(",",$sucursales);
				
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				if($stmt = $mysqli->prepare("SELECT s.id, s.nombre, c.razon_social FROM finan_cli.sucursal s, finan_cli.cadena c WHERE c.id = s.id_cadena AND s.id_cadena = ?"))
				{
					$stmt->bind_param('i', $idCadenaT);
					$stmt->execute();    
					$stmt->store_result();
					
					$stmt->bind_result($id_sucursal_des, $name_sucursal_des, $name_cadena_ad);
								
					while($stmt->fetch())
					{
						$date_registro = date("YmdHis");
						$date_registro2 = date("Y-m-d H:i:s");					
						$valor_log_user = "UPDATE finan_cli.sucursal SET id_cadena = NULL WHERE id_sucursal = ".$id_sucursal_des.", name = ".$name_sucursal_des.", id_cadena = ".$idCadenaT.", name_cadena = ".$name_cadena_ad;

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
							$motivo = 17;
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
				else
				{
					echo $mysqli->error;
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
					return;					
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;
				}
				
				if(!$stmt10 = $mysqli->prepare("UPDATE finan_cli.sucursal SET id_cadena = NULL WHERE id_cadena = ?"))
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
					$stmt10->bind_param('i', $idCadenaT);
					if(!$stmt10->execute())
					{
						echo $mysqli->error;
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;
					}
						
					foreach ($idSucursales as $idSucuR) 
					{
						if(!$stmt10 = $mysqli->prepare("UPDATE finan_cli.sucursal SET id_cadena = ? WHERE id = ?"))
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
							$stmt10->bind_param('ii', $idCadenaT, $idSucuR);
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
						$date_registro2 = date("Y-m-d H:i:s");					
						$valor_log_user = "UPDATE finan_cli.sucursal SET id_cadena = ".$idCadenaT." WHERE id_sucursal = ".$idSucuR;

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
							$motivo = 16;
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
				
				echo translate('Msg_Save_Assign_Tenders_To_Chain_OK',$GLOBALS['lang']);
				
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