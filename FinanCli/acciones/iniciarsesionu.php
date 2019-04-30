<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");		
		if (verificar_usuario($mysqli)){header('Location:../sesionusuario.php');}

		// ¡Oh, no! Existe un error 'connect_errno', fallando así el intento de conexión
		if ($mysqli->connect_errno) 
		{

    			//echo "Lo sentimos, este sitio web está experimentando problemas.";

    			// Algo que no se debería de hacer en un sitio público, aunque este ejemplo lo mostrará
    			// de todas formas, es imprimir información relacionada con errores de MySQL -- se podría registrar
    			//echo "Error: Fallo al conectarse a MySQL debido a: \n";
    			//echo "Errno: " . $mysqli->connect_errno . "\n";
    			//echo "Error: " . $mysqli->connect_error . "\n";
				header('Location:../login.php?error_l=9');
				return;
		}
		
		$usuario=htmlspecialchars($_POST["usuario"], ENT_QUOTES, 'UTF-8');	
		$clave=htmlspecialchars($_POST["p2"], ENT_QUOTES, 'UTF-8');

		$cusuario = 5;
		$cclave = 5;
		
		$lenusu = strlen($usuario);
		if($lenusu == 0) $cusuario = 1;
		else $cusuario = 0;
		
		if ($cusuario == 0)
		{
			if($stmt = $mysqli->prepare("SELECT id, clave FROM finan_cli.usuario WHERE id LIKE(?)"))
		    {
				$stmt->bind_param('s', $usuario);
				$stmt->execute();    
				$stmt->store_result();
			
				$totR = $stmt->num_rows;

				if($totR == 0) $cusuario = 2;
		   
				$stmt->free_result();
				$stmt->close();
			}
			else
			{
				header('Location:../login.php?error_l=10');
				return;				
			}
		}
				
		$lencla = strlen($clave);
		if($lencla == 0) $cclave = 1;
		else $cclave = 0;

		if ($cclave == 0) $cclave = login($usuario,$clave,$mysqli);
		
		
		if($cclave == 1 || $cclave == 2 || $cclave == 11 || $cclave == 12 || $cclave == 13 ||$cusuario == 1 || $cusuario == 2 || $cclave == 3 || $cclave == -1)
		{			
			if($cusuario == 1)
			{ 
				header('Location:../login.php?error_l=1');
				//echo'<font color="#FF0000">&nbsp;*El usuario no puede estar vacio</font>';
			}
			if($cusuario == 2) 
			{
				header('Location:../login.php?error_l=2');
				//echo'<font color="#FF0000">&nbsp;*El usuario es incorrecto</font>';
			}
			if($cclave == 1 && $cusuario == 0) 
			{
				header('Location:../login.php?error_l=3&usuario_el='.$usuario);
				//echo'<font color="#FF0000">&nbsp;*La clave no puede estar vacia&nbsp;&nbsp;</font>';
			}	
			if($cclave == 2 && $cusuario == 0) 
			{
				header('Location:../login.php?error_l=4&usuario_el='.$usuario);
				//echo'<font color="#FF0000">&nbsp;*La clave es incorrecta&nbsp;&nbsp;</font>';
			}
			if($cclave == 3 && $cusuario == 0) 
			{
				header('Location:../login.php?error_l=5&usuario_el='.$usuario);
				//echo'<font color="#FF0000">&nbsp;*El usuario se encuentra bloqueado&nbsp;&nbsp;</font>';
			}
			if($cclave == -1 && $cusuario == 0)
			{ 
				header('Location:../login.php?error_l=6&usuario_el='.$usuario);
				//echo'<font color="#FF0000">&nbsp;*Error desconocido contacte al administrador!!&nbsp;&nbsp;</font>';
			}
			if($cclave == 11 && $cusuario == 0)
			{ 
				header('Location:../login.php?error_l=11&usuario_el='.$usuario);
				//echo'<font color="#FF0000">&nbsp;*Error desconocido contacte al administrador!!&nbsp;&nbsp;</font>';
			}
			if($cclave == 12 && $cusuario == 0)
			{ 
				header('Location:../login.php?error_l=12&usuario_el='.$usuario);
				//echo'<font color="#FF0000">&nbsp;*Error desconocido contacte al administrador!!&nbsp;&nbsp;</font>';
			}
			if($cclave == 13 && $cusuario == 0)
			{ 
				header('Location:../login.php?error_l=13&usuario_el='.$usuario);
				//echo'<font color="#FF0000">&nbsp;*Error desconocido contacte al administrador!!&nbsp;&nbsp;</font>';
			}			
		}
		else
		{		
			header('Location:../sesionusuario.php');
			//echo'<p>Bienvenido usuario:&nbsp;' . $usuario;
			//echo'&nbsp;&nbsp;&nbsp;<a href="sesionusuario.php" title="sesion" target="_self">Ir a pagina principal</a></p>'; 
		}

		function comparar_fechas($fechaacom, $fecha_comparar = null)
		{
			if($fecha_comparar == null)
			{
				$fecha_comparar = date("Ymd");
			}
	 
			$fechaacom = strtotime($fechaacom);
			$fecha_comparar = strtotime($fecha_comparar);
	 
			if($fechaacom == $fecha_comparar)
			{  
				return 0;
			}
			else if($fechaacom < $fecha_comparar)
			{  
				return -1;
			}
			else if($fechaacom > $fecha_comparar)
			{    
				return 1;
			}
	 
			return false;
		}
?>