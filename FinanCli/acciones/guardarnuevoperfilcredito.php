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
		
		$nombre=htmlspecialchars($_POST["nombre"], ENT_QUOTES, 'UTF-8');
		$descripcion=htmlspecialchars($_POST["descripcion"], ENT_QUOTES, 'UTF-8');
		$montoMaximo=htmlspecialchars($_POST["montoMaximo"], ENT_QUOTES, 'UTF-8');
		
		if($montoMaximo < 0)
		{
			echo translate('Negative_Numbers_Are_Not_Allowed',$GLOBALS['lang']);
			return;
		}
				
		if($stmt41 = $mysqli->prepare("SELECT p.valor FROM ".$db_name.".parametros p WHERE p.nombre = 'monto_maximo_perfil_credito'"))
		{
			$stmt41->execute();    
			$stmt41->store_result();
			
			$totR41 = $stmt41->num_rows;

			if($totR41 > 0)
			{					
				$stmt41->bind_result($monto_maximo_perfil_credito_db);
				$stmt41->fetch();

				if(($monto_maximo_perfil_credito_db*100) < $montoMaximo)
				{
					echo translate('Maximum_Amount_Not_Allowed',$GLOBALS['lang']);
					return;
				}
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}

		if($stmt41 = $mysqli->prepare("SELECT p.valor FROM ".$db_name.".parametros p WHERE p.nombre = 'monto_minimo_perfil_credito'"))
		{
			$stmt41->execute();    
			$stmt41->store_result();
			
			$totR41 = $stmt41->num_rows;

			if($totR41 > 0)
			{					
				$stmt41->bind_result($monto_minimo_perfil_credito_db);
				$stmt41->fetch();

				if(($monto_minimo_perfil_credito_db*100) > $montoMaximo)
				{
					echo translate('Maximum_Amount_Not_Allowed',$GLOBALS['lang']);
					return;
				}
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}		
			
		if($stmt = $mysqli->prepare("SELECT pc.id FROM ".$db_name.".perfil_credito pc WHERE pc.nombre LIKE(?)"))
		{
			$stmt->bind_param('s', $nombre);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR > 0)
			{
				echo translate('Msg_A_Profile_Credit_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{					
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				if(!$stmt10 = $mysqli->prepare("INSERT INTO ".$db_name.".perfil_credito(nombre,descripcion,monto_maximo) VALUES (?,?,?)"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else 
				{
					$stmt10->bind_param('ssi', $nombre, $descripcion, $montoMaximo);
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
				$valor_log_user = "INSERT INTO ".$db_name.".perfil_credito(nombre,descripcion,monto_maximo) VALUES (".$nombre.",".str_replace('\'','',$descripcion).",".$montoMaximo.")";

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
					$motivo = 25;
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
				
				if($stmt = $mysqli->prepare("SELECT pc.id, pc.nombre, pc.descripcion, pc.monto_maximo FROM ".$db_name.".perfil_credito pc ORDER BY pc.id"))
				{
					$stmt->execute();    
					$stmt->store_result();
					
					$stmt->bind_result($id_perfil_credito, $name_perfil_credito, $descripcion_perfil_credito, $monto_maximo_perfil_credito);
										
					$array[0] = array();
					$posicion = 0;
					while($stmt->fetch())
					{
						$array[$posicion]['nombre'] = $name_perfil_credito;
						$array[$posicion]['descripcion'] = $descripcion_perfil_credito;
						$array[$posicion]['montomaximo'] = '$'.number_format(($monto_maximo_perfil_credito/100.00),2);
						
						$array[$posicion]['acciones'] = '<button type="button" id="borrarPerfilCredit'.$id_profile_credit.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Profile_Credit',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Removed_Profile_Credit',$GLOBALS['lang']).'\',\''.$id_perfil_credito.'\',\''.$name_perfil_credito.'\')"><i class="fas fa-trash-alt"></i></button>&nbsp;&nbsp;&nbsp;<button type="button"  id="modificarPerfilCredit'.$id_profile_credit.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Profile_Credit',$GLOBALS['lang']).'" onclick="modificarPerfilCredito(\''.$id_perfil_credito.'\',\''.$name_perfil_credito.'\')"><i class="fas fa-edit"></i></button>';
						
						$posicion++;
					}
					
					echo translate('Msg_New_Profile_Credit_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($array);
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