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
			document.getElementById("btnGenerarReporte").disabled = true;
			if($("#reportescreditsni").val() >= 1 && $("#reportescreditsni").val() <= 11)
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
					document.getElementById("btnGenerarReporte").disabled = false;
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
					document.getElementById("btnGenerarReporte").disabled = false;
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
					document.getElementById("btnGenerarReporte").disabled = false;
					return;
				}
				
				if($("#reportescreditsni").val() == 7 || $("#reportescreditsni").val() == 8)
				{
					if($( "#nrodocumentclientn2i" ).val().length == 0)
					{
						$(function() {
							$( "#nrodocumentclientn2i" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});
						$( "#nrodocumentclientn2i" ).focus();
						document.getElementById("btnGenerarReporte").disabled = false;
						return;
					}
					else 
					{
						$(function() {
							$( "#nrodocumentclientn2i" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});				
						$( "#nrodocumentclientn2i" ).tooltip('destroy');
					}
				}					
			}
			
			var urlgmu = "./acciones/mostrarreportespdf.php";
			$('#img_loader_24').show();
						
			$.ajax({
				url: urlgmu,
				method: "POST",
				data: { idReporte: $( "#reportescreditsni" ).val(), fechaDesde: $( "#datetimepickerfechadesdereporteni" ).val(), fechaHasta:  $( "#datetimepickerfechahastareporteni" ).val(), sucursal:  $( "#sucursalsni" ).val(), planCredito:  $( "#tipoplanni" ).val(), tipoDocumento:  $( "#tipodocclientni" ).val(), documento:  $( "#nrodocumentclientni" ).val(), tipoDocumento2:  $( "#tipodocclientn2i" ).val(), documento2:  $( "#nrodocumentclientn2i" ).val()},
				success: function(dataresponse, statustext, response){
					$('#img_loader_24').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Generate_Report_PDF_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						dataresponse = dataresponse.replace("<?php echo translate('Msg_Generate_Report_PDF_OK',$GLOBALS['lang']); ?>=:=:=:","");
						if($("#reportescreditsni").val() == 1)
						{
							window.open('acciones/mostrarreportespdf.php?idReporte='+$( "#reportescreditsni" ).val()+'&nombreReporte='+$( "#reportescreditsni option:selected" ).text()+'&fechaDesde='+$( "#datetimepickerfechadesdereporteni" ).val()+'&fechaHasta='+$( "#datetimepickerfechahastareporteni" ).val());
						}
						
						if($("#reportescreditsni").val() == 2)
						{
							window.open('acciones/mostrarreportespdf.php?idReporte='+$( "#reportescreditsni" ).val()+'&nombreReporte='+$( "#reportescreditsni option:selected" ).text()+'&fechaDesde='+$( "#datetimepickerfechadesdereporteni" ).val()+'&fechaHasta='+$( "#datetimepickerfechahastareporteni" ).val()+'&sucursal='+$( "#sucursalsni" ).val()+'&nombreSucursal='+$( "#sucursalsni option:selected" ).text()+'&planCredito='+$( "#tipoplanni" ).val()+'&nombrePlanCredito='+$( "#tipoplanni option:selected" ).text());
						}
						
						if($("#reportescreditsni").val() == 3)
						{
							window.open('acciones/mostrarreportespdf.php?idReporte='+$( "#reportescreditsni" ).val()+'&nombreReporte='+$( "#reportescreditsni option:selected" ).text()+'&fechaDesde='+$( "#datetimepickerfechadesdereporteni" ).val()+'&fechaHasta='+$( "#datetimepickerfechahastareporteni" ).val()+'&sucursal='+$( "#sucursalsni" ).val()+'&nombreSucursal='+$( "#sucursalsni option:selected" ).text());
						}

						if($("#reportescreditsni").val() == 4 || $("#reportescreditsni").val() == 5 || $("#reportescreditsni").val() == 6 || $("#reportescreditsni").val() == 9)
						{
							window.open('acciones/mostrarreportespdf.php?idReporte='+$( "#reportescreditsni" ).val()+'&nombreReporte='+$( "#reportescreditsni option:selected" ).text()+'&fechaDesde='+$( "#datetimepickerfechadesdereporteni" ).val()+'&fechaHasta='+$( "#datetimepickerfechahastareporteni" ).val()+'&sucursal='+$( "#sucursalsni" ).val()+'&nombreSucursal='+$( "#sucursalsni option:selected" ).text()+'&tipoDocumento='+$( "#tipodocclientni" ).val()+'&nombreTipoDocumento='+$( "#tipodocclientni option:selected" ).text()+'&documento='+$( "#nrodocumentclientni" ).val());
						}

						if($("#reportescreditsni").val() == 7)
						{
							window.open('acciones/mostrarreportespdf.php?idReporte='+$( "#reportescreditsni" ).val()+'&nombreReporte='+$( "#reportescreditsni option:selected" ).text()+'&fechaDesde='+$( "#datetimepickerfechadesdereporteni" ).val()+'&fechaHasta='+$( "#datetimepickerfechahastareporteni" ).val()+'&tipoDocumento2='+$( "#tipodocclientn2i" ).val()+'&nombreTipoDocumento='+$( "#tipodocclientn2i option:selected" ).text()+'&documento2='+$( "#nrodocumentclientn2i" ).val());
						}

						if($("#reportescreditsni").val() == 8)
						{
							window.open('acciones/mostrarreportespdf.php?idReporte='+$( "#reportescreditsni" ).val()+'&nombreReporte='+$( "#reportescreditsni option:selected" ).text()+'&fechaDesde='+$( "#datetimepickerfechadesdereporteni" ).val()+'&fechaHasta='+$( "#datetimepickerfechahastareporteni" ).val()+'&tipoDocumento2='+$( "#tipodocclientn2i" ).val()+'&nombreTipoDocumento='+$( "#tipodocclientn2i option:selected" ).text()+'&documento2='+$( "#nrodocumentclientn2i" ).val());
						}

						if($("#reportescreditsni").val() == 10 || $("#reportescreditsni").val() == 11)
						{
							window.open('acciones/mostrarreportespdf.php?idReporte='+$( "#reportescreditsni" ).val()+'&nombreReporte='+$( "#reportescreditsni option:selected" ).text()+'&fechaDesde='+$( "#datetimepickerfechadesdereporteni" ).val()+'&fechaHasta='+$( "#datetimepickerfechahastareporteni" ).val()+'&sucursal='+$( "#sucursalsni" ).val()+'&nombreSucursal='+$( "#sucursalsni option:selected" ).text());
						}						
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
					
					document.getElementById("btnGenerarReporte").disabled = false;
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_24').hide();
					document.getElementById("btnGenerarReporte").disabled = false;
				}
			});
			document.getElementById("btnGenerarReporte").disabled = false;			
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
			<div id="img_loader_24"></div>
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
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="fechadesdereporten"><?php echo translate('Lbl_Date_Since_Report',$GLOBALS['lang']).': ' ?></label>			
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
				<div class="form-group form-inline">
					<div class="form-group" id="sucursalsn">
						&nbsp;&nbsp;<label class="control-label" for="sucursalsn"><?php echo translate('Lbl_Tender_User',$GLOBALS['lang']).': '; ?></label>
						<select class="form-control input-sm" name="sucursalsni" id="sucursalsni" style="width:313px;">		 
							<?php 
								if ($stmt500 = $mysqli->prepare("SELECT c.id FROM finan_cli.cadena c, finan_cli.usuario u, finan_cli.sucursal s WHERE u.id_sucursal = s.id AND s.id_cadena = c.id AND u.id = ?")) 
								{
									$stmt500->bind_param('s', $_SESSION['username']);
									$stmt500->execute();    
									$stmt500->store_result();
							 
									$totR500 = $stmt500->num_rows;
									if($totR500 > 0)
									{
										$stmt500->bind_result($id_cadena_user);
										$stmt500->fetch();

										$stmt500->free_result();
										$stmt500->close();				
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
		
								if ($stmt = $mysqli->prepare("SELECT codigo, nombre FROM finan_cli.sucursal WHERE id_cadena = ? ORDER BY nombre")) 
								{ 
									$stmt->bind_param('s', $id_cadena_user);
									$stmt->execute();    
									$stmt->store_result();
							 
									$stmt->bind_result($id_sucursal,$nombre_sucursal);
									while($stmt->fetch())
									{
										echo '<option value="'.$id_sucursal.'">'.$nombre_sucursal.'</option>';
									}
									echo '<option selected value="'.translate('Lbl_All_Selection',$GLOBALS['lang']).'">'.translate('Lbl_All_Selection',$GLOBALS['lang']).'</option>';
								}
								else  
								{
									echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
									return;			
								}
							?>
						</select>
					</div>
					<div class="form-group" id="tipoplann">
						&nbsp;<label class="control-label" for="tipoplann"><?php echo translate('Lbl_Credit_Plan',$GLOBALS['lang']).': '; ?></label>
						<select class="form-control input-sm" name="tipoplanni" id="tipoplanni" style="width:193px;">		 
							<?php 		
								if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.plan_credito WHERE id_cadena = ? ORDER BY nombre")) 
								{ 
									$stmt->bind_param('s', $id_cadena_user);
									$stmt->execute();    
									$stmt->store_result();
							 
									$stmt->bind_result($id_plan_credito,$nombre_plan_credito);
									$ii = 0;
									while($stmt->fetch())
									{
										if($ii == 0) echo '<option selected value="'.$id_plan_credito.'">'.$nombre_plan_credito.'</option>';
										else echo '<option value="'.$id_plan_credito.'">'.$nombre_plan_credito.'</option>';
										
										$ii++;
									}
									echo '<option selected value="'.translate('Lbl_All_Selection2',$GLOBALS['lang']).'">'.translate('Lbl_All_Selection2',$GLOBALS['lang']).'</option>';
								}
								else  
								{
									echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
									return;			
								}
							?>
						</select>
					</div>
					<div class="form-group" id="tipodocclientn">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="tipodocclientn"><?php echo translate('Lbl_Type_Document_User',$GLOBALS['lang']).': '; ?></label>
						<select class="form-control input-sm" name="tipodocclientni" id="tipodocclientni" style="width:193px;">		 
							<?php 		
								if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.tipo_documento ORDER BY id")) 
								{ 
									$stmt->bind_param('s', $id_cadena_user);
									$stmt->execute();    
									$stmt->store_result();
							 
									$stmt->bind_result($id_tipo_documento,$nombre_tipo_documento);
									$ii = 0;
									while($stmt->fetch())
									{
										if($ii == 0) echo '<option selected value="'.$id_tipo_documento.'">'.$nombre_tipo_documento.'</option>';
										else echo '<option value="'.$id_tipo_documento.'">'.$nombre_tipo_documento.'</option>';
										
										$ii++;
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
					<div class="form-group" id="nrodocumentclientn">
						&nbsp;&nbsp;<label class="control-label" for="nrodocumentclientn"><?php echo translate('Lbl_Document_Client',$GLOBALS['lang']).': '; ?></label>
						<input class="form-control input-sm" id="nrodocumentclientni" name="nrodocumentclientni" type="text" maxlength="16" style="width:191px;" />
					</div>
					<div class="form-group" id="tipodocclientn2">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="tipodocclientn2"><?php echo translate('Lbl_Type_Document_User',$GLOBALS['lang']).': '; ?></label>
						<select class="form-control input-sm" name="tipodocclientn2i" id="tipodocclientn2i" style="width:313px;">		 
							<?php 		
								if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.tipo_documento ORDER BY id")) 
								{ 
									$stmt->bind_param('s', $id_cadena_user);
									$stmt->execute();    
									$stmt->store_result();
							 
									$stmt->bind_result($id_tipo_documento,$nombre_tipo_documento);
									$ii = 0;
									while($stmt->fetch())
									{
										if($ii == 0) echo '<option selected value="'.$id_tipo_documento.'">'.$nombre_tipo_documento.'</option>';
										else echo '<option value="'.$id_tipo_documento.'">'.$nombre_tipo_documento.'</option>';
										
										$ii++;
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
					<div class="form-group" id="nrodocumentclientn2">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nrodocumentclientn2"><?php echo translate('Lbl_Document_Client',$GLOBALS['lang']).': '; ?></label>
						<input title="<?php echo translate('Msg_A_Document_Client_Must_Enter',$GLOBALS['lang']); ?>" class="form-control input-sm" id="nrodocumentclientn2i" name="nrodocumentclientn2i" type="text" maxlength="16" style="width:191px;" />
					</div>					
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
			
			$( "#reportescreditsni" ).change(function() 
			{
				if($("#reportescreditsni").val() == 1)
				{
					$("#fechadesdereporten").show();
					$("#fechahastareporten").show();
					
					$("#sucursalsn").hide();
					$("#tipoplann").hide();
					$("#tipodocclientn").hide();
					$("#nrodocumentclientn").hide();
					$("#tipodocclientn2").hide();
					$("#nrodocumentclientn2").hide();
				}
				
				if($("#reportescreditsni").val() == 2)
				{
					$("#fechadesdereporten").show();
					$("#fechahastareporten").show();
					$("#sucursalsn").show();
					$("#tipoplann").show();
					
					$("#tipodocclientn").hide();
					$("#nrodocumentclientn").hide();
					$("#tipodocclientn2").hide();
					$("#nrodocumentclientn2").hide();
				}

				
				if($("#reportescreditsni").val() == 3)
				{
					$("#fechadesdereporten").show();
					$("#fechahastareporten").show();
					$("#sucursalsn").show();
					
					$("#tipoplann").hide();
					$("#tipodocclientn").hide();
					$("#nrodocumentclientn").hide();
					$("#tipodocclientn2").hide();
					$("#nrodocumentclientn2").hide();
				}

				if($("#reportescreditsni").val() == 4)
				{
					$("#fechadesdereporten").show();
					$("#fechahastareporten").show();
					$("#sucursalsn").show();
					$("#tipodocclientn").show();
					$("#nrodocumentclientn").show();
					$("#nrodocumentclientni").focus();
					
					$("#tipoplann").hide();
					$("#tipodocclientn2").hide();
					$("#nrodocumentclientn2").hide();
				}

				if($("#reportescreditsni").val() == 5)
				{
					$("#fechadesdereporten").show();
					$("#fechahastareporten").show();
					$("#sucursalsn").show();
					$("#tipodocclientn").show();
					$("#nrodocumentclientn").show();
					$("#nrodocumentclientni").focus();
					
					$("#tipoplann").hide();
					$("#tipodocclientn2").hide();
					$("#nrodocumentclientn2").hide();
				}

				if($("#reportescreditsni").val() == 6)
				{
					$("#fechadesdereporten").show();
					$("#fechahastareporten").show();
					$("#sucursalsn").show();
					$("#tipodocclientn").show();
					$("#nrodocumentclientn").show();
					$("#nrodocumentclientni").focus();
					
					$("#tipoplann").hide();
					$("#tipodocclientn2").hide();
					$("#nrodocumentclientn2").hide();
				}

				if($("#reportescreditsni").val() == 7)
				{
					$("#fechadesdereporten").show();
					$("#fechahastareporten").show();
					$("#tipodocclientn2").show();
					$("#nrodocumentclientn2").show();
					$("#nrodocumentclientn2i").focus();
					
					$("#sucursalsn").hide();
					$("#tipoplann").hide();
					$("#tipodocclientn").hide();
					$("#nrodocumentclientn").hide();
				}

				if($("#reportescreditsni").val() == 8)
				{
					$("#fechadesdereporten").show();
					$("#fechahastareporten").show();
					$("#tipodocclientn2").show();
					$("#nrodocumentclientn2").show();
					$("#nrodocumentclientn2i").focus();
					
					$("#sucursalsn").hide();
					$("#tipoplann").hide();
					$("#tipodocclientn").hide();
					$("#nrodocumentclientn").hide();
				}

				if($("#reportescreditsni").val() == 9)
				{
					$("#fechadesdereporten").show();
					$("#fechahastareporten").show();
					$("#sucursalsn").show();
					$("#tipodocclientn").show();
					$("#nrodocumentclientn").show();
					$("#nrodocumentclientni").focus();
					
					$("#tipoplann").hide();
					$("#tipodocclientn2").hide();
					$("#nrodocumentclientn2").hide();
				}

				if($("#reportescreditsni").val() == 10)
				{
					$("#fechadesdereporten").show();
					$("#fechahastareporten").show();
					$("#sucursalsn").show();
					
					$("#tipoplann").hide();
					$("#tipodocclientn").hide();
					$("#nrodocumentclientn").hide();
					$("#tipodocclientn2").hide();
					$("#nrodocumentclientn2").hide();
				}

				if($("#reportescreditsni").val() == 11)
				{
					$("#fechadesdereporten").show();
					$("#fechahastareporten").show();
					$("#sucursalsn").show();
					
					$("#tipoplann").hide();
					$("#tipodocclientn").hide();
					$("#nrodocumentclientn").hide();
					$("#tipodocclientn2").hide();
					$("#nrodocumentclientn2").hide();
				}				
			});
			
			if($("#reportescreditsni").val() == 1)
			{
				$("#fechadesdereporten").show();
				$("#fechahastareporten").show();
				
				$("#sucursalsn").hide();
				$("#tipoplann").hide();
				$("#tipodocclientn").hide();
				$("#nrodocumentclientn").hide();
				$("#tipodocclientn2").hide();
				$("#nrodocumentclientn2").hide();
			}
		});
	</script>
</body>
</html>