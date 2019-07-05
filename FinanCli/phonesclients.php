<?php
include ('./utiles/funciones.php');
require("../parametrosbasedatosfc.php");
$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
mysqli_set_charset($mysqli,"utf8");
if (!verificar_usuario($mysqli)){header('Location:./login.php');return;}
if (!verificar_permisos_usuario()){header('Location:./sinautorizacion.php?activauto=1');return;}
if(empty(htmlspecialchars($_GET['idCliente'], ENT_QUOTES, 'UTF-8'))){header('Location:./sinautorizacion.php?activauto=1');return;}
include("./menu/menu.php");

if($stmt2 = $mysqli->prepare("SELECT valor FROM finan_cli.parametros WHERE nombre = 'cantidad_telefonos_x_usuario_cliente'"))
{
	$stmt2->execute();    
	$stmt2->store_result();
	$stmt2->bind_result($cantidad_telefonos_db);
	$stmt2->fetch();

	$stmt2->free_result();
	$stmt2->close();	
}

if($stmt3 = $mysqli->prepare("SELECT td.nombre, c.documento FROM finan_cli.cliente c, finan_cli.tipo_documento td WHERE c.id = ? AND td.id = c.tipo_documento"))
{
	$stmt3->bind_param('i', $_GET['idCliente']);
	$stmt3->execute();    
	$stmt3->store_result();
	$stmt3->bind_result($nom_tipo_documento_cliente_db, $documento_cliente_db);
	$stmt3->fetch();
	
	$stmt3->free_result();
	$stmt3->close();
}
?>
<!doctype html>
<html lang="es-es">
<head>
	<meta charset="UTF-8">
	
	<link rel="shortcut icon" href="./images/iconoFinanCli.png" >
	<title><?php echo translate('Lbl_Phone_Client',$GLOBALS['lang']); ?></title>
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.op2.css" >
	<link rel="stylesheet" type="text/css" href="./css/bootstrap-theme.min.op2.css" >	
	<link rel="stylesheet" type="text/css" href="./css/bootstrap-table.min.op2.css" >
	<link rel="stylesheet" href="./css/fontawesome.min.css">
	<link rel="stylesheet" href="./css/all.css">
	<link rel="stylesheet" type="text/css" href="./css/jquery-ui.css">
	
	<script type="text/javascript" src="./js/jquery.js"></script>
	<script type="text/JavaScript" src="./js/bootstrap.min.op2.js" ></script>	
	<script type="text/javascript" src="./js/jquery-ui.js"></script>	
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
		function guardarNuevoTelefono(formulariod)
		{			
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
			
			if($('#validarclienteni').is(":checked") && $('#tipotelefonoi').val() == 1)
			{			
				var urlvc = "./acciones/validarcelularclientet.php";
				$('#img_loader_15').show();
				
				$.ajax({
					url: urlvc,
					method: "POST",
					data: { tokenVCC: $("#tokenvcci").val(), idCliente: <?php echo $_GET['idCliente'] ?>, prefijoTelefono: $( "#prefijotelefonoi" ).val(), nroTelefono: $( "#nrotelefonoi" ).val(), tipoTelefono: $( "#tipotelefonoi" ).val() },
					success: function(dataresponse, statustext, response){
						$('#img_loader_15').hide();
						
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
							confirmar_accion_validar_cliente("<?php echo translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']);?>", "<?php echo translate('Msg_Be_Sure_To_Register_The_Phone_Without_Validating',$GLOBALS['lang']);?>", 54);
						}
						else if(dataresponse.indexOf('<?php echo translate('Msg_Mobile_Phones_Not_Validated',$GLOBALS['lang']); ?>') != -1) 
						{
							confirmar_accion_validar_cliente("<?php echo translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']);?>", "<?php echo translate('Msg_Be_Sure_To_Register_The_Phone_Without_Validating',$GLOBALS['lang']);?>", 54);
						}
						else if(dataresponse.indexOf('<?php echo translate('Msg_Validation_Mobile_Is_Not_Necessary',$GLOBALS['lang']); ?>') != -1) 
						{
							guardarNuevoTelefonoFinal();
						}
						else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
							
					},
					error: function(request, errorcode, errortext){
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
						$('#img_loader_15').hide();
						return;
					}
				});	
			}
			else
			{
				if($('#tipotelefonoi').val() == 1) confirmar_accion_validar_cliente("<?php echo translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']);?>", "<?php echo translate('Msg_Be_Sure_To_Register_The_Phone_Without_Validating',$GLOBALS['lang']);?>", 54);
				else confirmar_accion_validar_cliente("<?php echo translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']);?>", "<?php echo translate('Msg_Be_Sure_To_Register_The_Phone_Without_Validating',$GLOBALS['lang']);?>", 55);
			}
		}			
	</script>
	
	<script type="text/javascript">
		function verificarValidacionSMSAltaTelefono(formulariovsms)
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

			var urlavcsms = "./acciones/verificarcodigosmsregistrotelefono.php";
			$('#img_loader_15').show();
				 									
			$.ajax({
				url: urlavcsms,
				method: "POST",
				data: { codigo: $('#codigovalidsmsi').val(), token: $( "#tokenvcci" ).val(), idCliente: <?php echo $_GET['idCliente'] ?>, prefijoTelefono: $( "#prefijotelefonoi" ).val(), nroTelefono: $( "#nrotelefonoi" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_15').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_SMS_Code_Validated_OK',$GLOBALS['lang']);?>') != -1)
					{
						$('#dialogvalidacioncelularcliente').dialog('destroy').remove();
						guardarNuevoTelefonoFinal();
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
					$('#img_loader_15').hide();
				}
			});
		}
    </script>	
	
	<script type="text/javascript">
		function guardarNuevoTelefonoFinal()
		{				
			var urlgnd = "./acciones/guardarnuevotelefonoc.php";
			$('#img_loader_15').show();
			
			$.ajax({
				url: urlgnd,
				method: "POST",
				data: { idCliente: "<?php echo $_GET['idCliente']; ?>", prefijoTelefono: $( "#prefijotelefonoi" ).val(), nroTelefono: $( "#nrotelefonoi" ).val(), tipoTelefono: $( "#tipotelefonoi" ).val(), preferido: $('#telefonopreferidoclientei').is(":checked"), token: $( "#tokenvcci" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_15').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Add_Phone_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialognewphone').dialog('close');
						$('#tableadminphonesclientst').bootstrapTable('load',JSON.parse(datTable));
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_15').hide();
				}
			});					
		}			
	</script>	
	
	<script type="text/javascript">
		function guardarModificacionTelefono(formulariod, idTelefono)
		{
			if($( "#prefijotelefonomi" ).val().length == 0)
			{
				$('#prefijotelefonomi').prop('title', '<?php echo translate('Msg_A_Pre_Number_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#prefijotelefonomi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#prefijotelefonomi" ).focus();
				return;
			}
			else 
			{
				if (isNaN($( "#prefijotelefonomi" ).val()) || $( "#prefijotelefonomi" ).val() % 1 != 0)
				{
					$('#prefijotelefonomi').prop('title', '<?php echo translate('Msg_A_Pre_Number_Must_Enter_A_Whole',$GLOBALS['lang']);?>');
					$(function() {
						$( "#prefijotelefonomi" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#prefijotelefonomi" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#prefijotelefonomi" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#prefijotelefonomi" ).tooltip('destroy');
				}
			}
			
			if($( "#nrotelefonomi" ).val().length == 0)
			{
				$('#nrotelefonomi').prop('title', '<?php echo translate('Msg_A_Number_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#nrotelefonomi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#nrotelefonomi" ).focus();
				return;
			}
			else 
			{
				if (isNaN($( "#nrotelefonomi" ).val()) || $( "#nrotelefonomi" ).val() % 1 != 0)
				{
					$('#nrotelefonomi').prop('title', '<?php echo translate('Msg_A_Number_Must_Enter_A_Whole',$GLOBALS['lang']);?>');
					$(function() {
						$( "#nrotelefonomi" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#nrotelefonomi" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#nrotelefonomi" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#nrotelefonomi" ).tooltip('destroy');
				}
			}
			
			if($('#validarclientemi').is(":checked") && $('#tipotelefonomi').val() == 1)
			{			
				var urlvcm = "./acciones/validarcelularclientetm.php";
				$('#img_loader_15').show();
				
				$.ajax({
					url: urlvcm,
					method: "POST",
					data: { idTelefono: idTelefono, tokenVCC: $("#tokenvccmi").val(), idCliente: <?php echo $_GET['idCliente'] ?>, prefijoTelefono: $( "#prefijotelefonomi" ).val(), nroTelefono: $( "#nrotelefonomi" ).val(), tipoTelefono: $( "#tipotelefonomi" ).val() },
					success: function(dataresponse, statustext, response){
						$('#img_loader_15').hide();
						
						if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
						{
							window.location.replace("./login.php?result_ok=3");
						}
						
						if(dataresponse.indexOf('<?php echo translate('Msg_Validation_Mobile_Client_OK',$GLOBALS['lang']); ?>') != -1)
						{
							var tokenR = dataresponse.substring(dataresponse.indexOf('=::=::=::')+9, dataresponse.indexOf('=:=:=:'));
							dataresponse = dataresponse.replace("<?php echo translate('Msg_Validation_Mobile_Client_OK',$GLOBALS['lang']); ?>=::=::=::","");
							dataresponse = dataresponse.replace(tokenR+"=:=:=:","");
							
							$('#tokenvccmi').val(tokenR);
							var tagvccm = $("<div id='dialogvalidacioncelularclientem'></div>");
							
							tagvccm.html(dataresponse).dialog({
							  show: "blind",
							  hide: "explode",
							  height: "auto",
							  width: "auto",					  
							  modal: true, 
							  title: "<?php echo translate('Lbl_Validation_Mobile_Client',$GLOBALS['lang']);?>",
							  autoResize:true,
									close: function(){
											tagvccm.dialog('destroy').remove()
									}
							}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
							tagvccm.dialog('open');
						}
						else if(dataresponse.indexOf('<?php echo translate('Msg_Only_Mobile_Phones_Can_Be_Validated',$GLOBALS['lang']); ?>') != -1)
						{
							confirmar_accion_validar_cliente_m("<?php echo translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']);?>", "<?php echo translate('Msg_Be_Sure_To_Modify_The_Phone_Without_Validating',$GLOBALS['lang']);?>", 57, idTelefono);
						}
						else if(dataresponse.indexOf('<?php echo translate('Msg_Mobile_Phones_Not_Validated',$GLOBALS['lang']); ?>') != -1) 
						{
							confirmar_accion_validar_cliente_m("<?php echo translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']);?>", "<?php echo translate('Msg_Be_Sure_To_Modify_The_Phone_Without_Validating',$GLOBALS['lang']);?>", 56, idTelefono);
						}
						else if(dataresponse.indexOf('<?php echo translate('Msg_Validation_Mobile_Is_Not_Necessary',$GLOBALS['lang']); ?>') != -1) 
						{
							guardarModificacionTelefonoFinal(idTelefono);
						}
						else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
							
					},
					error: function(request, errorcode, errortext){
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
						$('#img_loader_15').hide();
						return;
					}
				});	
			}
			else
			{
				if($('#tipotelefonomi').val() == 1) confirmar_accion_validar_cliente_m("<?php echo translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']);?>", "<?php echo translate('Msg_Be_Sure_To_Modify_The_Phone_Without_Validating',$GLOBALS['lang']);?>", 56, idTelefono);
				else confirmar_accion_validar_cliente_m("<?php echo translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']);?>", "<?php echo translate('Msg_Be_Sure_To_Modify_The_Phone_Without_Validating',$GLOBALS['lang']);?>", 57, idTelefono);
			}						
		}			
	</script>
	<script type="text/javascript">
		function guardarModificacionTelefonoFinal(idTelefono)
		{
			var urlgnd = "./acciones/guardarmodificaciontelefonoc.php";
			$('#img_loader_15').show();
			
			$.ajax({
				url: urlgnd,
				method: "POST",
				data: { usuario: "<?php echo $_GET['usuario']; ?>", idTelefono: idTelefono, prefijoTelefono: $( "#prefijotelefonomi" ).val(), nroTelefono: $( "#nrotelefonomi" ).val(), tipoTelefono: $( "#tipotelefonomi" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_15').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Modify_Phone_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialogmodifyphone').dialog('close');
						$('#tableadminphonesclientst').bootstrapTable('load',JSON.parse(datTable));
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_15').hide();
				}
			});				
		}			
	</script>	
	
	<script type="text/javascript">
		function verificarValidacionSMSModificacionTelefono(formulariovsmsm, idTelefono)
		{
			if($('#codigovalidsmsmi').val().length == 0)
			{
				$(function() {
					$('#codigovalidsmsmi').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$('#codigovalidsmsmi').focus();
				return;
			}
			else 
			{
				$(function() {
					$('#codigovalidsmsmi').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$('#codigovalidsmsmi').tooltip('destroy');
			}

			var urlavcsmsm = "./acciones/verificarcodigosmsregistrotelefonom.php";
			$('#img_loader_15').show();
				 									
			$.ajax({
				url: urlavcsmsm,
				method: "POST",
				data: { codigo: $('#codigovalidsmsmi').val(), token: $( "#tokenvccmi" ).val(), idTelefono: idTelefono, idCliente: <?php echo $_GET['idCliente'] ?>, prefijoTelefono: $( "#prefijotelefonomi" ).val(), nroTelefono: $( "#nrotelefonomi" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_15').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_SMS_Code_Validated_OK',$GLOBALS['lang']);?>') != -1)
					{
						$('#dialogvalidacioncelularclientem').dialog('destroy').remove();
						guardarModificacionTelefonoFinal(idTelefono);
					}
					else
					{
						$('#codigovalidsmsmi').focus();
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);					
					}
					
				},
				error: function(request, errorcode, errortext){
					$('#codigovalidsmsmi').focus();
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_15').hide();
				}
			});
		}
    </script>
	
	<script type="text/javascript">
		function nuevoTelefono(idCliente)
		{
			var urlnt = "./acciones/nuevotelefonoc.php";
			var tagnt = $("<div id='dialognewphone'></div>");
			$('#img_loader').show();
			
			$.ajax({
				url: urlnt,
				method: "POST",
				data: { idCliente: idCliente },
				success: function(dataresponse, statustext, response){
					$('#img_loader').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
										
					if(dataresponse.indexOf('<?php echo str_replace("%1",$cantidad_telefonos_db,translate('Msg_Limit_Phones_User',$GLOBALS['lang'])); ?>') != -1)
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
					}
					else
					{
						tagnt.html(dataresponse).dialog({
						  show: "blind",
						  hide: "explode",
						  height: "auto",
						  width: "auto",					  
						  modal: true, 
						  title: "<?php echo translate('Lbl_New_Phone',$GLOBALS['lang']);?>: "+"<?php echo $nom_tipo_documento_cliente_db.' - '.$documento_cliente_db;?>",
						  autoResize:true,
								close: function(){
										tagnt.dialog('destroy').remove()
								}
						}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
						
						$('#tipotelefonoi').change(function() {
							var valueAc = $('#tipotelefonoi').val();
							if(valueAc == 1) {
								$('#validartelefonocliente').show();
							}
							else
							{
								$('#validartelefonocliente').hide();
								$('#validarclienteni').prop('checked', false);
							}
							       
						});
						
						tagnt.dialog('open');
						$( "#prefijotelefonoi" ).focus();
					}
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader').hide();
				}
			});	
		}
    </script>
	
	<script type="text/javascript">
		function modificarTelefono(idCliente, idTelefono)
		{
			var urlmt = "./acciones/modificartelefonoc.php";
			var tagmt = $("<div id='dialogmodifyphone'></div>");
			$('#img_loader').show();
			
			$.ajax({
				url: urlmt,
				method: "POST",
				data: { idCliente: idCliente, id_telefono: idTelefono },
				success: function(dataresponse, statustext, response){
					$('#img_loader').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
										
					tagmt.html(dataresponse).dialog({
					  show: "blind",
					  hide: "explode",
					  height: "auto",
					  width: "auto",					  
					  modal: true, 
					  title: "<?php echo translate('Msg_Edit_Phone',$GLOBALS['lang']);?>",
					  autoResize:true,
							close: function(){
									tagmt.dialog('destroy').remove()
							}
					}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
					
					$('#tipotelefonomi').change(function() {
						var valueAcm = $('#tipotelefonomi').val();
						if(valueAcm == 1) {
							$('#validartelefonoclientem').show();
						}
						else
						{
							$('#validartelefonoclientem').hide();
							$('#validarclientemi').prop('checked', false);
						}		       
					});
											
					tagmt.dialog('open');
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
		function confirmar_accion(titulo, mensaje, usuario, idTelefono)
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
										borrar_telefono_usuario(usuario, idTelefono);                                                      
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
	<script type="text/javascript">
		function borrar_telefono_usuario(usuario, idTelefono)
		{
			var urlrdu = "./acciones/borrartelefonouser.php";
			$('#img_loader').show();
			
			$.ajax({
				url: urlrdu,
				method: "POST",
				data: { usuario: usuario, id_telefono: idTelefono },
				success: function(dataresponse, statustext, response){
					$('#img_loader').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Remove_Phone_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						var estaVaciaTabla = 0;
						
						var resBTU = JSON.parse(datTable);
						
						for(var i in resBTU)
						{
							if(resBTU[i]["tipotelefono"] == null || resBTU[i]["tipotelefono"] === '') 
							{
								estaVaciaTabla = 1;
								break;
							}
						}
						
						if(estaVaciaTabla == 0) $('#tableadminphonesclientst').bootstrapTable('load',JSON.parse(datTable));
						else $('#tableadminphonesclientst').bootstrapTable('removeAll');
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
		function confirmar_accion_validar_cliente_m(titulo, mensaje, motivo, idTelefono)
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
										
										validar_cliente_supervisor_m(motivo, idTelefono);                                                      
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
			var urlvcs = "./acciones/validacionclientesupervisortc.php";
			$('#img_loader_15').show();
									
			$.ajax({
				url: urlvcs,
				method: "POST",
				data: { motivo: motivo, idCliente: <?php echo $_GET['idCliente'] ?>, prefijoTelefono: $( "#prefijotelefonoi" ).val(), nroTelefono: $( "#nrotelefonoi" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_15').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_It_Is_Not_Necessary_To_Authorize',$GLOBALS['lang']); ?>') != -1)
					{
						guardarNuevoTelefonoFinal();
					}
					else
					{
						if(dataresponse.indexOf('<?php echo translate('Msg_Must_Authorize_Phone_Registration',$GLOBALS['lang']); ?>') != -1)
						{
							dataresponse = dataresponse.replace("<?php echo translate('Msg_Must_Authorize_Phone_Registration',$GLOBALS['lang']); ?>","");
							var tagarc = $("<div id='dialogautorizacionregistrotelefono'></div>");
							
							tagarc.html(dataresponse).dialog({
							  show: "blind",
							  hide: "explode",
							  height: "auto",
							  width: "auto",					  
							  modal: true, 
							  title: "<?php echo translate('Lbl_Authorize_Phone_Registration',$GLOBALS['lang']);?>",
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
					$('#img_loader_15').hide();
				}
			});			
		}	
	</script>
	
	<script type="text/javascript">
		function validar_cliente_supervisor_m(motivo, idTelefono)
		{			
			var urlvcsm = "./acciones/validacionclientesupervisortcm.php";
			$('#img_loader_15').show();
									
			$.ajax({
				url: urlvcsm,
				method: "POST",
				data: { motivo: motivo, idCliente: <?php echo $_GET['idCliente'] ?>, idTelefono: idTelefono, prefijoTelefono: $( "#prefijotelefonomi" ).val(), nroTelefono: $( "#nrotelefonomi" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_15').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_It_Is_Not_Necessary_To_Authorize',$GLOBALS['lang']); ?>') != -1)
					{
						guardarModificacionTelefonoFinal();
					}
					else
					{
						if(dataresponse.indexOf('<?php echo translate('Msg_Must_Authorize_Phone_Modify',$GLOBALS['lang']); ?>') != -1)
						{
							dataresponse = dataresponse.replace("<?php echo translate('Msg_Must_Authorize_Phone_Modify',$GLOBALS['lang']); ?>","");
							var tagarcm = $("<div id='dialogautorizacionregistrotelefonom'></div>");
							
							tagarcm.html(dataresponse).dialog({
							  show: "blind",
							  hide: "explode",
							  height: "auto",
							  width: "auto",					  
							  modal: true, 
							  title: "<?php echo translate('Lbl_Authorize_Phone_Modify',$GLOBALS['lang']);?>",
							  autoResize:true,
									close: function(){
											tagarcm.dialog('destroy').remove()
									}
							}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
							tagarcm.dialog('open');
						}
						else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
					}
						
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_15').hide();
				}
			});			
		}	
	</script>	

	<script type="text/javascript">
		function guardarAutorizacionSupervisorRegistroTelefono(formularionacrt, motivo)
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
			
			var urlasrc = "./acciones/verificarcredencialessupervisorregistrotelefono.php";
			$('#img_loader_13').show();
			
			
			var p211 = document.createElement("input");
		 			
			formularionacrt.appendChild(p211);
			p211.name = "p211";
			p211.type = "hidden";
			
			p211.value = hex_sha512(formularionacrt.passwordsupervisorn2i.value);
			
			if(formularionacrt.passwordsupervisorn2i.value == "") p211.value = "";
			formularionacrt.passwordsupervisorn2i.value = "";
									
			$.ajax({
				url: urlasrc,
				method: "POST",
				data: { motivo: motivo, usuarioSupervisor: formularionacrt.usuariosupervisorn2i.value, claveSupervisor: p211.value, idCliente: <?php echo $_GET['idCliente'] ?>, prefijoTelefono: $( "#prefijotelefonoi" ).val(), nroTelefono: $( "#nrotelefonoi" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_13').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']);?>') != -1)
					{
						$('#dialogautorizacionregistrotelefono').dialog('destroy').remove();
						guardarNuevoTelefonoFinal();
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
		function guardarAutorizacionSupervisorModificacionTelefono(formularionacrtm, motivo, idTelefono)
		{
			if($('#usuariosupervisorn2mi').val().length == 0)
			{
				$(function() {
					$('#usuariosupervisorn2mi').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$('#usuariosupervisorn2mi').focus();
				return;
			}
			else 
			{
				$(function() {
					$('#usuariosupervisorn2mi').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$('#usuariosupervisorn2mi').tooltip('destroy');
			}

			if($('#passwordsupervisorn2mi').val().length == 0)
			{
				$(function() {
					$('#passwordsupervisorn2mi').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$('#passwordsupervisorn2mi').focus();
				return;
			}
			else 
			{
				$(function() {
					$('#passwordsupervisorn2mi').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$('#passwordsupervisorn2mi').tooltip('destroy');
			}			
			
			var urlasrcm = "./acciones/verificarcredencialessupervisorregistrotelefonom.php";
			$('#img_loader_13').show();
			
			
			var p111 = document.createElement("input");
		 			
			formularionacrtm.appendChild(p111);
			p111.name = "p111";
			p111.type = "hidden";
			
			p111.value = hex_sha512(formularionacrtm.passwordsupervisorn2mi.value);
			
			if(formularionacrtm.passwordsupervisorn2mi.value == "") p111.value = "";
			formularionacrtm.passwordsupervisorn2mi.value = "";
									
			$.ajax({
				url: urlasrcm,
				method: "POST",
				data: { motivo: motivo, idTelefono: idTelefono, usuarioSupervisor: formularionacrtm.usuariosupervisorn2mi.value, claveSupervisor: p111.value, idCliente: <?php echo $_GET['idCliente'] ?>, prefijoTelefono: $( "#prefijotelefonomi" ).val(), nroTelefono: $( "#nrotelefonomi" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_13').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']);?>') != -1)
					{
						$('#dialogautorizacionregistrotelefonom').dialog('destroy').remove();
						guardarModificacionTelefonoFinal(idTelefono);
					}
					else
					{
						if(dataresponse.indexOf('<?php echo translate('Msg_Supervisor_Not_OK',$GLOBALS['lang']);?>') != -1)
						{
							$('#usuariosupervisorn2mi').focus();
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
						}
						else 
						{
							$('#usuariosupervisorn2mi').focus();
							mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
						}					
					}
					
				},
				error: function(request, errorcode, errortext){
					$('#usuariosupervisorn2mi').focus();
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
	<div class="panel-group" style="padding-bottom:60px;">				
		<div class="panel panel-default" style="margin-left:30px;margin-right:30px;">
		  <div id="panel-title-header" class="panel-heading">
			<h3 class="panel-title"><?php echo translate('Lbl_Phones',$GLOBALS['lang']).': '.$nom_tipo_documento_cliente_db.' - '.$documento_cliente_db; ?></h3>
		  </div>
		  <div id="apDiv1" class="panel-body">
			<div id="toolbar" style="margin-left:-95px; margin-top:-1px;">
				<button type="button" class="btn" data-toggle="tooltip" data-placement="top" onclick="nuevoTelefono('<?php echo $_GET['idCliente']; ?>');" title="<?php echo translate('Lbl_New_Phone',$GLOBALS['lang']);?>" ><i class="fas fa-phone"></i></button>
			</div>
			<div id="img_loader"></div>	
			<div id="tablaadminphonesclients" class="table-responsive">
				<table id="tableadminphonesclientst" data-classes="table table-hover table-condensed"
				   data-striped="true" data-pagination="true" data-show-export="true" data-export-options='{"fileName": "<?php echo translate('Lbl_Phones',$GLOBALS['lang']); ?>"}'
				   data-export-types="['excel','pdf','csv','txt']"
				   data-search="true" data-search-align="left" data-toolbar="#toolbar" data-toolbar-align="right">
					<thead>
						<tr>
							<th class="col-xs-1 text-center" data-field="tipotelefono" data-sortable="true"><?php echo translate('Lbl_Type_Phone',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="nrotelefono" data-sortable="true"><?php echo translate('Lbl_Number_Phone',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="preferencia" data-sortable="true"><?php echo translate('Lbl_Preference_Phone',$GLOBALS['lang']);?></th>							
							<th class="col-xs-2 text-center" data-field="acciones"><?php echo translate('Lbl_Actions_Phone',$GLOBALS['lang']);?></th>
						</tr>						
					</thead>
					<tbody>
						<?php
							if($stmt = $mysqli->prepare("SELECT t.id, tt.nombre, t.numero, ct.preferido FROM finan_cli.telefono t, finan_cli.cliente c, finan_cli.tipo_telefono tt, finan_cli.cliente_x_telefono ct WHERE c.id = ? AND tt.id = t.tipo_telefono AND ct.tipo_documento = c.tipo_documento AND ct.documento = c.documento AND ct.id_telefono = t.id")) 
							{
								$clienteP = htmlspecialchars($_GET['idCliente'], ENT_QUOTES, 'UTF-8');
								$stmt->bind_param('i', $clienteP);
								$stmt->execute();    // Ejecuta la consulta preparada.
								$stmt->store_result();
						 
								// Obtiene las variables del resultado.
								$stmt->bind_result($id_telefono, $client_tipo_telefono, $client_numero_telefono, $client_preference_telefono);
								
								while($stmt->fetch())
								{		
									echo '<tr>';
									echo '<td>'.$client_tipo_telefono.'</td>';
									echo '<td>'.$client_numero_telefono.'</td>';
									if($client_preference_telefono == 1) $preferenciaTel = translate('Lbl_Button_YES',$GLOBALS['lang']);
									else $preferenciaTel = translate('Lbl_Button_NO',$GLOBALS['lang']);
									echo '<td>'.$preferenciaTel.'</td>';									
									echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Phone',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Remove_Telefono',$GLOBALS['lang']).'\',\''.$_GET['idCliente'].'\',\''.$id_telefono.'\')"><i class="fas fa-phone-slash"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Phone',$GLOBALS['lang']).'" onclick="modificarTelefono(\''.$_GET['idCliente'].'\',\''.$id_telefono.'\')"><i class="fas fa-phone-volume"></i></button></td>';
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
			$('#tableadminphonesclientst').bootstrapTable({locale:'es-AR'});
		});
	</script>	
</body>
</html>