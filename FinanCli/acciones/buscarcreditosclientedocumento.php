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
		
		$documento=htmlspecialchars($_POST["documento"], ENT_QUOTES, 'UTF-8');

		if ($stmt = $mysqli->prepare("SELECT c.id, cc.fecha, td.nombre, cc.documento, c.monto_credito_original, pc.nombre, c.cantidad_cuotas, c.estado FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.cliente cli, finan_cli.plan_credito pc, finan_cli.tipo_documento td WHERE pc.id = c.id_plan_credito AND c.id = cc.id_credito AND cc.tipo_documento = cli.tipo_documento AND cc.documento = cli.documento AND cc.tipo_documento = td.id AND cli.documento = ? ORDER BY cc.fecha DESC")) 
		{
			$stmt->bind_param('s', $documento);
			$stmt->execute();    
			$stmt->store_result();
	 
			$stmt->bind_result($id_credit_client, $date_credit_client, $type_documento_credit_client, $document_credit_client, $amount_credit_client, $name_credit_plan_client, $fees_credit_client, $state_credit_client);			
			
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_Without_Credit_Client',$GLOBALS['lang']).'=::=::=::'.$documento;
				return;	
			}					
			
			$arrayC[0] = array();
			$posicion = 0;
			while($stmt->fetch())
			{
				$arrayC[$posicion]['fecha'] = substr($date_credit_client,6,2).'/'.substr($date_credit_client,4,2).'/'.substr($date_credit_client,0,4);
				$arrayC[$posicion]['tipodocumento'] = $type_documento_credit_client;
				$arrayC[$posicion]['documento'] = $document_credit_client;
				$arrayC[$posicion]['monto'] = '$'.round(($amount_credit_client/100.00),2);
				$arrayC[$posicion]['plancredito'] = $name_credit_plan_client;
				$arrayC[$posicion]['cuotas'] = $fees_credit_client;
				$arrayC[$posicion]['estado'] = $state_credit_client;
								
				$arrayC[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Credit_Client',$GLOBALS['lang']).'" onclick="verCredito('.$id_credit_client.')"><i class="fas fa-eye"></i></button>';												

				$posicion++;
			}
			
			echo translate('Msg_Search_Credit_Client_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($arrayC).'=::=::=::'.$documento;
			return;
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;	
		}

		return;
?>