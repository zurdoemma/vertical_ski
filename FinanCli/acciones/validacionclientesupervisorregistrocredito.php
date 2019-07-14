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
		$token=htmlspecialchars($_POST["token"], ENT_QUOTES, 'UTF-8');
		$tokenVC=htmlspecialchars($_POST["token2"], ENT_QUOTES, 'UTF-8');
		
		if(empty($token)) $token = 'n';
				
		$tipoDocumento=htmlspecialchars($_POST["tipoDocumento"], ENT_QUOTES, 'UTF-8');
		$documento=htmlspecialchars($_POST["documento"], ENT_QUOTES, 'UTF-8');
		
		if($stmt47 = $mysqli->prepare("SELECT c.id, c.estado, c.id_titular, c.monto_maximo_credito, c.nombres, c.apellidos, t.numero, c.id_perfil_credito FROM finan_cli.cliente c, finan_cli.telefono t, finan_cli.cliente_x_telefono ct WHERE ct.tipo_documento = c.tipo_documento AND ct.documento = c.documento AND t.id = ct.id_telefono AND c.tipo_documento = ? AND c.documento = ?"))
		{
			$stmt47->bind_param('is', $tipoDocumento, $documento);
			$stmt47->execute();    
			$stmt47->store_result();
			
			$totR47 = $stmt47->num_rows;

			if($totR47 == 0)
			{
				echo translate('Msg_Client_Not_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{
				$stmt47->bind_result($id_cliente_db, $estado_cliente_db, $id_titular_cliente_db, $monto_maximo_credito_cliente_db, $nombres_cliente_db, $apellidos_cliente_db, $telefono_cliente_db, $id_perfil_credito_cliente_db);
				$stmt47->fetch();
				
				if($estado_cliente_db != translate('State_User',$GLOBALS['lang']))
				{
					echo translate('Msg_Disable_Client',$GLOBALS['lang']);
					return;
				}
				else
				{
					if(!empty($id_titular_cliente_db))
					{
						if($stmt48 = $mysqli->prepare("SELECT c.id, c.estado, c.tipo_documento, c.documento FROM finan_cli.cliente c WHERE c.id = ?"))
						{
							$stmt48->bind_param('i', $id_titular_cliente_db);
							$stmt48->execute();    
							$stmt48->store_result();
							
							$totR48 = $stmt48->num_rows;

							if($totR48 > 0)
							{
								$stmt48->bind_result($id_cliente_titular_db, $estado_cliente_titular_db, $tipo_documento_cliente_titular_db, $documento_cliente_titular_db);
								$stmt48->fetch();
								
								if($estado_cliente_titular_db != translate('State_User',$GLOBALS['lang']))
								{
									echo translate('Msg_Disable_Client_Headline',$GLOBALS['lang']);
									return;
								}
								
								$stmt48->free_result();
								$stmt48->close();
							}
							else
							{
								echo translate('Msg_Client_Headline_Not_Exist',$GLOBALS['lang']);
								return;
							}
						}
						else
						{
							echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
							return;
						}
					}
				}
			}				
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']).$mysqli->error;
			return;
		}
			
		if(empty($id_titular_cliente_db)) $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang']);
		else $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang']);
		
		$selectMCC = "SELECT SUM(cu.monto_cuota_original) FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.cuota_credito cu WHERE c.id = cc.id_credito AND c.id = cu.id_credito AND cc.tipo_documento = ? AND cc.documento = ? AND c.estado IN (?,?)";
		if($stmt49 = $mysqli->prepare($selectMCC))
		{
			$est_pend = translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']);
			$est_mora = translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']);
			if(empty($id_titular_cliente_db)) $stmt49->bind_param('isss', $tipoDocumento, $documento, $est_pend, $est_mora);
			else $stmt49->bind_param('isss', $tipo_documento_cliente_titular_db, $documento_cliente_titular_db, $est_pend, $est_mora);
			$stmt49->execute();    
			$stmt49->store_result();
			
			$totR49 = $stmt49->num_rows;

			if($totR49 > 0)
			{
				$stmt49->bind_result($monto_credito_utilizado_db);
				$stmt49->fetch();
				
				$monto_credito_disponible = $monto_maximo_credito_cliente_db - $monto_credito_utilizado_db;
				if($monto_credito_disponible < 0) $monto_credito_disponible = 0;
				
				$stmt49->free_result();
				$stmt49->close();
			}
			else
			{
				$monto_credito_disponible = $monto_maximo_credito_cliente_db;
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}				
				
		$stmt47->free_result();
		$stmt47->close();		
		
				
		$selectVCS = "SELECT e.id, e.token FROM finan_cli.estado_cliente e WHERE e.tipo_documento = ? AND e.documento = ? AND e.fecha like ? AND e.id_motivo = ? AND e.token = ?";
		if($stmt4 = $mysqli->prepare($selectVCS))
		{
			$date_registro_a_s = date("Ymd")."%";
			if(empty($id_cliente_titular_db)) $stmt4->bind_param('issis', $tipoDocumento, $documento, $date_registro_a_s, $motivo, $token);
			else $stmt4->bind_param('issis', $tipo_documento_cliente_titular_db, $documento_cliente_titular_db, $date_registro_a_s, $motivo, $token);
			$stmt4->execute();    
			$stmt4->store_result();
			
			$totR4 = $stmt4->num_rows;

			if($totR4 > 0)
			{
				$stmt4->bind_result($id_estado_cliente_db, $token_estado_cliente_db);
				$stmt4->fetch();
				
				if($stmt61 = $mysqli->prepare("SELECT s.id_cadena FROM finan_cli.usuario u, finan_cli.sucursal s WHERE u.id_sucursal = s.id AND u.id = ?"))
				{
					$stmt61->bind_param('s', $_SESSION['username']);
					$stmt61->execute();    
					$stmt61->store_result();
					
					$totR61 = $stmt61->num_rows;

					if($totR61 > 0)
					{
						$stmt61->bind_result($id_cadena_usuario);
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
				
				if($stmt62 = $mysqli->prepare("SELECT pc.id, pc.nombre FROM finan_cli.perfil_credito_x_plan pcxp, finan_cli.plan_credito pc, finan_cli.cadena c, finan_cli.perfil_credito pcre WHERE pcxp.id_plan_credito = pc.id AND pcxp.id_perfil_credito = pcre.id AND pc.id_cadena = c.id AND pcre.id = ? AND c.id = ?"))
				{
					$stmt62->bind_param('ii', $id_perfil_credito_cliente_db, $id_cadena_usuario);
					$stmt62->execute();    
					$stmt62->store_result();
					
					$totR62 = $stmt62->num_rows;

					if($totR62 > 0)
					{
						$stmt62->bind_result($id_plan_credito_s_db, $nombre_plan_credito_s_db);
						
						while($stmt62->fetch())
						{
							if(empty($planesCreditoCli)) $planesCreditoCli = $id_plan_credito_s_db.'|'.$nombre_plan_credito_s_db;
							else $planesCreditoCli = $planesCreditoCli.';;'.$id_plan_credito_s_db.'|'.$nombre_plan_credito_s_db;
						}
						
						$stmt62->free_result();
						$stmt62->close();
						
				
						echo translate('Msg_Authorize_Client_Registration_Credit_OK',$GLOBALS['lang']).'=::=::'.$token_estado_cliente_db.'=:::=:::'.$tokenVC.'=::::=::::'.$planesCreditoCli.'=:=:'.$nombres_cliente_db.'|'.$apellidos_cliente_db.'|'.$tipo_cuenta_texto_cliente.'|'.$telefono_cliente_db.'|'.$monto_credito_disponible;
						return;								
					}
					else
					{
						echo translate('No_Credit_Plans_Associated_With_The_Customer_Credit_Profile',$GLOBALS['lang']);
						return;
					}
				}
				else
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;
				}				
			}			
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}
		
		$stmt4->free_result();
		$stmt4->close();
		
		if($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3)
		{
			$mysqli->autocommit(FALSE);
			$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
			
			if(empty($id_cliente_titular_db)) $insertVCS = "INSERT INTO finan_cli.estado_cliente(fecha,tipo_documento,documento,id_motivo,usuario,token) VALUES (?,?,?,?,?,?)";
			else $insertVCS = "INSERT INTO finan_cli.estado_cliente(fecha,tipo_documento,documento,id_motivo,usuario,token,tipo_documento_adicional,documento_adicional) VALUES (?,?,?,?,?,?,?,?)";
			if(!$stmt10 = $mysqli->prepare($insertVCS))
			{
				echo $mysqli->error;
				$mysqli->autocommit(TRUE);
				$stmt->free_result();
				$stmt->close();
				return;
			}
			else
			{
				$date_registro_a_s_db = date("YmdHis");
				$tokenECRC = md5(uniqid(rand(), true));
				$tokenECRC = hash('sha512', $tokenECRC);
				if(empty($id_cliente_titular_db)) $stmt10->bind_param('sisiss', $date_registro_a_s_db, $tipoDocumento, $documento, $motivo, $_SESSION['username'], $tokenECRC);
				else $stmt10->bind_param('sisissis', $date_registro_a_s_db, $tipo_documento_cliente_titular_db, $documento_cliente_titular_db, $motivo, $_SESSION['username'], $tokenECRC, $tipoDocumento, $documento);
				if(!$stmt10->execute())
				{
					echo $mysqli->error;
					$mysqli->autocommit(TRUE);
					$stmt->free_result();
					$stmt->close();
					return;						
				}
									
				$mysqli->commit();
				$mysqli->autocommit(TRUE);
				
				if($stmt61 = $mysqli->prepare("SELECT s.id_cadena FROM finan_cli.usuario u, finan_cli.sucursal s WHERE u.id_sucursal = s.id AND u.id = ?"))
				{
					$stmt61->bind_param('s', $_SESSION['username']);
					$stmt61->execute();    
					$stmt61->store_result();
					
					$totR61 = $stmt61->num_rows;

					if($totR61 > 0)
					{
						$stmt61->bind_result($id_cadena_usuario);
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
				
				if($stmt62 = $mysqli->prepare("SELECT pc.id, pc.nombre FROM finan_cli.perfil_credito_x_plan pcxp, finan_cli.plan_credito pc, finan_cli.cadena c, finan_cli.perfil_credito pcre WHERE pcxp.id_plan_credito = pc.id AND pcxp.id_perfil_credito = pcre.id AND pc.id_cadena = c.id AND pcre.id = ? AND c.id = ?"))
				{
					$stmt62->bind_param('ii', $id_perfil_credito_cliente_db, $id_cadena_usuario);
					$stmt62->execute();    
					$stmt62->store_result();
					
					$totR62 = $stmt62->num_rows;

					if($totR62 > 0)
					{
						$stmt62->bind_result($id_plan_credito_s_db, $nombre_plan_credito_s_db);
						
						while($stmt62->fetch())
						{
							if(empty($planesCreditoCli)) $planesCreditoCli = $id_plan_credito_s_db.'|'.$nombre_plan_credito_s_db;
							else $planesCreditoCli = $planesCreditoCli.';;'.$id_plan_credito_s_db.'|'.$nombre_plan_credito_s_db;
						}
						
						$stmt62->free_result();
						$stmt62->close();
						
						echo translate('Msg_Authorize_Client_Registration_Credit_OK',$GLOBALS['lang']).'=::=::'.$tokenECRC.'=:::=:::'.$tokenVC..'=::::=::::'.$planesCreditoCli.'=:=:'.$nombres_cliente_db.'|'.$apellidos_cliente_db.'|'.$tipo_cuenta_texto_cliente.'|'.$telefono_cliente_db.'|'.$monto_credito_disponible;
						return;								
					}
					else
					{
						echo translate('No_Credit_Plans_Associated_With_The_Customer_Credit_Profile',$GLOBALS['lang']);
						return;
					}
				}
				else
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;
				}							
			}				
		}							

		echo translate('Msg_Must_Authorize_Client_Registration_Credit',$GLOBALS['lang']);
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Lbl_Authorize_Client_Registration_Credit',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<form id="formularionacrc" role="form">';		
		echo '				<div class="form-group form-inline">';
		echo '					<label class="control-label" for="usuariosupervisorn2">'.translate('Lbl_User_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="usuariosupervisorn2">';
		echo '						<input title="'.translate('Msg_User_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="usuariosupervisorn2i" name="usuariosupervisorn2i" type="text" maxlength="50" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="passwordsupervisorn2">'.translate('Lbl_Password_Supervisor_For_Client_Additional',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="passwordsupervisorn2">';
		echo '						<input title="'.translate('Msg_Password_Supervisor_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="passwordsupervisorn2i" name="passwordsupervisorn2i" type="password" maxlength="128" />';
		echo '					</div>';		
		echo '				</div>';
		echo '				<div class="form-group form-inline">';
		echo '					<div id="img_loader_13"></div>';		
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarVS4" id="btnCancelarVS4" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogautorizacionregistrocreditocliente\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarS4" id="btnValidarS4" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarAutorizacionSupervisorRegistroCreditoCliente(document.getElementById(\'formularionacrc\'),'.$motivo.');"/>';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';		
		
		return;
?>