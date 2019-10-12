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
		function nuevoBin()
		{
			document.getElementById("btnNuevoBin").disabled = true;
			var urlnb = "./acciones/nuevobin.php";
			var tagnb = $("<div id='dialognewbin'></div>");
			$('#img_loader_2').show();
			
			$.ajax({
				url: urlnb,
				method: "POST",
				data: {},
				success: function(dataresponse, statustext, response){
					$('#img_loader_2').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					tagnb.html(dataresponse).dialog({
					  show: "blind",
					  hide: "explode",
					  height: "auto",
					  width: "auto",					  
					  modal: true, 
					  title: "<?php echo translate('Lbl_New_Bin',$GLOBALS['lang']);?>",
					  autoResize:true,
							close: function(){
									tagnb.dialog('destroy').remove()
							}
					}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
					tagnb.dialog('open');
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_2').hide();
				}
			});
			document.getElementById("btnNuevoBin").disabled = false;
		}
    </script>
	
	<script type="text/javascript">
		function guardarNuevoBin(formularionb)
		{
			document.getElementById("btnCargarNB").disabled = true;
			if($( "#binlengthni" ).val().length == 0)
			{
				$('#binlengthni').prop('title', '<?php echo translate('Msg_A_Bin_Length_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#binlengthni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#binlengthni" ).focus();
				document.getElementById("btnCargarNB").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#binlengthni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#binlengthni" ).tooltip('destroy');
			}			
			
			if($( "#binlengthni" ).val().length != 0)
			{			
				if (isNaN($( "#binlengthni" ).val()) || $( "#binlengthni" ).val() % 1 != 0)
				{
					$('#binlengthni').prop('title', '<?php echo translate('Msg_A_Bin_Length_Must_Enter_A_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#binlengthni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#binlengthni" ).focus();
					document.getElementById("btnCargarNB").disabled = false;
					return;
				}
				else
				{
					$(function() {
						$( "#binlengthni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#binlengthni" ).tooltip('destroy');
				}
			}


			if($( "#rangefrombinni" ).val().length == 0)
			{
				$('#rangefrombinni').prop('title', '<?php echo translate('Msg_A_Range_From_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#rangefrombinni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#rangefrombinni" ).focus();
				document.getElementById("btnCargarNB").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#rangefrombinni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#rangefrombinni" ).tooltip('destroy');
			}

			if($( "#rangefrombinni" ).val().length != 0)
			{			
				if (isNaN($( "#rangefrombinni" ).val()) || $( "#rangefrombinni" ).val() % 1 != 0)
				{
					$('#rangefrombinni').prop('title', '<?php echo translate('Msg_A_Range_From_Must_Enter_A_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#rangefrombinni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#rangefrombinni" ).focus();
					document.getElementById("btnCargarNB").disabled = false;
					return;
				}
				else
				{
					$(function() {
						$( "#rangefrombinni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#rangefrombinni" ).tooltip('destroy');
				}
			}			
			
			if($( "#rangetobinni" ).val().length == 0)
			{
				$('#rangetobinni').prop('title', '<?php echo translate('Msg_A_Range_To_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#rangetobinni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#rangetobinni" ).focus();
				document.getElementById("btnCargarNB").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#rangetobinni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#rangetobinni" ).tooltip('destroy');
			}

			if($( "#rangetobinni" ).val().length != 0)
			{			
				if (isNaN($( "#rangetobinni" ).val()) || $( "#rangetobinni" ).val() % 1 != 0)
				{
					$('#rangetobinni').prop('title', '<?php echo translate('Msg_A_Range_To_Must_Enter_A_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#rangetobinni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#rangetobinni" ).focus();
					document.getElementById("btnCargarNB").disabled = false;
					return;
				}
				else
				{
					$(function() {
						$( "#rangetobinni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#rangetobinni" ).tooltip('destroy');
				}
			}

			if($( "#rangetobinni" ).val().length <  $( "#binlengthni" ).val() || $( "#rangefrombinni" ).val().length < $( "#binlengthni" ).val())
			{
				$( "#binlengthni" ).focus();
				document.getElementById("btnCargarNB").disabled = false;
				mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_Range_From_Or_To_Must_Least_Length_Bin_Entered',$GLOBALS['lang']);?>");
				return;
			}			
			
			if($( "#rangetobinni" ).val() < $( "#rangefrombinni" ).val())
			{
				$( "#rangetobinni" ).focus();
				document.getElementById("btnCargarNB").disabled = false;
				mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_Range_From_Bin_Cant_be_older_To',$GLOBALS['lang']);?>");
				return;
			}
			
			if($( "#rangetobinni" ).val().length != $( "#rangefrombinni" ).val().length)
			{
				$( "#rangetobinni" ).focus();
				document.getElementById("btnCargarNB").disabled = false;
				mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_Range_From_Must_Have_Same_Number_Of_Numbers',$GLOBALS['lang']);?>");
				return;
			}
			
			var urlggnu = "./acciones/guardarnuevobin.php";
			$('#img_loader_3').show();
			
			$.ajax({
				url: urlggnu,
				method: "POST",
				data: { idTarjeta: $( "#tarjetabinni" ).val(), largoBin: $( "#binlengthni" ).val(), rangoDesde: $( "#rangefrombinni" ).val(), rangoHasta: $( "#rangetobinni" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_3').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_New_Bin_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialognewbin').dialog('close');
						$('#tableadminbint').bootstrapTable('load',JSON.parse(datTable));
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_3').hide();
				}
			});
			document.getElementById("btnCargarNB").disabled = false;
		}			
	</script>

	<script type="text/javascript">
		function modificarBin(binId)
		{
			document.getElementById("btnModificarBin"+binId).disabled = true;
			var urlmb = "./acciones/modificarbin.php";
			var tagmb = $("<div id='dialogmodifybin'></div>");
			$('#img_loader_2').show();
			
			$.ajax({
				url: urlmb,
				method: "POST",
				data: {idBin: binId},
				success: function(dataresponse, statustext, response){
					$('#img_loader_2').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					tagmb.html(dataresponse).dialog({
					  show: "blind",
					  hide: "explode",
					  height: "auto",
					  width: "auto",					  
					  modal: true, 
					  title: "<?php echo translate('Lbl_Modify_Bin',$GLOBALS['lang']);?>",
					  autoResize:true,
							close: function(){
									tagmb.dialog('destroy').remove()
							}
					}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
					tagmb.dialog('open');
					$( "#binlengthi" ).focus();
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_2').hide();
				}
			});
			document.getElementById("btnModificarBin"+binId).disabled = false;
		}
    </script>	
	
	<script type="text/javascript">
		function guardarModificacionBin(formulariomb, binId)
		{
			document.getElementById("btnCargarMB").disabled = true;
			if($( "#binlengthi" ).val().length == 0)
			{
				$('#binlengthi').prop('title', '<?php echo translate('Msg_A_Bin_Length_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#binlengthi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#binlengthi" ).focus();
				document.getElementById("btnCargarMB").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#binlengthi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#binlengthi" ).tooltip('destroy');
			}			
			
			if($( "#binlengthi" ).val().length != 0)
			{			
				if (isNaN($( "#binlengthi" ).val()) || $( "#binlengthi" ).val() % 1 != 0)
				{
					$('#binlengthi').prop('title', '<?php echo translate('Msg_A_Bin_Length_Must_Enter_A_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#binlengthi" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#binlengthi" ).focus();
					document.getElementById("btnCargarMB").disabled = false;
					return;
				}
				else
				{
					$(function() {
						$( "#binlengthi" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#binlengthi" ).tooltip('destroy');
				}
			}


			if($( "#rangefrombini" ).val().length == 0)
			{
				$('#rangefrombini').prop('title', '<?php echo translate('Msg_A_Range_From_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#rangefrombini" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#rangefrombini" ).focus();
				document.getElementById("btnCargarMB").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#rangefrombini" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#rangefrombini" ).tooltip('destroy');
			}

			if($( "#rangefrombini" ).val().length != 0)
			{			
				if (isNaN($( "#rangefrombini" ).val()) || $( "#rangefrombini" ).val() % 1 != 0)
				{
					$('#rangefrombini').prop('title', '<?php echo translate('Msg_A_Range_From_Must_Enter_A_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#rangefrombini" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#rangefrombini" ).focus();
					document.getElementById("btnCargarMB").disabled = false;
					return;
				}
				else
				{
					$(function() {
						$( "#rangefrombini" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#rangefrombini" ).tooltip('destroy');
				}
			}			
			
			if($( "#rangetobini" ).val().length == 0)
			{
				$('#rangetobini').prop('title', '<?php echo translate('Msg_A_Range_To_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#rangetobini" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#rangetobini" ).focus();
				document.getElementById("btnCargarMB").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#rangetobini" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#rangetobini" ).tooltip('destroy');
			}

			if($( "#rangetobini" ).val().length != 0)
			{			
				if (isNaN($( "#rangetobini" ).val()) || $( "#rangetobini" ).val() % 1 != 0)
				{
					$('#rangetobini').prop('title', '<?php echo translate('Msg_A_Range_To_Must_Enter_A_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#rangetobini" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#rangetobini" ).focus();
					document.getElementById("btnCargarMB").disabled = false;
					return;
				}
				else
				{
					$(function() {
						$( "#rangetobini" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#rangetobini" ).tooltip('destroy');
				}
			}

			if($( "#rangetobini" ).val().length <  $( "#binlengthi" ).val() || $( "#rangefrombini" ).val().length < $( "#binlengthi" ).val())
			{
				$( "#binlengthi" ).focus();
				document.getElementById("btnCargarMB").disabled = false;
				mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_Range_From_Or_To_Must_Least_Length_Bin_Entered',$GLOBALS['lang']);?>");
				return;
			}			
			
			if($( "#rangetobini" ).val() < $( "#rangefrombini" ).val())
			{
				$( "#rangetobini" ).focus();
				document.getElementById("btnCargarMB").disabled = false;
				mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_Range_From_Bin_Cant_be_older_To',$GLOBALS['lang']);?>");
				return;
			}
			
			if($( "#rangetobini" ).val().length != $( "#rangefrombini" ).val().length)
			{
				$( "#rangetobini" ).focus();
				document.getElementById("btnCargarMB").disabled = false;
				mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_Range_From_Must_Have_Same_Number_Of_Numbers',$GLOBALS['lang']);?>");
				return;
			}
			
			var urlggnu = "./acciones/guardarmodificacionbin.php";
			$('#img_loader_3').show();
			
			$.ajax({
				url: urlggnu,
				method: "POST",
				data: { idBin: binId, idTarjeta: $( "#tarjetabini" ).val(), largoBin: $( "#binlengthi" ).val(), rangoDesde: $( "#rangefrombini" ).val(), rangoHasta: $( "#rangetobini" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_3').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Modify_Bin_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialogmodifybin').dialog('close');
						$('#tableadminbint').bootstrapTable('load',JSON.parse(datTable));
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_3').hide();
				}
			});
			document.getElementById("btnCargarMB").disabled = false;
		}			
	</script>
	
	<script type="text/javascript">
		function borrar_bin(idBin)
		{
			var urlbb = "./acciones/borrarbin.php";
			$('#img_loader_2').show();
			
			$.ajax({
				url: urlbb,
				method: "POST",
				data: { idBin: idBin },
				success: function(dataresponse, statustext, response){
					$('#img_loader_2').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Remove_Bin_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#tableadminbint').bootstrapTable('load',JSON.parse(datTable));
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_2').hide();
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
		function confirmar_accion(titulo, mensaje, binId, rangoD, marcaT)
		{
			document.getElementById("borrarBin"+binId).disabled = true;
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
										
										borrar_bin(binId);                                                      
								},
								"<?php echo translate('Lbl_Button_NO',$GLOBALS['lang']);?>": function () {
										$("#confirmDialog").dialog('close');
										$('#img_loader_2').hide();
										document.getElementById("borrarBin"+binId).disabled = false;
										return;
								}
						}
				}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
				mensaje = mensaje.replace("%1", rangoD);
				mensaje = mensaje.replace("%2", marcaT);
				$( "#confirmDialog" ).html("<div id='confirmacionAccion'>"+mensaje+"?</div>");
				$('#img_loader_2').hide();
			document.getElementById("borrarBin"+binId).disabled = false;
		}
	</script>

	<script type="text/javascript">
		function importarBines()
		{
			document.getElementById("btnImportarBines").disabled = true;
			var urlib = "./acciones/importarbines.php";
			var tagib = $("<div id='dialogimportbins'></div>");
			$('#img_loader_2').show();
			
			$.ajax({
				url: urlib,
				method: "POST",
				data: {},
				success: function(dataresponse, statustext, response){
					$('#img_loader_2').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					tagib.html(dataresponse).dialog({
					  show: "blind",
					  hide: "explode",
					  height: "auto",
					  width: "auto",					  
					  modal: true, 
					  title: "<?php echo translate('Lbl_Import_Bins',$GLOBALS['lang']);?>",
					  autoResize:true,
							close: function(){
									tagib.dialog('destroy').remove()
							}
					}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
					tagib.dialog('open');
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_2').hide();
				}
			});
			document.getElementById("btnImportarBines").disabled = false;
		}
    </script>

	<script type="text/javascript">
	function subirArchivoBines(formulariod, filec)
	{			
		document.getElementById("btnSubirBines").disabled = true;
		if($( "#fileCM" ).val().length == 0)
		{
			$('#fileCM').prop('title', '<?php echo translate('Msg_A_File_Import_Bin_Must_Enter',$GLOBALS['lang']);?>');
			$(function() {
				$( "#fileCM" ).tooltip({
				   position: {
					  my: "center bottom",
					  at: "center top-10",
					  collision: "none"
				   }
				});
			});
			$( "#fileCM" ).focus();
			document.getElementById("btnSubirBines").disabled = false;
			return;
		}
		else 
		{
			$(function() {
				$( "#fileCM" ).tooltip({
				   position: {
					  my: "center bottom",
					  at: "center top-10",
					  collision: "none"
				   }
				});
			});				
			$( "#fileCM" ).tooltip('destroy');
		}		
		
		$('#img_loader_4').show();
		extArray = new Array(".txt");
		allowSubmit = false;
		if (!filec)
		{
			document.getElementById("btnSubirBines").disabled = false;
			$('#img_loader_4').hide();
			return;
		}
		while (filec.indexOf("\\") != -1)
		filec = filec.slice(filec.indexOf("\\") + 1);
		ext = filec.slice(filec.indexOf(".")).toLowerCase();
		for (var i = 0; i < extArray.length; i++) 
		{
			if (extArray[i] == ext) { allowSubmit = true; break; }
		}
		if (!allowSubmit) 
		{
			mensaje_atencion("<?php echo translate('Lbl_Attention',$GLOBALS['lang']);?>","<?php echo translate('Msg_Only_Files_With_Extension_Are_Allowed',$GLOBALS['lang']);?>: " +(extArray.join("  ")));		
			$("#fileCM").focus();
			document.getElementById("btnSubirBines").disabled = false;
			$('#img_loader_4').hide();
			return;
		}
		
					
		var fileu = formulariod.fileCM.files[0];
		if (fileu.size > 2000000) 
		{
			mensaje_atencion("<?php echo translate('Lbl_Attention',$GLOBALS['lang']);?>","<?php echo translate('Msg_The_Selected_File_Cannot_Be_More_Than_2MB_In_Size',$GLOBALS['lang']);?>.");					
			$("#fileCM").focus();
			document.getElementById("btnSubirBines").disabled = false;
			$('#img_loader_4').hide();
			return;
		}
		
		var fd = new FormData();
		fd.append("uploadedfile", fileu);
		var xhr = new XMLHttpRequest();
		xhr.open('POST', 'acciones/uploaderimportbines.php', true);
		  
		xhr.upload.onprogress = function(e) {
			if (e.lengthComputable) {
			  var percentComplete = (e.loaded / e.total) * 100;
			  console.log(percentComplete + '% uploaded');
			}
		};
		  
		xhr.onload = function() {
			if (this.status == 200) 
			{
				if(xhr.responseText.indexOf("<?php echo translate('Msg_File_Has_Been_Uploaded_Successfully_Press_Import_Button',$GLOBALS['lang']); ?>") >= 0)
				{
					mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>","<?php echo translate('Msg_File_Has_Been_Uploaded_Successfully_Press_Import_Button',$GLOBALS['lang']); ?>");						
					$("#btnCIB").prop('disabled', false);
				}
				else
				{
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",xhr.responseText);
					$("#btnCIB").prop('disabled', true);
				}
				document.getElementById("btnSubirBines").disabled = false;
				$('#img_loader_4').hide();
				return;
			};
		};
		xhr.send(fd);
		document.getElementById("btnSubirBines").disabled = false;
		$('#img_loader_4').hide();
	}
	</script>

	<script type="text/javascript">
		function ejecutarImportacionBines()
		{
			var urlbb = "./acciones/ejecutarimportacionbines.php";
			$('#img_loader_4').show();
			
			$.ajax({
				url: urlbb,
				method: "POST",
				data: {},
				success: function(dataresponse, statustext, response){
					$('#img_loader_4').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Import_Bins_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialogimportbins').dialog('close');
						$('#tableadminbint').bootstrapTable('load',JSON.parse(datTable));
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_4').hide();
				}
			});	
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
			<div id="img_loader_2"></div>
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
							if ($stmt = $mysqli->prepare("SELECT b.bin_id, pm.payment_method_id, pm.payment_method_description, b.range_from, b.range_to, b.bin_length FROM tef.bines b, tef.paymentmethods pm  WHERE b.payment_method_id = pm.payment_method_id ORDER BY CAST(b.range_from AS INT) LIMIT 5000")) 
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
									
									echo '<td><button type="button" id="borrarBin'.$id_bin.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Bin',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Remove_Bin',$GLOBALS['lang']).'\',\''.$id_bin.'\',\''.$range_from_bin.'\',\''.$name_payment_method.'\')"><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" id="btnModificarBin'.$id_bin.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Modify_Bin',$GLOBALS['lang']).'" onclick="modificarBin(\''.$id_bin.'\')"><i class="far fa-edit"></i></button></td>';
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