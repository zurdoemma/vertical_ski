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
	<title><?php echo translate('Lbl_Clients',$GLOBALS['lang']); ?></title>
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
		function nuevoCliente()
		{
			var urlnpc = "./acciones/nuevocliente.php";
			var tagnpc = $("<div id='dialognewclient'></div>");
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
					  title: "<?php echo translate('Lbl_New_Client',$GLOBALS['lang']);?>",
					  autoResize:true,
							close: function(){
									tagnpc.dialog('destroy').remove()
							}
					}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
					
					var todayDate = new Date().getDate();
					$("#datetimepickerfechanacimientoclientn").datetimepicker({
							format: 'L',
							locale: 'es',
							viewMode: 'years',
							minDate: new Date(new Date().setDate(todayDate - 40150)),
							maxDate: new Date(new Date().setDate(todayDate + 0)),
							widgetPositioning:{
								horizontal: 'auto',
								vertical: 'bottom'}
					});
					
					$( "#tipoclientni" ).change(function() 
					{
						if($( "#tipoclientni" ).val() != "<?php echo translate('Lbl_Type_Client_Headline',$GLOBALS['lang']);?>")
						{
							$( "#documentoni" ).val("");
							$( "#nombreclientni" ).val("");
							$( "#apellidoclientni" ).val("");
							$( "#fechanacimientoclientni" ).val("");
							$( "#cuitcuilclientni" ).val("");
							$( "#emailclientni" ).val("");
							$( "#montomaximoclientni" ).val("");
							$( "#observacionclientni" ).val("");
							
							$( "#calleni" ).val("");
							$( "#nrocalleni" ).val("");
							$( "#domlocalidadni" ).val("");
							$( "#domfloorni" ).val("");
							$( "#zipcodeni" ).val("");
							$( "#entrecalle1ni" ).val("");
							$( "#entrecalle2ni" ).val("");
							$( "#domdepartamentoni" ).val("");							
							$( "#prefijotelefonoi" ).val("");
							$( "#nrotelefonoi" ).val("");							
							
							$( "#tipodocumentoclientni" ).prop( "disabled", true );							
							$( "#documentoni" ).prop( "disabled", true );
							$( "#nombreclientni" ).prop( "disabled", true );
							$( "#apellidoclientni" ).prop( "disabled", true );
							$( "#fechanacimientoclientni" ).prop( "disabled", true );
							$( "#cuitcuilclientni" ).prop( "disabled", true );
							$( "#emailclientni" ).prop( "disabled", true );
							$( "#montomaximoclientni" ).prop( "disabled", true );
							$( "#perfilcreditoclientni" ).prop( "disabled", true );
							$( "#observacionclientni" ).prop( "disabled", true );
							
							$('#mostrarDomicilioCargaN').hide();
							$('#btnCargaDomicilioCN').prop('title', '<?php echo translate('Lbl_New_Home_Address_User',$GLOBALS['lang']);?>');
							$('#btnCargaDomicilioCN').html('<i class="fa fa-eye"></i>');
							$( "#btnCargaDomicilioCN" ).prop( "disabled", true );
							
							$('#mostrarTelefonoCargaN').hide();
							$('#btnCargaTelefonoCN').prop('title', '<?php echo translate('Lbl_New_Home_Address_User',$GLOBALS['lang']);?>');
							$('#btnCargaTelefonoCN').html('<i class="fas fa-phone"></i>');							
							$( "#btnCargaTelefonoCN" ).prop( "disabled", true );
							
							$( "#btnCargarNC" ).prop( "disabled", true );

							$( "#busquedatitular" ).show();
							$('#documentonbi').focus();
						}
					    else
						{							
							$( "#documentoni" ).val("");
							$( "#nombreclientni" ).val("");
							$( "#apellidoclientni" ).val("");
							$( "#fechanacimientoclientni" ).val("");
							$( "#cuitcuilclientni" ).val("");
							$( "#emailclientni" ).val("");
							$( "#montomaximoclientni" ).val("");
							$( "#observacionclientni" ).val("");

							$( "#calleni" ).val("");
							$( "#nrocalleni" ).val("");
							$( "#domlocalidadni" ).val("");
							$( "#domfloorni" ).val("");
							$( "#zipcodeni" ).val("");
							$( "#entrecalle1ni" ).val("");
							$( "#entrecalle2ni" ).val("");
							$( "#domdepartamentoni" ).val("");							
							$( "#prefijotelefonoi" ).val("");
							$( "#nrotelefonoi" ).val("");
							
							$( "#tipodocumentoclientni" ).prop( "disabled", false );							
							$( "#documentoni" ).prop( "disabled", false );
							$( "#nombreclientni" ).prop( "disabled", false );
							$( "#apellidoclientni" ).prop( "disabled", false );
							$( "#fechanacimientoclientni" ).prop( "disabled", false );
							$( "#cuitcuilclientni" ).prop( "disabled", false );
							$( "#emailclientni" ).prop( "disabled", false );
							$( "#montomaximoclientni" ).prop( "disabled", false );
							$( "#perfilcreditoclientni" ).prop( "disabled", false );
							$( "#observacionclientni" ).prop( "disabled", false );
							$( "#btnCargaDomicilioCN" ).prop( "disabled", false );							
							$( "#btnCargaTelefonoCN" ).prop( "disabled", false );
							$( "#btnCargarNC" ).prop( "disabled", false );
							$('#documentonbi').prop( "disabled", false );
							$('#tipodocumentoclientnbi').prop( "disabled", false );

							$( "#busquedatitular" ).hide();
							$('#documentonbi').css({'box-shadow' : '0 0 3px #0758DE'});
							$('#documentonbi').prop('title', "<?php echo translate('Msg_A_Document_Client_Must_Enter',$GLOBALS['lang']);?>");
							$('#tipodocumentoclientni').focus();
						}
					});

					$('#documentonbi').keypress(function(event){
						var keycode = (event.keyCode ? event.keyCode : event.which);
						if(keycode == '13'){
							validarExistenciaTitular($('#tipodocumentoclientnbi').val(),$('#documentonbi').val()); 
						}
					});
					
					//$('#documentonbi').focusout(function(){
					//	validarExistenciaTitular($('#tipodocumentoclientnbi').val(),$('#documentonbi').val());
					//});					
								
					tagnpc.dialog('open');
					$('#montomaximoclientni').maskNumber();
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_5').hide();
				}
			});	
		}
    </script>
	
	<script type="text/javascript">
		function validarExistenciaTitular(tipoDoc, documento)
		{
			$('#documentonbi').css({'box-shadow' : '0 0 3px #58ACFA'});
			
			if($('#documentonbi').val().length == 0)
			{
				$('#documentonbi').prop('title', '<?php echo translate('Msg_A_Document_Client_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$('#documentonbi').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$('#documentonbi').focus();
				return;
			}
			else 
			{
				$(function() {
					$('#documentonbi').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$('#documentonbi').tooltip('destroy');
			}			
			
			var urlve = "./acciones/validarexistenciatitular.php";
			$('#img_loader_12').show();
			
			$.ajax({
				url: urlve,
				method: "POST",
				data: { tipoDocumento: tipoDoc, documento: documento },
				success: function(dataresponse, statustext, response){
					$('#img_loader_12').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_A_Client_Does_Not_Exist_As_A_Proprietor',$GLOBALS['lang']);?>') != -1)
					{
						mensaje_atencion("<?php echo translate('Lbl_Attention',$GLOBALS['lang']);?>", dataresponse);
						$('#documentonbi').css({'box-shadow' : '0 0 3px #FF0000'});
						$('#documentonbi').prop('title', dataresponse);
						$('#documentonbi').focus();
					}
					else
					{
						if(dataresponse.indexOf('<?php echo translate('Msg_A_Client_Exist_As_A_Proprietor',$GLOBALS['lang']);?>') != -1)
						{
							$('#documentonbi').css({'box-shadow' : '0 0 3px #00FF00'});
							
							$( "#tipodocumentoclientni" ).prop( "disabled", false );							
							$( "#documentoni" ).prop( "disabled", false );
							$( "#nombreclientni" ).prop( "disabled", false );
							$( "#apellidoclientni" ).prop( "disabled", false );
							$( "#fechanacimientoclientni" ).prop( "disabled", false );
							$( "#cuitcuilclientni" ).prop( "disabled", false );
							$( "#emailclientni" ).prop( "disabled", false );
							$( "#montomaximoclientni" ).prop( "disabled", false );
							$( "#perfilcreditoclientni" ).prop( "disabled", false );
							$( "#observacionclientni" ).prop( "disabled", false );
							$( "#btnCargaDomicilioCN" ).prop( "disabled", false );							
							$( "#btnCargaTelefonoCN" ).prop( "disabled", false );
							$( "#btnCargarNC" ).prop( "disabled", false );
							
							$('#documentonbi').prop( "disabled", true );
							$('#tipodocumentoclientnbi').prop( "disabled", true );
							$('#documentonbi').prop('title', dataresponse);
							$('#tipodocumentoclientni').focus();
						}
						else 
						{
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
							$('#documentonbi').css({'box-shadow' : '0 0 3px #FF0000'});
							$('#documentonbi').prop('title', dataresponse);
							$('#documentonbi').focus();
						}					
					}
					
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_12').hide();
					$('#documentonbi').css({'box-shadow' : '0 0 3px #FF0000'});
					$('#documentonbi').focus();
				}
			});
		}
    </script>
	
	<script type="text/javascript">
		function verDomicilioNC()
		{
			if(!$('#mostrarDomicilioCargaN').is(':visible'))
			{
				$('#mostrarDomicilioCargaN').show();
				$('#btnCargaDomicilioCN').prop('title', '<?php echo translate('Lbl_Hide_New_Home_Address_User',$GLOBALS['lang']);?>');
				$('#btnCargaDomicilioCN').html('<i class="fa fa-eye-slash"></i>');
				$('#calleni').focus();
			}
			else
			{
				$('#mostrarDomicilioCargaN').hide();
				$('#btnCargaDomicilioCN').prop('title', '<?php echo translate('Lbl_New_Home_Address_User',$GLOBALS['lang']);?>');
				$('#btnCargaDomicilioCN').html('<i class="fa fa-eye"></i>');
				$('#tipoclientni').focus();	
			}
		}
    </script>
	
	<script type="text/javascript">
		function verTelefonoNC()
		{
			if(!$('#mostrarTelefonoCargaN').is(':visible'))
			{
				$('#mostrarTelefonoCargaN').show();
				$('#btnCargaTelefonoCN').prop('title', '<?php echo translate('Lbl_Hide_New_Home_Address_User',$GLOBALS['lang']);?>');
				$('#btnCargaTelefonoCN').html('<i class="fas fa-phone-slash"></i>');
				$('#prefijotelefonoi').focus();
			}
			else
			{
				$('#mostrarTelefonoCargaN').hide();
				$('#btnCargaTelefonoCN').prop('title', '<?php echo translate('Lbl_New_Home_Address_User',$GLOBALS['lang']);?>');
				$('#btnCargaTelefonoCN').html('<i class="fas fa-phone"></i>');
				$('#tipoclientni').focus();	
			}
		}
    </script>

	<script type="text/javascript">
		function guardarNuevoCliente(formulariod)
		{
			if($( "#documentoni" ).val().length == 0)
			{
				$(function() {
					$( "#documentoni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#documentoni" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#documentoni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#documentoni" ).tooltip('destroy');
			}
												
			if($( "#nombreclientni" ).val().length == 0)
			{
				$(function() {
					$( "#nombreclientni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#nombreclientni" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#nombreclientni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#nombreclientni" ).tooltip('destroy');
			}
			
			if($( "#apellidoclientni" ).val().length == 0)
			{
				$(function() {
					$( "#apellidoclientni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#apellidoclientni" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#apellidoclientni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#apellidoclientni" ).tooltip('destroy');
			}			
			
			if($( "#fechanacimientoclientni" ).val().length == 0)
			{
				$(function() {
					$( "#fechanacimientoclientni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#fechanacimientoclientni" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#fechanacimientoclientni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#fechanacimientoclientni" ).tooltip('destroy');
			}			
						
			if($( "#cuitcuilclientni" ).val().length == 0)
			{
				$('#cuitcuilclientni').prop('title', '<?php echo translate('Msg_A_Cuit_Cuil_Client_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#cuitcuilclientni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#cuitcuilclientni" ).focus();
				return;
			}
			else 
			{
				if (isNaN($( "#cuitcuilclientni" ).val()) || $( "#cuitcuilclientni" ).val() % 1 != 0)
				{
					$('#cuitcuilclientni').prop('title', '<?php echo translate('Msg_A_Cuit_Cuil_Client_Must_Enter_A_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#cuitcuilclientni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#cuitcuilclientni" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#cuitcuilclientni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#cuitcuilclientni" ).tooltip('destroy');
				}
			}			

			if($( "#emailclientni" ).val().length != 0)
			{			
				if(!caracteresCorreoValido($( "#emailclientni" ).val()))
				{
					$('#emailclientni').prop('title', '<?php echo translate('Msg_A_User_Email_Invalid',$GLOBALS['lang']);?>');
					$(function() {
						$( "#emailclientni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#emailclientni" ).focus();
					return;				
				}
				else
				{
					$(function() {
						$( "#emailclientni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});				
					$( "#emailclientni" ).tooltip('destroy');				
				}
			}
			
			if($( "#montomaximoclientni" ).val().length == 0)
			{
				$('#montomaximoclientni').prop('title', '<?php echo translate('Msg_A_Max_Amount_Client_Must_Enter',$GLOBALS['lang']);?>');	
				$(function() {
					$( "#montomaximoclientni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#montomaximoclientni" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#montomaximoclientni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#montomaximoclientni" ).tooltip('destroy');
			}				
			
			if($( "#montomaximoclientni" ).val().length != 0)
			{			
				if (isNaN($( "#montomaximoclientni" ).val().replace(/,/g,"")))
				{
					$('#montomaximoclientni').prop('title', '<?php echo translate('Msg_A_Amount_Limit_Client_Must_Enter_A_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#montomaximoclientni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#montomaximoclientni" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#montomaximoclientni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#montomaximoclientni" ).tooltip('destroy');
				}
			}

			if(!$('#mostrarDomicilioCargaN').is(':visible'))
			{
				$('#mostrarDomicilioCargaN').show();
				$('#btnCargaDomicilioCN').prop('title', '<?php echo translate('Lbl_Hide_New_Home_Address_User',$GLOBALS['lang']);?>');
				$('#btnCargaDomicilioCN').html('<i class="fa fa-eye-slash"></i>');
			}
			
			if($( "#calleni" ).val().length == 0)
			{
				$(function() {
					$( "#calleni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#calleni" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#calleni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#calleni" ).tooltip('destroy');
			}
			
			if($( "#nrocalleni" ).val().length == 0)
			{
				$('#nrocalleni').prop('title', '<?php echo translate('Msg_A_Street_Number_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#nrocalleni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#nrocalleni" ).focus();
				return;
			}
			else 
			{
				if (isNaN($( "#nrocalleni" ).val()) || $( "#nrocalleni" ).val() % 1 != 0)
				{
					$('#nrocalleni').prop('title', '<?php echo translate('Msg_A_Street_Number_Must_Enter_A_Whole',$GLOBALS['lang']);?>');
					$(function() {
						$( "#nrocalleni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#nrocalleni" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#nrocalleni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#nrocalleni" ).tooltip('destroy');
				}
			}
			
			if($( "#domlocalidadni" ).val().length == 0)
			{
				$(function() {
					$( "#domlocalidadni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#domlocalidadni" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#domlocalidadni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});					
				$( "#domlocalidadni" ).tooltip('destroy');		
			}
			
			if($( "#domfloorni" ).val().length != 0)
			{
				if (isNaN($( "#domfloorni" ).val()) || $( "#domfloorni" ).val() % 1 != 0)
				{
					$(function() {
						$( "#domfloorni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#domfloorni" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#domfloorni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#domfloorni" ).tooltip('destroy');
				}					
			}			

			if(!$('#mostrarTelefonoCargaN').is(':visible'))
			{
				$('#mostrarTelefonoCargaN').show();
				$('#btnCargaTelefonoCN').prop('title', '<?php echo translate('Lbl_Hide_New_Home_Address_User',$GLOBALS['lang']);?>');
				$('#btnCargaTelefonoCN').html('<i class="fas fa-phone-slash"></i>');
			}
			
			if($( "#prefijotelefonoi" ).val().length == 0)
			{
				$('#prefijotelefonoi').prop('title', '<?php echo translate('Msg_A_Pre_Number_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#prefijotelefonoi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#prefijotelefonoi" ).focus();
				return;
			}
			else 
			{
				if (isNaN($( "#prefijotelefonoi" ).val()) || $( "#prefijotelefonoi" ).val() % 1 != 0)
				{
					$('#prefijotelefonoi').prop('title', '<?php echo translate('Msg_A_Pre_Number_Must_Enter_A_Whole',$GLOBALS['lang']);?>');
					$(function() {
						$( "#prefijotelefonoi" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#prefijotelefonoi" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#prefijotelefonoi" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#prefijotelefonoi" ).tooltip('destroy');
				}
			}
			
			if($( "#nrotelefonoi" ).val().length == 0)
			{
				$('#nrotelefonoi').prop('title', '<?php echo translate('Msg_A_Number_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#nrotelefonoi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#nrotelefonoi" ).focus();
				return;
			}
			else 
			{
				if (isNaN($( "#nrotelefonoi" ).val()) || $( "#nrotelefonoi" ).val() % 1 != 0)
				{
					$('#nrotelefonoi').prop('title', '<?php echo translate('Msg_A_Number_Must_Enter_A_Whole',$GLOBALS['lang']);?>');
					$(function() {
						$( "#nrotelefonoi" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#nrotelefonoi" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#nrotelefonoi" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#nrotelefonoi" ).tooltip('destroy');
				}
			}			
			
			
			
			if($( "#tipoclientni" ).val() != "<?php echo translate('Lbl_Type_Client_Headline',$GLOBALS['lang']);?>")
			{
				
				if($( "#tipodocumentoclientnbi" ).val() == $( "#tipodocumentoclientni" ).val() && $( "#documentonbi" ).val() == $( "#documentoni" ).val())
				{
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('The_Additional_Client_Can_Not_Have_The_Same_Type_And_Document_Number',$GLOBALS['lang']);?>");
					$( "#documentoni" ).focus();
					return;
				}
				
				var urlvs = "./acciones/autorizaradicionalcliente.php";
				$('#img_loader_12').show();
				
				$.ajax({
					url: urlvs,
					method: "POST",
					data: { tokenA: $( "#tokenasi" ).val(), tipoDocumento: $( "#tipodocumentoclientni" ).val(), documento: $( "#documentoni" ).val(), tipoDocumentoTitular: $( "#tipodocumentoclientnbi" ).val(), documentoTitular: $( "#documentonbi" ).val()},
					success: function(dataresponse, statustext, response){
						$('#img_loader_12').hide();
						
						if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
						{
							window.location.replace("./login.php?result_ok=3");
						}
						
						if(dataresponse.indexOf('<?php echo translate('Msg_It_Is_Not_Necessary_To_Authorize',$GLOBALS['lang']); ?>') != -1)
						{
							guardarNuevoClienteUC();
							return;
						}
						else
						{
							if(dataresponse.indexOf('<?php echo translate('Msg_Must_Authorize_Additional',$GLOBALS['lang']); ?>') != -1)
							{
								dataresponse = dataresponse.replace("<?php echo translate('Msg_Must_Authorize_Additional',$GLOBALS['lang']); ?>","");
								var tagas = $("<div id='dialogautorizacionadicional'></div>");
								
								tagas.html(dataresponse).dialog({
								  show: "blind",
								  hide: "explode",
								  height: "auto",
								  width: "auto",					  
								  modal: true, 
								  title: "<?php echo translate('Lbl_Authorize_Additional',$GLOBALS['lang']);?>",
								  autoResize:true,
										close: function(){
												tagas.dialog('destroy').remove()
										}
								}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
								tagas.dialog('open');
							}
							else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
						}
							
					},
					error: function(request, errorcode, errortext){
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
						$('#img_loader_12').hide();
					}
				});					
			}
			else
			{
				guardarNuevoClienteUC();
				return;				
			}
		}			
	</script>

	<script type="text/javascript">
		function guardarNuevoClienteConSupervisor(formularionaac)
		{
			if($('#usuariosupervisorni').val().length == 0)
			{
				$(function() {
					$('#usuariosupervisorni').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$('#usuariosupervisorni').focus();
				return;
			}
			else 
			{
				$(function() {
					$('#usuariosupervisorni').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$('#usuariosupervisorni').tooltip('destroy');
			}

			if($('#passwordsupervisorni').val().length == 0)
			{
				$(function() {
					$('#passwordsupervisorni').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$('#passwordsupervisorni').focus();
				return;
			}
			else 
			{
				$(function() {
					$('#passwordsupervisorni').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$('#passwordsupervisorni').tooltip('destroy');
			}			
			
			var urlas = "./acciones/verificarcredencialessupervisor.php";
			$('#img_loader_13').show();
			
			
			var p210 = document.createElement("input");
		 			
			formularionaac.appendChild(p210);
			p210.name = "p210";
			p210.type = "hidden";
			
			p210.value = hex_sha512(formularionaac.passwordsupervisorni.value);
			
			if(formularionaac.passwordsupervisorni.value == "") p210.value = "";
			formularionaac.passwordsupervisorni.value = "";
						
			$.ajax({
				url: urlas,
				method: "POST",
				data: { usuarioSupervisor: formularionaac.usuariosupervisorni.value, claveSupervisor: p210.value, tipoDocumento: $( "#tipodocumentoclientni" ).val(), documento: $( "#documentoni" ).val(), tipoDocumentoTitular: $( "#tipodocumentoclientnbi" ).val(), documentoTitular: $( "#documentonbi" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_13').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var tokenR = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#tokenasi').val(tokenR);
						$('#dialogautorizacionadicional').dialog('destroy').remove();
						guardarNuevoClienteUC();
						return;
					}
					else
					{
						if(dataresponse.indexOf('<?php echo translate('Msg_Supervisor_Not_OK',$GLOBALS['lang']);?>') != -1)
						{
							$('#usuariosupervisorni').focus();
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
						}
						else 
						{
							$('#usuariosupervisorni').focus();
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
						}					
					}
					
				},
				error: function(request, errorcode, errortext){
					$('#usuariosupervisorni').focus();
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_13').hide();
				}
			});
		}
    </script>

	<script type="text/javascript">
		function guardarNuevoClienteUC()
		{
			if($('#validarclienteni').is(":checked"))
			{
				var urlvc = "./acciones/validarcelularcliente.php";
				$('#img_loader_12').show();
				
				$.ajax({
					url: urlvc,
					method: "POST",
					data: { tokenVCC: $("#tokenvcci").val(), tipoDocumento: $("#tipodocumentoclientni").val(), documento: $("#documentoni").val(), prefijoTelefono: $( "#prefijotelefonoi" ).val(), nroTelefono: $( "#nrotelefonoi" ).val(), tipoTelefono: $( "#tipotelefonoi" ).val(), tipoDocumentoTitular: $( "#tipodocumentoclientnbi" ).val(), documentoTitular: $( "#documentonbi" ).val() },
					success: function(dataresponse, statustext, response){
						$('#img_loader_12').hide();
						
						if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
						{
							window.location.replace("./login.php?result_ok=3");
						}
						
						if(dataresponse.indexOf('<?php echo translate('Msg_Validation_Mobile_Client_OK',$GLOBALS['lang']); ?>') != -1)
						{
							var tokenR = dataresponse.substring(dataresponse.indexOf('=::=::=::')+9, dataresponse.indexOf('=:=:=:'));
							dataresponse = dataresponse.replace("<?php echo translate('Msg_Validation_Mobile_Client_OK',$GLOBALS['lang']); ?>=::=::=::","");
							dataresponse = dataresponse.replace(tokenR+"=:=:=:","");
							
							$('#tokenvcci').val(tokenR);
							var tagvcc = $("<div id='dialogvalidacioncelularcliente'></div>");
							
							tagvcc.html(dataresponse).dialog({
							  show: "blind",
							  hide: "explode",
							  height: "auto",
							  width: "auto",					  
							  modal: true, 
							  title: "<?php echo translate('Lbl_Validation_Mobile_Client',$GLOBALS['lang']);?>",
							  autoResize:true,
									close: function(){
											tagvcc.dialog('destroy').remove()
									}
							}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
							tagvcc.dialog('open');
						}
						else if(dataresponse.indexOf('<?php echo translate('Msg_Only_Mobile_Phones_Can_Be_Validated',$GLOBALS['lang']); ?>') != -1)
						{
							confirmar_accion_validar_cliente("<?php echo translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']);?>", "<?php echo translate('Msg_Be_Sure_To_Register_The_Client_Without_Validating_The_Phone',$GLOBALS['lang']);?>", 36);
						}
						else if(dataresponse.indexOf('<?php echo translate('Msg_Mobile_Phones_Not_Validated',$GLOBALS['lang']); ?>') != -1) 
						{
							confirmar_accion_validar_cliente("<?php echo translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']);?>", "<?php echo translate('Msg_Be_Sure_To_Register_The_Client_Without_Validating_The_Phone',$GLOBALS['lang']);?>", 36);
						}
						else if(dataresponse.indexOf('<?php echo translate('Msg_Validation_Mobile_Is_Not_Necessary',$GLOBALS['lang']); ?>') != -1) 
						{
							guardarNuevoClienteUC2();
						}
						else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
							
					},
					error: function(request, errorcode, errortext){
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
						$('#img_loader_12').hide();
						return;
					}
				});				
			}
			else
			{
				confirmar_accion_validar_cliente("<?php echo translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']);?>", "<?php echo translate('Msg_Be_Sure_To_Register_The_Client_Without_Validating_The_Phone',$GLOBALS['lang']);?>", 36);
			}		
		}	
	</script>
	
	<script type="text/javascript">
		function verificarValidacionSMSAltaCliente(formulariovsms)
		{
			if($('#codigovalidsmsi').val().length == 0)
			{
				$(function() {
					$('#codigovalidsmsi').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$('#codigovalidsmsi').focus();
				return;
			}
			else 
			{
				$(function() {
					$('#codigovalidsmsi').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$('#codigovalidsmsi').tooltip('destroy');
			}

			var urlavcsms = "./acciones/verificarcodigosmsregistrocliente.php";
			$('#img_loader_14').show();
				 									
			$.ajax({
				url: urlavcsms,
				method: "POST",
				data: { codigo: $('#codigovalidsmsi').val(), token: $( "#tokenvcci" ).val(), tipoDocumento: $( "#tipodocumentoclientni" ).val(), documento: $( "#documentoni" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_14').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_SMS_Code_Validated_OK',$GLOBALS['lang']);?>') != -1)
					{
						$('#dialogvalidacioncelularcliente').dialog('destroy').remove();
						guardarNuevoClienteUC2();
					}
					else
					{
						$('#codigovalidsmsi').focus();
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);					
					}
					
				},
				error: function(request, errorcode, errortext){
					$('#codigovalidsmsi').focus();
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_14').hide();
				}
			});
		}
    </script>	

	<script type="text/javascript">
		function guardarNuevoClienteUC2()
		{
			if($('#validarstatuscreditclienteni').is(":checked"))
			{
				var urlvecc = "./acciones/validarestadocrediticiocliente.php";
				$('#img_loader_12').show();
				
				$.ajax({
					url: urlvecc,
					method: "POST",
					data: { tokenVECC: $("#tokenvecci").val(), tipoDocumento: $("#tipodocumentoclientni").val(), documento: $("#documentoni").val(), cuitCuil: $( "#cuitcuilclientni" ).val(), tipoDocumentoTitular: $( "#tipodocumentoclientnbi" ).val(), documentoTitular: $( "#documentonbi" ).val(), genero: $('#generoclientni').val(), motivo: 37 },
					success: function(dataresponse, statustext, response){
						$('#img_loader_12').hide();
						
						if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
						{
							window.location.replace("./login.php?result_ok=3");
						}
						
						if(dataresponse.indexOf('<?php echo translate('Msg_Validation_Credit_Status_Client_OK',$GLOBALS['lang']); ?>') != -1)
						{
							var tokenR = dataresponse.substring(dataresponse.indexOf('=::=::=::')+9, dataresponse.indexOf('=:=:=:'));
							dataresponse = dataresponse.replace("<?php echo translate('Msg_Validation_Credit_Status_Client_OK',$GLOBALS['lang']); ?>=::=::=::","");
							dataresponse = dataresponse.replace(tokenR+"=:=:=:","");
							
							$('#tokenvecci').val(tokenR);
							var tagvcc = $("<div id='dialogvalidacionestadocrediticiocliente'></div>");
							
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
							
							tagvcc.dialog('open');
						}
						else if(dataresponse.indexOf('<?php echo translate('Msg_Credit_Status_Client_Not_Validated',$GLOBALS['lang']); ?>') != -1) 
						{
							confirmar_accion_validar_cliente("<?php echo translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']);?>", "<?php echo translate('Msg_Be_Sure_To_Register_The_Client_Without_Validating_Credit_Status',$GLOBALS['lang']);?>", 37);
						}
						else if(dataresponse.indexOf('<?php echo translate('Msg_Validation_Credit_Status_Client_Is_Not_Necessary',$GLOBALS['lang']); ?>') != -1) 
						{
							guardarNuevoClienteFinal();
						}
						else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
							
					},
					error: function(request, errorcode, errortext){
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
						$('#img_loader_12').hide();
						return;
					}
				});
			}
			else
			{
				confirmar_accion_validar_cliente("<?php echo translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']);?>", "<?php echo translate('Msg_Be_Sure_To_Register_The_Client_Without_Validating_Credit_Status',$GLOBALS['lang']);?>", 37);
			}
		}	
	</script>

	<script type="text/javascript">
		function validar_cliente_supervisor(motivo)
		{
			var urlvcs = "./acciones/validacionclientesupervisor.php";
			$('#img_loader_12').show();
									
			$.ajax({
				url: urlvcs,
				method: "POST",
				data: { motivo: motivo, tipoDocumento: $("#tipodocumentoclientni").val(), documento: $("#documentoni").val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_12').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_It_Is_Not_Necessary_To_Authorize',$GLOBALS['lang']); ?>') != -1)
					{
						if(motivo != 37) guardarNuevoClienteUC2();
						else guardarNuevoClienteFinal();
					}
					else
					{
						if(dataresponse.indexOf('<?php echo translate('Msg_Must_Authorize_Client_Registration',$GLOBALS['lang']); ?>') != -1)
						{
							dataresponse = dataresponse.replace("<?php echo translate('Msg_Must_Authorize_Client_Registration',$GLOBALS['lang']); ?>","");
							var tagarc = $("<div id='dialogautorizacionregistrocliente'></div>");
							
							tagarc.html(dataresponse).dialog({
							  show: "blind",
							  hide: "explode",
							  height: "auto",
							  width: "auto",					  
							  modal: true, 
							  title: "<?php echo translate('Lbl_Authorize_Client_Registration',$GLOBALS['lang']);?>",
							  autoResize:true,
									close: function(){
											tagarc.dialog('destroy').remove()
									}
							}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
							tagarc.dialog('open');
						}
						else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
					}
						
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_12').hide();
				}
			});			
		}	
	</script>

	<script type="text/javascript">
		function guardarAutorizacionSupervisorRegistroCliente(formularionacr, motivo)
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
			
			var urlasrc = "./acciones/verificarcredencialessupervisorregistrocliente.php";
			$('#img_loader_13').show();
			
			
			var p211 = document.createElement("input");
		 			
			formularionacr.appendChild(p211);
			p211.name = "p210";
			p211.type = "hidden";
			
			p211.value = hex_sha512(formularionacr.passwordsupervisorn2i.value);
			
			if(formularionacr.passwordsupervisorn2i.value == "") p211.value = "";
			formularionacr.passwordsupervisorn2i.value = "";
						
			$.ajax({
				url: urlasrc,
				method: "POST",
				data: { motivo: motivo, usuarioSupervisor: formularionacr.usuariosupervisorn2i.value, claveSupervisor: p211.value, tipoDocumento: $( "#tipodocumentoclientni" ).val(), documento: $( "#documentoni" ).val(), tokenECC2: '' },
				success: function(dataresponse, statustext, response){
					$('#img_loader_13').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']);?>') != -1)
					{
						if(motivo != 37) 
						{
							$('#dialogautorizacionregistrocliente').dialog('destroy').remove();
							guardarNuevoClienteUC2();
						}
						else 
						{
							$('#dialogautorizacionregistrocliente').dialog('destroy').remove();
							guardarNuevoClienteFinal();
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
			
			var urlasrc2 = "./acciones/verificarcredencialessupervisorregistrocliente.php";
			$('#img_loader_13').show();
			
			
			var p221 = document.createElement("input");
		 			
			formulariocefc.appendChild(p221);
			p221.name = "p221";
			p221.type = "hidden";
			
			p221.value = hex_sha512(formulariocefc.passwordsupervisorn3i.value);
			
			if(formulariocefc.passwordsupervisorn3i.value == "") p221.value = "";
			formulariocefc.passwordsupervisorn3i.value = "";
			
			var tipoDocumentoCEA2 = null;
			var documentoCEA2 = null;
			
			if($( "#tipoclientni" ).val() != "<?php echo translate('Lbl_Type_Client_Headline',$GLOBALS['lang']);?>")
			{
				tipoDocumentoCEA2 = $( "#tipodocumentoclientnbi" ).val();
				documentoCEA2 = $( "#documentonbi" ).val();
			}
			else
			{
				tipoDocumentoCEA2 = $( "#tipodocumentoclientni" ).val();
				documentoCEA2 = $( "#documentoni" ).val();
			}			
						
			$.ajax({
				url: urlasrc2,
				method: "POST",
				data: { motivo: 38, usuarioSupervisor: formulariocefc.usuariosupervisorn3i.value, claveSupervisor: p221.value, tipoDocumento: tipoDocumentoCEA2, documento: documentoCEA2, tokenECC2: $('#tokenvecci').val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_13').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']);?>') != -1)
					{
						if(motivo != 37) 
						{
							$('#dialogvalidacionestadocrediticiocliente').dialog('destroy').remove();
							guardarNuevoClienteUC2();
						}
						else 
						{
							$('#dialogvalidacionestadocrediticiocliente').dialog('destroy').remove();
							guardarNuevoClienteFinal();
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
		function guardarSinSupervisorEstadoFinancieroCliente(motivo)
		{				
			var tipoDocumentoCEA = null;
			var documentoCEA = null;
			
			if($( "#tipoclientni" ).val() != "<?php echo translate('Lbl_Type_Client_Headline',$GLOBALS['lang']);?>")
			{
				tipoDocumentoCEA = $( "#tipodocumentoclientnbi" ).val();
				documentoCEA = $( "#documentonbi" ).val();
			}
			else
			{
				tipoDocumentoCEA = $( "#tipodocumentoclientni" ).val();
				documentoCEA = $( "#documentoni" ).val();
			}			
			
			var urlasrc3 = "./acciones/grabarregistroclientesinsupervisorestadofinanciero.php";
			$('#img_loader_13').show();
			
			$.ajax({
				url: urlasrc3,
				method: "POST",
				data: { motivo: motivo, tipoDocumento: tipoDocumentoCEA, documento: documentoCEA, tokenECC2: $('#tokenvecci').val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_13').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Not_Supervisor_OK',$GLOBALS['lang']);?>') != -1)
					{
						if(motivo != 37) 
						{
							$('#dialogvalidacionestadocrediticiocliente').dialog('destroy').remove();
							guardarNuevoClienteUC2();
						}
						else 
						{
							$('#dialogvalidacionestadocrediticiocliente').dialog('destroy').remove();
							guardarNuevoClienteFinal();
						}
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);					
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_13').hide();
				}
			});
		}
    </script>	
	
	<script type="text/javascript">
		function guardarNuevoClienteFinal()
		{			
			var urlnc = "./acciones/guardarnuevocliente.php";
			$('#img_loader_12').show();
									
			$.ajax({
				url: urlnc,
				method: "POST",
				data: { tipoDocumentoTitular: $( "#tipodocumentoclientnbi" ).val(), documentoTitular: $( "#documentonbi" ).val(), tipoDocumento: $("#tipodocumentoclientni").val(), documento: $("#documentoni").val(), nombre: $("#nombreclientni").val(), apellido: $("#apellidoclientni").val(), fechaNacimiento: $("#fechanacimientoclientni").val(), cuitCuil: $("#cuitcuilclientni").val(), email: $("#emailclientni").val(), montoMaximo: (($("#montomaximoclientni").val().replace(/,/g,""))*100.00), perfilCredito: $("#perfilcreditoclientni").val(), observaciones: $("#observacionclientni").val(), calle: $( "#calleni" ).val(), nroCalle: $( "#nrocalleni" ).val(), provincia: $( "#domprovinciani" ).val(), localidad: $( "#domlocalidadni" ).val(), departamento: $( "#domdepartamentoni" ).val(), piso: $( "#domfloorni" ).val(), codigoPostal: $( "#zipcodeni" ).val(), entreCalle1: $( "#entrecalle1ni" ).val(), entreCalle2: $( "#entrecalle2ni" ).val(), prefijoTelefono: $( "#prefijotelefonoi" ).val(), nroTelefono: $( "#nrotelefonoi" ).val(), tipoTelefono: $( "#tipotelefonoi" ).val(), tokenA: $('#tokenasi').val(), genero: $('#generoclientni').val(), tokenVC: $( "#tokenvcci" ).val(), tokenVECC: $('#tokenvecci').val()},
				success: function(dataresponse, statustext, response){
					$('#img_loader_12').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_New_Client_OK',$GLOBALS['lang']);?>') != -1)
					{
						$('#dialognewclient').dialog('destroy').remove();
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",dataresponse);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_12').hide();
				}
			});
		}
    </script>	
	
	<script type="text/javascript">
		function modificarCliente(plancredito, nombre)
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
		function guardarModificacionCliente(formulariod, plancredito)
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
						$('#tableadminclientt').bootstrapTable('load',JSON.parse(datTable));
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
		function borrar_cliente(plancredito)
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
						
						$('#tableadminclientt').bootstrapTable('load',JSON.parse(datTable));
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
										
										borrar_cliente(plancredito);                                                      
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
			<h3 class="panel-title"><?php echo translate('Lbl_Clients',$GLOBALS['lang']); ?></h3>
		  </div>
		  <div id="apDiv1" class="panel-body">
			<div id="toolbar" style="margin-left:-98px; margin-top:-1px;">
				<button type="button" class="btn" data-toggle="tooltip" data-placement="top" onclick="nuevoCliente();" title="<?php echo translate('Lbl_New_Client',$GLOBALS['lang']);?>" ><i class="fas fa-id-card-alt"></i></button>
			</div>
			<div id="img_loader"></div>
			<div id="tablaadminclient" class="table-responsive">
				<table id="tableadminclientt" data-classes="table table-hover table-condensed"
				   data-striped="true" data-pagination="true" data-show-export="true" data-export-options='{"fileName": "<?php echo translate('File_Clients',$GLOBALS['lang']); ?>"}'
				   data-export-types="['excel','pdf','csv','txt']"
				   data-search="true" data-search-align="left" data-toolbar="#toolbar" data-toolbar-align="right">
					<thead>
						<tr>
							<th class="col-xs-2 text-center" data-field="tipodocumento" data-sortable="true"><?php echo translate('Lbl_Type_Document_Client',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="documento" data-sortable="true"><?php echo translate('Lbl_Document_Client',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="nombre" data-sortable="true"><?php echo translate('Lbl_Name_Client',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="apellido" data-sortable="true"><?php echo translate('Lbl_Surname_Client',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="estado" data-sortable="true"><?php echo translate('Lbl_State_Client',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="tipocuenta" data-sortable="true"><?php echo translate('Lbl_Type_Account_Client',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="acciones"><?php echo translate('Lbl_Actions_Credit_Plan',$GLOBALS['lang']);?></th>
						</tr>						
					</thead>
					<tbody>
						<?php
							if ($stmt = $mysqli->prepare("SELECT c.id, td.nombre, c.documento, c.nombres, c.apellidos, c.estado, CASE WHEN c.id_titular IS NOT NULL THEN '".translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang'])."' ELSE '".translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang'])."' END AS tipoCuenta FROM finan_cli.cliente c, finan_cli.tipo_documento td  WHERE c.tipo_documento = td.id ORDER BY c.documento")) 
							{
								$stmt->execute();    // Ejecuta la consulta preparada.
								$stmt->store_result();
						 
								// Obtiene las variables del resultado.
								$stmt->bind_result($id_client, $type_document_client, $document_client, $name_client, $surname_client, $state_client, $type_account_client);
								
								while($stmt->fetch())
								{		
									echo '<tr>';
									echo '<td>'.$type_document_client.'</td>';
									echo '<td>'.$document_client.'</td>';
									echo '<td>'.$name_client.'</td>';
									echo '<td>'.$surname_client.'</td>';
									echo '<td>'.$state_client.'</td>';
									echo '<td>'.$type_account_client.'</td>';
									
									if($state_client == translate('State_User',$GLOBALS['lang'])) echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Deactivate_Client',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Deactivate_Client',$GLOBALS['lang']).'\',\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-times"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Client',$GLOBALS['lang']).'" onclick="modificarCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-cog"></i></button></td>';
									else echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Activate_Client',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Activate_Client',$GLOBALS['lang']).'\',\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-check"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Client',$GLOBALS['lang']).'" onclick="modificarCliente(\''.$id_client.'\',\''.$document_client.'\')"><i class="fas fa-user-cog"></i></button></td>';
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
			$('#tableadminclientt').bootstrapTable({locale:'es-AR'});
			var editorEF = null;
		});
	</script>		
</body>
</html>