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
		
		$tokenVCC=htmlspecialchars($_POST["tokenVCC"], ENT_QUOTES, 'UTF-8');
		$documentoTitular=htmlspecialchars($_POST["documentoTitular"], ENT_QUOTES, 'UTF-8');
		$tipoDocumento=htmlspecialchars($_POST["tipoDocumento"], ENT_QUOTES, 'UTF-8');
		$documento=htmlspecialchars($_POST["documento"], ENT_QUOTES, 'UTF-8');
		$tokenVCC=htmlspecialchars($_POST["tokenVCC"], ENT_QUOTES, 'UTF-8');
		$tipoTelefono=htmlspecialchars($_POST["tipoTelefono"], ENT_QUOTES, 'UTF-8');		
		$prefijoTelefono=htmlspecialchars($_POST["prefijoTelefono"], ENT_QUOTES, 'UTF-8');
		$nroTelefono=htmlspecialchars($_POST["nroTelefono"], ENT_QUOTES, 'UTF-8');
		
		if($stmt4 = $mysqli->prepare("SELECT c.id FROM finan_cli.cliente c WHERE c.tipo_documento = ? AND c.documento = ?"))
		{
			$stmt4->bind_param('is', $tipoDocumento, $documento);
			$stmt4->execute();    
			$stmt4->store_result();
			
			$totR4 = $stmt4->num_rows;

			if($totR4 > 0)
			{
				echo translate('Msg_Client_Exist',$GLOBALS['lang']);
				return;
			}			
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		
		$stmt4->free_result();
		$stmt4->close();		
		
		if($tipoTelefono != 1)
		{
			echo translate('Msg_Only_Mobile_Phones_Can_Be_Validated',$GLOBALS['lang']);
			return;
		}
		
		if($stmt = $mysqli->prepare("SELECT tvc.id FROM finan_cli.token_validacion_celular tvc WHERE tvc.tipo_documento = ? AND tvc.documento = ? AND tvc.fecha like ? AND token = ? AND validado = 0"))
		{
			$date_registro_a_vcc = date("Ymd")."%";
			$stmt->bind_param('isss', $tipoDocumento, $documento, $date_registro_a_vcc, $tokenVCC);
			$stmt->execute();    
			$stmt->store_result();
			
			$totR = $stmt->num_rows;

			if($totR > 0)
			{
				echo translate('Msg_Mobile_Phones_Not_Validated',$GLOBALS['lang']);
				return;
			}			
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}

		if($stmt2 = $mysqli->prepare("SELECT tvc.id FROM finan_cli.token_validacion_celular tvc WHERE tvc.tipo_documento = ? AND tvc.documento = ? AND tvc.fecha like ? AND token = ? AND validado = 1"))
		{
			$date_registro_a_vcc = date("Ymd")."%";
			$stmt2->bind_param('isss', $tipoDocumento, $documento, $date_registro_a_vcc, $tokenVCC);
			$stmt2->execute();    
			$stmt2->store_result();
			
			$totR2 = $stmt2->num_rows;

			if($totR2 > 0)
			{
				echo translate('Msg_Validation_Mobile_Is_Not_Necessary',$GLOBALS['lang']);
				return;
			}			
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		
		$url_envio="https://apps.netelip.com/sms/api.php";
		$from="prueba";
		$destination="00543513827932";
		$message="esto es una prueba desde la api";
		
		// Creo un array con los valores a enviar.
		$postSMS = array();
		$postSMS["token"]= $GLOBALS['token_envio_sms'];
		$postSMS["from"]= $from;
		$postSMS["destination"]= $destination;
		$postSMS["message"]= $message;

		$envio_prueba = curl_init($url_envio);
		curl_setopt( $envio_prueba, CURLOPT_POST, TRUE );
		curl_setopt( $envio_prueba, CURLOPT_POSTFIELDS, $postSMS );
		curl_setopt( $envio_prueba, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $envio_prueba, CURLOPT_CAINFO, "C:\wamp\cacert.pem");
		//curl_setopt( $envio_prueba, CURLOPT_SSL_VERIFYHOST, 0 );
		//curl_setopt( $envio_prueba, CURLOPT_SSL_VERIFYPEER, 0 );

		$respuesta_envio_prueba = curl_exec( $envio_prueba );
		
		if (curl_error($envio_prueba)) 
		{
			$error_msg = curl_error($envio_prueba);
		}	
		
		if ($respuesta_envio_prueba !== false)
		{
			$https_code_envio_prueba = curl_getinfo( $envio_prueba, CURLINFO_HTTP_CODE );
			echo $https_code_envio_prueba.'  -- Nada';
			switch($https_code_envio_prueba)
			{
				case 200:
				echo "Mensaje enviado con exito";
				break;
			}
		}
		else echo 'Nada -- Nada: '.$error_msg;
		curl_close( $envio_prueba );
		
		return;
?>