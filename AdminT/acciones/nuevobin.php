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
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Lbl_New_Data_Bin',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_3"></div>';
		echo '			<form id="formularionb" role="form">';
		echo '				<div class="form-group form-inline">';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="tarjetabinn">'.translate('Lbl_Card',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="tarjetabinn">';
		echo '						<select class="form-control input-sm" name="tarjetabinni" id="tarjetabinni" style="width:193px;">';			 
										if ($stmt = $mysqli->prepare("SELECT pm.payment_method_id, pm.payment_method_description FROM tef.paymentmethods pm ORDER BY pm.payment_method_id")) 
										{ 
											$stmt->execute();    
											$stmt->store_result();
									 
											$stmt->bind_result($id_payment_method_id, $nombre_payment_method);
											$ii = 0;
											while($stmt->fetch())
											{
												if($ii == 0) echo '<option selected value="'.$id_payment_method_id.'">'.$id_payment_method_id.' - '.$nombre_payment_method.'</option>';
												else echo '<option value="'.$id_payment_method_id.'">'.$id_payment_method_id.' - '.$nombre_payment_method.'</option>';
												
												$ii++;
											}
										}
										else  
										{
											echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
											return;			
										}
		echo '						</select>';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="binlengthn">'.translate('Lbl_Bin_Length',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="binlengthn">';
		echo '						<input title="'.translate('Msg_A_Bin_Length_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="binlengthni" name="binlengthni" type="text" maxlength="11" />';
		echo '					</div>';
		echo '				</div>';		
		echo '				<div class="form-group form-inline">';		
		echo '					<label class="control-label" for="rangefrombinn">'.translate('Lbl_Range_From',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="rangefrombinn">';
		echo '						<input title="'.translate('Msg_A_Range_From_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="rangefrombinni" name="rangefrombinni" type="text" maxlength="20" />';
		echo '					</div>';
		echo '					&nbsp;&nbsp;&nbsp;<label class="control-label" for="rangetobinn">'.translate('Lbl_Range_To',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="rangetobinn">';
		echo '						<input title="'.translate('Msg_A_Range_To_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="rangetobinni" name="rangetobinni" type="text" maxlength="20" />';
		echo '					</div>';	
		echo '				</div>';	
		echo '				<div class="form-group form-inline">';				
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCancelarNB" id="btnCancelarNB" value="'.translate('Lbl_Cancel',$GLOBALS['lang']).'" onClick="$(\'#dialognewbin\').dialog(\'close\');" style="margin-left:10px;" />';
		echo '					<input type="button" class="btn btn-primary pull-right" name="btnCargarNB" id="btnCargarNB" value="'.translate('Lbl_Save',$GLOBALS['lang']).'" onClick="guardarNuevoBin(document.getElementById(\'formularionb\'));"/>';										
		echo '				</div>';				
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		return;
?>