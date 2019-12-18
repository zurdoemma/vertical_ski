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
	<title><?php echo translate('Lbl_Generate_Token',$GLOBALS['lang']); ?></title>
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
		function generarToken(formulariogt)
		{
			document.getElementById("btnGenerarNT").disabled = true;
			var urlnt = "./acciones/generartokensup.php";
			var tagnt = $("<div id='dialognewtoken'></div>");
			$('#img_loader_25').show();
			
			$.ajax({
				url: urlnt,
				method: "POST",
				data: { usuario: $('#usuarionti').val(), duracion: $('#duraciontokenni').val() },
				success: function(dataresponse, statustext, response){
					$('#img_loader_25').hide();
					
					if(dataresponse.indexOf('<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>') != -1)
					{
						window.location.replace("./login.php?result_ok=3");
					}
					
					if(dataresponse.indexOf('<input id=') != -1)
					{
						tagnt.html(dataresponse).dialog({
						  show: "blind",
						  hide: "explode",
						  height: "auto",
						  width: "auto",					  
						  modal: true, 
						  title: "<?php echo translate('Lbl_Token_Generated',$GLOBALS['lang']);?>",
						  autoResize:true,
								close: function(){
										tagnt.dialog('destroy').remove()
								}
						}).prev(".ui-dialog-titlebar").css("background","#D6D4D3");
						tagnt.dialog('open');
					}
					else
					{
						mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",dataresponse);
					}
				},
				error: function(request, errorcode, errortext){
					mensaje_error("<?php echo translate('Lbl_Error',$GLOBALS['lang']);?>",errorcode + ' - '+errortext);
					$('#img_loader_25').hide();
				}
			});
			document.getElementById("btnGenerarNT").disabled = false;			
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
			<h3 class="panel-title"><?php echo translate('Lbl_Data_Generate_Token',$GLOBALS['lang']); ?></h3>
		  </div>
		  <div id="apDiv1" class="panel-body">
			<div id="img_loader_25"></div>
			<form id="formulariogt" role="form">
				<div class="form-group form-inline">						
					&nbsp;<label class="control-label" for="usuariont"><?php echo translate('Lbl_Authorize_User',$GLOBALS['lang']).': '; ?></label>
						  <div class="form-group" id="usuariont">
								<select class="form-control input-sm" name="usuarionti" id="usuarionti" style="width:130px;">		 
								<?php	
										if ($stmt500 = $mysqli->prepare("SELECT c.id FROM ".$db_name.".cadena c, ".$db_name.".usuario u, ".$db_name.".sucursal s WHERE u.id_sucursal = s.id AND s.id_cadena = c.id AND u.id = ?")) 
										{
											$stmt500->bind_param('s', $_SESSION['username']);
											$stmt500->execute();    
											$stmt500->store_result();
									 
											$totR500 = $stmt500->num_rows;
											if($totR500 > 0)
											{
												$stmt500->bind_result($id_cadena_user);
												$stmt500->fetch();

												$stmt500->free_result();
												$stmt500->close();				
											}
											else 
											{
												echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
												return;				
											}	
										}
										else 
										{
											echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
											return;				
										}
										
										if ($stmt = $mysqli->prepare("SELECT u.id FROM ".$db_name.".usuario u, ".$db_name.".sucursal s WHERE s.id = u.id_sucursal AND s.id_cadena = ? AND u.id_perfil = 2 ORDER BY u.id")) 
										{ 
											$stmt->bind_param('i', $id_cadena_user);
											$stmt->execute();    
											$stmt->store_result();
									 
											$stmt->bind_result($id_usuario);
											while($stmt->fetch())
											{
												echo '<option value="'.$id_usuario.'">'.$id_usuario.'</option>';
											}
											
											$stmt->free_result();
											$stmt->close();
										}
										else  
										{
											echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
											return;			
										}
								?>
							</select>
						</div>
					&nbsp;&nbsp;&nbsp;<label class="control-label" for="duraciontokenn"><?php echo translate('Lbl_Expire_Token',$GLOBALS['lang']).': '; ?></label>
						<div class="form-group" id="duraciontokenn">
							<select class="form-control input-sm" name="duraciontokenni" id="duraciontokenni" style="width:110px;">		 
								<?php											
										if ($stmt = $mysqli->prepare("SELECT valor FROM ".$db_name.".parametros WHERE nombre like 'duracion_token_%' ORDER BY valor")) 
										{ 
											$stmt->execute();    
											$stmt->store_result();
									 
											$stmt->bind_result($valor_duracion_token);
											while($stmt->fetch())
											{
												echo '<option value="'.$valor_duracion_token.'">'.$valor_duracion_token.' '.translate('Lbl_Time_Minute',$GLOBALS['lang']).'</option>';
											}
											
											$stmt->free_result();
											$stmt->close();											
										}
										else  
										{
											echo '<option value="99999">'.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</option>';
											return;			
										}
								?>
						</select>
					</div>
					&nbsp;&nbsp;<input type="button" class="btn btn-primary" name="btnGenerarNT" id="btnGenerarNT" value="<?php echo translate('Msg_Generate_Report_Credits',$GLOBALS['lang']); ?>" onClick="generarToken(document.getElementById('formulariogt'));"/>														
				</div>			
			</form>	
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
</body>
</html>