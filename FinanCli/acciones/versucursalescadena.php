<?php 		
		include ('../utiles/funciones.php');
		sec_session_start();
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');}
		if (!verificar_permisos_admin()){header('Location:../sinautorizacion.php?activauto=1');}

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
		
		$idCadena=htmlspecialchars($_POST["idCadena"], ENT_QUOTES, 'UTF-8');

		if($stmt = $mysqli->prepare("SELECT c.id FROM finan_cli.cadena c WHERE c.id = ?"))
		{
			$stmt->bind_param('i', $idCadena);
			$stmt->execute();    
			$stmt->store_result();
			
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				$stmt->free_result();
				$stmt->close();
				echo translate('Msg_Chain_Not_Exist',$GLOBALS['lang']);
				return;
			}

			if($stmt2 = $mysqli->prepare("SELECT s.id, s.nombre FROM finan_cli.cadena c, finan_cli.sucursal s WHERE s.id_cadena = c.id AND c.id = ?"))
			{
				$stmt2->bind_param('i', $idCadena);
				$stmt2->execute();    
				$stmt2->store_result();

				$stmt2->bind_result($id_sucursal_asignada, $nombre_sucursal_asignada);
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;				
			}

			if($stmt3 = $mysqli->prepare("SELECT s.id, s.nombre FROM finan_cli.sucursal s WHERE s.id NOT IN (SELECT s2.id FROM finan_cli.cadena c2, finan_cli.sucursal s2 WHERE s2.id_cadena = c2.id AND c2.id = ?)"))
			{
				$stmt3->bind_param('i', $idCadena);
				$stmt3->execute();    
				$stmt3->store_result();

				$stmt3->bind_result($id_sucursal_no_asignada, $nombre_sucursal_no_asignada);
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
				
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Lbl_Asignation_Tenders_X_Chain',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body" style="min-height:300px;">';
		echo '			<div id="img_loader_9"></div>';
		echo '			<form id="formulariomscu" role="form">';
		echo '				<div class="form-group form-inline">';
		//echo '					<label class="control-label" for="sucursalesasignadasc">'.translate('Lbl_Asignated_Tenders',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="sucursalesasignadasc">';
		echo '						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.translate('Lbl_Asignated_Tenders',$GLOBALS['lang']).'</br><select class="form-control" multiple="multiple" name="boot-multiselect-sucursales-asignadas" id="boot-multiselect-sucursales-asignadas" >';			 
										while($stmt2->fetch())
										{
											echo '<option value="'.$id_sucursal_asignada.'">'.$nombre_sucursal_asignada.'</option>';
										}
		echo '						</select>';
		echo '					</div>';
		echo '					<div class="form-group" id="desasignarsucursal">';
		echo '						</br>&nbsp;&nbsp;<button type="button" class="btn" title="'.translate('Lbl_Unassign_Tenders',$GLOBALS['lang']).'" onclick="desasignarSucursales();"><i class="fas fa-minus"></i></button>';
		echo '					</div>';
		echo '					<div class="form-group" id="asignarsucursal">';
		echo '						</br>&nbsp;<button type="button" class="btn" title="'.translate('Lbl_Assign_Tenders',$GLOBALS['lang']).'" onclick="asignarSucursales();"><i class="fas fa-plus"></i></button>';
		echo '					</div>';		
		//echo '					<label class="control-label" for="sucursalesnoasignadasc">'.translate('Lbl_Not_Asignated_Tenders',$GLOBALS['lang']).':</label>';				
		echo '					<div class="form-group" id="sucursalesnoasignadasc">';
		echo '						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.translate('Lbl_Tenders_Available',$GLOBALS['lang']).'</br>&nbsp;&nbsp;<select class="form-control" multiple="multiple" name="boot-multiselect-sucursales-activas" id="boot-multiselect-sucursales-activas" >';			 
										while($stmt3->fetch())
										{
											echo '<option value="'.$id_sucursal_no_asignada.'">'.$nombre_sucursal_no_asignada.'</option>';
										}
		echo '						</select>';
		echo '					</div>';		
		echo '				</div>';	
		echo '				<div class="form-group form-inline" style="margin-top:50%">';				
		echo '					<input type="button" class="btn btn-primary pull-right pull-bottom" name="btnCancelar" id="btnCancelar" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogmodtenderchain\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargar" id="btnCargar" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarModificacionCadena(document.getElementById(\'formulariomu\',\''.$id_cadena.'\'));"/>';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		$stmt->free_result();
		$stmt->close();		

		$stmt2->free_result();
		$stmt2->close();	
		
		$stmt3->free_result();
		$stmt3->close();			
		
		return;
?>