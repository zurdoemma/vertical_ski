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
		
		if($stmt61 = $mysqli->prepare("SELECT s.id_cadena, s.id, s.nombre FROM finan_cli.usuario u, finan_cli.sucursal s WHERE u.id_sucursal = s.id AND u.id = ?"))
		{
			$stmt61->bind_param('s', $_SESSION['username']);
			$stmt61->execute();    
			$stmt61->store_result();
			
			$totR61 = $stmt61->num_rows;

			if($totR61 > 0)
			{
				$stmt61->bind_result($id_cadena_usuario, $id_sucursal_usuario, $nombre_sucursal_usuario);
				$stmt61->fetch();
								
				$stmt61->free_result();
				$stmt61->close();
			}
			else
			{
				echo translate('There_Is_ No_Chain_Associated_With_The_User',$GLOBALS['lang']);
				return;
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}		

		if ($stmt = $mysqli->prepare("SELECT c.id, cc.fecha, c.cantidad_cuotas, pc.nombre, cli.nombres, cli.apellidos, cli.id_titular, c.monto_credito_original, td.nombre, cli.documento FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.cliente cli, finan_cli.plan_credito pc, finan_cli.tipo_documento td WHERE pc.id = c.id_plan_credito AND c.id = cc.id_credito AND cc.tipo_documento = cli.tipo_documento AND cc.documento = cli.documento AND cc.tipo_documento = td.id AND c.id = ? AND c.estado IN (?,?)")) 
		{
			$estado_p_1 = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
			$estado_p_2 = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
			$stmt->bind_param('iss', $idCredito, $estado_p_1, $estado_p_2);
			$stmt->execute();    
			$stmt->store_result();
	 
			$stmt->bind_result($id_credit_client, $fecha_cre_pi, $cantidad_cuotas_plan_credito_s_db, $nombre_plan_credito_s_db, $nombres_cliente_db, $apellidos_cliente_db, $id_titular_cliente_db, $montoTotalCredito, $nombre_tipo_documento_cliente_db, $documento);			
			
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_Credit_Client_Not_Exist',$GLOBALS['lang']);
				return;	
			}					
			
			
			if($stmt62 = $mysqli->prepare("SELECT cc.fecha_vencimiento FROM finan_cli.cuota_credito cc WHERE cc.id_credito = ? AND cc.estado IN (?,?) ORDER BY cc.numero_cuota"))
			{
				$stmt62->bind_param('iss', $idCredito, $estado_p_1, $estado_p_2);
				$stmt62->execute();    
				$stmt62->store_result();
				
				$totR62 = $stmt62->num_rows;

				if($totR62 > 0)
				{
					$stmt62->bind_result($fecha_vencimiento_cuota_db);
					$stmt62->fetch();
									
					$stmt62->free_result();
					$stmt62->close();
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
			
			$stmt->fetch();
			$fecha_cre_pi = substr($fecha_cre_pi,6,2).'-'.substr($fecha_cre_pi,4,2).'-'.substr($fecha_cre_pi,0,4).' '.substr($fecha_cre_pi,8,2).':'.substr($fecha_cre_pi,10,2).':'.substr($fecha_cre_pi,12,2);
			if(empty($id_titular_cliente_db)) $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang']);
			else $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang']);			
			
			$datosDeImpresion = $idCredito.'|'.$fecha_cre_pi.'|'.$nombre_sucursal_usuario.'|'.$cantidad_cuotas_plan_credito_s_db.'|'.$fecha_vencimiento_cuota_db.'|'.$nombre_plan_credito_s_db.'|'.$nombres_cliente_db.' '.$apellidos_cliente_db.'|'.$tipo_cuenta_texto_cliente.'|'.$montoTotalCredito.'|'.$nombre_tipo_documento_cliente_db.'|'.$documento; 
			echo translate('Msg_Reprint_Credit_Client_OK',$GLOBALS['lang']).'=:=:=:'.$datosDeImpresion;
			$stmt->free_result();
			$stmt->close();
			return;
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;	
		}

		return;
?>