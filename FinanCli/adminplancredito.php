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
	<title><?php echo translate('Lbl_Credit_Plan',$GLOBALS['lang']); ?></title>
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
		function nuevoPlanCredito()
		{
			var urlnpc = "./acciones/nuevoplancredito.php";
			var tagnpc = $("<div id='dialognewcreditplan'></div>");
			$('#img_loader_5').show();
			
			$.ajax({
				url: urlnpc,
				method: "POST",
				data: {},
				success: function(dataresponse, statustext, response){
					$('#img_loader_5').hide();
					tagnpc.html(dataresponse).dialog({
					  show: "blind",
					  hide: "explode",
					  height: "auto",
					  width: "auto",					  
					  modal: true, 
					  title: "<?php echo translate('Lbl_New_Credit_Plan',$GLOBALS['lang']);?>",
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
		function modificarPlanCredito(plancredito, nombre)
		{
			var urla = "./acciones/modificarplancredito.php";
			var tag = $("<div id='dialogmodifycreditplan'></div>");
			$('#img_loader').show();
			
			$.ajax({
				url: urla,
				method: "POST",
				data: { idPlanCredito: plancredito },
				success: function(dataresponse, statustext, response){
					$('#img_loader').hide();
					tag.html(dataresponse).dialog({
					  show: "blind",
					  hide: "explode",
					  height: "auto",
					  width: "auto",					  
					  modal: true, 
					  title: "<?php echo translate('Msg_Edit_Credit_Plan',$GLOBALS['lang']);?>: "+nombre,
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
		function guardarModificacionPlanCredito(formulariod, plancredito)
		{
			if($( "#nombreplancrediti" ).val().length == 0)
			{
				$(function() {
					$( "#nombreplancrediti" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#nombreplancrediti" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#nombreplancrediti" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#nombreplancrediti" ).tooltip('destroy');
			}
												
			if($( "#descripcionplancrediti" ).val().length == 0)
			{
				$(function() {
					$( "#descripcionplancrediti" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#descripcionplancrediti" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#descripcionplancrediti" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#descripcionplancrediti" ).tooltip('destroy');
			}
			
			
			if($( "#cantidadcuotasplancrediti" ).val().length == 0)
			{
				$('#cantidadcuotasplancrediti').prop('title', '<?php echo translate('Msg_Amount_Fees_Credit_Plan_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#cantidadcuotasplancrediti" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#cantidadcuotasplancrediti" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#cantidadcuotasplancrediti" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#cantidadcuotasplancrediti" ).tooltip('destroy');
			}			
			
			if($( "#cantidadcuotasplancrediti" ).val().length != 0)
			{			
				if (isNaN($( "#cantidadcuotasplancrediti" ).val()) || $( "#cantidadcuotasplancrediti" ).val() % 1 != 0)
				{
					$('#cantidadcuotasplancrediti').prop('title', '<?php echo translate('Msg_Amount_Fees_Credit_Plan_Must_Enter_A_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#cantidadcuotasplancrediti" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#cantidadcuotasplancrediti" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#cantidadcuotasplancrediti" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#cantidadcuotasplancrediti" ).tooltip('destroy');
				}
			}


			if($( "#interesfijoplancrediti" ).val().length == 0)
			{
				$('#interesfijoplancrediti').prop('title', '<?php echo translate('Msg_Fixed_Interest_Credit_Plan_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#interesfijoplancrediti" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#interesfijoplancrediti" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#interesfijoplancrediti" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#interesfijoplancrediti" ).tooltip('destroy');
			}			
			
			if($( "#interesfijoplancrediti" ).val().length != 0)
			{			
				if (isNaN($( "#interesfijoplancrediti" ).val()) || $( "#interesfijoplancrediti" ).val() % 1 != 0)
				{
					$('#interesfijoplancrediti').prop('title', '<?php echo translate('Msg_Fixed_Interest_Credit_Plan_Must_Enter_A_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#interesfijoplancrediti" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#interesfijoplancrediti" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#interesfijoplancrediti" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#interesfijoplancrediti" ).tooltip('destroy');
				}
			}			
			
			var urlgmu = "./acciones/guardarmodificacionplancredito.php";
			$('#img_loader_11').show();
			
			$.ajax({
				url: urlgmu,
				method: "POST",
				data: { idPlanCredito: plancredito, nombre: $( "#nombreplancrediti" ).val(), descripcion: $( "#descripcionplancrediti" ).val(), cantidadCuotas: $( "#cantidadcuotasplancrediti" ).val(), interesFijo: $( "#interesfijoplancrediti" ).val(), tipoDiferimientoCuota: $( "#tipodiferimientocuotasplancrediti" ).val(), cadena: $( "#cadenaplancrediti" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_11').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Modify_Credit_Plan_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialogmodifycreditplan').dialog('close');
						$('#tableadmincreditplant').bootstrapTable('load',JSON.parse(datTable));
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_11').hide();
				}
			});				
		}			
	</script>
	
	<script type="text/javascript">
		function guardarNuevoPlanCredito(formulariod)
		{
			if($( "#nombreplancreditni" ).val().length == 0)
			{
				$(function() {
					$( "#nombreplancreditni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#nombreplancreditni" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#nombreplancreditni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#nombreplancreditni" ).tooltip('destroy');
			}
												
			if($( "#descripcionplancreditni" ).val().length == 0)
			{
				$(function() {
					$( "#descripcionplancreditni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#descripcionplancreditni" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#descripcionplancreditni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#descripcionplancreditni" ).tooltip('destroy');
			}
			
			
			if($( "#cantidadcuotasplancreditni" ).val().length == 0)
			{
				$('#cantidadcuotasplancreditni').prop('title', '<?php echo translate('Msg_Amount_Fees_Credit_Plan_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#cantidadcuotasplancrediti" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#cantidadcuotasplancreditni" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#cantidadcuotasplancreditni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#cantidadcuotasplancreditni" ).tooltip('destroy');
			}			
			
			if($( "#cantidadcuotasplancreditni" ).val().length != 0)
			{			
				if (isNaN($( "#cantidadcuotasplancreditni" ).val()) || $( "#cantidadcuotasplancreditni" ).val() % 1 != 0)
				{
					$('#cantidadcuotasplancreditni').prop('title', '<?php echo translate('Msg_Amount_Fees_Credit_Plan_Must_Enter_A_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#cantidadcuotasplancreditni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#cantidadcuotasplancreditni" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#cantidadcuotasplancreditni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#cantidadcuotasplancreditni" ).tooltip('destroy');
				}
			}


			if($( "#interesfijoplancreditni" ).val().length == 0)
			{
				$('#interesfijoplancreditni').prop('title', '<?php echo translate('Msg_Fixed_Interest_Credit_Plan_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#interesfijoplancreditni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#interesfijoplancreditni" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#interesfijoplancreditni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#interesfijoplancreditni" ).tooltip('destroy');
			}			
			
			if($( "#interesfijoplancreditni" ).val().length != 0)
			{			
				if (isNaN($( "#interesfijoplancreditni" ).val()) || $( "#interesfijoplancreditni" ).val() % 1 != 0)
				{
					$('#interesfijoplancreditni').prop('title', '<?php echo translate('Msg_Fixed_Interest_Credit_Plan_Must_Enter_A_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#interesfijoplancreditni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#interesfijoplancreditni" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#interesfijoplancreditni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#interesfijoplancreditni" ).tooltip('destroy');
				}
			}			
						
			var urlggnu = "./acciones/guardarnuevoplancredito.php";
			$('#img_loader_11').show();
			
			$.ajax({
				url: urlggnu,
				method: "POST",
				data: { nombre: $( "#nombreplancreditni" ).val(), descripcion: $( "#descripcionplancreditni" ).val(), cantidadCuotas: $( "#cantidadcuotasplancreditni" ).val(), interesFijo: $( "#interesfijoplancreditni" ).val(), tipoDiferimientoCuota: $( "#tipodiferimientocuotasplancreditni" ).val(), cadena: $( "#cadenaplancreditni" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_11').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_New_Credit_Plan_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialognewcreditplan').dialog('close');
						$('#tableadmincreditplant').bootstrapTable('load',JSON.parse(datTable));
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_11').hide();
				}
			});
		}			
	</script>
		
	<script type="text/javascript">
		function borrar_plan_credito(plancredito)
		{
			var urlrdu = "./acciones/borrarplancredito.php";
			$('#img_loader').show();
			
			$.ajax({
				url: urlrdu,
				method: "POST",
				data: { idPlanCredito: plancredito },
				success: function(dataresponse, statustext, response){
					$('#img_loader').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Remove_Credit_Plan_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#tableadmincreditplant').bootstrapTable('load',JSON.parse(datTable));
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
		function confirmar_accion(titulo, mensaje, plancredito, nombre)
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
										
										borrar_plan_credito(plancredito);                                                      
								},
								"<?php echo translate('Lbl_Button_NO',$GLOBALS['lang']);?>": function () {
										$("#confirmDialog").dialog('close');
										$('#img_loader').hide();
										return;
								}
						}
				}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
				$( "#confirmDialog" ).html("<div id='confirmacionAccion'>"+mensaje+nombre+"?</div>");
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
			<h3 class="panel-title"><?php echo translate('Lbl_Profile_Credit',$GLOBALS['lang']); ?></h3>
		  </div>
		  <div id="apDiv1" class="panel-body">
			<div id="toolbar" style="margin-left:-98px; margin-top:-1px;">
				<button type="button" class="btn" data-toggle="tooltip" data-placement="top" onclick="nuevoPlanCredito();" title="<?php echo translate('Lbl_New_Credit_Plan',$GLOBALS['lang']);?>" ><i class="far fa-plus-square"></i></button>
			</div>
			<div id="img_loader"></div>
			<div id="tablaadmincreditplan" class="table-responsive">
				<table id="tableadmincreditplant" data-classes="table table-hover table-condensed"
				   data-striped="true" data-pagination="true" data-show-export="true" data-export-options='{"fileName": "<?php echo translate('File_Credit_Plan',$GLOBALS['lang']); ?>"}'
				   data-export-types="['excel','pdf','csv','txt']"
				   data-search="true" data-search-align="left" data-toolbar="#toolbar" data-toolbar-align="right">
					<thead>
						<tr>
							<th class="col-xs-2 text-center" data-field="nombre" data-sortable="true"><?php echo translate('Lbl_Name_Credit_Plan',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="cantidadcuotas" data-sortable="true"><?php echo translate('Lbl_Amount_Fees_Credit_Plan',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="interesfijo" data-sortable="true"><?php echo translate('Lbl_Fixed_Interest_Credit_Plan',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="diferimientocuotas" data-sortable="true"><?php echo translate('Lbl_Deferred_Installment_Credit_Plan',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="cadena" data-sortable="true"><?php echo translate('Lbl_Chain_Credit_Plan',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="acciones"><?php echo translate('Lbl_Actions_Credit_Plan',$GLOBALS['lang']);?></th>
						</tr>						
					</thead>
					<tbody>
						<?php
							if ($stmt = $mysqli->prepare("SELECT pc.id, pc.nombre, pc.descripcion, pc.cantidad_cuotas, pc.interes_fijo, par.valor, c.razon_social FROM finan_cli.plan_credito pc, finan_cli.cadena c, finan_cli.parametros par WHERE pc.id_cadena = c.id AND pc.id_tipo_diferimiento_cuota = par.id ORDER BY pc.cantidad_cuotas")) 
							{
								$stmt->execute();    // Ejecuta la consulta preparada.
								$stmt->store_result();
						 
								// Obtiene las variables del resultado.
								$stmt->bind_result($id_credit_plan, $name_credit_plan, $description_credit_plan, $cantidad_cuotas_credit_plan, $interes_fijo_credit_plan, $diferimiento_cuota_credit_plan, $cadena_credit_plan);
								
								while($stmt->fetch())
								{		
									echo '<tr>';
									echo '<td>'.$name_credit_plan.'</td>';
									echo '<td>'.$cantidad_cuotas_credit_plan.'</td>';
									echo '<td>'.$interes_fijo_credit_plan.'</td>';
									echo '<td>'.$diferimiento_cuota_credit_plan.'</td>';
									echo '<td>'.$cadena_credit_plan.'</td>';
									
									echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Credit_Plan',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Removed_Credit_Plan',$GLOBALS['lang']).'\',\''.$id_credit_plan.'\',\''.$name_credit_plan.'\')"><i class="fas fa-trash-alt"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Credit_Plan',$GLOBALS['lang']).'" onclick="modificarPlanCredito(\''.$id_credit_plan.'\',\''.$name_credit_plan.'\')"><i class="fas fa-edit"></i></button></td>';
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
			$('#tableadmincreditplant').bootstrapTable({locale:'es-AR'});
		});
	</script>	
</body>
</html>