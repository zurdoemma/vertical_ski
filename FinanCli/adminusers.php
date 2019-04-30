<?php
include ('./utiles/funciones.php');
require("../parametrosbasedatosfc.php");
$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
mysqli_set_charset($mysqli,"utf8");

if (!verificar_usuario($mysqli)){header('Location:./login.php');}
if (!verificar_permisos_admin()){header('Location:./sinautorizacion.php?activauto=1');}
include("./menu/menu.php");
?>
<!doctype html>
<html lang="es-es">
<head>
	<meta charset="UTF-8">
	
	<link rel="shortcut icon" href="./images/iconoFinanCli.png" >
	<title><?php echo translate('Lbl_Admin_User',$GLOBALS['lang']); ?></title>
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
		function nuevoUsuario()
		{
			var urlnu = "./acciones/nuevousuario.php";
			var tagnu = $("<div id='dialognewuser'></div>");
			$('#img_loader_5').show();
			
			$.ajax({
				url: urlnu,
				method: "POST",
				data: {},
				success: function(dataresponse, statustext, response){
					$('#img_loader_5').hide();
					tagnu.html(dataresponse).dialog({
					  show: "blind",
					  hide: "explode",
					  height: "auto",
					  width: "auto",					  
					  modal: true, 
					  title: "<?php echo translate('Lbl_New_Users',$GLOBALS['lang']);?>: ",
					  autoResize:true,
							close: function(){
									tagnu.dialog('destroy').remove()
							}
					}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
					tagnu.dialog('open');
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
				$('#iduserni').focus();	
			}
		}
    </script>	
		
	<script type="text/javascript">
		function modificarUsuario(usuario)
		{
			var urla = "./acciones/modificarusuario.php";
			var tag = $("<div id='dialogmoduser'></div>");
			$('#img_loader').show();
			
			$.ajax({
				url: urla,
				method: "POST",
				data: { usuario: usuario },
				success: function(dataresponse, statustext, response){
					$('#img_loader').hide();
					tag.html(dataresponse).dialog({
					  show: "blind",
					  hide: "explode",
					  height: "auto",
					  width: "auto",					  
					  modal: true, 
					  title: "<?php echo translate('Msg_Edit_User',$GLOBALS['lang']);?>: "+usuario,
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
		function guardarModificacionUsuario(formulariod)
		{
			if($( "#nameuseri" ).val().length == 0)
			{
				$(function() {
					$( "#nameuseri" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#nameuseri" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#nameuseri" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#nameuseri" ).tooltip('destroy');
			}
			
			if($( "#surnameuseri" ).val().length == 0)
			{
				$(function() {
					$( "#surnameuseri" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#surnameuseri" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#surnameuseri" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#surnameuseri" ).tooltip('destroy');
			}

			if($( "#documentuseri" ).val().length == 0)
			{
				$(function() {
					$( "#documentuseri" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#documentuseri" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#documentuseri" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#documentuseri" ).tooltip('destroy');
			}			
			

			if($( "#emailuseri" ).val().length == 0)
			{
				$('#emailuseri').prop('title', '<?php echo translate('Msg_A_User_Email_Must_Enter',$GLOBALS['lang']);?>');				
				$(function() {
					$( "#emailuseri" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#emailuseri" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#emailuseri" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#emailuseri" ).tooltip('destroy');
			}
			
			if(!caracteresCorreoValido($( "#emailuseri" ).val()))
			{
				$('#emailuseri').prop('title', '<?php echo translate('Msg_A_User_Email_Invalid',$GLOBALS['lang']);?>');
				$(function() {
					$( "#emailuseri" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#emailuseri" ).focus();
				return;				
			}
			else
			{
				$(function() {
					$( "#emailuseri" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#emailuseri" ).tooltip('destroy');				
			}				
			
			
			if($( "#claveuseri" ).val().length != 0)
			{
					if($( "#rclaveuseri" ).val().length == 0)
					{
						$('#claveuseri').prop('title', '<?php echo translate('Msg_A_User_Confirm_New_Password_Must_Enter',$GLOBALS['lang']);?>');
						$(function() {
							$( "#rclaveuseri" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});
						$( "#rclaveuseri" ).focus();
						return;
					}
					else 
					{
						$(function() {
							$( "#rclaveuseri" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});				
						$( "#rclaveuseri" ).tooltip('destroy');
					}
					
					if($( "#claveuseri" ).val().length < 7)
					{
						$('#claveuseri').prop('title', '<?php echo translate('Msg_The_Password_Must_Have_At_Least_7_Characters',$GLOBALS['lang']);?>');
						$(function() {
							$( "#claveuseri" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});
						$( "#claveuseri" ).focus();
						return;
					}
					else
					{
						$(function() {
							$( "#claveuseri" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});				
						$( "#claveuseri" ).tooltip('destroy');						
					}			
			}
			else
			{
				$(function() {
					$( "#claveuseri" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#claveuseri" ).tooltip('destroy');						
			}			

			if($( "#rclaveuseri" ).val().length != 0)
			{
					if($( "#claveuseri" ).val().length == 0)
					{
						$('#claveuseri').prop('title', '<?php echo translate('Msg_A_User_New_Password_Must_Enter',$GLOBALS['lang']);?>');
						$(function() {
							$( "#claveuseri" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});
						$( "#claveuseri" ).focus();
						return;
					}
					else 
					{
						$(function() {
							$( "#claveuseri" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});				
						$( "#claveuseri" ).tooltip('destroy');
					}
					
					if($( "#rclaveuseri" ).val().length < 7)
					{
						$('#rclaveuseri').prop('title', '<?php echo translate('Msg_The_Password_Must_Have_At_Least_7_Characters',$GLOBALS['lang']);?>');
						$(function() {
							$( "#rclaveuseri" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});
						$( "#rclaveuseri" ).focus();
						return;
					}
					else
					{
						$(function() {
							$( "#rclaveuseri" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});				
						$( "#rclaveuseri" ).tooltip('destroy');						
					}					
			}
			else
			{
				$(function() {
					$( "#rclaveuseri" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#rclaveuseri" ).tooltip('destroy');						
			}				
			
			if($( "#claveuseri" ).val() != $( "#rclaveuseri" ).val() )
			{
				$('#claveuseri').prop('title', '<?php echo translate('Msg_The_New_Password_And_Repetition_Not_Match',$GLOBALS['lang']);?>');
				$(function() {
					$( "#claveuseri" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#claveuseri" ).val('');
				$( "#rclaveuseri" ).val('');
				$( "#claveuseri" ).focus();
				return;
			}
			
			var passwmu = '';
			if($( "#claveuseri" ).val().length != 0)
			{
				passwmu = hex_sha512($( "#claveuseri" ).val());
				$( "#claveuseri" ).val('');
				$( "#rclaveuseri" ).val('');
			}
			var urlgmu = "./acciones/guardarmodificacionusuario.php";
			$('#img_loader_2').show();
			
			$.ajax({
				url: urlgmu,
				method: "POST",
				data: { usuario: $( "#iduseri" ).val(), nombre: $( "#nameuseri" ).val(), apellido: $( "#surnameuseri" ).val(), tipoDocumento: $( "#tipodocuseri" ).val(), documento: $( "#documentuseri" ).val(), email: $( "#emailuseri" ).val(), perfil: $( "#perfiluseri" ).val(), sucursal: $( "#sucursaluseri" ).val(), claveu: passwmu },
				success: function(dataresponse, statustext, response){
					$('#img_loader_2').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Modify_User_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialogmoduser').dialog('close');
						$('#tableadminuserst').bootstrapTable('load',JSON.parse(datTable));
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
		function guardarNuevoUsuario(formulariod)
		{

			if($( "#iduserni" ).val().length == 0)
			{
				$(function() {
					$( "#iduserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#iduserni" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#iduserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#iduserni" ).tooltip('destroy');
			}
			
			if($( "#nameuserni" ).val().length == 0)
			{
				$(function() {
					$( "#nameuserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#nameuserni" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#nameuserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#nameuserni" ).tooltip('destroy');
			}
			
			if($( "#surnameuserni" ).val().length == 0)
			{
				$(function() {
					$( "#surnameuserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#surnameuserni" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#surnameuserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#surnameuserni" ).tooltip('destroy');
			}

			if($( "#documentuserni" ).val().length == 0)
			{
				$(function() {
					$( "#documentuserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#documentuserni" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#documentuserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#documentuserni" ).tooltip('destroy');
			}			
			

			if($( "#emailuserni" ).val().length == 0)
			{
				$('#emailuserni').prop('title', '<?php echo translate('Msg_A_User_Email_Must_Enter',$GLOBALS['lang']);?>');				
				$(function() {
					$( "#emailuserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#emailuserni" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#emailuserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#emailuserni" ).tooltip('destroy');
			}
			
			if(!caracteresCorreoValido($( "#emailuserni" ).val()))
			{
				$('#emailuserni').prop('title', '<?php echo translate('Msg_A_User_Email_Invalid',$GLOBALS['lang']);?>');
				$(function() {
					$( "#emailuserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#emailuserni" ).focus();
				return;				
			}
			else
			{
				$(function() {
					$( "#emailuserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#emailuserni" ).tooltip('destroy');				
			}				
			
			if($( "#claveuserni" ).val().length == 0)
			{
				$('#claveuserni').prop('title', '<?php echo translate('Msg_A_Add_User_New_Password_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#claveuserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#claveuserni" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#claveuserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#claveuserni" ).tooltip('destroy');
			}			
			
			if($( "#rclaveuserni" ).val().length == 0)
			{
				$('#claveuserni').prop('title', '<?php echo translate('Msg_A_Add_User_Confirm_New_Password_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#rclaveuserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#rclaveuserni" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#rclaveuserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#rclaveuserni" ).tooltip('destroy');
			}
			
			if($( "#claveuserni" ).val().length < 7)
			{
				$('#claveuserni').prop('title', '<?php echo translate('Msg_The_Password_Must_Have_At_Least_7_Characters',$GLOBALS['lang']);?>');
				$(function() {
					$( "#claveuserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#claveuserni" ).focus();
				return;
			}
			else
			{
				$(function() {
					$( "#claveuserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#claveuserni" ).tooltip('destroy');						
			}			
						
			if($( "#rclaveuserni" ).val().length < 7)
			{
				$('#rclaveuserni').prop('title', '<?php echo translate('Msg_The_Password_Must_Have_At_Least_7_Characters',$GLOBALS['lang']);?>');
				$(function() {
					$( "#rclaveuserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#rclaveuserni" ).focus();
				return;
			}
			else
			{
				$(function() {
					$( "#rclaveuserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#rclaveuserni" ).tooltip('destroy');						
			}					
				
			
			if($( "#claveuserni" ).val() != $( "#rclaveuserni" ).val() )
			{
				$('#claveuserni').prop('title', '<?php echo translate('Msg_Add_The_New_Password_And_Repetition_Not_Match',$GLOBALS['lang']);?>');
				$(function() {
					$( "#claveuserni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#claveuserni" ).val('');
				$( "#rclaveuserni" ).val('');
				$( "#claveuserni" ).focus();
				return;
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
				if (isNaN($( "#nrocallei" ).val()))
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
				if (isNaN($( "#domfloori" ).val()))
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
			
			var passwmu = hex_sha512($( "#claveuserni" ).val());
			$( "#claveuserni" ).val('');
			$( "#rclaveuserni" ).val('');			
			
			var urlggnu = "./acciones/guardarnuevousuario.php";
			$('#img_loader_5').show();
			
			$.ajax({
				url: urlggnu,
				method: "POST",
				data: { usuario: $( "#iduserni" ).val(), nombre: $( "#nameuserni" ).val(), apellido: $( "#surnameuserni" ).val(), tipoDocumento: $( "#tipodocuserni" ).val(), documento: $( "#documentuserni" ).val(), email: $( "#emailuserni" ).val(), perfil: $( "#perfiluserni" ).val(), sucursal: $( "#sucursaluserni" ).val(), claveu: passwmu, calle: $( "#callei" ).val(), nroCalle: $( "#nrocallei" ).val(), provincia: $( "#domprovinciai" ).val(), localidad: $( "#domlocalidadi" ).val(), departamento: $( "#domdepartamentoi" ).val(), piso: $( "#domfloori" ).val(), codigoPostal: $( "#zipcodei" ).val(), entreCalle1: $( "#entrecalle1i" ).val(), entreCalle2: $( "#entrecalle2i" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_5').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_New_User_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialognewuser').dialog('close');
						$('#tableadminuserst').bootstrapTable('load',JSON.parse(datTable));
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
		function deshabilitarUsuario(usuario)
		{
			var urla = "./acciones/deshabilitarusuario.php";
			$('#img_loader').show();
			
			$.ajax({
				url: urla,
				method: "POST",
				data: { usuario: usuario },
				success: function(dataresponse, statustext, response){
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Disabled_User_OK',$GLOBALS['lang']);?>') != -1 || dataresponse.indexOf('<?php echo translate('Msg_Enabled_User_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialogmoduser').dialog('close');
						$('#tableadminuserst').bootstrapTable('load',JSON.parse(datTable));
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}					
					else mensaje_atencion("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",dataresponse);
					$('#img_loader').hide();
					console.log("success!");
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
		function confirmar_accion(titulo, mensaje, usuario)
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

										var urla = "./acciones/deshabilitarusuario.php";											

										deshabilitarUsuario(usuario);                                                      
								},
								"<?php echo translate('Lbl_Button_NO',$GLOBALS['lang']);?>": function () {
										$("#confirmDialog").dialog('close');
										$('#img_loader').hide();
										return;
								}
						}
				}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
				$( "#confirmDialog" ).html("<div id='confirmacionAccion'>"+mensaje+usuario+"?</div>");
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
			<h3 class="panel-title"><?php echo translate('Lbl_Users',$GLOBALS['lang']); ?></h3>
		  </div>
		  <div id="apDiv1" class="panel-body">
			<div id="toolbar" style="margin-left:-105px; margin-top:-1px;">
				<button type="button" class="btn" data-toggle="tooltip" data-placement="top" onclick="nuevoUsuario();" title="<?php echo translate('Lbl_New_Users',$GLOBALS['lang']);?>" ><i class="fas fa-user-plus"></i></button>
			</div>
			<div id="img_loader"></div>
			<div id="tablaadminusers" class="table-responsive">
				<table id="tableadminuserst" data-classes="table table-hover table-condensed"
				   data-striped="true" data-pagination="true" data-show-export="true" data-export-options='{"fileName": "<?php echo translate('Lbl_Users',$GLOBALS['lang']); ?>"}'
				   data-export-types="['excel','pdf','csv','txt']"
				   data-search="true" data-search-align="left" data-toolbar="#toolbar" data-toolbar-align="right">
					<thead>
						<tr>
							<th class="col-xs-1 text-center" data-field="usuario" data-sortable="true"><?php echo translate('Lbl_User',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="nombre" data-sortable="true"><?php echo translate('Lbl_Name_User',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="apellido" data-sortable="true"><?php echo translate('Lbl_Surname_User',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="documento" data-sortable="true"><?php echo translate('Lbl_Document_User',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="perfil" data-sortable="true"><?php echo translate('Lbl_Perfil_User',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="sucursal" data-sortable="true"><?php echo translate('Lbl_Tender_User',$GLOBALS['lang']);?></th>
							<th class="col-xs-3 text-center" data-field="estado" data-sortable="true"><?php echo translate('Lbl_State_User',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="acciones"><?php echo translate('Lbl_Actions_User',$GLOBALS['lang']);?></th>
						</tr>						
					</thead>
					<tbody>
						<?php
							if ($stmt = $mysqli->prepare("SELECT u.id, u.nombre, u.apellido, u.documento, p.nombre, s.nombre, u.estado   FROM finan_cli.usuario u, finan_cli.perfil p, finan_cli.sucursal s WHERE  u.id_perfil = p.id AND u.id_sucursal = s.id ORDER BY id")) 
							{
								$stmt->execute();    // Ejecuta la consulta preparada.
								$stmt->store_result();
						 
								// Obtiene las variables del resultado.
								$stmt->bind_result($id_user, $nombre_user, $apellido_user, $documento_user, $perfil_user, $sucursal_user, $estado_user);
								
								while($stmt->fetch())
								{		
									echo '<tr>';
									echo '<td>'.$id_user.'</td>';
									echo '<td>'.$nombre_user.'</td>';
									echo '<td>'.$apellido_user.'</td>';
									echo '<td>'.$documento_user.'</td>';
									echo '<td>'.$perfil_user.'</td>';
									echo '<td>'.$sucursal_user.'</td>';
									echo '<td>'.$estado_user.'</td>';
									
									if($id_user != 'admin_sys')
									{
										if($estado_user == translate('State_User',$GLOBALS['lang'])) echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Lbl_Disable_User',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Disabled_User',$GLOBALS['lang']).'\',\''.$id_user.'\')"><i class="fas fa-user-slash"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_User',$GLOBALS['lang']).'" onclick="modificarUsuario(\''.$id_user.'\')"><i class="fas fa-user-edit"></i></button></td>';
										else echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Enable_User',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Enabled_User',$GLOBALS['lang']).'\',\''.$id_user.'\')"><i class="fas fa-user-check"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_User',$GLOBALS['lang']).'" onclick="modificarUsuario(\''.$id_user.'\')"><i class="fas fa-user-edit"></i></button></td>';
									}
									else echo '<td>---</td>';
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
			$('#tableadminuserst').bootstrapTable({locale:'es-AR'});
			  
		});
	</script>	
</body>
</html>