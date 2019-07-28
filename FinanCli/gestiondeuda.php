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
		function verCredito(idCredito)
		{
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
					
					if(dataresponse.indexOf('<?php echo translate('Msg_View_Fee_Credit_OK',$GLOBALS['lang']);?>') != -1)
					{					
						dataresponse = dataresponse.replace('<?php echo translate('Msg_View_Fee_Credit_OK',$GLOBALS['lang']);?>',"");
						
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
						if(cantidadCuotasP <= 1) 
						{
							$('#btnPagoTotalCD').hide();
							document.getElementById("btnPagoTotalCD").disabled = true;
						}
						var infoImprPC = datosCompPC.split("|");
						document.getElementById("btnPagoSeleccionCD").disabled = true;
						
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
			<div id="toolbar" style="margin-left:-345px; margin-top:-1px;">
				<button type="button" class="btn" data-toggle="tooltip" data-placement="top" onclick="nuevoCredito();" title="<?php echo translate('Lbl_New_Credit',$GLOBALS['lang']);?>" ><i class="far fa-plus-square"></i></button>&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" onclick="buscarCreditosCliente();" title="<?php echo translate('Lbl_Search_Credits_Client',$GLOBALS['lang']);?>" ><i class="fas fa-search"></i></button>
			</div>
			<div id="img_loader"></div>
			<div id="tableadmindeuda" class="table-responsive">
				<table id="tableadmindeudat" data-classes="table table-hover table-condensed"
				   data-striped="true" data-pagination="true" data-show-export="true" data-export-options='{"fileName": "<?php echo translate('File_Credits_Clients',$GLOBALS['lang']); ?>"}'
				   data-export-types="['excel','pdf','csv','txt']"
				   data-search="true" data-search-align="right" data-toolbar="#toolbar" data-toolbar-align="right">
					<thead>
						<tr>
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
								
								if(!empty($tipo_documento_titular) && !empty($documento_titular)) $selecBDC = "SELECT c.id, cc.fecha, td.nombre, cc.documento, c.monto_credito_original, pc.nombre, c.cantidad_cuotas, c.estado, cc.tipo_documento_adicional, cc.documento_adicional FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.cliente cli, finan_cli.plan_credito pc, finan_cli.tipo_documento td WHERE pc.id = c.id_plan_credito AND c.id = cc.id_credito AND cc.tipo_documento = cli.tipo_documento AND cc.documento = cli.documento AND cc.tipo_documento = td.id AND cc.tipo_documento = ? AND cc.documento = ? AND cc.documento_adicional = ? ORDER BY cc.fecha DESC";
								else $selecBDC = "SELECT c.id, cc.fecha, td.nombre, cc.documento, c.monto_credito_original, pc.nombre, c.cantidad_cuotas, c.estado FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.cliente cli, finan_cli.plan_credito pc, finan_cli.tipo_documento td WHERE pc.id = c.id_plan_credito AND c.id = cc.id_credito AND cc.tipo_documento = cli.tipo_documento AND cc.documento = cli.documento AND cc.tipo_documento = td.id AND cli.documento = ? ORDER BY cc.fecha DESC";
								if ($stmt = $mysqli->prepare($selecBDC)) 
								{
									if(!empty($tipo_documento_titular) && !empty($documento_titular)) $stmt->bind_param('iss', $tipo_documento_titular, $documento_titular, $documento);
									else $stmt->bind_param('s', $documento);
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
									
									while($stmt->fetch())
									{
										echo '<tr>';
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
														
										echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Modify_Debt_Client',$GLOBALS['lang']).'" onclick="verCredito('.$id_credit_client.')"><i class="far fa-edit"></i></button></td>';													
									}								
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
			$('#tableadmindeudat').bootstrapTable({locale:'es-AR'});
		});
	</script>	
</body>
</html>