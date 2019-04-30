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
		
		$idCadena=htmlspecialchars ($_POST["idCadena"], ENT_QUOTES, 'UTF-8');
				
		if($stmt = $mysqli->prepare("SELECT c.id, c.razon_social, c.cuit_cuil, c.email, c.telefono, c.nombre_fantasia FROM finan_cli.cadena c WHERE c.id = ?"))
		{
			$stmt->bind_param('i', $idCadena);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				$stmt->free_result();
				$stmt->close();
				echo translate('Msg_Chain_Not_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{				
				if($stmt2 = $mysqli->prepare("SELECT c.id FROM finan_cli.cadena c, finan_cli.sucursal s WHERE c.id = s.id_cadena AND c.id = ?"))
				{
					$stmt2->bind_param('i', $idCadena);
					$stmt2->execute();    
					$stmt2->store_result();
				
					$totR = $stmt2->num_rows;

					if($totR > 0)
					{
						$stmt2->free_result();
						$stmt2->close();
						echo translate('Msg_Chain_Not_Remove_Because_Associated_Tender',$GLOBALS['lang']);
						return;
					}
					
					$stmt2->free_result();
					$stmt2->close();
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
				
				
				$stmt->bind_result($id_cadena, $cadena_razon_social, $cadena_cuit_cuil, $cadena_email, $cadena_telefono, $cadena_nombre_fantasia);
				
				if(!$stmt10 = $mysqli->prepare("DELETE FROM finan_cli.cadena WHERE id = ?"))
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
					$stmt10->bind_param('i', $idCadena);
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
				$stmt->fetch();
				$valor_log_user = "DELETE finan_cli.cadena --> id: ".$id_cadena." - Razon Social: ".$cadena_razon_social." - CUIT o CUIL: ".$cadena_cuit_cuil." - Email: ".$cadena_email." - Nombre de Fantasia: ".$cadena_nombre_fantasia;

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
					$motivo = 14;
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
				
				if($stmt = $mysqli->prepare("SELECT c.id, c.razon_social, c.cuit_cuil, c.nombre_fantasia FROM finan_cli.cadena c ORDER BY c.id"))
				{
					$stmt->execute();    
					$stmt->store_result();
					
					$stmt->bind_result($id_chain, $razon_social_chain, $cuit_cuil_chain, $nombre_fantasia_chain);
										
					$array[0] = array();
					$posicion = 0;
					while($stmt->fetch())
					{
						$array[$posicion]['razonsocial'] = $razon_social_chain;
						$array[$posicion]['cuitcuil'] = $cuit_cuil_chain;
						$array[$posicion]['nombrefantasia'] = $nombre_fantasia_chain;
						
						$array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Chain',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Removed_Chain',$GLOBALS['lang']).'\',\''.$id_chain.'\',\''.$razon_social_chain.'\')"><i class="fas fa-unlink"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Chain',$GLOBALS['lang']).'" onclick="modificarCadena(\''.$id_chain.'\',\''.$razon_social_chain.'\')"><i class="fas fa-edit"></i></button>';
						
						$posicion++;
					}
					
					echo translate('Msg_Remove_Chain_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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