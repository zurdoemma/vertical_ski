<?php 		
		include ('../utiles/funciones.php');
				
		echo '<div class="panel-group">';				
		echo '	<div class="panel panel-default">';
		echo '		<div id="panel-title-header" class="panel-heading">';
		echo '			<h3 class="panel-title">'.translate('Lbl_New_Data_Bin',$GLOBALS['lang']).'</h3>';
		echo ' 		</div>';
		echo '		<div class="panel-body">';
		echo '			<div id="img_loader_4"></div>';
		echo '			<form id="uploadedfile" role="form">';
		echo '				<div class="form-group form-inline">';	
		echo '					<label class="control-label" for="rangefrombinn">'.translate('Lbl_File_Import_Bins',$GLOBALS['lang']).':</label>';
		echo '					<div class="form-group" id="filedCM">';
		echo '						<input title="'.translate('Msg_A_File_Import_Bin_Must_Enter',$GLOBALS['lang']).'" class="form-control input-sm" id="fileCM" name="fileCM" type="file" />';						
		echo '					</div>';
		echo '					<input type="button" name="btnSubirBines" id="btnSubirBines" value="'.translate('Msg_Uploaded_File_Import_Bin',$GLOBALS['lang']).'" class="btn btn-dark" onClick="subirArchivoBines(document.getElementById(\'uploadedfile\'), document.getElementById(\'fileCM\').value);" />';
		echo '					&nbsp;&nbsp;<input type="button" class="btn btn-primary" name="btnCIB" id="btnCIB" value="'.translate('Lbl_Begin_Import_Bins',$GLOBALS['lang']).'" onClick="ejecutarImportacionBines();" disabled />';
		echo '				</div>';						
		echo '			</form>';
		echo '		</div>';
		echo '	</div>';
		echo '</div>';
		
		return;
?>