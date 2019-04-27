<?php 		
		include ('../utiles/funciones.php');
		sec_session_start();
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');}
		if (!verificar_permisos_usuario()){header('Location:../sinautorizacion.php');}

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
		
		$usuario=$_POST["usuario"];

		$nombre=$_POST["nombre"];
		$apellido=$_POST["apellido"];
		$tipoDocumento=$_POST["tipoDocumento"];
		$documento=$_POST["documento"];
		$email=$_POST["email"];
		$claveactu = $_POST["claveac"];
		$nclaveu=$_POST["claveu"];
				
		if($stmt = $mysqli->prepare("SELECT u.id, u.nombre, u.apellido, td.nombre, u.documento, u.email, u.clave, u.salt FROM finan_cli.usuario u, finan_cli.tipo_documento td WHERE u.tipo_documento = td.id AND u.id LIKE(?)"))
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
				if($_SESSION['username'] != $usuario)
				{
					echo translate('Msg_Edit_User_Not_Match_User_Logged',$GLOBALS['lang']);
					return;						
				}
				
				$stmt->bind_result($id_user, $user_name, $user_surname, $user_type_document, $user_document, $user_email, $user_clave, $user_salt);				
				$stmt->fetch();
				
				$claveAVerificar = hash('sha512', $claveactu . $user_salt);
				
				if($user_clave != $claveAVerificar)
				{
					echo translate('Msg_Incorrect_Current_Password',$GLOBALS['lang']);
					return;	
				}
				
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
				
				if(!$mysqli->query("UPDATE finan_cli.usuario SET nombre = '".$nombre."', apellido = '".$apellido."', tipo_documento = ".$tipoDocumento.", documento = '".$documento."', email = '".$email."', clave = '".$clavefu."', salt = '".$saltu."' WHERE id ='".$usuario."'"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
	
				$date_registro = date("YmdHis");
				$date_registro2 = date("Y-m-d H:i:s");
				if(empty($nclaveu)) $valor_log_user = "ANTERIOR: id = ".$id_user.", nombre = ".$user_name.", apellido = ".$user_surname.", tipo_documento = ".$user_type_document.", documento = ".$user_document.", email = ".$user_email." -- "."NUEVO: UPDATE finan_cli.usuario SET nombre = ".$nombre.", apellido = ".$apellido.", tipo_documento = ".$tipoDocumento.", documento = ".$documento.", email = ".$email." WHERE id =".$usuario;
				else $valor_log_user = "ANTERIOR: id = ".$id_user.", nombre = ".$user_name.", apellido = ".$user_surname.", tipo_documento = ".$user_type_document.", documento = ".$user_document.", email = ".$user_email." -- "."NUEVO: UPDATE finan_cli.usuario SET nombre = ".$nombre.", apellido = ".$apellido.", tipo_documento = ".$tipoDocumento.", documento = ".$documento.", email = ".$email.", clave =".$clavefu.", salt =".$saltu." WHERE id =".$usuario;
					
				if(!$mysqli->query("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES ('".$_SESSION['username']."','$date_registro',7,'".$valor_log_user."')"))
				{
					echo $mysqli->error;
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
										
				$mysqli->commit();
				$mysqli->autocommit(TRUE);
				
				if($stmt = $mysqli->prepare("SELECT u.nombre, u.apellido, u.tipo_documento, u.documento, u.email FROM finan_cli.usuario u WHERE u.id like(?)"))
				{
					$stmt->bind_param('s', $usuario);
					$stmt->execute();    
					$stmt->store_result();
					
					$stmt->bind_result($user_name_a, $user_surname_a, $user_type_document_a, $user_document_a, $user_email_a);				
										
					$array[0] = array();
					$stmt->fetch();
					
					$array[$posicion]['nombre'] = $user_name_a;
					$array[$posicion]['apellido'] = $user_surname_a;
					$array[$posicion]['tipodocumento'] = $user_type_document_a;					
					$array[$posicion]['documento'] = $user_document_a;
					$array[$posicion]['email'] = $user_email_a;
					
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