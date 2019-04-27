<!doctype html>
<html lang="es-es">
<head>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="./images/iconoFinanCli.png" >
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.op2.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">	

	<script type="text/javascript" src="./js/jquery-1.9.1.min.op2.js"></script>	
	<script type="text/JavaScript" src="./js/bootstrap.min.op2.js" ></script>

	<link rel="stylesheet" href="css/fondo.op2.css">
	<link rel="stylesheet" href="css/estilos.op2.css">
	<title><?php echo translate('Msg_Page_Not_Found',$GLOBALS['lang']);?></title>
</head>

<body>
	</br></br></br></br><img src="images/error404_2.png" class="center-block img-responsive">
	<h1><?php echo strtoupper(translate('Msg_Page_Not_Found',$GLOBALS['lang']));?></h1>
	</br></br></br></br></br></br><button type="button" class="btn btn-primary center-block" onclick="location.href = 'sesionusuario.php';"><?php echo strtoupper(translate('Msg_Back_To_Home',$GLOBALS['lang']));?></button>
	<footer class="footer">
		<div id="fondoPage">
			<img src="images/finanCliFooter.png" style="margin-top:-20px;">
		</div>
	</footer>
</body>
</html>
