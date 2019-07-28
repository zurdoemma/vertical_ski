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
		
		$idCredito=htmlspecialchars($_POST["idCredito"], ENT_QUOTES, 'UTF-8');
		$idCuotaCredito=htmlspecialchars($_POST["idCuotaCredito"], ENT_QUOTES, 'UTF-8');
				
		if($stmt68 = $mysqli->prepare("SELECT c.estado, cli.id_titular, cli.nombres, cli.apellidos, s.nombre, cc.numero_cuota, cc.usuario_registro_pago, td.nombre, cli.documento, cc.monto_cuota_original, cc.fecha_pago, cc.monto_pago FROM finan_cli.credito c, finan_cli.credito_cliente ccli, finan_cli.cliente cli, finan_cli.cuota_credito cc, finan_cli.sucursal s, finan_cli.tipo_documento td WHERE c.id = ccli.id_credito AND c.id = cc.id_credito AND ccli.tipo_documento = cli.tipo_documento AND ccli.documento = cli.documento AND ccli.id_sucursal = s.id AND cli.tipo_documento = td.id AND c.id = ? AND cc.id = ?"))
		{
			$stmt68->bind_param('ii', $idCredito, $idCuotaCredito);
			$stmt68->execute();    
			$stmt68->store_result();
			
			$totR68 = $stmt68->num_rows;

			if($totR68 > 0)
			{
				$stmt68->bind_result($estado_credito_db_res, $id_titular_cliente_db_res, $nombres_cliente_db_res, $apellidos_cliente_db_res, $nombre_sucursal_db_res, $numero_cuota_db_res, $usuario_registro_pago_cuota_db_res, $tipo_documento_cliente_db_res, $documento_cliente_db_res, $monto_cuota_original_db_res, $fecha_pago_cuota_db_res, $monto_pago_cuota_db_res);
				$stmt68->fetch();
				
				$stmt68->free_result();
				$stmt68->close();
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
		
		if($stmt69 = $mysqli->prepare("SELECT cc.fecha_vencimiento FROM finan_cli.cuota_credito cc WHERE cc.id_credito = ? AND cc.estado IN (?,?) ORDER BY cc.numero_cuota"))
		{
			$estado_p_1 = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
			$estado_p_2 = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
			$stmt69->bind_param('iss', $idCredito, $estado_p_1, $estado_p_2);
			$stmt69->execute();    
			$stmt69->store_result();
			
			$totR69 = $stmt69->num_rows;

			if($totR69 > 0)
			{
				$stmt69->bind_result($fecha_vencimiento_cuota_db_res);
				$stmt69->fetch();
								
				$stmt69->free_result();
				$stmt69->close();
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}

		if($stmt70 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM finan_cli.mora_cuota_credito mcc, finan_cli.cuota_credito cc WHERE mcc.id_cuota_credito = cc.id AND cc.id_credito = ? AND cc.id = ?"))
		{
			$stmt70->bind_param('ii', $idCredito, $idCuotaCredito);
			$stmt70->execute();    
			$stmt70->store_result();
			
			$totR70 = $stmt70->num_rows;

			if($totR70 > 0)
			{
				$stmt70->bind_result($monto_interes_cuota_credito_db_res);
				$stmt70->fetch();
				
				$stmt70->free_result();
				$stmt70->close();
			}
			else $monto_interes_cuota_credito_db_res = 0;
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}						
		
		if(empty($id_titular_cliente_db_res)) $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang']);
		else $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang']);	
		
		$date_registro = date("YmdHis");				
		$valor_log_user = translate('Msg_Reprint_Pay_Fee_Credit_Client_db',$GLOBALS['lang']).': '.$idCuotaCredito;

		
		$mysqli->autocommit(FALSE);
		$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
	
		if(!$stmt75 = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
		{
			echo $mysqli->error;
			$mysqli->autocommit(TRUE);
			$stmt75->free_result();
			$stmt75->close();
			return;
		}
		else
		{
			$motivo = 70;
			$stmt75->bind_param('ssis', $_SESSION['username'], $date_registro, $motivo, $valor_log_user);
			if(!$stmt75->execute())
			{
				echo $mysqli->error;
				$mysqli->autocommit(TRUE);
				$stmt75->free_result();
				$stmt75->close();
				return;						
			}
		}			
				
		$mysqli->commit();
		$mysqli->autocommit(TRUE);		
		
		echo translate('Msg_Reprint_Pay_Fee_Credit_Client_OK',$GLOBALS['lang']).'=:=:=:'.$fecha_pago_cuota_db_res.'|'.$idCredito.'|'.$numero_cuota_db_res.'|'.$tipo_cuenta_texto_cliente.'|'.$nombres_cliente_db_res.' '.$apellidos_cliente_db_res.'|'.$nombre_sucursal_db_res.'|'.$usuario_registro_pago_cuota_db_res.'|'.$monto_pago_cuota_db_res.'|'.$fecha_vencimiento_cuota_db_res.'|'.$tipo_documento_cliente_db_res.'|'.$documento_cliente_db_res.'|'.$monto_cuota_original_db_res.'|'.$monto_interes_cuota_credito_db_res;
		return;
?>