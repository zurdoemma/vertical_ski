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
	<title><?php echo translate('Lbl_Admin_Default_Notices',$GLOBALS['lang']); ?></title>
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
		function verAvisoXMoraCuotaCredito(idAvisoXMora)
		{
			document.getElementById("verAvisoXM"+idAvisoXMora).disabled = true;
			var urlvaxm = "./acciones/veravisoxmora.php";
			var tagvaxm = $("<div id='dialogviewdefaultnotice'></div>");
			$('#img_loader_5').show();
			
			$.ajax({
				url: urlvaxm,
				method: "POST",
				data: { idAvisoXMora: idAvisoXMora },
				success: function(dataresponse, statustext, response){
					$('#img_loader_5').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Search_Default_Notices_OK',$GLOBALS['lang']);?>') != -1)
					{					
						dataresponse = dataresponse.replace("<?php echo translate('Msg_Search_Default_Notices_OK',$GLOBALS['lang']); ?>","");
						
						tagvaxm.html(dataresponse).dialog({
						  show: "blind",
						  hide: "explode",
						  height: "auto",
						  width: "auto",					  
						  modal: true, 
						  title: "<?php echo translate('Lbl_View_Default_Notice',$GLOBALS['lang']);?>",
						  autoResize:true,
								close: function(){
										tagvaxm.dialog('destroy').remove()
								}
						}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
											
						tagvaxm.dialog('open');
						$('#montodeudacuotacreditoi').maskNumber();
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
			document.getElementById("verAvisoXM"+idAvisoXMora).disabled = false;			
		}
    </script>
	
	<script type="text/javascript">
		function verEnviosSMS(idAvisoXMora)
		{
			document.getElementById("btnVerAvisosXSMS").disabled = true;
			var urlvaxsms = "./acciones/veravisoxsms.php";
			var tagvaxsms = $("<div id='dialogviewdefaultnoticesms'></div>");
			$('#img_loader_5').show();
			
			$.ajax({
				url: urlvaxsms,
				method: "POST",
				data: { idAvisoXMora: idAvisoXMora },
				success: function(dataresponse, statustext, response){
					$('#img_loader_5').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Search_Default_Notices_SMS_OK',$GLOBALS['lang']);?>') != -1)
					{					
						dataresponse = dataresponse.replace("<?php echo translate('Msg_Search_Default_Notices_SMS_OK',$GLOBALS['lang']); ?>","");
						
						tagvaxsms.html(dataresponse).dialog({
						  show: "blind",
						  hide: "explode",
						  height: "auto",
						  width: "auto",					  
						  modal: true, 
						  title: "<?php echo translate('Lbl_Sended_Default_Notice_SMS',$GLOBALS['lang']);?>",
						  autoResize:true,
								close: function(){
										tagvaxsms.dialog('destroy').remove()
								}
						}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
						
						$('#tablesendmsgsmst').bootstrapTable({locale:'es-AR'});						
						
						tagvaxsms.dialog('open');
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
			document.getElementById("btnVerAvisosXSMS").disabled = false;
		}
    </script>	
	
	<script type="text/javascript">
		function buscarAvisosXMoraCliente()
		{
			document.getElementById("btnBuscarAvisosXMC").disabled = true;
			if($('.search').find(':input').val().length == 0)
			{
				$('.search').find(':input').focus();
				mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>","<?php echo translate('Msg_A_Customer_Must_Enter_To_Search_Default_Notices',$GLOBALS['lang']);?>");
				document.getElementById("btnBuscarAvisosXMC").disabled = false;
				return;
			}

			var urlbccd = "./acciones/buscaravisosxmoraclientedocumento.php";
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
					
					if(dataresponse.indexOf('<?php echo translate('Msg_Search_Default_Notices_OK',$GLOBALS['lang']);?>') != -1)
					{
						var menR = dataresponse.substring(0,dataresponse.indexOf('=:=:=:'));
						dataresponse = dataresponse.replace(menR+"=:=:=:","");
						var datTable = dataresponse.substring(0,dataresponse.indexOf('=::=::=::'));
						dataresponse = dataresponse.replace(datTable+"=::=::=::","");
						
						$('#tableadminavisosxmorat').bootstrapTable('load',JSON.parse(datTable));
						$('#tituloavisosxmoracliente').html('<?php echo translate('Lbl_Default_Notices_Fee_Credits_Client',$GLOBALS['lang']); ?>'+': '+dataresponse);						
						//mensaje_ok("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR);
					}
					else if(dataresponse.indexOf('<?php echo translate('Msg_Without_Default_Notices',$GLOBALS['lang']);?>') != -1)
					{
						$('#tableadminavisosxmorat').bootstrapTable('removeAll');
						var menR = dataresponse.substring(0,dataresponse.indexOf('=::=::=::'));
						dataresponse = dataresponse.replace(menR+"=::=::=::","");
						
						$('#tituloavisosxmoracliente').html('<?php echo translate('Lbl_Default_Notices_Fee_Credits_Client',$GLOBALS['lang']); ?>');
						$('.search').find(':input').focus();						
						mensaje_atencion("<?php echo translate('Lbl_Result',$GLOBALS['lang']);?>",menR.replace("%1",dataresponse));
					}
					else
					{
						$('#tableadminavisosxmorat').bootstrapTable('removeAll');
						$('#tituloavisosxmoracliente').html('<?php echo translate('Lbl_Default_Notices_Fee_Credits_Client',$GLOBALS['lang']); ?>');
						$('.search').find(':input').focus();
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);							
					}										
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_5').hide();
				}
			});
			document.getElementById("btnBuscarAvisosXMC").disabled = false;
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
			<h3 class="panel-title" id="tituloavisosxmoracliente"><?php echo translate('Lbl_Default_Notices_Fee_Credits_Clients',$GLOBALS['lang']); ?></h3>
		  </div>
		  <div id="apDiv1" class="panel-body">
			<div id="toolbar" style="margin-left:-295px; margin-top:-1px;">
				<button type="button" id="btnBuscarAvisosXMC" class="btn" data-toggle="tooltip" data-placement="top" onclick="buscarAvisosXMoraCliente();" title="<?php echo translate('Lbl_Search_Default_Notices_Client',$GLOBALS['lang']);?>" ><i class="fas fa-search"></i></button>
			</div>		  
			<div id="img_loader"></div>
			<div id="tablaadminavisosxmora" class="table-responsive">
				<table id="tableadminavisosxmorat" data-classes="table table-hover table-condensed"
				   data-striped="true" data-pagination="true" data-show-export="true" data-export-options='{"fileName": "<?php echo translate('File_Default_Notices_Clients',$GLOBALS['lang']); ?>"}'
				   data-export-types="['excel','pdf','csv','txt']"
				   data-search="true" data-search-align="right" data-toolbar="#toolbar" data-toolbar-align="right">
					<thead>
						<tr>
							<th class="col-xs-2 text-center" data-field="fecha" data-sortable="true"><?php echo translate('Lbl_Date_Credit',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="tipodocumento" data-sortable="true"><?php echo translate('Lbl_Type_Document_Credit',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="documento" data-sortable="true"><?php echo translate('Lbl_Document_Credit',$GLOBALS['lang']);?></th>
							<th class="col-xs-1 text-center" data-field="nrocredito" data-sortable="true"><?php echo translate('Lbl_Number_Credit',$GLOBALS['lang']);?></th>							
							<th class="col-xs-1 text-center" data-field="nrocuota" data-sortable="true"><?php echo translate('Lbl_Number_Fee_Credit',$GLOBALS['lang']);?></th>							
							<th class="col-xs-2 text-center" data-field="estado" data-sortable="true"><?php echo translate('Lbl_State_Credit',$GLOBALS['lang']);?></th>
							<th class="col-xs-2 text-center" data-field="acciones"><?php echo translate('Lbl_Actions_Default_Notice',$GLOBALS['lang']);?></th>
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
									if ($stmt = $mysqli->prepare("SELECT axm.id, axm.fecha, td.nombre, cc.documento, axm.estado, axm.id_credito, ccre.numero_cuota FROM finan_cli.credito c, finan_cli.credito_cliente cc, finan_cli.aviso_x_mora axm, finan_cli.tipo_documento td, finan_cli.cuota_credito ccre, finan_cli.sucursal suc WHERE c.id = cc.id_credito AND axm.id_credito = c.id AND cc.tipo_documento = td.id AND ccre.id_credito = c.id AND ccre.id = axm.id_cuota_credito AND cc.id_sucursal = suc.id AND suc.id_cadena = ? ORDER BY axm.fecha DESC LIMIT 50")) 
									{
										$stmt->bind_param('i', $id_cadena_user);
										$stmt->execute();    // Ejecuta la consulta preparada.
										$stmt->store_result();
								 
										// Obtiene las variables del resultado.
										$stmt->bind_result($id_default_notice_client, $date_default_notice_client, $type_documento_default_notice_client, $document_default_notice_client, $state_default_notice_client, $id_credito_default_notice_client, $numero_cuota_default_notice_client);
										
										while($stmt->fetch())
										{		
											echo '<tr>';
											echo '<td>'.substr($date_default_notice_client,6,2).'/'.substr($date_default_notice_client,4,2).'/'.substr($date_default_notice_client,0,4).' '.substr($date_default_notice_client,8,2).':'.substr($date_default_notice_client,10,2).':'.substr($date_default_notice_client,12,2).'</td>';
											echo '<td>'.$type_documento_default_notice_client.'</td>';
											echo '<td>'.$document_default_notice_client.'</td>';
											echo '<td>'.$id_credito_default_notice_client.'</td>';
											echo '<td>'.$numero_cuota_default_notice_client.'</td>';									
											echo '<td>'.$state_default_notice_client.'</td>';
											
											echo '<td><button type="button" id="verAvisoXM'.$id_default_notice_client.'" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_View_Detail_Default_Notice',$GLOBALS['lang']).'" onclick="verAvisoXMoraCuotaCredito('.$id_default_notice_client.')"><i class="fas fa-eye"></i></button></td>';
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
			$('#tableadminavisosxmorat').bootstrapTable({locale:'es-AR'});
		});
	</script>	
</body>
</html>