<?php
include ('./utiles/funciones.php');
require("../parametrosbasedatosfc.php");
$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
mysqli_set_charset($mysqli,"utf8");
if (!verificar_usuario($mysqli)){header('Location:./login.php');return;}
if (!verificar_permisos_usuario()){header('Location:./sinautorizacion.php?activauto=1');return;}
include("./menu/menu.php");

if($stmt2 = $mysqli->prepare("SELECT valor FROM finan_cli.parametros WHERE nombre = 'cantidad_domicilios_x_usuario_cliente'"))
{
	$stmt2->execute();    
	$stmt2->store_result();
	$stmt2->bind_result($cantidad_domicilios_db);
	$stmt2->fetch();
	
	$stmt2->free_result();
	$stmt2->close();
}

if($stmt3 = $mysqli->prepare("SELECT valor FROM finan_cli.parametros WHERE nombre = 'cantidad_telefonos_x_usuario_cliente'"))
{
	$stmt3->execute();    
	$stmt3->store_result();
	$stmt3->bind_result($cantidad_telefonos_db);
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
	<title><?php echo translate('Lbl_Edit_User_2',$GLOBALS['lang']); ?></title>
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
			
			
			if($( "#claveactualuseri" ).val().length == 0)
			{
				$(function() {
					$( "#claveactualuseri" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#claveactualuseri" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#claveactualuseri" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#claveactualuseri" ).tooltip('destroy');
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
			
			var currentpasw = hex_sha512($( "#claveactualuseri" ).val());
			$( "#claveactualuseri" ).val('');
			
			var urlgmu = "./acciones/guardarmodificacionusuariou.php";
			$('#img_loader_6').show();
			
			$.ajax({
				url: urlgmu,
				method: "POST",
				data: { usuario: "<?php echo $_SESSION['username'] ?>", nombre: $( "#nameuseri" ).val(), apellido: $( "#surnameuseri" ).val(), tipoDocumento: $( "#tipodocuseri" ).val(), documento: $( "#documentuseri" ).val(), email: $( "#emailuseri" ).val(), claveac: currentpasw, claveu: passwmu },
				success: function(dataresponse, statustext, response){
					$('#img_loader_6').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Modify_User_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
					
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
						
						var resMU = JSON.parse(datTable);
						
						for(var i in resMU)
						{
							 $( "#nameuseri" ).val(resMU[i]["nombre"]);
							 $( "#surnameuseri" ).val(resMU[i]["apellido"]);
							 $( "#tipodocuseri" ).val(resMU[i]["tipodocumento"]);
							 $( "#documentuseri" ).val(resMU[i]["documento"]);
							 $( "#emailuseri" ).val(resMU[i]["email"]);
						}
					}
					else 
					{
						$( "#claveactualuseri" ).focus();
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
					}
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_6').hide();
				}
			});				
			
			
		}			
	</script>
	
	<script type="text/javascript">
		function guardarNuevoDomicilio(formulariod)
		{
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
						
			var urlgnd = "./acciones/guardarnuevodomiciliou.php";
			$('#img_loader_3').show();
			
			$.ajax({
				url: urlgnd,
				method: "POST",
				data: { usuario: "<?php echo $_SESSION['username']; ?>", calle: $( "#callei" ).val(), nroCalle: $( "#nrocallei" ).val(), provincia: $( "#domprovinciai" ).val(), localidad: $( "#domlocalidadi" ).val(), departamento: $( "#domdepartamentoi" ).val(), piso: $( "#domfloori" ).val(), codigoPostal: $( "#zipcodei" ).val(), entreCalle1: $( "#entrecalle1i" ).val(), entreCalle2: $( "#entrecalle2i" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_3').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Add_Address_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialognewaddress').dialog('close');
						$('#tableadminaddressuserst').bootstrapTable('load',JSON.parse(datTable));
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_3').hide();
				}
			});				
			
			
		}			
	</script>
	
	<script type="text/javascript">
		function guardarModificacionDomicilio(formulariod, idDomicilio)
		{
			if($( "#callemi" ).val().length == 0)
			{
				$(function() {
					$( "#callemi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#callemi" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#callemi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#callemi" ).tooltip('destroy');
			}
			
			if($( "#nrocallemi" ).val().length == 0)
			{
				$('#nrocallemi').prop('title', '<?php echo translate('Msg_A_Street_Number_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#nrocallemi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#nrocallemi" ).focus();
				return;
			}
			else 
			{
				if (isNaN($( "#nrocallemi" ).val()))
				{
					$('#nrocallemi').prop('title', '<?php echo translate('Msg_A_Street_Number_Must_Enter_A_Whole',$GLOBALS['lang']);?>');
					$(function() {
						$( "#nrocallemi" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#nrocallemi" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#nrocallemi" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#nrocallemi" ).tooltip('destroy');
				}
			}
			
			if($( "#domlocalidadmi" ).val().length == 0)
			{
				$(function() {
					$( "#domlocalidadmi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#domlocalidadmi" ).focus();
				return;
			}
			else 
			{
				$(function() {
					$( "#domlocalidadmi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});					
				$( "#domlocalidadmi" ).tooltip('destroy');		
			}
			
			if($( "#domfloormi" ).val().length != 0)
			{
				if (isNaN($( "#domfloormi" ).val()))
				{
					$(function() {
						$( "#domfloormi" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#domfloormi" ).focus();
					return;
				}
				else
				{
					$(function() {
						$( "#domfloormi" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#domfloormi" ).tooltip('destroy');
				}					
			}
						
			var urlgnd = "./acciones/guardarmodificaciondomiciliou.php";
			$('#img_loader_4').show();
			
			$.ajax({
				url: urlgnd,
				method: "POST",
				data: { usuario: "<?php echo $_SESSION['username']; ?>", idDomicilio: idDomicilio, calle: $( "#callemi" ).val(), nroCalle: $( "#nrocallemi" ).val(), provincia: $( "#domprovinciami" ).val(), localidad: $( "#domlocalidadmi" ).val(), departamento: $( "#domdepartamentomi" ).val(), piso: $( "#domfloormi" ).val(), codigoPostal: $( "#zipcodemi" ).val(), entreCalle1: $( "#entrecalle1mi" ).val(), entreCalle2: $( "#entrecalle2mi" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_4').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Modify_Address_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialogmodifyaddress').dialog('close');
						$('#tableadminaddressuserst').bootstrapTable('load',JSON.parse(datTable));
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
	
	<script type="text/javascript">
		function nuevoDomicilio(usuario)
		{
			var urlnd = "./acciones/nuevodomiciliou.php";
			var tagnd = $("<div id='dialognewaddress'></div>");
			$('#img_loader').show();
			
			$.ajax({
				url: urlnd,
				method: "POST",
				data: { usuario: usuario },
				success: function(dataresponse, statustext, response){
					$('#img_loader').hide();
				
					if(dataresponse.indexOf('<?php echo str_replace("%1",$cantidad_domicilios_db,translate('Msg_Limit_Address_User',$GLOBALS['lang']));?>') != -1)
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
					}
					else
					{				
						tagnd.html(dataresponse).dialog({
						  show: "blind",
						  hide: "explode",
						  height: "auto",
						  width: "auto",					  
						  modal: true, 
						  title: "<?php echo translate('Lbl_New_Home_Address',$GLOBALS['lang']);?>: "+usuario,
						  autoResize:true,
								close: function(){
										tagnd.dialog('destroy').remove()
								}
						}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
						tagnd.dialog('open');
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
		function modificarDomicilio(usuario, idDomicilio)
		{
			var urlnd = "./acciones/modificardomiciliou.php";
			var tagmd = $("<div id='dialogmodifyaddress'></div>");
			$('#img_loader').show();
			
			$.ajax({
				url: urlnd,
				method: "POST",
				data: { usuario: usuario, id_domicilio: idDomicilio },
				success: function(dataresponse, statustext, response){
					$('#img_loader').hide();
					tagmd.html(dataresponse).dialog({
					  show: "blind",
					  hide: "explode",
					  height: "auto",
					  width: "auto",					  
					  modal: true, 
					  title: "<?php echo translate('Msg_Edit_Address',$GLOBALS['lang']);?>",
					  autoResize:true,
							close: function(){
									tagmd.dialog('destroy').remove()
							}
					}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
					tagmd.dialog('open');
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
		function confirmar_accion(titulo, mensaje, usuario, idDomicilio)
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
										borrar_domicilio_usuario(usuario, idDomicilio);                                                      
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
		function borrar_domicilio_usuario(usuario, idDomicilio)
		{
			var urlrdu = "./acciones/borrardomiciliouseru.php";
			$('#img_loader').show();
			
			$.ajax({
				url: urlrdu,
				method: "POST",
				data: { usuario: usuario, id_domicilio: idDomicilio },
				success: function(dataresponse, statustext, response){
					$('#img_loader').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Remove_Address_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#tableadminaddressuserst').bootstrapTable('load',JSON.parse(datTable));
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
		function nuevoTelefono(usuario)
		{
			var urlnt = "./acciones/nuevotelefonou.php";
			var tagnt = $("<div id='dialognewphone'></div>");
			$('#img_loader_8').show();
			
			$.ajax({
				url: urlnt,
				method: "POST",
				data: { usuario: usuario },
				success: function(dataresponse, statustext, response){
					$('#img_loader_8').hide();
					
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
						  title: "<?php echo translate('Lbl_New_Phone',$GLOBALS['lang']);?>: "+usuario,
						  autoResize:true,
								close: function(){
										tagnt.dialog('destroy').remove()
								}
						}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
						tagnt.dialog('open');
						$( "#prefijotelefonoi" ).focus();
					}
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_8').hide();
				}
			});	
		}
    </script>

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
				if (isNaN($( "#prefijotelefonoi" ).val()))
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
				if (isNaN($( "#nrotelefonoi" ).val()))
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
									
			var urlgnd = "./acciones/guardarnuevotelefonou.php";
			$('#img_loader_7').show();
			
			$.ajax({
				url: urlgnd,
				method: "POST",
				data: { usuario: "<?php echo $_SESSION['username']; ?>", prefijoTelefono: $( "#prefijotelefonoi" ).val(), nroTelefono: $( "#nrotelefonoi" ).val(), tipoTelefono: $( "#tipotelefonoi" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_7').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Add_Phone_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialognewphone').dialog('close');
						$('#tableadminphonesuserst').bootstrapTable('load',JSON.parse(datTable));
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_7').hide();
				}
			});					
		}			
	</script>
	<script type="text/javascript">
		function confirmar_accion_2(titulo, mensaje, usuario, idTelefono)
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
			var urlrdu = "./acciones/borrartelefonouseru.php";
			$('#img_loader_8').show();
			
			$.ajax({
				url: urlrdu,
				method: "POST",
				data: { usuario: usuario, id_telefono: idTelefono },
				success: function(dataresponse, statustext, response){
					$('#img_loader_8').hide();
					
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
						
						if(estaVaciaTabla == 0) $('#tableadminphonesuserst').bootstrapTable('load',JSON.parse(datTable));
						else $('#tableadminphonesuserst').bootstrapTable('removeAll');
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_8').hide();
				}
			});	
		}
	</script>
	<script type="text/javascript">
		function modificarTelefono(usuario, idTelefono)
		{
			var urlmt = "./acciones/modificartelefonou.php";
			var tagmt = $("<div id='dialogmodifyphone'></div>");
			$('#img_loader_8').show();
			
			$.ajax({
				url: urlmt,
				method: "POST",
				data: { usuario: usuario, id_telefono: idTelefono },
				success: function(dataresponse, statustext, response){
					$('#img_loader_8').hide();
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
					tagmt.dialog('open');
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_8').hide();
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
				if (isNaN($( "#prefijotelefonomi" ).val()))
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
				if (isNaN($( "#nrotelefonomi" ).val()))
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
						
			var urlgnd = "./acciones/guardarmodificaciontelefonou.php";
			$('#img_loader_7').show();
			
			$.ajax({
				url: urlgnd,
				method: "POST",
				data: { usuario: "<?php echo $_SESSION['username']; ?>", idTelefono: idTelefono, prefijoTelefono: $( "#prefijotelefonomi" ).val(), nroTelefono: $( "#nrotelefonomi" ).val(), tipoTelefono: $( "#tipotelefonomi" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_7').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Modify_Phone_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialogmodifyphone').dialog('close');
						$('#tableadminphonesuserst').bootstrapTable('load',JSON.parse(datTable));
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_7').hide();
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
	<div class="panel-group">			
		<div class="panel panel-default" style="margin-left:30px;margin-right:30px;font-size:14px;">
			<div id="panel-title-header" class="panel-heading">
				<h3 class="panel-title"><?php echo translate('Msg_Edit_User',$GLOBALS['lang']) ?>: <?php echo $_SESSION['username'] ?></h3>
			</div>
			<div class="panel-body">
				<div id="img_loader_6"></div>
				<form id="formulariomu" role="form">
				<?php
					if($stmt = $mysqli->prepare("SELECT u.nombre, u.apellido, u.tipo_documento, u.documento, u.email FROM finan_cli.usuario u WHERE id LIKE(?)"))
					{
						$stmt->bind_param('s', $_SESSION['username']);
						$stmt->execute();    
						$stmt->store_result();
				

						$stmt->bind_result($user_nombre, $user_apellido, $user_tipo_doc, $user_doc, $user_email);
						$stmt->fetch();
					}
				?>
					<div class="form-group form-inline">							
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="nameuser"><?php echo translate('Lbl_Name_User',$GLOBALS['lang']).':' ?></label>
						<div class="form-group" id="nameuser">
							<input title="<?php echo translate('Msg_A_User_Name_Must_Enter',$GLOBALS['lang']) ?>" class="form-control input-sm" id="nameuseri" name="nameuseri" type="text" maxlength="100" value="<?php echo $user_nombre ?>" />
						</div>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="surnameuser"><?php echo translate('Lbl_Surname_User',$GLOBALS['lang']).':' ?></label>
						<div class="form-group" id="nameuser">
							<input title="<?php echo translate('Msg_A_User_Surname_Must_Enter',$GLOBALS['lang']) ?>" class="form-control input-sm" id="surnameuseri" name="surnameuseri" type="text" maxlength="100" value="<?php echo $user_apellido ?>" />
						</div>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="tipodocuser"><?php echo translate('Lbl_Type_Document_User',$GLOBALS['lang']).':' ?></label>
						<div class="form-group" id="tipodocuser">
							<select class="form-control input-sm" name="tipodocuseri" id="tipodocuseri" style="width:168px;">		 
							<?php
								if ($stmt = $mysqli->prepare("SELECT id, nombre FROM finan_cli.tipo_documento")) 
									{ 
										$stmt->execute();    
										$stmt->store_result();
								 
										$stmt->bind_result($id_tipo_doc,$name_tipo_doc);
										while($stmt->fetch())
										{
											if($id_tipo_doc == $user_tipo_doc)
											{
												echo '<option selected value="'.$id_tipo_doc.'">'.$name_tipo_doc.'</option>';
											}
											else echo '<option value="'.$id_tipo_doc.'">'.$name_tipo_doc.'</option>';
										}
									}
									else  
									{
										echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
										return;			
									}
							?>
							</select>
						</div>														
					</div>
					<div class="form-group form-inline">
						&nbsp;&nbsp;<label class="control-label" for="documentuser"><?php echo translate('Lbl_Document_User',$GLOBALS['lang']).':' ?></label>
						<div class="form-group" id="documentuser">
							<input title="<?php echo translate('Msg_A_User_Document_Must_Enter',$GLOBALS['lang']) ?>" class="form-control input-sm" id="documentuseri" name="documentuseri" type="text" maxlength="20" value="<?php echo $user_doc ?>" />
						</div>					
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="emailuser"><?php echo translate('Lbl_Email_User',$GLOBALS['lang']).':' ?></label>
						<div class="form-group" id="emailuser">
							<input title="<?php echo translate('Msg_A_User_Email_Must_Enter',$GLOBALS['lang']) ?>" class="form-control input-sm" id="emailuseri" name="emailuseri" type="text" maxlength="250" value="<?php echo $user_email ?>" />
						</div>
						&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="claveactualuser"><?php echo translate('Lbl_Current_Password',$GLOBALS['lang']).':' ?></label>
						<div class="form-group" id="claveactualuser">
							<input title="<?php echo translate('Msg_A_User_Current_Password_Must_Enter',$GLOBALS['lang']) ?>" class="form-control input-sm" id="claveactualuseri" name="claveactualuseri" type="password" maxlength="128" />
						</div>							
					</div>
					<div class="form-group form-inline">			
						<label class="control-label" for="claveuser"><?php echo translate('Lbl_New_Password',$GLOBALS['lang']).':' ?></label>
						<div class="form-group" id="claveuser">
							<input title="<?php echo translate('Msg_A_User_New_Password_Must_Enter',$GLOBALS['lang']) ?>" class="form-control input-sm" id="claveuseri" name="claveuseri" type="password" maxlength="128" />
						</div>
						&nbsp;&nbsp;&nbsp;&nbsp;<label class="control-label" for="rclaveuser"><?php echo translate('Lbl_Repeat_Password',$GLOBALS['lang']).':' ?></label>
						<div class="form-group" id="rclaveuser">
							<input title="<?php echo translate('Msg_A_User_Confirm_New_Password_Must_Enter',$GLOBALS['lang']) ?>" class="form-control input-sm" id="rclaveuseri" name="rclaveuseri" type="password" maxlength="128" />
						</div>			
					</div>
					<div class="form-group form-inline">
						<input type="button" class="btn btn-primary pull-right" name="btnCargarMU" id="btnCargarMU" value="<?php echo translate('Lbl_Save',$GLOBALS['lang']) ?>" onClick="guardarModificacionUsuario(document.getElementById('formulariomu'));" />								
					</div>				
				</form>
			</div>
		</div>
	</div>
	
	<div class="panel-group">				
		<div class="panel panel-default" style="margin-left:30px;margin-right:30px;">
		  <div id="panel-title-header" class="panel-heading">
			<h3 class="panel-title"><?php echo translate('Lbl_Home_Addresses',$GLOBALS['lang']).': '.$_SESSION['username']; ?></h3>
		  </div>
		  <div id="apDiv1" class="panel-body">
			<div id="toolbar" style="margin-left:-95px; margin-top:-1px;">
				<button type="button" class="btn" data-toggle="tooltip" data-placement="top" onclick="nuevoDomicilio('<?php echo $_SESSION['username']; ?>');" title="<?php echo translate('Lbl_New_Home_Address',$GLOBALS['lang']);?>" ><i class="fas fa-map-marker-alt"></i></button>
			</div>
			<div id="img_loader"></div>	
			<div id="tablaadminaddressusers" class="table-responsive">
				<table id="tableadminaddressuserst" data-classes="table table-hover table-condensed"
				   data-striped="true" data-pagination="true" data-show-export="true" data-export-options='{"fileName": "<?php echo translate('Lbl_Home_Addresses',$GLOBALS['lang']); ?>"}'
				   data-export-types="['excel','pdf','csv','txt']"
				   data-search="true" data-search-align="left" data-toolbar="#toolbar" data-toolbar-align="right">
					<thead>
						<tr>
							<th class="col-xs-1 text-center" data-field="calle" data-sortable="true"><?php echo translate('Lbl_Street',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="nrocalle" data-sortable="true"><?php echo translate('Lbl_Number_Street',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="provincia" data-sortable="true"><?php echo translate('Lbl_State',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="localidad" data-sortable="true"><?php echo translate('Lbl_City',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="departamento" data-sortable="true"><?php echo translate('Lbl_Departament',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="piso" data-sortable="true"><?php echo translate('Lbl_Floor',$GLOBALS['lang']);?></th>
							<th class="col-xs-3 text-center" data-field="codigopostal" data-sortable="true"><?php echo translate('Lbl_Zip_Code',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="acciones"><?php echo translate('Lbl_Actions_Home_Address',$GLOBALS['lang']);?></th>
						</tr>						
					</thead>
					<tbody>
						<?php
							if($stmt = $mysqli->prepare("SELECT d.id, d.calle, d.nro_calle, p.nombre, d.localidad, d.departamento, d.piso, d.codigo_postal, d.entre_calle_1, d.entre_calle_2 FROM finan_cli.domicilio d, finan_cli.usuario u, finan_cli.provincia p, finan_cli.usuario_x_domicilio ud WHERE u.id LIKE(?) AND p.id = d.id_provincia AND ud.id_usuario = u.id AND ud.id_domicilio = d.id")) 
							{
								$stmt->bind_param('s', $_SESSION['username']);
								$stmt->execute();    // Ejecuta la consulta preparada.
								$stmt->store_result();
						 
								// Obtiene las variables del resultado.
								$stmt->bind_result($id_domicilio, $user_domicilio_calle, $user_domicilio_nro_calle, $user_domicilio_id_provincia, $user_domicilio_localidad, $user_domicilio_departamento, $user_domicilio_piso, $user_domicilio_codigo_postal, $user_domicilio_entre_calles_1, $user_domicilio_entre_calles_2);
								
								while($stmt->fetch())
								{		
									echo '<tr>';
									echo '<td>'.$user_domicilio_calle.'</td>';
									echo '<td>'.$user_domicilio_nro_calle.'</td>';
									echo '<td>'.$user_domicilio_id_provincia.'</td>';
									echo '<td>'.$user_domicilio_localidad.'</td>';
									if(empty($user_domicilio_departamento)) echo '<td> --- </td>'; 
									else echo '<td>'.$user_domicilio_departamento.'</td>';
									if(empty($user_domicilio_piso)) echo '<td> --- </td>'; 
									else echo '<td>'.$user_domicilio_piso.'</td>';
									if(empty($user_domicilio_codigo_postal)) echo '<td> --- </td>'; 
									else echo '<td>'.$user_domicilio_codigo_postal.'</td>';	
									echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Address',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Remove_Domicilio',$GLOBALS['lang']).'\',\''.$_SESSION['username'].'\',\''.$id_domicilio.'\')"><i class="fas fa-trash-alt"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Address',$GLOBALS['lang']).'" onclick="modificarDomicilio(\''.$_SESSION['username'].'\',\''.$id_domicilio.'\')"><i class="fas fa-edit"></i></button></td>';
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
	<div class="panel-group" style="padding-bottom:50px;">				
		<div class="panel panel-default" style="margin-left:30px;margin-right:30px;">
		  <div id="panel-title-header-2" class="panel-heading">
			<h3 class="panel-title"><?php echo translate('Lbl_Phones',$GLOBALS['lang']).': '.$_SESSION['username']; ?></h3>
		  </div>
		  <div id="apDiv2" class="panel-body">
			<div id="toolbar2" style="margin-left:-95px; margin-top:-2px;">
				<button type="button" class="btn" data-toggle="tooltip" data-placement="top" onclick="nuevoTelefono('<?php echo $_SESSION['username']; ?>');" title="<?php echo translate('Lbl_New_Phone',$GLOBALS['lang']);?>" ><i class="fas fa-phone"></i></button>
			</div>
			<div id="img_loader_8"></div>	
			<div id="tablaadminphonesusers" class="table-responsive">
				<table id="tableadminphonesuserst" data-classes="table table-hover table-condensed"
				   data-striped="true" data-pagination="true" data-show-export="true" data-export-options='{"fileName": "<?php echo translate('Lbl_Phones',$GLOBALS['lang']); ?>"}'
				   data-export-types="['excel','pdf','csv','txt']"
				   data-search="true" data-search-align="left" data-toolbar="#toolbar2" data-toolbar-align="right">
					<thead>
						<tr>
							<th class="col-xs-1 text-center" data-field="tipotelefono" data-sortable="true"><?php echo translate('Lbl_Type_Phone',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="nrotelefono" data-sortable="true"><?php echo translate('Lbl_Number_Phone',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="acciones"><?php echo translate('Lbl_Actions_Phone',$GLOBALS['lang']);?></th>
						</tr>						
					</thead>
					<tbody>
						<?php
							if($stmt = $mysqli->prepare("SELECT t.id, tt.nombre, t.numero FROM finan_cli.telefono t, finan_cli.usuario u, finan_cli.tipo_telefono tt, finan_cli.usuario_x_telefono ut WHERE u.id LIKE(?) AND tt.id = t.tipo_telefono AND ut.id_usuario = u.id AND ut.id_telefono = t.id")) 
							{
								$stmt->bind_param('s', $_SESSION['username']);
								$stmt->execute();    // Ejecuta la consulta preparada.
								$stmt->store_result();
						 
								// Obtiene las variables del resultado.
								$stmt->bind_result($id_telefono, $user_tipo_telefono, $user_numero_telefono);
								
								while($stmt->fetch())
								{		
									echo '<tr>';
									echo '<td>'.$user_tipo_telefono.'</td>';
									echo '<td>'.$user_numero_telefono.'</td>';
									echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Phone',$GLOBALS['lang']).'" onclick="confirmar_accion_2(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Remove_Telefono',$GLOBALS['lang']).'\',\''.$_SESSION['username'].'\',\''.$id_telefono.'\')"><i class="fas fa-phone-slash"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Phone',$GLOBALS['lang']).'" onclick="modificarTelefono(\''.$_SESSION['username'].'\',\''.$id_telefono.'\')"><i class="fas fa-phone-volume"></i></button></td>';
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
			$('#tableadminaddressuserst').bootstrapTable({locale:'es-AR'});
			$('#tableadminphonesuserst').bootstrapTable({locale:'es-AR'});
			$('#nameuseri').focus();
		});
	</script>	
</body>
</html>