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
		
		$tipoDocumento=htmlspecialchars($_POST["tipoDocumento"], ENT_QUOTES, 'UTF-8');
		$documento=htmlspecialchars($_POST["documento"], ENT_QUOTES, 'UTF-8');	

		$tipoDocumentoTitular=htmlspecialchars($_POST["tipoDocumentoTitular"], ENT_QUOTES, 'UTF-8');
		$documentoTitular=htmlspecialchars($_POST["documentoTitular"], ENT_QUOTES, 'UTF-8');		

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
		
		if($stmt = $mysqli->prepare("SELECT p.valor FROM finan_cli.parametros p WHERE p.nombre = 'limite_adicionales_sin_supervisor'"))
		{
			$stmt->execute();    
			$stmt->store_result();
			
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;
			}
			$stmt->bind_result($limite_adicional_sin_supervisor_db);
			$stmt->fetch();
			
			if($stmt2 = $mysqli->prepare("SELECT p.valor FROM finan_cli.parametros p WHERE p.nombre = 'limite_clientes_adicionales'"))
			{
				$stmt2->execute();    
				$stmt2->store_result();
				
				$totR2 = $stmt2->num_rows;

				if($totR2 == 0)
				{
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;
				}

				$stmt2->bind_result($limite_adicionales_db);
				$stmt2->fetch();

				if($stmt3 = $mysqli->prepare("SELECT count(c.id_titular) FROM finan_cli.cliente c WHERE c.id_titular = (SELECT c2.id FROM finan_cli.cliente c2 WHERE c2.tipo_documento = ? AND c2.documento = ?)"))
				{
					$stmt3->bind_param('is', $tipoDocumento, $documento);
					$stmt3->execute();    
					$stmt3->store_result();
					
					$totR3 = $stmt3->num_rows;

					if($totR3 == 0)
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;
					}

					$stmt3->bind_result($cantidad_adicionales_db);
					$stmt3->fetch();
					
					if($cantidad_adicionales_db >= $limite_adicionales_db)
					{
						$stmt3->free_result();
						$stmt3->close();
						
						$stmt2->free_result();
						$stmt2->close();
						
						$stmt->free_result();
						$stmt->close();						
						echo translate('Msg_The_Number_Of_Additional_Customers_Allowed_Was_Exceeded',$GLOBALS['lang']);
						return;	
					}
					
					if($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3)
					{
						echo translate('Msg_It_Is_Not_Necessary_To_Authorize',$GLOBALS['lang']);
						return;
					}
					
					if($cantidad_adicionales_db >= $limite_adicionales_sin_supervisor)
					{
						$stmt3->free_result();
						$stmt3->close();
						
						$stmt2->free_result();
						$stmt2->close();
						
						$stmt->free_result();
						$stmt->close();						
						echo translate('Msg_Must_Authorize_Additional',$GLOBALS['lang']);

						echo '<div class="panel-group">';				
						echo '	<div class="panel panel-default">';
						echo '		<div id="panel-title-header" class="panel-heading">';
						echo '			<h3 class="panel-title">'.translate('Lbl_Authorize_Additional',$GLOBALS['lang']).'</h3>';
						echo ' 		</div>';
						echo '		<div class="panel-body">';
						echo '			<form id="formularionaac" role="form">';		
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
						echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarVS" id="btnCancelarVS" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogautorizacionadicional\').dialog(\'close\');" style="margin-left:10px;" />';
						echo '					<input type="button" class="btn btn-primary pull-right" name="btnValidarS" id="btnValidarS" value="'.translate('Lbl_OK',$GLOBALS['lang']).'" onClick="guardarNuevoClienteConSupervisor(document.getElementById(\'formularionaac\'));"/>';										
						echo '				</div>';				
						echo '			</form>';
						echo '		</div>';
						echo '	</div>';
						echo '</div>';		
						
						return;	
					}
					else 
					{
						$stmt3->free_result();
						$stmt3->close();
						
						$stmt2->free_result();
						$stmt2->close();
						
						$stmt->free_result();
						$stmt->close();						
						echo translate('Msg_It_Is_Not_Necessary_To_Authorize',$GLOBALS['lang']);
						return;							
					}
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
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}
		
		return;
?>