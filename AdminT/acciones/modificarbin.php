<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosta.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		// ¡Oh, no! Existe un error 'connect_errno', fallando así el intento de conexión
		if ($mysqli->connect_errno) 
		{
			//echo "Lo sentimos, este sitio web está experimentando problemas.";

			//echo "Error: Fallo al conectarse a MySQL debido a: \n";
			//echo "Errno: " . $mysqli->connect_errno . "\n";
			//echo "Error: " . $mysqli->connect_error . "\n";
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}

		$idBin=htmlspecialchars($_POST["idBin"], ENT_QUOTES, 'UTF-8');
		if ($stmt = $mysqli->prepare("SELECT b.bin_id, b.payment_method_id, b.range_from, b.range_to, b.bin_length FROM tef.bines b WHERE b.bin_id = ?")) 
		{
			$stmt->bind_param('i', $idBin);
			$stmt->execute();    
			$stmt->store_result();
	 
			$stmt->bind_result($id_bin_a, $id_payment_method_a, $range_from_bin_a, $range_to_bin_a, $bin_length_a);
			$totR = $stmt->num_rows;

			if($totR > 0)
			{
				$stmt->fetch();
				
				$stmt->free_result();
				$stmt->close();
			}
			else
			{
				echo translate('Msg_Bin_Selected_Not_Exist',$GLOBALS['lang']);
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
		echo '			<h3 class="panel-title">'.translate('Lbl_Modify_Bin',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_3"></div>';
		echo '			<form id="formulariomb" role="form">';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="tarjetabin">'.translate('Lbl_Card',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="tarjetabin">';
		echo '						<select class="form-control input-sm" name="tarjetabini" id="tarjetabini" style="width:193px;">';			 
										if ($stmt = $mysqli->prepare("SELECT pm.payment_method_id, pm.payment_method_description FROM tef.paymentmethods pm ORDER BY pm.payment_method_id")) 
										{ 
											$stmt->execute();    
											$stmt->store_result();
									 
											$stmt->bind_result($id_payment_method_id, $nombre_payment_method);
											while($stmt->fetch())
											{
												if($id_payment_method_id == $id_payment_method_a) echo '<option selected value="'.$id_payment_method_id.'">'.$id_payment_method_id.' - '.$nombre_payment_method.'</option>';
												else echo '<option value="'.$id_payment_method_id.'">'.$id_payment_method_id.' - '.$nombre_payment_method.'</option>';
											}
										}
										else  
										{
											echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
											return;			
										}
		echo '						</select>';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="binlength">'.translate('Lbl_Bin_Length',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="binlength">';
		echo '						<input title="'.translate('Msg_A_Bin_Length_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="binlengthi" name="binlengthi" type="text" maxlength="11" value="'.$bin_length_a.'" />';
		echo '					</div>';
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';		
		echo '					<label class="control-label" for="rangefrombin">'.translate('Lbl_Range_From',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="rangefrombin">';
		echo '						<input title="'.translate('Msg_A_Range_From_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="rangefrombini" name="rangefrombini" type="text" maxlength="20" value="'.$range_from_bin_a.'" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="rangetobin">'.translate('Lbl_Range_To',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="rangetobin">';
		echo '						<input title="'.translate('Msg_A_Range_To_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="rangetobini" name="rangetobini" type="text" maxlength="20" value="'.$range_to_bin_a.'" />';
		echo '					</div>';	
		echo '				</div>';	
		echo '				<div class="form-group form-inline">';				
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarMB" id="btnCancelarMB" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialogmodifybin\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargarMB" id="btnCargarMB" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarModificacionBin(document.getElementById(\'formulariomb\'),\''.$idBin.'\');"/>';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		return;
?>