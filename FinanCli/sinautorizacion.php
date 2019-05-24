<?php
include ('utiles/funciones.php');
require("../parametrosbasedatosfc.php");
$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
if(!isset($_GET['activauto'])){header('Location:/FinanCli/login.php');return;}
include("menu/menu.php");
?>
<!doctype html>
<html lang="es-es">
<head>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="./images/iconoFinanCli.png" >
	<title><?php echo translate('Msg_Without_Authorization',$GLOBALS['lang']); ?></title>
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.op2.css" >
	<link rel="stylesheet" href="./css/fontawesome.min.css">
	<link rel="stylesheet" href="./css/all.css">	

	<script type="text/javascript" src="./js/jquery-1.9.1.min.op2.js"></script>	
	<script type="text/JavaScript" src="./js/bootstrap.min.op2.js" ></script>

	<link rel="stylesheet" href="css/fondo.op2.css">
	<link rel="stylesheet" href="css/estilos.op2.css">
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
	</nav>	
	</br></br></br></br><img src="images/accesoRestringido.png" class="center-block img-responsive">
	<h1><?php echo strtoupper(translate('Msg_Restricted_Access',$GLOBALS['lang']));?></h1>
	</br></br></br></br></br></br></br></br><button type="button" class="btn btn-primary center-block" onclick="location.href = 'sesionusuario.php';"><?php echo strtoupper(translate('Msg_Back_To_Home',$GLOBALS['lang']));?></button>
	<footer class="footer">
		<div id="fondoPage">
			<img src="images/finanCliFooter.png" style="margin-top:-20px;">
		</div>
	</footer>	
</body>
</html>
