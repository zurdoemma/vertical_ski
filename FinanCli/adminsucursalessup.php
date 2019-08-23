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
	<title><?php echo translate('Lbl_Tender',$GLOBALS['lang']); ?></title>
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
		function nuevaSucursal()
		{
			var urlnt = "./acciones/nuevasucursalsup.php";
			var tagnt = $("<div id='dialognewtender'></div>");
			$('#img_loader_5').show();
			
			$.ajax({
				url: urlnt,
				method: "POST",
				data: {},
				success: function(dataresponse, statustext, response){
					$('#img_loader_5').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
										
					tagnt.html(dataresponse).dialog({
					  show: "blind",
					  hide: "explode",
					  height: "auto",
					  width: "auto",					  
					  modal: true, 
					  title: "<?php echo translate('Lbl_New_Tender',$GLOBALS['lang']);?>",
					  autoResize:true,
							close: function(){
									tagnt.dialog('destroy').remove()
							}
					}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
					tagnt.dialog('open');
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_5').hide();
				}
			});	
		}
    </script>
			
	<script type="text/javascript">
		function modificarSucursal(sucursal, nombre)
		{
			var urla = "./acciones/modificarsucursalsup.php";
			var tag = $("<div id='dialogmodifytender'></div>");
			$('#img_loader').show();
			
			$.ajax({
				url: urla,
				method: "POST",
				data: { idSucursal: sucursal },
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
					  title: "<?php echo translate('Msg_Edit_Tender',$GLOBALS['lang']);?>: "+nombre,
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
		function guardarModificacionSucursal(formulariod, sucursal, idDomicilio)
		{
			if($( "#nombretenderi" ).val().length == 0)
			{
				$(function() {
					$( "#nombretenderi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#nombretenderi" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#nombretenderi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#nombretenderi" ).tooltip('destroy');
			}
			
			if($( "#codigotenderi" ).val().length == 0)
			{
				$('#codigotenderi').prop('title', '<?php echo translate('Msg_A_Code_Tender_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#codigotenderi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#codigotenderi" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#codigotenderi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#codigotenderi" ).tooltip('destroy');
			}			
			
			if($( "#codigotenderi" ).val().length != 0)
			{			
				if (isNaN($( "#codigotenderi" ).val()) || $( "#codigotenderi" ).val() % 1 != 0)
				{
					$('#codigotenderi').prop('title', '<?php echo translate('Msg_A_Code_Tender_Must_Enter_A_Whole',$GLOBALS['lang']);?>');
					$(function() {
						$( "#codigotenderi" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#codigotenderi" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#codigotenderi" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#codigotenderi" ).tooltip('destroy');
				}
			}			
						
			if($( "#emailtenderi" ).val().length != 0)
			{						
				if(!caracteresCorreoValido($( "#emailtenderi" ).val()))
				{
					$(function() {
						$( "#emailtenderi" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#emailtenderi" ).focus();
					return;				
				}
				else
				{
					$(function() {
						$( "#emailtenderi" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});				
					$( "#emailtenderi" ).tooltip('destroy');				
				}
			}

			if(!$('#mostrarDomicilioCarga').is(':visible'))
			{
				$('#mostrarDomicilioCarga').show();
				$('#btnCargaDomicilioU').prop('title', '<?php echo translate('Lbl_Hide_New_Home_Address_User',$GLOBALS['lang']);?>');
				$('#btnCargaDomicilioU').html('<i class="fa fa-eye-slash"></i>');
			}
			
			if($( "#callei" ).val().length == 0)
			{
				$(function() {
					$( "#callei" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#callei" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#callei" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#callei" ).tooltip('destroy');
			}
			
			if($( "#nrocallei" ).val().length == 0)
			{
				$('#nrocallei').prop('title', '<?php echo translate('Msg_A_Street_Number_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#nrocallei" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#nrocallei" ).focus();
				return;
			}
			else 
			{
				if (isNaN($( "#nrocallei" ).val()) || $( "#nrocallei" ).val() % 1 != 0)
				{
					$('#nrocallei').prop('title', '<?php echo translate('Msg_A_Street_Number_Must_Enter_A_Whole',$GLOBALS['lang']);?>');
					$(function() {
						$( "#nrocallei" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#nrocallei" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#nrocallei" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#nrocallei" ).tooltip('destroy');
				}
			}
			
			if($( "#domlocalidadi" ).val().length == 0)
			{
				$(function() {
					$( "#domlocalidadi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#domlocalidadi" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#domlocalidadi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});					
				$( "#domlocalidadi" ).tooltip('destroy');		
			}
			
			if($( "#domfloori" ).val().length != 0)
			{
				if (isNaN($( "#domfloori" ).val()) || $( "#domfloori" ).val() % 1 != 0)
				{
					$(function() {
						$( "#domfloori" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#domfloori" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#domfloori" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#domfloori" ).tooltip('destroy');
				}					
			}			
			
			var urlgmu = "./acciones/guardarmodificacionsucursalsup.php";
			$('#img_loader_9').show();
			
			$.ajax({
				url: urlgmu,
				method: "POST",
				data: { idSucursal: sucursal, nombre: $( "#nombretenderi" ).val(), codigo: $( "#codigotenderi" ).val(), email: $( "#emailtenderi" ).val(), cadena: $( "#cadenatenderi" ).val(), idDomicilio: idDomicilio, calle: $( "#callei" ).val(), nroCalle: $( "#nrocallei" ).val(), provincia: $( "#domprovinciai" ).val(), localidad: $( "#domlocalidadi" ).val(), departamento: $( "#domdepartamentoi" ).val(), piso: $( "#domfloori" ).val(), codigoPostal: $( "#zipcodei" ).val(), entreCalle1: $( "#entrecalle1i" ).val(), entreCalle2: $( "#entrecalle2i" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_9').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Modify_Tender_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialogmodifytender').dialog('close');
						$('#tableadmintenderst').bootstrapTable('load',JSON.parse(datTable));
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_9').hide();
				}
			});				
			
			
		}			
	</script>
	
	<script type="text/javascript">
		function guardarNuevaSucursal(formulariod)
		{
			if($( "#nombretenderni" ).val().length == 0)
			{
				$(function() {
					$( "#nombretenderni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#nombretenderni" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#nombretenderni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#nombretenderni" ).tooltip('destroy');
			}
			
			if($( "#codigotenderni" ).val().length == 0)
			{
				$('#codigotenderni').prop('title', '<?php echo translate('Msg_A_Code_Tender_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#codigotenderni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#codigotenderni" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#codigotenderni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#codigotenderni" ).tooltip('destroy');
			}			
			
			if($( "#codigotenderni" ).val().length != 0)
			{			
				if (isNaN($( "#codigotenderni" ).val()) || $( "#codigotenderni" ).val() % 1 != 0)
				{
					$('#codigotenderni').prop('title', '<?php echo translate('Msg_A_Code_Tender_Must_Enter_A_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#codigotenderni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#codigotenderni" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#codigotenderni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#codigotenderni" ).tooltip('destroy');
				}
			}			
						
			if($( "#emailtenderni" ).val().length != 0)
			{						
				if(!caracteresCorreoValido($( "#emailtenderni" ).val()))
				{
					$(function() {
						$( "#emailtenderni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#emailtenderni" ).focus();
					return;				
				}
				else
				{
					$(function() {
						$( "#emailtenderni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});				
					$( "#emailtenderni" ).tooltip('destroy');				
				}
			}

			if(!$('#mostrarDomicilioCargaN').is(':visible'))
			{
				$('#mostrarDomicilioCargaN').show();
				$('#btnCargaDomicilioUN').prop('title', '<?php echo translate('Lbl_Hide_New_Home_Address_User',$GLOBALS['lang']);?>');
				$('#btnCargaDomicilioUN').html('<i class="fa fa-eye-slash"></i>');
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
					$( "#nrocallei" ).tooltip({
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
		
			
			var urlggnu = "./acciones/guardarnuevasucursalsup.php";
			$('#img_loader_5').show();
			
			$.ajax({
				url: urlggnu,
				method: "POST",
				data: { nombre: $( "#nombretenderni" ).val(), codigo: $( "#codigotenderni" ).val(), email: $( "#emailtenderni" ).val(), cadena: $( "#cadenatenderni" ).val(), calle: $( "#calleni" ).val(), nroCalle: $( "#nrocalleni" ).val(), provincia: $( "#domprovinciani" ).val(), localidad: $( "#domlocalidadni" ).val(), departamento: $( "#domdepartamentoni" ).val(), piso: $( "#domfloorni" ).val(), codigoPostal: $( "#zipcodeni" ).val(), entreCalle1: $( "#entrecalle1ni" ).val(), entreCalle2: $( "#entrecalle2ni" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_5').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_New_Tender_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialognewtender').dialog('close');
						$('#tableadmintenderst').bootstrapTable('load',JSON.parse(datTable));
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_5').hide();
				}
			});
		}			
	</script>
	
	<script type="text/javascript">
		function verDomicilioNU()
		{
			if(!$('#mostrarDomicilioCargaN').is(':visible'))
			{
				$('#mostrarDomicilioCargaN').show();
				$('#btnCargaDomicilioUN').prop('title', '<?php echo translate('Lbl_Hide_New_Home_Address_User',$GLOBALS['lang']);?>');
				$('#btnCargaDomicilioUN').html('<i class="fa fa-eye-slash"></i>');
				$('#calleni').focus();
			}
			else
			{
				$('#mostrarDomicilioCargaN').hide();
				$('#btnCargaDomicilioUN').prop('title', '<?php echo translate('Lbl_New_Home_Address_User',$GLOBALS['lang']);?>');
				$('#btnCargaDomicilioUN').html('<i class="fa fa-eye"></i>');
				$('#nombretenderni').focus();	
			}
		}
    </script>

	<script type="text/javascript">
		function verDomicilioU()
		{
			if(!$('#mostrarDomicilioCarga').is(':visible'))
			{
				$('#mostrarDomicilioCarga').show();
				$('#btnCargaDomicilioU').prop('title', '<?php echo translate('Lbl_Hide_New_Home_Address_User',$GLOBALS['lang']);?>');
				$('#btnCargaDomicilioU').html('<i class="fa fa-eye-slash"></i>');
				$('#callei').focus();
			}
			else
			{
				$('#mostrarDomicilioCarga').hide();
				$('#btnCargaDomicilioU').prop('title', '<?php echo translate('Lbl_New_Home_Address_User',$GLOBALS['lang']);?>');
				$('#btnCargaDomicilioU').html('<i class="fa fa-eye"></i>');
				$('#nombretenderi').focus();	
			}
		}
    </script>	
	
	<script type="text/javascript">
		function borrar_sucursal(sucursal)
		{
			var urlrdu = "./acciones/borrarsucursalsup.php";
			$('#img_loader').show();
			
			$.ajax({
				url: urlrdu,
				method: "POST",
				data: { idSucursal: sucursal },
				success: function(dataresponse, statustext, response){
					$('#img_loader').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Remove_Tender_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#tableadmintenderst').bootstrapTable('load',JSON.parse(datTable));
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
		function confirmar_accion(titulo, mensaje, sucursal, nombresucu)
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
										
										borrar_sucursal(sucursal);                                                      
								},
								"<?php echo translate('Lbl_Button_NO',$GLOBALS['lang']);?>": function () {
										$("#confirmDialog").dialog('close');
										$('#img_loader').hide();
										return;
								}
						}
				}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
				$( "#confirmDialog" ).html("<div id='confirmacionAccion'>"+mensaje+nombresucu+"?</div>");
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
			<h3 class="panel-title"><?php echo translate('Lbl_Tender',$GLOBALS['lang']); ?></h3>
		  </div>
		  <div id="apDiv1" class="panel-body">
			<div id="toolbar" style="margin-left:-98px; margin-top:-1px;">
				<button type="button" class="btn" data-toggle="tooltip" data-placement="top" onclick="nuevaSucursal();" title="<?php echo translate('Lbl_New_Tender',$GLOBALS['lang']);?>" ><i class="far fa-hospital"></i></button>
			</div>
			<div id="img_loader"></div>
			<div id="tablaadmintenders" class="table-responsive">
				<table id="tableadmintenderst" data-classes="table table-hover table-condensed"
				   data-striped="true" data-pagination="true" data-show-export="true" data-export-options='{"fileName": "<?php echo translate('Lbl_Tender',$GLOBALS['lang']); ?>"}'
				   data-export-types="['excel','pdf','csv','txt']"
				   data-search="true" data-search-align="left" data-toolbar="#toolbar" data-toolbar-align="right">
					<thead>
						<tr>
							<th class="col-xs-1 text-center" data-field="codigo" data-sortable="true"><?php echo translate('Lbl_Code_Tender',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="nombre" data-sortable="true"><?php echo translate('Lbl_Name_Tender',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="cadena" data-sortable="true"><?php echo translate('Lbl_Chain_Tender',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="acciones"><?php echo translate('Lbl_Actions_Tender',$GLOBALS['lang']);?></th>
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
															
									if ($stmt = $mysqli->prepare("SELECT s.id, s.codigo, s.nombre, c.razon_social FROM finan_cli.cadena c, finan_cli.sucursal s  WHERE c.id = s.id_cadena AND c.id = ?")) 
									{
										$stmt->bind_param('i', $id_cadena_user);
										$stmt->execute();    // Ejecuta la consulta preparada.
										$stmt->store_result();
								 
										// Obtiene las variables del resultado.
										$stmt->bind_result($id_tender, $codigo_tender, $nombre_tender, $nombre_cadena_tender);
										
										while($stmt->fetch())
										{		
											echo '<tr>';
											echo '<td>'.$codigo_tender.'</td>';
											echo '<td>'.$nombre_tender.'</td>';
											echo '<td>'.$nombre_cadena_tender.'</td>';
											
											echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Tender',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Removed_Tender',$GLOBALS['lang']).'\',\''.$id_tender.'\',\''.$nombre_tender.'\')"><i class="fas fa-trash-alt"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Tender',$GLOBALS['lang']).'" onclick="modificarSucursal(\''.$id_tender.'\',\''.$codigo_tender.'\')"><i class="fas fa-edit"></i></button></td>';
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
			$('#tableadmintenderst').bootstrapTable({locale:'es-AR'});
			  
		});
	</script>	
</body>
</html>