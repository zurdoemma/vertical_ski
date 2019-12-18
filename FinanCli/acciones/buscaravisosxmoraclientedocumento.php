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
		
		if($stmt62 = $mysqli->prepare("SELECT c.id_titular FROM ".$db_name.".cliente c, ".$db_name.".credito_cliente cc, ".$db_name.".aviso_x_mora axm WHERE cc.tipo_documento = c.tipo_documento AND cc.documento = c.documento AND axm.id_credito = cc.id_credito AND c.documento = ?"))
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
				echo translate('Msg_Without_Default_Notices',$GLOBALS['lang']).'=::=::=::'.$documento;
				return;
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}

		if(!empty($tipo_documento_titular) && !empty($documento_titular)) $selecBDC = "SELECT axm.id, axm.fecha, td.nombre, cc.documento, axm.estado, axm.id_credito, ccre.numero_cuota FROM ".$db_name.".credito c, ".$db_name.".credito_cliente cc, ".$db_name.".aviso_x_mora axm, ".$db_name.".tipo_documento td, ".$db_name.".cuota_credito ccre, ".$db_name.".sucursal suc WHERE c.id = cc.id_credito AND axm.id_credito = c.id AND cc.tipo_documento = td.id AND ccre.id_credito = c.id AND ccre.id = axm.id_cuota_credito AND cc.id_sucursal = suc.id AND suc.id_cadena = ? AND cc.tipo_documento = ? AND cc.documento = ? AND cc.documento_adicional = ? ORDER BY axm.fecha DESC";
		else $selecBDC = "SELECT axm.id, axm.fecha, td.nombre, cc.documento, axm.estado, axm.id_credito, ccre.numero_cuota FROM ".$db_name.".credito c, ".$db_name.".credito_cliente cc, ".$db_name.".aviso_x_mora axm, ".$db_name.".tipo_documento td, ".$db_name.".cuota_credito ccre, ".$db_name.".sucursal suc WHERE c.id = cc.id_credito AND axm.id_credito = c.id AND cc.tipo_documento = td.id AND ccre.id_credito = c.id AND ccre.id = axm.id_cuota_credito AND cc.id_sucursal = suc.id AND suc.id_cadena = ? AND cc.documento = ? ORDER BY axm.fecha DESC";
		if ($stmt = $mysqli->prepare($selecBDC)) 
		{
			if(!empty($tipo_documento_titular) && !empty($documento_titular)) $stmt->bind_param('iiss', $id_cadena_user, $tipo_documento_titular, $documento_titular, $documento);
			else $stmt->bind_param('is', $id_cadena_user, $documento);
			$stmt->execute();    
			$stmt->store_result();
	 
			$stmt->bind_result($id_default_notice_client, $date_default_notice_client, $type_documento_default_notice_client, $document_default_notice_client, $state_default_notice_client, $id_credito_default_notice_client, $numero_cuota_default_notice_client);			
			
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_Without_Default_Notices',$GLOBALS['lang']).'=::=::=::'.$documento;
				return;	
			}					
			
			$arrayC[0] = array();
			$posicion = 0;
			while($stmt->fetch())
			{
				$arrayC[$posicion]['fecha'] = substr($date_default_notice_client,6,2).'/'.substr($date_default_notice_client,4,2).'/'.substr($date_default_notice_client,0,4).' '.substr($date_default_notice_client,8,2).':'.substr($date_default_notice_client,10,2).':'.substr($date_default_notice_client,12,2);
				$arrayC[$posicion]['tipodocumento'] = $type_documento_default_notice_client;
				$arrayC[$posicion]['documento'] = $document_default_notice_client;
				$arrayC[$posicion]['nrocredito'] = $id_credito_default_notice_client;
				$arrayC[$posicion]['nrocuota'] = $numero_cuota_default_notice_client;
				$arrayC[$posicion]['estado'] = $state_default_notice_client;
								
				$arrayC[$posicion]['acciones'] = '<button type="button" id="verAvisoXM'.$id_default_notice_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Detail_Default_Notice',$GLOBALS['lang']).'" onclick="verAvisoXMoraCuotaCredito('.$id_default_notice_client.')"><i class="fas fa-eye"></i></button>';													

				$posicion++;
			}
			
			echo translate('Msg_Search_Default_Notices_OK',$GLOBALS['lang']).'=:=:=:'.json_encode($arrayC).'=::=::=::'.$documento;
			return;
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;	
		}

		return;
?>