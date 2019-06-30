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
		$tokenECC2=htmlspecialchars($_POST["tokenECC2"], ENT_QUOTES, 'UTF-8');
		$idCliente=htmlspecialchars($_POST["idCliente"], ENT_QUOTES, 'UTF-8');
		
		
		if($stmt4 = $mysqli->prepare("SELECT c.id, c.id_titular, c.tipo_documento, c.documento FROM finan_cli.cliente c WHERE c.id = ?"))
		{
			$stmt4->bind_param('i', $idCliente);
			$stmt4->execute();    
			$stmt4->store_result();
			
			$totR4 = $stmt4->num_rows;

			if($totR4 == 0)
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
		
		$stmt4->bind_result($id_cliente_db, $tipo_cuenta_cliente_db, $tipo_documento_cliente_db, $documento_cliente_db);
		$stmt4->fetch();
		
		$tipoDocumento=$tipo_documento_cliente_db;
		$documento=$documento_cliente_db;
		
		$mysqli->autocommit(FALSE);
		$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
		
		if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.estado_cliente(fecha,tipo_documento,documento,id_motivo,usuario) VALUES (?,?,?,?,?)"))
		{
			$mysqli->autocommit(TRUE);
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		else
		{
			$date_registro_a_s_db = date("YmdHis");
			$stmt10->bind_param('sisis', $date_registro_a_s_db, $tipoDocumento, $documento, $motivo, $_SESSION['username']);
			if(!$stmt10->execute())
			{
				$mysqli->autocommit(TRUE);
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;						
			}
			
			if(!$stmt11 = $mysqli->prepare("UPDATE finan_cli.consulta_estado_financiero SET validado = 1 WHERE tipo_documento = ? AND documento = ? AND token = ? AND validado = 0"))
			{
				$mysqli->rollback();
				$mysqli->autocommit(TRUE);
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;
			}
			else
			{
				$stmt11->bind_param('iss', $tipoDocumento, $documento, $tokenECC2);
				if(!$stmt11->execute())
				{
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;						
				}
			}
			
			$mysqli->commit();
			$mysqli->autocommit(TRUE);
		}
		
		echo translate('Msg_Not_Supervisor_OK',$GLOBALS['lang']);
		return;
?>