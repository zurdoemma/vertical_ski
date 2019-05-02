<?php
include ('./utiles/funciones.php');
require("../parametrosbasedatosfc.php");
$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
if(empty($_GET['result_ok']) || empty($_SESSION['username'])) 
{
	if(empty($_GET['result_ok'])) 
	{
		if (verificar_usuario($mysqli)){header('Location:sesionusuario.php');return;}
	}
}
?>
<!doctype html>
<html lang="es-es">
<head>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="./images/iconoFinanCli.png" >
	<title><?php echo translate('Log In',$GLOBALS['lang']); ?></title>

	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.op2.css" >
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">	
	<link rel="stylesheet" type="text/css" href="./css/login.op2.css" >
	
	<script type="text/javascript" src="./js/jquery-1.9.1.min.op2.js"></script>
	<script type="text/JavaScript" src="./js/sha512.op2.js"></script>	
	<script type="text/JavaScript" src="./js/forms.op2.js"></script>
	<script type="text/JavaScript" src="./js/bootstrap.min.op2.js" ></script>	
	
		
	<script type="text/javascript">
		function foco(idElemento){
			document.getElementById(idElemento).focus();
		}
	</script>
	<script type="text/javascript">
	function checkKey(key)
	{
		var unicode
		if (key.charCode)
		{unicode=key.charCode;}
		else
		{unicode=key.keyCode;}
		//alert(unicode); // Para saber que codigo de tecla presiono , descomentar
	 
		if (unicode == 13){
			document.getElementById('login_form').submit();
		}
	}
	</script>	
</head>
<body>
	<div class="login">
	  <div class="login-triangle"></div>
	  
	  <h2 class="login-header"><?php echo translate('Hello',$GLOBALS['lang']);?></h2>

	  <form class="login-container" action="acciones/iniciarsesionu.php" method="post" name="login_form" id="login_form">
		<div class="input-container">
			<i class="fa fa-user icon fa-2x"></i>
			<input type="text" placeholder="<?php echo translate('User',$GLOBALS['lang']); ?>" maxlength="50" id="usuario" name="usuario" value="<?php if(!empty($_GET['usuario_el'])) echo $_GET['usuario_el'];else echo '';?>" style="font-size:16px; border-radius: 0px 10px 10px 0px;">
		</div>
		<div class="input-container">
			<i class="fa fa-key icon fa-2x"></i>
			<input type="password" placeholder="<?php echo translate('Password',$GLOBALS['lang']);?>" onkeydown="checkKey(event);" maxlength="50" id="password" name="password" style="font-size:16px; border-radius: 0px 10px 10px 0px;">
		</div>
		<p><input type="submit" value="<?php echo translate('Log In',$GLOBALS['lang']); ?>" onclick="formhash2(this.form, this.form.password);" style="background: #0259A1; font-size:16px; border-radius: 10px;"></p>
		<?php if($_GET['result_ok'] == 1) echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>'.translate('Msg_Password_Update',$GLOBALS['lang']).' </div>' ?>
		<?php if($_GET['result_ok'] == 2) echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>'.translate('Msg_Close_Sesion',$GLOBALS['lang']).'</div>' ?>		
		<?php if($_GET['result_ok'] == 3) echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>'.translate('Msg_Close_Sesion_Time_Expired',$GLOBALS['lang']).'</div>' ?>
		<?php if($_GET['error_l'] == 1) echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>'.translate('Msg_Close_Sesion_Error',$GLOBALS['lang']).' '.translate('Msg_Void_User',$GLOBALS['lang']).'</div>' ?>
		<?php if($_GET['error_l'] == 2) echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>'.translate('Msg_Close_Sesion_Error',$GLOBALS['lang']).' '.translate('Msg_Incorrect_User',$GLOBALS['lang']).'</div>' ?>
		<?php if($_GET['error_l'] == 3) echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>'.translate('Msg_Close_Sesion_Error',$GLOBALS['lang']).' '.translate('Msg_Void_Password',$GLOBALS['lang']).'</div>' ?>          
		<?php if($_GET['error_l'] == 4) echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>'.translate('Msg_Close_Sesion_Error',$GLOBALS['lang']).' '.translate('Msg_Incorrect_Password',$GLOBALS['lang']).'</div>' ?>
		<?php if($_GET['error_l'] == 5) echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>'.translate('Msg_Close_Sesion_Error',$GLOBALS['lang']).' '.translate('Msg_Block_User',$GLOBALS['lang']).'</div>' ?>
		<?php if($_GET['error_l'] == 6) echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>'.translate('Msg_Close_Sesion_Error',$GLOBALS['lang']).' '.translate('Msg_Unknown_Error',$GLOBALS['lang']).'</div>' ?>
		<?php if($_GET['error_l'] == 7) echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>'.translate('Msg_Close_Sesion_Error',$GLOBALS['lang']).' '.translate('Msg_Contact_Administrator',$GLOBALS['lang']).'</div>' ?>
		<?php if($_GET['error_l'] == 9) echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>'.translate('Msg_Close_Sesion_Error',$GLOBALS['lang']).' '.translate('Msg_Connect_DB_Error',$GLOBALS['lang']).'</div>' ?>			
		<?php if($_GET['error_l'] == 10) echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>'.translate('Msg_Close_Sesion_Error',$GLOBALS['lang']).' '.translate('Msg_Query_DB_Error',$GLOBALS['lang']).'</div>' ?>	
		<?php if($_GET['error_l'] == 11) echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>'.translate('Msg_Close_Sesion_Error',$GLOBALS['lang']).' '.translate('Msg_Query2_DB_Error',$GLOBALS['lang']).'</div>' ?>	
		<?php if($_GET['result_ok_register'] == 1) echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>'.translate('Msg_Close_Sesion_Error',$GLOBALS['lang']).' '.translate('Msg_Register_User_Success',$GLOBALS['lang']).'</div>' ?>		
		<?php if($_GET['error_l'] == 12) echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>'.translate('Msg_Close_Sesion_Error',$GLOBALS['lang']).' '.translate('Msg_User_Or_Password_Incorrect',$GLOBALS['lang']).'</div>' ?>
		<?php if($_GET['error_l'] == 13) echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>'.translate('Msg_Close_Sesion_Error',$GLOBALS['lang']).' '.translate('Msg_Disable_User',$GLOBALS['lang']).'</div>' ?>
	  </form>
	</div>
	<script type="text/javascript">
		if(document.getElementById('usuario').value.length == 0) foco('usuario');
		else foco('password');
	</script>	
</body>
</html>