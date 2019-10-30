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
	<title><?php echo translate('Lbl_Admin_Plans',$GLOBALS['lang']); ?></title>
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
		function nuevoPlan()
		{
			document.getElementById("btnNuevoPlan").disabled = true;
			var urlnp = "./acciones/nuevoplan.php";
			var tagnp = $("<div id='dialognewplan'></div>");
			$('#img_loader_2').show();
			
			$.ajax({
				url: urlnp,
				method: "POST",
				data: {},
				success: function(dataresponse, statustext, response){
					$('#img_loader_2').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					tagnp.html(dataresponse).dialog({
					  show: "blind",
					  hide: "explode",
					  height: "auto",
					  width: "auto",					  
					  modal: true, 
					  title: "<?php echo translate('Lbl_New_Plan',$GLOBALS['lang']);?>",
					  autoResize:true,
							close: function(){
									tagnp.dialog('destroy').remove()
							}
					}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
					
					$( "#planespecialplanni" ).change(function() 
					{
						if($( "#planespecialplanni" ).val() == "0")
						{
							$( "#codigoplandpni" ).prop( "disabled", true );
							$( "#codigoplandpni" ).val("0");
						}
					    else
						{														
							$( "#codigoplandpni" ).prop( "disabled", false );
							$( "#codigoplandpni" ).focus();
						}
					});	
					
					$( "#soportacashbackplanni" ).change(function() 
					{
						if($( "#soportacashbackplanni" ).val() == "0")
						{
							$( "#minimocompracashbackplanni" ).prop( "disabled", true );
							$( "#maximoextraccioncashbackplanni" ).prop( "disabled", true );
							$( "#minimocompracashbackplanni" ).val("0.00");
							$( "#maximoextraccioncashbackplanni" ).val("0.00");
						}
					    else
						{														
							$( "#minimocompracashbackplanni" ).prop( "disabled", false );
							$( "#maximoextraccioncashbackplanni" ).prop( "disabled", false );
							$( "#minimocompracashbackplanni" ).focus();
						}
					});					
					tagnp.dialog('open');
					
					$('#recargoplanni').maskNumber();
					$('#minimocompracashbackplanni').maskNumber();
					$('#maximoextraccioncashbackplanni').maskNumber();
					$('#montodesdeplanni').maskNumber();
					$('#montohastaplanni').maskNumber();
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_2').hide();
				}
			});
			document.getElementById("btnNuevoPlan").disabled = false;
		}
    </script>
	
	<script type="text/javascript">
		function guardarNuevoPlan(formularionp)
		{
			document.getElementById("btnCargarNP").disabled = true;
			if($( "#planidni" ).val().length == 0)
			{
				$('#planidni').prop('title', '<?php echo translate('Msg_A_Plan_Id_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#planidni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#planidni" ).focus();
				document.getElementById("btnCargarNP").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#planidni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#planidni" ).tooltip('destroy');
			}			
			
			if($( "#nameplanni" ).val().length == 0)
			{
				$('#nameplanni').prop('title', '<?php echo translate('Msg_A_Plan_Name_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#nameplanni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#nameplanni" ).focus();
				document.getElementById("btnCargarNP").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#nameplanni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#nameplanni" ).tooltip('destroy');
			}
			
			if($( "#cuotadesdeplanni" ).val().length == 0)
			{
				$('#cuotadesdeplanni').prop('title', '<?php echo translate('Msg_A_Number_Fee_From_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#cuotadesdeplanni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#cuotadesdeplanni" ).focus();
				document.getElementById("btnCargarNP").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#cuotadesdeplanni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#cuotadesdeplanni" ).tooltip('destroy');
			}

			if($( "#cuotadesdeplanni" ).val().length != 0)
			{			
				if (isNaN($( "#cuotadesdeplanni" ).val()) || $( "#cuotadesdeplanni" ).val() % 1 != 0)
				{
					$('#cuotadesdeplanni').prop('title', '<?php echo translate('Msg_A_Number_Fee_From_Must_Enter_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#cuotadesdeplanni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#cuotadesdeplanni" ).focus();
					document.getElementById("btnCargarNP").disabled = false;
					return;
				}
				else
				{
					$(function() {
						$( "#cuotadesdeplanni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#cuotadesdeplanni" ).tooltip('destroy');
				}
			}			
			
			if($( "#cuotahastaplanni" ).val().length == 0)
			{
				$('#cuotahastaplanni').prop('title', '<?php echo translate('Msg_A_Number_Fee_To_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#cuotahastaplanni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#cuotahastaplanni" ).focus();
				document.getElementById("btnCargarNP").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#cuotahastaplanni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#cuotahastaplanni" ).tooltip('destroy');
			}

			if($( "#cuotahastaplanni" ).val().length != 0)
			{			
				if (isNaN($( "#cuotahastaplanni" ).val()) || $( "#cuotahastaplanni" ).val() % 1 != 0)
				{
					$('#cuotahastaplanni').prop('title', '<?php echo translate('Msg_A_Number_Fee_To_Must_Enter_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#cuotahastaplanni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#cuotahastaplanni" ).focus();
					document.getElementById("btnCargarNP").disabled = false;
					return;
				}
				else
				{
					$(function() {
						$( "#cuotahastaplanni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#cuotahastaplanni" ).tooltip('destroy');
				}
			}
			
			if($( "#cuotahastaplanni" ).val() < $( "#cuotadesdeplanni" ).val())
			{
				$( "#cuotahastaplanni" ).focus();
				document.getElementById("btnCargarNP").disabled = false;
				mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_Fee_From_Plan_Cant_be_older_To',$GLOBALS['lang']);?>");
				return;
			}
			
			if($( "#nrocomercioni" ).val().length == 0)
			{
				$('#nrocomercioni').prop('title', '<?php echo translate('Msg_A_Number_Merchant_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#nrocomercioni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#nrocomercioni" ).focus();
				document.getElementById("btnCargarNP").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#nrocomercioni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#nrocomercioni" ).tooltip('destroy');
			}
			
			if($( "#recargoplanni" ).val().length == 0)
			{
				$('#recargoplanni').prop('title', '<?php echo translate('Msg_A_Percentage_Charge_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#recargoplanni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#recargoplanni" ).focus();
				document.getElementById("btnCargarNP").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#recargoplanni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#recargoplanni" ).tooltip('destroy');
			}

			if($( "#recargoplanni" ).val().length != 0)
			{			
				if (isNaN($( "#recargoplanni" ).val().replace(/,/g,"")))
				{
					$('#recargoplanni').prop('title', '<?php echo translate('Msg_A_Percentage_Charge_Must_Enter_Number',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#recargoplanni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#recargoplanni" ).focus();
					document.getElementById("btnCargarNP").disabled = false;
					return;
				}
				else
				{
					$(function() {
						$( "#recargoplanni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#recargoplanni" ).tooltip('destroy');
				}
			}			
			

			if($( "#planespecialplanni" ).val() == '1')
			{
				if($( "#codigoplandpni" ).val().length == 0)
				{
					$('#codigoplandpni').prop('title', '<?php echo translate('Msg_A_Code_Plan_DP_Must_Enter',$GLOBALS['lang']);?>');
					$(function() {
						$( "#codigoplandpni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#codigoplandpni" ).focus();
					document.getElementById("btnCargarNP").disabled = false;
					return;
				}
				else 
				{
					$(function() {
						$( "#codigoplandpni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});				
					$( "#codigoplandpni" ).tooltip('destroy');
				}

				if($( "#codigoplandpni" ).val().length != 0)
				{			
					if (isNaN($( "#codigoplandpni" ).val()) || $( "#codigoplandpni" ).val() % 1 != 0)
					{
						$('#codigoplandpni').prop('title', '<?php echo translate('Msg_A_Code_Plan_DP_Must_Enter_Number_Whole',$GLOBALS['lang']);?>');					
						$(function() {
							$( "#codigoplandpni" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});
						$( "#codigoplandpni" ).focus();
						document.getElementById("btnCargarNP").disabled = false;
						return;
					}
					else
					{
						$(function() {
							$( "#codigoplandpni" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});					
						$( "#codigoplandpni" ).tooltip('destroy');
					}
				}				
			}			
			
			if($( "#soportacashbackplanni" ).val() == '1')
			{
				if($( "#minimocompracashbackplanni" ).val().length == 0)
				{
					$('#minimocompracashbackplanni').prop('title', '<?php echo translate('Msg_A_Min_Buy_Cashback_Must_Enter',$GLOBALS['lang']);?>');
					$(function() {
						$( "#minimocompracashbackplanni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#minimocompracashbackplanni" ).focus();
					document.getElementById("btnCargarNP").disabled = false;
					return;
				}
				else 
				{
					$(function() {
						$( "#minimocompracashbackplanni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});				
					$( "#minimocompracashbackplanni" ).tooltip('destroy');
				}

				if($( "#minimocompracashbackplanni" ).val().length != 0)
				{			
					if (isNaN($( "#minimocompracashbackplanni" ).val().replace(/,/g,"")))
					{
						$('#minimocompracashbackplanni').prop('title', '<?php echo translate('Msg_A_Min_Buy_Cashback_Must_Enter_Number',$GLOBALS['lang']);?>');					
						$(function() {
							$( "#minimocompracashbackplanni" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});
						$( "#minimocompracashbackplanni" ).focus();
						document.getElementById("btnCargarNP").disabled = false;
						return;
					}
					else
					{
						$(function() {
							$( "#minimocompracashbackplanni" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});					
						$( "#minimocompracashbackplanni" ).tooltip('destroy');
					}
				}
				
				if($( "#maximoextraccioncashbackplanni" ).val().length == 0)
				{
					$('#maximoextraccioncashbackplanni').prop('title', '<?php echo translate('Msg_A_Max_Amount_Cashback_Must_Enter',$GLOBALS['lang']);?>');
					$(function() {
						$( "#maximoextraccioncashbackplanni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#maximoextraccioncashbackplanni" ).focus();
					document.getElementById("btnCargarNP").disabled = false;
					return;
				}
				else 
				{
					$(function() {
						$( "#maximoextraccioncashbackplanni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});				
					$( "#maximoextraccioncashbackplanni" ).tooltip('destroy');
				}

				if($( "#maximoextraccioncashbackplanni" ).val().length != 0)
				{			
					if (isNaN($( "#maximoextraccioncashbackplanni" ).val().replace(/,/g,"")))
					{
						$('#maximoextraccioncashbackplanni').prop('title', '<?php echo translate('Msg_A_Max_Amount_Cashback_Must_Enter_Number',$GLOBALS['lang']);?>');					
						$(function() {
							$( "#maximoextraccioncashbackplanni" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});
						$( "#maximoextraccioncashbackplanni" ).focus();
						document.getElementById("btnCargarNP").disabled = false;
						return;
					}
					else
					{
						$(function() {
							$( "#maximoextraccioncashbackplanni" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});					
						$( "#maximoextraccioncashbackplanni" ).tooltip('destroy');
					}
				}				
			}
			
			if($( "#montodesdeplanni" ).val().length == 0)
			{
				$('#montodesdeplanni').prop('title', '<?php echo translate('Msg_A_Amount_From_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#montodesdeplanni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#montodesdeplanni" ).focus();
				document.getElementById("btnCargarNP").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#montodesdeplanni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#montodesdeplanni" ).tooltip('destroy');
			}

			if($( "#montodesdeplanni" ).val().length != 0)
			{			
				if (isNaN($( "#montodesdeplanni" ).val().replace(/,/g,"")))
				{
					$('#montodesdeplanni').prop('title', '<?php echo translate('Msg_A_Amount_From_Must_Enter_Number',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#montodesdeplanni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#montodesdeplanni" ).focus();
					document.getElementById("btnCargarNP").disabled = false;
					return;
				}
				else
				{
					$(function() {
						$( "#montodesdeplanni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#montodesdeplanni" ).tooltip('destroy');
				}
			}
			
			if($( "#montohastaplanni" ).val().length == 0)
			{
				$('#montohastaplanni').prop('title', '<?php echo translate('Msg_A_Amount_To_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#montohastaplanni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#montohastaplanni" ).focus();
				document.getElementById("btnCargarNP").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#montohastaplanni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#montohastaplanni" ).tooltip('destroy');
			}

			if($( "#montohastaplanni" ).val().length != 0)
			{			
				if (isNaN($( "#montohastaplanni" ).val().replace(/,/g,"")))
				{
					$('#montohastaplanni').prop('title', '<?php echo translate('Msg_A_Amount_To_Must_Enter_Number',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#montohastaplanni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#montohastaplanni" ).focus();
					document.getElementById("btnCargarNP").disabled = false;
					return;
				}
				else
				{
					$(function() {
						$( "#montohastaplanni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#montohastaplanni" ).tooltip('destroy');
				}
			}

			if($( "#montohastaplanni" ).val() < $( "#montodesdeplanni" ).val())
			{
				$( "#montohastaplanni" ).focus();
				document.getElementById("btnCargarNP").disabled = false;
				mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_Amount_From_Plan_Cant_be_older_To',$GLOBALS['lang']);?>");
				return;
			}
			
			if($( "#planisoni" ).val().length == 0)
			{
				$('#planisoni').prop('title', '<?php echo translate('Msg_A_ISO_Plan_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#planisoni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#planisoni" ).focus();
				document.getElementById("btnCargarNP").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#planisoni" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#planisoni" ).tooltip('destroy');
			}
			
			var urlggnp = "./acciones/guardarnuevoplan.php";
			$('#img_loader_5').show();
			
			$.ajax({
				url: urlggnp,
				method: "POST",
				data: { idSucursal: $( "#sucursalplanni" ).val(), idTarjeta: $( "#tarjetaplanni" ).val(), idPlan: $( "#planidni" ).val(), descripcionPlan: $( "#nameplanni" ).val(), cuotaDesde: $( "#cuotadesdeplanni" ).val(), cuotaHasta: $( "#cuotahastaplanni" ).val(), nroComercio: $( "#nrocomercioni" ).val(), nodo: $( "#nodoplanni" ).val(), porcentajeRecargo: (($( "#recargoplanni" ).val().replace(/,/g,""))*100.00), planEspecial: $( "#planespecialplanni" ).val(), codigoDP: $( "#codigoplandpni" ).val(), poolID: $( "#poolterminalsplanni" ).val(), soportaCashback: $( "#soportacashbackplanni" ).val(), minCompraCashback: (($( "#minimocompracashbackplanni" ).val().replace(/,/g,""))*100.00), maxExtraccionCashback: (($( "#maximoextraccioncashbackplanni" ).val().replace(/,/g,""))*100.00), montoDesde: (($( "#montodesdeplanni" ).val().replace(/,/g,""))*100.00), montoHasta: (($( "#montohastaplanni" ).val().replace(/,/g,""))*100.00), planISO: $( "#planisoni" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_5').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_New_Plan_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialognewplan').dialog('close');
						$('#tableadminplant').bootstrapTable('load',JSON.parse(datTable));
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_5').hide();
				}
			});
			document.getElementById("btnCargarNP").disabled = false;
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
						$('#tableadminplant').bootstrapTable('load',JSON.parse(datTable));
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
		function modificarPlan(planId)
		{
			document.getElementById("btnModificarPlan"+planId).disabled = true;
			var urlmp = "./acciones/modificarplan.php";
			var tagmp = $("<div id='dialogmodifyplan'></div>");
			$('#img_loader_2').show();
			
			$.ajax({
				url: urlmp,
				method: "POST",
				data: {idPlan: planId},
				success: function(dataresponse, statustext, response){
					$('#img_loader_2').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					tagmp.html(dataresponse).dialog({
					  show: "blind",
					  hide: "explode",
					  height: "auto",
					  width: "auto",					  
					  modal: true, 
					  title: "<?php echo translate('Msg_Modify_Plan',$GLOBALS['lang']);?>",
					  autoResize:true,
							close: function(){
									tagmp.dialog('destroy').remove()
							}
					}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
					
					$( "#planespecialplani" ).change(function() 
					{
						if($( "#planespecialplani" ).val() == "0")
						{
							$( "#codigoplandpi" ).prop( "disabled", true );
							$( "#codigoplandpi" ).val("0");
						}
					    else
						{														
							$( "#codigoplandpi" ).prop( "disabled", false );
							$( "#codigoplandpi" ).focus();
						}
					});	
					
					$( "#soportacashbackplani" ).change(function() 
					{
						if($( "#soportacashbackplani" ).val() == "0")
						{
							$( "#minimocompracashbackplani" ).prop( "disabled", true );
							$( "#maximoextraccioncashbackplani" ).prop( "disabled", true );
							$( "#minimocompracashbackplani" ).val("0.00");
							$( "#maximoextraccioncashbackplani" ).val("0.00");
						}
					    else
						{														
							$( "#minimocompracashbackplani" ).prop( "disabled", false );
							$( "#maximoextraccioncashbackplani" ).prop( "disabled", false );
							$( "#minimocompracashbackplani" ).focus();
						}
					});					
					tagmp.dialog('open');
					
					$('#recargoplani').maskNumber();
					$('#minimocompracashbackplani').maskNumber();
					$('#maximoextraccioncashbackplani').maskNumber();
					$('#montodesdeplani').maskNumber();
					$('#montohastaplani').maskNumber();
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_2').hide();
				}
			});
			document.getElementById("btnModificarPlan"+planId).disabled = false;
		}
    </script>	
	
	<script type="text/javascript">
		function guardarModificacionPlan(formulariomp, idPlan)
		{
			document.getElementById("btnCargarMP").disabled = true;
			if($( "#planidi" ).val().length == 0)
			{
				$('#planidi').prop('title', '<?php echo translate('Msg_A_Plan_Id_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#planidi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#planidi" ).focus();
				document.getElementById("btnCargarMP").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#planidi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#planidi" ).tooltip('destroy');
			}			
			
			if($( "#nameplani" ).val().length == 0)
			{
				$('#nameplani').prop('title', '<?php echo translate('Msg_A_Plan_Name_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#nameplani" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#nameplani" ).focus();
				document.getElementById("btnCargarMP").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#nameplani" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#nameplani" ).tooltip('destroy');
			}
			
			if($( "#cuotadesdeplani" ).val().length == 0)
			{
				$('#cuotadesdeplani').prop('title', '<?php echo translate('Msg_A_Number_Fee_From_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#cuotadesdeplani" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#cuotadesdeplani" ).focus();
				document.getElementById("btnCargarMP").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#cuotadesdeplani" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#cuotadesdeplani" ).tooltip('destroy');
			}

			if($( "#cuotadesdeplani" ).val().length != 0)
			{			
				if (isNaN($( "#cuotadesdeplani" ).val()) || $( "#cuotadesdeplani" ).val() % 1 != 0)
				{
					$('#cuotadesdeplani').prop('title', '<?php echo translate('Msg_A_Number_Fee_From_Must_Enter_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#cuotadesdeplani" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#cuotadesdeplani" ).focus();
					document.getElementById("btnCargarMP").disabled = false;
					return;
				}
				else
				{
					$(function() {
						$( "#cuotadesdeplani" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#cuotadesdeplani" ).tooltip('destroy');
				}
			}			
			
			if($( "#cuotahastaplani" ).val().length == 0)
			{
				$('#cuotahastaplani').prop('title', '<?php echo translate('Msg_A_Number_Fee_To_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#cuotahastaplani" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#cuotahastaplani" ).focus();
				document.getElementById("btnCargarMP").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#cuotahastaplani" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#cuotahastaplani" ).tooltip('destroy');
			}

			if($( "#cuotahastaplani" ).val().length != 0)
			{			
				if (isNaN($( "#cuotahastaplani" ).val()) || $( "#cuotahastaplani" ).val() % 1 != 0)
				{
					$('#cuotahastaplani').prop('title', '<?php echo translate('Msg_A_Number_Fee_To_Must_Enter_Whole',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#cuotahastaplani" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#cuotahastaplani" ).focus();
					document.getElementById("btnCargarMP").disabled = false;
					return;
				}
				else
				{
					$(function() {
						$( "#cuotahastaplani" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#cuotahastaplani" ).tooltip('destroy');
				}
			}
			
			if($( "#cuotahastaplani" ).val() < $( "#cuotadesdeplani" ).val())
			{
				$( "#cuotahastaplani" ).focus();
				document.getElementById("btnCargarMP").disabled = false;
				mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_Fee_From_Plan_Cant_be_older_To',$GLOBALS['lang']);?>");
				return;
			}
			
			if($( "#nrocomercioi" ).val().length == 0)
			{
				$('#nrocomercioi').prop('title', '<?php echo translate('Msg_A_Number_Merchant_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#nrocomercioi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#nrocomercioi" ).focus();
				document.getElementById("btnCargarMP").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#nrocomercioi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#nrocomercioi" ).tooltip('destroy');
			}
			
			if($( "#recargoplani" ).val().length == 0)
			{
				$('#recargoplani').prop('title', '<?php echo translate('Msg_A_Percentage_Charge_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#recargoplani" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#recargoplani" ).focus();
				document.getElementById("btnCargarMP").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#recargoplani" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#recargoplani" ).tooltip('destroy');
			}

			if($( "#recargoplani" ).val().length != 0)
			{			
				if (isNaN($( "#recargoplani" ).val().replace(/,/g,"")))
				{
					$('#recargoplani').prop('title', '<?php echo translate('Msg_A_Percentage_Charge_Must_Enter_Number',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#recargoplani" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#recargoplani" ).focus();
					document.getElementById("btnCargarMP").disabled = false;
					return;
				}
				else
				{
					$(function() {
						$( "#recargoplani" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#recargoplani" ).tooltip('destroy');
				}
			}			
			

			if($( "#planespecialplani" ).val() == '1')
			{
				if($( "#codigoplandpi" ).val().length == 0)
				{
					$('#codigoplandpi').prop('title', '<?php echo translate('Msg_A_Code_Plan_DP_Must_Enter',$GLOBALS['lang']);?>');
					$(function() {
						$( "#codigoplandpi" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#codigoplandpi" ).focus();
					document.getElementById("btnCargarMP").disabled = false;
					return;
				}
				else 
				{
					$(function() {
						$( "#codigoplandpi" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});				
					$( "#codigoplandpi" ).tooltip('destroy');
				}

				if($( "#codigoplandpi" ).val().length != 0)
				{			
					if (isNaN($( "#codigoplandpi" ).val()) || $( "#codigoplandpi" ).val() % 1 != 0)
					{
						$('#codigoplandpi').prop('title', '<?php echo translate('Msg_A_Code_Plan_DP_Must_Enter_Number_Whole',$GLOBALS['lang']);?>');					
						$(function() {
							$( "#codigoplandpi" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});
						$( "#codigoplandpi" ).focus();
						document.getElementById("btnCargarMP").disabled = false;
						return;
					}
					else
					{
						$(function() {
							$( "#codigoplandpi" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});					
						$( "#codigoplandpi" ).tooltip('destroy');
					}
				}				
			}			
			
			if($( "#soportacashbackplani" ).val() == '1')
			{
				if($( "#minimocompracashbackplani" ).val().length == 0)
				{
					$('#minimocompracashbackplani').prop('title', '<?php echo translate('Msg_A_Min_Buy_Cashback_Must_Enter',$GLOBALS['lang']);?>');
					$(function() {
						$( "#minimocompracashbackplani" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#minimocompracashbackplani" ).focus();
					document.getElementById("btnCargarMP").disabled = false;
					return;
				}
				else 
				{
					$(function() {
						$( "#minimocompracashbackplani" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});				
					$( "#minimocompracashbackplani" ).tooltip('destroy');
				}

				if($( "#minimocompracashbackplani" ).val().length != 0)
				{			
					if (isNaN($( "#minimocompracashbackplani" ).val().replace(/,/g,"")))
					{
						$('#minimocompracashbackplani').prop('title', '<?php echo translate('Msg_A_Min_Buy_Cashback_Must_Enter_Number',$GLOBALS['lang']);?>');					
						$(function() {
							$( "#minimocompracashbackplani" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});
						$( "#minimocompracashbackplani" ).focus();
						document.getElementById("btnCargarMP").disabled = false;
						return;
					}
					else
					{
						$(function() {
							$( "#minimocompracashbackplani" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});					
						$( "#minimocompracashbackplani" ).tooltip('destroy');
					}
				}
				
				if($( "#maximoextraccioncashbackplani" ).val().length == 0)
				{
					$('#maximoextraccioncashbackplani').prop('title', '<?php echo translate('Msg_A_Max_Amount_Cashback_Must_Enter',$GLOBALS['lang']);?>');
					$(function() {
						$( "#maximoextraccioncashbackplani" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#maximoextraccioncashbackplani" ).focus();
					document.getElementById("btnCargarMP").disabled = false;
					return;
				}
				else 
				{
					$(function() {
						$( "#maximoextraccioncashbackplani" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});				
					$( "#maximoextraccioncashbackplani" ).tooltip('destroy');
				}

				if($( "#maximoextraccioncashbackplani" ).val().length != 0)
				{			
					if (isNaN($( "#maximoextraccioncashbackplani" ).val().replace(/,/g,"")))
					{
						$('#maximoextraccioncashbackplani').prop('title', '<?php echo translate('Msg_A_Max_Amount_Cashback_Must_Enter_Number',$GLOBALS['lang']);?>');					
						$(function() {
							$( "#maximoextraccioncashbackplani" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});
						$( "#maximoextraccioncashbackplani" ).focus();
						document.getElementById("btnCargarMP").disabled = false;
						return;
					}
					else
					{
						$(function() {
							$( "#maximoextraccioncashbackplani" ).tooltip({
							   position: {
								  my: "center bottom",
								  at: "center top-10",
								  collision: "none"
							   }
							});
						});					
						$( "#maximoextraccioncashbackplani" ).tooltip('destroy');
					}
				}				
			}
			
			if($( "#montodesdeplani" ).val().length == 0)
			{
				$('#montodesdeplani').prop('title', '<?php echo translate('Msg_A_Amount_From_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#montodesdeplani" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#montodesdeplani" ).focus();
				document.getElementById("btnCargarMP").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#montodesdeplani" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#montodesdeplani" ).tooltip('destroy');
			}

			if($( "#montodesdeplani" ).val().length != 0)
			{			
				if (isNaN($( "#montodesdeplani" ).val().replace(/,/g,"")))
				{
					$('#montodesdeplani').prop('title', '<?php echo translate('Msg_A_Amount_From_Must_Enter_Number',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#montodesdeplanni" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#montodesdeplani" ).focus();
					document.getElementById("btnCargarMP").disabled = false;
					return;
				}
				else
				{
					$(function() {
						$( "#montodesdeplani" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#montodesdeplani" ).tooltip('destroy');
				}
			}
			
			if($( "#montohastaplani" ).val().length == 0)
			{
				$('#montohastaplani').prop('title', '<?php echo translate('Msg_A_Amount_To_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#montohastaplani" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#montohastaplani" ).focus();
				document.getElementById("btnCargarMP").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#montohastaplani" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#montohastaplani" ).tooltip('destroy');
			}

			if($( "#montohastaplani" ).val().length != 0)
			{			
				if (isNaN($( "#montohastaplani" ).val().replace(/,/g,"")))
				{
					$('#montohastaplani').prop('title', '<?php echo translate('Msg_A_Amount_To_Must_Enter_Number',$GLOBALS['lang']);?>');					
					$(function() {
						$( "#montohastaplani" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});
					$( "#montohastaplani" ).focus();
					document.getElementById("btnCargarMP").disabled = false;
					return;
				}
				else
				{
					$(function() {
						$( "#montohastaplani" ).tooltip({
						   position: {
							  my: "center bottom",
							  at: "center top-10",
							  collision: "none"
						   }
						});
					});					
					$( "#montohastaplani" ).tooltip('destroy');
				}
			}

			if($( "#montohastaplani" ).val() < $( "#montodesdeplani" ).val())
			{
				$( "#montohastaplani" ).focus();
				document.getElementById("btnCargarMP").disabled = false;
				mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_Amount_From_Plan_Cant_be_older_To',$GLOBALS['lang']);?>");
				return;
			}
			
			if($( "#planisoi" ).val().length == 0)
			{
				$('#planisoi').prop('title', '<?php echo translate('Msg_A_ISO_Plan_Must_Enter',$GLOBALS['lang']);?>');
				$(function() {
					$( "#planisoi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$( "#planisoi" ).focus();
				document.getElementById("btnCargarMP").disabled = false;
				return;
			}
			else 
			{
				$(function() {
					$( "#planisoi" ).tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$( "#planisoi" ).tooltip('destroy');
			}			
			
			var urlggnp = "./acciones/guardarmodificacionplan.php";
			$('#img_loader_5').show();
			
			$.ajax({
				url: urlggnp,
				method: "POST",
				data: { idSucursal: $( "#sucursalplani" ).val(), idTarjeta: $( "#tarjetaplani" ).val(), idPlan: $( "#planidi" ).val(), descripcionPlan: $( "#nameplani" ).val(), cuotaDesde: $( "#cuotadesdeplani" ).val(), cuotaHasta: $( "#cuotahastaplani" ).val(), nroComercio: $( "#nrocomercioi" ).val(), nodo: $( "#nodoplani" ).val(), porcentajeRecargo: (($( "#recargoplani" ).val().replace(/,/g,""))*100.00), planEspecial: $( "#planespecialplani" ).val(), codigoDP: $( "#codigoplandpi" ).val(), poolID: $( "#poolterminalsplani" ).val(), soportaCashback: $( "#soportacashbackplani" ).val(), minCompraCashback: (($( "#minimocompracashbackplani" ).val().replace(/,/g,""))*100.00), maxExtraccionCashback: (($( "#maximoextraccioncashbackplani" ).val().replace(/,/g,""))*100.00), montoDesde: (($( "#montodesdeplani" ).val().replace(/,/g,""))*100.00), montoHasta: (($( "#montohastaplani" ).val().replace(/,/g,""))*100.00), planIDO: idPlan, planISO: $( "#planisoi" ).val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_5').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Modify_Plan_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#dialogmodifyplan').dialog('close');
						$('#tableadminplant').bootstrapTable('load',JSON.parse(datTable));
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_5').hide();
				}
			});
			document.getElementById("btnCargarMP").disabled = false;
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
						
						$('#tableadminplant').bootstrapTable('load',JSON.parse(datTable));
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
		function borrar_plan(idPlan)
		{
			var urlbp = "./acciones/borrarplan.php";
			$('#img_loader_2').show();
			
			$.ajax({
				url: urlbp,
				method: "POST",
				data: { idPlan: idPlan },
				success: function(dataresponse, statustext, response){
					$('#img_loader_2').hide();
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Remove_Plan_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						var datTable = dataresponse.substring(dataresponse.indexOf('=:=:=:')+6);
						
						$('#tableadminplant').bootstrapTable('load',JSON.parse(datTable));
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
		function confirmar_accion_plan(titulo, mensaje, planId, planIdV, tarjeta)
		{
			document.getElementById("borrarPlan"+planId).disabled = true;
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
										
										borrar_plan(planId);                                                      
								},
								"<?php echo translate('Lbl_Button_NO',$GLOBALS['lang']);?>": function () {
										$("#confirmDialog").dialog('close');
										$('#img_loader_2').hide();
										document.getElementById("borrarPlan"+planId).disabled = false;
										return;
								}
						}
				}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
				mensaje = mensaje.replace("%1", planIdV);
				mensaje = mensaje.replace("%2", tarjeta);
				$( "#confirmDialog" ).html("<div id='confirmacionAccion'>"+mensaje+"?</div>");
				$('#img_loader_2').hide();
			document.getElementById("borrarPlan"+planId).disabled = false;
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
			<h3 class="panel-title"><?php echo translate('Lbl_Plans',$GLOBALS['lang']); ?></h3>
		  </div>
		  <div id="apDiv1" class="panel-body">
			<div id="toolbar" style="margin-left:-98px; margin-top:-1px;">
				<button type="button" id="btnNuevoPlan" class="btn" data-toggle="tooltip" data-placement="top" onclick="nuevoPlan();" title="<?php echo translate('Lbl_New_Plan',$GLOBALS['lang']);?>" ><i class="far fa-plus-square"></i></button>
			</div>
			<div id="img_loader" style="display:block;"></div>
			<div id="img_loader_2"></div>
			<div id="tablaadminplan" class="table-responsive" style="display:none;">
				<table id="tableadminplant" data-classes="table table-hover table-condensed"
				   data-striped="true" data-pagination="true" data-show-export="true" data-export-options='{"fileName": "<?php echo translate('File_Plans',$GLOBALS['lang']); ?>"}'
				   data-export-types="['excel','pdf','csv','txt']"
				   data-search="true" data-search-align="left" data-toolbar="#toolbar" data-toolbar-align="right">
					<thead>
						<tr>
							<th class="col-xs-1 text-center" data-field="sucursal" data-sortable="true"><?php echo translate('Lbl_Tender',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="idtarjeta" data-sortable="true"><?php echo translate('Lbl_Card_Id',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="tarjeta" data-sortable="true"><?php echo translate('Lbl_Card',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="idplan" data-sortable="true"><?php echo translate('Lbl_Plan_Id',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="plan" data-sortable="true"><?php echo translate('Lbl_Name_Plan',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="nrocomercio" data-sortable="true"><?php echo translate('Lbl_Number_Merchant_Plan',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="cuotas" data-sortable="true"><?php echo translate('Lbl_Count_Fees_Plan',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="nodo" data-sortable="true"><?php echo translate('Lbl_Name_Node_Plan',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="porcrecargo" data-sortable="true"><?php echo translate('Lbl_Percentage_Charge_Plan',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="sopcashback" data-sortable="true"><?php echo translate('Lbl_Allowed_Cashback_Plan',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="acciones"><?php echo translate('Lbl_Plan_Actions',$GLOBALS['lang']);?></th>
						</tr>						
					</thead>
					<tbody>
						<?php
							if ($stmt = $mysqli->prepare("SELECT p.branch_id, pm.payment_method_id, pm.payment_method_description, p.plan_id, p.plan_description, p.merchant_id, p.facility_payments_from, n.host_name, p.charge_percentage, p.cashback_allowed FROM tef.plans p, tef.paymentmethods pm, tef.hosts n  WHERE p.payment_method_id = pm.payment_method_id AND p.host_id = n.host_id ORDER BY p.branch_id, p.payment_method_id, p.plan_id, p.facility_payments_from LIMIT 5000")) 
							{
								$stmt->execute();    // Ejecuta la consulta preparada.
								$stmt->store_result();
						 
								// Obtiene las variables del resultado.
								$stmt->bind_result($id_branch_plan, $id_payment_method, $name_payment_method, $id_plan, $name_plan, $number_merchant_plan, $count_fees_plan, $name_node_plan, $percentaje_charge_plan, $allowed_cashback_plan);
								
								while($stmt->fetch())
								{		
									echo '<tr>';
									echo '<td>'.$id_branch_plan.'</td>';
									echo '<td>'.$id_payment_method.'</td>';
									echo '<td>'.$name_payment_method.'</td>';
									echo '<td>'.$id_plan.'</td>';
									echo '<td>'.$name_plan.'</td>';
									echo '<td>'.$number_merchant_plan.'</td>';
									echo '<td>'.$count_fees_plan.'</td>';
									echo '<td>'.$name_node_plan.'</td>';
									echo '<td>'.$percentaje_charge_plan.'</td>';
									echo '<td>'.$allowed_cashback_plan.'</td>';
									
									echo '<td><button type="button" id="borrarPlan'.$id_branch_plan.'--'.$id_payment_method.'--'.$id_plan.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Plan',$GLOBALS['lang']).'" onclick="confirmar_accion_plan(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Remove_Plan',$GLOBALS['lang']).'\',\''.$id_branch_plan.'--'.$id_payment_method.'--'.$id_plan.'\',\''.$id_plan.'\',\''.$name_payment_method.'\')"><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" id="btnModificarPlan'.$id_branch_plan.'--'.$id_payment_method.'--'.$id_plan.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Modify_Plan',$GLOBALS['lang']).'" onclick="modificarPlan(\''.$id_branch_plan.'--'.$id_payment_method.'--'.$id_plan.'\')"><i class="far fa-edit"></i></button></td>';
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
			$('#tablaadminplan').show();
			$('#tableadminplant').bootstrapTable({locale:'es-AR'});
		});
	</script>		
</body>
</html>