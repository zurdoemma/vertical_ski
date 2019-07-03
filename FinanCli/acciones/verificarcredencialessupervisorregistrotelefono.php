<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');return;}
		if (!verificar_permisos_usuario()){header('Location:../sinautorizacion.php');return;}

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
		
		$motivo=htmlspecialchars($_POST["motivo"], ENT_QUOTES, 'UTF-8');
		$usuarioSupervisor=htmlspecialchars($_POST["usuarioSupervisor"], ENT_QUOTES, 'UTF-8');
		$claveSupervisor=htmlspecialchars($_POST["claveSupervisor"], ENT_QUOTES, 'UTF-8');
		$idCliente=htmlspecialchars($_POST["idCliente"], ENT_QUOTES, 'UTF-8');
		$prefijoTelefono=htmlspecialchars($_POST["prefijoTelefono"], ENT_QUOTES, 'UTF-8');
		$nroTelefono=htmlspecialchars($_POST["nroTelefono"], ENT_QUOTES, 'UTF-8');		
		
		if($stmt44 = $mysqli->prepare("SELECT c.id, c.tipo_documento, c.documento FROM finan_cli.cliente c WHERE c.id = ?"))
		{
			$stmt44->bind_param('i', $idCliente);
			$stmt44->execute();    
			$stmt44->store_result();
			
			$totR44 = $stmt44->num_rows;

			if($totR44 == 0)
			{
				echo translate('Msg_Client_Not_Exist',$GLOBALS['lang']);
				return;
			}			
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		
		$stmt44->bind_result($id_client, $type_document_client, $document_client);
		$stmt44->fetch();
		
		$tipoDocumento = $type_document_client;
		$documento = $document_client;
		
		$stmt44->free_result();
		$stmt44->close();
				
		if ($stmt = $mysqli->prepare("SELECT id, clave, salt, id_perfil, estado  FROM finan_cli.usuario WHERE id = ? AND id_perfil IN (1,3) LIMIT 1")) 
		{
			$stmt->bind_param('s', $usuarioSupervisor);  
			$stmt->execute();   
			$stmt->store_result();
	 

			$stmt->bind_result($user_id, $db_password, $salt, $permiso, $estado_user);
			$stmt->fetch();
	 
			$password = hash('sha512', $claveSupervisor . $salt);
			if ($stmt->num_rows == 1) 
			{
				if (checkbrute($user_id, $mysqli) == true) 
				{
					echo translate('Msg_Block_User',$GLOBALS['lang']);
					return;
				} 
				else 
				{
					if(empty($estado_user) || $estado_user != translate('State_User',$GLOBALS['lang']))
					{
						echo translate('Msg_Disable_User',$GLOBALS['lang']);
						return;
					}
					if ($db_password == $password) 
					{
						$mysqli->autocommit(FALSE);
						$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
						
						if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.estado_cliente(fecha,tipo_documento,documento,id_motivo,usuario,usuario_supervisor,nro_telefono) VALUES (?,?,?,?,?,?,?)"))
						{
							$mysqli->autocommit(TRUE);
							$stmt->free_result();
							$stmt->close();
							echo translate('Msg_Supervisor_Not_OK',$GLOBALS['lang']);
							return;
						}
						else
						{
							$date_registro_a_s_db = date("YmdHis");
							$telefonoFin = $prefijoTelefono.$nroTelefono;
							$stmt10->bind_param('sisissi', $date_registro_a_s_db, $tipoDocumento, $documento, $motivo, $_SESSION['username'], $usuarioSupervisor, $telefonoFin);
							if(!$stmt10->execute())
							{
								$mysqli->autocommit(TRUE);
								$stmt->free_result();
								$stmt->close();
								echo translate('Msg_Supervisor_Not_OK',$GLOBALS['lang']);
								return;						
							}
																			
							$mysqli->commit();
							$mysqli->autocommit(TRUE);
							$stmt->free_result();
							$stmt->close();
								
							echo translate('Msg_Supervisor_OK',$GLOBALS['lang']);
							return;							
						}
					}
					else
					{
						echo translate('Msg_Supervisor_Not_OK',$GLOBALS['lang']);
						return;
					}
				}
			}
			else
			{
				echo translate('Msg_Supervisor_Not_OK',$GLOBALS['lang']);
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