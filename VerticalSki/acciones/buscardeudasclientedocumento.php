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
		
		if ($stmt500 = $mysqli->prepare("SELECT c.id FROM ".$db_name.".cadena c, ".$db_name.".usuario u, ".$db_name.".sucursal s WHERE u.id_sucursal = s.id AND s.id_cadena = c.id AND u.id = ?")) 
		{
			$stmt500->bind_param('s', $_SESSION['username']);
			$stmt500->execute();    
			$stmt500->store_result();
	 
			$totR500 = $stmt500->num_rows;
			if($totR500 > 0)
			{
				$stmt500->bind_result($id_cadena_user);
				$stmt500->fetch();

				$stmt500->free_result();
				$stmt500->close();				
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
		
		if($stmt62 = $mysqli->prepare("SELECT c.id_titular FROM ".$db_name.".cliente c WHERE c.documento = ?"))
		{
			$stmt62->bind_param('s', $documento);
			$stmt62->execute();    
			$stmt62->store_result();
			
			$totR62 = $stmt62->num_rows;

			if($totR62 > 0)
			{
				$stmt62->bind_result($id_titular_cliente_db);
				$stmt62->fetch();
				
				if(!empty($id_titular_cliente_db))
				{
					if($stmt63 = $mysqli->prepare("SELECT c.tipo_documento, c.documento FROM ".$db_name.".cliente c WHERE c.id = ?"))
					{
						$stmt63->bind_param('i', $id_titular_cliente_db);
						$stmt63->execute();    
						$stmt63->store_result();
						
						$totR63 = $stmt63->num_rows;

						if($totR63 > 0)
						{
							$stmt63->bind_result($tipo_documento_titular, $documento_titular);
							$stmt63->fetch();
							
							$stmt63->free_result();
							$stmt63->close();				
						}
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;
					}					
					
				}
				
				$stmt62->free_result();
				$stmt62->close();
			}
			else 
			{
				echo translate('Msg_Without_Credit_Client',$GLOBALS['lang']).'=::=::=::'.$documento;
				return;
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}

		if(empty($id_titular_cliente_db)) $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang']);
		else $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang']);	
		
		if(!empty($tipo_documento_titular) && !empty($documento_titular)) $selecBDC = "SELECT c.id, cc.fecha, td.nombre, cc.documento, c.monto_credito_original, pc.nombre, c.cantidad_cuotas, c.estado, cc.tipo_documento_adicional, cc.documento_adicional FROM ".$db_name.".credito c, ".$db_name.".credito_cliente cc, ".$db_name.".cliente cli, ".$db_name.".plan_credito pc, ".$db_name.".tipo_documento td, ".$db_name.".sucursal suc WHERE pc.id = c.id_plan_credito AND c.id = cc.id_credito AND cc.tipo_documento = cli.tipo_documento AND cc.documento = cli.documento AND cc.tipo_documento = td.id AND cc.id_sucursal = suc.id AND suc.id_cadena = ? AND cc.tipo_documento = ? AND cc.documento = ? AND cc.documento_adicional = ? ORDER BY cc.fecha DESC";
		else $selecBDC = "SELECT c.id, cc.fecha, td.nombre, cc.documento, c.monto_credito_original, pc.nombre, c.cantidad_cuotas, c.estado FROM ".$db_name.".credito c, ".$db_name.".credito_cliente cc, ".$db_name.".cliente cli, ".$db_name.".plan_credito pc, ".$db_name.".tipo_documento td, ".$db_name.".sucursal suc WHERE pc.id = c.id_plan_credito AND c.id = cc.id_credito AND cc.tipo_documento = cli.tipo_documento AND cc.documento = cli.documento AND cc.tipo_documento = td.id AND cc.id_sucursal = suc.id AND suc.id_cadena = ? AND cli.documento = ? ORDER BY cc.fecha DESC";
		if ($stmt = $mysqli->prepare($selecBDC)) 
		{
			if(!empty($tipo_documento_titular) && !empty($documento_titular)) $stmt->bind_param('iiss', $id_cadena_user, $tipo_documento_titular, $documento_titular, $documento);
			else $stmt->bind_param('is', $id_cadena_user, $documento);
			$stmt->execute();    
			$stmt->store_result();
	 
			if(!empty($tipo_documento_titular) && !empty($documento_titular)) $stmt->bind_result($id_credit_client, $date_credit_client, $type_documento_credit_client, $document_credit_client, $amount_credit_client, $name_credit_plan_client, $fees_credit_client, $state_credit_client, $tipo_documento_adicional_client, $documento_adicional_client);			
			else $stmt->bind_result($id_credit_client, $date_credit_client, $type_documento_credit_client, $document_credit_client, $amount_credit_client, $name_credit_plan_client, $fees_credit_client, $state_credit_client);			
			
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
				$arrayC[$posicion]['idcredito'] = $id_credit_client;
				$arrayC[$posicion]['fecha'] = substr($date_credit_client,6,2).'/'.substr($date_credit_client,4,2).'/'.substr($date_credit_client,0,4);
				if(!empty($tipo_documento_titular) && !empty($documento_titular))
				{
					$arrayC[$posicion]['tipodocumento'] = $tipo_documento_adicional_client;
					$arrayC[$posicion]['documento'] = $documento_adicional_client;					
				}
				else
				{
					$arrayC[$posicion]['tipodocumento'] = $type_documento_credit_client;
					$arrayC[$posicion]['documento'] = $document_credit_client;
				}
				$arrayC[$posicion]['tipocuenta'] = $tipo_cuenta_texto_cliente;
				$arrayC[$posicion]['monto'] = '$'.round(($amount_credit_client/100.00),2);
				$arrayC[$posicion]['plancredito'] = $name_credit_plan_client;
				$arrayC[$posicion]['cuotas'] = $fees_credit_client;
				$arrayC[$posicion]['estado'] = $state_credit_client;
								
				$arrayC[$posicion]['acciones'] = '<button type="button" id="btnEditarDeuda'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Modify_Debt_Client',$GLOBALS['lang']).'" onclick="verCredito('.$id_credit_client.','.$posicion.')"><i class="far fa-edit"></i></button>';													

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