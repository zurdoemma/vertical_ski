<?php
include ('./utiles/funciones.php');
require("../parametrosbasedatosfc.php");
$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
mysqli_set_charset($mysqli,"utf8");
if (!verificar_usuario($mysqli)){header('Location:./login.php');return;}
if (!verificar_permisos_admin()){header('Location:./sinautorizacion.php?activauto=1');return;}
include("./menu/menu.php");
?>
<!doctype html>
<html lang="es-es">
<head>
	<meta charset="UTF-8">
	
	<link rel="shortcut icon" href="./images/iconoFinanCli.png" >
	<title><?php echo translate('Lbl_Interest_For_Late_Payment',$GLOBALS['lang']); ?></title>
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.op2.css" >
	<link rel="stylesheet" type="text/css" href="./css/bootstrap-theme.min.op2.css" >	
	<link rel="stylesheet" type="text/css" href="./css/bootstrap-table.min.op2.css" >
	<link rel="stylesheet" href="./css/fontawesome.min.css">
	<link rel="stylesheet" href="./css/all.css">
	<link rel="stylesheet" type="text/css" href="./css/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="./css/bootstrap-multiselect.css">	
	
	<script type="text/javascript" src="./js/jquery.js"></script>
	<script type="text/JavaScript" src="./js/bootstrap.min.op2.js" ></script>
	<script type="text/javascript" src="./js/jquery-ui.js"></script>
	<script type="text/JavaScript" src="./js/bootstrap-multiselect.js" ></script>	
	<script type="text/JavaScript" src="./js/moment.op2.js" ></script>	
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
	
	<link rel="stylesheet" href="./css/fondo.op2.css">
	<link rel="stylesheet" href="./css/estilos.op2.css">
	
	<script type="text/javascript">
		function nuevoInteresXMora()
		{
			var urlnpc = "./acciones/nuevointeresxmora.php";
			var tagnpc = $("<div id='dialognewinteresxmora'></div>");
			$('#img_loader_5').show();
			
			$.ajax({
				url: urlnpc,
				method: "POST",
				data: {},
				success: function(dataresponse, statustext, response){
					$('#img_loader_5').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
										
					tagnpc.html(dataresponse).dialog({
					  show: "blind",
					  hide: "explode",
					  height: "auto",
					  width: "auto",					  
					  modal: true, 
					  title: "<?php echo translate('Lbl_New_Interest_For_Late_Payment',$GLOBALS['lang']);?>",
					  autoResize:true,
							close: function(){
									tagnpc.dialog('destroy').remove()
							}
					}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
					tagnpc.dialog('open');
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_5').hide();
				}
			});	
		}
    </script>
			
	<script type="text/javascript">
		function modificarInteresXMora(interesxmora)
		{
			var urla = "./acciones/modificarinteresxmora.php";
			var tag = $("<div id='dialogmodifyinteresxmora'></div>");
			$('#img_loader').show();
			
			$.ajax({
				url: urla,
				method: "POST",
				data: { idInteresXMora: interesxmora },
				success: function(dataresponse, statustext, response){
					$('#img_loader').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
										
					tag.html(dataresponse).dialog({
					  show: "blind",
					  hide: "explode",
					  height: "auto",
					  width: "auto",					  
					  modal: true, 
					  title: "<?php echo translate('Msg_Edit_Interest_For_Late_Payment',$GLOBALS['lang']);?>",
					  autoResize:true,
							close: function(){
									tag.dialog('destroy').remove()
							}
					}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
					tag.dialog('open');
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader').hide();
				}
			});
		}
    </script>
	
	<script type="text/javascript">
		function guardarModificacionInteresXMora(formulariod, interesxmora)
		{			
			if($( "#cantidaddiasinteresxmorai" ).val().length == 0)
			{
				$('#cantidaddiasinteresxmorai').prop('title', '<?php echo translate('Msg_Amount_Days_Interest_For_Late_Payment_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#cantidaddiasinteresxmorai" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#cantidaddiasinteresxmorai" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#cantidaddiasinteresxmorai" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#cantidaddiasinteresxmorai" ).tooltip('destroy');
			}			
			
			if($( "#cantidaddiasinteresxmorai" ).val().length != 0)
			{			
				if (isNaN($( "#cantidaddiasinteresxmorai" ).val()) || $( "#cantidaddiasinteresxmorai" ).val() % 1 != 0)
				{
					$('#cantidaddiasinteresxmorai').prop('title', '<?php echo translate('Msg_Amount_Days_Interest_For_Late_Payment_Must_Enter_A_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#cantidaddiasinteresxmorai" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#cantidaddiasinteresxmorai" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#cantidaddiasinteresxmorai" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#cantidaddiasinteresxmorai" ).tooltip('destroy');
				}
			}


			if($( "#interesxmorai" ).val().length == 0)
			{
				$('#interesxmorai').prop('title', '<?php echo translate('Msg_Interest_For_Late_Payment_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#interesxmorai" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#interesxmorai" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#interesxmorai" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#interesxmorai" ).tooltip('destroy');
			}			
			
			if($( "#interesxmorai" ).val().length != 0)
			{			
				if (isNaN($( "#interesxmorai" ).val()) || $( "#interesxmorai" ).val() % 1 != 0)
				{
					$('#interesxmorai').prop('title', '<?php echo translate('Msg_Interest_For_Late_Payment_Must_Enter_A_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#interesxmorai" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#interesxmorai" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#interesxmorai" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#interesxmorai" ).tooltip('destroy');
				}
			}			
			
			var urlgmu = "./acciones/guardarmodificacioninteresxmora.php";
			$('#img_loader_22').show();
			
			$.ajax({
				url: urlgmu,
				method: "POST",
				data: { idInteresXMora: interesxmora, cantidadDias: $( "#cantidaddiasinteresxmorai" ).val(), interes: $( "#interesxmorai" ).val(), planCredito: $( "#plancreditointeresxmorai" ).val(), recurrente: $('#recurrenciainteresxmorai').is(":checked") },
				success: function(dataresponse, statustext, response){
					$('#img_loader_22').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Modify_Interest_For_Late_Payment_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialogmodifyinteresxmora').dialog('close');
						$('#tableadmininteresxmorat').bootstrapTable('load',JSON.parse(datTable));
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_22').hide();
				}
			});				
		}			
	</script>
	
	<script type="text/javascript">
		function guardarNuevoInteresXMora(formulariod)
		{
			if($( "#cantidaddiasinteresxmorani" ).val().length == 0)
			{
				$('#cantidaddiasinteresxmorani').prop('title', '<?php echo translate('Msg_Amount_Days_Interest_For_Late_Payment_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#cantidaddiasinteresxmorani" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#cantidaddiasinteresxmorani" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#cantidaddiasinteresxmorani" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#cantidaddiasinteresxmorani" ).tooltip('destroy');
			}			
			
			if($( "#cantidaddiasinteresxmorani" ).val().length != 0)
			{			
				if (isNaN($( "#cantidaddiasinteresxmorani" ).val()) || $( "#cantidaddiasinteresxmorani" ).val() % 1 != 0)
				{
					$('#cantidaddiasinteresxmorani').prop('title', '<?php echo translate('Msg_Amount_Days_Interest_For_Late_Payment_Must_Enter_A_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#cantidaddiasinteresxmorani" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#cantidaddiasinteresxmorani" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#cantidaddiasinteresxmorani" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#cantidaddiasinteresxmorani" ).tooltip('destroy');
				}
			}


			if($( "#interesxmorani" ).val().length == 0)
			{
				$('#interesxmorani').prop('title', '<?php echo translate('Msg_Interest_For_Late_Payment_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#interesxmorani" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#interesxmorani" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#interesxmorani" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#interesxmorani" ).tooltip('destroy');
			}			
			
			if($( "#interesxmorani" ).val().length != 0)
			{			
				if (isNaN($( "#interesxmorani" ).val()) || $( "#interesxmorani" ).val() % 1 != 0)
				{
					$('#interesxmorani').prop('title', '<?php echo translate('Msg_Interest_For_Late_Payment_Must_Enter_A_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#interesxmorani" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#interesxmorani" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#interesxmorani" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#interesxmorani" ).tooltip('destroy');
				}
			}			
						
			var urlggnu = "./acciones/guardarnuevointeresxmora.php";
			$('#img_loader_22').show();
			
			$.ajax({
				url: urlggnu,
				method: "POST",
				data: { cantidadDias: $( "#cantidaddiasinteresxmorani" ).val(), interes: $( "#interesxmorani" ).val(), planCredito: $( "#plancreditointeresxmorani" ).val(), recurrente: $('#recurrenciainteresxmorani').is(":checked") },
				success: function(dataresponse, statustext, response){
					$('#img_loader_22').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_New_Interest_For_Late_Payment_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialognewinteresxmora').dialog('close');
						$('#tableadmininteresxmorat').bootstrapTable('load',JSON.parse(datTable));
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_22').hide();
				}
			});
		}			
	</script>
		
	<script type="text/javascript">
		function borrar_interes_x_mora(interesxmora)
		{
			var urlrdu = "./acciones/borrarinteresxmora.php";
			$('#img_loader').show();
			
			$.ajax({
				url: urlrdu,
				method: "POST",
				data: { idInteresXMora: interesxmora },
				success: function(dataresponse, statustext, response){
					$('#img_loader').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Remove_Interest_For_Late_Payment_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						var estaVaciaTabla = 0;
						var resBTU = JSON.parse(datTable);
						
						for(var i in resBTU)
						{
							if(resBTU[i]["interes"] == null || resBTU[i]["interes"] === '') 
							{
								estaVaciaTabla = 1;
								break;
							}
						}
						
						if(estaVaciaTabla == 0) $('#tableadmininteresxmorat').bootstrapTable('load',JSON.parse(datTable));
						else $('#tableadmininteresxmorat').bootstrapTable('removeAll');
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
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
		function confirmar_accion(titulo, mensaje, interesxmora)
		{
			$( "#confirmDialog" ).dialog({
						title:titulo,
						show:"blind",
						modal: true,
						hide:"slide",
						resizable: false,
						height: "auto",
						width: "auto",
						buttons: {
								"<?php echo translate('Lbl_Button_YES',$GLOBALS['lang']);?>": function () {
										$("#confirmDialog").dialog('close');
										
										borrar_interes_x_mora(interesxmora);                                                      
								},
								"<?php echo translate('Lbl_Button_NO',$GLOBALS['lang']);?>": function () {
										$("#confirmDialog").dialog('close');
										$('#img_loader').hide();
										return;
								}
						}
				}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
				$( "#confirmDialog" ).html("<div id='confirmacionAccion'>"+mensaje+"?</div>");
				$('#img_loader').hide();
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
				<strong>| <?php echo translate('Lbl_Date',$GLOBALS['lang']); ?>:</strong> <?php date_default_timezone_set("America/Argentina/Buenos_Aires"); $fecha = date("d/m/Y"); echo $fecha;  ?>
				 - <strong><?php echo translate('Lbl_User',$GLOBALS['lang']); ?>:</strong><?php $usuario = $_SESSION['username']; echo"$usuario"; ?> |
			</a>
		</div>
	  </div>
	</nav></br></br></br></br>
	<div class="panel-group" style="padding-bottom:50px;">				
		<div class="panel panel-default" style="margin-left:30px;margin-right:30px;">
		  <div id="panel-title-header" class="panel-heading">
			<h3 class="panel-title"><?php echo translate('Lbl_Interest_For_Late_Payment',$GLOBALS['lang']); ?></h3>
		  </div>
		  <div id="apDiv1" class="panel-body">
			<div id="toolbar" style="margin-left:-98px; margin-top:-1px;">
				<button type="button" class="btn" data-toggle="tooltip" data-placement="top" onclick="nuevoInteresXMora();" title="<?php echo translate('Lbl_New_Interest_For_Late_Payment',$GLOBALS['lang']);?>" ><i class="far fa-plus-square"></i></button>
			</div>
			<div id="img_loader"></div>
			<div id="tablaadmininteresxmora" class="table-responsive">
				<table id="tableadmininteresxmorat" data-classes="table table-hover table-condensed"
				   data-striped="true" data-pagination="true" data-show-export="true" data-export-options='{"fileName": "<?php echo translate('File_Interest_For_Late_Payment',$GLOBALS['lang']); ?>"}'
				   data-export-types="['excel','pdf','csv','txt']"
				   data-search="true" data-search-align="left" data-toolbar="#toolbar" data-toolbar-align="right">
					<thead>
						<tr>
							<th class="col-xs-1 text-center" data-field="cantidaddias" data-sortable="true"><?php echo translate('Lbl_Amount_Days_Interest_For_Late_Payment',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="interes" data-sortable="true"><?php echo translate('Lbl_Interest_For_Late_Payment',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="plancredito" data-sortable="true"><?php echo translate('Lbl_Credit_Plan_Interest_For_Late_Payment',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="recurrente" data-sortable="true"><?php echo translate('Lbl_Recurrent_Interest',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="acciones"><?php echo translate('Lbl_Actions_Interest_For_Late_Payment',$GLOBALS['lang']);?></th>
						</tr>						
					</thead>
					<tbody>
						<?php
							if ($stmt = $mysqli->prepare("SELECT ixm.id, ixm.cantidad_dias, ixm.interes, pc.nombre, ixm.recurrente FROM finan_cli.interes_x_mora ixm, finan_cli.plan_credito pc WHERE pc.id = ixm.id_plan_credito ORDER BY pc.cantidad_cuotas, ixm.cantidad_dias")) 
							{
								$stmt->execute();    // Ejecuta la consulta preparada.
								$stmt->store_result();
						 
								// Obtiene las variables del resultado.
								$stmt->bind_result($id_interes_x_mora, $cantidad_dias_interes_x_mora, $interes_x_mora, $plan_credito_interes_x_mora, $recurrente_interes_x_mora);
								
								while($stmt->fetch())
								{		
									echo '<tr>';
									echo '<td>'.$cantidad_dias_interes_x_mora.'</td>';
									echo '<td>'.$interes_x_mora.'</td>';
									echo '<td>'.$plan_credito_interes_x_mora.'</td>';
									if($recurrente_interes_x_mora == 1)	echo '<td>'.translate('Lbl_Button_YES',$GLOBALS['lang']).'</td>';
									else echo '<td>'.translate('Lbl_Button_NO',$GLOBALS['lang']).'</td>';
									echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Interest_For_Late_Payment',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Removed_Interest_For_Late_Payment',$GLOBALS['lang']).'\',\''.$id_interes_x_mora.'\')"><i class="fas fa-trash-alt"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Interest_For_Late_Payment',$GLOBALS['lang']).'" onclick="modificarInteresXMora(\''.$id_interes_x_mora.'\')"><i class="fas fa-edit"></i></button></td>';
									echo '</tr>';
								}
							}
						?>						
					</tbody>					
				</table>
			</div>
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
		$(function () 
		{
			$('#tableadmininteresxmorat').bootstrapTable({locale:'es-AR'});
		});
	</script>	
</body>
</html>