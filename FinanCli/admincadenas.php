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
	<title><?php echo translate('Lbl_Chains',$GLOBALS['lang']); ?></title>
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
		function nuevaCadena()
		{
			document.getElementById("btnNuevaCadena").disabled = true;
			var urlnc = "./acciones/nuevacadena.php";
			var tagnc = $("<div id='dialognewchain'></div>");
			$('#img_loader_5').show();
			
			$.ajax({
				url: urlnc,
				method: "POST",
				data: {},
				success: function(dataresponse, statustext, response){
					$('#img_loader_5').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					tagnc.html(dataresponse).dialog({
					  show: "blind",
					  hide: "explode",
					  height: "auto",
					  width: "auto",					  
					  modal: true, 
					  title: "<?php echo translate('Lbl_New_Chain',$GLOBALS['lang']);?>",
					  autoResize:true,
							close: function(){
									tagnc.dialog('destroy').remove()
							}
					}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
					tagnc.dialog('open');
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_5').hide();
				}
			});
			document.getElementById("btnNuevaCadena").disabled = false;
		}
    </script>
	
	<script type="text/javascript">
		function asignarSucursales()
		{
			var pasoS = 0;
			$.each($("#boot-multiselect-sucursales-activas option:selected"), function()
			{
				pasoS = 1;
				
				$("#boot-multiselect-sucursales-asignadas").append('<option value="'+$(this).val()+'">'+$(this).text()+'</option>');
				$(this).remove();
				
				$("#boot-multiselect-sucursales-asignadas").multiselect('rebuild');
				$("#boot-multiselect-sucursales-activas").multiselect('rebuild');
			});	
			
			if(pasoS == 0) mensaje_atencion('<?php echo translate('Lbl_Information',$GLOBALS['lang']);?>','<?php echo translate('Lbl_Assign_Tenders_select',$GLOBALS['lang']);?>');
		}
    </script>
	
	<script type="text/javascript">
		function desasignarSucursales()
		{
			var pasoS2 = 0;
			$.each($("#boot-multiselect-sucursales-asignadas option:selected"), function()
			{
				pasoS2 = 1;
				
				$("#boot-multiselect-sucursales-activas").append('<option value="'+$(this).val()+'">'+$(this).text()+'</option>');
				$(this).remove();
				
				$("#boot-multiselect-sucursales-asignadas").multiselect('rebuild');
				$("#boot-multiselect-sucursales-activas").multiselect('rebuild');
			});	
			
			if(pasoS2 == 0) mensaje_atencion('<?php echo translate('Lbl_Information',$GLOBALS['lang']);?>','<?php echo translate('Lbl_Unassign_Tenders_select',$GLOBALS['lang']);?>');
		}
    </script>	

	<script type="text/javascript">
		function verSucursalesCadena(cadena, razonSocial)
		{
			document.getElementById("btnVerSucursalesCadena").disabled = true;
			var urlmtc = "./acciones/versucursalescadena.php";
			var tagmtc = $("<div id='dialogmodtenderchain'></div>");
			$('#img_loader_10').show();
			
			$.ajax({
				url: urlmtc,
				method: "POST",
				data: { idCadena: cadena },
				success: function(dataresponse, statustext, response){
					$('#img_loader_10').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
										
					tagmtc.html(dataresponse).dialog({
					  show: "blind",
					  hide: "explode",
					  height: "auto",
					  width: "auto",					  
					  modal: true, 
					  title: "<?php echo translate('Lbl_View_Tenders_X_Chain',$GLOBALS['lang']);?>: "+razonSocial,
					  autoResize:true,
							close: function(){
									tagmtc.dialog('destroy').remove()
							}
					}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
					
					tagmtc.dialog('open');
					
					$('#boot-multiselect-sucursales-activas').multiselect({
						includeSelectAllOption: true,
						buttonWidth: 190,
						enableFiltering: true
					});
					
					$('#boot-multiselect-sucursales-asignadas').multiselect({
						includeSelectAllOption: true,
						buttonWidth: 190,
						enableFiltering: true
					});					
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_10').hide();
				}
			});
			document.getElementById("btnVerSucursalesCadena").disabled = false;
		}
    </script>
	
	<script type="text/javascript">
		function guardarSucursalesCadena(idCadena)
		{
			document.getElementById("btnCargar").disabled = true;
			var sucursales = "";
			$("#boot-multiselect-sucursales-asignadas > option").each(function(){
			   if(!sucursales) sucursales = this.value;
			   else sucursales = sucursales+","+this.value;   
			});

			var urlmtsc = "./acciones/guardarsucursalescadena.php";
			$('#img_loader_10').show();
			
			$.ajax({
				url: urlmtsc,
				method: "POST",
				data: { idCadena: idCadena, idSucursales: sucursales },
				success: function(dataresponse, statustext, response){
					$('#img_loader_10').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Save_Assign_Tenders_To_Chain_OK',$GLOBALS['lang']);?>') != -1)
					{
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",dataresponse);
						$('#dialogmodtenderchain').dialog('close');
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);					
					
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_10').hide();
				}
			});
			document.getElementById("btnCargar").disabled = false;
		}
    </script>	
	
	<script type="text/javascript">
		function modificarCadena(cadena, razonSocial)
		{
			document.getElementById("btnModificarCadena"+cadena).disabled = true;
			var urla = "./acciones/modificarcadena.php";
			var tag = $("<div id='dialogmodchain'></div>");
			$('#img_loader').show();
			
			$.ajax({
				url: urla,
				method: "POST",
				data: { idCadena: cadena },
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
					  title: "<?php echo translate('Msg_Edit_Chain',$GLOBALS['lang']);?>: "+razonSocial,
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
			document.getElementById("btnModificarCadena"+cadena).disabled = false;
		}
    </script>
	
	<script type="text/javascript">
		function guardarModificacionCadena(formulariod, cadena)
		{
			document.getElementById("btnCargar").disabled = true;
			if($( "#razonsocialchaini" ).val().length == 0)
			{
				$(function() {
					$( "#razonsocialchaini" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#razonsocialchaini" ).focus();
				document.getElementById("btnCargar").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#razonsocialchaini" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#razonsocialchaini" ).tooltip('destroy');
			}
			
			if($( "#cuitcuilchaini" ).val().length == 0)
			{
				$(function() {
					$( "#cuitcuilchaini" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#cuitcuilchaini" ).focus();
				document.getElementById("btnCargar").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#cuitcuilchaini" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#cuitcuilchaini" ).tooltip('destroy');
			}

			if($( "#nombrefantasiachaini" ).val().length == 0)
			{
				$(function() {
					$( "#nombrefantasiachaini" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#nombrefantasiachaini" ).focus();
				document.getElementById("btnCargar").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#nombrefantasiachaini" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#nombrefantasiachaini" ).tooltip('destroy');
			}

			
			if($( "#emailchaini" ).val().length != 0)
			{						
				if(!caracteresCorreoValido($( "#emailchaini" ).val()))
				{
					$(function() {
						$( "#emailchaini" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#emailchaini" ).focus();
					document.getElementById("btnCargar").disabled = false;
					return;				
				}
				else
				{
					$(function() {
						$( "#emailchaini" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});				
					$( "#emailchaini" ).tooltip('destroy');				
				}
			}				

			if($( "#telefonochaini" ).val().length != 0)
			{			
				if (isNaN($( "#telefonochaini" ).val()) || $( "#telefonochaini" ).val() % 1 != 0)
				{
					$(function() {
						$( "#telefonochaini" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#telefonochaini" ).focus();
					document.getElementById("btnCargar").disabled = false;
					return;
				}
				else
				{
					$(function() {
						$( "#telefonochaini" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#telefonochaini" ).tooltip('destroy');
				}
			}
			
			var urlgmu = "./acciones/guardarmodificacioncadena.php";
			$('#img_loader_9').show();
			
			$.ajax({
				url: urlgmu,
				method: "POST",
				data: { idCadena: cadena, razonSocial: $( "#razonsocialchaini" ).val(), cuitCuil: $( "#cuitcuilchaini" ).val(), email: $( "#emailchaini" ).val(), telefono: $( "#telefonochaini" ).val(), nombreFantasia: $( "#nombrefantasiachaini" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_9').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Modify_Chain_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialogmodchain').dialog('close');
						$('#tableadminchainst').bootstrapTable('load',JSON.parse(datTable));
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_9').hide();
				}
			});				
			document.getElementById("btnCargar").disabled = false;
		}			
	</script>
	
	<script type="text/javascript">
		function guardarNuevaCadena(formulariod)
		{
			document.getElementById("btnCargarN").disabled = true;
			if($( "#razonsocialchainni" ).val().length == 0)
			{
				$(function() {
					$( "#razonsocialchainni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#razonsocialchainni" ).focus();
				document.getElementById("btnCargarN").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#razonsocialchainni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#razonsocialchainni" ).tooltip('destroy');
			}
			
			if($( "#cuitcuilchainni" ).val().length == 0)
			{
				$('#cuitcuilchainni').prop('title', '<?php echo translate('Msg_A_CUIT_CUIL_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#cuitcuilchainni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#cuitcuilchainni" ).focus();
				document.getElementById("btnCargarN").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#cuitcuilchainni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#cuitcuilchainni" ).tooltip('destroy');
			}
			
			if (isNaN($( "#cuitcuilchainni" ).val()) || $( "#cuitcuilchainni" ).val() % 1 != 0)
			{
				$('#cuitcuilchainni').prop('title', '<?php echo translate('Msg_A_CUIT_CUIL_Must_Enter_A_Whole',$GLOBALS['lang']);?>');
				$(function() {
					$( "#cuitcuilchainni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#cuitcuilchainni" ).focus();
				document.getElementById("btnCargarN").disabled = false;
				return;
			}
			else
			{
				$(function() {
					$( "#cuitcuilchainni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});					
				$( "#cuitcuilchainni" ).tooltip('destroy');
			}			

			if($( "#nombrefantasiachainni" ).val().length == 0)
			{
				$(function() {
					$( "#nombrefantasiachainni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#nombrefantasiachainni" ).focus();
				document.getElementById("btnCargarN").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#nombrefantasiachainni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#nombrefantasiachainni" ).tooltip('destroy');
			}

			
			if($( "#emailchainni" ).val().length != 0)
			{						
				if(!caracteresCorreoValido($( "#emailchainni" ).val()))
				{
					$(function() {
						$( "#emailchainni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#emailchainni" ).focus();
					document.getElementById("btnCargarN").disabled = false;
					return;				
				}
				else
				{
					$(function() {
						$( "#emailchainni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});				
					$( "#emailchainni" ).tooltip('destroy');				
				}
			}				

			if($( "#telefonochainni" ).val().length != 0)
			{			
				if (isNaN($( "#telefonochainni" ).val()) || $( "#telefonochainni" ).val() % 1 != 0)
				{
					$(function() {
						$( "#telefonochainni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#telefonochainni" ).focus();
					document.getElementById("btnCargarN").disabled = false;
					return;
				}
				else
				{
					$(function() {
						$( "#telefonochainni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#telefonochainni" ).tooltip('destroy');
				}
			}
		
			
			var urlggnu = "./acciones/guardarnuevacadena.php";
			$('#img_loader_5').show();
			
			$.ajax({
				url: urlggnu,
				method: "POST",
				data: { razonSocial: $( "#razonsocialchainni" ).val(), cuitCuil: $( "#cuitcuilchainni" ).val(), email: $( "#emailchainni" ).val(), telefono: $( "#telefonochainni" ).val(), nombreFantasia: $( "#nombrefantasiachainni" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_5').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_New_Chain_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialognewchain').dialog('close');
						$('#tableadminchainst').bootstrapTable('load',JSON.parse(datTable));
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_5').hide();
				}
			});				
			document.getElementById("btnCargarN").disabled = false;
		}			
	</script>
	
	<script type="text/javascript">
		function borrar_cadena(cadena)
		{
			var urlrdu = "./acciones/borrarcadena.php";
			$('#img_loader').show();
			
			$.ajax({
				url: urlrdu,
				method: "POST",
				data: { idCadena: cadena },
				success: function(dataresponse, statustext, response){
					$('#img_loader').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Remove_Chain_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#tableadminchainst').bootstrapTable('load',JSON.parse(datTable));
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
											if(mensaje.indexOf('<?php echo translate('Msg_Save_Assign_Tenders_To_Chain_OK',$GLOBALS['lang']);?>') != -1)
											{
												$('#dialogmodtenderchain').dialog('close');
											}
									}
							}
					}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
					$( "#okDialog" ).html("<div id='mensajeOK'>"+mensaje+"</div>");
		}
    </script>	
	<script type="text/javascript">
		function confirmar_accion(titulo, mensaje, cadena, razonsocial)
		{
			document.getElementById("btnBorrarCadena"+cadena).disabled = true;
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
										
										borrar_cadena(cadena);                                                      
								},
								"<?php echo translate('Lbl_Button_NO',$GLOBALS['lang']);?>": function () {
										$("#confirmDialog").dialog('close');
										$('#img_loader').hide();
										document.getElementById("btnBorrarCadena"+cadena).disabled = false;
										return;
								}
						}
				}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
				$( "#confirmDialog" ).html("<div id='confirmacionAccion'>"+mensaje+razonsocial+"?</div>");
				$('#img_loader').hide();
			document.getElementById("btnBorrarCadena"+cadena).disabled = false;
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
			<h3 class="panel-title"><?php echo translate('Lbl_Chains',$GLOBALS['lang']); ?></h3>
		  </div>
		  <div id="apDiv1" class="panel-body">
			<div id="toolbar" style="margin-left:-98px; margin-top:-1px;">
				<button type="button" id="btnNuevaCadena" class="btn" data-toggle="tooltip" data-placement="top" onclick="nuevaCadena();" title="<?php echo translate('Lbl_New_Chain',$GLOBALS['lang']);?>" ><i class="fas fa-plus-circle"></i></button>
			</div>
			<div id="img_loader"></div>
			<div id="tablaadminchains" class="table-responsive">
				<table id="tableadminchainst" data-classes="table table-hover table-condensed"
				   data-striped="true" data-pagination="true" data-show-export="true" data-export-options='{"fileName": "<?php echo translate('Lbl_Chains',$GLOBALS['lang']); ?>"}'
				   data-export-types="['excel','pdf','csv','txt']"
				   data-search="true" data-search-align="left" data-toolbar="#toolbar" data-toolbar-align="right">
					<thead>
						<tr>
							<th class="col-xs-1 text-center" data-field="razonsocial" data-sortable="true"><?php echo translate('Lbl_Business Name_Chain',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="cuitcuil" data-sortable="true"><?php echo translate('Lbl_CUIT_CUIL_Chain',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="nombrefantasia" data-sortable="true"><?php echo translate('Lbl_Fantasy_Name_Chain',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="acciones"><?php echo translate('Lbl_Actions_Chain',$GLOBALS['lang']);?></th>
						</tr>						
					</thead>
					<tbody>
						<?php
							if ($stmt = $mysqli->prepare("SELECT c.id, c.razon_social, c.cuit_cuil, c.nombre_fantasia FROM ".$db_name.".cadena c ORDER BY c.id")) 
							{
								$stmt->execute();    // Ejecuta la consulta preparada.
								$stmt->store_result();
						 
								// Obtiene las variables del resultado.
								$stmt->bind_result($id_chain, $razon_social_chain, $cuit_cuil_chain, $nombre_fantasia_chain);
								
								while($stmt->fetch())
								{		
									echo '<tr>';
									echo '<td>'.$razon_social_chain.'</td>';
									echo '<td>'.$cuit_cuil_chain.'</td>';
									echo '<td>'.$nombre_fantasia_chain.'</td>';
									
									echo '<td><button type="button" id="btnBorrarCadena'.$id_chain.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Chain',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Removed_Chain',$GLOBALS['lang']).'\',\''.$id_chain.'\',\''.$razon_social_chain.'\')"><i class="fas fa-unlink"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" id="btnModificarCadena'.$id_chain.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Chain',$GLOBALS['lang']).'" onclick="modificarCadena(\''.$id_chain.'\',\''.$razon_social_chain.'\')"><i class="fas fa-edit"></i></button></td>';
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
			$('#tableadminchainst').bootstrapTable({locale:'es-AR'});
			  
		});
	</script>	
</body>
</html>