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

		$idPerfilCredito=htmlspecialchars($_POST["idPerfilCredito"], ENT_QUOTES, 'UTF-8');
		$planes=htmlspecialchars($_POST["idPlanes"], ENT_QUOTES, 'UTF-8');
				
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
				$idPlanes = explode(",",$planes);
				
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				if($stmt2 = $mysqli->prepare("SELECT pc.id, pc.nombre, pc.cantidad_cuotas, pc.interes_fijo, pc.id_tipo_diferimiento_cuota, c.razon_social FROM finan_cli.perfil_credito_x_plan pcxp, finan_cli.plan_credito pc, finan_cli.cadena c WHERE c.id = pc.id_cadena AND pcxp.id_plan_credito = pc.id AND pcxp.id_perfil_credito = ?"))
				{
					$stmt2->bind_param('i', $idPerfilCredito);
					$stmt2->execute();    
					$stmt2->store_result();
					
					$stmt2->bind_result($id_plan_credito_des, $nombre_plan_credito_des, $cantidad_cuotas_plan_credito_des, $interes_fijo_plan_credito_des, $diferimiento_cuota_plan_credito_des, $cadena_plan_credito_des);
								
					while($stmt2->fetch())
					{
						$date_registro = date("YmdHis");
						$date_registro2 = date("Y-m-d H:i:s");					
						$valor_log_user = "DELETE finan_cli.perfil_credito_x_plan WHERE id_plan_credito = ".$id_plan_credito_des." -- nombre = ".$nombre_plan_credito_des.", cantidad_cuotas = ".$cantidad_cuotas_plan_credito_des.", interes_fijo = ".$interes_fijo_plan_credito_des.", tipo_diferimiento_cuota = ".$diferimiento_cuota_plan_credito_des.", cadena = ".$cadena_plan_credito_des;

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
							$motivo = 31;
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
					//echo htmlspecialchars($mysqli->error);
					$mysqli->autocommit(TRUE);				
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					$stmt->free_result();
					$stmt->close();				
					return;
				}
				
				$stmt2->free_result();
				$stmt2->close();
				
				if(!$stmt10 = $mysqli->prepare("DELETE FROM finan_cli.perfil_credito_x_plan WHERE id_perfil_credito = ?"))
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
					$stmt10->bind_param('i', $idPerfilCredito);
					if(!$stmt10->execute())
					{
						echo $mysqli->error;
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;
					}
						
					foreach ($idPlanes as $idPlanR) 
					{
						if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.perfil_credito_x_plan (id_perfil_credito, id_plan_credito) VALUES (?,?)"))
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
							$stmt10->bind_param('ii', $idPerfilCredito, $idPlanR);
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
						$valor_log_user = "INSERT INTO finan_cli.perfil_credito_x_plan (id_perfil_credito, id_plan_credito) VALUES (".$idPerfilCredito.",".$id_plan_credito.")";

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
							$motivo = 32;
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
				
				echo translate('Msg_Save_Assign_Credit_Plans_To_Profile_OK',$GLOBALS['lang']);
				
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