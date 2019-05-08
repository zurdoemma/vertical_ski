<?php
include ('./utiles/funciones.php');
require("../parametrosbasedatosfc.php");
$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
mysqli_set_charset($mysqli,"utf8");
if (!verificar_usuario($mysqli)){header('Location:./login.php');return;}
if (!verificar_permisos_admin()){header('Location:./sinautorizacion.php?activauto=1');return;}
if(empty(htmlspecialchars($_GET['usuario'], ENT_QUOTES, 'UTF-8'))){header('Location:./sinautorizacion.php?activauto=1');return;}
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
?>
<!doctype html>
<html lang="es-es">
<head>
	<meta charset="UTF-8">
	
	<link rel="shortcut icon" href="./images/iconoFinanCli.png" >
	<title><?php echo translate('Lbl_Home_Address_User',$GLOBALS['lang']); ?></title>
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
	
	<link rel="stylesheet" href="./css/fondo.op2.css">
	<link rel="stylesheet" href="./css/estilos.op2.css">
	
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
						
			var urlgnd = "./acciones/guardarnuevodomicilio.php";
			$('#img_loader_3').show();
			
			$.ajax({
				url: urlgnd,
				method: "POST",
				data: { usuario: "<?php echo $_GET['usuario']; ?>", calle: $( "#callei" ).val(), nroCalle: $( "#nrocallei" ).val(), provincia: $( "#domprovinciai" ).val(), localidad: $( "#domlocalidadi" ).val(), departamento: $( "#domdepartamentoi" ).val(), piso: $( "#domfloori" ).val(), codigoPostal: $( "#zipcodei" ).val(), entreCalle1: $( "#entrecalle1i" ).val(), entreCalle2: $( "#entrecalle2i" ).val() },
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
				if (isNaN($( "#nrocallemi" ).val()) || $( "#nrocallemi" ).val() % 1 != 0)
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
				if (isNaN($( "#domfloormi" ).val()) || $( "#domfloormi" ).val() % 1 != 0)
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
						
			var urlgnd = "./acciones/guardarmodificaciondomicilio.php";
			$('#img_loader_4').show();
			
			$.ajax({
				url: urlgnd,
				method: "POST",
				data: { usuario: "<?php echo $_GET['usuario']; ?>", idDomicilio: idDomicilio, calle: $( "#callemi" ).val(), nroCalle: $( "#nrocallemi" ).val(), provincia: $( "#domprovinciami" ).val(), localidad: $( "#domlocalidadmi" ).val(), departamento: $( "#domdepartamentomi" ).val(), piso: $( "#domfloormi" ).val(), codigoPostal: $( "#zipcodemi" ).val(), entreCalle1: $( "#entrecalle1mi" ).val(), entreCalle2: $( "#entrecalle2mi" ).val() },
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
			var urlnd = "./acciones/nuevodomicilio.php";
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
			var urlnd = "./acciones/modificardomicilio.php";
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
			var urlrdu = "./acciones/borrardomiciliouser.php";
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
			<h3 class="panel-title"><?php echo translate('Lbl_Home_Addresses',$GLOBALS['lang']).': '.$_GET['usuario']; ?></h3>
		  </div>
		  <div id="apDiv1" class="panel-body">
			<div id="toolbar" style="margin-left:-95px; margin-top:-1px;">
				<button type="button" class="btn" data-toggle="tooltip" data-placement="top" onclick="nuevoDomicilio('<?php echo $_GET['usuario']; ?>');" title="<?php echo translate('Lbl_New_Home_Address',$GLOBALS['lang']);?>" ><i class="fas fa-map-marker-alt"></i></button>
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
								$usuarioP = htmlspecialchars($_GET['usuario'], ENT_QUOTES, 'UTF-8');
								$stmt->bind_param('s', $usuarioP);
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
									echo '<td><button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Remove_Address',$GLOBALS['lang']).'" onclick="confirmar_accion(\''.translate('Msg_Confirm_Action',$GLOBALS['lang']).'\', \''.translate('Msg_Confirm_Action_Remove_Domicilio',$GLOBALS['lang']).'\',\''.$_GET['usuario'].'\',\''.$id_domicilio.'\')"><i class="fas fa-trash-alt"></i></button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="'.translate('Msg_Edit_Address',$GLOBALS['lang']).'" onclick="modificarDomicilio(\''.$_GET['usuario'].'\',\''.$id_domicilio.'\')"><i class="fas fa-edit"></i></button></td>';
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
		});
	</script>	
</body>
</html>