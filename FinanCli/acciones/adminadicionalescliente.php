<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');return;}
		if (!verificar_permisos_usuario()){header('Location:../sinautorizacion.php?activauto=1');return;}

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
		
		$idCliente=htmlspecialchars ($_POST["idCliente"], ENT_QUOTES, 'UTF-8');
				
		if($stmt = $mysqli->prepare("SELECT c.id FROM finan_cli.cliente c WHERE c.id = ?"))
		{
			$stmt->bind_param('i', $idCliente);
			$stmt->execute();    
			$stmt->store_result();
		
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				$stmt->free_result();
				$stmt->close();
				echo translate('Msg_Client_Not_Exist',$GLOBALS['lang']);
				return;
			}
			else
			{				
				if($stmt2 = $mysqli->prepare("SELECT c.id, td.nombre, c.documento, c.nombres, c.apellidos, c.estado FROM finan_cli.cliente c, finan_cli.tipo_documento td  WHERE c.tipo_documento = td.id AND c.id_titular = ? ORDER BY c.documento"))
				{
					$stmt2->bind_param('i', $idCliente);
					$stmt2->execute();    
					$stmt2->store_result();
				
					$totR2 = $stmt2->num_rows;

					if($totR2 > 0)
					{			 
						/**
						$stmt2->bind_result($id_client, $type_document_client, $document_client, $name_client, $surname_client, $state_client, $type_account_client);
						
						$array[0] = array();
						$posicion = 0;
						while($stmt2->fetch())
						{
							$array[$posicion]['tipodocumento'] = $type_document_client;
							$array[$posicion]['documento'] = $document_client;
							$array[$posicion]['nombre'] = $name_client;
							$array[$posicion]['apellido'] = $surname_client;
							$array[$posicion]['estado'] = $state_client;
							$array[$posicion]['tipocuenta'] = $type_account_client;
							
							if($state_client == translate('State_User',$GLOBALS['lang'])) $array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Deactivate_Client',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Deactivate_Client',$GLOBALS['lang']).'\',\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-times"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Client',$GLOBALS['lang']).'" onclick="modificarCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-cog"></i></button>';
							else $array[$posicion]['acciones'] = '<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Activate_Client',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Activate_Client',$GLOBALS['lang']).'\',\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-check"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Client',$GLOBALS['lang']).'" onclick="modificarCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-cog"></i></button>';
							$posicion++;
						}
						*/

						$stmt2->bind_result($id_client, $type_document_client, $document_client, $name_client, $surname_client, $state_client);
												
						echo translate('Msg_Find_Additional_Client_OK',$GLOBALS['lang']).
						'<div class="panel-group">				
							<div class="panel panel-default" style="width:750px;">
							  <div id="panel-title-header" class="panel-heading">
								<h3 class="panel-title">'.translate('Lbl_Additionals_Client',$GLOBALS['lang']).'</h3>
							  </div>
							  <div id="apDiv1" class="panel-body">
								<div id="tableadminadditionalclient" class="table-responsive">
									<table id="tableadminadditionalclientt" data-classes="table table-hover table-condensed"
									   data-striped="true" data-pagination="true" data-show-export="true" data-export-options=\'{"fileName": "'.translate('File_Clients_Additional',$GLOBALS['lang']).'"}\'
									   data-export-types="[\'excel\',\'pdf\',\'csv\',\'txt\']"
									   data-search="true" data-search-align="left" data-toolbar-align="right">
										<thead>
											<tr>
												<th class="col-xs-2 text-center" data-field="tipodocumento" data-sortable="true">'.translate('Lbl_Type_Document_Client',$GLOBALS['lang']).'</th>
												<th class="col-xs-1 text-center" data-field="documento" data-sortable="true">'.translate('Lbl_Document_Client',$GLOBALS['lang']).'</th>
												<th class="col-xs-1 text-center" data-field="nombre" data-sortable="true">'.translate('Lbl_Name_Client',$GLOBALS['lang']).'</th>
												<th class="col-xs-1 text-center" data-field="apellido" data-sortable="true">'.translate('Lbl_Surname_Client',$GLOBALS['lang']).'</th>
												<th class="col-xs-2 text-center" data-field="estado" data-sortable="true">'.translate('Lbl_State_Client',$GLOBALS['lang']).'</th>
											</tr>						
										</thead>
										<tbody>';
											while($stmt2->fetch())
											{		
												echo '<tr>';
												echo '<td>'.$type_document_client.'</td>';
												echo '<td>'.$document_client.'</td>';
												echo '<td>'.$name_client.'</td>';
												echo '<td>'.$surname_client.'</td>';
												echo '<td>'.$state_client.'</td>';
												
												//if($state_client == translate('State_User',$GLOBALS['lang'])) echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Deactivate_Client',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Deactivate_Client',$GLOBALS['lang']).'\',\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-times"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Client',$GLOBALS['lang']).'" onclick="modificarCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-cog"></i></button></td>';
												//else echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Activate_Client',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Activate_Client',$GLOBALS['lang']).'\',\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-check"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Client',$GLOBALS['lang']).'" onclick="modificarCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-cog"></i></button></td>';
												//echo '</tr>';
											}						
						echo'			</tbody>					
									</table>
								</div>
							  </div>
							</div>
						</div>';		
					}
					else
					{
						echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
						return;	
					}
					
					$stmt2->free_result();
					$stmt2->close();
				}
				else	
				{
					$stmt->free_result();
					$stmt->close();
					echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
					return;	
				}
				
				$stmt->free_result();
				$stmt->close();
				return;				
			}
		}
		else
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;				
		}
?>