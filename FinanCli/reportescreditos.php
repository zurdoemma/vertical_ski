<?php
include ('./utiles/funciones.php');
require("../parametrosbasedatosfc.php");
$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
mysqli_set_charset($mysqli,"utf8");
if (!verificar_usuario($mysqli)){header('Location:./login.php');return;}
if (!verificar_permisos_supervisor()){header('Location:./sinautorizacion.php?activauto=1');return;}
include("./menu/menu.php");
?>
<!doctype html>
<html lang="es-es">
<head>
	<meta charset="UTF-8">
	
	<link rel="shortcut icon" href="./images/iconoFinanCli.png" >
	<title><?php echo translate('Lbl_Reports_Credits',$GLOBALS['lang']); ?></title>
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
		function generarReporte()
		{						
			if($("#reportescreditsni").val() == 1)
			{
				if($( "#datetimepickerfechadesdereporteni" ).val().length == 0)
				{
					$(function() {
						$( "#datetimepickerfechadesdereporteni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#datetimepickerfechadesdereporteni" ).focus();
					return;
				}
				else 
				{
					$(function() {
						$( "#datetimepickerfechadesdereporteni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});				
					$( "#datetimepickerfechadesdereporteni" ).tooltip('destroy');
				}

				if($( "#datetimepickerfechahastareporteni" ).val().length == 0)
				{
					$(function() {
						$( "#datetimepickerfechahastareporteni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#datetimepickerfechahastareporteni" ).focus();
					return;
				}
				else 
				{
					$(function() {
						$( "#datetimepickerfechahastareporteni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});				
					$( "#datetimepickerfechahastareporteni" ).tooltip('destroy');
				}

				var dateD = new Date($( "#datetimepickerfechadesdereporteni" ).val());
				var dateH = new Date($( "#datetimepickerfechahastareporteni" ).val());
				
				if(dateD > dateH)
				{
					$( "#datetimepickerfechahastareporteni" ).val("");
					$( "#datetimepickerfechahastareporteni" ).focus();
					mensaje_atencion("<?php echo translate('Lbl_Attention',$GLOBALS['lang']);?>","<?php echo translate('Msg_The_Date_From_Cannot_Be_Greater_Than_The_Date_Until',$GLOBALS['lang']);?>");
					return;
				}
			}
			
			var urlgmu = "./acciones/mostrarreportespdf.php";
			$('#img_loader').show();
						
			$.ajax({
				url: urlgmu,
				method: "POST",
				data: { idReporte: $( "#reportescreditsni" ).val(), fechaDesde: $( "#datetimepickerfechadesdereporteni" ).val(), fechaHasta:  $( "#datetimepickerfechahastareporteni" ).val()},
				success: function(dataresponse, statustext, response){
					$('#img_loader').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Generate_Report_PDF_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						dataresponse = dataresponse.replace("<?php echo translate('Msg_Generate_Report_PDF_OK',$GLOBALS['lang']); ?>=:=:=:","");
						if($("#reportescreditsni").val() == 1)
						{
							window.open('acciones/mostrarreportespdf.php?idReporte='+$( "#reportescreditsni" ).val()+'&nombreReporte='+$( "#reportescreditsni option:selected" ).text()+'&fechaDesde='+$( "#datetimepickerfechadesdereporteni" ).val()+'&fechaHasta='+$( "#datetimepickerfechahastareporteni" ).val());
						}
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader').hide();
				}
			});				
		}			
	</script>
	
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
	<script type="text/javascript">
		$(function () 
		{
			var todayDate = new Date().getDate();
			$("#datetimepickerfechadesdereporten").datetimepicker({
					format: 'L',
					locale: 'es',
					viewMode: 'years',
					minDate: new Date(new Date().setDate(todayDate - 1825)),
					maxDate: new Date(new Date().setDate(todayDate + 0)),
					widgetPositioning:{
						horizontal: 'auto',
						vertical: 'bottom'}
			});
			
			$("#datetimepickerfechahastareporten").datetimepicker({
					format: 'L',
					locale: 'es',
					viewMode: 'years',
					minDate: new Date(new Date().setDate(todayDate - 1825)),
					maxDate: new Date(new Date().setDate(todayDate + 0)),
					widgetPositioning:{
						horizontal: 'auto',
						vertical: 'bottom'}
			});			
		});
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
				<strong>| <?php echo translate('Lbl_Date',$GLOBALS['lang']); ?>:</strong> <?php date_default_timezone_set("America/Argentina/Buenos_Aires"); $fecha = date("d/m/Y"); echo $fecha;  ?>
				 - <strong><?php echo translate('Lbl_User',$GLOBALS['lang']); ?>:</strong><?php $usuario = $_SESSION['username']; echo"$usuario"; ?> |
			</a>
		</div>
	  </div>
	</nav></br></br></br></br>
	<div class="panel-group" style="padding-bottom:50px;">				
		<div class="panel panel-default" style="margin-left:30px;margin-right:30px;">
		  <div id="panel-title-header" class="panel-heading">
			<h3 class="panel-title" id="tituloreportescreditos"><?php  echo translate('Lbl_Selection_Reports',$GLOBALS['lang']); ?></h3>
		  </div>
		  <div id="apDiv1" class="panel-body">
			<div id="img_loader"></div>
			<form id="formularior" role="form">
				<div class="form-group form-inline">
					&nbsp;&nbsp;<label class="control-label" for="reportescreditsn"><?php echo translate('Lbl_Reports_Credits',$GLOBALS['lang']).': '; ?></label>
					<div class="form-group" id="reportescreditsn">
						<select class="form-control input-sm" name="reportescreditsni" id="reportescreditsni" style="width:310px;">		 
							<?php 
								if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.reportes")) 
								{ 
									$stmt->execute();    
									$stmt->store_result();
							 
									$stmt->bind_result($id_reporte,$nombre_reporte);
									while($stmt->fetch())
									{
										echo '<option value="'.$id_reporte.'">'.$nombre_reporte.'</option>';
									}
								}
								else  
								{
									echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
									return;			
								}
							?>
						</select>
					</div>
					<div class="form-group" id="fechadesdereporten">
						&nbsp;&nbsp;<label class="control-label" for="fechadesdereporten"><?php echo translate('Lbl_Date_Since_Report',$GLOBALS['lang']).': ' ?></label>			
						<div class="input-group date" id="datetimepickerfechadesdereporten">
							<input title="<?php echo translate('Msg_Date_Since_Report_Must_Enter',$GLOBALS['lang']); ?>" class="form-control input-sm" id="datetimepickerfechadesdereporteni" name="datetimepickerfechadesdereporteni" type="text" maxlength="10" placeholder="<?php echo translate('Lbl_Date_Since_Report',$GLOBALS['lang']); ?>"  style="width: 152px;" />
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>		
						</div>
					</div>
					<div class="form-group" id="fechahastareporten">
						&nbsp;<label class="control-label" for="fechahastareporten"><?php echo translate('Lbl_Date_Until_Report',$GLOBALS['lang']).': ' ?></label>			
						<div class="input-group date" id="datetimepickerfechahastareporten">
							<input title="<?php echo translate('Msg_Date_Until_Report_Must_Enter',$GLOBALS['lang']); ?>" class="form-control input-sm" id="datetimepickerfechahastareporteni" name="datetimepickerfechahastareporteni" type="text" maxlength="10" placeholder="<?php echo translate('Lbl_Date_Until_Report',$GLOBALS['lang']); ?>"  style="width: 152px;" />
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>		
						</div>
					</div>					
					&nbsp;<input type="button" class="btn btn-primary" name="btnGenerarReporte" id="btnGenerarReporte" value="<?php echo translate('Msg_Generate_Report_Credits',$GLOBALS['lang']); ?>" onClick="generarReporte();" style="margin-left:10px;" />				
				</div>
			</form>
		  </div>
		</div>
	</div>		
	<footer class="footer">
		<div id="fondoPage">
			<img src="./images/finanCliFooter.png" style="margin-top:-20px;">
		</div>
	</footer>
	<div id="errorDialog" style="display:none;"></div>
	<div id="atencionDialog" style="display:none;"></div>
	<div id="okDialog" style="display:none;"></div>
	<div id="confirmDialog" style="display:none;"></div>
	<script type="text/javascript">
		$( document ).ready(function() { 
			if($("#reportescreditsni").val() == 1)
			{
				$("#fechadesdereporten").show();
				$("#fechahastareporten").show();
			}
			else
			{
				$("#fechadesdereporten").hide();
				$("#fechahastareporten").hide();
			}
		});
	</script>
</body>
</html>