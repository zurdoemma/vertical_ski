<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');return;}
		if (!verificar_permisos_supervisor()){header('Location:../sinautorizacion.php?activauto=1');return;}

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
		$motivoCancelacion=htmlspecialchars($_POST["motivoCancelacion"], ENT_QUOTES, 'UTF-8');
		
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
		
		if($stmt61 = $mysqli->prepare("SELECT s.id_cadena, s.id, s.nombre FROM ".$db_name.".usuario u, ".$db_name.".sucursal s WHERE u.id_sucursal = s.id AND u.id = ?"))
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

		if ($stmt = $mysqli->prepare("SELECT c.id, c.monto_credito_original, c.estado FROM ".$db_name.".credito c WHERE c.id = ?")) 
		{
			$stmt->bind_param('i', $idCredito);
			$stmt->execute();    
			$stmt->store_result();
	 
			$stmt->bind_result($id_credit_client, $montoTotalCredito, $estado_credit_client_a);			
			
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_Credit_Client_Not_Exist',$GLOBALS['lang']);
				return;	
			}								
			
			$stmt->fetch();

			$stmt->free_result();
			$stmt->close();
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;	
		}		
		
						
		if($stmt = $mysqli->prepare("SELECT u.id_perfil FROM ".$db_name.".usuario u WHERE u.id LIKE(?)"))
		{
			$stmt->bind_param('s', $_SESSION['username']);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR > 0)
			{
				$stmt->bind_result($perfil_usuario_control);
				$stmt->fetch();
				
				if($perfil_usuario_control != 1 && $perfil_usuario_control != 3)
				{
					echo translate('Msg_Restricted_Access',$GLOBALS['lang']);
					return;
				}
				
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				if(!$stmt10 = $mysqli->prepare("INSERT INTO ".$db_name.".token_anulacion_credito(fecha,id_credito,usuario,token,comentario) VALUES (?,?,?,?,?)"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$date_registro = date("YmdHis");
					$tokenCC = md5(uniqid(rand(), true));
					$tokenCC = hash('sha512', $tokenCC);
					$stmt10->bind_param('sisss', $date_registro, $idCredito, $_SESSION['username'], $tokenCC, $motivoCancelacion);
					if(!$stmt10->execute())
					{
						echo $mysqli->error;
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;						
					}
				}	
				
				if(!$stmt10 = $mysqli->prepare("UPDATE ".$db_name.".credito SET estado = ? WHERE id = ?"))
				{
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$estCanc = translate('Lbl_Status_Fee_Canceled',$GLOBALS['lang']);
					$stmt10->bind_param('si', $estCanc, $idCredito);
					if(!$stmt10->execute())
					{
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;						
					}
				}
	
				$date_registro = date("YmdHis");
				$valor_log_user = "ANTERIOR: UPDATE ".$db_name.".credito SET estado = ".$estado_credit_client_a." WHERE id = ".$idCredito." -- NUEVO: UPDATE ".$db_name.".credito SET estado = ".$estCanc." WHERE id = ".$idCredito;
					
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
					$motivo = 89;
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

				if(!$stmt10 = $mysqli->prepare("UPDATE ".$db_name.".cuota_credito SET estado = ? WHERE id_credito = ? AND estado IN (?,?)"))
				{
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$estCanc = translate('Lbl_Status_Fee_Canceled',$GLOBALS['lang']);
					$estPend = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
					$estEnMor = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
					$stmt10->bind_param('siss', $estCanc, $idCredito, $estPend, $estEnMor);
					if(!$stmt10->execute())
					{
						$mysqli->rollback();
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;						
					}
				}
				
				$mysqli->commit();
				$mysqli->autocommit(TRUE);
				
				if ($stmt = $mysqli->prepare("SELECT c.id, cc.fecha, td.nombre, cc.documento, c.monto_credito_original, pc.nombre, c.cantidad_cuotas, c.estado FROM ".$db_name.".credito c, ".$db_name.".credito_cliente cc, ".$db_name.".cliente cli, ".$db_name.".plan_credito pc, ".$db_name.".tipo_documento td, ".$db_name.".sucursal suc WHERE pc.id = c.id_plan_credito AND c.id = cc.id_credito AND cc.tipo_documento = cli.tipo_documento AND cc.documento = cli.documento AND cc.tipo_documento = td.id AND cc.id_sucursal = suc.id AND suc.id_cadena = ? ORDER BY cc.fecha DESC LIMIT 10")) 
				{
					$stmt->bind_param('i', $id_cadena_user);
					$stmt->execute();    
					$stmt->store_result();
			 
					$stmt->bind_result($id_credit_client, $date_credit_client, $type_documento_credit_client, $document_credit_client, $amount_credit_client, $name_credit_plan_client, $fees_credit_client, $state_credit_client);
										
					
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
						
						if($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3)
						{
							if(translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) == $state_credit_client || translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']) == $state_credit_client) $arrayC[$posicion]['acciones'] = '<button type="button" id="btnVerCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Credit_Client',$GLOBALS['lang']).'" onclick="verCredito('.$id_credit_client.')"><i class="fas fa-eye"></i></button>&nbsp;&nbsp;<button type="button" id="btnReimprimirCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Credit_Client',$GLOBALS['lang']).'" onclick="reImprimirCreditoCliente('.$id_credit_client.')"><i class="fas fa-print"></i></button>&nbsp;&nbsp;<button type="button" id="btnReimprimirPDFCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Generate_PDF_Credit_Client',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfcredito.php?idCredito='.$id_credit_client.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;&nbsp;<button type="button" id="btnGestionDeudaCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Lbl_Debt_Management2',$GLOBALS['lang']).'" onclick="window.open(\'gestiondeuda.php?doc='.$document_credit_client.'\')"><i class="fas fa-link"></i></button>&nbsp;&nbsp;<button type="button" id="btnCancelarCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Credit_Client',$GLOBALS['lang']).'" onclick="cancelarCredito('.$id_credit_client.')"><i class="far fa-window-close"></i></button>';
							else if(translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']) == $state_credit_client || translate('Lbl_Status_Fee_Condoned',$GLOBALS['lang']) == $state_credit_client || translate('Lbl_Status_Fee_Incobrable',$GLOBALS['lang']) == $state_credit_client || translate('Lbl_Status_Fee_Insolvent',$GLOBALS['lang']) == $state_credit_client)  $arrayC[$posicion]['acciones'] = '<button type="button" id="btnVerCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Credit_Client',$GLOBALS['lang']).'" onclick="verCredito('.$id_credit_client.')"><i class="fas fa-eye"></i></button>&nbsp;&nbsp;<button type="button" id="btnGestionDeudaCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Lbl_Debt_Management2',$GLOBALS['lang']).'" onclick="window.open(\'gestiondeuda.php?doc='.$document_credit_client.'\')"><i class="fas fa-link"></i></button>&nbsp;&nbsp;<button type="button" id="btnCancelarCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Credit_Client',$GLOBALS['lang']).'" onclick="cancelarCredito('.$id_credit_client.')"><i class="far fa-window-close"></i></button>';													
							else $arrayC[$posicion]['acciones'] = '<button type="button" id="btnVerCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Credit_Client',$GLOBALS['lang']).'" onclick="verCredito('.$id_credit_client.')"><i class="fas fa-eye"></i></button>';												
						}
						else
						{
							if(translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) == $state_credit_client || translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']) == $state_credit_client) $arrayC[$posicion]['acciones'] = '<button type="button" id="btnVerCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Credit_Client',$GLOBALS['lang']).'" onclick="verCredito('.$id_credit_client.')"><i class="fas fa-eye"></i></button>&nbsp;&nbsp;<button type="button" id="btnReimprimirCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Credit_Client',$GLOBALS['lang']).'" onclick="reImprimirCreditoCliente('.$id_credit_client.')"><i class="fas fa-print"></i></button>&nbsp;&nbsp;<button type="button" id="btnReimprimirPDFCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Generate_PDF_Credit_Client',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfcredito.php?idCredito='.$id_credit_client.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;&nbsp;<button type="button" id="btnGestionDeudaCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Lbl_Debt_Management2',$GLOBALS['lang']).'" onclick="window.open(\'gestiondeuda.php?doc='.$document_credit_client.'\')"><i class="fas fa-link"></i></button>';
							else $arrayC[$posicion]['acciones'] = '<button type="button" id="btnVerCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Credit_Client',$GLOBALS['lang']).'" onclick="verCredito('.$id_credit_client.')"><i class="fas fa-eye"></i></button>&nbsp;&nbsp;<button type="button" id="btnGestionDeudaCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Lbl_Debt_Management2',$GLOBALS['lang']).'" onclick="window.open(\'gestiondeuda.php?doc='.$document_credit_client.'\')"><i class="fas fa-link"></i></button>';																	
						}
						$posicion++;
					}
					 
					echo translate('Msg_Cancel_Credit_Client_OK',$GLOBALS['lang']).'=::=::'.json_encode($arrayC);
					return;
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
?>