<?php
include ('./utiles/funciones.php');
require("../parametrosbasedatosfc.php");
$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
mysqli_set_charset($mysqli,"utf8");
if (!verificar_usuario($mysqli)){header('Location:./login.php');return;}
if (!verificar_permisos_usuario()){header('Location:./sinautorizacion.php?activauto=1');return;}
include("./menu/menu.php");
?>
<!doctype html>
<html lang="es-es">
<head>
	<meta charset="UTF-8">
	
	<link rel="shortcut icon" href="./images/iconoFinanCli.png" >
	<title><?php echo translate('Lbl_Admin_Credits',$GLOBALS['lang']); ?></title>
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
	<link rel="stylesheet" href="./utiles/CodeMirror/lib/codemirror.css">
	<link rel="stylesheet" href="./utiles/CodeMirror/addon/hint/show-hint.css">	
	
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
		function buscarTextoEstadoFinanciero()
		{	     
			 if(editorEF != null)
			 {
				 var cursor = editorEF.getSearchCursor($('#buscartextoestadocrediticioclientei').val() , CodeMirror.Pos(editorEF.firstLine(), 0), {caseFold: true, multiline: true});
				 if(cursor.find(false))
				 { 
					  var from = cursor.from();
					  var to = cursor.to();
					  editorEF.setSelection(CodeMirror.Pos(from.line, 0), to);
					  editorEF.scrollIntoView({from: from, to: CodeMirror.Pos(to.line + 10, 0)});
				 }
			 }
		}
	</script>
	
	<script type="text/javascript">
		function nuevoCredito()
		{
			// alert($('.search').find(':input').val()); --> BUSQUEDA DE CREDITOS POR DOCUMENTO

			var urlnc = "./acciones/nuevocredito.php";
			var tagnc = $("<div id='dialognewcredit'></div>");
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
					  title: "<?php echo translate('Lbl_New_Credit',$GLOBALS['lang']);?>",
					  autoResize:true,
							close: function(){
									tagnpc.dialog('destroy').remove()
							}
					}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
					
					$('#tablefeescreditclientt').bootstrapTable({locale:'es-AR'});
					
					$( "#tipodocumentocreditclientni" ).change(function() 
					{
						if($( "#documentoclientcreditni" ).val().length != 0)
						{
							buscarClienteCredito();
						}
					});

					$('#documentoclientcreditni').keypress(function(event){
						var keycode = (event.keyCode ? event.keyCode : event.which);
						if(keycode == '13'){
							buscarClienteCredito(); 
						}
					});					
					
					tagnc.dialog('open');
					$('#montomaximoclientcreditni').maskNumber();
					$('#montocompraclientcreditni').maskNumber();
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_5').hide();
				}
			});	
		}
    </script>
	
	<script type="text/javascript">
		function buscarClienteCredito()
		{
			if($('#validarstatuscreditclientecreni').is(":checked"))
			{
				var urlbcc = "./acciones/buscarclientecredito.php";
				$('#img_loader_16').show();
				
				$.ajax({
					url: urlbcc,
					method: "POST",
					data: { motivo: 59, tipoDocumento: $("#tipodocumentocreditclientni").val(), documento: $("#documentoclientcreditni").val(), token: $("#tokenveccrediti").val() },
					success: function(dataresponse, statustext, response){
						$('#img_loader_16').hide();
						
						if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
						{
							window.location.replace("./login.php?result_ok=3");
						}
						
						if(dataresponse.indexOf('<?php echo translate('Msg_Validation_Credit_Status_Client_Is_Not_Necessary',$GLOBALS['lang']);?>') != -1)
						{
							dataresponse = dataresponse.replace("<?php echo translate('Msg_Validation_Credit_Status_Client_Is_Not_Necessary',$GLOBALS['lang']); ?>"+"=::=::","");
							var tokenVECCC3 = dataresponse.substring(0, dataresponse.indexOf('=:=:'));
							dataresponse = dataresponse.replace(tokenVECCC3+"=:=:","");
							
							var compCampos = dataresponse.split("|");
							if(compCampos.length != 5)
							{
								mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_Unknown_Error',$GLOBALS['lang']); ?>");
								$('#documentoclientcreditni').css({'box-shadow' : '0 0 3px #FF0000'});
								$('#documentoclientcreditni').prop('title', dataresponse);
								$('#documentoclientcreditni').focus();
								
								$( "#montocompraclientcreditni" ).prop( "disabled", true );
								
								$( "#montocompraclientcreditni" ).val("");
								$( "#nombreclientcreditni" ).val("");
								$( "#apellidoclientcreditni" ).val("");
								$( "#tipoclientcreditni" ).val("1");
								$( "#telefonoclientcreditni" ).val("");
								$( "#montomaximoclientcreditni" ).val("");
								$( "#montocompraclientcreditni" ).val("");								
							}
							else
							{
								$( "#montocompraclientcreditni" ).prop( "disabled", false );
								$('#documentoclientcreditni').css({'box-shadow' : '0 0 3px #00FF00'});
								$('#documentoclientcreditni').prop('title', '<?php echo translate('Msg_Validation_Credit_Status_Client_Is_Not_Necessary',$GLOBALS['lang']); ?>');
							
								$( "#nombreclientcreditni" ).val(compCampos[0]);
								$( "#apellidoclientcreditni" ).val(compCampos[1]);
								
								var tipoCliC = document.getElementById('tipoclientcreditni');
								for (var i = 0; i < tipoCliC.options.length; i++) {
									if (tipoCliC.options[i].text === compCampos[2]) {
										tipoCliC.selectedIndex = i;
										break;
									}
								}
								
								$( "#telefonoclientcreditni" ).val(compCampos[3]);
								$( "#montomaximoclientcreditni" ).val(compCampos[4]/100.00);
								$( "#tokenveccrediti" ).val(tokenVECCC3);
								$( "#montocompraclientcreditni" ).focus();
							}							
						}
						else if(dataresponse.indexOf('<?php echo translate('Msg_Validation_Credit_Status_Client_OK',$GLOBALS['lang']);?>') != -1)
						{
							var tokenR = dataresponse.substring(dataresponse.indexOf('=::=::=::')+9, dataresponse.indexOf('=:=:=:'));
							dataresponse = dataresponse.replace("<?php echo translate('Msg_Validation_Credit_Status_Client_OK',$GLOBALS['lang']); ?>=::=::=::","");
							dataresponse = dataresponse.replace(tokenR+"=:=:=:","");
							
							$('#tokenveccrediti').val(tokenR);
							var tagvcc = $("<div id='dialogsearchclientcredit'></div>");
							
							tagvcc.html(dataresponse).dialog({
							  show: "blind",
							  hide: "explode",
							  height: "auto",
							  width: "auto",					  
							  modal: true, 
							  title: "<?php echo translate('Lbl_Validation_Credit_Status_Client',$GLOBALS['lang']);?>",
							  autoResize:true,
									close: function(){
											tagvcc.dialog('destroy').remove()
									}
							}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
							
						    editorEF = CodeMirror.fromTextArea(document.getElementById("resultadoestadofinancieroclientei"), {
								mode: "xml",
								lineNumbers: true,
								readOnly: true
							});
							editorEF.setSize(650, 300);
							
							$('#buscartextoestadocrediticioclientei').keypress(function(event){
								var keycode = (event.keyCode ? event.keyCode : event.which);
								if(keycode == '13'){
									buscarTextoEstadoFinanciero(); 
								}
							});							
							
							$( "#montocompraclientcreditni" ).prop( "disabled", true );

							$( "#montocompraclientcreditni" ).val("");
							$( "#nombreclientcreditni" ).val("");
							$( "#apellidoclientcreditni" ).val("");
							$( "#tipoclientcreditni" ).val("1");
							$( "#telefonoclientcreditni" ).val("");
							$( "#montomaximoclientcreditni" ).val("");
							$( "#montocompraclientcreditni" ).val("");
							
							tagvcc.dialog('open');							
						}
						else if(dataresponse.indexOf('<?php echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']); ?>') != -1) 
						{
							alert(dataresponse);
							confirmar_accion_validar_cliente("<?php echo translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']);?>", "<?php echo translate('Msg_Be_Sure_To_Register_The_Credit_Without_Validating_Credit_Status',$GLOBALS['lang']);?>", 58);
						}						
						else 
						{
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
							$('#documentoclientcreditni').css({'box-shadow' : '0 0 3px #FF0000'});
							$('#documentoclientcreditni').prop('title', dataresponse);
							$('#documentoclientcreditni').focus();
							
							$( "#montocompraclientcreditni" ).prop( "disabled", true );

							$( "#montocompraclientcreditni" ).val("");
							$( "#nombreclientcreditni" ).val("");
							$( "#apellidoclientcreditni" ).val("");
							$( "#tipoclientcreditni" ).val("1");
							$( "#telefonoclientcreditni" ).val("");
							$( "#montomaximoclientcreditni" ).val("");
							$( "#montocompraclientcreditni" ).val("");								
						}
					},
					error: function(request, errorcode, errortext){
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
						$('#img_loader_16').hide();
					}
				});
			}
			else
			{
				confirmar_accion_validar_cliente("<?php echo translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']);?>", "<?php echo translate('Msg_Be_Sure_To_Register_The_Credit_Without_Validating_Credit_Status',$GLOBALS['lang']);?>", 58);
			}
		}
    </script>

	<script type="text/javascript">
		function guardarAutorizacionSupervisorEstadoFinancieroCliente(formulariocefc, motivo)
		{
			if($('#usuariosupervisorn3i').val().length == 0)
			{
				$(function() {
					$('#usuariosupervisorn3i').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$('#usuariosupervisorn3i').focus();
				return;
			}
			else 
			{
				$(function() {
					$('#usuariosupervisorn3i').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$('#usuariosupervisorn3i').tooltip('destroy');
			}

			if($('#passwordsupervisorn3i').val().length == 0)
			{
				$(function() {
					$('#passwordsupervisorn3i').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$('#passwordsupervisorn3i').focus();
				return;
			}
			else 
			{
				$(function() {
					$('#passwordsupervisorn3i').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$('#passwordsupervisorn3i').tooltip('destroy');
			}			
			
			var urlasrc2 = "./acciones/verificarcredencialessupervisorregistrocreditocliente2.php";
			$('#img_loader_13').show();
			
			
			var p221 = document.createElement("input");
		 			
			formulariocefc.appendChild(p221);
			p221.name = "p221";
			p221.type = "hidden";
			
			p221.value = hex_sha512(formulariocefc.passwordsupervisorn3i.value);
			
			if(formulariocefc.passwordsupervisorn3i.value == "") p221.value = "";
			formulariocefc.passwordsupervisorn3i.value = "";
					
			$.ajax({
				url: urlasrc2,
				method: "POST",
				data: { motivo: motivo, usuarioSupervisor: formulariocefc.usuariosupervisorn3i.value, claveSupervisor: p221.value, tipoDocumento: $("#tipodocumentocreditclientni").val(), documento: $("#documentoclientcreditni").val(), token: $("#tokenveccrediti").val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_13').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']);?>') != -1)
					{
						dataresponse = dataresponse.replace("<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']); ?>"+"=::=::","");
						var tokenVECCC4 = dataresponse.substring(0, dataresponse.indexOf('=:=:'));
						dataresponse = dataresponse.replace(tokenVECCC4+"=:=:","");
						
						var compCampos = dataresponse.split("|");
						if(compCampos.length != 5)
						{
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_Unknown_Error',$GLOBALS['lang']); ?>");
							$('#documentoclientcreditni').css({'box-shadow' : '0 0 3px #FF0000'});
							$('#documentoclientcreditni').prop('title', dataresponse);
							$('#documentoclientcreditni').focus();
							
							$( "#montocompraclientcreditni" ).prop( "disabled", true );

							$( "#montocompraclientcreditni" ).val("");
							$( "#nombreclientcreditni" ).val("");
							$( "#apellidoclientcreditni" ).val("");
							$( "#tipoclientcreditni" ).val("1");
							$( "#telefonoclientcreditni" ).val("");
							$( "#montomaximoclientcreditni" ).val("");
							$( "#montocompraclientcreditni" ).val("");								
						}
						else
						{
							$('#dialogautorizacionregistrocreditocliente').dialog('destroy').remove();
							$( "#montocompraclientcreditni" ).prop( "disabled", false );
							$('#documentoclientcreditni').css({'box-shadow' : '0 0 3px #00FF00'});
							$('#documentoclientcreditni').prop('title', '<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']); ?>');
						
							$( "#nombreclientcreditni" ).val(compCampos[0]);
							$( "#apellidoclientcreditni" ).val(compCampos[1]);
							
							var tipoCliC = document.getElementById('tipoclientcreditni');
							for (var i = 0; i < tipoCliC.options.length; i++) {
								if (tipoCliC.options[i].text === compCampos[2]) {
									tipoCliC.selectedIndex = i;
									break;
								}
							}
							
							$( "#telefonoclientcreditni" ).val(compCampos[3]);
							$( "#montomaximoclientcreditni" ).val(compCampos[4]/100.00);
							$( "#tokenvalidsupcrei" ).val(tokenVECCC4);
							$( "#montocompraclientcreditni" ).focus();
						}						
					}
					else
					{
						if(dataresponse.indexOf('<?php echo translate('Msg_Supervisor_Not_OK',$GLOBALS['lang']);?>') != -1)
						{
							$('#usuariosupervisorn3i').focus();
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
						}
						else 
						{
							$('#usuariosupervisorn3i').focus();
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
						}					
					}
					
				},
				error: function(request, errorcode, errortext){
					$('#usuariosupervisorn3i').focus();
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_13').hide();
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
						$('#tableadmincreditst').bootstrapTable('load',JSON.parse(datTable));
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
						$('#tableadmincreditst').bootstrapTable('load',JSON.parse(datTable));
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
	<script type="text/javascript">
		function confirmar_accion_validar_cliente(titulo, mensaje, motivo)
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
										
										validar_cliente_supervisor(motivo);                                                      
								},
								"<?php echo translate('Lbl_Button_NO',$GLOBALS['lang']);?>": function () {
										$("#confirmDialog").dialog('close');
										return;
								}
						}
				}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
				$( "#confirmDialog" ).html("<div id='confirmacionAccion'>"+mensaje+"?</div>");
				$('#img_loader').hide();
		}
	</script>
	
	<script type="text/javascript">
		function validar_cliente_supervisor(motivo)
		{			
			var urlvcsrc = "./acciones/validacionclientesupervisorregistrocredito.php";
			$('#img_loader_16').show();
									
			$.ajax({
				url: urlvcsrc,
				method: "POST",
				data: { motivo: motivo, tipoDocumento: $("#tipodocumentocreditclientni").val(), documento: $("#documentoclientcreditni").val(), token: $("#tokenvalidsupcrei").val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_16').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Must_Authorize_Client_Registration_Credit',$GLOBALS['lang']); ?>') != -1)
					{
						dataresponse = dataresponse.replace("<?php echo translate('Msg_Must_Authorize_Client_Registration_Credit',$GLOBALS['lang']); ?>","");
						var tagarcc = $("<div id='dialogautorizacionregistrocreditocliente'></div>");
						
						tagarcc.html(dataresponse).dialog({
						  show: "blind",
						  hide: "explode",
						  height: "auto",
						  width: "auto",					  
						  modal: true, 
						  title: "<?php echo translate('Lbl_Authorize_Client_Registration_Credit',$GLOBALS['lang']);?>",
						  autoResize:true,
								close: function(){
										tagarcc.dialog('destroy').remove()
								}
						}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
						tagarcc.dialog('open');
					}
					else if(dataresponse.indexOf('<?php echo translate('Msg_Authorize_Client_Registration_Credit_OK',$GLOBALS['lang']); ?>') != -1)
					{
						dataresponse = dataresponse.replace("<?php echo translate('Msg_Authorize_Client_Registration_Credit_OK',$GLOBALS['lang']); ?>"+"=::=::","");
						var tokenVECCC = dataresponse.substring(0, dataresponse.indexOf('=:=:'));
						dataresponse = dataresponse.replace(tokenVECCC+"=:=:","");
						
						var compCampos = dataresponse.split("|");
						if(compCampos.length != 5)
						{
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_Unknown_Error',$GLOBALS['lang']); ?>");
							$('#documentoclientcreditni').css({'box-shadow' : '0 0 3px #FF0000'});
							$('#documentoclientcreditni').prop('title', dataresponse);
							$('#documentoclientcreditni').focus();
							
							$( "#montocompraclientcreditni" ).prop( "disabled", true );
							
							$( "#montocompraclientcreditni" ).val("");
							$( "#nombreclientcreditni" ).val("");
							$( "#apellidoclientcreditni" ).val("");
							$( "#tipoclientcreditni" ).val("1");
							$( "#telefonoclientcreditni" ).val("");
							$( "#montomaximoclientcreditni" ).val("");
							$( "#montocompraclientcreditni" ).val("");								
						}
						else
						{
							$( "#montocompraclientcreditni" ).prop( "disabled", false );
							$('#documentoclientcreditni').css({'box-shadow' : '0 0 3px #00FF00'});
							$('#documentoclientcreditni').prop('title', '<?php echo translate('Msg_Authorize_Client_Registration_Credit_OK',$GLOBALS['lang']); ?>');
						
							$( "#nombreclientcreditni" ).val(compCampos[0]);
							$( "#apellidoclientcreditni" ).val(compCampos[1]);
							
							var tipoCliC = document.getElementById('tipoclientcreditni');
							for (var i = 0; i < tipoCliC.options.length; i++) {
								if (tipoCliC.options[i].text === compCampos[2]) {
									tipoCliC.selectedIndex = i;
									break;
								}
							}
							
							$( "#telefonoclientcreditni" ).val(compCampos[3]);
							$( "#montomaximoclientcreditni" ).val(compCampos[4]/100.00);
							$( "#tokenvalidsupcrei" ).val(tokenVECCC);
							$( "#montocompraclientcreditni" ).focus();
						}
					}
					else 
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
						$('#documentoclientcreditni').css({'box-shadow' : '0 0 3px #FF0000'});
						$('#documentoclientcreditni').prop('title', dataresponse);
						$('#documentoclientcreditni').focus();
						
						$( "#montocompraclientcreditni" ).prop( "disabled", true );

						$( "#montocompraclientcreditni" ).val("");
						$( "#nombreclientcreditni" ).val("");
						$( "#apellidoclientcreditni" ).val("");
						$( "#tipoclientcreditni" ).val("1");
						$( "#telefonoclientcreditni" ).val("");
						$( "#montomaximoclientcreditni" ).val("");
						$( "#montocompraclientcreditni" ).val("");						
					}
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_16').hide();
				}
			});			
		}	
	</script>

	<script type="text/javascript">
		function guardarAutorizacionSupervisorRegistroCreditoCliente(formularionacrc, motivo)
		{
			if($('#usuariosupervisorn2i').val().length == 0)
			{
				$(function() {
					$('#usuariosupervisorn2i').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$('#usuariosupervisorn2i').focus();
				return;
			}
			else 
			{
				$(function() {
					$('#usuariosupervisorn2i').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$('#usuariosupervisorn2i').tooltip('destroy');
			}

			if($('#passwordsupervisorn2i').val().length == 0)
			{
				$(function() {
					$('#passwordsupervisorn2i').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$('#passwordsupervisorn2i').focus();
				return;
			}
			else 
			{
				$(function() {
					$('#passwordsupervisorn2i').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$('#passwordsupervisorn2i').tooltip('destroy');
			}			
			
			var urlasrc = "./acciones/verificarcredencialessupervisorregistrocreditocliente.php";
			$('#img_loader_13').show();
			
			
			var p211 = document.createElement("input");
		 			
			formularionacrc.appendChild(p211);
			p211.name = "p211";
			p211.type = "hidden";
			
			p211.value = hex_sha512(formularionacrc.passwordsupervisorn2i.value);
			
			if(formularionacrc.passwordsupervisorn2i.value == "") p211.value = "";
			formularionacrc.passwordsupervisorn2i.value = "";
									
			$.ajax({
				url: urlasrc,
				method: "POST",
				data: { motivo: motivo, usuarioSupervisor: formularionacrc.usuariosupervisorn2i.value, claveSupervisor: p211.value, tipoDocumento: $( "#tipodocumentocreditclientni" ).val(), documento: $( "#documentoclientcreditni" ).val(), token: $("#tokenvalidsupcrei").val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_13').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']);?>') != -1)
					{
						dataresponse = dataresponse.replace("<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']); ?>"+"=::=::","");
						var tokenVECCC2 = dataresponse.substring(0, dataresponse.indexOf('=:=:'));
						dataresponse = dataresponse.replace(tokenVECCC2+"=:=:","");
						
						var compCampos = dataresponse.split("|");
						if(compCampos.length != 5)
						{
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_Unknown_Error',$GLOBALS['lang']); ?>");
							$('#documentoclientcreditni').css({'box-shadow' : '0 0 3px #FF0000'});
							$('#documentoclientcreditni').prop('title', dataresponse);
							$('#documentoclientcreditni').focus();
							
							$( "#montocompraclientcreditni" ).prop( "disabled", true );

							$( "#montocompraclientcreditni" ).val("");
							$( "#nombreclientcreditni" ).val("");
							$( "#apellidoclientcreditni" ).val("");
							$( "#tipoclientcreditni" ).val("1");
							$( "#telefonoclientcreditni" ).val("");
							$( "#montomaximoclientcreditni" ).val("");
							$( "#montocompraclientcreditni" ).val("");								
						}
						else
						{
							$('#dialogautorizacionregistrocreditocliente').dialog('destroy').remove();
							$( "#montocompraclientcreditni" ).prop( "disabled", false );
							$('#documentoclientcreditni').css({'box-shadow' : '0 0 3px #00FF00'});
							$('#documentoclientcreditni').prop('title', '<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']); ?>');
						
							$( "#nombreclientcreditni" ).val(compCampos[0]);
							$( "#apellidoclientcreditni" ).val(compCampos[1]);
							
							var tipoCliC = document.getElementById('tipoclientcreditni');
							for (var i = 0; i < tipoCliC.options.length; i++) {
								if (tipoCliC.options[i].text === compCampos[2]) {
									tipoCliC.selectedIndex = i;
									break;
								}
							}
							
							$( "#telefonoclientcreditni" ).val(compCampos[3]);
							$( "#montomaximoclientcreditni" ).val(compCampos[4]/100.00);
							$( "#tokenvalidsupcrei" ).val(tokenVECCC2);
							$( "#montocompraclientcreditni" ).focus();
						}						
					}
					else
					{
						if(dataresponse.indexOf('<?php echo translate('Msg_Supervisor_Not_OK',$GLOBALS['lang']);?>') != -1)
						{
							$('#usuariosupervisorn2i').focus();
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
						}
						else 
						{
							$('#usuariosupervisorn2i').focus();
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
						}					
					}
					
				},
				error: function(request, errorcode, errortext){
					$('#usuariosupervisorn2i').focus();
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_13').hide();
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
				<strong>| <?php echo translate('Lbl_Date',$GLOBALS['lang']); ?>:</strong> <?php date_default_timezone_set("America/Argentina/Buenos_Aires"); $fecha = date("d/m/Y"); echo $fecha;  ?>
				 - <strong><?php echo translate('Lbl_User',$GLOBALS['lang']); ?>:</strong><?php $usuario = $_SESSION['username']; echo"$usuario"; ?> |
			</a>
		</div>
	  </div>
	</nav></br></br></br></br>
	<div class="panel-group" style="padding-bottom:50px;">				
		<div class="panel panel-default" style="margin-left:30px;margin-right:30px;">
		  <div id="panel-title-header" class="panel-heading">
			<h3 class="panel-title"><?php echo translate('Lbl_Credits_Clients',$GLOBALS['lang']); ?></h3>
		  </div>
		  <div id="apDiv1" class="panel-body">
			<div id="toolbar" style="margin-left:-98px; margin-top:-1px;">
				<button type="button" class="btn" data-toggle="tooltip" data-placement="top" onclick="nuevoCredito();" title="<?php echo translate('Lbl_New_Credit',$GLOBALS['lang']);?>" ><i class="far fa-plus-square"></i></button>
			</div>
			<div id="img_loader"></div>
			<div id="tablaadmincredits" class="table-responsive">
				<table id="tableadmincreditst" data-classes="table table-hover table-condensed"
				   data-striped="true" data-pagination="true" data-show-export="true" data-export-options='{"fileName": "<?php echo translate('File_Credits_Clients',$GLOBALS['lang']); ?>"}'
				   data-export-types="['excel','pdf','csv','txt']"
				   data-search="true" data-search-align="left" data-toolbar="#toolbar" data-toolbar-align="right">
					<thead>
						<tr>
							<th class="col-xs-1 text-center" data-field="fecha" data-sortable="true"><?php echo translate('Lbl_Date_Credit',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="tipodocumento" data-sortable="true"><?php echo translate('Lbl_Type_Document_Credit',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="documento" data-sortable="true"><?php echo translate('Lbl_Document_Credit',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="monto" data-sortable="true"><?php echo translate('Lbl_Amount_Credit',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="plancredito" data-sortable="true"><?php echo translate('Lbl_Name_Plan_Credit',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="cuotas"><?php echo translate('Lbl_Fees_Credit',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="estado" data-sortable="true"><?php echo translate('Lbl_State_Credit',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="acciones"><?php echo translate('Lbl_Actions_Credit',$GLOBALS['lang']);?></th>
						</tr>						
					</thead>
					<tbody>
						<?php
							if ($stmt = $mysqli->prepare("SELECT c.id, cc.fecha, cc.tipo_documento, cc.documento, c.monto_credito_original, pc.nombre, c.cantidad_cuotas, c.estado FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.cliente cli, finan_cli.plan_credito pc WHERE pc.id = c.id_plan_credito AND c.id = cc.id_credito AND cc.tipo_documento = cli.tipo_documento AND cc.documento = cli.documento ORDER BY cc.fecha DESC LIMIT 10")) 
							{
								$stmt->execute();    // Ejecuta la consulta preparada.
								$stmt->store_result();
						 
								// Obtiene las variables del resultado.
								$stmt->bind_result($id_credit_client, $date_credit_client, $type_documento_credit_client, $document_credit_client, $amount_credit_client, $name_credit_plan_client, $fees_credit_client, $state_credit_client);
								
								while($stmt->fetch())
								{		
									echo '<tr>';
									echo '<td>'.$date_credit_client.'</td>';
									echo '<td>'.$type_documento_credit_client.'</td>';
									echo '<td>'.$document_credit_client.'</td>';
									echo '<td>'.$amount_credit_client.'</td>';
									echo '<td>'.$name_credit_plan_client.'</td>';
									echo '<td>'.$fees_credit_client.'</td>';
									echo '<td>'.$state_credit_client.'</td>';
									
									echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Credit_Client',$GLOBALS['lang']).'" onclick="verCredito('.$id_credit_client.')"><i class="fas fa-eye"></i></button></td>';
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
			$('#tableadmincreditst').bootstrapTable({locale:'es-AR'});
		});
	</script>	
</body>
</html>