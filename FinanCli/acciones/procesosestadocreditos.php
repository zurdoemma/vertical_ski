<?php 		
		include ('../utiles/funciones.php');
		require("../../parametrosbasedatosfc.php");
		$mysqli = new mysqli($serverName, $db_user, $db_password, $dbname);
		mysqli_set_charset($mysqli,"utf8");
		
		// ¡Oh, no! Existe un error 'connect_errno', fallando así el intento de conexión
		if ($mysqli->connect_errno) 
		{
			echo translate('Msg_Connect_DB_Error',$GLOBALS['lang']);
			return;
		}
		
		$tokenProceso=htmlspecialchars($_GET["tokenProceso"], ENT_QUOTES, 'UTF-8');
			
		if(empty($tokenProceso))
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}		

		if ($stmt72 = $mysqli->prepare("SELECT valor FROM finan_cli.parametros WHERE nombre = ?")) 
		{
			$nombreValPar = 'cantidad_horas_entre_procesos_auto';
			$stmt72->bind_param('s', $nombreValPar);
			$stmt72->execute(); 
			$stmt72->store_result();
			
			$totR72 = $stmt72->num_rows;

			if($totR72 > 0)
			{
				$stmt72->bind_result($cantidad_horas_entre_procesos_db);
				$stmt72->fetch();	
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
		
		if ($stmt73 = $mysqli->prepare("SELECT id FROM finan_cli.parametros WHERE nombre = ? AND valor = ?")) 
		{
			$nombreValPar = 'token_proceso_automatico';
			$stmt73->bind_param('ss', $nombreValPar, $tokenProceso);
			$stmt73->execute();    
			$stmt73->store_result();
	 			
			$totR73 = $stmt73->num_rows;

			if($totR73 > 0)
			{
				$stmt73->bind_result($parameter_id);
				$stmt73->fetch();	
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
		
		if ($stmt74 = $mysqli->prepare("SELECT MAX(fecha) FROM finan_cli.ejecucion_procesos_auto")) 
		{
			$stmt74->execute();
			$stmt74->store_result();
	 			
			$totR74 = $stmt74->num_rows;

			if($totR74 > 0)
			{
				$stmt74->bind_result($fecha_ultimo_proceso);
				$stmt74->fetch();
			
				$fechaObtDB = substr($fecha_ultimo_proceso, 0, 4).'-'.substr($fecha_ultimo_proceso, 4, 2).'-'.substr($fecha_ultimo_proceso, 6, 2).' '.substr($fecha_ultimo_proceso, 8, 2).':'.substr($fecha_ultimo_proceso, 10, 2).':'.substr($fecha_ultimo_proceso, 12, 2);
				$fechaInfDB = new DateTime($fechaObtDB);
				$fechaAct = new DateTime();
				$difHoras = $fechaAct->diff($fechaInfDB);
				
				if($difHoras->h < $cantidad_horas_entre_procesos_db )
				{
					echo str_replace("%1",$cantidad_horas_entre_procesos_db,translate('Msg_The_Automatic_Process_Runs_Every_Hours',$GLOBALS['lang']));
					return;
				}
			}			
		}
		else 
		{
			echo translate('Msg_Unknown_Error',$GLOBALS['lang']);
			return;
		}		
		
		

		return;

?>