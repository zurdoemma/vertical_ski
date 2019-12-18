<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		if (!verificar_usuario($mysqli)){header('Location:../sesionusuario.php');return;}
		if (!verificar_permisos_admin()){header('Location:../sinautorizacion.php?activauto=1');return;}

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
		
		$idPerfilCredito=htmlspecialchars($_POST["idPerfilCredito"], ENT_QUOTES, 'UTF-8');

		if($stmt = $mysqli->prepare("SELECT pc.id FROM ".$db_name.".perfil_credito pc WHERE pc.id = ?"))
		{
			$stmt->bind_param('i', $idPerfilCredito);
			$stmt->execute();    
			$stmt->store_result();
			
			$totR = $stmt->num_rows;

			if($totR == 0)
			{
				$stmt->free_result();
				$stmt->close();
				echo translate('Msg_Profile_Credit_Not_Exist',$GLOBALS['lang']);
				return;
			}

			if($stmt2 = $mysqli->prepare("SELECT pc.id, pc.nombre FROM ".$db_name.".plan_credito pc, ".$db_name.".perfil_credito_x_plan pcxp WHERE pc.id = pcxp.id_plan_credito AND pcxp.id_perfil_credito = ?"))
			{
				$stmt2->bind_param('i', $idPerfilCredito);
				$stmt2->execute();    
				$stmt2->store_result();

				$stmt2->bind_result($id_plan_credito_asignado, $nombre_plan_credito_asignado);
			}
			else
			{
				echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
				return;				
			}

			if($stmt3 = $mysqli->prepare("SELECT pc.id, pc.nombre FROM ".$db_name.".plan_credito pc WHERE pc.id NOT IN (SELECT pc2.id FROM ".$db_name.".plan_credito pc2, ".$db_name.".perfil_credito_x_plan pcxp WHERE pc2.id = pcxp.id_plan_credito AND pcxp.id_perfil_credito = ?)"))
			{
				$stmt3->bind_param('i', $idPerfilCredito);
				$stmt3->execute();    
				$stmt3->store_result();

				$stmt3->bind_result($id_plan_credito_no_asignado, $nombre_plan_credito_no_asignado);
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
		echo '			<h3 class="panel-title">'.translate('Lbl_View_Credit_Plan_X_Profile_2',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body" style="min-height:300px;">';
		echo '			<div id="img_loader_10"></div>';
		echo '			<form id="formulariomscu" role="form">';
		echo '				<div class="form-group form-inline">';
		echo '					<div class="form-group" id="planesasignadosc">';
		echo '						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.translate('Lbl_Asignated_Credit_Plans',$GLOBALS['lang']).'</br><select class="form-control" multiple="multiple" name="boot-multiselect-planes-asignados" id="boot-multiselect-planes-asignados" >';			 
										while($stmt2->fetch())
										{
											echo '<option value="'.$id_plan_credito_asignado.'">'.$nombre_plan_credito_asignado.'</option>';
										}
		echo '						</select>';
		echo '					</div>';
		echo '					<div class="form-group" id="desasignarplan">';
		echo '						</br>&nbsp;&nbsp;<button type="button" class="btn" title="'.translate('Lbl_Unassign_Credit_Plan',$GLOBALS['lang']).'" onclick="desasignarPlanesCredito();"><i class="fas fa-minus"></i></button>';
		echo '					</div>';
		echo '					<div class="form-group" id="asignarplan">';
		echo '						</br>&nbsp;<button type="button" class="btn" title="'.translate('Lbl_Assign_Credit_Plan',$GLOBALS['lang']).'" onclick="asignarPlanesCredito();"><i class="fas fa-plus"></i></button>';
		echo '					</div>';						
		echo '					<div class="form-group" id="planesnoasignadosc">';
		echo '						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.translate('Lbl_Credit_Plans_Available',$GLOBALS['lang']).'</br>&nbsp;&nbsp;<select class="form-control" multiple="multiple" name="boot-multiselect-planes-activos" id="boot-multiselect-planes-activos" >';			 
										while($stmt3->fetch())
										{
											echo '<option value="'.$id_plan_credito_no_asignado.'">'.$nombre_plan_credito_no_asignado.'</option>';
										}
		echo '						</select>';
		echo '					</div>';		
		echo '				</div>';	
		echo '				<div class="form-group form-inline" style="margin-top:50%">';				
		echo '					<input type="button" class="btn btn-primary pull-right pull-bottom" name="btnCancelar" id="btnCancelar" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogmodcreditplanxprofile\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargarPCP" id="btnCargarPCP" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarPlanesCreditoPerfil(\''.$idPerfilCredito.'\');"/>';										
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