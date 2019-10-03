<?php
include ('./utiles/funciones.php');
require("../parametrosbasedatosta.php");
$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
mysqli_set_charset($mysqli,"utf8");
include("./menu/menu.php");
?>
<!doctype html>
<html lang="es-es">
<head>
	<meta charset="UTF-8">
	
	<link rel="shortcut icon" href="./images/iconoAdminT.png" >
	<title><?php echo translate('Lbl_Admin_Bines',$GLOBALS['lang']); ?></title>
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.op2.css" >
	<link rel="stylesheet" type="text/css" href="./css/bootstrap-theme.min.op2.css" >
	<link rel="stylesheet" type="text/css" href="./css/bootstrap-datetimepicker.css" >	
	<link rel="stylesheet" type="text/css" href="./css/bootstrap-table.min.op2.css" >
	<link rel="stylesheet" href="./css/fontawesome.min.css">
	<link rel="stylesheet" href="./css/all.css">
	<link rel="stylesheet" type="text/css" href="./css/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="./css/bootstrap-multiselect.css">
	<link rel="stylesheet" href="./utiles/CodeMirror/lib/codemirror.css">
	<link rel="stylesheet" href="./utiles/CodeMirror/addon/hint/show-hint.css">	
	
	<script type="text/javascript" src="./js/jquery.js"></script>
	<script type="text/JavaScript" src="./js/bootstrap.min.op2.js" ></script>
	<script type="text/javascript" src="./js/jquery-ui.js"></script>
	<script type="text/JavaScript" src="./js/bootstrap-multiselect.js" ></script>	
	<script type="text/JavaScript" src="./js/moment.op2.js" ></script>
	<script type="text/JavaScript" src="./js/bootstrap-datetimepicker.js" ></script>	
	<script type="text/JavaScript" src="./js/bootstrap-table.min.op2.js" ></script>
	<script type="text/JavaScript" src="./js/locale/bootstrap-table-es-AR.js" ></script>	
	<script type="text/JavaScript" src="./js/extensions/export/FileSaver.min.js" ></script>	
	<script type="text/JavaScript" src="./js/extensions/export/jspdf/jspdf.min.js" ></script>
	<script type="text/JavaScript" src="./js/extensions/export/jspdf/jspdf.plugin.autotable.js" ></script>	
	<script type="text/JavaScript" src="./js/extensions/export/tableExport.js" ></script>
	<script type="text/JavaScript" src="./js/extensions/export/bootstrap-table-export.js" ></script>
	<script type="text/JavaScript" src="./js/jquery.validate.op2.js" ></script>
	<script type="text/JavaScript" src="./js/forms.op2.js" ></script>
	<script type="text/JavaScript" src="./js/sha512.op2.js" ></script>
	<script type="text/JavaScript" src="./js/jquery.masknumber.js" ></script>
	<script src="./utiles/CodeMirror/lib/codemirror.js"></script>
	<script src="./utiles/CodeMirror/addon/hint/show-hint.js"></script>
	<script src="./utiles/CodeMirror/addon/hint/xml-hint.js"></script>
	<script src="./utiles/CodeMirror/mode/xml/xml.js"></script>	
	<script src="./utiles/CodeMirror/addon/search/search.js"></script>
	<script src="./utiles/CodeMirror/addon/search/searchcursor.js"></script>
	
	<link rel="stylesheet" href="./css/fondo.op2.css">
	<link rel="stylesheet" href="./css/estilos.op2.css">
	
    <script type="text/javascript">
		function mensaje_error(titulo, mensaje){
			   $( "#errorDialog" ).dialog({
							title:titulo,
							show:"blind",
							modal: true,
							hide:"slide",
							resizable: false,
							height: "auto",
							width: "auto",
							buttons: {
									"<?php echo translate('Lbl_OK',$GLOBALS['lang']);?>": function() {
											$("#errorDialog").dialog('close');
									}
							}
					}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
					$( "#errorDialog" ).html("<div id='mensajeError'>"+mensaje+"</div>");
		}
    </script>
	
    <script type="text/javascript">
		function mensaje_atencion(titulo, mensaje){
			   $( "#atencionDialog" ).dialog({
							title:titulo,
							show:"blind",
							modal: true,
							hide:"slide",
							resizable: false,
							height: "auto",
							width: "auto",
							buttons: {
									"<?php echo translate('Lbl_OK',$GLOBALS['lang']);?>": function() {
											$("#atencionDialog").dialog('close');
									}
							}
					}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
					$( "#atencionDialog" ).html("<div id='mensajeAtencion'>"+mensaje+"</div>");
		}
    </script>
    <script type="text/javascript">
		function mensaje_ok(titulo, mensaje){
			   $( "#okDialog" ).dialog({
							title:titulo,
							show:"blind",
							modal: true,
							hide:"slide",
							resizable: false,
							height: "auto",
							width: "auto",
							buttons: {
									"<?php echo translate('Lbl_OK',$GLOBALS['lang']);?>": function() {
											$("#okDialog").dialog('close');
									}
							}
					}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
					$( "#okDialog" ).html("<div id='mensajeOK'>"+mensaje+"</div>");
		}
    </script>	
</head>

<body>
	<nav class="navbar navbar-default navbar-fixed-top">
	  <div class="container">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<?php echo $menu ?>
			<a class="navbar-brand pull-right" href="#" style="font-size: 12px;">
				<strong>| <?php echo translate('Lbl_Date',$GLOBALS['lang']); ?>:</strong> <?php $fecha = date("d/m/Y"); echo $fecha;  ?> |
			</a>
		</div>
	  </div>
	</nav></br></br></br></br>
	<div class="panel-group" style="padding-bottom:50px;">				
		<div class="panel panel-default" style="margin-left:30px;margin-right:30px;">
		  <div id="panel-title-header" class="panel-heading">
			<h3 class="panel-title"><?php echo translate('Lbl_Bines',$GLOBALS['lang']); ?></h3>
		  </div>
		  <div id="apDiv1" class="panel-body">
			<div id="toolbar" style="margin-left:-148px; margin-top:-1px;">
				<button type="button" id="btnNuevoBin" class="btn" data-toggle="tooltip" data-placement="top" onclick="nuevoBin();" title="<?php echo translate('Lbl_New_Bin',$GLOBALS['lang']);?>" ><i class="far fa-plus-square"></i></button>&nbsp;&nbsp;<button type="button" id="btnImportarBines" class="btn" data-toggle="tooltip" data-placement="top" onclick="importarBines();" title="<?php echo translate('Lbl_Bines_Import',$GLOBALS['lang']);?>" ><i class="fas fa-file-import"></i></button>
			</div>
			<div id="img_loader" style="display:block;"></div>
			<div id="tablaadminbin" class="table-responsive" style="display:none;">
				<table id="tableadminbint" data-classes="table table-hover table-condensed"
				   data-striped="true" data-pagination="true" data-show-export="true" data-export-options='{"fileName": "<?php echo translate('File_Bines',$GLOBALS['lang']); ?>"}'
				   data-export-types="['excel','pdf','csv','txt']"
				   data-search="true" data-search-align="left" data-toolbar="#toolbar" data-toolbar-align="right">
					<thead>
						<tr>
							<th class="col-xs-1 text-center" data-field="idtarjeta" data-sortable="true"><?php echo translate('Lbl_Card_Id',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="tarjeta" data-sortable="true"><?php echo translate('Lbl_Card',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="rangodesde" data-sortable="true"><?php echo translate('Lbl_Range_From',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="rangohasta" data-sortable="true"><?php echo translate('Lbl_Range_To',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="largobin" data-sortable="true"><?php echo translate('Lbl_Bin_Length',$GLOBALS['lang']);?></th>
							<th class="col-xs-3 text-center" data-field="acciones"><?php echo translate('Lbl_Bin_Actions',$GLOBALS['lang']);?></th>
						</tr>						
					</thead>
					<tbody>
						<?php
							if ($stmt = $mysqli->prepare("SELECT b.bin_id, pm.payment_method_id, pm.payment_method_description, b.range_from, b.range_to, b.bin_length FROM tef.bines b, tef.paymentmethods pm  WHERE b.payment_method_id = pm.payment_method_id ORDER BY pm.payment_method_id, b.bin_length, b.range_from LIMIT 5000")) 
							{
								$stmt->execute();    // Ejecuta la consulta preparada.
								$stmt->store_result();
						 
								// Obtiene las variables del resultado.
								$stmt->bind_result($id_bin, $id_payment_method, $name_payment_method, $range_from_bin, $range_to_bin, $bin_length);
								
								while($stmt->fetch())
								{		
									echo '<tr>';
									echo '<td>'.$id_payment_method.'</td>';
									echo '<td>'.$name_payment_method.'</td>';
									echo '<td>'.$range_from_bin.'</td>';
									echo '<td>'.$range_to_bin.'</td>';
									echo '<td>'.$bin_length.'</td>';
									
									echo '<td><button type="button" id="borrarBin'.$id_bin.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Bin',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Remove_Bin',$GLOBALS['lang']).'\',\''.$id_bin.'\')"><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" id="btnModificarBin'.$id_bin.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Modify_Bin',$GLOBALS['lang']).'" onclick="modificarBin(\''.$id_bin.'\')"><i class="far fa-edit"></i></button></td>';
									echo '</tr>';
								}
							}
							else echo $mysqli->error;
						?>						
					</tbody>					
				</table>
			</div>
		  </div>
		</div>
	</div>		
	<footer class="footer">
		<div id="fondoPage">
			<img src="./images/adminTFooter.png" style="margin-top:-20px;">
		</div>
	</footer>
	<div id="errorDialog" style="display:none;"></div>
	<div id="atencionDialog" style="display:none;"></div>
	<div id="okDialog" style="display:none;"></div>
	<div id="confirmDialog" style="display:none;"></div>
	<script type="text/javascript">
		$(function () 
		{
			$('#img_loader').hide();
			$('#tablaadminbin').show();
			$('#tableadminbint').bootstrapTable({locale:'es-AR'});
		});
	</script>		
</body>
</html>