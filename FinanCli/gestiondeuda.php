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
	<title><?php echo translate('Lbl_Debt_Management',$GLOBALS['lang']); ?></title>
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
		function verCredito(idCredito, indiceTabla)
		{
			$('#indicetablaacesti').val(indiceTabla);
			var urlvc = "./acciones/gestiondeudacredito.php";
			var tagvc = $("<div id='dialogviewcredit'></div>");
			$('#img_loader').show();
			
			$.ajax({
				url: urlvc,
				method: "POST",
				data: { idCredito: idCredito },
				success: function(dataresponse, statustext, response){
					$('#img_loader').hide();
					
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
						
						var primerSeleccionE = 0;
						var totalRTab = $('#tablefeescreditclienttv').bootstrapTable('getOptions').totalRows;
						for(var i=0; i<totalRTab; i++)
						{
							if(primerSeleccionE == 1 && $('#tablefeescreditclienttv').bootstrapTable('getData')[i].seleccioncuota.indexOf('seleccioncuotanro') != -1)
							{								
								document.getElementById("seleccioncuotanro"+(i+1)).disabled = true;
								$('#seleccioncuotanro'+(i+1)).change(function() {
									if($(this).is(":checked")) 
									{
										var idCS = $(this).attr('id');
										var idCSSig = parseInt(idCS[idCS.length -1]);
										
										if((idCSSig+1) <= $('#tablefeescreditclienttv').bootstrapTable('getOptions').totalRows)
										{
											document.getElementById("seleccioncuotanro"+(idCSSig+1)).disabled = false;
										}
										
									}
									else
									{
										var idCS = $(this).attr('id');
										var idCSSig = parseInt(idCS[idCS.length -1]);
										
										idCSSig = idCSSig + 1;
										while(idCSSig <= $('#tablefeescreditclienttv').bootstrapTable('getOptions').totalRows)
										{
											document.getElementById("seleccioncuotanro"+idCSSig).checked = false;
											document.getElementById("seleccioncuotanro"+idCSSig).disabled = true;
											
											idCSSig++;
										}
									}										
								});									
							}
							
							if(primerSeleccionE == 0 && $('#tablefeescreditclienttv').bootstrapTable('getData')[i].seleccioncuota.indexOf('seleccioncuotanro') != -1)
							{
								document.getElementById("seleccioncuotanro"+(i+1)).disabled = false;
								
								$('#seleccioncuotanro'+(i+1)).change(function() {
									if($(this).is(":checked")) 
									{
										document.getElementById("btnPagoSeleccionCD").disabled = false;
										var idCS = $(this).attr('id');
										var idCSSig = parseInt(idCS[idCS.length -1]);
										
										if((idCSSig+1) <= $('#tablefeescreditclienttv').bootstrapTable('getOptions').totalRows)
										{
											document.getElementById("seleccioncuotanro"+(idCSSig+1)).disabled = false;
										}
										
									}
									else
									{
										document.getElementById("btnPagoSeleccionCD").disabled = true;
										var idCS = $(this).attr('id');
										var idCSSig = parseInt(idCS[idCS.length -1]);
										
										idCSSig = idCSSig + 1;
										while(idCSSig <= $('#tablefeescreditclienttv').bootstrapTable('getOptions').totalRows)
										{
											document.getElementById("seleccioncuotanro"+idCSSig).checked = false;
											document.getElementById("seleccioncuotanro"+idCSSig).disabled = true;
											
											idCSSig++;
										}
									}										
								});
								
								primerSeleccionE = 1;
							}
						}
						
						tagvc.dialog('open');
						$('#montototalcreditvi').maskNumber();
						$('#interesescreditvi').maskNumber();
					}
					else
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
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
		function buscarCreditosCliente()
		{
			if($('.search').find(':input').val().length == 0)
			{
				$('.search').find(':input').focus();
				mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_A_Customer_Must_Enter_To_Search_Credits',$GLOBALS['lang']);?>");
				return;
			}

			var urlbccd = "./acciones/buscardeudasclientedocumento.php";
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
						
						$('#tableadmindeudat').bootstrapTable('load',JSON.parse(datTable));
						$('#titulocreditoscliente').html('<?php echo translate('Lbl_Credits_Clients',$GLOBALS['lang']); ?>'+': '+dataresponse);						
						//mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else if(dataresponse.indexOf('<?php echo translate('Msg_Without_Credit_Client',$GLOBALS['lang']);?>') != -1)
					{
						$('#tableadmindeudat').bootstrapTable('removeAll');
						var menR = dataresponse.substring(0,dataresponse.indexOf('=::=::=::'));
						dataresponse = dataresponse.replace(menR+"=::=::=::","");
						
						$('#titulocreditoscliente').html('<?php echo translate('Lbl_Credits_Clients',$GLOBALS['lang']); ?>');
						$('.search').find(':input').focus();						
						mensaje_atencion("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR.replace("%1",dataresponse));
					}
					else
					{
						$('#tableadmindeudat').bootstrapTable('removeAll');
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
		}
    </script>

	<script type="text/javascript">
		function cancelarPagoCuotaCredito(idCuotaCredito)
		{
			var urlccc = "./acciones/cancelarcuotacredito.php";
			var tagccc = $("<div id='dialogcancelfeecredit'></div>");
			$('#img_loader_17').show();
			
			$.ajax({
				url: urlccc,
				method: "POST",
				data: { idCuotaCredito: idCuotaCredito },
				success: function(dataresponse, statustext, response){
					$('#img_loader_17').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_View_Cancel_Fee_Credit_OK',$GLOBALS['lang']);?>') != -1)
					{					
						dataresponse = dataresponse.replace('<?php echo translate('Msg_View_Cancel_Fee_Credit_OK',$GLOBALS['lang']);?>',"");
						
						tagccc.html(dataresponse).dialog({
						  show: "blind",
						  hide: "explode",
						  height: "auto",
						  width: "auto",					  
						  modal: true, 
						  title: "<?php echo translate('Msg_Cancel_Fee_Credit_Client',$GLOBALS['lang']);?>",
						  autoResize:true,
								close: function(){
										tagccc.dialog('destroy').remove()
								}
						}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");					
						tagccc.dialog('open');
					}
					else
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
					}					
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_17').hide();
				}
			});	
		}
    </script>
	
	<script type="text/javascript">
		function pagarCuotaCredito(idCredito, idCuotaCredito)
		{
			if($('#idcreditovi').val() != idCredito) return;
			var urlpcc = "./acciones/buscarcuotacredito.php";
			var tagpcc = $("<div id='dialogviewfeecredit'></div>");
			$('#img_loader_17').show();
			
			$.ajax({
				url: urlpcc,
				method: "POST",
				data: { idCredito: idCredito, idCuotaCredito: idCuotaCredito },
				success: function(dataresponse, statustext, response){
					$('#img_loader_17').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_View_Fee_Credit_OK2',$GLOBALS['lang']);?>') != -1)
					{					
						dataresponse = dataresponse.replace('<?php echo translate('Msg_View_Fee_Credit_OK2',$GLOBALS['lang']);?>',"");
						
						tagpcc.html(dataresponse).dialog({
						  show: "blind",
						  hide: "explode",
						  height: "auto",
						  width: "auto",					  
						  modal: true, 
						  title: "<?php echo translate('Msg_Payment_Fee_Credit',$GLOBALS['lang']);?>",
						  autoResize:true,
								close: function(){
										tagpcc.dialog('destroy').remove()
								}
						}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
						tagpcc.dialog('open');
						$('#montooriginalcuotacreditvi').maskNumber();
						$('#interescuotacreditvi').maskNumber();
						$('#montototalcuotacreditvi').maskNumber();
					}
					else
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
					}					
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_17').hide();
				}
			});	
		}
    </script>

	<script type="text/javascript">
		function guardarPagoCuotaCredito(formulariopcc)
		{
			if($('#idcreditovi').val() != formulariopcc.idcreditovc2i.value) return;
			
			if(formulariopcc.montototalcuotacreditvi.value.length == 0)
			{
				$(function() {
					$('#montototalcuotacreditvi').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$('#montototalcuotacreditvi').focus();
				return;
			}
			else 
			{
				$(function() {
					$('#montototalcuotacreditvi').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$('#montototalcuotacreditvi').tooltip('destroy');
			}
			
			var urlpcc = "./acciones/pagarcuotacredito.php";
			$('#img_loader_18').show();
			
			$.ajax({
				url: urlpcc,
				method: "POST",
				data: { idCredito: formulariopcc.idcreditovc2i.value, idCuotaCredito: formulariopcc.idcuotacreditovi.value, montoPago: ((formulariopcc.montototalcuotacreditvi.value.replace(/,/g,""))*100.00), tokenVS: $('#tokenvalidsuppagocuotacrei').val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_18').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Pay_Fee_Credit_OK',$GLOBALS['lang']);?>') != -1)
					{					
						var menR = dataresponse.substring(0, dataresponse.indexOf('=:=:='));
						dataresponse = dataresponse.replace('<?php echo translate('Msg_Pay_Fee_Credit_OK',$GLOBALS['lang']);?>=:=:=',"");
						var estadoCredAc = dataresponse.substring(0, dataresponse.indexOf('=::=::='));
						dataresponse = dataresponse.replace(estadoCredAc+'=::=::=',"");
						var datosCompPC = dataresponse.substring(0, dataresponse.indexOf('=:::=:::='));
						dataresponse = dataresponse.replace(datosCompPC+'=:::=:::=',"");
						var datosTablaCuotas = dataresponse.substring(0, dataresponse.indexOf('=::::=::::='));
						dataresponse = dataresponse.replace(datosTablaCuotas+'=::::=::::=',"");
						var cantidadCuotasP = parseInt(dataresponse);
						
						$('#tablefeescreditclienttv').bootstrapTable('load',JSON.parse(datosTablaCuotas));
						$('#estadocreditvi').val(estadoCredAc);
						$('#tableadmindeudat').bootstrapTable('updateCell', {index: $('#indicetablaacesti').val(), field: 'estado', value: estadoCredAc});
						if(cantidadCuotasP == 1) 
						{
							$('#btnPagoTotalCD').hide();
							document.getElementById("btnPagoTotalCD").disabled = true;
						}
						var infoImprPC = datosCompPC.split("|");
						if(cantidadCuotasP >= 1) document.getElementById("btnPagoSeleccionCD").disabled = true;

						if(cantidadCuotasP == 1) 
						{
							document.getElementById("seleccioncuotanro"+$('#tablefeescreditclienttv').bootstrapTable('getOptions').totalRows).disabled = true;
							$('#btnPagoSeleccionCD').hide();
							$('#tablefeescreditclienttv').bootstrapTable('updateCell', {index: ($('#tablefeescreditclienttv').bootstrapTable('getOptions').totalRows-1), field: 'seleccioncuota', value: '-'});							
						}
						
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
						
						$('#dialogviewfeecredit').dialog('destroy').remove();
						imprimirPagoCuota(infoImprPC[0],infoImprPC[1],infoImprPC[2],infoImprPC[3],infoImprPC[4],infoImprPC[5],infoImprPC[6],infoImprPC[7],infoImprPC[8],infoImprPC[9],infoImprPC[10],infoImprPC[11],infoImprPC[12]);
					}
					else if(dataresponse.indexOf('<?php echo translate('Msg_Need_Authorize_Pay_Fee_Credit',$GLOBALS['lang']);?>') != -1)
					{
						var tokenR = dataresponse.substring(dataresponse.indexOf('=::=::=::')+9, dataresponse.indexOf('=:::=:::'));
						dataresponse = dataresponse.replace("<?php echo translate('Msg_Need_Authorize_Pay_Fee_Credit',$GLOBALS['lang']); ?>=::=::=::","");
						dataresponse = dataresponse.replace(tokenR+"=:::=:::","");
												
						$('#tokenvalidsuppagocuotacrei').val(tokenR);
						var tagvpcc = $("<div id='dialogvalidsuppagocuotacredit'></div>");
						
						tagvpcc.html(dataresponse).dialog({
						  show: "blind",
						  hide: "explode",
						  height: "auto",
						  width: "auto",					  
						  modal: true, 
						  title: "<?php echo translate('Lbl_Authorize_Pay_Fee_Credit2',$GLOBALS['lang']);?>",
						  autoResize:true,
								close: function(){
										tagvpcc.dialog('destroy').remove()
								}
						}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
						tagvpcc.dialog('open');					
					}
					else
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
					}					
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_18').hide();
				}
			});	
		}
    </script>

	<script type="text/javascript">
		function imprimirPagoCuota(fechaCreditoImp, nroCreditoP, nroCuotaP, tipoClienteCreditoImp, datosCliCreditoImp, sucursalCreditoImp, usuarioCreditoImp, montoPagado, proximoPagoCreditoImp, tipoDocumentoCreditoImp, documentoCreditoImp, montoCuota, montoInteres)
		{
			var urlinc = "<?php echo $GLOBALS['imprimir_pago_cuota_credito']; ?>";

			$.ajax({
				url: urlinc,
				method: "POST",
				data: { numeroCredito: nroCreditoP, fecha: fechaCreditoImp, nroCuota: nroCuotaP, cliente: datosCliCreditoImp, sucursal: sucursalCreditoImp, tipoCliente: tipoClienteCreditoImp, usuario: usuarioCreditoImp, montoPagado: montoPagado, proximoPago: proximoPagoCreditoImp, tipoDocumento: tipoDocumentoCreditoImp, documento: documentoCreditoImp, montoCuota: montoCuota, montoInteres: montoInteres },
				success: function(dataresponse, statustext, response){
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_The_New_Pay_Fee_Credit_Was_Printed_Correctly',$GLOBALS['lang']);?>') != -1)
					{
						console.log('<?php echo translate('Msg_The_New_Pay_Fee_Credit_Was_Printed_Correctly',$GLOBALS['lang']);?>');
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
		function guardarAutorizacionSupervisorPagoCuota(formularionaspcc)
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
			
			var urlvcspcc = "./acciones/verificarcredencialessupervisorpagocuotacredito.php";
			$('#img_loader_13').show();
			
			
			var p221 = document.createElement("input");
		 			
			formularionaspcc.appendChild(p221);
			p221.name = "p221";
			p221.type = "hidden";
			
			p221.value = hex_sha512(formularionaspcc.passwordsupervisorni.value);
			
			if(formularionaspcc.passwordsupervisorni.value == "") p221.value = "";
			formularionaspcc.passwordsupervisorni.value = "";
					
			$.ajax({
				url: urlvcspcc,
				method: "POST",
				data: { usuarioSupervisor: formularionaspcc.usuariosupervisorni.value, claveSupervisor: p221.value, idCredito: $('#idcreditovc2i').val(), idCuotaCredito: $('#idcuotacreditovi').val(), montoPago: (($('#montototalcuotacreditvi').val().replace(/,/g,""))*100.00), tokenVS: $('#tokenvalidsuppagocuotacrei').val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_13').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = "<?php echo translate('Msg_Pay_Fee_Credit_OK',$GLOBALS['lang']);?>";
						dataresponse = dataresponse.replace('<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']);?>=:=:=',"");
						var estadoCredAc = dataresponse.substring(0, dataresponse.indexOf('=::=::='));
						dataresponse = dataresponse.replace(estadoCredAc+'=::=::=',"");
						var datosCompPC = dataresponse.substring(0, dataresponse.indexOf('=:::=:::='));
						dataresponse = dataresponse.replace(datosCompPC+'=:::=:::=',"");
						var datosTablaCuotas = dataresponse.substring(0, dataresponse.indexOf('=::::=::::='));
						dataresponse = dataresponse.replace(datosTablaCuotas+'=::::=::::=',"");
						var cantidadCuotasP = parseInt(dataresponse);
						
						$('#tablefeescreditclienttv').bootstrapTable('load',JSON.parse(datosTablaCuotas));
						$('#estadocreditvi').val(estadoCredAc);
						$('#tableadmindeudat').bootstrapTable('updateCell', {index: $('#indicetablaacesti').val(), field: 'estado', value: estadoCredAc});
						if(cantidadCuotasP == 1) 
						{
							$('#btnPagoTotalCD').hide();
							document.getElementById("btnPagoTotalCD").disabled = true;
						}
						var infoImprPC = datosCompPC.split("|");
						if(cantidadCuotasP >= 1) document.getElementById("btnPagoSeleccionCD").disabled = true;

						if(cantidadCuotasP == 1) 
						{
							document.getElementById("seleccioncuotanro"+$('#tablefeescreditclienttv').bootstrapTable('getOptions').totalRows).disabled = true;
							$('#btnPagoSeleccionCD').hide();
							$('#tablefeescreditclienttv').bootstrapTable('updateCell', {index: ($('#tablefeescreditclienttv').bootstrapTable('getOptions').totalRows-1), field: 'seleccioncuota', value: '-'});
							//$('#tablefeescreditclienttv').bootstrapTable('getData')[($('#tablefeescreditclienttv').bootstrapTable('getOptions').totalRows-1)].seleccioncuota = '---';
						}
						
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
						
						$('#dialogvalidsuppagocuotacredit').dialog('destroy').remove();
						$('#dialogviewfeecredit').dialog('destroy').remove();
						imprimirPagoCuota(infoImprPC[0],infoImprPC[1],infoImprPC[2],infoImprPC[3],infoImprPC[4],infoImprPC[5],infoImprPC[6],infoImprPC[7],infoImprPC[8],infoImprPC[9],infoImprPC[10],infoImprPC[11],infoImprPC[12]);						
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
		function reImprimirPagoCuotaCreditoCliente(idCuotaCredito)
		{				
			var urlripcc = "./acciones/reimprimirpagocuotacreditocliente.php";
			$('#img_loader_17').show();
			
			$.ajax({
				url: urlripcc,
				method: "POST",
				data: { idCuotaCredito: idCuotaCredito, idCredito: $('#idcreditovi').val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_17').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Reprint_Pay_Fee_Credit_Client_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						dataresponse = dataresponse.replace(menR+"=:=:=:","");
						var datosImpresion = dataresponse.substring(0);

						var infoImprPC = datosImpresion.split('|');
						confirmar_accion_reimprimir_credito_cliente("<?php echo translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']);?>", "<?php echo translate('Msg_Be_Sure_To_Reprint_The_Pay_Fee_Credit',$GLOBALS['lang']);?>", infoImprPC[0],infoImprPC[1],infoImprPC[2],infoImprPC[3],infoImprPC[4],infoImprPC[5],infoImprPC[6],infoImprPC[7],infoImprPC[8],infoImprPC[9],infoImprPC[10],infoImprPC[11],infoImprPC[12]);
					}
					else 
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
					}
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_17').hide();
				}
			});
		}
    </script>

	<script type="text/javascript">
		function confirmar_accion_reimprimir_credito_cliente(titulo, mensaje, fechaCreditoImp, nroCreditoP, nroCuotaP, tipoClienteCreditoImp, datosCliCreditoImp, sucursalCreditoImp, usuarioCreditoImp, montoPagado, proximoPagoCreditoImp, tipoDocumentoCreditoImp, documentoCreditoImp, montoCuota, montoInteres)
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
										
										reImprimirPagoCuotaCredito(fechaCreditoImp, nroCreditoP, nroCuotaP, tipoClienteCreditoImp, datosCliCreditoImp, sucursalCreditoImp, usuarioCreditoImp, montoPagado, proximoPagoCreditoImp, tipoDocumentoCreditoImp, documentoCreditoImp, montoCuota, montoInteres);                                                      
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
		function reImprimirPagoCuotaCredito(fechaCreditoImp, nroCreditoP, nroCuotaP, tipoClienteCreditoImp, datosCliCreditoImp, sucursalCreditoImp, usuarioCreditoImp, montoPagado, proximoPagoCreditoImp, tipoDocumentoCreditoImp, documentoCreditoImp, montoCuota, montoInteres)
		{
			var urlinc2 = "<?php echo $GLOBALS['imprimir_pago_cuota_credito']; ?>";

			$.ajax({
				url: urlinc2,
				method: "POST",
				data: { numeroCredito: nroCreditoP, fecha: fechaCreditoImp, nroCuota: nroCuotaP, cliente: datosCliCreditoImp, sucursal: sucursalCreditoImp, tipoCliente: tipoClienteCreditoImp, usuario: usuarioCreditoImp, montoPagado: montoPagado, proximoPago: proximoPagoCreditoImp, tipoDocumento: tipoDocumentoCreditoImp, documento: documentoCreditoImp, montoCuota: montoCuota, montoInteres: montoInteres, esCopia: 1 },
				success: function(dataresponse, statustext, response){
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_The_New_Pay_Fee_Credit_Was_Printed_Correctly',$GLOBALS['lang']);?>') != -1)
					{
						console.log('<?php echo translate('Msg_The_New_Pay_Fee_Credit_Was_Printed_Correctly',$GLOBALS['lang']);?>');
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",'<?php echo translate('Msg_Reprint_Pay_Fee_Credit_Client_OK2',$GLOBALS['lang']);?>');
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
		function confirmar_accion_pago_seleccion_cuotas(titulo, mensaje)
		{
			var cuotas = '';
			var montoTotalAPagar = 0.00;
			for(var i=0; i < $('#tablefeescreditclienttv').bootstrapTable('getOptions').totalRows; i++)
			{
				if($('#tablefeescreditclienttv').bootstrapTable('getData')[i].seleccioncuota.indexOf('seleccioncuotanro') != -1)
				{
					if($('#seleccioncuotanro'+(i+1)).is(":checked"))
					{
						if(cuotas == '') cuotas = ''+(i+1);
						else cuotas = cuotas + '|' + (i+1);
						montoTotalAPagar = montoTotalAPagar + parseFloat($('#tablefeescreditclienttv').bootstrapTable('getData')[i].montototalcuotav.replace("$","")) + parseFloat($('#tablefeescreditclienttv').bootstrapTable('getData')[i].interesescuotav.replace("$",""));
					}
				}
			}
			
			if(cuotas == '') return;
			var cuotasVis = '';
			var cuotasRecV = cuotas.split('|');
			if(cuotasRecV.length == 1)
			{				
				$('#pagoCuotaNro'+cuotasRecV[0]).click();
				return;
			}
			
			for(var j=0; j < cuotasRecV.length; j++)
			{
				if(j==0) cuotasVis = cuotasRecV[j];
				else if((j+1) == cuotasRecV.length) cuotasVis = cuotasVis + ' <?php echo translate('Lbl_AND_letter',$GLOBALS['lang']);?> ' + cuotasRecV[j];
				else cuotasVis = cuotasVis + ', ' + cuotasRecV[j];
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
																				
										pagoSeleccionCuotasCredito(cuotas);                                                      
								},
								"<?php echo translate('Lbl_Button_NO',$GLOBALS['lang']);?>": function () {
										$("#confirmDialog").dialog('close');
										return;
								}
						}
				}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
				$( "#confirmDialog" ).html("<div id='confirmacionAccion'>"+mensaje.replace(":cuotasM",cuotasVis)+": $"+montoTotalAPagar+"?</div>");
				$('#img_loader').hide();
		}
	</script>

	<script type="text/javascript">
		function pagoSeleccionCuotasCredito(cuotas)
		{
			var urlrpcc = "./acciones/registrarpagocuotascredito.php";
			$('#img_loader_17').show();
								
			$.ajax({
				url: urlrpcc,
				method: "POST",
				data: { idCredito: $('#numerocreditvi').val(), nrosCuotasCredito: cuotas },
				success: function(dataresponse, statustext, response){
					$('#img_loader_17').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Pay_Fees_Credit_Selection_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = "<?php echo translate('Msg_Pay_Fees_Credit_Selection_OK',$GLOBALS['lang']);?>";
						dataresponse = dataresponse.replace('<?php echo translate('Msg_Pay_Fees_Credit_Selection_OK',$GLOBALS['lang']);?>=:=:=',"");
						var estadoCredAc = dataresponse.substring(0, dataresponse.indexOf('=::=::='));
						dataresponse = dataresponse.replace(estadoCredAc+'=::=::=',"");
						var datosCompPC = dataresponse.substring(0, dataresponse.indexOf('=:::=:::='));
						dataresponse = dataresponse.replace(datosCompPC+'=:::=:::=',"");
						var datosTablaCuotas = dataresponse.substring(0, dataresponse.indexOf('=::::=::::='));
						dataresponse = dataresponse.replace(datosTablaCuotas+'=::::=::::=',"");
						var cantidadCuotasP = parseInt(dataresponse);
						
						$('#tablefeescreditclienttv').bootstrapTable('load',JSON.parse(datosTablaCuotas));
						$('#estadocreditvi').val(estadoCredAc);
						$('#tableadmindeudat').bootstrapTable('updateCell', {index: $('#indicetablaacesti').val(), field: 'estado', value: estadoCredAc});
						if(cantidadCuotasP == 1) 
						{
							$('#btnPagoTotalCD').hide();
							document.getElementById("btnPagoTotalCD").disabled = true;
						}
						var infoImprPC = datosCompPC.split("|");
						if(cantidadCuotasP >= 1) document.getElementById("btnPagoSeleccionCD").disabled = true;

						if(cantidadCuotasP == 1) 
						{
							document.getElementById("seleccioncuotanro"+$('#tablefeescreditclienttv').bootstrapTable('getOptions').totalRows).disabled = true;
							$('#btnPagoSeleccionCD').hide();
							$('#tablefeescreditclienttv').bootstrapTable('updateCell', {index: ($('#tablefeescreditclienttv').bootstrapTable('getOptions').totalRows-1), field: 'seleccioncuota', value: '-'});
							//$('#tablefeescreditclienttv').bootstrapTable('getData')[($('#tablefeescreditclienttv').bootstrapTable('getOptions').totalRows-1)].seleccioncuota = '---';
						}
						
						if(cantidadCuotasP == 0)
						{
							if(document.getElementById("btnPagoSeleccionCD") != undefined && document.getElementById("btnPagoSeleccionCD") != null)
							{
								$('#btnPagoSeleccionCD').hide();
								document.getElementById("btnPagoSeleccionCD").disabled = true;
							}
							
							if(document.getElementById("btnPagoTotalCD") != undefined && document.getElementById("btnPagoTotalCD") != null)
							{
								$('#btnPagoTotalCD').hide();
								document.getElementById("btnPagoTotalCD").disabled = true;
							}							
						}
						
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
						imprimirPagoSeleccionCuotas(infoImprPC[0],infoImprPC[1],infoImprPC[2],infoImprPC[3],infoImprPC[4],infoImprPC[5],infoImprPC[6],infoImprPC[7],infoImprPC[8],infoImprPC[9],infoImprPC[10],infoImprPC[11]);						
					}
					else
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);				
					}
					
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_17').hide();
				}
			});
		}
    </script>

	<script type="text/javascript">
		function imprimirPagoSeleccionCuotas(fechaCreditoImp, nroCreditoP, cantidadCuotasP, tipoClienteCreditoImp, datosCliCreditoImp, sucursalCreditoImp, usuarioCreditoImp, montoPagado, proximoPagoCreditoImp, tipoDocumentoCreditoImp, documentoCreditoImp, datosCuotasPagadas)
		{
			var urlinc2 = "<?php echo $GLOBALS['imprimir_seleccion_pago_cuotas_credito']; ?>";

			$.ajax({
				url: urlinc2,
				method: "POST",
				data: { numeroCredito: nroCreditoP, fecha: fechaCreditoImp, cantidadCuotasP: cantidadCuotasP, cliente: datosCliCreditoImp, sucursal: sucursalCreditoImp, tipoCliente: tipoClienteCreditoImp, usuario: usuarioCreditoImp, montoPagado: montoPagado, proximoPago: proximoPagoCreditoImp, tipoDocumento: tipoDocumentoCreditoImp, documento: documentoCreditoImp, datosCuotasPagadas: datosCuotasPagadas },
				success: function(dataresponse, statustext, response){
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_The_Selection_Fees_Pay_Was_Printed_Correctly',$GLOBALS['lang']);?>') != -1)
					{
						console.log('<?php echo translate('Msg_The_Selection_Fees_Pay_Was_Printed_Correctly',$GLOBALS['lang']);?>');
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",'<?php echo translate('Msg_The_Selection_Fees_Pay_Was_Printed_Correctly',$GLOBALS['lang']);?>');
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
		function pagoTotalDeuda(idCredito)
		{
			if($('#idcreditovi').val() != idCredito) return;
			var urlpccs = "./acciones/buscarcuotascredito.php";
			var tagpccs = $("<div id='dialogviewfeescredit'></div>");
			$('#img_loader_17').show();
			
			$.ajax({
				url: urlpccs,
				method: "POST",
				data: { idCredito: idCredito },
				success: function(dataresponse, statustext, response){
					$('#img_loader_17').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_View_Fees_Credit_OK',$GLOBALS['lang']);?>') != -1)
					{					
						dataresponse = dataresponse.replace('<?php echo translate('Msg_View_Fees_Credit_OK',$GLOBALS['lang']);?>',"");
						
						tagpccs.html(dataresponse).dialog({
						  show: "blind",
						  hide: "explode",
						  height: "auto",
						  width: "auto",					  
						  modal: true, 
						  title: "<?php echo translate('Msg_Payment_Total_Amount_Debt_Credit',$GLOBALS['lang']);?>",
						  autoResize:true,
								close: function(){
										tagpccs.dialog('destroy').remove()
								}
						}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
						tagpccs.dialog('open');
						$('#montooriginalcuotascreditvi').maskNumber();
						$('#interescuotascreditvi').maskNumber();
						$('#montototalcuotascreditvi').maskNumber();
					}
					else
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
					}					
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_17').hide();
				}
			});	
		}
    </script>

	<script type="text/javascript">
		function guardarPagoCuotasCredito(formulariopccs)
		{
			if($('#idcreditovi').val() != formulariopccs.idcreditosvc3i.value) return;
			if(formulariopccs.montototalcuotascreditvi.value.length == 0)
			{
				$(function() {
					$('#montototalcuotascreditvi').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$('#montototalcuotascreditvi').focus();
				return;
			}
			else 
			{
				$(function() {
					$('#montototalcuotascreditvi').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$('#montototalcuotascreditvi').tooltip('destroy');
			}
			
			var urlpccs = "./acciones/pagarcuotascredito.php";
			$('#img_loader_18').show();
			
			$.ajax({
				url: urlpccs,
				method: "POST",
				data: { idCredito: formulariopccs.idcreditosvc3i.value, cuotasCredito: formulariopccs.idcuotascreditovi.value, montoPago: ((formulariopccs.montototalcuotascreditvi.value.replace(/,/g,""))*100.00), tokenVSS: $('#tokenvalidsuppagototaldeudacrei').val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_18').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Pay_Total_Amount_Debt_Credit_OK',$GLOBALS['lang']);?>') != -1)
					{					
						var menR = dataresponse.substring(0, dataresponse.indexOf('=:=:='));
						dataresponse = dataresponse.replace('<?php echo translate('Msg_Pay_Total_Amount_Debt_Credit_OK',$GLOBALS['lang']);?>=:=:=',"");
						var estadoCredAc = dataresponse.substring(0, dataresponse.indexOf('=::=::='));
						dataresponse = dataresponse.replace(estadoCredAc+'=::=::=',"");
						var datosCompPC = dataresponse.substring(0, dataresponse.indexOf('=:::=:::='));
						dataresponse = dataresponse.replace(datosCompPC+'=:::=:::=',"");
						var datosTablaCuotas = dataresponse;
						
						$('#tablefeescreditclienttv').bootstrapTable('load',JSON.parse(datosTablaCuotas));
						$('#estadocreditvi').val(estadoCredAc);
						$('#tableadmindeudat').bootstrapTable('updateCell', {index: $('#indicetablaacesti').val(), field: 'estado', value: estadoCredAc});

						$('#btnPagoTotalCD').hide();
						document.getElementById("btnPagoTotalCD").disabled = true;
						
						document.getElementById("btnReimpresionPagoTotalCD").disabled = false;						
						$('#btnReimpresionPagoTotalCD').show();
						
						document.getElementById("btnPDFPagoTotalCD").disabled = false;						
						$('#btnPDFPagoTotalCD').show();						

						var infoImprPC = datosCompPC.split("|");
						if(document.getElementById("btnPagoSeleccionCD") != undefined && document.getElementById("btnPagoSeleccionCD") != null)
						{
							$('#btnPagoSeleccionCD').hide();
							document.getElementById("btnPagoSeleccionCD").disabled = true;
						}
						
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
						
						$('#dialogviewfeescredit').dialog('destroy').remove();
						imprimirPagoTotalDeuda(infoImprPC[0],infoImprPC[1],infoImprPC[2],infoImprPC[3],infoImprPC[4],infoImprPC[5],infoImprPC[6],infoImprPC[7],infoImprPC[8],infoImprPC[9],infoImprPC[10]);
					}
					else if(dataresponse.indexOf('<?php echo translate('Msg_Need_Authorize_Pay_Fee_Credit',$GLOBALS['lang']);?>') != -1)
					{
						var tokenR = dataresponse.substring(dataresponse.indexOf('=::=::=::')+9, dataresponse.indexOf('=:::=:::'));
						dataresponse = dataresponse.replace("<?php echo translate('Msg_Need_Authorize_Pay_Fee_Credit',$GLOBALS['lang']); ?>=::=::=::","");
						dataresponse = dataresponse.replace(tokenR+"=:::=:::","");
												
						$('#tokenvalidsuppagototaldeudacrei').val(tokenR);
						var tagvpccs = $("<div id='dialogvalidsuppagototaldeudacredit'></div>");
						
						tagvpccs.html(dataresponse).dialog({
						  show: "blind",
						  hide: "explode",
						  height: "auto",
						  width: "auto",					  
						  modal: true, 
						  title: "<?php echo translate('Lbl_Authorize_Pay_Fee_Credit2',$GLOBALS['lang']);?>",
						  autoResize:true,
								close: function(){
										tagvpccs.dialog('destroy').remove()
								}
						}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
						tagvpccs.dialog('open');						
					}
					else
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
					}					
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_18').hide();
				}
			});	
		}
    </script>

	<script type="text/javascript">
		function imprimirPagoTotalDeuda(fechaCreditoImp, nroCreditoP, cantidadCuotasP, tipoClienteCreditoImp, datosCliCreditoImp, sucursalCreditoImp, usuarioCreditoImp, montoPagado, tipoDocumentoCreditoImp, documentoCreditoImp, datosCuotasPagadas)
		{
			var urlinc20 = "<?php echo $GLOBALS['imprimir_pago_total_deuda_credito']; ?>";

			$.ajax({
				url: urlinc20,
				method: "POST",
				data: { numeroCredito: nroCreditoP, fecha: fechaCreditoImp, cantidadCuotasP: cantidadCuotasP, cliente: datosCliCreditoImp, sucursal: sucursalCreditoImp, tipoCliente: tipoClienteCreditoImp, usuario: usuarioCreditoImp, montoPagado: montoPagado, tipoDocumento: tipoDocumentoCreditoImp, documento: documentoCreditoImp, datosCuotasPagadas: datosCuotasPagadas },
				success: function(dataresponse, statustext, response){
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_The_Pay_Debt_Credit_Was_Printed_Correctly',$GLOBALS['lang']);?>') != -1)
					{
						console.log('<?php echo translate('Msg_The_Pay_Debt_Credit_Was_Printed_Correctly',$GLOBALS['lang']);?>');
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",'<?php echo translate('Msg_The_Pay_Debt_Credit_Was_Printed_Correctly',$GLOBALS['lang']);?>');
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
		function reimprimirPagoTotalDeuda(idCredito)
		{				
			if(idCredito != $('#idcreditovi').val()) return;
			var urlriptd = "./acciones/reimprimirpagototaldeudacreditocliente.php";
			$('#img_loader_17').show();
			
			$.ajax({
				url: urlriptd,
				method: "POST",
				data: { idCredito: $('#idcreditovi').val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_17').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Reprint_Pay_Total_Amount_Debt_Credit_Client_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						dataresponse = dataresponse.replace(menR+"=:=:=:","");
						var datosImpresion = dataresponse.substring(0);

						var infoImprPC = datosImpresion.split('|');
						confirmar_accion_reimprimir_pago_total_deuda_credito_cliente("<?php echo translate('Lbl_Confirmation_Action_Register_Client',$GLOBALS['lang']);?>", "<?php echo translate('Msg_Be_Sure_To_Reprint_The_Pay_Total_Amount_Debt_Credit',$GLOBALS['lang']);?>", infoImprPC[0],infoImprPC[1],infoImprPC[2],infoImprPC[3],infoImprPC[4],infoImprPC[5],infoImprPC[6],infoImprPC[7],infoImprPC[8],infoImprPC[9],infoImprPC[10]);
					}
					else 
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
					}
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_17').hide();
				}
			});
		}
    </script>	
	
	<script type="text/javascript">
		function confirmar_accion_reimprimir_pago_total_deuda_credito_cliente(titulo, mensaje, fechaCreditoImp, nroCreditoP, cantidadCuotasP, tipoClienteCreditoImp, datosCliCreditoImp, sucursalCreditoImp, usuarioCreditoImp, montoPagado, tipoDocumentoCreditoImp, documentoCreditoImp, datosCuotasPagadas)
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
										
										reimpresionPagoTotalDeuda(fechaCreditoImp, nroCreditoP, cantidadCuotasP, tipoClienteCreditoImp, datosCliCreditoImp, sucursalCreditoImp, usuarioCreditoImp, montoPagado, tipoDocumentoCreditoImp, documentoCreditoImp, datosCuotasPagadas);
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
		function reimpresionPagoTotalDeuda(fechaCreditoImp, nroCreditoP, cantidadCuotasP, tipoClienteCreditoImp, datosCliCreditoImp, sucursalCreditoImp, usuarioCreditoImp, montoPagado, tipoDocumentoCreditoImp, documentoCreditoImp, datosCuotasPagadas)
		{
			var urlinc21 = "<?php echo $GLOBALS['imprimir_pago_total_deuda_credito']; ?>";

			$.ajax({
				url: urlinc21,
				method: "POST",
				data: { numeroCredito: nroCreditoP, fecha: fechaCreditoImp, cantidadCuotasP: cantidadCuotasP, cliente: datosCliCreditoImp, sucursal: sucursalCreditoImp, tipoCliente: tipoClienteCreditoImp, usuario: usuarioCreditoImp, montoPagado: montoPagado, tipoDocumento: tipoDocumentoCreditoImp, documento: documentoCreditoImp, datosCuotasPagadas: datosCuotasPagadas, esCopia: 1 },
				success: function(dataresponse, statustext, response){
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_The_Pay_Debt_Credit_Was_Printed_Correctly',$GLOBALS['lang']);?>') != -1)
					{
						console.log('<?php echo translate('Msg_The_Pay_Debt_Credit_Was_Reprinted_Correctly',$GLOBALS['lang']);?>');
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",'<?php echo translate('Msg_The_Pay_Debt_Credit_Was_Reprinted_Correctly',$GLOBALS['lang']);?>');
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
		function guardarAutorizacionSupervisorPagoTotalDeuda(formularionasptd)
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
			
			var urlvcsptdc = "./acciones/verificarcredencialessupervisorpagototaldeudacredito.php";
			$('#img_loader_13').show();
			
			
			var p222 = document.createElement("input");
		 			
			formularionasptd.appendChild(p222);
			p222.name = "p222";
			p222.type = "hidden";
			
			p222.value = hex_sha512(formularionasptd.passwordsupervisorn2i.value);
			
			if(formularionasptd.passwordsupervisorn2i.value == "") p222.value = "";
			formularionasptd.passwordsupervisorn2i.value = "";
					
			$.ajax({
				url: urlvcsptdc,
				method: "POST",
				data: { usuarioSupervisor: formularionasptd.usuariosupervisorn2i.value, claveSupervisor: p222.value, idCredito: $('#idcreditosvc3i').val(), cuotasCredito: $('#idcuotascreditovi').val(), montoPago: (($('#montototalcuotascreditvi').val().replace(/,/g,""))*100.00), tokenVSS: $('#tokenvalidsuppagototaldeudacrei').val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_13').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = "<?php echo translate('Msg_Pay_Total_Amount_Debt_Credit_OK',$GLOBALS['lang']);?>";
						dataresponse = dataresponse.replace('<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']);?>=:=:=',"");
						var estadoCredAc = dataresponse.substring(0, dataresponse.indexOf('=::=::='));
						dataresponse = dataresponse.replace(estadoCredAc+'=::=::=',"");
						var datosCompPC = dataresponse.substring(0, dataresponse.indexOf('=:::=:::='));
						dataresponse = dataresponse.replace(datosCompPC+'=:::=:::=',"");
						var datosTablaCuotas = dataresponse;
						
						$('#tablefeescreditclienttv').bootstrapTable('load',JSON.parse(datosTablaCuotas));
						$('#estadocreditvi').val(estadoCredAc);
						$('#tableadmindeudat').bootstrapTable('updateCell', {index: $('#indicetablaacesti').val(), field: 'estado', value: estadoCredAc});

						$('#btnPagoTotalCD').hide();
						document.getElementById("btnPagoTotalCD").disabled = true;
						
						document.getElementById("btnReimpresionPagoTotalCD").disabled = false;						
						$('#btnReimpresionPagoTotalCD').show();
						
						document.getElementById("btnPDFPagoTotalCD").disabled = false;						
						$('#btnPDFPagoTotalCD').show();	
						
						var infoImprPC = datosCompPC.split("|");
						if(document.getElementById("btnPagoSeleccionCD") != undefined && document.getElementById("btnPagoSeleccionCD") != null)
						{
							$('#btnPagoSeleccionCD').hide();
							document.getElementById("btnPagoSeleccionCD").disabled = true;
						}

						
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
						
						$('#dialogvalidsuppagototaldeudacredit').dialog('destroy').remove();
						$('#dialogviewfeescredit').dialog('destroy').remove();
						imprimirPagoTotalDeuda(infoImprPC[0],infoImprPC[1],infoImprPC[2],infoImprPC[3],infoImprPC[4],infoImprPC[5],infoImprPC[6],infoImprPC[7],infoImprPC[8],infoImprPC[9],infoImprPC[10]);						
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
		function cambiarEstadoCuotaCredito(idCredito, idCuotaCredito)
		{
			if($('#idcreditovi').val() != idCredito) return;
			var urlbecc = "./acciones/buscarestadoscuotacredito.php";
			var tagvfecc = $("<div id='dialogviewfeestatuschangecredit'></div>");
			$('#img_loader_17').show();
			
			$.ajax({
				url: urlbecc,
				method: "POST",
				data: { idCredito: idCredito, idCuotaCredito: idCuotaCredito },
				success: function(dataresponse, statustext, response){
					$('#img_loader_17').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_View_State_Fee_Credit_OK',$GLOBALS['lang']);?>') != -1)
					{					
						dataresponse = dataresponse.replace('<?php echo translate('Msg_View_State_Fee_Credit_OK',$GLOBALS['lang']);?>',"");
						
						tagvfecc.html(dataresponse).dialog({
						  show: "blind",
						  hide: "explode",
						  height: "auto",
						  width: "auto",					  
						  modal: true, 
						  title: "<?php echo translate('Msg_Change_Status_Fee_Credit',$GLOBALS['lang']);?>",
						  autoResize:true,
								close: function(){
										tagvfecc.dialog('destroy').remove()
								}
						}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
						tagvfecc.dialog('open');
					}
					else
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
					}					
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_17').hide();
				}
			});	
		}
    </script>

	<script type="text/javascript">
		function guardarCambioEstadoCuotaCredito(formulariocecc)
		{
			if($('#idcreditovi').val() != formulariocecc.idcreditovcec2i.value) return;
			
			var urlpcc = "./acciones/cambiarestadocuotacredito.php";
			$('#img_loader_19').show();
			
			$.ajax({
				url: urlpcc,
				method: "POST",
				data: { idCredito: formulariocecc.idcreditovcec2i.value, idCuotaCredito: formulariocecc.idcuotacreditocevi.value, estadoN: formulariocecc.nuevoestadocuotacreditvi.value, tokenVSCE: $('#tokenvalidsupcambioestadocuotacrei').val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_19').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Change_State_Fee_Credit_OK',$GLOBALS['lang']);?>') != -1)
					{					
						var menR = dataresponse.substring(0, dataresponse.indexOf('=:=:='));
						dataresponse = dataresponse.replace('<?php echo translate('Msg_Change_State_Fee_Credit_OK',$GLOBALS['lang']);?>=:=:=',"");
						var estadoCredAc = dataresponse.substring(0, dataresponse.indexOf('=::=::='));
						dataresponse = dataresponse.replace(estadoCredAc+'=::=::=',"");
						var datosTablaCuotas = dataresponse.substring(0, dataresponse.indexOf('=:::=:::='));
						dataresponse = dataresponse.replace(datosTablaCuotas+'=:::=:::=',"");
						var cantidadCuotasP = parseInt(dataresponse);
						
						$('#tablefeescreditclienttv').bootstrapTable('load',JSON.parse(datosTablaCuotas));
						$('#estadocreditvi').val(estadoCredAc);
						$('#tableadmindeudat').bootstrapTable('updateCell', {index: $('#indicetablaacesti').val(), field: 'estado', value: estadoCredAc});
						if(cantidadCuotasP <= 1 && cantidadCuotasP != 0) 
						{
							$('#btnPagoTotalCD').hide();
							document.getElementById("btnPagoTotalCD").disabled = true;
						}
						if(cantidadCuotasP >= 1) document.getElementById("btnPagoSeleccionCD").disabled = true;

						if(cantidadCuotasP <= 1 && cantidadCuotasP != 0) 
						{
							document.getElementById("seleccioncuotanro"+$('#tablefeescreditclienttv').bootstrapTable('getOptions').totalRows).disabled = true;
							$('#btnPagoSeleccionCD').hide();
							$('#tablefeescreditclienttv').bootstrapTable('updateCell', {index: ($('#tablefeescreditclienttv').bootstrapTable('getOptions').totalRows-1), field: 'seleccioncuota', value: '-'});							
						}
						
						$('#dialogviewfeestatuschangecredit').dialog('destroy').remove();
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else if(dataresponse.indexOf('<?php echo translate('Msg_Need_Authorize_Change_State_Fee_Credit',$GLOBALS['lang']);?>') != -1)
					{
						var tokenR = dataresponse.substring(dataresponse.indexOf('=::=::=::')+9, dataresponse.indexOf('=:::=:::'));
						dataresponse = dataresponse.replace("<?php echo translate('Msg_Need_Authorize_Change_State_Fee_Credit',$GLOBALS['lang']); ?>=::=::=::","");
						dataresponse = dataresponse.replace(tokenR+"=:::=:::","");
												
						$('#tokenvalidsupcambioestadocuotacrei').val(tokenR);
						var tagvcecc = $("<div id='dialogvalidsupcambioestadocuotacredit'></div>");
						
						tagvcecc.html(dataresponse).dialog({
						  show: "blind",
						  hide: "explode",
						  height: "auto",
						  width: "auto",					  
						  modal: true, 
						  title: "<?php echo translate('Lbl_Authorize_Pay_Fee_Credit2',$GLOBALS['lang']);?>",
						  autoResize:true,
								close: function(){
										tagvcecc.dialog('destroy').remove()
								}
						}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
						tagvcecc.dialog('open');					
					}
					else
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
					}					
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_19').hide();
				}
			});	
		}
    </script>

	<script type="text/javascript">
		function guardarAutorizacionSupervisorCambioEstadoCuota(formularionaspcec)
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
			
			var urlvcspcecc = "./acciones/verificarcredencialessupervisorcambioestadocuotacredito.php";
			$('#img_loader_13').show();
			
			
			var p322 = document.createElement("input");
		 			
			formularionaspcec.appendChild(p322);
			p322.name = "p322";
			p322.type = "hidden";
			
			p322.value = hex_sha512(formularionaspcec.passwordsupervisorn3i.value);
			
			if(formularionaspcec.passwordsupervisorn3i.value == "") p322.value = "";
			formularionaspcec.passwordsupervisorn3i.value = "";
					
			$.ajax({
				url: urlvcspcecc,
				method: "POST",
				data: { usuarioSupervisor: formularionaspcec.usuariosupervisorn3i.value, claveSupervisor: p322.value,  idCredito: $('#idcreditovcec2i').val(), idCuotaCredito: $('#idcuotacreditocevi').val(), estadoN: $('#nuevoestadocuotacreditvi').val(), tokenVSCE: $('#tokenvalidsupcambioestadocuotacrei').val()},
				success: function(dataresponse, statustext, response){
					$('#img_loader_13').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0, dataresponse.indexOf('=:=:='));
						dataresponse = dataresponse.replace('<?php echo translate('Msg_Supervisor_OK',$GLOBALS['lang']);?>=:=:=',"");
						var estadoCredAc = dataresponse.substring(0, dataresponse.indexOf('=::=::='));
						dataresponse = dataresponse.replace(estadoCredAc+'=::=::=',"");
						var datosTablaCuotas = dataresponse.substring(0, dataresponse.indexOf('=:::=:::='));
						dataresponse = dataresponse.replace(datosTablaCuotas+'=:::=:::=',"");
						var cantidadCuotasP = parseInt(dataresponse);
						
						$('#tablefeescreditclienttv').bootstrapTable('load',JSON.parse(datosTablaCuotas));
						$('#estadocreditvi').val(estadoCredAc);
						$('#tableadmindeudat').bootstrapTable('updateCell', {index: $('#indicetablaacesti').val(), field: 'estado', value: estadoCredAc});
						if(cantidadCuotasP <= 1 && cantidadCuotasP != 0) 
						{
							$('#btnPagoTotalCD').hide();
							document.getElementById("btnPagoTotalCD").disabled = true;
						}
						if(cantidadCuotasP >= 1) document.getElementById("btnPagoSeleccionCD").disabled = true;

						if(cantidadCuotasP <= 1 && cantidadCuotasP != 0) 
						{
							document.getElementById("seleccioncuotanro"+$('#tablefeescreditclienttv').bootstrapTable('getOptions').totalRows).disabled = true;
							$('#btnPagoSeleccionCD').hide();
							$('#tablefeescreditclienttv').bootstrapTable('updateCell', {index: ($('#tablefeescreditclienttv').bootstrapTable('getOptions').totalRows-1), field: 'seleccioncuota', value: '-'});							
						}

						$('#dialogviewfeestatuschangecredit').dialog('destroy').remove();
						$('#dialogvalidsupcambioestadocuotacredit').dialog('destroy').remove();						
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>","<?php echo translate('Msg_Change_State_Fee_Credit_OK',$GLOBALS['lang']);?>");					
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
		function verInteresesCuotaCredito(idCuotaCredito)
		{
			var urlvicc = "./acciones/verinteresescuotacredito.php";
			var tagvicc = $("<div id='dialogviewinterestfeecredit'></div>");
			$('#img_loader_17').show();
			
			$.ajax({
				url: urlvicc,
				method: "POST",
				data: { idCuotaCredito: idCuotaCredito },
				success: function(dataresponse, statustext, response){
					$('#img_loader_17').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_View_Interests_Fee_Credit_OK',$GLOBALS['lang']);?>') != -1)
					{					
						dataresponse = dataresponse.replace('<?php echo translate('Msg_View_Interests_Fee_Credit_OK',$GLOBALS['lang']);?>',"");
						
						tagvicc.html(dataresponse).dialog({
						  show: "blind",
						  hide: "explode",
						  height: "auto",
						  width: "auto",					  
						  modal: true, 
						  title: "<?php echo translate('Lbl_View_Interest_Fee_Credit',$GLOBALS['lang']);?>",
						  autoResize:true,
								close: function(){
										tagvicc.dialog('destroy').remove()
								}
						}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
						
						$('#tableinterestfeecreditclienttv').bootstrapTable({locale:'es-AR'});
						
						tagvicc.dialog('open');
						$('#montocuotaorigvi').maskNumber();
						$('#interesescuotacreditvi').maskNumber();
						$('#montototalcuotacreditvi').maskNumber();
					}
					else
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
					}					
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_17').hide();
				}
			});	
		}
    </script>

	<script type="text/javascript">
		function verAvisosDeuda(idCuotaCredito)
		{
			var urlvadcc = "./acciones/veravisosdeudacuotacredito.php";
			var tagvadcc = $("<div id='dialogseedebtnoticesfeecredit'></div>");
			$('#img_loader_17').show();
			
			$.ajax({
				url: urlvadcc,
				method: "POST",
				data: { idCuotaCredito: idCuotaCredito },
				success: function(dataresponse, statustext, response){
					$('#img_loader_17').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_View_See_Debt_Notices_Fee_Credit_OK',$GLOBALS['lang']);?>') != -1)
					{					
						dataresponse = dataresponse.replace('<?php echo translate('Msg_View_See_Debt_Notices_Fee_Credit_OK',$GLOBALS['lang']);?>',"");
						
						tagvadcc.html(dataresponse).dialog({
						  show: "blind",
						  hide: "explode",
						  height: "auto",
						  width: "auto",					  
						  modal: true, 
						  title: "<?php echo translate('Lbl_View_Notices_Debt_Fee_Credit',$GLOBALS['lang']);?>",
						  autoResize:true,
								close: function(){
										tagvadcc.dialog('destroy').remove()
								}
						}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
						
						$('#tablefeesnoticesdebtfeecreditclienttv').bootstrapTable({locale:'es-AR'});
						
						tagvadcc.dialog('open');
						$('#montocuotaorigv2i').maskNumber();
						$('#interesescuotacreditv2i').maskNumber();
						$('#montototalcuotacreditv2i').maskNumber();
					}
					else
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
					}					
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_17').hide();
				}
			});	
		}
    </script>

	<script type="text/javascript">
		function confirmar_accion_cancelar_cuota_credito(titulo, mensaje, formularioccc, idCuotaCredito)
		{
			if($('#motivocancelfeecrediti').val().length == 0)
			{
				$(function() {
					$('#motivocancelfeecrediti').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});
				$('#motivocancelfeecrediti').focus();
				return;
			}
			else 
			{
				$(function() {
					$('#motivocancelfeecrediti').tooltip({
					   position: {
						  my: "center bottom",
						  at: "center top-10",
						  collision: "none"
					   }
					});
				});				
				$('#motivocancelfeecrediti').tooltip('destroy');
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
										
										guardarCancelacionCuotaCredito(formularioccc, idCuotaCredito);                                                      
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
		function guardarCancelacionCuotaCredito(formularioccc, idCuotaCredito)
		{			
			var urlpcc = "./acciones/guardarcancelacioncuotacredito.php";
			$('#img_loader_23').show();
			
			$.ajax({
				url: urlpcc,
				method: "POST",
				data: { idCuotaCredito: idCuotaCredito, motivoCancelacion: $("#motivocancelfeecrediti").val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_23').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Cancel_Credit_Fee_Client_OK',$GLOBALS['lang']);?>') != -1)
					{					
						var menR = dataresponse.substring(0, dataresponse.indexOf('=:=:='));
						dataresponse = dataresponse.replace('<?php echo translate('Msg_Cancel_Credit_Fee_Client_OK',$GLOBALS['lang']);?>=:=:=',"");
						var estadoCredAc = dataresponse.substring(0, dataresponse.indexOf('=::=::='));
						dataresponse = dataresponse.replace(estadoCredAc+'=::=::=',"");
						var datosTablaCuotas = dataresponse.substring(0, dataresponse.indexOf('=::::=::::='));
						dataresponse = dataresponse.replace(datosTablaCuotas+'=::::=::::=',"");
						var cantidadCuotasP = parseInt(dataresponse);
						
						$('#tablefeescreditclienttv').bootstrapTable('load',JSON.parse(datosTablaCuotas));
						$('#estadocreditvi').val(estadoCredAc);
						$('#tableadmindeudat').bootstrapTable('updateCell', {index: $('#indicetablaacesti').val(), field: 'estado', value: estadoCredAc});
						if(cantidadCuotasP == 1) 
						{
							$('#btnPagoTotalCD').hide();
							document.getElementById("btnPagoTotalCD").disabled = true;
						}
						if(cantidadCuotasP >= 1) document.getElementById("btnPagoSeleccionCD").disabled = true;
						if(cantidadCuotasP == 1) 
						{
							document.getElementById("seleccioncuotanro"+$('#tablefeescreditclienttv').bootstrapTable('getOptions').totalRows).disabled = true;
							$('#btnPagoSeleccionCD').hide();
							$('#tablefeescreditclienttv').bootstrapTable('updateCell', {index: ($('#tablefeescreditclienttv').bootstrapTable('getOptions').totalRows-1), field: 'seleccioncuota', value: '-'});							
						}
						
						//VER QUE ESTE TODO LO QUE SE NECESITA AL CANCELAR LA CUOTA -- QUE SE ACTUALICEN TODOS LOS DATOS EN FORMA CORRECTA
						$('#dialogcancelfeecredit').dialog('destroy').remove();
						mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
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
			<h3 class="panel-title" id="titulocreditoscliente"><?php  if(!empty($_GET['doc'])) echo translate('Lbl_Credits_Clients',$GLOBALS['lang']).': '.$_GET['doc']; else echo translate('Lbl_Credits_Clients',$GLOBALS['lang']);?></h3>
		  </div>
		  <div id="apDiv1" class="panel-body">
			<div id="toolbar" style="margin-left:-300px; margin-top:-1px;">
				<button type="button" class="btn" data-toggle="tooltip" data-placement="top" onclick="buscarCreditosCliente();" title="<?php echo translate('Lbl_Search_Credits_Client',$GLOBALS['lang']);?>" ><i class="fas fa-search"></i></button>
			</div>
			<div id="img_loader"></div>
			<div id="tableadmindeuda" class="table-responsive">
				<table id="tableadmindeudat" data-classes="table table-hover table-condensed"
				   data-striped="true" data-pagination="true" data-show-export="true" data-export-options='{"fileName": "<?php echo translate('File_Credits_Clients',$GLOBALS['lang']); ?>"}'
				   data-export-types="['excel','pdf','csv','txt']"
				   data-search="true" data-search-align="right" data-toolbar="#toolbar" data-toolbar-align="right">
					<thead>
						<tr>
							<th class="col-xs-1 text-center" data-field="idcredito" data-sortable="true"><?php echo translate('Lbl_Credit_Number',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="fecha" data-sortable="true"><?php echo translate('Lbl_Date_Credit',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="tipodocumento" data-sortable="true"><?php echo translate('Lbl_Type_Document_Credit',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="documento" data-sortable="true"><?php echo translate('Lbl_Document_Credit',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="tipocuenta" data-sortable="true"><?php echo translate('Lbl_Type_Account_Client',$GLOBALS['lang']);?></th>
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
									
									if(!empty($_GET['doc']))
									{
										$documento = htmlspecialchars($_GET['doc'], ENT_QUOTES, 'UTF-8');
										if($stmt62 = $mysqli->prepare("SELECT c.id_titular FROM finan_cli.cliente c WHERE c.documento = ?"))
										{
											$stmt62->bind_param('s', $documento);
											$stmt62->execute();    
											$stmt62->store_result();
											
											$totR62 = $stmt62->num_rows;

											if($totR62 > 0)
											{
												$stmt62->bind_result($id_titular_cliente_db);
												$stmt62->fetch();
												
												if(!empty($id_titular_cliente_db))
												{
													if($stmt63 = $mysqli->prepare("SELECT c.tipo_documento, c.documento FROM finan_cli.cliente c WHERE c.id = ?"))
													{
														$stmt63->bind_param('i', $id_titular_cliente_db);
														$stmt63->execute();    
														$stmt63->store_result();
														
														$totR63 = $stmt63->num_rows;

														if($totR63 > 0)
														{
															$stmt63->bind_result($tipo_documento_titular, $documento_titular);
															$stmt63->fetch();
															
															$stmt63->free_result();
															$stmt63->close();				
														}
													}					
												}
												
												$stmt62->free_result();
												$stmt62->close();
											}
										}

										if(empty($id_titular_cliente_db)) $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Holder',$GLOBALS['lang']);
										else $tipo_cuenta_texto_cliente = translate('Lbl_Type_Account_Client_Additional',$GLOBALS['lang']);	
										
										if(!empty($tipo_documento_titular) && !empty($documento_titular)) $selecBDC = "SELECT c.id, cc.fecha, td.nombre, cc.documento, c.monto_credito_original, pc.nombre, c.cantidad_cuotas, c.estado, cc.tipo_documento_adicional, cc.documento_adicional FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.cliente cli, finan_cli.plan_credito pc, finan_cli.tipo_documento td, finan_cli.sucursal suc WHERE pc.id = c.id_plan_credito AND c.id = cc.id_credito AND cc.tipo_documento = cli.tipo_documento AND cc.documento = cli.documento AND cc.tipo_documento = td.id AND cc.id_sucursal = suc.id AND suc.id_cadena = ? AND cc.tipo_documento = ? AND cc.documento = ? AND cc.documento_adicional = ? ORDER BY cc.fecha DESC";
										else $selecBDC = "SELECT c.id, cc.fecha, td.nombre, cc.documento, c.monto_credito_original, pc.nombre, c.cantidad_cuotas, c.estado FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.cliente cli, finan_cli.plan_credito pc, finan_cli.tipo_documento td, finan_cli.sucursal suc WHERE pc.id = c.id_plan_credito AND c.id = cc.id_credito AND cc.tipo_documento = cli.tipo_documento AND cc.documento = cli.documento AND cc.tipo_documento = td.id AND cc.id_sucursal = suc.id AND suc.id_cadena = ? AND cli.documento = ? ORDER BY cc.fecha DESC";
										if ($stmt = $mysqli->prepare($selecBDC)) 
										{
											if(!empty($tipo_documento_titular) && !empty($documento_titular)) $stmt->bind_param('iiss', $id_cadena_user, $tipo_documento_titular, $documento_titular, $documento);
											else $stmt->bind_param('is', $id_cadena_user, $documento);
											$stmt->execute();    
											$stmt->store_result();
									 
											if(!empty($tipo_documento_titular) && !empty($documento_titular)) $stmt->bind_result($id_credit_client, $date_credit_client, $type_documento_credit_client, $document_credit_client, $amount_credit_client, $name_credit_plan_client, $fees_credit_client, $state_credit_client, $tipo_documento_adicional_client, $documento_adicional_client);			
											else $stmt->bind_result($id_credit_client, $date_credit_client, $type_documento_credit_client, $document_credit_client, $amount_credit_client, $name_credit_plan_client, $fees_credit_client, $state_credit_client);			
											
											$totR = $stmt->num_rows;

											if($totR == 0)
											{
												echo translate('Msg_Without_Credit_Client',$GLOBALS['lang']).'=::=::=::'.$documento;
												return;	
											}					
											
											$indiceTablaGD = 0;
											while($stmt->fetch())
											{
												echo '<tr>';
												echo '<td>'.$id_credit_client.'</td>';
												echo '<td>'.substr($date_credit_client,6,2).'/'.substr($date_credit_client,4,2).'/'.substr($date_credit_client,0,4).'</td>';
												if(!empty($tipo_documento_titular) && !empty($documento_titular))
												{
													echo '<td>'.$tipo_documento_adicional_client.'</td>';
													echo '<td>'.$documento_adicional_client.'</td>';					
												}
												else
												{
													echo '<td>'.$type_documento_credit_client.'</td>';
													echo '<td>'.$document_credit_client.'</td>';
												}
												echo '<td>'.$tipo_cuenta_texto_cliente.'</td>';
												echo '<td>'.'$'.round(($amount_credit_client/100.00),2).'</td>';
												echo '<td>'.$name_credit_plan_client.'</td>';
												echo '<td>'.$fees_credit_client.'</td>';
												echo '<td>'.$state_credit_client.'</td>';
																
												echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Modify_Debt_Client',$GLOBALS['lang']).'" onclick="verCredito('.$id_credit_client.','.$indiceTablaGD.')"><i class="far fa-edit"></i></button></td>';													
												$indiceTablaGD++;
											}								
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
	<div class="form-group" id="indicetablaacest" style="display:none;">
		<input class="form-control input-sm green-border" id="indicetablaacesti" name="indicetablaacesti" type="text" maxlength="11" disabled />
	</div>
	<script type="text/javascript">
		$(function () 
		{
			$('#tableadmindeudat').bootstrapTable({locale:'es-AR'});
		});
	</script>	
</body>
</html>