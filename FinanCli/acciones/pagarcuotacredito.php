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
		$montoPago=htmlspecialchars($_POST["montoPago"], ENT_QUOTES, 'UTF-8');
		$tokenVS=htmlspecialchars($_POST["tokenVS"], ENT_QUOTES, 'UTF-8');
		
		if($montoPago < 0)
		{
			echo translate('Negative_Numbers_Are_Not_Allowed',$GLOBALS['lang']);
			return;
		}
		
		if($montoPago == 0)
		{
			echo translate('The_Value_Entered_Is_Not_Allowed_Pay_Fee_Credit',$GLOBALS['lang']);
			return;
		}		
		
		if($stmt63 = $mysqli->prepare("SELECT cc.numero_cuota, cc.monto_cuota_original, c.monto_compra, c.cantidad_cuotas, ccli.documento, ccli.tipo_documento FROM finan_cli.cuota_credito cc, finan_cli.credito c, finan_cli.credito_cliente ccli WHERE c.id = ccli.id_credito AND c.id = cc.id_credito AND cc.id = ? AND cc.id_credito = ? AND cc.estado IN (?,?)"))
		{
			$estadoU = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
			$estadoD = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
			$stmt63->bind_param('iiss', $idCuotaCredito, $idCredito, $estadoU, $estadoD);
			$stmt63->execute();    
			$stmt63->store_result();
			
			$totR63 = $stmt63->num_rows;

			if($totR63 > 0)
			{
				$stmt63->bind_result($numero_cuota_db, $monto_cuota_original_db, $monto_compra_orig_credito_db, $cantidad_cuotas_credito_db, $documento_cliente_credito_db, $tipo_documento_cliente_credito_db);
				$stmt63->fetch();
				
				if(($monto_compra_orig_credito_db/$cantidad_cuotas_credito_db) > $montoPago)
				{
					echo translate('The_Payment_Amount_Cannot_Be_Less_Than_The_Interest_Free_Installment_Pay_Fee_Credit',$GLOBALS['lang']);
					return;	
				}
				
				$stmt63->free_result();
				$stmt63->close();				
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
		
		if($stmt64 = $mysqli->prepare("SELECT SUM(mcc.monto_interes) FROM finan_cli.mora_cuota_credito mcc WHERE mcc.id_cuota_credito = ?"))
		{
			$stmt64->bind_param('i', $idCuotaCredito);
			$stmt64->execute();    
			$stmt64->store_result();
			
			$totR64 = $stmt64->num_rows;
			$monto_interes_cuota_credito = 0;
			if($totR64 == 1)
			{
				$stmt64->bind_result($monto_interes_cuota_credito_db);
				$stmt64->fetch();
				
				$monto_interes_cuota_credito = $monto_interes_cuota_credito_db;
				
				$stmt64->free_result();
				$stmt64->close();				
			}
			else if($totR64 == 0) 
			{
				$monto_interes_cuota_credito = 0;			
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
		
		if(!empty($tokenVS))
		{
			if($stmt65 = $mysqli->prepare("SELECT tpc.validado FROM finan_cli.token_pago_cuota tpc WHERE tpc.token = ? AND tpc.documento = ? AND tpc.tipo_documento = ? AND fecha LIKE ?"))
			{
				$date_registro_a_c_db = date("Ymd").'%';
				$stmt65->bind_param('ssis', $tokenVS, $documento_cliente_credito_db, $tipo_documento_cliente_credito_db, $date_registro_a_c_db);
				$stmt65->execute();    
				$stmt65->store_result();
				
				$totR65 = $stmt65->num_rows;
				if($totR65 > 0)
				{
					$stmt65->bind_result($validacion_token_pago_cuota_db);
					$stmt65->fetch();
					
					if($validacion_token_pago_cuota_db == 0)
					{
						echo translate('Msg_Need_Authorize_Pay_Fee_Credit',$GLOBALS['lang']).'=::=::=::'.$tokenVS.'=:::=:::';
						echo '<div class="panel-group">';				
						echo '	<div class="panel panel-default">';
						echo '		<div id="panel-title-header" class="panel-heading">';
						echo '			<h3 class="panel-title">'.translate('Lbl_Authorize_Pay_Fee_Credit',$GLOBALS['lang']).'</h3>';
						echo ' 		</div>';
						echo '		<div class="panel-body">';
						echo '			<form id="formularionaspcc" role="form">';		
						echo '				<div class="form-group form-inline">';
						echo '					<label class="control-label" for="usuariosupervisorn">'.translate('Lbl_User_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
						echo '					<div class="form-group" id="usuariosupervisorn">';
						echo '						<input title="'.translate('Msg_User_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="usuariosupervisorni" name="usuariosupervisorni" type="text" maxlength="50" />';
						echo '					</div>';
						echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="passwordsupervisorn">'.translate('Lbl_Password_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
						echo '					<div class="form-group" id="passwordsupervisorn">';
						echo '						<input title="'.translate('Msg_Password_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="passwordsupervisorni" name="passwordsupervisorni" type="password" maxlength="128" />';
						echo '					</div>';		
						echo '				</div>';
						echo '				<div class="form-group form-inline">';
						echo '					<div id="img_loader_13"></div>';		
						echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarVS" id="btnCancelarVS" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogvalidsuppagocuotacredit\').dialog(\'close\');" style="margin-left:10px;" />';
						echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarS" id="btnValidarS" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarAutorizacionSupervisorPagoCuota(document.getElementById(\'formularionaspcc\'));"/>';										
						echo '				</div>';				
						echo '			</form>';
						echo '		</div>';
						echo '	</div>';
						echo '</div>';
						
						return;
					}
					else
					{
						if($montoPago > ($monto_cuota_original_db+$monto_interes_cuota_credito))
						{
							echo translate('The_Payment_Amount_Cannot_Be_Greater_Than_The_Total_Amount_Of_The_Fee_Pay_Fee_Credit',$GLOBALS['lang']);
							return;			
						}
						
						$esUltimaCuota = 0;
						
						if($numero_cuota_db == $cantidad_cuotas_credito_db) $esUltimaCuota = 1;
						
						$mysqli->autocommit(FALSE);
						$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
						
						if(!$stmt43 = $mysqli->prepare("INSERT INTO finan_cli.token_pago_cuota(fecha,tipo_documento,documento,id_motivo,token,usuario,validado) VALUES (?,?,?,?,?,?,?)"))
						{
							echo $mysqli->error;
							$mysqli->autocommit(TRUE);
							$stmt->free_result();
							$stmt->close();
							return;
						}
						else
						{
							$date_registro_a_pcc_db = date("YmdHis");
							$motivo = 67;
							$token = md5(uniqid(rand(), true));
							$token = hash('sha512', $token);
							$validacionI = 0;
							$stmt43->bind_param('sisissi', $date_registro_a_pcc_db, $tipo_documento_cliente_credito_db, $documento_cliente_credito_db, $motivo, $token, $_SESSION['username'], $validacionI);
							if(!$stmt43->execute())
							{
								echo $mysqli->error;
								$mysqli->autocommit(TRUE);
								$stmt->free_result();
								$stmt->close();
								return;						
							}
												
							$mysqli->commit();
							$mysqli->autocommit(TRUE);		
						}						
						
						$mysqli->commit();
						$mysqli->autocommit(TRUE);
					}
					
					$stmt65->free_result();
					$stmt65->close();				
				}
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;
			}
		}			

		
		if($montoPago > ($monto_cuota_original_db+$monto_interes_cuota_credito))
		{
			echo translate('The_Payment_Amount_Cannot_Be_Greater_Than_The_Total_Amount_Of_The_Fee_Pay_Fee_Credit',$GLOBALS['lang']);
			return;			
		}
		else if($montoPago < ($monto_cuota_original_db+$monto_interes_cuota_credito))
		{
			
			if($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3)
			{
				
				
				echo translate('Msg_Pay_Fee_Credit_OK',$GLOBALS['lang']);
				return;
			}
			else
			{
				$mysqli->autocommit(FALSE);
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				if(!$stmt43 = $mysqli->prepare("INSERT INTO finan_cli.token_pago_cuota(fecha,tipo_documento,documento,id_motivo,token,usuario,validado) VALUES (?,?,?,?,?,?,?)"))
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;
				}
				else
				{
					$date_registro_a_pcc_db = date("YmdHis");
					$motivo = 67;
					$token = md5(uniqid(rand(), true));
					$token = hash('sha512', $token);
					$validacionI = 0;
					$stmt43->bind_param('sisissi', $date_registro_a_pcc_db, $tipo_documento_cliente_credito_db, $documento_cliente_credito_db, $motivo, $token, $_SESSION['username'], $validacionI);
					if(!$stmt43->execute())
					{
						echo $mysqli->error;
						$mysqli->autocommit(TRUE);
						$stmt->free_result();
						$stmt->close();
						return;						
					}
										
					$mysqli->commit();
					$mysqli->autocommit(TRUE);		
				}
				echo translate('Msg_Need_Authorize_Pay_Fee_Credit',$GLOBALS['lang']).'=::=::=::'.$token.'=:::=:::';
				echo '<div class="panel-group">';				
				echo '	<div class="panel panel-default">';
				echo '		<div id="panel-title-header" class="panel-heading">';
				echo '			<h3 class="panel-title">'.translate('Lbl_Authorize_Pay_Fee_Credit',$GLOBALS['lang']).'</h3>';
				echo ' 		</div>';
				echo '		<div class="panel-body">';
				echo '			<form id="formularionaspcc" role="form">';		
				echo '				<div class="form-group form-inline">';
				echo '					<label class="control-label" for="usuariosupervisorn">'.translate('Lbl_User_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="usuariosupervisorn">';
				echo '						<input title="'.translate('Msg_User_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="usuariosupervisorni" name="usuariosupervisorni" type="text" maxlength="50" />';
				echo '					</div>';
				echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="passwordsupervisorn">'.translate('Lbl_Password_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
				echo '					<div class="form-group" id="passwordsupervisorn">';
				echo '						<input title="'.translate('Msg_Password_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="passwordsupervisorni" name="passwordsupervisorni" type="password" maxlength="128" />';
				echo '					</div>';		
				echo '				</div>';
				echo '				<div class="form-group form-inline">';
				echo '					<div id="img_loader_13"></div>';		
				echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarVS" id="btnCancelarVS" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogvalidsuppagocuotacredit\').dialog(\'close\');" style="margin-left:10px;" />';
				echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarS" id="btnValidarS" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarAutorizacionSupervisorPagoCuota(document.getElementById(\'formularionaspcc\'));"/>';										
				echo '				</div>';				
				echo '			</form>';
				echo '		</div>';
				echo '	</div>';
				echo '</div>';
			}

			return;
		}
		else
		{
			echo 'Grabar pago sin autorizacion';
			return;
		}

		return;
?>