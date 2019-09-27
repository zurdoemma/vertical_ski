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
			document.getElementById("btnNuevoCredito").disabled = true;
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
									tagnc.dialog('destroy').remove()
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

					$('#montocompraclientcreditni').keypress(function(event){
						var keycode = (event.keyCode ? event.keyCode : event.which);
						if(keycode == '13'){
							cargarInfoCredito(); 
						}
					});

					$( "#validarpagoprimeracuotani" ).change(function() 
					{
						if(document.getElementById("minimoentregaclientcreditni") != undefined && document.getElementById("minimoentregaclientcreditni") != null) 
						{
							var montoMinimoEntNum = (($( "#minimoentregaclientcreditni" ).val().replace(/,/g,""))*100.00);
							if(montoMinimoEntNum > 0)
							{
								var montoCompraNum = (($( "#montocompraclientcreditni" ).val().replace(/,/g,""))*100.00);
								if(this.checked) $( "#montocompraclientcreditni" ).val((montoCompraNum + montoMinimoEntNum)/100);
								cargarInfoCredito();
							}
							else if(montoMinimoEntNum == 0) cargarInfoCredito();
						}
					});					
					
					tagnc.dialog('open');
					$('#montomaximoclientcreditni').maskNumber();
					$('#montocompraclientcreditni').maskNumber();
					$( "#documentoclientcreditni" ).focus();
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_5').hide();
				}
			});	
			document.getElementById("btnNuevoCredito").disabled = false;
		}
    </script>
	
	<script type="text/javascript">
		function cancelarCredito(idCredito)
		{
			document.getElementById("btnCancelarCreditoClient"+idCredito).disabled = true;
			var urlcc = "./acciones/cancelarcredito.php";
			var tagcc = $("<div id='dialogcancelcredit'></div>");
			$('#img_loader_5').show();
			
			$.ajax({
				url: urlcc,
				method: "POST",
				data: { idCredito: idCredito },
				success: function(dataresponse, statustext, response){
					$('#img_loader_5').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_View_Cancel_Credit_OK',$GLOBALS['lang']);?>') != -1)
					{					
						dataresponse = dataresponse.replace('<?php echo translate('Msg_View_Cancel_Credit_OK',$GLOBALS['lang']);?>',"");
						
						tagcc.html(dataresponse).dialog({
						  show: "blind",
						  hide: "explode",
						  height: "auto",
						  width: "auto",					  
						  modal: true, 
						  title: "<?php echo translate('Msg_Cancel_Credit_Client',$GLOBALS['lang']);?>",
						  autoResize:true,
								close: function(){
										tagcc.dialog('destroy').remove()
								}
						}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");					
						tagcc.dialog('open');
					}
					else
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
					}					
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_5').hide();
				}
			});
			document.getElementById("btnCancelarCreditoClient"+idCredito).disabled = false;			
		}
    </script>	
	
	<script type="text/javascript">
		function verCredito(idCredito)
		{
			document.getElementById("btnVerCreditoClient"+idCredito).disabled = true;
			var urlvc = "./acciones/vercredito.php";
			var tagvc = $("<div id='dialogviewcredit'></div>");
			$('#img_loader_5').show();
			
			$.ajax({
				url: urlvc,
				method: "POST",
				data: { idCredito: idCredito },
				success: function(dataresponse, statustext, response){
					$('#img_loader_5').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_View_Credit_OK',$GLOBALS['lang']);?>') != -1)
					{					
						dataresponse = dataresponse.replace('<?php echo translate('Msg_View_Credit_OK',$GLOBALS['lang']);?>',"");
						
						tagvc.html(dataresponse).dialog({
						  show: "blind",
						  hide: "explode",
						  height: "auto",
						  width: "auto",					  
						  modal: true, 
						  title: "<?php echo translate('Lbl_View_Credit',$GLOBALS['lang']);?>: "+idCredito,
						  autoResize:true,
								close: function(){
										tagvc.dialog('destroy').remove()
								}
						}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
						
						$('#tablefeescreditclienttv').bootstrapTable({locale:'es-AR'});	
						
						tagvc.dialog('open');
						$('#montocompraclientcreditvi').maskNumber();
						$('#minimoentregaclientcreditvi').maskNumber();
					}
					else
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
					}					
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_5').hide();
				}
			});
			document.getElementById("btnVerCreditoClient"+idCredito).disabled = false;			
		}
    </script>	
	
	<script type="text/javascript">
		function buscarCreditosCliente()
		{
			document.getElementById("btnBuscarCreditosCliente").disabled = true;
			if($('.search').find(':input').val().length == 0)
			{
				$('.search').find(':input').focus();
				mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_A_Customer_Must_Enter_To_Search_Credits',$GLOBALS['lang']);?>");
				document.getElementById("btnBuscarCreditosCliente").disabled = false;
				return;
			}

			var urlbccd = "./acciones/buscarcreditosclientedocumento.php";
			$('#img_loader_5').show();
			
			$.ajax({
				url: urlbccd,
				method: "POST",
				data: { documento: $('.search').find(':input').val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_5').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Search_Credit_Client_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						dataresponse = dataresponse.replace(menR+"=:=:=:","");
						var datTable = dataresponse.substring(0,dataresponse.indexOf('=::=::=::'));
						dataresponse = dataresponse.replace(datTable+"=::=::=::","");
						
						$('#tableadmincreditst').bootstrapTable('load',JSON.parse(datTable));
						$('#titulocreditoscliente').html('<?php echo translate('Lbl_Credits_Clients',$GLOBALS['lang']); ?>'+': '+dataresponse);						
						//mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else if(dataresponse.indexOf('<?php echo translate('Msg_Without_Credit_Client',$GLOBALS['lang']);?>') != -1)
					{
						$('#tableadmincreditst').bootstrapTable('removeAll');
						var menR = dataresponse.substring(0,dataresponse.indexOf('=::=::=::'));
						dataresponse = dataresponse.replace(menR+"=::=::=::","");
						
						$('#titulocreditoscliente').html('<?php echo translate('Lbl_Credits_Clients',$GLOBALS['lang']); ?>');
						$('.search').find(':input').focus();						
						mensaje_atencion("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR.replace("%1",dataresponse));
					}
					else
					{
						$('#tableadmincreditst').bootstrapTable('removeAll');
						$('#titulocreditoscliente').html('<?php echo translate('Lbl_Credits_Clients',$GLOBALS['lang']); ?>');
						$('.search').find(':input').focus();
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
					}										
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_5').hide();
				}
			});
			document.getElementById("btnBuscarCreditosCliente").disabled = false;
		}
    </script>	
	
	<script type="text/javascript">
		function buscarClienteCredito()
		{
			if($('#documentoclientcreditni').val().length != 0)
			{				
				if($('#validarstatuscreditclientecreni').is(":checked"))
				{
					var urlbcc = "./acciones/buscarclientecredito.php";
					$('#img_loader_16').show();
					
					$.ajax({
						url: urlbcc,
						method: "POST",
						data: { motivo: 59, tipoDocumento: $("#tipodocumentocreditclientni").val(), documento: $("#documentoclientcreditni").val(), token: $("#tokenveccrediti").val(), token2: $("#tokenvalidsupcrei").val() },
						success: function(dataresponse, statustext, response){
							$('#img_loader_16').hide();
							
							if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
							{
								window.location.replace("./login.php?result_ok=3");
							}
							
							if(dataresponse.indexOf('<?php echo translate('Msg_Validation_Credit_Status_Client_Is_Not_Necessary',$GLOBALS['lang']);?>') != -1)
							{
								dataresponse = dataresponse.replace("<?php echo translate('Msg_Validation_Credit_Status_Client_Is_Not_Necessary',$GLOBALS['lang']); ?>"+"=::=::","");
								var tokenVECCC30 = dataresponse.substring(0, dataresponse.indexOf('=:::=:::'));
								dataresponse = dataresponse.replace(tokenVECCC30+"=:::=:::","");
								var tokenVECCC3 = dataresponse.substring(0, dataresponse.indexOf('=::::=::::'));
								dataresponse = dataresponse.replace(tokenVECCC3+"=::::=::::","");
								var planesCreCli = dataresponse.substring(0, dataresponse.indexOf('=:=:'));
								dataresponse = dataresponse.replace(planesCreCli+"=:=:","");
								
								var compCampos = dataresponse.split("|");
								if(compCampos.length != 5)
								{
									mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_Unknown_Error',$GLOBALS['lang']); ?>");
									$('#documentoclientcreditni').css({'box-shadow' : '0 0 3px #FF0000'});
									$('#documentoclientcreditni').prop('title', dataresponse);
									$('#documentoclientcreditni').focus();
									
									$( "#montocompraclientcreditni" ).prop( "disabled", true );
									$( "#plancreditclientni" ).prop( "disabled", true );
									$( "#plancreditclientni" ).empty();
									$('#plancreditclientni').off('change');
									
									$( "#montocompraclientcreditni" ).val("");
									$( "#nombreclientcreditni" ).val("");
									$( "#apellidoclientcreditni" ).val("");
									$( "#tipoclientcreditni" ).val("1");
									$( "#telefonoclientcreditni" ).val("");
									$( "#montomaximoclientcreditni" ).val("");
									$( "#montocompraclientcreditni" ).val("");
									$( "#minimoentregaclientcreditn" ).hide();
									$( "#btnCargarNC" ).prop( "disabled", true );
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
									$( "#tokenveccrediti" ).val(tokenVECCC30);
									$('#tokenvalidsupcrei').val(tokenVECCC3);
									$( "#plancreditclientni" ).prop( "disabled", false );
									$( "#montocompraclientcreditni" ).focus();
																		
									var planesCreCliA = planesCreCli.split(";;");
									for (var i = 0; i < planesCreCliA.length; i++) {
									   var datosPlanCreCli = planesCreCliA[i].split("|");
									   $("#plancreditclientni").append('<option value="'+datosPlanCreCli[0]+'">'+datosPlanCreCli[1]+'</option>');
									}

									$( "#btnCargarNC" ).prop( "disabled", false );
									$('#plancreditclientni').on('change', function() {
									  $( "#montocompraclientcreditni" ).focus();
									  $( "#btnCargarNC" ).prop( "disabled", true );
									});									
								}							
							}
							else if(dataresponse.indexOf('<?php echo translate('Msg_Validation_Credit_Status_Client_OK',$GLOBALS['lang']);?>') != -1)
							{
								var tokenR = dataresponse.substring(dataresponse.indexOf('=::=::=::')+9, dataresponse.indexOf('=:::=:::'));
								var tokenR2 = dataresponse.substring(dataresponse.indexOf('=:::=:::')+8, dataresponse.indexOf('=:=:=:'));
								dataresponse = dataresponse.replace("<?php echo translate('Msg_Validation_Credit_Status_Client_OK',$GLOBALS['lang']); ?>=::=::=::","");
								dataresponse = dataresponse.replace(tokenR+"=:::=:::","");
								dataresponse = dataresponse.replace(tokenR2+"=:=:=:","");
															
								$('#tokenveccrediti').val(tokenR);
								$('#tokenvalidsupcrei').val(tokenR2);
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
								$( "#plancreditclientni" ).prop( "disabled", true );
								$( "#plancreditclientni" ).empty();
								$('#plancreditclientni').off('change');

								$( "#montocompraclientcreditni" ).val("");
								$( "#nombreclientcreditni" ).val("");
								$( "#apellidoclientcreditni" ).val("");
								$( "#tipoclientcreditni" ).val("1");
								$( "#telefonoclientcreditni" ).val("");
								$( "#montomaximoclientcreditni" ).val("");
								$( "#montocompraclientcreditni" ).val("");
								$( "#minimoentregaclientcreditn" ).hide();
								$( "#btnCargarNC" ).prop( "disabled", true );
								
								tagvcc.dialog('open');							
							}
							else if(dataresponse.indexOf('<?php echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']); ?>') != -1) 
							{
								confirmar_accion_validar_cliente("<?php echo translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']);?>", "<?php echo translate('Msg_Be_Sure_To_Register_The_Credit_Without_Validating_Credit_Status',$GLOBALS['lang']);?>", 58);
							}						
							else 
							{
								mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
								$('#documentoclientcreditni').css({'box-shadow' : '0 0 3px #FF0000'});
								$('#documentoclientcreditni').prop('title', dataresponse);
								$('#documentoclientcreditni').focus();
								
								$( "#montocompraclientcreditni" ).prop( "disabled", true );
								$( "#plancreditclientni" ).prop( "disabled", true );
								$( "#plancreditclientni" ).empty();
								$('#plancreditclientni').off('change');
								
								$( "#montocompraclientcreditni" ).val("");
								$( "#nombreclientcreditni" ).val("");
								$( "#apellidoclientcreditni" ).val("");
								$( "#tipoclientcreditni" ).val("1");
								$( "#telefonoclientcreditni" ).val("");
								$( "#montomaximoclientcreditni" ).val("");
								$( "#montocompraclientcreditni" ).val("");
								$( "#minimoentregaclientcreditn" ).hide();
								$( "#btnCargarNC" ).prop( "disabled", true );								
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
			else
			{
				$('#documentoclientcreditni').focus();
				mensaje_atencion("<?php echo translate('Lbl_Attention',$GLOBALS['lang']);?>", "<?php echo translate('Msg_A_Document_Client_Must_Enter_Credit',$GLOBALS['lang']);?>");
				return;
			}
		}
    </script>

	<script type="text/javascript">
		function guardarAutorizacionSupervisorEstadoFinancieroCliente(formulariocefc, motivo)
		{
			document.getElementById("btnValidarEFC").disabled = true;
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
				document.getElementById("btnValidarEFC").disabled = false;
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
				document.getElementById("btnValidarEFC").disabled = false;
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
			
			var urlasrc2 = "./acciones/verificarcredencialessupervisorregistrocreditocliente.php";
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
				data: { motivo: motivo, usuarioSupervisor: formulariocefc.usuariosupervisorn3i.value, claveSupervisor: p221.value, tipoDocumento: $("#tipodocumentocreditclientni").val(), documento: $("#documentoclientcreditni").val(), token: $("#tokenveccrediti").val(), token2: $("#tokenvalidsupcrei").val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_13').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']);?>') != -1)
					{
						dataresponse = dataresponse.replace("<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']); ?>"+"=::=::","");
						var tokenVECCC40 = dataresponse.substring(0, dataresponse.indexOf('=:::=:::'));
						dataresponse = dataresponse.replace(tokenVECCC40+"=:::=:::","");
						var tokenVECCC4 = dataresponse.substring(0, dataresponse.indexOf('=::::=::::'));
						dataresponse = dataresponse.replace(tokenVECCC4+"=::::=::::","");
						var planesCreCli = dataresponse.substring(0, dataresponse.indexOf('=:=:'));
						dataresponse = dataresponse.replace(planesCreCli+"=:=:","");						
						
						var compCampos = dataresponse.split("|");
						if(compCampos.length != 5)
						{
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_Unknown_Error',$GLOBALS['lang']); ?>");
							$('#documentoclientcreditni').css({'box-shadow' : '0 0 3px #FF0000'});
							$('#documentoclientcreditni').prop('title', dataresponse);
							$('#documentoclientcreditni').focus();
							
							$( "#montocompraclientcreditni" ).prop( "disabled", true );
							$( "#plancreditclientni" ).prop( "disabled", true );
							$( "#plancreditclientni" ).empty();	
							$('#plancreditclientni').off('change');

							$( "#montocompraclientcreditni" ).val("");
							$( "#nombreclientcreditni" ).val("");
							$( "#apellidoclientcreditni" ).val("");
							$( "#tipoclientcreditni" ).val("1");
							$( "#telefonoclientcreditni" ).val("");
							$( "#montomaximoclientcreditni" ).val("");
							$( "#montocompraclientcreditni" ).val("");
							$( "#minimoentregaclientcreditn" ).hide();	
							$( "#btnCargarNC" ).prop( "disabled", true );
						}
						else
						{
							$('#dialogsearchclientcredit').dialog('destroy').remove();
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
							$( "#tokenvalidsupcrei" ).val(tokenVECCC40);
							$( "#tokenveccrediti" ).val(tokenVECCC4);
							$( "#plancreditclientni" ).prop( "disabled", false );
							$( "#montocompraclientcreditni" ).focus();
							
							var planesCreCliA = planesCreCli.split(";;");
							for (var i = 0; i < planesCreCliA.length; i++) {
							   var datosPlanCreCli = planesCreCliA[i].split("|");
							   $("#plancreditclientni").append('<option value="'+datosPlanCreCli[0]+'">'+datosPlanCreCli[1]+'</option>');
							}
							
							$( "#btnCargarNC" ).prop( "disabled", false );
							$('#plancreditclientni').on('change', function() {
							  $( "#montocompraclientcreditni" ).focus();
							  $( "#btnCargarNC" ).prop( "disabled", true );
							});							
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
			document.getElementById("btnValidarEFC").disabled = false;
		}
    </script>	

	<script type="text/javascript">
		function guardarSinSupervisorEstadoFinancieroCliente(motivo)
		{							
			document.getElementById("btnValidarEFC").disabled = true;
			var urlasrc3 = "./acciones/grabarregistrocreditoclientesinsupervisorestadofinanciero.php";
			$('#img_loader_13').show();
			
			$.ajax({
				url: urlasrc3,
				method: "POST",
				data: { motivo: 60, tipoDocumento: $("#tipodocumentocreditclientni").val(), documento: $("#documentoclientcreditni").val(), token: $("#tokenveccrediti").val(), token2: $("#tokenvalidsupcrei").val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_13').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Not_Supervisor_OK',$GLOBALS['lang']);?>') != -1)
					{
						dataresponse = dataresponse.replace("<?php echo translate('Msg_Not_Supervisor_OK',$GLOBALS['lang']); ?>"+"=::=::","");
						var tokenVECCC50 = dataresponse.substring(0, dataresponse.indexOf('=:::=:::'));
						dataresponse = dataresponse.replace(tokenVECCC50+"=:::=:::","");
						var tokenVECCC5 = dataresponse.substring(0, dataresponse.indexOf('=::::=::::'));
						dataresponse = dataresponse.replace(tokenVECCC5+"=::::=::::","");
						var planesCreCli = dataresponse.substring(0, dataresponse.indexOf('=:=:'));
						dataresponse = dataresponse.replace(planesCreCli+"=:=:","");						
						
						var compCampos = dataresponse.split("|");
						if(compCampos.length != 5)
						{
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_Unknown_Error',$GLOBALS['lang']); ?>");
							$('#documentoclientcreditni').css({'box-shadow' : '0 0 3px #FF0000'});
							$('#documentoclientcreditni').prop('title', dataresponse);
							$('#documentoclientcreditni').focus();
							
							$( "#montocompraclientcreditni" ).prop( "disabled", true );
							$( "#plancreditclientni" ).prop( "disabled", true );
							$( "#plancreditclientni" ).empty();
							$('#plancreditclientni').off('change');							

							$( "#montocompraclientcreditni" ).val("");
							$( "#nombreclientcreditni" ).val("");
							$( "#apellidoclientcreditni" ).val("");
							$( "#tipoclientcreditni" ).val("1");
							$( "#telefonoclientcreditni" ).val("");
							$( "#montomaximoclientcreditni" ).val("");
							$( "#montocompraclientcreditni" ).val("");
							$( "#minimoentregaclientcreditn" ).hide();
							$( "#btnCargarNC" ).prop( "disabled", true );
						}
						else
						{
							$('#dialogsearchclientcredit').dialog('destroy').remove();
							$( "#montocompraclientcreditni" ).prop( "disabled", false );
							$('#documentoclientcreditni').css({'box-shadow' : '0 0 3px #00FF00'});
							$('#documentoclientcreditni').prop('title', '<?php echo translate('Msg_Not_Supervisor_OK',$GLOBALS['lang']); ?>');
						
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
							$( "#tokenveccrediti" ).val(tokenVECCC50);
							$( "#tokenvalidsupcrei" ).val(tokenVECCC5);
							$( "#plancreditclientni" ).prop( "disabled", false );							
							$( "#montocompraclientcreditni" ).focus();
							
							var planesCreCliA = planesCreCli.split(";;");
							for (var i = 0; i < planesCreCliA.length; i++) {
							   var datosPlanCreCli = planesCreCliA[i].split("|");
							   $("#plancreditclientni").append('<option value="'+datosPlanCreCli[0]+'">'+datosPlanCreCli[1]+'</option>');
							}

							$( "#btnCargarNC" ).prop( "disabled", false );
							$('#plancreditclientni').on('change', function() {
							  $( "#montocompraclientcreditni" ).focus();
							  $( "#btnCargarNC" ).prop( "disabled", true );
							});							
						}
					}
					else 
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
					}						
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_13').hide();
				}
			});
			document.getElementById("btnValidarEFC").disabled = false;
		}
    </script>
	
	<script type="text/javascript">
		function cargarInfoCredito()
		{
			if($( "#montocompraclientcreditni" ).val().length != 0)
			{
				var urlbcc = "./acciones/cargarinformacioncreditocliente.php";
				$('#img_loader_16').show();
				
				$.ajax({
					url: urlbcc,
					method: "POST",
					data: { token: $("#tokenvalidsupcrei").val(), token2: $("#tokenveccrediti").val(), token3: $("#tokenvalidexcesomi").val(), tipoDocumento: $("#tipodocumentocreditclientni").val(), documento: $("#documentoclientcreditni").val(), montoMaximoCompra: (($( "#montomaximoclientcreditni" ).val().replace(/,/g,""))*100.00), montoCompra: (($( "#montocompraclientcreditni" ).val().replace(/,/g,""))*100.00), planCredito: $( "#plancreditclientni" ).val(), validacionEC: $( "#validarstatuscreditclientecreni" ).val(), validacionPrimeraCuota: $('#validarpagoprimeracuotani').is(":checked") },
					success: function(dataresponse, statustext, response){
						$('#img_loader_16').hide();
						
						if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
						{
							window.location.replace("./login.php?result_ok=3");
						}
						
						if(dataresponse.indexOf('<?php echo translate('Msg_View_Info_Credit_Client_OK',$GLOBALS['lang']);?>') != -1)
						{
							$( "#tipodocumentocreditclientni" ).prop( "disabled", true );
							$( "#documentoclientcreditni" ).prop( "disabled", true );
							$( "#validarpagoprimeracuotani" ).prop( "disabled", false );
														
							dataresponse = dataresponse.replace("<?php echo translate('Msg_View_Info_Credit_Client_OK',$GLOBALS['lang']); ?>"+"=::=::","");
							var datTable = dataresponse.substring(0, dataresponse.indexOf('=:=:'));
							dataresponse = dataresponse.replace(datTable+"=:=:","");
							var minimoEnt = parseInt(dataresponse.substring(0, dataresponse.indexOf('=:=:=:')));
							dataresponse = dataresponse.replace(minimoEnt+"=:=:=:","");

							var resF = JSON.parse(datTable);
							for(var i in resF)
							{
								 resF[i]["montocuota"] = '$'+(resF[i]["montocuota"]/100.00);
								 resF[i]["fechavencimiento"] = resF[i]["fechavencimiento"].substring(8,10)+'/'+resF[i]["fechavencimiento"].substring(5,7)+'/'+resF[i]["fechavencimiento"].substring(0,4);
							}
							$( "#montocreditoclientcreditni" ).val(dataresponse/100.00);
							
							if(minimoEnt != 0)
							{
								$( "#minimoentregaclientcreditn" ).show();

								$( "#minimoentregaclientcreditni" ).val((($( "#montocompraclientcreditni" ).val().replace(/,/g,""))*(minimoEnt/100.00)).toFixed(2));
								$('#minimoentregaclientcreditni').maskNumber();
								var montoCompraNum = (($( "#montocompraclientcreditni" ).val().replace(/,/g,""))*100.00);
								var minimoEntregaNum = (($( "#minimoentregaclientcreditni" ).val().replace(/,/g,""))*100.00);

								if($('#validarpagoprimeracuotani').is(":checked"))
								{
									$('#minimoentregaclientcreditni').val("0");
									//$('#montocompraclientcreditni').val(((montoCompraNum + minimoEntregaNum)/100.00).toFixed(2));
								}
								else
								{
									$('#montocompraclientcreditni').val((((montoCompraNum - minimoEntregaNum)/100.00)).toFixed(2));
								}					
							}
							else 
							{
								$( "#minimoentregaclientcreditn" ).val("0");
								$( "#minimoentregaclientcreditn" ).hide();
							}
							$( "#btnCargarNC" ).prop( "disabled", false );
							$('#tablefeescreditclientt').bootstrapTable('load',resF);
						}
						else if(dataresponse.indexOf('<?php echo translate('Msg_Max_Amount_Credit_Client_Exceeded',$GLOBALS['lang']);?>') != -1)
						{
							$( "#minimoentregaclientcreditn" ).hide();
							confirmar_accion_validar_credito_cliente("<?php echo translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']);?>", "<?php echo translate('Msg_Be_Sure_To_Register_Credit_With_Max_Amount_Exceeded',$GLOBALS['lang']);?>", 64);
						}
						else 
						{
							$( "#minimoentregaclientcreditn" ).hide();
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
						}
					},
					error: function(request, errorcode, errortext){
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
						$('#img_loader_16').hide();
					}
				});
			}
		}
    </script>

	<script type="text/javascript">
		function guardarNuevoCredito()
		{
			document.getElementById("btnCargarNC").disabled = true;
			if((!document.getElementById('documentoclientcreditni').disabled && $( "#documentoclientcreditni" ).val().length == 0) || $( "#nombreclientcreditni" ).val().length == 0)
			{
				$( "#documentoclientcreditni" ).focus();
				mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_A_Customer_Must_Enter_To_Register_The_Credit',$GLOBALS['lang']);?>");
				document.getElementById("btnCargarNC").disabled = false;
				return;					
			}			

			if($( "#montocompraclientcreditni" ).val().length != 0)
			{
				var tableFeesC = $("#tablefeescreditclientt tbody");
				var montoTCC = 0.00;
				tableFeesC.find('tr').each(function (i, el) {
					var $tds = $(this).find('td'),
						nroCuotC = $tds.eq(0).text(),
						fechVenC = $tds.eq(1).text(),
						montoCC = $tds.eq(2).text();
						montoTCC = montoTCC + parseFloat(montoCC.replace("$",""));
				});
								
				if($( "#montocompraclientcreditni" ).val().length == 0)
				{
					$( "#montocompraclientcreditni" ).focus();
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_You_Must_Confirm_The_Amount_Of_The_Purchase',$GLOBALS['lang']);?>");
					document.getElementById("btnCargarNC").disabled = false;
					return;					
				}
				
				if(montoTCC != $( "#montocreditoclientcreditni" ).val())
				{
					$( "#montocompraclientcreditni" ).focus();
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_You_Must_Confirm_The_Amount_Of_The_Purchase',$GLOBALS['lang']);?>");
					document.getElementById("btnCargarNC").disabled = false;
					return;
				}
				
				var urlbcc = "./acciones/guardarnuevocredito.php";
				$('#img_loader_16').show();
				
				$.ajax({
					url: urlbcc,
					method: "POST",
					data: { token: $("#tokenvalidsupcrei").val(), token2: $("#tokenveccrediti").val(), token3: $("#tokenvalidexcesomi").val(), tipoDocumento: $("#tipodocumentocreditclientni").val(), documento: $("#documentoclientcreditni").val(), montoMaximoCompra: (($( "#montomaximoclientcreditni" ).val().replace(/,/g,""))*100.00), montoCompra: (($( "#montocompraclientcreditni" ).val().replace(/,/g,""))*100.00), planCredito: $( "#plancreditclientni" ).val(), validacionEC: $( "#validarstatuscreditclientecreni" ).val(), validacionPrimeraCuota: $('#validarpagoprimeracuotani').is(":checked"), minimoEntrega: (($( "#minimoentregaclientcreditni" ).val().replace(/,/g,""))*100.00) },
					success: function(dataresponse, statustext, response){
						$('#img_loader_16').hide();
						
						if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
						{
							window.location.replace("./login.php?result_ok=3");
						}
						
						if(dataresponse.indexOf('<?php echo translate('Msg_New_Credit_Client_OK',$GLOBALS['lang']);?>') != -1)
						{
							var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
							dataresponse = dataresponse.replace(menR+"=:=:=:","");
							var datosImpresion = dataresponse.substring(0,dataresponse.indexOf('=::=::=::'));
							dataresponse = dataresponse.replace(datosImpresion+"=::=::=::","");
							var datTable = dataresponse.substring(0);
							
							$('#dialognewcredit').dialog('destroy').remove();
							$('#tableadmincreditst').bootstrapTable('load',JSON.parse(datTable));

							var datosFinImpre = datosImpresion.split('|');

							imprimirNuevoCredito(datosFinImpre[0], datosFinImpre[1], datosFinImpre[5], datosFinImpre[6], datosFinImpre[2], datosFinImpre[7], "<?php echo $_SESSION['username']; ?>", (datosFinImpre[8]/100.00), datosFinImpre[3], datosFinImpre[4], datosFinImpre[9], datosFinImpre[10], datosFinImpre[11], datosFinImpre[12], datosFinImpre[13], datosFinImpre[14], datosFinImpre[15]);
							mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
						}
						else 
						{
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
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
				$( "#montocompraclientcreditni" ).focus();
				mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_You_Must_Confirm_The_Amount_Of_The_Purchase',$GLOBALS['lang']);?>");
				document.getElementById("btnCargarNC").disabled = false;
				return;				
			}
			document.getElementById("btnCargarNC").disabled = false;
		}
    </script>	
	
	<script type="text/javascript">
		function imprimirNuevoCredito(idCreditoImp, fechaCreditoImp, planCreditoImp, datosCliCreditoImp, sucursalCreditoImp, tipoClienteCreditoImp, usuarioCreditoImp, montoCreditoImp, cuotasCreditoImp, proximoPagoCreditoImp, tipoDocumentoCreditoImp, documentoCreditoImp, datosCuotas, montoCompra, montoInteres, pagaprimeracuota, minimoEntrega)
		{
			var urlinc = "<?php echo $GLOBALS['imprimir_nuevo_credito']; ?>";

			$.ajax({
				url: urlinc,
				method: "POST",
				data: { numeroCredito: idCreditoImp, fecha: fechaCreditoImp, planCredito: planCreditoImp, cliente: datosCliCreditoImp, sucursal: sucursalCreditoImp, tipoCliente: tipoClienteCreditoImp, usuario: usuarioCreditoImp, montoCredito: montoCreditoImp, cuotas: cuotasCreditoImp, proximoPago: proximoPagoCreditoImp, tipoDocumento: tipoDocumentoCreditoImp, documento: documentoCreditoImp, datosCuotas: datosCuotas, montoCompra: montoCompra, montoInteres: montoInteres, pagaPrimeraCuota: pagaprimeracuota, minimoEntrega: minimoEntrega },
				success: function(dataresponse, statustext, response){
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_The_New_Credit_Was_Printed_Correctly',$GLOBALS['lang']);?>') != -1)
					{
						console.log('<?php echo translate('Msg_The_New_Credit_Was_Printed_Correctly',$GLOBALS['lang']);?>');
					}
					else 
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
					}
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
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
		function confirmar_accion_validar_credito_cliente(titulo, mensaje, motivo)
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
										
										validar_cliente_supervisor_me(motivo);                                                      
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
				data: { motivo: motivo, tipoDocumento: $("#tipodocumentocreditclientni").val(), documento: $("#documentoclientcreditni").val(), token: $("#tokenvalidsupcrei").val(), token2: $("#tokenveccrediti").val() },
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
						var tokenVECCC10 = dataresponse.substring(0, dataresponse.indexOf('=:::=:::'));
						dataresponse = dataresponse.replace(tokenVECCC10+"=:::=:::","");
						var tokenVECCC = dataresponse.substring(0, dataresponse.indexOf('=::::=::::'));
						dataresponse = dataresponse.replace(tokenVECCC+"=::::=::::","");
						var planesCreCli = dataresponse.substring(0, dataresponse.indexOf('=:=:'));
						dataresponse = dataresponse.replace(planesCreCli+"=:=:","");						
						
						var compCampos = dataresponse.split("|");
						if(compCampos.length != 5)
						{
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_Unknown_Error',$GLOBALS['lang']); ?>");
							$('#documentoclientcreditni').css({'box-shadow' : '0 0 3px #FF0000'});
							$('#documentoclientcreditni').prop('title', dataresponse);
							$('#documentoclientcreditni').focus();
							
							$( "#montocompraclientcreditni" ).prop( "disabled", true );
							$( "#plancreditclientni" ).prop( "disabled", true );
							$( "#plancreditclientni" ).empty();	
							$('#plancreditclientni').off('change');							
							
							$( "#montocompraclientcreditni" ).val("");
							$( "#nombreclientcreditni" ).val("");
							$( "#apellidoclientcreditni" ).val("");
							$( "#tipoclientcreditni" ).val("1");
							$( "#telefonoclientcreditni" ).val("");
							$( "#montomaximoclientcreditni" ).val("");
							$( "#montocompraclientcreditni" ).val("");
							$( "#minimoentregaclientcreditn" ).hide();
							$( "#btnCargarNC" ).prop( "disabled", true );
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
							$( "#tokenvalidsupcrei" ).val(tokenVECCC10);
							$( "#tokenveccrediti" ).val(tokenVECCC);
							$( "#plancreditclientni" ).prop( "disabled", false );
							$( "#montocompraclientcreditni" ).focus();
							
							var planesCreCliA = planesCreCli.split(";;");
							for (var i = 0; i < planesCreCliA.length; i++) {
							   var datosPlanCreCli = planesCreCliA[i].split("|");
							   $("#plancreditclientni").append('<option value="'+datosPlanCreCli[0]+'">'+datosPlanCreCli[1]+'</option>');
							}

							$( "#btnCargarNC" ).prop( "disabled", false );
							$('#plancreditclientni').on('change', function() {
							  $( "#montocompraclientcreditni" ).focus();
							  $( "#btnCargarNC" ).prop( "disabled", true );
							});							
						}
					}
					else 
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
						$('#documentoclientcreditni').css({'box-shadow' : '0 0 3px #FF0000'});
						$('#documentoclientcreditni').prop('title', dataresponse);
						$('#documentoclientcreditni').focus();
						
						$( "#montocompraclientcreditni" ).prop( "disabled", true );
						$( "#plancreditclientni" ).prop( "disabled", true );
						$( "#plancreditclientni" ).empty();
						$('#plancreditclientni').off('change');

						$( "#montocompraclientcreditni" ).val("");
						$( "#nombreclientcreditni" ).val("");
						$( "#apellidoclientcreditni" ).val("");
						$( "#tipoclientcreditni" ).val("1");
						$( "#telefonoclientcreditni" ).val("");
						$( "#montomaximoclientcreditni" ).val("");
						$( "#montocompraclientcreditni" ).val("");	
						$( "#minimoentregaclientcreditn" ).hide();	
						$( "#btnCargarNC" ).prop( "disabled", true );
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
		function validar_cliente_supervisor_me(motivo)
		{			
			var urlvcsrcme = "./acciones/validacionclientesupervisorregistrocreditome.php";
			$('#img_loader_16').show();
									
			$.ajax({
				url: urlvcsrcme,
				method: "POST",
				data: { motivo: motivo, tipoDocumento: $("#tipodocumentocreditclientni").val(), documento: $("#documentoclientcreditni").val(), token: $("#tokenvalidsupcrei").val(), token2: $("#tokenveccrediti").val(), token3: $("#tokenvalidexcesomi").val(), validacionEC: $( "#validarstatuscreditclientecreni" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_16').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Must_Authorize_Client_Registration_Credit',$GLOBALS['lang']); ?>') != -1)
					{
						$( "#minimoentregaclientcreditn" ).hide();
						dataresponse = dataresponse.replace("<?php echo translate('Msg_Must_Authorize_Client_Registration_Credit',$GLOBALS['lang']); ?>","");
						var tagarccme = $("<div id='dialogautorizacionregistrocreditoclienteme'></div>");
						
						tagarccme.html(dataresponse).dialog({
						  show: "blind",
						  hide: "explode",
						  height: "auto",
						  width: "auto",					  
						  modal: true, 
						  title: "<?php echo translate('Lbl_Authorize_Client_Registration_Credit',$GLOBALS['lang']);?>",
						  autoResize:true,
								close: function(){
										tagarccme.dialog('destroy').remove()
								}
						}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
						tagarccme.dialog('open');
					}
					else if(dataresponse.indexOf('<?php echo translate('Msg_It_Is_Not_Necessary_To_Authorize',$GLOBALS['lang']); ?>') != -1)
					{
						$( "#tipodocumentocreditclientni" ).prop( "disabled", true );
						$( "#documentoclientcreditni" ).prop( "disabled", true );
													
						dataresponse = dataresponse.replace("<?php echo translate('Msg_It_Is_Not_Necessary_To_Authorize',$GLOBALS['lang']); ?>"+"=::=::","");
						var datTable = dataresponse.substring(0, dataresponse.indexOf('=:=:'));
						dataresponse = dataresponse.replace(datTable+"=:=:","");
						var minimoEnt = parseInt(dataresponse.substring(0, dataresponse.indexOf('=::=::=::')));
						dataresponse = dataresponse.replace(minimoEnt+"=::=::=::","");						
						var montoTotCre = dataresponse.substring(0, dataresponse.indexOf('=:::=:::'));
						dataresponse = dataresponse.replace(montoTotCre+"=:::=:::","");					

						$("#tokenvalidexcesomi").val(dataresponse);
						var resF = JSON.parse(datTable);
						for(var i in resF)
						{
							 resF[i]["montocuota"] = '$'+(resF[i]["montocuota"]/100.00);
							 resF[i]["fechavencimiento"] = resF[i]["fechavencimiento"].substring(8,10)+'/'+resF[i]["fechavencimiento"].substring(5,7)+'/'+resF[i]["fechavencimiento"].substring(0,4);
						}								
						$( "#montocreditoclientcreditni" ).val(montoTotCre/100.00);
						
						if(minimoEnt != 0)
						{
							$( "#minimoentregaclientcreditn" ).show();
							$( "#minimoentregaclientcreditni" ).val((($( "#montocompraclientcreditni" ).val().replace(/,/g,""))*(minimoEnt/100.00)).toFixed(2));
							$('#minimoentregaclientcreditni').maskNumber();						
						}
						else 
						{
							$( "#minimoentregaclientcreditn" ).val("0");
							$( "#minimoentregaclientcreditn" ).hide();
						}
						
						$( "#btnCargarNC" ).prop( "disabled", false );
						$('#tablefeescreditclientt').bootstrapTable('load',resF);
					}
					else 
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
						$( "#montocompraclientcreditni" ).focus();
						$( "#minimoentregaclientcreditn" ).hide();
					}
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$( "#minimoentregaclientcreditn" ).hide();
					$('#img_loader_16').hide();
				}
			});			
		}	
	</script>	

	<script type="text/javascript">
		function guardarAutorizacionSupervisorRegistroCreditoCliente(formularionacrc, motivo)
		{
			document.getElementById("btnValidarS4").disabled = true;
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
				document.getElementById("btnValidarS4").disabled = false;
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
				document.getElementById("btnValidarS4").disabled = false;
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
				data: { motivo: motivo, usuarioSupervisor: formularionacrc.usuariosupervisorn2i.value, claveSupervisor: p211.value, tipoDocumento: $( "#tipodocumentocreditclientni" ).val(), documento: $( "#documentoclientcreditni" ).val(), token: $("#tokenvalidsupcrei").val(), token2: $("#tokenveccrediti").val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_13').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']);?>') != -1)
					{
						dataresponse = dataresponse.replace("<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']); ?>"+"=::=::","");
						var tokenVECCC2 = dataresponse.substring(0, dataresponse.indexOf('=:::=:::'));
						dataresponse = dataresponse.replace(tokenVECCC2+"=:::=:::","");
						var tokenVECCC20 = dataresponse.substring(0, dataresponse.indexOf('=::::=::::'));
						dataresponse = dataresponse.replace(tokenVECCC20+"=::::=::::","");
						var planesCreCli = dataresponse.substring(0, dataresponse.indexOf('=:=:'));
						dataresponse = dataresponse.replace(planesCreCli+"=:=:","");						

						
						var compCampos = dataresponse.split("|");
						if(compCampos.length != 5)
						{
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_Unknown_Error',$GLOBALS['lang']); ?>");
							$('#documentoclientcreditni').css({'box-shadow' : '0 0 3px #FF0000'});
							$('#documentoclientcreditni').prop('title', dataresponse);
							$('#documentoclientcreditni').focus();
							
							$( "#montocompraclientcreditni" ).prop( "disabled", true );
							$( "#plancreditclientni" ).prop( "disabled", true );
							$( "#plancreditclientni" ).empty();
							$('#plancreditclientni').off('change');

							$( "#montocompraclientcreditni" ).val("");
							$( "#nombreclientcreditni" ).val("");
							$( "#apellidoclientcreditni" ).val("");
							$( "#tipoclientcreditni" ).val("1");
							$( "#telefonoclientcreditni" ).val("");
							$( "#montomaximoclientcreditni" ).val("");
							$( "#montocompraclientcreditni" ).val("");
							$( "#minimoentregaclientcreditn" ).hide();
							$( "#btnCargarNC" ).prop( "disabled", true );
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
							$( "#tokenveccrediti" ).val(tokenVECCC20);
							$( "#plancreditclientni" ).prop( "disabled", false );
							$( "#montocompraclientcreditni" ).focus();
							
							var planesCreCliA = planesCreCli.split(";;");
							for (var i = 0; i < planesCreCliA.length; i++) {
							   var datosPlanCreCli = planesCreCliA[i].split("|");
							   $("#plancreditclientni").append('<option value="'+datosPlanCreCli[0]+'">'+datosPlanCreCli[1]+'</option>');
							}

							$( "#btnCargarNC" ).prop( "disabled", false );
							$('#plancreditclientni').on('change', function() {
							  $( "#montocompraclientcreditni" ).focus();
							  $( "#btnCargarNC" ).prop( "disabled", true );
							});							
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
			document.getElementById("btnValidarS4").disabled = false;
		}
    </script>

	<script type="text/javascript">
		function guardarAutorizacionSupervisorRegistroCreditoClienteME(formularionacrcme, motivo)
		{
			document.getElementById("btnValidarS40").disabled = true;
			if($('#usuariosupervisorn20i').val().length == 0)
			{
				$(function() {
					$('#usuariosupervisorn20i').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$('#usuariosupervisorn20i').focus();
				document.getElementById("btnValidarS40").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$('#usuariosupervisorn20i').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$('#usuariosupervisorn20i').tooltip('destroy');
			}

			if($('#passwordsupervisorn20i').val().length == 0)
			{
				$(function() {
					$('#passwordsupervisorn20i').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$('#passwordsupervisorn20i').focus();
				document.getElementById("btnValidarS40").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$('#passwordsupervisorn20i').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$('#passwordsupervisorn20i').tooltip('destroy');
			}			
			
			var urlasrc = "./acciones/verificarcredencialessupervisorregistrocreditoclienteme.php";
			$('#img_loader_13').show();
			
			
			var p221 = document.createElement("input");
		 			
			formularionacrcme.appendChild(p221);
			p221.name = "p221";
			p221.type = "hidden";
			
			p221.value = hex_sha512(formularionacrcme.passwordsupervisorn20i.value);
			
			if(formularionacrcme.passwordsupervisorn20i.value == "") p221.value = "";
			formularionacrcme.passwordsupervisorn20i.value = "";
									
			$.ajax({
				url: urlasrc,
				method: "POST",
				data: { motivo: motivo, usuarioSupervisor: formularionacrcme.usuariosupervisorn20i.value, claveSupervisor: p221.value, tipoDocumento: $( "#tipodocumentocreditclientni" ).val(), documento: $( "#documentoclientcreditni" ).val(), token: $("#tokenvalidsupcrei").val(), token2: $("#tokenveccrediti").val(), token3: $("#tokenvalidexcesomi").val(), montoMaximoCompra: (($( "#montomaximoclientcreditni" ).val().replace(/,/g,""))*100.00), montoCompra: (($( "#montocompraclientcreditni" ).val().replace(/,/g,""))*100.00), planCredito: $( "#plancreditclientni" ).val(), validacionEC: $( "#validarstatuscreditclientecreni" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_13').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']);?>') != -1)
					{
						$('#dialogautorizacionregistrocreditoclienteme').dialog('destroy').remove();
						$( "#tipodocumentocreditclientni" ).prop( "disabled", true );
						$( "#documentoclientcreditni" ).prop( "disabled", true );
													
						dataresponse = dataresponse.replace("<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']); ?>"+"=::=::","");
						var datTable = dataresponse.substring(0, dataresponse.indexOf('=:=:'));
						dataresponse = dataresponse.replace(datTable+"=:=:","");
						var minimoEnt = parseInt(dataresponse.substring(0, dataresponse.indexOf('=::=::=::')));
						dataresponse = dataresponse.replace(minimoEnt+"=::=::=::","");												
						var montoTotCre = dataresponse.substring(0, dataresponse.indexOf('=:::=:::'));
						dataresponse = dataresponse.replace(montoTotCre+"=:::=:::","");
						
						
						$("#tokenvalidexcesomi").val(dataresponse);
						var resF = JSON.parse(datTable);
						for(var i in resF)
						{
							 resF[i]["montocuota"] = '$'+(resF[i]["montocuota"]/100.00);
							 resF[i]["fechavencimiento"] = resF[i]["fechavencimiento"].substring(8,10)+'/'+resF[i]["fechavencimiento"].substring(5,7)+'/'+resF[i]["fechavencimiento"].substring(0,4);
						}
						$( "#montocreditoclientcreditni" ).val(montoTotCre/100.00);	
						
						if(minimoEnt != 0)
						{
							$( "#minimoentregaclientcreditn" ).show();
							$( "#minimoentregaclientcreditni" ).val((($( "#montocompraclientcreditni" ).val().replace(/,/g,""))*(minimoEnt/100.00)).toFixed(2));
							$('#minimoentregaclientcreditni').maskNumber();						
						}
						else 
						{
							$( "#minimoentregaclientcreditn" ).val("0");
							$( "#minimoentregaclientcreditn" ).hide();
						}
						$( "#btnCargarNC" ).prop( "disabled", false );
						$('#tablefeescreditclientt').bootstrapTable('load',resF);
					}
					else
					{
						if(dataresponse.indexOf('<?php echo translate('Msg_Supervisor_Not_OK',$GLOBALS['lang']);?>') != -1)
						{
							$( "#minimoentregaclientcreditn" ).hide();
							$('#usuariosupervisorn20i').focus();
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
						}
						else 
						{
							$( "#minimoentregaclientcreditn" ).hide();
							$('#usuariosupervisorn20i').focus();
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
						}					
					}
					
				},
				error: function(request, errorcode, errortext){
					$('#usuariosupervisorn20i').focus();
					$( "#minimoentregaclientcreditn" ).hide();
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_13').hide();
				}
			});
			document.getElementById("btnValidarS40").disabled = false;
		}
    </script>

	<script type="text/javascript">
		function reImprimirCreditoCliente(idCredito)
		{				
			document.getElementById("btnReimprimirCreditoClient"+idCredito).disabled = true;
			var urlricc = "./acciones/reimprimircreditocliente.php";
			$('#img_loader_5').show();
			
			$.ajax({
				url: urlricc,
				method: "POST",
				data: { idCredito: idCredito },
				success: function(dataresponse, statustext, response){
					$('#img_loader_5').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Reprint_Credit_Client_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						dataresponse = dataresponse.replace(menR+"=:=:=:","");
						var datosImpresion = dataresponse.substring(0);

						var datosFinImpre = datosImpresion.split('|');
						confirmar_accion_reimprimir_credito_cliente("<?php echo translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']);?>", "<?php echo translate('Msg_Be_Sure_To_Reprint_The_Credit',$GLOBALS['lang']);?>", datosFinImpre[0], datosFinImpre[1], datosFinImpre[5], datosFinImpre[6], datosFinImpre[2], datosFinImpre[7], datosFinImpre[14], (datosFinImpre[8]/100.00), datosFinImpre[3], datosFinImpre[4], datosFinImpre[9], datosFinImpre[10], datosFinImpre[11], datosFinImpre[12], datosFinImpre[13], datosFinImpre[15], datosFinImpre[16]);
					}
					else 
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
					}
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_5').hide();
				}
			});
			document.getElementById("btnReimprimirCreditoClient"+idCredito).disabled = false;
		}
    </script>

	<script type="text/javascript">
		function confirmar_accion_reimprimir_credito_cliente(titulo, mensaje, idCreditoImp, fechaCreditoImp, planCreditoImp, datosCliCreditoImp, sucursalCreditoImp, tipoClienteCreditoImp, usuarioCreditoImp, montoCreditoImp, cuotasCreditoImp, proximoPagoCreditoImp, tipoDocumentoCreditoImp, documentoCreditoImp, datosCuotas, montoCompra, montoInteres, pagaPrimeraCuota, minimoEntrega)
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
										
										reImprimirNuevoCredito(idCreditoImp, fechaCreditoImp, planCreditoImp, datosCliCreditoImp, sucursalCreditoImp, tipoClienteCreditoImp, usuarioCreditoImp, montoCreditoImp, cuotasCreditoImp, proximoPagoCreditoImp, tipoDocumentoCreditoImp, documentoCreditoImp, datosCuotas, montoCompra, montoInteres, pagaPrimeraCuota, minimoEntrega);                                                      
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
		function reImprimirNuevoCredito(idCreditoImp, fechaCreditoImp, planCreditoImp, datosCliCreditoImp, sucursalCreditoImp, tipoClienteCreditoImp, usuarioCreditoImp, montoCreditoImp, cuotasCreditoImp, proximoPagoCreditoImp, tipoDocumentoCreditoImp, documentoCreditoImp, datosCuotas, montoCompra, montoInteres, pagaPrimeraCuota, minimoEntrega)
		{
			var urlinc2 = "<?php echo $GLOBALS['imprimir_nuevo_credito']; ?>";

			$.ajax({
				url: urlinc2,
				method: "POST",
				data: { numeroCredito: idCreditoImp, fecha: fechaCreditoImp, planCredito: planCreditoImp, cliente: datosCliCreditoImp, sucursal: sucursalCreditoImp, tipoCliente: tipoClienteCreditoImp, usuario: usuarioCreditoImp, montoCredito: montoCreditoImp, cuotas: cuotasCreditoImp, proximoPago: proximoPagoCreditoImp, tipoDocumento: tipoDocumentoCreditoImp, documento: documentoCreditoImp, esCopia: 1, datosCuotas: datosCuotas, montoCompra: montoCompra, montoInteres: montoInteres, pagaPrimeraCuota: pagaPrimeraCuota, minimoEntrega: minimoEntrega },
				success: function(dataresponse, statustext, response){
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_The_New_Credit_Was_Printed_Correctly',$GLOBALS['lang']);?>') != -1)
					{
						console.log('<?php echo translate('Msg_The_New_Credit_Was_Printed_Correctly',$GLOBALS['lang']);?>');
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",'<?php echo translate('Msg_Reprint_Credit_Client_OK2',$GLOBALS['lang']);?>');
					}
					else 
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
					}
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
				}
			});
		}
    </script>

	<script type="text/javascript">
		function confirmar_accion_cancelar_credito_cliente(titulo, mensaje, formulariocc, idCredito)
		{
			document.getElementById("btnCancelarCreditoClient"+idCredito).disabled = true;
			if($('#motivocancelcrediti').val().length == 0)
			{
				$(function() {
					$('#motivocancelcrediti').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$('#motivocancelcrediti').focus();
				document.getElementById("btnCancelarCreditoClient"+idCredito).disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$('#motivocancelcrediti').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$('#motivocancelcrediti').tooltip('destroy');
			}			
			
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
										
										guardarCancelacionCredito(formulariocc, idCredito);                                                      
								},
								"<?php echo translate('Lbl_Button_NO',$GLOBALS['lang']);?>": function () {
										$("#confirmDialog").dialog('close');
										document.getElementById("btnCancelarCreditoClient"+idCredito).disabled = false;
										return;
								}
						}
				}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
				$( "#confirmDialog" ).html("<div id='confirmacionAccion'>"+mensaje+"?</div>");
				$('#img_loader').hide();
			document.getElementById("btnCancelarCreditoClient"+idCredito).disabled = false;
		}
	</script>

	<script type="text/javascript">
		function guardarCancelacionCredito(formulariocc, idCredito)
		{			
			var urlgcc = "./acciones/guardarcancelacioncredito.php";
			$('#img_loader_23').show();
												
			$.ajax({
				url: urlgcc,
				method: "POST",
				data: { idCredito: idCredito, motivoCancelacion: $( "#motivocancelcrediti" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_23').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Cancel_Credit_Client_OK',$GLOBALS['lang']);?>') != -1)
					{
						$('#dialogcancelcredit').dialog('destroy').remove();
													
						dataresponse = dataresponse.replace("<?php echo translate('Msg_Cancel_Credit_Client_OK',$GLOBALS['lang']); ?>"+"=::=::","");
						$('#tableadmincreditst').bootstrapTable('load',JSON.parse(dataresponse));						
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",'<?php echo translate('Msg_Cancel_Credit_Client_OK',$GLOBALS['lang']);?>');
					}
					else
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);				
					}
					
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_23').hide();
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
			<h3 class="panel-title" id="titulocreditoscliente"><?php echo translate('Lbl_Credits_Clients',$GLOBALS['lang']); ?></h3>
		  </div>
		  <div id="apDiv1" class="panel-body">
			<div id="toolbar" style="margin-left:-345px; margin-top:-1px;">
				<button type="button" id="btnNuevoCredito" class="btn" data-toggle="tooltip" data-placement="top" onclick="nuevoCredito();" title="<?php echo translate('Lbl_New_Credit',$GLOBALS['lang']);?>" ><i class="far fa-plus-square"></i></button>&nbsp;&nbsp;<button type="button" id="btnBuscarCreditosCliente" class="btn" data-toggle="tooltip" data-placement="top" onclick="buscarCreditosCliente();" title="<?php echo translate('Lbl_Search_Credits_Client',$GLOBALS['lang']);?>" ><i class="fas fa-search"></i></button>
			</div>
			<div id="img_loader"></div>
			<div id="tablaadmincredits" class="table-responsive">
				<table id="tableadmincreditst" data-classes="table table-hover table-condensed"
				   data-striped="true" data-pagination="true" data-show-export="true" data-export-options='{"fileName": "<?php echo translate('File_Credits_Clients',$GLOBALS['lang']); ?>"}'
				   data-export-types="['excel','pdf','csv','txt']"
				   data-search="true" data-search-align="right" data-toolbar="#toolbar" data-toolbar-align="right">
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
									if ($stmt = $mysqli->prepare("SELECT c.id, cc.fecha, td.nombre, cc.documento, c.monto_credito_original, pc.nombre, c.cantidad_cuotas, c.estado FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.cliente cli, finan_cli.plan_credito pc, finan_cli.tipo_documento td, finan_cli.sucursal suc WHERE pc.id = c.id_plan_credito AND c.id = cc.id_credito AND cc.tipo_documento = cli.tipo_documento AND cc.documento = cli.documento AND cc.tipo_documento = td.id AND cc.id_sucursal = suc.id AND suc.id_cadena = ? ORDER BY cc.fecha DESC LIMIT 50")) 
									{
										$stmt->bind_param('i', $id_cadena_user);
										$stmt->execute();    // Ejecuta la consulta preparada.
										$stmt->store_result();
								 
										// Obtiene las variables del resultado.
										$stmt->bind_result($id_credit_client, $date_credit_client, $type_documento_credit_client, $document_credit_client, $amount_credit_client, $name_credit_plan_client, $fees_credit_client, $state_credit_client);
										
										while($stmt->fetch())
										{		
											echo '<tr>';
											echo '<td>'.substr($date_credit_client,6,2).'/'.substr($date_credit_client,4,2).'/'.substr($date_credit_client,0,4).'</td>';
											echo '<td>'.$type_documento_credit_client.'</td>';
											echo '<td>'.$document_credit_client.'</td>';
											echo '<td>$'.round(($amount_credit_client/100.00),2).'</td>';
											echo '<td>'.$name_credit_plan_client.'</td>';
											echo '<td>'.$fees_credit_client.'</td>';
											echo '<td>'.$state_credit_client.'</td>';
											
											if($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 3)
											{
												if(translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) == $state_credit_client || translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']) == $state_credit_client) echo '<td><button type="button" id="btnVerCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Credit_Client',$GLOBALS['lang']).'" onclick="verCredito('.$id_credit_client.')"><i class="fas fa-eye"></i></button>&nbsp;&nbsp;<button type="button" id="btnReimprimirCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Credit_Client',$GLOBALS['lang']).'" onclick="reImprimirCreditoCliente('.$id_credit_client.')"><i class="fas fa-print"></i></button>&nbsp;&nbsp;<button type="button" id="btnReimprimirPDFCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Generate_PDF_Credit_Client',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfcredito.php?idCredito='.$id_credit_client.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;&nbsp;<button type="button" id="btnGestionDeudaCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Lbl_Debt_Management2',$GLOBALS['lang']).'" onclick="window.open(\'gestiondeuda.php?doc='.$document_credit_client.'\')"><i class="fas fa-link"></i></button>&nbsp;&nbsp;<button type="button" id="btnCancelarCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Credit_Client',$GLOBALS['lang']).'" onclick="cancelarCredito('.$id_credit_client.')"><i class="far fa-window-close"></i></button></td>';
												else if(translate('Lbl_Status_Fee_Paid',$GLOBALS['lang']) == $state_credit_client || translate('Lbl_Status_Fee_Condoned',$GLOBALS['lang']) == $state_credit_client || translate('Lbl_Status_Fee_Incobrable',$GLOBALS['lang']) == $state_credit_client || translate('Lbl_Status_Fee_Insolvent',$GLOBALS['lang']) == $state_credit_client) echo '<td><button type="button" id="btnVerCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Credit_Client',$GLOBALS['lang']).'" onclick="verCredito('.$id_credit_client.')"><i class="fas fa-eye"></i></button>&nbsp;&nbsp;<button type="button" id="btnGestionDeudaCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Lbl_Debt_Management2',$GLOBALS['lang']).'" onclick="window.open(\'gestiondeuda.php?doc='.$document_credit_client.'\')"><i class="fas fa-link"></i></button>&nbsp;&nbsp;<button type="button" id="btnCancelarCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Cancel_Credit_Client',$GLOBALS['lang']).'" onclick="cancelarCredito('.$id_credit_client.')"><i class="far fa-window-close"></i></button></td>'; 
												else echo '<td><button type="button" id="btnVerCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Credit_Client',$GLOBALS['lang']).'" onclick="verCredito('.$id_credit_client.')"><i class="fas fa-eye"></i></button></td>';
											}
											else
											{
												if(translate('Lbl_Status_Fee_Pending',$GLOBALS['lang']) == $state_credit_client || translate('Lbl_Status_Fee_In_Mora',$GLOBALS['lang']) == $state_credit_client) echo '<td><button type="button" id="btnVerCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Credit_Client',$GLOBALS['lang']).'" onclick="verCredito('.$id_credit_client.')"><i class="fas fa-eye"></i></button>&nbsp;&nbsp;<button type="button" id="btnReimprimirCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Reprint_Credit_Client',$GLOBALS['lang']).'" onclick="reImprimirCreditoCliente('.$id_credit_client.')"><i class="fas fa-print"></i></button>&nbsp;&nbsp;<button type="button" id="btnReimprimirPDFCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Generate_PDF_Credit_Client',$GLOBALS['lang']).'" onclick="window.open(\'acciones/mostrarpdfcredito.php?idCredito='.$id_credit_client.'\')"><i class="far fa-file-pdf"></i></button>&nbsp;&nbsp;<button type="button" id="btnGestionDeudaCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Lbl_Debt_Management2',$GLOBALS['lang']).'" onclick="window.open(\'gestiondeuda.php?doc='.$document_credit_client.'\')"><i class="fas fa-link"></i></button></td>';
												else echo '<td><button type="button" id="btnVerCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Credit_Client',$GLOBALS['lang']).'" onclick="verCredito('.$id_credit_client.')"><i class="fas fa-eye"></i></button>&nbsp;&nbsp;<button type="button" id="btnGestionDeudaCreditoClient'.$id_credit_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Lbl_Debt_Management2',$GLOBALS['lang']).'" onclick="window.open(\'gestiondeuda.php?doc='.$document_credit_client.'\')"><i class="fas fa-link"></i></button></td>';												
											}
											echo '</tr>';
										}
									}
									$stmt500->free_result();
									$stmt500->close();	
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